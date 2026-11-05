<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package EvaraArt
 * @version 1.2.0
 */

get_header(); ?>

<div class="evara-container">
	<div class="evara-404-wrap">
		<div class="evara-404-code">404</div>
		<span class="sub-heading">✦ <?php esc_html_e( 'Artwork Not Found', 'evara-art' ); ?></span>
		<h1 class="evara-404-title"><?php esc_html_e( 'This Sacred Space Is Empty', 'evara-art' ); ?></h1>
		<p class="evara-404-desc">
			<?php esc_html_e( 'The artwork or page you are looking for may have been moved, renamed, or is currently being handcrafted in our studio. Explore our curated fine art galleries below.', 'evara-art' ); ?>
		</p>
		
		<div class="evara-404-actions">
			<a href="<?php echo esc_url( home_url( '/canvas-paintings-home-decor/' ) ); ?>" class="evara-btn btn-primary">
				<span><?php esc_html_e( 'Explore Canvas Paintings', 'evara-art' ); ?></span>
				<i class="fa-solid fa-arrow-right"></i>
			</a>
			<a href="<?php echo esc_url( home_url( '/lippan-art-home-decor/' ) ); ?>" class="evara-btn btn-outline">
				<span><?php esc_html_e( 'Explore Lippan Art', 'evara-art' ); ?></span>
			</a>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="evara-btn btn-secondary">
				<?php esc_html_e( 'Return Home', 'evara-art' ); ?>
			</a>
		</div>
	</div>
</div>

<?php get_footer(); ?>
