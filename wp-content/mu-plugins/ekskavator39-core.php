<?php
/**
 * Plugin Name: ЭКСКАВАТОР 39 — обязательные доработки
 * Description: 152-ФЗ (локальные шрифты, cookie-баннер, Метрика после согласия, реквизиты), панель «Позвонить / MAX», SEO-правки. Живёт отдельно от темы, поэтому не стирается при замене темы.
 * Version: 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'EK39_ASSETS_DIR', WP_CONTENT_DIR . '/ekskavator39-assets' );
define( 'EK39_ASSETS_URL', content_url( 'ekskavator39-assets' ) );
define( 'EK39_MAX_URL', 'https://max.ru/u/f9LHodD0cOIrvN0FFdZ7L0gkKzMSf9hW5uft4JFkwy6TdEv9uPFiiBCnnfk' );
define( 'EK39_PHONE_RAW', '+79521100609' );
define( 'EK39_CONSENT_PATH', '/soglasie-na-obrabotku-personalnyh-dannyh/' );

/* =========================================================
 * 1. Шрифты и иконки с нашего сервера (152-ФЗ: без Google и Cloudflare)
 * ========================================================= */
add_action( 'wp_enqueue_scripts', function() {
    $map = array(
        'kopka-fonts'   => '/fonts/inter-outfit.css',
        'kopka3d-fonts' => '/fonts/unbounded.css',
        'font-awesome'  => '/fontawesome/css/all.min.css',
    );
    foreach ( $map as $handle => $file ) {
        if ( ! file_exists( EK39_ASSETS_DIR . $file ) ) {
            continue;
        }
        $was = wp_style_is( $handle, 'enqueued' ) || wp_style_is( $handle, 'registered' );
        if ( ! $was ) {
            continue;
        }
        wp_deregister_style( $handle );
        wp_register_style( $handle, EK39_ASSETS_URL . $file, array(), filemtime( EK39_ASSETS_DIR . $file ) );
        wp_enqueue_style( $handle );
    }
    if ( file_exists( EK39_ASSETS_DIR . '/extras.css' ) ) {
        wp_enqueue_style( 'ek39-extras', EK39_ASSETS_URL . '/extras.css', array(), filemtime( EK39_ASSETS_DIR . '/extras.css' ) );
    }
}, 99 );

add_filter( 'wp_resource_hints', function( $urls, $type ) {
    if ( 'dns-prefetch' !== $type && 'preconnect' !== $type ) {
        return $urls;
    }
    return array_values( array_filter( $urls, function( $u ) {
        $h = is_array( $u ) ? ( isset( $u['href'] ) ? $u['href'] : '' ) : $u;
        return false === strpos( $h, 'fonts.googleapis.com' )
            && false === strpos( $h, 'fonts.gstatic.com' )
            && false === strpos( $h, 'cdnjs.cloudflare.com' );
    } ) );
}, 20, 2 );

/* =========================================================
 * 2. Реквизиты и ссылки в подвале
 * ========================================================= */
function ek39_legal_line() {
    $links  = array();
    $policy = function_exists( 'get_privacy_policy_url' ) ? get_privacy_policy_url() : '';
    if ( $policy ) {
        $links[] = '<a href="' . esc_url( $policy ) . '">Политика обработки персональных данных</a>';
    }
    $links[] = '<a href="' . esc_url( home_url( EK39_CONSENT_PATH ) ) . '">Согласие на обработку персональных данных</a>';
    $links[] = '<a href="#" data-cookie-settings>Настройки cookie</a>';

    return '<div class="kopka-legal">'
        . '<p class="kopka-legal__links">' . implode( ' <span aria-hidden="true">·</span> ', $links ) . '</p>'
        . '</div>';
}

add_filter( 'theme_mod_pressmegpt_custom_footer_html', function( $html ) {
    if ( ! is_string( $html ) || false !== strpos( $html, 'kopka-legal' ) ) {
        return $html;
    }
    $pos = strrpos( $html, '</footer>' );
    if ( false === $pos ) {
        return $html;
    }
    return substr( $html, 0, $pos ) . ek39_legal_line() . substr( $html, $pos );
}, 20 );

/* =========================================================
 * 3. Cookie-баннер. Яндекс Метрика — только после «Принять все»
 * ========================================================= */
