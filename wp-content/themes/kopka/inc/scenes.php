<?php
/**
 * Псевдо-3D сцены сайта (вариант «3D»).
 *
 * Каждая сцена — набор прозрачных SVG-слоёв одного размера, лежащих
 * друг на друге. Атрибуты слоя читает js/k3-scenes.js:
 *   data-depth      — сдвиг за мышью (px у края сцены);
 *   data-ex/data-ey — смещение при «разборе» (px);
 *   data-rot        — поворот при разборе (градусы);
 *   data-o0/data-op — прозрачность до/после разбора.
 * Изометрия рисуется классом K3_Iso — объёмные блоки без картинок.
 * Сцены второй очереди и постоянные анимации — inc/scenes-extra.php.
 *
 * Сцена на внутренней странице выбирается по её адресу
 * (kopka3d_scene_map) и вставляется в первый экран контента.
 *
 * @package kopka
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class K3_Iso {
	public $ox;
	public $oy;
	public $s;

	public function __construct( $ox, $oy, $s ) {
		$this->ox = $ox;
		$this->oy = $oy;
		$this->s  = $s;
	}

	public function p( $x, $y, $z ) {
		return array(
			round( $this->ox + ( $x - $y ) * 0.8660254 * $this->s, 1 ),
			round( $this->oy + ( $x + $y ) * 0.5 * $this->s - $z * $this->s, 1 ),
		);
	}

	public function pts( array $list ) {
		$out = array();
		foreach ( $list as $q ) {
			$p     = $this->p( $q[0], $q[1], $q[2] );
			$out[] = $p[0] . ',' . $p[1];
		}
		return implode( ' ', $out );
	}

	/** Объёмный блок: видны верх, грань +y (левая) и грань +x (правая). */
	public function box( $x, $y, $z, $w, $d, $h, $base, $top = null ) {
		$top   = $top ? $top : k3_shade( $base, 1.18 );
		$left  = k3_shade( $base, 0.86 );
		$right = k3_shade( $base, 0.68 );
		$t = $this->pts( array( array( $x, $y, $z + $h ), array( $x + $w, $y, $z + $h ), array( $x + $w, $y + $d, $z + $h ), array( $x, $y + $d, $z + $h ) ) );
		$l = $this->pts( array( array( $x, $y + $d, $z ), array( $x + $w, $y + $d, $z ), array( $x + $w, $y + $d, $z + $h ), array( $x, $y + $d, $z + $h ) ) );
		$r = $this->pts( array( array( $x + $w, $y, $z ), array( $x + $w, $y + $d, $z ), array( $x + $w, $y + $d, $z + $h ), array( $x + $w, $y, $z + $h ) ) );
		return "<polygon points='$l' fill='$left'/><polygon points='$r' fill='$right'/><polygon points='$t' fill='$top'/>";
	}

	/** Плоский многоугольник на высоте z (разметка, плоскости). */
	public function flat( array $xy, $z, $attrs ) {
		$list = array();
		foreach ( $xy as $q ) {
			$list[] = array( $q[0], $q[1], $z );
		}
		return "<polygon points='" . $this->pts( $list ) . "' $attrs/>";
	}

	/** Линия на высоте z. */
	public function line( $x1, $y1, $x2, $y2, $z, $attrs ) {
		$a = $this->p( $x1, $y1, $z );
		$b = $this->p( $x2, $y2, $z );
		return "<line x1='{$a[0]}' y1='{$a[1]}' x2='{$b[0]}' y2='{$b[1]}' $attrs/>";
	}

	/** SVG-матрица: плоский рисунок в единицах сетки ложится на плоскость z. */
	public function flat_matrix( $z ) {
		$c = round( 0.8660254 * $this->s, 3 );
		$h = round( 0.5 * $this->s, 3 );
		return 'matrix(' . $c . ' ' . $h . ' ' . ( -$c ) . ' ' . $h . ' ' . $this->ox . ' ' . round( $this->oy - $z * $this->s, 2 ) . ')';
	}

	/** Окружность радиуса r на плоскости z — эллипс в проекции. */
	public function ring( $x, $y, $z, $r, $attrs ) {
		$c  = $this->p( $x, $y, $z );
		$rx = round( $r * 1.4142 * 0.8660254 * $this->s, 1 );
		$ry = round( $r * 1.4142 * 0.5 * $this->s, 1 );
		return "<ellipse cx='{$c[0]}' cy='{$c[1]}' rx='$rx' ry='$ry' $attrs/>";
	}
}

/** Осветлить/затемнить цвет #rrggbb в f раз. */
function k3_shade( $hex, $f ) {
	$hex = ltrim( $hex, '#' );
	$out = '#';
	for ( $i = 0; $i < 3; $i++ ) {
		$v    = hexdec( substr( $hex, $i * 2, 2 ) );
		$v    = max( 0, min( 255, (int) round( $v * $f ) ) );
		$out .= str_pad( dechex( $v ), 2, '0', STR_PAD_LEFT );
	}
	return $out;
}

/** Один слой сцены. */
function k3_layer( $inner, $attrs = '', $vb = '0 0 600 440' ) {
	return '<svg class="k3-layer" viewBox="' . $vb . '" preserveAspectRatio="xMidYMid meet" ' . $attrs . '>' . $inner . '</svg>';
}

/** Подпись узла, появляется при разборе. */
function k3_tag( $text, $left, $top, $attrs = '' ) {
	return '<span class="k3-tag" style="left:' . $left . '%;top:' . $top . '%" data-tag="1" ' . $attrs . '>' . esc_html( $text ) . '</span>';
}

/**
 * Обёртка сцены.
 * $mode: hero — разбирается при уходе вверх; inview — когда сцена в центре экрана.
 */
