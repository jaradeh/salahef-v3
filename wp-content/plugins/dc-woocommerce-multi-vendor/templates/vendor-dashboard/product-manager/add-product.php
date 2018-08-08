<?php
/*
 * The template for displaying vendor add product
 * Override this template by copying it to yourtheme/dc-product-vendor/vendor-dashboard/product-manager/add-product.php
 *
 * @author 	WC Marketplace
 * @package 	WCMp/Templates
 * @version   3.0.0
 */
global $WCMp, $wc_product_attributes;

$current_vendor_id = apply_filters('wcmp_current_loggedin_vendor_id', get_current_user_id());

// If vendor does not have product submission cap then show message
if (is_user_logged_in() && is_user_wcmp_vendor($current_vendor_id) && !current_user_can('edit_products')) {
    ?>
    <div class="col-md-12">
        <div class="panel panel-default">
            <?php _e('You do not have enough permission to submit a new product. Please contact site administrator.', 'dc-woocommerce-multi-vendor'); ?>
        </div>
    </div>
    <?php
    return;
}
if (is_user_logged_in() && is_user_wcmp_vendor($current_vendor_id) && !current_user_can('edit_published_products') && !empty($pro_id)) {
    ?>
    <div class="col-md-12">
        <div class="panel panel-default">
            <?php _e('Product has been sent to the admin for review. Once done, the product will be published.', 'dc-woocommerce-multi-vendor'); ?>
        </div>
    </div>
    <?php
    return;
}

$product_id = 0;
$product = array();
$product_type = '';
$is_virtual = '';
$title = '';
$sku = '';
$excerpt = '';
$description = '';
$regular_price = '';
$sale_price = '';
$sale_date_from = '';
$sale_date_upto = '';
//$product_url = '';
//$button_text = '';
$visibility = 'visible';
$is_downloadable = '';
$downloadable_files = array();
$download_limit = '';
$download_expiry = '';
$featured_img = '';
$gallery_img_ids = array();
$gallery_img_urls = array();
$categories = array();
$product_tag_list = array();
$product_tags = '';
$manage_stock = '';
$stock_qty = 0;
$backorders = '';
$stock_status = '';
$sold_individually = '';
$weight = '';
$length = '';
$width = '';
$height = '';
$shipping_class = '';
$tax_status = '';
$tax_class = '';
$attributes = array();
$default_attributes = '';
$attributes_select_type = array();
$variations = array();

$upsell_ids = array();
$crosssell_ids = array();
$children = array();

$enable_reviews = 'enable';
$menu_order = '';
$purchase_note = '';

// Yoast SEO Support
$yoast_wpseo_focuskw_text_input = '';
$yoast_wpseo_metadesc = '';

// WooCommerce Custom Product Tabs Lite Support
$product_tabs = array();

// WooCommerce Product Fees Support
$product_fee_name = '';
$product_fee_amount = '';
$product_fee_multiplier = 'no';

// WooCommerce Bulk Discount Support
$_bulkdiscount_enabled = 'no';
$_bulkdiscount_text_info = '';
$_bulkdiscounts = array();


if (!empty($pro_id)) {
    $product = wc_get_product((int) $pro_id);

    // Fetching Product Data
    if ($product && !empty($product)) {
        $product_id = $product->get_id();

        $vendor_data = get_wcmp_product_vendors($product_id);
        if (!current_user_can('administrator') && $vendor_data && ( $vendor_data->id != $current_vendor_id )) {
            _e('You do not have enough permission to access this product.', 'dc-woocommerce-multi-vendor');
            return;
        }

        $product_type = $product->get_type();
        $title = $product->get_title();
        $sku = $product->get_sku();
        $excerpt = $product->get_short_description();
        $description = $product->get_description();
        $regular_price = $product->get_regular_price();
        $sale_price = $product->get_sale_price();

        $sale_date_from = ( $date = get_post_meta($product_id, '_sale_price_dates_from', true) ) ? date_i18n('Y-m-d', $date) : '';
        $sale_date_upto = ( $date = get_post_meta($product_id, '_sale_price_dates_to', true) ) ? date_i18n('Y-m-d', $date) : '';

        // External product option
        //$product_url = get_post_meta($product_id, '_product_url', true);
        //$button_text = get_post_meta($product_id, '_button_text', true);
        // Product Visibility
        //$visibility = get_post_meta($product_id, '_visibility', true);
        $visibility = $product->get_catalog_visibility('view');

        // Virtual
        $is_virtual = ( get_post_meta($product_id, '_virtual', true) == 'yes' ) ? 'enable' : '';

        // Download ptions
        $is_downloadable = ( get_post_meta($product_id, '_downloadable', true) == 'yes' ) ? 'enable' : '';
        if ($is_downloadable == 'enable') {
            $downloadable_files = get_post_meta($product_id, '_downloadable_files', true);
            if (!$downloadable_files)
                $downloadable_files = array();
            $download_limit = get_post_meta($product_id, '_download_limit', true);
            $download_expiry = get_post_meta($product_id, '_download_expiry', true);
        }

        // Product Images
        $featured_img = ($product->get_image_id()) ? $product->get_image_id() : '';
        if ($featured_img)
            $featured_img = wp_get_attachment_url($featured_img);
        $gallery_img_ids = $product->get_gallery_image_ids();
        if (!empty($gallery_img_ids)) {
            foreach ($gallery_img_ids as $gallery_img_id) {
                $gallery_img_urls[]['image'] = wp_get_attachment_url($gallery_img_id);
            }
        }

        // Product Categories
        $pcategories = get_the_terms($product_id, 'product_cat');
        if (!empty($pcategories)) {
            foreach ($pcategories as $pkey => $pcategory) {
                $categories[] = $pcategory->term_id;
            }
        } else {
            $categories = array();
        }

        // Product Tags
        $product_tag_list = wp_get_post_terms($product_id, 'product_tag', array("fields" => "names"));
        $product_tags = implode(',', $product_tag_list);

        // Product Stock options
        $manage_stock = $product->managing_stock() ? 'enable' : '';
        $stock_qty = $product->get_stock_quantity();
        $backorders = $product->get_backorders();
        $stock_status = $product->get_stock_status();
        $sold_individually = $product->is_sold_individually() ? 'enable' : '';

        // Product Shipping Data
        $weight = $product->get_weight();
        $length = $product->get_length();
        $width = $product->get_width();
        $height = $product->get_height();
        $shipping_class = $product->get_shipping_class_id();

        // Product Tax Data
        $tax_status = $product->get_tax_status();
        $tax_class = $product->get_tax_class();

        // Product Attributes
        $pro_attributes = get_post_meta($product_id, '_product_attributes', true);
        if (!empty($pro_attributes)) {
            $acnt = 0;
            foreach ($pro_attributes as $pro_attribute) {

                if ($pro_attribute['is_taxonomy']) {
                    $att_taxonomy = $pro_attribute['name'];

                    if (!taxonomy_exists($att_taxonomy)) {
                        continue;
                    }

                    $attribute_taxonomy = $wc_product_attributes[$att_taxonomy];

                    $attributes[$acnt]['term_name'] = $att_taxonomy;
                    $attributes[$acnt]['name'] = wc_attribute_label($att_taxonomy);
                    $attributes[$acnt]['attribute_taxonomy'] = $attribute_taxonomy;
                    $attributes[$acnt]['tax_name'] = $att_taxonomy;
                    $attributes[$acnt]['is_taxonomy'] = 1;

                    if ('select' === $attribute_taxonomy->attribute_type) {
                        $args = array(
                            'orderby' => 'name',
                            'hide_empty' => 0
                        );
                        $all_terms = get_terms($att_taxonomy, apply_filters('wc_product_attribute_terms', $args));
                        $attributes_option = array();
                        if ($all_terms) {
                            foreach ($all_terms as $term) {
                                $attributes_option[$term->term_id] = esc_attr(apply_filters('woocommerce_product_attribute_term_name', $term->name, $term));
                            }
                        }
                        $attributes[$acnt]['attribute_type'] = 'select';
                        $attributes[$acnt]['option_values'] = $attributes_option;
                        $attributes[$acnt]['value'] = wp_get_post_terms($product_id, $att_taxonomy, array('fields' => 'ids'));
                    } else {
                        $attributes[$acnt]['attribute_type'] = 'text';
                        $attributes[$acnt]['value'] = esc_attr(implode(' ' . WC_DELIMITER . ' ', wp_get_post_terms($product_id, $att_taxonomy, array('fields' => 'names'))));
                    }
                } else {
                    $attributes[$acnt]['term_name'] = apply_filters('woocommerce_attribute_label', $pro_attribute['name'], $pro_attribute['name'], $product);
                    $attributes[$acnt]['name'] = apply_filters('woocommerce_attribute_label', $pro_attribute['name'], $pro_attribute['name'], $product);
                    $attributes[$acnt]['value'] = $pro_attribute['value'];
                    $attributes[$acnt]['tax_name'] = '';
                    $attributes[$acnt]['is_taxonomy'] = 0;
                    $attributes[$acnt]['attribute_type'] = 'text';
                }

                $attributes[$acnt]['is_visible'] = $pro_attribute['is_visible'] ? 'enable' : '';
                $attributes[$acnt]['is_variation'] = $pro_attribute['is_variation'] ? 'enable' : '';

                if ('select' === $attributes[$acnt]['attribute_type']) {
                    $attributes_select_type[$acnt] = $attributes[$acnt];
                    unset($attributes[$acnt]);
                }
                $acnt++;
            }
        }

        // Product Default Attributes
        $default_attributes = json_encode((array) get_post_meta($product_id, '_default_attributes', true));

        $upsell_ids = get_post_meta($product_id, '_upsell_ids', true) ? get_post_meta($product_id, '_upsell_ids', true) : array();
        $crosssell_ids = get_post_meta($product_id, '_crosssell_ids', true) ? get_post_meta($product_id, '_crosssell_ids', true) : array();
        //$children = get_post_meta($product_id, '_children', true) ? get_post_meta($product_id, '_children', true) : array();
        // Product Advance Options
        $product_post = get_post($product_id);
        $enable_reviews = ( $product_post->comment_status == 'open' ) ? 'enable' : '';
        $menu_order = $product_post->menu_order;
        $purchase_note = get_post_meta($product_id, '_purchase_note', true);

        // Yoast SEO Support
        if (WC_Dependencies_Product_Vendor::fpm_yoast_plugin_active_check()) {
            $yoast_wpseo_focuskw_text_input = get_post_meta($product_id, '_yoast_wpseo_focuskw_text_input', true);
            $yoast_wpseo_metadesc = get_post_meta($product_id, '_yoast_wpseo_metadesc', true);
        }

        // WooCommerce Custom Product Tabs Lite Support
        if (WC_Dependencies_Product_Vendor::fpm_wc_tabs_lite_plugin_active_check()) {
            $product_tabs = (array) get_post_meta($product_id, 'frs_woo_product_tabs', true);
        }

        // WooCommerce Product Fees Support
        if (WC_Dependencies_Product_Vendor::fpm_wc_product_fees_plugin_active_check()) {
            $product_fee_name = get_post_meta($product_id, 'product-fee-name', true);
            $product_fee_amount = get_post_meta($product_id, 'product-fee-amount', true);
            $product_fee_multiplier = get_post_meta($product_id, 'product-fee-multiplier', true);
        }

        // WooCommerce Bulk Discount Support
        if (WC_Dependencies_Product_Vendor::fpm_wc_bulk_discount_plugin_active_check()) {
            $_bulkdiscount_enabled = get_post_meta($product_id, '_bulkdiscount_enabled', true);
            $_bulkdiscount_text_info = get_post_meta($product_id, '_bulkdiscount_text_info', true);
            $_bulkdiscounts = (array) get_post_meta($product_id, '_bulkdiscounts', true);
        }
    }
}

