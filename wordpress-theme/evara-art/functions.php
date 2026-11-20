<?php
/**
 * Evara Art Theme Functions & Security Hardening
 *
 * @package EvaraArt
 * @version 1.4.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Graceful fatal error logger to prevent silent critical death
register_shutdown_function( function() {
	$error = error_get_last();
	if ( $error && in_array( $error['type'], array( E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR ) ) ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( sprintf( '[EvaraArt] Fatal shutdown error: %s in %s on line %d', $error['message'], $error['file'], $error['line'] ) );
		}
	}
} );

define( 'EVARA_VERSION', '1.5.0' );
define( 'EVARA_DIR', get_template_directory() );
define( 'EVARA_URI', get_template_directory_uri() );
define( 'EVARA_FREE_SHIPPING_THRESHOLD', 2999 );

/**
 * Global helper: Free shipping threshold
 */
function evara_get_free_shipping_threshold() {
	$threshold = get_theme_mod( 'evara_free_shipping_threshold', 2999 );
	return (int) apply_filters( 'evara_free_shipping_threshold', $threshold );
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
	// Disable buggy hover zoom that injects duplicate floating .zoomImg overlays
	// add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'evara_art_setup' );

// Proactively ensure wc-product-gallery-zoom is disabled even if plugins enable it
add_action( 'after_setup_theme', function() {
	remove_theme_support( 'wc-product-gallery-zoom' );
}, 100 );

// Remove category descriptions from all WooCommerce product category and archive pages
add_action( 'init', function() {
	remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );
	remove_action( 'woocommerce_archive_description', 'woocommerce_product_archive_description', 10 );
} );

/**
 * Helper: Resolve dynamic WooCommerce Category URLs with fallbacks
 */
function evara_get_category_url( $slug_variants, $fallback_path ) {
	if ( class_exists( 'WooCommerce' ) ) {
		foreach ( (array) $slug_variants as $slug ) {
			$term = get_term_by( 'slug', $slug, 'product_cat' );
			if ( $term && ! is_wp_error( $term ) ) {
				$link = get_term_link( $term );
				if ( ! is_wp_error( $link ) ) {
					return $link;
				}
			}
		}
	}
	return home_url( $fallback_path );
}

/**
 * Helper: Resolve dynamic About & Studio Story URL
 */
function evara_get_about_url() {
	$page = get_page_by_path( 'about' );
	if ( ! $page ) {
		$page = get_page_by_path( 'about-us' );
	}
	if ( ! $page ) {
		$page = get_page_by_path( 'studio-story' );
	}
	if ( ! $page ) {
		$page = get_page_by_path( 'about-studio-story' );
	}
	if ( $page ) {
		return get_permalink( $page->ID );
	}
	return home_url( '/about/' );
}

/**
 * Helper: Resolve dynamic Custom Art Commissions URL
 */
function evara_get_custom_art_url() {
	$page = get_page_by_path( 'custom-art' );
	if ( ! $page ) {
		$page = get_page_by_path( 'custom-commission' );
	}
	if ( ! $page ) {
		$page = get_page_by_path( 'commissions' );
	}
	if ( $page ) {
		return get_permalink( $page->ID );
	}
	return home_url( '/custom-art/' );
}

/**
 * Redirect legacy URLs to their canonical destinations:
 * - /canvas-paintings-home-decor/ -> /product-category/canvas-painting-evara-art/
 * - /lippan-art-home-decor/ -> /product-category/clay-art-evara-art/
 * - /coming-soon/ -> /
 * - /privacy-policy-2/ & ?page_id=3 -> /privacy-policy/
 * - /refund_returns/ & ?page_id=25 -> /refund-returns/
 * - /product/annapakshi/ -> /product/annapakshi-lippan-wall-hanging-set-of-2/
 * - /product/lotus-pichwai/ -> /product/18-inch-lotus-pichwai-lippan-wall-hanging/
 */
add_action( 'template_redirect', 'evara_redirect_old_category_pages', 2 );
function evara_redirect_old_category_pages() {
	if ( is_admin() ) {
		return;
	}

	$req_uri  = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
	$raw_path = trim( parse_url( $req_uri, PHP_URL_PATH ), '/' );

	// Subfolder path normalization
	$home_path = trim( parse_url( home_url(), PHP_URL_PATH ), '/' );
	if ( ! empty( $home_path ) && strpos( $raw_path, $home_path ) === 0 ) {
		$raw_path = trim( substr( $raw_path, strlen( $home_path ) ), '/' );
	}

	// 1. Old category pages & coming soon
	if ( 'canvas-paintings-home-decor' === $raw_path || is_page( 'canvas-paintings-home-decor' ) ) {
		wp_safe_redirect( home_url( '/product-category/canvas-painting-evara-art/' ), 301 );
		exit;
	}
	if ( 'lippan-art-home-decor' === $raw_path || is_page( 'lippan-art-home-decor' ) ) {
		wp_safe_redirect( home_url( '/product-category/clay-art-evara-art/' ), 301 );
		exit;
	}
	if ( 'coming-soon' === $raw_path || is_page( 'coming-soon' ) ) {
		wp_safe_redirect( home_url( '/' ), 301 );
		exit;
	}

	// 2. Harmonize Privacy Policies (redirect /privacy-policy-2/ and ?page_id=3 or ?p=3 or ?p=192 to /privacy-policy/)
	if ( 'privacy-policy-2' === $raw_path || ( isset( $_GET['page_id'] ) && 3 == (int) $_GET['page_id'] ) || ( isset( $_GET['p'] ) && 3 == (int) $_GET['p'] ) || ( isset( $_GET['p'] ) && 192 == (int) $_GET['p'] ) ) {
		wp_safe_redirect( home_url( '/privacy-policy/' ), 301 );
		exit;
	}

	// 3. Harmonize Refund Policies (redirect /refund_returns/ and ?page_id=25 or ?p=25 to /refund-returns/)
	if ( 'refund_returns' === $raw_path || ( isset( $_GET['page_id'] ) && 25 == (int) $_GET['page_id'] ) || ( isset( $_GET['p'] ) && 25 == (int) $_GET['p'] ) ) {
		wp_safe_redirect( home_url( '/refund-returns/' ), 301 );
		exit;
	}

	// 4. Product Slugs Aliases, Test Product & Deleted Product Redirects (never 404)
	if ( 'product/test-product' === $raw_path || strpos( $raw_path, 'test-product' ) !== false || 'product-category/uncategorized' === $raw_path ) {
		wp_safe_redirect( home_url( '/shop/' ), 301 );
		exit;
	}
	if ( in_array( $raw_path, array(
		'product/peacock-harmony',
		'product/peacock-lippanart',
		'product/annapakshi-lippan-wall-hanging-set-of-2',
		'product/annapakshi',
		'product/18-inch-lotus-pichwai-lippan-wall-hanging',
		'product/lotus-pichwai',
		'product/regal-mayur-jharokha',
		'product/cow-lippan',
		'product/colourful-flower-rangoli'
	) ) ) {
		wp_safe_redirect( home_url( '/product-category/clay-art-evara-art/' ), 301 );
		exit;
	}
	if ( in_array( $raw_path, array( 'product/the-forever-frame' ) ) ) {
		wp_safe_redirect( home_url( '/product-category/canvas-painting-evara-art/' ), 301 );
		exit;
	}
}

/**
 * Harmonize Privacy Policy URL across WordPress core & WooCommerce
 */
add_filter( 'privacy_policy_url', function( $url ) {
	return home_url( '/privacy-policy/' );
} );
add_filter( 'woocommerce_get_privacy_policy_page_id', function( $id ) {
	$page = get_page_by_path( 'privacy-policy' );
	return $page ? $page->ID : $id;
} );

/**
 * Enable Customer Account Creation on WooCommerce My Account page
 */
add_filter( 'option_woocommerce_enable_myaccount_registration', function( $val ) {
	return 'yes';
} );

/**
 * Default Primary Navigation Fallback (No "Home" link, New Categories beside Logo)
 */
function evara_default_primary_menu() {
	$canvas_url   = evara_get_category_url( array( 'canvas-painting-evara-art', 'canvas-paintings-home-decor', 'canvas-paintings' ), '/product-category/canvas-painting-evara-art/' );
	$lippan_url   = evara_get_category_url( array( 'clay-art-evara-art', 'lippan-art-home-decor', 'lippan-art' ), '/product-category/clay-art-evara-art/' );
	$animal_url   = evara_get_category_url( array( 'animal-painting', 'animal-paintings', 'animal-art' ), '/product-category/animal-painting/' );
	$boho_url     = evara_get_category_url( array( 'boho-art', 'boho-paintings', 'boho' ), '/product-category/boho-art/' );
	$abstract_url = evara_get_category_url( array( 'abstract-painting', 'abstract-paintings', 'abstract-art' ), '/product-category/abstract-painting/' );

	echo '<ul class="nav-links">';
	echo '<li><a href="' . esc_url( $canvas_url ) . '">' . esc_html__( 'Canvas Paintings', 'evara-art' ) . '</a></li>';
	echo '<li><a href="' . esc_url( $lippan_url ) . '">' . esc_html__( 'Lippan & Clay Art', 'evara-art' ) . '</a></li>';
	echo '<li><a href="' . esc_url( $animal_url ) . '">' . esc_html__( 'Animal Painting', 'evara-art' ) . '</a></li>';
	echo '<li><a href="' . esc_url( $boho_url ) . '">' . esc_html__( 'Boho Art', 'evara-art' ) . '</a></li>';
	echo '<li><a href="' . esc_url( $abstract_url ) . '">' . esc_html__( 'Abstract Painting', 'evara-art' ) . '</a></li>';
	echo '<li><a href="' . esc_url( evara_get_custom_art_url() ) . '" class="nav-highlight">' . esc_html__( 'Custom Art', 'evara-art' ) . '</a></li>';
	echo '<li><a href="' . esc_url( evara_get_about_url() ) . '">' . esc_html__( 'About', 'evara-art' ) . '</a></li>';
	echo '</ul>';
}

