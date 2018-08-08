<?php

/**
 * WCMp Ajax Class
 *
 * @version     2.2.0
 * @package     WCMp
 * @author      WC Marketplace
 */
class WCMp_Ajax {

    public function __construct() {
        //$general_singleproductmultisellersettings = get_option('wcmp_general_singleproductmultiseller_settings_name');
        add_action('wp_ajax_woocommerce_json_search_vendors', array(&$this, 'woocommerce_json_search_vendors'));
        add_action('wp_ajax_activate_pending_vendor', array(&$this, 'activate_pending_vendor'));
        add_action('wp_ajax_reject_pending_vendor', array(&$this, 'reject_pending_vendor'));
        add_action('wp_ajax_wcmp_suspend_vendor', array(&$this, 'wcmp_suspend_vendor'));
        add_action('wp_ajax_wcmp_activate_vendor', array(&$this, 'wcmp_activate_vendor'));
        add_action('wp_ajax_send_report_abuse', array(&$this, 'send_report_abuse'));
        add_action('wp_ajax_nopriv_send_report_abuse', array(&$this, 'send_report_abuse'));
        add_action('wp_ajax_dismiss_vendor_to_do_list', array(&$this, 'dismiss_vendor_to_do_list'));
        add_action('wp_ajax_get_more_orders', array(&$this, 'get_more_orders'));
        add_action('wp_ajax_withdrawal_more_orders', array(&$this, 'withdrawal_more_orders'));
        add_action('wp_ajax_show_more_transaction', array(&$this, 'show_more_transaction'));
        add_action('wp_ajax_nopriv_get_more_orders', array(&$this, 'get_more_orders'));
//        add_action('wp_ajax_order_mark_as_shipped', array(&$this, 'order_mark_as_shipped'));
//        add_action('wp_ajax_nopriv_order_mark_as_shipped', array(&$this, 'order_mark_as_shipped'));
        add_action('wp_ajax_transaction_done_button', array(&$this, 'transaction_done_button'));
        add_action('wp_ajax_wcmp_vendor_csv_download_per_order', array(&$this, 'wcmp_vendor_csv_download_per_order'));
        add_filter('ajax_query_attachments_args', array(&$this, 'show_current_user_attachments'), 10, 1);
        add_filter('wp_ajax_vendor_report_sort', array($this, 'vendor_report_sort'));
        add_filter('wp_ajax_vendor_search', array($this, 'search_vendor_data'));
        add_filter('wp_ajax_product_report_sort', array($this, 'product_report_sort'));
        add_filter('wp_ajax_product_search', array($this, 'search_product_data'));
        // woocommerce product enquiry form support
        if (WC_Dependencies_Product_Vendor::woocommerce_product_enquiry_form_active_check()) {
            add_filter('product_enquiry_send_to', array($this, 'send_enquiry_to_vendor'), 10, 2);
        }

        // Unsign vendor from product
        add_action('wp_ajax_unassign_vendor', array($this, 'unassign_vendor'));
        add_action('wp_ajax_wcmp_frontend_sale_get_row', array(&$this, 'wcmp_frontend_sale_get_row_callback'));
        add_action('wp_ajax_nopriv_wcmp_frontend_sale_get_row', array(&$this, 'wcmp_frontend_sale_get_row_callback'));
        add_action('wp_ajax_wcmp_frontend_pending_shipping_get_row', array(&$this, 'wcmp_frontend_pending_shipping_get_row_callback'));
        add_action('wp_ajax_nopriv_wcmp_frontend_pending_shipping_get_row', array(&$this, 'wcmp_frontend_pending_shipping_get_row_callback'));

        add_action('wp_ajax_wcmp_vendor_announcements_operation', array($this, 'wcmp_vendor_messages_operation'));
        add_action('wp_ajax_nopriv_wcmp_vendor_announcements_operation', array($this, 'wcmp_vendor_messages_operation'));
        add_action('wp_ajax_wcmp_announcements_refresh_tab_data', array($this, 'wcmp_msg_refresh_tab_data'));
        add_action('wp_ajax_nopriv_wcmp_announcements_refresh_tab_data', array($this, 'wcmp_msg_refresh_tab_data'));
        add_action('wp_ajax_wcmp_dismiss_dashboard_announcements', array($this, 'wcmp_dismiss_dashboard_message'));
        add_action('wp_ajax_nopriv_wcmp_dismiss_dashboard_announcements', array($this, 'wcmp_dismiss_dashboard_message'));

        if (get_wcmp_vendor_settings('is_singleproductmultiseller', 'general') == 'Enable') {
            // Product auto suggestion
            add_action('wp_ajax_wcmp_auto_search_product', array($this, 'wcmp_auto_suggesion_product'));
            add_action('wp_ajax_nopriv_wcmp_auto_search_product', array($this, 'wcmp_auto_suggesion_product'));
            // Product duplicate
            add_action('wp_ajax_wcmp_copy_to_new_draft', array($this, 'wcmp_copy_to_new_draft'));
            add_action('wp_ajax_nopriv_wcmp_copy_to_new_draft', array($this, 'wcmp_copy_to_new_draft'));
            add_action('wp_ajax_get_loadmorebutton_single_product_multiple_vendors', array($this, 'wcmp_get_loadmorebutton_single_product_multiple_vendors'));
            add_action('wp_ajax_nopriv_get_loadmorebutton_single_product_multiple_vendors', array($this, 'wcmp_get_loadmorebutton_single_product_multiple_vendors'));
            add_action('wp_ajax_single_product_multiple_vendors_sorting', array($this, 'single_product_multiple_vendors_sorting'));
            add_action('wp_ajax_nopriv_single_product_multiple_vendors_sorting', array($this, 'single_product_multiple_vendors_sorting'));

            add_action('wp_ajax_wcmp_create_duplicate_product', array(&$this, 'wcmp_create_duplicate_product'));
        }
        add_action('wp_ajax_wcmp_add_review_rating_vendor', array($this, 'wcmp_add_review_rating_vendor'));
        add_action('wp_ajax_nopriv_wcmp_add_review_rating_vendor', array($this, 'wcmp_add_review_rating_vendor'));
        // load more vendor review
        add_action('wp_ajax_wcmp_load_more_review_rating_vendor', array($this, 'wcmp_load_more_review_rating_vendor'));
        add_action('wp_ajax_nopriv_wcmp_load_more_review_rating_vendor', array($this, 'wcmp_load_more_review_rating_vendor'));

        add_action('wp_ajax_wcmp_save_vendor_registration_form', array(&$this, 'wcmp_save_vendor_registration_form_callback'));

        add_action('wp_ajax_dismiss_wcmp_servive_notice', array(&$this, 'dismiss_wcmp_servive_notice'));
        // search filter vendors from widget
        add_action('wp_ajax_vendor_list_by_search_keyword', array($this, 'vendor_list_by_search_keyword'));
        add_action('wp_ajax_nopriv_vendor_list_by_search_keyword', array($this, 'vendor_list_by_search_keyword'));


        //frontend product manager ajax
        add_action('wp_ajax_frontend_product_manager', array(&$this, 'frontend_product_manager'));
        add_action('wp_ajax_generate_taxonomy_attributes', array(&$this, 'generate_taxonomy_attributes'));
        add_action('wp_ajax_wcmp_product_tag_add', array(&$this, 'wcmp_product_tag_add'));

        //add_action('wp_ajax_generate_variation_attributes', array(&$this, 'generate_variation_attributes'));

        add_action('wp_ajax_delete_fpm_product', array(&$this, 'delete_fpm_product'));

        // Frontend Coupon Manager
        add_action('wp_ajax_frontend_coupon_manager', array(&$this, 'frontend_coupon_manager'));
        // Vendor dashboard product list
        add_action('wp_ajax_wcmp_vendor_product_list', array(&$this, 'wcmp_vendor_product_list'));
        // Vendor dashboard withdrawal list
        add_action('wp_ajax_wcmp_vendor_unpaid_order_vendor_withdrawal_list', array(&$this, 'wcmp_vendor_unpaid_order_vendor_withdrawal_list'));
        // Vendor dashboard transactions list
        add_action('wp_ajax_wcmp_vendor_transactions_list', array(&$this, 'wcmp_vendor_transactions_list'));
        // Vendor dashboard coupon list
        add_action('wp_ajax_wcmp_vendor_coupon_list', array(&$this, 'wcmp_vendor_coupon_list'));

        add_action('wp_ajax_wcmp_datatable_get_vendor_orders', array(&$this, 'wcmp_datatable_get_vendor_orders'));
        // Customer Q & A
        add_action('wp_ajax_wcmp_customer_ask_qna_handler', array(&$this, 'wcmp_customer_ask_qna_handler'));
        add_action('wp_ajax_nopriv_wcmp_customer_ask_qna_handler', array(&$this, 'wcmp_customer_ask_qna_handler'));
        // dashboard vendor reviews widget
        add_action('wp_ajax_wcmp_vendor_dashboard_reviews_data', array(&$this, 'wcmp_vendor_dashboard_reviews_data'));
        // dashboard customer questions widget
        add_action('wp_ajax_wcmp_vendor_dashboard_customer_questions_data', array(&$this, 'wcmp_vendor_dashboard_customer_questions_data'));
        // vendor products Q&As list
        add_action('wp_ajax_wcmp_vendor_products_qna_list', array(&$this, 'wcmp_vendor_products_qna_list'));
        // vendor pending shipping widget
        add_action('wp_ajax_wcmp_widget_vendor_pending_shipping', array(&$this, 'wcmp_widget_vendor_pending_shipping'));
        // vendor product sales report widget
        add_action('wp_ajax_wcmp_widget_vendor_product_sales_report', array(&$this, 'wcmp_widget_vendor_product_sales_report'));
        
        // vendor management tab under WCMp
        add_action('wp_ajax_wcmp_get_vendor_details', array(&$this, 'wcmp_get_vendor_details'));
    }

