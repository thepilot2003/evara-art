<?php
/**
 * Order Item Details
 *
 * @package EvaraArt
 * @version 1.4.9
 */

defined( 'ABSPATH' ) || exit;

if ( ! apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
	return;
}

$is_visible        = $product && $product->is_visible();
$product_permalink = apply_filters( 'woocommerce_order_item_permalink', $is_visible ? $product->get_permalink( $item ) : '', $item, $order );
$qty               = $item->get_quantity();
$item_name         = $item->get_name();

$thumb = '';
if ( $product ) {
	$thumb = $product->get_image( array( 80, 80 ), array( 'class' => 'evara-order-line-item-img' ) );
}
if ( ! $thumb ) {
	$thumb = '<div class="evara-order-line-item-img-placeholder">🎨</div>';
}
?>
<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'woocommerce-table__line-item order_item evara-order-line-item-row', $item, $order ) ); ?>">

	<td class="woocommerce-table__product-name product-name">
		<div class="evara-order-product-card">
			<div class="evara-order-product-thumb">
				<?php if ( $product_permalink ) : ?>
					<a href="<?php echo esc_url( $product_permalink ); ?>"><?php echo $thumb; ?></a>
				<?php else : ?>
					<?php echo $thumb; ?>
				<?php endif; ?>
			</div>

			<div class="evara-order-product-body">
				<div class="evara-order-product-title">
					<?php if ( $product_permalink ) : ?>
						<a href="<?php echo esc_url( $product_permalink ); ?>"><?php echo esc_html( $item_name ); ?></a>
					<?php else : ?>
						<span><?php echo esc_html( $item_name ); ?></span>
					<?php endif; ?>
				</div>

				<div class="evara-order-product-meta-row">
					<span class="evara-meta-pill evara-meta-pill--artist">Lead Artist: <strong>Shraddha Parmar</strong></span>
					<span class="evara-meta-pill evara-meta-pill--craft">Handmade Original</span>
					<?php if ( $product && $product->get_sku() ) : ?>
						<span class="evara-meta-pill evara-meta-pill--sku">SKU: <?php echo esc_html( $product->get_sku() ); ?></span>
					<?php endif; ?>
				</div>

				<div class="evara-order-product-qty-row">
					<span class="evara-qty-tag">Quantity: <strong><?php echo esc_html( $qty ); ?></strong></span>
				</div>

				<?php do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order, false ); ?>
				<?php
				wc_display_item_meta( $item, array(
					'before'    => '<div class="wc-item-meta evara-item-meta">',
					'after'     => '</div>',
					'separator' => '<br>',
					'echo'      => true,
					'autop'     => false,
				) );
				?>
				<?php do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order, false ); ?>
			</div>
		</div>
	</td>

	<td class="woocommerce-table__product-total product-total evara-order-line-total">
		<?php echo $order->get_formatted_line_subtotal( $item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</td>

</tr>

<?php if ( $show_purchase_note && $purchase_note ) : ?>
<tr class="woocommerce-table__product-purchase-note product-purchase-note">
	<td colspan="2"><?php echo wp_kses_post( wpautop( do_shortcode( $purchase_note ) ) ); ?></td>
</tr>
<?php endif; ?>
