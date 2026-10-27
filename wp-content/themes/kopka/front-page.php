<?php
/**
 * Главная страница — ЭКСКАВАТОР 39
 *
 * Контакты и параметры техники редактируются в одном файле:
 * wp-content/themes/kopka/inc/site-data.php
 *
 * @package kopka
 */

require_once get_template_directory() . '/inc/site-data.php';

$d        = kopka_site_data();
$tel      = 'tel:' . $d['phone_raw'];
$tg       = $d['telegram'];
$tg_handle = $d['telegram_handle'];
$max      = $d['max'];

get_header();
?>

<div class="theme-designer-homepage k3-home">
<main>

<!-- ===== HERO (псевдо-3D) ===== -->
<?php include get_template_directory() . '/template-parts/hero-3d.php'; ?>

<!-- ===== УСЛУГИ ===== -->
<section id="services" class="fse-section fse-section-dark fse-services">
  <div class="fse-group fse-section-header animate-on-scroll">
    <h2 class="fse-heading">Какие работы выполняю</h2>
    <p class="fse-paragraph">Экскаватор-погрузчик — универсальная машина: сзади копающее оборудование, спереди ковш для погрузки и планировки. За один выезд обычно удаётся закрыть несколько задач: выкопать, разровнять, переместить грунт, засыпать обратно.</p>
  </div>
  <div class="fse-group fse-services-grid">

    <div class="fse-card fse-service-card animate-on-scroll">
      <div class="fse-card-icon"><i class="fas fa-digging fse-icon"></i></div>
      <h3 class="fse-heading">Копка траншей</h3>
      <p class="fse-paragraph">Под водопровод, канализацию, дренаж и электрокабель. Длина и глубина обсуждаются по вашей задаче.</p>
      <a href="/kopka-transhey/" class="fse-button fse-button-secondary">Подробнее</a>
    </div>

    <div class="fse-card fse-service-card animate-on-scroll">
      <div class="fse-card-icon"><i class="fas fa-mountain fse-icon"></i></div>
      <h3 class="fse-heading">Котлован под фундамент</h3>
      <p class="fse-paragraph">Выемка грунта под ленту, плиту или подвал — в пределах возможностей техники.</p>
      <a href="/kotlovan-pod-fundament/" class="fse-button fse-button-secondary">Подробнее</a>
    </div>

    <div class="fse-card fse-service-card animate-on-scroll">
      <div class="fse-card-icon"><i class="fas fa-ruler-combined fse-icon"></i></div>
      <h3 class="fse-heading">Планировка участка</h3>
      <p class="fse-paragraph">Снятие бугров, засыпка ям, выведение площадки под строительство или газон.</p>
      <a href="/planirovka-uchastka/" class="fse-button fse-button-secondary">Подробнее</a>
    </div>

    <div class="fse-card fse-service-card animate-on-scroll">
      <div class="fse-card-icon"><i class="fas fa-truck-loading fse-icon"></i></div>
      <h3 class="fse-heading">Погрузка и перемещение грунта</h3>
      <p class="fse-paragraph">Грунт, песок, щебень, строительный мусор: погрузить, перекинуть по участку, подготовить к вывозу.</p>
      <a href="/pogruzka-grunta/" class="fse-button fse-button-secondary">Подробнее</a>
    </div>

    <div class="fse-card fse-service-card animate-on-scroll">
      <div class="fse-card-icon"><i class="fas fa-layer-group fse-icon"></i></div>
      <h3 class="fse-heading">Обратная засыпка</h3>
      <p class="fse-paragraph">Засыпка траншей и пазух котлована после того, как коммуникации уложены и проверены.</p>
      <a href="/obratnaya-zasypka/" class="fse-button fse-button-secondary">Подробнее</a>
    </div>

    <div class="fse-card fse-service-card animate-on-scroll">
      <div class="fse-card-icon"><i class="fas fa-hard-hat fse-icon"></i></div>
      <h3 class="fse-heading">Земляные работы по участку</h3>
      <p class="fse-paragraph">Комплекс из нескольких операций за один выезд — это дешевле, чем вызывать технику по отдельности.</p>
      <a href="/zemlyanye-raboty/" class="fse-button fse-button-secondary">Подробнее</a>
    </div>

    <div class="fse-card fse-service-card animate-on-scroll">
      <div class="fse-card-icon"><i class="fas fa-tractor fse-icon"></i></div>
      <h3 class="fse-heading">Аренда экскаватора с оператором</h3>
      <p class="fse-paragraph">Почасовая работа на вашем объекте под вашу задачу.</p>
      <a href="/arenda-ekskavatora/" class="fse-button fse-button-secondary">Подробнее</a>
    </div>

    <div class="fse-card fse-service-card animate-on-scroll">
      <div class="fse-card-icon"><i class="fas fa-water fse-icon"></i></div>
      <h3 class="fse-heading">Разработка грунта под коммуникации</h3>
      <p class="fse-paragraph">Только копка. Монтаж труб и кабеля выполняют ваши специалисты — это их зона ответственности и гарантии.</p>
      <a href="/kopka-transhey/" class="fse-button fse-button-secondary">Подробнее</a>
    </div>
    <div class="fse-card fse-service-card animate-on-scroll">
      <div class="fse-card-icon"><i class="fas fa-droplet fse-icon"></i></div>
      <h3 class="fse-heading">Дренажные канавы</h3>
      <p class="fse-paragraph">Копаю и чищу канавы для отвода воды с участка, вдоль дорог и в СНТ.</p>
      <a href="/drenazhnye-kanavy/" class="fse-button fse-button-secondary">Подробнее</a>
    </div>
    <div class="fse-card fse-service-card animate-on-scroll">
      <div class="fse-card-icon"><i class="fas fa-road fse-icon"></i></div>
      <h3 class="fse-heading">Заезд через канаву</h3>
      <p class="fse-paragraph">Труба в русло, отсыпка и планировка въезда — чтобы машины спокойно заезжали на участок.</p>
      <a href="/zaezd-cherez-kanavu/" class="fse-button fse-button-secondary">Подробнее</a>
    </div>
    <div class="fse-card fse-service-card animate-on-scroll">
      <div class="fse-card-icon"><i class="fas fa-tree fse-icon"></i></div>
      <h3 class="fse-heading">Расчистка участка</h3>
      <p class="fse-paragraph">Кустарник, мелколесье, пни и дернина — освобождаю участок под стройку или планировку.</p>
      <a href="/raschistka-uchastka/" class="fse-button fse-button-secondary">Подробнее</a>
    </div>
    <div class="fse-card fse-service-card animate-on-scroll">
      <div class="fse-card-icon"><i class="fas fa-snowflake fse-icon"></i></div>
      <h3 class="fse-heading">Уборка снега</h3>
      <p class="fse-paragraph">Дворы и въезды, проезды в СНТ, площадки и парковки. Гружу снег в ваши самосвалы.</p>
      <a href="/uborka-snega/" class="fse-button fse-button-secondary">Подробнее</a>
    </div>

  </div>

  <div class="fse-group animate-on-scroll">
    <blockquote class="fse-quote">
      <p class="fse-paragraph"><strong>Важно и честно:</strong> гидромолот, бурение, вывоз грунта самосвалом, снегоуборка и демонтаж — это отдельные услуги, и без подтверждения я их не обещаю. Если задача выходит за возможности техники, скажу об этом сразу, а не после приезда.</p>
    </blockquote>
  </div>
