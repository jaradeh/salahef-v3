<?php
$images = epico_get_meta('image');

if(!empty($images[0]))
{?>
<div id="portfolio-detail-parallax-container">
    <div id="pDSwiper" class="swiper-container clearfix">
	<?php if(count($images)>1){?>    
		<!-- Next Arrows -->
        <div class="arrows-button-next no-select"></div>

		<!-- Prev Arrows -->
		<div class="arrows-button-prev no-select"></div>
	<?php   }?>
        <div class="swiper-wrapper">
            
            <?php

            $imageSize = 'epico_portfolio-single';

            foreach($images as $img)
            {
                //For getting image size use
                //http://php.net/manual/en/function.getimagesize.php
                $imgId = epico_get_image_id($img);
                if($imgId == -1)//Fallback
                $imgTag = "<img src=\"" . esc_url($img) . "\" />";
                else
                    $imgTag = wp_get_attachment_image($imgId, $imageSize);
                ?>
                <div class="swiper-slide">
                    <?php echo $imgTag;//Sanitization performed in above lines! ?>
                </div>
            <?php
            }?>

        </div>
    </div>
</div>
<?php
} ?>


