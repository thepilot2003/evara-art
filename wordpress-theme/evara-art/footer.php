<?php
/**
 * The Footer for Evara Art Theme
 *
 * @package EvaraArt
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
	<span class="whatsapp-tooltip">Chat with Art Studio</span>
</a>

<!-- Site Footer -->
<footer class="evara-footer">
	<div class="evara-container footer-content-grid">
		
		<!-- Brand & Mission Column -->
		<div class="footer-col brand-col">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer-brand-link">
				<img src="<?php echo esc_url( EVARA_URI . '/assets/images/evara-logo.png' ); ?>" alt="Evara Art" class="footer-logo">
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
			<h4 class="footer-heading">Collections</h4>
			<ul class="footer-links">
				<li><a href="<?php echo esc_url( home_url( '/canvas-paintings-home-decor/' ) ); ?>">Canvas Paintings</a></li>
				<li><a href="<?php echo esc_url( home_url( '/lippan-art-home-decor/' ) ); ?>">Lippan &amp; Clay Mirror Art</a></li>
				<li><a href="<?php echo esc_url( home_url( '/custom-art/' ) ); ?>">Custom Art Commissions</a></li>
			</ul>
		</div>

		<!-- Navigation: Client Care & Policies -->
		<div class="footer-col">
			<h4 class="footer-heading">Client Care</h4>
			<ul class="footer-links">
				<li><a href="<?php echo esc_url( home_url( '/my-account/' ) ); ?>">Track Your Order</a></li>
				<li><a href="<?php echo esc_url( home_url( '/privacy-policy-2/' ) ); ?>">Privacy Policy</a></li>
				<li><a href="<?php echo esc_url( home_url( '/terms-conditions/' ) ); ?>">Terms &amp; Conditions</a></li>
				<li><a href="<?php echo esc_url( home_url( '/refund_returns/' ) ); ?>">Returns &amp; Refund Policy</a></li>
			</ul>
		</div>

		<!-- Direct Studio Contact -->
		<div class="footer-col contact-col">
			<h4 class="footer-heading">Art Studio</h4>
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
					<span>Gujarat, India &bull; Pan-India Insured Dispatch</span>
				</li>
			</ul>
		</div>

	</div>

	<!-- Bottom Copyright & Payment Badges -->
	<div class="footer-bottom">
		<div class="evara-container footer-bottom-flex">
			<p class="copyright-text">
				&copy; <?php echo esc_html( date( 'Y' ) ); ?> Evara Art. Handcrafted with reverence. All rights reserved.
			</p>
			<div class="payment-badges">
				<span class="payment-pill"><i class="fa-solid fa-shield-halved"></i> 100% Secure Checkout via Razorpay</span>
				<span class="payment-methods">UPI &bull; RuPay &bull; Visa &bull; Mastercard &bull; NetBanking</span>
			</div>
		</div>
	</div>
</footer>

<!-- Side Cart Drawer Template -->
<?php get_template_part( 'template-parts/cart-drawer' ); ?>

<?php wp_footer(); ?>
</body>
</html>
