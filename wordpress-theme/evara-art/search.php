<?php
/**
 * The template for displaying search results pages
 *
 * @package EvaraArt
 * @version 1.2.0
 */

get_header(); ?>

<div class="evara-page-header">
	<div class="evara-container">
		<span class="sub-heading">✦ <?php esc_html_e( 'Search Results', 'evara-art' ); ?></span>
		<h1 class="page-title">
			<?php
			/* translators: %s: search query. */
			printf( esc_html__( 'Results for: &ldquo;%s&rdquo;', 'evara-art' ), '<span>' . get_search_query() . '</span>' );
			?>
		</h1>
		<p class="section-desc">
			<?php
			global $wp_query;
			/* translators: %d: number of results */
			printf( esc_html__( 'Found %d artworks and articles matching your search.', 'evara-art' ), (int) $wp_query->found_posts );
			?>
		</p>
	</div>
</div>

<div class="evara-container page-content-container">
	<?php if ( have_posts() ) : ?>
		<div class="artworks-grid">
			<?php
			while ( have_posts() ) :
				the_post();
				if ( 'product' === get_post_type() && class_exists( 'WooCommerce' ) ) :
					wc_get_template_part( 'content', 'product' );
				else :
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
				endif;
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
			<div class="empty-icon-wrap" style="width: 80px; height: 80px; border-radius: 50%; background: var(--evara-surface-muted); display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px; color: var(--evara-terracotta); font-size: 1.8rem;">
				<i class="fa-solid fa-magnifying-glass"></i>
			</div>
			<h3 style="font-family: var(--font-serif); font-size: 1.8rem; margin-bottom: 12px;"><?php esc_html_e( 'No Matching Creations Found', 'evara-art' ); ?></h3>
			<p style="color: var(--evara-text-secondary); max-width: 500px; margin: 0 auto 28px;"><?php esc_html_e( 'Please check your spelling or try searching for keywords like Krishna, Lippan Art, Ganesha, or Canvas.', 'evara-art' ); ?></p>
			<a href="<?php echo esc_url( home_url( '/canvas-paintings-home-decor/' ) ); ?>" class="evara-btn btn-primary">
				<span><?php esc_html_e( 'Browse All Collections', 'evara-art' ); ?></span>
			</a>
		</div>
	<?php endif; ?>
</div>

<?php get_footer(); ?>
