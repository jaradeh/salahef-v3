<?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product->is_purchasable() ) {
	return;
}

echo wc_get_stock_html( $product ); // WPCS: XSS ok.

if ( $product->is_in_stock() ) : ?>

	<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

	<form  class="cart" method="post" enctype='multipart/form-data'>
	 	<?php

			do_action( 'woocommerce_before_add_to_cart_button' );


			do_action( 'woocommerce_before_add_to_cart_quantity' );

            woocommerce_quantity_input( array(
			    'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
			    'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
			    'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
		    ) );

			do_action( 'woocommerce_after_add_to_cart_quantity' );
	 	?>

	 	<?php //Epico codes 
		
		    $ajaxClass = '';
		    if ( 'yes'  === get_option( 'woocommerce_enable_ajax_add_to_cart' ) ) {
				$ajaxClass = 'ajax_add_to_cart';
            }
		
		?>
	 	<a class="single_add_to_cart_button button alt product_type_simple add_to_cart_button <?php echo $ajaxClass; ?> " data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" data-quantity="1" href="#" title="<?php echo esc_attr($product->single_add_to_cart_text()); ?>"> 
			<?php if ( 'no'  === get_option( 'woocommerce_enable_ajax_add_to_cart' ) ) { ?> 
			<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>"/> 
			<?php } ?>
            <span class="icon"></span>
            <span class="txt" data-hover="<?php echo esc_attr( $product->single_add_to_cart_text()); ?>">
                    <?php echo esc_attr( $product->single_add_to_cart_text()); ?>
	        </span>
        </a>

        <?php //End of epico codes ?>

		<?php 
					/**
			 * @since 2.1.0.
			 */
			do_action( 'woocommerce_after_add_to_cart_button' );
		?>
	</form>

	<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<?php endif; ?>