</section>

<!-- ===== НАПРЯМУЮ С ОПЕРАТОРОМ ===== -->
<section id="about" class="fse-section fse-equipment">
  <div class="fse-group fse-columns">
    <div class="fse-column fse-text-column animate-slide-left">
      <h2 class="fse-heading">Работаете напрямую с оператором</h2>
      <p class="fse-paragraph">Обычная схема аренды: вы звоните в фирму, менеджер передаёт задачу водителю, водитель приезжает и выясняет, что на объекте всё иначе. Дальше — доплаты, переносы и «мне так не говорили».</p>
      <p class="fse-paragraph">Здесь этой цепочки нет: вы объясняете задачу мне, я сам приезжаю и сам её выполняю.</p>
      <p class="fse-paragraph">По фото участка и размерам я заранее понимаю, реально ли заехать техникой и сколько займёт работа. Если задача не под эту машину — скажу до выезда, а не на месте.</p>
      <ul class="fse-feature-list">
        <li><i class="fas fa-check-circle fse-icon"></i> Один номер от звонка до окончания работы</li>
        <li><i class="fas fa-check-circle fse-icon"></i> Оплата по факту: <?php kopka_e( 'price_hour' ); ?>, минимум — <?php kopka_e( 'min_order' ); ?></li>
        <li><i class="fas fa-check-circle fse-icon"></i> О сложностях предупреждаю до выезда</li>
      </ul>
      <a href="<?php echo esc_attr( $tel ); ?>" class="fse-button fse-button-primary">Обсудить задачу</a>
    </div>
    <div class="fse-column fse-image-column animate-slide-right">
      <picture>
        <source srcset="<?php echo esc_url( get_template_directory_uri() . '/img/operator-ekskavatora-pogruzchika.webp' ); ?>" type="image/webp">
        <img src="<?php echo esc_url( get_template_directory_uri() . '/img/operator-ekskavatora-pogruzchika.jpg' ); ?>" alt="Оператор экскаватора-погрузчика ЭКСКАВАТОР 39" class="fse-image" width="900" height="1350" loading="lazy">
      </picture>
    </div>
  </div>
