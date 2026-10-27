<?php
/**
 * The header template - AI-designed header with Customizer support
 *
 * @package kopka
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="https://gmpg.org/xfn/11">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'kopka' ); ?></a>

<?php if ( get_theme_mod( 'pressmegpt_override_header', true ) ) : ?>
<!-- AI-designed header from PressMeGPT with dynamic menu -->
<div class="theme-designer-header">
    <?php echo kopka_inject_dynamic_menu( get_theme_mod( 'pressmegpt_custom_header_html', '<header class="fse-section fse-header fse-header-sticky" data-pmgpt-header-rebuilt="1"><div class="fse-header-left"><div class="fse-logo-area"><img src="https://placehold.co/200x60/EEE/999?text=Logo" alt="kopka Logo" class="fse-image fse-logo"><span class="fse-site-title">kopka</span></div></div><div class="fse-header-right"><nav class="fse-main-nav pmgpt-menu-inline" aria-label="Primary"><ul class="fse-nav-menu fse-nav-menu"><li><a href="/">Home</a></li></ul></nav><span class="fse-toggle-button pmgpt-menu-toggle" id="pmgpt-menu-toggle" role="button" tabindex="0" aria-label="Menu" aria-controls="pmgpt-menu-panel" aria-expanded="false"><svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path d="M3 6h18M3 12h18M3 18h18" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/></svg></span><div class="fse-header-actions"><a href="tel:+79000000000" class="fse-button fse-button-primary fse-button-phone">+7 (900) 000-00-00</a></div></div></header><style data-pmgpt-overlay-injected>
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
})();</script>' ), 'primary' ); ?>
</div>
<?php else : ?>
<header id="masthead" class="site-header">
    <div class="header-inner">
        <div class="site-branding">
            <?php
            if ( has_custom_logo() ) :
                the_custom_logo();
            else :
                ?>
                <?php if ( get_theme_mod( 'kopka_show_site_title', false ) ) : ?>
                    <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                <?php endif; ?>
                <?php if ( get_theme_mod( 'kopka_show_site_description', false ) ) : ?>
                    <p class="site-description"><?php bloginfo( 'description' ); ?></p>
                <?php endif; ?>
                <?php
            endif;
            ?>
        </div>

        <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
            <span class="screen-reader-text"><?php esc_html_e( 'Menu', 'kopka' ); ?></span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>

        <nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'kopka' ); ?>">
            <?php
            wp_nav_menu( array(
                'theme_location' => 'primary',
                'menu_id'        => 'primary-menu',
                'depth'          => 2,
                'fallback_cb'    => false,
            ) );
            ?>
        </nav>
    </div>
</header>
<?php endif; ?>

<main id="primary" class="site-main">
