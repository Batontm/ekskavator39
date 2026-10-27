<?php
/**
 * The template for displaying comments
 *
 * @package kopka
 */

if ( post_password_required() ) {
return;
}
?>

<div id="comments" class="comments-area">
<?php if ( have_comments() ) : ?>
    <h2 class="comments-title">
        <?php
        $comment_count = get_comments_number();
        printf(
            esc_html( _nx( '%1\$s Comment', '%1\$s Comments', $comment_count, 'comments title', 'kopka' ) ),
            number_format_i18n( $comment_count )
        );
        ?>
    </h2>

    <ol class="comment-list">
        <?php
        wp_list_comments( array(
            'style'       => 'ol',
            'short_ping'  => true,
            'avatar_size' => 48,
        ) );
        ?>
    </ol>

    <?php
    the_comments_navigation();
endif;

comment_form( array(
    'class_submit'  => 'submit-button',
    'label_submit'  => esc_html__( 'Post Comment', 'kopka' ),
) );
?>
</div>
