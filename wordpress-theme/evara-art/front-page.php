<?php
/**
 * The Front Page template for Evara Art (Auto 4-Slide Banner, Editorial & Reordered Sections)
 *
 * @package EvaraArt
 * @version 1.3.8
 */

get_header(); ?>

<?php
// Resolve category URLs dynamically
$canvas_url = evara_get_category_url( array( 'canvas-painting-evara-art', 'canvas-paintings-home-decor', 'canvas-paintings' ), '/product-category/canvas-painting-evara-art/' );
$lippan_url = evara_get_category_url( array( 'clay-art-evara-art', 'lippan-art-home-decor', 'lippan-art' ), '/product-category/clay-art-evara-art/' );

// Slider Timing (2-3 seconds configurable) & Captions
$slider_interval = (int) get_theme_mod( 'evara_hero_slider_interval', 2500 );
if ( $slider_interval < 1000 ) {
	$slider_interval = 2500;
}
$show_caption = ( 'no' !== get_theme_mod( 'evara_slider_show_caption', 'yes' ) );

// 1. Prepare Banners for Sliding Carousel (Supports 1-4 custom uploaded banners with custom text)
$custom_slides = array();
$legacy_banner = get_theme_mod( 'evara_custom_banner_image', '' );

for ( $s = 1; $s <= 4; $s++ ) {
	$s_img = get_theme_mod( "evara_slide{$s}_image", '' );
	if ( 1 === $s && empty( $s_img ) && ! empty( $legacy_banner ) ) {
		$s_img = $legacy_banner;
	}
	if ( ! empty( $s_img ) ) {
		$s_mobile_img = get_theme_mod( "evara_slide{$s}_mobile_image", '' );
		$s_title = get_theme_mod( "evara_slide{$s}_title", '' );
		if ( preg_match( '/\bheart\b/i', $s_title ) && ! preg_match( '/\bHEART\b/', $s_title ) ) {
			$s_title = preg_replace( '/\bheart\b/i', 'HEART', $s_title );
		}
		$s_badge = get_theme_mod( "evara_slide{$s}_badge", '✦ Evara Art Masterpiece' );
		$s_url   = get_theme_mod( "evara_slide{$s}_url", '' );
		$custom_slides[] = array(
			'image'        => $s_img,
			'mobile_image' => $s_mobile_img,
			'title'        => ! empty( $s_title ) ? $s_title : get_bloginfo( 'name' ),
			'badge'        => $s_badge,
			'url'          => ! empty( $s_url ) ? $s_url : $canvas_url,
		);
	}
}

$slides = array();
if ( ! empty( $custom_slides ) ) {
	$slides = $custom_slides;
} else {
	// Auto-fetch from WooCommerce featured/latest artworks
	if ( class_exists( 'WooCommerce' ) ) {
		$slider_products = wc_get_products( array(
			'status'   => 'publish',
			'limit'    => 4,
			'featured' => true,
		) );
		if ( count( $slider_products ) < 4 ) {
			$more_prods = wc_get_products( array(
				'status'  => 'publish',
				'limit'   => 4 - count( $slider_products ),
				'orderby' => 'date',
				'order'   => 'DESC',
				'exclude' => ! empty( $slider_products ) ? wp_list_pluck( $slider_products, 'id' ) : array(),
			) );
			$slider_products = array_merge( $slider_products, $more_prods );
		}
		foreach ( $slider_products as $p ) {
			if ( has_post_thumbnail( $p->get_id() ) ) {
				$slides[] = array(
					'image' => get_the_post_thumbnail_url( $p->get_id(), 'full' ),
					'title' => $p->get_name(),
					'badge' => '✦ Original Canvas Artwork',
					'url'   => get_permalink( $p->get_id() ),
				);
			}
		}
	}

	$fallback_data = array(
		array( 'image' => EVARA_URI . '/assets/images/hero-banner-krishna.png', 'title' => 'Art that connects to your HEART', 'badge' => '✦ Original Canvas Artwork', 'url' => $canvas_url ),
		array( 'image' => 'https://evaraart.in/wp-content/uploads/2026/07/18.jpg', 'title' => 'Divine Darshan of Lord Jagannath', 'badge' => '✦ Sacred Spiritual Art', 'url' => $canvas_url ),
		array( 'image' => 'https://evaraart.in/wp-content/uploads/2026/04/10.jpg', 'title' => 'Sacred Serenity Shiva Parvati', 'badge' => '✦ Divine Harmony', 'url' => $canvas_url ),
		array( 'image' => 'https://evaraart.in/wp-content/uploads/2026/06/Annapakshi-Lippan-Wall-Hanging-Set-of-2-01.jpg', 'title' => 'Annapakshi Lippan Craft', 'badge' => '✦ Heritage Craft', 'url' => $lippan_url ),
	);

	$f_idx = 0;
	while ( count( $slides ) < 4 ) {
		$slides[] = $fallback_data[ $f_idx % 4 ];
		$f_idx++;
	}
}

