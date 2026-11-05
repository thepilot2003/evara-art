<?php
/**
 * Template Name: Custom Commission Inquiry
 *
 * @package EvaraArt
 * @version 1.2.0
 */

get_header(); ?>

<div class="evara-page-header commission-page-header">
	<div class="evara-container text-center">
		<span class="sub-heading">✦ <?php esc_html_e( 'Personalized Art Experience', 'evara-art' ); ?></span>
		<h1 class="page-title"><?php esc_html_e( 'Bespoke Wall Commissions', 'evara-art' ); ?></h1>
		<p class="page-desc"><?php esc_html_e( 'Every space has its own rhythm. Tell us about your vision, and we will bring it to life on canvas or clay.', 'evara-art' ); ?></p>
	</div>
</div>

<div class="evara-container page-content-container">
	<div class="commission-guide-grid">
		
		<!-- Step 1 -->
		<div class="step-card">
			<span class="step-num">01</span>
			<h3><?php esc_html_e( 'Share Your Vision', 'evara-art' ); ?></h3>
			<p><?php esc_html_e( 'Provide your preferred theme (Spiritual, Lippan Art, Modern Minimalist) and wall photo or dimensions.', 'evara-art' ); ?></p>
		</div>

		<!-- Step 2 -->
		<div class="step-card">
			<span class="step-num">02</span>
			<h3><?php esc_html_e( 'Color & Size Consultation', 'evara-art' ); ?></h3>
			<p><?php esc_html_e( 'Our artist collaborates directly with you on color swatches to match your room’s upholstery and lighting.', 'evara-art' ); ?></p>
		</div>

		<!-- Step 3 -->
		<div class="step-card">
			<span class="step-num">03</span>
			<h3><?php esc_html_e( 'Handcrafting & Progress', 'evara-art' ); ?></h3>
			<p><?php esc_html_e( 'Watch your artwork come alive with work-in-progress photos and video updates before final varnishing.', 'evara-art' ); ?></p>
		</div>

		<!-- Step 4 -->
		<div class="step-card">
			<span class="step-num">04</span>
			<h3><?php esc_html_e( 'Insured Pan-India Dispatch', 'evara-art' ); ?></h3>
			<p><?php esc_html_e( 'Crated in custom wooden reinforcement and delivered with tracking to your doorstep ready to mount.', 'evara-art' ); ?></p>
		</div>

	</div>

	<!-- Direct Inquiry Box -->
	<div class="commission-inquiry-box text-center">
		<h2><?php esc_html_e( 'Ready to Start Your Custom Piece?', 'evara-art' ); ?></h2>
		<p><?php esc_html_e( 'Connect with lead artist Shraddha directly on WhatsApp for an immediate size and price estimate.', 'evara-art' ); ?></p>
		<div class="inquiry-actions">
			<a href="https://wa.me/919328143036?text=Hi%20Shraddha,%20I'm%20looking%20for%20a%20custom%20artwork%20for%20my%20home.%20Wall%20dimensions:" 
			   target="_blank" 
			   rel="noopener noreferrer" 
			   class="evara-btn btn-whatsapp-lg">
				<i class="fa-brands fa-whatsapp"></i> <?php esc_html_e( 'Chat on WhatsApp (+91 93281 43036)', 'evara-art' ); ?>
			</a>
			<a href="mailto:support@evaraart.in?subject=Custom%20Commission%20Inquiry" class="evara-btn btn-outline">
				<i class="fa-solid fa-envelope"></i> <?php esc_html_e( 'Email Details', 'evara-art' ); ?>
			</a>
		</div>
	</div>
</div>

<?php get_footer(); ?>
