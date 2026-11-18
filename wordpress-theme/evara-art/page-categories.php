<?php
/**
 * Template Name: All Categories Hub
 *
 * Dedicated Hub Page displaying all curated art categories with direct selection.
 *
 * @package EvaraArt
 * @version 1.4.3
 */

get_header();

// Fetch URLs for known categories
$canvas_url   = evara_get_category_url( array( 'canvas-painting-evara-art', 'canvas-paintings-home-decor', 'canvas-paintings' ), '/product-category/canvas-painting-evara-art/' );
$lippan_url   = evara_get_category_url( array( 'clay-art-evara-art', 'lippan-art-home-decor', 'lippan-art' ), '/product-category/clay-art-evara-art/' );
$animal_url   = evara_get_category_url( array( 'animal-painting', 'animal-paintings', 'animal-art' ), '/product-category/animal-painting/' );
$boho_url     = evara_get_category_url( array( 'boho-art', 'boho-paintings', 'boho' ), '/product-category/boho-art/' );
$abstract_url = evara_get_category_url( array( 'abstract-painting', 'abstract-paintings', 'abstract-art' ), '/product-category/abstract-painting/' );

// Helper to get product count and thumbnail for category
function evara_get_cat_meta_helper( $slug_variants, $fallback_img ) {
	$result = array(
		'count' => 0,
		'image' => $fallback_img,
	);
	if ( class_exists( 'WooCommerce' ) ) {
		foreach ( (array) $slug_variants as $slug ) {
			$term = get_term_by( 'slug', $slug, 'product_cat' );
			if ( $term && ! is_wp_error( $term ) ) {
				$result['count'] = $term->count;
				$thumb_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
				if ( $thumb_id ) {
					$img_url = wp_get_attachment_image_url( $thumb_id, 'large' );
					if ( $img_url ) {
						$result['image'] = $img_url;
					}
				}
				break;
			}
		}
	}
	return $result;
}

$canvas_meta   = evara_get_cat_meta_helper( array( 'canvas-painting-evara-art', 'canvas-paintings-home-decor', 'canvas-paintings' ), EVARA_URI . '/assets/images/hero-banner-krishna.png' );
$lippan_meta   = evara_get_cat_meta_helper( array( 'clay-art-evara-art', 'lippan-art-home-decor', 'lippan-art' ), 'https://evaraart.in/wp-content/uploads/2026/06/Annapakshi-Lippan-Wall-Hanging-Set-of-2-01.jpg' );
$animal_meta   = evara_get_cat_meta_helper( array( 'animal-painting', 'animal-paintings', 'animal-art' ), 'https://evaraart.in/wp-content/uploads/2026/04/10.jpg' );
$boho_meta     = evara_get_cat_meta_helper( array( 'boho-art', 'boho-paintings', 'boho' ), 'https://evaraart.in/wp-content/uploads/2026/07/18.jpg' );
$abstract_meta = evara_get_cat_meta_helper( array( 'abstract-painting', 'abstract-paintings', 'abstract-art' ), 'https://evaraart.in/wp-content/uploads/2026/07/18.jpg' );

$primary_categories = array(
	array(
		'title'       => __( 'Canvas Paintings', 'evara-art' ),
		'badge'       => __( '✦ Sacred & Fine Art', 'evara-art' ),
		'desc'        => __( 'Hand-painted Lord Krishna, Shiva Shakti, Ganesha, and figurative devotional masterpieces on 380 GSM textured cotton canvas.', 'evara-art' ),
		'url'         => $canvas_url,
		'image'       => $canvas_meta['image'],
		'count'       => $canvas_meta['count'],
	),
	array(
		'title'       => __( 'Lippan & Clay Art', 'evara-art' ),
		'badge'       => __( '✦ Heritage Kutch Craft', 'evara-art' ),
		'desc'        => __( 'Authentic Gujarat Lippan wall hangings hand-sculpted with natural clay mud-relief and shimmering geometric mirrors.', 'evara-art' ),
		'url'         => $lippan_url,
		'image'       => $lippan_meta['image'],
		'count'       => $lippan_meta['count'],
	),
	array(
		'title'       => __( 'Animal Painting', 'evara-art' ),
		'badge'       => __( '✦ Wildlife & Animals', 'evara-art' ),
		'desc'        => __( 'Soulful wildlife portraits, majestic equine studies, and animal fine art rendered with expressive texture and natural realism.', 'evara-art' ),
		'url'         => $animal_url,
		'image'       => $animal_meta['image'],
		'count'       => $animal_meta['count'],
	),
	array(
		'title'       => __( 'Boho Art', 'evara-art' ),
		'badge'       => __( '✦ Earth & Minimalist', 'evara-art' ),
		'desc'        => __( 'Warm earthy palettes, organic silhouettes, and modern bohemian textured artworks designed for contemporary sanctuary aesthetics.', 'evara-art' ),
		'url'         => $boho_url,
		'image'       => $boho_meta['image'],
		'count'       => $boho_meta['count'],
	),
	array(
		'title'       => __( 'Abstract Painting', 'evara-art' ),
		'badge'       => __( '✦ Modern & Expressive', 'evara-art' ),
		'desc'        => __( 'Bold contemporary abstract canvases, crimson bloom portraits, and textured expressions of deep aesthetic feeling.', 'evara-art' ),
		'url'         => $abstract_url,
		'image'       => $abstract_meta['image'],
		'count'       => $abstract_meta['count'],
	),
	array(
		'title'       => __( 'Custom Art Commissions', 'evara-art' ),
		'badge'       => __( '✦ Bespoke Commissions', 'evara-art' ),
		'desc'        => __( 'Commission a one-of-a-kind original painting painted by lead artist Shraddha Parmar tailored to your custom dimensions and interior palette.', 'evara-art' ),
		'url'         => home_url( '/custom-art/' ),
		'image'       => EVARA_URI . '/assets/images/hero-banner-krishna.png',
		'count'       => null,
	),
);
?>

