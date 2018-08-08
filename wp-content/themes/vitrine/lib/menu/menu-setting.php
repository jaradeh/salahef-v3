<?php

require_once(EPICO_THEME_LIB . '/forms/fieldtemplate.php');
require_once(EPICO_THEME_LIB . '/forms/post-options-provider.php');
require_once(EPICO_THEME_LIB . '/menu/nav-menu-handler.php');

class epico_Menu
{
    private $postType;

    function __construct()
    {

        $this->postType = "nav_menu_item";

        add_filter('nav_menu_item_additional_fields', array(&$this, 'epico_ShowMetaBox'), 10, 5 );

        add_filter('wp_edit_nav_menu_walker', array(&$this, 'epico_NavMenuHanler'));

        /* Save post meta on the 'save_post' hook. */
        add_action( 'save_post', array( &$this, 'epico_SaveData' ), 10, 2 );

        add_action('admin_enqueue_scripts', array(&$this, 'epico_InitScripts'));

    }

    function epico_SaveData($post_id = false, $post = false)
    {


        /* Verify the nonce before proceeding. */
        $nonce = THEME_NAME_SEO . '_post_nonce';

        if ( !isset( $_POST[$nonce] ) || !wp_verify_nonce( $_POST[$nonce], 'theme-post-meta-form' ) )
            return $post_id;

        // check auto-save
        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
            return $post_id;


        if( $post->post_type != $this->postType || !current_user_can('edit_post', $post_id))
            return $post_id;


        //CRUD Operation
        foreach( $this->epico_GetOptionsForStore() as $key => $settings )
        {
            $uniqueKey = $key . "-" . $post_id;//Unique key used for access fields of each menu item
            $postedVal = isset( $_POST[$uniqueKey] ) ? $_POST[$uniqueKey] : '';
            $val       = get_post_meta( $post_id, $key, false );

            if(is_array($postedVal))
            {
                //Insert
                if ( !empty($postedVal) && empty($val) )
                {
                    add_post_meta( $post_id, $key, $postedVal );

                }
                //Delete
                elseif ( !empty($val) && empty($postedVal) )
                {
                    delete_post_meta( $post_id, $key );

                    //Delete the attachment as well
                    if($settings['type'] == 'upload')
                    {
                        epico_delete_attachment($val);
                    }
                }
                //Update
                elseif ( !empty($val) && !empty($postedVal) && $postedVal != $val )
                {
                    update_post_meta( $post_id, $key, $postedVal );
                }

            }
            else
            {
                //Insert
                if ( $postedVal != '' && empty($val) )
                {
                    add_post_meta( $post_id, $key, $postedVal );

                }
                //Delete
                elseif ( !empty($val) && $postedVal == '' )
                {
                    delete_post_meta( $post_id, $key );

                    //Delete the attachment as well
                    if($settings['type'] == 'upload')
                    {
                        epico_delete_attachment($val);
                    }
                }
                //Update
                elseif ( $postedVal != '' && !empty($val) &&  $postedVal != $val )
                {
                    update_post_meta( $post_id, $key, $postedVal );
                }


            }
        }

        return $post_id;
    }

    private function epico_GetOptionsForStore()
    {
        $options = $this->epico_GetOptions();
        $values  = array();

        foreach($options as $key => $field)
        {
            $ignore = epico_array_value('dontsave', epico_array_value('meta', $field, array()), false);

            if($ignore)
                continue;

            $values[$key] = $field;
        }

        return $values;
    }