</section>

<!-- ===== ТЕХНИКА ===== -->
<section id="equipment" class="fse-section fse-section-dark">
  <div class="fse-group fse-section-header animate-on-scroll">
    <h2 class="fse-heading">Техника</h2>
    <p class="fse-paragraph">Работаю на экскаваторе-погрузчике — колёсной машине, которая совмещает функции экскаватора и фронтального погрузчика. Она передвигается своим ходом, заезжает на участок через обычные ворота при достаточной ширине проезда и не требует трала для перемещения в пределах города.</p>
  </div>
  <div class="fse-group animate-on-scroll">
    <figure class="fse-photo">
      <picture>
        <source srcset="<?php echo esc_url( get_template_directory_uri() . '/img/ekskavator-pogruzchik-tehnika.webp' ); ?>" type="image/webp">
        <img src="<?php echo esc_url( get_template_directory_uri() . '/img/ekskavator-pogruzchik-tehnika.jpg' ); ?>" alt="Экскаватор-погрузчик ЭКСКАВАТОР 39 с передним ковшом и обратной лопатой" class="fse-image" width="900" height="675" loading="lazy">
      </picture>
      <figcaption class="fse-photo-caption">Та самая машина, которая приедет к вам на объект: передний ковш для погрузки и планировки, задняя стрела для копки.</figcaption>
    </figure>
    <table class="fse-table">
      <tbody>
        <tr><th>Марка и модель</th><td><?php kopka_e( 'machine_model' ); ?></td></tr>
        <tr><th>Глубина копания</th><td><?php kopka_e( 'dig_depth' ); ?></td></tr>
        <tr><th>Ширина ковшей</th><td><?php kopka_e( 'bucket_width' ); ?></td></tr>
        <tr><th>Объём переднего ковша</th><td><?php kopka_e( 'front_bucket' ); ?></td></tr>
        <tr><th>Навесное оборудование</th><td><?php kopka_e( 'attachments' ); ?></td></tr>
      </tbody>
    </table>
    <p class="fse-paragraph"><a href="/tehnika/">Подробнее о технике</a></p>
  </div>
</section>

