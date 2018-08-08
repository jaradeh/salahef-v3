<!-- custom section  -->
<?php

    $post_id = get_the_ID();

    if ( ( get_post_meta( $post_id, "page-type-switch", true ) == "blog-section" ) && ( get_post_meta( $post_id, "blog-type-switch", true ) == "0" ) ) {  ?>

		<?php get_template_part( 'templates/loop-page-blog' ); ?>
	
	<?php } else if ( ( get_post_meta( $post_id, "page-type-switch", true ) == "blog-section" ) && ( get_post_meta( $post_id, "blog-type-switch", true ) == "1" )  ) {  ?>	
		
		<div class="row">
			<div class="container">
		
				<?php get_template_part( 'templates/loop-page-cblog' ); ?>	
				
			</div>
		</div>

    <?php } else {

    if (have_posts()) {
        while (have_posts()) { the_post();
            ?>
            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?> >
                <?php the_content(); ?>
                <?php

                	// If comments are open or we have at least one comment, load up the comment template.
			        if ( comments_open() || get_comments_number() ) {  ?>

                        <div class="container clearfix"> 
                            <div class="commentWrap" id="comment-text">
                                <?php  comments_template(); ?>
                            </div>
                        </div>

                    <?php  };


                    $footerMap = get_post_meta($post_id, "footer-map", true);
                    if ($footerMap == 1 && epico_get_meta("snap-to-scroll") == 1) {
                        //Footer Map
                        get_template_part('templates/section', 'location');
                    }

                    if(epico_get_meta("snap-to-scroll") == 1) {
                        $widgetized_footer = get_post_meta($post_id, "footer-widget-area", true);

                        if ($widgetized_footer == "inherit") {

                            $widgetized_footer_theme_setting = epico_opt('footer-widget-area');
                            if($widgetized_footer_theme_setting)
                            {
                                //Footer Widgetized Area
                                get_template_part('templates/section', 'widgetized_footer');
                            }
                            
                        }
                        elseif($widgetized_footer == "enable")
                        {
                            //Footer Widgetized Area
                            get_template_part('templates/section', 'widgetized_footer');
                        }
                    }

                 ?>
            </div>
        <?php
        }//While have_posts
    }//If have_posts
 }