    public function wcmp_datatable_get_vendor_orders() {
        global $wpdb, $WCMp;
        $requestData = $_REQUEST;
        $start_date = date('Y-m-d G:i:s', $_POST['start_date']);
        $end_date = date('Y-m-d G:i:s', $_POST['end_date']);
        $vendor = get_current_vendor();
        $vendor_all_orders = $wpdb->get_results("SELECT DISTINCT order_id from `{$wpdb->prefix}wcmp_vendor_orders` where commission_id > 0 AND vendor_id = '" . $vendor->id . "' AND (`created` >= '" . $start_date . "' AND `created` <= '" . $end_date . "') and `is_trashed` != 1 ORDER BY `created` DESC", ARRAY_A);
        $vendor_all_orders = apply_filters('wcmp_datatable_get_vendor_all_orders', $vendor_all_orders);
        $vendor_all_orders = apply_filters('wcmp_datatable_get_vendor_all_orders_id', wp_list_pluck($vendor_all_orders, 'order_id'));
        if (isset($requestData['order_status']) && $requestData['order_status'] != 'all' && $requestData['order_status'] != '') {
            foreach ($vendor_all_orders as $key => $value) {
                if (wc_get_order($value)->get_status() != $requestData['order_status']) {
                    unset($vendor_all_orders[$key]);
                }
            }
        }
        $vendor_orders = array_slice($vendor_all_orders, $requestData['start'], $requestData['length']);
        $data = array();

        foreach ($vendor_orders as $order_id) {
            $order = wc_get_order($order_id);
            if ($order) {
                $actions = array();
                $is_shipped = (array) get_post_meta($order->get_id(), 'dc_pv_shipped', true);
                if (!in_array($vendor->id, $is_shipped)) {
                    $mark_ship_title = __('Mark as shipped', 'dc-woocommerce-multi-vendor');
                } else {
                    $mark_ship_title = __('Shipped', 'dc-woocommerce-multi-vendor');
                }
                $actions['view'] = array(
                    'url' => esc_url(wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_vendor_orders_endpoint', 'vendor', 'general', 'vendor-orders'), $order->get_id())),
                    'icon' => 'ico-eye-icon action-icon',
                    'title' => __('View', 'dc-woocommerce-multi-vendor'),
                );
                if (apply_filters('can_wcmp_vendor_export_orders_csv', true, get_current_vendor_id())) :
                    $actions['wcmp_vendor_csv_download_per_order'] = array(
                        'url' => admin_url('admin-ajax.php?action=wcmp_vendor_csv_download_per_order&order_id=' . $order->get_id() . '&nonce=' . wp_create_nonce('wcmp_vendor_csv_download_per_order')),
                        'icon' => 'ico-download-icon action-icon',
                        'title' => __('Download', 'dc-woocommerce-multi-vendor'),
                    );
                endif;
                if ($vendor->is_shipping_enable() ) {
                    $vendor_shipping_method = get_wcmp_vendor_order_shipping_method($order->get_id(), $vendor->id);
                    // hide shipping for local pickup
                    if($vendor_shipping_method && !in_array($vendor_shipping_method->get_method_id(), apply_filters('hide_shipping_icon_for_vendor_order_on_methods',array('local_pickup')))){
                        $actions['mark_ship'] = array(
                            'url' => '#',
                            'title' => $mark_ship_title,
                            'icon' => 'ico-shippingnew-icon action-icon'
                        );
                    }
                }
                $actions = apply_filters('wcmp_my_account_my_orders_actions', $actions, $order->get_id());
                $action_html = '';
                foreach ($actions as $key => $action) {
                    if ($key == 'mark_ship' && !in_array($vendor->id, $is_shipped)) {
                        $action_html .= '<a href="javascript:void(0)" title="' . $mark_ship_title . '" onclick="wcmpMarkeAsShip(this,' . $order->get_id() . ')"><i class="wcmp-font ' . $action['icon'] . '"></i></a> ';
                    } else if ($key == 'mark_ship') {
                        $action_html .= '<i title="' . $mark_ship_title . '" class="wcmp-font ' . $action['icon'] . '"></i> ';
                    } else {
                        $action_html .= '<a href="' . $action['url'] . '" title="' . $action['title'] . '"><i class="wcmp-font ' . $action['icon'] . '"></i></a> ';
                    }
                }
                $data[] = apply_filters('wcmp_datatable_order_list_row_data', array(
                    'select_order' => '<input type="checkbox" class="select_' . $order->get_status() . '" name="selected_orders[' . $order->get_id() . ']" value="' . $order->get_id() . '" />',
                    'order_id' => $order->get_id(),
                    'order_date' => wcmp_date($order->get_date_created()),
                    'vendor_earning' => wc_price(get_wcmp_vendor_order_amount(array('vendor_id' => $vendor->id, 'order_id' => $order->get_id()))['total']),
                    'order_status' => esc_html(wc_get_order_status_name($order->get_status())), //ucfirst($order->get_status()),
                    'action' => apply_filters('wcmp_vendor_orders_row_action_html', $action_html, $actions)
                ), $order);
            }
        }
        $json_data = array(
            "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
            "recordsTotal" => intval(count($vendor_all_orders)), // total number of records
            "recordsFiltered" => intval(count($vendor_all_orders)), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $data   // total data array
        );
        wp_send_json($json_data);
    }

    public function wcmp_save_vendor_registration_form_callback() {
        $form_data = json_decode(stripslashes_deep($_REQUEST['form_data']), true);
        if (!empty($form_data) && is_array($form_data)) {
            foreach ($form_data as $key => $value) {
                $form_data[$key]['hidden'] = true;
            }
        }

        update_option('wcmp_vendor_registration_form_data', $form_data);
        die;
    }

    function single_product_multiple_vendors_sorting() {
        global $WCMp;
        $sorting_value = $_POST['sorting_value'];
        $attrid = $_POST['attrid'];
        $more_products = $WCMp->product->get_multiple_vendors_array_for_single_product($attrid);
        $more_product_array = $more_products['more_product_array'];
        $results = $more_products['results'];
        $WCMp->template->get_template('single-product/multiple_vendors_products_body.php', array('more_product_array' => $more_product_array, 'sorting' => $sorting_value));
        die;
    }

    function wcmp_get_loadmorebutton_single_product_multiple_vendors() {
        global $WCMp;
        $WCMp->template->get_template('single-product/load-more-button.php');
        die;
    }

    function wcmp_load_more_review_rating_vendor() {
        global $WCMp, $wpdb;

        if (!empty($_POST['pageno']) && !empty($_POST['term_id'])) {
            $vendor = get_wcmp_vendor_by_term($_POST['term_id']);
            $vendor_id = $vendor->id;
            $offset = $_POST['postperpage'] * $_POST['pageno'];
            $reviews_lists = $vendor->get_reviews_and_rating($offset);
            $WCMp->template->get_template('review/wcmp-vendor-review.php', array('reviews_lists' => $reviews_lists, 'vendor_term_id' => $_POST['term_id']));
        }
        die;
    }

    function wcmp_add_review_rating_vendor() {
        global $WCMp, $wpdb;
        $review = $_POST['comment'];
        $rating = isset($_POST['rating']) ? $_POST['rating'] : false;
        $comment_parent = isset($_POST['comment_parent']) ? $_POST['comment_parent'] : 0;
        $vendor_id = $_POST['vendor_id'];
        $current_user = wp_get_current_user();
        $comment_approve_by_settings = get_option('comment_moderation') ? 0 : 1;
        if (!empty($review)) {
            $time = current_time('mysql');
            if ($current_user->ID > 0) {
                $data = array(
                    'comment_post_ID' => wcmp_vendor_dashboard_page_id(),
                    'comment_author' => $current_user->display_name,
                    'comment_author_email' => $current_user->user_email,
                    'comment_author_url' => $current_user->user_url,
                    'comment_content' => $review,
                    'comment_type' => 'wcmp_vendor_rating',
                    'comment_parent' => $comment_parent,
                    'user_id' => $current_user->ID,
                    'comment_author_IP' => $_SERVER['REMOTE_ADDR'],
                    'comment_agent' => $_SERVER['HTTP_USER_AGENT'],
                    'comment_date' => $time,
                    'comment_approved' => $comment_approve_by_settings,
                );
                $comment_id = wp_insert_comment($data);
                if (!is_wp_error($comment_id)) {
                    // delete transient
                    if (get_transient('wcmp_dashboard_reviews_for_vendor_' . $vendor_id)) {
                        delete_transient('wcmp_dashboard_reviews_for_vendor_' . $vendor_id);
                    }
                    // mark as replied
                    if ($comment_parent != 0 && $vendor_id) {
                        update_comment_meta($comment_parent, '_mark_as_replied', 1);
                    }
                    if ($rating && !empty($rating)) {
                        update_comment_meta($comment_id, 'vendor_rating', $rating);
                    }
                    $is_updated = update_comment_meta($comment_id, 'vendor_rating_id', $vendor_id);
                    if ($is_updated) {
                        echo 1;
                    }
                }
            }
        } else {
            echo 0;
        }
        die;
    }

    function wcmp_copy_to_new_draft() {
        $post_id = $_POST['postid'];
        $post = get_post($post_id);
        echo wp_nonce_url(admin_url('edit.php?post_type=product&action=duplicate_product&post=' . $post->ID), 'woocommerce-duplicate-product_' . $post->ID);
        die;
    }

    public function wcmp_create_duplicate_product() {
        global $WCMp;
        $product_id = $_POST['product_id'];
        $redirect_url = isset($_POST['redirect_url']) ? $_POST['redirect_url'] : esc_url(wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_add_product_endpoint', 'vendor', 'general', 'add-product')));
        $product = wc_get_product($product_id);
        if (!function_exists('duplicate_post_plugin_activation')) {
            include_once( WC_ABSPATH . 'includes/admin/class-wc-admin-duplicate-product.php' );
        }
        $duplicate_product_class = new WC_Admin_Duplicate_Product();
        $duplicate_product = $duplicate_product_class->product_duplicate($product);
        $response = array('status' => false);
        if ($duplicate_product && is_user_wcmp_vendor(get_current_user_id())) {
            wp_update_post(array('ID' => $duplicate_product->get_id(), 'post_author' => get_current_vendor_id()));
            wp_set_object_terms($duplicate_product->get_id(), absint(get_current_vendor()->term_id), $WCMp->taxonomy->taxonomy_name);
            //update_post_meta($duplicate_product->get_id(), '_wcmp_parent_product_id', $product->get_id());
            $duplicate_product->set_parent_id($product->get_id());
            update_post_meta($duplicate_product->get_id(), '_wcmp_child_product', true);
            $duplicate_product->save();
            $permalink_structure = get_option('permalink_structure');
            if(!empty($permalink_structure)) {
                $redirect_url .= $duplicate_product->get_id();
            } else {
                $redirect_url .= '='.$duplicate_product->get_id();
            }
            $response['status'] = true;
            $response['redirect_url'] = htmlspecialchars_decode($redirect_url);
        }
        wp_send_json($response);
    }

    function wcmp_auto_suggesion_product() {
        global $WCMp;
        check_ajax_referer('search-products', 'security');
        $user = wp_get_current_user();
        $term = wc_clean(empty($term) ? stripslashes($_REQUEST['protitle']) : $term);
        $is_admin = $_REQUEST['is_admin'];

        if (empty($term)) {
            wp_die();
        }

        $data_store = WC_Data_Store::load('product');
        $ids = $data_store->search_products($term, '', false);

        $include = array();
        foreach ($ids as $id) {
            $_product = wc_get_product($id);
            if ($_product && !$_product->get_parent_id()) {
                $include[] = $_product->get_id();
            }
        }

        if ($include) {
            $ids = array_slice(array_intersect($ids, $include), 0, 10);
        } else {
            $ids = array();
        }
        $product_objects = array_map('wc_get_product', $ids);
        $html = '';
        if (count($product_objects) > 0) {
            $html .= "<ul>";
            foreach ($product_objects as $product_object) {
                if ($product_object) {
                    if (is_user_wcmp_vendor($user) && $WCMp->vendor_caps->vendor_can($product_object->get_type())) {
                        if ($is_admin == 'false') {
                            $html .= "<li><a data-product_id='{$product_object->get_id()}' href='javascript:void(0)'>" . rawurldecode($product_object->get_formatted_name()) . "</a></li>";
                        } else {
                            $html .= "<li data-element='{$product_object->get_id()}'><a href='" . wp_nonce_url(admin_url('edit.php?post_type=product&action=duplicate_product&singleproductmultiseller=1&post=' . $product_object->get_id()), 'woocommerce-duplicate-product_' . $product_object->get_id()) . "'>" . rawurldecode($product_object->get_formatted_name()) . "</a></li>";
                        }
                    } elseif (!is_user_wcmp_vendor($user) && current_user_can('edit_products')) {
                        $html .= "<li data-element='{$product_object->get_id()}'><a href='" . wp_nonce_url(admin_url('edit.php?post_type=product&action=duplicate_product&singleproductmultiseller=1&post=' . $product_object->get_id()), 'woocommerce-duplicate-product_' . $product_object->get_id()) . "'>" . rawurldecode($product_object->get_formatted_name()) . "</a></li>";
                    }
                }
            }
            $html .= "</ul>";
        } else {
            $html .= "<ul><li class='wcmp_no-suggesion'>" . __('No Suggestion found', 'dc-woocommerce-multi-vendor') . "</li></ul>";
        }

        wp_send_json(array('html' => $html, 'results_count' => count($product_objects)));
    }

    public function wcmp_dismiss_dashboard_message() {
        global $wpdb, $WCMp;
        $post_id = $_POST['post_id'];
        $current_user = wp_get_current_user();
        $current_user_id = $current_user->ID;
        $data_msg_deleted = get_user_meta($current_user_id, '_wcmp_vendor_message_deleted', true);
        if (!empty($data_msg_deleted)) {
            $data_arr = explode(',', $data_msg_deleted);
            $data_arr[] = $post_id;
            $data_str = implode(',', $data_arr);
        } else {
            $data_arr[] = $post_id;
            $data_str = implode(',', $data_arr);
        }
        $is_updated = update_user_meta($current_user_id, '_wcmp_vendor_message_deleted', $data_str);
        if ($is_updated) {
            $dismiss_notices_ids_array = array();
            $dismiss_notices_ids = get_user_meta($current_user_id, '_wcmp_vendor_message_deleted', true);
            if (!empty($dismiss_notices_ids)) {
                $dismiss_notices_ids_array = explode(',', $dismiss_notices_ids);
            } else {
                $dismiss_notices_ids_array = array();
            }
            $args_msg = array(
                'posts_per_page' => 1,
                'offset' => 0,
                'post__not_in' => $dismiss_notices_ids_array,
                'orderby' => 'date',
                'order' => 'DESC',
                'post_type' => 'wcmp_vendor_notice',
                'post_status' => 'publish',
                'suppress_filters' => true
            );
            $msgs_array = get_posts($args_msg);
            if (is_array($msgs_array) && !empty($msgs_array) && count($msgs_array) > 0) {
                $msg = $msgs_array[0];
                ?>
                <h2><?php echo __('Admin Message:', 'dc-woocommerce-multi-vendor'); ?> </h2>
                <span> <?php echo $msg->post_title; ?> </span><br/>
                <span class="mormaltext" style="font-weight:normal;"> <?php
                    echo $short_content = substr(stripslashes(strip_tags($msg->post_content)), 0, 155);
                    if (strlen(stripslashes(strip_tags($msg->post_content))) > 155) {
                        echo '...';
                    }
                    ?> </span><br/>
                <a href="<?php echo get_permalink(get_option('wcmp_product_vendor_messages_page_id')); ?>"><button><?php echo __('DETAILS', 'dc-woocommerce-multi-vendor'); ?></button></a>
                <div class="clear"></div>
                <a href="#" id="cross-admin" data-element = "<?php echo $msg->ID; ?>"  class="wcmp_cross wcmp_delate_message_dashboard"><i class="fa fa-times-circle"></i></a>
                    <?php
                } else {
                    ?>
                <h2><?php echo __('No Messages Found:', 'dc-woocommerce-multi-vendor'); ?> </h2>
                <?php
            }
        } else {
            ?>
            <h2><?php echo __('Error in process:', 'dc-woocommerce-multi-vendor'); ?> </h2>
            <?php
        }
        die;
    }

    public function wcmp_msg_refresh_tab_data() {
        global $wpdb, $WCMp;
        $tab = $_POST['tabname'];
        $WCMp->template->get_template('vendor-dashboard/vendor-announcements/vendor-announcements' . str_replace("_", "-", $tab) . '.php');
        die;
    }

    public function wcmp_vendor_messages_operation() {
        global $wpdb, $WCMp;
        $current_user = wp_get_current_user();
        $current_user_id = $current_user->ID;
        $post_id = $_POST['msg_id'];
        $actionmode = $_POST['actionmode'];
        if ($actionmode == "mark_delete") {
            $data_msg_deleted = get_user_meta($current_user_id, '_wcmp_vendor_message_deleted', true);
            if (!empty($data_msg_deleted)) {
                $data_arr = explode(',', $data_msg_deleted);
                $data_arr[] = $post_id;
                $data_str = implode(',', $data_arr);
            } else {
                $data_arr[] = $post_id;
                $data_str = implode(',', $data_arr);
            }
            if (update_user_meta($current_user_id, '_wcmp_vendor_message_deleted', $data_str)) {
                echo 1;
            } else {
                echo 0;
            }
        } elseif ($actionmode == "mark_read") {
            $data_msg_readed = get_user_meta($current_user_id, '_wcmp_vendor_message_readed', true);
            if (!empty($data_msg_readed)) {
                $data_arr = explode(',', $data_msg_readed);
                $data_arr[] = $post_id;
                $data_str = implode(',', $data_arr);
            } else {
                $data_arr[] = $post_id;
                $data_str = implode(',', $data_arr);
            }
            if (update_user_meta($current_user_id, '_wcmp_vendor_message_readed', $data_str)) {
                echo __('Mark Unread', 'dc-woocommerce-multi-vendor');
            } else {
                echo 0;
            }
        } elseif ($actionmode == "mark_unread") {
            $data_msg_readed = get_user_meta($current_user_id, '_wcmp_vendor_message_readed', true);
            if (!empty($data_msg_readed)) {
                $data_arr = explode(',', $data_msg_readed);
                if (is_array($data_arr)) {
                    if (($key = array_search($post_id, $data_arr)) !== false) {
                        unset($data_arr[$key]);
                    }
                }
                $data_str = implode(',', $data_arr);
            }
            if (update_user_meta($current_user_id, '_wcmp_vendor_message_readed', $data_str)) {
                echo __('Mark Read', 'dc-woocommerce-multi-vendor');
            } else {
                echo 0;
            }
        } elseif ($actionmode == "mark_restore") {
            $data_msg_deleted = get_user_meta($current_user_id, '_wcmp_vendor_message_deleted', true);
            if (!empty($data_msg_deleted)) {
                $data_arr = explode(',', $data_msg_deleted);
                if (is_array($data_arr)) {
                    if (($key = array_search($post_id, $data_arr)) !== false) {
                        unset($data_arr[$key]);
                    }
                }
                $data_str = implode(',', $data_arr);
            }
            if (update_user_meta($current_user_id, '_wcmp_vendor_message_deleted', $data_str)) {
                echo __('Mark Restore', 'dc-woocommerce-multi-vendor');
            } else {
                echo 0;
            }
        }
        die;
    }

    public function wcmp_frontend_sale_get_row_callback() {
        global $wpdb, $WCMp;
        $user = wp_get_current_user();
        $vendor = get_wcmp_vendor($user->ID);
        $today_or_weekly = $_POST['today_or_weekly'];
        $current_page = $_POST['current_page'];
        $next_page = $_POST['next_page'];
        $total_page = $_POST['total_page'];
        $perpagedata = $_POST['perpagedata'];
        if ($next_page <= $total_page) {
            if ($next_page > 1) {
                $start = ($next_page - 1) * $perpagedata;
                $WCMp->template->get_template('vendor-dashboard/dashboard/vendor-dashboard-sales-item.php', array('vendor' => $vendor, 'today_or_weekly' => $today_or_weekly, 'start' => $start, 'to' => $perpagedata));
            }
        } else {
            echo "<tr><td colspan='5'>" . __('no more data found', 'dc-woocommerce-multi-vendor') . "</td></tr>";
        }
        die;
    }

    public function wcmp_frontend_pending_shipping_get_row_callback() {
        global $wpdb, $WCMp;
        $user = wp_get_current_user();
        $vendor = get_wcmp_vendor($user->ID);
        $today_or_weekly = $_POST['today_or_weekly'];
        $current_page = $_POST['current_page'];
        $next_page = $_POST['next_page'];
        $total_page = $_POST['total_page'];
        $perpagedata = $_POST['perpagedata'];
        if ($next_page <= $total_page) {
            if ($next_page > 1) {
                $start = ($next_page - 1) * $perpagedata;
                $WCMp->template->get_template('vendor-dashboard/dashboard/vendor-dasboard-pending-shipping-items.php', array('vendor' => $vendor, 'today_or_weekly' => $today_or_weekly, 'start' => $start, 'to' => $perpagedata));
            }
        } else {
            echo "<tr><td colspan='5'>" . __('no more data found', 'dc-woocommerce-multi-vendor') . "</td></tr>";
        }
        die;
    }

    function show_more_transaction() {
        global $WCMp;
        $data_to_show = $_POST['data_to_show'];
        $WCMp->template->get_template('vendor-dashboard/vendor-transactions/vendor-transaction-items.php', array('transactions' => $data_to_show));
        die;
    }

    function withdrawal_more_orders() {
        global $WCMp;
        $user = wp_get_current_user();
        $vendor = get_wcmp_vendor($user->ID);
        $offset = $_POST['offset'];
        $meta_query['meta_query'] = array(
            array(
                'key' => '_paid_status',
                'value' => 'unpaid',
                'compare' => '='
            ),
            array(
                'key' => '_commission_vendor',
                'value' => absint($vendor->term_id),
                'compare' => '='
            )
        );
        $customer_orders = $vendor->get_orders(6, $offset, $meta_query);
        $WCMp->template->get_template('vendor-dashboard/vendor-withdrawal/vendor-withdrawal-items.php', array('vendor' => $vendor, 'commissions' => $customer_orders));
        die;
    }

    function wcmp_vendor_csv_download_per_order() {
        global $WCMp, $wpdb;

        if (isset($_GET['action']) && isset($_GET['order_id']) && isset($_GET['nonce'])) {
            $action = $_GET['action'];
            $order_id = $_GET['order_id'];
            $nonce = $_REQUEST["nonce"];

            if (!wp_verify_nonce($nonce, $action))
                die('Invalid request');

            $vendor = get_wcmp_vendor(get_current_vendor_id());
            $vendor = apply_filters('wcmp_csv_download_per_order_vendor', $vendor);
            if (!$vendor)
                die('Invalid request');
            $order_data = array();
            $customer_orders = $wpdb->get_results("SELECT DISTINCT commission_id from `{$wpdb->prefix}wcmp_vendor_orders` where vendor_id = " . $vendor->id . " AND order_id = " . $order_id, ARRAY_A);
            if (!empty($customer_orders)) {
                $commission_id = $customer_orders[0]['commission_id'];
                $order_data[$commission_id] = $order_id;
                $WCMp->vendor_dashboard->generate_csv($order_data, $vendor);
            }
            die;
        }
    }

    /**
     * Unassign vendor from a product
     */
    function unassign_vendor() {
        global $WCMp;

        $product_id = $_POST['product_id'];
        $vendor = get_wcmp_product_vendors($product_id);
        $admin_id = get_current_user_id();

        $_product = wc_get_product($product_id);
        $orders = array();
        if ($_product->is_type('variable')) {
            $get_children = $_product->get_children();
            if (!empty($get_children)) {
                foreach ($get_children as $child) {
                    $orders = array_merge($orders, $vendor->get_vendor_orders_by_product($vendor->term_id, $child));
                }
                $orders = array_unique($orders);
            }
        } else {
            $orders = array_unique($vendor->get_vendor_orders_by_product($vendor->term_id, $product_id));
        }

        foreach ($orders as $order_id) {
            $order = new WC_Order($order_id);
            $items = $order->get_items('line_item');
            foreach ($items as $item_id => $item) {
                wc_add_order_item_meta($item_id, '_vendor_id', $vendor->id);
            }
        }

        wp_delete_object_term_relationships($product_id, $WCMp->taxonomy->taxonomy_name);
        wp_delete_object_term_relationships($product_id, 'product_shipping_class');
        wp_update_post(array('ID' => $product_id, 'post_author' => $admin_id));
        delete_post_meta($product_id, '_commission_per_product');
        delete_post_meta($product_id, '_commission_percentage_per_product');
        delete_post_meta($product_id, '_commission_fixed_with_percentage_qty');
        delete_post_meta($product_id, '_commission_fixed_with_percentage');

        $product_obj = wc_get_product($product_id);
        if ($product_obj->is_type('variable')) {
            $child_ids = $product_obj->get_children();
            if (isset($child_ids) && !empty($child_ids)) {
                foreach ($child_ids as $child_id) {
                    delete_post_meta($child_id, '_commission_fixed_with_percentage');
                    delete_post_meta($child_id, '_product_vendors_commission_percentage');
                    delete_post_meta($child_id, '_product_vendors_commission_fixed_per_trans');
                    delete_post_meta($child_id, '_product_vendors_commission_fixed_per_qty');
                }
            }
        }

        die;
    }

    /**
     * WCMp Product Report sorting
     */
    function product_report_sort() {
        global $WCMp;

        $sort_choosen = isset($_POST['sort_choosen']) ? $_POST['sort_choosen'] : '';
        $report_array = isset($_POST['report_array']) ? $_POST['report_array'] : array();
        $report_bk = isset($_POST['report_bk']) ? $_POST['report_bk'] : array();
        $max_total_sales = isset($_POST['max_total_sales']) ? $_POST['max_total_sales'] : 0;
        $total_sales_sort = isset($_POST['total_sales_sort']) ? $_POST['total_sales_sort'] : array();
        $admin_earning_sort = isset($_POST['admin_earning_sort']) ? $_POST['admin_earning_sort'] : array();
        ;

        $i = 0;
        $max_value = 10;
        $report_sort_arr = array();

        if ($sort_choosen == 'total_sales_desc') {
            arsort($total_sales_sort);
            foreach ($total_sales_sort as $product_id => $value) {
                if ($i++ < $max_value) {
                    $report_sort_arr[$product_id]['total_sales'] = $report_bk[$product_id]['total_sales'];
                    $report_sort_arr[$product_id]['admin_earning'] = $report_bk[$product_id]['admin_earning'];
                }
            }
        } else if ($sort_choosen == 'total_sales_asc') {
            asort($total_sales_sort);
            foreach ($total_sales_sort as $product_id => $value) {
                if ($i++ < $max_value) {
                    $report_sort_arr[$product_id]['total_sales'] = $report_bk[$product_id]['total_sales'];
                    $report_sort_arr[$product_id]['admin_earning'] = $report_bk[$product_id]['admin_earning'];
                }
            }
        } else if ($sort_choosen == 'admin_earning_desc') {
            arsort($admin_earning_sort);
            foreach ($admin_earning_sort as $product_id => $value) {
                if ($i++ < $max_value) {
                    $report_sort_arr[$product_id]['total_sales'] = $report_bk[$product_id]['total_sales'];
                    $report_sort_arr[$product_id]['admin_earning'] = $report_bk[$product_id]['admin_earning'];
                }
            }
        } else if ($sort_choosen == 'admin_earning_asc') {
            asort($admin_earning_sort);
            foreach ($admin_earning_sort as $product_id => $value) {
                if ($i++ < $max_value) {
                    $report_sort_arr[$product_id]['total_sales'] = $report_bk[$product_id]['total_sales'];
                    $report_sort_arr[$product_id]['admin_earning'] = $report_bk[$product_id]['admin_earning'];
                }
            }
        }

        $report_chart = $report_html = '';

        if (sizeof($report_sort_arr) > 0) {
            foreach ($report_sort_arr as $product_id => $sales_report) {
                $width = ( $sales_report['total_sales'] > 0 ) ? ( round($sales_report['total_sales']) / round($max_total_sales) ) * 100 : 0;
                $width2 = ( $sales_report['admin_earning'] > 0 ) ? ( round($sales_report['admin_earning']) / round($max_total_sales) ) * 100 : 0;

                $product = new WC_Product($product_id);
                $product_url = admin_url('post.php?post=' . $product_id . '&action=edit');

                $report_chart .= '<tr><th><a href="' . $product_url . '">' . $product->get_title() . '</a></th>
                    <td width="1%"><span>' . wc_price($sales_report['total_sales']) . '</span><span class="alt">' . wc_price($sales_report['admin_earning']) . '</span></td>
                    <td class="bars">
                        <span style="width:' . esc_attr($width) . '%">&nbsp;</span>
                        <span class="alt" style="width:' . esc_attr($width2) . '%">&nbsp;</span>
                    </td></tr>';
            }

            $report_html = '
                <h4>' . __("Sales and Earnings", 'dc-woocommerce-multi-vendor') . '</h4>
                <div class="bar_indecator">
                    <div class="bar1">&nbsp;</div>
                    <span class="">' . __("Gross Sales", 'dc-woocommerce-multi-vendor') . '</span>
                    <div class="bar2">&nbsp;</div>
                    <span class="">' . __("My Earnings", 'dc-woocommerce-multi-vendor') . '</span>
                </div>
                <table class="bar_chart">
                    <thead>
                        <tr>
                            <th>' . __("Month", 'dc-woocommerce-multi-vendor') . '</th>
                            <th colspan="2">' . __("Sales Report", 'dc-woocommerce-multi-vendor') . '</th>
                        </tr>
                    </thead>
                    <tbody>
                        ' . $report_chart . '
                    </tbody>
                </table>
            ';
        } else {
            $report_html = '<tr><td colspan="3">' . __('No product was sold in the given period.', 'dc-woocommerce-multi-vendor') . '</td></tr>';
        }

        echo $report_html;

        die;
    }

    function send_enquiry_to_vendor($send_to, $product_id) {
        global $WCMp;
        $vendor = get_wcmp_product_vendors($product_id);
        if ($vendor) {
            $send_to = $vendor->user_data->data->user_email;
        }
        return $send_to;
    }

    /**
     * WCMp Product Data Searching
     */
    function search_product_data() {
        global $WCMp;

        $product_id = $_POST['product_id'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];

        $report_chart = $report_html = '';

        if ($product_id) {

            $total_sales = $admin_earnings = array();
            $max_total_sales = 0;

            $product_orders = get_wcmp_vendor_orders(array('product_id' => $product_id));

            if (!empty($product_orders)) {

                $gross_sales = $my_earning = $vendor_earning = 0;
                foreach ($product_orders as $order_obj) {
                    $order = new WC_Order($order_obj->order_id);

                    if (strtotime($order->get_date_created()) > $start_date && strtotime($order->get_date_created()) < $end_date) {
                        // Get date
                        $date = date('Ym', strtotime($order->get_date_created()));

                        $item = new WC_Order_Item_Product($order_obj->order_item_id);
                        $gross_sales += $item->get_subtotal();
                        $total_sales[$date] = isset($total_sales[$date]) ? ( $total_sales[$date] + $item->get_subtotal() ) : $item->get_subtotal();
                        $vendors_orders_amount = get_wcmp_vendor_order_amount(array('order_id' => $order->get_id(), 'product_id' => $order_obj->product_id));

                        $vendor_earning = $vendors_orders_amount['commission_amount'];
                        if ($vendor = get_wcmp_vendor(get_current_vendor_id()))
                            $admin_earnings[$date] = isset($admin_earnings[$date]) ? ( $admin_earnings[$date] + $vendor_earning ) : $vendor_earning;
                        else
                            $admin_earnings[$date] = isset($admin_earnings[$date]) ? ( $admin_earnings[$date] + $item->get_subtotal() - $vendor_earning ) : $item->get_subtotal() - $vendor_earning;

                        if ($total_sales[$date] > $max_total_sales)
                            $max_total_sales = $total_sales[$date];
                    }
                }
            }


            if (sizeof($total_sales) > 0) {
                foreach ($total_sales as $date => $sales) {
                    $width = ( $sales > 0 ) ? ( round($sales) / round($max_total_sales) ) * 100 : 0;
                    $width2 = ( $admin_earnings[$date] > 0 ) ? ( round($admin_earnings[$date]) / round($max_total_sales) ) * 100 : 0;

                    $report_chart .= '<tr><th>' . date_i18n('F', strtotime($date . '01')) . '</th>
                        <td width="1%"><span>' . wc_price($sales) . '</span><span class="alt">' . wc_price($admin_earnings[$date]) . '</span></td>
                        <td class="bars">
                            <span style="width:' . esc_attr($width) . '%">&nbsp;</span>
                            <span class="alt" style="width:' . esc_attr($width2) . '%">&nbsp;</span>
                        </td></tr>';
                }

                $report_html = '
                    <h4>' . __("Sales and Earnings", 'dc-woocommerce-multi-vendor') . '</h4>
                    <div class="bar_indecator">
                        <div class="bar1">&nbsp;</div>
                        <span class="">' . __("Gross Sales", 'dc-woocommerce-multi-vendor') . '</span>
                        <div class="bar2">&nbsp;</div>
                        <span class="">' . __("My Earnings", 'dc-woocommerce-multi-vendor') . '</span>
                    </div>
                    <table class="bar_chart">
                        <thead>
                            <tr>
                                <th>' . __("Month", 'dc-woocommerce-multi-vendor') . '</th>
                                <th colspan="2">' . __("Sales Report", 'dc-woocommerce-multi-vendor') . '</th>
                            </tr>
                        </thead>
                        <tbody>
                            ' . $report_chart . '
                        </tbody>
                    </table>
                ';
            } else {
                $report_html = '<tr><td colspan="3">' . __('This product was not sold in the given period.', 'dc-woocommerce-multi-vendor') . '</td></tr>';
            }

            echo $report_html;
        } else {
            echo '<tr><td colspan="3">' . __('Please select a product.', 'dc-woocommerce-multi-vendor') . '</td></tr>';
        }

        die;
    }

    /**
     * WCMp Vendor Data Searching
     */
    function search_vendor_data() {
        global $WCMp, $wpdb;

        $chosen_product_ids = $vendor_id = $vendor = false;
        $gross_sales = $my_earning = $vendor_earning = 0;
        $vendor_term_id = $_POST['vendor_id'];
        $vendor = get_wcmp_vendor_by_term($vendor_term_id);
        $vendor_id = $vendor->id;
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];

        if ($vendor_id) {
            if ($vendor)
                $products = $vendor->get_products();
            if (!empty($products)) {
                foreach ($products as $product) {
                    $chosen_product_ids[] = $product->ID;
                }
            }
        }

        if ($vendor_id && empty($products)) {
            $no_vendor = '<h4>' . __("Sales and Earnings", 'dc-woocommerce-multi-vendor') . '</h4>
            <table class="bar_chart">
                <thead>
                    <tr>
                        <th>' . __("Month", 'dc-woocommerce-multi-vendor') . '</th>
                        <th colspan="2">' . __("Sales", 'dc-woocommerce-multi-vendor') . '</th>
                    </tr>
                </thead>
                <tbody> 
                    <tr><td colspan="3">' . __("No Sales :(", 'dc-woocommerce-multi-vendor') . '</td></tr>
                </tbody>
            </table>';

            echo $no_vendor;
            die;
        }

        $args = array(
            'post_type' => 'shop_order',
            'posts_per_page' => -1,
            'post_status' => array('wc-pending', 'wc-processing', 'wc-on-hold', 'wc-completed', 'wc-cancelled', 'wc-refunded', 'wc-failed'),
            'meta_query' => array(
                array(
                    'key' => '_commissions_processed',
                    'value' => 'yes',
                    'compare' => '='
                )
            ),
            'date_query' => array(
                'inclusive' => true,
                'after' => array(
                    'year' => date('Y', $start_date),
                    'month' => date('n', $start_date),
                    'day' => date('j', $start_date),
                ),
                'before' => array(
                    'year' => date('Y', $end_date),
                    'month' => date('n', $end_date),
                    'day' => date('j', $end_date),
                ),
            )
        );

        $qry = new WP_Query($args);

        $orders = apply_filters('wcmp_filter_orders_report_vendor', $qry->get_posts());

        if (!empty($orders)) {

            $total_sales = $admin_earning = array();
            $max_total_sales = 0;

            foreach ($orders as $order_obj) {
                $order = new WC_Order($order_obj->ID);
                $vendors_orders = get_wcmp_vendor_orders(array('order_id' => $order->get_id()));
                $vendors_orders_amount = get_wcmp_vendor_order_amount(array('order_id' => $order->get_id()), $vendor_id);
                $current_vendor_orders = wp_list_filter($vendors_orders, array('vendor_id' => $vendor_id));
                $gross_sales += $vendors_orders_amount['total'] - $vendors_orders_amount['commission_amount'];
                $vendor_earning += $vendors_orders_amount['total'];

                foreach ($current_vendor_orders as $key => $vendor_order) {
                    $item = new WC_Order_Item_Product($vendor_order->order_item_id);
                    $gross_sales += $item->get_subtotal();
                }
                // Get date
                $date = date('Ym', strtotime($order->get_date_created()));

                // Set values
                $total_sales[$date] = $gross_sales;
                $admin_earning[$date] = $gross_sales - $vendor_earning;

                if ($total_sales[$date] > $max_total_sales)
                    $max_total_sales = $total_sales[$date];
            }

            $report_chart = $report_html = '';
            if (count($total_sales) > 0) {
                foreach ($total_sales as $date => $sales) {
                    $width = ( $sales > 0 ) ? ( round($sales) / round($max_total_sales) ) * 100 : 0;
                    $width2 = ( $admin_earning[$date] > 0 ) ? ( round($admin_earning[$date]) / round($max_total_sales) ) * 100 : 0;

                    $orders_link = admin_url('edit.php?s&post_status=all&post_type=shop_order&action=-1&s=' . urlencode(implode(' ', $chosen_product_titles)) . '&m=' . date('Ym', strtotime($date . '01')) . '&shop_order_status=' . implode(",", apply_filters('woocommerce_reports_order_statuses', array('completed', 'processing', 'on-hold'))));
                    $orders_link = apply_filters('woocommerce_reports_order_link', $orders_link, $chosen_product_ids, $chosen_product_titles);

                    $report_chart .= '<tr><th><a href="' . esc_url($orders_link) . '">' . date_i18n('F', strtotime($date . '01')) . '</a></th>
                        <td width="1%"><span>' . wc_price($sales) . '</span><span class="alt">' . wc_price($admin_earning[$date]) . '</span></td>
                        <td class="bars">
                            <span class="main" style="width:' . esc_attr($width) . '%">&nbsp;</span>
                            <span class="alt" style="width:' . esc_attr($width2) . '%">&nbsp;</span>
                        </td></tr>';
                }

                $report_html = '
                    <h4>' . $vendor_title . '</h4>
                    <div class="bar_indecator">
                        <div class="bar1">&nbsp;</div>
                        <span class="">' . __("Gross Sales", 'dc-woocommerce-multi-vendor') . '</span>
                        <div class="bar2">&nbsp;</div>
                        <span class="">' . __("My Earnings", 'dc-woocommerce-multi-vendor') . '</span>
                    </div>
                    <table class="bar_chart">
                        <thead>
                            <tr>
                                <th>' . __("Month", 'dc-woocommerce-multi-vendor') . '</th>
                                <th colspan="2">' . __("Vendor Earnings", 'dc-woocommerce-multi-vendor') . '</th>
                            </tr>
                        </thead>
                        <tbody>
                            ' . $report_chart . '
                        </tbody>
                    </table>
                ';
            } else {
                $report_html = '<tr><td colspan="3">' . __('This vendor did not generate any sales in the given period.', 'dc-woocommerce-multi-vendor') . '</td></tr>';
            }
        }

        echo $report_html;

        die;
    }

    /**
     * WCMp Vendor Report sorting
     */
    function vendor_report_sort() {
        global $WCMp;

        $dropdown_selected = isset($_POST['sort_choosen']) ? $_POST['sort_choosen'] : '';
        $vendor_report = isset($_POST['report_array']) ? $_POST['report_array'] : array();
        $report_bk = isset($_POST['report_bk']) ? $_POST['report_bk'] : array();
        $max_total_sales = isset($_POST['max_total_sales']) ? $_POST['max_total_sales'] : 0;
        $total_sales_sort = isset($_POST['total_sales_sort']) ? $_POST['total_sales_sort'] : array();
        $admin_earning_sort = isset($_POST['admin_earning_sort']) ? $_POST['admin_earning_sort'] : array();
        $report_sort_arr = array();
        $chart_arr = '';
        $i = 0;
        $max_value = 10;

        if ($dropdown_selected == 'total_sales_desc') {
            arsort($total_sales_sort);
            foreach ($total_sales_sort as $key => $value) {
                if ($i++ < $max_value) {
                    $report_sort_arr[$key]['total_sales'] = $report_bk[$key]['total_sales'];
                    $report_sort_arr[$key]['admin_earning'] = $report_bk[$key]['admin_earning'];
                }
            }
        } else if ($dropdown_selected == 'total_sales_asc') {
            asort($total_sales_sort);
            foreach ($total_sales_sort as $key => $value) {
                if ($i++ < $max_value) {
                    $report_sort_arr[$key]['total_sales'] = $report_bk[$key]['total_sales'];
                    $report_sort_arr[$key]['admin_earning'] = $report_bk[$key]['admin_earning'];
                }
            }
        } else if ($dropdown_selected == 'admin_earning_desc') {
            arsort($admin_earning_sort);
            foreach ($admin_earning_sort as $key => $value) {
                if ($i++ < $max_value) {
                    $report_sort_arr[$key]['total_sales'] = $report_bk[$key]['total_sales'];
                    $report_sort_arr[$key]['admin_earning'] = $report_bk[$key]['admin_earning'];
                }
            }
        } else if ($dropdown_selected == 'admin_earning_asc') {
            asort($admin_earning_sort);
            foreach ($admin_earning_sort as $key => $value) {
                if ($i++ < $max_value) {
                    $report_sort_arr[$key]['total_sales'] = $report_bk[$key]['total_sales'];
                    $report_sort_arr[$key]['admin_earning'] = $report_bk[$key]['admin_earning'];
                }
            }
        }

        if (sizeof($report_sort_arr) > 0) {
            foreach ($report_sort_arr as $vendor_id => $sales_report) {
                $total_sales_width = ( $sales_report['total_sales'] > 0 ) ? $sales_report['total_sales'] / round($max_total_sales) * 100 : 0;
                $admin_earning_width = ( $sales_report['admin_earning'] > 0 ) ? ( $sales_report['admin_earning'] / round($max_total_sales) ) * 100 : 0;

                $user = get_userdata($vendor_id);
                $user_name = $user->data->display_name;

                $chart_arr .= '<tr><th><a href="user-edit.php?user_id=' . $vendor_id . '">' . $user_name . '</a></th>
                <td width="1%"><span>' . wc_price($sales_report['total_sales']) . '</span><span class="alt">' . wc_price($sales_report['admin_earning']) . '</span></td>
                <td class="bars">
                    <span class="main" style="width:' . esc_attr($total_sales_width) . '%">&nbsp;</span>
                    <span class="alt" style="width:' . esc_attr($admin_earning_width) . '%">&nbsp;</span>
                </td></tr>';
            }

            $html_chart = '
                <h4>' . __("Sales and Earnings", 'dc-woocommerce-multi-vendor') . '</h4>
                <div class="bar_indecator">
                    <div class="bar1">&nbsp;</div>
                    <span class="">' . __("Gross Sales", 'dc-woocommerce-multi-vendor') . '</span>
                    <div class="bar2">&nbsp;</div>
                    <span class="">' . __("My Earnings", 'dc-woocommerce-multi-vendor') . '</span>
                </div>
                <table class="bar_chart">
                    <thead>
                        <tr>
                            <th>' . __("Vendors", 'dc-woocommerce-multi-vendor') . '</th>
                            <th colspan="2">' . __("Sales Report", 'dc-woocommerce-multi-vendor') . '</th>
                        </tr>
                    </thead>
                    <tbody>
                        ' . $chart_arr . '
                    </tbody>
                </table>
            ';
        } else {
            $html_chart = '<tr><td colspan="3">' . __('Any vendor did not generate any sales in the given period.', 'dc-woocommerce-multi-vendor') . '</td></tr>';
        }

        echo $html_chart;

        die;
    }

    /**
     * WCMp Transaction complete mark
     */
    function transaction_done_button() {
        global $WCMp;
        $transaction_id = $_POST['trans_id'];
        $vendor_id = $_POST['vendor_id'];
        update_post_meta($transaction_id, 'paid_date', date("Y-m-d H:i:s"));
        $commission_detail = get_post_meta($transaction_id, 'commission_detail', true);
        if ($commission_detail && is_array($commission_detail)) {
            foreach ($commission_detail as $commission_id) {
                wcmp_paid_commission_status($commission_id);
            }
            $email_admin = WC()->mailer()->emails['WC_Email_Vendor_Commission_Transactions'];
            $email_admin->trigger($transaction_id, $vendor_id);
            update_post_meta($transaction_id, '_dismiss_to_do_list', 'true');
            wp_update_post(array('ID' => $transaction_id, 'post_status' => 'wcmp_completed'));
        }
        die;
    }

    /**
     * WCMp get more orders
     */
    function get_more_orders() {
        global $WCMp;
        $data_to_show = isset($_POST['data_to_show']) ? $_POST['data_to_show'] : '';
        $order_status = isset($_POST['order_status']) ? $_POST['order_status'] : '';
        $vendor = get_wcmp_vendor(get_current_vendor_id());
        $WCMp->template->get_template('vendor-dashboard/vendor-orders/vendor-orders-item.php', array('vendor' => $vendor, 'orders' => $data_to_show, 'order_status' => $order_status));
        die;
    }

    /**
     * WCMp dismiss todo list
     */
    function dismiss_vendor_to_do_list() {
        global $WCMp;

        $id = $_POST['id'];
        $type = $_POST['type'];
        if ($type == 'user') {
            update_user_meta($id, '_dismiss_to_do_list', 'true');
        } else if ($type == 'shop_coupon') {
            update_post_meta($id, '_dismiss_to_do_list', 'true');
        } else if ($type == 'product') {
            update_post_meta($id, '_dismiss_to_do_list', 'true');
        } else if ($type == 'dc_commission') {
            update_post_meta($id, '_dismiss_to_do_list', 'true');
            wp_update_post(array('ID' => $id, 'post_status' => 'wcmp_canceled'));
        }
        die();
    }

    /**
     * WCMp current user attachment
     */
    function show_current_user_attachments($query = array()) {
        $user_id = get_current_vendor_id();
        if (is_user_wcmp_vendor($user_id)) {
            $query['author'] = $user_id;
        }
        return $query;
    }

    /**
     * Search vendors via AJAX
     *
     * @return void
     */
    function woocommerce_json_search_vendors() {
        global $WCMp;

        //check_ajax_referer( 'search-vendors', 'security' );

        header('Content-Type: application/json; charset=utf-8');

        $term = urldecode(stripslashes(strip_tags($_GET['term'])));

        if (empty($term))
            die();

        $found_vendors = array();

        $args1 = array(
            'search' => '*' . $term . '*',
            'search_columns' => array('user_login', 'display_name', 'user_email')
        );
        $args2 = array(
            'meta_key' => '_vendor_page_title',
            'meta_value' => esc_attr($term),
            'meta_compare' => 'LIKE',
        );
        $vendors1 = get_wcmp_vendors($args1);
        $vendors2 = get_wcmp_vendors($args2);
        $vendors = array_unique(array_merge($vendors1, $vendors2), SORT_REGULAR);

        if (!empty($vendors) && is_array($vendors)) {
            foreach ($vendors as $vendor) {
                $vendor_term = get_term($vendor->term_id);
                $found_vendors[$vendor->term_id] = $vendor_term->name;
            }
        }

        echo json_encode($found_vendors);
        die();
    }

    /**
     * Activate Pending Vendor via AJAX
     *
     * @return void
     */
    function activate_pending_vendor() {
        $user_id = filter_input(INPUT_POST, 'user_id');
        $redirect = filter_input(INPUT_POST, 'redirect');
        $custom_note = filter_input(INPUT_POST, 'custom_note');
        $note_by = filter_input(INPUT_POST, 'note_by');
        
        if ($user_id) {
            $user = new WP_User(absint($user_id));
            $user->set_role('dc_vendor');
            $user_dtl = get_userdata(absint($user_id));
            $email = WC()->mailer()->emails['WC_Email_Approved_New_Vendor_Account'];
            $email->trigger($user_id, $user_dtl->user_pass);
            
            if(isset($custom_note) && $custom_note != '') {
				$wcmp_vendor_rejection_notes = unserialize( get_user_meta( $user_id, 'wcmp_vendor_rejection_notes', true ) );
				$wcmp_vendor_rejection_notes[time()] = array(
						'note_by' => $note_by,
						'note' => $custom_note);
				update_user_meta( $user_id, 'wcmp_vendor_rejection_notes', serialize( $wcmp_vendor_rejection_notes ) );
			}
        }
        
        if(isset($redirect) && $redirect) wp_send_json( array( 'redirect' => true, 'redirect_url' => wp_get_referer() ? wp_get_referer() : admin_url( 'admin.php?page=vendors' ) ) );
        exit;
    }

    /**
     * Reject Pending Vendor via AJAX
     *
     * @return void
     */
    function reject_pending_vendor() {
        $user_id = filter_input(INPUT_POST, 'user_id');
        $redirect = filter_input(INPUT_POST, 'redirect');
        $custom_note = filter_input(INPUT_POST, 'custom_note');
        $note_by = filter_input(INPUT_POST, 'note_by');
        
        if ($user_id) {
            $user = new WP_User(absint($user_id));
            $user->set_role('dc_rejected_vendor');
            
			if(isset($custom_note) && $custom_note != '') {
				$wcmp_vendor_rejection_notes = unserialize( get_user_meta( $user_id, 'wcmp_vendor_rejection_notes', true ) );
				$wcmp_vendor_rejection_notes[time()] = array(
						'note_by' => $note_by,
						'note' => $custom_note);
				update_user_meta( $user_id, 'wcmp_vendor_rejection_notes', serialize( $wcmp_vendor_rejection_notes ) );
			}		
        }
        
        if(isset($redirect) && $redirect) wp_send_json( array( 'redirect' => true, 'redirect_url' => wp_get_referer() ? wp_get_referer() : admin_url( 'admin.php?page=vendors' ) ) );
        exit;
    }
    
    /**
     * Suspend Vendor via AJAX
     *
     * @return void
     */
    function wcmp_suspend_vendor() {
        $user_id = filter_input(INPUT_POST, 'user_id');
        $redirect = filter_input(INPUT_POST, 'redirect');
        if ($user_id) {
        	$user = new WP_User(absint($user_id));
            if(is_user_wcmp_vendor($user)) {
            	update_user_meta($user_id, '_vendor_turn_off', 'Enable');
            }
        }
        if(isset($redirect) && $redirect) wp_send_json( array( 'redirect' => true, 'redirect_url' => wp_get_referer() ? wp_get_referer() : admin_url( 'admin.php?page=vendors' ) ) );
        exit;
    }
    /**
     * Activate Vendor via AJAX from Suspend state
     *
     * @return void
     */
    function wcmp_activate_vendor() {
        $user_id = filter_input(INPUT_POST, 'user_id');
        $redirect = filter_input(INPUT_POST, 'redirect');
        if ($user_id) {
        	$user = new WP_User(absint($user_id));
            if(is_user_wcmp_vendor($user)) {
            	delete_user_meta($user_id, '_vendor_turn_off');
            }
        }
        if(isset($redirect) && $redirect) wp_send_json( array( 'redirect' => true, 'redirect_url' => wp_get_referer() ? wp_get_referer() : admin_url( 'admin.php?page=vendors' ) ) );
        exit;
    }

    /**
     * Report Abuse Vendor via AJAX
     *
     * @return void
     */
    function send_report_abuse() {
        global $WCMp;
        $check = false;
        $name = sanitize_text_field($_POST['name']);
        $from_email = sanitize_email($_POST['email']);
        $user_message = sanitize_text_field($_POST['msg']);
        $product_id = sanitize_text_field($_POST['product_id']);

        $check = !empty($name) && !empty($from_email) && !empty($user_message);

        if ($check) {
            $product = get_post(absint($product_id));
            $vendor = get_wcmp_product_vendors($product_id);
            $vendor_term = get_term($vendor->term_id);
            $subject = __('Report an abuse for product', 'dc-woocommerce-multi-vendor') . get_the_title($product_id);

            $to = sanitize_email(get_option('admin_email'));
            $from_email = sanitize_email($from_email);
            $headers = "From: {$name} <{$from_email}>" . "\r\n";

            $message = sprintf(__("User %s (%s) is reporting an abuse on the following product: \n", 'dc-woocommerce-multi-vendor'), $name, $from_email);
            $message .= sprintf(__("Product details: %s (ID: #%s) \n", 'dc-woocommerce-multi-vendor'), $product->post_title, $product->ID);

            $message .= sprintf(__("Vendor shop: %s \n", 'dc-woocommerce-multi-vendor'), $vendor_term->name);

            $message .= sprintf(__("Message: %s\n", 'dc-woocommerce-multi-vendor'), $user_message);
            $message .= "\n\n\n";

            $message .= sprintf(__("Product page:: %s\n", 'dc-woocommerce-multi-vendor'), get_the_permalink($product->ID));

            /* === Send Mail === */
            $response = wp_mail($to, $subject, $message, $headers);
        }
        die();
    }

    /**
     * Set a flag while dismiss WCMp service notice
     */
    public function dismiss_wcmp_servive_notice() {
        $updated = update_option('_is_dismiss_service_notice', true);
        echo $updated;
        die();
    }

    function vendor_list_by_search_keyword() {
        global $WCMp;
        // check vendor_search_nonce
        if (!isset($_POST['vendor_search_nonce']) || !wp_verify_nonce($_POST['vendor_search_nonce'], 'wcmp_widget_vendor_search_form')) {
            die();
        }
        $html = '';
        if (isset($_POST['s']) && sanitize_text_field($_POST['s'])) {
            $args1 = array(
                'search' => '*' . esc_attr($_POST['s']) . '*',
                'search_columns' => array('display_name', 'user_login', 'user_nicename'),
            );
            $args2 = array(
                'meta_key' => '_vendor_page_title',
                'meta_value' => esc_attr($_POST['s']),
                'meta_compare' => 'LIKE',
            );
            $vendors1 = get_wcmp_vendors($args1);
            $vendors2 = get_wcmp_vendors($args2);
            $vendors = array_unique(array_merge($vendors1, $vendors2), SORT_REGULAR);

            if ($vendors) {
                foreach ($vendors as $vendors_key => $vendor) {
                    $vendor_term = get_term($vendor->term_id);
                    $vendor->image = $vendor->get_image() ? $vendor->get_image() : $WCMp->plugin_url . 'assets/images/WP-stdavatar.png';
                    $html .= '<div style=" width: 100%; margin-bottom: 5px; clear: both; display: block;">
                    <div style=" width: 25%;  display: inline;">        
                    <img width="50" height="50" class="vendor_img" style="display: inline;" src="' . $vendor->image . '" id="vendor_image_display">
                    </div>
                    <div style=" width: 75%;  display: inline;  padding: 10px;">
                            <a href="' . esc_attr($vendor->permalink) . '">
                                ' . $vendor_term->name . '
                            </a>
                    </div>
                </div>';
                }
            } else {
                $html .= '<div style=" width: 100%; margin-bottom: 5px; clear: both; display: block;">
                    <div style="display: inline;  padding: 10px;">
                        ' . __('No Vendor Matched!', 'dc-woocommerce-multi-vendor') . '
                    </div>
                </div>';
            }
        } else {
            $vendors = get_wcmp_vendors();
            if ($vendors) {
                foreach ($vendors as $vendors_key => $vendor) {
                    $vendor_term = get_term($vendor->term_id);
                    $vendor->image = $vendor->get_image() ? $vendor->get_image() : $WCMp->plugin_url . 'assets/images/WP-stdavatar.png';
                    $html .= '<div style=" width: 100%; margin-bottom: 5px; clear: both; display: block;">
                    <div style=" width: 25%;  display: inline;">        
                    <img width="50" height="50" class="vendor_img" style="display: inline;" src="' . $vendor->image . '" id="vendor_image_display">
                    </div>
                    <div style=" width: 75%;  display: inline;  padding: 10px;">
                            <a href="' . esc_attr($vendor->permalink) . '">
                                ' . $vendor_term->name . '
                            </a>
                    </div>
                </div>';
                }
            }
        }
        echo $html;
        die();
    }

    //frontend product managet ajax callback functions
    public function generate_taxonomy_attributes() {
        global $WCMp, $wc_product_attributes;

        $att_taxonomy = $_POST['taxonomy'];
        $attribute_taxonomy = $wc_product_attributes[$att_taxonomy];
        $attributes = array();
        $attributes[0]['term_name'] = $att_taxonomy;
        $attributes[0]['name'] = wc_attribute_label($att_taxonomy);
        $attributes[0]['value'] = '';
        $attributes[0]['tax_name'] = $att_taxonomy;
        $attributes[0]['is_taxonomy'] = 1;
        $args = array(
            'orderby' => 'name',
            'hide_empty' => 0
        );
        $all_terms = get_terms($att_taxonomy, apply_filters('woocommerce_product_attribute_terms', $args));

        if ('select' === $attribute_taxonomy->attribute_type) {
            if ($all_terms) {
                foreach ($all_terms as $term) {
                    $attributes_option[$term->term_id] = esc_attr(apply_filters('woocommerce_product_attribute_term_name', $term->name, $term));
                }
            }

            $WCMp->wcmp_frontend_fields->wcmp_generate_form_field(apply_filters('wcmp_fpm_generate_taxonomy_attributes', array(
                "attributes" => array('label' => __('Attributes', 'dc-woocommerce-multi-vendor'), 'type' => 'multiinput', 'class' => 'regular-text pro_ele simple variable external', 'label_class' => 'pro_title', 'value' => $attributes, 'options' => array(
                        "term_name" => array('type' => 'hidden', 'label_class' => 'pro_title'),
                        "name" => array('label' => __('Name', 'dc-woocommerce-multi-vendor'), 'type' => 'text', 'class' => 'regular-text pro_ele simple variable external', 'label_class' => 'pro_title'),
                        "value" => array('label' => __('Value(s):', 'dc-woocommerce-multi-vendor'), 'type' => 'select', 'attributes' => array('multiple' => 'multiple'), 'class' => 'regular-select pro_ele simple variable external', 'options' => $attributes_option, 'label_class' => 'pro_title'),
                        "is_visible" => array('label' => __('Visible on the product page', 'dc-woocommerce-multi-vendor'), 'type' => 'checkbox', 'value' => 'enable', 'class' => 'regular-checkbox pro_ele simple variable external', 'label_class' => 'pro_title checkbox_title'),
                        "is_variation" => array('label' => __('Use as Variation', 'dc-woocommerce-multi-vendor'), 'type' => 'checkbox', 'value' => 'enable', 'class' => 'regular-checkbox pro_ele variable variable-subscription', 'label_class' => 'pro_title checkbox_title pro_ele variable variable-subscription'),
                        "tax_name" => array('type' => 'hidden'),
                        "is_taxonomy" => array('type' => 'hidden')
                    )))
            ));
        } else {
            $WCMp->wcmp_frontend_fields->wcmp_generate_form_field(apply_filters('wcmp_fpm_generate_taxonomy_attributes', array(
                "attributes" => array('label' => __('Attributes', 'dc-woocommerce-multi-vendor'), 'type' => 'multiinput', 'class' => 'regular-text pro_ele simple variable external', 'label_class' => 'pro_title', 'value' => $attributes, 'options' => array(
                        "term_name" => array('type' => 'hidden', 'label_class' => 'pro_title'),
                        "name" => array('label' => __('Name', 'dc-woocommerce-multi-vendor'), 'type' => 'text', 'class' => 'regular-text pro_ele simple variable external', 'label_class' => 'pro_title'),
                        "value" => array('label' => __('Value(s):', 'dc-woocommerce-multi-vendor'), 'type' => 'textarea', 'class' => 'regular-textarea pro_ele simple variable external', 'placeholder' => sprintf(esc_attr__('Enter some text, or some attributes by "%s" separating values.', 'dc-woocommerce-multi-vendor'), WC_DELIMITER), 'label_class' => 'pro_title'),
                        "is_visible" => array('label' => __('Visible on the product page', 'dc-woocommerce-multi-vendor'), 'type' => 'checkbox', 'value' => 'enable', 'class' => 'regular-checkbox pro_ele simple variable external', 'label_class' => 'pro_title checkbox_title'),
                        "is_variation" => array('label' => __('Use as Variation', 'dc-woocommerce-multi-vendor'), 'type' => 'checkbox', 'value' => 'enable', 'class' => 'regular-checkbox pro_ele variable variable-subscription', 'label_class' => 'pro_title checkbox_title pro_ele variable variable-subscription'),
                        "tax_name" => array('type' => 'hidden'),
                        "is_taxonomy" => array('type' => 'hidden')
                    )))
            ));
        }
        die();
    }

    public function generate_variation_attributes() {


        $product_manager_form_data = array();
        parse_str($_POST['product_manager_form'], $product_manager_form_data);

        if (isset($product_manager_form_data['attributes']) && !empty($product_manager_form_data['attributes'])) {
            $pro_attributes = '{';
            $attr_first = true;
            foreach ($product_manager_form_data['attributes'] as $attributes) {
                if (isset($attributes['is_variation'])) {
                    if (!empty($attributes['name']) && !empty($attributes['value'])) {
                        if (!$attr_first)
                            $pro_attributes .= ',';
                        if ($attr_first)
                            $attr_first = false;

                        if ($attributes['is_taxonomy']) {
                            $pro_attributes .= '"' . $attributes['tax_name'] . '": {';
                            if (!is_array($attributes['value'])) {
                                $att_values = explode("|", $attributes['value']);
                                $is_first = true;
                                foreach ($att_values as $att_value) {
                                    if (!$is_first)
                                        $pro_attributes .= ',';
                                    if ($is_first)
                                        $is_first = false;
                                    $pro_attributes .= '"' . sanitize_title($att_value) . '": "' . trim($att_value) . '"';
                                }
                            } else {
                                $att_values = $attributes['value'];
                                $is_first = true;
                                foreach ($att_values as $att_value) {
                                    if (!$is_first)
                                        $pro_attributes .= ',';
                                    if ($is_first)
                                        $is_first = false;
                                    $att_term = get_term(absint($att_value));
                                    if ($att_term) {
                                        $pro_attributes .= '"' . $att_term->slug . '": "' . $att_term->name . '"';
                                    } else {
                                        $pro_attributes .= '"' . sanitize_title($att_value) . '": "' . trim($att_value) . '"';
                                    }
                                }
                            }
                            $pro_attributes .= '}';
                        } else {
                            $pro_attributes .= '"' . $attributes['name'] . '": {';
                            $att_values = explode("|", $attributes['value']);
                            $is_first = true;
                            foreach ($att_values as $att_value) {
                                if (!$is_first)
                                    $pro_attributes .= ',';
                                if ($is_first)
                                    $is_first = false;
                                $pro_attributes .= '"' . trim($att_value) . '": "' . trim($att_value) . '"';
                            }
                            $pro_attributes .= '}';
                        }
                    }
                }
            }
            $pro_attributes .= '}';
            echo $pro_attributes;
        }

        die();
    }

    public function frontend_product_manager() {
        global $WCMp;

        $product_manager_form_data = array();
        parse_str($_POST['product_manager_form'], $product_manager_form_data);
        $WCMp_fpm_messages = get_frontend_product_manager_messages();
        $has_error = false;
        if (isset($product_manager_form_data['title']) && !empty($product_manager_form_data['title'])) {
            $is_update = false;
            $is_publish = false;
            $is_vendor = false;
            $is_new_pro = 0;

            $current_user_id = $vendor_id = apply_filters('wcmp_current_loggedin_vendor_id', get_current_user_id());
            if (is_user_wcmp_vendor($current_user_id))
                $is_vendor = true;

            if (isset($_POST['status']) && ($_POST['status'] == 'draft')) {
                $product_status = 'draft';
            } else {
                if ($is_vendor) {
                    if (!current_user_can('publish_products')) {
                        $product_status = 'pending';
                    } else {
                        $product_status = 'publish';
                    }
                } else {
                    $product_status = 'publish';
                }
            }
            //tinymce media support
//            $post_content = stripslashes(html_entity_decode($_POST['description'], ENT_QUOTES, 'UTF-8'));
//            preg_match('/<iframe.*src=\"(.*)\".*><\/iframe>/isU', $post_content, $matches);
//            if($matches){
//                $post_content = str_replace($matches[0], '[embed]'.$matches[1].'[/embed]', $post_content);
//            }
            do_action('before_wcmp_frontend_product_manager_save', $product_manager_form_data);

            // Creating new product
            $new_product = array(
                'post_title' => wc_clean($product_manager_form_data['title']),
                'post_status' => $product_status,
                'post_type' => 'product',
                'post_excerpt' => $_POST['excerpt'],
                'post_content' => $_POST['description'],
                'post_author' => $vendor_id
                    //'post_name' => sanitize_title($product_manager_form_data['title'])
            );

            if (isset($product_manager_form_data['pro_id']) && $product_manager_form_data['pro_id'] == 0) {
                $is_new_pro = 1;
                if ($product_status != 'draft') {
                    $is_publish = true;
                }
                $new_product_id = wp_insert_post($new_product, true);
            } else { // For Update
                $is_update = true;
                $new_product['ID'] = $product_manager_form_data['pro_id'];
                if (!$is_vendor)
                    unset($new_product['post_author']);
                if (get_post_status($new_product['ID']) != 'draft') {
                    unset($new_product['post_status']);
                } else if ((get_post_status($new_product['ID']) == 'draft') && ($product_status != 'draft')) {
                    $is_publish = true;
                }
                $new_product['post_status'] = $product_status;
                $new_product_id = wp_update_post($new_product, true);
            }

            if (!is_wp_error($new_product_id)) {
                // For Update
                if ($is_update)
                    $new_product_id = $product_manager_form_data['pro_id'];

                // Set Product SKU
                if (isset($product_manager_form_data['sku']) && !empty($product_manager_form_data['sku'])) {
                    update_post_meta($new_product_id, '_sku', $product_manager_form_data['sku']);
                    $unique_sku = wc_product_has_unique_sku($new_product_id, $product_manager_form_data['sku']);
                    if (!$unique_sku) {
                        update_post_meta($new_product_id, '_sku', '');
                        echo '{"status": false, "message": "' . $WCMp_fpm_messages['sku_unique'] . '", "id": "' . $new_product_id . '", "redirect": "' . esc_url(wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_add_product_endpoint', 'vendor', 'general', 'add-product'), $new_product_id)) . '"}';
                        $has_error = true;
                    }
                } else {
                    update_post_meta($new_product_id, '_sku', '');
                }

                // Set Product Type
                wp_set_object_terms($new_product_id, $product_manager_form_data['product_type'], 'product_type');

                // Group Products
                $grouped_products = isset($product_manager_form_data['grouped_products']) ? array_filter(array_map('intval', (array) $product_manager_form_data['grouped_products'])) : array();

                // file paths will be stored in an array keyed off md5(file path)
                $downloadables = array();
                if (isset($product_manager_form_data['is_downloadable']) && isset($product_manager_form_data['downloadable_files'])) {
                    foreach ($product_manager_form_data['downloadable_files'] as $downloadable_files) {
                        if (!empty($downloadable_files['file'])) {
                            $downloadables[] = array(
                                'name' => wc_clean($downloadable_files['name']),
                                'file' => wp_unslash(trim($downloadable_files['file'])),
                                'previous_hash' => md5($downloadable_files['file']),
                            );
                        }
                    }
                }

                // Attributes
                $pro_attributes = array();
                $default_attributes = array();
                if (isset($product_manager_form_data['attributes']) && !empty($product_manager_form_data['attributes'])) {
                    foreach ($product_manager_form_data['attributes'] as $attributes) {
                        if (!empty($attributes['name']) && !empty($attributes['value'])) {

                            $attribute_name = ( $attributes['term_name'] ) ? $attributes['term_name'] : $attributes['name'];

                            $is_visible = 0;
                            if (isset($attributes['is_visible']))
                                $is_visible = 1;

                            $is_variation = 0;
                            if (isset($attributes['is_variation']))
                                $is_variation = 1;
                            if (( $product_manager_form_data['product_type'] != 'variable' ) && ( $product_manager_form_data['product_type'] != 'variable-subscription' ))
                                $is_variation = 0;

                            $is_taxonomy = 0;
                            if ($attributes['is_taxonomy'] == 1)
                                $is_taxonomy = 1;

                            $attribute_id = wc_attribute_taxonomy_id_by_name($attributes['term_name']);
                            $options = isset($attributes['value']) ? $attributes['value'] : '';

                            if (is_array($options)) {
                                // Term ids sent as array.
                                $options = wp_parse_id_list($options);
                            } else {
                                // Terms or text sent in textarea.
                                $options = 0 < $attribute_id ? wc_sanitize_textarea(wc_sanitize_term_text_based($options)) : wc_sanitize_textarea($options);
                                $options = wc_get_text_attributes($options);
                            }

                            if (empty($options)) {
                                continue;
                            }

                            $attribute = new WC_Product_Attribute();
                            $attribute->set_id($attribute_id);
                            $attribute->set_name(wc_clean($attribute_name));
                            $attribute->set_options($options);
                            //$attribute->set_position( $attribute_position[ $i ] );
                            $attribute->set_visible($is_visible);
                            //$attribute->set_variation($is_variation);
                            $pro_attributes[] = $attribute;

                            if ($is_variation) {
                                //$attribute_key = $attribute_name;
                                //$value                        = $attribute->is_taxonomy() ? sanitize_title( $value ) : wc_clean( $value ); // Don't use wc_clean as it destroys sanitized characters in terms.
                                //$default_attributes[ $attribute_key ] = $value;
                            }
                        }
                    }
                }
                $pro_attributes = apply_filters('wcmp_fpm_product_attributes', $pro_attributes, $new_product_id, $product_manager_form_data);
                // Set default Attributes
                if (isset($product_manager_form_data['default_attributes']) && !empty($product_manager_form_data['default_attributes'])) {
                    $default_attributes = array();
                    if ($pro_attributes) {
                        foreach ($pro_attributes as $p_attribute) {
                            if ($p_attribute->get_variation()) {
                                $attribute_key = sanitize_title($p_attribute->get_name());

                                $value = isset($product_manager_form_data['default_attributes']["attribute_" . $attribute_key]) ? stripslashes($product_manager_form_data['default_attributes']["attribute_" . $attribute_key]) : '';

                                $value = $p_attribute->is_taxonomy() ? sanitize_title($value) : wc_clean($value); // Don't use wc_clean as it destroys sanitized characters in terms.
                                $default_attributes[$attribute_key] = $value;
                            }
                        }
                    }
                }

                // Process product type first so we have the correct class to run setters.
                $product_type = empty($product_manager_form_data['product_type']) ? WC_Product_Factory::get_product_type($new_product_id) : sanitize_title(stripslashes($product_manager_form_data['product_type']));
                $classname = WC_Product_Factory::get_product_classname($new_product_id, $product_type ? $product_type : 'simple');
                $product = new $classname($new_product_id);
                $errors = $product->set_props(array(
                    'sku' => isset($product_manager_form_data['sku']) ? wc_clean($product_manager_form_data['sku']) : null,
                    'purchase_note' => wp_kses_post(stripslashes($product_manager_form_data['purchase_note'])),
                    'downloadable' => isset($product_manager_form_data['is_downloadable']),
                    'virtual' => isset($product_manager_form_data['is_virtual']),
                    'featured' => isset($product_manager_form_data['featured']),
                    'catalog_visibility' => wc_clean($product_manager_form_data['visibility']),
                    'tax_status' => isset($product_manager_form_data['tax_status']) ? wc_clean($product_manager_form_data['tax_status']) : null,
                    'tax_class' => isset($product_manager_form_data['tax_class']) ? wc_clean($product_manager_form_data['tax_class']) : null,
                    'weight' => wc_clean($product_manager_form_data['weight']),
                    'length' => wc_clean($product_manager_form_data['length']),
                    'width' => wc_clean($product_manager_form_data['width']),
                    'height' => wc_clean($product_manager_form_data['height']),
                    'shipping_class_id' => absint($product_manager_form_data['shipping_class']),
                    'sold_individually' => !empty($product_manager_form_data['sold_individually']),
                    'upsell_ids' => isset($product_manager_form_data['upsell_ids']) ? array_map('intval', (array) $product_manager_form_data['upsell_ids']) : array(),
                    'cross_sell_ids' => isset($product_manager_form_data['crosssell_ids']) ? array_map('intval', (array) $product_manager_form_data['crosssell_ids']) : array(),
                    'regular_price' => wc_clean($product_manager_form_data['regular_price']),
                    'sale_price' => wc_clean($product_manager_form_data['sale_price']),
                    'date_on_sale_from' => wc_clean($product_manager_form_data['sale_date_from']),
                    'date_on_sale_to' => wc_clean($product_manager_form_data['sale_date_upto']),
                    'manage_stock' => !empty($product_manager_form_data['manage_stock']),
                    'backorders' => wc_clean($product_manager_form_data['backorders']),
                    'stock_status' => wc_clean($product_manager_form_data['stock_status']),
                    'stock_quantity' => wc_stock_amount($product_manager_form_data['stock_qty']),
                    'download_limit' => '' === $product_manager_form_data['download_limit'] ? '' : absint($product_manager_form_data['download_limit']),
                    'download_expiry' => '' === $product_manager_form_data['download_expiry'] ? '' : absint($product_manager_form_data['download_expiry']),
                    'downloads' => $downloadables,
                    //'product_url' => esc_url_raw($product_manager_form_data['product_url']),
                    //'button_text' => wc_clean($product_manager_form_data['button_text']),
                    //'children' => 'grouped' === $product_type ? $grouped_products : null,
                    'reviews_allowed' => !empty($product_manager_form_data['enable_reviews']),
                    'menu_order' => absint($product_manager_form_data['menu_order']),
                    'attributes' => $pro_attributes,
                    'default_attributes' => $default_attributes,
                ));

                if (is_wp_error($errors)) {
                    echo '{"status": false, "message": "' . $errors->get_error_message() . '", "id": "' . $new_product_id . '", "redirect": "' . esc_url(wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_add_product_endpoint', 'vendor', 'general', 'add-product'), $new_product_id)) . '"}';
                    $has_error = true;
                }


                /**
                 * @since 3.0.0 to set props before save.
                 */
                //do_action( 'woocommerce_admin_process_product_object', $product );
                $product->save();

                // Set Product Category

                if (isset($product_manager_form_data['product_cats']) && !empty($product_manager_form_data['product_cats'])) {

                    $is_first = true;
                    foreach ($product_manager_form_data['product_cats'] as $product_cats) {
                        if ($is_first) {
                            $is_first = false;
                            wp_set_object_terms($new_product_id, (int) $product_cats, 'product_cat');
                        } else {
                            wp_set_object_terms($new_product_id, (int) $product_cats, 'product_cat', true);
                        }
                    }
                } else {

                    wp_set_object_terms($new_product_id, array(), 'product_cat');
                }


                // Set Product Custom Taxonomies
                if (isset($product_manager_form_data['product_custom_taxonomies']) && !empty($product_manager_form_data['product_custom_taxonomies'])) {
                    foreach ($product_manager_form_data['product_custom_taxonomies'] as $taxonomy => $taxonomy_values) {
                        if (!empty($taxonomy_values)) {
                            $is_first = true;
                            foreach ($taxonomy_values as $taxonomy_value) {
                                if ($is_first) {
                                    $is_first = false;
                                    wp_set_object_terms($new_product_id, (int) $taxonomy_value, $taxonomy);
                                } else {
                                    wp_set_object_terms($new_product_id, (int) $taxonomy_value, $taxonomy, true);
                                }
                            }
                        }
                    }
                }

                // Set Product Tags
                if (isset($product_manager_form_data['product_tags']) && !empty($product_manager_form_data['product_tags'])) {
                    if(is_array($product_manager_form_data['product_tags'])){
                        wp_set_object_terms( $new_product_id, $product_manager_form_data['product_tags'], 'product_tag', false );
                    }else{
                        wp_set_post_terms($new_product_id, $product_manager_form_data['product_tags'], 'product_tag');
                    }
                }

                // Set Product Featured Image
                $wp_upload_dir = wp_upload_dir();
                if (isset($product_manager_form_data['featured_img']) && !empty($product_manager_form_data['featured_img'])) {
                    $featured_img_id = get_attachment_id_by_url($product_manager_form_data['featured_img']);
                    set_post_thumbnail($new_product_id, $featured_img_id);
                } else {
                    delete_post_thumbnail($new_product_id);
                }

                // Set Product Image Gallery
                if (isset($product_manager_form_data['gallery_img']) && !empty($product_manager_form_data['gallery_img'])) {
                    $gallery = array();
                    foreach ($product_manager_form_data['gallery_img'] as $gallery_imgs) {
                        if (isset($gallery_imgs['image']) && !empty($gallery_imgs['image'])) {
                            $gallery_img_id = get_attachment_id_by_url($gallery_imgs['image']);
                            $gallery[] = $gallery_img_id;
                        }
                    }
                    if (!empty($gallery)) {
                        update_post_meta($new_product_id, '_product_image_gallery', implode(',', $gallery));
                    }
                }
                do_action('after_wcmp_fpm_data_meta_save', $new_product_id, $product_manager_form_data, $pro_attributes);
                // Set product basic options for simple and external products
                /* if (( $product_manager_form_data['product_type'] == 'variable' ) || ( $product_manager_form_data['product_type'] == 'variable-subscription' )) {
                  // Create Variable Product Variations
                  if (isset($product_manager_form_data['variations']) && !empty($product_manager_form_data['variations'])) {
                  foreach ($product_manager_form_data['variations'] as $variations) {
                  $variation_status = isset($variations['enable']) ? 'publish' : 'private';

                  $variation_id = absint($variations['id']);

                  // Generate a useful post title
                  $variation_post_title = sprintf(__('Variation #%s of %s', 'dc-woocommerce-multi-vendor'), absint($variation_id), esc_html(get_the_title($new_product_id)));

                  if (!$variation_id) { // Adding New Variation
                  $variation = array(
                  'post_title' => $variation_post_title,
                  'post_content' => '',
                  'post_status' => $variation_status,
                  'post_author' => $current_user_id,
                  'post_parent' => $new_product_id,
                  'post_type' => 'product_variation'
                  );

                  $variation_id = wp_insert_post($variation);
                  }

                  // Only continue if we have a variation ID
                  if (!$variation_id) {
                  continue;
                  }

                  // Set Variation Thumbnail
                  $variation_img_id = 0;
                  if (isset($variations['image']) && !empty($variations['image'])) {
                  $variation_img_id = $this->fpm_get_image_id($variations['image']);
                  }

                  // Variation Download options
                  $downloadables = array();
                  if (isset($variations['is_downloadable']) && isset($variations['downloadable_file']) && $variations['downloadable_file'] && !empty($variations['downloadable_file'])) {
                  $downloadables[] = array(
                  'name' => wc_clean($variations['downloadable_file_name']),
                  'file' => wp_unslash(trim($variations['downloadable_file'])),
                  'previous_hash' => md5($variations['downloadable_file']),
                  );
                  }

                  // Update Attributes
                  $var_attributes = array();
                  if ($pro_attributes) {
                  foreach ($pro_attributes as $p_attribute) {
                  if ($p_attribute->get_variation()) {
                  $attribute_key = sanitize_title($p_attribute->get_name());

                  $value = isset($variations["attribute_" . $attribute_key]) ? stripslashes($variations["attribute_" . $attribute_key]) : '';

                  $value = $p_attribute->is_taxonomy() ? sanitize_title($value) : wc_clean($value); // Don't use wc_clean as it destroys sanitized characters in terms.
                  $var_attributes[$attribute_key] = $value;
                  }
                  }
                  }

                  $wc_variation = new WC_Product_Variation($variation_id);
                  $errors = $wc_variation->set_props(apply_filters('fpm_product_variation_data_factory', array(
                  //'status'            => 'publish' //isset( $variations['enable'] ) ? 'publish' : 'private',
                  'menu_order' => wc_clean($variations['menu_order']),
                  'regular_price' => wc_clean($variations['regular_price']),
                  'sale_price' => wc_clean($variations['sale_price']),
                  'manage_stock' => isset($variations['manage_stock']),
                  'stock_quantity' => wc_clean($variations['stock_qty']),
                  'backorders' => wc_clean($variations['backorders']),
                  'stock_status' => wc_clean($variations['stock_status']),
                  'image_id' => wc_clean($variation_img_id),
                  'attributes' => $var_attributes,
                  'sku' => isset($variations['sku']) ? wc_clean($variations['sku']) : '',
                  'virtual' => isset($variations['is_virtual']),
                  'downloadable' => isset($variations['is_downloadable']),
                  'date_on_sale_from' => wc_clean($variations['sale_price_dates_from']),
                  'date_on_sale_to' => wc_clean($variations['sale_price_dates_to']),
                  'description' => wp_kses_post($variations['description']),
                  'download_limit' => wc_clean($variations['download_limit']),
                  'download_expiry' => wc_clean($variations['download_expiry']),
                  'downloads' => $downloadables,
                  'weight' => isset($variations['weight']) ? wc_clean($variations['weight']) : '',
                  'length' => isset($variations['length']) ? wc_clean($variations['length']) : '',
                  'width' => isset($variations['width']) ? wc_clean($variations['width']) : '',
                  'height' => isset($variations['height']) ? wc_clean($variations['height']) : '',
                  'shipping_class_id' => wc_clean($variations['shipping_class']),
                  'tax_class' => isset($variations['tax_class']) ? wc_clean($variations['tax_class']) : null,
                  ), $new_product_id, $variation_id, $variations, $product_manager_form_data));

                  if (is_wp_error($errors)) {
                  echo '{"status": false, "message": "' . $errors->get_error_message() . '", "id": "' . $new_product_id . '", "redirect": "' . get_permalink($new_product_id) . '"}';
                  $has_error = true;
                  }

                  $wc_variation->save();
                  }
                  }

                  // Remove Variations
                  if (isset($_POST['removed_variations']) && !empty($_POST['removed_variations'])) {
                  foreach ($_POST['removed_variations'] as $removed_variations) {
                  wp_delete_post($removed_variations, true);
                  }
                  }

                  $product->get_data_store()->sync_variation_names($product, wc_clean($product_manager_form_data['title']), wc_clean($product_manager_form_data['title']));
                  } */

                // Yoast SEO Support
                if (WC_Dependencies_Product_Vendor::fpm_yoast_plugin_active_check()) {
                    if (isset($product_manager_form_data['yoast_wpseo_focuskw_text_input'])) {
                        update_post_meta($new_product_id, '_yoast_wpseo_focuskw_text_input', $product_manager_form_data['yoast_wpseo_focuskw_text_input']);
                        update_post_meta($new_product_id, '_yoast_wpseo_focuskw', $product_manager_form_data['yoast_wpseo_focuskw_text_input']);
                    }
                    if (isset($product_manager_form_data['yoast_wpseo_metadesc'])) {
                        update_post_meta($new_product_id, '_yoast_wpseo_metadesc', strip_tags($product_manager_form_data['yoast_wpseo_metadesc']));
                    }
                }

                // WooCommerce Custom Product Tabs Lite Support
                if (WC_Dependencies_Product_Vendor::fpm_wc_tabs_lite_plugin_active_check()) {
                    if (isset($product_manager_form_data['product_tabs'])) {
                        $frs_woo_product_tabs = array();
                        if (!empty($product_manager_form_data['product_tabs'])) {
                            foreach ($product_manager_form_data['product_tabs'] as $frs_woo_product_tab) {
                                if ($frs_woo_product_tab['title']) {
                                    // convert the tab title into an id string
                                    $tab_id = strtolower(wc_clean($frs_woo_product_tab['title']));

                                    // remove non-alphas, numbers, underscores or whitespace
                                    $tab_id = preg_replace("/[^\w\s]/", '', $tab_id);

                                    // replace all underscores with single spaces
                                    $tab_id = preg_replace("/_+/", ' ', $tab_id);

                                    // replace all multiple spaces with single dashes
                                    $tab_id = preg_replace("/\s+/", '-', $tab_id);

                                    // prepend with 'tab-' string
                                    $tab_id = 'tab-' . $tab_id;

                                    $frs_woo_product_tabs[] = array(
                                        'title' => wc_clean($frs_woo_product_tab['title']),
                                        'id' => $tab_id,
                                        'content' => $frs_woo_product_tab['content']
                                    );
                                }
                            }
                            update_post_meta($new_product_id, 'frs_woo_product_tabs', $frs_woo_product_tabs);
                        } else {
                            delete_post_meta($new_product_id, 'frs_woo_product_tabs');
                        }
                    }
                }

                // WooCommerce Product Fees Support
                if (WC_Dependencies_Product_Vendor::fpm_wc_product_fees_plugin_active_check()) {
                    update_post_meta($new_product_id, 'product-fee-name', $product_manager_form_data['product-fee-name']);
                    update_post_meta($new_product_id, 'product-fee-amount', $product_manager_form_data['product-fee-amount']);
                    $product_fee_multiplier = ( $product_manager_form_data['product-fee-multiplier'] ) ? 'yes' : 'no';
                    update_post_meta($new_product_id, 'product-fee-multiplier', $product_fee_multiplier);
                }

                // WooCommerce Bulk Discount Support
                if (WC_Dependencies_Product_Vendor::fpm_wc_bulk_discount_plugin_active_check()) {
                    $_bulkdiscount_enabled = ( $product_manager_form_data['_bulkdiscount_enabled'] ) ? 'yes' : 'no';
                    update_post_meta($new_product_id, '_bulkdiscount_enabled', $_bulkdiscount_enabled);
                    update_post_meta($new_product_id, '_bulkdiscount_text_info', $product_manager_form_data['_bulkdiscount_text_info']);
                    update_post_meta($new_product_id, '_bulkdiscounts', $product_manager_form_data['_bulkdiscounts']);

                    $bulk_discount_rule_counter = 0;
                    foreach ($product_manager_form_data['_bulkdiscounts'] as $bulkdiscount) {
                        $bulk_discount_rule_counter++;
                        update_post_meta($new_product_id, '_bulkdiscount_quantity_' . $bulk_discount_rule_counter, $bulkdiscount['quantity']);
                        update_post_meta($new_product_id, '_bulkdiscount_discount_' . $bulk_discount_rule_counter, $bulkdiscount['discount']);
                    }

                    if ($bulk_discount_rule_counter < 5) {
                        for ($bdrc = ($bulk_discount_rule_counter + 1); $bdrc <= 5; $bdrc++) {
                            update_post_meta($new_product_id, '_bulkdiscount_quantity_' . $bdrc, '');
                            update_post_meta($new_product_id, '_bulkdiscount_discount_' . $bdrc, '');
                        }
                    }
                }

                if (WC_Dependencies_Product_Vendor::fpm_toolset_plugin_active_check()) {
                    if (isset($product_manager_form_data['wpcf']) && !empty($product_manager_form_data['wpcf'])) {
                        foreach ($product_manager_form_data['wpcf'] as $toolset_types_filed_key => $toolset_types_filed_value) {
                            update_post_meta($new_product_id, $toolset_types_filed_key, $toolset_types_filed_value);
                        }
                    }
                }

                do_action('after_wcmp_fpm_meta_save', $new_product_id, $product_manager_form_data);

                // Set Product Vendor Data
                if ($is_vendor && !$is_update) {
                    $vendor_term = get_user_meta($current_user_id, '_vendor_term_id', true);
                    $term = get_term($vendor_term, $WCMp->taxonomy->taxonomy_name);
                    wp_delete_object_term_relationships($new_product_id, $WCMp->taxonomy->taxonomy_name);
                    //wp_set_post_terms($new_product_id, $term->name, $WCMp->taxonomy->taxonomy_name, true);
                    wp_set_object_terms($new_product_id, (int) $term->term_id, $WCMp->taxonomy->taxonomy_name, true);
                }

                // Notify Admin on New Product Creation
                if ($is_publish) {
                    $WCMp->product->on_all_status_transitions($product_status, '', get_post($new_product_id));
                }

                if (!$has_error) {
                    if (get_post_status($new_product_id) == 'publish' || (current_user_can('publish_products') && $product_status == 'publish')) {
                        if (!$has_error) {
                            if ($is_new_pro == 0) {
                                $WCMp_fpm_messages['product_published'] = __('Product updated successfully!', 'dc-woocommerce-multi-vendor');
                            }
                            set_transient('wcmp_fpm_product_added_msg', $WCMp_fpm_messages['product_published']);
                            $redirect_url = wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_add_product_endpoint', 'vendor', 'general', 'add-product'), $new_product_id);
                            if (!current_user_can('edit_published_products')) {
                                $redirect_url = apply_filters('wcmp_vendor_products', wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_products_endpoint', 'vendor', 'general', 'products')));
                            }
                            echo '{"status": true, "is_new": "' . $new_product_id . '", "message": "' . $WCMp_fpm_messages['product_published'] . '", "redirect": "' . esc_url($redirect_url) . '"}';
                        }
                    } else {
                        if (!$has_error) {
                            set_transient('wcmp_fpm_product_added_msg', $WCMp_fpm_messages['product_saved']);
                            echo '{"status": true, "is_new": "' . $new_product_id . '", "message": "' . $WCMp_fpm_messages['product_saved'] . '", "redirect": "' . esc_url(wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_add_product_endpoint', 'vendor', 'general', 'add-product'), $new_product_id)) . '"}';
                        }
                    }
                }
                die;
            }
        } else {
            echo '{"status": false, "message": "' . $WCMp_fpm_messages['no_title'] . '"}';
        }
        do_action('after_wcmp_frontend_product_manager_save', $new_product_id, $product_manager_form_data);
        die;
    }

    public function delete_fpm_product() {

        $proid = $_POST['proid'];

        if ($proid) {
            if (wp_delete_post($proid)) {
                //echo 'success';
                echo '{"status": "success", "shop_url": "' . get_permalink(wc_get_page_id('shop')) . '"}';
                die;
            }
            die;
        }
    }

    function fpm_get_image_id($attachment_url) {
        global $wpdb;
        $upload_dir_paths = wp_upload_dir();

        if (class_exists('WPH')) {
            global $wph;
            $new_upload_path = $wph->functions->get_module_item_setting('new_upload_path');
            $attachment_url = str_replace($new_upload_path, 'wp-content/uploads', $attachment_url);
        }

        // If this is the URL of an auto-generated thumbnail, get the URL of the original image
        if (false !== strpos($attachment_url, $upload_dir_paths['baseurl'])) {
            $attachment_url = preg_replace('/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url);

            // Remove the upload path base directory from the attachment URL
            $attachment_url = str_replace($upload_dir_paths['baseurl'] . '/', '', $attachment_url);

            // Finally, run a custom database query to get the attachment ID from the modified attachment URL
            $attachment_id = $wpdb->get_var($wpdb->prepare("SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachment_url));
        }
        return $attachment_id;
    }

    // Frontend Coupon Manager
    public function frontend_coupon_manager() {

        $coupon_manager_form_data = array();
        parse_str($_POST['coupon_manager_form'], $coupon_manager_form_data);
        //print_r($coupon_manager_form_data);
        $WCMp_fpm_coupon_messages = get_frontend_coupon_manager_messages();
        $has_error = false;

        if (isset($coupon_manager_form_data['title']) && !empty($coupon_manager_form_data['title'])) {
            $is_update = false;
            $is_publish = false;
            $is_vendor = false;
            $current_user_id = $vendor_id = apply_filters('wcmp_current_loggedin_vendor_id', get_current_user_id());
            if (is_user_wcmp_vendor($current_user_id))
                $is_vendor = true;

            if (isset($_POST['status']) && ($_POST['status'] == 'draft')) {
                $coupon_status = 'draft';
            } else {
                if ($is_vendor) {
                    if (!current_user_can('publish_shop_coupons')) {
                        $coupon_status = 'pending';
                    } else {
                        $coupon_status = 'publish';
                    }
                } else {
                    $coupon_status = 'publish';
                }
            }

            // Creating new coupon
            $new_coupon = array(
                'post_title' => wc_clean($coupon_manager_form_data['title']),
                'post_status' => $coupon_status,
                'post_type' => 'shop_coupon',
                'post_excerpt' => $coupon_manager_form_data['description'],
                'post_author' => $vendor_id
                    //'post_name' => sanitize_title($coupon_manager_form_data['title'])
            );

            if (isset($coupon_manager_form_data['coupon_id']) && $coupon_manager_form_data['coupon_id'] == 0) {
                if ($coupon_status != 'draft') {
                    $is_publish = true;
                }
                $new_coupon_id = wp_insert_post($new_coupon, true);
            } else { // For Update
                $is_update = true;
                $new_coupon['ID'] = $coupon_manager_form_data['coupon_id'];
                if (!$is_vendor)
                    unset($new_coupon['post_author']);
                if (get_post_status($new_coupon['ID']) != 'draft') {
                    unset($new_coupon['post_status']);
                } else if ((get_post_status($new_coupon['ID']) == 'draft') && ($coupon_status != 'draft')) {
                    $is_publish = true;
                }
                $new_coupon_id = wp_update_post($new_coupon, true);
            }

            if (!is_wp_error($new_coupon_id)) {
                // For Update
                if ($is_update)
                    $new_coupon_id = $coupon_manager_form_data['coupon_id'];
                $coupon_obj = new WC_Coupon($new_coupon_id);
                // Coupon General
                update_post_meta($new_coupon_id, 'discount_type', $coupon_manager_form_data['discount_type']);
                update_post_meta($new_coupon_id, 'coupon_amount', $coupon_manager_form_data['coupon_amount'] ? $coupon_manager_form_data['coupon_amount'] : '' );
                update_post_meta($new_coupon_id, 'free_shipping', isset($coupon_manager_form_data['free_shipping']) ? 'yes' : 'no' );
                //update_post_meta($new_coupon_id, 'expiry_date', $coupon_manager_form_data['expiry_date'] ? $coupon_manager_form_data['expiry_date'] : '' );
                $coupon_obj->set_date_expires($coupon_manager_form_data['expiry_date']);

                // Usage Restrictin
                update_post_meta($new_coupon_id, 'minimum_amount', $coupon_manager_form_data['minimum_amount'] ? $coupon_manager_form_data['minimum_amount'] : '' );
                update_post_meta($new_coupon_id, 'maximum_amount', $coupon_manager_form_data['maximum_amount'] ? $coupon_manager_form_data['maximum_amount'] : '' );
                update_post_meta($new_coupon_id, 'individual_use', isset($coupon_manager_form_data['individual_use']) ? 'yes' : 'no' );
                update_post_meta($new_coupon_id, 'exclude_sale_items', isset($coupon_manager_form_data['exclude_sale_items']) ? 'yes' : 'no' );
                update_post_meta($new_coupon_id, 'product_ids', $coupon_manager_form_data['product_ids'] ? implode(',', $coupon_manager_form_data['product_ids']) : '' );
                update_post_meta($new_coupon_id, 'exclude_product_ids', $coupon_manager_form_data['exclude_product_ids'] ? implode(',', $coupon_manager_form_data['exclude_product_ids']) : '' );
                update_post_meta($new_coupon_id, 'product_categories', isset($coupon_manager_form_data['product_categories']) ? array_map('intval', $coupon_manager_form_data['product_categories']) : array() );
                update_post_meta($new_coupon_id, 'exclude_product_categories', isset($coupon_manager_form_data['exclude_product_categories']) ? array_map('intval', $coupon_manager_form_data['exclude_product_categories']) : array() );
                update_post_meta($new_coupon_id, 'customer_email', $coupon_manager_form_data['customer_email'] ? array_filter(array_map('trim', explode(',', wc_clean($coupon_manager_form_data['customer_email'])))) : array() );

                // Usage Limits
                update_post_meta($new_coupon_id, 'usage_limit', $coupon_manager_form_data['usage_limit'] ? $coupon_manager_form_data['usage_limit'] : '' );
                update_post_meta($new_coupon_id, 'usage_limit_per_user', $coupon_manager_form_data['usage_limit_per_user'] ? $coupon_manager_form_data['usage_limit_per_user'] : '' );

                // limit_usage_to_x_items
                update_post_meta($new_coupon_id, 'limit_usage_to_x_items', $coupon_manager_form_data['limit_usage_to_x_items'] ? $coupon_manager_form_data['limit_usage_to_x_items'] : '' );

                $coupon_obj->save();
                if (!$has_error) {
                    if (get_post_status($new_coupon_id) == 'publish') {
                        if (!$has_error)
                            echo '{"status": true, "message": "' . $WCMp_fpm_coupon_messages['coupon_published'] . '", "redirect": "' . wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_coupons_endpoint', 'vendor', 'general', 'coupons')) . '"}';
                    } else {
                        if (!$has_error)
                            echo '{"status": true, "message": "' . $WCMp_fpm_coupon_messages['coupon_saved'] . '", "redirect": "' . add_query_arg('fpm_msg', 'coupon_saved', wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_add_coupon_endpoint', 'vendor', 'general', 'add-coupon'), $new_coupon_id)) . '"}';
                    }
                }
                die;
            }
        } else {
            echo '{"status": false, "message": "' . $WCMp_fpm_coupon_messages['no_title'] . '"}';
        }
    }

    public function wcmp_vendor_product_list() {
        if (is_user_logged_in() && is_user_wcmp_vendor(get_current_user_id())) {
            $vendor = get_current_vendor();
            $enable_ordering = apply_filters('wcmp_vendor_dashboard_product_list_table_orderable_columns', array('name', 'date'));
            $products_table_headers = array(
                'image' => '',
                'name' => __('Product', 'dc-woocommerce-multi-vendor'),
                'price' => __('Price', 'dc-woocommerce-multi-vendor'),
                'stock' => __('Stock', 'dc-woocommerce-multi-vendor'),
                'categories' => __('Categories', 'dc-woocommerce-multi-vendor'),
                'date' => __('Date', 'dc-woocommerce-multi-vendor'),
                'status' => __('Status', 'dc-woocommerce-multi-vendor'),
            );
            $products_table_headers = apply_filters('wcmp_vendor_dashboard_product_list_table_headers', $products_table_headers);
            // storing columns keys for ordering
            $columns = array();
            foreach ($products_table_headers as $key => $value) {
                $columns[] = $key;
            }

            $requestData = $_REQUEST;
            $df_post_status = array('publish', 'pending', 'draft');
            if (isset($requestData['post_status']) && $requestData['post_status'] != 'all') {
                $df_post_status = $requestData['post_status'];
            }
            $args = array(
                'posts_per_page' => -1,
                'offset' => 0,
                'category' => '',
                'category_name' => '',
                'orderby' => 'date',
                'order' => 'DESC',
                'include' => '',
                'exclude' => '',
                'meta_key' => '',
                'meta_value' => '',
                'post_type' => 'product',
                'post_mime_type' => '',
                'post_parent' => '',
                'author' => get_current_vendor_id(),
                'post_status' => $df_post_status,
                'suppress_filters' => true
            );

            if (isset($requestData['product_cat']) && $requestData['product_cat'] != '') {
                $args['tax_query'] = array(array('taxonomy' => 'product_cat', 'field' => 'term_id', 'terms' => $requestData['product_cat']));
            }

            $total_products_array = $vendor->get_products($args);
            // filter/ordering data
            if (!empty($requestData['search']['value'])) {
                $args['s'] = $requestData['search']['value'];
            }
            if (isset($columns[$requestData['order'][0]['column']]) && in_array($columns[$requestData['order'][0]['column']], $enable_ordering)) {
                $args['orderby'] = $columns[$requestData['order'][0]['column']];
                $args['order'] = $requestData['order'][0]['dir'];
            }
            if (isset($requestData['post_status']) && $requestData['post_status'] != 'all') {
                $args['post_status'] = $requestData['post_status'];
            }
            $args['offset'] = $requestData['start'];
            $args['posts_per_page'] = $requestData['length'];

            $data = array();
            $products_array = $vendor->get_products($args);
            if (!empty($products_array)) {
                foreach ($products_array as $product_single) {
                    $row = array();
                    $product = wc_get_product($product_single->ID);
                    $edit_product_link = '';
                    if (current_user_can('edit_published_products') && get_wcmp_vendor_settings('is_edit_delete_published_product', 'capabilities', 'product') == 'Enable') {
                        $edit_product_link = esc_url(wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_add_product_endpoint', 'vendor', 'general', 'add-product'), $product->get_id()));
                    }
                    // Get actions
                    $onclick = "return confirm('" . __('Are you sure want to delete this product?', 'dc-woocommerce-multi-vendor') . "')";
                    $view_title = __('View', 'dc-woocommerce-multi-vendor');
                    if (in_array($product->get_status(), array('draft', 'pending'))) {
                        $view_title = __('Preview', 'dc-woocommerce-multi-vendor');
                    }
                    $actions = array(
                        'id' => sprintf(__('ID: %d', 'dc-woocommerce-multi-vendor'), $product->get_id()),
                        'edit' => '<a href="' . esc_url($edit_product_link) . '">' . __('Edit', 'dc-woocommerce-multi-vendor') . '</a>',
                        'delete' => '<a class="productDelete" href="' . esc_url(wp_nonce_url(add_query_arg(array('product_id' => $product->get_id()), wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_products_endpoint', 'vendor', 'general', 'products'))), 'wcmp_delete_product')) . '" onclick="' . $onclick . '">' . __('Delete Permanently', 'dc-woocommerce-multi-vendor') . '</a>',
                        'view' => '<a href="' . esc_url($product->get_permalink()) . '" target="_blank">' . $view_title . '</a>',
                    );
                    if (!current_user_can('edit_published_products') && get_wcmp_vendor_settings('is_edit_delete_published_product', 'capabilities', 'product') != 'Enable') {
                        unset($actions['edit']);
                        unset($actions['delete']);
                    }
                    $actions = apply_filters('wcmp_vendor_product_list_row_actions', $actions, $product);
                    $row_actions = array();
                    foreach ($actions as $action => $link) {
                        $row_actions[] = '<span class="' . esc_attr($action) . '">' . $link . '</span>';
                    }
                    $action_html = '<div class="row-actions">' . implode(' <span class="divider">|</span> ', $row_actions) . '</div>';
                    // is in stock
                    if ($product->is_in_stock()) {
                        $stock_html = '<span class="label label-success instock">' . __('In stock', 'dc-woocommerce-multi-vendor');
                        if ($product->managing_stock()) {
                            $stock_html .= ' (' . wc_stock_amount($product->get_stock_quantity()) . ')';
                        }
                        $stock_html .= '</span>';
                    } else {
                        $stock_html = '<span class="label label-danger outofstock">' . __('Out of stock', 'dc-woocommerce-multi-vendor') . '</span>';
                    }
                    // product cat
                    $product_cats = '';
                    $termlist = array();
                    if (!$terms = get_the_terms($product->get_id(), 'product_cat')) {
                        $product_cats = '<span class="na">&ndash;</span>';
                    } else {
                        foreach ($terms as $term) {
                            $termlist[] = $term->name;
                        }
                    }
                    if ($termlist) {
                        $product_cats = implode(' | ', $termlist);
                    }
                    $date = '&ndash;';
                    if ($product->get_status() == 'publish') {
                        $status = __('Published', 'dc-woocommerce-multi-vendor');
                        $date = wcmp_date($product->get_date_created('edit'));
                    } elseif ($product->get_status() == 'pending') {
                        $status = __('Pending', 'dc-woocommerce-multi-vendor');
                    } elseif ($product->get_status() == 'draft') {
                        $status = __('Draft', 'dc-woocommerce-multi-vendor');
                    } elseif ($product->get_status() == 'private') {
                        $status = __('Private', 'dc-woocommerce-multi-vendor');
                    } else {
                        $status = ucfirst($product->get_status());
                    }

                    $row ['image'] = '<td>' . $product->get_image(apply_filters('wcmp_vendor_product_list_image_size', array(40, 40))) . '</td>';
                    $row ['name'] = '<td><a href="' . esc_url($edit_product_link) . '">' . $product->get_title() . '</a>' . $action_html . '</td>';
                    $row ['price'] = '<td>' . $product->get_price_html() . '</td>';
                    $row ['stock'] = '<td>' . $stock_html . '</td>';
                    $row ['categories'] = '<td>' . $product_cats . '</td>';
                    $row ['date'] = '<td>' . $date . '</td>';
                    $row ['status'] = '<td>' . $status . '</td>';
                    $data[] = apply_filters('wcmp_vendor_dashboard_product_list_table_row_data', $row, $product);
                }
            }

            $json_data = array(
                "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
                "recordsTotal" => intval(count($total_products_array)), // total number of records
                "recordsFiltered" => intval(count($total_products_array)), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data" => $data   // total data array
            );
            wp_send_json($json_data);
            die;
        }
    }

    public function wcmp_vendor_unpaid_order_vendor_withdrawal_list() {
        global $WCMp;
        if (is_user_logged_in() && is_user_wcmp_vendor(get_current_vendor_id())) {
            $vendor = get_wcmp_vendor(get_current_vendor_id());
            $requestData = $_REQUEST;
            $meta_query['meta_query'] = array(
                array(
                    'key' => '_paid_status',
                    'value' => 'unpaid',
                    'compare' => '='
                ),
                array(
                    'key' => '_commission_vendor',
                    'value' => absint($vendor->term_id),
                    'compare' => '='
                )
            );
            $vendor_unpaid_total_orders = $vendor->get_orders(false, false, $meta_query);
            if (isset($requestData['start']) && isset($requestData['length'])) {
                $vendor_unpaid_orders = $vendor->get_orders($requestData['length'], $requestData['start'], $meta_query);
            }
            $data = array();
            $commission_threshold_time = isset($WCMp->vendor_caps->payment_cap['commission_threshold_time']) && !empty($WCMp->vendor_caps->payment_cap['commission_threshold_time']) ? $WCMp->vendor_caps->payment_cap['commission_threshold_time'] : 0;
            if ($vendor_unpaid_orders) {
                foreach ($vendor_unpaid_orders as $commission_id => $order_id) {
                    $order = wc_get_order($order_id);
                    $vendor_share = get_wcmp_vendor_order_amount(array('vendor_id' => $vendor->id, 'order_id' => $order->get_id()));
                    if (!isset($vendor_share['total'])) {
                        $vendor_share['total'] = 0;
                    }
                    $commission_create_date = get_the_date('U', $commission_id);
                    $current_date = date('U');
                    $diff = intval(($current_date - $commission_create_date) / (3600 * 24));
                    if ($diff < $commission_threshold_time) {
                        continue;
                    }

                    if (is_commission_requested_for_withdrawals($commission_id)) {
                        $disabled_reqested_withdrawals = 'disabled';
                    } else {
                        $disabled_reqested_withdrawals = '';
                    }
                    $row = array();
                    $row ['select_withdrawal'] = '<input name="commissions[]" value="' . $commission_id . '" class="select_withdrawal" type="checkbox" ' . $disabled_reqested_withdrawals . '>';
                    $row ['order_id'] = $order->get_id();
                    $row ['commission_amount'] = wc_price($vendor_share['commission_amount']);
                    $row ['shipping_amount'] = wc_price($vendor_share['shipping_amount']);
                    $row ['tax_amount'] = wc_price($vendor_share['tax_amount']);
                    $row ['total'] = wc_price($vendor_share['total']);
                    $data[] = apply_filters('wcmp_vendor_withdrawal_list_row_data', $row, $commission_id);
                }
            }

            $json_data = array(
                "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
                "recordsTotal" => intval(count($vendor_unpaid_total_orders)), // total number of records
                "recordsFiltered" => intval(count($vendor_unpaid_total_orders)), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data" => $data   // total data array
            );
            wp_send_json($json_data);
            die;
        }
    }

    public function wcmp_vendor_coupon_list() {
        if (is_user_logged_in() && is_user_wcmp_vendor(get_current_vendor_id())) {
            $vendor = get_wcmp_vendor(get_current_vendor_id());
            $requestData = $_REQUEST;
            $args = array(
                'posts_per_page' => -1,
                'offset' => 0,
                'category' => '',
                'category_name' => '',
                'orderby' => 'date',
                'order' => 'DESC',
                'include' => '',
                'exclude' => '',
                'meta_key' => '',
                'meta_value' => '',
                'post_type' => 'shop_coupon',
                'post_mime_type' => '',
                'post_parent' => '',
                'author' => get_current_vendor_id(),
                'post_status' => array('publish', 'pending', 'draft'),
                'suppress_filters' => true
            );
            $vendor_total_coupons = get_posts($args);
            $args['offset'] = $requestData['start'];
            $args['posts_per_page'] = $requestData['length'];
            $vendor_coupons = get_posts($args);
            $data = array();
            if ($vendor_coupons) {
                foreach ($vendor_coupons as $coupon_single) {
                    $edit_coupon_link = '';
                    if (current_user_can('edit_published_shop_coupons') && get_wcmp_vendor_settings('is_edit_delete_published_coupon', 'capabilities', 'product') == 'Enable') {
                        $edit_coupon_link = esc_url(wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_add_coupon_endpoint', 'vendor', 'general', 'add-coupon'), $coupon_single->ID));
                    }
                    // Get actions
                    $onclick = "return confirm('" . __('Are you sure want to delete this coupon?', 'dc-woocommerce-multi-vendor') . "')";
                    $actions = array(
                        'id' => sprintf(__('ID: %d', 'dc-woocommerce-multi-vendor'), $coupon_single->ID),
                        'edit' => '<a href="' . esc_url($edit_coupon_link) . '">' . __('Edit', 'dc-woocommerce-multi-vendor') . '</a>',
                        'delete' => '<a class="couponDelete" href="' . esc_url(wp_nonce_url(add_query_arg(array('coupon_id' => $coupon_single->ID), wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_coupons_endpoint', 'vendor', 'general', 'coupons'))), 'wcmp_delete_coupon')) . '" onclick="' . $onclick . '">' . __('Delete Permanently', 'dc-woocommerce-multi-vendor') . '</a>',
                    );
                    if (!current_user_can('edit_published_shop_coupons') || get_wcmp_vendor_settings('is_edit_delete_published_coupon', 'capabilities', 'product') != 'Enable') {
                        unset($actions['edit']);
                        unset($actions['delete']);
                    }
                    $actions = apply_filters('wcmp_vendor_coupon_list_row_actions', $actions, $coupon_single);
                    $row_actions = array();
                    foreach ($actions as $action => $link) {
                        $row_actions[] = '<span class="' . esc_attr($action) . '">' . $link . '</span>';
                    }
                    $action_html = '<div class="row-actions">' . implode(' | ', $row_actions) . '</div>';
                    $coupon = new WC_Coupon($coupon_single->ID);
                    $usage_count = $coupon->get_usage_count();
                    $usage_limit = $coupon->get_usage_limit();
                    $usage_limit = $usage_limit ? $usage_limit : '&infin;';
                    
                    if ( $coupon->get_date_expires() ) {
                        $expiry_date = wcmp_date($coupon->get_date_expires());
                    } else {
                        $expiry_date = '&ndash;';
                    }

                    $row = array();
                    $row ['coupons'] = '<a href="' . esc_url($edit_coupon_link) . '">' . get_the_title($coupon_single->ID) . '</a>' . $action_html;
                    $row ['amount'] = $coupon->get_amount();
                    $row ['uses_limit'] = $usage_count . ' / ' . $usage_limit;
                    $row ['expiry_date'] = $expiry_date;
                    $data[] = $row;
                }
            }

            $json_data = array(
                "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
                "recordsTotal" => intval(count($vendor_total_coupons)), // total number of records
                "recordsFiltered" => intval(count($vendor_total_coupons)), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data" => $data   // total data array
            );
            wp_send_json($json_data);
            die;
        }
    }

    public function wcmp_vendor_transactions_list() {
        global $WCMp;
        if (is_user_logged_in() && is_user_wcmp_vendor(get_current_vendor_id())) {
            $vendor = get_wcmp_vendor(get_current_vendor_id());
            $requestData = $_REQUEST;
            $vendor = apply_filters('wcmp_transaction_vendor', $vendor);
            $start_date = isset($requestData['from_date']) ? $requestData['from_date'] : date('Y-m-01');
            $end_date = isset($requestData['to_date']) ? $requestData['to_date'] : date('Y-m-t');
            $transaction_details = $WCMp->transaction->get_transactions($vendor->term_id, $start_date, $end_date, array('wcmp_processing', 'wcmp_completed'));

            $data = array();
            if (!empty($transaction_details)) {
                foreach ($transaction_details as $transaction_id => $detail) {
                    $trans_post = get_post($transaction_id);
                    $order_ids = $commssion_ids = '';
                    $commission_details = get_post_meta($transaction_id, 'commission_detail', true);
                    $transfer_charge = get_post_meta($transaction_id, 'transfer_charge', true);
                    $transaction_amt = get_post_meta($transaction_id, 'amount', true) - get_post_meta($transaction_id, 'transfer_charge', true) - get_post_meta($transaction_id, 'gateway_charge', true);
                    $row = array();
                    $row ['select_transaction'] = '<input name="transaction_ids[]" value="' . $transaction_id . '"  class="select_transaction" type="checkbox" >';
                    $row ['date'] = wcmp_date($trans_post->post_date);
                    $row ['transaction_id'] = '<a href="' . esc_url(wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_transaction_details_endpoint', 'vendor', 'general', 'transaction-details'), $transaction_id)) . '">#' . $transaction_id . '</a>';
                    $row ['commission_ids'] = '#' . implode(', #', $commission_details);
                    $row ['fees'] = isset($transfer_charge) ? wc_price($transfer_charge) : wc_price(0);
                    $row ['net_earning'] = wc_price($transaction_amt);
                    $data[] = apply_filters('wcmp_vendor_transaction_list_row_data', $row, $transaction_id);
                }
            }
            $json_data = array(
                "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
                "recordsTotal" => intval(count($transaction_details)), // total number of records
                "recordsFiltered" => intval(count($transaction_details)), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data" => $data   // total data array
            );
            wp_send_json($json_data);
            die;
        }
    }

    /**
     * Customer Questions and Answers data handler
     */
    public function wcmp_customer_ask_qna_handler() {
        global $WCMp, $wpdb;
        $handler = isset($_POST['handler']) ? $_POST['handler'] : '';
        $msg = '';
        $no_data = '';
        $qna_data = '';
        $remain_data = '';
        $redirect = '';

        if ($handler == 'submit') {
            $qna_form_data = array();
            parse_str($_POST['customer_qna_data'], $qna_form_data);
            $wpnonce = isset($qna_form_data['cust_qna_nonce']) ? $qna_form_data['cust_qna_nonce'] : '';
            $product_id = isset($qna_form_data['product_ID']) ? (int) $qna_form_data['product_ID'] : 0;
            $cust_id = isset($qna_form_data['cust_ID']) ? (int) $qna_form_data['cust_ID'] : 0;
            $cust_question = isset($qna_form_data['cust_question']) ? sanitize_text_field($qna_form_data['cust_question']) : '';
            $vendor = get_wcmp_product_vendors($product_id);
            $redirect = get_permalink($product_id);
            $customer = wp_get_current_user();
            $cust_qna = array();
            if ($wpnonce && wp_verify_nonce($wpnonce, 'wcmp_customer_qna_form_submit') && $product_id && $cust_question) {
                $result = $WCMp->product_qna->createQuestion(array(
                    'product_ID' => $product_id,
                    'ques_details' => sanitize_text_field($cust_question),
                    'ques_by' => $cust_id,
                    'ques_created' => date('Y-m-d H:i:s', current_time('timestamp')),
                    'ques_vote' => ''
                ));
                if ($result) {
                    //delete transient
                    if (get_transient('wcmp_customer_qna_for_vendor_' . $vendor->id)) {
                        delete_transient('wcmp_customer_qna_for_vendor_' . $vendor->id);
                    }
                    $no_data = 0;
                    $msg = __("Your question submitted successfully!", 'dc-woocommerce-multi-vendor');
                    wc_add_notice($msg, 'success');
                }
            }
        } elseif ($handler == 'search') {
            $keyword = isset($_POST['keyword']) ? $_POST['keyword'] : '';
            $product_id = isset($_POST['product_ID']) ? $_POST['product_ID'] : 0;
            $product = wc_get_product($product_id);
            if ($product) {
                //$vendor = get_wcmp_product_vendors( $product->get_id() );
                $qnas_data = $WCMp->product_qna->get_Product_QNA($product->get_id(), array('sortby' => 'vote'));
                if ($keyword) {
                    $qnas_data = array_filter($qnas_data, function($data) use ($keyword) {
                        return ( strpos(strtolower($data->ques_details), $keyword) !== false );
                    });
                }
                if ($qnas_data) {
                    foreach ($qnas_data as $qna) {
                        $vendor = get_wcmp_vendor($qna->ans_by);
                        if ($vendor) {
                            $vendor_term = get_term($vendor->term_id);
                            $ans_by = $vendor_term->name;
                        } else {
                            $ans_by = get_userdata($qna->ans_by)->display_name;
                        }
                        $qna_data .= '<div class="qna-item-wrap item-' . $qna->ques_ID . '">
                        <div class="qna-block">
                            <div class="qna-vote">';
                        $count = 0;
                        $ans_vote = maybe_unserialize($qna->ans_vote);
                        if (is_array($ans_vote)) {
                            $count = array_sum($ans_vote);
                        }
                        $qna_data .= '<div class="vote">';
                        if (is_user_logged_in()) {
                            if ($ans_vote && array_key_exists(get_current_user_id(), $ans_vote)) {
                                if ($ans_vote[get_current_user_id()] > 0) {
                                    $qna_data .= '<a href="javascript:void(0)" title="' . __('You already gave a thumbs up.', 'dc-woocommerce-multi-vendor') . '" class="give-up-vote" data-vote="up" data-ans="' . $qna->ans_ID . '"><i class="vote-sprite vote-sprite-like"></i></a>
                                    <span class="vote-count">' . $count . '</span>
                                    <a href="" title="' . __('Give a thumbs down', 'dc-woocommerce-multi-vendor') . '" class="give-vote-btn give-down-vote" data-vote="down" data-ans="' . $qna->ans_ID . '"><i class="vote-sprite vote-sprite-dislike"></i></a>';
                                } else {
                                    $qna_data .= '<a href="" title="' . __('Give a thumbs up', 'dc-woocommerce-multi-vendor') . '" class="give-vote-btn give-up-vote" data-vote="up" data-ans="' . $qna->ans_ID . '"><i class="vote-sprite vote-sprite-like"></i></a>
                                    <span class="vote-count">' . $count . '</span>
                                    <a href="javascript:void(0)" title="' . __('You already gave a thumbs down.', 'dc-woocommerce-multi-vendor') . '" class="give-vote-btn give-down-vote" data-vote="down" data-ans="' . $qna->ans_ID . '"><i class="vote-sprite vote-sprite-dislike"></i></a>';
                                }
                            } else {
                                $qna_data .= '<a href="" title="' . __('Give a thumbs up', 'dc-woocommerce-multi-vendor') . '" class="give-vote-btn give-up-vote" data-vote="up" data-ans="' . $qna->ans_ID . '"><i class="vote-sprite vote-sprite-like"></i></a>
                                    <span class="vote-count">' . $count . '</span>
                                    <a href="" title="' . __('Give a thumbs down', 'dc-woocommerce-multi-vendor') . '" class="give-vote-btn give-down-vote" data-vote="down" data-ans="' . $qna->ans_ID . '"><i class="vote-sprite vote-sprite-dislike"></i></a>';
                            }
                        } else {
                            $qna_data .= '<a href="javascript:void(0)" class="non_loggedin"><i class="vote-sprite vote-sprite-like"></i></a><span class="vote-count">' . $count . '</span><a href="javascript:void(0)" class="non_loggedin"><i class="vote-sprite vote-sprite-dislike"></i></a>';
                        }
                        $qna_data .= '</div></div>'
                                . '<div class="qtn-content">'
                                . '<div class="qtn-row">'
                                . '<p class="qna-question">'
                                . '<span>' . __('Q: ', 'dc-woocommerce-multi-vendor') . ' </span>' . $qna->ques_details . '</p>'
                                . '</div>'
                                . '<div class="qtn-row">'
                                . '<p class="qna-answer">'
                                . '<span>' . __('A: ', 'dc-woocommerce-multi-vendor') . ' </span>' . $qna->ans_details . '</p>'
                                . '</div>'
                                . '<div class="bottom-qna">'
                                . '<ul class="qna-info">';

                        $qna_data .= '<li class="qna-user">' . $ans_by . '</li>'
                                . '<li class="qna-date">' . date_i18n(wc_date_format(), strtotime($qna->ans_created)) . '</li>'
                                . '</ul>'
                                . '</div>'
                                . '</div></div></div>';
                    }
                    if (count($qnas_data) > 4) {
                        $qna_data .= '<div class="qna-item-wrap load-more-qna"><a href="" class="load-more-btn button" style="width:100%;text-align:center;">' . __('Load More', 'dc-woocommerce-multi-vendor') . '</a></div>';
                    }
                }
            }
            if (empty($qna_data)) {
                if (!is_user_logged_in()) {
                    $msg = __("You are not logged in.", 'dc-woocommerce-multi-vendor');
                }
                $no_data = 1;
            }
        } elseif ($handler == 'answer') {
            $ques_ID = isset($_POST['key']) ? $_POST['key'] : '';
            $reply = isset($_POST['reply']) ? sanitize_textarea_field($_POST['reply']) : '';
            $vendor = get_wcmp_vendor(get_current_user_id());
            if ($vendor && $reply && $ques_ID) {
                $_is_answer_given = $WCMp->product_qna->get_Answers($ques_ID);
                if (isset($_is_answer_given[0]) && count($_is_answer_given[0]) > 0) {
                    $result = $WCMp->product_qna->updateAnswer($_is_answer_given[0]->ans_ID, array('ans_details' => sanitize_textarea_field($reply)));
                } else {
                    $result = $WCMp->product_qna->createAnswer(array(
                        'ques_ID' => $ques_ID,
                        'ans_details' => sanitize_textarea_field($reply),
                        'ans_by' => $vendor->id,
                        'ans_created' => date('Y-m-d H:i:s', current_time('timestamp')),
                        'ans_vote' => ''
                    ));
                }
                if ($result) {
                    //delete transient
                    if (get_transient('wcmp_customer_qna_for_vendor_' . $vendor->id)) {
                        delete_transient('wcmp_customer_qna_for_vendor_' . $vendor->id);
                    }
                    $remain_data = count($WCMp->product_qna->get_Vendor_Questions($vendor));
                    if ($remain_data == 0) {
                        $msg = __('No more customer query found.', 'dc-woocommerce-multi-vendor');
                    } else {
                        $msg = '';
                    }
                    $qna_data = '';
                    $no_data = 0;
                } else {
                    $no_data = 1;
                }
            }
        } elseif ($handler == 'vote_answer') {
            $ans_ID = isset($_POST['ans_ID']) ? (int) $_POST['ans_ID'] : '';
            $vote_type = isset($_POST['vote']) ? $_POST['vote'] : '';
            $ans_row = $WCMp->product_qna->get_Answer($ans_ID);
            $ques_row = $WCMp->product_qna->get_Question($ans_row->ques_ID);
            $vote = maybe_unserialize($ans_row->ans_vote);
            $redirect = get_permalink($ques_row->product_ID);
            if (!$vote) {
                $vote = array();
            }
            if ($ans_ID && $vote_type && is_user_logged_in()) {
                if ($vote_type == 'up') {
                    $vote[get_current_user_id()] = +1;
                } else {
                    $vote[get_current_user_id()] = -1;
                }
                $result = $WCMp->product_qna->updateAnswer($ans_ID, array('ans_vote' => maybe_serialize($vote)));
                if ($result) {
                    $qna_data = '';
                    $msg = __("Thanks for your vote!", 'dc-woocommerce-multi-vendor');
                    $no_data = 0;
                    wc_add_notice($msg, 'success');
                } else {
                    $no_data = 1;
                }
            }
        } elseif ($handler == 'update_answer') {
            $result = false;
            $ans_ID = isset($_POST['key']) ? (int) $_POST['key'] : '';
            $answer = isset($_POST['answer']) ? $_POST['answer'] : '';
            if ($ans_ID) {
                $result = $WCMp->product_qna->updateAnswer($ans_ID, array('ans_details' => sanitize_textarea_field($answer)));
            }
            if ($result) {
                $qna_data = '';
                $msg = __("Answer updated successfully!", 'dc-woocommerce-multi-vendor');
                $no_data = 0;
                wc_add_notice($msg, 'success');
            } else {
                $no_data = 1;
            }
        }
        wp_send_json(array('no_data' => $no_data, 'message' => $msg, 'data' => $qna_data, 'remain_data' => $remain_data, 'redirect' => $redirect, 'is_user' => is_user_logged_in()));
        die();
    }

    public function wcmp_vendor_dashboard_reviews_data() {
        $vendor = get_current_vendor();
        $requestData = $_REQUEST;
        $data = array();
        $vendor_reviews_total = array();
        if (get_transient('wcmp_dashboard_reviews_for_vendor_' . $vendor->id)) {
            $vendor_reviews_total = get_transient('wcmp_dashboard_reviews_for_vendor_' . $vendor->id);
        } else {
            $query = array('meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'vendor_rating_id',
                        'value' => $vendor->id,
                        'compare' => '=',
                    ),
                    array(
                        'key' => '_mark_as_replied',
                        'value' => 1,
                        'compare' => 'NOT EXISTS',
                    )
            ));
            $vendor_reviews_total = $vendor->get_reviews_and_rating(0, '', $query);
            set_transient('wcmp_dashboard_reviews_for_vendor_' . $vendor->id, $vendor_reviews_total);
        }
        //$vendor_reviews_total = $vendor->get_reviews_and_rating(0, -1, $query);
        //$vendor_reviews = $vendor->get_reviews_and_rating($requestData['start'], $requestData['length'], $query);
        if ($vendor_reviews_total) {
            $vendor_reviews = array_slice($vendor_reviews_total, $requestData['start'], $requestData['length']);
            foreach ($vendor_reviews as $comment) :
                $vendor = get_wcmp_vendor($comment->user_id);
                if ($vendor) {
                    $vendor_term = get_term($vendor->term_id);
                    $comment_by = $vendor_term->name;
                } else {
                    $comment_by = get_userdata($comment->user_id)->display_name;
                }
                $row = '';
                $row = '<div class="media-left pull-left">   
                        <a href="#">' . get_avatar($comment->user_id, 50, '', '') . '</a>
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">' . $comment_by . ' -- <small>' . human_time_diff(strtotime($comment->comment_date)) . __(' ago', 'dc-woocommerce-multi-vendor') . '</small></h4>
                        <p>' . wp_trim_words($comment->comment_content, 250, '...') . '</p>
                        <a data-toggle="modal" data-target="#commient-modal-' . $comment->comment_ID . '">' . __('Reply', 'dc-woocommerce-multi-vendor') . '</a>
                        <!-- Modal -->
                        <div class="modal fade" id="commient-modal-' . $comment->comment_ID . '" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">' . __('Reply to ', 'dc-woocommerce-multi-vendor') . $comment_by . '</h4>
                                    </div>
                                    <div class="wcmp-widget-modal modal-body">
                                            <textarea class="form-control" rows="5" id="comment-content-' . $comment->comment_ID . '" placeholder="' . __('Enter reply...', 'dc-woocommerce-multi-vendor') . '"></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" data-comment_id="' . $comment->comment_ID . '" data-vendor_id="' . get_current_vendor_id() . '" class="btn btn-default wcmp-comment-reply">' . __('Comment', 'dc-woocommerce-multi-vendor') . '</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>';

                $data[] = array($row);
            endforeach;
        }
        $json_data = array(
            "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
            "recordsTotal" => intval(count($vendor_reviews_total)), // total number of records
            "recordsFiltered" => intval(count($vendor_reviews_total)), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $data   // total data array
        );
        wp_send_json($json_data);
        die;
    }

    public function wcmp_vendor_dashboard_customer_questions_data() {
        global $WCMp;
        $vendor = get_current_vendor();
        $requestData = $_REQUEST;
        $data_html = array();
        $active_qna_total = array();
        if (get_transient('wcmp_customer_qna_for_vendor_' . $vendor->id)) {
            $active_qna_total = get_transient('wcmp_customer_qna_for_vendor_' . $vendor->id);
        } else {
            $active_qna_total = $WCMp->product_qna->get_Vendor_Questions($vendor);
            set_transient('wcmp_customer_qna_for_vendor_' . $vendor->id, $active_qna_total);
        }
        if ($active_qna_total) {
            $active_qna = array_slice($active_qna_total, $requestData['start'], $requestData['length']);
            if ($active_qna) {
                foreach ($active_qna as $key => $data) :
                    $product = wc_get_product($data->product_ID);
                    $row = '';
                    $row = '<article id="reply-item-' . $data->ques_ID . '" class="reply-item">
                        <div class="media">
                            <!-- <div class="media-left">' . $product->get_image() . '</div> -->
                            <div class="media-body">
                                <h4 class="media-heading qna-question">' . wp_trim_words($data->ques_details, 160, '...') . '</h4>
                                <time class="qna-date">
                                    <span>' . wcmp_date($data->ques_created) . '</span>
                                </time>
                                <a data-toggle="modal" data-target="#qna-reply-modal-' . $data->ques_ID . '" >' . __('Reply', 'dc-woocommerce-multi-vendor') . '</a>
                                <!-- Modal -->
                                <div class="modal fade" id="qna-reply-modal-' . $data->ques_ID . '" role="dialog">
                                    <div class="modal-dialog">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title">' . __('Product - ', 'dc-woocommerce-multi-vendor') . ' ' . $product->get_formatted_name() . '</h4>
                                            </div>
                                            <div class="wcmp-widget-modal modal-body">
                                                    <label class="qna-question">' . stripslashes($data->ques_details) . '</label>
                                                    <textarea class="form-control" rows="5" id="qna-reply-' . $data->ques_ID . '" placeholder="' . __('Post your answer...', 'dc-woocommerce-multi-vendor') . '"></textarea>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" data-key="' . $data->ques_ID . '" class="btn btn-default wcmp-add-qna-reply">' . __('Add', 'dc-woocommerce-multi-vendor') . '</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>';

                    $data_html[] = array($row);
                endforeach;
            }
        }

        $json_data = array(
            "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
            "recordsTotal" => intval(count($active_qna_total)), // total number of records
            "recordsFiltered" => intval(count($active_qna_total)), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $data_html   // total data array
        );
        wp_send_json($json_data);
        die;
    }

    public function wcmp_vendor_products_qna_list() {
        global $WCMp;
        $requestData = $_REQUEST;
        $vendor = get_current_vendor();
        // filter by status
        if (isset($requestData['qna_status']) && $requestData['qna_status'] == 'all' && $requestData['qna_status'] != '') {
            $vendor_questions_n_answers = $WCMp->product_qna->get_Vendor_Questions($vendor, false);
        } else {
            $vendor_questions_n_answers = $WCMp->product_qna->get_Vendor_Questions($vendor, true);
        }
        // filter by products
        if (isset($requestData['qna_products']) && is_array($requestData['qna_products'])) {
            if ($vendor_questions_n_answers) {
                foreach ($vendor_questions_n_answers as $key => $qna_ques) {
                    if (!in_array($qna_ques->product_ID, $requestData['qna_products'])) {
                        unset($vendor_questions_n_answers[$key]);
                    }
                }
            }
        }
        $vendor_qnas = array_slice($vendor_questions_n_answers, $requestData['start'], $requestData['length']);
        $data = array();

        if ($vendor_qnas) {
            // filter by vote
            if ($requestData['order'][0]['dir'] != 'asc') {
                $votes = array();
                foreach ($vendor_qnas as $key => $qna_ques) {
                    $count = 0;
                    $have_answer = $WCMp->product_qna->get_Answers($qna_ques->ques_ID);
                    if (isset($have_answer[0]) && count($have_answer[0]) > 0) {
                        $ans_vote = maybe_unserialize($have_answer[0]->ans_vote);
                        if (is_array($ans_vote)) {
                            $count = array_sum($ans_vote);
                        }
                        $vendor_qnas[$key]->vote_count = $count;
                        $votes[$key] = $count;
                    } else {
                        $vendor_qnas[$key]->vote_count = $count;
                        $votes[$key] = $count;
                    }
                }
                array_multisort($votes, SORT_DESC, $vendor_qnas);
            }

            foreach ($vendor_qnas as $question) {
                $product = wc_get_product($question->product_ID);
                if ($product) {
                    $have_answer = $WCMp->product_qna->get_Answers($question->ques_ID);
                    $details = '';
                    $status = '';
                    $vote = '&ndash;';
                    if (!isset($have_answer[0])) {
                        $status = '<span class="unanswered label label-default">' . __('Unanswered', 'dc-woocommerce-multi-vendor') . '</span>';
                        $details .= '<div class="wcmp-question-details-modal modal-body">
                                        <textarea class="form-control" rows="5" id="qna-reply-' . $question->ques_ID . '" placeholder="' . __('Post your answer...', 'dc-woocommerce-multi-vendor') . '"></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" data-key="' . $question->ques_ID . '" class="btn btn-default wcmp-add-qna-reply">' . __('Add', 'dc-woocommerce-multi-vendor') . '</button>
                                    </div>';
                    } else {
                        $status = '<span class="answered label label-success">' . __('Answered', 'dc-woocommerce-multi-vendor') . '</span>';
                        $ans_vote = maybe_unserialize($have_answer[0]->ans_vote);
                        if (is_array($ans_vote)) {
                            $vote = array_sum($ans_vote);
                            if ($vote > 0) {
                                $vote = '<span class="label label-success">' . $vote . '</span>';
                            } else {
                                $vote = '<span class="label label-danger">' . $vote . '</span>';
                            }
                        }
                        if (apply_filters('wcmp_vendor_can_modify_qna_answer', false)) {
                            $details .= '<div class="wcmp-question-details-modal modal-body">
                                        <textarea class="form-control" rows="5" id="qna-answer-' . $have_answer[0]->ans_ID . '">' . stripslashes($have_answer[0]->ans_details) . '</textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" data-key="' . $have_answer[0]->ans_ID . '" class="btn btn-default wcmp-update-qna-answer">' . __('Edit', 'dc-woocommerce-multi-vendor') . '</button>
                                    </div>';
                        } else {
                            $details .= '<div class="wcmp-question-details-modal modal-body">
                                        <textarea class="form-control" rows="5" id="qna-answer-' . $have_answer[0]->ans_ID . '" disabled>' . stripslashes($have_answer[0]->ans_details) . '</textarea>
                                    </div>';
                        }
                    }
                    $data[] = array(
                        'qnas' => '<a data-toggle="modal" data-target="#question-details-modal-' . $question->ques_ID . '" data-ques="' . $question->ques_ID . '" class="question-details">' . wp_trim_words(stripslashes($question->ques_details), 160, '...') . '</a>'
                        . '<!-- Modal -->
                                <div class="modal fade" id="question-details-modal-' . $question->ques_ID . '" role="dialog">
                                    <div class="modal-dialog">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title">' . stripslashes($question->ques_details) . '</h4>
                                            </div>
                                            ' . $details . '
                                        </div>
                                    </div>
                                </div>',
                        'product' => $product->get_title(),
                        'date' => wcmp_date($question->ques_created),
                        'vote' => $vote,
                        'status' => $status
                    );
                }
            }
        }
        $json_data = array(
            "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
            "recordsTotal" => intval(count($vendor_questions_n_answers)), // total number of records
            "recordsFiltered" => intval(count($vendor_questions_n_answers)), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $data   // total data array
        );
        wp_send_json($json_data);
    }
    
    function wcmp_get_vendor_details() {
    	global $WCMp;
    	
    	if (!isset($_GET['vendor_id'])) {
            wp_die( __('No Vendor ID found', 'dc-woocommerce-multi-vendor') );
        }
        
        if (isset($_GET['vendor_id']) && isset($_GET['vendor_id']) && isset($_GET['nonce'])) {
            $vendor_id = $_GET['vendor_id'];
            $nonce = $_REQUEST["nonce"];

            if (!wp_verify_nonce($nonce, 'wcmp-vendors')) wp_die( __('Invalid request', 'dc-woocommerce-multi-vendor') );

            $vendor = get_wcmp_vendor($vendor_id);
            $product_count = 0;
			$user_info['status'] = '';
			$user_info['status_name'] = '';
			if($vendor) {
				$vendor_term_id = get_user_meta($vendor_id, '_vendor_term_id', true);
			
				$vendor_products = $vendor->get_products();
				
				$vendor_review_info = wcmp_get_vendor_review_info($vendor_term_id);
				if(isset($vendor_review_info['total_rating'])) {
					$user_info['total_rating'] = $vendor_review_info['total_rating'];
					$user_info['avg_rating'] = number_format(floatval($vendor_review_info['avg_rating']), 1);
    			}
    			
    			$vendor_report_data = get_wcmp_vendor_dashboard_stats_reports_data($vendor);
    			if(isset($vendor_report_data[30]) && is_array($vendor_report_data[30])) {
    				$user_info['last_30_days_earning'] = $vendor_report_data[30]['_wcmp_stats_table']['current_earning'];
    				$user_info['last_30_days_sales_total'] = $vendor_report_data[30]['_wcmp_stats_table']['current_sales_total'];
    				$user_info['last_30_days_withdrawal'] = $vendor_report_data[30]['_wcmp_stats_table']['current_withdrawal'];
    				$user_info['last_30_days_orders_no'] = $vendor_report_data[30]['_wcmp_stats_table']['current_orders_no'];
    			}
    			
    			$unpaid_orders = get_wcmp_vendor_order_amount(array('commission_status' => 'unpaid'), $vendor->id);
    			if(isset($unpaid_orders['total']) && $unpaid_orders['total'] > 0) $user_info['withdrawable_balance'] = wc_price($unpaid_orders['total']);
    			else $user_info['withdrawable_balance'] = wc_price(0);
    			
    			$vendor_profile_image = get_user_meta($vendor_id, '_vendor_profile_image', true);
    			if(isset($vendor_profile_image) && $vendor_profile_image > 0) $user_info['profile_image'] = wp_get_attachment_url($vendor_profile_image);
    			else $user_info['profile_image'] = get_avatar_url($vendor_id, array('size' => 120));
    			
				$user_info['products'] = count($vendor_products);
                $user_info['shop_title'] = $vendor->page_title;
                $user_info['shop_url'] = $vendor->permalink;
                $user_info['address_1'] = $vendor->address_1;
                $user_info['address_2'] = $vendor->address_2;
                $user_info['city'] = $vendor->city;
                $user_info['state'] = $vendor->state;
                $user_info['country'] = $vendor->country;
                $user_info['postcode'] = $vendor->postcode;
                $user_info['phone'] = $vendor->phone;
                $user_info['description'] = $vendor->description;
                
                $user_info['facebook'] = $vendor->fb_profile;
				$user_info['twitter'] = $vendor->twitter_profile;
				$user_info['google_plus'] = $vendor->google_plus_profile;
				$user_info['linkdin'] = $vendor->linkdin_profile;
				$user_info['youtube'] = $vendor->youtube;
				$user_info['instagram'] = $vendor->instagram;
				
				$user_info['payment_mode'] = $vendor->payment_mode;
				$user_info['gateway_logo'] = isset($WCMp->payment_gateway->payment_gateways[$vendor->payment_mode]) ? $WCMp->payment_gateway->payment_gateways[$vendor->payment_mode]->gateway_logo() : '';
				
				$vendor_progress = wcmp_get_vendor_profile_completion( $vendor->id );
				
				if(isset($vendor_progress['progress'])) $user_info['profile_progress'] = $vendor_progress['progress'];
			}
			
			$user = get_user_by("ID", $vendor_id);
			$user_info['ID'] = $user->data->ID;
			$user_info['display_name'] = $user->data->display_name;
			$user_info['email'] = $user->data->user_email;
			$user_info['registered'] = $user->data->user_registered;
			
			$actions_html = '';
			
			if(in_array('dc_vendor', $user->roles)) {
				$is_block = get_user_meta($vendor_id, '_vendor_turn_off', true);
				if($is_block) {
					$user_info['status_name'] = __('Suspended', 'dc-woocommerce-multi-vendor');
					$user_info['status'] = 'suspended';
					$actions['activate'] = array(
						'ID'     => $user_info['ID'],
						'ajax_action' => 'wcmp_activate_vendor',
						'url'    => '#',
						'name'   => __( 'Activate', 'dc-woocommerce-multi-vendor' ),
						'action' => 'activate',
					);
				} else {
					$user_info['status_name'] = __('Approved', 'dc-woocommerce-multi-vendor');
					$user_info['status'] = 'approved';
					$actions['suspend'] = array(
						'ID'     => $user_info['ID'],
						'ajax_action' => 'wcmp_suspend_vendor',
						'url'    => '#',
						'name'   => __( 'Suspend', 'dc-woocommerce-multi-vendor' ),
						'action' => 'suspend',
					);
				}
			} else if(in_array('dc_rejected_vendor', $user->roles)) {
				$user_info['status_name'] = __('Rejected', 'dc-woocommerce-multi-vendor');
				$user_info['status'] = 'rejected';
			} else if(in_array('dc_pending_vendor', $user->roles)) {
				$user_info['status_name'] = __('Pending', 'dc-woocommerce-multi-vendor');
				$user_info['status'] = 'pending';
				$actions['approve'] = array(
					'ID'     => $user_info['ID'],
					'ajax_action' => 'activate_pending_vendor',
					'url'    => '#',
					'name'   => __( 'Approve', 'dc-woocommerce-multi-vendor' ),
					'action' => 'approve',
				);
				$actions['reject'] = array(
					'ID'     => $user_info['ID'],
					'ajax_action' => 'reject_pending_vendor',
					'url'    => '#',
					'name'   => __( 'Reject', 'dc-woocommerce-multi-vendor' ),
					'action' => 'reject',
				);
			}
			
			if(isset($actions) && is_array($actions)) {
				foreach($actions as $action) {
					$actions_html .= sprintf( '<a class="button button-primary button-large wcmp-action-button wcmp-action-button-%1$s %1$s-vendor" href="%2$s" aria-label="%3$s" title="%3$s" data-vendor-id="%4$s" data-ajax-action="%5$s">%6$s</a>', esc_attr( $action['action'] ), esc_url( $action['url'] ), esc_attr( isset( $action['title'] ) ? $action['title'] : $action['name'] ), $action['ID'], $action['ajax_action'], esc_html( $action['name'] ) );
				}
				$user_info['actions_html'] = $actions_html;
			}
			
			if( in_array('dc_pending_vendor', $user->roles) || in_array('dc_rejected_vendor', $user->roles) ) {
				// Add Vendor Application data
				$vendor_application_data = get_user_meta( $user_info['ID'], 'wcmp_vendor_fields', true );
				$vendor_application_data_string = '';
				if (!empty($vendor_application_data) && is_array($vendor_application_data)) {
					foreach ($vendor_application_data as $key => $value) {
                                            if ($value['type'] == 'recaptcha') continue;
						$vendor_application_data_string .= '<div class="wcmp-form-field">';
						$vendor_application_data_string .= '<label>' . html_entity_decode($value['label']) . ':</label>';
						if ($value['type'] == 'file') {
							if (!empty($value['value']) && is_array($value['value'])) {
								foreach ($value['value'] as $attacment_id) {
									$vendor_application_data_string .= '<span> <a href="' . wp_get_attachment_url($attacment_id) . '" download>' . get_the_title($attacment_id) . '</a> </span>';
								}
							}
						} else {
							if (is_array($value['value'])) {
								$vendor_application_data_string .= '<span> ' . implode(', ', $value['value']) . '</span>';
							} else {
								$vendor_application_data_string .= '<span> ' . $value['value'] . '</span>';
							}
						}
						$vendor_application_data_string .= '</div>';
					}
				}
				$user_info['vendor_application_data'] = $vendor_application_data_string;
				
				$wcmp_vendor_rejection_notes = unserialize( get_user_meta( $user_info['ID'], 'wcmp_vendor_rejection_notes', true ) );
				
				$wcmp_vendor_custom_notes_html = '';
                                if($wcmp_vendor_rejection_notes) :
                                    foreach($wcmp_vendor_rejection_notes as $time => $notes) {
                                            $author_info = get_userdata($notes['note_by']);
                                            $wcmp_vendor_custom_notes_html .= '<div class="note-clm"><p class="note-description">' . $notes['note'] . '</p><p class="note_time note-meta">On ' . date( "Y-m-d", $time ) . '</p><p class="note_owner note-meta">By ' . $author_info->display_name . '</p></div>';
                                    }
                                endif;
				
				$user_info['vendor_custom_notes'] = $wcmp_vendor_custom_notes_html;
			}
			
			wp_send_json_success( $user_info );
		}
		return 0;
    }
    
    /**
     * Ajax handler for tag add.
     *
     * @since 3.0.6
     */
    function wcmp_product_tag_add() {
        $taxonomy = apply_filters( 'wcmp_product_tag_add_taxonomy', 'product_tag' );
	$tax = get_taxonomy($taxonomy);
        $tag_name = '';
        $message = '';
        $status = false;
        if (!apply_filters('wcmp_vendor_can_add_product_tag', true, get_current_user_id())){
            $message = __("You don't have permission to add product tags", 'dc-woocommerce-multi-vendor');
            wp_send_json(array('status' => $status, 'tag_name' => $tag_name, 'message' => $message));
            die;
        }

	$tag = wp_insert_term($_POST['new_tag'], $taxonomy, array() );

	if ( !$tag || is_wp_error($tag) || (!$tag = get_term( $tag['term_id'], $taxonomy )) ) {
            $message = __('An error has occurred. Please reload the page and try again.', 'dc-woocommerce-multi-vendor');
            if ( is_wp_error($tag) && $tag->get_error_message() )
                $message = $tag->get_error_message();
        }else{
            $tag_name = $tag->name;
            $status = true;
        }
        wp_send_json(array('status' => $status, 'tag_name' => $tag_name, 'message' => $message));
        die;
    }
    
    function wcmp_widget_vendor_pending_shipping() {
        if (is_user_logged_in() && is_user_wcmp_vendor(get_current_vendor_id())) {
            
            $vendor = get_wcmp_vendor(get_current_vendor_id());
            $requestData = $_REQUEST;
            $today = @date('Y-m-d 00:00:00', strtotime("+1 days"));
            $last_seven_day_date = date('Y-m-d H:i:s', strtotime('-7 days'));
            
            $args = apply_filters('wcmp_vendor_pending_shipping_args', array(
                'start_date' => $last_seven_day_date,
                'end_date' => $today
            ));
            $pending_shippings = $vendor->get_vendor_orders_reports_of('pending_shipping', $args);
            $pending_shippings_arr = array();
            if($pending_shippings){
                foreach ($pending_shippings as $pending_orders_item) { 
                    $order = wc_get_order($pending_orders_item->order_id);
                    // hide shipping for local pickup
                    $vendor_shipping_method = get_wcmp_vendor_order_shipping_method($order->get_id(), $vendor->id);
                    if($vendor_shipping_method && in_array($vendor_shipping_method->get_method_id(), apply_filters('hide_shipping_icon_for_vendor_order_on_methods',array('local_pickup'))))
                        continue; 

                    $pending_shippings_arr[] = $pending_orders_item;
                }
            }
            $data = array();
            if ($pending_shippings_arr) {
                foreach ($pending_shippings_arr as $pending_orders_item) {
                    try {
                        $order = wc_get_order($pending_orders_item->order_id);
                        $pending_shipping_products = get_wcmp_vendor_orders(array('vendor_id' => $vendor->id, 'order_id' => $order->get_id(), 'shipping_status' => 0, 'is_trashed' => ''));
                        $pending_shipping_amount = get_wcmp_vendor_order_amount(array('vendor_id' => $vendor->id, 'order_id' => $order->get_id(), 'shipping_status' => 0));
                        $product_sku = array();
                        $product_name = array();
                        //$product_dimention = array();
                        foreach ($pending_shipping_products as $pending_shipping_product) {
                            $product = wc_get_product($pending_shipping_product->product_id);
                            if ($product && $product->needs_shipping()) {
                                $product_sku[] = $product->get_sku() ? $product->get_sku() : '<span class="na">&ndash;</span>';
                                $product_name[] = $product->get_title();
                                if ($pending_shipping_product->variation_id != 0) {
                                    $product = wc_get_product($pending_shipping_product->variation_id);
                                }
                            }
                        }
                        if(empty($product_name))                                
                            continue;  

                        $action_html = '';
                        if ($vendor->is_shipping_enable()) {
                            $is_shipped = (array) get_post_meta($order->get_id(), 'dc_pv_shipped', true);
                            if (!in_array($vendor->id, $is_shipped)) {
                                $action_html .= '<a href="javascript:void(0)" title="' . __('Mark as shipped', 'dc-woocommerce-multi-vendor') . '" onclick="wcmpMarkeAsShip(this,' . $order->get_id() . ')"><i class="wcmp-font ico-shippingnew-icon action-icon"></i></a> ';
                            } else {
                                $action_html .= '<i title="' . __('Shipped', 'dc-woocommerce-multi-vendor') . '" class="wcmp-font ico-shipping-icon"></i> ';
                            }
                        }
                        $action_html = apply_filters('wcmp_dashboard_pending_shipping_widget_data_actions', $action_html, $order->get_id());
                        $row = array();
                        $row ['order_id'] = '<a href="'.esc_url(wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_vendor_orders_endpoint', 'vendor', 'general', 'vendor-orders'), $order->get_id())).'">#'.$order->get_id().'</a>';
                        $row ['products_name'] = implode(' , ', $product_name);
                        $row ['order_date'] = wcmp_date($order->get_date_created());
                        $row ['shipping_address'] = $order->get_formatted_shipping_address();
                        $row ['shipping_amount'] = wc_price($pending_shipping_amount['shipping_amount']);
                        $row ['action'] = $action_html;
                        $data[] = apply_filters('wcmp_widget_vendor_pending_shipping_row_data', $row, $pending_orders_item, $order);

                        
                    } catch (Exception $ex) {}
                }
            }
            $json_data = array(
                "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
                "recordsTotal" => intval(count($pending_shippings_arr)), // total number of records
                "recordsFiltered" => intval(count($pending_shippings_arr)), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data" => $data   // total data array
            );
            wp_send_json($json_data);
            die;
        }
    }
    
    function wcmp_widget_vendor_product_sales_report() {
        global $wpdb;
        if (is_user_logged_in() && is_user_wcmp_vendor(get_current_vendor_id())) {
   
            $vendor = get_wcmp_vendor(get_current_vendor_id());
            $requestData = $_REQUEST;
            $today = @date('Y-m-d 00:00:00', strtotime("+1 days"));
            $last_seven_day_date = date('Y-m-d H:i:s', strtotime('-7 days'));

            $sale_results = $wpdb->get_results(
                $wpdb->prepare("SELECT * FROM {$wpdb->prefix}wcmp_vendor_orders WHERE commission_id != 0 AND vendor_id=%d AND `created` BETWEEN %s AND %s", $vendor->id, $last_seven_day_date, $today
                )
            );
            $sold_product_list = array();
            if($sale_results) :
                foreach ($sale_results as $key => $value) {
                    if (array_key_exists($value->product_id, $sold_product_list)) {
                        $sold_product_list[$value->product_id]['qty'] += $value->quantity;
                    } else {
                        $sold_product_list[$value->product_id]['qty'] = $value->quantity;
                        $sold_product_list[$value->product_id]['item_id'] = $value->order_item_id;
                        $sold_product_list[$value->product_id]['order_id'] = $value->order_id;
                    }
                }
            endif;
            arsort($sold_product_list);
            $data = array();
            foreach ($sold_product_list as $product_id => $value) {
                $product = wc_get_product($product_id);
                $row = array();
                if ($product) {                    
                    $row ['product'] = '<a href="' . $product->get_permalink($product_id) . '">' . $product->get_image(array(40, 40)) . ' ' . wp_trim_words( $product->get_name(), 60, '...' ) . '</a>';
                    $row ['revenue'] = wc_price($product->get_price('edit') * $value['qty']) ;
                    $row ['unique_purchase'] = $value['qty'];
                }else{
                    $row ['product'] = __('This product does not exists', 'dc-woocommerce-multi-vendor');
                    $row ['revenue'] = '-';
                    $row ['unique_purchase'] = $value['qty'];
                }
                $data[] = apply_filters('wcmp_widget_vendor_product_sales_report_row_data', $row, $product_id, $value);
            }
            
            $json_data = array(
                "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
                "recordsTotal" => intval(count($sold_product_list)), // total number of records
                "recordsFiltered" => intval(count($sold_product_list)), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data" => $data   // total data array
            );
            wp_send_json($json_data);
            die;
            
        }
    }

}
