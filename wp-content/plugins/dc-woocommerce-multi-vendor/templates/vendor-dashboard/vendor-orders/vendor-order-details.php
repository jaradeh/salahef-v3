<?php
/**
 * The template for displaying vendor order detail and called from vendor_order_item.php template
 *
 * Override this template by copying it to yourtheme/dc-product-vendor/vendor-dashboard/vendor-orders/vendor-order-details.php
 *
 * @author 	WC Marketplace
 * @package 	WCMp/Templates
 * @version   2.2.0
 */
if (!defined('ABSPATH')) {
    // Exit if accessed directly    
    exit;
}
global $woocommerce, $WCMp;
$vendor = get_current_vendor();
$order = wc_get_order($order_id);
$vendor_shipping_method = get_wcmp_vendor_order_shipping_method($order->get_id(), $vendor->id);
if (!$order) {
    ?>
    <div class="col-md-12">
        <div class="panel panel-default">
            <?php _e('Invalid order', 'dc-woocommerce-multi-vendor'); ?>
        </div>
    </div>
    <?php
    return;
}
$vendor_items = get_wcmp_vendor_orders(array('order_id' => $order->get_id(), 'vendor_id' => $vendor->id));
$vendor_order_amount = get_wcmp_vendor_order_amount(array('order_id' => $order->get_id(), 'vendor_id' => $vendor->id));
//print_r($vendor_order_amount);die;
$subtotal = 0;
?>