add_action( 'wp_footer', function() {
    if ( is_admin() ) {
        return;
    }
    $consent_url = esc_url( home_url( EK39_CONSENT_PATH ) );
    ?>
    <div id="kopka-cookie" class="kopka-cookie" role="dialog" aria-live="polite" aria-label="Использование cookie" hidden>
        <p class="kopka-cookie__text">Сайт использует cookie. Обязательные нужны для работы сайта. С вашего согласия также подключается Яндекс Метрика — она помогает понять, как посетители пользуются сайтом. <a href="<?php echo $consent_url; ?>">Подробнее</a></p>
        <div class="kopka-cookie__buttons">
            <button type="button" class="kopka-cookie__btn" data-consent="all">Принять все</button>
            <button type="button" class="kopka-cookie__btn" data-consent="necessary">Только обязательные</button>
        </div>
    </div>
    <script>
    (function () {
        var NAME = 'kopka_consent';
        var box = document.getElementById('kopka-cookie');
        function read() {
            var m = document.cookie.match(/(?:^|; )kopka_consent=([^;]+)/);
            return m ? m[1] : null;
        }
        function save(v) {
            document.cookie = NAME + '=' + v + '; path=/; max-age=31536000; SameSite=Lax';
        }
        function dropYm() {
            document.cookie.split('; ').forEach(function (c) {
                var n = c.split('=')[0];
                if (n.indexOf('_ym') === 0) {
                    document.cookie = n + '=; path=/; max-age=0';
                    document.cookie = n + '=; path=/; max-age=0; domain=.' + location.hostname;
                }
            });
        }
        function loadMetrika() {
            if (window.kopkaYmLoaded) { return; }
            window.kopkaYmLoaded = true;
            (function (m, e, t, r, i, k, a) {
                m[i] = m[i] || function () { (m[i].a = m[i].a || []).push(arguments); };
                m[i].l = 1 * new Date();
                for (var j = 0; j < document.scripts.length; j++) { if (document.scripts[j].src === r) { return; } }
                k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a);
            })(window, document, 'script', 'https://mc.yandex.ru/metrika/tag.js?id=112429357', 'ym');
            ym(112429357, 'init', { ssr: true, webvisor: true, clickmap: true, ecommerce: 'dataLayer', referrer: document.referrer, url: location.href, accurateTrackBounce: true, trackLinks: true });
        }
        var state = read();
        if (state === 'all') { loadMetrika(); }
        if (!state && box) { box.hidden = false; }
        if (box) {
            box.addEventListener('click', function (e) {
                var v = e.target.getAttribute('data-consent');
                if (!v) { return; }
                save(v);
                box.hidden = true;
                if (v === 'all') { loadMetrika(); } else { dropYm(); }
            });
        }
        document.addEventListener('click', function (e) {
            var t = e.target.closest ? e.target.closest('[data-cookie-settings]') : null;
            if (t && box) { e.preventDefault(); box.hidden = false; }
        });
    })();
    </script>
    <?php
}, 30 );

/* =========================================================
 * 4. Мобильная панель «Позвонить / Написать в MAX»
 * ========================================================= */
add_action( 'wp_footer', function() {
    if ( is_admin() ) {
        return;
    }
    ?>
    <div class="kopka-sticky-cta" role="complementary" aria-label="Быстрая связь">
        <a class="kopka-sticky-cta__btn kopka-sticky-cta__btn--call" href="tel:<?php echo esc_attr( EK39_PHONE_RAW ); ?>">
            <i class="fas fa-phone" aria-hidden="true"></i><span>Позвонить</span>
        </a>
        <a class="kopka-sticky-cta__btn kopka-sticky-cta__btn--max" href="<?php echo esc_url( EK39_MAX_URL ); ?>" target="_blank" rel="noopener">
            <i class="fas fa-comment-dots" aria-hidden="true"></i><span>Написать в MAX</span>
        </a>
    </div>
    <?php
}, 22 );

/* Кнопка в шапке: слово «Позвонить» вместо номера. */
add_filter( 'theme_mod_pressmegpt_custom_header_html', function( $html ) {
    if ( ! is_string( $html ) ) {
        return $html;
    }
    return preg_replace( '#(class="[^"]*fse-button-phone[^"]*"[^>]*>)\s*\+7[^<]*(</a>)#u', '$1Позвонить$2', $html );
}, 30 );

/* =========================================================
 * 5. SEO для страниц, которых нет в карте темы
 * ========================================================= */
function ek39_seo_extra() {
    return array(
        'zaezd-cherez-kanavu' => array(
            'title' => 'Заезд на участок через канаву в Калининграде — цена и заказ',
            'desc'  => 'Устройство заезда на участок через канаву в Калининграде и области: труба в русло, отсыпка, планировка, укладка ваших плит. Почасовая оплата.',
        ),
        'drenazhnye-kanavy'   => array(
            'title' => 'Копка и чистка дренажных канав в Калининграде — цена',
            'desc'  => 'Копаю, чищу и углубляю дренажные канавы в Калининграде и области: новая канава, чистка заиленной, восстановление русла. Почасовая оплата.',
        ),
        'raschistka-uchastka' => array(
            'title' => 'Расчистка участка в Калининграде — цена и заказ',
            'desc'  => 'Расчистка участка от кустарника, мелколесья и пней в Калининграде и области: корчевание, снятие дернины, планировка после расчистки. Почасовая оплата.',
        ),
        'uborka-snega'        => array(
            'title' => 'Уборка снега в Калининграде трактором — цена за час',
            'desc'  => 'Уборка снега трактором-погрузчиком в Калининграде и области: дворы и въезды, проезды в СНТ, площадки и парковки. Погрузка снега в самосвалы. 3000 ₽/час.',
        ),
    );
}

