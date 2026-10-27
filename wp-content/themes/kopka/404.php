<?php
/**
 * Страница 404 — вариант «3D»: цифры 404 из блоков на участке.
 *
 * @package kopka
 */

get_header();

if ( function_exists( 'kopka39_render_breadcrumbs' ) ) {
	kopka39_render_breadcrumbs();
}
?>

<section class="k3-404">
	<div class="k3-wrap k3-404-grid">
		<div>
			<p class="k3-404-code">404</p>
			<h1>Здесь только что копали — страницы нет</h1>
			<p>Возможно, адрес изменился или в ссылке опечатка. Начните с главной или выберите нужную работу.</p>
			<div class="k3-404-links">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="k3-btn k3-btn-primary">На главную</a>
				<a href="<?php echo esc_url( home_url( '/kopka-transhey/' ) ); ?>" class="k3-btn k3-btn-ghost">Копка траншей</a>
				<a href="<?php echo esc_url( home_url( '/tseny/' ) ); ?>" class="k3-btn k3-btn-ghost">Цены</a>
				<a href="<?php echo esc_url( home_url( '/kontakty/' ) ); ?>" class="k3-btn k3-btn-ghost">Контакты</a>
			</div>
		</div>
		<?php
		if ( function_exists( 'k3_scene_404' ) ) {
			echo k3_scene_404( 'inview' ); // phpcs:ignore WordPress.Security.EscapeOutput
		}
		?>
	</div>
</section>

<?php
get_footer();