/**
 * Default Mobile Navigation Fallback (High-Luxury Editorial Drawer)
 */
function evara_default_mobile_menu() {
	$canvas_url   = evara_get_category_url( array( 'canvas-painting-evara-art', 'canvas-paintings-home-decor', 'canvas-paintings' ), '/product-category/canvas-painting-evara-art/' );
	$lippan_url   = evara_get_category_url( array( 'clay-art-evara-art', 'lippan-art-home-decor', 'lippan-art' ), '/product-category/clay-art-evara-art/' );
	$animal_url   = evara_get_category_url( array( 'animal-painting', 'animal-paintings', 'animal-art' ), '/product-category/animal-painting/' );
	$boho_url     = evara_get_category_url( array( 'boho-art', 'boho-paintings', 'boho' ), '/product-category/boho-art/' );
	$abstract_url = evara_get_category_url( array( 'abstract-painting', 'abstract-paintings', 'abstract-art' ), '/product-category/abstract-painting/' );
	?>
	<div class="mobile-drawer-nav-sections">
		
		<!-- Section 1: Curated Collections -->
		<div class="drawer-nav-group">
			<span class="drawer-group-label">✦ <?php esc_html_e( 'Art Collections', 'evara-art' ); ?></span>
			<ul class="mobile-nav-list">
				<li>
					<a href="<?php echo esc_url( $canvas_url ); ?>" class="drawer-nav-link">
						<span class="link-title"><?php esc_html_e( 'Canvas Paintings', 'evara-art' ); ?></span>
						<i class="fa-solid fa-chevron-right link-arrow"></i>
					</a>
				</li>
				<li>
					<a href="<?php echo esc_url( $lippan_url ); ?>" class="drawer-nav-link">
						<span class="link-title"><?php esc_html_e( 'Lippan & Clay Art', 'evara-art' ); ?></span>
						<i class="fa-solid fa-chevron-right link-arrow"></i>
					</a>
				</li>
				<li>
					<a href="<?php echo esc_url( $animal_url ); ?>" class="drawer-nav-link">
						<span class="link-title"><?php esc_html_e( 'Animal Painting', 'evara-art' ); ?></span>
						<i class="fa-solid fa-chevron-right link-arrow"></i>
					</a>
				</li>
				<li>
					<a href="<?php echo esc_url( $boho_url ); ?>" class="drawer-nav-link">
						<span class="link-title"><?php esc_html_e( 'Boho Art', 'evara-art' ); ?></span>
						<i class="fa-solid fa-chevron-right link-arrow"></i>
					</a>
				</li>
				<li>
					<a href="<?php echo esc_url( $abstract_url ); ?>" class="drawer-nav-link">
						<span class="link-title"><?php esc_html_e( 'Abstract Painting', 'evara-art' ); ?></span>
						<i class="fa-solid fa-chevron-right link-arrow"></i>
					</a>
				</li>
			</ul>

			<!-- All Categories Hub Pill -->
			<a href="<?php echo esc_url( home_url( '/categories/' ) ); ?>" class="drawer-hub-pill">
				<span class="hub-pill-icon"><i class="fa-solid fa-shapes"></i></span>
				<span class="hub-pill-text"><?php esc_html_e( 'View All Art Categories', 'evara-art' ); ?></span>
				<i class="fa-solid fa-arrow-right hub-pill-arrow"></i>
			</a>
		</div>

		<!-- Section 2: Custom Commission Spotlight -->
		<div class="drawer-commission-card">
			<span class="d-comm-badge">✦ <?php esc_html_e( 'Bespoke Fine Art', 'evara-art' ); ?></span>
			<h4 class="d-comm-title"><?php esc_html_e( 'Request Custom Painting', 'evara-art' ); ?></h4>
			<p class="d-comm-desc"><?php esc_html_e( 'Have a personalized artwork painted by lead artist Shraddha Parmar.', 'evara-art' ); ?></p>
			<a href="<?php echo esc_url( evara_get_custom_art_url() ); ?>" class="d-comm-btn">
				<span><?php esc_html_e( 'Start Commission', 'evara-art' ); ?></span>
				<i class="fa-solid fa-arrow-right"></i>
			</a>
		</div>

		<!-- Section 3: Studio & Account -->
		<div class="drawer-nav-group">
			<span class="drawer-group-label">✦ <?php esc_html_e( 'Studio & Account', 'evara-art' ); ?></span>
			<ul class="mobile-nav-list sub-list">
				<li>
					<a href="<?php echo esc_url( evara_get_about_url() ); ?>" class="drawer-nav-link-sub">
						<i class="fa-regular fa-compass"></i>
						<span><?php esc_html_e( 'About & Studio Story', 'evara-art' ); ?></span>
					</a>
				</li>
				<?php if ( class_exists( 'WooCommerce' ) ) : ?>
					<li>
						<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="drawer-nav-link-sub">
							<i class="fa-regular fa-user"></i>
							<span><?php esc_html_e( 'My Orders & Account', 'evara-art' ); ?></span>
						</a>
					</li>
				<?php endif; ?>
				<li>
					<a href="<?php echo esc_url( home_url( '/shipping-policy/' ) ); ?>" class="drawer-nav-link-sub">
						<i class="fa-solid fa-truck-fast"></i>
						<span><?php esc_html_e( 'Insured Express Shipping', 'evara-art' ); ?></span>
					</a>
				</li>
				<li>
					<a href="https://wa.me/919328143036?text=Hi%20Evara%20Art,%20I'm%20interested%20in%20your%20artwork" target="_blank" rel="noopener noreferrer" class="drawer-nav-link-sub">
						<i class="fa-brands fa-whatsapp" style="color: #25D366;"></i>
						<span><?php esc_html_e( 'WhatsApp Studio Chat', 'evara-art' ); ?></span>
					</a>
				</li>
			</ul>
		</div>

	</div>
	<?php
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
	wp_localize_script( 'evara-main-js', 'evara_main_vars', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'home_url' => home_url( '/' ),
	) );

	if ( class_exists( 'WooCommerce' ) ) {
		// Include wc-cart-fragments as dependency so drawer stays reactive
		$cart_deps = array( 'jquery' );
		if ( wp_script_is( 'wc-cart-fragments', 'registered' ) ) {
			$cart_deps[] = 'wc-cart-fragments';
		}
		wp_enqueue_script( 'evara-cart-drawer', EVARA_URI . '/assets/js/cart-drawer.js', $cart_deps, EVARA_VERSION, true );

		$wc_ajax_endpoint = class_exists( 'WC_AJAX' ) ? WC_AJAX::get_endpoint( '%%endpoint%%' ) : add_query_arg( 'wc-ajax', '%%endpoint%%', home_url( '/' ) );

		wp_localize_script( 'evara-cart-drawer', 'evara_vars', array(
			'ajax_url'          => admin_url( 'admin-ajax.php' ),
			'wc_ajax_url'       => $wc_ajax_endpoint,
			'cart_url'          => function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/cart/' ),
			'free_shipping_min' => evara_get_free_shipping_threshold(),
			'currency_symbol'   => get_woocommerce_currency_symbol(),
			'nonce'             => wp_create_nonce( 'evara_cart_nonce' ),
		) );
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'evara_art_scripts', 9999 );

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

/**
 * 9. Single Product Page Enhancements: WhatsApp Order Button & Authenticity Guarantee Box
 */
