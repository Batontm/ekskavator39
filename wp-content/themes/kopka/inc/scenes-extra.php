<?php
/**
 * Сцены второй очереди: у каждой страницы — своя тематическая анимация.
 * Общие правила слоёв и подписи — в inc/scenes.php. Постоянные
 * («фоновые») анимации — классы k3a-* в css/k3-scenes.css и SMIL
 * (<animate>, <animateTransform>) для вращений в изометрии.
 *
 * @package kopka
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ------------------------------------------------------------
 * ТЕХНИКА: чертёж машины — контур прорисовывается, по нему
 * проходит луч сканера, внизу размерные линии.
 * ------------------------------------------------------------ */
function k3_scene_blueprint( $mode = 'hero' ) {
	$grid  = '<svg class="k3-layer" data-depth="2" viewBox="0 0 800 520" preserveAspectRatio="xMidYMid meet">'
		. '<defs><pattern id="k3bp" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M40 0H0V40" fill="none" stroke="#9fb4c7" stroke-opacity=".12"/></pattern></defs>'
		. '<rect x="20" y="40" width="780" height="460" rx="18" fill="#1b2430" fill-opacity=".55"/><rect x="20" y="40" width="780" height="460" rx="18" fill="url(#k3bp)"/></svg>';
	$dims  = '<svg class="k3-layer" data-depth="8" data-ey="30" viewBox="0 0 800 520" preserveAspectRatio="xMidYMid meet"><g stroke="#f28c1b" stroke-width="2" fill="none">'
		. '<path d="M56 488 H790 M56 478 V498 M790 478 V498"/><path d="M820 140 V470 M810 140 H830 M810 470 H830" transform="translate(-40 0)"/>'
		. '<path class="k3a-march" d="M480 398 L480 488" stroke-dasharray="6 6"/><path class="k3a-march" d="M248 414 L248 488" stroke-dasharray="6 6"/></g>'
		. '<circle cx="480" cy="398" r="5" fill="#f28c1b"/><circle cx="248" cy="414" r="5" fill="#f28c1b"/></svg>';
	$scan  = '<svg class="k3-layer" data-depth="12" viewBox="0 0 800 520" preserveAspectRatio="xMidYMid meet"><g class="k3a-sweep">'
		. '<rect x="40" y="60" width="3" height="420" fill="#ffb15c"/><rect x="18" y="60" width="25" height="420" fill="#f28c1b" opacity=".12"/></g></svg>';
	$tags  = k3_tag( 'Задняя стрела — копка', 64, 4, 'data-depth="24" data-ex="34" data-ey="-58"' )
		. k3_tag( 'Передний ковш — погрузка, планировка', 0, 50, 'data-depth="26" data-ex="-70" data-ey="14"' )
		. k3_tag( 'Колёсный ход — едет своим ходом', 30, 90, 'data-depth="14" data-ey="40"' );
	$html  = k3_scene_backhoe( $mode, $grid . $dims . $scan, 'blueprint' );
	// Подписи из обычной машины заменяем на «чертёжные».
	$html  = preg_replace( '#<span class="k3-tag".*?</span>#s', '', $html );
	return preg_replace( '#</div>$#', $tags . '</div>', $html );
}

/* ------------------------------------------------------------
 * ЗЕМЛЯНЫЕ РАБОТЫ: участок, на котором по очереди подсвечиваются
 * четыре работы одного выезда; маршрут техники «бежит» между ними.
 * ------------------------------------------------------------ */
