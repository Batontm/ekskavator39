<?php
/**
 * Защита номера телефона от сборщиков контактов.
 *
 * В HTML страницы номера в открытом виде нет: вместо него
 * «+7 (952) 110-••-••», а ссылки tel: заменены на href="#" с номером
 * в закодированном атрибуте data-k3p. Полный номер собирает
 * js/k3-phone.js: на компьютере — при наведении курсора или фокусе
 * с клавиатуры, на телефоне — по нажатию (сразу открывается звонок).
 *
 * Работает для всего вывода сайта (шаблоны, тексты страниц из базы,
 * шапка и футер), поэтому отдельные места править не нужно.
 *
 * Номер убран и из разметки schema.org (поле telephone): поисковики,
 * как и сборщики, читают её без наведения. Вернуть его туда —
 * поставить KOPKA39_PHONE_IN_SCHEMA в true.
 *
 * Отключить защиту целиком — закомментировать add_action ниже.
 *
 * @package kopka
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'KOPKA39_PHONE_IN_SCHEMA' ) ) {
	define( 'KOPKA39_PHONE_IN_SCHEMA', false );
}

/** Номер в закодированном виде: base64 от перевёрнутой строки. */
function kopka39_phone_code() {
	return base64_encode( strrev( kopka_d( 'phone_raw' ) ) );
}

/** «+7 (952) 110-06-09» → «+7 (952) 110-••-••». */
function kopka39_phone_masked_text() {
	$shown = kopka_d( 'phone' );
	return preg_replace( '/\d{2}([^\d]+)\d{2}$/u', '••$1••', $shown );
}

function kopka39_phone_masked_html() {
	$shown = kopka_d( 'phone' );
	if ( ! preg_match( '/^(.*?)(\d{2}[^\d]+\d{2})$/u', $shown, $m ) ) {
		return esc_html( $shown );
	}
	$tail = preg_replace( '/\d/', '•', $m[2] );
	return '<span class="k3-phone" data-k3p="' . esc_attr( kopka39_phone_code() ) . '" title="Наведите или нажмите, чтобы увидеть номер">'
		. esc_html( $m[1] ) . '<span class="k3-phone-tail">' . esc_html( $tail ) . '</span></span>';
}

function kopka39_phone_guard_filter( $html ) {
	$raw   = kopka_d( 'phone_raw' );
	$shown = kopka_d( 'phone' );
	if ( '' === $raw || '' === $shown ) {
		return $html;
	}
	$code = esc_attr( kopka39_phone_code() );

	// 1. schema.org: убрать телефон (или оставить, если так решено).
	if ( ! KOPKA39_PHONE_IN_SCHEMA ) {
		$html = preg_replace_callback(
			'#(<script[^>]*application/ld\+json[^>]*>)(.*?)(</script>)#is',
			function ( $m ) use ( $raw ) {
				$json = preg_replace( '/"telephone"\s*:\s*"[^"]*"\s*,?/', '', $m[2] );
				$json = preg_replace( '/,\s*([}\]])/', '$1', $json );
				return $m[1] . $json . $m[3];
			},
			$html
		);
	}

	// 2. Ссылки tel: → href="#" + закодированный номер.
	$digits = preg_quote( ltrim( $raw, '+' ), '#' );
	$html   = preg_replace(
		'#href=(["\'])tel:\+?' . $digits . '\1#',
		'href="#" data-k3p="' . $code . '" data-k3-call="1"',
		$html
	);

	// 3. Номер в тексте → маска. Только в текстовых узлах, не внутри
	//    <script>/<style>; в атрибутах (title, aria-label) — простой текст.
	$parts  = preg_split( '#(<script\b.*?</script>|<style\b.*?</style>|<[^>]+>)#is', $html, -1, PREG_SPLIT_DELIM_CAPTURE );
	$masked = kopka39_phone_masked_html();
	$plain  = kopka39_phone_masked_text();
	$alts   = array( $shown, esc_html( $shown ), $raw );
	foreach ( $parts as $i => $part ) {
		if ( '' === $part ) {
			continue;
		}
		if ( '<' === $part[0] ) {
			if ( preg_match( '#^<(script|style)\b#i', $part ) ) {
				continue;
			}
			$parts[ $i ] = str_replace( $alts, $plain, $part );
			continue;
		}
		$parts[ $i ] = str_replace( $alts, $masked, $part );
	}
	return implode( '', $parts );
}

function kopka39_phone_guard_start() {
	if ( is_admin() || is_feed() || wp_doing_ajax() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || is_customize_preview() ) {
		return;
	}
	ob_start( 'kopka39_phone_guard_filter' );
}
add_action( 'template_redirect', 'kopka39_phone_guard_start', 0 );
