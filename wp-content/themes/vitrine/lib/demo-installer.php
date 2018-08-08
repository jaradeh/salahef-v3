<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// include WordPress-importer plugin
require 'includes/ep-import.php';

class Epico_Importer{

    private $demos = array("demo1","demo2","demo3","demo4","demo5","demo7","demo8","demo9","demo10","demo11","demo12","demo13","demo14","demo15","demo16","demo17","demo18","demo19","demo20","demo22","demo23","demo24","demo25","demo26","demo27","demo28","demo29","demo30","demo31","demo32","demo33","demo34","demo35","demo36");
    private $medias = array();
    private $media_ids = array();
    private $theme_data = array();

    function __construct() {

        // Initialize the menu
        add_action( 'admin_enqueue_scripts', array( $this, 'init_admin' ) ,200); // add high priority to print them at the end of page (specially admin.js)
        
    } // end constructor


    function init_admin() {

        wp_enqueue_style('theme-admin-css',EPICO_THEME_LIB_URI . '/admin/css/style.css', false, '1.0.0', 'screen' );
        wp_enqueue_script( 'sweet-alert-js', EPICO_THEME_LIB_URI . '/admin/scripts/sweet-alert.min.js',array('jquery'), '0.4.1' );
        wp_enqueue_script('theme-admin-script', EPICO_THEME_LIB_URI  .'/admin/scripts/admin.js', array('jquery','sweet-alert-js'), '1.0.0');

    }

    /**
     * Added to http_request_timeout filter to force timeout at 200 seconds during import
     * @return int 500
     */
    function bump_request_timeout( $val ) {
        return 500;
    }


    function importer_start() 
    {
        
        @ini_set( 'max_execution_time', 0 );
        @ini_set( 'memory_limit', '256M' );
        @set_time_limit( 0 );
        @set_time_limit( 0 );
        @ob_implicit_flush(1);
        add_filter( 'http_request_timeout', array( $this, 'bump_request_timeout' ) );

        set_time_limit(0);
        ini_set('max_execution_time', 40000); //0=NOLIMIT
        ?>
        <div id="ep-importer-box">
            <div class="box-title">
                <h2><?php esc_html_e( 'Importing' , 'vitrine' ); ?> </h2>
                <p><strong style="color:#e21010;"><?php esc_html_e( 'Notice! Do not close the browser during importing process' , 'vitrine' ); ?></strong></p>
            </div>
        <?php
        $demo_name = "";

        if ( !defined('WP_LOAD_IMPORTERS') ) define('WP_LOAD_IMPORTERS', true);
        
        if ( isset($_POST) ) {
            $demo_name = sanitize_file_name($_POST['demo_name']);
        }
        
        if($demo_name == '' || !in_array($demo_name, $this->demos)) {
            echo '<strong>' . esc_html( 'Sorry, there has been an error!' , 'vitrine' ) . '</strong>';
            echo '<p>' . esc_html( 'The demo does not exists on the package!' , 'vitrine' ) . '</p>';
            return;

        }

        // Get the xml file from directory 
        $import_path = EPICO_THEME_LIB ."/admin/dummydata/".$demo_name ."/" ;
        $import_xml_filepath = $import_path . $demo_name .".xml" ;
        $import_json_filepath = $import_path ."options.json" ;

        if(!is_file($import_xml_filepath) || !is_file($import_json_filepath))
        {
            echo '<strong>' . esc_html( 'Sorry, there has been an error!' , 'vitrine' ) . '</strong>';
            echo '<p>' . esc_html( 'The demo File does not exist on your server!' , 'vitrine' ) . '</p>';
            return;
        }


        //Read theme options
        global $wp_filesystem;
        // Initialize the WP filesystem, no more using 'file-put-contents' function
        if (empty($wp_filesystem)) {
            WP_Filesystem();
        }
        $theme_options_json   = $wp_filesystem->get_contents( $import_json_filepath);


        $this->theme_data = json_decode( $theme_options_json , true );

        /* Start the process of importing */
        ob_start();
        
        echo "<h3>" . esc_html('Imporing started', 'vitrine') . "</h3>";
        wp_ob_end_flush_all();
        flush();
        //Import data

        echo "<p><strong>" . esc_html('Step1', 'vitrine') . "</strong>" . esc_html(' : Importing Pages,post,products,menu ... ', 'vitrine');
        wp_ob_end_flush_all();
        flush();
        $this->importData($import_xml_filepath);
        echo  esc_html(" Done", 'vitrine') . "</p>";
        wp_ob_end_flush_all();
        flush();

        //Import revolution slider
        echo "<p><strong>" . esc_html('Step2', 'vitrine') . "</strong>" . esc_html(' : Importing Revolution slider... ', 'vitrine');
        wp_ob_end_flush_all();
        flush();
        $this->importRevSlider($demo_name);
        wp_ob_end_flush_all();
        flush();

        //Set Reading Options
        echo "<p><strong>" . esc_html('Step3', 'vitrine') . "</strong>" . esc_html(' : Manipulating the "Reading Options" in settings ... ', 'vitrine');
        wp_ob_end_flush_all();
        flush();
        $this->setReadingOptions();
        echo  esc_html(" Done", 'vitrine') . "</p>";
        wp_ob_end_flush_all();
        flush();


        //Change shrotcodes options
        echo "<p><strong>" . esc_html('Step4', 'vitrine') . "</strong>" . esc_html(' : Processing Shortcodes ... ', 'vitrine');
        wp_ob_end_flush_all();
        flush();
        $this->processShortcodes();
        echo  esc_html(" Done", 'vitrine') . "</p>";
        wp_ob_end_flush_all();
        flush();

        //Update theme options
        echo "<p><strong>" . esc_html('Step5', 'vitrine') . "</strong>" . esc_html(' : Importing Theme options ... ', 'vitrine');
        wp_ob_end_flush_all();
        flush();
        $this->setThemeOptions();
        echo  esc_html(" Done", 'vitrine') . "</p>";
        wp_ob_end_flush_all();
        flush();

        //change some widget options + replace images with placeholders in widgets
        echo "<p><strong>" . esc_html('Step6', 'vitrine') . "</strong>" . esc_html(' : Processing Widgets ... ', 'vitrine');
        wp_ob_end_flush_all();
        flush();
        $this->processWidgets();
        echo  esc_html(" Done", 'vitrine') . "</p>";
        wp_ob_end_flush_all();
        flush();

        //Set Primary Navigation  
        echo "<p><strong>" . esc_html('Step7', 'vitrine') . "</strong>" . esc_html(' : Processing Menus ... ', 'vitrine');
        wp_ob_end_flush_all();
        flush();
        $this->setNavigationMenu();
        echo  esc_html(" Done", 'vitrine') . "</p>";
        wp_ob_end_flush_all();
        flush();

        echo "<p><strong>" . esc_html('Finished', 'vitrine') . "</strong></p>";

        wp_ob_end_flush_all();
        flush();

        ?>
        </div>
        <?php
    }

