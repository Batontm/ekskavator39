<?php
/**
 * kopka Theme Customizer
 *
 * @package kopka
 */

if ( ! defined( 'ABSPATH' ) ) {
exit;
}

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function kopka_customize_register( $wp_customize ) {

/* ========================================
 * PRESSMEGPT AI EDITOR SECTION (at top)
 * ======================================== */
$wp_customize->add_section( 'kopka_ai_editor', array(
    'title'       => __( '🎨 Edit with PressMeGPT', 'kopka' ),
    'description' => sprintf(
        '<div style="text-align: center; padding: 15px 0;">
            <p style="font-size: 14px; color: #666; margin-bottom: 15px;">Your website design is managed by PressMeGPT AI.</p>
            <a href="%s" target="_blank" class="button button-primary" style="display: inline-block; font-size: 14px; padding: 8px 20px;">Open AI Editor →</a>
            <p style="font-size: 12px; color: #999; margin-top: 12px;">Use natural language to change colors, fonts, images, text, and layout.</p>
        </div>',
        esc_url( PRESSMEGPT_EDIT_URL )
    ),
    'priority'    => 1,
) );

// Dummy setting so section appears
$wp_customize->add_setting( 'kopka_ai_placeholder', array(
    'default'           => '',
    'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'kopka_ai_placeholder', array(
    'section' => 'kopka_ai_editor',
    'type'    => 'hidden',
) );

// Enable selective refresh for built-in settings
$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

// Add selective refresh for site title and description
if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'blogname', array(
        'selector'        => '.site-title a',
        'render_callback' => function() {
            bloginfo( 'name' );
        },
    ) );
    $wp_customize->selective_refresh->add_partial( 'blogdescription', array(
        'selector'        => '.site-description',
        'render_callback' => function() {
            bloginfo( 'description' );
        },
    ) );
}

/* ========================================
 * GLOBAL COLORS SECTION
 * ======================================== */
$wp_customize->add_section( 'kopka_global_colors', array(
    'title'       => __( 'Global Colors', 'kopka' ),
    'description' => __( 'Set the global color scheme for your theme.', 'kopka' ),
    'priority'    => 25,
) );

// Primary Color
$wp_customize->add_setting( 'kopka_primary_color', array(
    'default'           => '#FFC107',
    'sanitize_callback' => 'sanitize_hex_color',
    'transport'         => 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'kopka_primary_color', array(
    'label'   => __( 'Primary Color', 'kopka' ),
    'description' => __( 'Main brand color for buttons and accents.', 'kopka' ),
    'section' => 'kopka_global_colors',
) ) );

// Secondary Color
$wp_customize->add_setting( 'kopka_secondary_color', array(
    'default'           => '#6C757D',
    'sanitize_callback' => 'sanitize_hex_color',
    'transport'         => 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'kopka_secondary_color', array(
    'label'   => __( 'Secondary Color', 'kopka' ),
    'description' => __( 'Complementary color for highlights.', 'kopka' ),
    'section' => 'kopka_global_colors',
) ) );

// Background Color
$wp_customize->add_setting( 'kopka_background_color', array(
    'default'           => '#F8F9FA',
    'sanitize_callback' => 'sanitize_hex_color',
    'transport'         => 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'kopka_background_color', array(
    'label'   => __( 'Background Color', 'kopka' ),
    'section' => 'kopka_global_colors',
) ) );

// Text Color
$wp_customize->add_setting( 'kopka_text_color', array(
    'default'           => '#343A40',
    'sanitize_callback' => 'sanitize_hex_color',
    'transport'         => 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'kopka_text_color', array(
    'label'   => __( 'Text Color', 'kopka' ),
    'section' => 'kopka_global_colors',
) ) );

// Heading Color
$wp_customize->add_setting( 'kopka_heading_color', array(
    'default'           => '#212529',
    'sanitize_callback' => 'sanitize_hex_color',
    'transport'         => 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'kopka_heading_color', array(
    'label'   => __( 'Heading Color', 'kopka' ),
    'section' => 'kopka_global_colors',
) ) );

