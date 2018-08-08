<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.3.2
 */

defined( 'ABSPATH' ) || exit;


// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
	return;
}

global $post, $product;
$product_gallery_popup =  epico_opt('product_gallery_popup');
$attachment_ids = $product-> get_gallery_image_ids();

//EpicoMedia codes
$image_num = count($attachment_ids) + ( has_post_thumbnail() ? 1 : 0 );

$processed_images = array();

//variation images
$variable_images = array();
$variable_image_titles = array();
if( $product->is_type( 'variable' ) ){
	$get_variations = sizeof( $product->get_children() ) <= apply_filters( 'woocommerce_ajax_variation_threshold', 30, $product );
	$available_variations = array_reverse($get_variations ? $product->get_available_variations() : array());
	foreach ($available_variations as $variable) {
		if(isset($variable['image']['url']) && $variable['image']['url'] != '')
		{
			$variable_images[] = esc_url($variable['image']['url']);
			$variable_image_titles[] = esc_attr($variable['image']['title']);
		}	

	}

	$variable_images = array_unique($variable_images);
	$variable_image_titles = array_unique($variable_image_titles);
}

///add number of variation images
$image_num += count($variable_images);


if(isset($is_quick_view) )
{
	$product_detail_style = 'classic'; // style of product detail for quickview
}
else
{
	if(epico_get_meta('product_detail_style_inherit') == '1')
	{
		$product_detail_style = epico_get_meta('product_detail_style'); // style of product detail in product page
	}
	else
	{
		$product_detail_style = epico_opt("product-detail-style"); // style of product detail in theme settings
	}
	
}

