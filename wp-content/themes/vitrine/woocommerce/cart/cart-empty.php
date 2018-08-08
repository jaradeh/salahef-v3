<?php
/**
 * Empty cart page
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wc_print_notices();

?>

<div class="container cartEmpty clearfix">
    <!-- widgetized Area -->
    <div class="wpb_row vc_row-fluid">
        <div class="wpb_column vc_column_container vc_col-sm-12">
            <div class="vc_column-inner">

            <?php 

			/**
			 * @hooked wc_empty_cart_message - 10
			 */

			do_action( 'woocommerce_cart_is_empty' ); ?>

            <p class="return-to-shop"><a class="button wc-backward" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>"><?php esc_html_e( 'Return to Shop', 'vitrine' ) ?></a></p>

            </div>
        </div>
    </div>
</div>