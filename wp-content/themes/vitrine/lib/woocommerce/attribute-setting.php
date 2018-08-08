<?php

require_once(EPICO_THEME_LIB . '/forms/fieldtemplate.php');


/********************************
* output function is taken from class-wc-meta-box-product-data.php of woocommerce plugin (woocommerce/includes/admin/meta-boxes/class-wc-meta-box-product-data.php)
* add_attribute function is originally taken from class-wc-ajax.php  of woocommerce plugin ( woocommerce/includes/class-wc-ajax.php)
* those function changed a bit to handle new attributes
********************************/

class epico_Attribute_settings
{

    function __construct()
    {

        //Add scripts,styles
        add_action('admin_print_scripts-post-new.php', array( &$this, 'epico_InitScripts' ));

        //Add new image attribute types to WC
        add_filter('product_attributes_type_selector', array( $this,'epico_add_woocommerce_attribute_types'));

        //Add image attribute UI to product metabox
        add_action( 'woocommerce_product_option_terms', array( $this,'epico_add_woocommerce_attribute_image_ui'), 10, 2 );

        //save extra data for new attribute types
        add_action( 'woocommerce_process_product_meta',  array( 'epico_Attribute_settings', 'epico_save_extra_metadata'), 30, 2 );

        //change handler of saving attributes ajax request to Save extra data for new attributes
        remove_action( 'wp_ajax_woocommerce_save_attributes', array( 'WC_AJAX', 'save_attributes' ));
        add_action( 'wp_ajax_woocommerce_save_attributes', array( $this,'epico_save_attributes'));
    }

    // define new attribute type
    function epico_GetOptions()
    {
        //This options define new type of product attribute
        $fields = array(
            'image' => array(
                'label' => esc_html__('Image', 'vitrine' ),
                'description' => esc_html__('Image allows adding image per pre-configured terms and show them in slider.', 'vitrine') ,
                'type' => 'upload',
                'referer' => 'ep-attr-image',
                'meta'  => array('array'=>false),
            ),
        );

        return $fields;

    }

