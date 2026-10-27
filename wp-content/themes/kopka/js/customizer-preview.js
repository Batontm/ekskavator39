/**
 * File customizer-preview.js
 *
 * Theme Customizer enhancements for a better user experience.
 * Contains handlers for live preview of Customizer settings.
 */

( function( $ ) {
// Site title
wp.customize( 'blogname', function( value ) {
    value.bind( function( to ) {
        $( '.site-title a' ).text( to );
    } );
} );

// Site description
wp.customize( 'blogdescription', function( value ) {
    value.bind( function( to ) {
        $( '.site-description' ).text( to );
    } );
} );

// Header layout
wp.customize( 'kopka_header_layout', function( value ) {
    value.bind( function( to ) {
        var header = $( '.site-header' );
        header.removeClass( 'header-layout-logo-left-menu-right header-layout-logo-right-menu-left header-layout-logo-center-menu-below header-layout-logo-center-menu-split' );
        header.addClass( 'header-layout-' + to );
    } );
} );

// Logo max width
wp.customize( 'kopka_logo_max_width', function( value ) {
    value.bind( function( to ) {
        $( '.custom-logo' ).css( 'max-width', to + 'px' );
    } );
} );

// Logo max height
wp.customize( 'kopka_logo_max_height', function( value ) {
    value.bind( function( to ) {
        $( '.custom-logo' ).css( 'max-height', to + 'px' );
    } );
} );

// Header background color
wp.customize( 'kopka_header_bg_color', function( value ) {
    value.bind( function( to ) {
        $( '.site-header' ).css( 'background-color', to );
    } );
} );

// Header text color
wp.customize( 'kopka_header_text_color', function( value ) {
    value.bind( function( to ) {
        $( '.site-header, .site-header a, .site-title a, .main-navigation a' ).css( 'color', to );
    } );
} );

// Header padding
wp.customize( 'kopka_header_padding', function( value ) {
    value.bind( function( to ) {
        $( '.site-header' ).css( 'padding-top', to + 'px' ).css( 'padding-bottom', to + 'px' );
    } );
} );

// Footer layout
wp.customize( 'kopka_footer_layout', function( value ) {
    value.bind( function( to ) {
        var footer = $( '.site-footer' );
        footer.removeClass( 'footer-layout-centered footer-layout-left-aligned footer-layout-split' );
        footer.addClass( 'footer-layout-' + to );
    } );
} );

// Footer menu position
wp.customize( 'kopka_footer_menu_position', function( value ) {
    value.bind( function( to ) {
        var footer = $( '.site-footer' );
        footer.removeClass( 'footer-menu-above footer-menu-below footer-menu-hidden' );
        footer.addClass( 'footer-menu-' + to );
        
        if ( to === 'hidden' ) {
            $( '.footer-navigation' ).hide();
        } else {
            $( '.footer-navigation' ).show();
        }
    } );
} );

// Footer text
wp.customize( 'kopka_footer_text', function( value ) {
    value.bind( function( to ) {
        if ( to ) {
            $( '.site-info p' ).html( to );
        } else {
            var year = new Date().getFullYear();
            var siteName = wp.customize( 'blogname' ).get();
            $( '.site-info p' ).html( '&copy; ' + year + ' ' + siteName + '. All rights reserved.' );
        }
    } );
} );

// Footer background color
wp.customize( 'kopka_footer_bg_color', function( value ) {
    value.bind( function( to ) {
        $( '.site-footer' ).css( 'background-color', to );
    } );
} );

// Footer text color
wp.customize( 'kopka_footer_text_color', function( value ) {
    value.bind( function( to ) {
        $( '.site-footer, .site-info' ).css( 'color', to );
    } );
} );

// Footer link color
wp.customize( 'kopka_footer_link_color', function( value ) {
    value.bind( function( to ) {
        $( '.site-footer a, .footer-navigation a' ).css( 'color', to );
    } );
} );

// Footer padding
wp.customize( 'kopka_footer_padding', function( value ) {
    value.bind( function( to ) {
        $( '.site-footer' ).css( 'padding-top', to + 'px' ).css( 'padding-bottom', to + 'px' );
    } );
} );

} )( jQuery );
