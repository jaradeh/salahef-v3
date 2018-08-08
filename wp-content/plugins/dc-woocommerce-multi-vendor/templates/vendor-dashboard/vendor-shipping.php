<?php
/**
 * The template for displaying vendor dashboard
 *
 * Override this template by copying it to yourtheme/dc-product-vendor/vendor-dashboard/vendor-shipping.php
 *
 * @author 		WC Marketplace
 * @package 	WCMp/Templates
 * @version   2.2.0
 */
if (!defined('ABSPATH')) {
    // Exit if accessed directly
    exit;
}
global $woocommerce, $WCMp, $wpdb;

$vendor_user_id = get_current_vendor_id();
$vendor_data = get_wcmp_vendor($vendor_user_id);
if ($vendor_data) :

    $vendor_shipping_data = get_user_meta($vendor_user_id, 'vendor_shipping_data', true);
    ?>
    <div class="col-md-12">
        <form name="vendor_shipping_form" class="wcmp_shipping_form form-horizontal" method="post">
            <?php
            if (version_compare(WC_VERSION, '2.6.0', '>=')) {
                $shipping_class_id = get_user_meta($vendor_user_id, 'shipping_class_id', true);
                if (!$shipping_class_id) {
                    $shipping_term = get_term_by('slug', $vendor_data->user_data->user_login . '-' . $vendor_user_id, 'product_shipping_class', ARRAY_A);
                    if (!$shipping_term) {
                        $shipping_term = wp_insert_term($vendor_data->user_data->user_login . '-' . $vendor_user_id, 'product_shipping_class');
                    }
                    if (!is_wp_error($shipping_term)) {
                        $shipping_term_id = $shipping_term['term_id'];
                        update_user_meta($vendor_user_id, 'shipping_class_id', $shipping_term['term_id']);
                        add_woocommerce_term_meta($shipping_term['term_id'], 'vendor_id', $vendor_user_id);
                        add_woocommerce_term_meta($shipping_term['term_id'], 'vendor_shipping_origin', get_option('woocommerce_default_country'));
                    }
                }
                $shipping_class_id = $shipping_term_id = get_user_meta($vendor_user_id, 'shipping_class_id', true);
                $raw_zones = WC_Shipping_Zones::get_zones();
                $raw_zones[] = array('id' => 0);
                foreach ($raw_zones as $raw_zone) {
                    $zone = new WC_Shipping_Zone($raw_zone['id']);
                    $raw_methods = $zone->get_shipping_methods();
                    foreach ($raw_methods as $raw_method) {
                        if ($raw_method->id == 'flat_rate' && isset($raw_method->instance_form_fields["class_cost_" . $shipping_class_id])) {
                            $instance_field = $raw_method->instance_form_fields["class_cost_" . $shipping_class_id];
                            $instance_settings = $raw_method->instance_settings["class_cost_" . $shipping_class_id];
                            $option_name = 'woocommerce_' . $raw_method->id . "_" . $raw_method->instance_id . "_settings_class_cost_" . $shipping_class_id;
                            ?>

                            <div class="panel panel-default panel-pading pannel-outer-heading">
                                <div class="panel-heading">
                                    <h3><?php echo $zone->get_zone_name(); ?></h3>
                                </div>
                                <div class="panel-body panel-content-padding">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3 col-md-3"><?php echo $instance_field['title'] . ' - ' . $raw_method->title; ?></label>
                                        <div class="col-md-6 col-sm-9">
                                            <input name="vendor_shipping_data[<?php echo $option_name; ?>]" type="text" class="form-control" type="text" step="0.01" value='<?php echo esc_attr($instance_settings); ?>' placeholder="<?php echo $instance_field['placeholder']; ?>" />
                                            <div class="hints">
                                                <?php echo strip_tags($instance_field['description'], '<code>'); ?> <br>
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                            </div>

                            <?php
                        }
                    }
                }
            }
            ?>
            <?php do_action('wcmp_before_shipping_form_end_vendor_dashboard'); ?>
            <div class="wcmp-action-container">
                <button class="wcmp_orange_btn btn btn-default" name="shipping_save"><?php _e('Save Options', 'dc-woocommerce-multi-vendor'); ?></button>
            </div>
            <div class="clear"></div>
        </form>

    </div>
<?php endif; ?>