function k3_stage( $inner, $mode = 'hero', $class = '' ) {
	return '<div class="k3-stage k3-iso ' . esc_attr( $class ) . '" data-k3-stage data-k3-mode="' . esc_attr( $mode ) . '" aria-hidden="true">'
		. '<svg class="k3-layer" viewBox="0 0 600 440" preserveAspectRatio="xMidYMid meet" data-depth="3"><ellipse cx="300" cy="412" rx="230" ry="18" fill="#000" opacity=".38"/></svg>'
		. $inner . '</div>';
}

/** Палитра грунта. */
function k3_soil() {
	return array(
		'sand'   => '#c49a5e',
		'clay'   => '#94633f',
		'loam'   => '#6b4a2f',
		'top'    => '#4b3626',
		'grass'  => '#5f7d3c',
		'dirt'   => '#7a5a3c',
		'conc'   => '#a3a8ae',
		'orange' => '#f28c1b',
	);
}

/* ------------------------------------------------------------
 * ТРАНШЕЯ: разрез грунта, слои расходятся, труба поднимается.
 * $fill = true — вариант «обратная засыпка».
 * ------------------------------------------------------------ */
function k3_scene_trench( $fill = false, $mode = 'hero' ) {
	$c   = k3_soil();
	$iso = new K3_Iso( 276, 143, 28 );
	$W   = 10;
	$D   = 8;
	$tx  = 4;
	$tw  = 2;
	$bot = 0.8;

	$strata = array(
		array( 'sand', 0, 1.2, 0 ),
		array( 'clay', 1.2, 2.2, -18 ),
		array( 'loam', 2.2, 3.0, -36 ),
		array( 'top', 3.0, 3.5, -58 ),
	);
	$html = '';
	foreach ( $strata as $i => $s ) {
		list( $key, $z0, $z1, $ey ) = $s;
		$g   = '';
		$top = ( 'top' === $key ) ? $c['grass'] : null;
		if ( 0 === $i ) {
			$g .= $iso->box( 0, 0, 0, $W, $D, $bot, $c['sand'] );
			$z0 = $bot;
		}
		$g   .= $iso->box( 0, 0, $z0, $tx, $D, $z1 - $z0, $c[ $key ], $top );
		$g   .= $iso->box( $tx + $tw, 0, $z0, $W - $tx - $tw, $D, $z1 - $z0, $c[ $key ], $top );
		$ey   = $fill ? 0 : $ey;
		$html .= k3_layer( $g, 'data-depth="' . ( 8 + $i * 4 ) . '" data-ey="' . $ey . '"' );
	}

	// Труба на дне траншеи.
	$zc    = $bot + 0.45;
	$a     = $iso->p( $tx + 1, 0, $zc );
	$b     = $iso->p( $tx + 1, $D, $zc );
	$pipe  = "<line x1='{$a[0]}' y1='{$a[1]}' x2='{$b[0]}' y2='{$b[1]}' stroke='#c9631a' stroke-width='25'/>";
	$pipe .= "<line x1='{$a[0]}' y1='" . ( $a[1] - 7 ) . "' x2='{$b[0]}' y2='" . ( $b[1] - 7 ) . "' stroke='#ff9e4a' stroke-width='5' opacity='.8'/>";
	$pipe .= "<circle cx='{$b[0]}' cy='{$b[1]}' r='12.5' fill='#e07a2e'/><circle cx='{$b[0]}' cy='{$b[1]}' r='8' fill='#2a1d14'/>";
	$html .= k3_layer( $pipe, $fill ? 'data-depth="20"' : 'data-depth="24" data-ey="-112"' );

	if ( $fill ) {
		// Засыпка: грунт возвращается в траншею.
		$g     = $iso->box( $tx, 0, $bot + 0.9, $tw, $D, 3.5 - $bot - 0.9, $c['loam'], $c['dirt'] );
		$html .= k3_layer( $g, 'data-depth="18" data-o0="0" data-op="1"' );
		$pos   = array( array( 4.3, 1.5, 6.2 ), array( 5.4, 3.2, 7.4 ), array( 4.6, 5.0, 6.6 ), array( 5.2, 6.6, 7.8 ), array( 4.2, 3.9, 8.6 ) );
		$ch    = '';
		foreach ( $pos as $p ) {
			$ch .= "<g class='k3a-fall' style='animation-delay:" . round( $p[2] - 6, 1 ) . "s'>" . $iso->box( $p[0], $p[1], $p[2] - 2.5, 0.55, 0.55, 0.55, $c['dirt'] ) . '</g>';
		}
		$html .= k3_layer( $ch, 'data-depth="34" data-ey="120" data-op="0"' );
		$html .= k3_tag( 'Грунт возвращается на место', 50, 6, 'data-depth="30"' );
		$html .= k3_tag( 'Коммуникации уложены и проверены', 4, 72, 'data-depth="20"' );
	} else {
		// Комья грунта «вылетают» из траншеи по кругу.
		$amb = '';
		foreach ( array( array( 4.7, 1.6, 0 ), array( 5.0, 4.2, 1.1 ), array( 4.6, 6.4, 2.2 ) ) as $q ) {
			$amb .= "<g class='k3a-rise' style='animation-delay:{$q[2]}s'>" . $iso->box( $q[0], $q[1], 1.6, 0.5, 0.5, 0.5, $c['dirt'], '#a07a56' ) . '</g>';
		}
		$html .= k3_layer( $amb, 'data-depth="22" data-ey="-60"' );
		$html .= k3_tag( 'Плодородный слой', 64, 8, 'data-depth="20" data-ey="-58"' );
		$html .= k3_tag( 'Траншея по разметке', 2, 30, 'data-depth="16" data-ey="-36"' );
		$html .= k3_tag( 'Под трубы и кабель', 40, 54, 'data-depth="24" data-ey="-112"' );
	}
	return k3_stage( $html, $mode );
}