function k3_scene_complex( $mode = 'hero' ) {
	$c   = k3_soil();
	$iso = new K3_Iso( 290, 120, 24 );
	$W   = 12;
	$D   = 10;
	$g   = $iso->box( 0, 0, -1.2, $W, $D, 0.8, $c['loam'] );
	$g  .= $iso->box( 0, 0, -0.4, $W, $D, 0.4, $c['top'], $c['grass'] );
	$html = k3_layer( $g, 'data-depth="6"' );

	$zones = array(
		// x, y, w, d, цвет, подпись, номер
		array( 1, 1, 1.4, 7, '#2c1f15', 'Копка' ),
		array( 7.6, 1, 3, 3, $c['dirt'], 'Перемещение грунта' ),
		array( 4.5, 5.5, 6, 3.5, '#8a6846', 'Планировка' ),
		array( 4, 2.2, 2.6, 1.2, $c['loam'], 'Засыпка' ),
	);
	$flat = '';
	$glow = '';
	foreach ( $zones as $i => $z ) {
		list( $x, $y, $w, $d, $col ) = $z;
		$quad  = array( array( $x, $y ), array( $x + $w, $y ), array( $x + $w, $y + $d ), array( $x, $y + $d ) );
		$flat .= $iso->flat( $quad, 0.01, "fill='$col'" );
		$q3    = array();
		foreach ( $quad as $qq ) {
			$q3[] = array( $qq[0], $qq[1], 0.02 );
		}
		$glow .= "<polygon points='" . $iso->pts( $q3 ) . "' fill='#f28c1b' fill-opacity='0' stroke='#f28c1b' stroke-width='2.5' stroke-opacity='0'>"
			. "<animate attributeName='fill-opacity' values='0;.35;0;0' keyTimes='0;.12;.25;1' dur='8s' begin='" . ( $i * 2 ) . "s' repeatCount='indefinite'/>"
			. "<animate attributeName='stroke-opacity' values='0;1;0;0' keyTimes='0;.12;.25;1' dur='8s' begin='" . ( $i * 2 ) . "s' repeatCount='indefinite'/></polygon>";
	}
	// Куча грунта на зоне «перемещение».
	$flat .= $iso->box( 8.2, 1.6, 0, 1.8, 1.8, 0.6, $c['dirt'], '#977250' ) . $iso->box( 8.6, 2.0, 0.6, 1.0, 1.0, 0.5, $c['dirt'], '#a07a56' );
	$html .= k3_layer( $flat . $glow, 'data-depth="10" data-ey="-14"' );

	// Маршрут техники между зонами.
	$pts   = array( array( 1.7, 4.5 ), array( 5.3, 2.8 ), array( 9.1, 2.5 ), array( 7.5, 7.2 ) );
	$route = array();
	foreach ( $pts as $q ) {
		$p       = $iso->p( $q[0], $q[1], 0.05 );
		$route[] = $p[0] . ',' . $p[1];
	}
	$html .= k3_layer( "<polyline class='k3a-march' points='" . implode( ' ', $route ) . "' fill='none' stroke='#ffb15c' stroke-width='3' stroke-dasharray='10 8' stroke-linejoin='round'/>", 'data-depth="14" data-ey="-20"' );

	// Номера работ над зонами.
	$marks = '';
	foreach ( $zones as $i => $z ) {
		$p      = $iso->p( $z[0] + $z[2] / 2, $z[1] + $z[3] / 2, 0 );
		$x      = $p[0];
		$y      = $p[1] - 46;
		$marks .= "<line x1='$x' y1='{$p[1]}' x2='$x' y2='" . ( $y + 16 ) . "' stroke='#f28c1b' stroke-width='2' stroke-dasharray='3 4'/>";
		$marks .= "<g class='k3a-bob' style='animation-delay:-" . ( $i * 0.4 ) . "s'><circle cx='$x' cy='$y' r='16' fill='#f28c1b'/>"
			. "<text x='$x' y='" . ( $y + 6 ) . "' text-anchor='middle' font-family='Unbounded, sans-serif' font-weight='800' font-size='16' fill='#1c1e21'>" . ( $i + 1 ) . '</text></g>';
	}
	$html .= k3_layer( $marks, 'data-depth="24" data-ey="-40"' );
	$html .= k3_tag( '1 Копка · 2 Перемещение', 0, 6, 'data-depth="20"' );
	$html .= k3_tag( '3 Планировка · 4 Засыпка', 56, 88, 'data-depth="16"' );
	$html .= k3_tag( 'Несколько работ — один выезд', 58, 6, 'data-depth="28" data-ey="-40"' );
	return k3_stage( $html, $mode );
}

/* ------------------------------------------------------------
 * АРЕНДА ЭКСКАВАТОРА: циферблат лежит на площадке, стрелка идёт,
 * подсвечиваются 4 часа минимального заказа.
 * ------------------------------------------------------------ */
function k3_scene_clock( $mode = 'hero' ) {
	$iso  = new K3_Iso( 300, 110, 22 );
	$html = k3_layer( $iso->box( 0.5, 0.5, -0.9, 11, 11, 0.6, '#2b2d31' ), 'data-depth="4"' );

	$cx = 6;
	$cy = 6;
	$r  = 4.6;
	// Толщина циферблата: тот же круг ниже.
	$rim  = "<g transform='" . $iso->flat_matrix( -0.3 ) . "'><circle cx='$cx' cy='$cy' r='$r' fill='#15171a'/></g>";
	$face = "<g transform='" . $iso->flat_matrix( 0 ) . "'><circle cx='$cx' cy='$cy' r='$r' fill='#26292d' stroke='#3a3e44' stroke-width='.12'/>";
	for ( $h = 0; $h < 12; $h++ ) {
		$a     = deg2rad( $h * 30 );
		$r1    = 0 === $h % 3 ? $r - 0.75 : $r - 0.45;
		$face .= "<line x1='" . round( $cx + sin( $a ) * $r1, 3 ) . "' y1='" . round( $cy - cos( $a ) * $r1, 3 ) . "' x2='" . round( $cx + sin( $a ) * ( $r - 0.15 ), 3 ) . "' y2='" . round( $cy - cos( $a ) * ( $r - 0.15 ), 3 ) . "' stroke='#cfd4da' stroke-width='" . ( 0 === $h % 3 ? '.16' : '.08' ) . "' stroke-linecap='round'/>";
	}
	$face .= '</g>';
	$html .= k3_layer( $rim . $face, 'data-depth="12" data-ey="-16"' );

	// Четыре часа минимального заказа — дуги, загораются по очереди.
	$arcs = "<g transform='" . $iso->flat_matrix( 0.02 ) . "'>";
	$ra   = $r - 1.25;
	for ( $i = 0; $i < 4; $i++ ) {
		$a0    = deg2rad( $i * 30 + 2 );
		$a1    = deg2rad( $i * 30 + 28 );
		$d     = 'M' . round( $cx + sin( $a0 ) * $ra, 3 ) . ' ' . round( $cy - cos( $a0 ) * $ra, 3 ) . ' A' . $ra . ' ' . $ra . ' 0 0 1 ' . round( $cx + sin( $a1 ) * $ra, 3 ) . ' ' . round( $cy - cos( $a1 ) * $ra, 3 );
		$arcs .= "<path d='$d' fill='none' stroke='#f28c1b' stroke-width='.55' stroke-linecap='round' opacity='.25'>"
			. "<animate attributeName='opacity' values='.25;.25;1;1;.25' keyTimes='0;" . round( $i * 0.18, 2 ) . ';' . round( $i * 0.18 + 0.05, 2 ) . ";.85;1' dur='8s' repeatCount='indefinite'/></path>";
	}
	$arcs .= "<text x='$cx' y='" . ( $cy + 2.3 ) . "' text-anchor='middle' font-family='Unbounded, sans-serif' font-weight='800' font-size='1.1' fill='#ffb15c'>мин. 4 ч</text></g>";
	$html .= k3_layer( $arcs, 'data-depth="18" data-ey="-34"' );

	// Стрелка: вращение в плоскости циферблата (SMIL внутри изометрической матрицы).
	$hand  = "<g transform='" . $iso->flat_matrix( 0.05 ) . "'><g transform='translate($cx $cy)'><g>"
		. "<line x1='0' y1='.6' x2='0' y2='-3.4' stroke='#ffffff' stroke-width='.22' stroke-linecap='round'/>"
		. "<animateTransform attributeName='transform' type='rotate' from='0' to='360' dur='8s' repeatCount='indefinite'/></g>"
		. "<circle r='.35' fill='#f28c1b'/></g></g>";
	$html .= k3_layer( $hand, 'data-depth="24" data-ey="-52"' );

	$html .= k3_tag( kopka_d( 'price_hour' ), 66, 14, 'data-depth="24" data-ey="-52"' );
	$html .= k3_tag( 'Минимум ' . kopka_d( 'min_order' ), 0, 30, 'data-depth="18" data-ey="-34"' );
	$html .= k3_tag( 'Оплата по факту отработанного времени', 18, 88, 'data-depth="8"' );
	return k3_stage( $html, $mode );
}