<main id="primary" class="site-main categories-hub-page">

	<!-- Header Hero Banner for Categories Hub -->
	<section class="categories-hub-section">
		<div class="evara-container">
			
			<div class="categories-hub-header">
				<span class="categories-hub-badge">
					<i class="fa-solid fa-shapes"></i> <?php esc_html_e( 'Curated Art Catalog', 'evara-art' ); ?>
				</span>
				<h1 class="categories-hub-title">
					<?php esc_html_e( 'Explore All Art Collections', 'evara-art' ); ?>
				</h1>
				<p class="categories-hub-subtitle">
					<?php esc_html_e( 'Select a collection below to browse 100% original, hand-painted canvas artworks, sacred Gujarat Lippan craft, wildlife animal portraits, and bespoke commissions.', 'evara-art' ); ?>
				</p>
			</div>

			<!-- Grid of All Collections -->
			<div class="categories-hub-grid">
				<?php foreach ( $primary_categories as $cat ) : ?>
					<a href="<?php echo esc_url( $cat['url'] ); ?>" class="category-hub-card">
						<div class="category-card-thumb-wrap">
							<span class="category-card-pill"><?php echo esc_html( $cat['badge'] ); ?></span>
							<?php if ( null !== $cat['count'] && $cat['count'] > 0 ) : ?>
								<span class="category-card-count"><?php printf( esc_html__( '%d Artworks', 'evara-art' ), $cat['count'] ); ?></span>
							<?php elseif ( null !== $cat['count'] ) : ?>
								<span class="category-card-count"><?php esc_html_e( 'Curated Collection', 'evara-art' ); ?></span>
							<?php else : ?>
								<span class="category-card-count"><?php esc_html_e( 'Personalized Art', 'evara-art' ); ?></span>
							<?php endif; ?>
							
							<img src="<?php echo esc_url( $cat['image'] ); ?>" 
							     alt="<?php echo esc_attr( $cat['title'] ); ?>" 
							     loading="lazy" 
							     width="600" 
							     height="400">
						</div>

						<div class="category-card-body">
							<h2 class="category-card-title"><?php echo esc_html( $cat['title'] ); ?></h2>
							<p class="category-card-desc"><?php echo esc_html( $cat['desc'] ); ?></p>
							<span class="category-card-action">
								<span><?php esc_html_e( 'Explore Collection', 'evara-art' ); ?></span>
								<i class="fa-solid fa-arrow-right"></i>
							</span>
						</div>
					</a>
				<?php endforeach; ?>
			</div>

		</div>
	</section>

	<!-- Trust & Authenticity Strip -->
	<section class="evara-trust-strip">
		<div class="evara-container trust-grid">
			<div class="trust-item">
				<div class="trust-icon"><i class="fa-solid fa-hand-holding-heart"></i></div>
				<div class="trust-text">
					<h4><?php esc_html_e( '100% Handcrafted', 'evara-art' ); ?></h4>
					<p><?php esc_html_e( 'Every artwork is individually created with pure passion and dedication by lead artist Shraddha Parmar.', 'evara-art' ); ?></p>
				</div>
			</div>
			<div class="trust-item">
				<div class="trust-icon"><i class="fa-solid fa-truck-fast"></i></div>
				<div class="trust-text">
					<h4><?php esc_html_e( 'Pan-India Insured Dispatch', 'evara-art' ); ?></h4>
					<p><?php printf( esc_html__( 'Zero-damage transit guarantee with custom armored wooden crating. Free shipping on orders above ₹%s.', 'evara-art' ), number_format_i18n( evara_get_free_shipping_threshold() ) ); ?></p>
				</div>
			</div>
			<div class="trust-item">
				<div class="trust-icon"><i class="fa-solid fa-certificate"></i></div>
				<div class="trust-text">
					<h4><?php esc_html_e( 'Authenticity Guaranteed', 'evara-art' ); ?></h4>
					<p><?php esc_html_e( 'Comes with a signed Certificate of Authenticity confirming genuine handcrafted origin.', 'evara-art' ); ?></p>
				</div>
			</div>
		</div>
	</section>

</main>

<?php get_footer(); ?>
