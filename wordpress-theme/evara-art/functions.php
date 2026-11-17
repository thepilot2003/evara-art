<?php
/**
 * Evara Art Theme Functions & Security Hardening
 *
 * @package EvaraArt
 * @version 1.3.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EVARA_VERSION', '1.3.4' );
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
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'evara_art_setup' );

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
 * Redirect legacy/empty static pages to live WooCommerce product category archives
 */
add_action( 'template_redirect', 'evara_redirect_old_category_pages' );
function evara_redirect_old_category_pages() {
	if ( is_page( 'canvas-paintings-home-decor' ) ) {
		$target = evara_get_category_url( array( 'canvas-painting-evara-art' ), '/product-category/canvas-painting-evara-art/' );
		wp_safe_redirect( $target, 301 );
		exit;
	}
	if ( is_page( 'lippan-art-home-decor' ) ) {
		$target = evara_get_category_url( array( 'clay-art-evara-art' ), '/product-category/clay-art-evara-art/' );
		wp_safe_redirect( $target, 301 );
		exit;
	}
}

/**
 * Enable Customer Account Creation on WooCommerce My Account page
 */
add_filter( 'option_woocommerce_enable_myaccount_registration', function( $val ) {
	return 'yes';
} );

/**
 * Default Primary Navigation Fallback
 */
function evara_default_primary_menu() {
	$canvas_url = evara_get_category_url( array( 'canvas-painting-evara-art', 'canvas-paintings-home-decor', 'canvas-paintings' ), '/product-category/canvas-painting-evara-art/' );
	$lippan_url = evara_get_category_url( array( 'clay-art-evara-art', 'lippan-art-home-decor', 'lippan-art' ), '/product-category/clay-art-evara-art/' );

	echo '<ul class="nav-links">';
	echo '<li><a href="' . esc_url( home_url( '/' ) ) . '" class="' . ( is_front_page() ? 'active' : '' ) . '">' . esc_html__( 'Home', 'evara-art' ) . '</a></li>';
	echo '<li><a href="' . esc_url( $canvas_url ) . '">' . esc_html__( 'Canvas Paintings', 'evara-art' ) . '</a></li>';
	echo '<li><a href="' . esc_url( $lippan_url ) . '">' . esc_html__( 'Lippan & Clay Art', 'evara-art' ) . '</a></li>';
	echo '<li><a href="' . esc_url( home_url( '/custom-art/' ) ) . '" class="nav-highlight">' . esc_html__( 'Custom Art', 'evara-art' ) . '</a></li>';
	echo '<li><a href="' . esc_url( home_url( '/#about' ) ) . '">' . esc_html__( 'About', 'evara-art' ) . '</a></li>';
	echo '</ul>';
}

/**
 * Default Mobile Navigation Fallback
 */
