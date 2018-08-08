<?php

function epico_high_priority_theme_styles ()
{
    //icomoon style
    wp_enqueue_style('epico_icomoon-style', EPICO_THEME_ASSETS_URI . '/css/icomoon.min.css', false, THEME_VERSION);

    //google fonts
    epico_theme_fonts();

}

add_action('wp_enqueue_scripts', 'epico_high_priority_theme_styles' , 0);// use 0 to be the first styles added to theme


function epico_theme_scripts() {

    // Suffix setup (debug mode loads un-minified scripts & styles)
    $suffix = '.min';
    if ( defined( 'EPICO_DEVELOP_MODE' ) && EPICO_DEVELOP_MODE ) {
        $suffix = '';
    }

    /**************************************************
        Register styles
    **************************************************/
    //main style
    wp_register_style('epico_theme-style', EPICO_THEME_ASSETS_URI . '/css/theme-styles' . $suffix . '.css', false, THEME_VERSION);

    //woocommerce style
    wp_register_style('epico_woocommerce-style', EPICO_THEME_ASSETS_URI . '/css/woocommerce' . $suffix . '.css', false, THEME_VERSION);


    /**************************************************
        Add styles in project
    **************************************************/

    //Add style overrides
    ob_start();
    include(epico_path_combine(EPICO_THEME_CSS, 'styles-inline.php'));
    if ( function_exists( 'is_woocommerce' )) {
        wp_add_inline_style('epico_woocommerce-style', ob_get_clean());
    }
    else
    {
        wp_add_inline_style('epico_theme-style', ob_get_clean());
    }

    //main style
    wp_enqueue_style('epico_theme-style');

    //woocommerce style
    if ( function_exists( 'is_woocommerce' )) {
        wp_enqueue_style('epico_woocommerce-style');
    }

    //responcive style
    wp_enqueue_style('epico_responsive-style', EPICO_THEME_ASSETS_URI . '/css/responsive' . $suffix . '.css', false, THEME_VERSION);

    // 3rd parties 
    wp_enqueue_style('epico_libs', EPICO_THEME_ASSETS_URI. '/css/libs.min.css', false, THEME_VERSION, 'screen');

    // Media Element (use css file in core of WP)
    wp_enqueue_style('mediaelement');

    // all scripts
    wp_enqueue_script('epico_allscripts', EPICO_THEME_ASSETS_URI . '/js/allscripts.min.js', false, '1.0', true);

    //modernizr 
    wp_enqueue_script('epico_modernizr', EPICO_THEME_ASSETS_URI . '/js/modernizr.min.js', false, '2.8.3', true);
    
    // Modified Scripts
    wp_enqueue_script('epico_modifiedscripts', EPICO_THEME_ASSETS_URI . '/js/modifiedscripts'. $suffix . '.js', false, '1.0', true );
    
	// RTL CSS
    if ( is_rtl() ) { 
        wp_register_style('rtl-style', EPICO_THEME_URI . '/rtl' . $suffix . '.css', false, THEME_VERSION);
    }

    // Media Element (use media element.js in core of WP)
    wp_enqueue_script('mediaelement');

    //google Map - gmap3
    //page or post ID
    $map;
    if(function_exists( 'is_woocommerce' ) && is_shop()) {
        $map = get_post_meta(get_option('woocommerce_shop_page_id'), "footer-map", true);
    }
    else
    {
        $map = get_post_meta(get_the_ID(), "footer-map", true);
    }

    if($map)
    {
        wp_enqueue_script('epico_gmap3', EPICO_THEME_ASSETS_URI . '/js/gmap3.min.js', false,'6.0.0',true);
    }  
    
    //Custom Script
    wp_enqueue_script(
        'epico_custom',
        EPICO_THEME_ASSETS_URI . '/js/custom' . $suffix  . '.js',
        false,
        THEME_VERSION,
        true
    );
	
	//Gallery
    wp_enqueue_script('epico_lightGallery', EPICO_THEME_ASSETS_URI . '/js/lg-custom-package.min.js', false, '1.2.22', true);
    
    $id = get_the_ID();

    $zoomLevel = get_post_meta($id, "footerMapZoom", true);
    $iconMap = get_post_meta($id, "map_marker", true);
    $customMap = get_post_meta($id, "footerStyleMap", true);
    $cityMapCenterLat = get_post_meta($id, "footerMapLatitude", true);
    $cityMapCenterLng = get_post_meta($id, "footerMapLongitude", true);
   
    if($iconMap == '')
    {
        $iconMap = get_template_directory_uri() ."/assets/img/marker.png";
    }

    // additional scripts
    $custom=epico_opt('additional-js');
    $custom=str_replace("<script>","",$custom);
    $custom=str_replace("</script>","",$custom);

    // Localize custom.js with url of site
    wp_localize_script( 'epico_custom', 'epico_theme_vars',
        array(
            // site variables
            'url' => esc_url(get_site_url()),
            'home_url' => esc_url(home_url( '/' )),
            'img' => esc_url(EPICO_THEME_IMAGES_URI),
            //ajax variables
            'ajax_url' => esc_url(admin_url( 'admin-ajax.php' )),
            'nonce' => wp_create_nonce( 'ajax-nonce' ),
            // home And Footer Google Map Variables
            'zoomLevel' => esc_html($zoomLevel),
            'iconMap' => esc_url($iconMap),
            'customMap' => esc_html($customMap),
            'cityMapCenterLat' => esc_html($cityMapCenterLat),
            'cityMapCenterLng' => esc_html($cityMapCenterLng),
            'customApiKey' => esc_html(epico_opt('customApiKey')),
            'ApiKey' => esc_html(epico_opt('googleApiKey')),
            // scrolling options
            'scrolling_speed' => esc_html(epico_opt('scrolling-speed')),
            'scrolling_easing' => esc_html(epico_opt('scrolling-easing')),
            //Custom scripts
            'additionaljs' => $custom,
        )
    );

    //get exception pages of ajax
    $no_ajax_pages = epico_no_ajax_pages();
    wp_localize_script( 'epico_custom', 'no_ajax_objects', array(
        'no_ajax_pages' => $no_ajax_pages
    ));

    if (is_singular() && comments_open() && get_option('thread_comments'))
    {
        wp_enqueue_script('comment-reply');
    }

    epico_Load_Posts_Init();

}
add_action('wp_enqueue_scripts', 'epico_theme_scripts' , 99);// use 99 to be the last scripts added to theme


