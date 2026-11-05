<?php
/**
 * The Front Page template for Evara Art (Fluid, Enhanced & Non-Clipping)
 *
 * @package EvaraArt
 * @version 1.2.0
 */

get_header(); ?>

<!-- 1. Hero Section with Authentic Background Artwork -->
<section class="evara-hero-section">
	<div class="evara-container">
		
		<!-- Visual Artwork Banner -->
		<div class="hero-banner-card">
			<img src="<?php echo esc_url( EVARA_URI . '/assets/images/hero-banner-krishna.png' ); ?>" 
			     alt="<?php esc_attr_e( 'Art that connects to your heart - Evara Art', 'evara-art' ); ?>" 
			     class="hero-banner-img"
			     width="1024" 
			     height="448" 
			     fetchpriority="high">
		</div>

		<!-- Fluid Editorial Intro (No Text Clipping) -->
		<div class="hero-editorial-wrap">
			
			<div class="hero-text-content">
				<div class="hero-badge">
					<span class="badge-dot"></span>
					<span><?php esc_html_e( 'Handmade in India • Bespoke Fine Art', 'evara-art' ); ?></span>
				</div>
				
				<h1 class="hero-title">
					Art that <span class="italic-serif">connects</span> to your <span class="highlight-text">Heart.</span>
				</h1>
				
				<p class="hero-subtitle">
					Welcome to <strong>Evara Art</strong> – where creativity comes to life through every brushstroke. “Evara” means <em>Gift of God</em>, turning sacred imagination into meaningful, handcrafted wall art for your sanctuary.
				</p>
				
				<div class="hero-cta-group">
					<a href="<?php echo esc_url( home_url( '/canvas-paintings-home-decor/' ) ); ?>" class="evara-btn btn-primary btn-lg">
						<span><?php esc_html_e( 'Explore Canvas Paintings', 'evara-art' ); ?></span>
						<i class="fa-solid fa-arrow-right"></i>
					</a>
					<a href="<?php echo esc_url( home_url( '/lippan-art-home-decor/' ) ); ?>" class="evara-btn btn-outline btn-lg">
						<span><?php esc_html_e( 'Explore Lippan Art', 'evara-art' ); ?></span>
					</a>
				</div>

				<div class="hero-stats-row">
					<div class="stat-item">
						<span class="stat-number">100%</span>
						<span class="stat-label"><?php esc_html_e( 'Handmade', 'evara-art' ); ?></span>
					</div>
					<div class="stat-divider"></div>
					<div class="stat-item">
						<span class="stat-number">Pan-India</span>
						<span class="stat-label"><?php esc_html_e( 'Collectors & Homes', 'evara-art' ); ?></span>
					</div>
					<div class="stat-divider"></div>
					<div class="stat-item">
						<span class="stat-number">5-Star</span>
						<span class="stat-label"><?php esc_html_e( 'Craftsmanship', 'evara-art' ); ?></span>
					</div>
				</div>
			</div>

			<!-- Featured Artwork Showcase Card (Dynamic from WooCommerce) -->
			<?php
			$featured_product = null;
			if ( class_exists( 'WooCommerce' ) ) {
				$featured_query = wc_get_products( array(
					'status'   => 'publish',
					'limit'    => 1,
					'featured' => true,
				) );
				if ( ! empty( $featured_query ) ) {
					$featured_product = $featured_query[0];
				} else {
					$recent_query = wc_get_products( array(
						'status'  => 'publish',
						'limit'   => 1,
						'orderby' => 'date',
						'order'   => 'DESC',
					) );
					if ( ! empty( $recent_query ) ) {
						$featured_product = $recent_query[0];
					}
				}
			}
			?>
			<div class="hero-art-showcase-card">
				<?php if ( $featured_product ) : ?>
					<a href="<?php echo esc_url( $featured_product->get_permalink() ); ?>" class="showcase-card-link">
						<div class="showcase-thumb-wrap">
							<?php echo $featured_product->get_image( 'woocommerce_thumbnail', array( 'class' => 'showcase-thumb-img', 'width' => 300, 'height' => 300 ) ); ?>
						</div>
						<div class="showcase-info-box">
							<span class="showcase-tag">✦ <?php esc_html_e( 'Featured Creation', 'evara-art' ); ?></span>
							<h3 class="showcase-title"><?php echo esc_html( $featured_product->get_name() ); ?></h3>
							<div class="showcase-price-row">
								<span class="showcase-price"><?php echo $featured_product->get_price_html(); ?></span>
								<span class="showcase-cta"><?php esc_html_e( 'View Details', 'evara-art' ); ?> <i class="fa-solid fa-arrow-right"></i></span>
							</div>
						</div>
					</a>
				<?php else : ?>
					<a href="<?php echo esc_url( home_url( '/canvas-paintings-home-decor/' ) ); ?>" class="showcase-card-link">
						<div class="showcase-thumb-wrap">
							<img src="<?php echo esc_url( EVARA_URI . '/assets/images/hero-banner-krishna.png' ); ?>" alt="<?php esc_attr_e( 'Evara Featured Art', 'evara-art' ); ?>" class="showcase-thumb-img" width="300" height="300">
						</div>
						<div class="showcase-info-box">
							<span class="showcase-tag">✦ <?php esc_html_e( 'Original Artwork', 'evara-art' ); ?></span>
							<h3 class="showcase-title"><?php esc_html_e( 'Sacred Spiritual Artworks', 'evara-art' ); ?></h3>
							<div class="showcase-price-row">
								<span class="showcase-cta"><?php esc_html_e( 'Explore Collection', 'evara-art' ); ?> <i class="fa-solid fa-arrow-right"></i></span>
							</div>
						</div>
					</a>
				<?php endif; ?>
			</div>

		</div>

	</div>
