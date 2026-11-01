<?php
/**
 * Fallback template
 *
 * @package EvaraArt
 */

get_header(); ?>

<div class="evara-container page-content-container" style="padding: 60px 20px;">
	<?php
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile;
	else :
		echo '<p>No content found.</p>';
	endif;
	?>
</div>

<?php get_footer(); ?>
