<?php
/**
 * The template for displaying archive pages
 *
 * @package kopka
 */

get_header();
?>

<div class="content-area has-sidebar">
<div class="primary-content">
    <header class="page-header">
        <?php
        the_archive_title( '<h1 class="page-title">', '</h1>' );
        the_archive_description( '<div class="archive-description">', '</div>' );
        ?>
    </header>

    <?php
    if ( have_posts() ) :
        while ( have_posts() ) :
            the_post();
            get_template_part( 'template-parts/content', get_post_type() );
        endwhile;

        the_posts_navigation();
    else :
        get_template_part( 'template-parts/content', 'none' );
    endif;
    ?>
</div>
<?php get_sidebar(); ?>
</div>

<?php
get_footer();
