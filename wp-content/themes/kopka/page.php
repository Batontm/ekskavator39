<?php
/**
 * The template for displaying pages
 * PressMeGPT pages include their own designed headers/titles in the content.
 *
 * @package kopka
 */

get_header();

if ( function_exists( 'kopka39_render_breadcrumbs' ) ) {
	kopka39_render_breadcrumbs();
}
?>

<?php while ( have_posts() ) : the_post(); ?>
<div class="pressmegpt-page-content entry-content">
    <?php
    the_content();

    wp_link_pages( array(
        'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'kopka' ),
        'after'  => '</div>',
    ) );
    ?>
</div>
<?php endwhile; ?>

<?php
get_footer();