//Dequeue Styles
function epico_remove_styles() {
    if ( class_exists( 'Woocommerce' )  ) {
        wp_dequeue_style('woocommerce_prettyPhoto_css');
    }

    if (class_exists('YITH_WCWL')) {
        wp_dequeue_style('yith-wcwl-font-awesome');
        wp_dequeue_style( 'jquery-selectBox' );
    }

    if(class_exists('YITH_Woocompare'))
    {
        wp_dequeue_style( 'jquery-colorbox' ); 
    }
}
add_action( 'wp_print_styles', 'epico_remove_styles' );

function epico_remove_scripts () {
    global $wp_scripts;

    if ( class_exists( 'Woocommerce' )  ) {
      
        wp_dequeue_script('prettyPhoto'); 
        wp_dequeue_script('prettyPhoto-init');
        wp_dequeue_script('wc-single-product');
        wp_dequeue_script('vc_woocommerce-add-to-cart-js');

        wp_localize_script(
            'epico_custom',
            'wc_single_product_params',
            array(
                'i18n_required_rating_text' => esc_html__( 'Please select a rating', 'vitrine' ),
                'review_rating_required'    => get_option( 'woocommerce_review_rating_required' ),
            )
        );

        if (class_exists('YITH_WCWL')) {
            wp_dequeue_script( 'jquery-selectBox' );
            //Remove depencency of jquery-yith-wcwl to jquery-selectBox (use this way to keep jquery-yith-wcwl localizations)
            if(isset($wp_scripts->registered['jquery-yith-wcwl']->deps[1]) && $wp_scripts->registered['jquery-yith-wcwl']->deps[1] =='jquery-selectBox')
            {
                unset($wp_scripts->registered['jquery-yith-wcwl']->deps[1]);
            }
        }

        if(class_exists('YITH_Woocompare'))
        {
            wp_dequeue_script( 'jquery-colorbox' ); 
        }

    }

}
add_action('wp_print_scripts', 'epico_remove_scripts' );

