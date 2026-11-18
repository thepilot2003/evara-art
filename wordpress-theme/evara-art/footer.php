<?php
/**
 * The Footer for Evara Art Theme
 *
 * @package EvaraArt
 * @version 1.4.4
 */

$phone = get_theme_mod( 'evara_contact_phone', '+91 93281 43036' );
$clean_phone = preg_replace( '/[^0-9]/', '', $phone );
$email = get_theme_mod( 'evara_contact_email', 'support@evaraart.in' );
$location = get_theme_mod( 'evara_contact_location', 'Gujarat, India • Pan-India Insured Dispatch' );
$ig = get_theme_mod( 'evara_instagram_url', 'https://www.instagram.com/evaraart.in/' );
$fb = get_theme_mod( 'evara_facebook_url', 'https://www.facebook.com/people/Evara-Art/61574370844039/' );
$li = get_theme_mod( 'evara_linkedin_url', 'https://www.linkedin.com/company/evara-art/' );
?>
</main><!-- #primary -->

<!-- Floating Quick WhatsApp Button -->
<a href="https://wa.me/<?php echo esc_attr( $clean_phone ); ?>?text=Hello%20Evara%20Art,%20I'm%20looking%20for%20a%20painting/custom%20order" 
   class="evara-floating-whatsapp" 
   target="_blank" 
   rel="noopener noreferrer" 
   aria-label="<?php esc_attr_e( 'Chat with us on WhatsApp', 'evara-art' ); ?>">
	<i class="fa-brands fa-whatsapp"></i>
	<span class="whatsapp-tooltip"><?php esc_html_e( 'Chat with Art Studio', 'evara-art' ); ?></span>
</a>

