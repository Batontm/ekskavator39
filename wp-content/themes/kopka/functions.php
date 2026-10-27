<?php
/**
 * kopka functions and definitions
 *
 * @package kopka
 */

if ( ! defined( 'ABSPATH' ) ) {
exit;
}

/**
 * Theme setup
 */
function kopka_setup() {
// Add default posts and comments RSS feed links
add_theme_support( 'automatic-feed-links' );

// Let WordPress manage the document title
add_theme_support( 'title-tag' );

// Enable support for Post Thumbnails
add_theme_support( 'post-thumbnails' );

// Register navigation menus
register_nav_menus( array(
    'primary' => esc_html__( 'Primary Menu (Header)', 'kopka' ),
    'footer'  => esc_html__( 'Footer Menu', 'kopka' ),
) );

// Switch default core markup to output valid HTML5
add_theme_support( 'html5', array(
    'search-form',
    'comment-form',
    'comment-list',
    'gallery',
    'caption',
    'style',
    'script',
) );

// Add support for Block Styles
add_theme_support( 'wp-block-styles' );

// Add support for full and wide align images
add_theme_support( 'align-wide' );

// Add support for editor styles
add_theme_support( 'editor-styles' );
add_editor_style( 'style.css' );

// Add support for responsive embedded content
add_theme_support( 'responsive-embeds' );

// Add support for custom logo with enhanced options
add_theme_support( 'custom-logo', array(
    'height'               => 150,
    'width'                => 400,
    'flex-height'          => true,
    'flex-width'           => true,
    'header-text'          => array( 'site-title', 'site-description' ),
    'unlink-homepage-logo' => false,
) );

// Add support for custom background
add_theme_support( 'custom-background', array(
    'default-color'          => 'ffffff',
    'default-image'          => '',
    'wp-head-callback'       => '_custom_background_cb',
    'admin-head-callback'    => '',
    'admin-preview-callback' => '',
) );

// Add support for selective refresh for widgets
add_theme_support( 'customize-selective-refresh-widgets' );

// Add support for custom header
add_theme_support( 'custom-header', array(
    'default-image'          => '',
    'width'                  => 1920,
    'height'                 => 200,
    'flex-height'            => true,
    'flex-width'             => true,
    'uploads'                => true,
    'random-default'         => false,
    'header-text'            => true,
    'default-text-color'     => '000000',
    'wp-head-callback'       => '',
    'admin-head-callback'    => '',
    'admin-preview-callback' => '',
) );
}
add_action( 'after_setup_theme', 'kopka_setup' );

/**
 * Enqueue Google Fonts
 */
function kopka_fonts() {
$body_font = 'Inter';
$heading_font = 'Outfit';
$script_font = '';

// Build Google Fonts URL (body + heading + optional script e.g. Pacifico)
$fonts = array();
if ( ! empty( $body_font ) ) {
    $fonts[] = str_replace( ' ', '+', $body_font ) . ':wght@400;500;600;700';
}
if ( ! empty( $heading_font ) && $heading_font !== $body_font ) {
    $fonts[] = str_replace( ' ', '+', $heading_font ) . ':wght@400;500;600;700';
}
if ( ! empty( $script_font ) && $script_font !== $body_font && $script_font !== $heading_font ) {
    $fonts[] = str_replace( ' ', '+', $script_font );
}

if ( ! empty( $fonts ) ) {
    $fonts_url = 'https://fonts.googleapis.com/css2?family=' . implode( '&family=', $fonts ) . '&display=swap';
    wp_enqueue_style( 'kopka-fonts', esc_url( $fonts_url ), array(), null );
}
}
add_action( 'wp_enqueue_scripts', 'kopka_fonts', 5 );

/**
 * Enqueue scripts and styles
 */
