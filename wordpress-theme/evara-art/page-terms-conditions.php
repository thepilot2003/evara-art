<?php
/**
 * Template Name: Terms & Conditions
 *
 * @package EvaraArt
 * @version 1.2.0
 */

get_header(); ?>

<div class="policy-page-header">
	<div class="evara-container text-center">
		<div class="evara-breadcrumbs" style="margin-bottom: 12px;">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'evara-art' ); ?></a> &rsaquo; <span><?php esc_html_e( 'Terms & Conditions', 'evara-art' ); ?></span>
		</div>
		<span class="sub-heading">✦ <?php esc_html_e( 'Studio Terms & Conditions', 'evara-art' ); ?></span>
		<h1 class="page-title"><?php esc_html_e( 'Terms & Conditions', 'evara-art' ); ?></h1>
		<p class="page-desc"><?php esc_html_e( 'Welcome to Evara Art. Please review the terms governing orders, handmade artistry, and intellectual property.', 'evara-art' ); ?></p>
	</div>
</div>

<div class="policy-container">
	<div class="policy-card">
		
		<div class="policy-section">
			<h2><i class="fa-solid fa-paintbrush" style="color: var(--evara-terracotta);"></i> 1. <?php esc_html_e( 'Authentic Handcrafted Variations', 'evara-art' ); ?></h2>
			<p><?php esc_html_e( 'Every piece created at Evara Art is an original, handmade work of devotional craftsmanship painted and sculpted by lead artist Shraddha Parmar. Unlike mass-manufactured prints, subtle organic nuances in brushstroke texture, acrylic pigment layering, clay relief patterns, and mirror cut angles are natural characteristics that celebrate the authenticity and individuality of original art.', 'evara-art' ); ?></p>
		</div>

		<div class="policy-section">
			<h2><i class="fa-solid fa-certificate" style="color: var(--evara-terracotta);"></i> 2. <?php esc_html_e( 'Certificate of Authenticity', 'evara-art' ); ?></h2>
			<p><?php esc_html_e( 'All original canvas paintings and premium Lippan wall hangings are accompanied by an official, serialized Certificate of Authenticity signed by founder & lead artist Shraddha Parmar, certifying artwork medium, creation date, and original provenance.', 'evara-art' ); ?></p>
		</div>

		<div class="policy-section">
			<h2><i class="fa-solid fa-copyright" style="color: var(--evara-terracotta);"></i> 3. <?php esc_html_e( 'Copyright & Intellectual Property', 'evara-art' ); ?></h2>
			<p><?php esc_html_e( 'All artistic compositions, painting designs, Lippan patterns, high-resolution photographs, brand marks, and literary descriptions on this website are the sole intellectual property of Evara Art and Shraddha Parmar protected under Indian and international copyright conventions. Unauthorized duplication, commercial reproduction, or resale is strictly prohibited.', 'evara-art' ); ?></p>
		</div>

		<div class="policy-section">
			<h2><i class="fa-solid fa-scale-balanced" style="color: var(--evara-terracotta);"></i> 4. <?php esc_html_e( 'Pricing, Accuracy & Governing Law', 'evara-art' ); ?></h2>
			<p><?php esc_html_e( 'All prices listed on the website are in Indian Rupees (INR) and include applicable taxes. We reserve the right to modify prices, dimensions, and specifications without prior notice.', 'evara-art' ); ?></p>
			<p><?php esc_html_e( 'These terms and any disputes arising out of purchases from Evara Art shall be governed by and construed in accordance with the laws of India, under the exclusive jurisdiction of the competent courts in Gujarat, India.', 'evara-art' ); ?></p>
		</div>

		<div class="policy-contact-box">
			<h3 style="font-family: var(--font-serif); font-size: 1.4rem; margin-bottom: 8px;"><?php esc_html_e( 'Questions About Our Terms?', 'evara-art' ); ?></h3>
			<p style="font-size: 0.92rem; color: var(--evara-text-secondary); margin-bottom: 16px;"><?php esc_html_e( 'We are pleased to clarify any questions regarding our studio policies.', 'evara-art' ); ?></p>
			<a href="mailto:support@evaraart.in?subject=Terms%20Inquiry" class="evara-btn btn-outline" style="display: inline-flex;">
				<i class="fa-solid fa-envelope"></i> <?php esc_html_e( 'Email Studio Support (support@evaraart.in)', 'evara-art' ); ?>
			</a>
		</div>

	</div>
</div>

<?php get_footer(); ?>