<!-- Site Footer -->
<footer class="evara-footer">
	<div class="evara-container footer-content-grid">
		
		<!-- Brand & Mission Column -->
		<div class="footer-col brand-col">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer-brand-link" aria-label="<?php bloginfo( 'name' ); ?>">
				<?php
				$footer_logo_custom = get_theme_mod( 'evara_footer_logo', '' );
				if ( ! empty( $footer_logo_custom ) ) {
					$footer_logo_url = $footer_logo_custom;
				} else {
					$custom_logo_id = get_theme_mod( 'custom_logo' );
					$footer_logo_url = $custom_logo_id ? wp_get_attachment_image_url( $custom_logo_id, 'full' ) : EVARA_URI . '/assets/images/evara-logo.png';
				}
				?>
				<div class="footer-logo-badge" style="background: transparent !important; padding: 0 !important; box-shadow: none !important;">
					<img src="<?php echo esc_url( $footer_logo_url ); ?>" 
					     alt="<?php bloginfo( 'name' ); ?>" 
					     class="footer-logo-img" 
					     width="160" 
					     height="48"
					     style="max-height: 48px; width: auto; object-fit: contain; display: block; background: transparent !important;">
				</div>
			</a>
			<p class="brand-bio">
				<strong>&ldquo;Evara&rdquo;</strong> means <em>Gift of God</em>. We believe that art is a sacred bridge connecting emotion, soul, and sacred living spaces. Handcrafted in India with exquisite devotion.
			</p>
			<div class="social-links">
				<?php if ( ! empty( $ig ) ) : ?>
					<a href="<?php echo esc_url( $ig ); ?>" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
						<i class="fa-brands fa-instagram"></i>
					</a>
				<?php endif; ?>
				<?php if ( ! empty( $fb ) ) : ?>
					<a href="<?php echo esc_url( $fb ); ?>" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
						<i class="fa-brands fa-facebook-f"></i>
					</a>
				<?php endif; ?>
				<?php if ( ! empty( $li ) ) : ?>
					<a href="<?php echo esc_url( $li ); ?>" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
						<i class="fa-brands fa-linkedin-in"></i>
					</a>
				<?php endif; ?>
				<a href="https://wa.me/<?php echo esc_attr( $clean_phone ); ?>" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">
					<i class="fa-brands fa-whatsapp"></i>
				</a>
			</div>
		</div>

		<!-- Navigation: Curated Collections -->
		<div class="footer-col">
			<h4 class="footer-heading"><?php esc_html_e( 'Collections', 'evara-art' ); ?></h4>
			<ul class="footer-links">
				<li><a href="<?php echo esc_url( home_url( '/categories/' ) ); ?>" style="color: var(--evara-gold); font-weight: 600;"><i class="fa-solid fa-shapes"></i> <?php esc_html_e( 'All Art Categories', 'evara-art' ); ?></a></li>
				<li><a href="<?php echo esc_url( evara_get_category_url( array( 'canvas-painting-evara-art', 'canvas-paintings-home-decor' ), '/product-category/canvas-painting-evara-art/' ) ); ?>"><?php esc_html_e( 'Canvas Paintings', 'evara-art' ); ?></a></li>
				<li><a href="<?php echo esc_url( evara_get_category_url( array( 'clay-art-evara-art', 'lippan-art-home-decor' ), '/product-category/clay-art-evara-art/' ) ); ?>"><?php esc_html_e( 'Lippan & Clay Art', 'evara-art' ); ?></a></li>
				<li><a href="<?php echo esc_url( evara_get_category_url( array( 'animal-painting' ), '/product-category/animal-painting/' ) ); ?>"><?php esc_html_e( 'Animal Painting', 'evara-art' ); ?></a></li>
				<li><a href="<?php echo esc_url( evara_get_category_url( array( 'boho-art' ), '/product-category/boho-art/' ) ); ?>"><?php esc_html_e( 'Boho Art', 'evara-art' ); ?></a></li>
				<li><a href="<?php echo esc_url( evara_get_category_url( array( 'abstract-painting' ), '/product-category/abstract-painting/' ) ); ?>"><?php esc_html_e( 'Abstract Painting', 'evara-art' ); ?></a></li>
				<li><a href="<?php echo esc_url( function_exists( 'evara_get_custom_art_url' ) ? evara_get_custom_art_url() : home_url( '/custom-art/' ) ); ?>"><?php esc_html_e( 'Custom Art Commissions', 'evara-art' ); ?></a></li>
			</ul>
		</div>

		<!-- Navigation: Client Care & Policies -->
		<div class="footer-col">
			<h4 class="footer-heading"><?php esc_html_e( 'Client Care', 'evara-art' ); ?></h4>
			<ul class="footer-links">
				<li><a href="<?php echo esc_url( function_exists( 'evara_get_about_url' ) ? evara_get_about_url() : home_url( '/about/' ) ); ?>"><?php esc_html_e( 'About & Studio Story', 'evara-art' ); ?></a></li>
				<?php if ( class_exists( 'WooCommerce' ) ) : ?>
					<li><a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"><?php esc_html_e( 'Track Your Order', 'evara-art' ); ?></a></li>
				<?php endif; ?>
				<li><a href="<?php echo esc_url( home_url( '/shipping-policy/' ) ); ?>"><?php esc_html_e( 'Shipping & Delivery Policy', 'evara-art' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/refund_returns/' ) ); ?>"><?php esc_html_e( 'Returns & Refund Policy', 'evara-art' ); ?></a></li>
				<li>
					<a href="<?php echo esc_url( function_exists( 'get_privacy_policy_url' ) && get_privacy_policy_url() ? get_privacy_policy_url() : home_url( '/privacy-policy/' ) ); ?>">
						<?php esc_html_e( 'Privacy Policy', 'evara-art' ); ?>
					</a>
				</li>
				<li><a href="<?php echo esc_url( home_url( '/terms-conditions/' ) ); ?>"><?php esc_html_e( 'Terms & Conditions', 'evara-art' ); ?></a></li>
			</ul>
		</div>

		<!-- Direct Studio Contact -->
		<div class="footer-col contact-col">
			<h4 class="footer-heading"><?php esc_html_e( 'Art Studio', 'evara-art' ); ?></h4>
			<ul class="contact-details">
				<li>
					<i class="fa-solid fa-phone"></i>
					<a href="tel:<?php echo esc_attr( $clean_phone ); ?>"><?php echo esc_html( $phone ); ?></a>
				</li>
				<li>
					<i class="fa-solid fa-envelope"></i>
					<a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
				</li>
				<li>
					<i class="fa-solid fa-location-dot"></i>
					<span><?php echo esc_html( $location ); ?></span>
				</li>
			</ul>
		</div>

	</div>

	<!-- Bottom Copyright & Payment Badges -->
	<div class="footer-bottom">
		<div class="evara-container footer-bottom-flex">
			<p class="copyright-text">
				&copy; <?php echo esc_html( date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'Handcrafted with reverence. All rights reserved.', 'evara-art' ); ?>
			</p>
			<div class="payment-badges">
				<span class="payment-pill"><i class="fa-solid fa-shield-halved"></i> <?php esc_html_e( '100% Secure Checkout via Razorpay', 'evara-art' ); ?></span>
				<span class="payment-methods"><?php esc_html_e( 'UPI • RuPay • Visa • Mastercard • NetBanking', 'evara-art' ); ?></span>
			</div>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