add_action( 'woocommerce_single_product_summary', 'evara_single_product_trust_box', 35 );
function evara_single_product_trust_box() {
	global $product;
	if ( ! $product ) {
		return;
	}
	$phone = get_theme_mod( 'evara_contact_phone', '+91 93281 43036' );
	$clean_phone = preg_replace( '/[^0-9]/', '', $phone );
	$title = rawurlencode( $product->get_name() );
	$url = rawurlencode( get_permalink( $product->get_id() ) );
	$wa_link = "https://wa.me/{$clean_phone}?text=Hi%20Shraddha,%20I'm%20interested%20in%20ordering:%20{$title}%20({$url})";
	?>
	<div class="single-product-actions-extra">
		<a href="<?php echo esc_url( $wa_link ); ?>" target="_blank" rel="noopener noreferrer" class="evara-btn btn-whatsapp-inquire">
			<i class="fa-brands fa-whatsapp"></i> <?php esc_html_e( 'Order / Inquire via WhatsApp', 'evara-art' ); ?>
		</a>
	</div>

	<div class="product-trust-card">
		<div class="trust-point">
			<i class="fa-solid fa-paintbrush"></i>
			<div>
				<strong><?php esc_html_e( '100% Hand-Painted Original', 'evara-art' ); ?></strong>
				<p><?php esc_html_e( 'Heavy textured acrylic on 380 GSM cotton canvas. Never a machine print.', 'evara-art' ); ?></p>
			</div>
		</div>
		<div class="trust-point">
			<i class="fa-solid fa-certificate"></i>
			<div>
				<strong><?php esc_html_e( 'Certificate of Authenticity', 'evara-art' ); ?></strong>
				<p><?php esc_html_e( 'Individually signed and certified by lead artist Shraddha Parmar.', 'evara-art' ); ?></p>
			</div>
		</div>
		<div class="trust-point">
			<i class="fa-solid fa-shield-halved"></i>
			<div>
				<strong><?php esc_html_e( 'Zero-Risk Transit Guarantee', 'evara-art' ); ?></strong>
				<p><?php esc_html_e( 'Armored wooden crating with 100% replacement protection against courier damage.', 'evara-art' ); ?></p>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Sanitizer for Customizer Image Controls (supports attachment ID, raw URL, or sanitized URL)
 */
function evara_sanitize_image_upload( $input ) {
	if ( empty( $input ) ) {
		return '';
	}
	if ( is_numeric( $input ) ) {
		return absint( $input );
	}
	return esc_url_raw( $input );
}

/**
 * Helper: Resolve image upload values (attachment ID or URL) safely into a valid image URL
 */
function evara_get_image_url( $val, $size = 'full' ) {
	if ( empty( $val ) ) {
		return '';
	}
	if ( is_numeric( $val ) ) {
		$url = wp_get_attachment_image_url( (int) $val, $size );
		return $url ? $url : '';
	}
	// Check if it's an accidental "http://<id>" caused by previous esc_url_raw
	if ( preg_match( '#^https?://([0-9]+)$#', $val, $matches ) ) {
		$url = wp_get_attachment_image_url( (int) $matches[1], $size );
		if ( $url ) {
			return $url;
		}
	}
	return esc_url( $val );
}

/**
 * 10. WordPress Theme Customizer: Edit the Entire Website via Appearance > Customize
 */
function evara_customize_register( $wp_customize ) {

	// Main Panel: Evara Art Store Settings
	$wp_customize->add_panel( 'evara_theme_panel', array(
		'title'       => __( 'Evara Art Store Settings', 'evara-art' ),
		'description' => __( 'Edit your homepage banner, text, announcements, artist story, trust badges, and studio contacts.', 'evara-art' ),
		'priority'    => 20,
	) );

	// --- SECTION 1: Free Shipping Threshold ---
	$wp_customize->add_section( 'evara_announcement_section', array(
		'title'    => __( 'Free Shipping Threshold', 'evara-art' ),
		'panel'    => 'evara_theme_panel',
		'priority' => 10,
	) );

	$wp_customize->add_setting( 'evara_free_shipping_threshold', array(
		'default'           => 2999,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'evara_free_shipping_threshold', array(
		'label'       => __( 'Free Shipping Threshold (₹)', 'evara-art' ),
		'description' => __( 'Orders above this amount qualify for 100% Free Pan-India Delivery in the cart drawer calculation.', 'evara-art' ),
		'section'     => 'evara_announcement_section',
		'type'        => 'number',
	) );

	// --- SECTION 2: Homepage Hero & Banner ---
	$wp_customize->add_section( 'evara_hero_section', array(
		'title'       => __( 'Homepage Hero & Banner', 'evara-art' ),
		'panel'       => 'evara_theme_panel',
		'description' => __( 'Upload your custom banners, set the banner text overlay, and adjust slider timing.', 'evara-art' ),
		'priority'    => 20,
	) );

	// Banner Timing (2-3 seconds)
	$wp_customize->add_setting( 'evara_hero_slider_interval', array(
		'default'           => 2500,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'evara_hero_slider_interval', array(
		'label'       => __( 'Banner Slider Timing (Seconds)', 'evara-art' ),
		'description' => __( 'How long each banner slide stays visible before transitioning to the next (e.g. 2.5s).', 'evara-art' ),
		'section'     => 'evara_hero_section',
		'type'        => 'select',
		'choices'     => array(
			2000 => __( '2.0 Seconds (Fast)', 'evara-art' ),
			2500 => __( '2.5 Seconds (Recommended)', 'evara-art' ),
			3000 => __( '3.0 Seconds (Relaxed)', 'evara-art' ),
			4000 => __( '4.0 Seconds', 'evara-art' ),
			5000 => __( '5.0 Seconds', 'evara-art' ),
		),
	) );

	// Caption Toggle
	$wp_customize->add_setting( 'evara_slider_show_caption', array(
		'default'           => 'yes',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'evara_slider_show_caption', array(
		'label'       => __( 'Show Text Caption on Banners', 'evara-art' ),
		'description' => __( 'Choose whether to display the text overlay badge and title on the banners.', 'evara-art' ),
		'section'     => 'evara_hero_section',
		'type'        => 'select',
		'choices'     => array(
			'yes' => __( 'Yes — Show Text Box on Banners', 'evara-art' ),
			'no'  => __( 'No — Hide Text Box (Images Only)', 'evara-art' ),
		),
	) );

	// Custom Slide 1
	$wp_customize->add_setting( 'evara_slide1_image', array( 'default' => '', 'sanitize_callback' => 'evara_sanitize_image_upload' ) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'evara_slide1_image', array(
		'label'       => __( 'Banner 1: Desktop Image', 'evara-art' ),
		'description' => __( 'Upload image for Banner 1 on desktop (recommended 1600x600 or 1400x520).', 'evara-art' ),
		'section'     => 'evara_hero_section',
	) ) );
	$wp_customize->add_setting( 'evara_slide1_mobile_image', array( 'default' => '', 'sanitize_callback' => 'evara_sanitize_image_upload' ) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'evara_slide1_mobile_image', array(
		'label'       => __( 'Banner 1: Mobile Image (Optional)', 'evara-art' ),
		'description' => __( 'Optional dedicated image for mobile screens (recommended 4:3 ratio: 1200x900 or 800x600). If empty, desktop image is centered automatically.', 'evara-art' ),
		'section'     => 'evara_hero_section',
	) ) );
	$wp_customize->add_setting( 'evara_slide1_title', array( 'default' => 'Art that connects to your HEART', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'evara_slide1_title', array( 'label' => __( 'Banner 1: Text / Title', 'evara-art' ), 'section' => 'evara_hero_section', 'type' => 'text' ) );
	$wp_customize->add_setting( 'evara_slide1_badge', array( 'default' => '✦ Original Canvas Artwork', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'evara_slide1_badge', array( 'label' => __( 'Banner 1: Badge / Tag', 'evara-art' ), 'section' => 'evara_hero_section', 'type' => 'text' ) );
	$wp_customize->add_setting( 'evara_slide1_url', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control( 'evara_slide1_url', array( 'label' => __( 'Banner 1: Click Link URL', 'evara-art' ), 'section' => 'evara_hero_section', 'type' => 'url' ) );

	// Custom Slide 2
	$wp_customize->add_setting( 'evara_slide2_image', array( 'default' => '', 'sanitize_callback' => 'evara_sanitize_image_upload' ) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'evara_slide2_image', array(
		'label'       => __( 'Banner 2: Desktop Image', 'evara-art' ),
		'section'     => 'evara_hero_section',
	) ) );
	$wp_customize->add_setting( 'evara_slide2_mobile_image', array( 'default' => '', 'sanitize_callback' => 'evara_sanitize_image_upload' ) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'evara_slide2_mobile_image', array(
		'label'       => __( 'Banner 2: Mobile Image (Optional)', 'evara-art' ),
		'description' => __( 'Optional mobile image (recommended 4:3 ratio: 1200x900 or 800x600).', 'evara-art' ),
		'section'     => 'evara_hero_section',
	) ) );
	$wp_customize->add_setting( 'evara_slide2_title', array( 'default' => 'Divine Darshan of Lord Jagannath', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'evara_slide2_title', array( 'label' => __( 'Banner 2: Text / Title', 'evara-art' ), 'section' => 'evara_hero_section', 'type' => 'text' ) );
	$wp_customize->add_setting( 'evara_slide2_badge', array( 'default' => '✦ Sacred Spiritual Art', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'evara_slide2_badge', array( 'label' => __( 'Banner 2: Badge / Tag', 'evara-art' ), 'section' => 'evara_hero_section', 'type' => 'text' ) );
	$wp_customize->add_setting( 'evara_slide2_url', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control( 'evara_slide2_url', array( 'label' => __( 'Banner 2: Click Link URL', 'evara-art' ), 'section' => 'evara_hero_section', 'type' => 'url' ) );

	// Custom Slide 3
	$wp_customize->add_setting( 'evara_slide3_image', array( 'default' => '', 'sanitize_callback' => 'evara_sanitize_image_upload' ) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'evara_slide3_image', array(
		'label'       => __( 'Banner 3: Desktop Image', 'evara-art' ),
		'section'     => 'evara_hero_section',
	) ) );
	$wp_customize->add_setting( 'evara_slide3_mobile_image', array( 'default' => '', 'sanitize_callback' => 'evara_sanitize_image_upload' ) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'evara_slide3_mobile_image', array(
		'label'       => __( 'Banner 3: Mobile Image (Optional)', 'evara-art' ),
		'description' => __( 'Optional mobile image (recommended 4:3 ratio: 1200x900 or 800x600).', 'evara-art' ),
		'section'     => 'evara_hero_section',
	) ) );
	$wp_customize->add_setting( 'evara_slide3_title', array( 'default' => 'Sacred Serenity Shiva Parvati', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'evara_slide3_title', array( 'label' => __( 'Banner 3: Text / Title', 'evara-art' ), 'section' => 'evara_hero_section', 'type' => 'text' ) );
	$wp_customize->add_setting( 'evara_slide3_badge', array( 'default' => '✦ Divine Harmony', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'evara_slide3_badge', array( 'label' => __( 'Banner 3: Badge / Tag', 'evara-art' ), 'section' => 'evara_hero_section', 'type' => 'text' ) );
	$wp_customize->add_setting( 'evara_slide3_url', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control( 'evara_slide3_url', array( 'label' => __( 'Banner 3: Click Link URL', 'evara-art' ), 'section' => 'evara_hero_section', 'type' => 'url' ) );

	// Custom Slide 4
	$wp_customize->add_setting( 'evara_slide4_image', array( 'default' => '', 'sanitize_callback' => 'evara_sanitize_image_upload' ) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'evara_slide4_image', array(
		'label'       => __( 'Banner 4: Desktop Image', 'evara-art' ),
		'section'     => 'evara_hero_section',
	) ) );
	$wp_customize->add_setting( 'evara_slide4_mobile_image', array( 'default' => '', 'sanitize_callback' => 'evara_sanitize_image_upload' ) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'evara_slide4_mobile_image', array(
		'label'       => __( 'Banner 4: Mobile Image (Optional)', 'evara-art' ),
		'description' => __( 'Optional mobile image (recommended 4:3 ratio: 1200x900 or 800x600).', 'evara-art' ),
		'section'     => 'evara_hero_section',
	) ) );
	$wp_customize->add_setting( 'evara_slide4_title', array( 'default' => 'Annapakshi Lippan Mirror Craft', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'evara_slide4_title', array( 'label' => __( 'Banner 4: Text / Title', 'evara-art' ), 'section' => 'evara_hero_section', 'type' => 'text' ) );
	$wp_customize->add_setting( 'evara_slide4_badge', array( 'default' => '✦ Heritage Craft', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'evara_slide4_badge', array( 'label' => __( 'Banner 4: Badge / Tag', 'evara-art' ), 'section' => 'evara_hero_section', 'type' => 'text' ) );
	$wp_customize->add_setting( 'evara_slide4_url', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control( 'evara_slide4_url', array( 'label' => __( 'Banner 4: Click Link URL', 'evara-art' ), 'section' => 'evara_hero_section', 'type' => 'url' ) );

	// Backwards compatibility legacy field
	$wp_customize->add_setting( 'evara_custom_banner_image', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );

	$wp_customize->add_setting( 'evara_hero_badge', array(
		'default'           => 'Handmade in India • Bespoke Fine Art',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'evara_hero_badge', array(
		'label'   => __( 'Hero Badge Text', 'evara-art' ),
		'section' => 'evara_hero_section',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'evara_hero_title', array(
		'default'           => 'Art that connects to your HEART.',
		'sanitize_callback' => 'wp_kses_post',
	) );
	$wp_customize->add_control( 'evara_hero_title', array(
		'label'   => __( 'Hero Main Heading', 'evara-art' ),
		'section' => 'evara_hero_section',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'evara_hero_subtitle', array(
		'default'           => 'Welcome to Evara Art – where creativity comes to life through every brushstroke. “Evara” means Gift of God, turning sacred imagination into meaningful, handcrafted wall art for your sanctuary.',
		'sanitize_callback' => 'wp_kses_post',
	) );
	$wp_customize->add_control( 'evara_hero_subtitle', array(
		'label'   => __( 'Hero Subtitle / Description', 'evara-art' ),
		'section' => 'evara_hero_section',
		'type'    => 'textarea',
	) );

	$wp_customize->add_setting( 'evara_hero_cta1_text', array(
		'default'           => 'Explore Canvas Paintings',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'evara_hero_cta1_text', array(
		'label'   => __( 'Primary Button Text', 'evara-art' ),
		'section' => 'evara_hero_section',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'evara_hero_cta1_url', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'evara_hero_cta1_url', array(
		'label'       => __( 'Primary Button Link (Leave blank for auto)', 'evara-art' ),
		'section'     => 'evara_hero_section',
		'type'        => 'url',
	) );

	$wp_customize->add_setting( 'evara_hero_cta2_text', array(
		'default'           => 'Explore Lippan Art',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'evara_hero_cta2_text', array(
		'label'   => __( 'Secondary Button Text', 'evara-art' ),
		'section' => 'evara_hero_section',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'evara_hero_cta2_url', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'evara_hero_cta2_url', array(
		'label'       => __( 'Secondary Button Link (Leave blank for auto)', 'evara-art' ),
		'section'     => 'evara_hero_section',
		'type'        => 'url',
	) );

	// Stats Row
	$wp_customize->add_setting( 'evara_stat1_number', array( 'default' => '100%', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'evara_stat1_number', array( 'label' => __( 'Stat 1 Number', 'evara-art' ), 'section' => 'evara_hero_section', 'type' => 'text' ) );
	$wp_customize->add_setting( 'evara_stat1_label', array( 'default' => 'Handmade', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'evara_stat1_label', array( 'label' => __( 'Stat 1 Label', 'evara-art' ), 'section' => 'evara_hero_section', 'type' => 'text' ) );

	$wp_customize->add_setting( 'evara_stat2_number', array( 'default' => 'Pan-India', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'evara_stat2_number', array( 'label' => __( 'Stat 2 Number', 'evara-art' ), 'section' => 'evara_hero_section', 'type' => 'text' ) );
	$wp_customize->add_setting( 'evara_stat2_label', array( 'default' => 'Collectors & Homes', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'evara_stat2_label', array( 'label' => __( 'Stat 2 Label', 'evara-art' ), 'section' => 'evara_hero_section', 'type' => 'text' ) );

	$wp_customize->add_setting( 'evara_stat3_number', array( 'default' => '5-Star', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'evara_stat3_number', array( 'label' => __( 'Stat 3 Number', 'evara-art' ), 'section' => 'evara_hero_section', 'type' => 'text' ) );
	$wp_customize->add_setting( 'evara_stat3_label', array( 'default' => 'Craftsmanship', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'evara_stat3_label', array( 'label' => __( 'Stat 3 Label', 'evara-art' ), 'section' => 'evara_hero_section', 'type' => 'text' ) );

	// Hero Side Card (Featured card next to editorial content on desktop)
	$wp_customize->add_setting( 'evara_showcase_card_show', array(
		'default'           => 'yes',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'evara_showcase_card_show', array(
		'label'       => __( 'Hero Side Card: Show / Hide', 'evara-art' ),
		'description' => __( 'Show or hide the featured artwork card next to the editorial text on desktop.', 'evara-art' ),
		'section'     => 'evara_hero_section',
		'type'        => 'select',
		'choices'     => array(
			'yes' => __( 'Yes — Show Card', 'evara-art' ),
			'no'  => __( 'No — Hide Card', 'evara-art' ),
		),
	) );

	$wp_customize->add_setting( 'evara_showcase_card_image', array(
		'default'           => '',
		'sanitize_callback' => 'evara_sanitize_image_upload',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'evara_showcase_card_image', array(
		'label'       => __( 'Hero Side Card: Custom Image', 'evara-art' ),
		'description' => __( 'Upload your custom artwork image for the card next to editorial text (e.g. 600x600).', 'evara-art' ),
		'section'     => 'evara_hero_section',
	) ) );

	$wp_customize->add_setting( 'evara_showcase_card_badge', array(
		'default'           => '✦ Original Artwork',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'evara_showcase_card_badge', array(
		'label'   => __( 'Hero Side Card: Badge Tag', 'evara-art' ),
		'section' => 'evara_hero_section',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'evara_showcase_card_title', array(
		'default'           => 'Sacred Spiritual Artworks',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'evara_showcase_card_title', array(
		'label'   => __( 'Hero Side Card: Title', 'evara-art' ),
		'section' => 'evara_hero_section',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'evara_showcase_card_cta', array(
		'default'           => 'Explore Collection',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'evara_showcase_card_cta', array(
		'label'   => __( 'Hero Side Card: Button Text', 'evara-art' ),
		'section' => 'evara_hero_section',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'evara_showcase_card_url', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'evara_showcase_card_url', array(
		'label'       => __( 'Hero Side Card: Link URL', 'evara-art' ),
		'description' => __( 'Leave empty to auto-link to Canvas Paintings.', 'evara-art' ),
		'section'     => 'evara_hero_section',
		'type'        => 'url',
	) );

	// --- SECTION 3: Artist Philosophy & Story ---
	$wp_customize->add_section( 'evara_philosophy_section', array(
		'title'    => __( 'Artist Philosophy & Story', 'evara-art' ),
		'panel'    => 'evara_theme_panel',
		'priority' => 30,
	) );

	$wp_customize->add_setting( 'evara_phil_badge', array(
		'default'           => '✦ The Sacred Heart of Evara',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'evara_phil_badge', array(
		'label'   => __( 'Section Badge Text', 'evara-art' ),
		'section' => 'evara_philosophy_section',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'evara_phil_title', array(
		'default'           => '“Evara” — A Gift of God, Painted From The Soul',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'evara_phil_title', array(
		'label'   => __( 'Main Heading', 'evara-art' ),
		'section' => 'evara_philosophy_section',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'evara_phil_quote', array(
		'default'           => 'Welcome to Evara Art – where creativity comes to life through every brushstroke. “Evara” means Gift of God and I believe the art I create is truly a blessing and a form of divine expression.',
		'sanitize_callback' => 'wp_kses_post',
	) );
	$wp_customize->add_control( 'evara_phil_quote', array(
		'label'   => __( 'Artist Quote / Story Lead', 'evara-art' ),
		'section' => 'evara_philosophy_section',
		'type'    => 'textarea',
	) );

	$wp_customize->add_setting( 'evara_pillar1_title', array( 'default' => 'Crafted with Passion', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'evara_pillar1_title', array( 'label' => __( 'Pillar 1 Title', 'evara-art' ), 'section' => 'evara_philosophy_section', 'type' => 'text' ) );
	$wp_customize->add_setting( 'evara_pillar1_desc', array( 'default' => 'Each piece is crafted with passion, turning imagination into meaningful and beautiful artwork that honors divine patience.', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'evara_pillar1_desc', array( 'label' => __( 'Pillar 1 Description', 'evara-art' ), 'section' => 'evara_philosophy_section', 'type' => 'textarea' ) );

	$wp_customize->add_setting( 'evara_pillar2_title', array( 'default' => 'Emotion & Inspiration', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'evara_pillar2_title', array( 'label' => __( 'Pillar 2 Title', 'evara-art' ), 'section' => 'evara_philosophy_section', 'type' => 'text' ) );
	$wp_customize->add_setting( 'evara_pillar2_desc', array( 'default' => 'My goal is to bring emotion, inspiration, and uniqueness into every space, creating a calm spiritual focal point for your home.', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'evara_pillar2_desc', array( 'label' => __( 'Pillar 2 Description', 'evara-art' ), 'section' => 'evara_philosophy_section', 'type' => 'textarea' ) );

	$wp_customize->add_setting( 'evara_pillar3_title', array( 'default' => 'Connecting with Your Soul', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'evara_pillar3_title', array( 'label' => __( 'Pillar 3 Title', 'evara-art' ), 'section' => 'evara_philosophy_section', 'type' => 'text' ) );
	$wp_customize->add_setting( 'evara_pillar3_desc', array( 'default' => 'Discover art that connects with your soul — timeless handmade canvas paintings and sacred Gujarat Lippan mirror craft.', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'evara_pillar3_desc', array( 'label' => __( 'Pillar 3 Description', 'evara-art' ), 'section' => 'evara_philosophy_section', 'type' => 'textarea' ) );

	// --- SECTION 4: Signature Collections & Portfolios ---
	$wp_customize->add_section( 'evara_collections_section', array(
		'title'       => __( 'Signature Collections (Cards & Images)', 'evara-art' ),
		'panel'       => 'evara_theme_panel',
		'description' => __( 'Customize the card images, titles, and descriptions of the two signature collections on the homepage.', 'evara-art' ),
		'priority'    => 35,
	) );

	// Canvas Collection Card
	$wp_customize->add_setting( 'evara_canvas_collection_img', array(
		'default'           => '',
		'sanitize_callback' => 'evara_sanitize_image_upload',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'evara_canvas_collection_img', array(
		'label'       => __( 'Canvas Paintings: Card Image', 'evara-art' ),
		'description' => __( 'Upload an image for the Canvas Paintings collection card (recommended 600x400 or 500x500).', 'evara-art' ),
		'section'     => 'evara_collections_section',
	) ) );

	$wp_customize->add_setting( 'evara_canvas_collection_title', array(
		'default'           => 'Canvas Paintings • Home Decor',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'evara_canvas_collection_title', array(
		'label'   => __( 'Canvas Paintings: Card Title', 'evara-art' ),
		'section' => 'evara_collections_section',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'evara_canvas_collection_desc', array(
		'default'           => 'Krishna, Shiv Shakti, Ganesha, and serene modern abstract portraits.',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'evara_canvas_collection_desc', array(
		'label'   => __( 'Canvas Paintings: Description', 'evara-art' ),
		'section' => 'evara_collections_section',
		'type'    => 'textarea',
	) );

	// Lippan Collection Card
	$wp_customize->add_setting( 'evara_lippan_collection_img', array(
		'default'           => '',
		'sanitize_callback' => 'evara_sanitize_image_upload',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'evara_lippan_collection_img', array(
		'label'       => __( 'Lippan & Clay Art: Card Image', 'evara-art' ),
		'description' => __( 'Upload an image for the Lippan Art collection card (recommended 600x400 or 500x500).', 'evara-art' ),
		'section'     => 'evara_collections_section',
	) ) );

	$wp_customize->add_setting( 'evara_lippan_collection_title', array(
		'default'           => 'Lippan Art • Home Decor',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'evara_lippan_collection_title', array(
		'label'   => __( 'Lippan Art: Card Title', 'evara-art' ),
		'section' => 'evara_collections_section',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'evara_lippan_collection_desc', array(
		'default'           => 'Intricate clay dough patterns and sparkling hand-cut mirrors for auspicious walls.',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'evara_lippan_collection_desc', array(
		'label'   => __( 'Lippan Art: Description', 'evara-art' ),
		'section' => 'evara_collections_section',
		'type'    => 'textarea',
	) );

	// --- SECTION 5: Studio Contact & Social Links ---
	$wp_customize->add_section( 'evara_contact_section', array(
		'title'    => __( 'Studio Contact & Footer Settings', 'evara-art' ),
		'panel'    => 'evara_theme_panel',
		'priority' => 40,
	) );

	// Footer Logo (Dedicated full-color upload)
	$wp_customize->add_setting( 'evara_footer_logo', array(
		'default'           => '',
		'sanitize_callback' => 'evara_sanitize_image_upload',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'evara_footer_logo', array(
		'label'       => __( 'Footer Brand Logo (Full Color)', 'evara-art' ),
		'description' => __( 'Upload a full-color logo for the footer. If left empty, the main site logo will be used.', 'evara-art' ),
		'section'     => 'evara_contact_section',
	) ) );

	$wp_customize->add_setting( 'evara_contact_phone', array( 'default' => '+91 93281 43036', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'evara_contact_phone', array( 'label' => __( 'Studio Phone / WhatsApp', 'evara-art' ), 'section' => 'evara_contact_section', 'type' => 'text' ) );

	$wp_customize->add_setting( 'evara_contact_email', array( 'default' => 'support@evaraart.in', 'sanitize_callback' => 'sanitize_email' ) );
	$wp_customize->add_control( 'evara_contact_email', array( 'label' => __( 'Studio Email', 'evara-art' ), 'section' => 'evara_contact_section', 'type' => 'email' ) );

	$wp_customize->add_setting( 'evara_contact_location', array( 'default' => 'Gujarat, India • Pan-India Insured Dispatch', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'evara_contact_location', array( 'label' => __( 'Studio Location / Dispatch Note', 'evara-art' ), 'section' => 'evara_contact_section', 'type' => 'text' ) );

	$wp_customize->add_setting( 'evara_instagram_url', array( 'default' => 'https://www.instagram.com/evaraart.in/', 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control( 'evara_instagram_url', array( 'label' => __( 'Instagram URL', 'evara-art' ), 'section' => 'evara_contact_section', 'type' => 'url' ) );

	$wp_customize->add_setting( 'evara_facebook_url', array( 'default' => 'https://www.facebook.com/people/Evara-Art/61574370844039/', 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control( 'evara_facebook_url', array( 'label' => __( 'Facebook URL', 'evara-art' ), 'section' => 'evara_contact_section', 'type' => 'url' ) );

	$wp_customize->add_setting( 'evara_linkedin_url', array( 'default' => 'https://www.linkedin.com/company/evara-art/', 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control( 'evara_linkedin_url', array( 'label' => __( 'LinkedIn URL', 'evara-art' ), 'section' => 'evara_contact_section', 'type' => 'url' ) );
}
add_action( 'customize_register', 'evara_customize_register' );

/**
 * 10. Auto-ensure core product categories exist in WooCommerce
 */
add_action( 'init', 'evara_ensure_product_categories' );
function evara_ensure_product_categories() {
	if ( ! taxonomy_exists( 'product_cat' ) ) {
		return;
	}
	$categories = array(
		'canvas-painting-evara-art' => array(
			'name' => 'Canvas Paintings',
			'desc' => 'Handmade acrylic and oil paintings on 380 GSM textured cotton canvas.',
		),
		'clay-art-evara-art'        => array(
			'name' => 'Lippan & Clay Art',
			'desc' => 'Authentic Kutch Gujarat Lippan mirror artwork with handcrafted mud relief work.',
		),
		'animal-painting'           => array(
			'name' => 'Animal Painting',
			'desc' => 'Handmade wildlife and animal fine artwork portraits painted with intricate realism.',
		),
		'boho-art'                  => array(
			'name' => 'Boho Art',
			'desc' => 'Earth-toned, bohemian, and minimalist textured art pieces for modern interior sanctuaries.',
		),
		'abstract-painting'         => array(
			'name' => 'Abstract Painting',
			'desc' => 'Bold contemporary abstract canvas artworks designed to evoke deep aesthetic emotion.',
		),
	);
	foreach ( $categories as $slug => $data ) {
		if ( ! term_exists( $slug, 'product_cat' ) ) {
			wp_insert_term(
				$data['name'],
				'product_cat',
				array(
					'slug'        => $slug,
					'description' => $data['desc'],
				)
			);
		}
	}
}

/**
 * 11. WooCommerce Account Registration & Instant Password Entry (No Email Dependency)
 */
add_filter( 'pre_option_woocommerce_enable_myaccount_registration', function() { return 'yes'; } );
add_filter( 'pre_option_woocommerce_registration_generate_password', function() { return 'no'; } );
add_filter( 'pre_option_woocommerce_registration_generate_username', function() { return 'no'; } );

/**
 * 12. Search Refinements:
 * - Do NOT automatically redirect to single product when only 1 result is found
 * - Keep user on full search results gallery so they see all related options
 */
add_filter( 'woocommerce_redirect_single_search_result', '__return_false' );

/**
 * 13. Instant Live AJAX Search for Header Search Modal
 */
add_action( 'wp_ajax_evara_live_search', 'evara_ajax_live_search' );
add_action( 'wp_ajax_nopriv_evara_live_search', 'evara_ajax_live_search' );
function evara_ajax_live_search() {
	$keyword = isset( $_GET['keyword'] ) ? sanitize_text_field( wp_unslash( $_GET['keyword'] ) ) : '';
	if ( strlen( $keyword ) < 2 ) {
		wp_send_json_success( array( 'results' => array(), 'total' => 0, 'keyword' => $keyword ) );
	}

	$args = array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => 6,
		's'              => $keyword,
	);

	$query = new WP_Query( $args );
	$results = array();

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$product = wc_get_product( get_the_ID() );
			if ( ! $product ) {
				continue;
			}

			$thumb = get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' );
			if ( ! $thumb ) {
				$thumb = wc_placeholder_img_src( 'thumbnail' );
			}

			$results[] = array(
				'id'        => get_the_ID(),
				'title'     => get_the_title(),
				'permalink' => get_permalink(),
				'thumbnail' => $thumb,
				'price'     => $product->get_price_html(),
			);
		}
		wp_reset_postdata();
	}

	wp_send_json_success( array(
		'results' => $results,
		'total'   => (int) $query->found_posts,
		'keyword' => $keyword,
		'all_url' => add_query_arg( array( 's' => $keyword, 'post_type' => 'product' ), home_url( '/' ) ),
	) );
}

/**
 * 14. Auto-Provision Core Pages in Database (About, Custom Art, Categories, Policies)
 * Runs safely ONLY on theme activation (never intercepts manual admin trashing/deletions)
 */
add_action( 'after_switch_theme', 'evara_auto_provision_essential_pages' );
function evara_auto_provision_essential_pages() {
	if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$pages_to_provision = array(
		'about' => array(
			'title'    => 'About & Studio Story',
			'template' => 'page-about.php',
		),
		'custom-art' => array(
			'title'    => 'Custom Art Commissions',
			'template' => 'page-custom-art.php',
		),
		'categories' => array(
			'title'    => 'All Art Categories',
			'template' => 'page-categories.php',
		),
		'shipping-policy' => array(
			'title'    => 'Shipping & Delivery Policy',
			'template' => 'page-shipping-policy.php',
		),
		'refund-returns' => array(
			'title'    => 'Returns & Refund Policy',
			'template' => 'page-refund-returns.php',
		),
		'refund_returns' => array(
			'title'    => 'Returns & Refund Policy',
			'template' => 'page-refund_returns.php',
		),
		'privacy-policy' => array(
			'title'    => 'Privacy Policy',
			'template' => 'page-privacy-policy.php',
		),
		'terms-conditions' => array(
			'title'    => 'Terms & Conditions',
			'template' => 'page-terms-conditions.php',
		),
	);

	foreach ( $pages_to_provision as $slug => $data ) {
		$query = new WP_Query( array(
			'name'           => $slug,
			'post_type'      => 'page',
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'fields'         => 'ids',
		) );

		if ( ! empty( $query->posts ) ) {
			$page_id = $query->posts[0];
			update_post_meta( $page_id, '_wp_page_template', $data['template'] );
		} else {
			$new_id = wp_insert_post( array(
				'post_title'     => $data['title'],
				'post_name'      => $slug,
				'post_status'    => 'publish',
				'post_type'      => 'page',
				'comment_status' => 'closed',
				'ping_status'    => 'closed',
			) );
			if ( $new_id && ! is_wp_error( $new_id ) ) {
				update_post_meta( $new_id, '_wp_page_template', $data['template'] );
			}
		}
	}
}

/**
 * 15. Safe One-Time Cleanup on Theme Activation: Move Unwanted Test/Draft Products to Trash
 * The theme NEVER forcibly recreates or un-trashes products. The store owner has 100% control to delete or empty trash.
 */
add_action( 'after_switch_theme', 'evara_cleanup_unwanted_products' );
function evara_cleanup_unwanted_products() {
	$unwanted_slugs = array(
		'peacock-harmony',
		'the-forever-frame',
		'annapakshi-lippan-wall-hanging-set-of-2',
		'18-inch-lotus-pichwai-lippan-wall-hanging',
	);

	foreach ( $unwanted_slugs as $slug ) {
		$posts = get_posts( array(
			'name'        => $slug,
			'post_type'   => 'product',
			'post_status' => 'publish',
			'numberposts' => 1,
			'fields'      => 'ids',
		) );
		if ( ! empty( $posts ) ) {
			wp_trash_post( $posts[0] );
		}
	}
}

/**
 * Purge LiteSpeed Cache on Theme Activation / Update
 */
add_action( 'after_switch_theme', function() {
	if ( has_action( 'litespeed_purge_all' ) ) {
		do_action( 'litespeed_purge_all' );
	}
} );

/**
 * 15. Early Intercept on template_redirect for Core Pages
 */
add_action( 'template_redirect', 'evara_intercept_core_virtual_pages', 1 );
function evara_intercept_core_virtual_pages() {
	global $wp_query;

	$req_uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
	$raw_path = trim( parse_url( $req_uri, PHP_URL_PATH ), '/' );

	$home_path = trim( parse_url( home_url(), PHP_URL_PATH ), '/' );
	if ( ! empty( $home_path ) && strpos( $raw_path, $home_path ) === 0 ) {
		$raw_path = trim( substr( $raw_path, strlen( $home_path ) ), '/' );
	}

	$slug_map = array(
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
		'refund-returns'               => 'page-refund-returns.php',
		'refund_returns'               => 'page-refund_returns.php',
		'returns-policy'               => 'page-refund-returns.php',
		'refund-and-return-policy'     => 'page-refund-returns.php',
		'privacy-policy'               => 'page-privacy-policy.php',
		'terms-conditions'             => 'page-terms-conditions.php',
		'terms-and-conditions'         => 'page-terms-conditions.php',
	);

	if ( isset( $slug_map[ $raw_path ] ) ) {
		$target_file = locate_template( array( $slug_map[ $raw_path ] ) );
		if ( $target_file && file_exists( $target_file ) ) {
			status_header( 200 );
			if ( isset( $wp_query ) ) {
				$wp_query->is_404 = false;
				$wp_query->is_page = true;
				$wp_query->is_singular = true;
			}
			require $target_file;
			exit;
		}
	}
}

/**
 * 16. Template Include Filter Fallback
 */
add_filter( 'template_include', 'evara_core_virtual_page_template_loader', 99 );
function evara_core_virtual_page_template_loader( $template ) {
	global $wp_query;

	$req_uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
	$raw_path = trim( parse_url( $req_uri, PHP_URL_PATH ), '/' );

	$home_path = trim( parse_url( home_url(), PHP_URL_PATH ), '/' );
	if ( ! empty( $home_path ) && strpos( $raw_path, $home_path ) === 0 ) {
		$raw_path = trim( substr( $raw_path, strlen( $home_path ) ), '/' );
	}

	$slug_to_template = array(
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
		'refund-returns'               => 'page-refund-returns.php',
		'refund_returns'               => 'page-refund_returns.php',
		'returns-policy'               => 'page-refund-returns.php',
		'refund-and-return-policy'     => 'page-refund-returns.php',
		'privacy-policy'               => 'page-privacy-policy.php',
		'terms-conditions'             => 'page-terms-conditions.php',
		'terms-and-conditions'         => 'page-terms-conditions.php',
	);

	if ( isset( $slug_to_template[ $raw_path ] ) ) {
		$custom_template = locate_template( array( $slug_to_template[ $raw_path ] ) );
		if ( $custom_template && file_exists( $custom_template ) ) {
			status_header( 200 );
			if ( isset( $wp_query ) ) {
				$wp_query->is_404 = false;
				$wp_query->is_page = true;
				$wp_query->is_singular = true;
			}
			return $custom_template;
		}
	}

	return $template;
}

/**
 * --------------------------------------------------------------------------
 * EVARA ART: LUXURY ORDERS DASHBOARD & ORDER DETAILS ENHANCEMENTS
 * --------------------------------------------------------------------------
 */

// 1. Add "Artwork / Items" column to My Account Orders table
add_filter( 'woocommerce_account_orders_columns', 'evara_add_order_items_column', 20 );
function evara_add_order_items_column( $columns ) {
	$new_columns = array();
	foreach ( $columns as $key => $title ) {
		$new_columns[ $key ] = $title;
		if ( 'order-date' === $key ) {
			$new_columns['order-items'] = __( 'Artwork / Items', 'evara-art' );
		}
	}
	if ( ! isset( $new_columns['order-items'] ) ) {
		$new_columns['order-items'] = __( 'Artwork / Items', 'evara-art' );
	}
	return $new_columns;
}

// 2. Render Artwork / Items in the Orders table
add_action( 'woocommerce_my_account_my_orders_column_order-items', 'evara_render_order_items_column', 10, 1 );
function evara_render_order_items_column( $order ) {
	if ( ! $order ) {
		return;
	}
	$items = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
	if ( empty( $items ) ) {
		echo '<span class="text-muted">&mdash;</span>';
		return;
	}

	echo '<div class="evara-account-order-items-list">';
	foreach ( $items as $item_id => $item ) {
		$product = $item->get_product();
		$thumb   = $product ? $product->get_image( array( 64, 64 ), array( 'class' => 'evara-order-row-thumb' ) ) : '';
		if ( ! $thumb ) {
			$thumb = '<div class="evara-order-row-thumb-placeholder">🎨</div>';
		}
		$name      = $item->get_name();
		$qty       = $item->get_quantity();
		$permalink = ( $product && $product->is_visible() ) ? $product->get_permalink( $item ) : '';

		echo '<div class="evara-account-order-item-chip">';
		if ( $permalink ) {
			echo '<a href="' . esc_url( $permalink ) . '" class="evara-order-item-thumb-link" title="' . esc_attr( $name ) . '">' . $thumb . '</a>';
		} else {
			echo '<span class="evara-order-item-thumb-static">' . $thumb . '</span>';
		}
		echo '<div class="evara-order-item-chip-details">';
		if ( $permalink ) {
			echo '<a href="' . esc_url( $permalink ) . '" class="evara-order-item-chip-title">' . esc_html( $name ) . '</a>';
		} else {
			echo '<span class="evara-order-item-chip-title">' . esc_html( $name ) . '</span>';
		}
		echo '<div class="evara-order-item-chip-meta">';
		echo '<span class="evara-order-item-qty">Qty: ' . intval( $qty ) . '</span>';
		echo '<span class="evara-order-item-artist">By <strong>Shraddha Parmar</strong></span>';
		echo '</div>';
		echo '</div>';
		echo '</div>';
	}
	echo '</div>';
}

// 3. Render Luxury Status Badges in Orders Table
add_action( 'woocommerce_my_account_my_orders_column_order-status', 'evara_render_order_status_column', 10, 1 );
function evara_render_order_status_column( $order ) {
	if ( ! $order ) {
		return;
	}
	$status = $order->get_status();
	$status_label = wc_get_order_status_name( $status );

	$badge_class = 'evara-status-badge--default';
	$icon = '•';

	switch ( $status ) {
		case 'processing':
			$badge_class = 'evara-status-badge--processing';
			$icon = '<span class="evara-pulse-dot"></span>';
			$status_label = __( 'In Studio • Preparing', 'evara-art' );
			break;
		case 'completed':
			$badge_class = 'evara-status-badge--completed';
			$icon = '✓';
			$status_label = __( 'Delivered', 'evara-art' );
			break;
		case 'on-hold':
		case 'pending':
			$badge_class = 'evara-status-badge--pending';
			$icon = '⏳';
			$status_label = __( 'Payment Pending', 'evara-art' );
			break;
		case 'cancelled':
		case 'failed':
		case 'refunded':
			$badge_class = 'evara-status-badge--cancelled';
			$icon = '✕';
			break;
	}

	echo '<span class="evara-status-badge ' . esc_attr( $badge_class ) . '">' . $icon . ' ' . esc_html( $status_label ) . '</span>';
}

// 4. In View-Order / Order-Received: Add Order Progress Stepper before Order Table
add_action( 'woocommerce_order_details_before_order_table', 'evara_render_order_progress_stepper', 5, 1 );
function evara_render_order_progress_stepper( $order ) {
	if ( ! $order ) {
		return;
	}
	$status = $order->get_status();
	$order_number = $order->get_order_number();
	$date_created = $order->get_date_created() ? $order->get_date_created()->date_i18n( get_option( 'date_format' ) ) : '';

	// Stepper stages: 1 = Confirmed, 2 = Handcrafting / In Studio, 3 = Armored Crating, 4 = Delivered
	$step = 2; // Default for processing
	if ( 'completed' === $status ) {
		$step = 4;
	} elseif ( 'on-hold' === $status || 'pending' === $status ) {
		$step = 1;
	} elseif ( 'cancelled' === $status || 'failed' === $status || 'refunded' === $status ) {
		$step = 0;
	}
	?>
	<div class="evara-order-stepper-container">
		<div class="evara-order-stepper-header">
			<div class="evara-order-stepper-title-wrap">
				<span class="evara-order-stepper-badge">Order #<?php echo esc_html( $order_number ); ?></span>
				<h3 class="evara-order-stepper-heading"><?php esc_html_e( 'Order Tracking & Studio Progress', 'evara-art' ); ?></h3>
			</div>
			<div class="evara-order-stepper-meta">
				<span>Placed on: <strong><?php echo esc_html( $date_created ); ?></strong></span>
				<span>Status: <strong class="evara-text-terracotta"><?php echo esc_html( wc_get_order_status_name( $status ) ); ?></strong></span>
			</div>
		</div>

		<?php if ( $step > 0 ) : ?>
		<div class="evara-order-stepper-track">
			<!-- Step 1: Confirmed -->
			<div class="evara-stepper-step <?php echo $step >= 1 ? 'is-complete' : ''; ?>">
				<div class="evara-step-icon"><?php echo $step >= 1 ? '✓' : '1'; ?></div>
				<div class="evara-step-content">
					<div class="evara-step-label"><?php esc_html_e( 'Order Confirmed', 'evara-art' ); ?></div>
					<div class="evara-step-sub"><?php esc_html_e( 'Payment verified via Razorpay', 'evara-art' ); ?></div>
				</div>
			</div>

			<!-- Step 2: Handcrafting & Studio Inspection -->
			<div class="evara-stepper-step <?php echo $step >= 2 ? ( $step === 2 ? 'is-active' : 'is-complete' ) : ''; ?>">
				<div class="evara-step-icon"><?php echo $step > 2 ? '✓' : '🎨'; ?></div>
				<div class="evara-step-content">
					<div class="evara-step-label"><?php esc_html_e( 'Studio Handcrafting', 'evara-art' ); ?></div>
					<div class="evara-step-sub"><?php esc_html_e( 'Inspected by Shraddha Parmar', 'evara-art' ); ?></div>
				</div>
			</div>

			<!-- Step 3: Armored Crating -->
			<div class="evara-stepper-step <?php echo $step >= 3 ? ( $step === 3 ? 'is-active' : 'is-complete' ) : ''; ?>">
				<div class="evara-step-icon"><?php echo $step > 3 ? '✓' : '📦'; ?></div>
				<div class="evara-step-content">
					<div class="evara-step-label"><?php esc_html_e( 'Armored Wooden Crating', 'evara-art' ); ?></div>
					<div class="evara-step-sub"><?php esc_html_e( 'Zero-risk transit packaging', 'evara-art' ); ?></div>
				</div>
			</div>

			<!-- Step 4: Dispatched & Delivered -->
			<div class="evara-stepper-step <?php echo $step >= 4 ? 'is-complete' : ''; ?>">
				<div class="evara-step-icon"><?php echo $step >= 4 ? '✓' : '🚚'; ?></div>
				<div class="evara-step-content">
					<div class="evara-step-label"><?php esc_html_e( 'Pan-India Delivery', 'evara-art' ); ?></div>
					<div class="evara-step-sub"><?php esc_html_e( 'Insured courier to doorstep', 'evara-art' ); ?></div>
				</div>
			</div>
		</div>
		<?php else : ?>
		<div class="evara-order-status-notice evara-order-status-notice--alert">
			<p><?php esc_html_e( 'This order has been cancelled or refunded. If you have any questions, please reach out directly to lead artist Shraddha Parmar.', 'evara-art' ); ?></p>
		</div>
		<?php endif; ?>
	</div>
	<?php
}

// 5. In View-Order / Order-Received: Add Concierge & Support Card after Order Table
add_action( 'woocommerce_order_details_after_order_table', 'evara_render_order_support_card', 10, 1 );
function evara_render_order_support_card( $order ) {
	if ( ! $order ) {
		return;
	}
	$order_number = $order->get_order_number();
	$order_total  = $order->get_formatted_order_total();
	$whatsapp_msg = rawurlencode( "Hello Shraddha, I have an inquiry regarding my Evara Art Order #{$order_number} ({$order_total}). Could you please update me on its studio status?" );
	$whatsapp_url = "https://wa.me/919328143036?text={$whatsapp_msg}";
	?>
	<div class="evara-order-concierge-card">
		<div class="evara-concierge-grid">
			<div class="evara-concierge-col">
				<div class="evara-concierge-icon">✨</div>
				<div class="evara-concierge-text">
					<h4><?php esc_html_e( 'Studio Authenticity Guarantee', 'evara-art' ); ?></h4>
					<p><?php esc_html_e( 'Every artwork is an authentic, original creation handcrafted with premium materials under the direct supervision of lead artist Shraddha Parmar.', 'evara-art' ); ?></p>
				</div>
			</div>
			<div class="evara-concierge-col">
				<div class="evara-concierge-icon">🛡️</div>
				<div class="evara-concierge-text">
					<h4><?php esc_html_e( 'Zero-Risk Transit Protection', 'evara-art' ); ?></h4>
					<p><?php esc_html_e( 'Shipped in heavy-duty armored wooden crating with corner protectors. In the rare event of transit damage, 100% free replacement is guaranteed.', 'evara-art' ); ?></p>
				</div>
			</div>
		</div>
		<div class="evara-concierge-actions">
			<a href="<?php echo esc_url( $whatsapp_url ); ?>" target="_blank" rel="noopener noreferrer" class="evara-btn evara-btn-whatsapp">
				<i class="fa-brands fa-whatsapp"></i> <?php printf( esc_html__( 'Inquire About Order #%s on WhatsApp', 'evara-art' ), esc_html( $order_number ) ); ?>
			</a>
			<button type="button" onclick="window.print();" class="evara-btn evara-btn-outline evara-btn-print">
				<i class="fa-solid fa-print"></i> <?php esc_html_e( 'Print Order Receipt', 'evara-art' ); ?>
			</button>
		</div>
	</div>
	<?php
}

/**
 * --------------------------------------------------------------------------
 * EVARA ART v1.5.0: CRITICAL SITE INTEGRITY, CACHE PURGE, SEO & CATALOG CLEANUP
 * --------------------------------------------------------------------------
 */

// 1. LiteSpeed Cache Full Server Purge on Version Bump + Anti-404-Caching Headers
add_action( 'init', 'evara_litespeed_cache_manager' );
function evara_litespeed_cache_manager() {
	$last_purge = get_option( 'evara_cache_purge_v', '' );
	if ( $last_purge !== EVARA_VERSION ) {
		update_option( 'evara_cache_purge_v', EVARA_VERSION );
		if ( class_exists( '\LiteSpeed\Purge' ) ) {
			\LiteSpeed\Purge::purge_all();
		}
		do_action( 'litespeed_purge_all' );
		if ( ! headers_sent() ) {
			header( 'X-LiteSpeed-Purge: *' );
		}
		if ( function_exists( 'wp_cache_flush' ) ) {
			wp_cache_flush();
		}
	}
}

// Never allow LiteSpeed or browsers to cache 404 error pages
add_action( 'template_redirect', function() {
	if ( is_404() ) {
		header( 'X-LiteSpeed-Cache-Control: no-cache, no-store' );
		header( 'Cache-Control: no-cache, no-store, must-revalidate, max-age=0' );
		header( 'Pragma: no-cache' );
		header( 'Expires: 0' );
	}
}, 1 );

// 2. Permanently Delete Any "Test Product" & Remove From Cart
add_action( 'init', 'evara_purge_test_products', 5 );
function evara_purge_test_products() {
	// Query any product titled "test product", slug "test-product", or price <= 1
	$test_query = new WP_Query( array(
		'post_type'      => 'product',
		'post_status'    => array( 'publish', 'draft', 'pending', 'private', 'trash' ),
		'posts_per_page' => 10,
		's'              => 'test product',
		'fields'         => 'ids',
	) );
	if ( ! empty( $test_query->posts ) ) {
		foreach ( $test_query->posts as $pid ) {
			$prod = wc_get_product( $pid );
			if ( $prod && ( stripos( $prod->get_name(), 'test product' ) !== false || $prod->get_slug() === 'test-product' || floatval( $prod->get_price() ) <= 1.00 ) ) {
				wp_delete_post( $pid, true );
			}
		}
	}

	// Direct slug check
	$by_slug = get_page_by_path( 'test-product', OBJECT, 'product' );
	if ( $by_slug ) {
		wp_delete_post( $by_slug->ID, true );
	}

	// Purge legacy pages from wp_posts if they exist
	foreach ( array( 'coming-soon', 'canvas-paintings-home-decor', 'lippan-art-home-decor' ) as $legacy_slug ) {
		$leg_page = get_page_by_path( $legacy_slug );
		if ( $leg_page && 'trash' !== $leg_page->post_status ) {
			wp_trash_post( $leg_page->ID );
		}
	}

	// Sanitize Cart: Remove any item with price <= 1 or title "test product"
	if ( class_exists( 'WooCommerce' ) && WC()->cart ) {
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$product = $cart_item['data'];
			if ( $product && ( stripos( $product->get_name(), 'test product' ) !== false || floatval( $product->get_price() ) <= 1.00 ) ) {
				WC()->cart->remove_cart_item( $cart_item_key );
			}
		}
	}
}

// 3. Exclude "Uncategorized" & Test Products from Catalog Queries & Searches
add_action( 'woocommerce_product_query', 'evara_filter_catalog_products' );
function evara_filter_catalog_products( $q ) {
	if ( is_admin() ) {
		return;
	}
	// Exclude Uncategorized term
	$tax_query = (array) $q->get( 'tax_query' );
	$tax_query[] = array(
		'taxonomy' => 'product_cat',
		'field'    => 'slug',
		'terms'    => array( 'uncategorized' ),
		'operator' => 'NOT IN',
	);
	$q->set( 'tax_query', $tax_query );

	// Exclude products with price <= 1.00
	$meta_query = (array) $q->get( 'meta_query' );
	$meta_query[] = array(
		'key'     => '_price',
		'value'   => 1.00,
		'compare' => '>',
		'type'    => 'NUMERIC',
	);
	$q->set( 'meta_query', $meta_query );
}

// 4. Dequeue Modern Cart Plugin Frontend Scripts (Kill Duplicate Cart Drawer)
add_action( 'wp_enqueue_scripts', 'evara_neutralize_external_cart_plugins', 999 );
function evara_neutralize_external_cart_plugins() {
	wp_dequeue_script( 'moderncart-frontend-scripts' );
	wp_dequeue_script( 'moderncart-scripts' );
	wp_dequeue_script( 'moderncart' );
	wp_dequeue_style( 'moderncart-frontend-styles' );
	wp_dequeue_style( 'moderncart-styles' );
	wp_dequeue_style( 'moderncart' );
}

// 5. SEO: Meta Descriptions, OpenGraph Tags & Clean Document Titles
add_filter( 'document_title_parts', 'evara_seo_document_title_parts', 20 );
function evara_seo_document_title_parts( $title ) {
	if ( is_front_page() || is_home() ) {
		$title['title']   = 'Evara Art';
		$title['tagline'] = 'Original Handmade Canvas Paintings & Lippan Art | Lead Artist Shraddha Parmar';
	} elseif ( is_product_category() ) {
		$term = get_queried_object();
		if ( $term && ! is_wp_error( $term ) ) {
			$name = $term->name;
			if ( stripos( $name, 'canvas painting' ) !== false ) {
				$name = 'Canvas Paintings';
			} elseif ( stripos( $name, 'clay art' ) !== false || stripos( $name, 'lippan' ) !== false ) {
				$name = 'Lippan & Clay Art';
			}
			$title['title'] = $name;
			$title['site']  = 'Evara Art';
		}
	}
	return $title;
}

// Clean up Category Titles on WooCommerce category archives
add_filter( 'woocommerce_page_title', 'evara_clean_category_page_title', 20 );
function evara_clean_category_page_title( $title ) {
	if ( is_product_category() ) {
		if ( stripos( $title, 'canvas painting' ) !== false ) {
			return __( 'Canvas Paintings', 'evara-art' );
		}
		if ( stripos( $title, 'clay art' ) !== false || stripos( $title, 'lippan' ) !== false ) {
			return __( 'Lippan & Clay Art', 'evara-art' );
		}
	}
	return $title;
}

// Breadcrumbs cleanup
add_filter( 'woocommerce_get_breadcrumb', 'evara_clean_breadcrumbs', 20, 2 );
function evara_clean_breadcrumbs( $crumbs, $breadcrumb ) {
	foreach ( $crumbs as $k => $crumb ) {
		if ( isset( $crumb[0] ) ) {
			if ( stripos( $crumb[0], 'canvas painting' ) !== false ) {
				$crumbs[ $k ][0] = 'Canvas Paintings';
			} elseif ( stripos( $crumb[0], 'clay art' ) !== false || stripos( $crumb[0], 'lippan' ) !== false ) {
				$crumbs[ $k ][0] = 'Lippan & Clay Art';
			}
		}
	}
	return $crumbs;
}

// Dynamic SEO Meta Description & OpenGraph Tags
add_action( 'wp_head', 'evara_render_seo_meta_tags', 1 );
function evara_render_seo_meta_tags() {
	if ( is_admin() ) {
		return;
	}

	$title = wp_get_document_title();
	$url   = esc_url( home_url( add_query_arg( array(), $GLOBALS['wp']->request ?? '' ) ) );
	$image = EVARA_URI . '/assets/images/hero-banner-krishna.png';
	$desc  = 'Discover authentic original handmade canvas paintings and Gujarat Lippan mirror art by lead artist Shraddha Parmar. 100% hand-painted with certified authenticity and armored Pan-India delivery.';

	if ( is_front_page() || is_home() ) {
		$desc = 'Evara Art offers original handmade canvas paintings, sacred spiritual art (Krishna, Shiv Shakti, Jagannath), and authentic Gujarat Lippan mirror art by lead artist Shraddha Parmar. Free Pan-India insured delivery.';
	} elseif ( is_product() ) {
		global $product;
		if ( $product ) {
			$title = $product->get_name() . ' | Evara Art';
			$short = $product->get_short_description();
			if ( ! empty( $short ) ) {
				$desc = wp_strip_all_tags( $short );
			} else {
				$desc = sprintf( 'Handmade %s artwork by lead artist Shraddha Parmar at Evara Art. Available with insured Pan-India transit guarantee.', $product->get_name() );
			}
			$img_id = $product->get_image_id();
			if ( $img_id ) {
				$img_src = wp_get_attachment_image_url( $img_id, 'full' );
				if ( $img_src ) {
					$image = $img_src;
				}
			}
		}
	} elseif ( is_product_category() ) {
		$term = get_queried_object();
		if ( $term && ! is_wp_error( $term ) ) {
			$cat_name = evara_clean_category_page_title( $term->name );
			$title    = $cat_name . ' | Evara Art';
			$desc     = sprintf( 'Explore our curated collection of %s at Evara Art. Handcrafted by lead artist Shraddha Parmar with museum-grade materials and zero-risk transit guarantee.', $cat_name );
		}
	} elseif ( is_page( 'about' ) ) {
		$title = 'About & Studio Story | Evara Art';
		$desc  = 'Learn about Evara Art and lead artist Shraddha Parmar. Discover the sacred devotion, artistic philosophy, and handcrafted techniques behind our canvas paintings and Lippan craft.';
	} elseif ( is_page( 'custom-art' ) ) {
		$title = 'Custom Art Commissions | Evara Art';
		$desc  = 'Commission a bespoke, personalized canvas painting or Lippan wall art tailored to your home interior and spiritual devotion by lead artist Shraddha Parmar.';
	}

	echo "\n<!-- Evara Art SEO & OpenGraph Meta Tags -->\n";
	echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
	echo '<meta property="og:site_name" content="Evara Art">' . "\n";
	echo '<meta property="og:type" content="' . ( is_product() ? 'product' : 'website' ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta property="og:description" content="' . esc_attr( $desc ) . '">' . "\n";
	echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
	echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta name="twitter:description" content="' . esc_attr( $desc ) . '">' . "\n";
	echo '<meta name="twitter:image" content="' . esc_url( $image ) . '">' . "\n";
	echo "<!-- End Evara Art SEO Meta Tags -->\n\n";
}