// Link Color
$wp_customize->add_setting( 'kopka_link_color', array(
    'default'           => 'var(--td-color-primary)',
    'sanitize_callback' => 'sanitize_hex_color',
    'transport'         => 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'kopka_link_color', array(
    'label'   => __( 'Link Color', 'kopka' ),
    'section' => 'kopka_global_colors',
) ) );

/* ========================================
 * HEADER OPTIONS SECTION
 * ======================================== */
$wp_customize->add_section( 'kopka_header_options', array(
    'title'       => __( 'Header Options', 'kopka' ),
    'description' => __( 'Customize your header layout, logo size, and colors. Hidden while AI-Designed Header is enabled.', 'kopka' ),
    'priority'    => 30,
    'active_callback' => function() {
        return ! get_theme_mod( 'pressmegpt_override_header', true );
    },
) );

// Header Layout
$wp_customize->add_setting( 'kopka_header_layout', array(
    'default'           => 'logo-left-menu-right',
    'sanitize_callback' => 'sanitize_text_field',
    'transport'         => 'postMessage',
) );

$wp_customize->add_control( 'kopka_header_layout', array(
    'label'    => __( 'Header Layout', 'kopka' ),
    'section'  => 'kopka_header_options',
    'type'     => 'select',
    'choices'  => array(
        'logo-left-menu-right'    => __( 'Logo Left, Menu Right', 'kopka' ),
        'logo-right-menu-left'    => __( 'Logo Right, Menu Left', 'kopka' ),
        'logo-center-menu-below'  => __( 'Logo Center, Menu Below', 'kopka' ),
        'logo-center-menu-split'  => __( 'Logo Center, Menu Split', 'kopka' ),
    ),
) );

// Logo Max Width
$wp_customize->add_setting( 'kopka_logo_max_width', array(
    'default'           => 200,
    'sanitize_callback' => 'absint',
    'transport'         => 'postMessage',
) );

$wp_customize->add_control( 'kopka_logo_max_width', array(
    'label'       => __( 'Logo Max Width (px)', 'kopka' ),
    'section'     => 'kopka_header_options',
    'type'        => 'range',
    'input_attrs' => array(
        'min'  => 50,
        'max'  => 500,
        'step' => 10,
    ),
) );

// Logo Max Height
$wp_customize->add_setting( 'kopka_logo_max_height', array(
    'default'           => 80,
    'sanitize_callback' => 'absint',
    'transport'         => 'postMessage',
) );

$wp_customize->add_control( 'kopka_logo_max_height', array(
    'label'       => __( 'Logo Max Height (px)', 'kopka' ),
    'section'     => 'kopka_header_options',
    'type'        => 'range',
    'input_attrs' => array(
        'min'  => 30,
        'max'  => 200,
        'step' => 5,
    ),
) );

// Header Background Color
$wp_customize->add_setting( 'kopka_header_bg_color', array(
    'default'           => '#ffffff',
    'sanitize_callback' => 'sanitize_hex_color',
    'transport'         => 'postMessage',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'kopka_header_bg_color', array(
    'label'    => __( 'Header Background Color', 'kopka' ),
    'section'  => 'kopka_header_options',
) ) );

// Header Text Color
$wp_customize->add_setting( 'kopka_header_text_color', array(
    'default'           => '#333333',
    'sanitize_callback' => 'sanitize_hex_color',
    'transport'         => 'postMessage',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'kopka_header_text_color', array(
    'label'    => __( 'Header Text/Link Color', 'kopka' ),
    'section'  => 'kopka_header_options',
) ) );

// Header Padding
$wp_customize->add_setting( 'kopka_header_padding', array(
    'default'           => 20,
    'sanitize_callback' => 'absint',
    'transport'         => 'postMessage',
) );

$wp_customize->add_control( 'kopka_header_padding', array(
    'label'       => __( 'Header Padding (px)', 'kopka' ),
    'section'     => 'kopka_header_options',
    'type'        => 'range',
    'input_attrs' => array(
        'min'  => 0,
        'max'  => 60,
        'step' => 5,
    ),
) );

// Show/Hide Site Title
$wp_customize->add_setting( 'kopka_show_site_title', array(
    'default'           => false,
    'sanitize_callback' => 'wp_validate_boolean',
    'transport'         => 'postMessage',
) );

