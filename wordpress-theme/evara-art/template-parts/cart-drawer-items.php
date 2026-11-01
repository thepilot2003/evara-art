<?php
/**
 * Cart Drawer Items Fragment
 *
 * @package EvaraArt
 */

if ( ! class_exists( 'WooCommerce' ) || ! WC()->cart ) {
	return;
}

if ( WC()->cart->is_empty() ) :
	?>
	<div class="empty-cart-state">
		<div class="empty-icon-wrap">
			<i class="fa-solid fa-paintbrush"></i>
		</div>
		<h4>Your art bag is empty</h4>
		<p>Bring devotion, serenity, and contemporary elegance to your home walls.</p>
		<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="evara-btn btn-primary" id="evara-explore-btn">
			Explore Gallery
		</a>
	</div>
	<?php
else :
	?>
	<ul class="cart-items-list">
		<?php
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
			$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) :
				$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
				?>
				<li class="cart-item-row" data-cart-key="<?php echo esc_attr( $cart_item_key ); ?>">
					
					<!-- Product Thumbnail -->
					<div class="item-thumb">
						<?php
						$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
						if ( ! $product_permalink ) :
							echo $thumbnail; // PHPCS: XSS ok.
						else :
							printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
						endif;
						?>
					</div>

					<!-- Item Info -->
					<div class="item-details">
						<h5 class="item-title">
							<?php
							if ( ! $product_permalink ) :
								echo wp_kses_post( $_product->get_name() );
							else :
								echo wp_kses_post( sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ) );
							endif;
							?>
						</h5>
						<div class="item-price">
							<?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok. ?>
						</div>
						<div class="item-qty-row">
							<span class="qty-pill">Qty: <?php echo esc_html( $cart_item['quantity'] ); ?></span>
							<?php
							echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								'woocommerce_cart_item_remove_link',
								sprintf(
									'<a href="%s" class="remove-cart-item" aria-label="%s" data-product_id="%s" data-cart_item_key="%s"><i class="fa-regular fa-trash-can"></i> Remove</a>',
									esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
									/* translators: %s is the product name */
									esc_attr( sprintf( __( 'Remove %s from cart', 'evara-art' ), wp_strip_all_tags( $_product->get_name() ) ) ),
									esc_attr( $product_id ),
									esc_attr( $cart_item_key )
								),
								$cart_item_key
							);
							?>
						</div>
					</div>

				</li>
				<?php
			endif;
		endforeach;
		?>
	</ul>
	<?php
endif;
