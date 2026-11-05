<?php
/**
 * The template for displaying comments
 *
 * @package EvaraArt
 * @version 1.2.0
 */

if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area" style="margin-top: 50px; padding-top: 36px; border-top: 1px solid var(--evara-border);">

	<?php if ( have_comments() ) : ?>
		<h3 class="comments-title" style="font-family: var(--font-serif); font-size: 1.6rem; margin-bottom: 24px;">
			<?php
			$evara_comment_count = get_comments_number();
			if ( '1' === $evara_comment_count ) {
				printf(
					/* translators: 1: title. */
					esc_html__( 'One thought on &ldquo;%1$s&rdquo;', 'evara-art' ),
					'<span>' . wp_kses_post( get_the_title() ) . '</span>'
				);
			} else {
				printf( 
					/* translators: 1: comment count number, 2: title. */
					esc_html( _nx( '%1$s thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', $evara_comment_count, 'comments title', 'evara-art' ) ),
					number_format_i18n( $evara_comment_count ),
					'<span>' . wp_kses_post( get_the_title() ) . '</span>'
				);
			}
			?>
		</h3>

		<ol class="comment-list" style="list-style: none; padding: 0;">
			<?php
			wp_list_comments( array(
				'style'      => 'ol',
				'short_ping' => true,
				'avatar_size'=> 48,
			) );
			?>
		</ol>

		<?php
		the_comments_navigation();

		if ( ! comments_open() ) :
			?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'evara-art' ); ?></p>
			<?php
		endif;

	endif;

	comment_form( array(
		'class_submit' => 'evara-btn btn-primary',
	) );
	?>

</div><!-- #comments -->