    function epico_add_woocommerce_attribute_image_ui($attribute_taxonomy, $i) {
        global $post, $thepostid, $product_object;

        $settings = $this->epico_GetOptions();

        if(!isset($_POST['taxonomy']))
        {
            $product_id       = $thepostid;
            $product_object   = wc_get_product( $product_id );
            $attributes = $product_object->get_attributes( 'edit' );

            
            if(isset($attributes['pa_' . $attribute_taxonomy->attribute_name]))
            {
                $attribute = $attributes['pa_' . $attribute_taxonomy->attribute_name];
            }
            else
            {
                return;
            }
                       
            $taxonomy   = $attribute->get_taxonomy();
            
            $attribute_taxonomy = $attribute->get_taxonomy_object();
            if ( 'image' == $attribute_taxonomy->attribute_type ) {

                if($thepostid)
                {
                    $attr_values = get_post_meta( $thepostid, esc_attr( $attribute->get_taxonomy() ) . '_extravalue',true);
                }

                $args = array(
                    'orderby'    => 'name',
                    'hide_empty' => 0,
                );
                $all_terms = get_terms( $attribute->get_taxonomy(), apply_filters( 'woocommerce_product_attribute_terms', $args ) );

                if ( $all_terms ) {

                    $form = new epico_FieldTemplate(new epico_PostOptionsProvider(), dirname(__FILE__));
                    $form->epico_SetWorkingDirectory(epico_path_combine(EPICO_THEME_LIB, 'forms/templates'));

                    ?>
                    <div class="fields-container">
                    <?php
                    
                    

                    foreach ( $all_terms as $term ) {
                        $key = "" . $term->slug;

                        $container_class = "field-container image-attr-field-container";
                        $input_value_name = "attribute_values";
                        $input_extra_value_name = "attribute_extra_values";

                        $options = $attribute->get_options();
                        $options = ! empty( $options ) ? $options : array();
                        

                        //Hide deleted terms
                        //Add X before names to prevent from saving it's values
                        if(selected( in_array( $term->term_id, $options ), true, false ) == '' && !empty($options)) {
                        //if(selected( has_term( absint( $term->term_id ), $attribute->get_taxonomy(), $thepostid ), true, false ) == '' && $thepostid != 0) {
                            $container_class = "field-container image-attr-field-container hide-field";
                            $input_value_name = "x_attribute_values";
                            $input_extra_value_name = "x_attribute_extra_values";
                        }

                        $val = $original_val = '';
                        if(isset($attr_values[$term->slug]))
                        {

                            // get image for upload field
                            if('image' == $attribute_taxonomy->attribute_type)
                            {
                                $original_val = $attr_values[$term->slug];//id of image
                                $val = wp_get_attachment_url($attr_values[$term->slug]); //url of image
                            }   
                            
                        }
                        
                        $settings_key = array_keys($settings)[0]; // get the key of $i'th field
                        $settings[$settings_key]['label'] = $term->name;
                        ?>
                        <div class="<?php echo esc_attr($container_class); ?>" data-attr-number="<?php echo esc_attr($i); ?>">
                            <a class="attr_remove"></a>
                            <input type="hidden" name="<?php echo esc_attr($input_value_name); ?>[<?php echo esc_attr($i); ?>][]" value="<?php echo esc_attr($term->term_id); ?>">
                            <input type="hidden" name="<?php echo esc_attr($input_extra_value_name); ?>[<?php echo esc_attr($i); ?>][<?php echo esc_attr($term->slug); ?>]" value="<?php echo esc_attr($original_val); ?>">
                            
                        <?php

                        echo $form->GetField($key, $settings['image'], array('val' => $val));

                        ?>
                        </div>
                        <?php

                    }
                    ?>

                    </div>
                    <?php
                }

                ?>
                <a href="#" class="button plus add_all_attr"><?php esc_html_e( 'Add all terms', 'vitrine' ); ?></a>
                <a href="#" class="button plus add_new_attr"><?php esc_html_e( 'Add new terms', 'vitrine' ); ?></a>
        <?php
            }
            
        }
        else
        {
            $taxonomy   = $_POST['taxonomy'];


            $i             = absint( $_POST['i'] );
            $metabox_class = array();
            $attribute     = new WC_Product_Attribute();

            $attribute->set_id( wc_attribute_taxonomy_id_by_name( sanitize_text_field( $_POST['taxonomy'] ) ) );
            $attribute->set_name( sanitize_text_field( $_POST['taxonomy'] ) );
            $attribute->set_visible( apply_filters( 'woocommerce_attribute_default_visibility', 1 ) );
            $attribute->set_variation( apply_filters( 'woocommerce_attribute_default_is_variation', 0 ) );

            if ( $attribute->is_taxonomy() ) {
                $metabox_class[] = 'taxonomy';
                $metabox_class[] = $attribute->get_name();
            }

            $settings = $this->epico_GetOptions();


            $attribute_taxonomy = $attribute->get_taxonomy_object();



            if ( 'image' == $attribute_taxonomy->attribute_type ) {

                if($thepostid)
                {
                    $attr_values = get_post_meta( $thepostid, esc_attr( $attribute->get_taxonomy() ) . '_extravalue',true);
                }

                $args = array(
                    'orderby'    => 'name',
                    'hide_empty' => 0,
                );
                $all_terms = get_terms( $attribute->get_taxonomy(), apply_filters( 'woocommerce_product_attribute_terms', $args ) );


                if ( $all_terms ) {

                    $form = new epico_FieldTemplate(new epico_PostOptionsProvider(), dirname(__FILE__));
                    $form->epico_SetWorkingDirectory(epico_path_combine(EPICO_THEME_LIB, 'forms/templates'));

                    ?>
                    <div class="fields-container">
                    <?php

                    foreach ( $all_terms as $term ) {
                        $key = "" . $term->slug;

                        $container_class = "field-container image-attr-field-container";
                        $input_value_name = "attribute_values";
                        $input_extra_value_name = "attribute_extra_values";

                        $options = $attribute->get_options();
                        $options = ! empty( $options ) ? $options : array();

                        //Hide deleted terms
                        //Add X before names to prevent from saving it's values
                        if(selected( in_array( $term->term_id, $options ), true, false ) == '' && !empty($options)) {
                        //if(selected( has_term( absint( $term->term_id ), $attribute->get_taxonomy(), $thepostid ), true, false ) == '' && $thepostid != 0) {
                            $container_class = "field-container image-attr-field-container hide-field";
                            $input_value_name = "x_attribute_values";
                            $input_extra_value_name = "x_attribute_extra_values";
                        }

                        $val = $original_val = '';
                        if(isset($attr_values[$term->slug]))
                        {

                            // get image for upload field
                            if('image' == $attribute_taxonomy->attribute_type)
                            {
                                $original_val = $attr_values[$term->slug];//id of image
                                $val = wp_get_attachment_url($attr_values[$term->slug]); //url of image
                            }   
                            
                        }
                        
                        $settings_key = array_keys($settings)[0]; // get the key of $i'th field
                        $settings[$settings_key]['label'] = $term->name;
                        ?>
                        <div class="<?php echo esc_attr($container_class); ?>">
                            <a class="attr_remove"></a>
                            <input type="hidden" name="<?php echo esc_attr($input_value_name); ?>[<?php echo esc_attr($i); ?>][]" value="<?php echo esc_attr($term->term_id); ?>">
                            <input type="hidden" name="<?php echo esc_attr($input_extra_value_name); ?>[<?php echo esc_attr($i); ?>][<?php echo esc_attr($term->slug); ?>]" value="<?php echo esc_attr($original_val); ?>">
                            
                        <?php

                        echo $form->GetField($key, $settings['image'], array('val' => $val));

                        ?>
                        </div>
                        <?php

                    }
                    ?>
                    </div>
                    <?php
                }

                ?>
                <a href="#" class="button plus add_all_attr"><?php esc_html_e( 'Add all terms', 'vitrine' ); ?></a>
                <a href="#" class="button plus add_new_attr"><?php esc_html_e( 'Add new terms', 'vitrine' ); ?></a>
        <?php
            }
        }        
    }


