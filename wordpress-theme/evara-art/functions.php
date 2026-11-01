<?php
/**
 * Evara Art Theme Functions & Security Hardening
 *
 * @package EvaraArt
 * @version 1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EVARA_VERSION', '1.2.0' );
define( 'EVARA_DIR', get_template_directory() );
define( 'EVARA_URI', get_template_directory_uri() );

/**
 * 1. Security Headers & Protection
 */
function evara_security_headers() {
	if ( ! is_admin() ) {
		header( 'X-Content-Type-Options: nosniff' );
		header( 'X-Frame-Options: SAMEORIGIN' );
		header( 'X-XSS-Protection: 1; mode=block' );
		header( 'Referrer-Policy: strict-origin-when-cross-origin' );
		header( 'Permissions-Policy: geolocation=(), microphone=(), camera=()' );
	}
}
add_action( 'send_headers', 'evara_security_headers' );

// Remove WordPress Version Generator
remove_action( 'wp_head', 'wp_generator' );
add_filter( 'the_generator', '__return_empty_string' );

// Disable XML-RPC to prevent brute-force amplification attacks
add_filter( 'xmlrpc_enabled', '__return_false' );

// Block User Enumeration via /?author=N
function evara_block_author_enumeration() {
	if ( ! is_admin() && isset( $_REQUEST['author'] ) ) {
		wp_safe_redirect( home_url( '/' ), 301 );
		exit;
	}
}
add_action( 'init', 'evara_block_author_enumeration' );

// Security Hardening: Disable REST API user enumeration for non-authenticated users
function evara_disable_rest_user_enumeration( $endpoints ) {
	if ( ! is_user_logged_in() && isset( $endpoints['/wp/v2/users'] ) ) {
		unset( $endpoints['/wp/v2/users'] );
	}
	if ( ! is_user_logged_in() && isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) {
		unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
	}
	return $endpoints;
}
add_filter( 'rest_endpoints', 'evara_disable_rest_user_enumeration' );

// Remove user sitemap from WordPress Core XML Sitemap
function evara_remove_user_sitemap( $provider, $name ) {
	if ( 'users' === $name ) {
		return false;
	}
	return $provider;
}
add_filter( 'wp_sitemaps_add_provider', 'evara_remove_user_sitemap', 10, 2 );

/**
 * 2. Theme Setup
 */
function evara_art_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );

	add_theme_support( 'custom-logo', array(
		'height'      => 120,
		'width'       => 300,
		'flex-height' => true,
		'flex-width'  => true,
	) );

	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'evara-art' ),
		'footer'  => __( 'Footer Navigation', 'evara-art' ),
		'mobile'  => __( 'Mobile Navigation', 'evara-art' ),
	) );

	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
	) );

	// WooCommerce Setup with Full Gallery Features
	add_theme_support( 'woocommerce', array(
		'thumbnail_image_width' => 600,
		'single_image_width'    => 1200,
		'product_grid'          => array(
			'default_rows'    => 4,
			'min_rows'        => 2,
			'max_rows'        => 8,
			'default_columns' => 3,
			'min_columns'     => 2,
			'max_columns'     => 4,
		),
	) );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'evara_art_setup' );

/**
 * 3. Enqueue Styles and Scripts
 */
function evara_art_scripts() {
	wp_enqueue_style(
		'evara-google-fonts',
		'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'evara-icons',
		'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
		array(),
		'6.5.1'
	);

	wp_enqueue_style( 'evara-main', EVARA_URI . '/assets/css/main.css', array(), EVARA_VERSION );
	wp_enqueue_style( 'evara-animations', EVARA_URI . '/assets/css/animations.css', array( 'evara-main' ), EVARA_VERSION );

	if ( class_exists( 'WooCommerce' ) ) {
		wp_enqueue_style( 'evara-woocommerce', EVARA_URI . '/assets/css/woocommerce.css', array( 'evara-main' ), EVARA_VERSION );
	}

	wp_enqueue_script( 'evara-main-js', EVARA_URI . '/assets/js/main.js', array( 'jquery' ), EVARA_VERSION, true );

	if ( class_exists( 'WooCommerce' ) ) {
		wp_enqueue_script( 'evara-cart-drawer', EVARA_URI . '/assets/js/cart-drawer.js', array( 'jquery' ), EVARA_VERSION, true );
		wp_localize_script( 'evara-cart-drawer', 'evara_vars', array(
			'ajax_url'          => admin_url( 'admin-ajax.php' ),
			'wc_ajax_url'       => WC_AJAX::get_endpoint( '%%endpoint%%' ),
			'free_shipping_min' => 2999,
			'currency_symbol'   => get_woocommerce_currency_symbol(),
			'nonce'             => wp_create_nonce( 'evara_cart_nonce' ),
		) );
	}
}
add_action( 'wp_enqueue_scripts', 'evara_art_scripts' );

/**
 * 4. WooCommerce AJAX Cart Fragments
 */
function evara_cart_fragments( $fragments ) {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return $fragments;
	}

	ob_start();
	?>
	<span class="cart-count-badge" id="evara-cart-badge">
		<?php echo WC()->cart ? esc_html( WC()->cart->get_cart_contents_count() ) : '0'; ?>
	</span>
	<?php
	$fragments['#evara-cart-badge'] = ob_get_clean();

	ob_start();
	get_template_part( 'template-parts/cart-drawer-items' );
	$fragments['div.evara-cart-drawer-items'] = ob_get_clean();

	ob_start();
	?>
	<span class="evara-subtotal-amount">
		<?php echo WC()->cart ? WC()->cart->get_cart_subtotal() : '₹0.00'; ?>
	</span>
	<?php
	$fragments['span.evara-subtotal-amount'] = ob_get_clean();

	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'evara_cart_fragments' );

/**
 * 5. Free Shipping Progress Calculator
 */
function evara_get_shipping_progress() {
	if ( ! class_exists( 'WooCommerce' ) || ! WC()->cart ) {
		return array( 'percentage' => 0, 'remaining' => 2999, 'eligible' => false );
	}
	$threshold = 2999;
	$subtotal = WC()->cart->get_subtotal();
	$remaining = max( 0, $threshold - $subtotal );
	$percentage = min( 100, round( ( $subtotal / $threshold ) * 100 ) );

	return array(
		'percentage' => $percentage,
		'remaining'  => $remaining,
		'eligible'   => $subtotal >= $threshold,
	);
}

/**
 * 6. Always Link Product Cards to Single Product Page
 */
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

add_action( 'woocommerce_before_shop_loop_item', function() {
	echo '<a href="' . esc_url( get_permalink() ) . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link" aria-label="' . esc_attr( get_the_title() ) . '">';
}, 10 );

add_action( 'woocommerce_after_shop_loop_item', function() {
	echo '</a>';
}, 5 );
