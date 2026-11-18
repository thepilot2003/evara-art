<?php
/**
 * The template for displaying 404 pages (not found) & Virtual Route Fail-Safe
 *
 * @package EvaraArt
 * @version 1.4.6
 */

$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
$clean_path = trim( parse_url( $request_uri, PHP_URL_PATH ), '/' );

// Strip subfolder if WordPress is installed in a subdirectory
$home_path = trim( parse_url( home_url(), PHP_URL_PATH ), '/' );
if ( ! empty( $home_path ) && strpos( $clean_path, $home_path ) === 0 ) {
	$clean_path = trim( substr( $clean_path, strlen( $home_path ) ), '/' );
}

$virtual_templates = array(
	'about'                        => 'page-about.php',
	'about-us'                     => 'page-about.php',
	'studio-story'                 => 'page-about.php',
	'custom-art'                   => 'page-custom-art.php',
	'custom-commission'            => 'page-custom-art.php',
	'commissions'                  => 'page-custom-art.php',
	'categories'                   => 'page-categories.php',
	'collections'                  => 'page-categories.php',
	'all-categories'               => 'page-categories.php',
	'shipping-policy'              => 'page-shipping-policy.php',
	'shipping-and-delivery-policy' => 'page-shipping-policy.php',
	'shipping-delivery-policy'     => 'page-shipping-policy.php',
	'refund_returns'               => 'page-refund-returns.php',
	'refund-returns'               => 'page-refund-returns.php',
	'returns-policy'               => 'page-refund-returns.php',
	'refund-and-return-policy'     => 'page-refund-returns.php',
	'privacy-policy'               => 'page-privacy-policy.php',
	'terms-conditions'             => 'page-terms-conditions.php',
	'terms-and-conditions'         => 'page-terms-conditions.php',
);

if ( isset( $virtual_templates[ $clean_path ] ) ) {
	status_header( 200 );
	global $wp_query;
	if ( isset( $wp_query ) ) {
		$wp_query->is_404 = false;
		$wp_query->is_page = true;
		$wp_query->is_singular = true;
	}
	$target_tpl = locate_template( array( $virtual_templates[ $clean_path ] ) );
	if ( $target_tpl && file_exists( $target_tpl ) ) {
		require $target_tpl;
		return;
	}
}

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
			<a href="<?php echo esc_url( home_url( '/product-category/canvas-painting-evara-art/' ) ); ?>" class="evara-btn btn-primary">
				<span><?php esc_html_e( 'Explore Canvas Paintings', 'evara-art' ); ?></span>
				<i class="fa-solid fa-arrow-right"></i>
			</a>
			<a href="<?php echo esc_url( home_url( '/product-category/clay-art-evara-art/' ) ); ?>" class="evara-btn btn-outline">
				<span><?php esc_html_e( 'Explore Lippan Art', 'evara-art' ); ?></span>
			</a>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="evara-btn btn-secondary">
				<?php esc_html_e( 'Return Home', 'evara-art' ); ?>
			</a>
		</div>
	</div>
</div>

<?php get_footer(); ?>
