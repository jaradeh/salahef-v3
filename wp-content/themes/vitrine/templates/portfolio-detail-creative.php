<?php 
        
$termNames = get_the_term_list( $id , 'skills', '<span>#', '</span>  <span>#', ' </span>' ); // get the item skills

$pPostType = get_post_format();

if($pPostType == false)
{
    $pPostType = "standard";
}

?>
<div <?php post_class(); ?>>
    <!-- Portfolio Detail Title  -->
    <div id="PDetail">

        <div class="pd_creative_fixed_content">
            
            <!--  Portfolio Detail title  -->
            <div class="title_container no-select">

                <?php get_template_part( 'templates/pd-title' ); ?>
                
                <div class="project-subtitle project-skill">
                    <?php echo $termNames; ?>
                </div>
            
            </div>

             <?php 

			//check social share is Enable or not
			if(get_post_meta( get_the_ID(), "social_share_inherit", true ) == '1')
			{
				$portfolio_social_share = get_post_meta( get_the_ID(), "portfolio-social-share", true );
			}
			else
			{
				$portfolio_social_share = epico_opt("social_share_display"); //theme settings;
			}
        
            if( $portfolio_social_share == 1 ) { ?>
                <div class="social_share_container">
                    <!-- portfolio Socail share -->
                    <?php ep_social_share(); ?>
                </div>
            <?php } ?>
            
        
            <!-- Portfolio Detail content  -->
            <div class="desc<?php if( $portfolio_social_share != 1 ) { echo " pd-no-social";} ?>"> 
                <?php the_content();?> 
            </div>
            <a class="home ep-icon icon-home" href="<?php echo get_site_url(); ?>" title="<?php echo esc_attr__('Back to portfolio','vitrine'); ?> "></a>

            <div class="like pd-like"><?php echo getPostLikeLink($id); ?></div>

            <!-- Portfolio Detail navigation  -->
            <div id="PDnavigation"></div>
            
        </div>
    
         <!--  Portfolio Detail Slider  -->
        <div class="pd_creative_item">
            <div class="pDHeader pDHeader-<?php echo esc_attr($pPostType) ?>">
                <?php
                    if( $pPostType == 'standard')
                    {
                        get_template_part('templates/pd', 'creative-gallery');
                        
                    } else if ( $pPostType == 'audio' ) {
                    
                        get_template_part('templates/pd', 'audio');
                    
                    } else if ( $pPostType == 'video' ) {
                    
                        get_template_part('templates/pd', 'video');
                        
                    }
                
                ?>
            </div>
        </div>

    </div>
</div>