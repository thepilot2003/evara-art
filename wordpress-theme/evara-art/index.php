<?php
/**
 * The main template file (fallback blog & posts index)
 *
 * @package EvaraArt
 * @version 1.2.0
 */

get_header(); ?>

<div class="evara-page-header">
	<div class="evara-container">
		<span class="sub-heading">✦ <?php esc_html_e( 'Sacred Art & Studio Journal', 'evara-art' ); ?></span>
		<h1 class="page-title"><?php esc_html_e( 'Latest Stories & Art Updates', 'evara-art' ); ?></h1>
		<p class="section-desc"><?php esc_html_e( 'Discover the meditative stories, sacred symbolism, and spiritual devotion behind each Evara Art creation.', 'evara-art' ); ?></p>
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
					<div class="art-card-details" style="padding: 24px;">
						<div class="blog-meta">
							<span><i class="fa-regular fa-calendar"></i> <?php echo esc_html( get_the_date() ); ?></span>
							<span><i class="fa-regular fa-user"></i> <?php the_author(); ?></span>
						</div>
						<h3 class="art-title" style="font-size: 1.3rem;">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h3>
						<div class="post-excerpt" style="font-size: 0.95rem; color: var(--evara-text-secondary); margin: 12px 0 18px; line-height: 1.6;">
							<?php the_excerpt(); ?>
						</div>
						<a href="<?php the_permalink(); ?>" class="evara-btn btn-outline" style="padding: 9px 18px; font-size: 0.88rem;">
							<?php esc_html_e( 'Read Full Article', 'evara-art' ); ?> <i class="fa-solid fa-arrow-right"></i>
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
		<div class="text-center" style="padding: 60px 20px;">
			<h3 style="font-family: var(--font-serif); font-size: 1.8rem; margin-bottom: 12px;"><?php esc_html_e( 'No Articles Published Yet', 'evara-art' ); ?></h3>
			<p style="color: var(--evara-text-secondary);"><?php esc_html_e( 'Please check back soon for our studio stories and upcoming exhibitions.', 'evara-art' ); ?></p>
		</div>
	<?php endif; ?>
</div>

<?php get_footer(); ?>