/* ------------------------------------------------------------
 * ФОТО РАБОТ: стопка настоящих снимков с объектов, веером.
 * ------------------------------------------------------------ */
function k3_scene_photos( $mode = 'hero' ) {
	$uri    = get_template_directory_uri() . '/img/';
	$photos = array(
		// файл, left%, top%, наклон, data-depth, ex, ey, rot
		array( 'raschistka-zarosshego-uchastka.webp', 4, 30, -9, 10, -70, 30, -6 ),
		array( 'planirovka-grunta-vdol-zabora.webp', 44, 34, 8, 14, 80, 40, 6 ),
		array( 'kopka-drenazhnoy-kanavy.webp', 10, 6, -4, 18, -50, -60, -4 ),
		array( 'kopka-transhei-pod-kommunikacii.webp', 36, 10, 5, 24, 60, -40, 5 ),
	);
	$html = '';
	foreach ( $photos as $i => $p ) {
		$html .= '<div class="k3-photo" data-depth="' . $p[4] . '" data-ex="' . $p[5] . '" data-ey="' . $p[6] . '" data-rot="' . $p[7] . '" style="left:' . $p[1] . '%;top:' . $p[2] . '%;--r:' . $p[3] . 'deg;--d:' . ( $i * -1.3 ) . 's">'
			. '<div class="k3-photo-in"><img src="' . esc_url( $uri . $p[0] ) . '" alt="" loading="lazy" decoding="async"></div></div>';
	}
	$html .= k3_tag( 'Реальные объекты', 66, 4, 'data-depth="24"' );
	$html .= k3_tag( 'Снято во время работы', 2, 88, 'data-depth="12"' );
	return '<div class="k3-stage k3-photos" data-k3-stage data-k3-mode="' . esc_attr( $mode ) . '" aria-hidden="true">' . $html . '</div>';
}

/* ------------------------------------------------------------
 * БЛОГ: стопка «статей»-карточек, парят по очереди.
 * ------------------------------------------------------------ */
function k3_scene_articles( $mode = 'hero' ) {
	$iso   = new K3_Iso( 300, 230, 26 );
	$html  = '';
	$cards = array( 'Объём траншеи', 'Объём котлована', 'Стоимость копки', 'Подготовка участка' );
	foreach ( $cards as $i => $title ) {
		$z  = $i * 1.25;
		$x  = ( $i % 2 ) * 0.6;
		$g  = $iso->box( $x, 0, $z, 6, 4.2, 0.22, '#2a2d31', '#33373c' );
		$g .= $iso->flat( array( array( $x + 0.4, 0.4 ), array( $x + 5.6, 0.4 ), array( $x + 5.6, 1.2 ), array( $x + 0.4, 1.2 ) ), $z + 0.23, "fill='#f28c1b'" );
		foreach ( array( 1.7, 2.3, 2.9, 3.5 ) as $k => $ly ) {
			$len = array( 4.8, 4.2, 4.6, 3.0 )[ $k ];
			$g  .= $iso->flat( array( array( $x + 0.4, $ly ), array( $x + 0.4 + $len, $ly ), array( $x + 0.4 + $len, $ly + 0.25 ), array( $x + 0.4, $ly + 0.25 ) ), $z + 0.23, "fill='#ffffff' fill-opacity='.18'" );
		}
		$html .= k3_layer( "<g class='k3a-bob' style='animation-delay:-" . ( $i * 0.7 ) . "s'>$g</g>", 'data-depth="' . ( 8 + $i * 6 ) . '" data-ey="' . ( -$i * 22 ) . '" data-ex="' . ( $i % 2 ? 14 : -14 ) . '"' );
	}
	$html .= k3_tag( 'Расчёт объёма грунта', 62, 12, 'data-depth="26" data-ey="-66"' );
	$html .= k3_tag( 'Из чего складывается цена', 0, 36, 'data-depth="18" data-ey="-44"' );
	$html .= k3_tag( 'Как подготовить участок', 54, 80, 'data-depth="8"' );
	return k3_stage( $html, $mode );
}

/* ------------------------------------------------------------
 * СТОИМОСТЬ КОПКИ: одинаковые траншеи в песке и в глине с камнями,
 * столбики времени растут по-разному.
 * ------------------------------------------------------------ */