</section>

<!-- 2. Artist Divine Philosophy Section -->
<section class="evara-philosophy-section reveal-on-scroll">
	<div class="evara-container">
		<div class="philosophy-card">
			<div class="philosophy-header">
				<span class="philosophy-badge">✦ <?php esc_html_e( 'The Sacred Heart of Evara', 'evara-art' ); ?></span>
				<h2 class="main-heading">“Evara” — A Gift of God, Painted From The Soul</h2>
				<p class="philosophy-quote-lead">
					&ldquo;Welcome to Evara Art – where creativity comes to life through every brushstroke. &lsquo;Evara&rsquo; means Gift of God and I believe the art I create is truly a blessing and a form of divine expression.&rdquo;
				</p>
			</div>

			<div class="philosophy-grid">
				<div class="philosophy-col">
					<div class="col-icon"><i class="fa-solid fa-paintbrush"></i></div>
					<h3 class="col-title"><?php esc_html_e( 'Crafted with Passion', 'evara-art' ); ?></h3>
					<p class="philosophy-desc"><?php esc_html_e( 'Each piece is crafted with passion, turning imagination into meaningful and beautiful artwork that honors divine patience.', 'evara-art' ); ?></p>
				</div>
				<div class="philosophy-col">
					<div class="col-icon"><i class="fa-solid fa-spa"></i></div>
					<h3 class="col-title"><?php esc_html_e( 'Emotion & Inspiration', 'evara-art' ); ?></h3>
					<p class="philosophy-desc"><?php esc_html_e( 'My goal is to bring emotion, inspiration, and uniqueness into every space, creating a calm spiritual focal point for your home.', 'evara-art' ); ?></p>
				</div>
				<div class="philosophy-col">
					<div class="col-icon"><i class="fa-solid fa-heart"></i></div>
					<h3 class="col-title"><?php esc_html_e( 'Connecting with Your Soul', 'evara-art' ); ?></h3>
					<p class="philosophy-desc"><?php esc_html_e( 'Discover art that connects with your soul — timeless handmade canvas paintings and sacred Gujarat Lippan mirror craft.', 'evara-art' ); ?></p>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- 3. Trust Strip -->
