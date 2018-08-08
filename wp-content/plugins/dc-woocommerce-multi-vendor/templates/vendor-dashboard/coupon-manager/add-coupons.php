<?php
global $WCMp;

$current_vendor_id = apply_filters('wcmp_current_loggedin_vendor_id', get_current_user_id());
$current_user_id = $current_vendor_id;
$wcmp_capabilities_settings_name = get_option('wcmp_capabilities_product_settings_name');
// If vendor does not have product submission cap then show message
if (is_user_logged_in() && is_user_wcmp_vendor($current_user_id) && !isset($wcmp_capabilities_settings_name['is_submit_coupon'])) {
    _e('You do not have enough permission to submit a new coupon. Please contact site administrator.', 'dc-woocommerce-multi-vendor');
    return;
}

$coupon_id = 0;
$title = '';
$description = '';
$discount_type = '';
$coupon_amount = 0;
$free_shipping = '';
$expiry_date = '';

$minimum_amount = '';
$maximum_amount = '';
$individual_use = '';
$exclude_sale_items = '';
$product_ids = '';
$exclude_product_ids = '';
$product_categories = '';
$exclude_product_categories = '';
$customer_email = '';

$usage_limit = '';
$limit_usage_to_x_items = '';
$usage_limit_per_user = '';

if (!empty($couponid)) {
    $coupon_post = get_post($couponid);
    // Fetching Coupon Data
    if ($coupon_post && !empty($coupon_post)) {
        $coupon_id = $couponid;
        $coupon_obj = new WC_Coupon($coupon_id);
        $title = $coupon_post->post_title;
        $description = $coupon_post->post_excerpt;
        $discount_type = get_post_meta($coupon_id, 'discount_type', true);
        $coupon_amount = get_post_meta($coupon_id, 'coupon_amount', true);
        $free_shipping = ( get_post_meta($coupon_id, 'free_shipping', true) == 'yes' ) ? 'enable' : '';
        $expiry_date = $coupon_obj->get_date_expires() ? $coupon_obj->get_date_expires()->date('Y-m-d') : ''; //get_post_meta($coupon_id, 'expiry_date', true);

        $minimum_amount = get_post_meta($coupon_id, 'minimum_amount', true);
        $maximum_amount = get_post_meta($coupon_id, 'maximum_amount', true);
        $individual_use = ( get_post_meta($coupon_id, 'individual_use', true) == 'yes' ) ? 'enable' : '';
        $exclude_sale_items = ( get_post_meta($coupon_id, 'exclude_sale_items', true) == 'yes' ) ? 'enable' : '';
        $product_ids = get_post_meta($coupon_id, 'product_ids', true);
        $exclude_product_ids = get_post_meta($coupon_id, 'exclude_product_ids', true);
        $product_categories = (array) get_post_meta($coupon_id, 'product_categories', true);
        $exclude_product_categories = (array) get_post_meta($coupon_id, 'exclude_product_categories', true);
        $customer_email = get_post_meta($coupon_id, 'customer_email', true);

        if ($customer_email)
            $customer_email = implode(',', $customer_email);

        $usage_limit = get_post_meta($coupon_id, 'usage_limit', true);
        $limit_usage_to_x_items = get_post_meta($coupon_id, 'limit_usage_to_x_items', true);
        $usage_limit_per_user = get_post_meta($coupon_id, 'usage_limit_per_user', true);
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
    'author' => $current_user_id,
    'post_status' => array('publish'),
    'suppress_filters' => true
);
$products_array = get_posts($args);
$categories = get_terms('product_cat', 'orderby=name&hide_empty=0');
?>
<div class="col-md-12">
    <form id="coupon_manager_form" class="form-horizontal">
        <?php
        if (isset($_REQUEST['fpm_msg']) && !empty($_REQUEST['fpm_msg'])) {
            $WCMp_fpm_coupon_messages = get_frontend_coupon_manager_messages();
            ?>
            <div class="woocommerce-message" tabindex="-1"><?php echo $WCMp_fpm_coupon_messages[$_REQUEST['fpm_msg']]; ?></div>
            <?php
        }
        ?>

        <div id="frontend_coupon_manager_accordion">
            <h3 class="pro_ele_head"><?php _e('General', 'dc-woocommerce-multi-vendor'); ?></h3>
            <div class="pro_ele_block">
                <?php
                $WCMp->wcmp_frontend_fields->wcmp_generate_form_field(array("title" => array('label' => __('Title', 'dc-woocommerce-multi-vendor'), 'type' => 'text', 'class' => 'regular-text pro_ele', 'label_class' => 'pro_title pro_ele', 'value' => $title),
                    "description" => array('label' => __('Description', 'dc-woocommerce-multi-vendor'), 'type' => 'textarea', 'class' => 'regular-textarea pro_ele', 'label_class' => 'pro_title', 'value' => $description),
                    "discount_type" => array('label' => __('Discount Type', 'dc-woocommerce-multi-vendor'), 'type' => 'select', 'options' => apply_filters( "wcmp_vendor_frontend_add_coupon_types", array('fixed_product' => __('Fixed Product Discount', 'dc-woocommerce-multi-vendor'))), 'class' => 'regular-select pro_ele', 'label_class' => 'pro_ele pro_title', 'value' => $discount_type),
                    "coupon_amount" => array('label' => __('Coupon Amount', 'dc-woocommerce-multi-vendor'), 'type' => 'text', 'class' => 'regular-text pro_ele', 'label_class' => 'pro_ele pro_title', 'value' => $coupon_amount),
                    "expiry_date" => array('label' => __('Coupon expiry date', 'dc-woocommerce-multi-vendor'), 'type' => 'text', 'placeholder' => __('YYYY-MM-DD', 'dc-woocommerce-multi-vendor'), 'class' => 'regular-text pro_ele', 'label_class' => 'pro_ele pro_title', 'value' => $expiry_date),
                    "coupon_id" => array('type' => 'hidden', 'value' => $coupon_id)
                ));
                ?>
            </div>
            <h3 class="pro_ele_head"><?php _e('Usage Restriction', 'dc-woocommerce-multi-vendor'); ?></h3>
            <div class="pro_ele_block">
                <?php
                $WCMp->wcmp_frontend_fields->wcmp_generate_form_field(array("minimum_amount" => array('label' => __('Minimum spend', 'dc-woocommerce-multi-vendor'), 'type' => 'text', 'placeholder' => __('No Minimum', 'dc-woocommerce-multi-vendor'), 'class' => 'regular-text pro_ele', 'label_class' => 'pro_ele pro_title', 'value' => $minimum_amount),
                    "maximum_amount" => array('label' => __('Maximum spend', 'dc-woocommerce-multi-vendor'), 'type' => 'text', 'placeholder' => __('No Maximum', 'dc-woocommerce-multi-vendor'), 'class' => 'regular-text pro_ele', 'label_class' => 'pro_ele pro_title', 'value' => $maximum_amount),
                    "individual_use" => array('label' => __('Individual use only', 'dc-woocommerce-multi-vendor'), 'type' => 'checkbox', 'class' => 'regular-checkbox pro_ele', 'value' => 'enable', 'label_class' => 'pro_title checkbox_title', 'hints' => __('Check this box if the coupon cannot be used in conjunction with other coupons.', 'dc-woocommerce-multi-vendor'), 'dfvalue' => $individual_use),
                    "exclude_sale_items" => array('label' => __('Exclude sale items', 'dc-woocommerce-multi-vendor'), 'type' => 'checkbox', 'class' => 'regular-checkbox pro_ele', 'value' => 'enable', 'label_class' => 'pro_title checkbox_title', 'hints' => __('Check this box if the coupon should not apply to items on sale. Per-item coupons will only work if the item is not on sale. Per-cart coupons will only work if there are no sale items in the cart.', 'dc-woocommerce-multi-vendor'), 'dfvalue' => $exclude_sale_items)
                ));
                ?>
                <div class="form-group">
                    <label class="control-label col-sm-3" for="product_ids"><?php _e('Products', 'dc-woocommerce-multi-vendor'); ?></label>
                    <div class="col-md-6 col-sm-9">
                        <select id="product_ids" name="product_ids[]" class="form-control regular-select pro_ele" multiple="multiple" style="width: 100%;">
                            <?php
                            $product_ids = array_filter(array_map('absint', explode(',', $product_ids)));

                            if ($products_array)
                                foreach ($products_array as $products_single) {
                                    echo '<option value="' . esc_attr($products_single->ID) . '"' . selected(in_array($products_single->ID, $product_ids), true, false) . '>' . esc_html($products_single->post_title) . '</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3" for="exclude_product_ids"><?php _e('Exclude products', 'dc-woocommerce-multi-vendor'); ?></label>
                    <div class="col-md-6 col-sm-9">
                        <select id="exclude_product_ids" name="exclude_product_ids[]" class="form-control regular-select pro_ele" multiple="multiple" style="width: 100%;">
                            <?php
                            $exclude_product_ids = array_filter(array_map('absint', explode(',', $exclude_product_ids)));

                            if ($products_array)
                                foreach ($products_array as $products_single) {
                                    echo '<option value="' . esc_attr($products_single->ID) . '"' . selected(in_array($products_single->ID, $exclude_product_ids), true, false) . '>' . esc_html($products_single->post_title) . '</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
<!--                <p class="product_categories pro_ele pro_title"><strong>Product categories</strong><span class="img_tip" data-desc="A product must be in this category for the coupon to remain valid or, for 'Product Discounts', products in these categories will be discounted."></span></p>-->
                    <label class="control-label col-sm-3" for="product_categories"><?php _e('Product categories', 'dc-woocommerce-multi-vendor'); ?></label>
                    <div class="col-md-6 col-sm-9">
                        <select id="product_categories" name="product_categories[]" class="form-control regular-select pro_ele" multiple="multiple" style="width: 100%;">
                            <?php
                            $category_ids = (array) $product_categories;
                            $categories = get_terms('product_cat', 'orderby=name&hide_empty=0');

                            if ($categories)
                                foreach ($categories as $cat) {
                                    echo '<option value="' . esc_attr($cat->term_id) . '"' . selected(in_array($cat->term_id, $category_ids), true, false) . '>' . esc_html($cat->name) . '</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
<!--                <p class="exclude_product_categories pro_ele pro_title"><strong>Exclude categories</strong><span class="img_tip" data-desc="Product must not be in this category for the coupon to remain valid or, for 'Product Discounts', products in these categories will not be discounted."></span></p>-->
                    <label class="control-label col-sm-3" for="exclude_product_categories"><?php _e('Exclude categories', 'dc-woocommerce-multi-vendor'); ?></label>    
                    <div class="col-md-6 col-sm-9">
                        <select id="exclude_product_categories" name="exclude_product_categories[]" class="form-control regular-select pro_ele" multiple="multiple" style="width: 100%;">
                            <?php
                            $category_ids = (array) $exclude_product_categories;
                            $categories = get_terms('product_cat', 'orderby=name&hide_empty=0');

                            if ($categories)
                                foreach ($categories as $cat) {
                                    echo '<option value="' . esc_attr($cat->term_id) . '"' . selected(in_array($cat->term_id, $category_ids), true, false) . '>' . esc_html($cat->name) . '</option>';
                                }
                            ?>                        
                        </select>
                    </div>
                </div>
                <?php
                $WCMp->wcmp_frontend_fields->wcmp_generate_form_field(array(
                    "customer_email" => array('label' => __('Email restrictions', 'dc-woocommerce-multi-vendor'), 'type' => 'text', 'placeholder' => __('No restictions', 'dc-woocommerce-multi-vendor'), 'class' => 'regular-text pro_ele', 'label_class' => 'pro_ele pro_title', 'hints' => __('List of allowed emails to check against the customer\'s billing email when an order is placed. Separate email addresses with commas.', 'dc-woocommerce-multi-vendor'), 'value' => $customer_email)
                ));
                ?>
            </div>
            <h3 class="pro_ele_head simple variable external"><?php _e('Usage Limit', 'dc-woocommerce-multi-vendor'); ?></h3>
            <div class="pro_ele_block simple variable external">
                <?php
                $WCMp->wcmp_frontend_fields->wcmp_generate_form_field(array("usage_limit" => array('label' => __('Usage limit per coupon', 'dc-woocommerce-multi-vendor'), 'type' => 'number', 'placeholder' => __('Unlimited usage', 'dc-woocommerce-multi-vendor'), 'class' => 'regular-text regular-text-limit pro_ele', 'label_class' => 'pro_ele pro_title limit_title', 'attributes' => array('min' => 0, 'steps' => 1), 'hints' => __('How many times this coupon can be used before it is void.', 'dc-woocommerce-multi-vendor'), 'value' => $usage_limit),
                    "limit_usage_to_x_items" => array('label' => __('Limit usage to X items', 'dc-woocommerce-multi-vendor'), 'type' => 'number', 'placeholder' => __('Apply to all qualifying items in cart', 'dc-woocommerce-multi-vendor'), 'class' => 'regular-text regular-text-limit pro_ele', 'label_class' => 'pro_ele pro_title limit_title', 'attributes' => array('min' => 0, 'steps' => 1), 'hints' => __('The maximum number of individual items this coupon can apply to when using product discounts. Leave blank to apply to all qualifying items in cart.', 'dc-woocommerce-multi-vendor'), 'value' => $limit_usage_to_x_items),
                    "usage_limit_per_user" => array('label' => __('Usage limit per user', 'dc-woocommerce-multi-vendor'), 'type' => 'number', 'placeholder' => __('Unlimited usage', 'dc-woocommerce-multi-vendor'), 'class' => 'regular-text regular-text-limit pro_ele', 'label_class' => 'pro_ele pro_title limit_title', 'attributes' => array('min' => 0, 'steps' => 1), 'hints' => __('How many times this coupon can be used by an invidual user. Uses billing email for guests, and user ID for logged in users.', 'dc-woocommerce-multi-vendor'), 'value' => $usage_limit_per_user)
                ));
                ?>
            </div>
        </div>
        <div id="coupon_manager_submit" class="wcmp-action-container">
            <input type="submit" name="submit-data" value="<?php _e('Submit', 'dc-woocommerce-multi-vendor'); ?>" id="coupon_manager_submit_button" class="btn btn-default" />
            <?php if (empty($couponid) || (!empty($couponid) && (get_post_status($couponid) == 'draft'))) { ?>
                <input type="submit" name="draft-data" value="<?php _e('Draft', 'dc-woocommerce-multi-vendor'); ?>" id="coupon_manager_draft_button" class="btn btn-default" />
            <?php } ?>
        </div>
    </form>
</div>
<?php
do_action('wcmp-frontend-coupon-manager_template');
