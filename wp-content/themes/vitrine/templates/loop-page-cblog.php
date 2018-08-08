<?php

    $blogSidebar = 'span9';
    $span1='';/* It is used to fix the positioning of span 10*/
    
    //Inheritance check for blog sidebar
    $postid=$wp_query->queried_object->ID;
    $checkSidebar=get_post_meta($postid, 'blog-sidebar', true);
    if($checkSidebar!='inherit-from-theme-settings'){
        $sidebar= $checkSidebar;
    }else{
        $sidebar= epico_opt('blog-sidebar-position');
    }
    
    if($sidebar == 'no-sidebar' ) {
        $blogSidebar = 'span10 fullWidthBlog';
        $span1='<div class="span1"></div>';
    } 
	
?>
<!-- Blog -->
<section  id="blog" class="cblog <?php if( $sidebar == 'main-sidebar' ) { ?> blogHasSidebar <?php } ?>">
    <div class="wrap">
		<!-- blog post items -->
		<div class="container" id="content">
			<div class="row">
			    <?php echo $span1;?>
				<div class="<?php echo esc_attr($blogSidebar); ?>">
                
				    <div id="blogLoop">
                    
					    <?php

						    $postpage = isset( $_GET['postpage'] ) ? (int) $_GET['postpage'] : 1;

						    $args2=array(
							    'post_type' => 'post',
							    'paged'          => $postpage
							    );

						    $main_query = new WP_Query($args2);

						    if ( have_posts() ) {
						    while ($main_query->have_posts()) { $main_query -> the_post();
							
								    global $post;
								    $postType = get_post_meta( get_the_ID() ,'media', true);

								    if ($postType == 'gallery' ) {
									    $postType = 'gallery';
								    } else if ($postType == 'video' ) {
									    $postType = 'video';
								    } else if ($postType == 'video_gallery' ) {
									    $postType = 'video';
								    } else if ($postType == 'audio' ){
									    $postType = 'audio';
								    } else if ($postType == 'audio_gallery' ) {
									    $postType = 'audio';
								    }else if ($postType == 'quote' ) {
									    $postType = 'quote';
								    } else {
									    $postType = 'standard';
								    }
								    ?>
									
									    <div <?php post_class('clearfix'); ?>>
									
										    <?php  get_template_part( 'templates/loop', "blog-$postType" ); ?>
										
									    </div>
								    <?php
							    }
						    }
					    ?>
                    
                    </div>
                    
                    
			        <?php if (have_posts()) { ?>
			
				        <!-- Single Page Navigation-->
				        <div class="pageNavigation clearfix">
					        <div class="navNext"><?php next_posts_link(esc_html__('&larr; Older Entries', 'vitrine')) ?></div>
					        <div class="navPrevious"><?php previous_posts_link(esc_html__('Newer Entries &rarr;', 'vitrine')) ?></div>
				        </div>

			        <?php } ?>
										
				</div>
				
			<?php  if( $sidebar !== 'no-sidebar' ) { ?>
			   
			   <!-- Right Sidebar  -->
				<div class="span3 main-sidebar-container">
					<?php  epico_get_sidebar('main-sidebar'); ?>
				</div>
				
			<?php } ?>

			</div>
		</div>
		
    </div>
</section>
<!-- End Blog -->