// Customizer text options
$hero_badge     = get_theme_mod( 'evara_hero_badge', 'Handmade in India • Bespoke Fine Art' );
$hero_title     = get_theme_mod( 'evara_hero_title', 'Art that connects to your HEART.' );
if ( preg_match( '/\bheart\b/i', $hero_title ) && ! preg_match( '/\bHEART\b/', $hero_title ) ) {
	$hero_title = preg_replace( '/\bheart\b/i', 'HEART', $hero_title );
}
$hero_subtitle  = get_theme_mod( 'evara_hero_subtitle', 'Welcome to Evara Art – where creativity comes to life through every brushstroke. “Evara” means Gift of God, turning sacred imagination into meaningful, handcrafted wall art for your sanctuary.' );

$hero_cta1_text = get_theme_mod( 'evara_hero_cta1_text', 'Explore Canvas Paintings' );
$hero_cta1_url  = get_theme_mod( 'evara_hero_cta1_url', '' );

$hero_cta2_text = get_theme_mod( 'evara_hero_cta2_text', 'Explore Lippan Art' );
$hero_cta2_url  = get_theme_mod( 'evara_hero_cta2_url', '' );

$stat1_num   = get_theme_mod( 'evara_stat1_number', '100%' );
$stat1_label = get_theme_mod( 'evara_stat1_label', 'Handmade' );
$stat2_num   = get_theme_mod( 'evara_stat2_number', 'Pan-India' );
$stat2_label = get_theme_mod( 'evara_stat2_label', 'Collectors & Homes' );
$stat3_num   = get_theme_mod( 'evara_stat3_number', '5-Star' );
$stat3_label = get_theme_mod( 'evara_stat3_label', 'Craftsmanship' );

$phil_badge  = get_theme_mod( 'evara_phil_badge', '✦ The Sacred Heart of Evara' );
$phil_title  = get_theme_mod( 'evara_phil_title', '“Evara” — A Gift of God, Painted From The Soul' );
$phil_quote  = get_theme_mod( 'evara_phil_quote', '“Welcome to Evara Art – where creativity comes to life through every brushstroke. ‘Evara’ means Gift of God and I believe the art I create is truly a blessing and a form of divine expression.”' );

$pillar1_title = get_theme_mod( 'evara_pillar1_title', 'Crafted with Passion' );
$pillar1_desc  = get_theme_mod( 'evara_pillar1_desc', 'Each piece is crafted with passion, turning imagination into meaningful and beautiful artwork that honors divine patience.' );
$pillar2_title = get_theme_mod( 'evara_pillar2_title', 'Emotion & Inspiration' );
$pillar2_desc  = get_theme_mod( 'evara_pillar2_desc', 'My goal is to bring emotion, inspiration, and uniqueness into every space, creating a calm spiritual focal point for your home.' );
$pillar3_title = get_theme_mod( 'evara_pillar3_title', 'Connecting with Your Soul' );
$pillar3_desc  = get_theme_mod( 'evara_pillar3_desc', 'Discover art that connects with your soul — timeless handmade canvas paintings and sacred Gujarat Lippan mirror craft.' );
?>

