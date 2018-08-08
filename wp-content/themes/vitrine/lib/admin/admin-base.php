<?php

require_once('mediabox-strings.php');
include_once('form-settings.php');

class epico_ThemeAdmin
    {
    function __construct() {
        $this->epico_Activation();

        add_action("admin_menu", array( &$this, "epico_Setup_Menus"));
        add_action( 'admin_bar_menu', array(&$this,'epico_toolbar_link_theme_setting'), 999 );
        add_action('admin_enqueue_scripts', array(&$this, 'epico_Admin_Scripts'));
        add_action('admin_init', array(&$this, 'epico_Admin_Init') );
        add_action('after_setup_theme', array(&$this, 'epico_After_Setup') );
        add_action('switch_theme', array(&$this, 'epico_After_Deactivate') );
        add_action('wp_ajax_theme_save_options', array(&$this, 'epico_Save_Options'));
    }

    function epico_toolbar_link_theme_setting( $wp_admin_bar ) {
        $args = array(
            'id'    => 'theme_setting',
            'title' => esc_html__('Epico Theme Setting','vitrine'),
            'parent'=> 'site-name',
            'href'  => admin_url("admin.php?page=theme_settings"),
            'meta'  => array( 'class' => 'my-toolbar-page' ),
        );
        $wp_admin_bar->add_node( $args );
    }
    
    function epico_Save_Options()
    {
        $options = get_option( OPTIONS_KEY );

        foreach($options as $key => $value)
        {
            $newVal = isset($_POST[$key]) ? $_POST[$key] : '';
            $options[$key] = $newVal;
        }

        update_option(OPTIONS_KEY, $options);

        echo 'OK';
        die(); // this is required to return a proper result
    }


    function epico_After_Deactivate()
    {
        update_option('theme_initialised',false);

    }

    function epico_After_Setup()
    {
        //Use an option to run this function just 1 time adn after theme activation
        // we can't use directly after_switch_theme hook because we need activated plguins before that
		if(!is_admin())
			return;
		
        $theme_initialised = get_option('theme_initialised');

        if($theme_initialised != true)
        {
            if ( class_exists('WPBakeryVisualComposerAbstract') || class_exists('YITH_WCWL'))
                update_option('theme_initialised',true);

            if ( class_exists('WPBakeryVisualComposerAbstract')) {

                //Enable Visual composer in portfolio,page,post and product
                $pt_array = vc_editor_post_types();

                if(is_array($pt_array))
                {

                    if(!in_array("post",$pt_array))
                        $pt_array[] = "post";

                    if(!in_array("portfolio",$pt_array))
                        $pt_array[] = "portfolio";

                    if(!in_array("page",$pt_array))
                        $pt_array[] = "page";

                    if(!in_array("product",$pt_array))
                        $pt_array[] = "product";

                    vc_editor_set_post_types($pt_array );
                }
            }

            //Set wishlist position after add to cart button
            if (class_exists('YITH_WCWL')) {

                $wishlist_position = get_option( 'yith_wcwl_button_position' );

                if($wishlist_position != 'add-to-cart')
                {
                    update_option('yith_wcwl_button_position', 'add-to-cart');
                }
            }
        }
		
        //Initialize default options
        $options  = get_option( OPTIONS_KEY );
        $defaults = epico_admin_get_defaults();
		
        // Are our options saved in the DB?
        if ( false !== $options )
        {
            $changed = false;
            //Add new keys if any
            foreach($defaults as $key => $value)
            {
                if(!array_key_exists($key, $options))
                {
                    //Add default value
                    $options[$key] = $value;
                    $changed = true;
                }
            }
			
            //Check if any key removed from defaults
            foreach($options as $key => $value)
            {
                if(!array_key_exists($key, $defaults))
                {
                    //Remove the option
                    unset($options[$key]);
                    $changed = true;
                }
            }

            if($changed)
                update_option(OPTIONS_KEY, $options);

            return;
        }

        // If not, we'll save our default options
        add_option( OPTIONS_KEY, $defaults );
    }

    function epico_Activation()
    {
        // Redirect To Theme Options Page on Activation
        if (isset($_GET['activated'])){
            wp_redirect(admin_url("admin.php?page=theme_settings"));
        }
    }

    function epico_Setup_Menus() {

        add_menu_page(THEME_NAME, 'Theme Settings', 'manage_options',
        'theme_settings', array(&$this, 'epico_Admin_Page'),'dashicons-icon-gear',60);
    }

    function epico_Admin_Init()
    {
        if(in_array($GLOBALS['pagenow'], array('media-upload.php', 'async-upload.php'))) {
            // Now we'll replace the 'Insert into Post Button' inside Thickbox
            add_filter( 'gettext', array(&$this, 'epico_Replace_Thickbox_Text')  , 1, 3 );
        }

        wp_enqueue_style( 'icomoon', EPICO_THEME_ASSETS_URI . '/css/icomoon.min.css' );

    }

    function epico_Replace_Thickbox_Text($translated_text, $text, $domain)
    {
        if ('Insert into Post' == $text) {

            $texts = epico_get_mediaBox_strings();

            foreach($texts as $key => $value)
            {
                $referer = strpos( wp_get_referer(), $key );

                if ( $referer !== false ) {
                    return $value;
                }

            }

        }

        return $translated_text;
    }

    function epico_Admin_Page()
    {
        $page = include(EPICO_THEME_LIB . '/admin/forms.php');
    }

    function epico_Admin_Scripts()
    {
        if (!isset($_GET['page']) || $_GET['page'] != 'theme_settings' )
            return;

        $this->epico_Register_Scripts();
        $this->epico_Enqueue_Scripts();
    }

    function epico_Register_Scripts()
    {

        wp_register_script('jquery-easing', EPICO_THEME_LIB_URI  .'/admin/scripts/jquery.easing.1.3.js', array('jquery'), '1.3.0');

        wp_register_style( 'nouislider', EPICO_THEME_LIB_URI . '/admin/css/jquery.nouislider.min.css', false, '7.0.10', 'screen' );
        wp_register_script('nouislider', EPICO_THEME_LIB_URI  .'/admin/scripts/jquery.nouislider.min.js', array('jquery'), '7.0.10');

        //Include wpcolorpicker + its patch to support alpha chanel
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );
        wp_enqueue_script('colorpickerAlpha', EPICO_THEME_LIB_URI  .'/admin/scripts/wp-color-picker-alpha.js',array( 'wp-color-picker' ), '1.2.2');

        wp_register_style( 'chosen', EPICO_THEME_LIB_URI . '/admin/css/chosen.css', false, '1.0.0', 'screen' );
        wp_register_script('chosen', EPICO_THEME_LIB_URI  .'/admin/scripts/chosen.jquery.min.js', array('jquery'), '1.0.0');

        wp_register_style( 'theme-admin-css', EPICO_THEME_LIB_URI . '/admin/css/style.css', false, '1.0.0', 'screen' );
        wp_register_script('theme-admin-script', EPICO_THEME_LIB_URI  .'/admin/scripts/admin.js', array('jquery'), '1.0.0');
    }

    function epico_Enqueue_Scripts()
    {

    }
	
}