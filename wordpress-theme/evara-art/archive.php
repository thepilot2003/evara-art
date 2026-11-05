<?php
/**
 * The template for displaying archive pages
 *
 * @package EvaraArt
 * @version 1.2.0
 */

get_header(); ?>

<div class="evara-page-header">
	<div class="evara-container">
		<span class="sub-heading">✦ <?php esc_html_e( 'Journal & Archives', 'evara-art' ); ?></span>
		<h1 class="page-title"><?php the_archive_title(); ?></h1>
		<?php if ( get_the_archive_description() ) : ?>
			<div class="archive-description section-desc" style="max-width: 680px; margin-top: 8px;">
				<?php the_archive_description(); ?>
			</div>
		<?php endif; ?>
	</div>
</div>

<div class="evara-container page-content-container">
	<?php if ( have_posts() ) : ?>
		<div class="artworks-grid">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'artwork-card blog-entry-card' ); ?>>
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="art-thumbnail-wrap">
							<a href="<?php the_permalink(); ?>">
								<?php the_post_thumbnail( 'medium_large', array( 'class' => 'art-img-primary', 'width' => 450, 'height' => 300 ) ); ?>
							</a>
						</div>
					<?php endif; ?>
					<div class="art-card-details" style="padding: 20px;">
						<div class="blog-meta">
							<span><i class="fa-regular fa-calendar"></i> <?php echo esc_html( get_the_date() ); ?></span>
							<span><i class="fa-regular fa-user"></i> <?php the_author(); ?></span>
						</div>
						<h3 class="art-title" style="font-size: 1.25rem;">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h3>
						<div class="post-excerpt" style="font-size: 0.92rem; color: var(--evara-text-secondary); margin: 10px 0 16px;">
							<?php the_excerpt(); ?>
						</div>
						<a href="<?php the_permalink(); ?>" class="evara-btn btn-outline" style="padding: 8px 16px; font-size: 0.85rem;">
							<?php esc_html_e( 'Read Article', 'evara-art' ); ?> <i class="fa-solid fa-arrow-right"></i>
						</a>
					</div>
				</article>
				<?php
			endwhile;
			?>
		</div>

		<div class="evara-pagination">
			<?php
			the_posts_pagination( array(
				'mid_size'  => 2,
				'prev_text' => '<i class="fa-solid fa-chevron-left"></i>',
				'next_text' => '<i class="fa-solid fa-chevron-right"></i>',
			) );
			?>
		</div>

	<?php else : ?>
		<p><?php esc_html_e( 'No entries found in this archive.', 'evara-art' ); ?></p>
	<?php endif; ?>
</div>

<?php get_footer(); ?>
