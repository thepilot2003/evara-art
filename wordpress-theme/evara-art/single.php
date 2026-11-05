<?php
/**
 * The template for displaying all single posts
 *
 * @package EvaraArt
 * @version 1.2.0
 */

get_header(); ?>

<div class="evara-page-header">
	<div class="evara-container">
		<div class="blog-meta" style="margin-bottom: 12px;">
			<span><i class="fa-regular fa-calendar"></i> <?php echo esc_html( get_the_date() ); ?></span>
			<span><i class="fa-regular fa-user"></i> <?php the_author(); ?></span>
			<span><i class="fa-regular fa-folder"></i> <?php the_category( ', ' ); ?></span>
		</div>
		<h1 class="page-title"><?php the_title(); ?></h1>
	</div>
</div>

<div class="evara-container page-content-container" style="max-width: 860px;">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-post-entry' ); ?>>
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="single-post-thumbnail" style="margin-bottom: 36px; border-radius: var(--radius-md); overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.06);">
					<?php the_post_thumbnail( 'large', array( 'style' => 'width: 100%; height: auto; display: block;' ) ); ?>
				</div>
			<?php endif; ?>

			<div class="page-entry-content">
				<?php
				the_content();

				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'evara-art' ),
					'after'  => '</div>',
				) );
				?>
			</div>

			<div class="post-tags" style="margin-top: 32px; padding-top: 20px; border-top: 1px solid var(--evara-border);">
				<?php the_tags( '<span class="tags-label">' . esc_html__( 'Tags: ', 'evara-art' ) . '</span>', ' ' ); ?>
			</div>

			<div class="post-navigation-links" style="display: flex; justify-content: space-between; margin: 40px 0; padding: 24px 0; border-top: 1px solid var(--evara-border); border-bottom: 1px solid var(--evara-border);">
				<div class="prev-post"><?php previous_post_link( '%link', '<i class="fa-solid fa-arrow-left"></i> %title' ); ?></div>
				<div class="next-post"><?php next_post_link( '%link', '%title <i class="fa-solid fa-arrow-right"></i>' ); ?></div>
			</div>

			<?php
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;
			?>
		</article>
		<?php
	endwhile;
	?>
</div>

<?php get_footer(); ?>
