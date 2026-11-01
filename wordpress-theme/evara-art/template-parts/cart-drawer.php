<?php
/**
 * Slide-out AJAX Cart Drawer
 *
 * @package EvaraArt
 */

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

$shipping_data = evara_get_shipping_progress();
?>
<div class="evara-cart-drawer-wrapper" id="evara-cart-drawer" aria-hidden="true">
	<!-- Backdrop -->
	<div class="cart-drawer-overlay" id="evara-cart-close-overlay"></div>

	<!-- Side Panel -->
	<aside class="cart-drawer-panel" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Shopping Bag', 'evara-art' ); ?>">
		
		<!-- Drawer Header -->
		<div class="drawer-header">
			<div class="header-title-wrap">
				<h3 class="drawer-title">Your Art Bag</h3>
				<span class="drawer-count">(<?php echo WC()->cart ? esc_html( WC()->cart->get_cart_contents_count() ) : '0'; ?>)</span>
			</div>
			<button class="drawer-close-btn" id="evara-cart-close" aria-label="<?php esc_attr_e( 'Close cart', 'evara-art' ); ?>">
				<i class="fa-solid fa-xmark"></i>
			</button>
		</div>

		<!-- Free Shipping Threshold Bar -->
		<div class="shipping-threshold-banner">
			<?php if ( $shipping_data['eligible'] ) : ?>
				<p class="threshold-msg success">
					<i class="fa-solid fa-circle-check"></i> Congratulations! You unlocked <strong>Free Pan-India Delivery</strong>
				</p>
			<?php else : ?>
				<p class="threshold-msg">
					Add <strong><?php echo wc_price( $shipping_data['remaining'] ); ?></strong> more to unlock <strong>Free Pan-India Delivery</strong>!
				</p>
			<?php endif; ?>
			<div class="threshold-progress-bar">
				<div class="threshold-progress-fill" style="width: <?php echo esc_attr( $shipping_data['percentage'] ); ?>%;"></div>
			</div>
		</div>

		<!-- Cart Items Loop -->
		<div class="evara-cart-drawer-items">
			<?php get_template_part( 'template-parts/cart-drawer-items' ); ?>
		</div>

		<!-- Cart Drawer Footer -->
		<div class="drawer-footer">
			<div class="subtotal-row">
				<span class="subtotal-label">Subtotal</span>
				<span class="subtotal-amount evara-subtotal-amount">
					<?php echo WC()->cart ? WC()->cart->get_cart_subtotal() : '₹0.00'; ?>
				</span>
			</div>
			<p class="shipping-calc-note">Taxes &amp; Pan-India insured delivery calculated at checkout.</p>
			
			<div class="drawer-actions">
				<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="evara-btn btn-primary btn-checkout">
					<span>Proceed to Checkout</span>
					<i class="fa-solid fa-arrow-right"></i>
				</a>
				<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="evara-btn btn-secondary btn-view-cart">
					View Bag Details
				</a>
			</div>
		</div>

	</aside>
</div>
