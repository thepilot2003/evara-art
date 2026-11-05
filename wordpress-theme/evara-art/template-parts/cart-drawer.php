<?php
/**
 * Slide-out AJAX Cart Drawer
 *
 * @package EvaraArt
 * @version 1.2.0
 */

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}
?>
<div class="evara-cart-drawer-wrapper" id="evara-cart-drawer" aria-hidden="true">
	<!-- Backdrop -->
	<div class="cart-drawer-overlay" id="evara-cart-close-overlay"></div>

	<!-- Side Panel -->
	<aside class="cart-drawer-panel" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Shopping Bag', 'evara-art' ); ?>">
		
		<!-- Drawer Header -->
		<div class="drawer-header">
			<div class="header-title-wrap">
				<h3 class="drawer-title"><?php esc_html_e( 'Your Art Bag', 'evara-art' ); ?></h3>
				<span class="drawer-count evara-drawer-count">(<?php echo WC()->cart ? esc_html( WC()->cart->get_cart_contents_count() ) : '0'; ?>)</span>
			</div>
			<button class="drawer-close-btn" id="evara-cart-close" aria-label="<?php esc_attr_e( 'Close cart', 'evara-art' ); ?>">
				<i class="fa-solid fa-xmark"></i>
			</button>
		</div>

		<!-- Free Shipping Threshold Bar (Fragment-enabled) -->
		<?php echo evara_render_shipping_threshold_banner(); ?>

		<!-- Cart Items Loop (Outer wrapper preserved on AJAX fragments) -->
		<div class="evara-cart-drawer-items">
			<?php get_template_part( 'template-parts/cart-drawer-items' ); ?>
		</div>

		<!-- Cart Drawer Footer -->
		<div class="drawer-footer">
			<div class="subtotal-row">
				<span class="subtotal-label"><?php esc_html_e( 'Subtotal', 'evara-art' ); ?></span>
				<span class="subtotal-amount evara-subtotal-amount">
					<?php echo WC()->cart ? WC()->cart->get_cart_subtotal() : '₹0.00'; ?>
				</span>
			</div>
			<p class="shipping-calc-note"><?php esc_html_e( 'Taxes & Pan-India insured delivery calculated at checkout.', 'evara-art' ); ?></p>
			
			<div class="drawer-actions">
				<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="evara-btn btn-primary btn-checkout">
					<span><?php esc_html_e( 'Proceed to Checkout', 'evara-art' ); ?></span>
					<i class="fa-solid fa-arrow-right"></i>
				</a>
				<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="evara-btn btn-secondary btn-view-cart">
					<?php esc_html_e( 'View Bag Details', 'evara-art' ); ?>
				</a>
			</div>
		</div>

	</aside>
</div>
