<?php
/**
 * Template for displaying all single posts.
 */

    get_header();

    $blogSidebar = 'span9';
    
    $sidebar=epico_opt('blog-sidebar-position');

    if($sidebar == 'no-sidebar') {
        $blogSidebar = 'span12 fullWidthBlog';
    }
?>

	<div class="container">
		<div class="row">
			<!--Content-->
			<div class="<?php echo esc_attr($blogSidebar); ?>">

				<?php
				$postType = get_post_meta(get_the_ID(), 'media', true);
				if ( have_posts()) {
					while ( have_posts() ) { the_post(); ?>
						<?php if ($postType == 'gallery' ) {
							get_template_part('templates/single', 'post-gallery');
						} else if ($postType == 'video' ) {
							get_template_part('templates/single', 'post-video');
						} else if ($postType == 'video_gallery' ) {
							get_template_part('templates/single', 'post-video-gallery');
						} else if ($postType == 'audio' ){
							get_template_part('templates/single', 'post-audio');
						} else if ($postType == 'audio_gallery' ) {
							get_template_part('templates/single', 'post-audio-gallery');
						} else if ($postType == 'quote' ) {
							get_template_part('templates/single', 'post-quote');
						} else {
							get_template_part( 'templates/single');
						}

					} // end of the loop.
				} // end if ?>
			</div>

			<?php
				 if( $sidebar == 'main-sidebar' ){ ?>
					<!-- Right Sidebar  -->
					<div class="span3 main-sidebar-container">
						<?php  epico_get_sidebar('main-sidebar'); ?>
					</div>
			<?php } ?>
		</div>
	</div>
<?php get_footer(); ?>