    function importData($import_xml_filepath)
    {
        if ( class_exists( 'Epico_Import' ) ) {

            $epico_Import = new Epico_Import();

            $epico_Import->fetch_attachments = false;

            $epico_Import->import($import_xml_filepath);

            $this->medias = $epico_Import->get_medias();
            $this->media_ids = $epico_Import->get_media_ids();

        }
    }

    function setThemeOptions()
    {
        
        $media_url = wp_get_attachment_url($this->media_ids[0] );

        foreach($this->theme_data["options"] as $key => $value)
        {
            $option_values = maybe_unserialize( $value);
            
            if($key == "theme_vitrine_options")
            {
                $options = array();
                foreach($option_values as $optionkey => $option_value)
                {
                    $option_value = str_replace('EPICO_DEMO_IMAGE', $this->medias[0], $option_value);
                    $options[$optionkey] = $option_value;
                }
                
                update_option(OPTIONS_KEY, $options);
            }
            else
            {
                $option_values = stripslashes_deep($option_values);
                update_option($key, $option_values);
            }
        }

    }

    function processShortcodes()
    {

        $all_categories_ids= array();
        $all_product_ids= array();
        $cf7_form_id = -1;
        $newsleter_form_id= -1;
        $shop_url = urlencode(get_permalink( wc_get_page_id( 'shop' ) ));

        //prepare information for category shortcodes
        $args = array(
             'taxonomy'     => 'product_cat',
             'orderby'      => 'name',
             'show_count'   => 0,
             'pad_counts'   => 0,
             'hierarchical' => 1,
             'title_li'     => '',
             'hide_empty'   => 0
        );
        $all_categories = get_categories( $args );
        
        foreach ($all_categories as $cat) {
            if($cat->category_parent == 0) {
                $all_categories_ids[] = $cat->term_id;
            }       
        }

        //Get all product ids
        $args = array(
            'fields' => 'ids',
            'post_type' => array('product'),
            'post_status' => 'publish'
        );
        $all_product_ids = get_posts($args);

        //prepare information for contact-form-7 shortcode
        $args = array(
            'numberposts' => -1,
            'post_type' => 'wpcf7_contact_form',
            'post_status' => 'publish'
        );
        $cf7_forms = get_posts($args);
        foreach ($cf7_forms as $form) {
            if( $form->post_title == "ContactUS" || $form->post_title == "Contact" )
            {
                $cf7_form_id = $form->ID;
            }
        }

        //prepare information for mail-poet newsletter shortcode
        $newsletters = epico_get_mail_poet_forms();
        foreach ($newsletters as $newslettername => $ID) {
            $newsleter_form_id = $ID;    
        }

        $args = array(
            'sort_order' => 'asc',
            'sort_column' => 'post_title',
            'hierarchical' => 1,
            'exclude' => '',
            'include' => '',
            'meta_key' => '',
            'meta_value' => '',
            'authors' => '',
            'child_of' => 0,
            'parent' => -1,
            'exclude_tree' => '',
            'number' => '',
            'offset' => 0,
            'post_type' => 'page',
            'post_status' => 'publish'
        ); 
        $pages = get_pages($args);

        foreach ($pages as $page) {
            if($page->post_content != '')
            {
                $postContent = $page->post_content;

                //Process WC categories shortcode
                $occurence_number = substr_count($postContent, 'EPICO_DEMO_WC_CAT_ID');
                $index = 0;
                for($i = 0; $i < $occurence_number; $i++)
                {
                    if($index >= count($all_categories_ids))
                    {
                        $index = 0;
                    }

                    $postContent = preg_replace('/EPICO_DEMO_WC_CAT_ID/' ,$all_categories_ids[$index], $postContent, 1);

                    $index++;
                }


                //Process WC products shortcode
                $occurence_number = substr_count($postContent, 'EPICO_DEMO_WC_PRODUCT_ID');
                $index = 0;
                for($i = 0; $i < $occurence_number; $i++)
                {
                    if($index >= count($all_product_ids))
                    {
                        $index = 0;
                    }

                    $postContent = preg_replace('/EPICO_DEMO_WC_PRODUCT_ID/' ,$all_product_ids[$index], $postContent, 1);

                    $index++;
                }

                //Process contact form shortcodes
                if($cf7_form_id != -1)
                {
                    $postContent = preg_replace('/EPICO_DEMO_CF7_ID/', $cf7_form_id, $postContent);
                }

                //Process contact form shortcodes
                if($shop_url)
                {
                
                    $postContent = preg_replace('/EPICO_SHOP_URL/', $shop_url, $postContent);
                }

                //process newsletter shortcode
                if($newsleter_form_id != -1)
                {
                    $postContent = preg_replace('/EPICO_DEMO_NEWSLETTER_ID/', $newsleter_form_id, $postContent);
                }
                

                $new_post = array(
                    'ID'           => $page->ID,
                    'post_type'    => 'page',
                    'post_content' => $postContent,
                );

                wp_update_post( $new_post );
            }
        }
    }

