<?php
/**
 * The Footer for Evara Art Theme
 *
 * @package EvaraArt
 * @version 1.2.0
 */
?>
</main><!-- #primary -->

<!-- Floating Quick WhatsApp Button -->
<a href="https://wa.me/919328143036?text=Hello%20Evara%20Art,%20I'm%20looking%20for%20a%20painting/custom%20order" 
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
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer-brand-link">
				<img src="<?php echo esc_url( EVARA_URI . '/assets/images/evara-logo.png' ); ?>" 
				     alt="<?php bloginfo( 'name' ); ?>" 
				     class="footer-logo" 
				     width="160" 
				     height="46">
			</a>
			<p class="brand-bio">
				<strong>&ldquo;Evara&rdquo;</strong> means <em>Gift of God</em>. We believe that art is a sacred bridge connecting emotion, soul, and sacred living spaces. Handcrafted in India with exquisite devotion.
			</p>
			<div class="social-links">
				<a href="https://www.instagram.com/evaraart.in/" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
					<i class="fa-brands fa-instagram"></i>
				</a>
				<a href="https://www.facebook.com/people/Evara-Art/61574370844039/" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
					<i class="fa-brands fa-facebook-f"></i>
				</a>
				<a href="https://www.linkedin.com/company/evara-art/" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
					<i class="fa-brands fa-linkedin-in"></i>
				</a>
				<a href="https://wa.me/919328143036" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">
					<i class="fa-brands fa-whatsapp"></i>
				</a>
			</div>
		</div>

		<!-- Navigation: Curated Collections -->
		<div class="footer-col">
			<h4 class="footer-heading"><?php esc_html_e( 'Collections', 'evara-art' ); ?></h4>
			<ul class="footer-links">
				<li><a href="<?php echo esc_url( home_url( '/canvas-paintings-home-decor/' ) ); ?>"><?php esc_html_e( 'Canvas Paintings', 'evara-art' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/lippan-art-home-decor/' ) ); ?>"><?php esc_html_e( 'Lippan & Clay Mirror Art', 'evara-art' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/custom-art/' ) ); ?>"><?php esc_html_e( 'Custom Art Commissions', 'evara-art' ); ?></a></li>
			</ul>
		</div>

		<!-- Navigation: Client Care & Policies -->
		<div class="footer-col">
			<h4 class="footer-heading"><?php esc_html_e( 'Client Care', 'evara-art' ); ?></h4>
			<ul class="footer-links">
				<?php if ( class_exists( 'WooCommerce' ) ) : ?>
					<li><a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"><?php esc_html_e( 'Track Your Order', 'evara-art' ); ?></a></li>
				<?php endif; ?>
				<li>
					<a href="<?php echo esc_url( function_exists( 'get_privacy_policy_url' ) && get_privacy_policy_url() ? get_privacy_policy_url() : home_url( '/privacy-policy/' ) ); ?>">
						<?php esc_html_e( 'Privacy Policy', 'evara-art' ); ?>
					</a>
				</li>
				<li><a href="<?php echo esc_url( home_url( '/terms-conditions/' ) ); ?>"><?php esc_html_e( 'Terms & Conditions', 'evara-art' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/refund_returns/' ) ); ?>"><?php esc_html_e( 'Returns & Refund Policy', 'evara-art' ); ?></a></li>
			</ul>
		</div>

		<!-- Direct Studio Contact -->
		<div class="footer-col contact-col">
			<h4 class="footer-heading"><?php esc_html_e( 'Art Studio', 'evara-art' ); ?></h4>
			<ul class="contact-details">
				<li>
					<i class="fa-solid fa-phone"></i>
					<a href="tel:+919328143036">+91 93281 43036</a>
				</li>
				<li>
					<i class="fa-solid fa-envelope"></i>
					<a href="mailto:support@evaraart.in">support@evaraart.in</a>
				</li>
				<li>
					<i class="fa-solid fa-location-dot"></i>
					<span><?php esc_html_e( 'Gujarat, India • Pan-India Insured Dispatch', 'evara-art' ); ?></span>
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
