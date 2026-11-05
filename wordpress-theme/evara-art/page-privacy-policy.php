<?php
/**
 * Template Name: Privacy Policy
 *
 * @package EvaraArt
 * @version 1.2.0
 */

get_header(); ?>

<div class="policy-page-header">
	<div class="evara-container text-center">
		<div class="evara-breadcrumbs" style="margin-bottom: 12px;">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'evara-art' ); ?></a> &rsaquo; <span><?php esc_html_e( 'Privacy Policy', 'evara-art' ); ?></span>
		</div>
		<span class="sub-heading">✦ <?php esc_html_e( 'Data Protection & Confidentiality', 'evara-art' ); ?></span>
		<h1 class="page-title"><?php esc_html_e( 'Privacy Policy', 'evara-art' ); ?></h1>
		<p class="page-desc"><?php esc_html_e( 'Your privacy is sacred to us. Learn how Evara Art collects, protects, and respects your personal details.', 'evara-art' ); ?></p>
	</div>
</div>

<div class="policy-container">
	<div class="policy-card">
		
		<div class="policy-section">
			<h2><i class="fa-solid fa-lock" style="color: var(--evara-terracotta);"></i> 1. <?php esc_html_e( 'Information We Collect', 'evara-art' ); ?></h2>
			<p><?php esc_html_e( 'When you browse our studio catalog, place an order, or submit a custom commission request, we collect only the essential information needed to fulfill your request:', 'evara-art' ); ?></p>
			<ul>
				<li><strong><?php esc_html_e( 'Contact Details:', 'evara-art' ); ?></strong> <?php esc_html_e( 'Your name, email address, and mobile phone number for order confirmations, courier live tracking, and WhatsApp progress updates.', 'evara-art' ); ?></li>
				<li><strong><?php esc_html_e( 'Delivery Address:', 'evara-art' ); ?></strong> <?php esc_html_e( 'Your postal shipping address and PIN code to arrange secure, crated doorstep delivery.', 'evara-art' ); ?></li>
				<li><strong><?php esc_html_e( 'Consultation Inputs:', 'evara-art' ); ?></strong> <?php esc_html_e( 'Optional wall photos, dimensions, and color preferences submitted for bespoke commissions.', 'evara-art' ); ?></li>
			</ul>
		</div>

		<div class="policy-section">
			<h2><i class="fa-solid fa-credit-card" style="color: var(--evara-terracotta);"></i> 2. <?php esc_html_e( 'Payment Security & Encryption', 'evara-art' ); ?></h2>
			<p><strong><?php esc_html_e( 'Evara Art does not store, access, or process your credit card numbers, CVVs, or UPI PINs on our servers.', 'evara-art' ); ?></strong></p>
			<p><?php esc_html_e( 'All online transactions are securely handled by Razorpay, an RBI-licensed, PCI-DSS Level 1 compliant payment gateway. All checkout data transmission is encrypted using industry-standard 256-bit SSL (Secure Socket Layer) encryption.', 'evara-art' ); ?></p>
		</div>

		<div class="policy-section">
			<h2><i class="fa-solid fa-user-shield" style="color: var(--evara-terracotta);"></i> 3. <?php esc_html_e( 'Zero-Spam & Non-Disclosure Guarantee', 'evara-art' ); ?></h2>
			<p><?php esc_html_e( 'We respect your peace of mind. We will never sell, rent, lease, or monetize your personal details to third-party marketing brokers or advertisers.', 'evara-art' ); ?></p>
			<p><?php esc_html_e( 'Your data is shared exclusively with verified logistics partners (such as Blue Dart, Delhivery, or DTDC) solely for delivery purposes.', 'evara-art' ); ?></p>
		</div>

		<div class="policy-section">
			<h2><i class="fa-solid fa-envelope" style="color: var(--evara-terracotta);"></i> 4. <?php esc_html_e( 'Contact Privacy Officer', 'evara-art' ); ?></h2>
			<p><?php esc_html_e( 'If you have any questions regarding your personal information, or wish to request data correction or deletion, please reach out to our studio at support@evaraart.in.', 'evara-art' ); ?></p>
		</div>

	</div>
</div>

<?php get_footer(); ?>
