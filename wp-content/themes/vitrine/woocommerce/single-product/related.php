<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
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
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$related_product_display = epico_opt('related_product_display');
$related_product_width = epico_opt('related_product_width');

if ( $related_product_width == "1") {
	$related_product_width = "container";
}
else {
	$related_product_width = "fullwidth";
}
 
$carouselClass= '';
if($related_product_display != 1)
{
	$carouselClass = 'carousel woocommerce wc-shortcode no-responsive-animation';
}

if ( $related_products ) : ?>
    <div class="<?php echo  esc_attr($related_product_width); ?>">
	    <div class="related-products">
	        <div class="related   <?php echo $carouselClass; ?>">
 
                <h2><?php esc_html_e( 'Related Products', 'vitrine' ); ?></h2>
    
                <?php woocommerce_product_loop_start(); ?>

                <?php if ($related_product_display !=1 ): ?>
                    <div class="swiper-container" data-visibleitems="4">
                        <div class="swiper-wrapper">
                <?php endif; ?> 

			            <?php foreach ( $related_products as $related_product ) : ?>

				            <?php
                                $post_object = get_post( $related_product->get_id() );
                                setup_postdata( $GLOBALS['post'] =& $post_object );
                            ?>
              
                            <?php wc_get_template_part( 'content', 'product' ); ?>

			            <?php endforeach; ?>

                <?php if ($related_product_display !=1 ): ?>
                     </div>
                </div>
                <?php endif; ?> 


                <?php if ($related_product_display !=1 ): ?>
                    <div class="arrows-button-next"></div>
                    <div class="arrows-button-prev"></div>
                <?php endif; ?> 
    
                <?php  woocommerce_product_loop_end(); ?>

            </div>
        </div>
    </div>
<?php endif; ?>
 
<?php wp_reset_postdata();
