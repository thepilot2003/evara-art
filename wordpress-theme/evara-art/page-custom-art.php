<?php
/**
 * Template Name: Custom Art Commissions
 *
 * Bespoke Wall Commission Inquiry Page with Interactive WhatsApp Direct Booking.
 *
 * @package EvaraArt
 * @version 1.4.3
 */

get_header(); ?>

<!-- Page Hero Header -->
<div class="evara-page-header commission-page-header">
	<div class="evara-container text-center">
		<div class="evara-breadcrumbs" style="margin-bottom: 14px;">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'evara-art' ); ?></a>
			<span class="sep">/</span>
			<span><?php esc_html_e( 'Custom Art Commissions', 'evara-art' ); ?></span>
		</div>
		<span class="sub-heading">✦ <?php esc_html_e( 'Tailored For Your Wall & Sanctuary', 'evara-art' ); ?></span>
		<h1 class="page-title" style="font-size: clamp(2.2rem, 4vw, 3.2rem); margin-bottom: 14px; line-height: 1.15;">
			<?php esc_html_e( 'Bespoke Fine Art Commissions', 'evara-art' ); ?>
		</h1>
		<p class="page-desc" style="max-width: 720px; margin: 0 auto; font-size: 1.05rem; line-height: 1.7; color: var(--evara-text-secondary);">
			<?php esc_html_e( 'Have a specific wall dimension, prayer room deity, or interior palette? Collaborate directly with lead artist Shraddha to craft your one-of-a-kind original masterpiece.', 'evara-art' ); ?>
		</p>
	</div>
</div>