$wp_customize->add_control( 'kopka_show_site_title', array(
    'label'   => __( 'Show Site Title (when no logo)', 'kopka' ),
    'section' => 'kopka_header_options',
    'type'    => 'checkbox',
) );

// Show/Hide Site Description
$wp_customize->add_setting( 'kopka_show_site_description', array(
    'default'           => false,
    'sanitize_callback' => 'wp_validate_boolean',
    'transport'         => 'postMessage',
) );

$wp_customize->add_control( 'kopka_show_site_description', array(
    'label'   => __( 'Show Site Tagline', 'kopka' ),
    'section' => 'kopka_header_options',
    'type'    => 'checkbox',
) );

/* ========================================
 * FOOTER OPTIONS SECTION
 * ======================================== */
$wp_customize->add_section( 'kopka_footer_options', array(
    'title'       => __( 'Footer Options', 'kopka' ),
    'description' => __( 'Customize your footer layout, text, and colors. Hidden while AI-Designed Footer is enabled.', 'kopka' ),
    'priority'    => 35,
    'active_callback' => function() {
        return ! get_theme_mod( 'pressmegpt_override_footer', true );
    },
) );

// Footer Layout
$wp_customize->add_setting( 'kopka_footer_layout', array(
    'default'           => 'centered',
    'sanitize_callback' => 'sanitize_text_field',
    'transport'         => 'postMessage',
) );

$wp_customize->add_control( 'kopka_footer_layout', array(
    'label'   => __( 'Footer Layout', 'kopka' ),
    'section' => 'kopka_footer_options',
    'type'    => 'select',
    'choices' => array(
        'centered'     => __( 'Centered', 'kopka' ),
        'left-aligned' => __( 'Left Aligned', 'kopka' ),
        'split'        => __( 'Split (Copyright Left, Menu Right)', 'kopka' ),
    ),
) );

// Footer Menu Position
$wp_customize->add_setting( 'kopka_footer_menu_position', array(
    'default'           => 'above',
    'sanitize_callback' => 'sanitize_text_field',
    'transport'         => 'postMessage',
) );

$wp_customize->add_control( 'kopka_footer_menu_position', array(
    'label'   => __( 'Footer Menu Position', 'kopka' ),
    'section' => 'kopka_footer_options',
    'type'    => 'select',
    'choices' => array(
        'above'  => __( 'Above Copyright Text', 'kopka' ),
        'below'  => __( 'Below Copyright Text', 'kopka' ),
        'hidden' => __( 'Hidden', 'kopka' ),
    ),
) );

// Footer Copyright Text
$wp_customize->add_setting( 'kopka_footer_text', array(
    'default'           => '',
    'sanitize_callback' => 'wp_kses_post',
    'transport'         => 'postMessage',
) );

$wp_customize->add_control( 'kopka_footer_text', array(
    'label'       => __( 'Footer Copyright Text', 'kopka' ),
    'description' => __( 'Leave empty for default: © [year] [site name]. All rights reserved.', 'kopka' ),
    'section'     => 'kopka_footer_options',
    'type'        => 'textarea',
) );

// Footer Background Color
$wp_customize->add_setting( 'kopka_footer_bg_color', array(
    'default'           => '#1a1a1a',
    'sanitize_callback' => 'sanitize_hex_color',
    'transport'         => 'postMessage',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'kopka_footer_bg_color', array(
    'label'   => __( 'Footer Background Color', 'kopka' ),
    'section' => 'kopka_footer_options',
) ) );

// Footer Text Color
$wp_customize->add_setting( 'kopka_footer_text_color', array(
    'default'           => '#ffffff',
    'sanitize_callback' => 'sanitize_hex_color',
    'transport'         => 'postMessage',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'kopka_footer_text_color', array(
    'label'   => __( 'Footer Text Color', 'kopka' ),
    'section' => 'kopka_footer_options',
) ) );

// Footer Link Color
$wp_customize->add_setting( 'kopka_footer_link_color', array(
    'default'           => '#ffffff',
    'sanitize_callback' => 'sanitize_hex_color',
    'transport'         => 'postMessage',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'kopka_footer_link_color', array(
    'label'   => __( 'Footer Link Color', 'kopka' ),
    'section' => 'kopka_footer_options',
) ) );