<section class="evara-trust-strip reveal-on-scroll">
	<div class="evara-container trust-grid">
		<div class="trust-item">
			<div class="trust-icon"><i class="fa-solid fa-hand-holding-heart"></i></div>
			<div class="trust-text">
				<h4><?php esc_html_e( '100% Original Handwork', 'evara-art' ); ?></h4>
				<p><?php esc_html_e( 'Hand-painted brushstrokes on 380 GSM cotton duck canvas. Never mass machine prints.', 'evara-art' ); ?></p>
			</div>
		</div>
		<div class="trust-item">
			<div class="trust-icon"><i class="fa-solid fa-box-archive"></i></div>
			<div class="trust-text">
				<h4><?php esc_html_e( 'Reinforced Hardboard Crating', 'evara-art' ); ?></h4>
				<p><?php esc_html_e( 'Foam-padded edge protectors and wooden hardboard crating for damage-free transit.', 'evara-art' ); ?></p>
			</div>
		</div>
		<div class="trust-item">
			<div class="trust-icon"><i class="fa-solid fa-palette"></i></div>
			<div class="trust-text">
				<h4><?php esc_html_e( 'Custom Wall Sizing', 'evara-art' ); ?></h4>
				<p><?php esc_html_e( 'Tailored canvas dimensions and palettes to harmonize with your living space.', 'evara-art' ); ?></p>
			</div>
		</div>
		<div class="trust-item">
			<div class="trust-icon"><i class="fa-solid fa-truck-fast"></i></div>
			<div class="trust-text">
				<h4><?php esc_html_e( 'Insured Pan-India Delivery', 'evara-art' ); ?></h4>
				<p><?php esc_html_e( 'Safe doorstep delivery across all Indian pin codes with live dispatch tracking.', 'evara-art' ); ?></p>
			</div>
		</div>
	</div>
</section>

<!-- 4. Curated Collections -->
<?php
$canvas_cat = get_term_by( 'slug', 'canvas-paintings-home-decor', 'product_cat' );
if ( ! $canvas_cat ) {
	$canvas_cat = get_term_by( 'slug', 'canvas-paintings', 'product_cat' );
}
$lippan_cat = get_term_by( 'slug', 'lippan-art-home-decor', 'product_cat' );
if ( ! $lippan_cat ) {
	$lippan_cat = get_term_by( 'slug', 'lippan-art', 'product_cat' );
}

$canvas_count_str = ( $canvas_cat && $canvas_cat->count > 0 ) ? sprintf( esc_html__( '%d Original Works', 'evara-art' ), $canvas_cat->count ) : esc_html__( 'Original Works', 'evara-art' );
$lippan_count_str = ( $lippan_cat && $lippan_cat->count > 0 ) ? sprintf( esc_html__( '%d Handcrafted Pieces', 'evara-art' ), $lippan_cat->count ) : esc_html__( 'Handcrafted Pieces', 'evara-art' );

$canvas_img_id = $canvas_cat ? get_term_meta( $canvas_cat->term_id, 'thumbnail_id', true ) : 0;
$canvas_img_url = $canvas_img_id ? wp_get_attachment_image_url( $canvas_img_id, 'large' ) : EVARA_URI . '/assets/images/hero-banner-krishna.png';

$lippan_img_id = $lippan_cat ? get_term_meta( $lippan_cat->term_id, 'thumbnail_id', true ) : 0;
$lippan_img_url = $lippan_img_id ? wp_get_attachment_image_url( $lippan_img_id, 'large' ) : EVARA_URI . '/assets/images/hero-banner-krishna.png';
?>
<section class="evara-collections-section reveal-on-scroll">
	<div class="evara-container">
		<div class="section-header text-center">
			<span class="sub-heading"><?php esc_html_e( 'Curated Portfolios', 'evara-art' ); ?></span>
			<h2 class="main-heading"><?php esc_html_e( 'Explore Signature Collections', 'evara-art' ); ?></h2>
			<p class="section-desc"><?php esc_html_e( 'Select a collection below to browse original creations made to illuminate your home.', 'evara-art' ); ?></p>
		</div>

		<div class="collections-grid">
			
			<!-- Canvas Paintings -->
			<div class="collection-card">
				<a href="<?php echo esc_url( home_url( '/canvas-paintings-home-decor/' ) ); ?>" class="collection-link">
					<div class="collection-img-wrap">
						<img src="<?php echo esc_url( $canvas_img_url ); ?>" alt="<?php esc_attr_e( 'Canvas Paintings Home Decor', 'evara-art' ); ?>" width="600" height="400">
						<div class="collection-overlay"></div>
					</div>
					<div class="collection-info">
						<span class="col-count"><?php echo esc_html( $canvas_count_str ); ?></span>
						<h3 class="col-title"><?php esc_html_e( 'Canvas Paintings • Home Decor', 'evara-art' ); ?></h3>
						<p class="col-desc"><?php esc_html_e( 'Krishna, Shiv Shakti, Ganesha, and serene modern abstract portraits.', 'evara-art' ); ?></p>
						<span class="col-action"><?php esc_html_e( 'Explore Collection', 'evara-art' ); ?> <i class="fa-solid fa-arrow-right"></i></span>
					</div>
				</a>
			</div>

			<!-- Lippan Art -->
			<div class="collection-card">
				<a href="<?php echo esc_url( home_url( '/lippan-art-home-decor/' ) ); ?>" class="collection-link">
					<div class="collection-img-wrap">
						<img src="<?php echo esc_url( $lippan_img_url ); ?>" alt="<?php esc_attr_e( 'Lippan Art Home Decor', 'evara-art' ); ?>" width="600" height="400">
						<div class="collection-overlay"></div>
					</div>
					<div class="collection-info">
						<span class="col-count"><?php echo esc_html( $lippan_count_str ); ?></span>
						<h3 class="col-title"><?php esc_html_e( 'Lippan Art • Home Decor', 'evara-art' ); ?></h3>
						<p class="col-desc"><?php esc_html_e( 'Intricate clay dough patterns and sparkling hand-cut mirrors for auspicious walls.', 'evara-art' ); ?></p>
						<span class="col-action"><?php esc_html_e( 'Explore Collection', 'evara-art' ); ?> <i class="fa-solid fa-arrow-right"></i></span>
					</div>
				</a>
			</div>

		</div>
	</div>
