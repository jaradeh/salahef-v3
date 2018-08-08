<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}


// Extra post classes
$classes = array();


$attachment_ids = $product->get_gallery_image_ids();

if( count($attachment_ids) > 0 ) {	
	$classes[] = 'has-gallery';
}

$product_border = epico_opt('shop-product-border');

if( $product_border  != 0 ) {	
	$classes[] = 'with-border';
}

$product_style = epico_opt('shop-product-style');

   



    if($product_style == 'buttonsOnHover' || $product_style == 'centered')
    {

    ?>
	    <li <?php wc_product_class( $classes ); ?>>

		    <?php
		    /**
		     * woocommerce_before_shop_loop_item hook.
		     *
		     * @hooked woocommerce_template_loop_product_link_open - 10
		     */
		    do_action( 'woocommerce_before_shop_loop_item' );

		    ?>
		    <div class="productwrap">
	            <div class="add_to_cart_btn_wrap lazy-load-hover-container">
	    
			        <?php
				        /**
				         * woocommerce_before_shop_loop_item_title hook
				         *
				         * @hooked woocommerce_show_product_loop_sale_flash - 10
				         * @hooked woocommerce_template_loop_product_thumbnail - 10
				         */
				        do_action( 'woocommerce_before_shop_loop_item_title' );
	                
			        ?>
	            

	                <?php if ( !$product->is_in_stock() ) : ?>            
	            
	                    <div class="out_of_stock_badge_loop"><?php esc_html_e( 'Out of stock', 'vitrine' ); ?></div>            
	            
	                <?php endif; ?>
	            
	                <?php
	                //Epico codes
				    if( epico_opt("product-hover-image") == 1 && count($attachment_ids) > 0 ) {			
					    $first_gallery_img = reset($attachment_ids); //get the first image of gallery
					    $image_link = wp_get_attachment_url( $first_gallery_img );
		
					    if (isset($image_link))
					    {

	                        if (epico_opt('shop-layout') == 'masonry') { 
	                            //Auto-height product images used in masonry style 
	                            $img_src = wp_get_attachment_image_src( $first_gallery_img, 'epico_product_thumbnail-auto-height' );
	                        } else {
	                            $img_src = wp_get_attachment_image_src( $first_gallery_img, 'shop_catalog' );
	                        }
						
						    if($img_src != false )
						    {
							    printf( '<div class="hover-image lazy-load lazy-load-hover bg-lazy-load" data-src="%s"></div>',  esc_url($img_src[0]) );
						    }
						    else
						    {
							    printf( '<div class="hover-image lazy-load lazy-load-hover bg-lazy-load" data-src="%s"></div>',  esc_url($image_link) );
						    }
						
					    }
			
				    }
	                ?>
	        	    <div class="product-buttons">
	            	    <?php do_action( 'epico_woocommerce_shop_loop_buttons' ); ?>
	        	    </div>
	        
	            </div>
	        
	            <div class="wrap_after_thumbnail">
	        
			        <?php

					    /**
					     * woocommerce_shop_loop_item_title hook
					     *
					     * @hooked woocommerce_template_loop_product_title - 10
					     */
					    do_action( 'woocommerce_shop_loop_item_title' );
				
				        /**
				         * woocommerce_after_shop_loop_item_title hook
				         *
				         * @hooked woocommerce_template_loop_rating - 5
				         * @hooked woocommerce_template_loop_price - 10
				         */
				        do_action( 'woocommerce_after_shop_loop_item_title' );
			        ?>

	            </div>
	        </div>
	        <?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
	    </li>
    <?php
    } elseif($product_style == 'infoOnHover'){

	    $hover_layer = '<div class="hover_layer"></div>';
	    $hover_color = epico_opt('product-hover-color');
	    $custom_hover_color = epico_opt('product-hover-custom-color');
	    if ( isset( $hover_color ) ) {
	        if( $hover_color != 'custom' && $hover_color != '')
	        {
	            $hover_layer = '<div class="hover_layer" style="background-color:#' . esc_attr($hover_color) . ';"></div>';
	        }
	        else
	        {
	            if( isset( $custom_hover_color ) && $custom_hover_color != '')
	            {
	                $hover_layer = '<div class="hover_layer" style="background-color:' . esc_attr($custom_hover_color) . ';"></div>';
	            }

	        }
	    }
	    ?>
	    <li <?php wc_product_class( $classes ); ?>>
	        <div class="productwrap">
	            <?php echo $hover_layer; ?>
	            <?php 
	            if ( $product->is_on_sale() ) {

	                echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( 'Sale!', 'vitrine' ) . '</span>', $post, $product );
	        
	            }

	            if ( !$product->is_in_stock() ) {          
	                
	                echo '<div class="out_of_stock_badge_loop">' . esc_html__( 'Out of stock', 'vitrine' ) .'</div>';            
	                
	            }
	            ?>

	            <div class="add_to_cart_btn_wrap lazy-load-hover-container">
	                <?php
	                echo '<a href="' . esc_url(get_the_permalink()) . '" class="product-link" title="' . esc_attr(get_the_title()) .'"><span class="hidden-desktop">' . esc_html__('go to detail','vitrine') .'</span></a>'; 

	                echo woocommerce_get_product_thumbnail();

	                $attachment_ids = $product-> get_gallery_image_ids();
	                if(count($attachment_ids) > 0  && epico_opt("product-hover-image") == 1)
	                {
	                    $image_src = '';
	                    $image = '';
	                    $first_gallery_img = reset($attachment_ids);//get the first image of gallery

	                    if(function_exists('wc_get_image_size')) {

	                        $image_dimension = wc_get_image_size('shop_catalog');

	                        $image_link  = wp_get_attachment_url( $first_gallery_img);
	                        $img_url = aq_resize($image_link, $image_dimension['width'], $image_dimension['height'], $image_dimension['crop'], true, true);
	                    
	                        if(!$img_url) {
	                            $img_url = $image_link;
	                        }

	                        $image = '<div class="hover-image lazy-load lazy-load-hover bg-lazy-load" data-src="'.esc_url($img_url).'"></div>';

	                    } else {

	                        $image_url = wp_get_attachment_image_src($first_gallery_img, apply_filters( 'single_product_large_thumbnail_size', 'shop_catalog') );
	                        if($image_url != false )
	                        {
	                            $image = '<div class="hover-image lazy-load lazy-load-hover bg-lazy-load" data-src="'.esc_url($image_url[0]).'"></div>';
	                        }
	                        else
	                        {
	                            $image_src = wp_get_attachment_image_src( $first_gallery_img , 'full' );
	                            $image = '<div class="hover-image lazy-load lazy-load-hover bg-lazy-load" data-src="'.esc_url($image_src[0]).'"></div>';
	                        }

	                    }

	                    echo $image;//Sanitization performed in above lines!
	                }
	                ?>
	            
	                <div class="product-buttons">
					    <?php do_action( 'epico_woocommerce_shop_loop_buttons' ); ?>
	                </div>
	            </div>
	            <div class="wrap_after_thumbnail">
	                <?php
	                if ( $price_html = $product->get_price_html() ) { ?>
	                    <span class="price"><?php echo $price_html; ?></span>
	                <?php }

	                echo '<h3>' . get_the_title() . '</h3>';
	                if ( $price_html) {
	                    if(strpos($price_html,"amount") > 0)
	                    {
	                        $price_html = str_replace("&ndash;","",$price_html); // remove dash "-" used in variable products
	                    }
	                ?>

	                <span class="price"><?php echo $price_html; ?></span>

	                <?php }

	                if ( ($rating_html = wc_get_rating_html( $product->get_average_rating() )) && epico_opt('shop-product-rating') == 1 ) {
	                    echo $rating_html;
	                } 

	                ?>

	            </div>
	        </div>
	    </li>
    <?php
    }
    elseif($product_style == 'infoOnClick') 
    { ?>

	    <li <?php wc_product_class( $classes);?> data-productid=<?php echo esc_attr($product->get_id()); ?>>
	        <div class="productwrap">
                <?php 
                if ( $product->is_on_sale() ) {

	                echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( 'Sale!', 'vitrine' ) . '</span>', $post, $product );
	        
	            }

	            if ( !$product->is_in_stock() ) {          
	                
	                echo '<div class="out_of_stock_badge_loop">' . esc_html__( 'Out of stock', 'vitrine' ) .'</div>';            
	                
	            }
                ?>
		
		
	            <div class="add_to_cart_btn_wrap lazy-load-hover-container">
	                <?php

	                echo woocommerce_get_product_thumbnail();

	                $attachment_ids = $product-> get_gallery_image_ids();
	                if(count($attachment_ids) >= 1  && epico_opt("product-hover-image") == 1)
	                {
	                    $image_src = '';
	                    $image = '';
	                    $first_gallery_img = reset($attachment_ids);//get the first image of gallery

	                    if(function_exists('wc_get_image_size')) {

	                        $image_dimension = wc_get_image_size('shop_catalog');

	                        $image_link  = wp_get_attachment_url( $first_gallery_img);
	                        $img_url = aq_resize($image_link, $image_dimension['width'], $image_dimension['height'], $image_dimension['crop'], true, true);
	                    
	                        if(!$img_url) {
	                            $img_url = $image_link;
	                        }

	                        $image = '<div class="hover-image lazy-load lazy-load-hover bg-lazy-load" data-src="'.esc_url($img_url).'"></div>';

	                    } else {

	                        $image_url = wp_get_attachment_image_src($first_gallery_img, apply_filters( 'single_product_large_thumbnail_size', 'shop_catalog') );
	                        if($image_url != false )
	                        {
	                            $image = '<div class="hover-image lazy-load lazy-load-hover bg-lazy-load" data-src="'.esc_url($image_url[0]).'"></div>';
	                        }
	                        else
	                        {
	                            $image_src = wp_get_attachment_image_src( $first_gallery_img , 'full' );
	                            $image = '<div class="hover-image lazy-load lazy-load-hover bg-lazy-load" data-src="'.esc_url($image_src[0]).'"></div>';
	                        }

	                    }

	                    echo $image;//Sanitization performed in above lines!
	                }
	                ?>
	                <span class="show-hover"></span>
	                <div class="hover-content no-select">
	                    <a href=" <?php echo get_the_permalink(); ?> " > <h3><?php echo get_the_title(); ?></h3></a>
	                    <?php

	                    if ( $price_html = $product->get_price_html() ) {
	                       ?> <span class="price"><?php echo $price_html; ?></span>
	                    <?php } 

	                    wc_get_template( 'single-product/short-description.php' );

	                    ?>
	                </div>
	            </div>
	            <div class="wrap_after_thumbnail">
			
				    <a href=" <?php echo get_the_permalink(); ?> " > <h3><?php echo get_the_title(); ?></h3></a>
	                <?php
	                if ( $price_html = $product->get_price_html() ) { ?>
	                    <span class="price"><?php echo $price_html; ?></span>
	                <?php } ?>

	            </div>  
	        </div>
	    </li>

    <?php } else { // instant-shop style ?>

	    <li <?php wc_product_class( $classes ); ?>>

		    <?php
		    /**
		     * woocommerce_before_shop_loop_item hook.
		     *
		     * @hooked woocommerce_template_loop_product_link_open - 10
		     */
		    do_action( 'woocommerce_before_shop_loop_item' );

		    ?>
		    <div class="productwrap">
	            <div class="add_to_cart_btn_wrap lazy-load-hover-container">
            
			        <?php
				        /**
				         * woocommerce_before_shop_loop_item_title hook
				         *
				         * @hooked woocommerce_show_product_loop_sale_flash - 10
				         * @hooked woocommerce_template_loop_product_thumbnail - 10
				         */
				        do_action( 'woocommerce_before_shop_loop_item_title' );
	                
			        ?>
	            

	                <?php if ( !$product->is_in_stock() ) : ?>            
	            
	                    <div class="out_of_stock_badge_loop"><?php esc_html_e( 'Out of stock', 'vitrine' ); ?></div>            
	            
	                <?php endif; ?>
	            
	                <?php
	                //Epico codes
				    if( epico_opt("product-hover-image") == 1 && count($attachment_ids) > 0 ) {			
					
					    $first_gallery_img = reset($attachment_ids);//get the first image of gallery
					    $image_link = wp_get_attachment_url( $first_gallery_img );
		
					    if (isset($image_link))
					    {

	                        if (epico_opt('shop-layout') == 'masonry') { 
	                            //Auto-height product images used in masonry style 
	                            $img_src = wp_get_attachment_image_src( $first_gallery_img, 'epico_product_thumbnail-auto-height' );
	                        } else {
	                            $img_src = wp_get_attachment_image_src( $first_gallery_img, 'shop_catalog' );
	                        }
						
						    if($img_src != false )
						    {
							    printf( '<div class="hover-image lazy-load lazy-load-hover bg-lazy-load" data-src="%s"></div>',  esc_url($img_src[0]) );
						    }
						    else
						    {
							    printf( '<div class="hover-image lazy-load lazy-load-hover bg-lazy-load" data-src="%s"></div>',  esc_url($image_link) );
						    }
						
					    }
			
				    }
	                 ?>
				     <div class="product-buttons">
					    <?php do_action( 'epico_woocommerce_shop_loop_hover_buttons' ); ?>
	                </div>
	            </div>
	      
			    <div class="wrap_after_thumbnail">
				    <div class="product-buttons">
					    <?php do_action( 'epico_woocommerce_shop_loop_buttons' ); ?>
	                </div>

	                <?php echo '<a href="' . get_the_permalink() . '" ><h3 class="instant_shop_heading">' . get_the_title() . '</h3></a>';?>

                    <div class="instant_shop_button">

                        <?php if ( $price_html = $product->get_price_html() ) { ?>
                                <span class="price"><?php echo $price_html; ?></span>
                  
                                <?php
                                    $button = apply_filters( 'woocommerce_loop_add_to_cart_link',
                                        sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" data-quantity="%s" class="%s product_type_%s %s">%s</a>',
                                            esc_url( $product->add_to_cart_url() ),
                                            esc_attr( $product->get_id() ),
                                            esc_attr( $product->get_sku() ),
                                            esc_attr( isset( $quantity ) ? $quantity : 1 ),
                                            $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                                            esc_attr( $product->get_type() ),
										    esc_attr( $product->get_type() == 'simple' && 'yes'  === get_option( 'woocommerce_enable_ajax_add_to_cart' ) ? 'ajax_add_to_cart':''),
                                            ( $product->add_to_cart_text() )
                                        ),
                                    $product );

                                    $button = apply_filters('epico_loop_instant_shop_add_to_cart_link', $button);
                                    echo $button;
                                ?>

                        <?php } else { ?>
                                
                                <span class="price"></span>

                                <div class="no_price">
                                    <?php
                                        $button = apply_filters( 'woocommerce_loop_add_to_cart_link',
                                            sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" data-quantity="%s" class="%s product_type_%s %s">%s</a>',
                                                esc_attr( $product->get_type() ),
                                                esc_url( $product->add_to_cart_url() ),
                                                esc_attr( $product->get_id() ),
                                                esc_attr( $product->get_sku() ),
                                                esc_attr( isset( $quantity ) ? $quantity : 1 ),
                                                $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                                                esc_attr( $product->get_type() ),
											    esc_attr( $product->get_type() == 'simple' && 'yes'  === get_option( 'woocommerce_enable_ajax_add_to_cart' ) ? 'ajax_add_to_cart':''),
                                                $product->add_to_cart_text()
                                            ),
                                        $product );

                                        $button = apply_filters('epico_loop_instant_shop_add_to_cart_link', $button);
                                        echo $button;
                                    ?>
                                </div>

                        <?php } ?>

                    </div>

                    <?php

	                if ( ($rating_html = wc_get_rating_html( $product->get_average_rating() )) && epico_opt('shop-product-rating') == 1 ) {
	                    echo $rating_html;
	                } 

	                ?>

	            </div>  

	        </div>
	        <?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
	    </li>

    <?php } ?>
