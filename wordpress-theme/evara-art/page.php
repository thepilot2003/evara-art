<?php
/**
 * The standard page template
 *
 * @package EvaraArt
 */

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
		endwhile;
		?>
	</div>
</div>

<?php get_footer(); ?>
