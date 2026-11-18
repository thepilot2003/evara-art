<?php
/**
 * WooCommerce main layout wrapper
 *
 * @package EvaraArt
 * @version 1.4.4
 */

get_header(); ?>

<?php if ( ! is_product() ) : ?>
	<!-- Shop & Taxonomy Category Hero Banner (Single H1) -->
	<div class="evara-shop-hero category-header-banner">
		<div class="evara-container">
			<div class="evara-breadcrumbs"><?php woocommerce_breadcrumb(); ?></div>
			<h1 class="shop-page-title category-title">
				<?php woocommerce_page_title(); ?>
			</h1>
		</div>
	</div>
<?php endif; ?>

<div class="evara-container evara-shop-container <?php echo is_product() ? 'evara-single-product-container' : 'evara-archive-container'; ?>">
	<?php woocommerce_content(); ?>
</div>

<?php get_footer(); ?>