/* ------------------------------------------------------------
 * КОТЛОВАН: блок грунта с выемкой, плита фундамента поднимается.
 * ------------------------------------------------------------ */
function k3_scene_pit( $mode = 'hero' ) {
	$c   = k3_soil();
	$iso = new K3_Iso( 300, 150, 24 );
	$N   = 10;
	$a   = 3;
	$b   = 7;
	$bot = 1.2;

	$html   = k3_layer( $iso->box( 0, 0, 0, $N, $N, $bot, $c['sand'] ), 'data-depth="6"' );
	$strata = array(
		array( 'clay', 1.2, 2.2, -14 ),
		array( 'loam', 2.2, 3.0, -30 ),
		array( 'top', 3.0, 3.5, -50 ),
	);
	foreach ( $strata as $i => $s ) {
		list( $key, $z0, $z1, $ey ) = $s;
		$h   = $z1 - $z0;
		$top = ( 'top' === $key ) ? $c['grass'] : null;
		$g   = $iso->box( 0, 0, $z0, $N, $a, $h, $c[ $key ], $top );
		$g  .= $iso->box( 0, $a, $z0, $a, $b - $a, $h, $c[ $key ], $top );
		$g  .= $iso->box( $b, $a, $z0, $N - $b, $b - $a, $h, $c[ $key ], $top );
		$g  .= $iso->box( 0, $b, $z0, $N, $N - $b, $h, $c[ $key ], $top );
		if ( 'top' === $key ) {
			$g .= $iso->flat( array( array( $a - 0.3, $a - 0.3 ), array( $b + 0.3, $a - 0.3 ), array( $b + 0.3, $b + 0.3 ), array( $a - 0.3, $b + 0.3 ) ), 3.52, "class='k3a-march' fill='none' stroke='#f28c1b' stroke-width='2.5' stroke-dasharray='8 6'" );
		}
		$html .= k3_layer( $g, 'data-depth="' . ( 10 + $i * 4 ) . '" data-ey="' . $ey . '"' );
	}
	$in    = $b - $a - 0.7;
	$slab  = $iso->box( $a + 0.35, $a + 0.35, $bot, $in, $in, 0.45, $c['conc'] );
	$slab .= $iso->box( $a + 0.35, $a + 0.35, $bot + 0.45, $in, 0.35, 0.9, $c['conc'] );
	$slab .= $iso->box( $a + 0.35, $a + 0.35, $bot + 0.45, 0.35, $in, 0.9, $c['conc'] );
	$html .= k3_layer( $slab, 'data-depth="26" data-ey="-120"' );
	$html .= k3_tag( 'Разметка по проекту', 62, 12, 'data-depth="22" data-ey="-50"' );
	$html .= k3_tag( 'Выемка грунта', 4, 42, 'data-depth="16" data-ey="-30"' );
	$html .= k3_tag( 'Под ленту, плиту или подвал', 28, 2, 'data-depth="26" data-ey="-120"' );
	return k3_stage( $html, $mode );
}

/* ------------------------------------------------------------
 * ПЛАНИРОВКА: бугры уходят в грунт, опускается ровная плоскость.
 * ------------------------------------------------------------ */
function k3_scene_level( $mode = 'hero' ) {
	$c    = k3_soil();
	$iso  = new K3_Iso( 267, 136, 26 );
	$W    = 12;
	$D    = 9;
	$g    = $iso->box( 0, 0, 0, $W, $D, 1.0, $c['loam'] );
	$g   .= $iso->box( 0, 0, 1.0, $W, $D, 0.4, $c['dirt'], '#8a6846' );
	$html = k3_layer( $g, 'data-depth="8"' );

	$m      = '';
	$mounds = array( array( 1.5, 1.5, 2.4, 2.2 ), array( 6.5, 1.0, 3.2, 2.8 ), array( 3.0, 5.5, 2.0, 1.8 ), array( 8.5, 5.5, 2.4, 2.2 ) );
	foreach ( $mounds as $q ) {
		list( $x, $y, $w, $d ) = $q;
		$m .= $iso->box( $x, $y, 1.4, $w, $d, 0.45, $c['dirt'], '#937050' );
		$m .= $iso->box( $x + $w * 0.2, $y + $d * 0.2, 1.85, $w * 0.6, $d * 0.6, 0.4, $c['dirt'], '#9a7756' );
		$m .= $iso->box( $x + $w * 0.38, $y + $d * 0.38, 2.25, $w * 0.24, $d * 0.24, 0.3, $c['dirt'], '#a07c5a' );
	}
	$html .= k3_layer( $m, 'data-depth="16" data-ey="26" data-op="0"' );

	$plane = $iso->flat( array( array( 0, 0 ), array( $W, 0 ), array( $W, $D ), array( 0, $D ) ), 3.6, "fill='#f28c1b' fill-opacity='.14' stroke='#f28c1b' stroke-width='2'" );
	for ( $i = 1; $i < 6; $i++ ) {
		$plane .= $iso->line( $i * 2, 0, $i * 2, $D, 3.6, "stroke='#f28c1b' stroke-opacity='.35' stroke-width='1'" );
	}
	$scan   = $iso->line( 0, 0, 0, $D, 3.6, "stroke='#ffb15c' stroke-width='3'" );
	$plane .= "<g class='k3a-scan' style='--tx:" . round( $W * 0.866 * 26 ) . 'px;--ty:' . round( $W * 0.5 * 26 ) . "px'>" . $scan . '</g>';
	$html .= k3_layer( $plane, 'data-depth="28" data-ey="57"' );
	$html .= k3_tag( 'Бугры снимаются', 60, 16, 'data-depth="16"' );
	$html .= k3_tag( 'Ровная площадка', 4, 24, 'data-depth="28" data-ey="57"' );
	$html .= k3_tag( 'Под стройку или газон', 34, 86, 'data-depth="10"' );
	return k3_stage( $html, $mode );
}

