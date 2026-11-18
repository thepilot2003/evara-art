<?php
/**
 * The standard page template & Essential Slug Router
 *
 * @package EvaraArt
 * @version 1.4.6
 */

$current_slug = get_post_field( 'post_name', get_post() );

if ( in_array( $current_slug, array( 'refund_returns', 'refund-returns', 'returns-policy', 'refund-and-return-policy' ) ) ) {
	require locate_template( 'page-refund-returns.php' );
	return;
}
if ( in_array( $current_slug, array( 'about', 'about-us', 'studio-story' ) ) ) {
	require locate_template( 'page-about.php' );
	return;
}
if ( in_array( $current_slug, array( 'custom-art', 'custom-commission', 'commissions' ) ) ) {
	require locate_template( 'page-custom-art.php' );
	return;
}
if ( in_array( $current_slug, array( 'shipping-policy', 'shipping-and-delivery-policy', 'shipping-delivery-policy' ) ) ) {
	require locate_template( 'page-shipping-policy.php' );
	return;
}
if ( in_array( $current_slug, array( 'privacy-policy' ) ) ) {
	require locate_template( 'page-privacy-policy.php' );
	return;
}
if ( in_array( $current_slug, array( 'terms-conditions', 'terms-and-conditions' ) ) ) {
	require locate_template( 'page-terms-conditions.php' );
	return;
}
if ( in_array( $current_slug, array( 'categories', 'collections', 'all-categories' ) ) ) {
	require locate_template( 'page-categories.php' );
	return;
}

get_header(); ?>

<div class="evara-page-header">
	<div class="evara-container">
		<h1 class="page-title"><?php the_title(); ?></h1>
		<?php if ( function_exists( 'woocommerce_breadcrumb' ) && is_woocommerce() ) : ?>
			<div class="evara-breadcrumbs"><?php woocommerce_breadcrumb(); ?></div>
		<?php endif; ?>
	</div>
</div>

<div class="evara-container page-content-container">
	<div class="page-entry-content">
		<?php
		while ( have_posts() ) :
			the_post();
			the_content();

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'evara-art' ),
				'after'  => '</div>',
			) );

			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;
		endwhile;
		?>
	</div>
</div>

<?php get_footer(); ?>
