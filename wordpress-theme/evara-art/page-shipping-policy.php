<?php
/**
 * Template Name: Shipping & Delivery Policy
 *
 * @package EvaraArt
 * @version 1.2.0
 */

get_header(); ?>

<div class="policy-page-header">
	<div class="evara-container text-center">
		<div class="evara-breadcrumbs" style="margin-bottom: 12px;">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'evara-art' ); ?></a> &rsaquo; <span><?php esc_html_e( 'Shipping & Delivery Policy', 'evara-art' ); ?></span>
		</div>
		<span class="sub-heading">✦ <?php esc_html_e( 'Safe, Armored Transit Pan-India', 'evara-art' ); ?></span>
		<h1 class="page-title"><?php esc_html_e( 'Pan-India Shipping & Delivery Policy', 'evara-art' ); ?></h1>
		<p class="page-desc"><?php esc_html_e( 'Every original painting and Lippan artwork is hand-crated with care to guarantee pristine, damage-free arrival at your doorstep.', 'evara-art' ); ?></p>
	</div>
</div>

<div class="policy-container">
	<div class="policy-card">
		
		<div class="policy-section">
			<h2><i class="fa-solid fa-truck-fast" style="color: var(--evara-terracotta);"></i> 1. <?php esc_html_e( 'Insured Delivery & Shipping Charges', 'evara-art' ); ?></h2>
			<p><?php esc_html_e( 'We provide fully insured express doorstep delivery across all serviceable postal PIN codes in India through top-tier courier networks including Blue Dart, Delhivery, DTDC, and Xpressbees.', 'evara-art' ); ?></p>
			
			<div class="policy-highlight-box">
				<strong>✦ <?php esc_html_e( 'Free Shipping Threshold:', 'evara-art' ); ?></strong> <?php printf( esc_html__( 'All domestic orders with a cart subtotal of ₹%s and above qualify for 100%% Free Pan-India Delivery. For orders below ₹%s, a flat insured shipping fee of ₹150 is applied at checkout.', 'evara-art' ), number_format( evara_get_free_shipping_threshold() ), number_format( evara_get_free_shipping_threshold() ) ); ?>
			</div>
		</div>

		<div class="policy-section">
			<h2><i class="fa-solid fa-clock" style="color: var(--evara-terracotta);"></i> 2. <?php esc_html_e( 'Studio Processing & Dispatch Timelines', 'evara-art' ); ?></h2>
			<p><?php esc_html_e( 'Because Evara Art creates genuine handcrafted originals rather than automated mass prints, every artwork undergoes careful final curing, moisture-seal varnishing, and structural inspection before dispatch:', 'evara-art' ); ?></p>
			
			<div class="policy-table-wrap">
				<table class="policy-table">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Artwork Category', 'evara-art' ); ?></th>
							<th><?php esc_html_e( 'Studio Handcraft & Preparation', 'evara-art' ); ?></th>
							<th><?php esc_html_e( 'Transit Time (Post-Dispatch)', 'evara-art' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><strong><?php esc_html_e( 'Ready-to-Ship Canvas Paintings', 'evara-art' ); ?></strong></td>
							<td><?php esc_html_e( '2 – 4 Business Days (Varnish & Quality Check)', 'evara-art' ); ?></td>
							<td><?php esc_html_e( '3 – 6 Business Days', 'evara-art' ); ?></td>
						</tr>
						<tr>
							<td><strong><?php esc_html_e( 'Handcrafted Lippan Mirror Art', 'evara-art' ); ?></strong></td>
							<td><?php esc_html_e( '3 – 5 Business Days (Clay Curing & Mirror Inspection)', 'evara-art' ); ?></td>
							<td><?php esc_html_e( '4 – 7 Business Days', 'evara-art' ); ?></td>
						</tr>
						<tr>
							<td><strong><?php esc_html_e( 'Bespoke Custom Commissions', 'evara-art' ); ?></strong></td>
							<td><?php esc_html_e( '7 – 14 Business Days (Collaborative Painting & Drying)', 'evara-art' ); ?></td>
							<td><?php esc_html_e( '3 – 6 Business Days', 'evara-art' ); ?></td>
						</tr>
					</tbody>
				</table>
			</div>
			<p style="font-size: 0.88rem; color: var(--evara-text-muted);"><?php esc_html_e( '*Business days exclude Sundays and Indian National Holidays. Severe weather conditions or regional transport restrictions may occasionally extend courier transit windows.', 'evara-art' ); ?></p>
		</div>

		<div class="policy-section">
			<h2><i class="fa-solid fa-box-archive" style="color: var(--evara-terracotta);"></i> 3. <?php esc_html_e( 'Armored Wooden Crating Standard', 'evara-art' ); ?></h2>
			<p><?php esc_html_e( 'We understand that delicate canvas duck fabric and hand-cut Lippan mirrors require exceptional structural reinforcement. Every package is packed using our 4-tier packaging protocol:', 'evara-art' ); ?></p>
			<ul>
				<li><strong><?php esc_html_e( 'Layer 1 – Anti-Moisture Barrier:', 'evara-art' ); ?></strong> <?php esc_html_e( 'Archival glassine wrap and dust-proof protective plastic sheathing.', 'evara-art' ); ?></li>
				<li><strong><?php esc_html_e( 'Layer 2 – Impact Air-Cushion:', 'evara-art' ); ?></strong> <?php esc_html_e( 'Triple-layer 10mm shock-absorbent air bubble wrap.', 'evara-art' ); ?></li>
				<li><strong><?php esc_html_e( 'Layer 3 – Reinforced Corner Guards:', 'evara-art' ); ?></strong> <?php esc_html_e( 'High-density rigid foam caps on all 4 corners to shield against drops.', 'evara-art' ); ?></li>
				<li><strong><?php esc_html_e( 'Layer 4 – Rigid Wooden Hardboard Outer Crating:', 'evara-art' ); ?></strong> <?php esc_html_e( 'Solid composite hardboard sandwiching the artwork with tamper-evident security strapping.', 'evara-art' ); ?></li>
			</ul>
		</div>

		<div class="policy-section">
			<h2><i class="fa-solid fa-location-dot" style="color: var(--evara-terracotta);"></i> 4. <?php esc_html_e( 'Live Tracking & Delivery Updates', 'evara-art' ); ?></h2>
			<p><?php esc_html_e( 'The moment your artwork is handed over to our insured logistics partner, you will receive an automatic dispatch notification with a direct tracking link via WhatsApp and SMS. You can follow your parcel’s live movement directly to your doorstep.', 'evara-art' ); ?></p>
		</div>

		<div class="policy-contact-box">
			<h3 style="font-family: var(--font-serif); font-size: 1.4rem; margin-bottom: 8px;"><?php esc_html_e( 'Need Express or Urgent Dispatch?', 'evara-art' ); ?></h3>
			<p style="font-size: 0.92rem; color: var(--evara-text-secondary); margin-bottom: 16px;"><?php esc_html_e( 'Celebrating a housewarming, anniversary, or pooja? Connect with artist Shraddha on WhatsApp for priority expedited dispatch.', 'evara-art' ); ?></p>
			<a href="https://wa.me/919328143036?text=Hi%20Shraddha,%20I%20have%20an%20urgent%20shipping%20request%20for%20an%20artwork" target="_blank" rel="noopener noreferrer" class="evara-btn btn-whatsapp-lg" style="display: inline-flex;">
				<i class="fa-brands fa-whatsapp"></i> <?php esc_html_e( 'WhatsApp Dispatch Support (+91 93281 43036)', 'evara-art' ); ?>
			</a>
		</div>

	</div>
</div>

<?php get_footer(); ?>