/* ------------------------------------------------------------
 * ПОГРУЗКА: куча грунта, комья перелетают в кузов.
 * ------------------------------------------------------------ */
function k3_scene_load( $mode = 'hero' ) {
	$c    = k3_soil();
	$o    = $c['orange'];
	$iso  = new K3_Iso( 236, 160, 24 );
	$html = k3_layer( $iso->box( -1, 0, -0.3, 14, 8, 0.3, '#3a3c40' ), 'data-depth="4"' );

	$pile  = $iso->box( 0, 2, 0, 4, 4, 0.8, $c['dirt'], '#8f6b48' );
	$pile .= $iso->box( 0.6, 2.6, 0.8, 2.8, 2.8, 0.8, $c['dirt'], '#977250' );
	$pile .= $iso->box( 1.2, 3.2, 1.6, 1.6, 1.6, 0.7, $c['dirt'], '#a07a56' );
	$html .= k3_layer( $pile, 'data-depth="10"' );

	$bed  = $iso->box( 7, 1, 0, 5, 5, 0.4, '#2b2d31' );
	$bed .= $iso->box( 7, 1, 0.4, 5, 0.3, 2.0, $o );
	$bed .= $iso->box( 7, 1.3, 0.4, 0.3, 4.7, 2.0, $o );
	$bed .= $iso->box( 7.3, 1.3, 0.4, 4.4, 4.4, 0.9, $c['dirt'], '#8f6b48' );
	$bed .= $iso->box( 11.7, 1.3, 0.4, 0.3, 4.7, 2.0, $o );
	$bed .= $iso->box( 7.3, 5.7, 0.4, 4.4, 0.3, 2.0, $o );
	$html .= k3_layer( $bed, 'data-depth="14"' );

	$chunks = array(
		array( 2.0, 3.0, 2.6, 150, -70 ),
		array( 2.8, 4.0, 2.4, 170, -40 ),
		array( 1.6, 4.2, 2.3, 190, -60 ),
		array( 3.2, 2.8, 2.2, 130, -30 ),
	);
	foreach ( $chunks as $i => $q ) {
		$g     = $iso->box( $q[0], $q[1], $q[2], 0.6, 0.6, 0.6, $c['dirt'], '#a07a56' );
		$html .= k3_layer( $g, 'data-depth="' . ( 22 + $i * 4 ) . '" data-ex="' . $q[3] . '" data-ey="' . $q[4] . '"' );
	}
	// Комья по дуге из кучи в кузов, по кругу.
	$amb = '';
	foreach ( array( 0, 1.1, 2.2 ) as $i => $delay ) {
		$cube = $iso->box( 1.8 + $i * 0.3, 3.8, 2.4, 0.5, 0.5, 0.5, $c['dirt'], '#a07a56' );
		$amb .= "<g class='k3a-arcx' style='--dx:166px;animation-delay:{$delay}s'><g class='k3a-arcy' style='--h:-90px;--dy:108px;animation-delay:{$delay}s'>$cube</g></g>";
	}
	$html .= k3_layer( $amb, 'data-depth="20"' );
	$html .= k3_tag( 'Грунт, песок, щебень', 2, 72, 'data-depth="10"' );
	$html .= k3_tag( 'Погрузка в самосвал', 60, 14, 'data-depth="14"' );
	$html .= k3_tag( 'Перемещение по участку', 26, 4, 'data-depth="24"' );
	return k3_stage( $html, $mode );
}

/* ------------------------------------------------------------
 * ЦЕНЫ: четыре «часа»-куба (минимальный заказ) расходятся.
 * ------------------------------------------------------------ */
function k3_scene_hours( $mode = 'hero' ) {
	$iso  = new K3_Iso( 300, 250, 30 );
	$html = k3_layer( $iso->box( -1, -1, -0.4, 5, 5, 0.4, '#2b2d31' ), 'data-depth="4"' );
	for ( $i = 0; $i < 4; $i++ ) {
		$z     = $i * 1.15;
		$g     = $iso->box( 0, 0, $z, 3, 3, 1.0, '#f28c1b', '#ffad55' );
		$g    .= $iso->flat( array( array( 0, 0 ), array( 3, 0 ), array( 3, 3 ), array( 0, 3 ) ), $z + 1.0, "class='k3a-glow' style='animation-delay:" . ( $i * 0.6 ) . "s' fill='#fff2d9'" );
		$pt    = $iso->p( 3, 1.5, $z + 0.5 );
		// Надпись «лежит» на правой грани куба (наклон −30°).
		$g    .= "<text transform='matrix(0.866 -0.5 0 1 {$pt[0]} {$pt[1]})' x='0' y='6' text-anchor='middle' font-family='Unbounded, sans-serif' font-weight='800' font-size='15' fill='#2a1a08' opacity='.8'>1 ч</text>";
		$html .= k3_layer( $g, 'data-depth="' . ( 10 + $i * 6 ) . '" data-ey="' . ( -$i * 26 ) . '" data-ex="' . ( $i % 2 ? 12 : -12 ) . '"' );
	}
	$html .= k3_tag( kopka_d( 'price_hour' ), 64, 18, 'data-depth="28" data-ey="-78"' );
	$html .= k3_tag( 'Минимум ' . kopka_d( 'min_order' ), 2, 34, 'data-depth="20" data-ey="-40"' );
	$html .= k3_tag( '+ подача по адресу', 58, 76, 'data-depth="8"' );
	return k3_stage( $html, $mode );
}

