<?php
/**
 * The main template file
 *
 * @package kopka
 */

get_header();
?>

<div class="content-area has-sidebar">
<div class="primary-content">
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