<!-- ===== ЦЕНЫ ===== -->
<section id="prices" class="fse-section fse-prices fse-section-dark-alt">
  <div class="fse-group fse-section-header animate-on-scroll">
    <h2 class="fse-heading">Сколько стоит и как считается</h2>
    <p class="fse-paragraph">Назвать точную сумму по телефону, не видя объекта, невозможно: скорость земляных работ сильно зависит от условий. Одна и та же по размерам траншея в песке и в плотном суглинке с камнями копается за разное время, и цифра «навскидку» почти всегда расходится с тем, что окажется на участке. Поэтому расчёт идёт по часам, а ориентир по времени я даю после того, как посмотрю фото участка и размеры.</p>
  </div>
  <div class="fse-group k3-inline-scene"><?php echo k3_scene_hours( 'inview' ); // phpcs:ignore ?></div>

  <!-- Единый блок: работа + подача = итоговая стоимость -->
  <div class="fse-group fse-price-card animate-on-scroll">
    <h3 class="fse-heading">Экскаватор-погрузчик с оператором</h3>
    <p class="fse-paragraph fse-price-value"><span class="fse-highlight-price"><?php kopka_e( 'price_hour' ); ?></span></p>
    <p class="fse-paragraph">Минимальный заказ — <?php kopka_e( 'min_order' ); ?>. Оплата — по фактически отработанному времени на объекте.</p>

    <h4 class="fse-heading fse-price-subhead">Подача техники на объект</h4>
    <p class="fse-paragraph">Машина приезжает своим ходом, поэтому дорога считается отдельно от часов работы. Сумма подачи согласуется при обращении, ориентиры такие:</p>
    <table class="fse-table">
      <tbody>
        <tr><th>По Калининграду</th><td><?php kopka_e( 'delivery_city' ); ?></td></tr>
        <tr><th>За городом, в пределах 15 км</th><td><?php kopka_e( 'delivery_15km' ); ?></td></tr>
        <tr><th>Дальше 15 км от города</th><td><?php kopka_e( 'delivery_far' ); ?></td></tr>
      </tbody>
    </table>

    <p class="fse-paragraph fse-price-formula"><strong>Итог складывается так:</strong> время работы × <?php kopka_e( 'price_hour_num' ); ?> ₽ (но не меньше <?php kopka_e( 'min_order_hours' ); ?> часов) + подача техники по вашему адресу.</p>
    <a href="<?php echo esc_attr( $tel ); ?>" class="fse-button fse-button-primary fse-button-large animate-scale">Узнать стоимость</a>
  </div>

  <div class="fse-group animate-on-scroll">
    <h3 class="fse-heading">Почему стоимость считается именно так</h3>
    <p class="fse-paragraph">Я работаю по часам, а не по фиксированной цене «за метр» или «за объект». Причина простая: две одинаковые по размерам траншеи — в сухом песке и в плотной глине с камнями — копаются за разное время. В тариф «за метр» эта разница всегда закладывается с запасом, и на лёгком грунте заказчик платит за сложные условия, которых у него на участке нет. При почасовой оплате вы платите за фактически выполненную работу.</p>
    <p class="fse-paragraph">Минимальный заказ — <?php kopka_e( 'min_order' ); ?>. Выезд состоит не только из копки: техника идёт на объект и обратно своим ходом, встаёт на позицию, на месте мы уточняем задачу. Из-за этого рабочий день делится на выезды, а не на отдельные часы, и взять заказ короче четырёх часов не получится, не подводя других клиентов на ту же дату.</p>
    <p class="fse-paragraph">Подача вынесена отдельной строкой, а не спрятана в стоимость часа. Иначе заказчики в черте города оплачивали бы в составе своей ставки чужие поездки за 30 км. Отдельная строка оставляет расчёт понятным: видно, сколько стоит работа, а сколько — дорога.</p>
    <p class="fse-paragraph">Все цифры известны до выезда. Если по фото участка и размерам видно, что задача не под эту машину, я скажу об этом заранее, а не на месте — так вы не платите за приезд впустую.</p>
  </div>

  <div class="fse-group animate-on-scroll">
    <h3 class="fse-heading">От чего зависит итог</h3>
    <ul class="fse-feature-list">
      <li><i class="fas fa-cube fse-icon"></i> <strong>Объём работ</strong> — длина, глубина и ширина траншеи или размеры котлована.</li>
      <li><i class="fas fa-layer-group fse-icon"></i> <strong>Тип грунта</strong> — песок, суглинок, глина, камни, строительный мусор, старый бетон.</li>
      <li><i class="fas fa-door-open fse-icon"></i> <strong>Доступ на участок</strong> — ширина ворот и проезда, постройки, забор, деревья, теплицы, уклон.</li>
      <li><i class="fas fa-route fse-icon"></i> <strong>Удалённость объекта</strong> — влияет на условия подачи техники.</li>
      <li><i class="fas fa-exclamation-triangle fse-icon"></i> <strong>Наличие коммуникаций</strong> — рядом с действующими трубами и кабелями темп намеренно снижается, часть грунта дорабатывается вручную.</li>
      <li><i class="fas fa-plus-circle fse-icon"></i> <strong>Дополнительные операции</strong> — планировка после копки, перемещение грунта, обратная засыпка.</li>
      <li><i class="fas fa-dolly fse-icon"></i> <strong>Куда девать вынутый грунт</strong> — распределить по участку или сложить в отвал под последующий вывоз.</li>
    </ul>
    <p class="fse-paragraph"><a href="/tseny/">Смотреть страницу цен</a></p>
  </div>
