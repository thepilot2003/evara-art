<?php
/**
 * WooCommerce main layout wrapper
 *
 * @package EvaraArt
 */

get_header(); ?>

<div class="evara-shop-hero">
	<div class="evara-container">
		<h1 class="shop-page-title">
			<?php
			if ( is_shop() ) {
				echo 'Art Gallery &amp; Shop';
			} else {
				woocommerce_page_title();
			}
			?>
		</h1>
		<div class="evara-breadcrumbs"><?php woocommerce_breadcrumb(); ?></div>
	</div>
</div>

<div class="evara-container evara-shop-container">
	<?php woocommerce_content(); ?>
</div>

<?php get_footer(); ?>
