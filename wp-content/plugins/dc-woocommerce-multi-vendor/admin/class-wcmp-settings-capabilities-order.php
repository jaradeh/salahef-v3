<?php

class WCMp_Settings_Capabilities_Order {

    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;
    private $tab;
    private $subsection;

    /**
     * Start up
     */
    public function __construct($tab, $subsection) {
        $this->tab = $tab;
        $this->subsection = $subsection;
        $this->options = get_option("wcmp_{$this->tab}_{$this->subsection}_settings_name");
        $this->settings_page_init();
    }

    /**
     * Register and add settings
     */
    public function settings_page_init() {
        global $WCMp;
        $settings_tab_options = array("tab" => "{$this->tab}",
            "ref" => &$this,
            "subsection" => "{$this->subsection}",
            "sections" => array(
                "vendor_order" => array(
                    "title" => __('Order Notes', 'dc-woocommerce-multi-vendor'),
                    "fields" => array(
                        "is_vendor_view_comment" => array('title' => __('View Order Note', 'dc-woocommerce-multi-vendor'), 'type' => 'checkbox', 'id' => 'is_vendor_view_comment', 'label_for' => 'is_vendor_view_comment', 'name' => 'is_vendor_view_comment', 'text' => __('Vendor can see order notes.', 'dc-woocommerce-multi-vendor'), 'value' => 'Enable'), // Checkbox
                        "is_vendor_submit_comment" => array('title' => __('Add Order Note', 'dc-woocommerce-multi-vendor'), 'type' => 'checkbox', 'id' => 'is_vendor_submit_comment', 'label_for' => 'is_vendor_submit_comment', 'name' => 'is_vendor_submit_comment', 'text' => __('Vendor can add order notes.', 'dc-woocommerce-multi-vendor'), 'value' => 'Enable'), // Checkbox
                    )
                )
            )
        );

        $WCMp->admin->settings->settings_field_withsubtab_init(apply_filters("settings_{$this->tab}_{$this->subsection}_tab_options", $settings_tab_options));
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function wcmp_capabilities_order_settings_sanitize($input) {
        global $WCMp;
        $new_input = array();

        $hasError = false;

        if (isset($input['is_vendor_view_comment'])) {
            $new_input['is_vendor_view_comment'] = sanitize_text_field($input['is_vendor_view_comment']);
        }

        if (isset($input['is_vendor_submit_comment'])) {
            $new_input['is_vendor_submit_comment'] = sanitize_text_field($input['is_vendor_submit_comment']);
        }

        if (!$hasError) {
            add_settings_error(
                    "wcmp_{$this->tab}_{$this->subsection}_settings_name", esc_attr("wcmp_{$this->tab}_{$this->subsection}_settings_admin_updated"), __('Vendor Settings Updated', 'dc-woocommerce-multi-vendor'), 'updated'
            );
        }
        return apply_filters("settings_{$this->tab}_{$this->subsection}_tab_new_input", $new_input, $input);
    }

}