function k3_scene_compare( $mode = 'hero' ) {
	$c    = k3_soil();
	$iso  = new K3_Iso( 200, 150, 22 );
	$html = '';
	$blocks = array(
		// смещение x, y, грунт, высота столбика времени, подпись
		array( 0, 3, 'sand', 1.6 ),
		array( 7, -3, 'clay', 4.2 ),
	);
	foreach ( $blocks as $i => $b ) {
		list( $ox, $oy, $soil, $bar ) = $b;
		$col = $c[ $soil ];
		$g   = $iso->box( $ox, $oy, 0, 4, 4, 0.6, $col );
		$g  .= $iso->box( $ox, $oy, 0.6, 1.5, 4, 1.6, $col, 'clay' === $soil ? $c['grass'] : '#d9b57c' );
		$g  .= $iso->box( $ox + 2.5, $oy, 0.6, 1.5, 4, 1.6, $col, 'clay' === $soil ? $c['grass'] : '#d9b57c' );
		if ( 'clay' === $soil ) {
			foreach ( array( array( 1.0, 3.6, 1.0 ), array( 3.5, 1.2, 1.5 ), array( 4.0, 3.2, 0.7 ), array( 1.5, 0.5, 1.8 ) ) as $st ) {
				$g .= $iso->box( $ox + $st[0] - 0.35, $oy + $st[1] - 0.35, $st[2], 0.5, 0.45, 0.4, '#7d838b' );
			}
		}
		$html .= k3_layer( $g, 'data-depth="' . ( 10 + $i * 4 ) . '" data-ey="' . ( $i ? -16 : 16 ) . '"' );

		// Столбик времени рядом с блоком.
		$col_g = $iso->box( $ox + 4.6, $oy + 1.6, 0, 0.9, 0.9, $bar, '#f28c1b', '#ffad55' );
		$html .= k3_layer( "<g class='k3a-grow' style='animation-delay:" . ( $i * 0.2 ) . "s'>$col_g</g>", 'data-depth="' . ( 18 + $i * 4 ) . '" data-ey="' . ( $i ? -30 : 4 ) . '"' );
	}
	$html .= k3_tag( 'Песок — копается быстрее', 0, 64, 'data-depth="12"' );
	$html .= k3_tag( 'Глина с камнями — дольше', 56, 6, 'data-depth="18" data-ey="-20"' );
	$html .= k3_tag( 'Размеры одинаковые, время — разное', 22, 90, 'data-depth="8"' );
	return k3_stage( $html, $mode );
}

/* ------------------------------------------------------------
 * ОБЪЁМ ТРАНШЕИ: вынутый грунт поднимается «брусом» с размерами.
 * ------------------------------------------------------------ */
function k3_scene_trenchvol( $mode = 'hero' ) {
	$c   = k3_soil();
	$iso = new K3_Iso( 276, 190, 24 );
	$W   = 10;
	$D   = 8;
	$tx  = 4;
	$tw  = 2;
	$bot = 0.8;
	$h   = 3.0;
	$g   = $iso->box( 0, 0, 0, $W, $D, $bot, $c['sand'] );
	$g  .= $iso->box( 0, 0, $bot, $tx, $D, $h - $bot, $c['loam'], $c['grass'] );
	$g  .= $iso->box( $tx + $tw, 0, $bot, $W - $tx - $tw, $D, $h - $bot, $c['loam'], $c['grass'] );
	$html = k3_layer( $g, 'data-depth="8"' );

	// Брус грунта, равный траншее, парит над ней.
	$lift  = 4.2;
	$z0    = $bot + $lift;
	$z1    = $h + $lift;
	$bar   = $iso->box( $tx, 0, $z0, $tw, $D, $z1 - $z0, '#8a6846', '#a07c5a' );
	$o     = "stroke='#f28c1b' stroke-width='2'";
	$t     = "font-family='Unbounded, sans-serif' font-weight='800' font-size='15' fill='#ffb15c'";
	// Длина — вдоль правого нижнего ребра.
	$bar  .= $iso->line( $tx + $tw + 0.4, 0, $tx + $tw + 0.4, $D, $z0, $o );
	$pl    = $iso->p( $tx + $tw + 0.9, $D / 2, $z0 );
	$bar  .= "<text x='{$pl[0]}' y='{$pl[1]}' $t>Д</text>";
	// Ширина — по переднему ребру.
	$bar  .= $iso->line( $tx, $D + 0.4, $tx + $tw, $D + 0.4, $z0, $o );
	$pw    = $iso->p( $tx + $tw / 2, $D + 1.2, $z0 );
	$bar  .= "<text x='" . ( $pw[0] - 8 ) . "' y='" . ( $pw[1] + 8 ) . "' $t>Ш</text>";
	// Глубина — вертикаль на углу.
	$a     = $iso->p( $tx, $D + 0.4, $z0 );
	$b     = $iso->p( $tx, $D + 0.4, $z1 );
	$bar  .= "<line x1='" . ( $a[0] - 8 ) . "' y1='{$a[1]}' x2='" . ( $b[0] - 8 ) . "' y2='{$b[1]}' $o/>";
	$bar  .= "<text x='" . ( $a[0] - 30 ) . "' y='" . ( ( $a[1] + $b[1] ) / 2 + 6 ) . "' $t>Г</text>";
	$html .= k3_layer( "<g class='k3a-bob'>$bar</g>", 'data-depth="24" data-ey="-40"' );

	// Пунктир «откуда» — контур траншеи.
	$html .= k3_layer( $iso->flat( array( array( $tx, 0 ), array( $tx + $tw, 0 ), array( $tx + $tw, $D ), array( $tx, $D ) ), $h + 0.02, "class='k3a-march' fill='none' stroke='#f28c1b' stroke-width='2' stroke-dasharray='7 6'" ), 'data-depth="10"' );
	$html .= k3_tag( 'V = Д × Ш × Г', 64, 10, 'data-depth="24" data-ey="-40"' );
	$html .= k3_tag( 'Столько грунта выйдет наружу', 0, 84, 'data-depth="10"' );
	return k3_stage( $html, $mode );
}

