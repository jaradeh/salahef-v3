<div class="post-content">
    <div class="post-media">
    <?php

    $images_urls = get_post_meta( get_the_ID() ,'gallery', true);
    if(is_array($images_urls))
    {
        $images = implode(",",$images_urls);
    }
    else
    {
        $images = $images_urls;
    }
             
    $images=explode(",",$images);


    if ($images[0]!=''){ ?>

    <div class="bpSwiper swiper-container clearfix <?php echo(count($images) == 1 ? 'disabled_swiper' : ''); ?>">
    
                <!-- Next Arrows -->
                <div class="arrows-button-next no-select"></div>


                <!-- Prev Arrows -->
                <div class="arrows-button-prev no-select"></div>
                
        
        <div class="swiper-wrapper">
        
             <?php
                //Inheritance check for blog sidebar

                $postid =  get_queried_object_id();
                $checkSidebar = get_post_meta($postid, 'blog-sidebar', true);
                if( $checkSidebar!='inherit-from-theme-settings' && $checkSidebar != false ){ //  ( $checkSidebar == false ) -> when Blog Post by default is set in index and Home Page
                    $sidebar= $checkSidebar;
                }else{
                    $sidebar= epico_opt('blog-sidebar-position');
                }
                
                if ( $sidebar == 'no-sidebar') {
                    $imageSize = 'post-thumbnail';
                } else {
                    $imageSize = 'epico_post-thumbnail-fullwidth';
                }

                foreach($images as $img)
                {
                    //For getting image size use
                    //http://php.net/manual/en/function.getimagesize.php
                    $imgId = epico_get_image_id($img);

                    if($imgId == -1) {//Fallback
                        if ($img != '') {
                            $imgURL= $img;
                        }
                    } else {
                        $imgURL = wp_get_attachment_image_src($imgId, $imageSize);
                        $imgURL = $imgURL[0];
                    }
                    ?>

                    <?php if ($imgURL != '') { ?>
                        <div class="swiper-slide" style="background:url(<?php echo esc_url($imgURL); ?>);"></div>
                    <?php } ?>

                <?php
                }?>
                
            </div>
      </div>
        
    <?php
    }
    ?>
    </div>
    <?php
     get_template_part( 'templates/loop', "blog-meta" );?>    
</div>
<hr>