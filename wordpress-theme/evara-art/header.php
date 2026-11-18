<?php
/**
 * The Header for Evara Art Theme (Top-Left Brand Logo, Navigation Categories beside Logo, Right Actions)
 *
 * @package EvaraArt
 * @version 1.4.5
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

<!-- Main Header (Top-Left Brand Logo, Categories Navigation beside Logo, Right Actions) -->
<header id="site-header" class="evara-header">
	<div class="evara-container header-inner">
		
		<!-- Left: Top-Left Brand Logo & Categories Navigation Beside It -->
		<div class="header-left">
			<button class="evara-mobile-toggle" id="evara-menu-open" aria-label="<?php esc_attr_e( 'Open Navigation Menu', 'evara-art' ); ?>" aria-expanded="false" aria-controls="evara-mobile-menu">
				<span class="toggle-line"></span>
				<span class="toggle-line"></span>
				<span class="toggle-line"></span>
			</button>

			<!-- Top-Left Brand Logo -->
			<div class="evara-brand">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="brand-link" rel="home">
					<?php
					$custom_logo_id = get_theme_mod( 'custom_logo' );
					$logo_url = $custom_logo_id ? wp_get_attachment_image_url( $custom_logo_id, 'full' ) : EVARA_URI . '/assets/images/evara-logo.png';
					?>
					<img src="<?php echo esc_url( $logo_url ); ?>" 
					     alt="<?php bloginfo( 'name' ); ?>" 
					     class="brand-logo-img" 
					     width="160" 
					     height="48" 
					     style="max-height: 48px; width: auto; object-fit: contain; display: block;"
					     onerror="this.style.display='none'; document.getElementById('theme-logo-fallback').style.display='flex';">
					<div class="brand-fallback-mark" id="theme-logo-fallback" style="display: none;">
						<span class="brand-fallback-feather">✦</span>
						<span class="brand-fallback-name">EVARA</span>
						<span class="brand-fallback-sub">ART</span>
					</div>
				</a>
			</div>

			<!-- Categories & About Beside Logo -->
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

		<!-- Right: Action Icons (Search, Account, Cart) -->
		<div class="evara-header-actions">
			<!-- Search Trigger -->
			<button class="action-btn" id="evara-search-trigger" aria-label="<?php esc_attr_e( 'Search Artworks', 'evara-art' ); ?>" aria-expanded="false" aria-controls="evara-search-modal">
				<i class="fa-solid fa-magnifying-glass"></i>
			</button>

			<!-- Account -->
			<a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/contact/' ) ); ?>" class="action-btn header-account-btn" aria-label="<?php esc_attr_e( 'My Account', 'evara-art' ); ?>">
				<i class="fa-regular fa-user"></i>
			</a>

			<!-- Cart Drawer Trigger -->
			<button class="action-btn cart-trigger" id="evara-cart-open" 
			        data-cart-url="<?php echo esc_url( class_exists( 'WooCommerce' ) ? wc_get_cart_url() : home_url( '/cart/' ) ); ?>"
			        aria-label="<?php esc_attr_e( 'View Cart', 'evara-art' ); ?>" 
			        aria-expanded="false" 
			        aria-controls="evara-cart-drawer">
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
		<div class="drawer-header luxury-drawer-header">
			<div class="drawer-brand-block">
				<img src="<?php echo esc_url( $logo_url ); ?>" 
				     alt="<?php bloginfo( 'name' ); ?>" 
				     class="drawer-brand-logo" 
				     width="135" 
				     height="40" 
				     style="max-height: 40px; width: auto; object-fit: contain; display: block;">
				<span class="drawer-tagline">✦ <?php esc_html_e( 'Handcrafted Art Studio', 'evara-art' ); ?></span>
			</div>
			<button class="drawer-close-btn" id="evara-menu-close" aria-label="<?php esc_attr_e( 'Close Navigation Menu', 'evara-art' ); ?>">
				<i class="fa-solid fa-xmark"></i>
			</button>
		</div>

		<div class="drawer-body-scroll">
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