// Footer Padding
$wp_customize->add_setting( 'kopka_footer_padding', array(
    'default'           => 40,
    'sanitize_callback' => 'absint',
    'transport'         => 'postMessage',
) );

$wp_customize->add_control( 'kopka_footer_padding', array(
    'label'       => __( 'Footer Padding (px)', 'kopka' ),
    'section'     => 'kopka_footer_options',
    'type'        => 'range',
    'input_attrs' => array(
        'min'  => 10,
        'max'  => 100,
        'step' => 5,
    ),
) );

// Show Footer Widgets
$wp_customize->add_setting( 'kopka_show_footer_widgets', array(
    'default'           => false,
    'sanitize_callback' => 'wp_validate_boolean',
    'transport'         => 'refresh',
) );

$wp_customize->add_control( 'kopka_show_footer_widgets', array(
    'label'   => __( 'Show Footer Widget Areas', 'kopka' ),
    'section' => 'kopka_footer_options',
    'type'    => 'checkbox',
) );
/* ========================================
 * PressMeGPT design HTML sanitizer
 * wp_kses_post() strips <svg>/<path>/<style>, which silently deletes the
 * hamburger + drawer close icons from the designed header/footer. Allow the
 * inline SVG vocabulary the menu contract emits.
 * ======================================== */
if ( ! function_exists( 'kopka_sanitize_design_html' ) ) {
function kopka_sanitize_design_html( $value ) {
    $allowed = wp_kses_allowed_html( 'post' );
    $svg_attrs = array(
        'class' => true, 'id' => true, 'style' => true, 'role' => true, 'aria-hidden' => true,
        'aria-label' => true, 'focusable' => true, 'xmlns' => true, 'viewbox' => true,
        'width' => true, 'height' => true, 'fill' => true, 'stroke' => true,
        'stroke-width' => true, 'stroke-linecap' => true, 'stroke-linejoin' => true,
        'stroke-miterlimit' => true, 'd' => true, 'points' => true, 'transform' => true,
        'x' => true, 'y' => true, 'x1' => true, 'y1' => true, 'x2' => true, 'y2' => true,
        'cx' => true, 'cy' => true, 'r' => true, 'rx' => true, 'ry' => true,
        'opacity' => true, 'fill-rule' => true, 'clip-rule' => true, 'preserveaspectratio' => true,
    );
    foreach ( array( 'svg', 'g', 'path', 'line', 'polyline', 'polygon', 'rect', 'circle', 'ellipse', 'use', 'defs', 'title', 'symbol' ) as $tag ) {
        $allowed[ $tag ] = $svg_attrs;
    }
    $allowed['style'] = array( 'type' => true, 'media' => true );
    $allowed['button'] = array(
        'class' => true, 'id' => true, 'style' => true, 'type' => true,
        'aria-label' => true, 'aria-expanded' => true, 'aria-controls' => true, 'data-pmgpt' => true,
    );
    return wp_kses( $value, $allowed );
}
}

/* ========================================
 * PRESSMEGPT OPTIONS - Header/Footer Override
 * ======================================== */
$wp_customize->add_section( 'kopka_pressmegpt_options', array(
    'title'       => __( '⚡ PressMeGPT Options', 'kopka' ),
    'description' => __( 'Control whether to use the AI-designed header/footer or the standard WordPress Customizer header/footer.', 'kopka' ),
    'priority'    => 5,
) );

// --- HEADER SECTION ---
// Override Header checkbox (above the textarea)
$wp_customize->add_setting( 'pressmegpt_override_header', array(
    'default'           => true,
    'sanitize_callback' => 'wp_validate_boolean',
    'transport'         => 'refresh',
) );
$wp_customize->add_control( 'pressmegpt_override_header', array(
    'label'       => __( 'Use AI-Designed Header', 'kopka' ),
    'description' => __( 'When checked, the custom header HTML below is used. Uncheck to use the standard WordPress header (responds to Header Options above).', 'kopka' ),
    'section'     => 'kopka_pressmegpt_options',
    'type'        => 'checkbox',
    'priority'    => 10,
) );