/* ------------------------------------------------------------
 * ВЫЕЗД: площадка-карта с кольцами зон и поднимающимися метками.
 * Условная схема, не карта местности.
 * ------------------------------------------------------------ */
function k3_scene_area( $mode = 'hero' ) {
	$iso   = new K3_Iso( 300, 140, 22 );
	$plate = $iso->box( 0, 0, -0.6, 12, 12, 0.6, '#3b4638', '#46543f' );
	for ( $i = 2; $i < 12; $i += 2 ) {
		$plate .= $iso->line( $i, 0, $i, 12, 0.01, "stroke='#ffffff' stroke-opacity='.07'" );
		$plate .= $iso->line( 0, $i, 12, $i, 0.01, "stroke='#ffffff' stroke-opacity='.07'" );
	}
	$plate .= $iso->ring( 6, 6, 0.02, 4.6, "fill='#f28c1b' fill-opacity='.06' stroke='#f28c1b' stroke-opacity='.5' stroke-width='1.5' stroke-dasharray='6 6'" );
	$plate .= $iso->ring( 6, 6, 0.02, 2.0, "fill='#f28c1b' fill-opacity='.16' stroke='#f28c1b' stroke-width='2'" );
	$plate .= $iso->ring( 6, 6, 0.02, 2.0, "class='k3a-pulse' fill='none' stroke='#ffb15c' stroke-width='2'" );
	$html   = k3_layer( $plate, 'data-depth="6"' );

	$pins = array(
		array( 6, 6, 32 ),
		array( 9.4, 4.4, 22 ),
		array( 2.2, 10.4, 16 ),
	);
	foreach ( $pins as $q ) {
		$p     = $iso->p( $q[0], $q[1], 0 );
		$x     = $p[0];
		$y     = $p[1];
		$g     = "<ellipse cx='$x' cy='$y' rx='9' ry='4.5' fill='#000' opacity='.35'/>";
		$g    .= "<g class='k3a-bob' style='animation-delay:-" . ( $q[2] / 20 ) . "s'><path d='M$x $y c-6 -14 -18 -22 -18 -36 a18 18 0 1 1 36 0 c0 14 -12 22 -18 36z' fill='#f28c1b'/>";
		$g    .= "<circle cx='$x' cy='" . ( $y - 36 ) . "' r='7' fill='#1c1e21'/></g>";
		$html .= k3_layer( $g, 'data-depth="' . $q[2] . '" data-ey="' . round( -$q[2] * 1.6 ) . '"' );
	}
	$html .= k3_tag( 'Калининград — подача ' . kopka_d( 'delivery_city' ), 34, 12, 'data-depth="32" data-ey="-51"' );
	$html .= k3_tag( 'До 15 км — ' . kopka_d( 'delivery_15km' ), 64, 40, 'data-depth="22" data-ey="-35"' );
	$html .= k3_tag( 'Дальше — по договорённости', 0, 64, 'data-depth="16" data-ey="-26"' );
	return k3_stage( $html, $mode );
}

/* ------------------------------------------------------------
 * КОНТАКТЫ: телефон и всплывающие сообщения.
 * ------------------------------------------------------------ */
function k3_scene_contact( $mode = 'hero' ) {
	$iso    = new K3_Iso( 300, 176, 26 );
	$phone  = $iso->box( 0, 0, 0, 4, 7, 0.45, '#26292d', '#2f3338' );
	$phone .= $iso->flat( array( array( 0.3, 0.4 ), array( 3.7, 0.4 ), array( 3.7, 6.6 ), array( 0.3, 6.6 ) ), 0.46, "fill='#f28c1b' fill-opacity='.92'" );
	// «Сообщения» на экране — полоски разной длины.
	$rows = array( array( 0.8, 2.2 ), array( 1.8, 2.6 ), array( 2.8, 1.6 ), array( 3.8, 2.4 ), array( 4.8, 1.8 ) );
	foreach ( $rows as $i => $r ) {
		$x0     = ( $i % 2 ) ? 3.3 - $r[1] : 0.7;
		$phone .= $iso->flat( array( array( $x0, $r[0] ), array( $x0 + $r[1], $r[0] ), array( $x0 + $r[1], $r[0] + 0.6 ), array( $x0, $r[0] + 0.6 ) ), 0.47, "fill='#1c1e21' fill-opacity='" . ( ( $i % 2 ) ? '.55' : '.8' ) . "'" );
	}
	$html   = k3_layer( $phone, 'data-depth="10"' );

	$bubbles = array(
		array( 404, 92, 'Telegram', 28, 40, -40 ),
		array( 70, 118, 'MAX', 22, -40, -30 ),
		array( 392, 292, 'Фото участка', 36, 50, 20 ),
	);
	foreach ( $bubbles as $bb ) {
		list( $x, $y, $t, $d, $ex, $ey ) = $bb;
		$w     = 46 + (int) round( mb_strlen( $t ) * 10 );
		$g     = "<g transform='translate($x $y)'><g class='k3a-pop' style='animation-delay:" . ( $d / 12 ) . "s'><rect x='0' y='0' width='$w' height='46' rx='16' fill='#2f3338' stroke='#ffffff' stroke-opacity='.14'/>";
		$g    .= "<path d='M18 45 l-6 13 l18 -13z' fill='#2f3338'/><circle cx='22' cy='23' r='5' fill='#f28c1b'/>";
		$g    .= "<text x='36' y='29' font-family='Inter, sans-serif' font-weight='700' font-size='16' fill='#ECEDEE'>" . esc_html( $t ) . '</text></g></g>';
		$html .= k3_layer( $g, 'data-depth="' . $d . '" data-ex="' . $ex . '" data-ey="' . $ey . '"' );
	}
	return k3_stage( $html, $mode );
}