/* ------------------------------------------------------------
 * ОБЪЁМ КОТЛОВАНА: грунт поднимается «усечённой пирамидой» —
 * поверху котлован шире из-за откосов.
 * ------------------------------------------------------------ */
function k3_scene_pitvol( $mode = 'hero' ) {
	$c   = k3_soil();
	$iso = new K3_Iso( 300, 196, 22 );
	$N   = 10;
	$top = 2.6;
	$g   = $iso->box( 0, 0, 0, $N, $N, 0.8, $c['sand'] );
	// Рамка вокруг выемки 2.4…7.6, дно 3…7.
	$g  .= $iso->box( 0, 0, 0.8, $N, 2.4, $top - 0.8, $c['loam'], $c['grass'] );
	$g  .= $iso->box( 0, 2.4, 0.8, 2.4, 5.2, $top - 0.8, $c['loam'], $c['grass'] );
	$g  .= $iso->box( 7.6, 2.4, 0.8, 2.4, 5.2, $top - 0.8, $c['loam'], $c['grass'] );
	$g  .= $iso->box( 0, 7.6, 0.8, $N, 2.4, $top - 0.8, $c['loam'], $c['grass'] );
	$html = k3_layer( $g, 'data-depth="8"' );

	// Усечённая пирамида грунта.
	$lift = 4.0;
	$z0   = 0.8 + $lift;
	$z1   = $top + $lift;
	$a0   = 3;
	$b0   = 7;
	$a1   = 2.4;
	$b1   = 7.6;
	$f    = "<polygon points='" . $iso->pts( array( array( $a0, $b0, $z0 ), array( $b0, $b0, $z0 ), array( $b1, $b1, $z1 ), array( $a1, $b1, $z1 ) ) ) . "' fill='" . k3_shade( '#8a6846', 0.86 ) . "'/>";
	$f   .= "<polygon points='" . $iso->pts( array( array( $b0, $a0, $z0 ), array( $b0, $b0, $z0 ), array( $b1, $b1, $z1 ), array( $b1, $a1, $z1 ) ) ) . "' fill='" . k3_shade( '#8a6846', 0.68 ) . "'/>";
	$f   .= $iso->flat( array( array( $a1, $a1 ), array( $b1, $a1 ), array( $b1, $b1 ), array( $a1, $b1 ) ), $z1, "fill='#a07c5a'" );
	// Откос подсвечен.
	$f   .= "<polyline points='" . $iso->pts( array( array( $b0, $a0, $z0 ), array( $b1, $a1, $z1 ) ) ) . "' fill='none' stroke='#f28c1b' stroke-width='3'/>";
	$f   .= "<polyline points='" . $iso->pts( array( array( $a0, $b0, $z0 ), array( $a1, $b1, $z1 ) ) ) . "' fill='none' stroke='#f28c1b' stroke-width='3'/>";
	$html .= k3_layer( "<g class='k3a-bob'>$f</g>", 'data-depth="24" data-ey="-40"' );

	$html .= k3_layer( $iso->flat( array( array( $a1, $a1 ), array( $b1, $a1 ), array( $b1, $b1 ), array( $a1, $b1 ) ), $top + 0.02, "class='k3a-march' fill='none' stroke='#f28c1b' stroke-width='2' stroke-dasharray='7 6'" ), 'data-depth="10"' );
	$html .= k3_tag( 'Откосы: поверху шире, чем по дну', 50, 6, 'data-depth="24" data-ey="-40"' );
	$html .= k3_tag( 'Точность расчёта ниже, чем у траншеи', 0, 86, 'data-depth="10"' );
	return k3_stage( $html, $mode );
}

/* ------------------------------------------------------------
 * ПОДГОТОВКА УЧАСТКА: ворота, путь для техники, разметка колышками.
 * ------------------------------------------------------------ */