</section>

<!-- 5. Featured Artworks Grid (Clean, Fluid & Non-Clipping) -->
<section class="evara-artworks-section reveal-on-scroll">
	<div class="evara-container">
		
		<div class="artworks-header-flex">
			<div>
				<span class="sub-heading"><?php esc_html_e( 'Masterpiece Gallery', 'evara-art' ); ?></span>
				<h2 class="main-heading"><?php esc_html_e( 'Featured Creations', 'evara-art' ); ?></h2>
			</div>
			<a href="<?php echo esc_url( home_url( '/canvas-paintings-home-decor/' ) ); ?>" class="view-all-link">
				<span><?php esc_html_e( 'View Full Catalog', 'evara-art' ); ?></span>
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
					$ajax_enabled = ( 'yes' === get_option( 'woocommerce_enable_ajax_add_to_cart' ) );
					while ( $loop->have_posts() ) : $loop->the_post();
						global $product;
						if ( ! $product ) {
							continue;
						}
						?>
						<div class="artwork-card">
							<div class="art-thumbnail-wrap">
								<a href="<?php the_permalink(); ?>">
									<?php
									if ( has_post_thumbnail() ) {
										the_post_thumbnail( 'woocommerce_thumbnail', array( 'class' => 'art-img-primary', 'width' => 450, 'height' => 450 ) );
									} else {
										echo '<img src="' . esc_url( EVARA_URI . '/assets/images/hero-banner-krishna.png' ) . '" alt="' . esc_attr( get_the_title() ) . '" class="art-img-primary" width="450" height="450">';
									}
									?>
								</a>
							</div>
							<div class="art-card-details">
								<h3 class="art-title">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h3>
								<div class="art-price-row">
									<div class="price-val"><?php echo $product->get_price_html(); ?></div>
									<?php if ( ! $product->is_in_stock() ) : ?>
										<a href="<?php the_permalink(); ?>" class="add-to-bag-btn btn-out-of-stock">
											<?php esc_html_e( 'Out of Stock', 'evara-art' ); ?>
										</a>
									<?php elseif ( $product->is_type( 'simple' ) && $product->is_purchasable() ) : ?>
										<a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" 
										   data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" 
										   data-quantity="1" 
										   class="add-to-bag-btn add_to_cart_button <?php echo $ajax_enabled ? 'ajax_add_to_cart' : ''; ?>">
											<i class="fa-solid fa-plus"></i> <?php esc_html_e( 'Add', 'evara-art' ); ?>
										</a>
									<?php else : ?>
										<a href="<?php the_permalink(); ?>" class="add-to-bag-btn">
											<i class="fa-solid fa-eye"></i> <?php esc_html_e( 'Options', 'evara-art' ); ?>
										</a>
									<?php endif; ?>
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