    function importRevSlider($demo_name )
    {
        // Get the xml file from directory 
        $import_path = EPICO_THEME_LIB ."/admin/dummydata/".$demo_name ."/" ;
        $import_rev_slider = $import_path ."revslider.zip";

        # Import Layer Slider
        if(is_file($import_rev_slider))
        {
            if(class_exists('RevSlider'))
            {
                $slider = new RevSlider();
                $response = $slider->importSliderFromPost(true, true, $import_rev_slider);
                echo  esc_html(" Done", 'vitrine') . "</p>";
            }
            else
            {
                 echo ": <strong>" . esc_html('Revolution Slider required', 'vitrine') . "</strong>" . " - " . esc_html('Failed', 'vitrine'). "</p>";

            }
        }
        else
        {
            echo ": <strong>" . esc_html("This demo don't have any Revolution slider", 'vitrine') . "</strong>" . " - " . esc_html('Done', 'vitrine'). "</p>";
        }
    }

    function setNavigationMenu()
    {

        $locations = get_registered_nav_menus();
        $new_locations = array();
        $menus = wp_get_nav_menus();

        for($i=0; $i < count($menus); $i++) {
            if($menus[$i]->slug == "widget-menu")
            {
                if(isset($locations['footer-nav']))
                {
                    $new_locations['footer-nav'] = (int)($menus[$i]->term_id);
                }
            }
            elseif($menus[$i]->slug == "top-bar-menu")
            {
                if(isset($locations['topbar-nav']))
                {
                    $new_locations['topbar-nav'] = (int)($menus[$i]->term_id);
                }
            }
            elseif($menus[$i]->slug == "main-menu")
            {
                if(isset($locations['primary-nav']))
                {
                    $new_locations['primary-nav'] = (int)($menus[$i]->term_id);
                }
            }
        }
        set_theme_mod( 'nav_menu_locations', $new_locations );

    }

    function processWidgets()
    {
        $widget_menus = get_option( 'widget_nav_menu' );

        $args = array(
            'slug'     => 'widget-menu'
        );
        $menu = wp_get_nav_menus($args);

        if ( $widget_menus !== false && count($menu) > 0 ) {

            foreach ($widget_menus as $key => $widget_menu) {
                
                // The option already exists, so we just update it.
                if(isset($widget_menu['nav_menu']))
                {
                    $widget_menu['nav_menu'] = (int)($menu[0]->term_id);
                    $widget_menus[$key] = $widget_menu;
                }

            }

        }
        update_option('widget_nav_menu',$widget_menus);


        //Use placeholder images in text widgets
        $text_widgets = get_option( 'widget_text' );
        $media = wp_get_attachment_image_url( $this->media_ids[1], 'thumbnail');
        $media_id = $this->media_ids[1];
        if(is_array($text_widgets))
        {
            foreach ($text_widgets as $key => $text_widget) {

                if(isset($text_widget['text']))
                {
                    $text_widget['text'] = str_replace('EPICO_DEMO_IMAGE_ID', $media_id, $text_widget['text']);
                    $text_widget['text'] = str_replace('EPICO_DEMO_IMAGE', $media, $text_widget['text']);
                    $text_widgets[$key] = $text_widget;         
                }

            }
        }

        update_option('widget_text',$text_widgets); 

		
		//Use placeholder images in  custom html widget
        $custom_html_widgets = get_option( 'widget_custom_html' );
        $media = wp_get_attachment_image_url( $this->media_ids[1], 'thumbnail');
        $media_id = $this->media_ids[1];
        if(is_array($custom_html_widgets))
        {
            foreach ($custom_html_widgets as $key => $custom_html_widget) {

                if(isset($custom_html_widget['content']))
                {
                    $custom_html_widget['content'] = str_replace('EPICO_DEMO_IMAGE_ID', $media_id, $custom_html_widget['content']);
                    $custom_html_widget['content'] = str_replace('EPICO_DEMO_IMAGE', $media, $custom_html_widget['content']);
                   $custom_html_widgets[$key] = $custom_html_widget;         
                }

            }
        }

        update_option('widget_custom_html',$custom_html_widgets);



        
        //Use placeholder images in media widgets
        $media_image_widgets = get_option( 'widget_media_image' );
        if(is_array($media_image_widgets))
        {
            foreach ($media_image_widgets as $key => $media_image_widget) {

                if(isset($media_image_widget['url']))
                {
                    $media_image_widget['url'] = str_replace('EPICO_DEMO_IMAGE', $media, $media_image_widget['url']);
                    $media_image_widgets[$key] = $media_image_widget;         
                }

            }
        }

        update_option('widget_media_image',$media_image_widgets);


        //Use placeholder images in video widgets
        $epico_video_widgets = get_option( 'widget_epico_video' );
        if(is_array($epico_video_widgets))
        {
            foreach ($epico_video_widgets as $key => $video_widget) {

                if(isset($video_widget['video_poster_image']))
                {
                    $video_widget['video_poster_image'] = str_replace('EPICO_DEMO_IMAGE', $media, $video_widget['video_poster_image']);
                    $video_widget[$key] = $video_widget;         
                }

                if(isset($video_widget['video_background_image']))
                {
                    $video_widget['video_background_image'] = str_replace('EPICO_DEMO_IMAGE', $media, $video_widget['video_background_image']);
                    $epico_video_widgets[$key] = $video_widget;         
                }

            }
        }

        update_option('widget_epico_video',$epico_video_widgets);


        //Use placeholder images in woocommerce_layered_nav widgets
        $layered_nav__widgets = get_option( 'widget_woocommerce_layered_nav' );

        if(is_array($layered_nav__widgets))
        {
            foreach ($layered_nav__widgets as $key => $layered_nav__widget) {

                if(isset($layered_nav__widget['values']))
                {
                    foreach ($layered_nav__widget['values'] as $val_key => $val_val) {
                        $random_image = $this->medias[array_rand($this->medias)];
                        $layered_nav__widget['values'][$val_key] = str_replace('EPICO_DEMO_IMAGE', $random_image, $layered_nav__widget['values'][$val_key]);
                        
                    }

                    $layered_nav__widgets[$key] = $layered_nav__widget;
                }

            }
        }

        update_option('widget_woocommerce_layered_nav',$layered_nav__widgets);


        //Set newsletter form in newsletter widget
        $newsletter_widgets = get_option( 'widget_wysija' );
        $newsleter_form_id = -1;
        $newsletters = epico_get_mail_poet_forms();

        foreach ($newsletters as $newslettername => $ID) {
            $newsleter_form_id = $ID;    
        }

        if(is_array($newsletter_widgets) && $newsletter_widgets != false && $newsleter_form_id != -1)
        {
            foreach ($newsletter_widgets as $key => $newsletter_widget) {

                if(isset($newsletter_widget['form']))
                {
                    $newsletter_widget['form'] = $newsleter_form_id;
                    $newsletter_widgets[$key] = $newsletter_widget;
                }
            }
        }

        update_option('widget_wysija',$newsletter_widgets);

    }

