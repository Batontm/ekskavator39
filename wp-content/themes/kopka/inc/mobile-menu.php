<?php
/**
 * Мобильное меню (выезжающая панель) — пункты из меню WordPress.
 *
 * Шапка приходит из theme_mod PressMeGPT. Для компьютера пункты меню
 * подставляет kopka_inject_dynamic_menu (inc/customizer.php), но в
 * мобильной панели ссылки лежат без <ul>, и там оставалась заглушка
 * «Home». Здесь панель заполняется тем же меню «primary»: разделы
 * верхнего уровня и вложенные пункты с отступом.
 *
 * @package kopka
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function kopka39_mobile_menu_links() {
	$locations = get_nav_menu_locations();
	if ( empty( $locations['primary'] ) ) {
		return '';
	}
	$items = wp_get_nav_menu_items( $locations['primary'] );
	if ( empty( $items ) ) {
		return '';
	}
	$by_parent = array();
	foreach ( $items as $item ) {
		$by_parent[ (int) $item->menu_item_parent ][] = $item;
	}
	$current = untrailingslashit( home_url( add_query_arg( array(), $GLOBALS['wp']->request ?? '' ) ) );
	$out     = '';
	$walk    = function ( $parent, $level ) use ( &$walk, &$out, $by_parent, $current ) {
		if ( empty( $by_parent[ $parent ] ) || $level > 1 ) {
			return;
		}
		foreach ( $by_parent[ $parent ] as $item ) {
			$url   = $item->url;
			$cls   = 'pmgpt-menu-link' . ( $level ? ' k3-sub' : '' );
			$attr  = '';
			if ( untrailingslashit( $url ) === $current ) {
				$cls  .= ' is-current';
				$attr .= ' aria-current="page"';
			}
			$out .= '<a class="' . esc_attr( $cls ) . '" href="' . esc_url( $url ) . '"' . $attr . '>' . esc_html( $item->title ) . '</a>';
			$walk( (int) $item->ID, $level + 1 );
		}
	};
	$walk( 0, 0 );
	return $out;
}

function kopka39_mobile_menu_fill( $html ) {
	if ( ! is_string( $html ) || false === strpos( $html, 'pmgpt-menu-links' ) || is_admin() ) {
		return $html;
	}
	$links = kopka39_mobile_menu_links();
	if ( '' === $links ) {
		return $html;
	}
	return preg_replace(
		'#(<nav class="pmgpt-menu-links"[^>]*>).*?(</nav>)#s',
		'$1' . str_replace( array( '\\', '$' ), array( '\\\\', '\$' ), $links ) . '$2',
		$html,
		1
	);
}
add_filter( 'theme_mod_pressmegpt_custom_header_html', 'kopka39_mobile_menu_fill', 20 );
