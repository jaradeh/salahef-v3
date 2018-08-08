<?php
/**
 * Variable product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/variable.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.1
 */

defined( 'ABSPATH' ) || exit;


global $product;
$catalog_mode =  epico_opt('catalog_mode');


// Added by Epico

/* get attribute complete info */
$taxonomy_types = array();
$attribute_taxonomies = wc_get_attribute_taxonomies();
if ( $attribute_taxonomies ) {
	foreach ( $attribute_taxonomies as $tax ) {
		$taxonomy_types[wc_attribute_taxonomy_name( $tax->attribute_name )] = $tax->attribute_type;
	}
}

// Show Variation title 
$variable_title = epico_opt('variable_title');
$title_display = '';

if($variable_title != "0"){
	$title_display = 'title_display';
}

// End of Added by Epico

$attribute_keys = array_keys( $attributes );

do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<form  class="variations_form cart" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>" data-product_variations="<?php echo htmlspecialchars( wp_json_encode( $available_variations ) ) ?>">
	<?php do_action( 'woocommerce_before_variations_form' ); ?>

	<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
		<p class="stock out-of-stock"><?php esc_html_e( 'This product is currently out of stock and unavailable.', 'vitrine' ); ?></p>
	<?php else : ?>



        <!-- Edited by Epico -->
		<ul class="variations <?php echo $title_display; ?>">
			<?php foreach ( $attributes as $attribute_name => $options ) : ?>
				<li>
					<?php
					//it supports custom attribute
					if( !isset($taxonomy_types[$attribute_name]) || $taxonomy_types[$attribute_name] == "select" || $taxonomy_types[$attribute_name] == "text")
					{
						?>
						<span class="label"><label for="<?php echo sanitize_title( $attribute_name ); ?>"><?php echo wc_attribute_label( $attribute_name ); ?></label></span>
						<?php
					}
					else
					{
						?>
						<span class="label image-label"><label for="<?php echo sanitize_title( $attribute_name ); ?>"><?php echo wc_attribute_label( $attribute_name ); ?> : </label> <span class="attr-value"></span></span>
						<?php
					}
					?>
					<div class="value">
						<?php
                            $selected = isset( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ? wc_clean( stripslashes( urldecode( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ) ) : $product->get_variation_default_attribute( $attribute_name );
							if(!isset($taxonomy_types[$attribute_name]) || $taxonomy_types[$attribute_name] == "select" || $taxonomy_types[$attribute_name] == "text")
							{
								wc_dropdown_variation_attribute_options( array( 'options' => $options, 'attribute' => $attribute_name, 'product' => $product, 'selected' => $selected ) );
								echo end( $attribute_keys ) === $attribute_name ? '<a class="reset_variations" href="#">' . esc_html__( 'Clear selection', 'vitrine' ) . '</a>' : '';
							}
							else
							{
								epico_wc_slider_variation_attribute_items( array( 'items' => $options, 'attribute' => $attribute_name, 'product' => $product, 'selected' => $selected ) );
                                echo end( $attribute_keys ) === $attribute_name ? '<a class="reset_variations" href="#">' . esc_html__( 'Clear selection', 'vitrine' ) . '</a>' : '';
							}


						?>
					</div>
				</li>

		    <?php endforeach;?>
		</ul>
        <!-- End of Edited by Epico -->



		<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

		<div class="single_variation_wrap vitrine_variation_override" style="display:none;">
			<?php
				/**
				 * woocommerce_before_single_variation Hook
				 */
				do_action( 'woocommerce_before_single_variation' );

				/**
				 * woocommerce_single_variation hook. Used to output the cart button and placeholder for variation data.
				 * @since 2.4.0
				 * @hooked woocommerce_single_variation - 10 Empty div for variation data.
				 * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
				 */
				do_action( 'woocommerce_single_variation' );

				/**
				 * woocommerce_after_single_variation Hook
				 */
				do_action( 'woocommerce_after_single_variation' );
			?>


			<!-- Added by Epico -->
			<?php 
			    $ajaxClass = '';
		        if ( 'yes'  === get_option( 'woocommerce_enable_ajax_add_to_cart' ) ) {
					$ajaxClass = '  ajax_add_to_cart';
                }
				
				$catalog_mode_class='';
				if($catalog_mode != 0)
				{
					$catalog_mode_class = 'catalog_add_to_cart';
				}
			?>
			<a class="single_add_to_cart_button button alt product_type_variable <?php echo $ajaxClass .' '. $catalog_mode_class; ?>" href="#" title="<?php echo esc_attr($product->single_add_to_cart_text()); ?>">
	            <span class="icon"></span>
	            <span class="txt" data-hover="<?php echo esc_attr( $product->single_add_to_cart_text()); ?>">
	                    <?php echo esc_attr( $product->single_add_to_cart_text()); ?>
		        </span>
	        </a>
	        <!-- End of Added by Epico -->


		</div>

		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
	<?php endif; ?>

	<?php do_action( 'woocommerce_after_variations_form' ); ?>
</form>

<?php
do_action( 'woocommerce_after_add_to_cart_form' );