    function setReadingOptions()
    {

        //Front page displays : A static page
        update_option('show_on_front', 'page');

        //Get ID of pages
        $home_page = get_page_by_title('Home');
        wp_reset_postdata();

        $blog_page = get_page_by_title('Blog');
        wp_reset_postdata();

        $shop_page = get_page_by_title('Shop');
        wp_reset_postdata();

        $cart_page = get_page_by_title('Cart');
        wp_reset_postdata();

        $checkout_page = get_page_by_title('Checkout');
        wp_reset_postdata();

        $account_page = get_page_by_title('My Account');
        wp_reset_postdata();

        if($home_page)
        {
            $page_on_front = $home_page->ID;
        }

        //check if page_on_front is shop or blog
        if(isset($this->theme_data["frontpage_options"]))
        {
                
            if($this->theme_data["frontpage_options"]['front_page'] == "shop")
            {
                $page_on_front = $shop_page->ID;
            }
            elseif($this->theme_data["frontpage_options"]['front_page'] == "blog")
            {
                $page_on_front = $blog_page->ID;
            }
            else
            {
                $page_on_front = $home_page->ID;
            }
        }


        if( isset($page_on_front) ) {
            update_option('page_on_front',  $page_on_front );
        }

        if( isset($blog_page) ) {
            update_option('page_for_posts', $blog_page->ID );
        }

        if( isset($shop_page) ) {
            update_option('woocommerce_shop_page_id', $shop_page->ID );
        }

        if( isset($cart_page) ) {
            update_option('woocommerce_cart_page_id', $cart_page->ID );
        }

        if( isset($checkout_page) ) {
            update_option('woocommerce_checkout_page_id', $checkout_page->ID );
        }

        if( isset($account_page) ) {
            update_option('woocommerce_myaccount_page_id', $account_page->ID );
        }
    }

    function dispatch() {
        $step = empty( $_GET['step'] ) ? 0 : (int) $_GET['step'];

        switch ( $step ) {
            case 0:
                $this->importer_page();
                break;
            case 1:
                check_admin_referer( 'demo-importer' );
                $this->importer_ready();
                break;
            case 2:
                check_admin_referer( 'demo-importer' );
                $this->importer_start();
                break;
        }
    }

    function importer_ready() {

        ?>
        <div id="ep-importer-box">
            <div class="box-title">
                <h2><?php esc_html_e( 'Demo Importer' , 'vitrine' ); ?> </h2>
                <p><strong style="color:#e21010;"><?php esc_html_e( 'Notice! Install and activate all required plugins before importing a demo. without plugins some data missed from importing' , 'vitrine' ); ?></strong></p>
            </div>
            <br>
            <br>
            <form action="<?php echo admin_url( 'themes.php?page=demo_importer&amp;step=2' ); ?>" method="post">
                <?php wp_nonce_field( 'demo-importer' ); ?>
                <input type="hidden" id="demo_name" name="demo_name" value="<?php echo esc_attr($_POST['demo_name']); ?>">
                <input type="submit" name="submit" id="submit" class="button button-primary" value="Import demo">
            </form>
        </div>
    <?php
    }

