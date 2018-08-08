<?php
/**
 * Template for displaying portfolio single posts.
 */

    get_header();
	
	//global $wp_query;
    $pPostDetailType = epico_get_meta('portfolio-detail-style');

    if ( isset($_GET['inner']) ) {
      $pPostDetailType = 'portfolio_detail_default';
    } 

	if ( have_posts())
	{
		while ( have_posts() ) { the_post();

			if ($pPostDetailType == 'portfolio_detail_full_width') 
			{
				get_template_part('templates/portfolio-detail', 'fullwidth');
			}
			else if ($pPostDetailType == 'portfolio_detail_boxed' )
			{
				get_template_part('templates/portfolio-detail', 'boxed');
			}
			else if ($pPostDetailType == 'portfolio_detail_creative' )
			{
                get_template_part('templates/portfolio-detail', 'creative');
			}
			else
			{
                get_template_part('templates/portfolio-detail', 'default');     
			}

		}
	}
	
get_footer();
