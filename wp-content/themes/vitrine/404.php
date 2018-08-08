<?php
/**
 * 404 (Page not found) template
 */

    get_header();

?>
	<div class="container">
		<div class="row">
			<div class="span12 not_found_page">
				<strong><?php esc_html_e('404 ERROR', 'vitrine'); ?></strong>
				<p><?php esc_html_e('PAGE NOT FOUND', 'vitrine'); ?></p>
				<hr />
				<p><?php esc_html_e('Sorry, the page you are looking for is not available. You can use the search box below if you like.', 'vitrine'); ?></p>
				<br/>
				<?php get_search_form(); ?>
			</div>
		 </div>
	</div>
<?php get_footer(); ?>