// Custom Header HTML textarea (right below its checkbox)
$wp_customize->add_setting( 'pressmegpt_custom_header_html', array(
    'default'           => '<header class="fse-section fse-header fse-header-sticky" data-pmgpt-header-rebuilt="1"><div class="fse-header-left"><div class="fse-logo-area"><img src="https://placehold.co/200x60/EEE/999?text=Logo" alt="kopka Logo" class="fse-image fse-logo"><span class="fse-site-title">kopka</span></div></div><div class="fse-header-right"><nav class="fse-main-nav pmgpt-menu-inline" aria-label="Primary"><ul class="fse-nav-menu fse-nav-menu"><li><a href="/">Home</a></li></ul></nav><span class="fse-toggle-button pmgpt-menu-toggle" id="pmgpt-menu-toggle" role="button" tabindex="0" aria-label="Menu" aria-controls="pmgpt-menu-panel" aria-expanded="false"><svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path d="M3 6h18M3 12h18M3 18h18" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/></svg></span><div class="fse-header-actions"><a href="tel:+79000000000" class="fse-button fse-button-primary fse-button-phone">+7 (900) 000-00-00</a></div></div></header><style data-pmgpt-overlay-injected>
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
})();</script>',
    'sanitize_callback' => 'kopka_sanitize_design_html',
    'transport'         => 'refresh',
) );
$wp_customize->add_control( 'pressmegpt_custom_header_html', array(
    'label'       => __( 'Custom Header HTML', 'kopka' ),
    'description' => __( 'The AI-designed header HTML. You can edit this directly.', 'kopka' ),
    'section'     => 'kopka_pressmegpt_options',
    'type'        => 'textarea',
    'input_attrs' => array( 'rows' => 8 ),
    'priority'    => 11,
) );

// --- FOOTER SECTION ---
// Override Footer checkbox (above the textarea)
$wp_customize->add_setting( 'pressmegpt_override_footer', array(
    'default'           => true,
    'sanitize_callback' => 'wp_validate_boolean',
    'transport'         => 'refresh',
) );
$wp_customize->add_control( 'pressmegpt_override_footer', array(
    'label'       => __( 'Use AI-Designed Footer', 'kopka' ),
    'description' => __( 'When checked, the custom footer HTML below is used. Uncheck to use the standard WordPress footer (responds to Footer Options above).', 'kopka' ),
    'section'     => 'kopka_pressmegpt_options',
    'type'        => 'checkbox',
    'priority'    => 20,
) );

// Custom Footer HTML textarea (right below its checkbox)
$wp_customize->add_setting( 'pressmegpt_custom_footer_html', array(
    'default'           => '<footer class="fse-section fse-footer"><div class="fse-group fse-footer-content"><div class="fse-columns fse-footer-columns"><div class="fse-column fse-footer-about"><img src="https://placehold.co/200x60/EEE/999?text=Logo" alt="kopka Logo" class="fse-image fse-footer-logo"><p class="fse-paragraph">Индивидуальный предприниматель в сфере земляных работ. Экскаватор-погрузчик с оператором в Калининграде и области.</p></div><div class="fse-column fse-footer-links"><h4 class="fse-heading">Быстрые Ссылки</h4><ul class="fse-footer-menu"><li><a href="/">Home</a></li></ul></div><div class="fse-column fse-footer-contact"><h4 class="fse-heading">Связаться</h4><ul class="fse-contact-list"><li><i class="fas fa-map-marker-alt"></i> <span>Калининград и область</span></li><li><i class="fas fa-phone"></i> <span><a href="tel:+79000000000">+7 (900) 000-00-00</a></span></li><li><i class="fas fa-envelope"></i> <span><a href="mailto:info@kopka.ru">info@kopka.ru</a></span></li></ul></div></div><div class="fse-footer-bottom"><p class="fse-copyright">&copy; 2026 kopka. Все права защищены.</p></div></div></footer>',
    'sanitize_callback' => 'kopka_sanitize_design_html',
    'transport'         => 'refresh',
) );
$wp_customize->add_control( 'pressmegpt_custom_footer_html', array(
    'label'       => __( 'Custom Footer HTML', 'kopka' ),
    'description' => __( 'The AI-designed footer HTML. You can edit this directly.', 'kopka' ),
    'section'     => 'kopka_pressmegpt_options',
    'type'        => 'textarea',
    'input_attrs' => array( 'rows' => 8 ),
    'priority'    => 21,
) );

