<?php
/**
 * Search template
 */

    get_header();

?>
	<div class="container">
		<div class="row">
			<div class="span12">
				<br/>
				<?php get_search_form(); ?>
				<hr class="search-top-line"/>
				<?php $pageHeading = have_posts() ? sprintf(esc_html__("Results for &nbsp; '%s'", 'vitrine'), $s ) : esc_html__('No Results Found', 'vitrine'); ?>
				<h2 class="search-title"><?php echo esc_attr($pageHeading); ?></h2>
				<?php get_template_part( 'templates/loop', 'search' );
					epico_get_pagination();
				?>
			</div>
		</div>
	</div>
<?php get_footer(); ?>