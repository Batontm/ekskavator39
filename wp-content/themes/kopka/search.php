<?php
/**
 * The template for displaying search results
 *
 * @package kopka
 */

get_header();
?>

<div class="content-area has-sidebar">
<div class="primary-content">
    <header class="page-header">
        <h1 class="page-title">
            <?php printf( esc_html__( 'Search Results for: %s', 'kopka' ), '<span>' . get_search_query() . '</span>' ); ?>
        </h1>
    </header>

    <?php
    if ( have_posts() ) :
        while ( have_posts() ) :
            the_post();
            get_template_part( 'template-parts/content', 'search' );
        endwhile;

        the_posts_navigation();
    else :
        ?>
        <div class="no-results">
            <p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'kopka' ); ?></p>
            <?php get_search_form(); ?>
        </div>
        <?php
    endif;
    ?>
</div>
<?php get_sidebar(); ?>
</div>

<?php
get_footer();