function evara_default_mobile_menu() {
	$canvas_url = evara_get_category_url( array( 'canvas-painting-evara-art', 'canvas-paintings-home-decor', 'canvas-paintings' ), '/product-category/canvas-painting-evara-art/' );
	$lippan_url = evara_get_category_url( array( 'clay-art-evara-art', 'lippan-art-home-decor', 'lippan-art' ), '/product-category/clay-art-evara-art/' );

	echo '<ul class="mobile-nav-list">';
	echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'evara-art' ) . '</a></li>';
	echo '<li><a href="' . esc_url( $canvas_url ) . '">' . esc_html__( 'Canvas Paintings', 'evara-art' ) . '</a></li>';
	echo '<li><a href="' . esc_url( $lippan_url ) . '">' . esc_html__( 'Lippan & Clay Mirror Art', 'evara-art' ) . '</a></li>';
	echo '<li><a href="' . esc_url( home_url( '/custom-art/' ) ) . '" class="highlight-link">✦ ' . esc_html__( 'Request Custom Commission', 'evara-art' ) . '</a></li>';
	echo '<li><a href="' . esc_url( home_url( '/#about' ) ) . '">' . esc_html__( 'About & Studio Story', 'evara-art' ) . '</a></li>';
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
	$wp_customize->add_setting( 'evara_slide1_image', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'evara_slide1_image', array(
		'label'       => __( 'Banner 1: Custom Image', 'evara-art' ),
		'description' => __( 'Upload image for Banner 1 (recommended 1600x600 or 1400x520).', 'evara-art' ),
		'section'     => 'evara_hero_section',
	) ) );
	$wp_customize->add_setting( 'evara_slide1_title', array( 'default' => 'Art that connects to your Heart', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'evara_slide1_title', array( 'label' => __( 'Banner 1: Text / Title', 'evara-art' ), 'section' => 'evara_hero_section', 'type' => 'text' ) );
	$wp_customize->add_setting( 'evara_slide1_badge', array( 'default' => '✦ Original Canvas Artwork', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'evara_slide1_badge', array( 'label' => __( 'Banner 1: Badge / Tag', 'evara-art' ), 'section' => 'evara_hero_section', 'type' => 'text' ) );
	$wp_customize->add_setting( 'evara_slide1_url', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control( 'evara_slide1_url', array( 'label' => __( 'Banner 1: Click Link URL', 'evara-art' ), 'section' => 'evara_hero_section', 'type' => 'url' ) );

	// Custom Slide 2
	$wp_customize->add_setting( 'evara_slide2_image', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'evara_slide2_image', array(
		'label'       => __( 'Banner 2: Custom Image', 'evara-art' ),
		'section'     => 'evara_hero_section',
	) ) );
	$wp_customize->add_setting( 'evara_slide2_title', array( 'default' => 'Divine Darshan of Lord Jagannath', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'evara_slide2_title', array( 'label' => __( 'Banner 2: Text / Title', 'evara-art' ), 'section' => 'evara_hero_section', 'type' => 'text' ) );
	$wp_customize->add_setting( 'evara_slide2_badge', array( 'default' => '✦ Sacred Spiritual Art', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'evara_slide2_badge', array( 'label' => __( 'Banner 2: Badge / Tag', 'evara-art' ), 'section' => 'evara_hero_section', 'type' => 'text' ) );
	$wp_customize->add_setting( 'evara_slide2_url', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control( 'evara_slide2_url', array( 'label' => __( 'Banner 2: Click Link URL', 'evara-art' ), 'section' => 'evara_hero_section', 'type' => 'url' ) );

	// Custom Slide 3
	$wp_customize->add_setting( 'evara_slide3_image', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'evara_slide3_image', array(
		'label'       => __( 'Banner 3: Custom Image', 'evara-art' ),
		'section'     => 'evara_hero_section',
	) ) );
	$wp_customize->add_setting( 'evara_slide3_title', array( 'default' => 'Sacred Serenity Shiva Parvati', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'evara_slide3_title', array( 'label' => __( 'Banner 3: Text / Title', 'evara-art' ), 'section' => 'evara_hero_section', 'type' => 'text' ) );
	$wp_customize->add_setting( 'evara_slide3_badge', array( 'default' => '✦ Divine Harmony', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'evara_slide3_badge', array( 'label' => __( 'Banner 3: Badge / Tag', 'evara-art' ), 'section' => 'evara_hero_section', 'type' => 'text' ) );
	$wp_customize->add_setting( 'evara_slide3_url', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control( 'evara_slide3_url', array( 'label' => __( 'Banner 3: Click Link URL', 'evara-art' ), 'section' => 'evara_hero_section', 'type' => 'url' ) );

	// Custom Slide 4
	$wp_customize->add_setting( 'evara_slide4_image', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'evara_slide4_image', array(
		'label'       => __( 'Banner 4: Custom Image', 'evara-art' ),
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
		'default'           => 'Art that connects to your Heart.',
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
		'sanitize_callback' => 'esc_url_raw',
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
		'sanitize_callback' => 'esc_url_raw',
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
		'sanitize_callback' => 'esc_url_raw',
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

