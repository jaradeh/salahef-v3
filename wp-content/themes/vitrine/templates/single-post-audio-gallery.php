<div <?php post_class(); ?>>
    <?php
    $sidebar=epico_opt('blog-sidebar-position');//to make header gallery and audio fill in span8
    if($sidebar=='no-sidebar'){?>
        <div class="row"><div class="span8 offset2 single-post-header">
    <?php } ?>
    <div class="post-media">
        <!-- Audio -->
        <?php

        //Parse the content for the first occurrence of video url
        $audio = epico_extract_audio_info(epico_get_meta('audio-url'));

        if($audio != null)
        {
            //Extract video ID
            ?>
            <div class="post-media audio-frame">
                <?php
                echo epico_soundcloud_get_embed($audio['url']);
                ?>
            </div>

        <?php } ?>
        <!-- SlideShow -->
        <?php
        $images = epico_get_meta('gallery');
        if(count($images))
        {?>
        
            <div class="bpSwiper swiper-container clearfix <?php echo(count($images) == 1 ? 'disabled_swiper' : ''); ?>">
                
                <?php if ( is_array($images)) { ?>
                <!-- Next Arrows -->
                <div class="arrows-button-next no-select"></div>


                <!-- Prev Arrows -->
                <div class="arrows-button-prev no-select"></div>

                <?php } ?>
                
                <div class="swiper-wrapper">
                
                    <?php

                        if ( ! is_array($images)) { ?>

                            <div class="swiper-slide">  <?php echo  "<img src=\"$images\" />"; ?> </div>
                    
                        <?php } else {

                        $imageSize = 'full';
                        foreach($images as $img)
                        {
                            //For getting image size use
                            //http://php.net/manual/en/function.getimagesize.php
                            $imgId = epico_get_image_id($img);
                            if($imgId == -1) {//Fallback
                                 if (! empty($img)) {

                                    $imgTag = '<img src="' . esc_url($img) . '" alt="" />';
                                }
                            } else {
                                $imgTag = wp_get_attachment_image($imgId, $imageSize);
                            }
                            ?>

                            <?php if (! empty($imgTag)) { ?>
                                <div class="swiper-slide">  <?php echo $imgTag;//Sanitization performed in above lines! ?> </div>
                            <?php } ?>

                        <?php
                        }
                    } ?>
                      
                </div>
            </div>
        <?php
        }?>

    </div>
    <?php    
    if($sidebar=='no-sidebar'){?>
         </div></div>
    <?php } 
    
    get_template_part( 'templates/single', "post-meta" );
    the_content();
    wp_link_pages();?>
</div>
<?php   get_template_part( 'templates/single', "post-content" );?>