// --- MOBILE MENU BEHAVIOR ---
$wp_customize->add_setting( 'pressmegpt_mobile_menu', array(
    'default'           => 'responsive',
    'sanitize_callback' => 'sanitize_text_field',
    'transport'         => 'refresh',
) );
$wp_customize->add_control( 'pressmegpt_mobile_menu', array(
    'label'       => __( 'Mobile Menu Behavior', 'kopka' ),
    'description' => __( 'Controls when the hamburger menu icon is shown for the AI-designed header.', 'kopka' ),
    'section'     => 'kopka_pressmegpt_options',
    'type'        => 'radio',
    'choices'     => array(
        'responsive' => __( 'Responsive — inline on desktop, hamburger on mobile (Default)', 'kopka' ),
        'always'     => __( 'Always show hamburger menu', 'kopka' ),
        'never'      => __( 'Never show hamburger menu (always inline)', 'kopka' ),
    ),
    'priority'    => 15,
) );
}
add_action( 'customize_register', 'kopka_customize_register' );

/**
 * Add body class based on mobile menu setting
 */
function kopka_mobile_menu_body_class( $classes ) {
$mode = get_theme_mod( 'pressmegpt_mobile_menu', 'responsive' );
if ( $mode === 'always' ) {
    $classes[] = 'pmgpt-mobile-always';
} elseif ( $mode === 'never' ) {
    $classes[] = 'pmgpt-mobile-never';
}
// 'responsive' and legacy 'breakpoint' -> no body class (uses default responsive CSS)
return $classes;
}
add_filter( 'body_class', 'kopka_mobile_menu_body_class' );

/**
 * Add per-page body class (pmgpt-page-{slug}) so per-page CSS scoped in
 * style.css only applies on its target page. Hooks into the
 * _pressmegpt_page_slug post-meta written at import time.
 */
function kopka_pressmegpt_page_body_class( $classes ) {
if ( is_singular() ) {
    $slug = get_post_meta( get_the_ID(), '_pressmegpt_page_slug', true );
    if ( $slug ) {
        $classes[] = 'pmgpt-page-' . sanitize_html_class( $slug );
    }
}
return $classes;
}
add_filter( 'body_class', 'kopka_pressmegpt_page_body_class' );

/**
 * Disable wpautop / wptexturize on PressMeGPT-imported pages.
 * Their post_content is hand-authored HTML with full-bleed sections —
 * wpautop wraps stray newlines in <p></p> tags that appear as empty
 * paragraphs above the first section and break section layout.
 */
function kopka_pressmegpt_disable_wpautop( $content ) {
if ( is_singular() && get_post_meta( get_the_ID(), '_pressmegpt_page_slug', true ) ) {
    remove_filter( 'the_content', 'wpautop' );
    remove_filter( 'the_content', 'wptexturize' );
}
return $content;
}
add_filter( 'the_content', 'kopka_pressmegpt_disable_wpautop', 1 );

/**
 * Output customizer CSS
 */
