<?php
/**
 * Template part for displaying posts
 *
 * @package kopka
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<header class="entry-header">
    <?php
    if ( is_singular() ) :
        the_title( '<h1 class="entry-title">', '</h1>' );
    else :
        the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' );
    endif;
    ?>
</header>

<?php if ( has_post_thumbnail() && ! is_singular() ) : ?>
    <a class="post-thumbnail" href="<?php the_permalink(); ?>">
        <?php the_post_thumbnail( 'medium_large' ); ?>
    </a>
<?php endif; ?>

<div class="entry-content">
    <?php
    if ( is_singular() ) :
        the_content();
    else :
        the_excerpt();
    endif;
    ?>
</div>
</article>
