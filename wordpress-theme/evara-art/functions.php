<?php
/**
 * Evara Art Theme Functions & Security Hardening
 *
 * @package EvaraArt
 * @version 1.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EVARA_VERSION', '1.2.0' );
define( 'EVARA_DIR', get_template_directory() );
define( 'EVARA_URI', get_template_directory_uri() );
define( 'EVARA_FREE_SHIPPING_THRESHOLD', 2999 );

/**
 * Global helper: Free shipping threshold
 */
function evara_get_free_shipping_threshold() {
	return (int) apply_filters( 'evara_free_shipping_threshold', EVARA_FREE_SHIPPING_THRESHOLD );
}

/**
 * 1. Security Headers & Protection (Standards-compliant)
 */
function evara_security_headers() {
	if ( ! is_admin() ) {
		header( 'X-Content-Type-Options: nosniff' );
		header( 'X-Frame-Options: SAMEORIGIN' );
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

/**
 * Block User Enumeration via /?author=N safely
 * Uses template_redirect to avoid breaking REST API, Gutenberg editor, or WP-JSON calls.
 */
function evara_block_author_enumeration() {
	if ( is_admin() ) {
		return;
	}
	if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
		return;
	}
	if ( function_exists( 'wp_is_json_request' ) && wp_is_json_request() ) {
		return;
	}
	if ( isset( $_SERVER['REQUEST_URI'] ) && false !== strpos( $_SERVER['REQUEST_URI'], 'wp-json' ) ) {
		return;
	}
	if ( isset( $_GET['author'] ) && ! empty( $_GET['author'] ) ) {
		wp_safe_redirect( home_url( '/' ), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'evara_block_author_enumeration' );

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
	load_theme_textdomain( 'evara-art', EVARA_DIR . '/languages' );

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
 * Default Primary Navigation Fallback
 */
function evara_default_primary_menu() {
	echo '<ul class="nav-links">';
	echo '<li><a href="' . esc_url( home_url( '/' ) ) . '" class="' . ( is_front_page() ? 'active' : '' ) . '">' . esc_html__( 'Home', 'evara-art' ) . '</a></li>';
	echo '<li><a href="' . esc_url( home_url( '/canvas-paintings-home-decor/' ) ) . '">' . esc_html__( 'Canvas Paintings', 'evara-art' ) . '</a></li>';
	echo '<li><a href="' . esc_url( home_url( '/lippan-art-home-decor/' ) ) . '">' . esc_html__( 'Lippan Art', 'evara-art' ) . '</a></li>';
	echo '<li><a href="' . esc_url( home_url( '/custom-art/' ) ) . '" class="nav-highlight">' . esc_html__( 'Custom Art', 'evara-art' ) . '</a></li>';
	echo '<li><a href="' . esc_url( home_url( '/contact/' ) ) . '">' . esc_html__( 'About', 'evara-art' ) . '</a></li>';
	echo '</ul>';
}

/**
 * Default Mobile Navigation Fallback
 */
function evara_default_mobile_menu() {
	echo '<ul class="mobile-nav-list">';
	echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'evara-art' ) . '</a></li>';
	echo '<li><a href="' . esc_url( home_url( '/canvas-paintings-home-decor/' ) ) . '">' . esc_html__( 'Canvas Paintings', 'evara-art' ) . '</a></li>';
	echo '<li><a href="' . esc_url( home_url( '/lippan-art-home-decor/' ) ) . '">' . esc_html__( 'Lippan & Clay Mirror Art', 'evara-art' ) . '</a></li>';
	echo '<li><a href="' . esc_url( home_url( '/custom-art/' ) ) . '" class="highlight-link">✦ ' . esc_html__( 'Request Custom Commission', 'evara-art' ) . '</a></li>';
	echo '<li><a href="' . esc_url( home_url( '/contact/' ) ) . '">' . esc_html__( 'About & Studio Contact', 'evara-art' ) . '</a></li>';
	if ( class_exists( 'WooCommerce' ) ) {
		echo '<li><a href="' . esc_url( wc_get_page_permalink( 'myaccount' ) ) . '">' . esc_html__( 'My Orders & Account', 'evara-art' ) . '</a></li>';
	}
	echo '</ul>';
}

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
		// Include wc-cart-fragments as dependency so drawer stays reactive
		$cart_deps = array( 'jquery' );
		if ( wp_script_is( 'wc-cart-fragments', 'registered' ) ) {
			$cart_deps[] = 'wc-cart-fragments';
		}
		wp_enqueue_script( 'evara-cart-drawer', EVARA_URI . '/assets/js/cart-drawer.js', $cart_deps, EVARA_VERSION, true );

		wp_localize_script( 'evara-cart-drawer', 'evara_vars', array(
			'ajax_url'          => admin_url( 'admin-ajax.php' ),
			'wc_ajax_url'       => WC_AJAX::get_endpoint( '%%endpoint%%' ),
			'free_shipping_min' => evara_get_free_shipping_threshold(),
			'currency_symbol'   => get_woocommerce_currency_symbol(),
			'nonce'             => wp_create_nonce( 'evara_cart_nonce' ),
		) );
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'evara_art_scripts' );

/**
 * 4. Free Shipping Progress Calculator
 */
function evara_get_shipping_progress() {
	$threshold = evara_get_free_shipping_threshold();
	if ( ! class_exists( 'WooCommerce' ) || ! WC()->cart ) {
		return array( 'percentage' => 0, 'remaining' => $threshold, 'eligible' => false );
	}
	$subtotal = (float) WC()->cart->get_subtotal();
	$remaining = max( 0, $threshold - $subtotal );
	$percentage = min( 100, round( ( $subtotal / $threshold ) * 100 ) );

	return array(
		'percentage' => $percentage,
		'remaining'  => $remaining,
		'eligible'   => $subtotal >= $threshold,
	);
}

/**
 * 5. Render Free Shipping Bar Markup
 */
function evara_render_shipping_threshold_banner() {
	$shipping_data = evara_get_shipping_progress();
	ob_start();
	?>
	<div class="shipping-threshold-banner evara-shipping-banner">
		<?php if ( $shipping_data['eligible'] ) : ?>
			<p class="threshold-msg success">
				<i class="fa-solid fa-circle-check"></i> <?php esc_html_e( 'Congratulations! You unlocked', 'evara-art' ); ?> <strong><?php esc_html_e( 'Free Pan-India Delivery', 'evara-art' ); ?></strong>
			</p>
		<?php else : ?>
			<p class="threshold-msg">
				<?php printf( esc_html__( 'Add %s more to unlock ', 'evara-art' ), '<strong>' . wc_price( $shipping_data['remaining'] ) . '</strong>' ); ?><strong><?php esc_html_e( 'Free Pan-India Delivery', 'evara-art' ); ?></strong>!
			</p>
		<?php endif; ?>
		<div class="threshold-progress-bar">
			<div class="threshold-progress-fill" style="width: <?php echo esc_attr( $shipping_data['percentage'] ); ?>%;"></div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * 6. WooCommerce AJAX Cart Fragments
 * Ensures the outer div wrapper is preserved on every replaceWith() cycle.
 */
function evara_cart_fragments( $fragments ) {
	if ( ! class_exists( 'WooCommerce' ) || ! WC()->cart ) {
		return $fragments;
	}

	$cart_count = (int) WC()->cart->get_cart_contents_count();

	// 1. Header Cart Badge
	ob_start();
	?>
	<span class="cart-count-badge" id="evara-cart-badge">
		<?php echo esc_html( $cart_count ); ?>
	</span>
	<?php
	$fragments['#evara-cart-badge'] = ob_get_clean();

	// 2. Drawer Title Count "(N)"
	ob_start();
	?>
	<span class="drawer-count evara-drawer-count">(<?php echo esc_html( $cart_count ); ?>)</span>
	<?php
	$fragments['span.evara-drawer-count'] = ob_get_clean();

	// 3. Cart Drawer Items (CRITICAL: wrapped in outer <div class="evara-cart-drawer-items">)
	ob_start();
	?>
	<div class="evara-cart-drawer-items">
		<?php get_template_part( 'template-parts/cart-drawer-items' ); ?>
	</div>
	<?php
	$fragments['div.evara-cart-drawer-items'] = ob_get_clean();

	// 4. Free Shipping Bar & Progress
	$fragments['div.evara-shipping-banner'] = evara_render_shipping_threshold_banner();

	// 5. Drawer Subtotal
	ob_start();
	?>
	<span class="subtotal-amount evara-subtotal-amount">
		<?php echo WC()->cart->get_cart_subtotal(); ?>
	</span>
	<?php
	$fragments['span.evara-subtotal-amount'] = ob_get_clean();

	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'evara_cart_fragments' );

/**
 * 7. Suppress Duplicate WooCommerce Page Title on Shop / Archives
 * Our custom .evara-shop-hero template outputs the singular luxury H1 banner.
 */
add_filter( 'woocommerce_show_page_title', function( $show ) {
	if ( is_shop() || is_product_taxonomy() ) {
		return false;
	}
	return $show;
} );

/**
 * 8. Always Link Product Cards to Single Product Page
 */
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

add_action( 'woocommerce_before_shop_loop_item', function() {
	echo '<a href="' . esc_url( get_permalink() ) . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link" aria-label="' . esc_attr( get_the_title() ) . '">';
}, 10 );

add_action( 'woocommerce_after_shop_loop_item', function() {
	echo '</a>';
}, 5 );
