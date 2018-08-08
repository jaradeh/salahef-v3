<?php

/**
 *  WCMPp Vendor Admin Dashboard - Vendor WP-Admin Dashboard Pages
 * 
 * @version	2.2.0
 * @package WCMp
 * @author  WC Marketplace
 */
Class WCMp_Admin_Dashboard {

    private $wcmp_vendor_order_page;

    function __construct() {

        // Add Shop Settings page 
        add_action('admin_menu', array($this, 'vendor_dashboard_pages'));

        add_action('woocommerce_product_options_shipping', array($this, 'wcmp_product_options_shipping'), 5);

        add_action('wp_before_admin_bar_render', array($this, 'remove_admin_bar_links'));

        add_action('wp_footer', 'wcmp_remove_comments_section_from_vendor_dashboard');

        add_action('wcmp_dashboard_setup', array(&$this, 'wcmp_dashboard_setup'), 5);
        add_action('wcmp_dashboard_widget', array(&$this, 'do_wcmp_dashboard_widget'));

        // Init export functions
        $this->export_csv();

        // Init submit comment
        $this->submit_comment();

        $this->vendor_withdrawl();

        $this->export_vendor_orders_csv();
        // vendor tools handler
        $this->vendor_tools_handler();
    }

    function remove_admin_bar_links() {
        global $wp_admin_bar;
        if (!current_user_can('manage_options')) {
            $wp_admin_bar->remove_menu('new-post');
            $wp_admin_bar->remove_menu('new-dc_commission');
            $wp_admin_bar->remove_menu('comments');
        }
    }

    /**
     * Vendor Commission withdrawl
     */
    public function vendor_withdrawl() {
        global $WCMp;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['vendor_get_paid'])) {
                $vendor = get_wcmp_vendor(get_current_vendor_id());
                $commissions = isset($_POST['commissions']) ? $_POST['commissions'] : array();     
                if (!empty($commissions)) {
                    $payment_method = get_user_meta($vendor->id, '_vendor_payment_mode', true);
                    if ($payment_method) {
                        if (array_key_exists($payment_method, $WCMp->payment_gateway->payment_gateways)) {
                            $response = $WCMp->payment_gateway->payment_gateways[$payment_method]->process_payment($vendor, $commissions, 'manual');
                            if ($response) {
                                if (isset($response['transaction_id'])) {
                                    $redirect_url = wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_transaction_details_endpoint', 'vendor', 'general', 'transaction-details'), $response['transaction_id']);
                                    $notice = $this->get_wcmp_transaction_notice($response['transaction_id']);
                                    if (isset($notice['type'])) {
                                        wc_add_notice($notice['message'], $notice['type']);
                                    }
                                    wp_safe_redirect($redirect_url);
                                    exit;
                                } else {
                                    foreach ($response as $message) {
                                        wc_add_notice($message['message'], $message['type']);
                                    }
                                }
                            } else {
                                wc_add_notice(__('Oops! Something went wrong please try again later', 'dc-woocommerce-multi-vendor'), 'error');
                            }
                        } else {
                            wc_add_notice(__('Invalid payment method', 'dc-woocommerce-multi-vendor'), 'error');
                        }
                    } else {
                        wc_add_notice(__('No payment method has been selected for commission withdrawal', 'dc-woocommerce-multi-vendor'), 'error');
                    }
                } else {
                    wc_add_notice(__('Please select atleast one or more commission.', 'dc-woocommerce-multi-vendor'), 'error');
                }
            }
        }
    }

    public function get_wcmp_transaction_notice($transaction_id) {
        $transaction = get_post($transaction_id);
        $notice = array();
        switch ($transaction->post_status) {
            case 'wcmp_processing':
                $notice = array('type' => 'success', 'message' => __('Your withdrawal request has been sent to the admin and your commission will be disbursed shortly!', 'dc-woocommerce-multi-vendor'));
                break;
            case 'wcmp_completed':
                $notice = array('type' => 'success', 'message' => __('Congrats! You have successfully received your commission amount.', 'dc-woocommerce-multi-vendor'));
                break;
            case 'wcmp_canceled':
                $notice = array('type' => 'error', 'message' => __('Oops something went wrong! Your commission withdrawal request was declined!', 'dc-woocommerce-multi-vendor'));
                break;
            default :
                break;
        }
        return apply_filters('wcmp_get_transaction_status_notice', $notice, $transaction);
    }

    /**
     * Export CSV from vendor dasboard page
     *
     * @access public
     * @return void
     */
    public function export_csv() {
        global $WCMp;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (isset($_POST['export_transaction'])) {
                $transaction_details = array();
                if (!empty($_POST['transaction_ids'])) {
                    $date = date('Y-m-d');
                    $filename = 'TransactionReport-' . $date . '.csv';
                    header("Pragma: public");
                    header("Expires: 0");
                    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                    header("Content-Type: application/force-download");
                    header("Content-Type: application/octet-stream");
                    header("Content-Type: application/download");
                    header("Content-Disposition: attachment;filename={$filename}");
                    header("Content-Transfer-Encoding: binary");
                    header("Content-Type: charset=UTF-8");

                    $headers = array(
                        'date' => __('Date', 'dc-woocommerce-multi-vendor'),
                        'trans_id' => __('Transaction ID', 'dc-woocommerce-multi-vendor'),
                        'commission_ids' => __('Commission IDs', 'dc-woocommerce-multi-vendor'),
                        'mode' => __('Mode', 'dc-woocommerce-multi-vendor'),
                        'commission' => __('Commission', 'dc-woocommerce-multi-vendor'),
                        'fee' => __('Fee', 'dc-woocommerce-multi-vendor'),
                        'credit' => __('Credit', 'dc-woocommerce-multi-vendor'),
                    );
                    if (!empty($_POST['transaction_ids'])) {
                        foreach ($_POST['transaction_ids'] as $transaction_id) {
                            $commission_details = get_post_meta($transaction_id, 'commission_detail', true);
                            $transfer_charge = get_post_meta($transaction_id, 'transfer_charge', true) + get_post_meta($transaction_id, 'gateway_charge', true);
                            $transaction_amt = get_post_meta($transaction_id, 'amount', true) - get_post_meta($transaction_id, 'transfer_charge', true) - get_post_meta($transaction_id, 'gateway_charge', true);
                            $transaction_commission = get_post_meta($transaction_id, 'amount', true);

                            $mode = get_post_meta($transaction_id, 'transaction_mode', true);
                            if ($mode == 'paypal_masspay' || $mode == 'paypal_payout') {
                                $transaction_mode = __('PayPal', 'dc-woocommerce-multi-vendor');
                            } else if ($mode == 'stripe_masspay') {
                                $transaction_mode = __('Stripe', 'dc-woocommerce-multi-vendor');
                            } else if ($mode == 'direct_bank') {
                                $transaction_mode = __('Direct Bank Transfer', 'dc-woocommerce-multi-vendor');
                            } else {
                                $transaction_mode = $mode;
                            }

                            $order_datas[] = array(
                                'date' => get_the_date('Y-m-d', $transaction_id),
                                'trans_id' => '#' . $transaction_id,
                                'order_ids' => '#' . implode(', #', $commission_details),
                                'mode' => $transaction_mode,
                                'commission' => $transaction_commission,
                                'fee' => $transfer_charge,
                                'credit' => $transaction_amt,
                            );
                        }
                    }


                    // Initiate output buffer and open file
                    ob_start();
                    $file = fopen("php://output", 'w');

                    // Add headers to file
                    fputcsv($file, $headers);
                    // Add data to file
                    if (!empty($order_datas)) {
                        foreach ($order_datas as $order_data) {
                            fputcsv($file, $order_data);
                        }
                    } else {
                        fputcsv($file, array(__('Sorry. no transaction data is available upon your selection', 'dc-woocommerce-multi-vendor')));
                    }

                    // Close file and get data from output buffer
                    fclose($file);
                    $csv = ob_get_clean();

                    // Send CSV to browser for download
                    echo $csv;
                    die();
                } else {
                    wc_add_notice(__('Please select atleast one and more transactions.', 'dc-woocommerce-multi-vendor'), 'error');
                }
            }
            $user = wp_get_current_user();
            $vendor = get_wcmp_vendor($user->ID);
            if (isset($_POST['wcmp_stat_export']) && !empty($_POST['wcmp_stat_export']) && $vendor && apply_filters('can_wcmp_vendor_export_orders_csv', true, $vendor->id)) {
                $vendor = apply_filters('wcmp_order_details_export_vendor', $vendor);
                $start_date = isset($_POST['wcmp_stat_start_dt']) ? $_POST['wcmp_stat_start_dt'] : date('Y-m-01');
                $end_date = isset($_POST['wcmp_stat_end_dt']) ? $_POST['wcmp_stat_end_dt'] : date('Y-m-t');
                $start_date = strtotime('-1 day', strtotime($start_date));
                $end_date = strtotime('+1 day', strtotime($end_date));
                $query = array(
                    'date_query' => array(
                        array(
                            'after' => array('year' => date('Y', $start_date), 'month' => date('m', $start_date), 'day' => date('d', $start_date)),
                            'before' => array('year' => date('Y', $end_date), 'month' => date('m', $end_date), 'day' => date('d', $end_date)),
                            'inclusive' => true,
                        )
                    )
                );
                $records = $vendor->get_orders(false, false, $query);
                if (!empty($records) && is_array($records)) {
                    $vendor_orders = array_unique($records);
                    if (!empty($vendor_orders))
                        $this->generate_csv($vendor_orders, $vendor);
                }
            }
        }
    }

    public function generate_csv($customer_orders, $vendor, $args = array()) {
        global $WCMp;
        $order_datas = array();
        $index = 0;
        $date = date('Y-m-d');
        $default = array(
            'filename' => 'SalesReport-' . $date . '.csv',
            'iostream' => 'php://output',
            'buffer' => 'w',
            'action' => 'download',
        );
        $args = wp_parse_args($args, $default);

        $filename = $args['filename'];
        if ($args['action'] == 'download') {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename={$filename}");
            header("Content-Transfer-Encoding: binary");
        }

        $headers = apply_filters('wcmp_vendor_order_generate_csv_headers', array(
            'order' => __('Order', 'dc-woocommerce-multi-vendor'),
            'date_of_purchase' => __('Date of Purchase', 'dc-woocommerce-multi-vendor'),
            'time_of_purchase' => __('Time Of Purchase', 'dc-woocommerce-multi-vendor'),
            'vendor_name' => __('Vendor Name', 'dc-woocommerce-multi-vendor'),
            'product' => __('Items bought', 'dc-woocommerce-multi-vendor'),
            'qty' => __('Quantity', 'dc-woocommerce-multi-vendor'),
            'discount_used' => __('Discount Used', 'dc-woocommerce-multi-vendor'),
            'tax' => __('Tax', 'dc-woocommerce-multi-vendor'),
            'shipping' => __('Shipping', 'dc-woocommerce-multi-vendor'),
            'commission_share' => __('Earning', 'dc-woocommerce-multi-vendor'),
            'payment_system' => __('Payment System', 'dc-woocommerce-multi-vendor'),
            'buyer_name' => __('Customer Name', 'dc-woocommerce-multi-vendor'),
            'buyer_email' => __('Customer Email', 'dc-woocommerce-multi-vendor'),
            'buyer_contact' => __('Customer Contact', 'dc-woocommerce-multi-vendor'),
            'billing_address' => __('Billing Address Details', 'dc-woocommerce-multi-vendor'),
            'shipping_address' => __('Shipping Address Details', 'dc-woocommerce-multi-vendor'),
            'order_status' => __('Order Status', 'dc-woocommerce-multi-vendor'),
        ));

        if (!apply_filters('show_customer_details_in_export_orders', true, $vendor->id)) {
            unset($headers['buyer_name']);
            unset($headers['buyer_email']);
            unset($headers['buyer_contact']);
        }
        if (!apply_filters('show_customer_billing_address_in_export_orders', true, $vendor->id)) {
            unset($headers['billing_address']);
        }
        if (!apply_filters('show_customer_shipping_address_in_export_orders', true, $vendor->id)) {
            unset($headers['shipping_address']);
        }

        if ($vendor) {
            if (!empty($customer_orders)) {
                foreach ($customer_orders as $commission_id => $customer_order) {
                    $order = new WC_Order($customer_order);
                    $vendor_items = $vendor->get_vendor_items_from_order($customer_order, $vendor->term_id);
                    $item_names = $item_qty = array();
                    if (sizeof($vendor_items) > 0) {
                        foreach ($vendor_items as $item) {
                            $item_names[] = $item['name'];
                            $item_qty[] = $item['quantity'];
                        }

                        //coupons count
                        $coupon_used = '';
                        $coupons = $order->get_items('coupon');
                        foreach ($coupons as $coupon_item_id => $item) {
                            $coupon = new WC_Coupon(trim($item['name']));
                            $coupon_post = get_post($coupon->get_id());
                            $author_id = $coupon_post->post_author;
                            if ($vendor->id == $author_id) {
                                $coupon_used .= $item['name'] . ', ';
                            }
                        }

                        // Formatted Addresses
                        $formatted_billing_address = apply_filters('woocommerce_order_formatted_billing_address', array(
                            'address_1' => $order->get_billing_address_1(),
                            'address_2' => $order->get_billing_address_2(),
                            'city' => $order->get_billing_city(),
                            'state' => $order->get_billing_state(),
                            'postcode' => $order->get_billing_postcode(),
                            'country' => $order->get_billing_country()
                                ), $order);
                        $formatted_billing_address = WC()->countries->get_formatted_address($formatted_billing_address);

                        $formatted_shipping_address = apply_filters('woocommerce_order_formatted_shipping_address', array(
                            'address_1' => $order->get_shipping_address_1(),
                            'address_2' => $order->get_shipping_address_2(),
                            'city' => $order->get_shipping_city(),
                            'state' => $order->get_shipping_state(),
                            'postcode' => $order->get_shipping_postcode(),
                            'country' => $order->get_shipping_country()
                                ), $order);
                        $formatted_shipping_address = WC()->countries->get_formatted_address($formatted_shipping_address);

                        $customer_name = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
                        $customer_email = $order->get_billing_email();
                        $customer_phone = $order->get_billing_phone();

                        $order_datas[$index] = apply_filters('wcmp_vendor_order_generate_csv_data', array(
                            'order' => '#' . $customer_order,
                            'date_of_purchase' => date_i18n('Y-m-d', strtotime($order->get_date_created())),
                            'time_of_purchase' => date_i18n('H', strtotime($order->get_date_created())) . ' : ' . date_i18n('i', strtotime($order->get_date_created())),
                            'vendor_name' => $vendor->page_title,
                            'product' => implode( ', ', $item_names ),
                            'qty' => implode( ', ', $item_qty ),
                            'discount_used' => apply_filters('wcmp_export_discount_used_in_order', $coupon_used),
                            'tax' => get_post_meta($commission_id, '_tax', true),
                            'shipping' => get_post_meta($commission_id, '_shipping', true),
                            'commission_share' => get_post_meta($commission_id, '_commission_amount', true),
                            'payment_system' => $order->get_payment_method_title(),
                            'buyer_name' => $customer_name,
                            'buyer_email' => $customer_email,
                            'buyer_contact' => $customer_phone,
                            'billing_address' => str_replace('<br/>', ', ', $formatted_billing_address),
                            'shipping_address' => str_replace('<br/>', ', ', $formatted_shipping_address),
                            'order_status' => $order->get_status(),
                                ), $customer_order, $vendor);
                        if (!apply_filters('show_customer_details_in_export_orders', true, $vendor->id)) {
                            unset($order_datas[$index]['buyer_name']);
                            unset($order_datas[$index]['buyer_email']);
                            unset($order_datas[$index]['buyer_contact']);
                        }
                        if (!apply_filters('show_customer_billing_address_in_export_orders', true, $vendor->id)) {
                            unset($order_datas[$index]['billing_address']);
                        }
                        if (!apply_filters('show_customer_shipping_address_in_export_orders', true, $vendor->id)) {
                            unset($order_datas[$index]['shipping_address']);
                        }
                        $index++;
                    }
                }
            }
        }
        // Initiate output buffer and open file
        ob_start();
        if ($args['action'] == 'download' && $args['iostream'] == 'php://output') {
            $file = fopen($args['iostream'], $args['buffer']);
        } elseif ($args['action'] == 'temp' && $args['filename']) {
            $filename = sys_get_temp_dir() . '/' . $args['filename'];
            $file = fopen($filename, $args['buffer']);
        }
        // Add headers to file
        fputcsv($file, $headers);
        // Add data to file
        foreach ($order_datas as $order_data) {
            if (!$WCMp->vendor_caps->vendor_capabilities_settings('is_order_show_email') || apply_filters('is_not_show_email_field', true)) {
                unset($order_data['buyer']);
            }
            fputcsv($file, $order_data);
        }

        // Close file and get data from output buffer
        fclose($file);
        $csv = ob_get_clean();
        if ($args['action'] == 'temp') {
            return $filename;
        } else {
            // Send CSV to browser for download
            echo $csv;
            die();
        }
    }

    /**
     * Submit Comment 
     *
     * @access public
     * @return void
     */
    public function submit_comment() {
        global $WCMp;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!empty($_POST['wcmp_submit_comment'])) {
                // verify nonce
                if ($_POST['vendor_add_order_nonce'] && !wp_verify_nonce($_POST['vendor_add_order_nonce'], 'dc-vendor-add-order-comment'))
                    return false;
                $vendor = get_current_vendor();
                // Don't submit empty comments
                if (empty($_POST['comment_text']))
                    return false;
                // Only submit if the order has the product belonging to this vendor
                $order = wc_get_order($_POST['order_id']);
                $comment = esc_textarea($_POST['comment_text']);
                $comment_id = $order->add_order_note($comment, 1);
                add_comment_meta($comment_id, '_vendor_id', $vendor->id);
                wp_redirect(esc_url(wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_vendor_orders_endpoint', 'vendor', 'general', 'vendor-orders'), $order->get_id())));
                die();
            }
        }
    }

    /**
     * Vendor tools handler 
     *
     * @access public
     * @return void
     */
    public function vendor_tools_handler() {
        $vendor = get_current_vendor();
        $wpnonce = isset($_REQUEST['_wpnonce']) ? $_REQUEST['_wpnonce'] : '';
        $tools_action = isset($_REQUEST['tools_action']) ? $_REQUEST['tools_action'] : '';
        if ($wpnonce && wp_verify_nonce($wpnonce, 'wcmp_clear_vendor_transients') && $tools_action && $tools_action == 'clear_all_transients') {
            if (current_user_can('delete_published_products')) {
                if ($vendor->clear_all_transients($vendor->id)) {
                    wc_add_notice(__('Vendor transients cleared!', 'dc-woocommerce-multi-vendor'), 'success');
                }
                wp_redirect(esc_url(wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_vendor_tools_endpoint', 'vendor', 'general', 'vendor-tools'))));
                die();
            }
        }
        do_action('wcmp_vendor_tools_handler', $tools_action, $wpnonce);
    }

    public function vendor_dashboard_pages() {
        $user = wp_get_current_user();
        $vendor = get_wcmp_vendor($user->ID);
        $vendor = apply_filters('wcmp_vendor_dashboard_pages_vendor', $vendor);
        if ($vendor) {
            $order_page = apply_filters('wcmp_vendor_view_order_page', true);
            if ($order_page) {
                $hook = add_menu_page(__('Orders', 'dc-woocommerce-multi-vendor'), __('Orders', 'dc-woocommerce-multi-vendor'), 'read', 'dc-vendor-orders', array($this, 'wcmp_vendor_orders_page'));
                add_action("load-$hook", array($this, 'add_order_page_options'));
            }

            $shipping_page = apply_filters('wcmp_vendor_view_shipping_page', true);
            if ($vendor->is_shipping_tab_enable() && $shipping_page) {
                add_menu_page(__('Shipping', 'dc-woocommerce-multi-vendor'), __('Shipping', 'dc-woocommerce-multi-vendor'), 'read', 'dc-vendor-shipping', array($this, 'shipping_page'));
            }
        }
    }

    /**
     * HTML setup for the Orders Page 
     */
    public static function shipping_page() {
        $vendor_user_id = apply_filters('wcmp_dashboard_shipping_vendor', get_current_vendor_id());

        $vendor_data = get_wcmp_vendor($vendor_user_id);
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
        ?>
        <div class="wrap">
            <div id="icon-woocommerce" class="icon32 icon32-woocommerce-reports"><br/></div>
            <h2><?php _e('Shipping', 'dc-woocommerce-multi-vendor'); ?></h2>
            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if (isset($_POST['vendor_shipping_data'])) {
                    if (version_compare(WC_VERSION, '2.6.0', '>=')) {
                        $shipping_class_id = get_user_meta($vendor_user_id, 'shipping_class_id', true);
                        $raw_zones = WC_Shipping_Zones::get_zones();
                        $raw_zones[] = array('id' => 0);
                        foreach ($raw_zones as $raw_zone) {
                            $zone = new WC_Shipping_Zone($raw_zone['id']);
                            $raw_methods = $zone->get_shipping_methods();
                            foreach ($raw_methods as $raw_method) {
                                if ($raw_method->id == 'flat_rate') {
                                    $option_name = "woocommerce_" . $raw_method->id . "_" . $raw_method->instance_id . "_settings";
                                    $shipping_details = get_option($option_name);
                                    $class = "class_cost_" . $shipping_class_id;
                                    $shipping_details[$class] = stripslashes($_POST['vendor_shipping_data'][$option_name . '_' . $class]);
                                    update_option($option_name, $shipping_details);
                                }
                            }
                        }
                        if (update_user_meta($vendor_user_id, 'vendor_shipping_data', $_POST['vendor_shipping_data'])) {
                            echo '<div class="updated settings-error notice is-dismissible"><p><strong>' . __("Shipping Data Updated", 'dc-woocommerce-multi-vendor') . '</strong></p></div>';
                        }
                    }
                }
            }
            ?>

            <form name="vendor_shipping_form" method="post">
                <table>
                    <tbody>
                        <?php
                        if (version_compare(WC_VERSION, '2.6.0', '>=')) {
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
                                        echo '<tr><td><h2>Shipping Zone : ' . $zone->get_zone_name() . '</h2></td></tr>';
                                        ?>
                                        <tr>
                                            <td>
                                                <label><?php echo $instance_field['title'] . ' - ' . $raw_method->title; ?></label>
                                            </td>
                                            <td>
                                                <input name="vendor_shipping_data[<?php echo $option_name; ?>]" type="text" value='<?php echo $instance_settings; ?>' placeholder="<?php echo $instance_field['placeholder']; ?>" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                        <?php echo strip_tags($instance_field['description'], '<code>'); ?> <br><br>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                            }
                        }
                        ?>						
                    </tbody>
                </table>
        <?php do_action('wcmp_vendor_shipping_settings'); ?>
        <?php submit_button(); ?>
            </form>
            <br class="clear"/>
        </div>
        <?php
    }

    /**
     *
     *
     * @param unknown $status
     * @param unknown $option
     * @param unknown $value
     *
     * @return unknown
     */
    public static function set_table_option($status, $option, $value) {
        if ($option == 'orders_per_page') {
            return $value;
        }
    }

    /**
     * Add order page options
     * Defined cores in Vendor Order Page class
     */
    public function add_order_page_options() {
        global $WCMp;
        $args = array(
            'label' => 'Rows',
            'default' => 10,
            'option' => 'orders_per_page'
        );
        add_screen_option('per_page', $args);

        $WCMp->load_class('vendor-order-page');
        $this->wcmp_vendor_order_page = new WCMp_Vendor_Order_Page();
    }

    /**
     * Generate Orders Page view 
     */
    public function wcmp_vendor_orders_page() {
        $this->wcmp_vendor_order_page->wcmp_prepare_order_page_items();
        ?>
        <div class="wrap">

            <div id="icon-woocommerce" class="icon32 icon32-woocommerce-reports"><br/></div>
            <h2><?php _e('Orders', 'dc-woocommerce-multi-vendor'); ?></h2>

            <form id="posts-filter" method="get">

                <input type="hidden" name="page" value="dc-vendor-orders"/>
        <?php $this->wcmp_vendor_order_page->display(); ?>

            </form>
            <div id="ajax-response"></div>
            <br class="clear"/>
        </div>
        <?php
    }

    function wcmp_product_options_shipping() {
        global $post;
        if (!is_user_wcmp_vendor(get_current_user_id())) {
            return;
        }
        $product_object = wc_get_product($post->ID);
        $args = array(
            'taxonomy' => 'product_shipping_class',
            'hide_empty' => 0,
            'meta_query' => array(
                array(
                    'key' => 'vendor_id',
                    'value' => get_current_vendor_id(),
                    'compare' => '='
                )
            ),
            'show_option_none' => __('No shipping class', 'dc-woocommerce-multi-vendor'),
            'name' => 'product_shipping_class',
            'id' => 'product_shipping_class',
            'selected' => $product_object->get_shipping_class_id('edit'),
            'class' => 'select short',
        );
        ?>
        <p class="form-field dimensions_field">
            <label for="product_shipping_class"><?php _e('Shipping class', 'dc-woocommerce-multi-vendor'); ?></label>
        <?php wp_dropdown_categories($args); ?>
        <?php echo wc_help_tip(__('Shipping classes are used by certain shipping methods to group similar products.', 'dc-woocommerce-multi-vendor')); ?>
        </p>
        <script type="text/javascript">
            jQuery('#product_shipping_class').closest("p").remove();
        </script>
        <?php
    }

    public function export_vendor_orders_csv() {
        global $wpdb;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['wcmp_download_vendor_order_csv'])) {
                $vendor = get_current_vendor();
                $order_data = array();
                $order_ids = isset($_POST['selected_orders']) ? $_POST['selected_orders'] : array();
                if ($order_ids && count($order_ids) > 0) {
                    foreach ($order_ids as $order_id) {
                        $vendor_orders = $wpdb->get_results("SELECT DISTINCT commission_id from `{$wpdb->prefix}wcmp_vendor_orders` where vendor_id = " . $vendor->id . " AND order_id = " . $order_id, ARRAY_A);
                        $commission_id = $vendor_orders[0]['commission_id'];
                        $order_data[$commission_id] = $order_id;
                    }
                    if (!empty($order_data)) {
                        $this->generate_csv($order_data, $vendor);
                    }
                } else {
                    wc_add_notice(__('Please select atleast one and more order.', 'dc-woocommerce-multi-vendor'), 'error');
                }
            }
        }
    }

    public function is_order_shipped($order_id, $vendor) {
        global $WCMp, $wpdb;
        $shipping_status = $wpdb->get_results("SELECT DISTINCT shipping_status from `{$wpdb->prefix}wcmp_vendor_orders` where vendor_id = " . $vendor->id . " AND order_id = " . $order_id, ARRAY_A);
        $shipping_status = $shipping_status[0]['shipping_status'];
        if ($shipping_status == 0)
            return false;
        if ($shipping_status == 1)
            return true;
    }

    public function save_store_settings($user_id, $post) {
        global $WCMp;
        $vendor = get_wcmp_vendor($user_id);
        $fields = $WCMp->user->get_vendor_fields($user_id);
        foreach ($fields as $fieldkey => $value) {

            if (isset($post[$fieldkey])) {
                if ($fieldkey == "vendor_page_slug" && !empty($post[$fieldkey])) {
                    if ($vendor && !$vendor->update_page_slug(wc_clean($_POST[$fieldkey]))) {
                        if (is_admin()) {
                            echo _e('Slug already exists', 'dc-woocommerce-multi-vendor');
                        } else {
                            $err_msg = __('Slug already exists', 'dc-woocommerce-multi-vendor');
                            return $err_msg;
                        }
                    } else {
                        update_user_meta($user_id, '_' . $fieldkey, wc_clean($post[$fieldkey]));
                    }
                    continue;
                }
                if ($fieldkey == "vendor_page_slug" && empty($post[$fieldkey])) {
                    if (is_admin()) {
                        echo _e('Slug can not be empty', 'dc-woocommerce-multi-vendor');
                    } else {
                        $err_msg = __('Slug can not be empty', 'dc-woocommerce-multi-vendor');
                        return $err_msg;
                    }
                }

                if ($fieldkey == 'vendor_description') {
                    update_user_meta($user_id, '_' . $fieldkey, $post[$fieldkey]);
                } else {
                    // social url validation
                    if (in_array($fieldkey, array('vendor_fb_profile', 'vendor_twitter_profile', 'vendor_google_plus_profile', 'vendor_linkdin_profile', 'vendor_youtube', 'vendor_instagram'))) {
                        if (!empty($post[$fieldkey]) && filter_var($post[$fieldkey], FILTER_VALIDATE_URL)) {
                            update_user_meta($user_id, '_' . $fieldkey, $post[$fieldkey]);
                        } else {
                            update_user_meta($user_id, '_' . $fieldkey, '');
                        }
                    } else {
                        update_user_meta($user_id, '_' . $fieldkey, $post[$fieldkey]);
                    }
                }
                if ($fieldkey == 'vendor_page_title' && empty($post[$fieldkey])) {
                    if (is_admin()) {
                        echo _e('Shop Title can not be empty', 'dc-woocommerce-multi-vendor');
                    } else {
                        $err_msg = __('Shop Title can not be empty', 'dc-woocommerce-multi-vendor');
                        return $err_msg;
                    }
                }
                if ($fieldkey == 'vendor_page_title') {
                    if (!$vendor->update_page_title(wc_clean($post[$fieldkey]))) {
                        if (is_admin()) {
                            echo _e('Shop Title Update Error', 'dc-woocommerce-multi-vendor');
                        } else {
                            $err_msg = __('Shop Title Update Error', 'dc-woocommerce-multi-vendor');
                            return $err_msg;
                        }
                    } else {
                        if(apply_filters('wcmp_update_user_display_name_with_vendor_store_name', false, $user_id)){
                            wp_update_user(array('ID' => $user_id, 'display_name' => $post[$fieldkey]));
                        }
                    }
                }
            }
        }
        if (isset($_POST['_shop_template']) && !empty($_POST['_shop_template'])) {
            update_user_meta($user_id, '_shop_template', $_POST['_shop_template']);
        }
        if (isset($_POST['_store_location']) && !empty($_POST['_store_location'])) {
            update_user_meta($user_id, '_store_location', $_POST['_store_location']);
        }
        if (isset($_POST['_store_lat']) && !empty($_POST['_store_lat'])) {
            update_user_meta($user_id, '_store_lat', $_POST['_store_lat']);
        }
        if (isset($_POST['_store_lng']) && !empty($_POST['_store_lng'])) {
            update_user_meta($user_id, '_store_lng', $_POST['_store_lng']);
        }
        if (isset($_POST['timezone_string']) && !empty($_POST['timezone_string'])) {
            if (!empty($_POST['timezone_string']) && preg_match('/^UTC[+-]/', $_POST['timezone_string'])) {
                $_POST['gmt_offset'] = $_POST['timezone_string'];
                $_POST['gmt_offset'] = preg_replace('/UTC\+?/', '', $_POST['gmt_offset']);
                $_POST['timezone_string'] = '';
            } else{
                $_POST['gmt_offset'] = 0;
            }
            update_user_meta($user_id, 'timezone_string', $_POST['timezone_string']);
            update_user_meta($user_id, 'gmt_offset', $_POST['gmt_offset']);
        }
    }

    /**
     * Save Vendor Shipping data
     * @global type $WCMp
     * @param type $vendor_user_id
     * @param type $post
     */
    public function save_vendor_shipping($vendor_user_id, $post) {
        global $WCMp;
        if (version_compare(WC_VERSION, '2.6.0', '>=') && isset($_POST['vendor_shipping_data'])) {
            $shipping_class_id = get_user_meta($vendor_user_id, 'shipping_class_id', true);
            $raw_zones = WC_Shipping_Zones::get_zones();
            $raw_zones[] = array('id' => 0);
            foreach ($raw_zones as $raw_zone) {
                $zone = new WC_Shipping_Zone($raw_zone['id']);
                $raw_methods = $zone->get_shipping_methods();
                foreach ($raw_methods as $raw_method) {
                    if ($raw_method->id == 'flat_rate') {
                        $option_name = "woocommerce_" . $raw_method->id . "_" . $raw_method->instance_id . "_settings";
                        $shipping_details = get_option($option_name);
                        $class = "class_cost_" . $shipping_class_id;
                        $shipping_details[$class] = stripslashes($_POST['vendor_shipping_data'][$option_name . '_' . $class]);
                        update_option($option_name, $shipping_details);
                    }
                }
            }
            $shipping_updt = update_user_meta($vendor_user_id, 'vendor_shipping_data', $_POST['vendor_shipping_data']);
            if ($shipping_updt) {
                wc_add_notice(__('Shipping Data Updated', 'dc-woocommerce-multi-vendor'), 'success');
            } else {
                wc_add_notice(__('Shipping Data Not Updated', 'dc-woocommerce-multi-vendor'), 'success');
                delete_user_meta($vendor_user_id, 'vendor_shipping_data');
            }
        }
    }

    /**
     * Save Vendor Profile data
     * @since 3.1.0
     * @global type $WCMp
     * @param type $vendor_user_id
     * @param type $post
     */
    public function save_vendor_profile($vendor_user_id, $post) {
        global $WCMp;
        if (isset($_POST['vendor_profile_data'])) {
            $userdata = array(
				'ID' => $vendor_user_id,
				'user_pass' => $_POST['vendor_profile_data']['password'],
				'user_email' => $_POST['vendor_profile_data']['user_email'],
				'first_name' => $_POST['vendor_profile_data']['first_name'],
				'last_name' => $_POST['vendor_profile_data']['last_name'],
			);
			
			$user_id = wp_update_user( $userdata ) ;
			
            $profile_updt = update_user_meta($vendor_user_id, '_vendor_profile_image', $_POST['vendor_profile_data']['vendor_profile_image']);
            
            if ($profile_updt || $user_id) {
                wc_add_notice(__('Profile Data Updated', 'dc-woocommerce-multi-vendor'), 'success');
            }
        }
    }
    
    /**
     * Add vendor dashboard header navigation
     * @since 3.0.0
     */
    public function dashboard_header_nav() {
        $vendor = get_current_vendor();
        $header_nav = array(
            'shop-link' => array(
                'label' => __('My Shop', 'dc-woocommerce-multi-vendor')
                , 'url' => apply_filters('wcmp_vendor_shop_permalink', esc_url($vendor->permalink))
                , 'class' => ''
                , 'capability' => true
                , 'position' => 10
                , 'link_target' => '_blank'
                , 'nav_icon' => 'wcmp-font ico-my-shop-icon'
            ),
            'add-product' => array(
                'label' => __('Add Product', 'dc-woocommerce-multi-vendor')
                , 'url' => apply_filters('wcmp_vendor_submit_product', esc_url(wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_add_product_endpoint', 'vendor', 'general', 'add-product'))))
                , 'class' => ''
                , 'capability' => true
                , 'position' => 20
                , 'link_target' => '_self'
                , 'nav_icon' => 'wcmp-font ico-product-icon'
            ),
            'orders' => array(
                'label' => __('Orders', 'dc-woocommerce-multi-vendor')
                , 'url' => esc_url(wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_vendor_orders_endpoint', 'vendor', 'general', 'vendor-orders')))
                , 'class' => ''
                , 'capability' => true
                , 'position' => 30
                , 'link_target' => '_self'
                , 'nav_icon' => 'wcmp-font ico-orders-icon'
            ),
            'announcement' => array(
                'label' => __('Announcement', 'dc-woocommerce-multi-vendor')
                , 'url' => esc_url(wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_vendor_announcements_endpoint', 'vendor', 'general', 'vendor-announcements')))
                , 'class' => ''
                , 'capability' => apply_filters('wcmp_show_vendor_announcements', true)
                , 'position' => 40
                , 'link_target' => '_self'
                , 'nav_icon' => 'wcmp-font ico-announcement-icon'
            )
        );
        return apply_filters('wcmp_vendor_dashboard_header_nav', $header_nav);
    }

    /**
     * Add vendor dashboard header right panel navigation
     * @since 3.0.0
     */
    public function dashboard_header_right_panel_nav() {
        $panel_nav = array(
            'storefront' => array(
                'label' => __('Storefront', 'dc-woocommerce-multi-vendor')
                , 'url' => esc_url(wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_store_settings_endpoint', 'vendor', 'general', 'storefront')))
                , 'class' => ''
                , 'capability' => true
                , 'position' => 10
                , 'link_target' => '_self'
                , 'nav_icon' => 'wcmp-font ico-storefront-icon'
            ),
            'profile' => array(
                'label' => __('Profile management', 'dc-woocommerce-multi-vendor')
                , 'url' => esc_url(wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_profile_endpoint', 'vendor', 'general', 'profile')))
                , 'class' => ''
                , 'capability' => true
                , 'position' => 20
                , 'link_target' => '_self'
                , 'nav_icon' => 'wcmp-font ico-user-icon'
            ),
            'wp-admin' => array(
                'label' => __('WordPress backend', 'dc-woocommerce-multi-vendor')
                , 'url' => esc_url(admin_url())
                , 'class' => ''
                , 'capability' => true
                , 'position' => 30
                , 'link_target' => '_self'
                , 'nav_icon' => 'wcmp-font ico-wp-backend-icon'
            ),
            'logout' => array(
                'label' => __('Logout', 'dc-woocommerce-multi-vendor')
                , 'url' => esc_url(wp_logout_url(get_permalink(wcmp_vendor_dashboard_page_id())))
                , 'class' => ''
                , 'capability' => true
                , 'position' => 40
                , 'link_target' => '_self'
                , 'nav_icon' => 'wcmp-font ico-logout-icon'
            )
        );
        return apply_filters('wcmp_vendor_dashboard_header_right_panel_nav', $panel_nav);
    }

    /**
     * Add vendor dashboard widgets
     * @since 3.0.0
     */
    public function wcmp_dashboard_setup() {
        $vendor = get_wcmp_vendor(get_current_user_id());
        $this->wcmp_add_dashboard_widget('wcmp_vendor_stats_reports', '', array(&$this, 'wcmp_vendor_stats_reports'), 'full');
        $trans_details_widget_args = array();
        if (apply_filters('wcmp_vendor_dashboard_menu_vendor_withdrawal_capability', false)) {
            $trans_details_widget_args['action'] = array('title' => __('Withdrawal', 'dc-woocommerce-multi-vendor'), 'link' => esc_url(wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_vendor_withdrawal_endpoint', 'vendor', 'general', 'vendor-withdrawal'))));
        }
        $this->wcmp_add_dashboard_widget('wcmp_vendor_transaction_details', __('Transaction Details', 'dc-woocommerce-multi-vendor'), array(&$this, 'wcmp_vendor_transaction_details'), 'side', array(), $trans_details_widget_args);
        $visitor_map_filter_attr = apply_filters('wcmp_vendor_visitors_map_filter_attr', array(
            '7' => __('Last 7 days', 'dc-woocommerce-multi-vendor'),
            '30' => __('Last 30 days', 'dc-woocommerce-multi-vendor'),
        ));
        $visitor_map_filter = '<div class="widget-action-area pull-right">
            <select id="wcmp_visitor_stats_date_filter" class="form-control">';
        if ($visitor_map_filter_attr) {
            foreach ($visitor_map_filter_attr as $key => $value) {
                $visitor_map_filter .= '<option value="' . $key . '">' . $value . '</option>';
            }
        }
        $visitor_map_filter .= '</select>
        </div>';
        $this->wcmp_add_dashboard_widget('wcmp_vendor_visitors_map', __('Visitors Map', 'dc-woocommerce-multi-vendor'), array(&$this, 'wcmp_vendor_visitors_map'), 'normal', '', array('action' => array('html' => $visitor_map_filter)));
        if ($vendor->is_shipping_enable()):
            $this->wcmp_add_dashboard_widget('wcmp_vendor_pending_shipping', __('Pending Shipping', 'dc-woocommerce-multi-vendor'), array(&$this, 'wcmp_vendor_pending_shipping'));
        endif;
        if (current_user_can('edit_products')) {
            $this->wcmp_add_dashboard_widget('wcmp_vendor_product_stats', __('Product Stats', 'dc-woocommerce-multi-vendor'), array(&$this, 'wcmp_vendor_product_stats'), 'side', '', array('action' => array('title' => __('Add Product', 'dc-woocommerce-multi-vendor'), 'link' => esc_url(wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_add_product_endpoint', 'vendor', 'general', 'add-product'))))));
            $this->wcmp_add_dashboard_widget('wcmp_vendor_product_sales_report', __('Product Sales Report', 'dc-woocommerce-multi-vendor'), array(&$this, 'wcmp_vendor_product_sales_report'));
        }
        if (get_wcmp_vendor_settings('is_sellerreview', 'general') == 'Enable') {
            $this->wcmp_add_dashboard_widget('wcmp_customer_reviews', __('Reviews', 'dc-woocommerce-multi-vendor'), array(&$this, 'wcmp_customer_review'));
        }
        $this->wcmp_add_dashboard_widget('wcmp_vendor_products_cust_qna', __('Customer Questions', 'dc-woocommerce-multi-vendor'), array(&$this, 'wcmp_vendor_products_cust_qna'), 'side', '', array('action' => array('title' => __('Show All Q&As', 'dc-woocommerce-multi-vendor'), 'link' => esc_url(wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_vendor_products_qnas_endpoint', 'vendor', 'general', 'products-qna'))))));
    }

    /**
     * Register new vendor dashboard widget
     * @global array $wcmp_dashboard_widget
     * @param string $widget_id
     * @param string $widget_title
     * @param callable $callback
     * @param string $context
     * @param int $priority
     * @param array $callback_args
     * @since 3.0.0
     */
    public function wcmp_add_dashboard_widget($widget_id, $widget_title, $callback, $context = 'normal', $callback_args = null, $args = array()) {
        global $wcmp_dashboard_widget;
        if (!is_user_wcmp_vendor(get_current_vendor_id())) {
            return;
        }
        if (!isset($wcmp_dashboard_widget)) {
            $wcmp_dashboard_widget = array();
        }
        if (!isset($wcmp_dashboard_widget[$context])) {
            $wcmp_dashboard_widget[$context] = array();
        }
        $wcmp_dashboard_widget[$context][$widget_id] = array(
            'id' => $widget_id,
            'title' => $widget_title,
            'callback' => $callback,
            'calback_args' => $callback_args,
            'args' => $args
        );
    }

    /**
     * Output vendor dashboard widgets
     * @global array $wcmp_dashboard_widget
     * @since 3.0.0
     */
    public function do_wcmp_dashboard_widget($place) {
        global $wcmp_dashboard_widget;
        if (!$wcmp_dashboard_widget) {
            return;
        }
        $wcmp_dashboard_widget = apply_filters('before_wcmp_dashboard_widget', $wcmp_dashboard_widget);
        if ($wcmp_dashboard_widget) {
            foreach ($wcmp_dashboard_widget as $context => $dashboard_widget) {
                if ($place == $context) {
                    foreach ($dashboard_widget as $widget_id => $widget) {
                        echo '<div class="panel panel-default pannel-outer-heading wcmp-dash-widget ' . $widget_id . '">';
                        $this->build_widget_header($widget['title'], $widget['args']);
                        echo '<div class="panel-body">';
                        call_user_func($widget['callback'], $widget['calback_args']);
                        echo '</div>';
                        $this->build_widget_footer($widget['args']);
                        echo '</div>';
                    }
                }
            }
        }
    }

    public function build_widget_header($title, $args = array()) {
        $default = array(
            'icon' => '',
            'action' => array()
        );
        $args = array_merge($default, $args);
        if (!empty($title)) {
            ?>
            <div class="panel-heading">
                <h3 class="pull-left">
                    <?php if (!empty($args['icon'])) : ?>
                        <span class="icon_stand dashicons-before <?php echo $args['icon']; ?>"></span>
                    <?php endif; ?>
            <?php echo $title; ?>
                </h3>
            </div>
            <div class="clearfix"></div>
            <?php
        }
    }

    public function build_widget_footer($args = array()) {
        $default = array(
            'icon' => '',
            'action' => array()
        );
        $args = array_merge($default, $args);
        if (!empty($args['action'])) {
            ?>
            <div class="panel-footer">
                    <?php if (isset($args['action']['link']) && isset($args['action']['title'])) { ?>
                    <a href="<?php echo $args['action']['link']; ?>" class="footer-link">
                        <?php
                        if (isset($args['action']['icon'])) {
                            echo '<span class="icon_stand dashicons-before ' . $args['action']['icon'] . '"></span>';
                        }
                        ?>
                <?php echo $args['action']['title']; ?>
                        <i class="wcmp-font ico-right-arrow-icon"></i>
                    </a>
            <?php } if (isset($args['action']['html'])) {
                echo $args['action']['html'];
            } ?>
            </div>
            <div class="clearfix"></div>
            <?php
        }
    }

    public function wcmp_vendor_stats_reports($args = array()) {
        global $WCMp;
        $vendor = get_current_vendor();
        $vendor_report_data = get_wcmp_vendor_dashboard_stats_reports_data();
        $default_data = array();
        $default_data['stats_reports_periods'] = apply_filters('wcmp_vendor_stats_reports_periods', array(
            '7' => __('Last 7 days', 'dc-woocommerce-multi-vendor'),
            '30' => __('Last 30 days', 'dc-woocommerce-multi-vendor'),
        ));
        $default_data['vendor_report_data'] = $vendor_report_data;
        $default_data['payment_mode'] = ucwords(str_replace('_', ' ', $vendor->payment_mode));
        $WCMp->template->get_template('vendor-dashboard/dashboard-widgets/wcmp_vendor_stats_reports.php', $default_data);
    }

    public function wcmp_vendor_pending_shipping($args = array()) {
        global $WCMp;
        $vendor = get_wcmp_vendor(get_current_user_id());
        $today = @date('Y-m-d 00:00:00', strtotime("+1 days"));
        $last_seven_day_date = date('Y-m-d H:i:s', strtotime('-7 days'));
        // Mark as shipped
        if (isset($_POST['wcmp-submit-mark-as-ship'])) {
            $order_id = $_POST['order_id'];
            $tracking_id = $_POST['tracking_id'];
            $tracking_url = $_POST['tracking_url'];
            $vendor->set_order_shipped($order_id, $tracking_id, $tracking_url);
        }

        $default_headers = apply_filters('wcmp_vendor_pending_shipping_table_header', array(
                'order_id' => __('Order ID', 'dc-woocommerce-multi-vendor'),
                'products_name' => __('Product', 'dc-woocommerce-multi-vendor'),
                'order_date' => __('Order Date', 'dc-woocommerce-multi-vendor'),
                'shipping_address' => __('Address', 'dc-woocommerce-multi-vendor'),
                'shipping_amount' => __('Charges', 'dc-woocommerce-multi-vendor'),
                'action' => __('Action', 'dc-woocommerce-multi-vendor'),
            ));
        $WCMp->template->get_template('vendor-dashboard/dashboard-widgets/wcmp_vendor_pending_shipping.php', array('default_headers' => $default_headers));
    }

    public function wcmp_customer_review() {
        global $WCMp;
        $WCMp->template->get_template('vendor-dashboard/dashboard-widgets/wcmp_customer_review.php');
    }

    public function wcmp_vendor_product_stats($args = array()) {
        global $WCMp;
        $publish_products_count = 0;
        $pending_products_count = 0;
        $draft_products_count = 0;
        $trashed_products_count = 0;

        $user_id = get_current_user_id();

        $args = array('post_status' => array('publish', 'pending', 'draft', 'trash'));
        $vendor = get_wcmp_vendor(absint($user_id));
        $product_stats = array();
        $products = $vendor->get_products($args);
        $product_stats['total_products'] = count($products);
        foreach ($products as $key => $value) {
            $product_id = $value->ID;
            $product = wc_get_product($product_id);
            $vendor = get_wcmp_product_vendors($product_id);
            if (!empty($vendor) && $vendor->id == $user_id) {
                if ($value->post_status == 'publish')
                    $publish_products_count += 1;
                if ($value->post_status == 'pending')
                    $pending_products_count += 1;
                if ($value->post_status == 'draft')
                    $draft_products_count += 1;
                if ($value->post_status == 'trash') {
                    $trashed_products_count += 1;
                }
            }
        }
        $product_stats['publish_products_count'] = $publish_products_count;
        $product_stats['pending_products_count'] = $pending_products_count;
        $product_stats['draft_products_count'] = $draft_products_count;
        $product_stats['trashed_products_count'] = $trashed_products_count;

        $product_stats['product_page_url'] = wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_products_endpoint', 'vendor', 'general', 'products'));

// variables to send $product_page_url $publish_products_count $pending_products_count $trashed_products_count
        //require_once(plugin_dir_path( __FILE__ ) . "wcmp_vendor_published_pending_trashed_products.php");
        $WCMp->template->get_template('vendor-dashboard/dashboard-widgets/wcmp_vendor_product_stats.php', $product_stats);
    }

    public function wcmp_vendor_product_sales_report() {
        global $WCMp;
        $WCMp->template->get_template('vendor-dashboard/dashboard-widgets/wcmp_vendor_product_sales_report.php');
    }

    function wcmp_vendor_transaction_details() {
        global $WCMp;
        $total_amount = 0;
        $transaction_display_array = array();
        $vendor = get_wcmp_vendor(get_current_vendor_id());
        $requestData = $_REQUEST;
        $vendor = apply_filters('wcmp_transaction_vendor', $vendor);
        $start_date = isset($requestData['from_date']) ? $requestData['from_date'] : date('01-m-Y');
        $end_date = isset($requestData['to_date']) ? $requestData['to_date'] : date('t-m-Y');
        $transaction_details = $WCMp->transaction->get_transactions($vendor->term_id);
        $unpaid_orders = get_wcmp_vendor_order_amount(array('commission_status' => 'unpaid'), $vendor->id);
        $count = 0; // varible for counting 5 transaction details
        foreach ($transaction_details as $transaction_id => $details) {
            $count++;
            if ($count <= 5) {
                //$transaction_display_array[$transaction_id] = $details['total_amount'];
                //$transaction_display_array['id'] = $transaction_id;
                $transaction_display_array[$transaction_id]['transaction_date'] = wcmp_date($details['post_date']);
                $transaction_display_array[$transaction_id]['total_amount'] = $details['total_amount'];
            }

            $total_amount = $total_amount + $details['total_amount'];
        }
        //print_r($total_amount);
        $WCMp->template->get_template('vendor-dashboard/dashboard-widgets/wcmp_vendor_transaction_details.php', array('total_amount' => $unpaid_orders['total'], 'transaction_display_array' => $transaction_display_array));
       
    }

    public function wcmp_vendor_products_cust_qna() {
        global $WCMp;
        $WCMp->template->get_template('vendor-dashboard/dashboard-widgets/wcmp_vendor_products_cust_qna.php');
    }

    public function wcmp_vendor_visitors_map() {
        global $WCMp;
        $WCMp->library->load_jqvmap_script_lib();
        $vendor = get_current_vendor();
        $visitor_map_stats = get_wcmp_vendor_dashboard_visitor_stats_data($vendor->id);
        $visitor_map_stats['init'] = array('map' => 'world_en', 'background_color' => false, 'color' => '#a0a0a0', 'hover_color' => false, 'hover_opacity' => 0.7);
        //wp_enqueue_script('wcmp_gchart_loader', '//www.gstatic.com/charts/loader.js');
        wp_enqueue_script('wcmp_visitor_map_data', $WCMp->plugin_url . 'assets/frontend/js/wcmp_vendor_map_widget_data.js', apply_filters('wcmp_vendor_visitors_map_script_dependancies', array('jquery','wcmp-vmap-world-script')));
        wp_localize_script('wcmp_visitor_map_data', 'visitor_map_stats', apply_filters('wcmp_vendor_visitors_map_script_data', $visitor_map_stats));
        $WCMp->template->get_template('vendor-dashboard/dashboard-widgets/wcmp_vendor_visitors_map.php');
    }

}
