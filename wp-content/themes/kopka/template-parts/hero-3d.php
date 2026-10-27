<?php
/**
 * Первый экран главной с псевдо-3D сценой: экскаватор-погрузчик собран
 * из отдельных слоёв (inc/scenes.php → k3_backhoe_layers). При движении
 * мыши слои сдвигаются с разной скоростью, при прокрутке машина
 * «разбирается» на узлы. Логика — js/k3-scenes.js.
 *
 * Ожидает переменные $tel, $tg, $max из front-page.php.
 *
 * @package kopka
 */

$k3_extra = '<div class="k3-ghost" data-depth="-10">ЭКСКА<br>ВАТОР</div>'
	. '<div class="k3-badge"><strong>Работаю лично</strong><span>Прямой контакт с оператором</span></div>';
?>
<section class="k3-hero" id="top">
  <div class="k3-wrap k3-hero-grid">
    <div class="k3-hero-text">
      <p class="k3-kicker"><span class="k3-dot"></span><?php kopka_e( 'area' ); ?></p>
      <h1 class="k3-h1">Экскаватор-погрузчик <span class="k3-accent">с&nbsp;оператором</span> в&nbsp;Калининграде</h1>
      <p class="k3-lead">Копаю траншеи и котлованы, планирую грунт, гружу и перемещаю землю, песок и щебень. Работаю сам на своей технике — вы обсуждаете задачу напрямую с тем, кто будет её выполнять.</p>
      <div class="k3-hero-actions">
        <a href="<?php echo esc_attr( $tel ); ?>" class="k3-btn k3-btn-primary">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2"/></svg>
          Позвонить <?php kopka_e( 'phone' ); ?>
        </a>
        <a href="<?php echo esc_url( $tg ); ?>" class="k3-btn k3-btn-ghost" target="_blank" rel="noopener">Telegram</a>
        <?php echo kopka39_max_button_html( $max, 'k3-btn k3-btn-ghost' ); ?>
      </div>
      <p class="k3-note"><?php kopka_e( 'work_hours' ); ?> · Пришлите фото участка и размеры — сориентирую по времени работы.</p>
    </div>

    <?php echo k3_scene_backhoe( 'hero', $k3_extra ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
  </div>
  <p class="k3-scroll-hint k3-wrap">Прокрутите — машина «разберётся» на узлы</p>
</section>

<section class="k3-strip">
  <div class="k3-wrap k3-strip-grid">
    <div class="k3-plaque"><span class="k3-num">01</span><div><strong><?php kopka_e( 'price_hour' ); ?></strong><span>минимальный заказ — <?php kopka_e( 'min_order' ); ?></span></div></div>
    <div class="k3-plaque"><span class="k3-num">02</span><div><strong>Без посредников</strong><span>звоните тому, кто приедет</span></div></div>
    <div class="k3-plaque"><span class="k3-num">03</span><div><strong>Подача <?php kopka_e( 'delivery_city' ); ?></strong><span>по Калининграду, своим ходом</span></div></div>
    <div class="k3-plaque"><span class="k3-num">04</span><div><strong><?php kopka_e( 'work_hours' ); ?></strong><span>Калининград и область</span></div></div>
  </div>
</section>