<div class="col-md-12">
    <div class="icon-header">
        <span><i class="wcmp-font ico-order-details-icon"></i></span>
        <h2><?php _e('Order #', 'dc-woocommerce-multi-vendor'); ?><?php echo $order->get_id(); ?></h2>
        <h3><?php _e('was placed on', 'dc-woocommerce-multi-vendor'); ?> <?php echo wcmp_date($order->get_date_created()); ?> <?php _e('and is currently', 'dc-woocommerce-multi-vendor'); ?> <span class="<?php echo $order->get_status(); ?>" style="float:none;"><?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>.</span></h3>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default pannel-outer-heading marginTop-0">
                <div class="panel-heading"><h3><?php _e('Order Details', 'dc-woocommerce-multi-vendor'); ?></h3></div>
                <div class="panel-body panel-content-padding">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><?php _e('Product', 'dc-woocommerce-multi-vendor'); ?></th>
                                <th><?php _e('Total', 'dc-woocommerce-multi-vendor'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($vendor_items as $item): 
                                $item_obj = $order->get_item($item->order_item_id); 
                                $edit_product_link = '';
                                if (current_user_can('edit_published_products') && get_wcmp_vendor_settings('is_edit_delete_published_product', 'capabilities', 'product') == 'Enable') {
                                    $edit_product_link = esc_url(wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_add_product_endpoint', 'vendor', 'general', 'add-product'), $item->product_id));
                                } ?>
                                <tr>
                                    <td><?php echo $edit_product_link ? '<a href="' . $edit_product_link . '" class="wcmp-order-item-link">' . esc_html( $item_obj->get_name() ) . '</a>' : esc_html( $item_obj->get_name() ); ?> <small class="times">&times;</small> <?php echo esc_html( $item_obj->get_quantity() ); ?></td>
                                    <td><?php echo wc_price( $item_obj->get_total(), array( 'currency' => $order->get_currency() ) ); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td><?php _e('Commission:', 'dc-woocommerce-multi-vendor'); ?></td>
                                <td><?php echo wc_price($vendor_order_amount['commission_amount']); ?></td>
                            </tr>
                            <tr>
                                <td><?php _e('Shipping:', 'dc-woocommerce-multi-vendor'); ?></td>
                                <td><?php echo wc_price($vendor_order_amount['shipping_amount']); ?><?php if($vendor_shipping_method) echo __(' via ', 'dc-woocommerce-multi-vendor') . $vendor_shipping_method->get_name(); ?></td>
                            </tr>
                            <tr>
                                <td><?php _e('All Tax:', 'dc-woocommerce-multi-vendor'); ?></td>
                                <td><?php echo wc_price($vendor_order_amount['tax_amount'] + $vendor_order_amount['shipping_tax_amount']); ?></td>
                            </tr>
                            <tr>
                                <td><?php _e('Payment method:', 'dc-woocommerce-multi-vendor'); ?></td>
                                <td><?php echo $order->get_payment_method_title(); ?></td>
                            </tr>
                            <tr>
                                <td><?php _e('Total Earning:', 'dc-woocommerce-multi-vendor'); ?></td>
                                <td><?php echo wc_price($vendor_order_amount['total']); ?></td>
                            </tr>
                            <tr>
                                <td><?php _e('Customer Note:', 'dc-woocommerce-multi-vendor'); ?></td>
                                <td><?php echo $order->get_customer_note(); ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>  
            <div class="panel panel-default pannel-outer-heading">
                <?php if(apply_filters('is_vendor_can_see_order_billing_address', true, $vendor->id)) :?>
                <div class="panel-heading">
                    <h3><?php _e('Billing &amp; Shipping address', 'dc-woocommerce-multi-vendor'); ?></h3>
                </div>
                <div class="panel-body panel-content-padding address-holder">
                    <div class="row">
                        <div class="col-xs-6">
                            <h2><?php _e('Billing address', 'dc-woocommerce-multi-vendor'); ?></h2>
                            <address>
                                <?php echo ( $address = $order->get_formatted_billing_address() ) ? $address : __('N/A', 'dc-woocommerce-multi-vendor'); ?>
                                <?php if ($order->get_billing_phone() || apply_filters('show_customer_billing_phone_for_vendor', true)) : ?>
                                    <p class="woocommerce-customer-details-phone"><?php echo esc_html($order->get_billing_phone()); ?></p>
                                <?php endif; ?>
                                <?php if ($order->get_billing_email() || apply_filters('show_customer_billing_email_for_vendor', true)) : ?>
                                    <p class="woocommerce-customer-details-email"><?php echo esc_html($order->get_billing_email()); ?></p>
                                <?php endif; ?>
                            </address>
                        </div>
                        <?php endif; ?>
                        <?php if(apply_filters('is_vendor_can_see_order_shipping_address', true, $vendor->id)) :?>
                        <div class="col-xs-6">
                            <h2><?php _e('Shipping address', 'dc-woocommerce-multi-vendor'); ?></h2>
                            <address>
                            <?php echo ( $address = $order->get_formatted_shipping_address() ) ? $address : __('N/A', 'dc-woocommerce-multi-vendor'); ?>
                            </address>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>      
        </div>
        <div class="col-md-4">
            <?php
            $vendor_comments = $order->get_customer_order_notes();
            if (apply_filters('is_vendor_can_view_order_notes', true, $vendor->id)) { ?>
            <h3><?php _e('Order notes :', 'dc-woocommerce-multi-vendor'); ?></h3>
            <ul class="list-group">
                <?php  
                    if($vendor_comments){         
                        foreach ($vendor_comments as $comment) {
                        $comment_vendor = get_comment_meta($comment->comment_ID, '_vendor_id', true);
                        if ($comment_vendor && $comment_vendor != $vendor->id) {
                            continue;
                        }
                        $last_added = human_time_diff(strtotime($comment->comment_date), current_time('timestamp'));
                        ?>
                        <li class="list-group-item list-group-item-action flex-column align-items-start">
                            <p><?php printf(__('Added %s ago', 'dc-woocommerce-multi-vendor'), $last_added); ?></p>
                            <p><?php echo $comment->comment_content; ?></p>
                        </li>
                    <?php
                        } } 
                    ?>
                <li class="list-group-item list-group-item-action flex-column align-items-start">
                    <?php if(apply_filters('is_vendor_can_add_order_notes', true, $vendor->id)) :?>
                    <?php endif; ?>  
                    <form method="post" name="add_comment">
                        <?php wp_nonce_field('dc-vendor-add-order-comment', 'vendor_add_order_nonce'); ?>
                        <div class="form-group">
                            <textarea placeholder="<?php _e('Add Note', 'dc-woocommerce-multi-vendor'); ?>" required class="form-control" name="comment_text"></textarea>
                            <input type="hidden" name="order_id" value="<?php echo $order->get_id(); ?>">
                        </div>
                        <input class="btn btn-default wcmp-add-order-note" type="submit" name="wcmp_submit_comment" value="<?php _e('Submit', 'dc-woocommerce-multi-vendor'); ?>">
                    </form>              
                </li>
            </ul>
            <?php } ?>
        </div>
    </div>
</div>