/* ------------------------------------------------------------
 * ЭКСКАВАТОР-ПОГРУЗЧИК (как на главной), viewBox 800×520.
 * ------------------------------------------------------------ */
function k3_backhoe_layers( $variant = '' ) {
	ob_start();
	?>
	<svg class="k3-layer" data-depth="10" data-ex="-20" data-ey="12" viewBox="0 0 800 520" preserveAspectRatio="xMidYMid meet">
		<path d="M600 472 Q640 392 700 402 Q735 375 768 410 Q792 430 800 472 Z" fill="#4a3828"/>
		<path d="M616 472 Q650 418 700 424 Q740 410 770 440 Q785 455 790 472 Z" fill="#5d4733"/>
		<circle cx="680" cy="446" r="7" fill="#6e5841"/><circle cx="742" cy="438" r="5" fill="#7a634a"/>
	</svg>
	<div class="k3-float">
		<svg class="k3-layer" data-depth="14" data-ey="40" viewBox="0 0 800 520" preserveAspectRatio="xMidYMid meet">
			<g><circle cx="480" cy="398" r="62" fill="#1b1c1f"/><circle cx="480" cy="398" r="62" fill="none" stroke="#34363b" stroke-width="10" stroke-dasharray="10 8"/><circle cx="480" cy="398" r="32" fill="#3a3d42"/><circle cx="480" cy="398" r="12" fill="#6b7077"/></g>
			<g><circle cx="248" cy="414" r="46" fill="#1b1c1f"/><circle cx="248" cy="414" r="46" fill="none" stroke="#34363b" stroke-width="8" stroke-dasharray="9 7"/><circle cx="248" cy="414" r="24" fill="#3a3d42"/><circle cx="248" cy="414" r="9" fill="#6b7077"/></g>
		</svg>
		<svg class="k3-layer" data-depth="18" data-ex="-6" data-ey="-22" viewBox="0 0 800 520" preserveAspectRatio="xMidYMid meet">
			<rect x="196" y="328" width="380" height="44" rx="6" fill="#2b2d31"/>
			<path d="M498 332 L498 280 L572 280 L586 332 Z" class="k3-fill"/>
			<path d="M498 332 L498 280 L572 280 L586 332 Z" fill="#000" opacity=".25"/>
			<path d="M168 332 L184 270 Q189 258 204 258 L362 258 L362 332 Z" class="k3-fill"/>
			<path d="M184 270 Q189 258 204 258 L362 258 L362 270 Z" fill="#fff" opacity=".14"/>
			<path d="M210 278 V316 M226 278 V316 M242 278 V316 M258 278 V316" stroke="#000" stroke-opacity=".28" stroke-width="5"/>
			<path d="M354 334 L354 162 Q354 150 366 150 L476 150 Q488 150 490 162 L506 334 Z" class="k3-fill"/>
			<path d="M369 166 L470 166 L486 258 L369 258 Z" fill="#17232b"/>
			<path d="M374 172 L410 172 L388 252 L374 252 Z" fill="#fff" opacity=".12"/>
			<rect x="346" y="140" width="152" height="14" rx="4" fill="#2b2d31"/>
			<path d="M408 344 A74 74 0 0 1 552 344" fill="none" class="k3-stroke" stroke-width="14"/>
			<path d="M198 368 A52 52 0 0 1 298 368" fill="none" class="k3-stroke" stroke-width="12"/>
			<path d="M556 340 L598 440 L612 435 L574 334 Z" fill="#2b2d31"/>
			<rect x="586" y="438" width="40" height="10" rx="3" fill="#3a3d42"/>
		</svg>
		<svg class="k3-layer" data-depth="26" data-ex="-70" data-ey="14" data-rot="8" style="transform-origin: 12% 74%" viewBox="0 0 800 520" preserveAspectRatio="xMidYMid meet">
			<path d="M300 334 L190 350" stroke="#3b3e43" stroke-width="12" stroke-linecap="round"/>
			<path d="M300 334 L240 343" stroke="#d4d8dd" stroke-width="6" stroke-linecap="round"/>
			<path d="M372 288 L384 306 L150 376 L138 358 Z" class="k3-fill"/>
			<path d="M372 288 L384 306 L150 376 L138 358 Z" fill="#000" opacity=".15"/>
			<circle cx="146" cy="368" r="9" fill="#222" stroke="#8a8f96" stroke-width="3"/>
			<path d="M56 328 L142 330 L152 424 L68 436 Q50 424 54 402 Z" fill="#3d4147"/>
			<path d="M56 328 L142 330 L144 344 L58 342 Z" fill="#fff" opacity=".1"/>
			<path d="M66 436 L152 424 L152 430 L68 442 Z" fill="#8a8f96"/>
		</svg>
		<svg class="k3-layer" data-depth="24" data-ex="34" data-ey="-58" viewBox="0 0 800 520" preserveAspectRatio="xMidYMid meet">
			<path d="M574 330 L652 210" stroke="#3b3e43" stroke-width="12" stroke-linecap="round"/>
			<path d="M574 330 L612 272" stroke="#d4d8dd" stroke-width="6" stroke-linecap="round"/>
			<path d="M578 312 L598 290 L688 140 Q698 126 711 136 L716 150 L612 322 Z" class="k3-fill"/>
			<path d="M598 290 L688 140 Q698 126 711 136 L698 144 L608 296 Z" fill="#fff" opacity=".15"/>
			<path d="M688 140 L712 132 L758 350 L738 360 Z" class="k3-fill"/>
			<path d="M688 140 L712 132 L758 350 L738 360 Z" fill="#000" opacity=".22"/>
			<path d="M680 120 L732 250" stroke="#3b3e43" stroke-width="9" stroke-linecap="round"/>
			<path d="M680 120 L706 186" stroke="#d4d8dd" stroke-width="5" stroke-linecap="round"/>
			<circle cx="590" cy="304" r="10" fill="#222" stroke="#8a8f96" stroke-width="3"/>
			<circle cx="700" cy="140" r="10" fill="#222" stroke="#8a8f96" stroke-width="3"/>
		</svg>
		<svg class="k3-layer" data-depth="34" data-ex="72" data-ey="34" data-rot="-18" style="transform-origin: 94% 69%" viewBox="0 0 800 520" preserveAspectRatio="xMidYMid meet">
			<circle cx="748" cy="354" r="9" fill="#222" stroke="#8a8f96" stroke-width="3"/>
			<path d="M734 350 L776 346 Q794 386 766 412 L730 406 Q746 380 734 350 Z" fill="#3d4147"/>
			<path d="M776 346 Q794 386 766 412 L758 410 Q782 384 766 350 Z" fill="#fff" opacity=".1"/>
			<path d="M732 405 L738 420 L744 407 Z M746 408 L752 423 L758 410 Z" fill="#2a2c30"/>
		</svg>
	</div>
	<svg class="k3-layer" data-depth="42" data-ey="-26" viewBox="0 0 800 520" preserveAspectRatio="xMidYMid meet">
		<g class="k3-rock1"><path d="M560 120 L580 108 L596 122 L586 140 L564 138 Z" fill="#4a3828"/></g>
		<g class="k3-rock2"><path d="M110 210 L124 202 L136 212 L126 226 Z" fill="#5d4733"/></g>
		<g class="k3-rock1"><path d="M60 470 L72 462 L84 470 L76 482 Z" fill="#3d4147"/></g>
	</svg>
	<span class="k3-tag" style="left:4%;top:56%" data-tag="1" data-depth="26" data-ex="-70" data-ey="14">Передний ковш</span>
	<span class="k3-tag" style="left:66%;top:10%" data-tag="1" data-depth="24" data-ex="34" data-ey="-58">Обратная лопата</span>
	<span class="k3-tag" style="left:42%;top:20%" data-tag="1" data-depth="18" data-ex="-6" data-ey="-22">Кабина оператора</span>
	<span class="k3-tag" style="left:28%;top:88%" data-tag="1" data-depth="14" data-ey="40">Колёсный ход</span>
	<?php
	$html = ob_get_clean();
	$html = str_replace( 'class="k3-layer"', 'class="k3-layer k3-machine"', $html );
	if ( 'dig' === $variant ) {
		// Рабочий цикл: стрела опускается, ковш загребает, передний ковш покачивается.
		$html = preg_replace( '#(<svg class="k3-layer k3-machine" data-depth="24"[^>]*>)(.*?)(</svg>)#s', '$1<g class="k3a-boom">$2</g>$3', $html, 1 );
		$html = preg_replace( '#(<svg class="k3-layer k3-machine" data-depth="34"[^>]*>)(.*?)(</svg>)#s', '$1<g class="k3a-boom"><g class="k3a-curl">$2</g></g>$3', $html, 1 );
		$html = preg_replace( '#(<svg class="k3-layer k3-machine" data-depth="26"[^>]*>)(.*?)(</svg>)#s', '$1<g class="k3a-lift">$2</g>$3', $html, 1 );
	}
	return $html;
}

