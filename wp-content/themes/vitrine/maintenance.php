<?php
/*
Template Name: Maintenance Page
*/

wp_head();  ?>
 <div class="wrap" id="pageHeight">
	<?php /* The loop */ ?>
	<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<div class="entry-content">
					<?php the_content(); ?>

			</article><!-- #post -->
	<?php endwhile; ?>

</div><!-- .site-content -->

<?php wp_footer(); 