    function importer_page() { ?>
        
        <div id="ep-importer-box">
            <div class="box-title">
                <h2><?php esc_html_e( 'Demo Importer' , 'vitrine' ); ?> </h2>
                <p><?php esc_html_e( 'Install each one of these demos, just click on the one you like and nothing else.' , 'vitrine' ); ?></p>
                <p><strong style="color:#e21010;"><?php esc_html_e( 'Notice! Install and activate all required plugins before importing a demo. without plugins some data missed from importing' , 'vitrine' ); ?></strong></p>
            </div>
            <form action="<?php echo admin_url( 'themes.php?page=demo_importer&amp;step=1' ); ?>" method="post">
                <?php wp_nonce_field( 'demo-importer' ); ?>
                <input type="hidden" id="demo_name" name="demo_name" value="demo1">
            <hr>

            <!-- Display demos -->
            <div id="demo-container">
                <!--demo 1 -->
                <div class="demo">
                    <div class="description">
                        <h3 class="demo-name">Demo 1</h3>
                        <p>One page</p>
                        <a target="_blank" href="#" class="button import" data-demo="demo1"><?php esc_html_e('Import' , 'vitrine'); ?></a>
                        <a target="_blank" href="http://www.epicomedia.com/vitrine-demo1" class="button"><?php esc_html_e('Preview' , 'vitrine'); ?></a>
                    </div>
                    <div class="overlay"></div>
                    
                    <img src="<?php echo EPICO_THEME_LIB_URI; ?>/admin/dummydata/img/demo1.jpg" />  
                </div>
                <!--demo 2 -->
                <div class="demo">
                    <div class="description">
                        <h3 class="demo-name">Demo 2</h3>
                        <p>One page</p>
                        <a target="_blank" href="#" class="button import" data-demo="demo2"><?php esc_html_e('Import' , 'vitrine'); ?></a>
                        <a target="_blank" href="http://www.epicomedia.com/vitrine-demo2" class="button"><?php esc_html_e('Preview' , 'vitrine'); ?></a>
                    </div>
                    <div class="overlay"></div>
                    
                    <img src="<?php echo EPICO_THEME_LIB_URI; ?>/admin/dummydata/img/demo2.jpg" />  
                </div>
                <!--demo 3 -->
                <div class="demo">
                    <div class="description">
                        <h3 class="demo-name">Demo 3</h3>
                        <p>Multi page</p>
                        <a target="_blank" href="#" class="button import" data-demo="demo3"><?php esc_html_e('Import' , 'vitrine'); ?></a>
                        <a target="_blank" href="http://www.epicomedia.com/vitrine-demo3" class="button"><?php esc_html_e('Preview' , 'vitrine'); ?></a>
                    </div>
                    <div class="overlay"></div>
                    
                    <img src="<?php echo EPICO_THEME_LIB_URI; ?>/admin/dummydata/img/demo3.jpg" />  
                </div>
                <!--demo 4 -->
                <div class="demo">
                    <div class="description">
                        <h3 class="demo-name">Demo 4</h3>
                        <p>Multi page</p>
                        <a target="_blank" href="#" class="button import" data-demo="demo4"><?php esc_html_e('Import' , 'vitrine'); ?></a>
                        <a target="_blank" href="http://www.epicomedia.com/vitrine-demo4" class="button"><?php esc_html_e('Preview' , 'vitrine'); ?></a>
                    </div>
                    <div class="overlay"></div>
                    
                    <img src="<?php echo EPICO_THEME_LIB_URI; ?>/admin/dummydata/img/demo4.jpg" />  
                </div>
               <!--demo 5 -->
                <div class="demo">
                    <div class="description">
                        <h3 class="demo-name">Demo 5</h3>
                        <p>Multi page</p>
                        <a target="_blank" href="#" class="button import" data-demo="demo5"><?php esc_html_e('Import' , 'vitrine'); ?></a>
                        <a target="_blank" href="http://www.epicomedia.com/vitrine-demo5" class="button"><?php esc_html_e('Preview' , 'vitrine'); ?></a>
                    </div>
                    <div class="overlay"></div>
                    
                    <img src="<?php echo EPICO_THEME_LIB_URI; ?>/admin/dummydata/img/demo5.jpg" />  
                </div>
               <!--demo 7 -->
                <div class="demo">
                    <div class="description">
                        <h3 class="demo-name">Demo 7</h3>
                        <p>One page</p>
                        <a target="_blank" href="#" class="button import" data-demo="demo7"><?php esc_html_e('Import' , 'vitrine'); ?></a>
                        <a target="_blank" href="http://www.epicomedia.com/vitrine-demo7" class="button"><?php esc_html_e('Preview' , 'vitrine'); ?></a>
                    </div>
                    <div class="overlay"></div>
                    
                    <img src="<?php echo EPICO_THEME_LIB_URI; ?>/admin/dummydata/img/demo7.jpg" />  
                </div>
               <!--demo 8 -->
                <div class="demo">
                    <div class="description">
                        <h3 class="demo-name">Demo 8</h3>
                        <p>Multi page</p>
                        <a target="_blank" href="#" class="button import" data-demo="demo8"><?php esc_html_e('Import' , 'vitrine'); ?></a>
                        <a target="_blank" href="http://www.epicomedia.com/vitrine-demo8" class="button"><?php esc_html_e('Preview' , 'vitrine'); ?></a>
                    </div>
                    <div class="overlay"></div>
                    
                    <img src="<?php echo EPICO_THEME_LIB_URI; ?>/admin/dummydata/img/demo8.jpg" />  
                </div>
				 <!--demo 9 -->
                <div class="demo">
                    <div class="description">
                        <h3 class="demo-name">Demo 9</h3>
                        <p>One page</p>
                        <a target="_blank" href="#" class="button import" data-demo="demo9"><?php esc_html_e('Import' , 'vitrine'); ?></a>
                        <a target="_blank" href="http://www.epicomedia.com/vitrine-demo9" class="button"><?php esc_html_e('Preview' , 'vitrine'); ?></a>
                    </div>
                    <div class="overlay"></div>
                    
                    <img src="<?php echo EPICO_THEME_LIB_URI; ?>/admin/dummydata/img/demo9.jpg" />  
                </div>
                <div class="demo">
                    <div class="description">
                        <h3 class="demo-name">Demo 10</h3>
                        <p>Multi page</p>
                        <a target="_blank" href="#" class="button import" data-demo="demo10"><?php esc_html_e('Import' , 'vitrine'); ?></a>
                        <a target="_blank" href="http://www.epicomedia.com/vitrine-demo10" class="button"><?php esc_html_e('Preview' , 'vitrine'); ?></a>
                    </div>
                    <div class="overlay"></div>
                    
                    <img src="<?php echo EPICO_THEME_LIB_URI; ?>/admin/dummydata/img/demo10.jpg" />  
                </div>
                <div class="demo">
                    <div class="description">
                        <h3 class="demo-name">Demo 11</h3>
                        <p>Multi page</p>
                        <a target="_blank" href="#" class="button import" data-demo="demo11"><?php esc_html_e('Import' , 'vitrine'); ?></a>
                        <a target="_blank" href="http://www.epicomedia.com/vitrine-demo11" class="button"><?php esc_html_e('Preview' , 'vitrine'); ?></a>
                    </div>
                    <div class="overlay"></div>
                    
                    <img src="<?php echo EPICO_THEME_LIB_URI; ?>/admin/dummydata/img/demo11.jpg" />  
                </div>
				<div class="demo">
                    <div class="description">
                        <h3 class="demo-name">Demo 12</h3>
                        <p>Multi page</p>
                        <a target="_blank" href="#" class="button import" data-demo="demo12"><?php esc_html_e('Import' , 'vitrine'); ?></a>
                        <a target="_blank" href="http://www.epicomedia.com/vitrine-demo12" class="button"><?php esc_html_e('Preview' , 'vitrine'); ?></a>
                    </div>
                    <div class="overlay"></div>
                    
                    <img src="<?php echo EPICO_THEME_LIB_URI; ?>/admin/dummydata/img/demo12.jpg" />  
                </div>
                <div class="demo">
                    <div class="description">
                        <h3 class="demo-name">Demo 13</h3>
                        <p>Multi page</p>
                        <a target="_blank" href="#" class="button import" data-demo="demo13"><?php esc_html_e('Import' , 'vitrine'); ?></a>
                        <a target="_blank" href="http://www.epicomedia.com/vitrine-demo13" class="button"><?php esc_html_e('Preview' , 'vitrine'); ?></a>
                    </div>
                    <div class="overlay"></div>
                    
                    <img src="<?php echo EPICO_THEME_LIB_URI; ?>/admin/dummydata/img/demo13.jpg" />  
                </div>
                <div class="demo">
                    <div class="description">
                        <h3 class="demo-name">Demo 14</h3>
                        <p>Multi page</p>
                        <a target="_blank" href="#" class="button import" data-demo="demo14"><?php esc_html_e('Import' , 'vitrine'); ?></a>
                        <a target="_blank" href="http://www.epicomedia.com/vitrine-demo14" class="button"><?php esc_html_e('Preview' , 'vitrine'); ?></a>
                    </div>
                    <div class="overlay"></div>
                    
                    <img src="<?php echo EPICO_THEME_LIB_URI; ?>/admin/dummydata/img/demo14.jpg" />  
                </div>
                <div class="demo">
                    <div class="description">
                        <h3 class="demo-name">Demo 15</h3>
                        <p>Multi page</p>
                        <a target="_blank" href="#" class="button import" data-demo="demo15"><?php esc_html_e('Import' , 'vitrine'); ?></a>
                        <a target="_blank" href="http://www.epicomedia.com/vitrine-demo15" class="button"><?php esc_html_e('Preview' , 'vitrine'); ?></a>
                    </div>
                    <div class="overlay"></div>
                    
                    <img src="<?php echo EPICO_THEME_LIB_URI; ?>/admin/dummydata/img/demo15.jpg" />  
                </div>

                <div class="demo">
                    <div class="description">
                        <h3 class="demo-name">Demo 16</h3>
                        <p>Multi page</p>
                        <a target="_blank" href="#" class="button import" data-demo="demo16"><?php esc_html_e('Import' , 'vitrine'); ?></a>
                        <a target="_blank" href="http://www.epicomedia.com/vitrine-demo16" class="button"><?php esc_html_e('Preview' , 'vitrine'); ?></a>
                    </div>
                    <div class="overlay"></div>
                    
                    <img src="<?php echo EPICO_THEME_LIB_URI; ?>/admin/dummydata/img/demo16.jpg" />  
                </div>
                <div class="demo">
                    <div class="description">
                        <h3 class="demo-name">Demo 17</h3>
                        <p>Multi page</p>
                        <a target="_blank" href="#" class="button import" data-demo="demo17"><?php esc_html_e('Import' , 'vitrine'); ?></a>
                        <a target="_blank" href="http://www.epicomedia.com/vitrine-demo17" class="button"><?php esc_html_e('Preview' , 'vitrine'); ?></a>
                    </div>
                    <div class="overlay"></div>
                    
                    <img src="<?php echo EPICO_THEME_LIB_URI; ?>/admin/dummydata/img/demo17.jpg" />  
                </div>
                <div class="demo">
                    <div class="description">
                        <h3 class="demo-name">Demo 18</h3>
                        <p>Multi page</p>
                        <a target="_blank" href="#" class="button import" data-demo="demo18"><?php esc_html_e('Import' , 'vitrine'); ?></a>
                        <a target="_blank" href="http://www.epicomedia.com/vitrine-demo18" class="button"><?php esc_html_e('Preview' , 'vitrine'); ?></a>
                    </div>
                    <div class="overlay"></div>
                    
                    <img src="<?php echo EPICO_THEME_LIB_URI; ?>/admin/dummydata/img/demo18.jpg" />  
                </div>
				<div class="demo">
                    <div class="description">
                        <h3 class="demo-name">Demo 19</h3>
                        <p>Multi page</p>
                        <a target="_blank" href="#" class="button import" data-demo="demo19"><?php esc_html_e('Import' , 'vitrine'); ?></a>
                        <a target="_blank" href="http://www.epicomedia.com/vitrine-demo19" class="button"><?php esc_html_e('Preview' , 'vitrine'); ?></a>
                    </div>
                    <div class="overlay"></div>
                    
                    <img src="<?php echo EPICO_THEME_LIB_URI; ?>/admin/dummydata/img/demo19.jpg" />  
                </div>
                <div class="demo">
                    <div class="description">
                        <h3 class="demo-name">Demo 20</h3>
                        <p>Multi page</p>
                        <a target="_blank" href="#" class="button import" data-demo="demo20"><?php esc_html_e('Import' , 'vitrine'); ?></a>
                        <a target="_blank" href="http://www.epicomedia.com/vitrine-demo20" class="button"><?php esc_html_e('Preview' , 'vitrine'); ?></a>
                    </div>
                    <div class="overlay"></div>
                    
                    <img src="<?php echo EPICO_THEME_LIB_URI; ?>/admin/dummydata/img/demo20.jpg" />  
                </div>
                <div class="demo">
                    <div class="description">
                        <h3 class="demo-name">Demo 22</h3>
                        <p>Multi page</p>
                        <a target="_blank" href="#" class="button import" data-demo="demo22"><?php esc_html_e('Import' , 'vitrine'); ?></a>
                        <a target="_blank" href="http://www.epicomedia.com/vitrine-demo22" class="button"><?php esc_html_e('Preview' , 'vitrine'); ?></a>
                    </div>
                    <div class="overlay"></div>
                    
                    <img src="<?php echo EPICO_THEME_LIB_URI; ?>/admin/dummydata/img/demo22.jpg" />  
                </div>
                <div class="demo">
                    <div class="description">
                        <h3 class="demo-name">Demo 23</h3>
                        <p>Multi page</p>
                        <a target="_blank" href="#" class="button import" data-demo="demo23"><?php esc_html_e('Import' , 'vitrine'); ?></a>
                        <a target="_blank" href="http://www.epicomedia.com/vitrine-demo23" class="button"><?php esc_html_e('Preview' , 'vitrine'); ?></a>
                    </div>
                    <div class="overlay"></div>
                    
                    <img src="<?php echo EPICO_THEME_LIB_URI; ?>/admin/dummydata/img/demo23.jpg" />  
                </div>
                <div class="demo">
                    <div class="description">
                        <h3 class="demo-name">Demo 24</h3>
                        <p>Multi page</p>
                        <a target="_blank" href="#" class="button import" data-demo="demo24"><?php esc_html_e('Import' , 'vitrine'); ?></a>
                        <a target="_blank" href="http://www.epicomedia.com/vitrine-demo24" class="button"><?php esc_html_e('Preview' , 'vitrine'); ?></a>
                    </div>
                    <div class="overlay"></div>
                    
                    <img src="<?php echo EPICO_THEME_LIB_URI; ?>/admin/dummydata/img/demo24.jpg" />  
                </div>
                <div class="demo">
                    <div class="description">
                        <h3 class="demo-name">Demo 25</h3>
                        <p>Multi page</p>
                        <a target="_blank" href="#" class="button import" data-demo="demo25"><?php esc_html_e('Import' , 'vitrine'); ?></a>
                        <a target="_blank" href="http://www.epicomedia.com/vitrine-demo25" class="button"><?php esc_html_e('Preview' , 'vitrine'); ?></a>
                    </div>
                    <div class="overlay"></div>
                    
                    <img src="<?php echo EPICO_THEME_LIB_URI; ?>/admin/dummydata/img/demo25.jpg" />  
                </div>
				<div class="demo">
                    <div class="description">
                        <h3 class="demo-name">Demo 26</h3>
                        <p>one page</p>
                        <a target="_blank" href="#" class="button import" data-demo="demo26"><?php esc_html_e('Import' , 'vitrine'); ?></a>
                        <a target="_blank" href="http://www.epicomedia.com/vitrine-demo26" class="button"><?php esc_html_e('Preview' , 'vitrine'); ?></a>
                    </div>
                    <div class="overlay"></div>
                    
                    <img src="<?php echo EPICO_THEME_LIB_URI; ?>/admin/dummydata/img/demo26.jpg" />  
                </div>
                <div class="demo">
                    <div class="description">
                        <h3 class="demo-name">Demo 27</h3>
                        <p>Multi page</p>
                        <a target="_blank" href="#" class="button import" data-demo="demo27"><?php esc_html_e('Import' , 'vitrine'); ?></a>
                        <a target="_blank" href="http://www.epicomedia.com/vitrine-demo27" class="button"><?php esc_html_e('Preview' , 'vitrine'); ?></a>
                    </div>
                    <div class="overlay"></div>
                    
                    <img src="<?php echo EPICO_THEME_LIB_URI; ?>/admin/dummydata/img/demo27.jpg" />  
                </div>
                <div class="demo">
                    <div class="description">
                        <h3 class="demo-name">Demo 28</h3>
                        <p>Multi page</p>
                        <a target="_blank" href="#" class="button import" data-demo="demo28"><?php esc_html_e('Import' , 'vitrine'); ?></a>
                        <a target="_blank" href="http://www.epicomedia.com/vitrine-demo28" class="button"><?php esc_html_e('Preview' , 'vitrine'); ?></a>
                    </div>
                    <div class="overlay"></div>
                    
                    <img src="<?php echo EPICO_THEME_LIB_URI; ?>/admin/dummydata/img/demo28.jpg" />  
                </div>
                <div class="demo">
                    <div class="description">
                        <h3 class="demo-name">Demo 29</h3>
                        <p>Multi page</p>
                        <a target="_blank" href="#" class="button import" data-demo="demo29"><?php esc_html_e('Import' , 'vitrine'); ?></a>
                        <a target="_blank" href="http://www.epicomedia.com/vitrine-demo29" class="button"><?php esc_html_e('Preview' , 'vitrine'); ?></a>
                    </div>
                    <div class="overlay"></div>
                    
                    <img src="<?php echo EPICO_THEME_LIB_URI; ?>/admin/dummydata/img/demo29.jpg" />  
                </div>
				<div class="demo">
                    <div class="description">
                        <h3 class="demo-name">Demo 30</h3>
                        <p>Multi page</p>
                        <a target="_blank" href="#" class="button import" data-demo="demo30"><?php esc_html_e('Import' , 'vitrine'); ?></a>
                        <a target="_blank" href="http://www.epicomedia.com/vitrine-demo30" class="button"><?php esc_html_e('Preview' , 'vitrine'); ?></a>
                    </div>
                    <div class="overlay"></div>
                    
                    <img src="<?php echo EPICO_THEME_LIB_URI; ?>/admin/dummydata/img/demo30.jpg" />  
                </div>
				<div class="demo">
                    <div class="description">
                        <h3 class="demo-name">Demo 31</h3>
                        <p>one page</p>
                        <a target="_blank" href="#" class="button import" data-demo="demo31"><?php esc_html_e('Import' , 'vitrine'); ?></a>
                        <a target="_blank" href="http://www.epicomedia.com/vitrine-demo31" class="button"><?php esc_html_e('Preview' , 'vitrine'); ?></a>
                    </div>
                    <div class="overlay"></div>
                    
                    <img src="<?php echo EPICO_THEME_LIB_URI; ?>/admin/dummydata/img/demo31.jpg" />  
                </div>
				<div class="demo">
                    <div class="description">
                        <h3 class="demo-name">Demo 32</h3>
                        <p>one page</p>
                        <a target="_blank" href="#" class="button import" data-demo="demo32"><?php esc_html_e('Import' , 'vitrine'); ?></a>
                        <a target="_blank" href="http://www.epicomedia.com/vitrine-demo32" class="button"><?php esc_html_e('Preview' , 'vitrine'); ?></a>
                    </div>
                    <div class="overlay"></div>
                    
                    <img src="<?php echo EPICO_THEME_LIB_URI; ?>/admin/dummydata/img/demo32.jpg" />  
                </div>
				<div class="demo">
                    <div class="description">
                        <h3 class="demo-name">Demo 33</h3>
                        <p>Multi page</p>
                        <a target="_blank" href="#" class="button import" data-demo="demo33"><?php esc_html_e('Import' , 'vitrine'); ?></a>
                        <a target="_blank" href="http://www.epicomedia.com/vitrine-demo33" class="button"><?php esc_html_e('Preview' , 'vitrine'); ?></a>
                    </div>
                    <div class="overlay"></div>
                    
                    <img src="<?php echo EPICO_THEME_LIB_URI; ?>/admin/dummydata/img/demo33.jpg" />  
                </div>
				<div class="demo">
                    <div class="description">
                        <h3 class="demo-name">Demo 34</h3>
                        <p>Multi page</p>
                        <a target="_blank" href="#" class="button import" data-demo="demo34"><?php esc_html_e('Import' , 'vitrine'); ?></a>
                        <a target="_blank" href="http://www.epicomedia.com/vitrine-demo34" class="button"><?php esc_html_e('Preview' , 'vitrine'); ?></a>
                    </div>
                    <div class="overlay"></div>
                    
                    <img src="<?php echo EPICO_THEME_LIB_URI; ?>/admin/dummydata/img/demo34.jpg" />  
                </div>
				<div class="demo">
                    <div class="description">
                        <h3 class="demo-name">Demo 35</h3>
                        <p>Multi page</p>
                        <a target="_blank" href="#" class="button import" data-demo="demo35"><?php esc_html_e('Import' , 'vitrine'); ?></a>
                        <a target="_blank" href="http://www.epicomedia.com/vitrine-demo35" class="button"><?php esc_html_e('Preview' , 'vitrine'); ?></a>
                    </div>
                    <div class="overlay"></div>
                    
                    <img src="<?php echo EPICO_THEME_LIB_URI; ?>/admin/dummydata/img/demo35.jpg" />  
                </div>
				<div class="demo">
                    <div class="description">
                        <h3 class="demo-name">Demo 36</h3>
                        <p>Multi page</p>
                        <a target="_blank" href="#" class="button import" data-demo="demo36"><?php esc_html_e('Import' , 'vitrine'); ?></a>
                        <a target="_blank" href="http://www.epicomedia.com/vitrine-demo36" class="button"><?php esc_html_e('Preview' , 'vitrine'); ?></a>
                    </div>
                    <div class="overlay"></div>
                    
                    <img src="<?php echo EPICO_THEME_LIB_URI; ?>/admin/dummydata/img/demo36.jpg" />  
                </div>

                </div>
            </form>
        </div>

     <?php
    
    }

}

// instantiate class
$GLOBALS['Epico_Importer'] = new Epico_Importer();

function menu_init() {
    add_menu_page(THEME_NAME, 'Demo Importer', 'manage_options','demo_importer', array($GLOBALS['Epico_Importer'], 'dispatch'),'dashicons-icon-cloud-download2',61);
}

// Add menu item in admin
add_action('admin_menu', 'menu_init');

?>