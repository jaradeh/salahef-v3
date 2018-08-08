<?php
/**
 * Archive template
 */

    get_header();
    $blogSidebar = 'span9';
    $span1='';/* It is used to fix the positioning of span 10*/
    
    $sidebar= epico_opt('blog-sidebar-position');
    
    if($sidebar == 'no-sidebar' ) {
        $blogSidebar = 'span10 fullWidthBlog';
        $span1='<div class="span1"></div>';
    } 
	
?>

<!-- Blog -->
<section  id="blog" class="cblog <?php if( $sidebar == 'main-sidebar' ) { ?> blogHasSidebar <?php } ?>">
    <div class="wrap">
		<div class="container" id="content">
			<div class="row">
				<?php echo $span1;?>
				<div class="<?php echo esc_attr($blogSidebar); ?>">
					<div id="blogLoop">
						<?php
						$pageTitle    = '';
						$post = $posts[0]; 
						if (is_category()) {
							$pageTitle = sprintf(__('All posts in: %s', 'vitrine'), single_cat_title('',false));
						}
						elseif( is_tag() ){
							$pageTitle = sprintf(__('All posts tagged: %s', 'vitrine'), single_tag_title('',false));
						}
						elseif( is_day() ){
							$pageTitle = sprintf(__('Archive for: %s', 'vitrine'), get_the_time('F jS, Y'));
						}
						elseif( is_month() ){
							$pageTitle = sprintf(__('Archive for: %s', 'vitrine'), get_the_time('F, Y'));
						}
						elseif ( is_year() ){
							$pageTitle = sprintf(__('Archive for: %s', 'vitrine'), get_the_time('Y') );
						}
						elseif ( is_author() ){
							/* Get author data */
							if(get_query_var('author_name'))
								$curauth = get_user_by('login', get_query_var('author_name'));
							else
								$curauth = get_userdata(get_query_var('author'));

							$pageTitle = sprintf(esc_html__('Posts by: %s', 'vitrine'), $curauth->display_name );
						}
						elseif ( isset( $_GET['paged'] ) && !empty( $_GET['paged'] ) ){
							
							$pageTitle = esc_html__('Blog Archive', 'vitrine');
						}
						
						?>
							 
						
						<h2><?php echo esc_attr($pageTitle); ?></h2>
						
						<?php

						if ( have_posts() ) {
							while ( have_posts() ) { the_post(); 
								   
									
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
						<?php epico_get_pagination();
					} ?>
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

	
	
<?php get_footer(); ?>