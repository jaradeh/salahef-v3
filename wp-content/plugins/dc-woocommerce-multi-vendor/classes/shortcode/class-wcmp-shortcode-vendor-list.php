<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('WCMp_Shortcode_Vendor_List')) {

    class WCMp_Shortcode_Vendor_List {

        /**
         * Filter vendor list
         * @global object $WCMp
         * @param string $orderby
         * @param string $order
         * @param string $product_category
         * @return array
         */
        public static function get_vendor($orderby = 'registered', $order = 'ASC', $product_category = '') {
            global $WCMp;
            $vendor_info = array();
            $block_vendors = wp_list_pluck(wcmp_get_all_blocked_vendors(), 'id');
            if ($product_category) {
                $args = array(
                    'posts_per_page' => -1,
                    'post_type' => 'product',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'product_cat',
                            'field' => 'term_id',
                            'terms' => absint($product_category)
                        )
                    )
                );
                $products = get_posts($args);
                $product_ids = wp_list_pluck($products, 'ID');
                foreach ($product_ids as $product_id) {
                    $vendor = get_wcmp_product_vendors($product_id);
                    if ($vendor && !in_array($vendor->id, $block_vendors)) {
                        $vendor_info[$vendor->id] = array(
                            'vendor_permalink' => $vendor->permalink,
                            'vendor_name' => $vendor->page_title,
                            'vendor_image' => $vendor->get_image() ? $vendor->get_image('image', array(125, 125)) : $WCMp->plugin_url . 'assets/images/WP-stdavatar.png',
                            'ID' => $vendor->id,
                            'term_id' => $vendor->term_id
                        );
                    }
                }
            } else {
                $sort_type = isset($_REQUEST['vendor_sort_type']) ? $_REQUEST['vendor_sort_type'] : '';
                $vendors = get_wcmp_vendors(apply_filters('wcmp_vendor_list_get_wcmp_vendors_args', array('orderby' => $orderby, 'order' => $order), $sort_type, $_GET));
                foreach ($vendors as $vendor) {
                    if (!in_array($vendor->id, $block_vendors)) {
                        $vendor_info[$vendor->id] = array(
                            'vendor_permalink' => $vendor->permalink,
                            'vendor_name' => $vendor->page_title,
                            'vendor_image' => $vendor->get_image() ? $vendor->get_image('image', array(125, 125)) : $WCMp->plugin_url . 'assets/images/WP-stdavatar.png',
                            'ID' => $vendor->id,
                            'term_id' => $vendor->term_id
                        );
                    }
                }
            }
            return $vendor_info;
        }

        /**
         * Output vendor list shortcode
         * @global object $WCMp
         * @param array $atts
         */
        public static function output($atts) {
            global $WCMp;
            wp_enqueue_script('frontend_js');
            extract(shortcode_atts(array('orderby' => 'registered', 'order' => 'ASC'), $atts, 'wcmp_vendorslist'));
            $product_category = $sort_type = '';
            if (isset($_REQUEST['vendor_sort_type'])) {
                $sort_type = $_REQUEST['vendor_sort_type'];
                if ($sort_type == 'category' && isset($_REQUEST['vendor_sort_category'])) {
                    $product_category = $_REQUEST['vendor_sort_category'];
                } else {
                    $orderby = $_REQUEST['vendor_sort_type'];
                }
            }
            $vendor_info = apply_filters('wcmp_vendor_lits_vendor_info_fields', self::get_vendor($orderby, $order, $product_category));
            $WCMp->template->get_template('shortcode/vendor_lists.php', array('vendor_info' => $vendor_info, 'sort_type' => $sort_type, 'selected_category' => $product_category));
        }

    }

}