    //Add new type to woocommerce attribute types
    function epico_add_woocommerce_attribute_types($types) {
		$types['image'] = __( 'Image', 'vitrine' );
		return $types;
    }


    /**
     * Save attributes via ajax.
     */
    public static function epico_save_attributes() {

        check_ajax_referer( 'save-attributes', 'security' );

        if ( ! current_user_can( 'edit_products' ) ) {
            wp_die( -1 );
        }

        parse_str( $_POST['data'], $data );

        $attributes   = WC_Meta_Box_Product_Data::prepare_attributes( $data );
        $product_id   = absint( $_POST['post_id'] );
        $product_type = ! empty( $_POST['product_type'] ) ? wc_clean( $_POST['product_type'] ) : 'simple';
        $classname    = WC_Product_Factory::get_product_classname( $product_id, $product_type );
        $product      = new $classname( $product_id );

        error_log(print_r($attributes,true));

        $product->set_attributes( $attributes );
        $product->save();


        //Epico code
        self::epico_save_extra_metadata($_POST['post_id'],null);
        //End of epico code

        wp_die();
    }


    /**
     * Save meta box data
     */
    public static function epico_save_extra_metadata( $post_id, $post ) {
        global $wpdb;

        if ( isset( $_POST['attribute_names'] ) && isset( $_POST['attribute_values'] ) || isset( $_POST['data'] )) {


            if(isset( $_POST['data'] ))
            {
                parse_str( $_POST['data'], $data );
                
                $attribute_names  = $data['attribute_names'];
                $attribute_values = $data['attribute_values'];
                $attribute_extra_values = array();
                if(isset($data['attribute_extra_values']))
                {
                    $attribute_extra_values = $data['attribute_extra_values']; // extra metadata such as image id                
                }
            }
            else
            {
                $attribute_names  = $_POST['attribute_names'];
                $attribute_values = $_POST['attribute_values'];
                $attribute_extra_values = array();
                if(isset($_POST['attribute_extra_values']))
                {
                    $attribute_extra_values = $_POST['attribute_extra_values']; // extra metadata such as image id                
                }
            }


            $attribute_names_max_key = max( array_keys( $attribute_names ) );

            for ( $i = 0; $i <= $attribute_names_max_key; $i++ ) {
                if ( empty( $attribute_names[ $i ] ) ) {
                    continue;
                }

                    if ( taxonomy_exists( $attribute_names[ $i ] ) && isset( $attribute_values[ $i ] ) ) {
                        if ( isset( $attribute_extra_values[ $i ] ) ) {

                            $slug_extra_data = array();
                            //posted values are extra data & key is term slug
                            if ( is_array( $attribute_extra_values[ $i ] ) ) {

                                $extravalues = $attribute_extra_values[ $i ];

                                foreach( $extravalues as $key => $value ) {
                                    $slug_extra_data[$key] = $value;
                                }
                            }


                            update_post_meta($post_id, wc_clean( $attribute_names[ $i ] ) . '_extravalue', $slug_extra_data);

                        }
                        else
                        {
                            update_post_meta($post_id, wc_clean( $attribute_names[ $i ] ) . '_extravalue' ,'');

                        }
                    }
            }
        }

    }

    function epico_InitScripts()
    {
        $this->epico_RegisterScripts();
        $this->epico_EnqueueScripts();
    }

    protected function epico_RegisterScripts()
    {
        wp_register_style( 'theme-admin-css', EPICO_THEME_LIB_URI . '/admin/css/style.css', false, '1.0.0', 'screen' );
        wp_register_script('theme-admin-script', EPICO_THEME_LIB_URI  .'/admin/scripts/admin.js', array('jquery'), '1.0.0');
    }

    protected function epico_EnqueueScripts()
    {
        wp_enqueue_style('theme-admin-css');
        wp_enqueue_script('theme-admin-script');
    }
}

new epico_Attribute_settings();
