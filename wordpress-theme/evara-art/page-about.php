<?php
/**
 * Template Name: About & Studio Story
 *
 * Dedicated Brand Narrative & Artisan Heritage Page for Evara Art.
 *
 * @package EvaraArt
 * @version 1.4.3
 */

get_header(); ?>

<!-- Page Hero Header -->
<div class="evara-page-header about-page-hero">
	<div class="evara-container text-center">
		<div class="evara-breadcrumbs" style="margin-bottom: 14px;">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'evara-art' ); ?></a>
			<span class="sep">/</span>
			<span><?php esc_html_e( 'About & Studio Story', 'evara-art' ); ?></span>
		</div>
		<span class="sub-heading">✦ <?php esc_html_e( 'The Sacred Heart of Evara', 'evara-art' ); ?></span>
		<h1 class="page-title" style="font-size: clamp(2.2rem, 4vw, 3.2rem); margin-bottom: 14px; line-height: 1.15;">
			<?php esc_html_e( '“Evara” — A Gift of God, Painted From The Soul', 'evara-art' ); ?>
		</h1>
		<p class="page-desc" style="max-width: 720px; margin: 0 auto; font-size: 1.05rem; line-height: 1.7; color: var(--evara-text-secondary);">
			<?php esc_html_e( 'Where timeless Indian devotion, meditative brushstrokes, and authentic Gujarat mirror craft come alive to adorn sacred living spaces.', 'evara-art' ); ?>
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

	<!-- 1. The Artisan Journey Narrative Card -->
	<div class="about-narrative-card">
		<span class="detail-artisan-badge">✦ <?php esc_html_e( 'The Artisan Journey', 'evara-art' ); ?></span>
		<h2 style="font-family: var(--font-serif); font-size: clamp(1.85rem, 3vw, 2.4rem); color: var(--evara-text-primary); margin-bottom: 18px; line-height: 1.25;">
			<?php esc_html_e( 'Art Created as a Form of Divine Prayer', 'evara-art' ); ?>
		</h2>
		<p style="font-size: 1.02rem; line-height: 1.8; color: var(--evara-text-secondary); margin-bottom: 20px;">
			<?php esc_html_e( 'Welcome to Evara Art. The word “Evara” is an ancient derivation signifying Gift of God. To lead artist Shraddha Parmar, painting is never an assembly line or an automated factory process. It is a quiet meditation, a form of spiritual communion where sacred imagination is transformed into tangible, textured wall art for your home sanctuary.', 'evara-art' ); ?>
		</p>
		
		<blockquote class="about-quote-lead">
			<?php esc_html_e( '“Welcome to Evara Art – where creativity comes to life through every brushstroke. ‘Evara’ means Gift of God and I believe the art I create is truly a blessing and a form of divine expression. Each piece is crafted with passion, turning imagination into meaningful and beautiful artwork that honors divine patience.”', 'evara-art' ); ?>
			<footer style="margin-top: 14px; font-size: 0.92rem; font-style: normal; font-weight: 700; color: var(--evara-text-primary);">
				&mdash; <?php esc_html_e( 'Shraddha Parmar, Founder & Lead Artist', 'evara-art' ); ?>
			</footer>
		</blockquote>

		<p style="font-size: 1.02rem; line-height: 1.8; color: var(--evara-text-secondary); margin-top: 20px;">
			<?php esc_html_e( 'In a modern world saturated with flat, digital inkjet prints and synthetic mass factory decor, Evara Art was founded with a singular, uncompromising purpose: to bring back the soulful presence of authentic human touch. When you touch an Evara canvas or Lippan panel, you feel the raised ridges of the heavy impasto pigment, the coolness of hand-set glass mirrors, and the palpable tranquility imbued by days of patient devotion.', 'evara-art' ); ?>
		</p>
	</div>

	<!-- 2. The Two Heritage Disciplines -->
	<div class="craft-duo-grid" style="margin: 48px 0;">
		
		<!-- Discipline 1: Sacred Devotional Canvas -->
		<div class="craft-duo-card">
			<div class="craft-duo-content">
				<span class="craft-badge-pill"><?php esc_html_e( 'Heritage Discipline I', 'evara-art' ); ?></span>
				<h3 style="font-family: var(--font-serif); font-size: 1.7rem; color: var(--evara-text-primary); margin-bottom: 14px;">
					<?php esc_html_e( 'Sacred Devotional Canvas Paintings', 'evara-art' ); ?>
				</h3>
				<p style="font-size: 0.92rem; line-height: 1.7; color: var(--evara-text-secondary); margin-bottom: 18px;">
					<?php esc_html_e( 'Dedicated depictions of Lord Krishna, Radha Rani, Mahadev Shiva, and serene Pichwai lotus motifs. Each composition begins on seasoned kiln-dried wooden frames, hand-stretched with 380 GSM textured cotton canvas, layered with luminous pigments, and sealed with UV-resistant museum varnish.', 'evara-art' ); ?>
				</p>
				<ul style="font-size: 0.88rem; color: var(--evara-text-secondary); line-height: 1.85; padding-left: 20px; margin-bottom: 20px;">
					<li><strong><?php esc_html_e( '100% Original Hand-Painted Brushwork', 'evara-art' ); ?></strong> &bull; <?php esc_html_e( 'Never mass machine prints', 'evara-art' ); ?></li>
					<li><strong><?php esc_html_e( 'Warm, Soothing Palettes', 'evara-art' ); ?></strong> <?php esc_html_e( 'designed for living rooms, master bedrooms & prayer alcoves', 'evara-art' ); ?></li>
					<li><strong><?php esc_html_e( 'Luminous Depth', 'evara-art' ); ?></strong> <?php esc_html_e( 'that radiates warmth in daylight and ambient evening lighting', 'evara-art' ); ?></li>
				</ul>
				<a href="<?php echo esc_url( evara_get_category_url( array( 'canvas-painting-evara-art', 'canvas-paintings-home-decor' ), '/product-category/canvas-painting-evara-art/' ) ); ?>" class="evara-btn btn-outline" style="padding: 10px 20px; font-size: 0.88rem;">
					<span><?php esc_html_e( 'Explore Canvas Collection', 'evara-art' ); ?></span> <i class="fa-solid fa-arrow-right"></i>
				</a>
			</div>
		</div>

		<!-- Discipline 2: Traditional Gujarat Lippan Mirror Art -->
		<div class="craft-duo-card">
			<div class="craft-duo-content">
				<span class="craft-badge-pill"><?php esc_html_e( 'Heritage Discipline II', 'evara-art' ); ?></span>
				<h3 style="font-family: var(--font-serif); font-size: 1.7rem; color: var(--evara-text-primary); margin-bottom: 14px;">
					<?php esc_html_e( 'Traditional Gujarat Lippan & Clay Art', 'evara-art' ); ?>
				</h3>
				<p style="font-size: 0.92rem; line-height: 1.7; color: var(--evara-text-secondary); margin-bottom: 18px;">
					<?php esc_html_e( 'Born from the rural artisan hamlets of Kutch, Gujarat, Lippan Kaam is an ancient mud-relief architecture craft. We elevate this sacred tradition into contemporary luxury art pieces using durable fortified clay dough, hand-rolled relief borders, and hundreds of individually hand-placed mirror facets.', 'evara-art' ); ?>
				</p>
				<ul style="font-size: 0.88rem; color: var(--evara-text-secondary); line-height: 1.85; padding-left: 20px; margin-bottom: 20px;">
					<li><strong><?php esc_html_e( 'Authentic Fortified Clay Dough', 'evara-art' ); ?></strong> <?php esc_html_e( 'formulated for zero cracking and lifetime durability', 'evara-art' ); ?></li>
					<li><strong><?php esc_html_e( 'Hand-Trimmed Mirror Facets', 'evara-art' ); ?></strong> <?php esc_html_e( 'that reflect ambient warm light and invite prosperity', 'evara-art' ); ?></li>
					<li><strong><?php esc_html_e( 'Sacred Geometry & Mandalas', 'evara-art' ); ?></strong> &bull; <?php esc_html_e( 'Auspicious Ganpati, Annapakshi, and Gayatri patterns', 'evara-art' ); ?></li>
				</ul>
				<a href="<?php echo esc_url( evara_get_category_url( array( 'clay-art-evara-art', 'lippan-art-home-decor' ), '/product-category/clay-art-evara-art/' ) ); ?>" class="evara-btn btn-outline" style="padding: 10px 20px; font-size: 0.88rem;">
					<span><?php esc_html_e( 'Explore Lippan Collection', 'evara-art' ); ?></span> <i class="fa-solid fa-arrow-right"></i>
				</a>
			</div>
		</div>

	</div>

	<!-- 3. The 4 Sacred Commitments -->
	<div class="pillars-4-grid" style="margin: 48px 0;">
		<div class="pillar-box">
			<div class="pillar-icon-wrap"><i class="fa-solid fa-hand-holding-heart"></i></div>
			<h4 style="font-size: 1.08rem; font-weight: 700; margin-bottom: 8px;"><?php esc_html_e( '100% Genuine Handwork', 'evara-art' ); ?></h4>
			<p style="font-size: 0.85rem; color: var(--evara-text-secondary); line-height: 1.6;">
				<?php esc_html_e( 'Every artwork is an authentic human creation from initial charcoal sketch to the final protective varnish seal.', 'evara-art' ); ?>
			</p>
		</div>

		<div class="pillar-box">
			<div class="pillar-icon-wrap"><i class="fa-solid fa-spa"></i></div>
			<h4 style="font-size: 1.08rem; font-weight: 700; margin-bottom: 8px;"><?php esc_html_e( 'Soulful Living Energy', 'evara-art' ); ?></h4>
			<p style="font-size: 0.85rem; color: var(--evara-text-secondary); line-height: 1.6;">
				<?php esc_html_e( 'Infusing peaceful devotion, auspicious positivity, and spiritual serenity into Indian households and sanctuaries.', 'evara-art' ); ?>
			</p>
		</div>

		<div class="pillar-box">
			<div class="pillar-icon-wrap"><i class="fa-solid fa-box-archive"></i></div>
			<h4 style="font-size: 1.08rem; font-weight: 700; margin-bottom: 8px;"><?php esc_html_e( 'Armored Wooden Crating', 'evara-art' ); ?></h4>
			<p style="font-size: 0.85rem; color: var(--evara-text-secondary); line-height: 1.6;">
				<?php esc_html_e( 'Custom reinforced wooden crating with 100% zero-damage replacement transit protection across all Indian states.', 'evara-art' ); ?>
			</p>
		</div>

		<div class="pillar-box">
			<div class="pillar-icon-wrap"><i class="fa-solid fa-certificate"></i></div>
			<h4 style="font-size: 1.08rem; font-weight: 700; margin-bottom: 8px;"><?php esc_html_e( 'Direct Artist Patronage', 'evara-art' ); ?></h4>
			<p style="font-size: 0.85rem; color: var(--evara-text-secondary); line-height: 1.6;">
				<?php esc_html_e( 'Connect directly with lead artist Shraddha Parmar, complete with signed Certificate of Authenticity.', 'evara-art' ); ?>
			</p>
		</div>
	</div>

	<!-- 4. Collector Testimonials -->
	<div style="margin: 54px 0 28px; text-align: center;">
		<span class="sub-heading">✦ <?php esc_html_e( 'Voices From Indian Homes', 'evara-art' ); ?></span>
		<h3 class="main-heading" style="font-size: 2.1rem;"><?php esc_html_e( 'Collector Love & Experiences', 'evara-art' ); ?></h3>
	</div>

	<div class="testimonials-row" style="margin-bottom: 50px;">
		<div class="testimonial-card">
			<div style="color: #F59E0B; margin-bottom: 8px;">★★★★★</div>
			<p style="font-size: 0.9rem; line-height: 1.65; color: var(--evara-text-secondary); font-style: italic;">
				<?php esc_html_e( '“The Meera & Krishna painting arrived in Mumbai in pristine armored wooden crating. The divine expression on Krishna’s face brings absolute peace to our living room.”', 'evara-art' ); ?>
			</p>
			<div class="testimonial-author"><?php esc_html_e( 'Dr. Rajesh Iyer', 'evara-art' ); ?></div>
			<div class="testimonial-city"><?php esc_html_e( 'Mumbai, Maharashtra', 'evara-art' ); ?></div>
		</div>

		<div class="testimonial-card">
			<div style="color: #F59E0B; margin-bottom: 8px;">★★★★★</div>
			<p style="font-size: 0.9rem; line-height: 1.65; color: var(--evara-text-secondary); font-style: italic;">
				<?php esc_html_e( '“Our Ganpati Lippan art piece is the crown jewel of our foyer. When the morning sunlight hits the mirrors, the whole hallway sparkles with warmth.”', 'evara-art' ); ?>
			</p>
			<div class="testimonial-author"><?php esc_html_e( 'Pooja & Vikram Mehta', 'evara-art' ); ?></div>
			<div class="testimonial-city"><?php esc_html_e( 'Ahmedabad, Gujarat', 'evara-art' ); ?></div>
		</div>

		<div class="testimonial-card">
			<div style="color: #F59E0B; margin-bottom: 8px;">★★★★★</div>
			<p style="font-size: 0.9rem; line-height: 1.65; color: var(--evara-text-secondary); font-style: italic;">
				<?php esc_html_e( '“Shraddha accommodated our custom 4x3 ft wall dimensions perfectly and sent WhatsApp videos at every stage. Authentic artisan devotion at its best!”', 'evara-art' ); ?>
			</p>
			<div class="testimonial-author"><?php esc_html_e( 'Sneha Kulkarni', 'evara-art' ); ?></div>
			<div class="testimonial-city"><?php esc_html_e( 'Bengaluru, Karnataka', 'evara-art' ); ?></div>
		</div>
	</div>

	<!-- 5. Studio Direct Connection & Inquiry Box -->
	<div style="background: #FFFFFF; border-radius: var(--radius-lg); padding: clamp(28px, 4vw, 44px); border: 1px solid var(--evara-border); box-shadow: var(--shadow-card); text-align: center;">
		<span class="sub-heading">✦ <?php esc_html_e( 'Studio Connection', 'evara-art' ); ?></span>
		<h3 style="font-family: var(--font-serif); font-size: clamp(1.8rem, 2.5vw, 2.2rem); color: var(--evara-text-primary); margin-bottom: 12px;">
			<?php esc_html_e( 'Connect Directly with Our Studio', 'evara-art' ); ?>
		</h3>
		<p style="font-size: 0.95rem; color: var(--evara-text-secondary); max-width: 600px; margin: 0 auto 30px; line-height: 1.7;">
			<?php esc_html_e( 'Whether you have questions about artwork availability, bespoke commissions, or insured wooden crated shipping, we are delighted to assist you directly.', 'evara-art' ); ?>
		</p>

		<div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 16px; margin-bottom: 30px;">
			<a href="https://wa.me/919328143036?text=Hi%20Shraddha,%20I'm%20visiting%20Evara%20Art%20and%20would%20love%20to%20know%20more%20about%20your%20artworks." 
			   target="_blank" 
			   rel="noopener noreferrer" 
			   class="evara-btn btn-whatsapp-lg" 
			   style="padding: 14px 28px;">
				<i class="fa-brands fa-whatsapp"></i> <?php esc_html_e( 'Chat with Artist (+91 93281 43036)', 'evara-art' ); ?>
			</a>
			<a href="<?php echo esc_url( evara_get_custom_art_url() ); ?>" class="evara-btn btn-primary" style="padding: 14px 28px;">
				<span><?php esc_html_e( 'Commission Custom Artwork', 'evara-art' ); ?></span> <i class="fa-solid fa-arrow-right"></i>
			</a>
			<a href="mailto:support@evaraart.in" class="evara-btn btn-outline" style="padding: 14px 28px;">
				<i class="fa-solid fa-envelope"></i> <?php esc_html_e( 'Email Studio', 'evara-art' ); ?>
			</a>
		</div>

		<div style="font-size: 0.88rem; color: var(--evara-text-muted); border-top: 1px solid var(--evara-border); padding-top: 20px;">
			<i class="fa-solid fa-location-dot" style="color: var(--evara-terracotta); margin-right: 6px;"></i>
			<?php esc_html_e( 'Studio based in Gujarat, India • Insured wooden crated dispatch across all Indian pin codes.', 'evara-art' ); ?>
		</div>
	</div>

</div>

<?php get_footer(); ?>