function k3_scene_prep( $mode = 'hero' ) {
	$c   = k3_soil();
	$iso = new K3_Iso( 300, 120, 24 );
	$W   = 12;
	$D   = 10;
	$g   = $iso->box( 0, 0, -1.0, $W, $D, 0.6, $c['loam'] );
	$g  .= $iso->box( 0, 0, -0.4, $W, $D, 0.4, $c['top'], $c['grass'] );
	$html = k3_layer( $g, 'data-depth="6"' );

	// Забор по дальним краям, ворота в левом (x = 0) с проёмом 3…6.
	$f = '';
	for ( $x = 0; $x <= $W; $x += 2 ) {
		$f .= $iso->box( $x, 0, 0, 0.2, 0.2, 1.4, '#8c8f94' );
	}
	$f .= $iso->line( 0, 0.1, $W, 0.1, 1.2, "stroke='#a7abb1' stroke-width='2.5'" ) . $iso->line( 0, 0.1, $W, 0.1, 0.5, "stroke='#a7abb1' stroke-width='2.5'" );
	foreach ( array( 2, 3, 6, 8, 10 ) as $y ) {
		$f .= $iso->box( 0, $y, 0, 0.2, 0.2, 1.4, '#8c8f94' );
	}
	$f .= $iso->line( 0.1, 0, 0.1, 3, 1.2, "stroke='#a7abb1' stroke-width='2.5'" ) . $iso->line( 0.1, 6, 0.1, $D, 1.2, "stroke='#a7abb1' stroke-width='2.5'" );
	$f .= $iso->line( 0.1, 0, 0.1, 3, 0.5, "stroke='#a7abb1' stroke-width='2.5'" ) . $iso->line( 0.1, 6, 0.1, $D, 0.5, "stroke='#a7abb1' stroke-width='2.5'" );
	// Ширина ворот — размерная линия.
	$f .= $iso->line( -0.6, 3, -0.6, 6, 0.05, "stroke='#f28c1b' stroke-width='2'" );
	$html .= k3_layer( $f, 'data-depth="10" data-ey="-12"' );

	// Путь техники от ворот к месту работ.
	$path  = $iso->flat( array( array( 0.2, 3.2 ), array( 6.5, 3.2 ), array( 6.5, 5.8 ), array( 0.2, 5.8 ) ), 0.01, "fill='#8a6846' fill-opacity='.55'" );
	$a     = $iso->p( 0.6, 4.5, 0.03 );
	$b     = $iso->p( 6.2, 4.5, 0.03 );
	$path .= "<line class='k3a-march' x1='{$a[0]}' y1='{$a[1]}' x2='{$b[0]}' y2='{$b[1]}' stroke='#ffb15c' stroke-width='3' stroke-dasharray='10 8'/>";
	$html .= k3_layer( $path, 'data-depth="12"' );

	// Разметка места работ: колышки с флажками и пунктир.
	$m   = $iso->flat( array( array( 7, 3 ), array( 11, 3 ), array( 11, 8.5 ), array( 7, 8.5 ) ), 0.02, "class='k3a-march' fill='#f28c1b' fill-opacity='.08' stroke='#f28c1b' stroke-width='2' stroke-dasharray='8 6'" );
	foreach ( array( array( 7, 3 ), array( 11, 3 ), array( 11, 8.5 ), array( 7, 8.5 ) ) as $i => $q ) {
		$m  .= $iso->box( $q[0] - 0.1, $q[1] - 0.1, 0, 0.2, 0.2, 1.6, '#c9a46a' );
		$top = $iso->p( $q[0], $q[1], 1.6 );
		$m  .= "<path class='k3a-flag' style='animation-delay:-" . ( $i * 0.3 ) . "s' d='M{$top[0]} {$top[1]} l22 6 l-22 8z' fill='#f28c1b'/>";
	}
	$html .= k3_layer( $m, 'data-depth="18" data-ey="-26"' );

	// «Готово» — галочка всплывает.
	$ck    = $iso->p( 9, 5.7, 3.4 );
	$html .= k3_layer( "<g class='k3a-pop' style='animation-delay:.6s'><circle cx='{$ck[0]}' cy='{$ck[1]}' r='20' fill='#f28c1b'/><path d='M" . ( $ck[0] - 9 ) . ' ' . $ck[1] . ' l6 7 l13 -14' . "' fill='none' stroke='#1c1e21' stroke-width='4' stroke-linecap='round' stroke-linejoin='round'/></g>", 'data-depth="28" data-ey="-50"' );
	$html .= k3_tag( 'Ширина ворот и проезда', 0, 24, 'data-depth="10" data-ey="-12"' );
	$html .= k3_tag( 'Путь для техники свободен', 10, 80, 'data-depth="12"' );
	$html .= k3_tag( 'Место работ размечено', 60, 10, 'data-depth="18" data-ey="-26"' );
	return k3_stage( $html, $mode );
}

/* ------------------------------------------------------------
 * 404: цифры из блоков поднимаются из грунта.
 * ------------------------------------------------------------ */
function k3_scene_404( $mode = 'hero' ) {
	$c    = k3_soil();
	$iso  = new K3_Iso( 236, 120, 22 );
	$g    = $iso->box( -1, -1, -1.4, 15, 7, 0.9, $c['loam'] ) . $iso->box( -1, -1, -0.5, 15, 7, 0.5, $c['top'], $c['grass'] );
	$html = k3_layer( $g, 'data-depth="6"' );
	// Цифры лежат на земле объёмными блоками (строки шрифта идут по оси y).
	$font = array(
		'4' => array( '101', '101', '111', '001', '001' ),
		'0' => array( '111', '101', '101', '101', '111' ),
	);
	foreach ( array( '4', '0', '4' ) as $n => $ch ) {
		$x0    = $n * 4.4;
		$boxes = array();
		foreach ( $font[ $ch ] as $row => $bits ) {
			for ( $col = 0; $col < 3; $col++ ) {
				if ( '1' === $bits[ $col ] ) {
					$boxes[] = array( $x0 + $col, $row );
				}
			}
		}
		usort(
			$boxes,
			function ( $a, $b ) {
				return ( $a[0] + $a[1] ) <=> ( $b[0] + $b[1] );
			}
		);
		$g = '';
		foreach ( $boxes as $bx ) {
			$g .= $iso->box( $bx[0], $bx[1], 0, 1, 1, 1.1, '#f28c1b', '#ffad55' );
		}
		$html .= k3_layer( "<g class='k3a-bob' style='animation-delay:-" . ( $n * 0.5 ) . "s'>$g</g>", 'data-depth="' . ( 14 + $n * 6 ) . '" data-ey="' . ( -24 - $n * 14 ) . '"' );
	}
	$html .= k3_tag( 'Копали здесь — страницы нет', 50, 6, 'data-depth="20"' );
	return k3_stage( $html, $mode );
}

/* ------------------------------------------------------------
 * ЗАЕЗД ЧЕРЕЗ КАНАВУ: канава, в ней труба, сверху отсыпан переезд;
 * по переезду «бежит» путь машины.
 * ------------------------------------------------------------ */
