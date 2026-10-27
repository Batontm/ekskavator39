<?php
/**
 * The footer template - AI-designed footer with Customizer support
 *
 * @package kopka
 */
?>
</main>

<?php if ( get_theme_mod( 'pressmegpt_override_footer', true ) ) : ?>
<div class="theme-designer-footer">
    <?php echo kopka_inject_dynamic_menu( get_theme_mod( 'pressmegpt_custom_footer_html', '<footer class="fse-section fse-footer"><div class="fse-group fse-footer-content"><div class="fse-columns fse-footer-columns"><div class="fse-column fse-footer-about"><img src="https://placehold.co/200x60/EEE/999?text=Logo" alt="kopka Logo" class="fse-image fse-footer-logo"><p class="fse-paragraph">Индивидуальный предприниматель в сфере земляных работ. Экскаватор-погрузчик с оператором в Калининграде и области.</p></div><div class="fse-column fse-footer-links"><h4 class="fse-heading">Быстрые Ссылки</h4><ul class="fse-footer-menu"><li><a href="/">Home</a></li></ul></div><div class="fse-column fse-footer-contact"><h4 class="fse-heading">Связаться</h4><ul class="fse-contact-list"><li><i class="fas fa-map-marker-alt"></i> <span>Калининград и область</span></li><li><i class="fas fa-phone"></i> <span><a href="tel:+79000000000">+7 (900) 000-00-00</a></span></li><li><i class="fas fa-envelope"></i> <span><a href="mailto:info@kopka.ru">info@kopka.ru</a></span></li></ul></div></div><div class="fse-footer-bottom"><p class="fse-copyright">&copy; 2026 kopka. Все права защищены.</p></div></div></footer>' ), 'footer' ); ?>
</div>
<?php else : ?>
<footer id="colophon" class="site-footer">
    <div class="footer-inner">
        <?php if ( get_theme_mod( 'kopka_show_footer_widgets', false ) && ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) ) ) : ?>
            <div class="footer-widgets">
                <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
                    <div class="footer-widget-area"><?php dynamic_sidebar( 'footer-1' ); ?></div>
                <?php endif; ?>
                <?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
                    <div class="footer-widget-area"><?php dynamic_sidebar( 'footer-2' ); ?></div>
                <?php endif; ?>
                <?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
                    <div class="footer-widget-area"><?php dynamic_sidebar( 'footer-3' ); ?></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <?php
        $footer_menu_pos = get_theme_mod( 'kopka_footer_menu_position', 'above' );
        if ( $footer_menu_pos !== 'hidden' && has_nav_menu( 'footer' ) ) :
        ?>
            <nav class="footer-navigation" aria-label="<?php esc_attr_e( 'Footer Menu', 'kopka' ); ?>">
                <?php wp_nav_menu( array( 'theme_location' => 'footer', 'depth' => 1, 'fallback_cb' => false ) ); ?>
            </nav>
        <?php endif; ?>
        <div class="site-info">
            <p><?php echo wp_kses_post( kopka_get_footer_text() ); ?></p>
        </div>
    </div>
</footer>
<?php endif; ?>

</div>

<?php wp_footer(); ?>
</body>
</html>