$is_vendor = false;
$current_user_id = $current_vendor_id;
if (is_user_wcmp_vendor($current_user_id))
    $is_vendor = true;

// Shipping Class List
$product_shipping_class = get_terms('product_shipping_class', array('hide_empty' => 0));
$variation_shipping_option_array = array('-1' => __('Same as parent', 'dc-woocommerce-multi-vendor'));
$shipping_option_array = array();
foreach ($product_shipping_class as $product_shipping) {
    if ($is_vendor && apply_filters('wcmp_allowed_only_vendor_shipping_class', true)) {
        $vendor_id = get_woocommerce_term_meta($product_shipping->term_id, 'vendor_id', true);
        if (!$vendor_id) {
            //$variation_shipping_option_array[$product_shipping->term_id] = $product_shipping->name;
            //$shipping_option_array[$product_shipping->term_id] = $product_shipping->name;
        } else {
            if ($vendor_id == $current_user_id) {
                $variation_shipping_option_array[$product_shipping->term_id] = $product_shipping->name;
                $shipping_option_array[$product_shipping->term_id] = $product_shipping->name;
            }
        }
    } else {
        $variation_shipping_option_array[$product_shipping->term_id] = $product_shipping->name;
        $shipping_option_array[$product_shipping->term_id] = $product_shipping->name;
    }
}
$shipping_option_array['_no_shipping_class'] = __('No shipping class', 'dc-woocommerce-multi-vendor');

// Tax Class List
$tax_classes = WC_Tax::get_tax_classes();
$classes_options = array();
$variation_tax_classes_options['parent'] = __('Same as parent', 'dc-woocommerce-multi-vendor');
$variation_tax_classes_options[''] = __('Standard', 'dc-woocommerce-multi-vendor');
$tax_classes_options[''] = __('Standard', 'dc-woocommerce-multi-vendor');

if (!empty($tax_classes)) {

    foreach ($tax_classes as $class) {
        $tax_classes_options[sanitize_title($class)] = esc_html($class);
        $variation_tax_classes_options[sanitize_title($class)] = esc_html($class);
    }
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
    'author' => $current_vendor_id,
    'post_status' => array('publish'),
    'suppress_filters' => true
);
$products_array = get_posts($args);

$product_categories = get_terms('product_cat', 'orderby=name&hide_empty=0&parent=0');
$product_categories = apply_filters('wcmp_frontend_product_cat_filter', $product_categories);
global $wc_product_attributes;

// Array of defined attribute taxonomies
$attribute_taxonomies = wc_get_attribute_taxonomies();