function k3_scene_crossing( $mode = 'hero' ) {
	$c   = k3_soil();
	$iso = new K3_Iso( 276, 150, 26 );
	$W   = 10;
	$D   = 8;
	$tx  = 4;
	$tw  = 2;
	$bot = 0.8;
	$h   = 3.0;
	$g   = $iso->box( 0, 0, 0, $W, $D, $bot, $c['clay'] );
	$g  .= $iso->box( 0, 0, $bot, $tx, $D, $h - $bot, $c['loam'], $c['grass'] );
	$g  .= $iso->box( $tx + $tw, 0, $bot, $W - $tx - $tw, $D, $h - $bot, $c['loam'], $c['grass'] );
	$html = k3_layer( $g, 'data-depth="8"' );

	// Труба вдоль канавы (пропускает воду под переездом).
	$zc    = $bot + 0.45;
	$a     = $iso->p( $tx + 1, 0, $zc );
	$b     = $iso->p( $tx + 1, $D, $zc );
	$pipe  = "<line x1='{$a[0]}' y1='{$a[1]}' x2='{$b[0]}' y2='{$b[1]}' stroke='#6f757d' stroke-width='24'/>";
	$pipe .= "<line x1='{$a[0]}' y1='" . ( $a[1] - 7 ) . "' x2='{$b[0]}' y2='" . ( $b[1] - 7 ) . "' stroke='#a3a9b0' stroke-width='4'/>";
	$pipe .= "<circle cx='{$b[0]}' cy='{$b[1]}' r='12' fill='#8a9097'/><circle cx='{$b[0]}' cy='{$b[1]}' r='8' fill='#1d2a33'/>";
	$html .= k3_layer( $pipe, 'data-depth="14" data-ey="-50"' );

	// Отсыпка переезда над трубой.
	$fill  = $iso->box( $tx, 2.8, $bot + 0.9, $tw, 2.4, $h - $bot - 0.9, '#8a8f96', '#a7acb2' );
	$html .= k3_layer( $fill, 'data-depth="18" data-ey="-90"' );

	// Путь машины поперёк канавы.
	$p1    = $iso->p( 0.6, 4, $h + 0.02 );
	$p2    = $iso->p( $W - 0.6, 4, $h + 0.02 );
	$path  = "<line class='k3a-march' x1='{$p1[0]}' y1='{$p1[1]}' x2='{$p2[0]}' y2='{$p2[1]}' stroke='#ffb15c' stroke-width='3' stroke-dasharray='10 8'/>";
	$path .= "<circle cx='{$p2[0]}' cy='{$p2[1]}' r='6' fill='#f28c1b'/>";
	$html .= k3_layer( $path, 'data-depth="22" data-ey="-90"' );
	$html .= k3_tag( 'Переезд через канаву', 58, 8, 'data-depth="22" data-ey="-90"' );
	$html .= k3_tag( 'Труба пропускает воду', 2, 70, 'data-depth="14" data-ey="-50"' );
	return k3_stage( $html, $mode );
}

/* ------------------------------------------------------------
 * ДРЕНАЖНЫЕ КАНАВЫ: по дну канавы бежит вода, стрелки уклона.
 * ------------------------------------------------------------ */
function k3_scene_drain( $mode = 'hero' ) {
	$c   = k3_soil();
	$iso = new K3_Iso( 276, 140, 26 );
	$W   = 10;
	$D   = 8;
	$tx  = 4;
	$tw  = 2;
	$bot = 1.0;
	$h   = 3.0;
	$g   = $iso->box( 0, 0, 0, $W, $D, $bot, $c['clay'] );
	$html = k3_layer( $g, 'data-depth="6"' );
	$side  = $iso->box( 0, 0, $bot, $tx, $D, $h - $bot, $c['loam'], $c['grass'] );
	$side2 = $iso->box( $tx + $tw, 0, $bot, $W - $tx - $tw, $D, $h - $bot, $c['loam'], $c['grass'] );
	$html .= k3_layer( $side, 'data-depth="10" data-ex="-26" data-ey="-14"' );

	// Вода на дне канавы и её течение.
	$water  = $iso->flat( array( array( $tx, 0 ), array( $tx + $tw, 0 ), array( $tx + $tw, $D ), array( $tx, $D ) ), $bot + 0.15, "fill='#3b82c4' fill-opacity='.75'" );
	foreach ( array( 4.5, 5.0, 5.5 ) as $i => $x ) {
		$a      = $iso->p( $x, 0.3, $bot + 0.17 );
		$b      = $iso->p( $x, $D - 0.3, $bot + 0.17 );
		$water .= "<line class='k3a-flow' style='animation-delay:-" . ( $i * 0.4 ) . "s' x1='{$a[0]}' y1='{$a[1]}' x2='{$b[0]}' y2='{$b[1]}' stroke='#bfe0ff' stroke-width='2' stroke-dasharray='14 22' stroke-linecap='round'/>";
	}
	$html .= k3_layer( $water, 'data-depth="12"' );
	$html .= k3_layer( $side2, 'data-depth="14" data-ex="26" data-ey="14"' );

	// Капли сверху — вода уходит с участка в канаву.
	$drops = '';
	foreach ( array( array( 2.0, 2.0, 0 ), array( 8.0, 3.0, 0.8 ), array( 2.4, 6.0, 1.6 ), array( 7.6, 6.2, 2.2 ) ) as $q ) {
		$p      = $iso->p( $q[0], $q[1], $h + 2.2 );
		$drops .= "<g class='k3a-fall' style='animation-delay:{$q[2]}s'><path d='M{$p[0]} " . ( $p[1] - 8 ) . " q6 9 0 14 q-6 -5 0 -14z' fill='#7cc0ff'/></g>";
	}
	$html .= k3_layer( $drops, 'data-depth="24"' );
	$html .= k3_tag( 'Вода уходит по канаве', 56, 10, 'data-depth="24"' );
	$html .= k3_tag( 'Ровный профиль и уклон', 0, 72, 'data-depth="12"' );
	return k3_stage( $html, $mode );
}