function k3_scene_backhoe( $mode = 'hero', $extra = '', $variant = '' ) {
	$class = 'blueprint' === $variant ? ' k3-blueprint' : '';
	return '<div class="k3-stage' . $class . '" data-k3-stage data-k3-mode="' . esc_attr( $mode ) . '" aria-hidden="true">'
		. $extra
		. '<svg class="k3-layer" data-depth="4" viewBox="0 0 800 520" preserveAspectRatio="xMidYMid meet"><ellipse cx="420" cy="470" rx="340" ry="24" fill="#000" opacity=".4"/></svg>'
		. k3_backhoe_layers( $variant ) . '</div>';
}

/* ------------------------------------------------------------
 * Какая сцена на какой странице.
 * ------------------------------------------------------------ */
function kopka3d_scene_map() {
	return array(
		'kopka-transhey'                                => 'trench',
		'kotlovan-pod-fundament'                        => 'pit',
		'planirovka-uchastka'                           => 'level',
		'pogruzka-grunta'                               => 'load',
		'obratnaya-zasypka'                             => 'fill',
		'zemlyanye-raboty'                              => 'complex',
		'arenda-ekskavatora'                            => 'clock',
		'arenda-ekskavatora-pogruzchika'                => 'dig',
		'tehnika'                                       => 'blueprint',
		'foto-rabot'                                    => 'photos',
		'tseny'                                         => 'hours',
		'rayony-vyezda'                                 => 'area',
		'kontakty'                                      => 'contact',
		'blog'                                          => 'articles',
		'ot-chego-zavisit-stoimost-kopki-transhei'      => 'compare',
		'kak-rasschitat-obem-grunta-v-transhee'         => 'trenchvol',
		'kak-rasschitat-obem-kotlovana'                 => 'pitvol',
		'kak-podgotovit-uchastok-k-priezdu-ekskavatora' => 'prep',
		'zaezd-cherez-kanavu'                           => 'crossing',
		'drenazhnye-kanavy'                             => 'drain',
		'raschistka-uchastka'                           => 'clear',
		'uborka-snega'                                  => 'snow',
	);
}

