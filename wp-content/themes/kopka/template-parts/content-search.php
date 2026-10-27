<?php
/**
 * Template part for displaying results in search pages
 *
 * @package kopka
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'search-result' ); ?>>
<header class="entry-header">
    <?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' ); ?>
</header>
<div class="entry-summary">
    <?php the_excerpt(); ?>
</div>
</article>