/* ------------------------------------------------------------
 * РАСЧИСТКА УЧАСТКА: кусты и пни исчезают, остаётся чистая
 * площадка; заросль сдвигается в отвал.
 * ------------------------------------------------------------ */
function k3_scene_clear( $mode = 'hero' ) {
	$c   = k3_soil();
	$iso = new K3_Iso( 267, 130, 26 );
	$W   = 12;
	$D   = 9;
	$g   = $iso->box( 0, 0, 0, $W, $D, 1.0, $c['loam'] ) . $iso->box( 0, 0, 1.0, $W, $D, 0.4, $c['dirt'], '#8a6846' );
	$html = k3_layer( $g, 'data-depth="8"' );

	// Заросль: кусты (зелёные «воксели») и пни.
	$bush  = '';
	$spots = array( array( 1.5, 1.5 ), array( 4.5, 1.2 ), array( 8.0, 1.8 ), array( 2.2, 5.0 ), array( 6.0, 4.6 ), array( 9.4, 5.4 ), array( 4.0, 7.2 ), array( 7.6, 7.2 ) );
	foreach ( $spots as $i => $s ) {
		$bush .= "<g class='k3a-bob' style='animation-delay:-" . ( $i * 0.3 ) . "s'>"
			. $iso->box( $s[0], $s[1], 1.4, 1.2, 1.2, 0.7, '#4f7a32', '#6a9a44' )
			. $iso->box( $s[0] + 0.25, $s[1] + 0.25, 2.1, 0.7, 0.7, 0.5, '#5b8a3a', '#78aa4e' ) . '</g>';
	}
	$html .= k3_layer( $bush, 'data-depth="16" data-ey="34" data-op="0"' );

	// Отвал сбоку — растёт при расчистке.
	$pile  = $iso->box( 10.4, 0.4, 1.4, 1.4, 3.0, 0.6, '#4a5a32', '#5f7340' ) . $iso->box( 10.6, 0.8, 2.0, 1.0, 2.2, 0.5, '#4a5a32', '#6a7f48' );
	$html .= k3_layer( $pile, 'data-depth="12" data-o0=".25" data-op="1"' );

	$plane = $iso->flat( array( array( 0.2, 0.2 ), array( 10, 0.2 ), array( 10, 8.8 ), array( 0.2, 8.8 ) ), 1.42, "class='k3a-march' fill='none' stroke='#f28c1b' stroke-width='2' stroke-dasharray='8 6'" );
	$html .= k3_layer( $plane, 'data-depth="10"' );
	$html .= k3_tag( 'Кустарник и дернина', 4, 10, 'data-depth="16"' );
	$html .= k3_tag( 'Сдвигаю в отвал', 70, 22, 'data-depth="12"' );
	$html .= k3_tag( 'Площадка под стройку', 34, 86, 'data-depth="8"' );
	return k3_stage( $html, $mode );
}

/* ------------------------------------------------------------
 * УБОРКА СНЕГА: идёт снег, слой снега расчищается,
 * снег собирается в кучу.
 * ------------------------------------------------------------ */
function k3_scene_snow( $mode = 'hero' ) {
	$iso = new K3_Iso( 267, 140, 26 );
	$W   = 12;
	$D   = 9;
	$g   = $iso->box( 0, 0, 0, $W, $D, 0.8, '#55595f', '#6b7077' );
	$html = k3_layer( $g, 'data-depth="6"' );

	// Снежный покров — уходит при расчистке.
	$snow  = $iso->box( 0, 0, 0.8, $W, $D, 0.45, '#dfe7ee', '#f6fafd' );
	$html .= k3_layer( $snow, 'data-depth="10" data-ey="10" data-op="0"' );

	// Расчищенная полоса и куча снега.
	$lane  = $iso->flat( array( array( 0, 3.5 ), array( $W, 3.5 ), array( $W, 5.5 ), array( 0, 5.5 ) ), 0.81, "fill='#3f4349'" );
	$lane .= $iso->line( 0.5, 4.5, $W - 0.5, 4.5, 0.82, "class='k3a-march' stroke='#f28c1b' stroke-width='2' stroke-dasharray='10 10'" );
	$html .= k3_layer( $lane, 'data-depth="8"' );
	$pile  = $iso->box( 9.6, 0.6, 0.8, 2.0, 2.4, 0.9, '#dfe7ee', '#f6fafd' ) . $iso->box( 10.0, 1.0, 1.7, 1.2, 1.6, 0.7, '#dfe7ee', '#ffffff' );
	$html .= k3_layer( $pile, 'data-depth="16" data-ey="-20"' );

	// Снегопад.
	$fl = '';
	for ( $i = 0; $i < 14; $i++ ) {
		$x   = 40 + ( $i * 71 ) % 520;
		$y   = 20 + ( $i * 37 ) % 200;
		$r   = 2 + ( $i % 3 );
		$fl .= "<g class='k3a-snow' style='animation-delay:-" . ( ( $i * 0.47 ) % 4 ) . "s'><circle cx='$x' cy='$y' r='$r' fill='#ffffff' opacity='.85'/></g>";
	}
	$html .= k3_layer( $fl, 'data-depth="30"' );
	$html .= k3_tag( 'Расчистка проезда', 4, 12, 'data-depth="10"' );
	$html .= k3_tag( 'Снег — в кучу', 66, 20, 'data-depth="16" data-ey="-20"' );
	return k3_stage( $html, $mode );
}
