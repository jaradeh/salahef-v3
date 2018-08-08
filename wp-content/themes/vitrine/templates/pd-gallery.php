<?php

$images = epico_get_meta('image');

if(!empty($images[0]))
{?>
<div id="portfolio-detail-parallax-container">
    <div id="pDSwiper" class="swiper-container clearfix <?php echo(count($images) == 1 ? 'disabled_swiper' : ''); ?>">
	<?php if(count($images)>1){?>
				<!-- Next Arrows -->
				<div class="arrows-button-next no-select"></div>

				<!-- Prev Arrows -->
				<div class="arrows-button-prev no-select"></div>
	<?php   } ?>
		<div class="swiper-wrapper">		
            
            <?php

            $imageSize = 'epico_portfolio-detail-gallery';

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
                    <div class="swiper-slide" style="background :url(<?php echo esc_url($imgTag[0]); ?>);"></div>
                <?php } ?>

            <?php
            } ?>

        </div>
    </div>
</div>
<?php
} ?>