<!-- 1. Hero Section with 4 Auto-Sliding Banners (Every 1s) & Fluid Editorial Intro -->
<section class="evara-hero-section">
	<div class="evara-container">
		
		<!-- Automatic Sliding Banners (Configurable 2-3s Timing & Custom Text) -->
		<div class="evara-hero-slider-wrap">
			<div class="evara-hero-slider" id="hero-slider" data-interval="<?php echo esc_attr( $slider_interval ); ?>">
				<div class="slider-track" id="slider-track">
					<?php foreach ( $slides as $index => $slide ) : ?>
						<div class="slider-slide <?php echo ( 0 === $index ) ? 'is-active' : ''; ?>" data-index="<?php echo esc_attr( $index ); ?>">
							<a href="<?php echo esc_url( $slide['url'] ); ?>" class="slider-card-link">
								<picture class="hero-slider-picture">
									<?php if ( ! empty( $slide['mobile_image'] ) ) : ?>
										<source media="(max-width: 768px)" srcset="<?php echo esc_url( $slide['mobile_image'] ); ?>">
									<?php endif; ?>
									<img src="<?php echo esc_url( $slide['image'] ); ?>" 
									     alt="<?php echo esc_attr( $slide['title'] ); ?>" 
									     class="hero-slider-img"
									     width="1400" 
									     height="520" 
									     <?php echo ( 0 === $index ) ? 'fetchpriority="high"' : 'loading="lazy"'; ?>>
								</picture>
								<?php if ( $show_caption && ! empty( $slide['title'] ) ) : ?>
									<div class="slider-caption">
										<?php if ( ! empty( $slide['badge'] ) ) : ?>
											<span class="slider-pill"><?php echo esc_html( $slide['badge'] ); ?></span>
										<?php endif; ?>
										<h3 class="slider-artwork-title"><?php echo esc_html( $slide['title'] ); ?></h3>
									</div>
								<?php endif; ?>
							</a>
						</div>
					<?php endforeach; ?>
				</div>

				<?php if ( count( $slides ) > 1 ) : ?>
					<!-- Navigation Dots -->
					<div class="slider-dots" id="slider-dots">
						<?php for ( $d = 0; $d < count( $slides ); $d++ ) : ?>
							<button type="button" class="slider-dot <?php echo ( 0 === $d ) ? 'active' : ''; ?>" data-slide="<?php echo esc_attr( $d ); ?>" aria-label="<?php printf( esc_attr__( 'Go to slide %d', 'evara-art' ), $d + 1 ); ?>"></button>
						<?php endfor; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<!-- Fluid Editorial Intro (No Text Clipping) -->
		<div class="hero-editorial-wrap">
			
			<div class="hero-text-content">
				<div class="hero-badge">
					<span class="badge-dot"></span>
					<span><?php echo esc_html( $hero_badge ); ?></span>
				</div>
				
				<h1 class="hero-title">
					<?php echo wp_kses_post( $hero_title ); ?>
				</h1>
				
				<p class="hero-subtitle">
					<?php echo wp_kses_post( $hero_subtitle ); ?>
				</p>
				
				<div class="hero-cta-group">
					<a href="<?php echo esc_url( ! empty( $hero_cta1_url ) ? $hero_cta1_url : $canvas_url ); ?>" class="evara-btn btn-primary btn-lg">
						<span><?php echo esc_html( $hero_cta1_text ); ?></span>
						<i class="fa-solid fa-arrow-right"></i>
					</a>
					<a href="<?php echo esc_url( ! empty( $hero_cta2_url ) ? $hero_cta2_url : $lippan_url ); ?>" class="evara-btn btn-outline btn-lg">
						<span><?php echo esc_html( $hero_cta2_text ); ?></span>
					</a>
				</div>

				<div class="hero-stats-row">
					<div class="stat-item">
						<span class="stat-number"><?php echo esc_html( $stat1_num ); ?></span>
						<span class="stat-label"><?php echo esc_html( $stat1_label ); ?></span>
					</div>
					<div class="stat-divider"></div>
					<div class="stat-item">
						<span class="stat-number"><?php echo esc_html( $stat2_num ); ?></span>
						<span class="stat-label"><?php echo esc_html( $stat2_label ); ?></span>
					</div>
					<div class="stat-divider"></div>
					<div class="stat-item">
						<span class="stat-number"><?php echo esc_html( $stat3_num ); ?></span>
						<span class="stat-label"><?php echo esc_html( $stat3_label ); ?></span>
					</div>
				</div>
			</div>

			<!-- Featured Artwork Showcase Card (Fully Configurable via WordPress Customizer) -->
			<?php
			$showcase_show  = ( 'no' !== get_theme_mod( 'evara_showcase_card_show', 'yes' ) );
			$showcase_img   = get_theme_mod( 'evara_showcase_card_image', '' );
			$showcase_badge = get_theme_mod( 'evara_showcase_card_badge', '✦ Original Artwork' );
			$showcase_title = get_theme_mod( 'evara_showcase_card_title', 'Sacred Spiritual Artworks' );
			$showcase_cta   = get_theme_mod( 'evara_showcase_card_cta', 'Explore Collection' );
			$showcase_url   = get_theme_mod( 'evara_showcase_card_url', '' );

			if ( empty( $showcase_url ) ) {
				$showcase_url = $canvas_url;
			}
			if ( empty( $showcase_img ) ) {
				if ( class_exists( 'WooCommerce' ) && ! empty( $slider_products ) && has_post_thumbnail( $slider_products[0]->get_id() ) ) {
					$showcase_img = get_the_post_thumbnail_url( $slider_products[0]->get_id(), 'medium_large' );
				} elseif ( ! empty( $slides[0]['image'] ) ) {
					$showcase_img = $slides[0]['image'];
				} else {
					$showcase_img = EVARA_URI . '/assets/images/hero-banner-krishna.png';
				}
			}
			?>
			<?php if ( $showcase_show ) : ?>
				<div class="hero-art-showcase-card">
					<a href="<?php echo esc_url( $showcase_url ); ?>" class="showcase-card-link">
						<div class="showcase-thumb-wrap">
							<img src="<?php echo esc_url( $showcase_img ); ?>" 
							     alt="<?php echo esc_attr( $showcase_title ); ?>" 
							     class="showcase-thumb-img" 
							     width="300" 
							     height="300" 
							     loading="lazy">
						</div>
						<div class="showcase-info-box">
							<span class="showcase-tag"><?php echo esc_html( $showcase_badge ); ?></span>
							<h3 class="showcase-title"><?php echo esc_html( $showcase_title ); ?></h3>
							<div class="showcase-price-row">
								<span class="showcase-cta"><?php echo esc_html( $showcase_cta ); ?> <i class="fa-solid fa-arrow-right"></i></span>
							</div>
						</div>
					</a>
				</div>
			<?php endif; ?>

		</div>
	</div>
