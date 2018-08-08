<?php
/**
 * Displayed when no products are found matching the current query
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/no-products-found.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$shopFullwidth = epico_opt('shop-enable-fullwidth'); 

$page_url = wc_get_page_permalink( 'shop' );
if ( '' === get_option( 'permalink_structure' )) {
   $page_url = get_post_type_archive_link('product');
}

?>

<?php if ($shopFullwidth == '1') { ?>

    <div class="woocommerce-info shopFullwidth">
        <div class="container">
            <div class="span12">
                <?php esc_html_e( 'No products were found matching your selection. ', 'vitrine' ); ?>
                <a href="<?php echo esc_url($page_url); ?>" class="back-to-shop no_djax"><?php esc_html_e('Back to shop','vitrine'); ?></a>
            </div>
        </div>
    </div>

<?php } else {  ?>

    <p class="woocommerce-info">
        <?php esc_html_e( 'No products were found matching your selection. ', 'vitrine' ); ?>
        <a href="<?php echo esc_url($page_url); ?>" class="back-to-shop no_djax"><?php esc_html_e('Back to shop','vitrine'); ?></a>
    </p>

<?php } ?>