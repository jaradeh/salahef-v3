<?php

$images = epico_get_meta('image');

if(count($images))
{?>

    <div id="pDSwiper" class="swiper-container clearfix">
        <div class="swiper-wrapper">
            
            <?php

            $imageSize = 'full';

            foreach($images as $img)
            {
                //For getting image size use
                //http://php.net/manual/en/function.getimagesize.php
                $imgId = epico_get_image_id($img);
                if($imgId == -1) { //Fallback
                    if (! empty($img)) {
                        $imgTag = "<img src=\"$img\" />";
                    }
                } else { 
                    $imgTag = wp_get_attachment_image_src($imgId, $imageSize);
                }
                ?>
                
                <?php if (! empty($imgTag[0])) { ?>
                    <div class="swiper-slide">
						<img src="<?php echo esc_attr($imgTag[0]); ?>" alt=""/>
					</div>
                <?php } ?>
               
            <?php
            }?>

        </div>
        <div class="pd-arrows-button-prev"></div>
        <div class="pd-arrows-button-next"></div>
    </div>
<?php
}?>