function kopka_customizer_css() {
$header_layout      = get_theme_mod( 'kopka_header_layout', 'logo-left-menu-right' );
$logo_max_width     = get_theme_mod( 'kopka_logo_max_width', 200 );
$logo_max_height    = get_theme_mod( 'kopka_logo_max_height', 80 );
$header_bg          = get_theme_mod( 'kopka_header_bg_color', '#ffffff' );
$header_text        = get_theme_mod( 'kopka_header_text_color', '#333333' );
$header_padding     = get_theme_mod( 'kopka_header_padding', 20 );

$footer_bg          = get_theme_mod( 'kopka_footer_bg_color', '#1a1a1a' );
$footer_text        = get_theme_mod( 'kopka_footer_text_color', '#ffffff' );
$footer_link        = get_theme_mod( 'kopka_footer_link_color', '#ffffff' );
$footer_padding     = get_theme_mod( 'kopka_footer_padding', 40 );
?>
<style type="text/css" id="kopka-customizer-css">
    /* Header Customizer Styles */
    .site-header {
        background-color: <?php echo esc_attr( $header_bg ); ?>;
        color: <?php echo esc_attr( $header_text ); ?>;
        padding: <?php echo esc_attr( $header_padding ); ?>px 2rem;
    }
    
    .site-header a,
    .site-title a,
    .main-navigation a {
        color: <?php echo esc_attr( $header_text ); ?>;
    }
    
    .custom-logo {
        max-width: <?php echo esc_attr( $logo_max_width ); ?>px;
        max-height: <?php echo esc_attr( $logo_max_height ); ?>px;
        width: auto;
        height: auto;
    }
    
    /* Footer Customizer Styles */
    .site-footer {
        background-color: <?php echo esc_attr( $footer_bg ); ?>;
        color: <?php echo esc_attr( $footer_text ); ?>;
        padding: <?php echo esc_attr( $footer_padding ); ?>px 2rem;
    }
    
    .site-footer a,
    .footer-navigation a {
        color: <?php echo esc_attr( $footer_link ); ?>;
    }
</style>
<?php
}
add_action( 'wp_head', 'kopka_customizer_css' );

/**
 * Get footer copyright text
 */
function kopka_get_footer_text() {
$custom_text = get_theme_mod( 'kopka_footer_text', '' );

$pressmegpt_credit = ' | Powered by <a href="https://pressmegpt.com" target="_blank" rel="noopener">PressMeGPT.com</a>';

if ( ! empty( $custom_text ) ) {
    return $custom_text . $pressmegpt_credit;
}

return sprintf(
    '&copy; %s %s. %s',
    date( 'Y' ),
    get_bloginfo( 'name' ),
    __( 'All rights reserved.', 'kopka' )
) . $pressmegpt_credit;
}

/**
 * Replace static nav links in designed HTML with dynamic wp_nav_menu output.
 * Prioritises <nav> blocks, then falls back to the first <ul> with <li><a>.
 * Preserves surrounding markup and CSS classes.
 */
function kopka_inject_dynamic_menu( $html, $location = 'primary' ) {
if ( empty( $html ) || ! has_nav_menu( $location ) ) {
    return $html;
}

// Get the WordPress menu HTML (just the <li> items, no wrapper)
$menu_html = wp_nav_menu( array(
    'theme_location' => $location,
    'depth'          => $location === 'footer' ? 1 : 2,
    'container'      => false,
    'echo'           => false,
    'fallback_cb'    => false,
    'items_wrap'     => '%3$s',
) );

if ( empty( $menu_html ) ) {
    return $html;
}

// Strategy 1: find a <ul> inside a <nav> block first (most reliable)
if ( preg_match( '#<nav[^>]*>([sS]*?)</nav>#si', $html, $nav_match, PREG_OFFSET_CAPTURE ) ) {
    $nav_inner = $nav_match[1][0];
    if ( preg_match( '#<ul([^>]*)>(.*?<li[s>].*?)</ul>#si', $nav_inner, $ul_match, PREG_OFFSET_CAPTURE ) ) {
        $ul_attrs   = $ul_match[1][0];
        $old_ul     = $ul_match[0][0];
        $new_ul     = '<ul' . $ul_attrs . '>' . $menu_html . '</ul>';
        // Replace within the nav section (use the absolute offset in the full html)
        $abs_offset = $nav_match[1][1] + $ul_match[0][1];
        $html = substr_replace( $html, $new_ul, $abs_offset, strlen( $old_ul ) );
        return $html;
    }
}

// Strategy 2: fall back to the first <ul> containing <li> anywhere in the HTML
if ( preg_match( '#<ul([^>]*)>(.*?<li[s>].*?)</ul>#si', $html, $ul_match, PREG_OFFSET_CAPTURE ) ) {
    $ul_attrs    = $ul_match[1][0];
    $replacement = '<ul' . $ul_attrs . '>' . $menu_html . '</ul>';
    $html = substr_replace( $html, $replacement, $ul_match[0][1], strlen( $ul_match[0][0] ) );
}

return $html;
}
