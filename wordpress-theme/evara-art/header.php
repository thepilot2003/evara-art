<?php
/**
 * The Header for Evara Art Theme (Centered Logo on Desktop & Mobile)
 *
 * @package EvaraArt
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Top Announcement Ribbon -->
<aside class="evara-announcement-bar" aria-label="<?php esc_attr_e( 'Store Announcement', 'evara-art' ); ?>">
	<div class="evara-container">
		<div class="announcement-content">
			<span class="announcement-sparkle">✦</span>
			<span class="announcement-text-desktop">Pan-India Insured Express Delivery &bull; Free Shipping on Orders Above &#8377;2,999 &bull; 100% Handcrafted Authenticity</span>
			<span class="announcement-text-mobile">Pan-India Delivery &bull; Free Shipping Over &#8377;2,999</span>
			<span class="announcement-sparkle">✦</span>
		</div>
	</div>
</aside>

<!-- Main Sticky Header (Centered Logo on Both Desktop & Mobile) -->
<header id="site-header" class="evara-header">
	<div class="evara-container header-inner">
		
		<!-- Left: Mobile Toggle or Desktop Navigation -->
		<div class="header-left">
			<button class="evara-mobile-toggle" id="evara-menu-open" aria-label="<?php esc_attr_e( 'Open Navigation Menu', 'evara-art' ); ?>">
				<span class="toggle-line"></span>
				<span class="toggle-line"></span>
				<span class="toggle-line"></span>
			</button>

			<nav class="evara-desktop-nav" aria-label="<?php esc_attr_e( 'Primary Menu', 'evara-art' ); ?>">
				<ul class="nav-links">
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="<?php echo is_front_page() ? 'active' : ''; ?>">Home</a></li>
					<li><a href="<?php echo esc_url( home_url( '/canvas-paintings-home-decor/' ) ); ?>">Canvas Paintings</a></li>
					<li><a href="<?php echo esc_url( home_url( '/lippan-art-home-decor/' ) ); ?>">Lippan Art</a></li>
					<li><a href="<?php echo esc_url( home_url( '/custom-art/' ) ); ?>" class="nav-highlight">Custom Art</a></li>
					<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">About</a></li>
				</ul>
			</nav>
		</div>

		<!-- Exact Center: Brand Logo -->
		<div class="evara-brand">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="brand-link" rel="home">
				<?php
				$custom_logo_id = get_theme_mod( 'custom_logo' );
				$logo_url = $custom_logo_id ? wp_get_attachment_image_url( $custom_logo_id, 'full' ) : EVARA_URI . '/assets/images/evara-logo.png';
				?>
				<img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="brand-logo-img">
			</a>
		</div>

		<!-- Right: Action Icons (Search, Account, Cart) -->
		<div class="evara-header-actions">
			<!-- Search Trigger -->
			<button class="action-btn" id="evara-search-trigger" aria-label="<?php esc_attr_e( 'Search Artworks', 'evara-art' ); ?>">
				<i class="fa-solid fa-magnifying-glass"></i>
			</button>

			<!-- Account -->
			<a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>" class="action-btn" aria-label="<?php esc_attr_e( 'My Account', 'evara-art' ); ?>">
				<i class="fa-regular fa-user"></i>
			</a>

			<!-- Cart Drawer Trigger -->
			<button class="action-btn cart-trigger" id="evara-cart-open" aria-label="<?php esc_attr_e( 'View Cart', 'evara-art' ); ?>">
				<span class="cart-icon-wrap">
					<i class="fa-solid fa-bag-shopping"></i>
					<span class="cart-count-badge" id="evara-cart-badge">
						<?php echo ( class_exists( 'WooCommerce' ) && WC()->cart ) ? esc_html( WC()->cart->get_cart_contents_count() ) : '0'; ?>
					</span>
				</span>
			</button>
		</div>

	</div>
</header>

<!-- Mobile Navigation Drawer -->
<div class="evara-mobile-drawer" id="evara-mobile-menu" aria-hidden="true">
	<div class="mobile-drawer-overlay" id="evara-menu-close-overlay"></div>
	<div class="mobile-drawer-panel">
		<div class="drawer-header">
			<span class="drawer-title">Navigation</span>
			<button class="drawer-close-btn" id="evara-menu-close" aria-label="<?php esc_attr_e( 'Close Navigation Menu', 'evara-art' ); ?>">
				<i class="fa-solid fa-xmark"></i>
			</button>
		</div>
		<ul class="mobile-nav-list">
			<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
			<li><a href="<?php echo esc_url( home_url( '/canvas-paintings-home-decor/' ) ); ?>">Canvas Paintings</a></li>
			<li><a href="<?php echo esc_url( home_url( '/lippan-art-home-decor/' ) ); ?>">Lippan &amp; Clay Mirror Art</a></li>
			<li><a href="<?php echo esc_url( home_url( '/custom-art/' ) ); ?>" class="highlight-link">✦ Request Custom Commission</a></li>
			<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">About &amp; Studio Contact</a></li>
			<li><a href="<?php echo esc_url( home_url( '/my-account/' ) ); ?>">My Orders &amp; Account</a></li>
		</ul>
		<div class="mobile-drawer-footer">
			<p class="contact-prompt">Have questions or custom requirements?</p>
			<a href="https://wa.me/919328143036?text=Hi%20Evara%20Art,%20I'm%20interested%20in%20your%20artwork" target="_blank" class="evara-btn btn-whatsapp">
				<i class="fa-brands fa-whatsapp"></i> Chat on WhatsApp
			</a>
		</div>
	</div>
</div>

<!-- Search Modal Overlay -->
<div class="evara-search-modal" id="evara-search-modal" aria-hidden="true">
	<div class="search-modal-backdrop" id="evara-search-close-backdrop"></div>
	<div class="search-modal-content">
		<button class="search-close-btn" id="evara-search-close" aria-label="<?php esc_attr_e( 'Close search', 'evara-art' ); ?>">
			<i class="fa-solid fa-xmark"></i>
		</button>
		<form role="search" method="get" class="evara-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<label for="evara-search-input" class="screen-reader-text">Search for artwork:</label>
			<div class="search-input-wrap">
				<i class="fa-solid fa-magnifying-glass search-icon"></i>
				<input type="search" id="evara-search-input" class="search-field" placeholder="Search paintings, Krishna, Lippan art..." value="<?php echo get_search_query(); ?>" name="s" autocomplete="off" />
				<input type="hidden" name="post_type" value="product" />
				<button type="submit" class="search-submit">Search</button>
			</div>
		</form>
		<div class="search-quick-tags">
			<span>Popular searches:</span>
			<a href="<?php echo esc_url( home_url( '/?s=Krishna&post_type=product' ) ); ?>">Krishna</a>
			<a href="<?php echo esc_url( home_url( '/?s=Lippan&post_type=product' ) ); ?>">Lippan Art</a>
			<a href="<?php echo esc_url( home_url( '/?s=Shiv+Shakti&post_type=product' ) ); ?>">Shiv Shakti</a>
			<a href="<?php echo esc_url( home_url( '/?s=Ganesha&post_type=product' ) ); ?>">Ganesha</a>
		</div>
	</div>
</div>

<main id="primary" class="site-main">
