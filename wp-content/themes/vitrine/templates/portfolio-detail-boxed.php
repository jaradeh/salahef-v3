<?php

$pPostType = get_post_format();

if($pPostType == false)
{
    $pPostType = "standard";
}

?>
<div <?php post_class(); ?>>
    <!-- Portfolio Detail Title  -->
    <div id="PDetail">
        <div class="container">
            <div class="row ">
                <div class="span12 pDHeader postMedia pDHeader-<?php echo esc_attr($pPostType); /*if($pPostType != 'gallery') { echo ' minimize'; }*/ ?>">
                    <?php
					if($pPostType == 'standard')
                    {
                        get_template_part('templates/pd', 'gallery');
                    }

                    ?>
				
					<?php
					if($pPostType == 'video')
					{
						get_template_part('templates/pd', 'video');
					}
					else if($pPostType == 'audio')
					{
						get_template_part('templates/pd', 'audio');
					}
					?>
				
				</div>
            </div>
        </div>
        <?php
        $checkTitle = get_post_meta( get_the_ID(), "title-bar", true );
        $title = get_post_meta( get_the_ID(), "title-text", true );
        $subTitle = get_post_meta( get_the_ID() , "subtitle-text", true );
        if($checkTitle != 0) {
        ?>
        <div class="container">
            <div class="row ">
                <div class="span12">
                    <div class="pDHeader-title">
                        <div class="textBox">                             
                        <div class="clearfix">
                            <div class="title clearfix">
                            <?php
                            $checkTitle = get_post_meta( get_the_ID(), "title-bar", true );
                            $title = get_post_meta( get_the_ID(), "title-text", true );
                            $subTitle = get_post_meta( get_the_ID() , "subtitle-text", true );
                            if ( ( $checkTitle == 1 ) && ! empty( $title )) {
                                
                                echo esc_attr($title);

                                if ( ! empty( $subTitle ) ) { ?>
                                    <!-- subtitle -->
                                    <span class="subtitle"><?php echo esc_attr($subTitle); ?></span>
                                <?php 
                                }

                            }
                            else
                            {
                                the_title();
                            }
                            ?>
                            </div>
                        </div>

                                    
                                        
                                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
        <!--  Portfolio Detail Slider  -->
        <div class="pDcontent">
        
            <div class="container">
                <?php get_template_part('templates/project', 'detail'); ?>
            </div>
            


            <!-- Portfolio Detail content  -->
            <div class="post-media">
                <?php the_content();?>
            </div>
        </div>

        <div class="container">
            <div class="row ">
                <div class="span12">
                    <div class="like pd-like"><?php echo getPostLikeLink($id); ?></div>
                </div>
            </div>
        </div>

        <!-- Portfolio Detail navigation  -->
        <div id="PDnavigation"></div>
        
    </div>
</div>