<?php
/**
 * Add to wishlist button template
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Wishlist
 * @version 2.0.8
 */

global $product;
?>

<a href="<?php echo esc_url( add_query_arg( 'add_to_wishlist', $product_id ) )?>" rel="nofollow" data-product-id="<?php echo esc_attr($product_id); ?>" data-product-type="<?php echo esc_attr($product_type); ?>" class="<?php echo esc_attr($link_classes); ?>" title="<?php esc_attr_e('Add to wishlist','vitrine'); ?>">
    <?php echo esc_attr($icon); ?>
    <?php echo esc_attr($label); ?>
</a>
<!-- added by Epicomedia -->
<div class="wc-loading  ajax-loading" style="visibility:hidden;"></div>