//load more function
function epico_Load_Posts_Init() {

    // Add some parameters for the JS - blog load more .
    $queryArgsPost = array (
        'post_type'      => 'post',
    );
    $query = new WP_Query($queryArgsPost);
    $max = $query-> max_num_pages;
    $paged = ( get_query_var('paged') > 1 ) ? get_query_var('paged') : 1;

    wp_localize_script(
        'epico_custom',
        'paged_data',
        array(
            'startPage' => $paged,
            'maxPages' => $max,
            'nextLink' => next_posts($max, false),
            'loadingText' => esc_html__('Loading...', 'vitrine'),
            'loadMoreText' => esc_html__('more posts', 'vitrine'),
            'noMorePostsText' => esc_html__('No More Posts', 'vitrine')
        )
    );
    wp_reset_postdata();

}

//Custom stylesheet file to the TinyMCE visual editor
function epico_add_editor_styles()
{
    add_editor_style();
}

add_action( 'init', 'epico_add_editor_styles' );

function epico_theme_fonts()
{

    $fontBodyType     = epico_opt('font-body-type');
    $fontHeadingType  = epico_opt('font-headings-type');
    $fontNavType      = epico_opt('font-navigation-type');
    $ShortcodeFontType  = epico_opt('font-shortcode-type');

    $fontBody      = array();
    $fontHeading   = array();
    $fontNav       = array();
    $ShortcodeFont = array();

    /* Define default fonts */
    if($fontBodyType == 'default')
    {
        $fontBody  = array('Lato'=>array("300","400","500","600","700"));
    }
    elseif($fontBodyType == 'google')
    {
        $fontBody  = (Array)json_decode(epico_opt('font-body'));
    }

    if($fontHeadingType == 'default')
    {
        $fontHeading  = array('Poppins'=>array("300","400","500","600","700"));
    }
    elseif($fontHeadingType == 'google')
    {
        $fontHeading  = (Array)json_decode(epico_opt('font-headings'));
    }

    if($fontNavType == 'default')
    {
        $fontNav  = array('Poppins'=>array("300","400","500","600","700"));
    }
    elseif($fontNavType == 'google')
    {
        $fontNav  = (Array)json_decode(epico_opt('font-navigation'));
    }

    if($ShortcodeFontType == 'default')
    {
        $ShortcodeFont  = array('Lato'=>array("300","400","500","600","700"));
    }
    elseif($ShortcodeFontType == 'google')
    {
        $ShortcodeFont  = (Array)json_decode(epico_opt('font-shortcode'));
    }
    
    //Merge 4 font arrays + remove duplicates
    $fonts  = array_merge($fontBody, $fontHeading , $fontNav , $ShortcodeFont);
    $fonts        = array_filter($fonts);//remove empty elements
    $fontReq      = '//fonts.googleapis.com/css?family=';

    $RequestedFonts=array();
    foreach($fonts as $font => $variants)
    {
        //Repplace space in font name with plus character
        $query = preg_replace('/ /', '+', $font);

        if(count($variants))
            $query .= ':' . implode(',', $variants);

        $RequestedFonts[] = $query;
    }

    //Load default or user selected google fonts
    $fontReq .= implode('|', $RequestedFonts);

    if(count($RequestedFonts) > 0 )
    {
        wp_enqueue_style('epico_fonts', $fontReq);
    }
        

    /* Load custom fonts */
    if($fontBodyType == 'custom')
    {
        $bodyCustomFontUrl = epico_opt('custom-font-url-body');
        wp_enqueue_style('epico_custom_body_fonts', $bodyCustomFontUrl);
    }

    if($fontHeadingType == 'custom')
    {
        $headCustomFontUrl = epico_opt('custom-font-url-headings');
        wp_enqueue_style('epico_custom_headings_fonts', $headCustomFontUrl);
    }

    if($fontNavType == 'custom')
    {
        $navCustomFontUrl = epico_opt('custom-font-url-navigation');
        wp_enqueue_style('epico_custom_nav_fonts', $navCustomFontUrl);
    }
    
    if($ShortcodeFontType == 'custom')
    {
        $ShortcodeCustomFontUrl = epico_opt('custom-font-url-shortcode');
        wp_enqueue_style('epico_custom_shortcode_fonts', $ShortcodeCustomFontUrl);
    }




    //Get font of slider
    if(epico_get_meta('display-top-slider') == 1 && epico_get_meta('slider-type') == 'epico-slider') {
        $slides = get_posts(array(
          'post_type' => 'slider',
          'numberposts' => -1,
          'tax_query' => array(
            array(
              'taxonomy' => 'slider_cats',
              'field' => 'slug',
              'terms' =>  epico_get_meta('epico-slider-cat'), 
            )
          )
        ));

        $sliderCustomFonts = array();
        $sliderGoogleFonts = array();

        foreach ($slides as $slideID) {
                             

            $title_font_type = get_post_meta( $slideID->ID, 'title-font-type' ,true );
            if ( $title_font_type == 'google') {
                $slider_google_title_font = (Array)json_decode(get_post_meta($slideID->ID, 'title-font' ,true )); 
                if(!array_key_exists(key($slider_google_title_font),$fonts))
                {
                    $variant = get_post_meta($slideID->ID, 'title-font-weight' ,true );
                    $slider_google_title_font[key($slider_google_title_font)] =  array($variant => $variant);
                    $sliderGoogleFonts = array_merge($sliderGoogleFonts, $slider_google_title_font);
                }
            }
            elseif($title_font_type == 'custom')
            {
                $sliderCustomFonts[] = get_post_meta($slideID->ID, 'title-custom-font-url',true);
            }

            $subtitle_font_type = get_post_meta( $slideID->ID, 'subtitle-font-type' ,true );
            if ( $subtitle_font_type == 'google') {
                $slider_google_subtitle_font = (Array)json_decode(get_post_meta($slideID->ID, 'subtitle-font' ,true ));
                if(!array_key_exists(key($slider_google_subtitle_font),$fonts))
                {
                    $variant = get_post_meta($slideID->ID, 'subtitle-font-weight' ,true );
                    $slider_google_subtitle_font[key($slider_google_subtitle_font)] =  array($variant => $variant);
                    $sliderGoogleFonts = array_merge($sliderGoogleFonts, $slider_google_subtitle_font);
                }
            }
            elseif($subtitle_font_type == 'custom')
            {
                $sliderCustomFonts[] = get_post_meta($slideID->ID, 'subtitle-custom-font-url',true);
            }

            $subtitle2_font_type = get_post_meta( $slideID->ID, 'subtitle2-font-type' ,true );
            if(get_post_meta($slideID->ID, 'caption-style' ,true ) == 'style6')
            {
                if($subtitle2_font_type == 'google')
                {            
                    $slider_google_subtitle2_font = (Array)json_decode(get_post_meta($slideID->ID, 'subtitle2-font' ,true ));

                    if(!array_key_exists(key($slider_google_subtitle2_font),$fonts))
                    {
                        $variant = get_post_meta($slideID->ID, 'subtitle2-font-weight' ,true );
                        $slider_google_subtitle2_font[key($slider_google_subtitle2_font)] =  array($variant => $variant);
                        $sliderGoogleFonts = array_merge($sliderGoogleFonts, $slider_google_subtitle2_font);
                    }
                }
                elseif($subtitle2_font_type == 'custom')
                {
                    $sliderCustomFonts[] = get_post_meta($slideID->ID, 'subtitle2-custom-font-url',true);
                }
            }

        }

        //Google fonts
        $RequestedFonts=array();
        $sliderGoogleFonts = array_filter($sliderGoogleFonts);//remove empty elements
        $fontReq      = '//fonts.googleapis.com/css?family=';

        foreach($sliderGoogleFonts as $font => $variants)
        {
            //Repplace space in font name with plus character
            $query = preg_replace('/ /', '+', $font);

            if(count($variants))
                $query .= ':' . implode(',', $variants);

            $RequestedFonts[] = $query;
        }

        //Load default or user selected google fonts
        $fontReq .= implode('|', $RequestedFonts);

        if(count($RequestedFonts) > 0 )
        {
            wp_enqueue_style('epico_slider_fonts', $fontReq);
        }

        //Custom fonts
        $sliderCustomFonts = array_unique($sliderCustomFonts);//remove empty elements
        $i = 0;
        foreach ($sliderCustomFonts as $slider_font_url) {
            wp_enqueue_style('epico_custom_slider_fonts_'. $i, $slider_font_url);
            $i++;
        }

    }   
}