</section>

<!-- ===== ЧТО СООБЩИТЬ ДЛЯ РАСЧЁТА ===== -->
<section id="checklist" class="fse-section">
  <div class="fse-group fse-section-header animate-on-scroll">
    <h2 class="fse-heading">Что нужно сообщить для расчёта</h2>
    <p class="fse-paragraph">Чтобы я сориентировал вас по времени и стоимости, достаточно звонка или короткого сообщения — в Telegram или MAX, как вам удобнее.</p>
  </div>
  <div class="fse-group animate-on-scroll">
    <ol class="fse-list">
      <li><strong>Что нужно сделать</strong> — траншея, котлован, планировка, погрузка.</li>
      <li><strong>Размеры</strong> — длина, примерная глубина и ширина; для котлована — габариты в плане.</li>
      <li><strong>Адрес или населённый пункт</strong> — от этого зависят условия подачи.</li>
      <li><strong>Фото участка</strong> — 3–5 кадров: место работ, подъезд, ворота. Это самое полезное, что можно прислать.</li>
      <li><strong>Тип грунта, если знаете</strong> — песок, глина, суглинок, насыпной, каменистый.</li>
      <li><strong>Есть ли на участке коммуникации</strong> — трубы, кабель, септик, скважина, дренаж.</li>
      <li><strong>Желаемые сроки</strong> — конкретная дата или диапазон.</li>
      <li><strong>Куда девать грунт</strong> — распределить, оставить в отвале, подготовить к вывозу.</li>
    </ol>
    <p class="fse-paragraph">Если чего-то не знаете — не страшно, разберёмся по фото и на месте.</p>

    <div class="fse-calc-contacts">
      <p class="fse-paragraph"><strong>Куда прислать:</strong></p>
      <ul class="fse-contact-list">
        <li class="fse-contact-info"><strong>Телефон:</strong> <a href="<?php echo esc_attr( $tel ); ?>"><?php kopka_e( 'phone' ); ?></a></li>
        <li class="fse-contact-info"><strong>MAX:</strong> <?php echo kopka39_max_button_html( $max, 'fse-inline-max-link' ); ?> — удобнее всего для фото участка</li>
        <li class="fse-contact-info"><strong>Telegram:</strong> <a href="<?php echo esc_url( $tg ); ?>" target="_blank" rel="noopener">Написать в Telegram</a></li>
        <li class="fse-contact-info"><strong>Время связи:</strong> <?php kopka_e( 'work_hours' ); ?></li>
      </ul>
      <div class="fse-contact-buttons">
        <a href="<?php echo esc_attr( $tel ); ?>" class="fse-button fse-button-primary">Позвонить</a>
        <?php echo kopka39_max_button_html( $max, 'fse-button fse-button-secondary' ); ?>
        <a href="<?php echo esc_url( $tg ); ?>" class="fse-button fse-button-secondary" target="_blank" rel="noopener">Написать в Telegram</a>
      </div>
    </div>
  </div>
