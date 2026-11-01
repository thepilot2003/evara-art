<?php
/**
 * The Front Page template for Evara Art (Fluid, Enhanced & Non-Clipping)
 *
 * @package EvaraArt
 */

get_header(); ?>

<!-- 1. Hero Section with Authentic Background Artwork -->
<section class="evara-hero-section">
	<div class="evara-container">
		
		<!-- Visual Artwork Banner -->
		<div class="hero-banner-card">
			<img src="<?php echo esc_url( EVARA_URI . '/assets/images/hero-banner-krishna.png' ); ?>" 
			     alt="Art that connects to your heart - Evara Art" 
			     class="hero-banner-img">
		</div>

		<!-- Fluid Editorial Intro (No Text Clipping) -->
		<div class="hero-editorial-wrap">
			
			<div class="hero-text-content">
				<div class="hero-badge">
					<span class="badge-dot"></span>
					<span>Handmade in India &bull; Bespoke Fine Art</span>
				</div>
				
				<h1 class="hero-title">
					Art that <span class="italic-serif">connects</span> to your <span class="highlight-text">Heart.</span>
				</h1>
				
				<p class="hero-subtitle">
					Welcome to <strong>Evara Art</strong> – where creativity comes to life through every brushstroke. “Evara” means <em>Gift of God</em>, turning sacred imagination into meaningful, handcrafted wall art for your sanctuary.
				</p>
				
				<div class="hero-cta-group">
					<a href="<?php echo esc_url( home_url( '/canvas-paintings-home-decor/' ) ); ?>" class="evara-btn btn-primary btn-lg">
						<span>Explore Canvas Paintings</span>
						<i class="fa-solid fa-arrow-right"></i>
					</a>
					<a href="<?php echo esc_url( home_url( '/lippan-art-home-decor/' ) ); ?>" class="evara-btn btn-outline btn-lg">
						<span>Explore Lippan Art</span>
					</a>
				</div>

				<div class="hero-stats-row">
					<div class="stat-item">
						<span class="stat-number">100%</span>
						<span class="stat-label">Handmade</span>
					</div>
					<div class="stat-divider"></div>
					<div class="stat-item">
						<span class="stat-number">500+</span>
						<span class="stat-label">Homes Adorned</span>
					</div>
					<div class="stat-divider"></div>
					<div class="stat-item">
						<span class="stat-number">4.9 ★</span>
						<span class="stat-label">Collector Love</span>
					</div>
				</div>
			</div>

			<!-- Featured Artwork Showcase Card (Properly spaced, never overlaps) -->
			<div class="hero-art-showcase-card">
				<a href="<?php echo esc_url( home_url( '/product/divine-melody-krishna-meera-canvas-painting/' ) ); ?>" class="showcase-card-link">
					<div class="showcase-thumb-wrap">
						<img src="https://evaraart.in/wp-content/uploads/2026/04/12-500x500.jpg" alt="Eternal Devotion Meera & Krishna" class="showcase-thumb-img">
					</div>
					<div class="showcase-info-box">
						<span class="showcase-tag">✦ Featured Creation</span>
						<h3 class="showcase-title">ETERNAL DEVOTION: MEERA &amp; KRISHNA</h3>
						<div class="showcase-price-row">
							<span class="showcase-price">&#8377;3,499</span>
							<span class="showcase-cta">View Details <i class="fa-solid fa-arrow-right"></i></span>
						</div>
					</div>
				</a>
			</div>

		</div>

	</div>
</section>