function kopka3d_scene( $name, $mode = 'hero' ) {
	$fn = array(
		'trench'    => function () use ( $mode ) { return k3_scene_trench( false, $mode ); },
		'fill'      => function () use ( $mode ) { return k3_scene_trench( true, $mode ); },
		'pit'       => function () use ( $mode ) { return k3_scene_pit( $mode ); },
		'level'     => function () use ( $mode ) { return k3_scene_level( $mode ); },
		'load'      => function () use ( $mode ) { return k3_scene_load( $mode ); },
		'hours'     => function () use ( $mode ) { return k3_scene_hours( $mode ); },
		'area'      => function () use ( $mode ) { return k3_scene_area( $mode ); },
		'contact'   => function () use ( $mode ) { return k3_scene_contact( $mode ); },
		'backhoe'   => function () use ( $mode ) { return k3_scene_backhoe( $mode ); },
		'dig'       => function () use ( $mode ) { return k3_scene_backhoe( $mode, '', 'dig' ); },
		'blueprint' => function () use ( $mode ) { return k3_scene_blueprint( $mode ); },
		'complex'   => function () use ( $mode ) { return k3_scene_complex( $mode ); },
		'clock'     => function () use ( $mode ) { return k3_scene_clock( $mode ); },
		'photos'    => function () use ( $mode ) { return k3_scene_photos( $mode ); },
		'articles'  => function () use ( $mode ) { return k3_scene_articles( $mode ); },
		'compare'   => function () use ( $mode ) { return k3_scene_compare( $mode ); },
		'trenchvol' => function () use ( $mode ) { return k3_scene_trenchvol( $mode ); },
		'pitvol'    => function () use ( $mode ) { return k3_scene_pitvol( $mode ); },
		'prep'      => function () use ( $mode ) { return k3_scene_prep( $mode ); },
		'e404'      => function () use ( $mode ) { return k3_scene_404( $mode ); },
		'crossing'  => function () use ( $mode ) { return k3_scene_crossing( $mode ); },
		'drain'     => function () use ( $mode ) { return k3_scene_drain( $mode ); },
		'clear'     => function () use ( $mode ) { return k3_scene_clear( $mode ); },
		'snow'      => function () use ( $mode ) { return k3_scene_snow( $mode ); },
	);
	return isset( $fn[ $name ] ) ? $fn[ $name ]() : '';
}

/** Slug текущей страницы, если для неё есть сцена. */
function kopka3d_page_scene_name() {
	if ( ! is_page() ) {
		return '';
	}
	$slug = get_post_field( 'post_name', get_queried_object_id() );
	$map  = kopka3d_scene_map();
	return isset( $map[ $slug ] ) ? $map[ $slug ] : '';
}

/**
 * Вставляем сцену в первый экран страницы: перед закрывающим
 * </div> блока .fse-hero-content (сразу после текстовой колонки).
 */
function kopka3d_inject_scene( $content ) {
	if ( is_admin() || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}
	$name = kopka3d_page_scene_name();
	if ( '' === $name || false === strpos( $content, 'fse-hero-content' ) ) {
		return $content;
	}
	$end = strpos( $content, '</section>' );
	if ( false === $end ) {
		return $content;
	}
	$pos = strrpos( substr( $content, 0, $end ), '</div>' );
	if ( false === $pos ) {
		return $content;
	}
	$scene = '<div class="k3-page-scene">' . kopka3d_scene( $name ) . '</div>';
	return substr( $content, 0, $pos ) . $scene . substr( $content, $pos );
}
add_filter( 'the_content', 'kopka3d_inject_scene', 20 );

/** Класс на <body>: первый экран со сценой становится двухколоночным. */
add_filter(
	'body_class',
	function ( $classes ) {
		if ( '' !== kopka3d_page_scene_name() ) {
			$classes[] = 'k3-has-scene';
		}
		return $classes;
	}
);

/** Фоновые комья земли, плавающие на разной глубине (все страницы). */
function kopka3d_particles() {
	$rocks = array(
		array( 4, 18, 22, 0.9, '#4a3828' ),
		array( 94, 30, 16, 0.5, '#5d4733' ),
		array( 12, 64, 12, 0.35, '#3d4147' ),
		array( 88, 74, 26, 1.1, '#4a3828' ),
		array( 46, 92, 10, 0.3, '#5d4733' ),
		array( 70, 8, 12, 0.4, '#3d4147' ),
		array( 30, 44, 9, 0.25, '#5d4733' ),
		array( 97, 52, 11, 0.6, '#3d4147' ),
	);
	echo '<div class="k3-particles" aria-hidden="true">';
	foreach ( $rocks as $i => $r ) {
		echo '<span class="k3-particle" data-speed="' . esc_attr( $r[3] ) . '" style="left:' . (int) $r[0] . '%;top:' . (int) $r[1] . '%;width:' . (int) $r[2] . 'px;height:' . (int) $r[2] . 'px;animation-delay:-' . esc_attr( $i * 1.3 ) . 's">'
			. '<svg viewBox="0 0 20 20" width="100%" height="100%"><path d="M3 9 L9 2 L17 6 L18 14 L10 19 L2 15 Z" fill="' . esc_attr( $r[4] ) . '"/><path d="M3 9 L9 2 L17 6 L10 9 Z" fill="#fff" opacity=".12"/></svg></span>';
	}
	echo '</div>';
}
add_action( 'wp_body_open', 'kopka3d_particles' );