?>
<div class="images">
    <div id="product-fullview-thumbs" class="<?php if(count($attachment_ids) == 0 && count($variable_images) == 0) { echo "no-gallery";} ?>">
    	<div class="zoom-container">
    	<?php
    	if($image_num > 1)
		{
			?>

	        <div class="swiper-container clearfix">
	            <div class="swiper-wrapper">

					<?php
					$zoom = esc_attr(epico_get_meta('shop_enable_zoom'));

					$slide_num = 0;

					if ( has_post_thumbnail() )					
					{
						$image_title = get_the_title( get_post_thumbnail_id() );

						if(function_exists('wc_get_image_size')) {

							$image_dimension = wc_get_image_size('single');

							$image_link  = wp_get_attachment_url( get_post_thumbnail_id() );
							$img_url = aq_resize($image_link, $image_dimension['width'], $image_dimension['height'], $image_dimension['crop'], true, true);
							
							if(!$img_url) {
								$img_url = $image_link;
							}

							$image = '<img src="'.esc_url($img_url).'" alt="'.esc_attr($image_title).'" />';

						} else {

							$image = get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'single' ) );

						}
							    

						//echo slide
						if ($zoom == 1 && $product_detail_style != 'pd_top') {

							$big_image = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID ), 'full' );
							if($product_gallery_popup  )
					             echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div class="swiper-slide easyzoom enable-popup"  data-zoom-image="%s" data-src="%s" data-slide="%s" >%s</div>', esc_url($big_image[0]), esc_url($img_url), esc_attr($slide_num), $image ), $post->ID );
                            else
							    echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div class="swiper-slide easyzoom" data-zoom-image="%s" data-slide="%s">%s</div>', esc_url($big_image[0]), esc_attr($slide_num), $image ), $post->ID );
						
						}
						else
						{
							if($product_gallery_popup )
					           echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div class="swiper-slide enable-popup"   data-src="%s" data-slide="%s" >%s</div>',  esc_url($img_url), esc_attr($slide_num), $image ), $post->ID );
						    else
			            	   echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div class="swiper-slide" data-slide="%s" >%s</div>',  esc_attr($slide_num), $image ), $post->ID );

						}

						preg_match( '@src="([^"]+)"@' , $image, $match );
						$src = array_pop($match);
				        $processed_images[] = $src;	

						$slide_num++;
					
					}


					//Process variable images at first (remove duplicate images of gallery because we need variable images for showing when user select a- it's more important than gallery iamges)
						
					if( $product->is_type( 'variable' ) ){
						$iterateor = 0;
						foreach ($variable_images as $variable_image) {


                            // crop variable Image 
                            $image_dimension = wc_get_image_size('single');
						    $variable_url = aq_resize($variable_image, $image_dimension['width'], $image_dimension['height'], $image_dimension['crop'], true, true);

							if(!$variable_url) {
								$variable_url = $variable_image;
							}
							
							if(in_array($variable_url, $processed_images))
							{
								continue;
							}
					        $processed_images[] = $variable_url;	

							//echo slide
					        $image_title = "";
					        if(isset($variable_image_titles[$iterateor]))
					        {
					        	$image_title = $variable_image_titles[$iterateor];
					        }
							
							if ($zoom == 1 && $product_detail_style != 'pd_top') {


							if($product_gallery_popup  )
					             echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div class="swiper-slide easyzoom enable-popup" data-src="%s" data-zoom-image="%s" data-slide="%s" data-variableimageurl="%s"><img src="%s" alt="%s"></div>', esc_url($variable_image), esc_url($variable_image), esc_attr($slide_num), esc_url($variable_image), esc_url($variable_url), esc_attr($image_title)  ), $post->ID );
                            else
								echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div class="swiper-slide easyzoom" data-zoom-image="%s" data-slide="%s" data-variableimageurl="%s"><img src="%s" alt="%s"></div>', esc_url($variable_image), esc_attr($slide_num), esc_url($variable_image), esc_url($variable_url), esc_attr($image_title) ), $post->ID );

						}
						else
						{
							if($product_gallery_popup )
					           echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div class="swiper-slide enable-popup"   data-src="%s" data-slide="%s"  data-variableimageurl="%s"><img src="%s" alt="%s"></div>',  esc_url($variable_image), esc_attr($slide_num), esc_url($variable_image), esc_url($variable_url), esc_attr($image_title) ), $post->ID );
						    else
                               echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div class="swiper-slide" data-slide="%s" data-variableimageurl="%s"><img src="%s" alt="%s"></div>',esc_attr($slide_num), esc_url($variable_image), esc_url($variable_url), esc_attr($image_title)), $post->ID );

						}

							$iterateor++;
							$slide_num++;

						}
					}



					foreach ( $attachment_ids as $attachment_id ) {

						$image_title = get_the_title( $attachment_id );

						if(function_exists('wc_get_image_size')) {

							$image_dimension = wc_get_image_size('single');

							$image_link  = wp_get_attachment_url( $attachment_id );
							$img_url = aq_resize($image_link, $image_dimension['width'], $image_dimension['height'], $image_dimension['crop'], true, true);
							
							if(!$img_url) {
								$img_url = $image_link;
							}

							$image = '<img src="'.esc_url($img_url).'" alt="'.esc_attr($image_title).'" />';

						} else {

							$image = get_the_post_thumbnail( $attachment_id, apply_filters( 'single_product_large_thumbnail_size', 'single' ) );

						}

						preg_match( '@src="([^"]+)"@' , $image, $match );
						$src = array_pop($match);
						if(in_array($src, $processed_images))
						{
							continue;
						}
				        $processed_images[] = $src;	
						
						
						//echo slide
						if ($zoom == 1 && $product_detail_style != 'pd_top') {

					$big_image = wp_get_attachment_image_src( $attachment_id , 'full' );
							if($product_gallery_popup  )
					             echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div class="swiper-slide easyzoom enable-popup"  data-zoom-image="%s" data-src="%s" data-slide="%s" >%s</div>', esc_url($big_image[0]), esc_url($img_url), esc_attr($slide_num), $image ), $post->ID );
                            else
							    echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div class="swiper-slide easyzoom" data-zoom-image="%s" data-slide="%s">%s</div>', esc_url($big_image[0]), esc_attr($slide_num), $image ), $post->ID );
						
						}
						else
						{
							if($product_gallery_popup )
					           echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div class="swiper-slide enable-popup"   data-src="%s" data-slide="%s" >%s</div>',  esc_url($img_url), esc_attr($slide_num), $image ), $post->ID );
						    else
			            	   echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div class="swiper-slide" data-slide="%s" >%s</div>',  esc_attr($slide_num), $image ), $post->ID );

						}

						$slide_num++;
					}

					?>
				</div>
				<div class="swiper-button-next"></div>
				<div class="swiper-button-prev"></div>
	    	</div>
	    	<?php
	    }
	    else
	    {

			$zoom = esc_attr(epico_get_meta('shop_enable_zoom')); 

			if ( has_post_thumbnail() )					
			{

				$image_title = get_the_title( get_post_thumbnail_id() );

				if($product_detail_style == 'pd_top')
				{
					
					$image = wp_get_attachment_image( get_post_thumbnail_id($post->ID ), 'full' );

				}
				else
				{
					if(function_exists('wc_get_image_size')) {

						$image_dimension = wc_get_image_size('single');

						$image_link  = wp_get_attachment_url( get_post_thumbnail_id() );
						$img_url = aq_resize($image_link, $image_dimension['width'], $image_dimension['height'], $image_dimension['crop'], true, true);
						
						if(!$img_url) {
							$img_url = $image_link;
						}

						$image = '<img src="'.esc_url($img_url).'" alt="'.esc_attr($image_title).'" />';

					} else {

						$image = get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'single' ) );

					}

				}
					

				//echo slide
				if ($zoom == 1 && $product_detail_style != 'pd_top') {

					$big_image = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID ), 'full' );

					echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div class="easyzoom" data-zoom-image="%s">%s</div>', esc_url($big_image[0]), $image ), $post->ID );
				
				}
				else
				{

	            	echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '%s', $image ), $post->ID );

				}
			
			}

			foreach ( $attachment_ids as $attachment_id ) 
			{

				$image_title = get_the_title( $attachment_id );

				if($product_detail_style == 'pd_top')
				{
					
					$image = wp_get_attachment_image( $attachment_id, 'full' );

				}
				else
				{
					if(function_exists('wc_get_image_size')) {

						$image_dimension = wc_get_image_size('single');

						$image_link  = wp_get_attachment_url( $attachment_id );
						$img_url = aq_resize($image_link, $image_dimension['width'], $image_dimension['height'], $image_dimension['crop'], true, true);
						
						if(!$img_url) {
							$img_url = $image_link;
						}

						$image = '<img src="'.esc_url($img_url).'" alt="'.esc_attr($image_title).'" />';

					} else {

						$image = get_the_post_thumbnail( $attachment_id, apply_filters( 'single_product_large_thumbnail_size', 'single' ) );

					}

				}


				//echo slide
				if ($zoom == 1 && $product_detail_style != 'pd_top') {

					$big_image = wp_get_attachment_image_src( $attachment_id , 'full' );
					
					if($product_gallery_popup )
						echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div class="easyzoom enable-popup"  data-src="%s" data-zoom-image="%s">%s</div>', esc_url($big_image[0]), esc_url($big_image[0]), $image ), $post->ID );
					else
						echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div class="easyzoom" data-zoom-image="%s">%s</div>', esc_url($big_image[0]), $image ), $post->ID );
				
				}
				else
				{

	            	echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '%s', $image ), $post->ID );

				}

			}

	    }
	    ?>
		</div>
    </div>
			<?php do_action( 'woocommerce_product_thumbnails' ); ?>
</div>


