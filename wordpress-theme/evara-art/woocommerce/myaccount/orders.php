<?php
/**
 * Orders
 *
 * Shows orders on the account page.
 *
 * @package EvaraArt
 * @version 1.4.9
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_account_orders', $has_orders ); ?>

<?php if ( $has_orders ) : ?>

	<div class="evara-account-orders-wrapper">
		<div class="evara-account-orders-header">
			<h2 class="evara-account-orders-title"><?php esc_html_e( 'Your Art Orders & Acquisitions', 'evara-art' ); ?></h2>
			<p class="evara-account-orders-sub"><?php esc_html_e( 'Track studio progress, view order receipts, and access collector care.', 'evara-art' ); ?></p>
		</div>

		<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table evara-luxury-orders-table">
			<thead>
				<tr>
					<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
						<th class="woocommerce-orders-table__header woocommerce-orders-table__header-<?php echo esc_attr( $column_id ); ?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
					<?php endforeach; ?>
				</tr>
			</thead>

			<tbody>
				<?php
				foreach ( $customer_orders->orders as $customer_order ) {
					$order      = wc_get_order( $customer_order );
					if ( ! $order ) {
						continue;
					}
					$item_count = $order->get_item_count() - $order->get_item_count_refunded();
					?>
					<tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr( $order->get_status() ); ?> order">
						<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
							<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
								<?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
									<?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>

								<?php elseif ( 'order-number' === $column_id ) : ?>
									<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>" class="evara-order-number-link">
										<?php echo esc_html( _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number() ); ?>
									</a>

								<?php elseif ( 'order-date' === $column_id ) : ?>
									<time datetime="<?php echo esc_attr( $order->get_date_created() ? $order->get_date_created()->date( 'c' ) : '' ); ?>">
										<?php echo esc_html( $order->get_date_created() ? wc_format_datetime( $order->get_date_created() ) : '' ); ?>
									</time>

								<?php elseif ( 'order-status' === $column_id ) : ?>
									<span class="evara-status-badge evara-status-badge--<?php echo esc_attr( $order->get_status() ); ?>">
										<?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>
									</span>

								<?php elseif ( 'order-total' === $column_id ) : ?>
									<span class="evara-order-total-formatted">
										<?php echo wp_kses_post( $order->get_formatted_order_total() ); ?>
									</span>
									<span class="evara-order-item-count-sub">
										<?php printf( esc_html( _n( 'for %d item', 'for %d items', $item_count, 'evara-art' ) ), intval( $item_count ) ); ?>
									</span>

								<?php elseif ( 'order-actions' === $column_id ) : ?>
									<?php
									$actions = wc_get_account_orders_actions( $order );
									if ( ! empty( $actions ) ) {
										foreach ( $actions as $key => $action ) {
											echo '<a href="' . esc_url( $action['url'] ) . '" class="woocommerce-button button ' . sanitize_html_class( $key ) . ' evara-btn evara-btn-sm">' . esc_html( $action['name'] ) . '</a>';
										}
									}
									?>
								<?php endif; ?>
							</td>
						<?php endforeach; ?>
					</tr>
					<?php
				}
				?>
			</tbody>
		</table>

		<?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

		<?php if ( 1 < $customer_orders->max_num_pages ) : ?>
			<div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination evara-pagination">
				<?php if ( 1 !== $current_page ) : ?>
					<a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>"><?php esc_html_e( 'Previous', 'woocommerce' ); ?></a>
				<?php endif; ?>

				<?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
					<a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>"><?php esc_html_e( 'Next', 'woocommerce' ); ?></a>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>

<?php else : ?>

	<div class="evara-empty-orders-card">
		<div class="evara-empty-orders-icon">🎨</div>
		<h3><?php esc_html_e( 'No Art Orders Placed Yet', 'evara-art' ); ?></h3>
		<p><?php esc_html_e( 'You have not acquired any artworks yet. Explore our handcrafted canvas paintings, traditional Lippan art, and contemporary collections.', 'evara-art' ); ?></p>
		<a class="evara-btn evara-btn-primary" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
			<?php esc_html_e( 'Explore Art Gallery', 'evara-art' ); ?>
		</a>
	</div>

<?php endif; ?>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