</section>

<!-- 2. Artist Divine Philosophy Section (The Sacred Heart of Evara) -->
<?php
$clean_phil_quote = trim( $phil_quote );
$clean_phil_quote = preg_replace( '/^[“"\'”\s]+|[“"\'”\s]+$/u', '', $clean_phil_quote );
?>
<section class="evara-philosophy-section reveal-on-scroll" id="about">
	<div class="evara-container">
		<div class="philosophy-card">
			<div class="philosophy-header">
				<span class="philosophy-badge"><?php echo esc_html( $phil_badge ); ?></span>
				<h2 class="main-heading"><?php echo esc_html( $phil_title ); ?></h2>
				<p class="philosophy-quote-lead">
					&ldquo;<?php echo wp_kses_post( $clean_phil_quote ); ?>&rdquo;
				</p>
			</div>

			<div class="philosophy-grid">
				<div class="philosophy-col">
					<div class="col-icon"><i class="fa-solid fa-paintbrush"></i></div>
					<h3 class="col-title"><?php echo esc_html( $pillar1_title ); ?></h3>
					<p class="philosophy-desc"><?php echo esc_html( $pillar1_desc ); ?></p>
				</div>
				<div class="philosophy-col">
					<div class="col-icon"><i class="fa-solid fa-spa"></i></div>
					<h3 class="col-title"><?php echo esc_html( $pillar2_title ); ?></h3>
					<p class="philosophy-desc"><?php echo esc_html( $pillar2_desc ); ?></p>
				</div>
				<div class="philosophy-col">
					<div class="col-icon"><i class="fa-solid fa-heart"></i></div>
					<h3 class="col-title"><?php echo esc_html( $pillar3_title ); ?></h3>
					<p class="philosophy-desc"><?php echo esc_html( $pillar3_desc ); ?></p>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- 3. Curated Signature Collections (Canvas Paintings & Lippan Art - Curated Portfolios) -->
<?php
$canvas_cat = get_term_by( 'slug', 'canvas-painting-evara-art', 'product_cat' );
if ( ! $canvas_cat ) {
	$canvas_cat = get_term_by( 'slug', 'canvas-paintings-home-decor', 'product_cat' );
}
$lippan_cat = get_term_by( 'slug', 'clay-art-evara-art', 'product_cat' );
if ( ! $lippan_cat ) {
	$lippan_cat = get_term_by( 'slug', 'lippan-art-home-decor', 'product_cat' );
}

$canvas_count_str = ( $canvas_cat && $canvas_cat->count > 0 ) ? sprintf( esc_html__( '%d Original Works', 'evara-art' ), $canvas_cat->count ) : esc_html__( '11 Original Works', 'evara-art' );
$lippan_count_str = ( $lippan_cat && $lippan_cat->count > 0 ) ? sprintf( esc_html__( '%d Handcrafted Pieces', 'evara-art' ), $lippan_cat->count ) : esc_html__( '10 Handcrafted Pieces', 'evara-art' );

// Customizer options for Signature Collections (Customizer editable images & titles)
$canvas_custom_img   = get_theme_mod( 'evara_canvas_collection_img', '' );
$canvas_custom_title = get_theme_mod( 'evara_canvas_collection_title', 'Canvas Paintings • Home Decor' );
$canvas_custom_desc  = get_theme_mod( 'evara_canvas_collection_desc', 'Krishna, Shiv Shakti, Ganesha, and serene modern abstract portraits.' );