    private function epico_GetOptions()
    {
        $fields = array(
            'hide-in-menu-switch' => array(
                'type' => 'checkbox',
                'label'=> esc_html__('Hide in the menu', 'vitrine'),
                'class' => 'hide-in-menu',
                'description'=> esc_html__('If you check, this item  will be hidden.(Used in one-page style)', 'vitrine'),
                'value' => "1",
            ),
            'badge-label' => array(
                'type' => 'text',
                'label'=> esc_html__('Badge label', 'vitrine'),
                'placeholder'=> esc_html__('eg: new', 'vitrine'),
                'value' => "",
            ),
            'badge-bg-color' => array(
                'type'   => 'color',
                'label'  => esc_html__('Badge background color', 'vitrine'),
                'value'  => '#ccc'
            ),
            'is-mega-menu' => array(
                'type' => 'checkbox',
                'class' => 'is-mega-menu',
                'label'=> esc_html__('Mega Menu', 'vitrine'),
                'description'=> esc_html__('Transforms the menu to mega menu', 'vitrine'),
                'value' => "1",
            ),
            'is-bottom-line' => array(
                'type' => 'checkbox',
                'class' => 'special-last-child',
                'label'=> esc_html__('Show the last child in special style', 'vitrine'),
                'description'=> esc_html__('It is displayed in bottom of mega menu, use description field of last child to show subtitle', 'vitrine'),
                'value' => "1",
            ),
            'bg-image' => array(
                'label' => esc_html__('Background Image', 'vitrine' ),
                'description' => esc_html__('Set an image for the mega menu', 'vitrine' ),
                'type' => 'upload',
                'referer' => 'ep-portfolio-image',
                'meta'  => array('array'=>false),
            ),
            'nav-menu-icon' => array(
                'type'  => 'icon',
                'label' => esc_html__('Icon', 'vitrine'),
                'class' => 'menu-icon-container',
                'flags' => 'attribute',//CSV
            ),
        );

        return $fields;

    }

    //this option add id of menu to each options because we need unique field names in menu page for each menu item
    private function epico_MakeUniqueOptions($id)
    {
        $options = $this->epico_GetOptions();

        $new_options;

        foreach($options as $key => $field)
        {
            $new_options[$key. "-" . $id] = $field;
        }

        return $new_options;
    }

    function epico_ShowMetaBox($new_fields, $item_output, $item, $depth, $args )
    {
        global $post;
        $post = $item;
        $options = $this->epico_MakeUniqueOptions($item->ID);

        $form = new epico_FieldTemplate(new epico_PostOptionsProvider(), dirname(__FILE__));
        return $form-> epico_GetTemplate('meta-form', $options);
    }

    function epico_NavMenuHanler()
    {
        //return the name of class that handles nav-menu output in wp-admin
        return 'epico_Walker_Nav_Menu_Edit';

    }

    function epico_InitScripts()
    {
        global $post;
        if( !$post || $post->post_type != $this->postType )
            return;

        $this->epico_RegisterScripts();
        $this->epico_EnqueueScripts();
    }

    private function epico_RegisterScripts()
    {
        wp_register_script('jquery-easing', EPICO_THEME_LIB_URI  .'/admin/scripts/jquery.easing.1.3.js', array('jquery'), '1.3.0');

        wp_register_style( 'nouislider', EPICO_THEME_LIB_URI . '/admin/css/jquery.nouislider.min.css', false, '7.0.10', 'screen' );
        wp_register_script('nouislider', EPICO_THEME_LIB_URI  .'/admin/scripts/jquery.nouislider.min.js', array('jquery'), '7.0.10');

        wp_register_script('colorpickerAlpha', EPICO_THEME_LIB_URI  .'/admin/scripts/wp-color-picker-alpha.js',array( 'wp-color-picker' ), '1.2.2');

        wp_register_style( 'chosen', EPICO_THEME_LIB_URI . '/admin/css/chosen.css', false, '1.0.0', 'screen' );
        wp_register_script('chosen', EPICO_THEME_LIB_URI  .'/admin/scripts/chosen.jquery.min.js', array('jquery'), '1.0.0');

        wp_register_style( 'theme-admin-css', EPICO_THEME_LIB_URI . '/admin/css/style.css', false, '1.0.0', 'screen' );
        wp_register_script('theme-admin-script', EPICO_THEME_LIB_URI  .'/admin/scripts/admin.js', array('jquery'), '1.0.0');
    }

    private function epico_EnqueueScripts()
    {
        wp_enqueue_script('hoverIntent');
        wp_enqueue_script('jquery-easing');

        wp_enqueue_style('nouislider');
        wp_enqueue_script('nouislider');

        //Include wpcolorpicker + its patch to support alpha chanel
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );
        wp_enqueue_script( 'colorpickerAlpha' );

        wp_enqueue_style('chosen');
        wp_enqueue_script('chosen');

        wp_enqueue_style('theme-admin-css');
        wp_enqueue_script('theme-admin-script');
        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
        wp_enqueue_style('thickbox');
    }
}

new epico_Menu();
