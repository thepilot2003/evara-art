<?php
/**
 * Order details
 *
 * @package EvaraArt
 * @version 1.4.9
 */

defined( 'ABSPATH' ) || exit;

$order = wc_get_order( $order_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

if ( ! $order ) {
	return;
}

$order_items           = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
$show_purchase_note    = $order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );
$show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();
$downloads             = $order->get_downloadable_items();
$show_downloads        = $order->has_downloadable_item() && $order->is_download_permitted();

if ( $show_downloads ) {
	wc_get_template(
		'order/order-downloads.php',
		array(
			'downloads'  => $downloads,
			'show_title' => true,
		)
	);
}
?>
<section class="woocommerce-order-details evara-luxury-order-details">
	<?php do_action( 'woocommerce_order_details_before_order_table', $order ); ?>

	<div class="evara-order-table-header">
		<h2 class="woocommerce-order-details__title evara-order-details-title-main"><?php esc_html_e( 'Ordered Artworks & Summary', 'evara-art' ); ?></h2>
	</div>

	<table class="woocommerce-table woocommerce-table--order-details shop_table order_details evara-order-items-table">
		<thead>
			<tr>
				<th class="woocommerce-table__product-name product-name"><?php esc_html_e( 'Artwork & Specifications', 'evara-art' ); ?></th>
				<th class="woocommerce-table__product-table product-total"><?php esc_html_e( 'Total', 'evara-art' ); ?></th>
			</tr>
		</thead>

		<tbody>
			<?php
			do_action( 'woocommerce_order_details_before_order_table_items', $order );

			foreach ( $order_items as $item_id => $item ) {
				$product = $item->get_product();

				wc_get_template(
					'order/order-details-item.php',
					array(
						'order'              => $order,
						'item_id'            => $item_id,
						'item'               => $item,
						'show_purchase_note' => $show_purchase_note,
						'purchase_note'      => $product ? $product->get_purchase_note() : '',
						'product'            => $product,
					)
				);
			}

			do_action( 'woocommerce_order_details_after_order_table_items', $order );
			?>
		</tbody>

		<tfoot>
			<?php
			foreach ( $order->get_order_item_totals() as $key => $total ) {
				?>
				<tr class="evara-order-total-row evara-order-total-row--<?php echo esc_attr( $key ); ?>">
					<th scope="row"><?php echo esc_html( $total['label'] ); ?></th>
					<td><?php echo wp_kses_post( $total['value'] ); ?></td>
				</tr>
				<?php
			}
			?>
			<?php if ( $order->get_customer_note() ) : ?>
				<tr class="evara-order-note-row">
					<th><?php esc_html_e( 'Customer Note:', 'evara-art' ); ?></th>
					<td><?php echo wp_kses_post( nl2br( wptexturize( $order->get_customer_note() ) ) ); ?></td>
				</tr>
			<?php endif; ?>
		</tfoot>
	</table>

	<?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>
</section>

<?php
/**
 * Action hook fired after the order details.
 *
 * @since 4.4.0
 * @param WC_Order $order Order data.
 */
do_action( 'woocommerce_after_order_details', $order );

if ( $show_customer_details ) {
	wc_get_template( 'order/order-details-customer.php', array( 'order' => $order ) );
}