<div class="evara-container page-content-container" style="max-width: 1040px; padding-bottom: 90px;">

	<!-- If admin has added custom content in the WordPress page editor, render it cleanly -->
	<?php
	while ( have_posts() ) :
		the_post();
		if ( trim( get_the_content() ) !== '' ) :
			?>
			<div class="page-entry-content standard-wp-content" style="margin-bottom: 40px;">
				<?php the_content(); ?>
			</div>
			<?php
		endif;
	endwhile;
	?>

	<!-- 4-Step Commission Journey Grid -->
	<div class="commission-journey-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 44px;">
		<div class="journey-step-card" style="background: #FFFFFF; border: 1px solid var(--evara-border); border-radius: var(--radius-md); padding: 24px; box-shadow: var(--shadow-subtle);">
			<div class="journey-num" style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 50%; background: var(--evara-surface-muted); color: var(--evara-terracotta); font-weight: 700; font-size: 0.95rem; margin-bottom: 14px;">1</div>
			<h4 style="font-size: 1.05rem; font-weight: 700; margin-bottom: 8px; color: var(--evara-text-primary);"><?php esc_html_e( 'Wall Consultation', 'evara-art' ); ?></h4>
			<p style="font-size: 0.88rem; color: var(--evara-text-secondary); line-height: 1.6; margin: 0;"><?php esc_html_e( 'Share your room lighting, wall dimensions, and deity or theme vision with our studio.', 'evara-art' ); ?></p>
		</div>

		<div class="journey-step-card" style="background: #FFFFFF; border: 1px solid var(--evara-border); border-radius: var(--radius-md); padding: 24px; box-shadow: var(--shadow-subtle);">
			<div class="journey-num" style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 50%; background: var(--evara-surface-muted); color: var(--evara-terracotta); font-weight: 700; font-size: 0.95rem; margin-bottom: 14px;">2</div>
			<h4 style="font-size: 1.05rem; font-weight: 700; margin-bottom: 8px; color: var(--evara-text-primary);"><?php esc_html_e( 'Concept & Palette', 'evara-art' ); ?></h4>
			<p style="font-size: 0.88rem; color: var(--evara-text-secondary); line-height: 1.6; margin: 0;"><?php esc_html_e( 'We harmonize colors, clay relief patterns, and canvas sizing for your personal approval.', 'evara-art' ); ?></p>
		</div>

		<div class="journey-step-card" style="background: #FFFFFF; border: 1px solid var(--evara-border); border-radius: var(--radius-md); padding: 24px; box-shadow: var(--shadow-subtle);">
			<div class="journey-num" style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 50%; background: var(--evara-surface-muted); color: var(--evara-terracotta); font-weight: 700; font-size: 0.95rem; margin-bottom: 14px;">3</div>
			<h4 style="font-size: 1.05rem; font-weight: 700; margin-bottom: 8px; color: var(--evara-text-primary);"><?php esc_html_e( 'Handcrafting & Videos', 'evara-art' ); ?></h4>
			<p style="font-size: 0.88rem; color: var(--evara-text-secondary); line-height: 1.6; margin: 0;"><?php esc_html_e( 'Receive periodic work-in-progress photos and 4K video updates directly on WhatsApp.', 'evara-art' ); ?></p>
		</div>

		<div class="journey-step-card" style="background: #FFFFFF; border: 1px solid var(--evara-border); border-radius: var(--radius-md); padding: 24px; box-shadow: var(--shadow-subtle);">
			<div class="journey-num" style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 50%; background: var(--evara-surface-muted); color: var(--evara-terracotta); font-weight: 700; font-size: 0.95rem; margin-bottom: 14px;">4</div>
			<h4 style="font-size: 1.05rem; font-weight: 700; margin-bottom: 8px; color: var(--evara-text-primary);"><?php esc_html_e( 'Armored Delivery', 'evara-art' ); ?></h4>
			<p style="font-size: 0.88rem; color: var(--evara-text-secondary); line-height: 1.6; margin: 0;"><?php esc_html_e( 'Crated in reinforced wooden hardboards and safely dispatched to your doorstep ready to mount.', 'evara-art' ); ?></p>
		</div>
	</div>

	<!-- Interactive Commission Studio Form Box -->
	<div class="commission-studio-box" style="background: #FFFFFF; border-radius: var(--radius-lg); padding: clamp(28px, 4vw, 44px); border: 1px solid var(--evara-border); box-shadow: var(--shadow-card); margin-bottom: 48px;">
		<div style="text-align: center; margin-bottom: 28px;">
			<span class="sub-heading">✦ <?php esc_html_e( 'Personal Studio Request', 'evara-art' ); ?></span>
			<h3 style="font-family: var(--font-serif); font-size: clamp(1.8rem, 2.5vw, 2.2rem); color: var(--evara-text-primary); margin-bottom: 8px;">
				<?php esc_html_e( 'Design Your Custom Artwork', 'evara-art' ); ?>
			</h3>
			<p style="font-size: 0.92rem; color: var(--evara-text-secondary); max-width: 580px; margin: 0 auto;">
				<?php esc_html_e( 'Fill in your details below to instantly generate a direct WhatsApp message to lead artist Shraddha Parmar.', 'evara-art' ); ?>
			</p>
		</div>

		<form id="evara-commission-form" onsubmit="handleCommissionSubmit(event)">
			<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px; margin-bottom: 20px;">
				<div>
					<label for="comm-name" style="display: block; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--evara-text-primary); margin-bottom: 6px;">
						<?php esc_html_e( 'Your Full Name *', 'evara-art' ); ?>
					</label>
					<input type="text" id="comm-name" required placeholder="<?php esc_attr_e( 'e.g. Ananya Patel', 'evara-art' ); ?>" style="width: 100%; padding: 12px 16px; border: 1px solid var(--evara-border); border-radius: var(--radius-sm); font-size: 0.95rem;">
				</div>
				<div>
					<label for="comm-phone" style="display: block; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--evara-text-primary); margin-bottom: 6px;">
						<?php esc_html_e( 'WhatsApp Phone *', 'evara-art' ); ?>
					</label>
					<input type="tel" id="comm-phone" required placeholder="<?php esc_attr_e( 'e.g. +91 98765 43210', 'evara-art' ); ?>" style="width: 100%; padding: 12px 16px; border: 1px solid var(--evara-border); border-radius: var(--radius-sm); font-size: 0.95rem;">
				</div>
			</div>

			<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px; margin-bottom: 20px;">
				<div>
					<label for="comm-city" style="display: block; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--evara-text-primary); margin-bottom: 6px;">
						<?php esc_html_e( 'Delivery City & State *', 'evara-art' ); ?>
					</label>
					<input type="text" id="comm-city" required placeholder="<?php esc_attr_e( 'e.g. Mumbai, Maharashtra', 'evara-art' ); ?>" style="width: 100%; padding: 12px 16px; border: 1px solid var(--evara-border); border-radius: var(--radius-sm); font-size: 0.95rem;">
				</div>
				<div>
					<label for="comm-type" style="display: block; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--evara-text-primary); margin-bottom: 6px;">
						<?php esc_html_e( 'Art Style *', 'evara-art' ); ?>
					</label>
					<select id="comm-type" style="width: 100%; padding: 12px 16px; border: 1px solid var(--evara-border); border-radius: var(--radius-sm); font-size: 0.95rem; background: #FFFFFF;">
						<option value="Sacred Canvas Painting"><?php esc_html_e( 'Sacred Canvas Painting (Krishna, Shiva, Devotional)', 'evara-art' ); ?></option>
						<option value="Traditional Lippan & Clay Art"><?php esc_html_e( 'Traditional Lippan & Clay Mirror Art', 'evara-art' ); ?></option>
						<option value="Modern Boho / Portrait Art"><?php esc_html_e( 'Modern Boho / Portrait Artwork', 'evara-art' ); ?></option>
						<option value="Custom Deity / Temple Sanctuary"><?php esc_html_e( 'Custom Deity / Temple Sanctuary Art', 'evara-art' ); ?></option>
					</select>
				</div>
			</div>

			<div style="margin-bottom: 20px;">
				<label for="comm-size" style="display: block; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--evara-text-primary); margin-bottom: 6px;">
					<?php esc_html_e( 'Desired Wall Dimensions (Approximate)', 'evara-art' ); ?>
				</label>
				<input type="text" id="comm-size" placeholder="<?php esc_attr_e( 'e.g. 3x2 ft, 4x3 ft, 5x3 ft, or custom wall space in inches', 'evara-art' ); ?>" style="width: 100%; padding: 12px 16px; border: 1px solid var(--evara-border); border-radius: var(--radius-sm); font-size: 0.95rem;">
			</div>

			<div style="margin-bottom: 28px;">
				<label for="comm-notes" style="display: block; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--evara-text-primary); margin-bottom: 6px;">
					<?php esc_html_e( 'Describe Your Vision (Colors, Deities, Special Requests)', 'evara-art' ); ?>
				</label>
				<textarea id="comm-notes" rows="4" placeholder="<?php esc_attr_e( 'Tell us about your room color scheme, deity pose, gold leaf accents, or specific inspirations...', 'evara-art' ); ?>" style="width: 100%; padding: 12px 16px; border: 1px solid var(--evara-border); border-radius: var(--radius-sm); font-size: 0.95rem;"></textarea>
			</div>

			<div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 16px;">
				<button type="submit" class="evara-btn btn-whatsapp-lg" style="padding: 14px 32px; font-size: 1rem; border: none; cursor: pointer;">
					<i class="fa-brands fa-whatsapp"></i> <?php esc_html_e( 'Send Commission Inquiry on WhatsApp', 'evara-art' ); ?>
				</button>
				<a href="mailto:support@evaraart.in?subject=Custom%20Commission%20Inquiry" class="evara-btn btn-outline" style="padding: 14px 28px;">
					<i class="fa-solid fa-envelope"></i> <?php esc_html_e( 'Email Details to Studio', 'evara-art' ); ?>
				</a>
			</div>
		</form>
	</div>

	<!-- Commission FAQ Accordion -->
	<div style="margin-top: 50px;">
		<div style="text-align: center; margin-bottom: 30px;">
			<span class="sub-heading">✦ <?php esc_html_e( 'Clarity & Patronage', 'evara-art' ); ?></span>
			<h3 class="main-heading" style="font-size: 2rem;"><?php esc_html_e( 'Frequently Asked Questions', 'evara-art' ); ?></h3>
		</div>

		<div class="commission-faq-list" style="max-width: 840px; margin: 0 auto; display: flex; flex-direction: column; gap: 14px;">
			
			<details style="background: #FFFFFF; border: 1px solid var(--evara-border); border-radius: var(--radius-md); padding: 18px 22px; cursor: pointer;" open>
				<summary style="font-weight: 700; font-size: 1.05rem; color: var(--evara-text-primary); list-style: none; display: flex; justify-content: space-between; align-items: center;">
					<span><?php esc_html_e( 'How long does a bespoke artwork take to complete?', 'evara-art' ); ?></span>
					<i class="fa-solid fa-chevron-down" style="font-size: 0.85rem; color: var(--evara-terracotta);"></i>
				</summary>
				<p style="margin-top: 12px; font-size: 0.92rem; color: var(--evara-text-secondary); line-height: 1.7;">
					<?php esc_html_e( 'Hand-painted canvas compositions typically take 10 to 18 business days, while intricate clay and mirror Lippan Kaam takes 14 to 21 business days. Drying, pigment curing, and protective varnishing cannot be rushed to guarantee decades of longevity.', 'evara-art' ); ?>
				</p>
			</details>

			<details style="background: #FFFFFF; border: 1px solid var(--evara-border); border-radius: var(--radius-md); padding: 18px 22px; cursor: pointer;">
				<summary style="font-weight: 700; font-size: 1.05rem; color: var(--evara-text-primary); list-style: none; display: flex; justify-content: space-between; align-items: center;">
					<span><?php esc_html_e( 'Can you match specific colors to our room interior?', 'evara-art' ); ?></span>
					<i class="fa-solid fa-chevron-down" style="font-size: 0.85rem; color: var(--evara-terracotta);"></i>
				</summary>
				<p style="margin-top: 12px; font-size: 0.92rem; color: var(--evara-text-secondary); line-height: 1.7;">
					<?php esc_html_e( 'Absolutely. You are welcome to send photos of your room wall, sofa fabric, or lighting. Artist Shraddha will customize the background tone and highlight palette to harmonize effortlessly with your living space.', 'evara-art' ); ?>
				</p>
			</details>

			<details style="background: #FFFFFF; border: 1px solid var(--evara-border); border-radius: var(--radius-md); padding: 18px 22px; cursor: pointer;">
				<summary style="font-weight: 700; font-size: 1.05rem; color: var(--evara-text-primary); list-style: none; display: flex; justify-content: space-between; align-items: center;">
					<span><?php esc_html_e( 'How is the custom piece protected during courier transit?', 'evara-art' ); ?></span>
					<i class="fa-solid fa-chevron-down" style="font-size: 0.85rem; color: var(--evara-terracotta);"></i>
				</summary>
				<p style="margin-top: 12px; font-size: 0.92rem; color: var(--evara-text-secondary); line-height: 1.7;">
					<?php esc_html_e( 'Every custom creation is encased in protective bubble wrap, corner guards, and sealed inside an armored wooden hardboard crate. We offer a 100% zero-risk transit guarantee with free immediate repair or remake in the rare event of transit mishap.', 'evara-art' ); ?>
				</p>
			</details>

			<details style="background: #FFFFFF; border: 1px solid var(--evara-border); border-radius: var(--radius-md); padding: 18px 22px; cursor: pointer;">
				<summary style="font-weight: 700; font-size: 1.05rem; color: var(--evara-text-primary); list-style: none; display: flex; justify-content: space-between; align-items: center;">
					<span><?php esc_html_e( 'What are the booking and payment terms?', 'evara-art' ); ?></span>
					<i class="fa-solid fa-chevron-down" style="font-size: 0.85rem; color: var(--evara-terracotta);"></i>
				</summary>
				<p style="margin-top: 12px; font-size: 0.92rem; color: var(--evara-text-secondary); line-height: 1.7;">
					<?php esc_html_e( 'We require a 50% deposit to procure materials, build custom stretchers, and begin sketching. The remaining 50% is due only upon your final approval of 4K work-in-progress videos right before wooden crating and insured dispatch.', 'evara-art' ); ?>
				</p>
			</details>

		</div>
	</div>

</div>

<script>
function handleCommissionSubmit(e) {
	e.preventDefault();
	var name = document.getElementById('comm-name').value;
	var phone = document.getElementById('comm-phone').value;
	var city = document.getElementById('comm-city').value;
	var artType = document.getElementById('comm-type').value;
	var size = document.getElementById('comm-size').value || 'To be discussed';
	var notes = document.getElementById('comm-notes').value || 'None';

	var msg = "Hi Shraddha,\n\nI would love to commission a custom artwork with Evara Art!\n\n" +
	          "✦ Name: " + name + "\n" +
	          "✦ Phone: " + phone + "\n" +
	          "✦ City: " + city + "\n" +
	          "✦ Art Style: " + artType + "\n" +
	          "✦ Desired Dimensions: " + size + "\n" +
	          "✦ Vision & Notes: " + notes + "\n\n" +
	          "Looking forward to hearing from you!";

	var url = "https://wa.me/919328143036?text=" + encodeURIComponent(msg);
	window.open(url, '_blank');
}
</script>

<?php get_footer(); ?>