</section>

<!-- ===== КАК ЗАКАЗАТЬ ===== -->
<section id="how" class="fse-section fse-section-dark">
  <div class="fse-group fse-section-header animate-on-scroll">
    <h2 class="fse-heading">Как заказать</h2>
  </div>
  <div class="fse-group fse-services-grid">
    <div class="fse-card animate-on-scroll">
      <div class="fse-card-icon"><i class="fas fa-comment-dots fse-icon"></i></div>
      <h3 class="fse-heading">1. Опишите задачу</h3>
      <p class="fse-paragraph">Позвоните или напишите в MAX или Telegram. Фото и размеры ускоряют дело в разы.</p>
    </div>
    <div class="fse-card animate-on-scroll">
      <div class="fse-card-icon"><i class="fas fa-calendar-check fse-icon"></i></div>
      <h3 class="fse-heading">2. Согласуем время и условия</h3>
      <p class="fse-paragraph">Называю ориентировочное время работы, ставку и условия подачи. Если задача не под эту технику — скажу сразу.</p>
    </div>
    <div class="fse-card animate-on-scroll">
      <div class="fse-card-icon"><i class="fas fa-truck fse-icon"></i></div>
      <h3 class="fse-heading">3. Приезжаю в назначенный день</h3>
      <p class="fse-paragraph">На месте ещё раз проходим по разметке: где копать, какая глубина, куда складывать грунт.</p>
    </div>
    <div class="fse-card animate-on-scroll">
      <div class="fse-card-icon"><i class="fas fa-handshake fse-icon"></i></div>
      <h3 class="fse-heading">4. Работа и расчёт</h3>
      <p class="fse-paragraph">По окончании смотрим результат вместе. Оплата по фактически отработанному времени: <?php kopka_e( 'payment' ); ?></p>
    </div>
  </div>
</section>

