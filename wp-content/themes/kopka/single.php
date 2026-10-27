<?php
/**
 * The template for displaying single posts
 *
 * @package kopka
 */

get_header();
?>

<?php while ( have_posts() ) : the_post(); ?>
<header class="entry-header full-bleed" style="background: #FFC107; color: #1a1a2e; padding: 60px 20px 60px 20px; text-align: center; margin: 0;">
    <?php the_title( '<h1 class="entry-title" style="color: #1a1a2e;">', '</h1>' ); ?>
    <div class="entry-meta" style="color: #1a1a2ecc;">
        <?php
        printf(
            esc_html__( 'Posted on %s by %s', 'kopka' ),
            get_the_date(),
            get_the_author()
        );
        ?>
    </div>
</header>

<?php $pmgpt_has_sidebar = is_active_sidebar( 'sidebar-1' ); ?>
<div class="content-area<?php echo $pmgpt_has_sidebar ? ' has-sidebar' : ''; ?>">
    <div class="primary-content">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php if ( has_post_thumbnail() ) : ?>
                <div class="post-thumbnail">
                    <?php the_post_thumbnail( 'large' ); ?>
                </div>
            <?php endif; ?>

            <div class="entry-content">
                <?php
                the_content();

                wp_link_pages( array(
                    'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'kopka' ),
                    'after'  => '</div>',
                ) );
                ?>
            </div>

            <footer class="entry-footer">
                <?php
                $categories = get_the_category_list( ', ' );
                if ( $categories ) {
                    printf( '<span class="cat-links">' . esc_html__( 'Posted in %s', 'kopka' ) . '</span>', $categories );
                }

                $tags = get_the_tag_list( '', ', ' );
                if ( $tags ) {
                    printf( '<span class="tags-links">' . esc_html__( 'Tagged %s', 'kopka' ) . '</span>', $tags );
                }
                ?>
            </footer>

            <?php
            the_post_navigation( array(
                'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'kopka' ) . '</span> <span class="nav-title">%title</span>',
                'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'kopka' ) . '</span> <span class="nav-title">%title</span>',
            ) );

            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;
            ?>
        </article>
    </div>
    <?php if ( $pmgpt_has_sidebar ) get_sidebar(); ?>
</div>
<?php endwhile; ?>

<?php
get_footer();