function kopka_scripts() {
wp_enqueue_style( 'kopka-style', get_stylesheet_uri(), array( 'kopka-fonts' ), '1.0.0' );


// Navigation script
wp_enqueue_script( 'kopka-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '1.0.0', true );

// Scroll-reveal animations
wp_enqueue_script( 'kopka-animations', get_template_directory_uri() . '/js/animations.js', array(), '1.0.0', true );
wp_enqueue_script( 'kopka-faq', get_template_directory_uri() . '/js/faq.js', array(), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'kopka_scripts' );

/**
 * Enqueue customizer preview script
 */
function kopka_customize_preview_js() {
wp_enqueue_script( 'kopka-customizer-preview', get_template_directory_uri() . '/js/customizer-preview.js', array( 'customize-preview' ), '1.0.0', true );
}
add_action( 'customize_preview_init', 'kopka_customize_preview_js' );

/**
 * Register widget areas
 */
function kopka_widgets_init() {
register_sidebar( array(
    'name'          => esc_html__( 'Sidebar', 'kopka' ),
    'id'            => 'sidebar-1',
    'description'   => esc_html__( 'Add widgets here for the sidebar.', 'kopka' ),
    'before_widget' => '<section id="%1$s" class="widget %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h2 class="widget-title">',
    'after_title'   => '</h2>',
) );

register_sidebar( array(
    'name'          => esc_html__( 'Footer Widget Area 1', 'kopka' ),
    'id'            => 'footer-1',
    'description'   => esc_html__( 'Add footer widgets here.', 'kopka' ),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3 class="widget-title">',
    'after_title'   => '</h3>',
) );

register_sidebar( array(
    'name'          => esc_html__( 'Footer Widget Area 2', 'kopka' ),
    'id'            => 'footer-2',
    'description'   => esc_html__( 'Add footer widgets here.', 'kopka' ),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3 class="widget-title">',
    'after_title'   => '</h3>',
) );

register_sidebar( array(
    'name'          => esc_html__( 'Footer Widget Area 3', 'kopka' ),
    'id'            => 'footer-3',
    'description'   => esc_html__( 'Add footer widgets here.', 'kopka' ),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3 class="widget-title">',
    'after_title'   => '</h3>',
) );
}
add_action( 'widgets_init', 'kopka_widgets_init' );

/**
 * Include Customizer settings
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Register block pattern category
 */
function kopka_register_block_pattern_category() {
register_block_pattern_category(
    'kopka',
    array( 'label' => __( 'kopka', 'kopka' ) )
);
}
add_action( 'init', 'kopka_register_block_pattern_category' );

/**
 * Register block patterns
 */
function kopka_register_block_patterns() {
// Homepage pattern with Gutenberg blocks (editable)
if ( file_exists( get_template_directory() . '/patterns/homepage-blocks.php' ) ) {
    register_block_pattern(
        'kopka/homepage',
        array(
            'title'       => __( 'Homepage Layout', 'kopka' ),
            'description' => __( 'The complete homepage design with editable blocks.', 'kopka' ),
            'categories'  => array( 'kopka' ),
            'content'     => kopka_read_pattern_content( get_template_directory() . '/patterns/homepage-blocks.php' ),
        )
    );
}

// Original HTML pattern (for reference/backup)
if ( file_exists( get_template_directory() . '/patterns/homepage-raw.php' ) ) {
    register_block_pattern(
        'kopka/homepage-raw',
        array(
            'title'       => __( 'Homepage Layout (Original HTML)', 'kopka' ),
            'description' => __( 'Original Theme Designer HTML. Use for pixel-perfect display but limited editing.', 'kopka' ),
            'categories'  => array( 'kopka' ),
            'content'     => kopka_read_pattern_content( get_template_directory() . '/patterns/homepage-raw.php' ),
        )
    );
}
}
add_action( 'init', 'kopka_register_block_patterns' );

/**
 * Read a bundled pattern PHP file and return only the block markup,
 * stripping the leading <?php ... ?> header that WordPress requires
 * at the top of pattern files.
 */
function kopka_read_pattern_content( $path ) {
    if ( ! file_exists( $path ) ) {
        return '';
    }
    $raw = file_get_contents( $path );
    if ( ! is_string( $raw ) || $raw === '' ) {
        return '';
    }
    return trim( preg_replace( '/^<\?php[\s\S]*?\?>\s*/i', '', $raw ) );
}

/**
 * Theme activation: Create or update homepage and additional pages with designed content
 * Uses Gutenberg block patterns for native editing experience
 */
function kopka_activation() {
// Load the Gutenberg-compatible block pattern content
$homepage_content = '';
$pattern_file = get_template_directory() . '/patterns/homepage-blocks.php';

if ( file_exists( $pattern_file ) ) {
    $homepage_content = kopka_read_pattern_content( $pattern_file );
}

// Fallback: If no block pattern, use raw HTML wrapped minimally
if ( empty( $homepage_content ) ) {
    $raw_content_file = get_template_directory() . '/inc/default-content.php';
    if ( file_exists( $raw_content_file ) ) {
        ob_start();
        include $raw_content_file;
        $raw_content = ob_get_clean();
        $homepage_content = '<!-- wp:group {"layout":{"type":"default"},"className":"theme-designer-content"} -->
<div class="wp-block-group theme-designer-content">' . $raw_content . '</div>
<!-- /wp:group -->';
    }
}

// Check for existing homepage by meta key first, then by path
$homepage = null;
$meta_query = new WP_Query( array(
    'post_type'  => 'page',
    'meta_key'   => '_pressmegpt_page_slug',
    'meta_value' => 'home',
    'posts_per_page' => 1,
    'post_status' => 'any',
) );
if ( $meta_query->have_posts() ) {
    $homepage = $meta_query->posts[0];
}
wp_reset_postdata();

if ( ! $homepage ) {
    $homepage = get_page_by_path( 'home' );
}
$front_page_id = get_option( 'page_on_front' );

if ( $homepage ) {
    wp_update_post( array(
        'ID'           => $homepage->ID,
        'post_content' => $homepage_content,
    ) );
    $homepage_id = $homepage->ID;
} elseif ( $front_page_id && $front_page_id > 0 ) {
    wp_update_post( array(
        'ID'           => $front_page_id,
        'post_content' => $homepage_content,
    ) );
    $homepage_id = $front_page_id;
} else {
    $homepage_id = wp_insert_post( array(
        'post_title'   => 'Home',
        'post_name'    => 'home',
        'post_content' => $homepage_content,
        'post_status'  => 'publish',
        'post_type'    => 'page',
    ) );
}

if ( $homepage_id && ! is_wp_error( $homepage_id ) ) {
    update_post_meta( $homepage_id, '_pressmegpt_page_slug', 'home' );
    update_option( 'page_on_front', $homepage_id );
    update_option( 'show_on_front', 'page' );
}

// Create blog page if needed
$blog_page = get_page_by_path( 'blog' );
if ( ! $blog_page ) {
    $blog_id = wp_insert_post( array(
        'post_title'   => 'Blog',
        'post_name'    => 'blog',
        'post_content' => '',
        'post_status'  => 'publish',
        'post_type'    => 'page',
    ) );
    
    if ( $blog_id && ! is_wp_error( $blog_id ) ) {
        update_option( 'page_for_posts', $blog_id );
    }
}

// Create/update additional pages from PressMeGPT
kopka_create_designed_pages();

// Store export hash
update_option( 'kopka_export_hash', 'kopka_pages_hash_v1.1' );

// Create menus with all pages
kopka_create_default_menus();

// Pre-populate PressMeGPT custom header/footer HTML on activation
set_theme_mod( 'pressmegpt_custom_header_html', '<header class="fse-section fse-header fse-header-sticky" data-pmgpt-header-rebuilt="1"><div class="fse-header-left"><div class="fse-logo-area"><img src="https://placehold.co/200x60/EEE/999?text=Logo" alt="kopka Logo" class="fse-image fse-logo"><span class="fse-site-title">kopka</span></div></div><div class="fse-header-right"><nav class="fse-main-nav pmgpt-menu-inline" aria-label="Primary"><ul class="fse-nav-menu fse-nav-menu"><li><a href="/">Home</a></li></ul></nav><span class="fse-toggle-button pmgpt-menu-toggle" id="pmgpt-menu-toggle" role="button" tabindex="0" aria-label="Menu" aria-controls="pmgpt-menu-panel" aria-expanded="false"><svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path d="M3 6h18M3 12h18M3 18h18" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/></svg></span><div class="fse-header-actions"><a href="tel:+79000000000" class="fse-button fse-button-primary fse-button-phone">+7 (900) 000-00-00</a></div></div></header><style data-pmgpt-overlay-injected>
/* PressMeGPT Header — archetype: logo-left-menu-right */
.fse-header-content{padding:15px 15px;box-sizing:border-box;}
.fse-header{display:flex;align-items:center;padding:15px 15px;box-sizing:border-box;}
.fse-header-left{gap:10px;}
.fse-header-center{display:none;flex:1 1 auto;justify-content:center;}
/* PressMeGPT canonical menu — placement:logo-left-menu-right scheme:light */
.fse-header-content,.fse-header-inner{display:flex!important;align-items:center!important;justify-content:space-between!important;flex-direction:row!important;flex-wrap:nowrap!important;}
.fse-header-left{display:flex!important;align-items:center!important;flex:0 1 auto!important;min-width:0!important;}
.fse-header-right{display:flex!important;align-items:center!important;justify-content:flex-end!important;flex:0 0 auto!important;gap:10px;margin-left:auto!important;}
.fse-main-nav{display:none;align-items:center;justify-content:flex-end;}
.fse-main-nav ul{list-style:none!important;margin:0!important;padding:0!important;display:flex!important;align-items:center!important;flex-wrap:nowrap!important;gap:24px;}
.fse-main-nav li{list-style:none!important;margin:0!important;}
.fse-main-nav a{text-decoration:none;color:#343a40;font-family:inherit;font-size:15px;font-weight:500;text-transform:none;white-space:nowrap;}
.fse-main-nav a:hover,.fse-main-nav a.current-menu-item,.fse-main-nav .current-menu-item > a{color:#343a40;text-decoration:underline;text-underline-offset:4px;}
/* Hamburger — always first in the actions cluster, always contrast-checked.
   Stroke AND fill are driven from the token, and a low-contrast fallback adds a
   halo so the icon can never disappear into the bar. */
.fse-toggle-button,#pmgpt-menu-toggle{order:-1!important;display:inline-flex!important;align-items:center!important;justify-content:center!important;background:none!important;border:none!important;box-shadow:none!important;padding:0!important;margin:0 4px 0 0!important;min-width:44px!important;min-height:44px!important;cursor:pointer;color:#343a40!important;opacity:1!important;visibility:visible!important;}
.fse-toggle-button svg,#pmgpt-menu-toggle svg{width:24px!important;height:24px!important;min-width:24px;min-height:24px;stroke:currentColor!important;color:#343a40!important;opacity:1!important;display:inline-block!important;}
.fse-toggle-button svg [stroke]:not([stroke="none"]),#pmgpt-menu-toggle svg [stroke]:not([stroke="none"]){stroke:currentColor!important;}
.fse-toggle-button svg [fill]:not([fill="none"]),#pmgpt-menu-toggle svg [fill]:not([fill="none"]){fill:currentColor!important;}

/* Search never ships */
form[role="search"],input[type="search"],.fse-search{display:none!important;}
@media(min-width:1024px){
  .fse-main-nav{display:flex!important;}
  .fse-toggle-button,#pmgpt-menu-toggle{display:none!important;}
}
/* Drawer — id-scoped so design CSS cannot reshape it */
#pmgpt-menu-backdrop{position:fixed!important;inset:0!important;z-index:99998!important;background:rgba(0,0,0,.5);opacity:0;visibility:hidden;transition:opacity .25s,visibility .25s;}
#pmgpt-menu-backdrop.open{opacity:1;visibility:visible;}
#pmgpt-menu-panel{position:fixed!important;top:0!important;right:0!important;bottom:0!important;left:auto!important;width:300px!important;max-width:86vw!important;z-index:99999!important;display:flex!important;flex-direction:column!important;align-items:stretch!important;transform:translateX(100%);transition:transform .25s ease;background:#ffffff!important;color:#343a40!important;box-shadow:-4px 0 24px rgba(0,0,0,.18);overflow-y:auto;}
#pmgpt-menu-panel.open{transform:translateX(0);}
#pmgpt-menu-panel .pmgpt-menu-panel-head{display:flex!important;flex-direction:row!important;align-items:center!important;justify-content:flex-end!important;width:100%!important;padding:12px 16px!important;border-bottom:1px solid #00000014;}
#pmgpt-menu-close{display:inline-flex!important;align-items:center!important;justify-content:center!important;min-width:44px!important;min-height:44px!important;background:none!important;border:none!important;padding:0!important;cursor:pointer;color:#343a40!important;opacity:1!important;visibility:visible!important;}
#pmgpt-menu-close svg{width:24px!important;height:24px!important;stroke:currentColor!important;color:#343a40!important;opacity:1!important;display:inline-block!important;}
#pmgpt-menu-close svg [stroke]:not([stroke="none"]){stroke:currentColor!important;}
#pmgpt-menu-close svg [fill]:not([fill="none"]){fill:currentColor!important;}
#pmgpt-menu-panel .pmgpt-menu-links{display:flex!important;flex-direction:column!important;align-items:stretch!important;width:100%!important;padding:8px 0!important;}
#pmgpt-menu-panel .pmgpt-menu-link{display:block!important;width:100%!important;box-sizing:border-box!important;padding:14px 20px!important;text-align:left!important;text-decoration:none!important;color:#343a40!important;font-family:inherit;font-size:16px;font-weight:500;text-transform:none;border-bottom:1px solid #00000014;}
#pmgpt-menu-panel .pmgpt-menu-actions{display:flex!important;flex-direction:row!important;flex-wrap:wrap!important;align-items:center!important;gap:12px;padding:16px 20px!important;border-top:1px solid #00000014;}
#pmgpt-menu-panel .pmgpt-menu-actions a,#pmgpt-menu-panel .pmgpt-menu-actions button{color:#343a40;}
#pmgpt-menu-panel .pmgpt-menu-actions svg,#pmgpt-menu-panel .pmgpt-menu-actions img,#pmgpt-menu-panel .pmgpt-menu-actions i{width:22px;height:22px;max-width:22px;max-height:22px;}
@media(min-width:1024px){
  #pmgpt-menu-backdrop,#pmgpt-menu-panel{display:none!important;}
}
/* Header action icons (profile / wishlist / bag …) — design fidelity, inert links */
.fse-header-actions{display:flex!important;align-items:center!important;gap:8px;flex-wrap:nowrap!important;margin-left:auto;}
.fse-header-actions a,.fse-header-actions button{display:inline-flex;flex-direction:column;align-items:center;justify-content:center;gap:2px;text-decoration:none;color:#343a40;font-size:12px;line-height:1.2;white-space:nowrap;}
.fse-header-actions svg,.fse-header-actions i,.fse-header-actions img{width:20px;height:20px;max-width:20px;max-height:20px;font-size:18px;object-fit:contain;flex:0 0 auto;}
/* Suppress fallback WP menu-toggle */
.theme-designer-header .menu-toggle{display:none!important;}
/* Customizer override: ALWAYS show hamburger (opt-in legacy behavior) */
body.pmgpt-mobile-always .fse-main-nav{display:none!important;}
body.pmgpt-mobile-always .fse-toggle-button,
body.pmgpt-mobile-always #pmgpt-menu-toggle,
body.pmgpt-mobile-always [class*="fse-toggle"]{display:inline-flex!important;}
/* Customizer override: NEVER show hamburger (always inline nav) */
body.pmgpt-mobile-never .fse-toggle-button,
body.pmgpt-mobile-never #pmgpt-menu-toggle,
body.pmgpt-mobile-never [class*="fse-toggle"]{display:none!important;}
body.pmgpt-mobile-never .fse-main-nav{display:flex!important;}
</style>
<div class="pmgpt-menu-backdrop" id="pmgpt-menu-backdrop" hidden></div>
<div class="pmgpt-menu-panel" id="pmgpt-menu-panel" role="dialog" aria-modal="true" aria-label="Menu">
  <div class="pmgpt-menu-panel-head">
    <button type="button" class="pmgpt-menu-close" id="pmgpt-menu-close" aria-label="Close menu"><svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path d="M5 5l14 14M19 5L5 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/></svg></button>
  </div>
  <nav class="pmgpt-menu-links" aria-label="Mobile"><a class="pmgpt-menu-link" href="/">Home</a></nav>
  <div class="pmgpt-menu-actions"><div class="fse-header-actions"><a href="tel:+79000000000" class="fse-button fse-button-primary fse-button-phone">+7 (900) 000-00-00</a></div></div>
</div><script>(function(){
  function init(){
    var toggle=document.getElementById(\'pmgpt-menu-toggle\')||document.querySelector(\'.fse-toggle-button,.pmgpt-menu-toggle,[aria-label="Menu"]\');
    var panel=document.getElementById(\'pmgpt-menu-panel\');
    var backdrop=document.getElementById(\'pmgpt-menu-backdrop\');
    var closeBtn=document.getElementById(\'pmgpt-menu-close\');
    if(!toggle||!panel||!backdrop) return;
    if(panel.getAttribute(\'data-pmgpt-menu-bound\')===\'1\') return;
    panel.setAttribute(\'data-pmgpt-menu-bound\',\'1\');
    function focusables(){return panel.querySelectorAll(\'a[href],button:not([disabled]),[tabindex]:not([tabindex="-1"])\');}
    function open(){
      backdrop.hidden=false;backdrop.classList.add(\'open\');panel.classList.add(\'open\');
      toggle.setAttribute(\'aria-expanded\',\'true\');document.documentElement.style.overflow=\'hidden\';
      var f=focusables();if(f.length)f[0].focus();
    }
    function close(){
      backdrop.classList.remove(\'open\');panel.classList.remove(\'open\');
      toggle.setAttribute(\'aria-expanded\',\'false\');document.documentElement.style.overflow=\'\';
      window.setTimeout(function(){if(!panel.classList.contains(\'open\'))backdrop.hidden=true;},250);
      if(toggle.focus)toggle.focus();
    }
    function toggleMenu(e){if(e){e.preventDefault();e.stopPropagation();}panel.classList.contains(\'open\')?close():open();}
    toggle.addEventListener(\'click\',toggleMenu);
    toggle.addEventListener(\'keydown\',function(e){if(e.key===\'Enter\'||e.key===\' \')toggleMenu(e);});
    if(closeBtn)closeBtn.addEventListener(\'click\',close);
    backdrop.addEventListener(\'click\',close);
    document.addEventListener(\'keydown\',function(e){
      if(!panel.classList.contains(\'open\'))return;
      if(e.key===\'Escape\'){close();return;}
      if(e.key!==\'Tab\')return;
      var f=focusables();if(!f.length)return;
      var first=f[0],last=f[f.length-1];
      if(e.shiftKey&&document.activeElement===first){e.preventDefault();last.focus();}
      else if(!e.shiftKey&&document.activeElement===last){e.preventDefault();first.focus();}
    });
    panel.addEventListener(\'click\',function(e){var t=e.target;if(t&&t.closest&&t.closest(\'a\'))close();});
  }
  if(document.readyState===\'loading\')document.addEventListener(\'DOMContentLoaded\',init);else init();
})();</script>' );
set_theme_mod( 'pressmegpt_custom_footer_html', '<footer class="fse-section fse-footer"><div class="fse-group fse-footer-content"><div class="fse-columns fse-footer-columns"><div class="fse-column fse-footer-about"><img src="https://placehold.co/200x60/EEE/999?text=Logo" alt="kopka Logo" class="fse-image fse-footer-logo"><p class="fse-paragraph">Индивидуальный предприниматель в сфере земляных работ. Экскаватор-погрузчик с оператором в Калининграде и области.</p></div><div class="fse-column fse-footer-links"><h4 class="fse-heading">Быстрые Ссылки</h4><ul class="fse-footer-menu"><li><a href="/">Home</a></li></ul></div><div class="fse-column fse-footer-contact"><h4 class="fse-heading">Связаться</h4><ul class="fse-contact-list"><li><i class="fas fa-map-marker-alt"></i> <span>Калининград и область</span></li><li><i class="fas fa-phone"></i> <span><a href="tel:+79000000000">+7 (900) 000-00-00</a></span></li><li><i class="fas fa-envelope"></i> <span><a href="mailto:info@kopka.ru">info@kopka.ru</a></span></li></ul></div></div><div class="fse-footer-bottom"><p class="fse-copyright">&copy; 2026 kopka. Все права защищены.</p></div></div></footer>' );
}
add_action( 'after_switch_theme', 'kopka_activation' );

/**
 * Re-sync pages on init if export hash changed (theme update without reactivation).
 * Runs on frontend too so InstaWP redeploys refresh designed chrome without wp-admin.
 */
function kopka_check_page_sync() {
$stored_hash = get_option( 'kopka_export_hash', '' );
$current_hash = 'kopka_pages_hash_v1.1';
if ( $stored_hash !== $current_hash ) {
    if ( is_admin() ) {
        kopka_create_designed_pages();
        kopka_create_default_menus();
    }
    // Always refresh header/footer theme mods with latest exported HTML
    set_theme_mod( 'pressmegpt_custom_header_html', '<header class="fse-section fse-header fse-header-sticky" data-pmgpt-header-rebuilt="1"><div class="fse-header-left"><div class="fse-logo-area"><img src="https://placehold.co/200x60/EEE/999?text=Logo" alt="kopka Logo" class="fse-image fse-logo"><span class="fse-site-title">kopka</span></div></div><div class="fse-header-right"><nav class="fse-main-nav pmgpt-menu-inline" aria-label="Primary"><ul class="fse-nav-menu fse-nav-menu"><li><a href="/">Home</a></li></ul></nav><span class="fse-toggle-button pmgpt-menu-toggle" id="pmgpt-menu-toggle" role="button" tabindex="0" aria-label="Menu" aria-controls="pmgpt-menu-panel" aria-expanded="false"><svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path d="M3 6h18M3 12h18M3 18h18" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/></svg></span><div class="fse-header-actions"><a href="tel:+79000000000" class="fse-button fse-button-primary fse-button-phone">+7 (900) 000-00-00</a></div></div></header><style data-pmgpt-overlay-injected>
/* PressMeGPT Header — archetype: logo-left-menu-right */
.fse-header-content{padding:15px 15px;box-sizing:border-box;}
.fse-header{display:flex;align-items:center;padding:15px 15px;box-sizing:border-box;}
.fse-header-left{gap:10px;}
.fse-header-center{display:none;flex:1 1 auto;justify-content:center;}
/* PressMeGPT canonical menu — placement:logo-left-menu-right scheme:light */
.fse-header-content,.fse-header-inner{display:flex!important;align-items:center!important;justify-content:space-between!important;flex-direction:row!important;flex-wrap:nowrap!important;}
.fse-header-left{display:flex!important;align-items:center!important;flex:0 1 auto!important;min-width:0!important;}
.fse-header-right{display:flex!important;align-items:center!important;justify-content:flex-end!important;flex:0 0 auto!important;gap:10px;margin-left:auto!important;}
.fse-main-nav{display:none;align-items:center;justify-content:flex-end;}
.fse-main-nav ul{list-style:none!important;margin:0!important;padding:0!important;display:flex!important;align-items:center!important;flex-wrap:nowrap!important;gap:24px;}
.fse-main-nav li{list-style:none!important;margin:0!important;}
.fse-main-nav a{text-decoration:none;color:#343a40;font-family:inherit;font-size:15px;font-weight:500;text-transform:none;white-space:nowrap;}
.fse-main-nav a:hover,.fse-main-nav a.current-menu-item,.fse-main-nav .current-menu-item > a{color:#343a40;text-decoration:underline;text-underline-offset:4px;}
/* Hamburger — always first in the actions cluster, always contrast-checked.
   Stroke AND fill are driven from the token, and a low-contrast fallback adds a
   halo so the icon can never disappear into the bar. */
.fse-toggle-button,#pmgpt-menu-toggle{order:-1!important;display:inline-flex!important;align-items:center!important;justify-content:center!important;background:none!important;border:none!important;box-shadow:none!important;padding:0!important;margin:0 4px 0 0!important;min-width:44px!important;min-height:44px!important;cursor:pointer;color:#343a40!important;opacity:1!important;visibility:visible!important;}
.fse-toggle-button svg,#pmgpt-menu-toggle svg{width:24px!important;height:24px!important;min-width:24px;min-height:24px;stroke:currentColor!important;color:#343a40!important;opacity:1!important;display:inline-block!important;}
.fse-toggle-button svg [stroke]:not([stroke="none"]),#pmgpt-menu-toggle svg [stroke]:not([stroke="none"]){stroke:currentColor!important;}
.fse-toggle-button svg [fill]:not([fill="none"]),#pmgpt-menu-toggle svg [fill]:not([fill="none"]){fill:currentColor!important;}

/* Search never ships */
form[role="search"],input[type="search"],.fse-search{display:none!important;}
@media(min-width:1024px){
  .fse-main-nav{display:flex!important;}
  .fse-toggle-button,#pmgpt-menu-toggle{display:none!important;}
}
/* Drawer — id-scoped so design CSS cannot reshape it */
#pmgpt-menu-backdrop{position:fixed!important;inset:0!important;z-index:99998!important;background:rgba(0,0,0,.5);opacity:0;visibility:hidden;transition:opacity .25s,visibility .25s;}
#pmgpt-menu-backdrop.open{opacity:1;visibility:visible;}
#pmgpt-menu-panel{position:fixed!important;top:0!important;right:0!important;bottom:0!important;left:auto!important;width:300px!important;max-width:86vw!important;z-index:99999!important;display:flex!important;flex-direction:column!important;align-items:stretch!important;transform:translateX(100%);transition:transform .25s ease;background:#ffffff!important;color:#343a40!important;box-shadow:-4px 0 24px rgba(0,0,0,.18);overflow-y:auto;}
#pmgpt-menu-panel.open{transform:translateX(0);}
#pmgpt-menu-panel .pmgpt-menu-panel-head{display:flex!important;flex-direction:row!important;align-items:center!important;justify-content:flex-end!important;width:100%!important;padding:12px 16px!important;border-bottom:1px solid #00000014;}
#pmgpt-menu-close{display:inline-flex!important;align-items:center!important;justify-content:center!important;min-width:44px!important;min-height:44px!important;background:none!important;border:none!important;padding:0!important;cursor:pointer;color:#343a40!important;opacity:1!important;visibility:visible!important;}
#pmgpt-menu-close svg{width:24px!important;height:24px!important;stroke:currentColor!important;color:#343a40!important;opacity:1!important;display:inline-block!important;}
#pmgpt-menu-close svg [stroke]:not([stroke="none"]){stroke:currentColor!important;}
#pmgpt-menu-close svg [fill]:not([fill="none"]){fill:currentColor!important;}
#pmgpt-menu-panel .pmgpt-menu-links{display:flex!important;flex-direction:column!important;align-items:stretch!important;width:100%!important;padding:8px 0!important;}
#pmgpt-menu-panel .pmgpt-menu-link{display:block!important;width:100%!important;box-sizing:border-box!important;padding:14px 20px!important;text-align:left!important;text-decoration:none!important;color:#343a40!important;font-family:inherit;font-size:16px;font-weight:500;text-transform:none;border-bottom:1px solid #00000014;}
#pmgpt-menu-panel .pmgpt-menu-actions{display:flex!important;flex-direction:row!important;flex-wrap:wrap!important;align-items:center!important;gap:12px;padding:16px 20px!important;border-top:1px solid #00000014;}
#pmgpt-menu-panel .pmgpt-menu-actions a,#pmgpt-menu-panel .pmgpt-menu-actions button{color:#343a40;}
#pmgpt-menu-panel .pmgpt-menu-actions svg,#pmgpt-menu-panel .pmgpt-menu-actions img,#pmgpt-menu-panel .pmgpt-menu-actions i{width:22px;height:22px;max-width:22px;max-height:22px;}
@media(min-width:1024px){
  #pmgpt-menu-backdrop,#pmgpt-menu-panel{display:none!important;}
}
/* Header action icons (profile / wishlist / bag …) — design fidelity, inert links */
.fse-header-actions{display:flex!important;align-items:center!important;gap:8px;flex-wrap:nowrap!important;margin-left:auto;}
.fse-header-actions a,.fse-header-actions button{display:inline-flex;flex-direction:column;align-items:center;justify-content:center;gap:2px;text-decoration:none;color:#343a40;font-size:12px;line-height:1.2;white-space:nowrap;}
.fse-header-actions svg,.fse-header-actions i,.fse-header-actions img{width:20px;height:20px;max-width:20px;max-height:20px;font-size:18px;object-fit:contain;flex:0 0 auto;}
/* Suppress fallback WP menu-toggle */
.theme-designer-header .menu-toggle{display:none!important;}
/* Customizer override: ALWAYS show hamburger (opt-in legacy behavior) */
body.pmgpt-mobile-always .fse-main-nav{display:none!important;}
body.pmgpt-mobile-always .fse-toggle-button,
body.pmgpt-mobile-always #pmgpt-menu-toggle,
body.pmgpt-mobile-always [class*="fse-toggle"]{display:inline-flex!important;}
/* Customizer override: NEVER show hamburger (always inline nav) */
body.pmgpt-mobile-never .fse-toggle-button,
body.pmgpt-mobile-never #pmgpt-menu-toggle,
body.pmgpt-mobile-never [class*="fse-toggle"]{display:none!important;}
body.pmgpt-mobile-never .fse-main-nav{display:flex!important;}
</style>
<div class="pmgpt-menu-backdrop" id="pmgpt-menu-backdrop" hidden></div>
<div class="pmgpt-menu-panel" id="pmgpt-menu-panel" role="dialog" aria-modal="true" aria-label="Menu">
  <div class="pmgpt-menu-panel-head">
    <button type="button" class="pmgpt-menu-close" id="pmgpt-menu-close" aria-label="Close menu"><svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path d="M5 5l14 14M19 5L5 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/></svg></button>
  </div>
  <nav class="pmgpt-menu-links" aria-label="Mobile"><a class="pmgpt-menu-link" href="/">Home</a></nav>
  <div class="pmgpt-menu-actions"><div class="fse-header-actions"><a href="tel:+79000000000" class="fse-button fse-button-primary fse-button-phone">+7 (900) 000-00-00</a></div></div>
</div><script>(function(){
  function init(){
    var toggle=document.getElementById(\'pmgpt-menu-toggle\')||document.querySelector(\'.fse-toggle-button,.pmgpt-menu-toggle,[aria-label="Menu"]\');
    var panel=document.getElementById(\'pmgpt-menu-panel\');
    var backdrop=document.getElementById(\'pmgpt-menu-backdrop\');
    var closeBtn=document.getElementById(\'pmgpt-menu-close\');
    if(!toggle||!panel||!backdrop) return;
    if(panel.getAttribute(\'data-pmgpt-menu-bound\')===\'1\') return;
    panel.setAttribute(\'data-pmgpt-menu-bound\',\'1\');
    function focusables(){return panel.querySelectorAll(\'a[href],button:not([disabled]),[tabindex]:not([tabindex="-1"])\');}
    function open(){
      backdrop.hidden=false;backdrop.classList.add(\'open\');panel.classList.add(\'open\');
      toggle.setAttribute(\'aria-expanded\',\'true\');document.documentElement.style.overflow=\'hidden\';
      var f=focusables();if(f.length)f[0].focus();
    }
    function close(){
      backdrop.classList.remove(\'open\');panel.classList.remove(\'open\');
      toggle.setAttribute(\'aria-expanded\',\'false\');document.documentElement.style.overflow=\'\';
      window.setTimeout(function(){if(!panel.classList.contains(\'open\'))backdrop.hidden=true;},250);
      if(toggle.focus)toggle.focus();
    }
    function toggleMenu(e){if(e){e.preventDefault();e.stopPropagation();}panel.classList.contains(\'open\')?close():open();}
    toggle.addEventListener(\'click\',toggleMenu);
    toggle.addEventListener(\'keydown\',function(e){if(e.key===\'Enter\'||e.key===\' \')toggleMenu(e);});
    if(closeBtn)closeBtn.addEventListener(\'click\',close);
    backdrop.addEventListener(\'click\',close);
    document.addEventListener(\'keydown\',function(e){
      if(!panel.classList.contains(\'open\'))return;
      if(e.key===\'Escape\'){close();return;}
      if(e.key!==\'Tab\')return;
      var f=focusables();if(!f.length)return;
      var first=f[0],last=f[f.length-1];
      if(e.shiftKey&&document.activeElement===first){e.preventDefault();last.focus();}
      else if(!e.shiftKey&&document.activeElement===last){e.preventDefault();first.focus();}
    });
    panel.addEventListener(\'click\',function(e){var t=e.target;if(t&&t.closest&&t.closest(\'a\'))close();});
  }
  if(document.readyState===\'loading\')document.addEventListener(\'DOMContentLoaded\',init);else init();
})();</script>' );
    set_theme_mod( 'pressmegpt_custom_footer_html', '<footer class="fse-section fse-footer"><div class="fse-group fse-footer-content"><div class="fse-columns fse-footer-columns"><div class="fse-column fse-footer-about"><img src="https://placehold.co/200x60/EEE/999?text=Logo" alt="kopka Logo" class="fse-image fse-footer-logo"><p class="fse-paragraph">Индивидуальный предприниматель в сфере земляных работ. Экскаватор-погрузчик с оператором в Калининграде и области.</p></div><div class="fse-column fse-footer-links"><h4 class="fse-heading">Быстрые Ссылки</h4><ul class="fse-footer-menu"><li><a href="/">Home</a></li></ul></div><div class="fse-column fse-footer-contact"><h4 class="fse-heading">Связаться</h4><ul class="fse-contact-list"><li><i class="fas fa-map-marker-alt"></i> <span>Калининград и область</span></li><li><i class="fas fa-phone"></i> <span><a href="tel:+79000000000">+7 (900) 000-00-00</a></span></li><li><i class="fas fa-envelope"></i> <span><a href="mailto:info@kopka.ru">info@kopka.ru</a></span></li></ul></div></div><div class="fse-footer-bottom"><p class="fse-copyright">&copy; 2026 kopka. Все права защищены.</p></div></div></footer>' );
    update_option( 'kopka_export_hash', $current_hash );
}
}
add_action( 'init', 'kopka_check_page_sync', 5 );

/**
 * Create additional designed pages on theme activation
 */
function kopka_create_designed_pages() {
$pages_data = kopka_get_pages_data();

foreach ( $pages_data as $slug => $page_info ) {
    if ( empty( $slug ) || $slug === 'home' ) {
        continue;
    }
    
    // Skip template entries
    if ( ! empty( $page_info['is_template'] ) ) {
        continue;
    }
    
    $post_type = isset( $page_info['type'] ) && $page_info['type'] === 'post' ? 'post' : 'page';
    
    // Look up existing page by custom meta key first (handles WP slug renames)
    $existing = null;
    $meta_query = new WP_Query( array(
        'post_type'  => $post_type,
        'meta_key'   => '_pressmegpt_page_slug',
        'meta_value' => $slug,
        'posts_per_page' => 1,
        'post_status' => 'any',
    ) );
    if ( $meta_query->have_posts() ) {
        $existing = $meta_query->posts[0];
    }
    wp_reset_postdata();
    
    // Fallback to path lookup
    if ( ! $existing ) {
        $existing = get_page_by_path( $slug );
    }
    
    if ( $existing ) {
        wp_update_post( array(
            'ID'           => $existing->ID,
            'post_content' => $page_info['content'],
        ) );
    } else {
        $new_id = wp_insert_post( array(
            'post_title'   => $page_info['title'],
            'post_name'    => $slug,
            'post_content' => $page_info['content'],
            'post_status'  => 'publish',
            'post_type'    => $post_type,
        ) );
        if ( $new_id && ! is_wp_error( $new_id ) ) {
            update_post_meta( $new_id, '_pressmegpt_page_slug', $slug );
        }
    }
}
}

/**
 * Get designed pages data (embedded at build time)
 */
function kopka_get_pages_data() {
return array(
        // No additional pages
);
}

/**
 * Look up a PressMeGPT-imported page/post by its original slug meta key.
 * Returns the WP post ID or 0 if not found.
 */
function kopka_find_page_by_slug( $slug, $post_type = 'page' ) {
$q = new WP_Query( array(
    'post_type'      => $post_type,
    'meta_key'       => '_pressmegpt_page_slug',
    'meta_value'     => $slug,
    'posts_per_page' => 1,
    'post_status'    => 'publish',
    'fields'         => 'ids',
) );
$ids = $q->posts;
wp_reset_postdata();
if ( ! empty( $ids ) ) {
    return (int) $ids[0];
}
// Fallback: try by path
$p = get_page_by_path( $slug, OBJECT, $post_type );
return $p ? (int) $p->ID : 0;
}

/**
 * Create default menus on theme activation.
 * Links point to the actual imported WordPress page/post objects
 * so URLs stay valid even if the user renames slugs later.
 */
function kopka_create_default_menus() {
// Delete and recreate Primary Menu to prevent duplicates on re-import
$primary_menu_name = 'Primary Menu';
$primary_menu_exists = wp_get_nav_menu_object( $primary_menu_name );

if ( $primary_menu_exists ) {
    wp_delete_nav_menu( $primary_menu_exists->term_id );
}

$primary_menu_id = wp_create_nav_menu( $primary_menu_name );

// Add Home link — try to use the front page object
$front_page_id = (int) get_option( 'page_on_front' );
if ( $front_page_id ) {
    wp_update_nav_menu_item( $primary_menu_id, 0, array(
        'menu-item-title'     => 'Home',
        'menu-item-type'      => 'post_type',
        'menu-item-object'    => 'page',
        'menu-item-object-id' => $front_page_id,
        'menu-item-status'    => 'publish',
    ) );
} else {
    wp_update_nav_menu_item( $primary_menu_id, 0, array(
        'menu-item-title'   => 'Home',
        'menu-item-url'     => home_url( '/' ),
        'menu-item-type'    => 'custom',
        'menu-item-status'  => 'publish',
    ) );
}

    // No additional pages for menu
    
// Assign to primary location
$locations = get_theme_mod( 'nav_menu_locations' );
$locations['primary'] = $primary_menu_id;
set_theme_mod( 'nav_menu_locations', $locations );

// Delete and recreate Footer Menu to prevent duplicates on re-import
$footer_menu_name = 'Footer Menu';
$footer_menu_exists = wp_get_nav_menu_object( $footer_menu_name );

if ( $footer_menu_exists ) {
    wp_delete_nav_menu( $footer_menu_exists->term_id );
}

$footer_menu_id = wp_create_nav_menu( $footer_menu_name );

// Add Home link to footer
if ( $front_page_id ) {
    wp_update_nav_menu_item( $footer_menu_id, 0, array(
        'menu-item-title'     => 'Home',
        'menu-item-type'      => 'post_type',
        'menu-item-object'    => 'page',
        'menu-item-object-id' => $front_page_id,
        'menu-item-status'    => 'publish',
    ) );
} else {
    wp_update_nav_menu_item( $footer_menu_id, 0, array(
        'menu-item-title'   => 'Home',
        'menu-item-url'     => home_url( '/' ),
        'menu-item-type'    => 'custom',
        'menu-item-status'  => 'publish',
    ) );
}

    // No additional pages for footer menu

// Add Privacy Policy to footer
wp_update_nav_menu_item( $footer_menu_id, 0, array(
    'menu-item-title'   => 'Privacy Policy',
    'menu-item-url'     => home_url( '/privacy-policy/' ),
    'menu-item-type'    => 'custom',
    'menu-item-status'  => 'publish',
) );

// Assign to footer location
$locations = get_theme_mod( 'nav_menu_locations' );
$locations['footer'] = $footer_menu_id;
set_theme_mod( 'nav_menu_locations', $locations );
}

/**
 * PressMeGPT Configuration
 */
define( 'PRESSMEGPT_PROJECT_ID', '3fd472db-6c62-4f30-ac2f-b2b96b53bb87' );
define( 'PRESSMEGPT_EDIT_URL', 'https://pressmegpt.com/project/3fd472db-6c62-4f30-ac2f-b2b96b53bb87' );

/**
 * Add "Edit with PressMeGPT" link to admin bar
 */
function kopka_admin_bar_edit_link( $wp_admin_bar ) {
if ( ! current_user_can( 'edit_theme_options' ) ) {
    return;
}

$wp_admin_bar->add_node( array(
    'id'    => 'pressmegpt-edit',
    'title' => '<span class="ab-icon dashicons dashicons-admin-customizer" style="font-family: dashicons; font-size: 20px; padding-top: 4px;"></span> Edit with PressMeGPT',
    'href'  => PRESSMEGPT_EDIT_URL,
    'meta'  => array( 
        'target' => '_blank',
        'title'  => 'Open PressMeGPT to edit your website',
    ),
) );
}
add_action( 'admin_bar_menu', 'kopka_admin_bar_edit_link', 100 );

/**
 * Add dashboard widget for PressMeGPT
 */
function kopka_dashboard_widget() {
echo '<div style="text-align: center; padding: 20px 10px;">';
echo '';
echo '<h3 style="margin: 0 0 10px 0;">Your Website is AI-Powered</h3>';
echo '<p style="color: #666; margin-bottom: 20px;">Your website was created with PressMeGPT. Use AI to change colors, text, images, and layout.</p>';
echo '<a href="' . esc_url( PRESSMEGPT_EDIT_URL ) . '" target="_blank" class="button button-primary button-hero" style="font-size: 16px;">Edit with PressMeGPT</a>';
echo '<p style="color: #999; font-size: 12px; margin-top: 15px;">Opens in a new tab</p>';
echo '</div>';
}

function kopka_add_dashboard_widget() {
wp_add_dashboard_widget(
    'pressmegpt_edit_widget',
    'kopka - AI Theme Editor',
    'kopka_dashboard_widget'
);

// Move widget to top
global $wp_meta_boxes;
$dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
$widget = array( 'pressmegpt_edit_widget' => $dashboard['pressmegpt_edit_widget'] );
unset( $dashboard['pressmegpt_edit_widget'] );
$wp_meta_boxes['dashboard']['normal']['core'] = array_merge( $widget, $dashboard );
}
add_action( 'wp_dashboard_setup', 'kopka_add_dashboard_widget' );

/**
 * Add "Edit Homepage" menu item under Appearance
 */
function kopka_add_appearance_menu() {
add_theme_page(
    'kopka - Edit with PressMeGPT',
    'Edit with PressMeGPT',
    'edit_theme_options',
    'pressmegpt-edit-homepage',
    'kopka_render_edit_page'
);
}
add_action( 'admin_menu', 'kopka_add_appearance_menu' );

function kopka_render_edit_page() {
?>
<div class="wrap" style="max-width: 700px;">
    <h1 style="display: flex; align-items: center; gap: 10px;">
        
        Edit your theme with PressMeGPT
    </h1>
    
    <div style="background: #fff; border: 1px solid #ccd0d4; border-radius: 4px; padding: 30px; margin-top: 20px; text-align: center;">
        <p style="font-size: 16px; color: #666; margin-bottom: 25px;">
            Your website was created with PressMeGPT. Click below to make changes using natural language AI prompts.
        </p>
        
        <a href="<?php echo esc_url( PRESSMEGPT_EDIT_URL ); ?>" target="_blank" class="button button-primary button-hero" style="font-size: 18px; padding: 12px 30px;">
            Open AI Editor →
        </a>
    </div>
    
    <div style="background: #f8f9fa; border: 1px solid #e2e4e7; border-radius: 4px; padding: 25px; margin-top: 25px;">
        <h2 style="margin-top: 0; font-size: 18px;">What you can change:</h2>
        <ul style="list-style: none; padding: 0; margin: 0; display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
            <li style="display: flex; align-items: center; gap: 8px;">•  Colors and color schemes</li>
            <li style="display: flex; align-items: center; gap: 8px;">•  Fonts and typography</li>
            <li style="display: flex; align-items: center; gap: 8px;">•  Images and logos</li>
            <li style="display: flex; align-items: center; gap: 8px;">•  Text content and copy</li>
            <li style="display: flex; align-items: center; gap: 8px;">•  Section layouts</li>
            <li style="display: flex; align-items: center; gap: 8px;">•  Overall design style</li>
        </ul>
    </div>
    
    <p style="color: #666; font-size: 13px; margin-top: 20px;">
        <strong>Tip:</strong> After making changes in PressMeGPT, export and re-upload your theme to see updates.
    </p>
</div>
<?php
}

/**
 * Theme activation notice
 */
function kopka_activation_notice() {
$transient_name = 'kopka_activation_notice';

if ( ! get_transient( $transient_name ) ) {
    return;
}

delete_transient( $transient_name );
?>
<div class="notice notice-success is-dismissible" style="padding: 15px;">
    <p style="font-size: 16px; margin: 0 0 10px 0;">
        <strong>kopka is now active!</strong>
    </p>
    <p style="margin: 0;">
        Your AI-generated website is ready. Need to make changes? 
        <a href="<?php echo esc_url( PRESSMEGPT_EDIT_URL ); ?>" target="_blank" style="font-weight: bold;">Edit with PressMeGPT →</a>
    </p>
</div>
<?php
}
add_action( 'admin_notices', 'kopka_activation_notice' );

// Set transient on theme activation
add_action( 'after_switch_theme', function() {
set_transient( 'kopka_activation_notice', true, 60 );
} );

/**
 * Classic Theme Page Editor Notice
 * Shows a prominent banner in the block editor explaining that homepage edits 
 * should be made in PressMeGPT or by downloading the Gutenberg version
 */
function kopka_page_editor_notice() {
$screen = get_current_screen();

// Only show on post/page edit screens using the block editor
if ( ! $screen || $screen->base !== 'post' || ! $screen->is_block_editor ) {
    return;
}

// Get the download URL for this theme (Gutenberg version)
$gutenberg_download_url = 'https://pressmegpt.com/project/' . PRESSMEGPT_PROJECT_ID . '?export=gutenberg';

?>
<div id="pressmegpt-classic-notice" style="
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    padding: 16px 20px;
    margin: 0 0 20px 0;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
">
    
    <div style="flex: 1; min-width: 280px;">
        <strong style="font-size: 15px; display: block; margin-bottom: 4px;">
            This is a WordPress Classic Theme
        </strong>
        <span style="opacity: 0.95; font-size: 13px;">
            Website changes won't appear on the frontend. Edit your website design with AI on PressMeGPT, or download the Gutenberg version to edit directly in WordPress.
        </span>
    </div>
    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
        <a href="<?php echo esc_url( PRESSMEGPT_EDIT_URL ); ?>" target="_blank" style="
            background: #fff;
            color: #667eea;
            padding: 10px 18px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: transform 0.2s, box-shadow 0.2s;
        " onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)';" onmouseout="this.style.transform='none';this.style.boxShadow='none';">
            ✨ Edit with AI
        </a>
        <a href="<?php echo esc_url( $gutenberg_download_url ); ?>" target="_blank" style="
            background: rgba(255,255,255,0.2);
            color: #fff;
            padding: 10px 18px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1px solid rgba(255,255,255,0.3);
            transition: background 0.2s;
        " onmouseover="this.style.background='rgba(255,255,255,0.3)';" onmouseout="this.style.background='rgba(255,255,255,0.2)';">
            ⬇️ Get Gutenberg Version
        </a>
    </div>
</div>
<script>
// Move notice to top of editor after it loads
(function() {
    function moveNotice() {
        var notice = document.getElementById('pressmegpt-classic-notice');
        var editorWrapper = document.querySelector('.interface-interface-skeleton__content, .edit-post-layout__content, .editor-styles-wrapper');
        var headerBar = document.querySelector('.edit-post-header, .editor-header');
        
        if (notice && headerBar && headerBar.parentNode) {
            // Insert after the header bar
            headerBar.parentNode.insertBefore(notice, headerBar.nextSibling);
        } else if (notice) {
            // Fallback: keep it visible at the top of the notices area
            var wpBody = document.querySelector('.block-editor-writing-flow, #wpbody-content');
            if (wpBody && wpBody.firstChild) {
                wpBody.insertBefore(notice, wpBody.firstChild);
            }
        }
    }
    
    // Try immediately and after short delay for block editor loading
    moveNotice();
    setTimeout(moveNotice, 500);
    setTimeout(moveNotice, 1500);
})();
</script>
<?php
}
add_action( 'admin_notices', 'kopka_page_editor_notice' );

/**
 * Add row action "Edit on PressMeGPT" to Pages list
 */
function kopka_page_row_actions( $actions, $post ) {
if ( $post->post_type === 'page' && current_user_can( 'edit_theme_options' ) ) {
    $actions['pressmegpt_edit'] = sprintf(
        '<a href="%s" target="_blank" style="color: #667eea; font-weight: 500;">%s</a>',
        esc_url( PRESSMEGPT_EDIT_URL ),
        __( 'Edit on PressMeGPT', 'kopka' )
    );
}
return $actions;
}
add_filter( 'page_row_actions', 'kopka_page_row_actions', 10, 2 );

/**
 * Load theme deletion helper
 */
require_once get_template_directory() . '/inc/delete-theme.php';

/* ============================================================
 * ЭКСКАВАТОР 39 — дополнения
 * ============================================================ */

// Контактные данные сайта (редактируются в inc/site-data.php)
require_once get_template_directory() . '/inc/site-data.php';

/**
 * Font Awesome — иконки в секциях.
 * Раньше подключался прямо в front-page.php вместе с лишним <!DOCTYPE>,
 * из-за чего страница отдавала невалидный HTML.
 */
function kopka39_enqueue_icons() {
	wp_enqueue_style(
		'font-awesome',
		'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
		array(),
		'6.5.1'
	);
	wp_enqueue_style(
		'kopka39-custom',
		get_template_directory_uri() . '/css/kopka39.css',
		array( 'kopka-style' ),
		file_exists( get_template_directory() . '/css/kopka39.css' ) ? filemtime( get_template_directory() . '/css/kopka39.css' ) : '1.0.0'
	);
}
add_action( 'wp_enqueue_scripts', 'kopka39_enqueue_icons', 20 );


/* ============================================================
 * ЭКСКАВАТОР 39 — логотип, фавикон, телефон в шапке
 *
 * Шапка и футер приходят из theme_mod PressMeGPT с жёстко
 * прописанным placehold.co и номером-заглушкой. Фильтруем
 * значение на выходе — работает и для дефолта в header.php,
 * и для варианта, сохранённого в базе через Настройщик.
 * ============================================================ */

function kopka39_brand_html( $html, $is_footer = false ) {
	if ( ! is_string( $html ) || '' === $html ) {
		return $html;
	}

	// Шапка тёмная — логотип в светлых тонах (logo.png).
	// Футер теперь светлый (см. css/kopka39.css) — нужен графитовый
	// вариант логотипа, иначе он снова сольётся с фоном.
	$logo_file = 'logo.png'; // футер тоже тёмный (css/kopka3d.css)
	$logo      = get_template_directory_uri() . '/img/' . $logo_file;

	// Заглушка логотипа → наш логотип
	$html = preg_replace( '#https://placehold\.co/[^"\'\s]*#', esc_url( $logo ), $html );
	$html = str_replace( 'kopka Logo', 'ЭКСКАВАТОР 39', $html );

	// Текстовый логотип рядом с картинкой не нужен — он уже в логотипе
	$html = preg_replace( '#<span class="fse-site-title">.*?</span>#us', '', $html );

	// Телефон-заглушка → реальный из inc/site-data.php
	if ( function_exists( 'kopka_d' ) ) {
		$html = str_replace( '+7 (900) 000-00-00', esc_html( kopka_d( 'phone' ) ), $html );
		$html = str_replace( 'tel:+79000000000', 'tel:' . esc_attr( kopka_d( 'phone_raw' ) ), $html );
		// Сайт не использует WhatsApp — если в шаблоне вдруг остался
		// wa.me-плейсхолдер, заменяем его на Telegram, а не на пустоту.
		$html = str_replace( 'https://wa.me/79000000000', esc_url( kopka_d( 'telegram' ) ), $html );
	}

	return $html;
}
add_filter( 'theme_mod_pressmegpt_custom_header_html', 'kopka39_brand_html' );
add_filter( 'theme_mod_pressmegpt_custom_footer_html', function( $html ) {
	return kopka39_brand_html( $html, true );
} );

/**
 * Фавикон из ковша логотипа — если иконка сайта не задана в настройках.
 */
function kopka39_favicon() {
	if ( function_exists( 'has_site_icon' ) && has_site_icon() ) {
		return;
	}
	$icon = get_template_directory_uri() . '/img/favicon-512.png';
	echo '<link rel="icon" href="' . esc_url( $icon ) . '" sizes="512x512">' . "\n";
	echo '<link rel="apple-touch-icon" href="' . esc_url( $icon ) . '">' . "\n";
}
add_action( 'wp_head', 'kopka39_favicon', 5 );

/**
 * Кнопка "Написать в MAX". Ссылка на профиль указана в
 * wp-content/themes/kopka/inc/site-data.php (поле 'max') — кнопка
 * везде на сайте ведёт на неё автоматически. Если поле когда-либо
 * снова окажется пустым, кнопка вернётся в безопасный режим-заглушку
 * (без перехода на несуществующий адрес) — см. код ниже.
 */
function kopka39_max_button_html( $max, $classes = 'fse-button fse-button-secondary' ) {
	if ( ! empty( $max ) ) {
		return '<a href="' . esc_url( $max ) . '" class="' . esc_attr( $classes ) . '" target="_blank" rel="noopener noreferrer">Написать в MAX</a>';
	}
	// Ссылки пока нет: кнопка не disabled (иначе клик не сработает и
	// подсказку не показать), а обычная кнопка без перехода, которая
	// по клику показывает безопасное сообщение вместо ошибочного адреса.
	return '<span class="fse-max-button-wrap">'
		. '<button type="button" class="' . esc_attr( $classes ) . ' fse-button-max-pending" '
		. 'onclick="this.nextElementSibling.hidden=false;" title="Ссылка на MAX будет добавлена позже">Написать в MAX</button>'
		. '<small class="fse-max-note" hidden>Ссылка на MAX будет добавлена позже</small>'
		. '</span>';
}

/* ============================================================
 * SEO: заголовки вкладок и описания страниц
 *
 * SEO-плагина на сайте нет, поэтому title и meta description
 * задаются здесь. Тексты — из SEO-структуры проекта, приведённые
 * к текущим реальным данным (ставка, минимальный заказ, подача).
 *
 * Ключ массива — slug страницы, '_front' — главная.
 * Длина description выдержана в пределах ~160 знаков, чтобы
 * сниппет не обрезался в выдаче.
 * ============================================================ */

function kopka39_seo_data() {
	return array(

		'_front' => array(
			'title' => 'Экскаватор-погрузчик с оператором в Калининграде | ЭКСКАВАТОР 39',
			'desc'  => 'Земляные работы экскаватором-погрузчиком в Калининграде и области: траншеи, котлованы, планировка, погрузка грунта. 3000 ₽/час, работаю сам, без посредников.',
		),

		'zemlyanye-raboty' => array(
			'title' => 'Земляные работы в Калининграде экскаватором-погрузчиком',
			'desc'  => 'Земляные работы в Калининграде и области: траншеи, котлованы, планировка, погрузка грунта. Выполняю сам на своей технике. 3000 ₽/час, минимальный заказ 4 часа.',
		),

		'arenda-ekskavatora' => array(
			'title' => 'Аренда экскаватора в Калининграде с оператором | ЭКСКАВАТОР 39',
			'desc'  => 'Аренда экскаватора-погрузчика с оператором в Калининграде и области. Копка, планировка, погрузка грунта. 3000 ₽/час, минимальный заказ 4 часа, без посредников.',
		),

		'arenda-ekskavatora-pogruzchika' => array(
			'title' => 'Заказать экскаватор-погрузчик с оператором в Калининграде',
			'desc'  => 'Экскаватор-погрузчик с оператором в Калининграде: какие задачи берёт машина и где её возможности заканчиваются. 3000 ₽/час, минимальный заказ 4 часа.',
		),

		'kopka-transhey' => array(
			'title' => 'Копка траншей экскаватором в Калининграде — цена и заказ',
			'desc'  => 'Копка траншей под водопровод, канализацию, дренаж и кабель в Калининграде и области. Ставка 3000 ₽/час, минимальный заказ 4 часа. От чего зависит цена.',
		),

		'planirovka-uchastka' => array(
			'title' => 'Планировка участка экскаватором-погрузчиком в Калининграде',
			'desc'  => 'Планировка и выравнивание грунта на участке экскаватором-погрузчиком в Калининграде. Не проектирование, а работа техникой по месту. 3000 ₽/час, минимум 4 часа.',
		),

		'kotlovan-pod-fundament' => array(
			'title' => 'Копка котлована под фундамент в Калининграде',
			'desc'  => 'Выемка грунта под фундамент дома, бани или гаража экскаватором-погрузчиком в Калининграде и области. Ставка 3000 ₽/час, минимальный заказ 4 часа.',
		),

		'pogruzka-grunta' => array(
			'title' => 'Погрузка и перемещение грунта экскаватором в Калининграде',
			'desc'  => 'Погрузка и перемещение грунта, песка и щебня экскаватором-погрузчиком в Калининграде и области. Ставка 3000 ₽/час, минимальный заказ 4 часа.',
		),

		'obratnaya-zasypka' => array(
			'title' => 'Обратная засыпка траншей и котлованов в Калининграде',
			'desc'  => 'Обратная засыпка траншей и пазух котлована после укладки коммуникаций — экскаватором-погрузчиком в Калининграде. 3000 ₽/час, минимальный заказ 4 часа.',
		),

		'tseny' => array(
			'title' => 'Цены на экскаватор-погрузчик в Калининграде | ЭКСКАВАТОР 39',
			'desc'  => 'Ставка 3000 ₽ за час работы, минимальный заказ 4 часа, подача от 3500 ₽ по Калининграду. Разбор, от чего зависит итоговая стоимость земляных работ.',
		),

		'tehnika' => array(
			'title' => 'Экскаватор-погрузчик: характеристики техники | ЭКСКАВАТОР 39',
			'desc'  => 'Какая техника приедет на объект: экскаватор-погрузчик с передним ковшом и обратной лопатой. Характеристики, возможности и фотографии машины в работе.',
		),

		'foto-rabot' => array(
			'title' => 'Фотографии работ экскаватора-погрузчика в Калининграде',
			'desc'  => 'Реальные фото с объектов: траншеи, дренажные канавы, планировка и расчистка участков в Калининграде и области. Снимки сделаны во время работы.',
		),

		'rayony-vyezda' => array(
			'title' => 'Выезд экскаватора-погрузчика по Калининграду и области',
			'desc'  => 'Куда выезжает экскаватор-погрузчик и сколько стоит подача: от 3500 ₽ по Калининграду, 5000 ₽ за городом до 15 км. Условия по адресу — при обращении.',
		),

		// — Раздел «Полезное» —
		'blog' => array(
			'title' => 'Полезное о земляных работах | ЭКСКАВАТОР 39',
			'desc'  => 'Разборы вопросов, которые задают перед заказом техники: как считается стоимость, сколько выйдет грунта, что подготовить к приезду экскаватора.',
		),

		'ot-chego-zavisit-stoimost-kopki-transhei' => array(
			'title' => 'От чего зависит стоимость копки траншеи',
			'desc'  => 'Почему цены за метр траншеи не существует и из чего складывается сумма: грунт, доступ, глубина, коммуникации. Ставка 3000 ₽/час, минимум 4 часа.',
		),

		'kak-rasschitat-obem-grunta-v-transhee' => array(
			'title' => 'Как рассчитать объём грунта в траншее — формула',
			'desc'  => 'Формула объёма траншеи с примером расчёта и честными оговорками: почему вынутого грунта на поверхности всегда оказывается больше расчётного.',
		),

		'kak-rasschitat-obem-kotlovana' => array(
			'title' => 'Как рассчитать объём котлована — примерная формула',
			'desc'  => 'Прикидка объёма грунта в котловане под фундамент: формула, пример, влияние откосов и рельефа и когда без проекта не обойтись.',
		),

		'kak-podgotovit-uchastok-k-priezdu-ekskavatora' => array(
			'title' => 'Подготовка участка к приезду экскаватора-погрузчика',
			'desc'  => 'Чек-лист перед вызовом техники: подъезд, разметка границ, коммуникации, место под грунт и фото участка. Полчаса подготовки экономят час работы.',
		),

		'kontakty' => array(
			'title' => 'Заказать экскаватор-погрузчик в Калининграде | ЭКСКАВАТОР 39',
			'desc'  => 'Телефон и Telegram для заказа экскаватора-погрузчика с оператором. Калининград и область, ежедневно 08:00–20:00. Отвечаю лично, без диспетчеров.',
		),
	);
}

/**
 * Данные для текущей страницы или null, если страницы нет в списке.
 */
function kopka39_seo_current() {
	$map = kopka39_seo_data();

	if ( is_front_page() ) {
		return $map['_front'];
	}

	if ( is_page() ) {
		$slug = get_post_field( 'post_name', get_queried_object_id() );
		if ( $slug && isset( $map[ $slug ] ) ) {
			return $map[ $slug ];
		}
	}

	return null;
}

/**
 * Заголовок вкладки. Возврат непустой строки отменяет сборку
 * заголовка средствами WordPress — название сайта не дописывается,
 * оно уже заложено в наши тексты там, где нужно.
 */
function kopka39_seo_title( $title ) {
	$data = kopka39_seo_current();
	if ( $data && ! empty( $data['title'] ) ) {
		return $data['title'];
	}
	return $title;
}
add_filter( 'pre_get_document_title', 'kopka39_seo_title' );

/**
 * Описание страницы для сниппета в выдаче.
 */
function kopka39_seo_description() {
	$data = kopka39_seo_current();
	if ( ! $data || empty( $data['desc'] ) ) {
		return;
	}
	echo '<meta name="description" content="' . esc_attr( $data['desc'] ) . '">' . "\n";
}
add_action( 'wp_head', 'kopka39_seo_description', 1 );

/* ============================================================
 * РАЗМЕТКА ОРГАНИЗАЦИИ (Schema.org LocalBusiness)
 *
 * Выводится на всех страницах сайта. Все значения — реальные,
 * из inc/site-data.php. Ничего не выдумываем: рейтингов, отзывов,
 * юридических реквизитов и адреса офиса здесь нет, потому что
 * офиса нет, а реквизиты владелец пока не подтвердил.
 * ============================================================ */

function kopka39_business_schema() {
	if ( is_404() || is_search() ) {
		return;
	}

	$home = home_url( '/' );
	$img  = get_template_directory_uri() . '/img/ekskavator-pogruzchik-tehnika.jpg';

	$schema = array(
		'@context'    => 'https://schema.org',
		'@type'       => 'HomeAndConstructionBusiness',
		'@id'         => $home . '#business',
		'name'        => 'ЭКСКАВАТОР 39',
		'description' => 'Земляные работы экскаватором-погрузчиком с оператором в Калининграде и Калининградской области: копка траншей и котлованов, планировка участка, погрузка и перемещение грунта, обратная засыпка.',
		'url'         => $home,
		'image'       => $img,
		'logo'        => get_template_directory_uri() . '/img/logo.png',
		'telephone'   => kopka_d( 'phone_raw' ),
		'priceRange'  => '3000 ₽/час',
		'address'     => array(
			'@type'           => 'PostalAddress',
			'addressLocality' => 'Калининград',
			'addressRegion'   => 'Калининградская область',
			'addressCountry'  => 'RU',
		),
		'areaServed'  => array(
			array( '@type' => 'City',  'name' => 'Калининград' ),
			array( '@type' => 'State', 'name' => 'Калининградская область' ),
		),
		'openingHoursSpecification' => array(
			array(
				'@type'     => 'OpeningHoursSpecification',
				'dayOfWeek' => array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday' ),
				'opens'     => '08:00',
				'closes'    => '20:00',
			),
		),
		'makesOffer'  => array(
			array(
				'@type'       => 'Offer',
				'name'        => 'Работа экскаватора-погрузчика с оператором',
				'description' => 'Почасовая оплата, минимальный заказ — 4 часа. Подача техники оплачивается отдельно.',
				'priceSpecification' => array(
					'@type'         => 'UnitPriceSpecification',
					'price'         => '3000',
					'priceCurrency' => 'RUB',
					'unitCode'      => 'HUR',
					'unitText'      => 'час',
				),
			),
		),
	);

	$tg = kopka_d( 'telegram' );
	if ( ! empty( $tg ) ) {
		$schema['sameAs'] = array( $tg );
	}

	echo '<script type="application/ld+json">'
		. wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES )
		. '</script>' . "\n";
}
add_action( 'wp_head', 'kopka39_business_schema', 5 );

/* ============================================================
 * ЧАСТЫЕ ВОПРОСЫ
 *
 * Единый источник: и видимый блок на главной, и разметка
 * Schema.org берут вопросы отсюда. Так текст на странице и текст
 * в разметке гарантированно совпадают — это требование Google
 * и Яндекса, за расхождение можно получить санкции.
 * ============================================================ */

function kopka39_faq_list() {
	$price = kopka_d( 'price_hour' );
	$min   = kopka_d( 'min_order' );

	return array(

		// — Цена и оплата —
		'Сколько стоит час работы экскаватора-погрузчика?' =>
			'Ставка — ' . $price . ', минимальный заказ — ' . $min . '. Подача техники считается отдельно и зависит от адреса. Итог зависит от объёма, грунта и доступа на участок: пришлите фото и размеры — назову ориентир по времени.',

		'Почему минимальный заказ — 4 часа?' =>
			'Выезд состоит не только из копки: техника идёт на объект и обратно своим ходом, встаёт на позицию, на месте мы уточняем задачу. Поэтому рабочий день делится на выезды, а не на отдельные часы, и заказ короче четырёх часов взять не получится, не подводя других клиентов на ту же дату.',

		'Сколько стоит подача техники?' =>
			'По Калининграду — от 3500 ₽, за городом в пределах 15 км — 5000 ₽, дальше 15 км — по договорённости. Точную сумму по вашему адресу называю при обращении: она зависит от расстояния, состояния дороги и загрузки на выбранную дату.',

		'Почему нельзя назвать цену за метр траншеи?' =>
			'Потому что две одинаковые по размерам траншеи в сухом песке и в плотной глине с камнями копаются за разное время. В фиксированной цене за метр эта разница закладывается с запасом, и на лёгком грунте заказчик переплачивает за условия, которых у него нет.',

		'Как оплачивается работа?' =>
			'Расчёт по фактически отработанному на объекте времени плюс подача техники. Способы оплаты уточняйте при обращении.',

		'Есть ли скидки за большой объём работ?' =>
			'При большом объёме условия обсуждаются индивидуально — уточните при обращении. Заранее обещать скидку, не зная объекта, я не буду.',

		'Оплата идёт с момента приезда или с начала работы?' =>
			'С начала работы на объекте. Дорога до участка оплачивается отдельно как подача техники — она не входит в почасовую ставку.',

		// — Техника и её возможности —
		'Вы работаете один или это компания?' =>
			'Один. У меня одна единица техники, и я сам на ней работаю. Все вопросы решаются напрямую со мной, без диспетчеров и посредников.',

		'Экскаватор-погрузчик — это трактор с ковшом?' =>
			'В быту его действительно часто так называют. Формально это колёсная машина с копающим оборудованием сзади и погрузочным ковшом спереди — то есть экскаватор и погрузчик в одном.',

		'На какую глубину можно выкопать траншею?' =>
			'Достижимая глубина зависит от возможностей машины, типа грунта, устойчивости стенок и доступа на участок. Назовите нужную глубину при обращении — скажу сразу, реально ли её взять этой техникой.',

		'Пройдёт ли техника в узкие ворота?' =>
			'Зависит от ширины проезда и места для разворота. Пришлите фотографию ворот и подъезда — по снимку почти всегда видно, зайдёт машина или нет. Это лучше выяснить до выезда, чем на месте.',

		'Работаете в стеснённых условиях — рядом с забором и постройками?' =>
			'Да, такие задачи беру. Работа идёт медленнее и аккуратнее, это влияет на время. Заранее пришлите фото места работ, чтобы я оценил, хватит ли техника манёвра.',

		'Можно ли заказать несколько видов работ за один выезд?' =>
			'Да, и это выгоднее: подача оплачивается один раз. Частая комбинация — выкопать траншею, а после укладки коммуникаций сделать обратную засыпку и разровнять участок.',

		// — Границы услуги —
		'Укладываете трубы и монтируете канализацию?' =>
			'Нет. Я выполняю копку и, при необходимости, обратную засыпку. Монтаж коммуникаций выполняют ваши специалисты — это их зона ответственности и гарантии.',

		'Вывозите грунт?' =>
			'Своим транспортом вывоз не выполняю. Грунт могу переместить по участку, распределить или сложить в отвал под последующий вывоз. Если нужен именно вывоз самосвалом — это отдельная услуга, её нужно согласовывать заранее.',

		'Есть ли гидромолот, бурение, снегоуборка, демонтаж?' =>
			'Это отдельные услуги, и без подтверждения я их не обещаю. Если задача выходит за возможности техники, скажу об этом сразу, а не после приезда.',

		// — Организация работ —
		'Что нужно подготовить к приезду техники?' =>
			'Освободить проезд и место работ, обозначить границы копки колышками или краской, выбрать место под отвал грунта и обязательно сообщить о действующих подземных коммуникациях.',

		'Что делать, если я не знаю, где проходят коммуникации?' =>
			'Скажите об этом прямо. В таких местах я иду медленнее и осторожнее, а последние сантиметры у предполагаемой трассы дорабатываются вручную. Расположение действующих сетей знает только собственник участка — из кабины их не видно.',

		'Что прислать, чтобы получить расчёт?' =>
			'Что нужно сделать, примерные размеры, адрес или район, три-четыре фотографии участка с подъездом и воротами, желаемую дату и тип грунта, если знаете. Этого достаточно для ориентира по времени.',

		'Как быстро можете приехать?' =>
			'Зависит от текущей загрузки. Точную ближайшую дату назову при звонке — заранее писать её на сайте было бы нечестно.',

		'Работаете по выходным и в какое время?' =>
			'На связи ежедневно с 08:00 до 20:00, выходные не исключение. Время начала работ на объекте согласуем отдельно.',

		'Работаете с юридическими лицами?' =>
			'Формат работы и документов обсуждаем при обращении — расскажите о задаче, и я скажу, чем смогу быть полезен.',

		'Можно ли работать зимой или после дождя?' =>
			'Часто можно, но по сырому и промёрзшему грунту техника идёт тяжелее, а это влияет на время работы. Оценю по фотографиям участка и погоде на выбранную дату.',
	);
}

/**
 * Видимый блок вопросов на главной.
 */
function kopka39_render_faq() {
	foreach ( kopka39_faq_list() as $q => $a ) {
		echo "\n" . '    <div class="fse-faq-item fse-accordion-item">' . "\n"
			. '      <details class="fse-faq-details">' . "\n"
			. '        <summary class="fse-heading">' . esc_html( $q ) . '</summary>' . "\n"
			. '        <div class="fse-faq-answer"><p class="fse-paragraph">' . esc_html( $a ) . '</p></div>' . "\n"
			. '      </details>' . "\n"
			. '    </div>' . "\n";
	}
}

/**
 * Разметка FAQPage. На главной берётся из массива выше, на остальных
 * страницах — вытаскивается из их же содержимого, поэтому разметка
 * всегда соответствует тому, что видит посетитель.
 */
function kopka39_collect_faq_pairs() {
	if ( is_front_page() ) {
		return kopka39_faq_list();
	}

	if ( ! is_singular() ) {
		return array();
	}

	$content = get_post_field( 'post_content', get_queried_object_id() );
	if ( ! $content ) {
		return array();
	}

	$pairs = array();
	if ( preg_match_all(
		'#<summary[^>]*>(.*?)</summary>.*?<div class="fse-faq-answer">(.*?)</div>#s',
		$content, $matches, PREG_SET_ORDER
	) ) {
		foreach ( $matches as $m ) {
			$q = trim( wp_strip_all_tags( $m[1] ) );
			$a = trim( wp_strip_all_tags( $m[2] ) );
			if ( $q !== '' && $a !== '' ) {
				$pairs[ $q ] = $a;
			}
		}
	}

	return $pairs;
}

function kopka39_faq_schema() {
	$pairs = kopka39_collect_faq_pairs();
	if ( count( $pairs ) < 2 ) {
		return;
	}

	$items = array();
	foreach ( $pairs as $q => $a ) {
		$items[] = array(
			'@type'          => 'Question',
			'name'           => $q,
			'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $a ),
		);
	}

	$schema = array(
		'@context'   => 'https://schema.org',
		'@type'      => 'FAQPage',
		'mainEntity' => $items,
	);

	echo '<script type="application/ld+json">'
		. wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES )
		. '</script>' . "\n";
}
add_action( 'wp_head', 'kopka39_faq_schema', 6 );

/* ============================================================
 * ХЛЕБНЫЕ КРОШКИ
 *
 * Видимая навигация + разметка BreadcrumbList из одного источника.
 * Страницы услуг физически лежат в корне сайта, но логически
 * относятся к разделу «Услуги» (/zemlyanye-raboty/) — этот случай
 * описан картой ниже. Для статей блога цепочка строится
 * автоматически по родительской странице.
 * ============================================================ */

function kopka39_breadcrumb_parent_map() {
	return array(
		'arenda-ekskavatora'             => 'zemlyanye-raboty',
		'arenda-ekskavatora-pogruzchika' => 'zemlyanye-raboty',
		'kopka-transhey'                 => 'zemlyanye-raboty',
		'planirovka-uchastka'            => 'zemlyanye-raboty',
		'kotlovan-pod-fundament'         => 'zemlyanye-raboty',
		'pogruzka-grunta'                => 'zemlyanye-raboty',
		'obratnaya-zasypka'              => 'zemlyanye-raboty',
	);
}

/**
 * Короткие названия для крошек — в цепочке длинный H1 не нужен.
 */
function kopka39_breadcrumb_titles() {
	return array(
		'zemlyanye-raboty'                              => 'Услуги',
		'arenda-ekskavatora'                            => 'Аренда экскаватора',
		'arenda-ekskavatora-pogruzchika'                => 'Экскаватор-погрузчик с оператором',
		'kopka-transhey'                                => 'Копка траншей',
		'planirovka-uchastka'                           => 'Планировка участка',
		'kotlovan-pod-fundament'                        => 'Котлован под фундамент',
		'pogruzka-grunta'                               => 'Погрузка грунта',
		'obratnaya-zasypka'                             => 'Обратная засыпка',
		'tseny'                                         => 'Цены',
		'tehnika'                                       => 'Техника',
		'foto-rabot'                                    => 'Фото работ',
		'rayony-vyezda'                                 => 'Районы выезда',
		'kontakty'                                      => 'Контакты',
		'blog'                                          => 'Полезное',
		'ot-chego-zavisit-stoimost-kopki-transhei'      => 'Стоимость копки траншеи',
		'kak-rasschitat-obem-grunta-v-transhee'         => 'Объём грунта в траншее',
		'kak-rasschitat-obem-kotlovana'                 => 'Объём котлована',
		'kak-podgotovit-uchastok-k-priezdu-ekskavatora' => 'Подготовка участка',
	);
}

/**
 * Цепочка вида [ ['name' => …, 'url' => …], … ].
 * Пустой массив — крошки не нужны (главная, поиск).
 */
function kopka39_breadcrumb_items() {
	if ( is_front_page() || is_search() ) {
		return array();
	}

	$items = array( array( 'name' => 'Главная', 'url' => home_url( '/' ) ) );

	if ( is_404() ) {
		$items[] = array( 'name' => 'Страница не найдена', 'url' => '' );
		return $items;
	}

	if ( ! is_page() ) {
		return array();
	}

	$id     = get_queried_object_id();
	$slug   = get_post_field( 'post_name', $id );
	$titles = kopka39_breadcrumb_titles();
	$map    = kopka39_breadcrumb_parent_map();

	$chain = array();

	// Родитель из карты разделов (услуги) …
	if ( isset( $map[ $slug ] ) ) {
		$p_slug = $map[ $slug ];
		$parent = get_page_by_path( $p_slug );
		if ( $parent ) {
			$chain[] = array(
				'name' => isset( $titles[ $p_slug ] ) ? $titles[ $p_slug ] : get_the_title( $parent ),
				'url'  => get_permalink( $parent ),
			);
		}
	} else {
		// … либо реальная иерархия страниц (статьи блога).
		$ancestors = array_reverse( get_post_ancestors( $id ) );
		foreach ( $ancestors as $anc_id ) {
			$anc_slug = get_post_field( 'post_name', $anc_id );
			$chain[]  = array(
				'name' => isset( $titles[ $anc_slug ] ) ? $titles[ $anc_slug ] : get_the_title( $anc_id ),
				'url'  => get_permalink( $anc_id ),
			);
		}
	}

	$items = array_merge( $items, $chain );

	$items[] = array(
		'name' => isset( $titles[ $slug ] ) ? $titles[ $slug ] : get_the_title( $id ),
		'url'  => get_permalink( $id ),
	);

	return $items;
}

/**
 * Видимые крошки. Последний элемент — текущая страница, без ссылки.
 */
function kopka39_render_breadcrumbs() {
	$items = kopka39_breadcrumb_items();
	if ( count( $items ) < 2 ) {
		return;
	}

	$last = count( $items ) - 1;

	echo '<nav class="fse-breadcrumbs" aria-label="Вы находитесь здесь"><div class="fse-breadcrumbs-inner"><ol>';
	foreach ( $items as $i => $item ) {
		if ( $i === $last || empty( $item['url'] ) ) {
			echo '<li><span aria-current="page">' . esc_html( $item['name'] ) . '</span></li>';
		} else {
			echo '<li><a href="' . esc_url( $item['url'] ) . '">' . esc_html( $item['name'] ) . '</a></li>';
		}
	}
	echo '</ol></div></nav>' . "\n";
}

/**
 * Разметка BreadcrumbList — из той же цепочки, что и видимые крошки.
 */
function kopka39_breadcrumb_schema() {
	if ( is_404() ) {
		return;
	}

	$items = kopka39_breadcrumb_items();
	if ( count( $items ) < 2 ) {
		return;
	}

	$list = array();
	foreach ( $items as $i => $item ) {
		$entry = array(
			'@type'    => 'ListItem',
			'position' => $i + 1,
			'name'     => $item['name'],
		);
		if ( ! empty( $item['url'] ) ) {
			$entry['item'] = $item['url'];
		}
		$list[] = $entry;
	}

	$schema = array(
		'@context'        => 'https://schema.org',
		'@type'           => 'BreadcrumbList',
		'itemListElement' => $list,
	);

	echo '<script type="application/ld+json">'
		. wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES )
		. '</script>' . "\n";
}
add_action( 'wp_head', 'kopka39_breadcrumb_schema', 7 );


/* ============================================================
 * Вариант «3D»: тёмная тема + псевдо-3D первый экран.
 * Откат — закомментировать add_action ниже.
 * ============================================================ */
require_once get_template_directory() . '/inc/scenes.php';
require_once get_template_directory() . '/inc/scenes-extra.php';
require_once get_template_directory() . '/inc/phone-guard.php';
require_once get_template_directory() . '/inc/placeholders.php';
require_once get_template_directory() . '/inc/mobile-menu.php';

function kopka3d_enqueue() {
	$dir = get_template_directory();
	$uri = get_template_directory_uri();
	wp_enqueue_style( 'kopka3d-fonts', 'https://fonts.googleapis.com/css2?family=Unbounded:wght@600;700;800&display=swap', array(), null );
	wp_enqueue_style( 'kopka3d', $uri . '/css/kopka3d.css', array( 'kopka39-custom' ), filemtime( $dir . '/css/kopka3d.css' ) );
	wp_enqueue_style( 'kopka3d-scenes', $uri . '/css/k3-scenes.css', array( 'kopka3d' ), filemtime( $dir . '/css/k3-scenes.css' ) );
	// Сцены (первый экран, цены, выезд, контакты, 404) и общие 3D-эффекты.
	wp_enqueue_script( 'kopka3d-scenes', $uri . '/js/k3-scenes.js', array(), filemtime( $dir . '/js/k3-scenes.js' ), true );
	wp_enqueue_script( 'kopka3d-fx', $uri . '/js/k3-fx.js', array(), filemtime( $dir . '/js/k3-fx.js' ), true );
	// Номер телефона раскрывается при наведении / нажатии (inc/phone-guard.php).
	wp_enqueue_script( 'kopka39-phone', $uri . '/js/k3-phone.js', array(), filemtime( $dir . '/js/k3-phone.js' ), true );
}
add_action( 'wp_enqueue_scripts', 'kopka3d_enqueue', 30 );