<!-- ===== ВИДЫ РАБОТ ===== -->
<section id="portfolio" class="fse-section fse-portfolio">
  <div class="fse-group fse-section-header animate-on-scroll">
    <h2 class="fse-heading">Виды выполняемых работ</h2>
    <p class="fse-paragraph">Ниже — фотографии с реальных объектов в Калининграде и области. Снимал сам во время работы, поэтому кадры рабочие, без постановки.</p>
  </div>
  <div class="fse-group fse-portfolio-grid">
    <div class="fse-portfolio-item fse-card animate-on-scroll">
      <div class="fse-image-container">
        <picture>
          <source srcset="<?php echo esc_url( get_template_directory_uri() . '/img/kopka-transhei-pod-kommunikacii.webp' ); ?>" type="image/webp">
          <img src="<?php echo esc_url( get_template_directory_uri() . '/img/kopka-transhei-pod-kommunikacii.jpg' ); ?>" alt="Копка траншеи под коммуникации у частного дома в Калининградской области" class="fse-image" width="900" height="675" loading="lazy">
        </picture>
      </div>
      <h3 class="fse-heading">Траншеи под коммуникации</h3>
      <p class="fse-paragraph">Водопровод, канализация, кабель, дренаж. Копаю по разметке заказчика, грунт складываю с одной стороны — чтобы потом было чем засыпать и осталось место для работы.</p>
    </div>
    <div class="fse-portfolio-item fse-card animate-on-scroll">
      <div class="fse-image-container">
        <picture>
          <source srcset="<?php echo esc_url( get_template_directory_uri() . '/img/kopka-drenazhnoy-kanavy.webp' ); ?>" type="image/webp">
          <img src="<?php echo esc_url( get_template_directory_uri() . '/img/kopka-drenazhnoy-kanavy.jpg' ); ?>" alt="Выкопанная дренажная канава с отвалом грунта вдоль борта" class="fse-image" width="900" height="675" loading="lazy">
        </picture>
      </div>
      <h3 class="fse-heading">Дренажные канавы</h3>
      <p class="fse-paragraph">Отвод воды с участка и вдоль границы. Ровный профиль стенок и уклон в нужную сторону — вода уходит, а не стоит в яме.</p>
    </div>
    <div class="fse-portfolio-item fse-card animate-on-scroll">
      <div class="fse-image-container">
        <picture>
          <source srcset="<?php echo esc_url( get_template_directory_uri() . '/img/planirovka-grunta-vdol-zabora.webp' ); ?>" type="image/webp">
          <img src="<?php echo esc_url( get_template_directory_uri() . '/img/planirovka-grunta-vdol-zabora.jpg' ); ?>" alt="Спланированный и выровненный грунт вдоль забора частного дома" class="fse-image" width="900" height="675" loading="lazy">
        </picture>
      </div>
      <h3 class="fse-heading">Планировка и выравнивание</h3>
      <p class="fse-paragraph">Разравниваю привезённый или вынутый грунт, убираю перепады перед укладкой газона, отмостки или заезда.</p>
    </div>
    <div class="fse-portfolio-item fse-card animate-on-scroll">
      <div class="fse-image-container">
        <picture>
          <source srcset="<?php echo esc_url( get_template_directory_uri() . '/img/raschistka-zarosshego-uchastka.webp' ); ?>" type="image/webp">
          <img src="<?php echo esc_url( get_template_directory_uri() . '/img/raschistka-zarosshego-uchastka.jpg' ); ?>" alt="Экскаватор-погрузчик расчищает заросший участок от кустарника" class="fse-image" width="900" height="675" loading="lazy">
        </picture>
      </div>
      <h3 class="fse-heading">Расчистка участка</h3>
      <p class="fse-paragraph">Заросший участок под стройку: срезаю кустарник и дернину передним ковшом, сдвигаю в отвал. После прохода технику видно, где ровно, а где нужен подсыпной грунт.</p>
    </div>
    <div class="fse-portfolio-item fse-card animate-on-scroll">
      <div class="fse-image-container">
        <picture>
          <source srcset="<?php echo esc_url( get_template_directory_uri() . '/img/kopka-v-stesnennyh-usloviyah.webp' ); ?>" type="image/webp">
          <img src="<?php echo esc_url( get_template_directory_uri() . '/img/kopka-v-stesnennyh-usloviyah.jpg' ); ?>" alt="Копка грунта экскаватором-погрузчиком в стеснённых условиях рядом с забором" class="fse-image" width="900" height="675" loading="lazy">
        </picture>
      </div>
      <h3 class="fse-heading">Работа в стеснённых условиях</h3>
      <p class="fse-paragraph">Узкий проезд, забор и постройки рядом. Такие задачи беру, но прошу заранее прислать фото подъезда — по нему видно, пройдёт ли машина.</p>
    </div>
    <div class="fse-portfolio-item fse-card animate-on-scroll">
      <div class="fse-image-container">
        <picture>
          <source srcset="<?php echo esc_url( get_template_directory_uri() . '/img/planirovka-proezda.webp' ); ?>" type="image/webp">
          <img src="<?php echo esc_url( get_template_directory_uri() . '/img/planirovka-proezda.jpg' ); ?>" alt="Подготовленный проезд к участку после прохода экскаватора-погрузчика" class="fse-image" width="900" height="675" loading="lazy">
        </picture>
      </div>
      <h3 class="fse-heading">Проезды и подъезды</h3>
      <p class="fse-paragraph">Временный заезд на участок или подсыпка колеи, чтобы техника и самосвалы доезжали до места работ, а не буксовали на въезде.</p>
    </div>
  </div>
  <div class="fse-group animate-on-scroll">
    <p class="fse-paragraph">Котлованы под фундамент, погрузку грунта и обратную засыпку тоже выполняю — фотографии по этим направлениям добавлю по мере съёмки на объектах.</p>
    <p class="fse-paragraph"><a href="/foto-rabot/">Смотреть все фотографии работ</a></p>
  </div>