<!-- 2. Artist Divine Philosophy Section (Enhanced with User's Exact Words) -->
<section class="evara-philosophy-section">
	<div class="evara-container">
		<div class="philosophy-card">
			<div class="philosophy-header">
				<span class="philosophy-badge">✦ The Sacred Heart of Evara</span>
				<h2 class="main-heading">“Evara” — A Gift of God, Painted From The Soul</h2>
				<p class="philosophy-quote-lead">
					&ldquo;Welcome to Evara Art – where creativity comes to life through every brushstroke. &lsquo;Evara&rsquo; means Gift of God and I believe the art I create is truly a blessing and a form of divine expression.&rdquo;
				</p>
			</div>

			<div class="philosophy-grid">
				<div class="philosophy-col">
					<div class="col-icon"><i class="fa-solid fa-paintbrush"></i></div>
					<h3 class="col-title">Crafted with Passion</h3>
					<p class="philosophy-desc">Each piece is crafted with passion, turning imagination into meaningful and beautiful artwork that honors divine patience.</p>
				</div>
				<div class="philosophy-col">
					<div class="col-icon"><i class="fa-solid fa-spa"></i></div>
					<h3 class="col-title">Emotion &amp; Inspiration</h3>
					<p class="philosophy-desc">My goal is to bring emotion, inspiration, and uniqueness into every space, creating a calm spiritual focal point for your home.</p>
				</div>
				<div class="philosophy-col">
					<div class="col-icon"><i class="fa-solid fa-heart"></i></div>
					<h3 class="col-title">Connecting with Your Soul</h3>
					<p class="philosophy-desc">Discover art that connects with your soul — timeless handmade canvas paintings and sacred Gujarat Lippan mirror craft.</p>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- 3. Trust Strip -->
<section class="evara-trust-strip">
	<div class="evara-container trust-grid">
		<div class="trust-item">
			<div class="trust-icon"><i class="fa-solid fa-hand-holding-heart"></i></div>
			<div class="trust-text">
				<h4>100% Original Handwork</h4>
				<p>Hand-painted brushstrokes on 380 GSM cotton duck canvas. Never mass prints.</p>
			</div>
		</div>
		<div class="trust-item">
			<div class="trust-icon"><i class="fa-solid fa-box-archive"></i></div>
			<div class="trust-text">
				<h4>Armored Wooden Crating</h4>
				<p>Foam-padded edge protectors and wooden hardboard crating for 0% transit damage.</p>
			</div>
		</div>
		<div class="trust-item">
			<div class="trust-icon"><i class="fa-solid fa-palette"></i></div>
			<div class="trust-text">
				<h4>Custom Wall Sizing</h4>
				<p>Tailored canvas dimensions and palettes to harmonize with your living space.</p>
			</div>
		</div>
		<div class="trust-item">
			<div class="trust-icon"><i class="fa-solid fa-truck-fast"></i></div>
			<div class="trust-text">
				<h4>Insured Pan-India Delivery</h4>
				<p>Safe doorstep delivery across all Indian pin codes with live dispatch tracking.</p>
			</div>
		</div>
	</div>
</section>

<!-- 4. Curated Collections -->
<section class="evara-collections-section">
	<div class="evara-container">
		<div class="section-header text-center">
			<span class="sub-heading">Curated Portfolios</span>
			<h2 class="main-heading">Explore Signature Collections</h2>
			<p class="section-desc">Select a collection below to browse original creations made to illuminate your home.</p>
		</div>

		<div class="collections-grid">
			
			<!-- Canvas Paintings -->
			<div class="collection-card">
				<a href="<?php echo esc_url( home_url( '/canvas-paintings-home-decor/' ) ); ?>" class="collection-link">
					<div class="collection-img-wrap">
						<img src="https://evaraart.in/wp-content/uploads/2026/04/10-500x500.jpg" alt="Canvas Paintings Home Decor">
						<div class="collection-overlay"></div>
					</div>
					<div class="collection-info">
						<span class="col-count">11 Original Works</span>
						<h3 class="col-title">Canvas Paintings &bull; Home Decor</h3>
						<p class="col-desc">Krishna, Shiv Shakti, Ganesha, and serene modern abstract portraits.</p>
						<span class="col-action">Explore Collection <i class="fa-solid fa-arrow-right"></i></span>
					</div>
				</a>
			</div>

			<!-- Lippan Art -->
			<div class="collection-card">
				<a href="<?php echo esc_url( home_url( '/lippan-art-home-decor/' ) ); ?>" class="collection-link">
					<div class="collection-img-wrap">
						<img src="https://evaraart.in/wp-content/uploads/2026/06/Annapakshi-Lippan-Wall-Hanging-Set-of-2-01-500x500.jpg" alt="Lippan Art Home Decor">
						<div class="collection-overlay"></div>
					</div>
					<div class="collection-info">
						<span class="col-count">10 Handcrafted Pieces</span>
						<h3 class="col-title">Lippan Art &bull; Home Decor</h3>
						<p class="col-desc">Intricate clay dough patterns and sparkling hand-cut mirrors for auspicious walls.</p>
						<span class="col-action">Explore Collection <i class="fa-solid fa-arrow-right"></i></span>
					</div>
				</a>
			</div>

		</div>
	</div>
</section>

<!-- 5. Featured Artworks Grid (Clean, Fluid & Non-Clipping) -->
<section class="evara-artworks-section">
	<div class="evara-container">
		
		<div class="artworks-header-flex">
			<div>
				<span class="sub-heading">Masterpiece Gallery</span>
				<h2 class="main-heading">Featured Creations</h2>
			</div>
			<a href="<?php echo esc_url( home_url( '/canvas-paintings-home-decor/' ) ); ?>" class="view-all-link">
				<span>View Full Catalog</span>
				<i class="fa-solid fa-arrow-right"></i>
			</a>
		</div>

		<div class="artworks-grid">
			<?php
			if ( class_exists( 'WooCommerce' ) ) :
				$args = array(
					'post_type'      => 'product',
					'posts_per_page' => 8,
					'post_status'    => 'publish',
				);
				$loop = new WP_Query( $args );
				if ( $loop->have_posts() ) :
					while ( $loop->have_posts() ) : $loop->the_post();
						global $product;
						?>
						<div class="artwork-card">
							<div class="art-thumbnail-wrap">
								<a href="<?php the_permalink(); ?>">
									<?php
									if ( has_post_thumbnail() ) {
										the_post_thumbnail( 'woocommerce_thumbnail', array( 'class' => 'art-img-primary' ) );
									} else {
										echo '<img src="https://evaraart.in/wp-content/uploads/2026/04/11-500x500.jpg" alt="' . esc_attr( get_the_title() ) . '" class="art-img-primary">';
									}
									?>
								</a>
								<span class="art-badge">Handmade</span>
								<div class="art-quick-actions">
									<a href="<?php the_permalink(); ?>" class="quick-view-btn">
										<i class="fa-regular fa-eye"></i> View Details
									</a>
								</div>
							</div>
							<div class="art-card-details">
								<h3 class="art-title">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h3>
								<div class="art-price-row">
									<div class="price-val"><?php echo $product->get_price_html(); ?></div>
									<a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" 
									   data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" 
									   data-quantity="1" 
									   class="add-to-bag-btn ajax_add_to_cart add_to_cart_button">
										<i class="fa-solid fa-plus"></i> Add
									</a>
								</div>
							</div>
						</div>
						<?php
					endwhile;
					wp_reset_postdata();
				endif;
			endif;
			?>
		</div>

	</div>
</section>

<?php get_footer(); ?>
