<?php
/**
 * The Header for Evara Art Theme (Centered Logo with CSS Grid, Full Menu Support)
 *
 * @package EvaraArt
 * @version 1.2.0
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

<!-- Accessible Skip to Content Link -->
<a class="screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'evara-art' ); ?></a>

<!-- Top Announcement Ribbon -->
<aside class="evara-announcement-bar" aria-label="<?php esc_attr_e( 'Store Announcement', 'evara-art' ); ?>">
	<div class="evara-container">
		<div class="announcement-content">
			<span class="announcement-sparkle">✦</span>
			<span class="announcement-text-desktop">
				<?php printf( esc_html__( 'Pan-India Insured Express Delivery • Free Shipping on Orders Above ₹%s • 100%% Handcrafted Authenticity', 'evara-art' ), number_format( evara_get_free_shipping_threshold() ) ); ?>
			</span>
			<span class="announcement-text-mobile">
				<?php printf( esc_html__( 'Pan-India Delivery • Free Shipping Over ₹%s', 'evara-art' ), number_format( evara_get_free_shipping_threshold() ) ); ?>
			</span>
			<span class="announcement-sparkle">✦</span>
		</div>
	</div>
</aside>

<!-- Main Header (Grid 1fr auto 1fr Layout: Left Nav, Center Brand Logo, Right Actions) -->
<header id="site-header" class="evara-header">
	<div class="evara-container header-inner">
		
		<!-- Left: Mobile Toggle or Desktop Navigation -->
		<div class="header-left">
			<button class="evara-mobile-toggle" id="evara-menu-open" aria-label="<?php esc_attr_e( 'Open Navigation Menu', 'evara-art' ); ?>" aria-expanded="false" aria-controls="evara-mobile-menu">
				<span class="toggle-line"></span>
				<span class="toggle-line"></span>
				<span class="toggle-line"></span>
			</button>

			<nav class="evara-desktop-nav" aria-label="<?php esc_attr_e( 'Primary Menu', 'evara-art' ); ?>">
				<?php
				if ( has_nav_menu( 'primary' ) ) {
					wp_nav_menu( array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'nav-links',
						'fallback_cb'    => 'evara_default_primary_menu',
					) );
				} else {
					evara_default_primary_menu();
				}
				?>
			</nav>
		</div>

		<!-- Exact Center: Brand Logo (No Overlap with Navigation) -->
		<div class="evara-brand">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="brand-link" rel="home">
				<?php
				$custom_logo_id = get_theme_mod( 'custom_logo' );
				$logo_url = $custom_logo_id ? wp_get_attachment_image_url( $custom_logo_id, 'full' ) : EVARA_URI . '/assets/images/evara-logo.png';
				?>
				<img src="<?php echo esc_url( $logo_url ); ?>" 
				     alt="<?php bloginfo( 'name' ); ?>" 
				     class="brand-logo-img" 
				     width="180" 
				     height="52" 
				     onerror="this.style.display='none'; document.getElementById('theme-logo-fallback').style.display='flex';">
				<div class="brand-fallback-mark" id="theme-logo-fallback" style="display: none;">
					<span class="brand-fallback-feather">✦</span>
					<span class="brand-fallback-name">EVARA</span>
					<span class="brand-fallback-sub">ART</span>
				</div>
			</a>
		</div>

		<!-- Right: Action Icons (Search, Account, Cart) -->
		<div class="evara-header-actions">
			<!-- Search Trigger -->
			<button class="action-btn" id="evara-search-trigger" aria-label="<?php esc_attr_e( 'Search Artworks', 'evara-art' ); ?>" aria-expanded="false" aria-controls="evara-search-modal">
				<i class="fa-solid fa-magnifying-glass"></i>
			</button>

			<!-- Account -->
			<a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/contact/' ) ); ?>" class="action-btn" aria-label="<?php esc_attr_e( 'My Account', 'evara-art' ); ?>">
				<i class="fa-regular fa-user"></i>
			</a>

			<!-- Cart Drawer Trigger -->
			<button class="action-btn cart-trigger" id="evara-cart-open" aria-label="<?php esc_attr_e( 'View Cart', 'evara-art' ); ?>" aria-expanded="false" aria-controls="evara-cart-drawer">
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
			<span class="drawer-title"><?php esc_html_e( 'Navigation', 'evara-art' ); ?></span>
			<button class="drawer-close-btn" id="evara-menu-close" aria-label="<?php esc_attr_e( 'Close Navigation Menu', 'evara-art' ); ?>">
				<i class="fa-solid fa-xmark"></i>
			</button>
		</div>

		<?php
		if ( has_nav_menu( 'mobile' ) ) {
			wp_nav_menu( array(
				'theme_location' => 'mobile',
				'container'      => false,
				'menu_class'     => 'mobile-nav-list',
				'fallback_cb'    => 'evara_default_mobile_menu',
			) );
		} else {
			evara_default_mobile_menu();
		}
		?>

		<div class="mobile-drawer-footer">
			<p class="contact-prompt"><?php esc_html_e( 'Have questions or custom requirements?', 'evara-art' ); ?></p>
			<a href="https://wa.me/919328143036?text=Hi%20Evara%20Art,%20I'm%20interested%20in%20your%20artwork" target="_blank" rel="noopener noreferrer" class="evara-btn btn-whatsapp">
				<i class="fa-brands fa-whatsapp"></i> <?php esc_html_e( 'Chat on WhatsApp', 'evara-art' ); ?>
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
			<label for="evara-search-input" class="screen-reader-text"><?php esc_html_e( 'Search for artwork:', 'evara-art' ); ?></label>
			<div class="search-input-wrap">
				<i class="fa-solid fa-magnifying-glass search-icon"></i>
				<input type="search" id="evara-search-input" class="search-field" placeholder="<?php esc_attr_e( 'Search paintings, Krishna, Lippan art...', 'evara-art' ); ?>" value="<?php echo get_search_query(); ?>" name="s" autocomplete="off" />
				<?php if ( class_exists( 'WooCommerce' ) ) : ?>
					<input type="hidden" name="post_type" value="product" />
				<?php endif; ?>
				<button type="submit" class="search-submit"><?php esc_html_e( 'Search', 'evara-art' ); ?></button>
			</div>
		</form>
		<div class="search-quick-tags">
			<span><?php esc_html_e( 'Popular searches:', 'evara-art' ); ?></span>
			<a href="<?php echo esc_url( home_url( '/?s=Krishna' . ( class_exists( 'WooCommerce' ) ? '&post_type=product' : '' ) ) ); ?>">Krishna</a>
			<a href="<?php echo esc_url( home_url( '/?s=Lippan' . ( class_exists( 'WooCommerce' ) ? '&post_type=product' : '' ) ) ); ?>">Lippan Art</a>
			<a href="<?php echo esc_url( home_url( '/?s=Shiv+Shakti' . ( class_exists( 'WooCommerce' ) ? '&post_type=product' : '' ) ) ); ?>">Shiv Shakti</a>
			<a href="<?php echo esc_url( home_url( '/?s=Ganesha' . ( class_exists( 'WooCommerce' ) ? '&post_type=product' : '' ) ) ); ?>">Ganesha</a>
		</div>
	</div>
</div>

<main id="primary" class="site-main">
