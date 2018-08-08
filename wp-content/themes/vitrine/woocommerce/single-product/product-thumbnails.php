<?php
/**
 * Single Product Thumbnails
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-thumbnails.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.3.2
 */

defined( 'ABSPATH' ) || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
	return;
}

global $post, $product;

$attachment_ids = $product->get_gallery_image_ids();


	//EpicoMedia codes
	//variation images
	$variable_images = array();
	if( $product->is_type( 'variable' ) ){
		$get_variations = sizeof( $product->get_children() ) <= apply_filters( 'woocommerce_ajax_variation_threshold', 30, $product );
		$available_variations = array_reverse($get_variations ? $product->get_available_variations() : array());

		foreach ($available_variations as $variable) {
			if(isset($variable['image']['url']) && $variable['image']['url'] != '')
			{
				$variable_images[] = esc_url($variable['image']['url']);
			}	

		}

		$variable_images = array_unique($variable_images);
	}

if ( (count($attachment_ids) + count($variable_images)) > 0 ) {
	
	$processed_images = array();

	if ( $attachment_ids || count($variable_images) > 0 ) {
		?>
		<div class="thumbnails zoom-gallery">
		    <div id="product-thumbs">
		        <div class="swiper-container clearfix <?php if ( epico_opt('product_gallery_autoplay') != 0 ) { ?> auto-play <?php } ?>">
		            <div class="swiper-wrapper">
							<?php

							$columns = apply_filters( 'woocommerce_product_thumbnails_columns', 3 );
							$image_dimension = wc_get_image_size('gallery_thumbnail');
							
							if ( has_post_thumbnail() )					
							{
								$thumb_image       = wp_get_attachment_image_src(get_post_thumbnail_id( $post->ID) ,'full');
								$image       = aq_resize($thumb_image[0], $image_dimension['width'], $image_dimension['height'], true, true);
								//var_dump($image);
								if(!$image)
									$image = $thumb_image[0];
								
								
								
								$image = '<img src="' . $image . '" alt="">';
								
								echo '<div class="swiper-slide">';
								echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $image, $post->ID, $post->ID );
						        echo '</div>';

						        preg_match( '@src="([^"]+)"@' , $image, $match );
								$src = array_pop($match);
						        $processed_images[] = $src;				
							}

							//variation images
							if( $product->is_type( 'variable' ) ){
								foreach ($variable_images as $variable_image) {
	                                // crop variable Image 
	                                
							        $variable_url = aq_resize($variable_image, $image_dimension['width'], $image_dimension['height'], true, true);

									if(!$variable_url)
										$variable_url = $variable_image;
									
									if(in_array($variable_url, $processed_images))
									{
										continue;
									}
							        $processed_images[] = $variable_url;

	                                echo '<div class="swiper-slide">';
									echo '<img src="' . esc_url($variable_url) . '" alt="">';
						            echo '</div>';
								}
							}

							foreach ( $attachment_ids as $attachment_id ) {

								$image       = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'gallery_thumbnail' ) );

								preg_match( '@src="([^"]+)"@' , $image, $match );
								$src = array_pop($match);
								if(in_array($src, $processed_images))
								{
									continue;
								}
						        $processed_images[] = $src;

								

					            echo '<div class="swiper-slide">';
								echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $image , $attachment_id, $post->ID);
					            echo '</div>';
							}

						?>		
					</div>
	        	</div>
	    	</div>
		</div>
		<?php
	}
}