function ek39_seo_current_extra() {
    if ( ! is_page() ) {
        return null;
    }
    if ( function_exists( 'kopka39_seo_current' ) ) {
        $theme = kopka39_seo_current();
        if ( $theme && ! empty( $theme['title'] ) ) {
            return null;
        }
    }
    $slug = get_post_field( 'post_name', get_queried_object_id() );
    $map  = ek39_seo_extra();
    if ( $slug && isset( $map[ $slug ] ) ) {
        return $map[ $slug ];
    }
    /* Статьи «Полезного»: title и description хранятся в мета-полях страницы. */
    $pid = get_queried_object_id();
    $t   = get_post_meta( $pid, '_ek39_title', true );
    return $t ? array( 'title' => $t, 'desc' => (string) get_post_meta( $pid, '_ek39_desc', true ) ) : null;
}

add_filter( 'pre_get_document_title', function( $title ) {
    $d = ek39_seo_current_extra();
    return $d ? $d['title'] : $title;
}, 20 );

add_action( 'wp_head', function() {
    $d = ek39_seo_current_extra();
    if ( $d ) {
        echo '<meta name="description" content="' . esc_attr( $d['desc'] ) . '">' . "\n";
    }
}, 2 );

/* =========================================================
 * 6. Sitemap без авторов, страницы авторов не индексируются
 * ========================================================= */
add_filter( 'wp_sitemaps_add_provider', function( $provider, $name ) {
    return ( 'users' === $name ) ? false : $provider;
}, 10, 2 );

add_action( 'wp_head', function() {
    if ( is_author() ) {
        echo '<meta name="robots" content="noindex, follow">' . "\n";
    }
}, 1 );

/* Старый адрес wp-sitemap-users-1.xml Яндекс помнит — отдаём валидный sitemap с главной. */
add_action( 'parse_request', function() {
    $path = parse_url( isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '', PHP_URL_PATH );
    if ( $path && preg_match( '#/wp-sitemap-users-\d+\.xml$#', $path ) ) {
        status_header( 200 );
        nocache_headers();
        header( 'Content-Type: application/xml; charset=UTF-8' );
        header( 'X-Robots-Tag: noindex' );
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        echo '<url><loc>' . esc_url( home_url( '/' ) ) . '</loc></url>' . "\n";
        echo '</urlset>' . "\n";
        exit;
    }
}, 0 );

/* =========================================================
 * 7. robots.txt: поиск и RSS закрыты, служебные параметры склеиваются
 * ========================================================= */
add_filter( 'robots_txt', function( $output, $public ) {
    if ( ! $public || false !== strpos( $output, 'Clean-param' ) ) {
        return $output;
    }
    $rules  = "Disallow: /?s=\n";
    $rules .= "Disallow: /search/\n";
    $rules .= "Disallow: /feed/\n";
    $rules .= "Disallow: */feed/\n";
    $anchor = "Allow: /wp-admin/admin-ajax.php\n";
    if ( false !== strpos( $output, $anchor ) ) {
        $output = str_replace( $anchor, $anchor . $rules, $output );
    } else {
        $output .= $rules;
    }
    $output  = rtrim( $output ) . "\n\n";
    $output .= "Clean-param: utm_source&utm_medium&utm_campaign&utm_content&utm_term&yclid&ysclid&gclid&fbclid&_openstat&from&v&n\n";
    return $output;
}, 20, 2 );

/* Главный экран: машина заметно «разбирается» на узлы, и сильнее, чем в теме. */
add_action( 'wp_enqueue_scripts', function () {
    if ( ! wp_script_is( 'kopka3d-scenes', 'registered' ) ) { return; }
    wp_add_inline_script( 'kopka3d-scenes', "(function(){var s=document.querySelector('.k3-hero [data-k3-stage][data-k3-mode=\"hero\"]');if(!s)return;if(s.getBoundingClientRect().top>window.innerHeight*0.45)s.setAttribute('data-k3-mode','inview');[].forEach.call(s.querySelectorAll('[data-ex],[data-ey]'),function(el){['data-ex','data-ey'].forEach(function(a){var v=el.getAttribute(a);if(v!==null)el.setAttribute(a,Math.round(parseFloat(v)*(a==='data-ex'?0.7:1.3)));});});})();", 'before' );
}, 100 );

/* Сцены: своя копия k3-scenes.js — разбор ещё и по наведению курсора (на телефоне — по нажатию). */
add_action( 'wp_enqueue_scripts', function () {
    $f = EK39_ASSETS_DIR . '/k3-scenes.js';
    $s = wp_scripts();
    if ( file_exists( $f ) && isset( $s->registered['kopka3d-scenes'] ) ) {
        $s->registered['kopka3d-scenes']->src = EK39_ASSETS_URL . '/k3-scenes.js';
        $s->registered['kopka3d-scenes']->ver = (string) filemtime( $f );
    }
}, 101 );