</section>

<!-- ===== РАЙОНЫ ВЫЕЗДА ===== -->
<section id="geo" class="fse-section fse-section-dark-alt">
  <div class="fse-group fse-section-header animate-on-scroll">
    <h2 class="fse-heading">Куда выезжаю</h2>
  </div>
  <div class="fse-group k3-split">
  <div class="animate-on-scroll">
    <p class="fse-paragraph">Работаю в Калининграде и Калининградской области. Не буду утверждать, что выезжаю в любую точку региона: возможность и условия подачи зависят от адреса, расстояния и загрузки на конкретный день.</p>
    <p class="fse-paragraph">Готового списка направлений не веду: всё решает конкретный адрес — расстояние, состояние дороги и загрузка на выбранную дату. Назовите населённый пункт, и я сразу скажу, доеду ли и на каких условиях по подаче.</p>
    <p class="fse-paragraph"><a href="/rayony-vyezda/">Подробнее о районах выезда</a></p>
  </div>
  <?php echo k3_scene_area( 'inview' ); // phpcs:ignore ?>
  </div>
</section>

<!-- ===== FAQ ===== -->
<section id="faq" class="fse-section fse-faq">
  <div class="fse-group fse-section-header animate-on-scroll">
    <h2 class="fse-heading">Частые вопросы</h2>
    <p class="fse-paragraph">Всё, что чаще всего спрашивают до заказа: цена и оплата, возможности техники, границы услуги и подготовка участка.</p>
  </div>
  <div class="fse-group animate-on-scroll">
<?php kopka39_render_faq(); ?>
  </div>
</section>

<!-- ===== КОНТАКТЫ / CTA ===== -->
<section id="contact" class="fse-section fse-contact-cta fse-section-dark-alt">
  <div class="fse-group fse-contact-content animate-on-scroll">
    <div class="fse-contact-text">
      <h2 class="fse-heading">Опишите задачу — сориентирую по времени и стоимости</h2>
      <p class="fse-paragraph">Позвоните или пришлите в MAX или Telegram фото участка и примерные размеры. Отвечу и скажу, реально ли выполнить работу этой техникой.</p>
      <ul class="fse-contact-list">
        <li class="fse-contact-info"><strong>Телефон:</strong> <a href="<?php echo esc_attr( $tel ); ?>"><?php kopka_e( 'phone' ); ?></a></li>
        <li class="fse-contact-info"><strong>Telegram:</strong> <a href="<?php echo esc_url( $tg ); ?>" target="_blank" rel="noopener">Написать в Telegram</a></li>
        <li class="fse-contact-info"><strong>MAX:</strong> <?php echo kopka39_max_button_html( $max, 'fse-inline-max-link' ); ?></li>
        <li class="fse-contact-info"><strong>Время работы:</strong> <?php kopka_e( 'work_hours' ); ?></li>
        <li class="fse-contact-info"><strong>Зона выезда:</strong> <?php kopka_e( 'area' ); ?></li>
      </ul>
    </div>
    <div class="fse-contact-buttons">
      <a href="<?php echo esc_attr( $tel ); ?>" class="fse-button fse-button-primary fse-button-large animate-scale">Позвонить</a>
      <a href="<?php echo esc_url( $tg ); ?>" class="fse-button fse-button-secondary fse-button-large animate-scale" target="_blank" rel="noopener">Написать в Telegram</a>
      <?php echo kopka39_max_button_html( $max, 'fse-button fse-button-secondary fse-button-large' ); ?>
      <a href="/kontakty/" class="fse-button fse-button-secondary fse-button-large">Оставить заявку</a>
    </div>
  </div>
  <div class="fse-group k3-inline-scene"><?php echo k3_scene_contact( 'inview' ); // phpcs:ignore ?></div>
</section>

</main>
</div>

<?php
get_footer();