$lippan_custom_img   = get_theme_mod( 'evara_lippan_collection_img', '' );
$lippan_custom_title = get_theme_mod( 'evara_lippan_collection_title', 'Lippan Art • Home Decor' );
$lippan_custom_desc  = get_theme_mod( 'evara_lippan_collection_desc', 'Intricate clay dough patterns and sparkling hand-cut mirrors for auspicious walls.' );

$canvas_img_url = ! empty( $canvas_custom_img ) ? $canvas_custom_img : 'https://evaraart.in/wp-content/uploads/2026/04/10-500x500.jpg';
$lippan_img_url = ! empty( $lippan_custom_img ) ? $lippan_custom_img : 'https://evaraart.in/wp-content/uploads/2026/06/Annapakshi-Lippan-Wall-Hanging-Set-of-2-01-500x500.jpg';
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
				<a href="<?php echo esc_url( $canvas_url ); ?>" class="collection-link">
					<div class="collection-img-wrap">
						<img src="<?php echo esc_url( $canvas_img_url ); ?>" alt="<?php echo esc_attr( $canvas_custom_title ); ?>" width="600" height="400">
						<div class="collection-overlay"></div>
					</div>
					<div class="collection-info">
						<span class="col-count"><?php echo esc_html( $canvas_count_str ); ?></span>
						<h3 class="col-title"><?php echo esc_html( $canvas_custom_title ); ?></h3>
						<p class="col-desc"><?php echo esc_html( $canvas_custom_desc ); ?></p>
						<span class="col-action"><?php esc_html_e( 'Explore Collection', 'evara-art' ); ?> <i class="fa-solid fa-arrow-right"></i></span>
					</div>
				</a>
			</div>

			<!-- Lippan Art -->
			<div class="collection-card">
				<a href="<?php echo esc_url( $lippan_url ); ?>" class="collection-link">
					<div class="collection-img-wrap">
						<img src="<?php echo esc_url( $lippan_img_url ); ?>" alt="<?php echo esc_attr( $lippan_custom_title ); ?>" width="600" height="400">
						<div class="collection-overlay"></div>
					</div>
					<div class="collection-info">
						<span class="col-count"><?php echo esc_html( $lippan_count_str ); ?></span>
						<h3 class="col-title"><?php echo esc_html( $lippan_custom_title ); ?></h3>
						<p class="col-desc"><?php echo esc_html( $lippan_custom_desc ); ?></p>
						<span class="col-action"><?php esc_html_e( 'Explore Collection', 'evara-art' ); ?> <i class="fa-solid fa-arrow-right"></i></span>
					</div>
				</a>
			</div>

		</div>
	</div>
</section>

<!-- 3. Featured Masterpiece Artworks Grid (Products) -->
<section class="evara-artworks-section reveal-on-scroll">
	<div class="evara-container">
		
		<div class="artworks-header-flex">
			<div>
				<span class="sub-heading"><?php esc_html_e( 'Masterpiece Gallery', 'evara-art' ); ?></span>
				<h2 class="main-heading"><?php esc_html_e( 'Featured Creations', 'evara-art' ); ?></h2>
			</div>
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

		<!-- Dedicated View Full Catalogue / Explore All Categories Option (Placed after Featured Creations) -->
		<div class="artworks-footer-cta">
			<div class="catalog-cta-card">
				<div class="catalog-cta-content">
					<span class="catalog-cta-badge"><i class="fa-solid fa-shapes"></i> <?php esc_html_e( 'Curated Art Catalog', 'evara-art' ); ?></span>
					<h3 class="catalog-cta-title"><?php esc_html_e( 'Looking for more original artworks?', 'evara-art' ); ?></h3>
					<p class="catalog-cta-desc"><?php esc_html_e( 'Explore our complete collections by category — Canvas Paintings, Lippan Mirror Art, Animal Wildlife, Boho art, and Abstract expressions.', 'evara-art' ); ?></p>
				</div>
				<div class="catalog-cta-action">
					<a href="<?php echo esc_url( home_url( '/categories/' ) ); ?>" class="evara-btn evara-btn-primary catalog-cta-btn">
						<span><?php esc_html_e( 'View Full Catalogue & Categories', 'evara-art' ); ?></span>
						<i class="fa-solid fa-arrow-right"></i>
					</a>
				</div>
			</div>
		</div>

	</div>
</section>

<!-- 4. Trust Strip (PLACED AFTER PRODUCTS AS REQUESTED) -->
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

<?php get_footer(); ?>
