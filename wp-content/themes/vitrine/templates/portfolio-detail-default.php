<?php

$pPostType = get_post_format();

if($pPostType == false)
{
    $pPostType = "standard";
}
?>
<div <?php post_class(); ?>>
    <!-- Portfolio Detail Title  -->
    <div id="ajaxPDetail">
        <?php
        $checkTitle = get_post_meta( get_the_ID(), "title-bar", true );
        $title = get_post_meta( get_the_ID(), "title-text", true );
        $subTitle = get_post_meta( get_the_ID() , "subtitle-text", true );
        if($checkTitle != 0) {
        ?>
        <div class="pDHeader pDHeader-<?php echo esc_attr($pPostType);?>">
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
        <?php } ?>

        <?php if($pPostType == 'standard') { ?>
            <div class="postMedia">
                <?php get_template_part('templates/pd', 'gallery-default'); ?>
            </div> 
        <?php } ?>

        <?php if($pPostType == 'video' || $pPostType == 'audio') {  ?>

            <div class="postMedia">
                    
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

        <?php } ?>

        <!--  Portfolio Detail Slider  -->
        <div class="pDcontent">

            <!-- Portfolio Detail like  -->
            <div class="like portfolioInnerSocialShare"><?php echo getPostLikeLink($id); ?></div> 

            <?php get_template_part('templates/project', 'detail'); ?>

            <!-- Portfolio Detail content  -->
            <div class="post-media">
                <?php the_content();?>
            </div>
        </div>

        <!-- Portfolio Detail navigation  -->
        <div id="PDnavigation"></div>

    </div>
</div>