?>
<div class="col-md-12">
    <form id="product_manager_form" class="woocommerce form-horizontal">
        <?php
        if (isset($_REQUEST['fpm_msg']) && !empty($_REQUEST['fpm_msg'])) {
            $WCMp_fpm_messages = get_frontend_product_manager_messages();
            ?>
            <div class="woocommerce-message" tabindex="-1"><?php echo $WCMp_fpm_messages[$_REQUEST['fpm_msg']]; ?></div>
            <?php

            
        }
       
        if (isset($_SESSION["fpm_msg"])) {
          
        ?>
            <div class="woocommerce-error" tabindex="-1"><?php echo $_SESSION["fpm_msg"]; ?></div>
            <?php
            unset($_SESSION["fpm_msg"]);
        }
        ?>
        <div class="frontend_product_manager_product_types">
            <?php
            $product_types = array();
            if (is_user_wcmp_vendor($current_user_id)) {
                if ($WCMp->vendor_caps->vendor_can('simple')) {
                    $product_types['simple'] = __('Simple', 'dc-woocommerce-multi-vendor');
                }
            } else {
                $product_types = array('simple' => __('Simple', 'dc-woocommerce-multi-vendor'));
            }
            $product_types = apply_filters('wcmp_product_types', $product_types);
            $custom_product_type_attribute = array();
            $disable_other_product_type = apply_filters('wcmp_disable_other_product_type', true);
            if($disable_other_product_type){
                $custom_product_type_attribute = array('disabled' => 'disabled');
            }
            if (!empty($product_types)) {
                if (!$product_id) {
                    $WCMp->wcmp_frontend_fields->wcmp_generate_form_field(array("product_type" => array('label' => __('Product Type', 'dc-woocommerce-multi-vendor'), 'type' => 'select', 'options' => $product_types, 'class' => 'regular-select', 'label_class' => 'pro_title', 'attributes' => $custom_product_type_attribute)));
                    if (is_user_wcmp_vendor($current_user_id)) {
                        if ($WCMp->vendor_caps->vendor_can('virtual') || $WCMp->vendor_caps->vendor_can('downloadable')) {
                            //$WCMp->wcmp_frontend_fields->wcmp_generate_form_field(array("is_virtual_downloadable" => array('type' => 'text')));
                        }
                        if ($WCMp->vendor_caps->vendor_can('virtual')) {
                            $WCMp->wcmp_frontend_fields->wcmp_generate_form_field(array("is_virtual" => array('label' => __('Virtual', 'dc-woocommerce-multi-vendor'),'label_class' =>apply_filters('auction_classes','regular-checkbox pro_ele simple non-external non-grouped  non-accommodation-booking non-variable non-variable-subscription non-redq_rental '), 'type' => 'checkbox', 'class' => apply_filters('auction_classes','regular-checkbox pro_ele simple non-external non-grouped  non-accommodation-booking non-variable non-variable-subscription non-redq_rental '), 'value' => 'enable', 'desc_class' => apply_filters('auction_classes','regular-checkbox pro_ele simple non-external non-grouped  non-accommodation-booking non-variable non-variable-subscription non-redq_rental '))));
                        }
                        if ($WCMp->vendor_caps->vendor_can('downloadable')) {
                            $WCMp->wcmp_frontend_fields->wcmp_generate_form_field(array("is_downloadable" => array('label' => __('Downloadable', 'dc-woocommerce-multi-vendor'), 'label_class' =>apply_filters('auction_classes','regular-checkbox pro_ele simple non-external non-grouped non-booking non-accommodation-booking non-variable non-variable-subscription non-redq_rental '), 'type' => 'checkbox', 'class' => apply_filters('auction_classes','regular-checkbox pro_ele simple non-external non-grouped non-booking non-accommodation-booking non-variable non-variable-subscription non-redq_rental '), 'value' => 'enable', 'desc_class' => apply_filters('auction_classes','pro_ele simple non-external non-grouped non-booking non-accommodation-booking non-variable non-variable-subscription non-redq_rental '))));
                        }
                    } else {
                        $WCMp->wcmp_frontend_fields->wcmp_generate_form_field(array("is_virtual_downloadable" => array('type' => 'text')));
                        $WCMp->wcmp_frontend_fields->wcmp_generate_form_field(array("is_virtual" => array('desc' => __('Virtual', 'dc-woocommerce-multi-vendor'), 'type' => 'checkbox', 'class' => apply_filters('auction_classes','regular-checkbox pro_ele simple non-external non-grouped  non-accommodation-booking non-variable non-variable-subscription non-redq_rental '), 'desc_class' => apply_filters('auction_classes','regular-checkbox pro_ele simple non-external non-grouped  non-accommodation-booking non-variable non-variable-subscription non-redq_rental '), 'value' => 'enable')));
                        $WCMp->wcmp_frontend_fields->wcmp_generate_form_field(array("is_downloadable" => array('desc' => __('Downloadable', 'dc-woocommerce-multi-vendor'), 'type' => 'checkbox', 'class' => apply_filters('auction_classes','regular-checkbox pro_ele simple non-external non-grouped non-booking non-accommodation-booking non-variable non-variable-subscription non-redq_rental '), 'desc_class' => apply_filters('auction_classes','pro_ele simple non-external non-grouped non-booking non-accommodation-booking non-variable non-variable-subscription non-redq_rental '), 'value' => 'enable')));
                    }
                } else {
                    $WCMp->wcmp_frontend_fields->wcmp_generate_form_field(array("product_type" => array('type' => 'hidden', 'value' => $product_type)));
                    if ($is_virtual) {
                        $WCMp->wcmp_frontend_fields->wcmp_generate_form_field(array("is_virtual" => array('type' => 'hidden', 'class' => 'is_virtual_hidden', 'value' => $is_virtual)));
                    }
                    if ($is_downloadable) {
                        $WCMp->wcmp_frontend_fields->wcmp_generate_form_field(array("is_downloadable" => array('type' => 'hidden', 'class' => 'is_downloadable_hidden', 'value' => $is_downloadable)));
                    }
                }
            } else {
                _e('You do not have enough permission to submit a new product. Please contact site administrator.', 'dc-woocommerce-multi-vendor');
            }
            ?>
        </div>
        <?php if($disable_other_product_type) : ?>
            <div class="add-product-backend">
                <p><?php _e('For other product types go to ', 'dc-woocommerce-multi-vendor'); ?><a href="<?php echo admin_url('post-new.php?post_type=product'); ?>"><?php _e('WP backend', 'dc-woocommerce-multi-vendor'); ?> <i class="wcmp-font ico-wp-backend-icon"></i></a></p>
            </div>
        <?php endif; ?>

        <div id="frontend_product_manager_accordion">
            <?php do_action('before_wcmp_fpm_template'); ?>
            <h3 class="pro_ele_head simple variable external grouped"><?php _e('General', 'dc-woocommerce-multi-vendor'); ?></h3>
            <div class="pro_ele_block simple variable external grouped">
                <?php
                $_wp_editor_settings = array('tinymce' => true);
                if (!$WCMp->vendor_caps->vendor_can('is_upload_files')) {
                    $_wp_editor_settings['media_buttons'] = false;
                }
                $_wp_editor_settings = apply_filters('wcmp_vendor_product_manager_wp_editor_settings', $_wp_editor_settings);
                //print_r($visibility);die;
                $vendor_show_sku = (apply_filters( 'wcmp_vendor_product_sku_enabled', true , get_current_vendor_id() )) ? 'text' : 'hidden';
                $WCMp->wcmp_frontend_fields->wcmp_generate_form_field(apply_filters('wcmp_fpm_fields_general', array("title" => array('label' => __('Title', 'dc-woocommerce-multi-vendor'), 'type' => 'text', 'class' => 'regular-text pro_ele simple variable external grouped', 'label_class' => 'pro_title pro_ele simple variable external grouped', 'value' => $title),
                    "sku" => array('label' => __('SKU', 'dc-woocommerce-multi-vendor'), 'type' => $vendor_show_sku, 'class' => (apply_filters("product_sku_hide",false)) ? 'regular-text pro_ele simple variable external grouped vendor_hidden non-booking non-accommodation-booking non-redq_rental' : 'regular-text pro_ele simple variable external grouped non-booking non-accommodation-booking non-redq_rental', 'label_class' => (apply_filters("product_sku_hide",false)) ? 'pro_title vendor_hidden simple variable external grouped non-booking non-accommodation-booking non-redq_rental' : 'pro_title simple variable external grouped non-booking non-accommodation-booking non-redq_rental', 'value' => $sku, 'hints' => __('SKU refers to a Stock-keeping unit, a unique identifier for each distinct product and service that can be purchased.', 'dc-woocommerce-multi-vendor')),
                    "regular_price" => array('label' => __('Price', 'dc-woocommerce-multi-vendor') . '(' . get_woocommerce_currency_symbol() . ')', 'type' => 'text', 'wrapper_class' =>'pro_ele simple external non-grouped non-booking non-accommodation-booking non-subscription non-variable non-variable-subscription non-redq_rental non-auction','class' => 'regular-text simple external non-grouped non-booking non-accommodation-booking non-subscription non-variable non-variable-subscription non-redq_rental non-auction', 'label_class' => 'pro_ele pro_title simple external non-grouped non-booking non-accommodation-booking non-subscription non-variable non-variable-subscription non-redq_rental non-auction', 'value' => $regular_price),
                    "sale_price" => array('label' => __('Sale Price', 'dc-woocommerce-multi-vendor') . '(' . get_woocommerce_currency_symbol() . ')', 'type' => 'text', 'wrapper_class' =>'pro_ele simple external non-grouped non-booking non-accommodation-booking non-variable non-variable-subscription non-redq_rental non-auction','class' => 'regular-text simple external non-grouped non-booking non-accommodation-booking non-variable non-variable-subscription non-redq_rental non-auction', 'label_class' => 'pro_ele pro_title simple external non-grouped non-booking non-accommodation-booking non-variable non-variable-subscription non-redq_rental non-auction', 'value' => $sale_price),
                    "sale_date_from" => array('label' => __('Sale Date From', 'dc-woocommerce-multi-vendor'), 'type' => 'text', 'placeholder' => __('From... YYYY-MM-DD', 'dc-woocommerce-multi-vendor'), 'wrapper_class' => 'pro_ele simple external non-grouped non-booking non-accommodation-booking non-variable non-variable-subscription non-redq_rental non-auction','class' => 'regular-text simple external non-grouped non-booking non-accommodation-booking non-variable non-variable-subscription non-redq_rental non-auction', 'label_class' => 'pro_ele pro_title simple external non-grouped non-accommodation-booking non-booking non-variable non-variable-subscription non-redq_rental non-auction', 'value' => $sale_date_from),
                    "sale_date_upto" => array('label' => __('Sale Date Upto', 'dc-woocommerce-multi-vendor'), 'type' => 'text', 'placeholder' => __('To... YYYY-MM-DD', 'dc-woocommerce-multi-vendor'), 'wrapper_class' => 'pro_ele simple external non-grouped non-booking non-accommodation-booking non-variable non-variable-subscription non-redq_rental non-auction','class' => 'regular-text simple external non-grouped non-booking non-accommodation-booking non-variable non-variable-subscription non-redq_rental non-auction', 'label_class' => 'pro_ele pro_title simple external non-grouped non-accommodation-booking non-booking non-variable non-variable-subscription non-redq_rental non-auction', 'value' => $sale_date_upto),
                    "visibility" => array('label' => __('Visibility', 'dc-woocommerce-multi-vendor'), 'type' => 'select', 'options' => array('visible' => __('Catalog/Search', 'dc-woocommerce-multi-vendor'), 'catalog' => __('Catalog', 'dc-woocommerce-multi-vendor'), 'search' => __('Search', 'dc-woocommerce-multi-vendor'), 'hidden' => __('Hidden', 'dc-woocommerce-multi-vendor')), 'class' => 'regular-select pro_ele simple variable external grouped', 'label_class' => 'pro_ele pro_title simple variable external grouped', 'value' => $visibility, 'hints' => __('Choose where this product should be displayed in your catalog. The product will always be accessible directly.', 'dc-woocommerce-multi-vendor')),
                    "excerpt" => array('label' => __('Short Description', 'dc-woocommerce-multi-vendor'), 'type' => 'wpeditor', 'class' => 'regular-textarea pro_ele simple variable external grouped', 'label_class' => 'pro_title grouped', 'value' => $excerpt, 'settings' => $_wp_editor_settings),
                    "description" => array('label' => __('Description', 'dc-woocommerce-multi-vendor'), 'type' => 'wpeditor', 'class' => 'regular-textarea pro_ele simple variable external grouped', 'label_class' => 'pro_title grouped', 'value' => $description, 'settings' => $_wp_editor_settings),
                    "pro_id" => array('type' => 'hidden', 'value' => $product_id)
                                ), $product_id));
  
                ?>

            </div>
            <?php do_action('after_wcmp_fpm_general', $product_id); ?>
            <?php if (!is_user_wcmp_vendor($current_user_id) || ( is_user_wcmp_vendor($current_user_id) && $WCMp->vendor_caps->vendor_can('downloadable') )) { ?>
                <h3 class="pro_ele_head simple subscription downlodable non-external non-grouped non-booking non-accommodation-booking non-variable non-variable-subscription non-redq_rental non-auction"><?php _e('Downloadable Options', 'dc-woocommerce-multi-vendor'); ?></h3>
                <div class="pro_ele_block simple subscription downlodable non-external non-grouped non-booking non-accommodation-booking non-variable non-variable-subscription non-redq_rental non-auction">

                    <?php
                    $WCMp->wcmp_frontend_fields->wcmp_generate_form_field(apply_filters('wcmp_fpm_fields_downloadable', array("downloadable_files" => array('label' => __('Files', 'dc-woocommerce-multi-vendor'), 'type' => 'multiinput', 'class' => 'regular-text pro_ele simple downlodable', 'label_class' => 'pro_title', 'value' => $downloadable_files, 'options' => array(
                                "name" => array('label' => __('Name', 'dc-woocommerce-multi-vendor'), 'type' => 'text', 'class' => 'regular-text pro_ele simple downlodable', 'label_class' => 'pro_ele pro_title simple downlodable'),
                                "file" => array('label' => __('File', 'dc-woocommerce-multi-vendor'), 'type' => 'upload', 'mime' => 'Uploads', 'class' => 'regular-text pro_ele simple downlodable', 'label_class' => 'pro_ele pro_title simple downlodable')
                            )),
                        "download_limit" => array('label' => __('Download Limit', 'dc-woocommerce-multi-vendor'), 'type' => 'number', 'value' => $download_limit, 'placeholder' => __('Unlimited', 'dc-woocommerce-multi-vendor'), 'class' => 'regular-text pro_ele simple external', 'label_class' => 'pro_ele pro_title simple downlodable', 'hints' => __('Leave blank for unlimited re-downloads.', 'dc-woocommerce-multivendor')),
                        "download_expiry" => array('label' => __('Download Expiry', 'dc-woocommerce-multi-vendor'), 'type' => 'number', 'value' => $download_expiry, 'placeholder' => __('Never', 'dc-woocommerce-multi-vendor'), 'class' => 'regular-text pro_ele simple external', 'label_class' => 'pro_ele pro_title simple downlodable', 'hints' => __('Enter the number of days before a download link expires, or leave blank.', 'dc-woocommerce-multivendor'))
                    )));
                    ?>

                </div>
            <?php } ?>
            <h3 class="pro_ele_head simple variable external grouped"><?php _e('Featured Image and Gallery', 'dc-woocommerce-multi-vendor'); ?></h3>
            <div class="pro_ele_block simple variable external grouped">

                <?php
                $WCMp->wcmp_frontend_fields->wcmp_generate_form_field(apply_filters('wcmp_fpm_fields_featured_images', array("featured_img" => array('label' => __('Featured Image', 'dc-woocommerce-multi-vendor'), 'type' => 'upload', 'class' => 'regular-text pro_ele simple variable external grouped', 'label_class' => 'pro_title', 'value' => $featured_img),
                    "gallery_img" => array('label' => __('Gallery Images', 'dc-woocommerce-multi-vendor'), 'type' => 'multiinput', 'class' => 'regular-text pro_ele simple variable external grouped', 'label_class' => 'pro_title', 'value' => $gallery_img_urls, 'options' => array(
                            "image" => array('label' => __('Image', 'dc-woocommerce-multi-vendor'), 'type' => 'upload', 'prwidth' => 125),
                        ))
                )));
                ?>

            </div>
            <h3 class="pro_ele_head simple variable external grouped"><?php _e('Category & Tags', 'dc-woocommerce-multi-vendor'); ?></h3>
            <div class="pro_ele_block simple variable external grouped">
                <div class="form-group">
                    <label class="control-label form-label col-sm-3"><?php _e('Categories', 'dc-woocommerce-multi-vendor'); ?></label>
                    <div class="col-md-6 col-sm-9">
                        <select id="product_cats" name="product_cats[]" class="form-control regular-select pro_ele_head simple variable external grouped" multiple="multiple" style="width: 100%;">
                            <?php
                            if ($product_categories) {
                                WCMpGenerateTaxonomyHTML('product_cat', $product_categories, $categories);
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <!--<p class="pro_title"><strong><?php _e('Categories', 'dc-woocommerce-multi-vendor'); ?></strong></p><label class="screen-reader-text" for="product_cats"><?php _e('Categories', 'dc-woocommerce-multi-vendor'); ?></label>-->

                <?php
                $product_taxonomies = get_object_taxonomies('product', 'objects');
                if (!empty($product_taxonomies)) {
                    foreach ($product_taxonomies as $product_taxonomy) {
                        if (!in_array($product_taxonomy->name, array('product_cat', 'product_tag'))) {
                            if ($product_taxonomy->public && $product_taxonomy->show_ui && $product_taxonomy->meta_box_cb) {
                                // Fetching Saved Values
                                $taxonomy_values_arr = array();
                                if ($product && !empty($product)) {
                                    $taxonomy_values = get_the_terms($product_id, $product_taxonomy->name);
                                    if (!empty($taxonomy_values)) {
                                        foreach ($taxonomy_values as $pkey => $ptaxonomy) {
                                            $taxonomy_values_arr[] = $ptaxonomy->term_id;
                                        }
                                    }
                                }
                                ?>
                                <div class="form-group">
                                    <label class="control-label form-label col-sm-3"><?php _e($product_taxonomy->label, 'dc-woocommerce-multi-vendor'); ?></label>
                                    <div class="col-md-6 col-sm-9">
                                        <!--<p class="pro_title"><strong><?php _e($product_taxonomy->label, 'dc-woocommerce-multi-vendor'); ?></strong></p><label class="screen-reader-text" for="<?php echo $product_taxonomy->name; ?>"><?php _e($product_taxonomy->label, 'dc-woocommerce-multi-vendor'); ?></label>-->
                                        <select id="<?php echo $product_taxonomy->name; ?>" name="product_custom_taxonomies[<?php echo $product_taxonomy->name; ?>][]" class="form-control regular-select product_taxonomies pro_ele simple variable external grouped" multiple="multiple" style="width: 100%;">
                                            <?php
                                            $product_taxonomy_terms = get_terms($product_taxonomy->name, 'orderby=name&hide_empty=0&parent=0');
                                            if ($product_taxonomy_terms) {
                                                WCMpGenerateTaxonomyHTML($product_taxonomy->name, $product_taxonomy_terms, $taxonomy_values_arr);
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                    }
                }

//                $WCMp->wcmp_frontend_fields->wcmp_generate_form_field(array("product_tags" => array('label' => __('Tags', 'dc-woocommerce-multi-vendor'), 'type' => 'textarea', 'class' => 'regular-textarea pro_ele simple variable external grouped', 'label_class' => 'pro_title', 'value' => $product_tags, 'desc' => __('Separate product tags with commas', 'dc-woocommerce-multi-vendor'))
//                ));
                ?>
                
                <div class="form-group tagsdiv">
                    <label class="control-label form-label col-sm-3"><?php _e('Tags', 'dc-woocommerce-multi-vendor'); ?></label>
                    <div class="col-md-6">
                        <select id="wcmp-product-tags" name="product_tags[]" multiple="multiple" style="width: 100%;">
                            <?php 
                            $tags = get_terms( 'product_tag', apply_filters('wcmp_add_product_tag_query_args', array( 'number' => 45, 'orderby' => 'count', 'order' => 'DESC' )));
                            if($tags) :
                                foreach ($tags as $tag) {
                                    echo '<option value="'.$tag->name.'" '.selected(in_array($tag->name, $product_tag_list), true, false).'>'.$tag->name.'</option>';
                                }
                            endif;
                            ?>
                        </select>
                    </div>
                </div>

                
            </div>
            <h3 class="pro_ele_head simple variable grouped non-external non-redq_rental non-accommodation-booking non-booking <?php if (apply_filters("vendor_product_inventory_hide",false)) echo ' vendor_hidden'; ?>"><?php _e('Inventory', 'dc-woocommerce-multi-vendor'); ?></h3>
            <div class="pro_ele_block simple variable grouped non-external non-redq_rental non-accommodation-booking non-booking <?php if (apply_filters("vendor_product_inventory_hide",false)) echo ' vendor_hidden'; ?>">

                <?php
                $non_manage_stock_ele = 'enable' === $manage_stock ? '' : 'non_manage_stock_ele';
                $WCMp->wcmp_frontend_fields->wcmp_generate_form_field(apply_filters('wcmp_fpm_fields_inventory', array("manage_stock" => array('label' => __('Manage Stock?', 'dc-woocommerce-multi-vendor'), 'type' => 'checkbox', 'class' => 'regular-checkbox pro_ele simple variable external manage_stock_ele non-auction', 'value' => 'enable', 'label_class' => 'pro_title checkbox_title pro_ele simple variable external manage_stock_ele non-auction', 'hints' => __('Enable stock management at product level', 'dc-woocommerce-multi-vendor'), 'dfvalue' => $manage_stock),
                    "stock_qty" => array('label' => __('Stock Qty', 'dc-woocommerce-multi-vendor'), 'type' => 'number', 'wrapper_class' =>'pro_ele '. $non_manage_stock_ele .' simple variable external non-auction','class' => 'regular-text pro_ele simple variable external '. $non_manage_stock_ele .' non-auction', 'label_class' => 'pro_title pro_ele_head '. $non_manage_stock_ele .' simple variable external non-auction', 'value' => $stock_qty, 'hints' => __('Stock quantity. If this is a variable product this value will be used to control stock for all variations, unless you define stock at variation level.', 'dc-woocommerce-multi-vendor')),
                    "backorders" => array('label' => __('Allow Backorders?', 'dc-woocommerce-multi-vendor'), 'type' => 'select', 'options' => array('no' => __('Do not Allow', 'dc-woocommerce-multi-vendor'), 'notify' => __('Allow, but notify customer', 'dc-woocommerce-multi-vendor'), 'yes' => __('Allow', 'dc-woocommerce-multi-vendor')), 'class' => 'regular-select pro_ele simple variable external ' . $non_manage_stock_ele .' non-auction','wrapper_class' =>'pro_ele '. $non_manage_stock_ele .' simple variable external non-auction', 'label_class' => 'pro_title pro_ele_head ' . $non_manage_stock_ele .' simple variable external non-auction', 'value' => $backorders, 'hints' => __('If managing stock, this controls whether or not backorders are allowed. If enabled, stock quantity can go below 0.', 'dc-woocommerce-multi-vendor')),
                    "stock_status" => array('label' => __('Stock Status', 'dc-woocommerce-multi-vendor'), 'type' => 'select', 'options' => array('instock' => __('In stock', 'dc-woocommerce-multi-vendor'), 'outofstock' => __('Out of stock', 'dc-woocommerce-multi-vendor')), 'class' => 'regular-select pro_ele simple grouped non-variable-subscription non-variable', 'label_class' => 'pro_ele pro_title simple grouped non-variable-subscription non-variable', 'value' => $stock_status, 'hints' => __('Controls whether or not the product is listed as "in stock" or "out of stock" on the frontend.', 'dc-woocommerce-multi-vendor')),
                    "sold_individually" => array('label' => __('Sold Individually', 'dc-woocommerce-multi-vendor'), 'type' => 'checkbox', 'value' => 'enable', 'class' => 'regular-checkbox pro_ele simple variable external non-auction', 'hints' => __('Enable this to only allow one of this item to be bought in a single order', 'dc-woocommerce-multi-vendor'), 'label_class' => 'pro_title checkbox_title pro_ele simple variable external manage_stock_ele non-auction', 'dfvalue' => $sold_individually)
                                ), $product_id));
                ?>

            </div>
            <h3 class="pro_ele_head simple non-external non-grouped variable nonvirtual <?php if (apply_filters("vendor_product_shipping_hide",false)) echo ' vendor_hidden'; ?>"><?php _e('Shipping', 'dc-woocommerce-multi-vendor'); ?></h3>
            <div class="pro_ele_block simple non-external non-grouped variable nonvirtual <?php if (apply_filters("vendor_product_shipping_hide",false)) echo ' vendor_hidden'; ?>">

                <?php
                $WCMp->wcmp_frontend_fields->wcmp_generate_form_field(apply_filters('wcmp_fpm_fields_shipping', array("weight" => array('label' => __('Weight', 'dc-woocommerce-multi-vendor') . ' (' . get_option('woocommerce_weight_unit', 'kg') . ')', 'type' => 'text', 'class' => 'regular-text pro_ele simple variable', 'label_class' => 'pro_title', 'value' => $weight),
                    "length" => array('label' => __('Length', 'dc-woocommerce-multi-vendor') . ' (' . get_option('woocommerce_dimension_unit', 'cm') . ')', 'type' => 'text', 'class' => 'regular-text pro_ele simple variable', 'label_class' => 'pro_title', 'value' => $length),
                    "width" => array('label' => __('Width', 'dc-woocommerce-multi-vendor') . ' (' . get_option('woocommerce_dimension_unit', 'cm') . ')', 'type' => 'text', 'class' => 'regular-text pro_ele simple variable', 'label_class' => 'pro_title', 'value' => $width),
                    "height" => array('label' => __('Height', 'dc-woocommerce-multi-vendor') . ' (' . get_option('woocommerce_dimension_unit', 'cm') . ')', 'type' => 'text', 'class' => 'regular-text pro_ele simple variable', 'label_class' => 'pro_title', 'value' => $height),
                    "shipping_class" => array('label' => __('Shipping Class', 'dc-woocommerce-multi-vendor'), 'type' => 'select', 'options' => $shipping_option_array, 'class' => 'regular-select pro_ele simple variable', 'label_class' => 'pro_title', 'value' => $shipping_class)
                                ), $product_id));
                ?>

            </div>
            <?php if (wc_tax_enabled()) { ?>
                <h3 class="pro_ele_head simple variable <?php if (apply_filters("vendor_product_tax_hide",false)) echo ' vendor_hidden'; ?>"><?php _e('Tax', 'dc-woocommerce-multi-vendor'); ?></h3>
                <div class="pro_ele_block simple variable <?php if (apply_filters("vendor_product_tax_hide",false)) echo ' vendor_hidden'; ?>">

                    <?php
                    $WCMp->wcmp_frontend_fields->wcmp_generate_form_field(apply_filters('wcmp_fpm_fields_tax', array(
                        "tax_status" => array('label' => __('Tax Status', 'dc-woocommerce-multi-vendor'), 'type' => 'select', 'options' => array('taxable' => __('Taxable', 'dc-woocommerce-multi-vendor'), 'shipping' => __('Shipping only', 'dc-woocommerce-multi-vendor'), 'none' => _x('None', 'Tax status', 'dc-woocommerce-multi-vendor')), 'class' => 'regular-select pro_ele simple variable', 'label_class' => 'pro_title', 'value' => $tax_status, 'hints' => __('Define whether or not the entire product is taxable, or just the cost of shipping it.', 'dc-woocommerce-multi-vendor')),
                        "tax_class" => array('label' => __('Tax Class', 'dc-woocommerce-multi-vendor'), 'type' => 'select', 'options' => $tax_classes_options, 'class' => 'regular-select pro_ele simple variable', 'label_class' => 'pro_title', 'value' => $tax_class, 'hints' => __('Choose a tax class for this product. Tax classes are used to apply different tax rates specific to certain types of product.', 'dc-woocommerce-multi-vendor'))
                    )));
                    ?>

                </div>
            <?php } ?>
        <h3 class="pro_ele_head simple variable external grouped <?php if (apply_filters("vendor_product_attributes_hide",false)) echo ' vendor_hidden'; ?>"><?php _e('Attributes', 'dc-woocommerce-multi-vendor'); ?></h3>
        <div class="pro_ele_block simple variable external grouped <?php if (apply_filters("vendor_product_attributes_hide",false)) echo ' vendor_hidden'; ?>">

            <div>
                <div class="form-group">
                    <div class="col-md-4">
                        <select name="fpm_attribute_taxonomy" class="fpm_attribute_taxonomy form-control">
                            <option value=""><?php _e('Custom product attribute', 'dc-woocommerce-multi-vendor'); ?></option>
                            <?php
                            if (!empty($attribute_taxonomies)) {
                                foreach ($attribute_taxonomies as $tax) {
                                    $attribute_taxonomy_name = wc_attribute_taxonomy_name($tax->attribute_name);
                                    $label = $tax->attribute_label ? $tax->attribute_label : $tax->attribute_name;
                                    echo '<option value="' . esc_attr($attribute_taxonomy_name) . '">' . esc_html($label) . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="button fpm_add_attribute btn btn-default"><?php _e('Add', 'dc-woocommerce-multi-vendor'); ?></button>
                    </div>
                </div>
          
                <?php
                $WCMp->wcmp_frontend_fields->wcmp_generate_form_field(apply_filters('wcmp_fpm_fields_attributes', array(
                    "attributes" => array('label' => __('Attributes', 'dc-woocommerce-multi-vendor'), 'type' => 'multiinput', 'class' => 'regular-text pro_ele simple variable external grouped', 'label_class' => 'pro_title', 'value' => $attributes, 'options' => array(
                            "term_name" => array('type' => 'hidden', 'label_class' => 'pro_title'),
                            "name" => array('label' => __('Name', 'dc-woocommerce-multi-vendor'), 'type' => 'text', 'class' => 'regular-text pro_ele simple variable external grouped', 'label_class' => 'pro_title'),
                            "value" => array('label' => __('Value(s):', 'dc-woocommerce-multi-vendor'), 'type' => 'textarea', 'class' => 'regular-textarea pro_ele simple variable external grouped', 'placeholder' => sprintf( esc_attr__( 'Enter some text, or some attributes by "%s" separating values.', 'dc-woocommerce-multi-vendor' ), WC_DELIMITER ), 'label_class' => 'pro_title'),
                            "is_visible" => array('label' => __('Visible on the product page', 'dc-woocommerce-multi-vendor'), 'type' => 'checkbox', 'value' => 'enable', 'class' => 'regular-checkbox pro_ele simple variable external grouped', 'label_class' => 'pro_title checkbox_title'),
                            "is_variation" => array('label' => __('Use as Variation', 'dc-woocommerce-multi-vendor'), 'type' => 'checkbox', 'value' => 'enable', 'class' => 'regular-checkbox pro_ele variable variable-subscription', 'label_class' => 'pro_title checkbox_title pro_ele variable variable-subscription'),
                            "tax_name" => array('type' => 'hidden'),
                            "is_taxonomy" => array('type' => 'hidden')
                        ))
                )));

                if (!empty($attributes_select_type)) {
                    $WCMp->wcmp_frontend_fields->wcmp_generate_form_field(apply_filters('product_simple_fields_attributes', array(
                        "select_attributes" => array('type' => 'multiinput', 'class' => 'regular-text pro_ele simple variable external grouped booking', 'label_class' => 'pro_title', 'value' => $attributes_select_type, 'options' => array(
                                "term_name" => array('type' => 'hidden'),
                                "name" => array('label' => __('Name', 'dc-woocommerce-multi-vendor'), 'type' => 'text', 'class' => 'regular-text pro_ele simple variable external grouped booking', 'label_class' => 'pro_title'),
                                "value" => array('label' => __('Value(s):', 'dc-woocommerce-multi-vendor'), 'type' => 'select', 'attributes' => array('multiple' => 'multiple', 'style' => 'width: 60%;'), 'class' => 'regular-select pro_ele simple variable external grouped booking', 'label_class' => 'pro_title'),
                                "is_visible" => array('label' => __('Visible on the product page', 'dc-woocommerce-multi-vendor'), 'type' => 'checkbox', 'value' => 'enable', 'class' => 'regular-checkbox pro_ele simple variable external grouped booking', 'label_class' => 'pro_title checkbox_title'),
                                "is_variation" => array('label' => __('Use as Variation', 'dc-woocommerce-multi-vendor'), 'type' => 'checkbox', 'value' => 'enable', 'class' => 'regular-checkbox pro_ele variable variable-subscription', 'label_class' => 'pro_title checkbox_title pro_ele variable variable-subscription'),
                                "tax_name" => array('type' => 'hidden'),
                                "is_taxonomy" => array('type' => 'hidden')
                            ))
                    )));
                }
                ?>
            </div>
        </div>
        <?php do_action('after_wcmp_fpm_attributes', $product_id); ?>

        <h3 class="pro_ele_head simple variable external grouped <?php if (apply_filters("vendor_product_linked_hide",false)) echo ' vendor_hidden'; ?>"><?php _e('Linked Products', 'dc-woocommerce-multi-vendor'); ?></h3>
        <div class="pro_ele_block simple variable external grouped <?php if (apply_filters("vendor_product_linked_hide",false)) echo ' vendor_hidden'; ?>">
            <div class="form-group">
<!--                <p class="upsell_ids pro_ele pro_title simple variable external grouped"><strong><?php _e('Up-sells', 'dc-woocommerce-multi-vendor'); ?></strong><span class="img_tip" data-desc="Up-sells are products which you recommend instead of the currently viewed product, for example, products that are more profitable or better quality or more expensive."></span></p>-->
                <label class="control-label form-label col-sm-3" for="upsell_ids"><?php _e('Up-sells', 'dc-woocommerce-multi-vendor'); ?><span class="img_tip" data-desc="<?php _e('Up-sells are products which you recommend instead of the currently viewed product, for example, products that are more profitable or better quality or more expensive.', 'dc-woocommerce-multi-vendor'); ?>"></span></label>
                <div class="col-md-6 col-sm-9">
                    <select id="upsell_ids" name="upsell_ids[]" class="form-control regular-select pro_ele simple variable external grouped" multiple="multiple" style="width: 100%;">
                        <?php
                        if ($products_array)
                            foreach ($products_array as $products_single) {
                                echo '<option value="' . esc_attr($products_single->ID) . '"' . selected(in_array($products_single->ID, $upsell_ids), true, false) . '>' . esc_html($products_single->post_title) . '</option>';
                            }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group non-grouped non-external">
            <!--<p class="crosssell_ids pro_ele pro_title simple variable external grouped"><strong><?php _e('Cross-sells', 'dc-woocommerce-multi-vendor'); ?></strong><span class="img_tip" data-desc="Cross-sells are products which you promote in the cart, based on the current product."></span></p>-->
                <label class="control-label form-label col-sm-3" for="crosssell_ids"><?php _e('Cross-sells', 'dc-woocommerce-multi-vendor'); ?><span class="img_tip" data-desc="<?php _e('Cross-sells are products which you promote in the cart, based on the current product.', 'dc-woocommerce-multi-vendor'); ?>"></span></label>
                <div class="col-md-6 col-sm-9">
                    <select id="crosssell_ids" name="crosssell_ids[]" class="form-control regular-select pro_ele simple variable external grouped" multiple="multiple" style="width: 100%;">
                        <?php
                        if ($products_array)
                            foreach ($products_array as $products_single) {
                                echo '<option value="' . esc_attr($products_single->ID) . '"' . selected(in_array($products_single->ID, $crosssell_ids), true, false) . '>' . esc_html($products_single->post_title) . '</option>';
                            }
                        ?>
                    </select>
                </div>
            </div>
        </div>

        <h3 class="pro_ele_head simple variable external grouped booking <?php if (apply_filters("vendor_product_advanced_hide",false)) echo ' vendor_hidden'; ?>"><?php _e('Advanced', 'dc-woocommerce-multi-vendor'); ?></h3>
        <div class="pro_ele_block simple variable external grouped booking <?php if (apply_filters("vendor_product_advanced_hide",false)) echo ' vendor_hidden'; ?>">

            <?php
            $WCMp->wcmp_frontend_fields->wcmp_generate_form_field(apply_filters('wcmp_fpm_manage_fields_advanced', array(
                "enable_reviews" => array('label' => __('Enable Reviews', 'dc-woocommerce-multi-vendor'), 'type' => 'checkbox', 'class' => 'regular-checkbox pro_ele simple variable external grouped', 'value' => 'enable', 'label_class' => 'pro_title checkbox_title grouped', 'dfvalue' => $enable_reviews),
                "menu_order" => array('label' => __('Menu Order', 'dc-woocommerce-multi-vendor'), 'type' => 'number', 'class' => 'regular-text pro_ele simple variable external grouped', 'label_class' => 'pro_title grouped', 'value' => $menu_order, 'hints' => __('Custom ordering position.', 'dc-woocommerce-multi-vendor')),
                "purchase_note" => array('label' => __('Purchase Note', 'dc-woocommerce-multi-vendor'), 'type' => 'textarea', 'class' => 'regular-textarea pro_ele simple variable grouped', 'label_class' => 'pro_ele pro_title simple variable grouped', 'value' => $purchase_note, 'hints' => __('Enter an optional note to send the customer after purchase.', 'dc-woocommerce-multi-vendor'))
                            ), $product_id));
            ?>

        </div>

        <?php if (WC_Dependencies_Product_Vendor::fpm_yoast_plugin_active_check()) { ?>
            <h3 class="pro_ele_head pro_ele_yoast_head simple variable external grouped booking"><?php _e('Yoast SEO', 'dc-woocommerce-multi-vendor'); ?></h3>
            <div class="pro_ele_block pro_ele_yoast_block simple variable external grouped booking">

                <?php
                $WCMp->wcmp_frontend_fields->wcmp_generate_form_field(apply_filters('product_manage_fields_yoast', array(
                    "yoast_wpseo_focuskw_text_input" => array('label' => __('Enter a focus keyword', 'dc-woocommerce-multi-vendor'), 'type' => 'text', 'class' => 'regular-text pro_ele simple variable external grouped booking', 'label_class' => 'pro_title pro_ele simple variable external grouped booking', 'value' => $yoast_wpseo_focuskw_text_input, 'hints' => __('It should appear in title and first paragraph of the copy.', 'dc-woocommerce-multi-vendor')),
                    "yoast_wpseo_metadesc" => array('label' => __('Meta description', 'dc-woocommerce-multi-vendor'), 'type' => 'textarea', 'class' => 'regular-textarea pro_ele simple variable external grouped booking', 'label_class' => 'pro_ele pro_title simple variable external grouped booking', 'value' => $yoast_wpseo_metadesc, 'hints' => __('It should not be more than 156 characters.', 'dc-woocommerce-multi-vendor'))
                )));
                ?>

            </div>
        <?php } ?>

        <?php if (WC_Dependencies_Product_Vendor::fpm_wc_tabs_lite_plugin_active_check()) { ?>
            <h3 class="pro_ele_head simple variable external grouped booking"><?php _e('Custom Tabs', 'dc-woocommerce-multi-vendor'); ?></h3>
            <div class="pro_ele_block simple variable external grouped booking">

                <?php
                $WCMp->wcmp_frontend_fields->wcmp_generate_form_field(apply_filters('product_manage_fields_wc_tabs_lite', array(
                    "product_tabs" => array('label' => __('Tabs', 'dc-woocommerce-multi-vendor'), 'type' => 'multiinput', 'class' => 'pro_ele simple variable external grouped booking', 'label_class' => 'pro_title', 'value' => $product_tabs, 'options' => array(
                            "title" => array('label' => __('Title', 'dc-woocommerce-multi-vendor'), 'type' => 'text', 'class' => 'regular-text pro_ele simple variable external grouped booking', 'label_class' => 'pro_title pro_ele simple variable external grouped booking', 'hints' => __('Required for tab to be visible', 'dc-woocommerce-multi-vendor')),
                            "content" => array('label' => __('Content', 'dc-woocommerce-multi-vendor'), 'type' => 'textarea', 'class' => 'regular-textarea pro_ele simple variable external grouped booking', 'label_class' => 'pro_ele pro_title simple variable external grouped booking', 'placeholder' => __('HTML or Text to display ...', 'dc-woocommerce-multi-vendor'))
                        ))
                )));
                ?>
            </div>
        <?php } ?>

        <?php if (WC_Dependencies_Product_Vendor::fpm_wc_product_fees_plugin_active_check()) { ?>
            <h3 class="pro_ele_head simple variable external grouped booking"><?php _e('Product Fees', 'dc-woocommerce-multi-vendor'); ?></h3>
            <div class="pro_ele_block simple variable external grouped booking">

                <?php
                $WCMp->wcmp_frontend_fields->wcmp_generate_form_field(apply_filters('product_manage_fields_yoast', array(
                    "product-fee-name" => array('label' => __('Fee Name', 'dc-woocommerce-multi-vendor'), 'type' => 'text', 'class' => 'regular-text pro_ele simple variable external grouped booking', 'label_class' => 'pro_title pro_ele simple variable external grouped booking', 'value' => $product_fee_name, 'hints' => __('This will be shown at the checkout description the added fee.', 'dc-woocommerce-multi-vendor')),
                    "product-fee-amount" => array('label' => __('Fee Amount', 'dc-woocommerce-multi-vendor') . '(' . get_woocommerce_currency_symbol() . ')', 'type' => 'text', 'class' => 'regular-text pro_ele simple variable external grouped booking', 'label_class' => 'pro_ele pro_title simple variable external grouped booking', 'value' => $product_fee_amount, 'hints' => __('Enter a monetary decimal without any currency symbols or thousand separator. This field also accepts percentages.', 'dc-woocommerce-multi-vendor')),
                    "product-fee-multiplier" => array('label' => __('Multiple Fee by Quantity', 'dc-woocommerce-multi-vendor'), 'type' => 'checkbox', 'class' => 'regular-checkbox pro_ele simple variable external grouped booking', 'value' => 'yes', 'label_class' => 'pro_title checkbox_title simple variable external grouped booking', 'hints' => __('Multiply the fee by the quantity of this product that is added to the cart.', 'dc-woocommerce-multi-vendor'), 'dfvalue' => $product_fee_multiplier),
                )));
                ?>

            </div>
        <?php } ?>

        <?php if (WC_Dependencies_Product_Vendor::fpm_wc_bulk_discount_plugin_active_check()) { ?>
            <h3 class="pro_ele_head simple variable external grouped booking"><?php _e('Bulk Discount', 'dc-woocommerce-multi-vendor'); ?></h3>
            <div class="pro_ele_block simple variable external grouped booking">

                <?php
                $WCMp->wcmp_frontend_fields->wcmp_generate_form_field(apply_filters('product_manage_fields_yoast', array(
                    "_bulkdiscount_enabled" => array('label' => __('Bulk Discount enabled', 'dc-woocommerce-multi-vendor'), 'type' => 'checkbox', 'class' => 'regular-checkbox pro_ele simple variable external grouped booking', 'value' => 'yes', 'label_class' => 'pro_title checkbox_title simple variable external grouped booking', 'dfvalue' => $_bulkdiscount_enabled),
                    "_bulkdiscount_text_info" => array('label' => __('Bulk discount special offer text in product description', 'dc-woocommerce-multi-vendor'), 'type' => 'textarea', 'class' => 'regular-textarea pro_ele simple variable external grouped booking', 'label_class' => 'pro_ele pro_title simple variable external grouped booking', 'value' => $_bulkdiscount_text_info),
                    "_bulkdiscounts" => array('label' => __('Discount Rules', 'dc-woocommerce-multi-vendor'), 'type' => 'multiinput', 'custom_attributes' => array('limit' => 5), 'class' => 'regular-text pro_ele simple variable external grouped booking', 'label_class' => 'pro_title', 'value' => $_bulkdiscounts, 'options' => array(
                            "quantity" => array('label' => __('Quantity (min.)', 'dc-woocommerce-multi-vendor'), 'type' => 'number', 'class' => 'regular-text pro_ele simple variable external grouped booking', 'label_class' => 'pro_title'),
                            "discount" => array('label' => __('Discount (%)', 'dc-woocommerce-multi-vendor'), 'type' => 'number', 'class' => 'regular-text pro_ele simple variable external grouped booking', 'label_class' => 'pro_title'),
                        ))
                )));
                ?>

            </div>
        <?php } ?>

        <?php if (WC_Dependencies_Product_Vendor::fpm_mappress_plugin_active_check()) { ?>

        <?php } ?>

        <?php if (WC_Dependencies_Product_Vendor::fpm_toolset_plugin_active_check()) { ?>
            <?php
            include_once( WPCF_EMBEDDED_ABSPATH . '/includes/fields-post.php' );
            $product_post = get_post();
            $product_post->post_type = 'product';
            $field_groups = wpcf_admin_post_get_post_groups_fields($product_post);

            if (!empty($field_groups)) {
                foreach ($field_groups as $field_group_index => $field_group) {
                    //If Access plugin activated
                    if (function_exists('wpcf_access_register_caps')) {
                        //If user can't view own profile fields
                        if (!current_user_can('view_fields_in_edit_page_' . $field_group['slug'])) {
                            continue;
                        }
                        //If user can modify current group in own profile
                        if (!current_user_can('modify_fields_in_edit_page_' . $field_group['slug'])) {
                            continue;
                        }
                    }
                    if (isset($group['__show_meta_box']) && $group['__show_meta_box'] == false)
                        continue;
                    $field_group_load = Types_Field_Group_Post_Factory::load($field_group['slug']);
                    if (null === $field_group_load)
                        continue;

                    // WooCommerce Filter Views discard
                    if ($field_group['slug'] == 'woocommerce-views-filter-fields')
                        continue;

                    if (!empty($field_group['fields'])) {
                        ?>
                        <h3 class="pro_ele_head simple variable external grouped booking"><?php echo $field_group['name']; ?></h3>
                        <div class="pro_ele_block simple variable external grouped booking">

                            <?php
                            if (!empty($field_group['fields'])) {
                                foreach ($field_group['fields'] as $field_group_field) {
                                    $field_value = '';
                                    if ($product_id)
                                        $field_value = get_post_meta($product_id, $field_group_field['meta_key'], true);
                                    switch ($field_group_field['type']) {
                                        case 'url':
                                        case 'phone':
                                        case 'textfield':
                                        case 'google_address':
                                            $WCMp->wcmp_frontend_fields->wcmp_generate_form_field(array($field_group_field['meta_key'] => array('label' => $field_group_field['name'], 'desc' => $field_group_field['description'], 'name' => 'wpcf[' . $field_group_field['meta_key'] . ']', 'type' => 'text', 'class' => 'regular-text pro_ele simple variable external grouped booking', 'label_class' => 'pro_title', 'value' => $field_value)));
                                            break;

                                        case 'numeric':
                                            $WCMp->wcmp_frontend_fields->wcmp_generate_form_field(array($field_group_field['meta_key'] => array('label' => $field_group_field['name'], 'desc' => $field_group_field['description'], 'name' => 'wpcf[' . $field_group_field['meta_key'] . ']', 'type' => 'number', 'class' => 'regular-text pro_ele simple variable external grouped booking', 'label_class' => 'pro_title', 'value' => $field_value)));
                                            break;

                                        case 'wysiwyg':
                                        case 'textarea':
                                            $WCMp->wcmp_frontend_fields->wcmp_generate_form_field(array($field_group_field['meta_key'] => array('label' => $field_group_field['name'], 'desc' => $field_group_field['description'], 'name' => 'wpcf[' . $field_group_field['meta_key'] . ']', 'type' => 'textarea', 'class' => 'regular-textarea pro_ele simple variable external grouped booking', 'label_class' => 'pro_title', 'value' => $field_value)));
                                            break;

                                        case 'date':
                                            $WCMp->wcmp_frontend_fields->wcmp_generate_form_field(array($field_group_field['meta_key'] => array('label' => $field_group_field['name'], 'desc' => $field_group_field['description'], 'name' => 'wpcf[' . $field_group_field['meta_key'] . ']', 'type' => 'text', 'placeholder' => __('YYYY-MM-DD', 'dc-woocommerce-multi-vendor'), 'class' => 'regular-text pro_ele dc_datepicker simple variable external grouped booking', 'label_class' => 'pro_title', 'value' => $field_value)));
                                            break;

                                        case 'timepicker':
                                            $WCMp->wcmp_frontend_fields->wcmp_generate_form_field(array($field_group_field['meta_key'] => array('label' => $field_group_field['name'], 'desc' => $field_group_field['description'], 'name' => 'wpcf[' . $field_group_field['meta_key'] . ']', 'type' => 'time', 'class' => 'regular-text pro_ele simple variable external grouped booking', 'label_class' => 'pro_title', 'value' => $field_value)));
                                            break;

                                        case 'checkbox':
                                            $WCMp->wcmp_frontend_fields->wcmp_generate_form_field(array($field_group_field['meta_key'] => array('label' => $field_group_field['name'], 'desc' => $field_group_field['description'], 'name' => 'wpcf[' . $field_group_field['meta_key'] . ']', 'type' => 'checkbox', 'class' => 'regular-checkbox pro_ele simple variable external grouped booking', 'label_class' => 'pro_title checkbox_title', 'value' => $field_group_field['data']['set_value'], 'dfvalue' => $field_value)));
                                            break;

                                        case 'radio':
                                            $radio_opt_vals = array();
                                            if (!empty($field_group_field['data']['options'])) {
                                                foreach ($field_group_field['data']['options'] as $radio_option) {
                                                    if (!empty($radio_option) && isset($radio_option['value']) && isset($radio_option['title'])) {
                                                        $radio_opt_vals[$radio_option['value']] = $radio_option['title'];
                                                    }
                                                }
                                            }
                                            $WCMp->wcmp_frontend_fields->wcmp_generate_form_field(array($field_group_field['meta_key'] => array('label' => $field_group_field['name'], 'desc' => $field_group_field['description'], 'name' => 'wpcf[' . $field_group_field['meta_key'] . ']', 'type' => 'radio', 'class' => 'regular-select pro_ele', 'label_class' => 'pro_title', 'options' => $radio_opt_vals, 'value' => $field_value)));
                                            break;

                                        case 'select':
                                            $select_opt_vals = array('' => __('--- not set ---', 'dc-woocommerce-multi-vendor'));
                                            if (!empty($field_group_field['data']['options'])) {
                                                foreach ($field_group_field['data']['options'] as $select_option) {
                                                    if (!empty($select_option) && isset($select_option['value']) && isset($select_option['title'])) {
                                                        $select_opt_vals[$select_option['value']] = $select_option['title'];
                                                    }
                                                }
                                            }
                                            $WCMp->wcmp_frontend_fields->wcmp_generate_form_field(array($field_group_field['meta_key'] => array('label' => $field_group_field['name'], 'desc' => $field_group_field['description'], 'name' => 'wpcf[' . $field_group_field['meta_key'] . ']', 'type' => 'select', 'class' => 'regular-select pro_ele simple variable external grouped booking', 'label_class' => 'pro_title', 'options' => $select_opt_vals, 'value' => $field_value)));
                                            break;

                                        case 'image':
                                            $WCMp->wcmp_frontend_fields->wcmp_generate_form_field(array($field_group_field['meta_key'] => array('label' => $field_group_field['name'], 'desc' => $field_group_field['description'], 'name' => 'wpcf[' . $field_group_field['meta_key'] . ']', 'type' => 'upload', 'class' => 'pro_ele simple variable external grouped booking', 'label_class' => 'pro_title', 'value' => $field_value)));
                                            break;

                                        case 'file':
                                        case 'audio':
                                        case 'video':
                                            $WCMp->wcmp_frontend_fields->wcmp_generate_form_field(array($field_group_field['meta_key'] => array('label' => $field_group_field['name'], 'desc' => $field_group_field['description'], 'name' => 'wpcf[' . $field_group_field['meta_key'] . ']', 'type' => 'upload', 'mime' => 'Uploads', 'class' => 'pro_ele simple variable external grouped booking', 'label_class' => 'pro_title', 'value' => $field_value)));
                                            break;
                                    }
                                }
                            }
                            ?>

                        </div>
                        <?php
                    }
                }
            }
            ?>
        <?php } ?>

        <?php do_action('end_wcmp_fpm_products_manage', $product_id); ?>

        <?php do_action('after_wcmp_fpm_template'); ?> 
</div>
<?php if (!empty($product_types)) {
 ?>
    <div id="product_manager_submit" class="wcmp-action-container">
        <input type="submit" class="btn btn-default" name="submit-data" value="<?php _e('Submit', 'dc-woocommerce-multi-vendor'); ?>" id="pruduct_manager_submit_button" />
        <?php if (empty($pro_id) || (!empty($pro_id) && (get_post_status((int) $pro_id) == 'draft'))) { ?>
            <input type="submit" class="btn btn-default" name="draft-data" value="<?php _e('Draft', 'dc-woocommerce-multi-vendor'); ?>" id="pruduct_manager_draft_button" />
        <?php } ?>
    </div>
<?php } ?>
</form>
</div>
<?php
do_action('wcmp-frontend-product-manager_template');
