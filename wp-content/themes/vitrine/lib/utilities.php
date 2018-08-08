<?php

// include simple_html_dom  :: A simple PHP HTML DOM parser written in PHP5+, supports invalid HTML, and provides a very easy way to handle HTML elements.
if (!function_exists('file_get_html')) require_once(EPICO_THEME_LIB . '/includes/simple_html_dom.php');
/*---------------------------------
     Post - Like - System
------------------------------------*/

    include(EPICO_THEME_LIB . '/admin/post-like.php');

/*---------------------------------
     Aqua Resizer : This small script will allow you to resize & crop WordPress images uploaded via the media uploader on the fly.
------------------------------------*/

if ( ! function_exists( 'aq_resize' ) ) {
    include(EPICO_THEME_LIB . '/admin/aq_resizer.php');
}

/*---------------------------------
    Multi thumbnails posts : used in epico_multi_post_thumbnails function
------------------------------------*/
require_once(EPICO_THEME_LIB . '/includes/multi-post-thumbnails.php');

/*---------------------------------
    Gathering In-line styles of pages of main-page + adding them to main-page
------------------------------------*/
if ( ! function_exists( 'epico_addVCCustomCss' ) ) {
    function  epico_addVCCustomCss() {

        $shortcodes_custom_css = '';

        //if is main-page
        if(is_page_template('main-page.php')) {
            $page_ids = get_all_page_ids();
            $current_page_id = get_the_ID();

            if( count($page_ids) > 0 ) {
                foreach ($page_ids as $page_id)
                {
                    $separate_page = get_post_meta($page_id, "page-position-switch", true);
                    
                    if ( $separate_page !== "0" && $page_id != $current_page_id ) {
                        $shortcodes_custom_css .= get_post_meta( $page_id, '_wpb_shortcodes_custom_css', true );
                    }
                }
        
                if ( $shortcodes_custom_css != '' ) {
                    echo '<style type="text/css" data-type="vc_shortcodes-custom-css">';
                    echo $shortcodes_custom_css;
                    echo '</style>';
                }
            }
        }
        else
        {
            if(function_exists("is_shop"))
            {
                $shortcodes_custom_css = get_post_meta( wc_get_page_id('shop'), '_wpb_shortcodes_custom_css', true );
                if(is_shop() && $shortcodes_custom_css != '' )
                {
                   echo '<style type="text/css" data-type="vc_shortcodes-custom-css">';
                   echo $shortcodes_custom_css;
                   echo '</style>';                
                }
            }

        }
    }
}

if ( ! function_exists( 'epico_addVCCustomCss_action' ) ) {
    function  epico_addVCCustomCss_action() {
		add_action( 'wp_head', 'epico_addVCCustomCss' , 1000 );
	}
}

epico_addVCCustomCss_action();


/*---------------------------------
    Social Link 
------------------------------------*/
if ( ! function_exists( 'epico_socialLink' ) ) {
    function epico_socialLink($optKey, $text, $class , $socialname) {
        $SocialText= $text;
        if(epico_opt($optKey)!=''){
             if(esc_attr($optKey)!='social_custom1_url'&& esc_attr($optKey)!='social_custom2_url')
        { ?>
        
            <li class="socialLinkShortcode textstyle <?php echo esc_attr($socialname); ?>">
                <a  href="<?php esc_url(epico_eopt($optKey)); ?>" target="_blank">
                    <span><?php echo esc_attr($SocialText); ?></span>
                </a>
            </li>
            
        <?php
        }elseif(esc_attr($optKey)=='social_custom1_url'|| esc_attr($optKey)=='social_custom2_url')
        { ?>
            <li class="socialLinkShortcode textstyle <?php echo esc_attr($socialname); ?>">
                <a  href="<?php esc_url(epico_eopt($optKey)); ?>" target="_blank">
                    <span><?php echo epico_eopt($SocialText); ?></span>
                </a>
            </li>
        <?php }
        }
    }
}

/*---------------------------------
    Social Icon 
------------------------------------*/
if ( ! function_exists( 'epico_socialIcon' ) ) {
    function epico_socialIcon($optKey, $class , $socialname) {
        if(epico_opt($optKey)!=''){
            if(esc_attr($optKey)!='social_custom1_url'&& esc_attr($optKey)!='social_custom2_url'){ ?>
          
            <li class="socialLinkShortcode iconstyle <?php echo esc_attr($socialname); ?>">
                <a  href="<?php esc_url(epico_eopt($optKey)); ?>" target="_blank">
                    <span class="firstIcon icon <?php echo esc_attr($class); ?>"></span>
                    <span class="SecoundIcon icon <?php echo esc_attr($class); ?>"></span>
                </a>
            </li>
            
        <?php
        }elseif(esc_attr($optKey)=='social_custom1_url'|| esc_attr($optKey)=='social_custom2_url')
        { ?>
            <li class="socialLinkShortcode iconstyle <?php echo esc_attr($socialname); ?>">
                <a  href="<?php esc_url(epico_eopt($optKey)); ?>" target="_blank">
                    <span class="icon <?php echo esc_attr($class); ?>"></span>
                </a>
            </li>
        <?php }
        }
    }
}


/*---------------------------------
     Font size in text editor
------------------------------------*/
if ( ! function_exists( 'epico_mce_buttons' ) ) {
    function  epico_mce_buttons( $buttons ) {
        array_unshift( $buttons, 'fontsizeselect' ); // Add Font Size Select

        return $buttons;
    }
}
if ( ! function_exists( 'epico_mce_buttons_filter' ) ) {
    function  epico_mce_buttons_filter() {
		add_filter( 'mce_buttons_2', 'epico_mce_buttons' );
	}
}

epico_mce_buttons_filter();


// Customize mce editor font sizes
if ( ! function_exists( 'epico_mce_text_sizes' ) ) {
    function epico_mce_text_sizes( $initArray ){
        $initArray['fontsize_formats'] = "12px 13px 14px 15px 16px 17px 18px 19px 20px 21px 22px 24px 26px 28px 36px 40px 48px 60px 72px 80px";
        return $initArray;
    }
}
if ( ! function_exists( 'epico_mce_text_sizes_filter' ) ) {
    function epico_mce_text_sizes_filter(){
		add_filter( 'tiny_mce_before_init', 'epico_mce_text_sizes' );
	}
}

epico_mce_text_sizes_filter();

/*---------------------------------
     Preloader 
------------------------------------*/

if ( ! function_exists ('epico_preloader' ) ) {

    function epico_preloader() {
    
        global $post;

        if ( isset( $post ) ) {

            if ( ! ( is_page() || is_archive() || is_search() ) && has_post_thumbnail( $post->ID ) ) {
                $thumb = get_post_thumbnail_id( $post->ID );
                $img_url = wp_get_attachment_url( $thumb, 'full' );  
                echo aq_resize( $img_url, 200, 200, true ); 
            } else if ( epico_opt( 'preloader-logo' ) != '' ) {
                epico_eopt( 'preloader-logo' );
            } else {
                echo get_template_directory_uri() . '/assets/img/preloader.png';
            }

        } else {

            if ( get_option( 'preloader-logo' ) != '' ) {
                epico_eopt( 'preloader-logor' );
            } else {
                echo get_template_directory_uri() . '/assets/img/preloader.png';
            }

        }

    }
}

/*---------------------------------
    Customizing wp_title
------------------------------------*/

if ( ! function_exists( 'epico_filter_wp_title' ) ) {

    function epico_filter_wp_title( $title, $separator ) {

        if ( is_feed() ) return $title;
        
        global $paged, $page;

        if ( is_search() ) {
            
            $title = sprintf( esc_html__( 'Search results for %s', 'vitrine' ), '"' . get_search_query() . '"' );

            if ( $paged >= 2 ) {
                $title .= " $separator " . sprintf( esc_html__( 'Page %s', 'vitrine' ), $paged );
            }

            $title .= " $separator " . get_bloginfo( 'name', 'display' );

            return $title;

        }

        return $title;

    }
}

if ( ! function_exists( 'epico_filter_wp_title_filter' ) ) {
    function epico_filter_wp_title_filter() {
		add_filter( 'wp_title', 'epico_filter_wp_title', 10, 2 );
	}
}
epico_filter_wp_title_filter();

/*---------------------------------
    Vertical menu - left And Right position
------------------------------------*/

if (!function_exists('epico_body_class_utility')) {
    
    function epico_body_class_utility($classes) {

        if(epico_is_shop_ajax_request())
            return;


        if ( function_exists( 'is_woocommerce' ) && is_shop() ) {
            $classes[] = 'is-woocommerce-shop';
        }
        
        //prelaoder
        if ( epico_opt('loader_display') != '0') {
            $classes[] = 'no-preloader';
            /* notloaded class prevent from running animation of page transition at first time */
            $classes[] = 'no-page-transition';
            $transition_type = epico_opt('page-transition-type');
            if($transition_type == '')
            {
                $transition_type = 'fade';
            }

            if($transition_type != 'none')
            {
                $classes[] = $transition_type;
            }
        }
        else
        {
            //use fade effect even in preloader mode
            $classes[] = 'fade';
        }

        if(epico_opt('woocommerce-notices') == '0' )
        {
            $classes[] = 'no_wc_notices';
        }
        
        //Menu
        $headerPosition = epico_opt('header-type');
        $headerStyle = epico_opt('header-style');
        $ajax_page_transition = epico_opt('ajax_page_transition');

        if($ajax_page_transition == 1)
        {
            $classes[] = 'ajax_page_transition';
        }

        //is left menu area turned on
        if (isset($headerPosition) && $headerPosition ==  7 ) { //left menu
            $classes[] = 'vertical_menu_enabled left_menu_enabled';
        } else if (isset($headerPosition) && $headerPosition == 8 ) { //right menu
            $classes[] = 'vertical_menu_enabled right_menu_enabled';
        }

        //Check wishlist
        if (class_exists('YITH_WCWL'))
        {
            $classes[] = 'wishlist-enable';
        }

        //Check compare
        if (class_exists('YITH_Woocompare'))
        {
            $classes[] = 'compare-enable';
        }
        

        //Product gallery
        if(function_exists('is_woocommerce')) {
            global $product;
            if(is_product())
            {
                $attachment_ids = $product-> get_gallery_image_ids();
                if(count($attachment_ids) > 0)
                {
                    $classes[] = 'have_gallery';
                }
            }
            
        }

        //Fixed add to cart
        $fixed_add_to_cart = epico_opt('shop-enable-fixed-addtocart');
        $catalog_mode = epico_opt('catalog_mode');
        if($fixed_add_to_cart == '1' && $catalog_mode == 0  && class_exists('Woocommerce') && is_product())
        {
            $classes[] = 'fixed-add-to-cart-enable';
        }

        //check if snap to scroll
        $snap_to_scroll = epico_get_meta('snap-to-scroll');
        $snap_to_scroll_nav_style = epico_get_meta('snap-to-scroll-nav-style');
        if($snap_to_scroll_nav_style != 0)
        {
            $snap_to_scroll_nav_style = ' snap-to-scroll-dark-nav';
        }
        else
        {
            $snap_to_scroll_nav_style = '';
        }
        if($snap_to_scroll == '1' && !is_page_template('main-page.php'))
        {
            $classes[] = 'snap-to-scroll snap-to-scroll-init' . esc_attr($snap_to_scroll_nav_style);
        }

        //product styles in shop(main page)
        if(class_exists('Woocommerce'))
        {
            $product_gutter = epico_opt('shop-product-gutter');

            if(is_shop() || is_product_category() || is_product_tag())
            {

                if($product_gutter == 0)
                {
                    $classes[] = 'no-gutter';
                }
            }

            $catalog_mode = epico_opt('catalog_mode');
            if($catalog_mode != 0)
            {
                $classes[] = 'catalog-mode';
            }
        }

        // check topbar is Enable Or not
        $topbar = epico_opt('topbar_display');
        if($topbar == '1')
        {
              $classes[] = 'has-topbar';
        }

        // check menu is has-scrollsticky styles or not 
        $menuStyle = epico_opt('header-style');
        if($menuStyle == 'scroll-sticky')
        {
              $classes[] = 'has-scrollstickymenu';
        }

        //check if is portfolio detail creative or not
        $pPostDetailType = epico_get_meta('portfolio-detail-style');

        if($pPostDetailType == 'portfolio_detail_creative')
        {
              $classes[] = 'is_portfolio_detail_creative';
        }

        //check if page has extra class name or not
        $extra_class = epico_get_meta('extra_class');
        if($extra_class)
        {
            $classes[] = $extra_class;
        }

        return $classes;
    }

}
if (!function_exists('epico_body_class_utility_filter')) {
    function epico_body_class_utility_filter() {
		add_filter('body_class','epico_body_class_utility');
	}
}

epico_body_class_utility_filter();

if ( ! function_exists( 'epico_body_attr' ) ) {
    function epico_body_attr() {
        global $post;
        if(function_exists('is_shop') && is_shop()){
            $page_id = wc_get_page_id('shop');
        }else{
            if($post)
            {
                $page_id = $post->ID;
            }
            else
            {
                $page_id = get_the_ID();
            }
            
        }
        echo 'data-pageid="' . esc_attr($page_id) . '"';
    }
}


/*---------------------------------
    Remove the excerpt "more"
------------------------------------*/
if ( ! function_exists( 'epico_new_excerpt_more' ) ) {
    function epico_new_excerpt_more($more) {
        return '';
    }
}
if ( ! function_exists( 'epico_new_excerpt_more_action' ) ) {
    function epico_new_excerpt_more_action() {
		add_filter('excerpt_more', 'epico_new_excerpt_more');
	}
}
epico_new_excerpt_more_action();


/*---------------------------------
    retrieves the attachment ID from the file URL
------------------------------------*/
if ( ! function_exists( 'epico_get_image_id' ) ) {
    function epico_get_image_id($image_url) {
        global $wpdb;
        $prefix = $wpdb->prefix;

        // generate Full size Image URL by removing image size info 
        $original_image_url = preg_replace('/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $image_url); 

        if($original_image_url == '')
        {
            $original_image_url = $image_url;
        }

        $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM " . $prefix . "posts" . " WHERE guid='%s';",$original_image_url));

        if(count($attachment))
            return $attachment[0];
        else
            return -1;
    }
}

/*---------------------------------
    Return theme option
------------------------------------*/
if ( ! function_exists( 'epico_opt' ) ) {
    function epico_opt($option){
        $opt = get_option(OPTIONS_KEY);
        if(!isset($opt[$option]))
            return '';

        return stripslashes($opt[$option]);
    }
}


if ( ! function_exists( 'epico_eopt' ) ) {
    function epico_eopt($option){
        echo epico_opt($option);
    }
}

/*---------------------------------
    Gets array value with specified key, if the key doesn't exist  default value is returned
------------------------------------*/
if ( ! function_exists( 'epico_array_value' ) ) {
    function epico_array_value($key, $arr, $default='') {
        return array_key_exists($key, $arr) ? $arr[$key] : $default;
    }
}

/*---------------------------------
    Deletes attachment by given url
------------------------------------*/
if ( ! function_exists( 'epico_delete_attachment' ) ) {
    function epico_delete_attachment( $url ) {
        global $wpdb;
        $prefix = $wpdb->prefix; 
        
        // We need to get the image's meta ID.
        $results = $wpdb->get_results($wpdb->prepare("SELECT ID FROM " . $prefix . "posts" . " where guid = %s AND post_type = 'attachment" , $url));

        // And delete it
        foreach ( $results as $row ) {
            wp_delete_attachment( $row->ID );
        }
    }
}

/*---------------------------------
    get page meta
------------------------------------*/
if ( ! function_exists( 'epico_get_meta' ) ) {

    function epico_get_meta($key = '', $single = true)
    {
        $pid = null;

        if(in_the_loop() || is_single() || (is_page() && !is_home()))
        {
            $pid = get_the_ID();
        }

        //Special case for blog page
        if(is_home() && !is_front_page())
        {
            $pid = get_option('page_for_posts');
        }
        
        if(function_exists("is_shop"))
        {
            if(is_shop()) {

                $pid = get_option('woocommerce_shop_page_id');//use woocommerce function : wc_get_page_id('shop') instead of get_option('woocommerce_shop_page_id')
            }
        }

        if(null == $pid)
            return '';
        
        if($key == '')
            return get_post_meta($pid, $key, false);//return all post metas
        else
            return get_post_meta($pid, $key, $single);
    }
}

/*---------------------------------
    Get title of page inside its loop
------------------------------------*/
if ( ! function_exists( 'epico_get_the_title' ) ) {

    function epico_get_the_title()
    {
        $pid = null;

        if(in_the_loop() || is_single() || (is_page() && !is_home()))
        {
            $pid = get_the_ID();
        }

        //Special case for blog page
        if(is_home() && !is_front_page())
        {
            $pid = get_option('page_for_posts');
        }
        
        if(function_exists("is_shop"))
        {
            if(is_shop()) {

                $pid = get_option('woocommerce_shop_page_id');//use woocommerce function : wc_get_page_id('shop') instead of get_option('woocommerce_shop_page_id')
            }
        }

        if(null == $pid)
            return '';
        
        return get_the_title($pid);//return all post metas
    }
}
/*---------------------------------
    Get video URL from known sources such as YouTube and vimeo 
------------------------------------*/
if ( ! function_exists( 'epico_extract_video_info' ) ) {
    function epico_extract_video_info($string)
    {
        //check for YouTube video URL
        if(preg_match('/https?:\/\/(?:www\.)?youtube\.com\/watch\?v=[^&\n\s"<>]+/i', $string, $matches ))
        {
            $url = parse_url($matches[0]);
            parse_str($url['query'], $queryParams);

            return array('type'=>'youtube', 'url'=> $matches[0], 'id' => $queryParams['v']);
        }
        //Vimeo
        else if(preg_match('/https?:\/\/(?:www\.)?vimeo\.com\/\d+/i', $string, $matches))
        {
            $url = parse_url($matches[0]);

            return array('type'=>'vimeo', 'url'=> $matches[0], 'id' => ltrim($url['path'], '/'));
        }

        return null;
    }
}

/*---------------------------------
    Get Audio URL from SoundCloud
------------------------------------*/
if ( ! function_exists( 'epico_extract_audio_info' ) ) {
    function epico_extract_audio_info($string)
    {
        //check for soundcloud url
        if(preg_match('/https?:\/\/(?:www\.)?soundcloud\.com\/[^&\n\s"<>]+\/[^&\n\s"<>]+\/?/i', $string, $matches ))
        {
            return array('type'=>'soundcloud', 'url'=> $matches[0]);
        }

        return null;
    }
}

if ( ! function_exists( 'epico_get_video_meta' ) ) {
    function epico_get_video_meta(array &$video)
    {
        if($video['type']  != 'youtube' && $video['type'] != 'vimeo')
            return null;

        $ret = epico_get_url_content($video['url']/*, '127.0.0.1:8080'*/);

        if(is_array($ret))
            return 'Server Error: ' . $ret['error'] . " \nError No: " . $ret['errorno'];

        if(trim($ret) == '')
            return 'Error: got empty response from youtube';

        $html = epico_str_get_html($ret);
        $vW   = $html->find('meta[property="og:video:width"]');
        $vH   = $html->find('meta[property="og:video:height"]');

        if(count($vW) && count($vH))
        {
            $video['width']  = $vW[0]->content;
            $video['height'] = $vH[0]->content;
        }

        return null;
    }
}

if ( ! function_exists( 'epico_soundcloud_get_embed' ) ) {
    function epico_soundcloud_get_embed($url)
    {
        $json = epico_get_url_content("http://soundcloud.com/oembed?format=json&url=$url"/*, '127.0.0.1:86'*/);

        if(is_array($json))
            return 'Server Error: ' . $json['error'] . " \nError No: " . $json['errorno'];

        if(trim($json) == '')
            return 'Error: got empty response from soundcloud';

        //Convert the response string to PHP object
        $data = json_decode($json);

        if(NULL == $data)
            return "Cant decode the soundcloud response \nData: $json" ;

        //TODO: add additional error checks

        return $data->html;
    }
}

/*---------------------------------
    Downloads data from given URL
------------------------------------*/
if ( ! function_exists( 'epico_get_url_content' ) ) {
    function epico_get_url_content($url, $proxy='')
    {
        $args = array(
            'headers' => array(),
            'body' => null,
            'sslverify' => true,
        );

        $response = wp_remote_get($url, array(
                'timeout' => 45,
            )
        );

        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
            $ret = array('error' => $error_message, 'errorno' => '');
        } else {
            $ret = $response['body'];
        }

        return $ret;
    }
}

/*---------------------------------
    Revolution slider
------------------------------------*/
if ( ! function_exists( 'epico_get_revolutionSlider_slides' ) ) {
    function epico_get_revolutionSlider_slides() {

        if (class_exists("RevSlider")) {
        
			// Get WPDB Object
			global $wpdb;

			// Table name
			$prefix = $wpdb->prefix;

			// Get sliders
			$sliders = $wpdb->get_results( "SELECT * FROM " . $prefix . "revslider_sliders" . "
												ORDER BY id ASC LIMIT 100" );                  
			$items = array('no-slider'=>esc_html__('No slider','vitrine'));

			// Iterate over the sliders
			foreach($sliders as $key => $item) {
				$items[$item->alias] = $item->alias;
			}
			return $items;
       }
       
    }
}


/*---------------------------------
    Generate Portfolio Skill array 
------------------------------------*/
if ( ! function_exists( 'epico_generate_portfolio_skill' ) ) {
    function epico_generate_portfolio_skill()
    {
        $portfolioTerms = get_terms('skills');
        $skillsArray = array();

        if ( ! empty( $portfolioTerms ) && ! is_wp_error( $portfolioTerms ) ){ // check is portfolio plugin is active or not 
            foreach($portfolioTerms as $term) {
                $skillArray = array(
                                    'type' => 'checkbox',
                                    'checked' => true,
                                    'value' => 'visible',
                                    'label' => $term->name
                                );

                $skillsArray["term-".$term->term_id] = $skillArray;
            }
        }
        return $skillsArray;
    }
}

/*---------------------------------
    CF7
------------------------------------*/
if ( ! function_exists( 'epico_get_contact_form7_forms' ) ) {
    function epico_get_contact_form7_forms()
    {
        // Get WPDB Object
        global $wpdb;

        // Table name
        $table_name = $wpdb->prefix . "posts";

        // Get forms
        $forms = $wpdb->get_results( "SELECT * FROM $table_name
                                      WHERE post_type='wpcf7_contact_form'
                                      LIMIT 100" );

        $items = array('no-form'=>'');

        // Iterate over the sliders
        foreach($forms as $key => $item) {
            $items[$item->ID] = $item->post_title;
        }

        return $items;
    }
}

/*---------------------------------
    post pagination Search And Archive page!
------------------------------------*/
if ( ! function_exists( 'epico_get_pagination' ) ) {
    function epico_get_pagination($query = null, $range = 3) {
        global $paged, $wp_query;

        $q = $query == null ? $wp_query : $query;
        $output = '';

        // pages that exist
        if ( !isset($max_page) ) {
            $max_page = $q->max_num_pages;
        }

        // We need the pagination only if there is more than 1 page
        if ( $max_page < 2 )
            return $output;

        $output .= '<div class="post-pagination">';

        if ( !$paged ) $paged = 1;

        // To the previous page
        if($paged > 1)
            $output .= '<a class="prev-page-link" href="' . esc_url(get_pagenum_link($paged-1)) . '">'. esc_html__('Prev', 'vitrine') .'</a>';

        if ( $max_page > $range + 1 ) {
            if ( $paged >= $range )
                $output .= '<a href="' . esc_url(get_pagenum_link(1)) . '">1</a>';
            if ( $paged >= ($range + 1) )
                $output .= '<span class="page-numbers">&hellip;</span>';
        }

        // We need the sliding effect only if there are more pages than is the sliding range
        if ( $max_page > $range ) {
            // When closer to the beginning
            if ( $paged < $range ) {
                for ( $i = 1; $i <= ($range + 1); $i++ ) {
                    $output .= ( $i != $paged ) ? '<a href="' . esc_url(get_pagenum_link($i)) .'">'.$i.'</a>' : '<span class="this-page">'.$i.'</span>';
                }
                // When closer to the end
            } elseif ( $paged >= ($max_page - ceil(($range/2))) ) {
                for ( $i = $max_page - $range; $i <= $max_page; $i++ ) {
                    $output .= ( $i != $paged ) ? '<a href="' . esc_url(get_pagenum_link($i)) .'">'.$i.'</a>' : '<span class="this-page">'.$i.'</span>';
                }
                // Somewhere in the middle
            } elseif ( $paged >= $range && $paged < ($max_page - ceil(($range/2))) ) {
                for ( $i = ($paged - ceil($range/2)); $i <= ($paged + ceil(($range/2))); $i++ ) {
                    $output .= ($i != $paged) ? '<a href="' . esc_url(get_pagenum_link($i)) .'">'.$i.'</a>' : '<span class="this-page">'.$i.'</span>';
                }
            }
            // Less pages than the range, no sliding effect needed
        } else {
            for ( $i = 1; $i <= $max_page; $i++ ) {
                $output .= ($i != $paged) ? '<a href="' . esc_url(get_pagenum_link($i)) .'">'.$i.'</a>' : '<span class="this-page">'.$i.'</span>';
            }
        }

        if ( $max_page > $range + 1 ){
            // On the last page, don't put the Last page link
            if ( $paged <= $max_page - ($range - 1) )
                $output .= '<span class="page-numbers">&hellip;</span><a href="' . esc_url(get_pagenum_link($max_page)) . '">' . $max_page . '</a>';
        }

        // Next page
        if($paged < $max_page)
            $output .= '<a class="next-page-link" href="' . esc_url(get_pagenum_link($paged+1)) . '">'. esc_html__('Next', 'vitrine') .'</a>';

        $output .= '</div><!-- post-pagination -->';

        echo $output;
    }
}

/*---------------------------------
     Add  featured image column in admin panel
------------------------------------*/
if ( ! function_exists( 'epico_add_post_thumbnail_column' ) ) {
    function epico_add_post_thumbnail_column($cols){
      $cols['epico_post_thumb'] = esc_html__('Featured', 'vitrine');
      return $cols;
    }
}
if ( ! function_exists( 'epico_add_post_thumbnail_column_filter' ) ) {
    function epico_add_post_thumbnail_column_filter(){
		add_filter('manage_posts_columns', 'epico_add_post_thumbnail_column', 5); // Add the posts columns filter.
		add_filter('manage_pages_columns', 'epico_add_post_thumbnail_column', 5); // Add the pages columns filter.
	}
}
epico_add_post_thumbnail_column_filter();

/*---------------------------------
    Add support for Vertical Featured Images.
------------------------------------*/
if ( ! function_exists( 'epico_thumbnail_vertical_check' ) ) {
    function epico_thumbnail_vertical_check( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
        $image_data = wp_get_attachment_image_src( $post_thumbnail_id , 'large' );
        //Get the image width and height from the data provided by wp_get_attachment_image_src()
        $width  = $image_data[1];
        $height = $image_data[2];
        if ( $height > $width ) {
            $html = str_replace( 'attachment-', 'vertical-image attachment-', $html );
        }
        return $html;
    }
}

if ( ! function_exists( 'epico_thumbnail_vertical_check_filter' ) ) {
    function epico_thumbnail_vertical_check_filter() {
		add_filter( 'post_thumbnail_html', 'epico_thumbnail_vertical_check', 10, 5 );
	}
}
epico_thumbnail_vertical_check_filter();

/*---------------------------------
    Hook into the posts an pages column managing.
------------------------------------*/
if ( ! function_exists( 'epico_display_post_thumbnail_column' ) ) {
    function epico_display_post_thumbnail_column($col, $id){
      switch($col){
        case 'epico_post_thumb':
            if( function_exists('the_post_thumbnail') ) {
            
                echo the_post_thumbnail( 'admin-list-thumb' );
               
            } else {
                echo 'Not supported in theme';
            }
          break;
      }
    }
}

if ( ! function_exists( 'epico_display_post_thumbnail_column_action' ) ) {
    function epico_display_post_thumbnail_column_action(){
		add_action('manage_posts_custom_column', 'epico_display_post_thumbnail_column', 5, 2);
		add_action('manage_pages_custom_column', 'epico_display_post_thumbnail_column', 5, 2);
	}
}
epico_display_post_thumbnail_column_action();

/*---------------------------------
    Search Pages by content 
------------------------------------*/
if ( ! function_exists( 'epico_search_pages_by_content' ) ) {
    function epico_search_pages_by_content($cnt)
    {
        // Get WPDB Object
        global $wpdb;

        $sql = $wpdb->prepare( "SELECT * FROM $wpdb->posts WHERE post_type='page' AND post_status='publish' AND post_content LIKE %s",
            '%' . $wpdb->esc_like($cnt) . '%' );

        // Get forms
        $pages = $wpdb->get_results( $sql );

        return $pages;
    }
}


/*---------------------------------
    Custom Login Logo 
------------------------------------*/
if ( ! function_exists( 'epico_login_logo' ) ) {
    function epico_login_logo() {

        $login_logo = ( epico_opt('login-logo') ? epico_opt('login-logo') : EPICO_THEME_LIB_URI . '/admin/img/wp_login_logo.png' );
        echo '<style type="text/css"> h1 a { background: url(' . esc_url($login_logo) . ') center no-repeat !important; width:302px !important; height:67px !important; } </style>';
    }
}
if ( ! function_exists( 'epico_login_logo_action' ) ) {
    function epico_login_logo_action() {
		add_action('login_head', 'epico_login_logo');
	}
}
epico_login_logo_action();

/*---------------------------------
    Sidebar widget count 
------------------------------------*/
if ( ! function_exists( 'epico_count_sidebar_widgets' ) ) {
    function epico_count_sidebar_widgets( $sidebar_id, $echo = false ) {
        $sidebars = wp_get_sidebars_widgets();

        if( !isset( $sidebars[$sidebar_id] ) )
            return -1;

        $cnt = count( $sidebars[$sidebar_id] );

        if( $echo )
            echo $cnt;
        else
            return $cnt;
    }
}

/*---------------------------------
    Get Sidebar 
------------------------------------*/
if ( ! function_exists( 'epico_get_sidebar' ) ) {
    function epico_get_sidebar($id = 1, $class='') {

        if(epico_count_sidebar_widgets($id) < 1)
            $class .= ' no-widgets';
    ?>
        <div class="<?php echo esc_attr($class); ?>"><?php dynamic_sidebar($id); ?></div>
    <?php
    }
}

/*---------------------------------
     WooCommerce columns
------------------------------------*/
if ( ! function_exists( 'epico_custom_loop_columns' ) ) {
    function epico_custom_loop_columns() {
        return epico_opt('shop-column');
    }
}

if ( ! function_exists( 'epico_custom_loop_columns_filter' ) ) {
    function epico_custom_loop_columns_filter() {
		add_filter( 'loop_shop_columns', 'epico_custom_loop_columns' );
	}
}
epico_custom_loop_columns_filter();

/*---------------------------------
     WooCommerce search redirect to product detail (when there is just 1 product)
------------------------------------*/
if(!function_exists('epico_redirect_single_search_result_filter')){
	function epico_redirect_single_search_result_filter(){
		add_filter( 'woocommerce_redirect_single_search_result', '__return_false' );
	}
}

epico_redirect_single_search_result_filter();

/*---------------------------------
    Get account/login link 
------------------------------------*/
if ( ! function_exists( 'epico_get_myaccount_link' ) ) {
    function epico_get_myaccount_link() {
        $myaccount_url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
        $link_title = ( is_user_logged_in() ) ?  esc_html__( 'My Account', 'vitrine' ) : esc_html__( 'Login', 'vitrine' );
        $link_class= ( is_user_logged_in() ) ?  '' : ' class="login-link-popup no_djax"';
        
        return '<a ' . $link_class . 'href="' . esc_url( $myaccount_url ) . '">' . $link_title . '</a>';
    }
}

/*-----------------------------------
    Redeclare Original WC functions - cart & checkout buttons in cart
------------------------------------*/
if ( ! function_exists( 'woocommerce_widget_shopping_cart_button_view_cart' ) ) {
    function woocommerce_widget_shopping_cart_button_view_cart() {
        //add data-hover attribute for checkout and view cart buttons
        echo '<a href="' . esc_url( wc_get_cart_url() ) . '" class="button wc-forward">
                <span data-hover="' . esc_attr__( "View Cart", "vitrine" ) . '">' . esc_html__( "View Cart", "vitrine" ) . '</span>
            </a>';
    }
}

if ( ! function_exists( 'woocommerce_widget_shopping_cart_proceed_to_checkout' ) ) {
    function woocommerce_widget_shopping_cart_proceed_to_checkout() {
        echo '<a href="' .  esc_url( wc_get_checkout_url() ) . '" class="button checkout wc-forward"  >
                <span data-hover="' . esc_attr__( 'Checkout', 'vitrine' ) .'">' . esc_html__( 'Checkout', 'vitrine' ) . '</span>
            </a>';
    }
}

/*-----------------------------------
    Product review
------------------------------------*/
if ( ! function_exists( 'epico_product_review' ) ) {
    function epico_product_review($comment) {
        $verified = wc_review_is_from_verified_owner( $comment->comment_ID );
        if ( '0' === $comment->comment_approved ) { ?>

            <p class="meta"><em><?php esc_html_e( 'Your comment is awaiting approval', 'vitrine' ); ?></em></p>

        <?php } else { ?>

            <p class="meta">
                <strong class="woocommerce-review__author" itemprop="author"><?php comment_author(); ?></strong> <?php
                    
                    if ( 'yes' === get_option( 'woocommerce_review_rating_verification_label' ) && $verified ) {
                        echo '<em class="woocommerce-review__verified verified">(' . esc_html__( 'verified owner', 'vitrine' ) . ')</em> ';
                    }

                    if ( get_option( 'woocommerce_review_rating_verification_label' ) === 'yes' )
                        if ( wc_customer_bought_product( $comment->comment_author_email, $comment->user_id, $comment->comment_post_ID ) )
                            echo '<em class="verified">(' . esc_html__( 'verified owner', 'vitrine' ) . ')</em> ';
                ?><time itemprop="datePublished" datetime="<?php echo esc_attr(get_comment_date( 'c' )); ?>"><?php printf(esc_html__('%1$s', 'vitrine'), get_comment_date(get_option( 'date_format' )) ); ?></time>

            </p>

        <?php }
    }
}


if ( ! function_exists( 'epico_product_review_action' ) ) {
    function epico_product_review_action() {
		add_action('woocommerce_review_before_comment_meta', 'epico_product_review',9);
		remove_action( 'woocommerce_review_meta', 'woocommerce_review_display_meta', 10 );
	}
}
epico_product_review_action();

/*-----------------------------------
     WooCommerce
------------------------------------*/
if ( ! function_exists( 'epico_woocommerce_is_attribute_in_product_name' ) ) {
    function epico_woocommerce_is_attribute_in_product_name($is_in_name, $attribute, $name )
    {
        $is_in_name = false;
    }
}
//when attribute names are in product name(variable product), WC does not display attributes in cart, So we set it to False to show attributes
if ( ! function_exists( 'epico_woocommerce_is_attribute_in_product_name_action' ) ) {
    function epico_woocommerce_is_attribute_in_product_name_action(){
		add_filter( 'woocommerce_is_attribute_in_product_name', 'epico_woocommerce_is_attribute_in_product_name' , 10 , 3);
	}
}

epico_woocommerce_is_attribute_in_product_name_action();

//display categories and subcategories as text.
if(!function_exists('epico_woocommerce_product_subcategories')){
    function epico_woocommerce_product_subcategories(){
		
		$parentid = get_queried_object_id();
		$args = array(
			'parent'     => $parentid,
			'hide_empty' => false
		);
 
		$terms = get_terms( 'product_cat', $args );        

		if ( $terms )
		{
			foreach ( $terms as $term ){
                if ($term->count > 0 ) { // prevent to display empty categories               
				    echo '<li><a href="' .  esc_url( get_term_link( $term ) ) . '" class="' . $term->slug . '">' . $term->name . '</a></li>';
                } 
			}  
        }
	}
}

//Redeclare original woocommerce_content function of WC to change structure of shop
if ( ! function_exists( 'woocommerce_content' ) ) {
    function woocommerce_content() {

        if ( is_singular( 'product' ) ) {

            while ( have_posts() ) : the_post();

                wc_get_template_part( 'content', 'single-product' );

            endwhile;

        } else { ?>

            <?php do_action( 'woocommerce_archive_description' ); ?>

            <?php if ( have_posts() ) : ?>

                <?php do_action('woocommerce_before_shop_loop'); ?>

                <?php woocommerce_product_loop_start(); ?>

                    <?php while ( have_posts() ) : the_post(); ?>

                        <?php wc_get_template_part( 'content', 'product' ); ?>

                    <?php endwhile; // end of the loop. ?>

                <?php woocommerce_product_loop_end(); ?>

                <?php do_action('woocommerce_after_shop_loop'); ?>

            <?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

                <?php do_action('woocommerce_before_shop_loop'); ?>
                <?php wc_get_template( 'loop/no-products-found.php' ); ?>
                <?php do_action('woocommerce_after_shop_loop'); ?>

            <?php endif;

        }
    }
}



// show subcategories in shop and categories pages
//if ( ! function_exists( 'epico_wc_subcategories' ) ) {
//    function epico_wc_subcategories() {
//        $term 			= get_queried_object();
//        $cateID 		= empty( $term->term_id ) ? 0 : $term->term_id;
//        $display_type_wc_setting = get_option('woocommerce_category_archive_display');
//        $display_type_cat_setting = get_woocommerce_term_meta( $cateID, 'display_type' );
//        //show subcategories after shop-filter
//        if ( (is_product_category() && $display_type_cat_setting == 'subcategories') || (is_product_category() && $display_type_wc_setting == 'subcategories' && $display_type_cat_setting == '') || ( is_shop() && get_option('woocommerce_shop_page_display') == 'subcategories') )
//        { 
//           $class = '';	
//           $class = 'shop-'. epico_opt('shop-column') .'column ';
//           $fullwidth = epico_opt('shop-enable-fullwidth');
//           $class .= ($fullwidth == 0 ? ' ': 'fullwidthshop ');

//           echo '<div class="woocommerce wc-categories"><ul class="products  '. esc_attr($class) .'">' ;
//           woocommerce_maybe_show_product_subcategories($loop_html);
//           echo '</ul></div>';
//        }
//    }
//}

//if ( ! function_exists( 'epico_wc_subcategories_action' ) ) {
//    function epico_wc_subcategories_action() {
//        add_action( 'woocommerce_before_shop_loop', 'epico_wc_subcategories', 50 );
//    }
//}
//epico_wc_subcategories_action();
	
	
// Redeclare original woocommerce_get_product_thumbnail 
if ( ! function_exists( 'woocommerce_get_product_thumbnail' ) ) {
    function woocommerce_get_product_thumbnail( $size = 'shop_catalog', $placeholder_width = 0, $placeholder_height = 0  ) {
        global $post;
        $image_size = apply_filters( 'single_product_archive_thumbnail_size', $size );

        if ( has_post_thumbnail() )
        {

            $props = wc_get_product_attachment_props( get_post_thumbnail_id(), $post );
            $img = get_the_post_thumbnail( $post->ID, $image_size , array(
                'title'  => $props['title'],
                'alt'    => $props['alt'],
            ) );

            $img = str_replace("src=", 'src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src=', $img);

            return "<div class=\"imageswrap productthumbnail lazy-load lazy-load-on-load\" style=\"padding-top:" . esc_attr(epico_get_height_percentage($img)) . "%;\">". $img ."</div>";

        }
        elseif ( wc_placeholder_img_src() ) {
            return wc_placeholder_img( $image_size );
        }
            
    }
}

// Set appropriate image size for product thumbnails in masonry shop
if ( ! function_exists( 'epico_product_thumbnail_masonry_size' ) ) {
    function epico_product_thumbnail_masonry_size( $size ) {
        if(epico_opt('shop-layout') == 'masonry')
        {
            return 'epico_product_thumbnail-auto-height';
        }
        return $size;
    }
}

if ( ! function_exists( 'epico_product_thumbnail_masonry_size_filter' ) ) {
    function epico_product_thumbnail_masonry_size_filter() {
		add_filter( 'single_product_archive_thumbnail_size', 'epico_product_thumbnail_masonry_size' );
	}
}
epico_product_thumbnail_masonry_size_filter();


if ( ! function_exists( 'epico_get_height_percentage' ) ) {
    function epico_get_height_percentage($image, $width=1, $height=1) {
        if($image != '')
        {
            $re = "/width=\"(\\d+)\".*height=\"(\\d+)\"/";

            preg_match($re, $image, $matches);
			if(isset($matches[1]) && isset($matches[2]))
			{
            $height = $matches[2];
            $width = $matches[1];
        }
			else {
				return 100;
			}

        }

        if($width == 0)
            return 100;

        return ( $height / $width ) * 100;
    }
}


if(!function_exists('epico_woo_hide_page_title')) {
    function epico_woo_hide_page_title() {
        return false;
    }
}
    

if(!function_exists('epico_woo_hide_page_title_filter')) {
    function epico_woo_hide_page_title_filter() {   
		add_filter( 'woocommerce_show_page_title' , 'epico_woo_hide_page_title' );
	}
}
epico_woo_hide_page_title_filter();


if(!function_exists('epico_woocommerce_header_add_to_cart_fragment')) {
    function epico_woocommerce_header_add_to_cart_fragment( $fragments ) {
        ob_start();
        ?>
            <div class="cart-contents"><div class="cartContentsCount"><?php echo  WC()->cart->cart_contents_count; ?></div></div>
        <?php
    
        $fragments['div.cart-contents'] = ob_get_clean();

        return $fragments;
    }
}
// Ensure cart contents update when products are added to the cart via AJAX
if(!function_exists('epico_woocommerce_header_add_to_cart_fragment_filter')) {
    function epico_woocommerce_header_add_to_cart_fragment_filter() {
		add_filter( 'woocommerce_add_to_cart_fragments', 'epico_woocommerce_header_add_to_cart_fragment' );
	}
}
epico_woocommerce_header_add_to_cart_fragment_filter();


/********** Notices **********/
//Hook into ajax add-to-cart functionality to add notices even when woocommerce_cart_redirect_after_add == yes
if(!function_exists('epico_woocommerce_addtocart_add_notices')) {
    function epico_woocommerce_addtocart_add_notices($product_id) {
        if ( get_option( 'woocommerce_cart_redirect_after_add' ) != 'yes' ) {
            $quantity          = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( $_POST['quantity'] );
            wc_add_to_cart_message( array( $product_id => $quantity ), true );
        }
    }
}

if(!function_exists('epico_woocommerce_addtocart_add_notices_action')) {
    function epico_woocommerce_addtocart_add_notices_action() {
		add_action( 'woocommerce_ajax_added_to_cart', 'epico_woocommerce_addtocart_add_notices' );
	}
}
epico_woocommerce_addtocart_add_notices_action();


//Print notices in reponse of adding item to cart ( cart widget) to access it through ajax add-to-cart
if(!function_exists('epico_woocommerce_addtocart_print_notices')) {
    function epico_woocommerce_addtocart_print_notices() {
		if(epico_is_shop_ajax_add_to_cart())
		{
			if(epico_opt('woocommerce-notices') != '0')
			{
				wc_print_notices(); // print notices to be shown in popup style
			}
			else{
				wc_clear_notices();//clear notices silently
			}
		}

    }
}
if(!function_exists('epico_woocommerce_addtocart_print_notices_action')) {
    function epico_woocommerce_addtocart_print_notices_action() {
		add_action( 'woocommerce_after_mini_cart', 'epico_woocommerce_addtocart_print_notices' );
		
		//print notices in loop products shortcodes
		add_action( 'woocommerce_shortcode_before_single_product_loop', 'wc_print_notices', 10 );
		add_action( 'woocommerce_shortcode_before_single_product_2_loop', 'wc_print_notices', 10 );
		add_action( 'woocommerce_shortcode_before_products_loop', 'wc_print_notices', 10 );
		add_action( 'woocommerce_shortcode_before_sale_products_loop', 'wc_print_notices', 10 );
		add_action( 'woocommerce_shortcode_before_best_selling_products_loop', 'wc_print_notices', 10 );
		add_action( 'woocommerce_shortcode_before_top_rated_products_loop', 'wc_print_notices', 10 );
		add_action( 'woocommerce_shortcode_before_featured_products_loop', 'wc_print_notices', 10 );
		add_action( 'woocommerce_shortcode_before_product_attribute_loop', 'wc_print_notices', 10 );
		add_action( 'woocommerce_shortcode_before_recent_products_loop', 'wc_print_notices', 10 );
	}
}
epico_woocommerce_addtocart_print_notices_action();
/********** End of Notices **********/


// remove archive desciption
if(!function_exists('epico_woocommerce_archive_description_action')){
	function epico_woocommerce_archive_description_action(){
		remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );
	}
}

epico_woocommerce_archive_description_action();

if(!function_exists('epico_remove_ptags_around_shop_page_content')) {
    function epico_remove_ptags_around_shop_page_content($content)
    {
        if (strpos($content, '</div>') !== false) {
            return preg_replace( '/<p>(.+)<\/p>$/Uuis', '$1', $content );
        }

        return $content;
    }
}

if(!function_exists('epico_remove_ptags_around_shop_page_content_filter')) {
    function epico_remove_ptags_around_shop_page_content_filter(){
		add_filter('woocommerce_format_content', 'epico_remove_ptags_around_shop_page_content');
	}
}
epico_remove_ptags_around_shop_page_content_filter();


// Product Filter and Porduct Order 
if(!function_exists('epico_woocommerce_shop_filter_action')){
	function epico_woocommerce_shop_filter_action(){

		if(epico_opt('shop-filter'))
		{
			// Enable Product filter
			remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
			remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
			add_action( 'woocommerce_before_shop_loop', 'epico_woocommerce_filter', 40 );
			
		} elseif(epico_opt('shop-filter') != 1) {
			
			if (epico_opt('shop-ordering')) {            
       		//disable product filtes and enable product order 
			} else {
				
				//disable Product Filter and Disable Product Order 
				remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
				remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
    		}
    
			if(is_active_sidebar('woocommerce-sidebar'))
				add_action( 'woocommerce_before_shop_loop', 'epico_woocommerce_sidebar', 40 );
		}
	}
}
epico_woocommerce_shop_filter_action();

if(!function_exists('epico_woocommerce_container')) {
    function epico_woocommerce_container() {
        $fullwidth = epico_opt('shop-enable-fullwidth');
        $sidebarPos = epico_opt('shop-sidebar-position');
        //if(is_shop() || is_product_category() || is_product_tag()) {
        if (!is_product()) {

            if( 0 == $sidebarPos ) {
                if( $fullwidth != 1 )
                {
                    echo '<div class="shop_top_padding container">';
                }
                else
                {
                    echo '<div class="shop_top_padding shop_fullwidth_widthoutSidebar">';
                }
                echo '<div class="container">';
            }
            else
            {
                if( $fullwidth != 1 )
                {
                    echo '<div class="shop_top_padding container">';
                }
                else
                {
                    echo '<div class="shop_top_padding shop_fullwidth_sidebar">';
                }

                $contentClass = 'span9 has-wc-sidebar';
                if( 1 == $sidebarPos)
                    $contentClass .= ' float-right';

                echo '<div class="' . esc_attr($contentClass) . '">';


            }

        } 
    }
}

if(!function_exists('epico_woocommerce_top_container_close')) {
    function epico_woocommerce_top_container_close() {
        $sidebarPos = epico_opt('shop-sidebar-position');
        if( 0 == $sidebarPos ) {
            echo '</div>';
        }
    }
}

if(!function_exists('epico_woocommerce_container_close')) {
    function epico_woocommerce_container_close() {
        //if(is_shop() || is_product_category() || is_product_tag()) {
         if (!is_product()){
            $sidebarPos = epico_opt('shop-sidebar-position');
            if( $sidebarPos != 0) {
                echo '</div>';
                echo '<!-- Sidebar -->';
                ob_start();
                epico_get_sidebar('woocommerce-sidebar');
                $sidebar = ob_get_clean();
                echo '<div id="woocommerce-sidebar" class="span3">' . $sidebar . '</div>';
            }

            echo '</div>';            
        }
    }
}   
if(!function_exists('epico_woocommerce_sidebar')) {
    function epico_woocommerce_sidebar() {


        // filter button in Mobile
        echo    '<span class="filterBgTabletPhone hidden-desktop"></span>';
        echo    '<span class="shop-filter-toggle  hidden-desktop">
                    <span class="shop-filter-text no-select"><span class="closetext">'.esc_html__('Filter','vitrine') .'</span></span>
                </span>';

        echo '<div class="shop-filter woocommerce-sidebar sidebar widget-area"></div>';
    }
}

if(!function_exists('epico_woocommerce_sidebar_action')) {
    function epico_woocommerce_sidebar_action() {
		add_action( 'woocommerce_before_shop_loop', 'epico_woocommerce_container', 5 );
		add_action( 'woocommerce_before_shop_loop', 'epico_woocommerce_top_container_close', 41 );
		add_action( 'woocommerce_after_shop_loop', 'epico_woocommerce_container_close', 40 );
	}
}
epico_woocommerce_sidebar_action();


if(!function_exists('epico_woocommerce_filter')) {
    function epico_woocommerce_filter() {
		
		// Find the category + category parent, if applicable
		$term 			= get_queried_object();
		$parent_id 		= empty( $term->term_id ) ? 0 : $term->term_id;

				
		// NOTE: using child_of instead of parent - this is not ideal but due to a WP bug ( https://core.trac.wordpress.org/ticket/15626 ) pad_counts won't work
		$product_categories = get_categories( apply_filters( 'woocommerce_product_subcategories_args', array(
			'parent'       => $parent_id,
			'menu_order'   => 'ASC',
			'hide_empty'   => 0,
			'hierarchical' => 1,
			'taxonomy'     => 'product_cat',
			'pad_counts'   => 1,
		) ) );

		if ( apply_filters( 'woocommerce_product_subcategories_hide_empty', true ) ) {
			$product_categories = wp_list_filter( $product_categories, array( 'count' => 0 ), 'NOT' );
		}
		
		$display_type_shop_wc_setting = get_option('woocommerce_shop_page_display');
		$display_type_wc_setting = get_option('woocommerce_category_archive_display');
		$display_type_cat_setting = get_woocommerce_term_meta( $parent_id, 'display_type' );
		
		
		$show_filter = ( (is_shop() && $display_type_shop_wc_setting == 'subcategories') || (is_product_category() && $display_type_cat_setting == 'subcategories' && $product_categories) || (is_product_category() && $display_type_wc_setting == 'subcategories' && $display_type_cat_setting == '' && $product_categories)) ? false : true;
		
		//show category just when shop/category page is on displaying products mode
		//$show_categories = (epico_opt('shop-filter-categories') && (( is_shop() && $display_type_shop_wc_setting == '')  || ( is_product_category() && $display_type_cat_setting == '' && $display_type_wc_setting == '') || ( is_product_category() && ($display_type_cat_setting == 'subcategories' || ($display_type_cat_setting == '' && $display_type_wc_setting == 'subcategories')) &&  !$product_categories) )) ? true : false;
		$enabled_categories = epico_opt('shop-filter-categories') == 1  ? true : false;
		$show_categories = (( is_shop() && $display_type_shop_wc_setting == '')  || ( is_product_category() && $display_type_cat_setting == '' && $display_type_wc_setting == '') || ( is_product_category() && $display_type_cat_setting == '' && $display_type_wc_setting == 'products') ) ? true : false;
		
        //shop categories buttons in mobiles  
        if( $enabled_categories && $show_categories )
        {
            echo '<span class="shopFilterCategoriesBtn hidden-desktop">'. esc_html__('Categories','vitrine') .'</span>';
        } 

        // filter button in Mobile
		if($show_filter) {
			echo	'<span class="filterBgTabletPhone hidden-desktop"></span>
					<span class="shop-filter-toggle">
						<span class="togglelines"></span>
                <span class="shop-filter-text no-select"><span class="opentext">'.esc_html__('Close','vitrine') .'</span><span class="closetext">'.esc_html__('Filter','vitrine') .'</span></span>
            </span>';
		}

            echo '<div class="shop-filter sidebar widget-area ' . ( get_search_query() ? 'show-search-result': '') . '">';

            //shop categories
            if( $enabled_categories )
            {
                echo '<div class="special-filter cat ' . ( $show_categories ? '' : 'hidden-cats') . '">';
                    epico_change_categories_nav_walker();
                echo '</div>';
            }

			if ( (is_shop() && $display_type_shop_wc_setting == 'subcategories') || (is_product_category() && $display_type_cat_setting == 'subcategories' && $product_categories) || (is_product_category() && $display_type_wc_setting == 'subcategories' && $display_type_cat_setting == '' && $product_categories))
			{
                echo '</div>';
				return;
            }

            //show search form
            if(epico_opt('shop-filter-search'))
            {
                epico_search_form();
            }

            //show search keyword
            if(epico_opt('shop-filter-search'))
            {
                epico_search_keywords();
            }

            //Filters in filter sidebar
            epico_get_sidebar('woocommerce-filter-sidebar');
            
            echo '<div class="bottomPartFilter">';
                echo '<div class="special-filter">';
                    $instance = array(
                        "title" => "",
                    );
                    //show on sale filter
                    if(epico_opt('shop-filter-on-sale'))
                    {   
                        the_widget( "epico_WC_Widget_On_Sale_Filter", $instance );
                    }

                    //show in stock filter
                    if(epico_opt('shop-filter-in-stock'))
                    {
                        the_widget( "epico_WC_Widget_In_Stock_Filter", $instance );
                    }

                    if(function_exists('woocommerce_result_count'))
                    {
                        woocommerce_result_count();
                    }
                    
                echo '</div>';

                //show active filters
                if(epico_opt('shop-filter-active-filters'))
                {
                    echo '<div id="special_layered_nav_filters" class="special-filter">';
                        the_widget( "epico_WC_Widget_Layered_Nav_Filters" );
                    echo '</div>';
                }

            echo '</div>';
        echo '</div>';

    }
}

// category in filter
if(!function_exists('epico_change_categories_nav_walker')) {
    function epico_change_categories_nav_walker( ) {

        global $wp_query;
        $page_url = wc_get_page_permalink( 'shop' );
        if ( '' === get_option( 'permalink_structure' )) {
           $page_url = get_post_type_archive_link('product');
        }
        $hide_sub = true;
        $all_categories_class = '';
        $current_cat = ( is_tax( 'product_cat' ) ) ? $wp_query->queried_object->term_id : '';
        $current_cat_parent = ( is_tax( 'product_cat' ) ) ? $wp_query->queried_object->parent : '';

		// Get current category's direct children
		$current_cat_has_children = get_terms( 'product_cat',
			array(
				'fields'       	=> 'ids',
				'parent'       	=> $current_cat,
				'hierarchical'	=> true,
				'hide_empty'   	=> 0
			)
		);
		$category_has_children = ( empty( $current_cat_has_children ) ) ? false : true;
		
        if ( strlen( $current_cat ) > 0 ) { // category page    

        } else {

            // No current category, set "All" as current (if not product tag archive or search)
            if ( ! is_product_tag() && ! isset( $_REQUEST['s'] ) ) {
                $all_categories_class = ' class="current-cat"';
            }

        }

        $args = array( 
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => -1 
        );

        $products = new WP_Query( $args );

        $output = '<li' . $all_categories_class . '><a href="' . esc_url ( $page_url ) . '">' . esc_html__( 'All', 'vitrine' ) . '</a><span class="count">' . $products->found_posts . '</span></li>';
        
        // Categories order
        $orderby = 'slug';
        $order = 'asc';

        $categories = get_categories( $args = array(
            'type'          => 'post',
            'orderby'       => $orderby, 
            'order'         => $order,
            'hide_empty'    => 0,
            'hierarchical'  => 1,
            'taxonomy'      => 'product_cat'
        ) );

		$suboutput = '';
		$show_sabcategories = epico_opt('shop-filter-subcategories') == 1 ? true : false;
        foreach( $categories as $category ) {
			if($category->parent == '0' )// category
			{
				$output .= epico_category_list( $category, $current_cat );
			}
			else if($show_sabcategories == true && ($category->parent == $current_cat || (! $category_has_children && $category->parent == $current_cat_parent)) ) // subcategory of current parent or siblings of current subcategory
			{
				$suboutput .= epico_subcategory_list( $category, $current_cat );		
			}
            
        }
		
		if($suboutput != '')
		{
			$suboutput = '<ul class="product-subcategories icon-chevron-right">' . $suboutput . '</ul>';
		}
    
        $output = '<div id="shop-filter-cat" class="widget woocommerce widget_product_categories"><ul class="product-categories">' . $output . '</ul>' . $suboutput .'</div>';

        echo $output;
    }
}

// category list 
if(!function_exists('epico_category_list')) {
    function epico_category_list( $category, $current_cat) {
        $output = '<li class="cat-item-' . esc_attr($category->term_id);

            if ( $current_cat == $category->term_id ) {
                $output .= ' current-cat';
            }

            $output .= ' '. esc_attr($category->name);

        $output .=  '"><a href="' . esc_url( get_term_link( (int) $category->term_id, 'product_cat' ) ) . '">' . esc_attr( $category->name ) . '</a><span class="count">' . $category->count . '</span></li>';

        return $output;
    }
}

// subcategory list 
if(!function_exists('epico_subcategory_list')) {
    function epico_subcategory_list( $category, $current_cat) {
        $output = '<li class="cat-item-' . esc_attr($category->term_id);

            if ( $current_cat == $category->term_id ) {
                $output .= ' current-cat';
            }

        $output .=  '"><a href="' . esc_url( get_term_link( (int) $category->term_id, 'product_cat' ) ) . '">' . esc_attr( $category->name ) . '</a></li>';

        return $output;
    }
}

if(!function_exists('epico_search_form')) {
    function epico_search_form()
    {
        $page_url = '';
        $type = '';//this variabe used to detect search form is in category page or main page of shop

        if(is_product_category())
        {
            global $wp_query;
            // get the query object
            $cat_obj = $wp_query->get_queried_object();

            if($cat_obj)    {
                $category_ID  = $cat_obj->term_id;
                $page_url = get_category_link($category_ID);
            }

            $type = 'category';
        }
        else
        {
            $page_url =  esc_url( home_url( '/'  ) );
            $type = 'mainshop';
        }

        echo '<span class="search-box no-select">
                <span class="icon icon-magnifier"></span>
                <span class="text">' . esc_html__('Search','vitrine'). '</span>
                <span class="close"></span>
            </span>
            <div class="filter-search-form-container">
                <form role="search" method="get" class="woocommerce-product-search" data-type="' . esc_attr($type) . '" action="' . esc_url($page_url) . '">
                    <span class="icon icon-magnifier"></span>
                    <input type="search" id="woocommerce-product-search-field" class="search-field" placeholder="' . esc_attr_x( 'Search Products&hellip;', 'placeholder', 'vitrine' ) . '" value="' . esc_attr(get_search_query()) . '" name="s" title="' . esc_attr_x( 'Search for:', 'label', 'vitrine' ) . '" />
                    <input type="hidden" name="post_type" value="product" />
                </form>
            </div>
            <span class="search-hint hide">' . esc_html__('Press "Enter" to search', 'vitrine') . '</span>
            ';
    }
}

if(!function_exists('epico_search_keywords')) {
    function epico_search_keywords() {
        global $wp;
        
        if ( get_option( 'permalink_structure' ) == '' ) {
            $link = remove_query_arg( array( 'page', 'paged', 's' ), add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
        } else {
            if(is_shop())
            {
                $link = preg_replace( '%\/page/[0-9]+%', '', wc_get_page_permalink( 'shop' ) );
            }
            else
            {
                $link = preg_replace( '%\/page/[0-9]+%', '', home_url( $wp->request ) );
            }
        }

        $output = '<span class="search-keyword' . (get_search_query() ? ' show' : '') . '">';
        
        if ( get_search_query() ) {
            $output .= '<a href="' . esc_url( $link ) . '">' . esc_html__( 'Search result for "', 'vitrine' ) . get_search_query() . '"' . '</a></span>';
        }
        $output .= '</span>';
        echo $output;
    }
}

//Product
if(!function_exists('epico_woocommerce_shop_loop_action')){
	function epico_woocommerce_shop_loop_action(){
		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

		if(epico_opt( 'shop-product-rating') == 1)
		{
			add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 11 );
		}

		//Product buttons
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
		if(epico_opt( 'shop-product-style') != 'instantShop') {
		add_action( 'epico_woocommerce_shop_loop_buttons', 'woocommerce_template_loop_add_to_cart', 10 );
		}


		remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
		add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_open', 0 );
		add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 2 );
	}
}

epico_woocommerce_shop_loop_action();
/*---------------------------------
     WooCommerce product title - linkde to product page
------------------------------------*/
if ( ! function_exists( 'epico_woocommerce_product_title' ) ) {
    function epico_woocommerce_product_title() {
		global $product;
		echo '<a href="' . get_the_permalink() . '" ><h2 class="woocommerce-loop-product__title">' . get_the_title() . '</h2></a>';
    }
}

if(!function_exists('epico_woocommerce_product_title_action')){
	function epico_woocommerce_product_title_action(){
		remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
		add_action( 'woocommerce_shop_loop_item_title', 'epico_woocommerce_product_title', 10 );

	}
}

epico_woocommerce_product_title_action();


			if(!function_exists('epico_add_compare_button')) {
				function epico_add_compare_button() {
		if ( class_exists('YITH_Woocompare') && get_option('yith_woocompare_compare_button_in_products_list') == 'yes' ) {
					global $yith_woocompare;
					ob_start();
					$yith_woocompare->obj->add_compare_link();
					$output = ob_get_clean();
					$output = str_replace('class="','class="no_djax ',$output);
					echo '<span title="' . esc_attr__("Add to compare list",'vitrine') . '">' . $output . '</span>';
				}
			}
}
			
if(!function_exists('epico_add_yith_compare_button')){
	function epico_add_yith_compare_button(){
		if ( class_exists('YITH_Woocompare') && get_option('yith_woocompare_compare_button_in_products_list') == 'yes' ) {
			global $yith_woocompare;

			remove_action( 'woocommerce_after_shop_loop_item', array( $yith_woocompare->obj, 'add_compare_link' ), 20 );
			if(epico_opt( 'shop-product-style') != 'instantShop') {
			add_action( 'epico_woocommerce_shop_loop_buttons', 'epico_add_compare_button', 20 );
			}

		}
	}
}

epico_add_yith_compare_button();



if(!function_exists('epico_summery_add_compare_link')) {
	function epico_summery_add_compare_link() {
		if(class_exists('YITH_Woocompare'))
		{
			global $yith_woocompare;
			ob_start();

			$yith_woocompare->obj->add_compare_link();

			$compare_button = ob_get_clean();
			$compare_button = str_replace('<a','<a title="' . esc_attr__('Add to compare list','vitrine') . '"',$compare_button);
			$compare_button = str_replace('class="','class="no_djax ',$compare_button);
			echo $compare_button;
		}
	}
}
			
if(!function_exists('epico_yith_woocompare_button')){
	function epico_yith_woocompare_button(){
		if ( class_exists('YITH_Woocompare') && get_option('yith_woocompare_compare_button_in_product_page') == 'yes' )
		{
			global $yith_woocompare;

			remove_action( 'woocommerce_single_product_summary', array( $yith_woocompare->obj, 'add_compare_link' ), 35 );
			add_action( 'woocommerce_single_product_summary', 'epico_summery_add_compare_link', 35 );
		}
	}
}
epico_yith_woocompare_button();

// change priority of items in WooCommerce single product page   
if(!function_exists('epico_woocommerce_single_product_summary_action')){
	function epico_woocommerce_single_product_summary_action(){
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );

		add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
		add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 15 );
		add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 20 );
		add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 40 );
		add_action( 'woocommerce_after_single_product_summary', 'woocommerce_template_single_meta', 10 );
	}
}

epico_woocommerce_single_product_summary_action();


if(!function_exists('epico_woocommerce_subcategory_thumbnail')) {
    function epico_woocommerce_subcategory_thumbnail( $category, $image_size ) {
        $image = '';
        $attachment_id = get_woocommerce_term_meta( $category->term_id, 'thumbnail_id', true  );
        $width = $height = 0;
        if($image_size == 'full')
        {
            $image_src = wp_get_attachment_image_src( $attachment_id , 'full' );
            if($image_src)
            {
                $image = '<img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="'.esc_url($image_src[0]).'" alt="' . esc_attr( $category->name ) . '"/>';

                $width = $image_src[1];
                $height = $image_src[2];
            }


        }
        else
        {
            if(function_exists('wc_get_image_size')) {

                $image_dimension = wc_get_image_size($image_size);

                $image_link  = wp_get_attachment_image_src( $attachment_id, 'full');
                $img = aq_resize($image_link[0], $image_dimension['width'], $image_dimension['height'], $image_dimension['crop'], false, true);
            
                if(!$img) {
                    $image = '<img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="'.esc_url($image_link[0]).'" alt="' . esc_attr( $category->name ) . '"/>';
                    $width = $image_link[1];
                    $height = $image_link[2];
                    if($image_link[0] == '')
                        $image = '';
                }
                else
                {
                    $image = '<img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="'.esc_url($img[0]).'" alt="' . esc_attr( $category->name ) . '"/>';
                    $width = $img[1];
                    $height = $img[2];                    
                }



            } else {

                $image_src = wp_get_attachment_image_src($attachment_id, $image_size );

                if($image_src)
                {
                    $image = '<img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="'.esc_url($image_src[0]).'" alt="' . esc_attr( $category->name ) .'"/>';
                    $width = $image_src[0];
                    $height = $image_src[1];
                }
            }
        }

        if($image == '')
        {
            $image = '<img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="'. esc_url(wc_placeholder_img_src()) .'" alt="' . esc_attr( $category->name ) . '"/>';
            $width = 100;
            $height = 100;

        }
        echo '<div class="lazy-load lazy-load-on-load" style="padding-top:' . esc_attr(epico_get_height_percentage('',$width, $height)) . '%;">';
            echo $image;//Sanitization performed in above lines!
        echo '</div>';

    }
}
//Product categories images
if(!function_exists('epico_woocommerce_subcategory_thumbnail_action')) {
    function epico_woocommerce_subcategory_thumbnail_action() {
		remove_action( 'woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10 );
		add_action( 'woocommerce_before_subcategory_title', 'epico_woocommerce_subcategory_thumbnail', 10 , 2);
	}
}
epico_woocommerce_subcategory_thumbnail_action();


if(!function_exists('epico_woocommerce_ajax_wrapper_start')) {
    function epico_woocommerce_ajax_wrapper_start()
    {

        $shop_pagination = epico_opt('ajax_shop_pagination');

        if($shop_pagination) {

            echo '<div class="wc-ajax-wrapper">
                    <span class="wc-loading hide"></span>
                    <div class="wc-ajax-content">';
        } else {

            echo '<div class="wc-ajax-wrapper">
                    <span class="wc-loading hide"></span>
                    <div class="wc-ajax-content disable_pagination">';
        }
    }
}

if(!function_exists('epico_woocommerce_ajax_wrapper_end')) {
    function epico_woocommerce_ajax_wrapper_end()
    {
        echo '</div></div>';
    }
}
//Add a wrapper around products for updating them with ajax
if(!function_exists('epico_woocommerce_ajax_wrapper_action')) {
    function epico_woocommerce_ajax_wrapper_action(){
		add_action( 'woocommerce_before_shop_loop', 'epico_woocommerce_ajax_wrapper_start', 45 );
		add_action( 'woocommerce_after_shop_loop', 'epico_woocommerce_ajax_wrapper_end', 10 );
	}
}
epico_woocommerce_ajax_wrapper_action();




//Redeclare woocommerce function- Show a shop page description on product archives.
if ( ! function_exists( 'woocommerce_product_archive_description' ) ) {
    function woocommerce_product_archive_description() {
		// Don't display the description on search results page
		if ( is_search() || epico_is_shop_ajax_request()) {
			return;
		}

		if ( is_post_type_archive( 'product' ) && 0 === absint( get_query_var( 'paged' ) ) ) {
            $shop_page   = get_post( wc_get_page_id( 'shop' ) );
			if ( $shop_page ) {
                $description = wc_format_content( $shop_page->post_content );
            
                if ( $description ) {
                    echo '<div class="page-description">' . $description . '</div>';
                }
        }
    }
    }
}

    
/*-----------------------------------------------------------------*/
/* Get all exception pages of ajax
/*-----------------------------------------------------------------*/
if ( ! function_exists( 'epico_no_ajax_pages' ) ) {
    function epico_no_ajax_pages() {

        $no_ajax_pages = array();

        //Get translation pages for current page and merge with main array

        $no_ajax_pages = array_merge($no_ajax_pages, epico_get_wpml_pages_of_current_page());

        //Add logout URL to main array
        $no_ajax_pages[] = htmlspecialchars_decode(wp_logout_url());

        return $no_ajax_pages;
    }
}

if ( ! function_exists( 'epico_get_wpml_pages_of_current_page' ) ) {
    function epico_get_wpml_pages_of_current_page() {
        $wpml_pages_of_current_page = array();

        if(defined('ICL_SITEPRESS_VERSION')) {
            $language_pages = icl_get_languages('skip_missing=0');

            foreach($language_pages as $key => $language_page) {
                $wpml_pages_of_current_page[] = $language_page["url"];
            }
        }

        return $wpml_pages_of_current_page;
    }
}
 
/*-----------------------------------------------------------------*/
/* WooCommerce Quick view button
/*-----------------------------------------------------------------*/
if ( ! function_exists( 'epico_add_quick_view_button' ) ) {
    function epico_add_quick_view_button() {

        $quick_view = epico_opt('shop-enable-quickview');
        
        if($quick_view == '1' && class_exists('Woocommerce'))
        {
            global $product;

            echo '<span class="ep-qv"><a href="#" class="quick-view-button" data-product_id="' . esc_attr($product->get_id()) . '"  title="' . esc_attr__('Show in quickview','vitrine') .'">' . esc_attr__('Quick View','vitrine') .'</a></span>';

        }
    }
}

if ( ! function_exists( 'epico_add_quick_view_button_action' ) ) {
    function epico_add_quick_view_button_action() {
		if(epico_opt( 'shop-product-style') != 'instantShop') {
		add_action( 'epico_woocommerce_shop_loop_buttons',  'epico_add_quick_view_button' , 15 );
	}
		else{
			add_action( 'epico_woocommerce_shop_loop_hover_buttons',  'epico_add_quick_view_button' , 15 );
		}
	}
}
epico_add_quick_view_button_action();


// Load modal template
if ( ! function_exists( 'epico_quikview_compare_modal' ) ) {
    function epico_quikview_compare_modal() {
        $quick_view = epico_opt('shop-enable-quickview');

		if($quick_view == '1' && class_exists('Woocommerce'))
		{
			wp_enqueue_script( 'wc-add-to-cart-variation' );
		}

        if (function_exists('is_woocommerce')) { //  check woocomerce plugin is active or not		
            wc_get_template( 'modal.php', array(), '', EPICO_THEME_DIR . '/woocommerce/' );		
        }
    }
}

if ( ! function_exists( 'epico_quikview_compare_modal_action' ) ) {
    function epico_quikview_compare_modal_action() {
		add_action( 'wp_footer', 'epico_quikview_compare_modal' );
	}
}
epico_quikview_compare_modal_action();


//Quick view Ajax
if ( ! function_exists( 'epico_load_quick_view' ) ) {
    function epico_load_quick_view() {

        global $woocommerce, $product, $post;
        
        $product = get_product( $_POST['product_id'] );
        $post = $product->post;
        $output = '';
        
        setup_postdata( $post );
            
        ob_start();
            wc_get_template( 'quick-view-content.php', array(), '', EPICO_THEME_DIR . '/woocommerce/quickview/'  );
        $output = ob_get_clean();
        
        wp_reset_postdata();
                
        echo $output;
                
        exit;
        
    }
}

if ( ! function_exists( 'epico_load_quick_view_action' ) ) {
    function epico_load_quick_view_action() {
		add_action( 'wp_ajax_load_quick_view', 'epico_load_quick_view' );
		add_action( 'wp_ajax_nopriv_load_quick_view', 'epico_load_quick_view' );
		add_action( 'wc_ajax_load_quick_view', 'epico_load_quick_view' );// Register WooCommerce Ajax endpoint (available since 2.4)
	}
}

epico_load_quick_view_action();


//title of quick view product
if ( ! function_exists( 'epico_title_quick_view' ) ) {
	function epico_title_quick_view() {
		global $product;
        echo '<a  href="'. esc_url( get_permalink( $product->id ) ) . '">';
        the_title('<h1 class="product_title entry-title">', '</h1>');
        echo '</a>';
	}
}

// gallery
if ( ! function_exists( 'epico_woocommerce_show_product_images' ) ) {
    function epico_woocommerce_show_product_images() {
        wc_get_template( 'single-product/product-image.php', array('is_quick_view' => true ) );
    }
}



// Summary
if ( ! function_exists( 'epico_quick_view_action' ) ) {
    function epico_quick_view_action() {
		
		add_action( 'quick_view_product_image', 'epico_woocommerce_show_product_images', 20 );
		add_action( 'quick_view_product_image', 'woocommerce_show_product_sale_flash', 10 );
		add_action( 'quick_view_product_summary', 'epico_title_quick_view', 5 );
		add_action( 'quick_view_product_summary', 'woocommerce_template_single_rating', 23 );
		add_action( 'quick_view_product_summary', 'woocommerce_template_single_price', 15 );
		add_action( 'quick_view_product_summary', 'woocommerce_template_single_excerpt', 20 );
		add_action( 'quick_view_product_summary', 'woocommerce_template_single_add_to_cart', 25 );

	}
}

epico_quick_view_action();

/*-----------------------------------------------------------------*/
/* Woocommerce product video
/*-----------------------------------------------------------------*/
if ( ! function_exists( 'epico_woocommerce_product_video' ) ) {
    function epico_woocommerce_product_video ()
    {

        global $product;
        $video_type = epico_get_meta('video_type');
        $attributes = '';

        $attributes = 'video_display_type="' . esc_attr($video_type) . '" ';

        if($video_type == 'none' || $video_type == '')
        {
            return;
        }
        elseif($video_type == 'local_video_popup')
        {
            $video_webm = epico_get_meta('video_webm');
            $video_mp4 = epico_get_meta('video_mp4');
            $video_ogv = epico_get_meta('video_ogv');

            if($video_webm == '' && $video_mp4 == '' && $video_ogv == '')
                return;

            $attributes .= 'video_webm="' . esc_attr($video_webm) . '" ';
            $attributes .= 'video_mp4="' . esc_attr($video_mp4) . '" ';
            $attributes .= 'video_ogv="' . esc_attr($video_ogv) . '" ';
        }
        elseif($video_type == 'embeded_video_vimeo_popup')
        {
            $vimeo_id = epico_get_meta('video_vimeo_id');
            if($vimeo_id == '')
                return;

            $attributes .= 'video_vimeo_id="' . esc_attr($vimeo_id) . '" ';
        }
        else
        {
            $youtube_id = epico_get_meta('video_youtube_id');
            if($youtube_id == '')
                return;

            $attributes .= 'video_youtube_id="' . esc_attr($youtube_id) . '" ';
        }

        $video_play_button_color = epico_get_meta('video_play_button_color');
        $attributes .= 'video_play_button_color="' . esc_attr($video_play_button_color) .'"';

        $video_button_label = epico_get_meta('video_button_label');
        if (!empty($video_button_label)) {
            $video_button_label = esc_attr($video_button_label);
        } else {
            $video_button_label = 'Watch video';
        }

        echo do_shortcode('[embed_video text="' . esc_attr($video_button_label) .'" video_autoplay="disable" ' . $attributes .']');
    }
}

if ( ! function_exists( 'epico_woocommerce_product_video_action' ) ) {
    function epico_woocommerce_product_video_action(){
		add_action( 'woocommerce_product_thumbnails', 'epico_woocommerce_product_video', 10 );
	}
}

epico_woocommerce_product_video_action();

/*-----------------------------------------------------------------*/
/* Woocommerce number of columns for shop page with sidebar
/*-----------------------------------------------------------------*/
if ( ! function_exists( 'epico_shop_with_sidebar_loop_columns' ) ) {
    function epico_shop_with_sidebar_loop_columns( $number_columns ) {
        $page_id = wc_get_page_id('shop');
        $sidebar = epico_opt('shop-sidebar-position'); // detect side bar position that set in admin panel

        if (0 != $sidebar) { // if shop page has sidebar
            return 3;
        }

        return $number_columns;
    }
}

if ( ! function_exists( 'epico_shop_loop_columns_action' ) ) {
    function epico_shop_loop_columns_action() {
		add_filter( 'loop_shop_columns', 'epico_shop_with_sidebar_loop_columns', 100 , 1 );
		add_filter( 'loop_shop_per_page', create_function( '$cols', 'return ' . (epico_opt('shop-item-per-page') != '' ? epico_opt('shop-item-per-page') : 12 ) .';' ), 20 );// Display X products per page.
	}
}
epico_shop_loop_columns_action();


if ( ! function_exists( 'epico_shop_page_wishlist_button' ) ) {
    function epico_shop_page_wishlist_button() {
        if (class_exists('YITH_WCWL')){
            global $product;
            global $yith_wcwl;

            $default_wishlists = is_user_logged_in() ? YITH_WCWL()->get_wishlists( array( 'is_default' => true ) ) : false;

            if( ! empty( $default_wishlists ) ){
                $default_wishlist = $default_wishlists[0]['ID'];
            }
            else{
                $default_wishlist = false;
            }

            //We put 2 buttons inside a tag to similify css codes
            $output  = '<span>';
            $output .= '<a href="'. esc_url( add_query_arg( "add_to_wishlist", $product->get_id() ) ) .'" rel="nofollow" data-product-id="' . esc_attr($product->get_id()) . '" data-product-type="' . esc_attr($product->get_type()) . '" class="add_to_wishlist shop_wishlist_button ' . esc_attr(($yith_wcwl->is_product_in_wishlist($product->get_id() , $default_wishlist) == true ? "exist_in_wishlist ": "")) .'" title="' . esc_attr__('Add to wishlist','vitrine') .'"><span class="wc-loading hide"></span></a>';
            $output .= '<a href="'. esc_url($yith_wcwl->get_wishlist_url()) . '" rel="nofollow" class="wishlist-link shop_wishlist_button" style="' . esc_attr(($yith_wcwl->is_product_in_wishlist($product->get_id() , $default_wishlist) == true ? "display:block; ": "")) .'" title="' . esc_attr__('Go to wishlist','vitrine') .'"></a>';
            $output .= '</span>';

            echo $output;
        }
    }
}

if ( ! function_exists( 'epico_shop_page_wishlist_button_action' ) ) {
    function epico_shop_page_wishlist_button_action() {
		add_action('epico_woocommerce_shop_loop_buttons','epico_shop_page_wishlist_button',11);
	}
}
epico_shop_page_wishlist_button_action();


if ( ! function_exists( 'epico_remove_item' ) ) {
    function epico_remove_item() {

        $item_key = $_POST['item_key'];
        
        $removed = WC()->cart->remove_cart_item( $item_key ); // Note: WP 2.3 >
        
        if ( $removed ) {
           $data['status'] = '1';
           $data['cart_count'] = WC()->cart->get_cart_contents_count();
           $data['cart_subtotal'] = WC()->cart->get_cart_subtotal();
        } else {
            $data['status'] = '0';
        }
        
        echo json_encode( $data );
                
        exit;

    }
}

// Remove item from card
if ( ! function_exists( 'epico_remove_item_action' ) ) {
    function epico_remove_item_action() {
		add_action('wp_ajax_cart_remove_item', 'epico_remove_item');
		add_action('wp_ajax_nopriv_cart_remove_item', 'epico_remove_item');
	}
}
epico_remove_item_action();


if ( ! function_exists( 'epico_undo_removed_item' ) ) {
    function epico_undo_removed_item() {

        $item_key = $_POST['item_key'];
        
        $cart = WC()->instance()->cart;
        $undo_item = $cart->restore_cart_item( $item_key );
        
        if ( $undo_item ) {
           $data['status'] = '1';
           $data['cart_count'] = $cart->get_cart_contents_count();
           $data['cart_subtotal'] = $cart->get_cart_subtotal();
        } else {
            $data['status'] = '0';
        }
        
        echo json_encode( $data );
                
        exit;

    }
}
// Get back removed item to cart
if ( ! function_exists( 'epico_undo_removed_item_action' ) ) {
    function epico_undo_removed_item_action() {
		add_action('wp_ajax_undo_removed_item', 'epico_undo_removed_item');
		add_action('wp_ajax_nopriv_undo_removed_item', 'epico_undo_removed_item');
	}
}
epico_undo_removed_item_action();

    
/*
 *  Fetch  Add To cart fragments in Ajax request
 */
if ( ! function_exists( 'epico_ajax_add_to_cart_redirect_template' ) ) {
    function epico_ajax_add_to_cart_redirect_template() {
        if ( isset( $_REQUEST['ep-ajax-add-to-cart'] ) ) {
            wc_get_template( 'ajax-add-to-cart-fragments.php' );
            exit;
        }
    }
}

if ( ! function_exists( 'epico_ajax_add_to_cart_redirect_template_action' ) ) {
    function epico_ajax_add_to_cart_redirect_template_action() {
		add_action( 'wp', 'epico_ajax_add_to_cart_redirect_template', 1000 );
	}
}
epico_ajax_add_to_cart_redirect_template_action();



if ( ! function_exists( 'epico_get_wishlist_quantity' ) ) {
    function epico_get_wishlist_quantity() {
        global $yith_wcwl;

        // check to see if the submitted nonce matches with the generated nonce we created earlier
        check_ajax_referer( 'ajax-nonce', 'security' );

        $data = array(
            'wishlist_count_products' => yith_wcwl_count_products()
        );
        wp_send_json($data);
    }
}

// Update wishlist widget
if ( ! function_exists( 'epico_get_wishlist_quantity_action' ) ) {
    function epico_get_wishlist_quantity_action() {
		add_action('wp_ajax_get_wishlist_quantity', 'epico_get_wishlist_quantity');
		add_action('wp_ajax_nopriv_get_wishlist_quantity', 'epico_get_wishlist_quantity');
	}
}
epico_get_wishlist_quantity_action();


// add 'row' that wrap feilds
if ( ! function_exists( 'epico_comment_before_fields' ) ) {
    function epico_comment_before_fields () {
        echo '<div class="row">';
    }
}

if ( ! function_exists( 'epico_comment_after_fields' ) ) {
    function epico_comment_after_fields () {
        echo '</div>';
    }
}
    
/*---------------------------------
    Add FeatureImage Boxes In Portfolio
------------------------------------*/

if ( ! function_exists( 'epico_multi_post_thumbnails' ) ) {
    function epico_multi_post_thumbnails() {
		if (class_exists('MultiPostThumbnails')) {

		   $featureImageNum = 4;
		   $counter = 2;

			while ( $counter < ($featureImageNum)) {
			
				// Add Slides in Portfolio Items
				new MultiPostThumbnails(
					array(
						'label' => 'Featured Image ' . $counter,
						'id' => $counter . '-slide',
						'post_type' => 'portfolio'
					)
				);
			
				$counter++;
			}
		}
	}
}
epico_multi_post_thumbnails();

/*---------------------------------
    Add NEXT/PREV item in portfolio detail
------------------------------------*/
if ( ! function_exists( 'epico_load_portfolio_detail_navigation' ) ) {
    function epico_load_portfolio_detail_navigation() {
        $skill_ids;
        $back_url;
        $id;

        // check to see if the submitted nonce matches with the generated nonce we created earlier
        check_ajax_referer( 'ajax-nonce', 'security' );

        if ( isset($_REQUEST) ) {

            $id = wp_filter_nohtml_kses($_REQUEST['pid']);
            $skill_ids = explode(" ", wp_filter_nohtml_kses($_REQUEST['skill_ids']));
            $back_url = wp_filter_nohtml_kses($_REQUEST['back_url']);


            if($skill_ids[0] == 'all')
            {
                $tax_query = array();
            }
            else
            {
                $tax_query = array(
                    array(
                        'taxonomy' => 'skills',
                        'field' => 'id',
                        'terms' => $skill_ids
                    )
                );
            }
            $args = array( 
            'fields' =>'ids', //we don't really need all post data so just id wil do fine.
            'posts_per_page' => -1, //-1 to get all post
            'post_type' => 'portfolio', 
            'tax_query' => $tax_query
            );

            $post_ids = get_posts( $args );
      
            $thisindex = array_search($id, $post_ids);

            if( $thisindex == 0 )
            {
                $previd = "";
            }
            else
            {
                $previd = $post_ids[$thisindex-1];
            }


            if( $thisindex == count($post_ids) -1 )
            {
                $nextid = "";
            }
            else
            {
                $nextid = $post_ids[$thisindex+1];
            }


        ?>
            <div class="container">
                <div class="row "> 
                    <div class="span2"></div>
                    <div class="span8 nav_box">

                        <!-- Prev Arrows -->
                        <?php
                        if ( !empty( $previd ) ):
                            $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($previd), 'epico_blog_navigation');
                            $checkTitle = get_post_meta( $previd , "title-bar", true );
                            $title = get_post_meta( $previd, "title-text", true );
                            if ( ( $checkTitle == 0 ) || empty( $title )) {
                                $title = get_the_title($previd);
                            }
                        ?>
                               
                            <a href="<?php echo esc_url(get_permalink( $previd ));?> " title="<?php esc_attr_e('PREV', 'vitrine'); ?>" class="prevPD portfolioDetailNavLink" data-pid="<?php echo esc_attr($previd); ?>" data-skills="<?php echo esc_attr(implode(" ",$skill_ids)); ?>">
                                <div class="prevNav">
                                    <div class="bg" style="background-image:url(<?php echo esc_url($thumb[0]); ?>);background-color:#ddd;"></div>
                                    <span class="postTitle"><?php echo esc_attr($title); ?></span>
                                </div>
                            </a>

                        <?php endif; ?>


                        <!-- Back to portfolio -->
                        <a id="PDbackToPortfolio" href="<?php echo esc_url($back_url); ?>" title="<?php esc_attr_e('Back to portfolio', 'vitrine'); ?>" class="<?php if ( empty( $previd ) ) { echo "noPrev"; }?> <?php if ( empty( $nextid ) ) { echo "noNext"; }?>">
                                <span class="icon-icons2" data-name="grid2"></span>
                        </a>

                        <!-- Next Arrows -->
                        <?php
                        
                        if ( !empty( $nextid ) ):
                            $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($nextid), 'epico_blog_navigation');
                            $checkTitle = get_post_meta( $nextid, "title-bar", true );
                            $title = get_post_meta( $nextid, "title-text", true );
                            if ( ( $checkTitle == 0 ) || empty( $title )) {
                                $title = get_the_title( $nextid );
                            }
                            
                             ?>
                            <a href="<?php echo esc_url(get_permalink( $nextid )); ?>" title="<?php esc_attr_e('NEXT', 'vitrine'); ?>"  class="nextPD portfolioDetailNavLink" data-pid="<?php echo esc_attr($nextid); ?>" data-skills="<?php echo esc_attr(implode(" ",$skill_ids)); ?>">
                                <div class="nextNav">
                                    <div class="bg" style="background-image:url(<?php echo esc_url($thumb[0]); ?>);background-color:#ddd;"></div>
                                    <span class="postTitle"><?php echo esc_attr($title); ?></span>
                                </div>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php
        }
        die();
    }
}

if ( ! function_exists( 'epico_load_portfolio_detail_navigation_action' ) ) {
    function epico_load_portfolio_detail_navigation_action() {
		add_action('wp_ajax_load_pd_navigation', 'epico_load_portfolio_detail_navigation');
		add_action('wp_ajax_nopriv_load_pd_navigation', 'epico_load_portfolio_detail_navigation');
	}
}

epico_load_portfolio_detail_navigation_action();
/*---------------------------------
    Add NEXT/PREV item in portfolio detail
------------------------------------*/
if ( ! function_exists( 'epico_load_creative_portfolio_detail_navigation' ) ) {
    function epico_load_creative_portfolio_detail_navigation() {
        $skill_ids;
        $back_url;
        $id;

        // check to see if the submitted nonce matches with the generated nonce we created earlier
        check_ajax_referer( 'ajax-nonce', 'security' );

        if ( isset($_REQUEST) ) {
            $id = wp_filter_nohtml_kses($_REQUEST['pid']);
            $skill_ids = explode(" ", wp_filter_nohtml_kses($_REQUEST['skill_ids']));
            $back_url = wp_filter_nohtml_kses($_REQUEST['back_url']);


            if($skill_ids[0] == 'all')
            {
                $tax_query = array();
            }
            else
            {
                $tax_query = array(
                    array(
                        'taxonomy' => 'skills',
                        'field' => 'id',
                        'terms' => $skill_ids
                    )
                );
            }

            $args = array( 
            'fields' =>'ids', //we don't really need all post data so just id wil do fine.
            'posts_per_page' => -1, //-1 to get all post
            'post_type' => 'portfolio', 
            'tax_query' => $tax_query
            );

            $post_ids = get_posts( $args );
      
            $thisindex = array_search($id, $post_ids);

            if( $thisindex == 0 )
            {
                $previd = "";
            }
            else
            {
                $previd = $post_ids[$thisindex-1];
            }


            if( $thisindex == count($post_ids) -1 )
            {
                $nextid = "";
            }
            else
            {
                $nextid = $post_ids[$thisindex+1];
            }
        ?>

            <!-- Next Arrows -->
            <?php
            
            if ( !empty($nextid )):
                 ?>
                <a href="<?php echo esc_url(get_permalink( $nextid )); ?>" title="<?php esc_attr_e('NEXT', 'vitrine'); ?>"  class="portfolioDetailNavLink" data-pid="<?php echo esc_attr($nextid); ?>" data-skills="<?php echo esc_attr(implode(" ",$skill_ids)); ?>">
                    <div class="arrows-button-next no-select">
                        <span class="text">
                            <?php esc_html_e('NEXT', 'vitrine'); ?>
                        </span>
                    </div>
                </a>
            <?php endif; ?>

            <!-- Back to portfolio -->
            <a id="PDbackToPortfolio" href="<?php echo esc_url($back_url); ?>" title="<?php esc_attr_e('Back to portfolio', 'vitrine'); ?>" class="<?php if ( empty( $nextid ) ) { echo "noNext"; }?>">
                <div>
                    <span class="backToPortfolio" data-name="grid2"></span>
                </div>
            </a>

            <!-- Prev Arrows -->
            <?php
            if ( !empty( $previd ) ):
            ?>

                <a href="<?php echo esc_url(get_permalink( $previd )); ?>" title="<?php esc_attr_e('PREV', 'vitrine'); ?>" class="portfolioDetailNavLink" data-pid="<?php echo esc_attr($previd); ?>" data-skills="<?php echo esc_attr(implode(" ",$skill_ids)); ?>">
                    <div class="arrows-button-prev no-select">
                        <span class="text">
                            <?php esc_html_e('PREV', 'vitrine'); ?>
                        </span>
                    </div>
                </a>

            <?php endif;
        }
        die();
    }
}

if ( ! function_exists( 'epico_load_creative_portfolio_detail_navigation_action' ) ) {
    function epico_load_creative_portfolio_detail_navigation_action() {
		add_action('wp_ajax_load_cpd_navigation', 'epico_load_creative_portfolio_detail_navigation');
		add_action('wp_ajax_nopriv_load_cpd_navigation', 'epico_load_creative_portfolio_detail_navigation');
	}
}

epico_load_creative_portfolio_detail_navigation_action();
/*---------------------------------
    Get Portfolio Slides
------------------------------------*/
if (!function_exists('epico_thumbnail_post_slideshow')) {

    function epico_thumbnail_post_slideshow ($image_size, $id ,$post_name , $pDTargetCheck , $terms , $isLink , $pLink, $layout, $portolio_type ) {

        // Add slideshow JavaScript
        global $add_slider;
        $add_slider = true;

        $maxthumbnum = 3;

        // Set the slideshow variable
        $slideshow = '';

        // Get The Post Type
        $posttype = get_post_type( $id );

        // Check whether the slide should be link
        $permalink = get_permalink($id);
        $title = get_the_title($id); // get the item title
                
        //For custom taxonomy use the fallowing line
        $terms = wp_get_object_terms( $id , 'skills' );
        $term_names = array();
        foreach( $terms as $term )
            $term_names[] = $term->name;

        
        if ( $isLink == true) {
        
              $permalink = '<a href="'. esc_url($pLink) .'"  title="'.esc_attr($title).'" class="portfolioLink overlay thumbnail-'.esc_attr($image_size).'">';
        
        } else {
        
                if ( $pDTargetCheck == 'portfolio_detail_inner' ) { //portfolio Ajax enable
                    
                    if(is_home()){ // If Your Portfolio item in Home Page - Portfolio link for fetch Ajax is below form
                          
                        $permalink = '<a href="'. esc_url(home_url()) .'/#!portfolio-detail/'. esc_attr($post_name) .'"  title="'. esc_attr($title) .'" class="no_djax portfolioLink overlay thumbnail-'. esc_attr($image_size) .'">';
                        
                    } else {
                          $permalink = '<a href="'.   esc_url($_SERVER["REQUEST_URI"]) .'#!portfolio-detail/'. esc_attr($post_name) .'"  title="'. esc_attr($title) .'" class="no_djax portfolioLink overlay thumbnail-'. esc_attr($image_size) .'">';
                          
                    }
                    
                } else { //portfolio Ajax Disable
                    $permalink = '<a href="'. esc_url(home_url()) .'/?portfolio='.esc_attr($post_name).'"  title="'.esc_attr($title).'" class="portfolioLink overlay thumbnail-'.esc_attr($image_size).'" data-pid="'.esc_attr($id) .'">';
                }

        }
        
        $permalinkend = '</a>';

        $counter = 2; //start counter at 2

        $full = get_post_meta($id,'_thumbnail_id',false); // Get Image ID

        $image_size = 'epico_thumbnail-'.$image_size;

        //Set appropriate size for masonry style
        if($layout == 'masonry')
        {
             $image_size = 'epico_thumbnail-auto-height';
        }

        // Get all slides
        while ($counter <= ($maxthumbnum)) {

            ${"full" . $counter} = MultiPostThumbnails::get_post_thumbnail_id($posttype, $counter . '-slide', $id); // Get Image ID
            ${"full" . $counter} = wp_get_attachment_image_src(${"full" . $counter}, false); // URL of Second Slide Full Image

            ${"thumb" . $counter} = MultiPostThumbnails::get_post_thumbnail_id($posttype, $counter . '-slide', $id);
            ${"thumb" . $counter} = wp_get_attachment_image_src(${"thumb" . $counter}, $image_size, false); // URL of next Slide

        $counter++;

        }

        //number of entered thumbnails
        $maxthumbnumset = 0;
        if($full)
            $maxthumbnumset = 1;

        if(isset($thumb2[0]) && $thumb2[0] != '')
            $maxthumbnumset++;

        if(isset($thumb3[0]) && $thumb3[0] != '')
            $maxthumbnumset++;

        // If there's a featured image
        if($maxthumbnumset > 0) {
            $thumb = '';
            if($full)
            {
                $thumb = get_post_meta($id,'_thumbnail_id',false);

                $temporarythumb = wp_get_attachment_image_src($thumb[0], $image_size);  // URL of Featured first slide
                if ($temporarythumb[3] == 'true') {
                    $thumb = wp_get_attachment_image_src($thumb[0], 'full'); // load fullsize image insted when 'wp_get_attachment_image_src' function couldnt crop Images
                } else {
                    $thumb = $temporarythumb;
                }

            }

            $slideshow .= $permalink.'<div class="hoverContent">';
            $slideshow .= '<div class="frame top">';
            $slideshow .= '<div></div>';
            $slideshow .= '</div>';
            $slideshow .= '<div class="frame right">';
            $slideshow .= '<div></div>';
            $slideshow .= '</div>';
            $slideshow .= '<div class="frame bottom">';
            $slideshow .= '<div></div>';
            $slideshow .= '</div>';
            $slideshow .= '<div class="frame left">';
            $slideshow .= '<div></div>';
            $slideshow .= '</div>';
            $slideshow .= '<div class="icon-wrap"><div class="icon-type"></div></div><div class="center-line"></div><div class="title-wrap"><div class="titleContainer"><div class="hover-title">'.get_the_title($id).'</div><div class="hover-subtitle">'. implode( ', ', $term_names ). '</div></div></div>';
            $slideshow .= '</div>';
            $slideshow .=  $permalinkend;
            $slideshow .= '<div class="like">' . getPostLikeLink($id). '</div>';
            // If there's more than one slide and the device is not iPad
            if($maxthumbnumset > 1 && isset($_SERVER['HTTP_USER_AGENT']) && !strstr($_SERVER['HTTP_USER_AGENT'], 'iPad') )
                $slideshow .= '<div class="portfolioswiper swiper-container"><div class="swiper-wrapper">';

            $thumb1 = $thumb;

            // Loop through thumbnails and set them
            $tcounter = 1;
            while ( $tcounter <= $maxthumbnum ){
                if ( ${'thumb' . $tcounter}){

                    //Set appropriate size for masonry style
                    if($layout == 'masonry')
                    {
                        $img = '<img src="'. esc_url(${'thumb' . $tcounter}[0])  .'" alt="' . esc_attr($title) .'">';
                        $img = str_replace("src=", 'src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src=', $img);


                        $slideshow .= '<div class="pSlide lazy-load lazy-load-on-load" style="padding-top:' . esc_attr(epico_get_height_percentage('',${'thumb' . $tcounter}[1], ${'thumb' . $tcounter}[2])) . '%;">' . $img .'</div>';
                        break;

                    }
                    else
                    {

                        $height_percentage = '100%';
                        if($image_size == 'epico_thumbnail-slim')
                        {
                            $height_percentage = '200%';
                            if($portolio_type == "portfolio_text")
                            {
                                $height_percentage = 'calc(200% + 92px)'; // 77px meta height + 15 gutter
                            }
                            elseif($portolio_type == "portfolio_space")
                            {
                                $height_percentage = 'calc(200% + 15px)'; // 77px meta height + 15 gutter
                            }
                        }
                        elseif($image_size == 'epico_thumbnail-hslim')
                        {
                            $height_percentage = '50%';
                        }
                        elseif($image_size == 'epico_thumbnail-big')
                        {
                            $height_percentage = '100%';
                            if($portolio_type == "portfolio_text" )
                            {
                                $height_percentage = 'calc(100% + 77px)';
                            }

                        }
                        elseif($image_size == 'epico_thumbnail-wide')
                        {
                            $height_percentage = '25%';
                            if($portolio_type == "portfolio_space")
                            {
                                $height_percentage = 'calc(25% + 15px)'; // 77px meta height + 15 gutter
                            }
                        }

                        //If there is just 1 thumb or it is ipad
                        if($maxthumbnumset == 1 || (isset($_SERVER['HTTP_USER_AGENT']) && strstr($_SERVER['HTTP_USER_AGENT'], 'iPad')) )
                        {

                            
                            $slideshow .= '<div class="pSlide"><div class="lazy-load lazy-load-on-load bg-lazy-load" data-src="'. esc_url(${'thumb' . $tcounter}[0]) .'" style="padding-top:' . esc_attr($height_percentage) . ';"></div></div>';
                            break;
                        }
                        else
                        {
                            $slideshow .= '<div class="pSlide swiper-slide"><div class="lazy-load lazy-load-on-load bg-lazy-load" data-src="'. esc_url(${'thumb' . $tcounter}[0]) .'" style="padding-top:' . esc_attr($height_percentage) . ';"></div></div>';
                        }

                    }

                }
                $tcounter++;
            }

            if($maxthumbnumset > 1 && isset($_SERVER['HTTP_USER_AGENT']) && !strstr($_SERVER['HTTP_USER_AGENT'], 'iPad') )
                $slideshow .= '</div></div>';
            } 
            else {// when we have no featured image
            $thumb1=array(get_template_directory_uri() . '/assets/img/placeholders/noImage.jpg',300,300);//sets a default image with a default size
            $slideshow .= '<div class="pSlide swiper-slide lazy-load lazy-load-on-load bg-lazy-load" style="padding-top:'. esc_attr(epico_get_height_percentage('',$thumb1[1], $thumb1[2])) .'%;"></div>';
            $slideshow .= $permalink.'<div class="hoverContent">';
            $slideshow .= '<div class="frame top">';
            $slideshow .= '<div></div>';
            $slideshow .= '</div>';
            $slideshow .= '<div class="frame right">';
            $slideshow .= '<div></div>';
            $slideshow .= '</div>';
            $slideshow .= '<div class="frame bottom">';
            $slideshow .= '<div></div>';
            $slideshow .= '</div>';
            $slideshow .= '<div class="frame left">';
            $slideshow .= '<div></div>';
            $slideshow .= '</div>';
            $slideshow .= '<div class="icon-wrap"><div class="icon-type"></div></div><div class="center-line"></div><div class="title-wrap"><div class="titleContainer"><div class="hover-title">'.get_the_title($id).'</div><div class="hover-subtitle">'. implode( ', ', $term_names ). '</div></div></div></div>';
            $slideshow .=  $permalinkend;
            $slideshow .= '<div class="like">' . getPostLikeLink($id). '</div>';
            
        } // End if $full

        return $slideshow;

    }
}

/*---------------------------------
    Get Gallery Slides
------------------------------------*/

if (!function_exists('epico_thumbnail_gallery_slideshow')) {

    function epico_thumbnail_gallery_slideshow ($image_size, $id ,$post_name , $terms , $isLink , $pLink , $isVideo, $isAudio , $portfolioLoop , $count , $layout, $portolio_type ) {


        // Add slideshow javascript
        global $add_slider;
        $add_slider = true;
        
        // Set the slideshow variable
        $slideshow = '';

        // Get The Post Type
        $posttype = get_post_type( $id );

        // Check whether the slide should link
        $permalink = get_permalink($id);
        $title = get_the_title($id); // get the item title
        
        //For custom taxonomy use this line below
        $terms = wp_get_object_terms( $id , 'gallery_cat' );
        $term_names = array();
        foreach( $terms as $term )
        $term_names[] = $term->name;
        $galleryId = get_the_title($id);
        $galleryTitleSwitch = get_post_meta( $id , "title-bar", true );
        $galleryTitle = get_post_meta( $id , "title-text", true );
        $galleryExternalLink = get_post_meta( $id , "gallery-external-link", true );
        $galleryExternalLinkText = get_post_meta( $id , "gallery-external-link-text", true );
        $gallerySubTitle = get_post_meta( $id , "subtitle-text", true );


        if ( $galleryTitleSwitch  == '1' ) { // Show custom title

            if($galleryExternalLink && $galleryExternalLinkText) {
                $tilteDOM = "<h4> $galleryTitle </h4><p> $gallerySubTitle </p><p class='galleryexternalwrap'><a href='$galleryExternalLink' target='_blank' class='galleryexternallink'> $galleryExternalLinkText  </a></p>";
            } else {
                $tilteDOM = "<h4> $galleryTitle </h4><p> $gallerySubTitle </p>";
            }

        } else {  // Show gallery item title

             if($galleryExternalLink && $galleryExternalLinkText) {
              $tilteDOM = "<h4> $galleryId </h4><p class='galleryexternalwrap'><a  href='$galleryExternalLink' target='_blank' class='galleryexternallink'> $galleryExternalLinkText </a></p>";
            } else {
                $tilteDOM = "<h4> $galleryId </h4>";
            }

        }


        //Creating gallery thumbnail image
        $gallery_url[]='';
        $gallery_url = get_post_meta($id,'_thumbnail_id',false);
        if(!empty($gallery_url)){ 
             $gallery_url = wp_get_attachment_image_src($gallery_url[0], $image_size, false);
        }else{//If we had no gallery image
             $gallery_url[0]= get_template_directory_uri() . '/assets/img/placeholders/noImage.jpg';
        }

        ob_start();

        //Social Share
        $SocialShareHtml = ob_get_clean();

        if ( $isLink == true) {

              $permalink = '<div class="galleryItem" data-sub-html="'. esc_attr($tilteDOM) .'" data-src="'.  esc_url($pLink) .'"><a href="'.  esc_url($pLink) .'"  title="'.esc_attr($title).'" class="portfolioLink overlay thumbnail-'. esc_attr($image_size) .'">';

        } else if ( $isVideo == true){
                          //generate URL of video
                          $videoSRC=epico_get_meta('video-id');
                          $permalink = '<div class="galleryItem" data-sub-html="'. esc_attr($tilteDOM) .'" data-src="'. esc_url($videoSRC) .'"><a href="'. esc_url($gallery_url[0]).'"  title="'. esc_attr($title) .'" class=" portfolioLink overlay thumbnail-'. esc_attr($image_size) .'">';
        } else if ( $isAudio == true){
                          $soundCloudURL=epico_get_meta('audio-url');
                          $permalink = '<div class="galleryItem" data-sub-html="'. esc_attr($tilteDOM) .'" data-iframe="true" data-src="https://w.soundcloud.com/player/?visual=true&url='.esc_url($soundCloudURL).'"><a href="'.esc_url($gallery_url[0]) .'"  title="'. esc_attr($title) .'" class=" portfolioLink overlay thumbnail-'. esc_attr($image_size).'"><img src="'.esc_url($gallery_url[0]).'"/>';
        } else {
              if( $gallery_url[0]==''){
                  $gallery_url[0]=get_template_directory_uri() . '/assets/img/placeholders/noImage.jpg';
              }
                          $permalink = '<div class="galleryItem" data-sub-html="'. esc_attr($tilteDOM) .'" data-src="'. esc_url($gallery_url[0]) .'"><a href="'. esc_url($gallery_url[0]) .'"  title="'. esc_attr($title) .'" class="portfolioLink overlay thumbnail-'. esc_attr($image_size) .'"><img src="'. esc_url($gallery_url[0]) .'"/>';
        }
        
        $permalinkend = '</a></div>';

        $image_size = 'epico_thumbnail-'.$image_size;

        //Set appropriate size for masonry style
        if($layout == 'masonry')
        {
             $image_size = 'epico_thumbnail-auto-height';
        }

        // Show featured image
            $thumb[] = '';
                $thumb = get_post_meta($id,'_thumbnail_id',false);// Get Image ID + has featured image
                if(!empty($thumb)){

                $temporarythumb = wp_get_attachment_image_src($thumb[0], $image_size);

                if ($temporarythumb[3] == 'true') {
                    $thumb = wp_get_attachment_image_src($thumb[0], 'full'); // load fullsize image insted when 'wp_get_attachment_image_src' function couldnt crop Images
                } else {
                    $thumb = $temporarythumb;
                }

               }else{//If we had no gallery image
                   $thumb =wp_get_attachment_image_src(get_template_directory_uri() . '/assets/img/placeholders/noImage.jpg',$image_size, false);
               }

            $slideshow .= $permalink.'<div class="hoverContent">';
            $slideshow .= '<div class="frame top">';
            $slideshow .= '<div></div>';
            $slideshow .= '</div>';
            $slideshow .= '<div class="frame right">';
            $slideshow .= '<div></div>';
            $slideshow .= '</div>';
            $slideshow .= '<div class="frame bottom">';
            $slideshow .= '<div></div>';
            $slideshow .= '</div>';
            $slideshow .= '<div class="frame left">';
            $slideshow .= '<div></div>';
            $slideshow .= '</div>';
            $slideshow .= '<div class="title-wrap"><div class="titleContainer"><div class="hover-title" >'. esc_html(get_the_title($id)).'</div><div class="hover-subtitle">'. implode( ', ', $term_names ). '</div></div></div>';
            $slideshow .= '</div>';
            $slideshow .=  $permalinkend;
            $slideshow .= '<div class="like">' . getPostLikeLink($id). '</div>';

            // Set the thumbnails

            if($layout == 'masonry')
            {
                $img = '<img src="'. esc_url(${'thumb'}[0] ) .'" alt="' . esc_attr($title) .'">';
                $img = str_replace("src=", 'src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src=', $img);

                if($thumb ==''){//If we had no image, 300x300 is predefined size for thumbnails
                    $thumb= array(get_template_directory_uri() . '/assets/img/placeholders/noImage.jpg',300,300);
                }                    
                $slideshow .= '<div class="pSlide lazy-load lazy-load-on-load" data-src="'. esc_url( ${'thumb'}[0] ) .'" style="padding-top:' . esc_attr(epico_get_height_percentage('',${'thumb'}[1], ${'thumb'}[2])) . '%;">' . $img .'<a href="'. esc_url(${'thumb'}[0] )  .'" ></a></div>';
            }
            else
            {
                $height_percentage = '100%';

                if($image_size == 'epico_thumbnail-slim')
                {
                    $height_percentage = '200%';
                    if($portolio_type == "portfolio_text")
                    {
                        $height_percentage = 'calc(200% + 92px)'; // 77px meta height + 15 gutter
                    }
                    elseif($portolio_type == "portfolio_space")
                    {
                        $height_percentage = 'calc(200% + 15px)'; // 77px meta height + 15 gutter
                    }
                }
                elseif($image_size == 'epico_thumbnail-hslim')
                {
                    $height_percentage = '50%';
                }
                elseif($image_size == 'epico_thumbnail-big')
                {
                    $height_percentage = '100%';
                    if($portolio_type == "portfolio_text" )
                    {
                        $height_percentage = 'calc(100% + 77px)';
                    }

                }
                elseif($image_size == 'epico_thumbnail-wide')
                {
                    $height_percentage = '25%';
                    if($portolio_type == "portfolio_space")
                    {
                        $height_percentage = 'calc(25% + 15px)'; // 77px meta height + 15 gutter
                    }
                }

                $slideshow .= '<div class="pSlide lazy-load lazy-load-on-load bg-lazy-load" data-src="'. esc_url( ${'thumb'}[0] )  .'" style="padding-top:' . esc_attr($height_percentage) . ';"><a href="'. esc_url( ${'thumb'}[0] )  .'"></a></div>';
            }

        return $slideshow;
    }
}

/*-----------------------------------------------------------------*/
// allowed skype protocol
/*-----------------------------------------------------------------*/
if (!function_exists('epico_ss_allow_skype_protocol')) {
    function epico_ss_allow_skype_protocol( $protocols ){
        $protocols[] = 'skype';
        return $protocols;
    }
}

if (!function_exists('epico_ss_allow_skype_protocol_filter')) {
    function epico_ss_allow_skype_protocol_filter(){
		add_filter( 'kses_allowed_protocols' , 'epico_ss_allow_skype_protocol' );

    }
}
epico_ss_allow_skype_protocol_filter();

/*-----------------------------------------------------------------*/
// Output of new attributes in woocommerce frontend
/*-----------------------------------------------------------------*/
if (!function_exists('epico_wc_slider_variation_attribute_items')) {
    function epico_wc_slider_variation_attribute_items( $args = array() ) {
        $args = wp_parse_args( apply_filters( 'woocommerce_dropdown_variation_attribute_options_args', $args ), array(
            'options'          => false,
            'attribute'        => false,
            'product'          => false,
            'selected'         => false,
            'name'             => '',
            'id'               => '',
            'class'            => '',
            'show_option_none' => esc_html__( 'Choose an option', 'vitrine' )
        ) );

        $options   = $args['options'];
        $product   = $args['product'];
        $attribute = $args['attribute'];
        $name      = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title( $attribute );
        $id        = $args['id'] ? $args['id'] : sanitize_title( $attribute );
        $class     = $args['class'];


        if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
            $attributes = $product->get_variation_attributes();
            $options    = $attributes[ $attribute ];
        }

        $attr_values = get_post_meta( absint( $product->get_id() ), esc_attr( $attribute ) . '_extravalue',true);

        echo '<div class="attr-container image-attr">';
            echo '<div class="swiper-container">
                    <div class="swiper-wrapper">';
        $images_count = 0;

        if ( ! empty( $options ) ) {
            if ( $product && taxonomy_exists( $attribute ) ) {
                // Get terms if this is a taxonomy - ordered. We need the names too.
                $terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );
                
                foreach ( $terms as $term ) {

                    if ( in_array( $term->slug, $options ) ) {
                        if(isset($attr_values[$term->slug]))
                        {
                            $image = wp_get_attachment_image_src($attr_values[$term->slug],'thumbnail',false); //url of image
                            $image = $image[0];
                            
                            if(!$image)
                            {
                                $image = wp_get_attachment_url($attr_values[$term->slug]); //url of image
                            }

                            if($image)
                            {
                                $images_count++;
                                $selected = sanitize_title( $args['selected'] ) === $term->slug ? ' selected' : '';
                                echo '<div class="swiper-slide' . esc_attr($selected) .'" data-value="' . esc_attr( $term->slug ) . '"><image title="' .  esc_attr($term->slug) .'" alt="' .  esc_attr($term->slug) .'" src="' . esc_url($image) . '"></div>';
                            }
                        }

                    }
                }
            }
        }

        echo '</div>
                </div>';     

        if($images_count > 5)
        {
            echo '<div class="swiper-button-prev no-select"><span></span></div>
                  <div class="swiper-button-next no-select"><span></span></div>';
        }

        echo '</div>';


        //We keep select for using codes of add-to-cart-variation.js of woocommerce

        echo '<select id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . ' hide-attr-select" name="' . esc_attr( $name ) . '" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '">';

        if ( $args['show_option_none'] ) {
            echo '<option value="">' . esc_html( $args['show_option_none'] ) . '</option>';
        }

        if ( ! empty( $options ) ) {
            if ( $product && taxonomy_exists( $attribute ) ) {
                // Get terms if this is a taxonomy - ordered. We need the names too.
                $terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );

                foreach ( $terms as $term ) {
                    if ( in_array( $term->slug, $options ) ) {
                        echo '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $args['selected'] ), $term->slug, false ) . '>' . apply_filters( 'woocommerce_variation_option_name', $term->name ) . '</option>';
                    }
                }
            } else {
                foreach ( $options as $option ) {
                    // This handles < 2.4.0 bw compatibility where text attributes were not sanitized.
                    $selected = sanitize_title( $args['selected'] ) === $args['selected'] ? selected( $args['selected'], sanitize_title( $option ), false ) : selected( $args['selected'], $option, false );
                    echo '<option value="' . esc_attr( $option ) . '" ' . $selected . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</option>';
                }
            }
        }

        echo '</select>';
    }
}
/*-----------------------------------------------------------------*/
// Fixed add to cart
/*-----------------------------------------------------------------*/
if (!function_exists('epico_fixed_add_to_cart')) {
    function epico_fixed_add_to_cart() {
        $fixed_add_to_cart = epico_opt('shop-enable-fixed-addtocart');
        $catalog_mode = epico_opt('catalog_mode');
        if($fixed_add_to_cart == '1' && $catalog_mode == 0 && class_exists('Woocommerce'))
        {

            ?>
            <div class="cart fixed-add-to-cart-container">
                <div class="fixed-add-to-cart">
                    <?php
                    if(class_exists('YITH_Woocompare'))
                    { ?>
                        <a href="#" class="compare button" rel="nofollow"></a>
                    <?php
                    }

                    if (class_exists('YITH_WCWL'))
                    {
                        global $woocommerce,$yith_wcwl;

                        ?>
                        <div class="yith-wcwl-add-to-wishlist">
                            <div class="yith-wcwl-add-button">            
                                <a href="#" rel="nofollow" data-product-type="simple" class="add_to_wishlist" title="<?php esc_attr_e('Add to wishlist','vitrine'); ?>"></a>
                            </div>

                            <div class="wc-loading  ajax-loading" style="visibility:hidden;"></div>

                            <div class="yith-wcwl-wishlistaddedbrowse">
                                <a href="<?php echo esc_url($yith_wcwl->get_wishlist_url()); ?>" title="<?php esc_attr_e('Go to wishlist','vitrine'); ?>"></a>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    <a class="single_add_to_cart_button button alt product_type_simple add_to_cart_button" href="#" title="">
                        <span class="icon"></span>
                        <span class="txt" data-hover="">
                                
                        </span>
                    </a>
                    <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="added_to_cart wc-forward hide"></a>
                </div>
            </div>
            <?php

        }

    }
}

if (!function_exists('epico_fixed_add_to_cart_action')) {
    function epico_fixed_add_to_cart_action() {
		add_action( 'wp_footer', 'epico_fixed_add_to_cart' );
	}
}
epico_fixed_add_to_cart_action();

/*-----------------------------------------------------------------*/
// Newsletter embedding (MailPoet)
/*-----------------------------------------------------------------*/
if (!function_exists('epico_get_mail_poet_forms')) {
    function epico_get_mail_poet_forms(){
        
        // Get WPDB Object
           global $wpdb;

        if (class_exists( 'WYSIJA_NL_Widget' )) {// If the plugin is installed and activated create the shortcode       
            
            // Get Form Values and IDs
            $table_name = $wpdb->prefix . "wysija_form";
            if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name){//If we had the DB
                $mailPoetForm=$wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wysija_form" );
                $items=array();

                // Iterate over the Forms
                foreach($mailPoetForm  as $value)
                {
                     $items[$value->name]=$value->form_id;
                }
                if(!is_array($items))
                    return array();

                return $items;
            }

            return array();

        }

        return array();
    }
}

/*-----------------------------------------------------------------*/
// Check if current request is an AJAX request */
/*-----------------------------------------------------------------*/
if (!function_exists('epico_is_ajax_request')) {
    function epico_is_ajax_request() {
        if ( ! empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ) {
            return true;
        }

        return false;
    }
}

//Check if current request is an AJAX request for main-loop shop */
if (!function_exists('epico_is_shop_ajax_request')) {
    function epico_is_shop_ajax_request() {
        if(epico_is_ajax_request() == true && isset($_POST['ajax_shop_req']) && $_POST['ajax_shop_req'] == true)
        {
            return true;
        }

        return false;
    }
}

//Check if current request is an AJAX request for main-loop shop */
if (!function_exists('epico_is_shop_ajax_add_to_cart')) {
    function epico_is_shop_ajax_add_to_cart() {

        if(epico_is_ajax_request() == true && ((isset($_GET['wc-ajax']) && $_GET['wc-ajax'] == 'add_to_cart') || (isset($_GET['ep-ajax-add-to-cart']) && $_GET['ep-ajax-add-to-cart'] == '1')))
        {
            return true;
        }

        return false;
    }
}
//Check if current request is an AJAX request for main-loop shop */
if (!function_exists('epico_is_djax_request')) {
    function epico_is_djax_request() {
        if(epico_is_ajax_request() == true && isset($_GET['djax_req']) && $_GET['djax_req'] == true)
        {
            return true;
        }

        return false;
    }
}

/**************************************************
    Custom Excerpt for posts + no format box
**************************************************/
if (!function_exists('epico_default_hidden_meta_boxes')) {
    function epico_default_hidden_meta_boxes( $hidden, $screen ) {
        // Grab the current post type
        $post_type = $screen->post_type;
        // If we're on a 'post'...
        if ( $post_type == 'post' ) {
            // Define which meta boxes we wish to hide
            $hidden = array(
                'trackbacksdiv',
                'slugdiv',
                'revisionsdiv',
                'postcustom',
                'commentstatusdiv',
                'authordiv',
                'formatdiv',
            );
            // Pass our new defaults onto WordPress
            return $hidden;
        }
        // If we are not on a 'post', pass the
        // original defaults, as defined by WordPress
        return $hidden;
    }
}
if (!function_exists('epico_default_hidden_meta_boxes_action')) {
    function epico_default_hidden_meta_boxes_action() {
		add_action( 'default_hidden_meta_boxes', 'epico_default_hidden_meta_boxes', 10, 2 );
	}
}
epico_default_hidden_meta_boxes_action();

/**************************************************
    increase quality of WordPress thumbnails images.
**************************************************/
if (!function_exists('epico_thumbnail_quality')) {
    function epico_thumbnail_quality( $quality ) {
        return 100;
    }
}

if (!function_exists('epico_thumbnail_quality_filter')) {
    function epico_thumbnail_quality_filter() {
		add_filter( 'jpeg_quality', 'epico_thumbnail_quality' );
		add_filter( 'wp_editor_set_quality', 'epico_thumbnail_quality' );
    }
}

epico_thumbnail_quality_filter();

/*---------------------------------
    epico_get_template_part
------------------------------------*/
/**
 * Like get_template_part() lets you pass args to the template file
 * Args are available in the tempalte as $template_args array
 * @param string filepart
 * @param mixed wp_args style argument list
 */

if ( ! function_exists( 'epico_get_template_part' ) ) {

    function epico_get_template_part( $file, $template_args = array(), $cache_args = array() ) {
        $template_args = wp_parse_args( $template_args );
        $cache_args = wp_parse_args( $cache_args );
        if ( $cache_args ) {
            foreach ( $template_args as $key => $value ) {
                if ( is_scalar( $value ) || is_array( $value ) ) {
                    $cache_args[$key] = $value;
                } else if ( is_object( $value ) && method_exists( $value, 'get_id' ) ) {
                    $cache_args[$key] = call_user_method( 'get_id', $value );
                }
            }
            if ( ( $cache = wp_cache_get( $file, serialize( $cache_args ) ) ) !== false ) {
                if ( ! empty( $template_args['return'] ) )
                    return $cache;
                echo $cache;
                return;
            }
        }
        $file_handle = $file;
        do_action( 'start_operation', 'epico_template_part::' . $file_handle );
        if ( file_exists( get_stylesheet_directory() . '/' . $file . '.php' ) )
            $file = get_stylesheet_directory() . '/' . $file . '.php';
        elseif ( file_exists( get_template_directory() . '/' . $file . '.php' ) )
            $file = get_template_directory() . '/' . $file . '.php';
        ob_start();
        $return = require( $file );
        $data = ob_get_clean();
        do_action( 'end_operation', 'epico_template_part::' . $file_handle );
        if ( $cache_args ) {
            wp_cache_set( $file, $data, serialize( $cache_args ), 3600 );
        }
        if ( ! empty( $template_args['return'] ) )
            if ( $return === false )
                return false;
            else
                return $data;
        echo $data;
    }
}

/*---------------------------------
        Catalog Mode
------------------------------------*/
if ( ! function_exists( 'epico_catalog_mode_pages_redirect' ) ) {
    function epico_catalog_mode_pages_redirect() {

        if(!class_exists('Woocommerce'))
            return;

        $cart     = is_page( wc_get_page_id( 'cart' ) );
        $checkout = is_page( wc_get_page_id( 'checkout' ) );

        wp_reset_query();

        if ( $cart || $checkout ) {

            wp_redirect( home_url() );
            exit;
        }

    }
}

if ( ! function_exists( 'epico_catalog_mode' ) ) {
    function epico_catalog_mode() {
        $catalog_mode =  epico_opt('catalog_mode');
        $catalog_mode_price =  epico_opt('catalog_mode_price');
        if($catalog_mode != 0){

            //Remove add to cart button
           remove_action( 'epico_woocommerce_shop_loop_buttons', 'woocommerce_template_loop_add_to_cart', 10 );
            remove_action( 'quick_view_product_summary', 'woocommerce_template_single_add_to_cart', 25 );
			remove_action( 'woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30 );
			remove_action( 'woocommerce_grouped_add_to_cart', 'woocommerce_grouped_add_to_cart', 30 );
			remove_action( 'woocommerce_external_add_to_cart', 'woocommerce_external_add_to_cart', 30 );
			remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20 );
			remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation', 10 );
            


            //Disable any add to cart link(shortcodes, shop ...)
            add_filter( 'woocommerce_loop_add_to_cart_link', '__return_empty_string', 10 );

            //Disable add to cart functionality
            $priority = has_action( 'wp_loaded', array( 'WC_Form_Handler', 'add_to_cart_action' ) );
            remove_action( 'wp_loaded', array( 'WC_Form_Handler', 'add_to_cart_action' ), $priority );

            //Redirect "cart" and "checkout" page
            add_action( 'wp', 'epico_catalog_mode_pages_redirect' );

            // hiden price in catalog mode 
            if($catalog_mode_price != 0){
			    //Remove price from shop page, product details and quick view
			    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
			    remove_action( 'quick_view_product_summary', 'woocommerce_template_single_price', 15 );
			    remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
		    }
        }
		

    }
}

epico_catalog_mode();


 
/*-----------------------------------------------------------------*/
//Enable/disable related products */
/*-----------------------------------------------------------------*/

if ( ! function_exists( 'epico_related_product' ) ) {
    function epico_related_product() {
		$related_product =  epico_opt('related_product');
		if ($related_product != 1 ){
			remove_action( 'woocommerce_after_single_product_summary','woocommerce_output_related_products', 20 );
        }
    }
}

epico_related_product();


// Change number of related products output
if ( ! function_exists( 'epico_related_products_limit' ) ) {

        $related_product_display =  epico_opt('related_product_display'); // check related product on grid mode or carousel mode 
        
        if($related_product_display == 0) {

            function epico_related_products_limit() {
                global $product;
	
                $args['posts_per_page'] = 6;
                return $args;
            }
            add_filter( 'woocommerce_output_related_products_args', 'ep_related_products_args' );

            function ep_related_products_args( $args ) {
                $args['posts_per_page'] = 6; // 4 related products
                $args['columns'] = 2; // arranged in 2 columns
                return $args;
            }
        }
}


/*-----------------------------------------------------------------*/
// percentage Sale
/*-----------------------------------------------------------------*/
if ( ! function_exists( 'epico_percentage_sale' ) ) {
    function epico_percentage_sale() {
        $percentage_sale =  epico_opt('percentage_sale');
        if($percentage_sale != 0){
          add_filter ('woocommerce_sale_flash', 'epico_percentage_sale_filter');  
        }
    }
}

if( !function_exists( 'epico_percentage_sale_filter' )){

    function epico_percentage_sale_filter(){ 
        global $post, $product;

        if ( ! $product->is_on_sale() )
          return;
        $maximumper = 0;

        if ($product->is_type('variable') ) { 
            $maximumper = 0;
            $available_variations = $product->get_available_variations();

            for ($i = 0; $i < count($available_variations); ++$i) {
                $variation_id=$available_variations[$i]['variation_id'];
                $variation= new WC_Product_Variation( $variation_id );
                $regular_price = $variation ->get_regular_price();
                $sale_price = $variation ->get_sale_price();

                if($regular_price == 0)
                    continue;

               $savings = ceil(( ($regular_price - $sale_price) / $regular_price ) * 100);
               if ($savings > $maximumper) {
                  $maximumper = $savings;
                }       
            }
        }  
        elseif ($product->is_type('simple') || $product->is_type('external') ) {
            $sale_price = $product->get_sale_price();
            $regular_price = $product->get_regular_price();

            if($regular_price == 0)
                return;

            $savings = ceil(( ($regular_price - $sale_price) / $regular_price ) * 100);
            $maximumper = $savings;
        }             
        elseif ( $product->is_type( 'grouped' ) ){
            $product_id = $product->get_id();
            $childs_id = $product->get_children();
            $maximumper = 0;
            for ($i = 0; $i < count($childs_id); ++$i) {
                $product_child_id=$childs_id[$i];
                $simple= wc_get_product( $product_child_id );

                if($product->is_type('simple')) // Just handle the simple products as child of grouped product
                {
                    $regular_price = $product ->get_regular_price();
                    $sale_price =  $product->get_sale_price();
                    $savings = ceil(( ($regular_price - $sale_price) / $regular_price ) * 100);

                    if($regular_price == 0)
                        continue;
                    
                    if ($savings > $maximumper) {
                        $maximumper = $savings;
                    }
                }
            }
        }  
                
        if($maximumper == 0)
            return;
    
        $sale_flash = '<span class="onsale percentage-sale">' .'-'. $maximumper. '%'  . '</span>';
        return $sale_flash;
    }
    
}
 
epico_percentage_sale();


/*-----------------------------------------------------------------*/
// Size Guide plugin
/*-----------------------------------------------------------------*/

if(!function_exists('epico_size_guide_plugin_styles')) {

    function epico_size_guide_plugin_styles() {
        //dequeue assets to use vitrine modal
        wp_dequeue_style( 'ct.sizeguide.style.css');
        wp_dequeue_style( 'magnific.popup.css' );
        wp_dequeue_script( 'magnific.popup.js' );
        wp_dequeue_script( 'ct.sg.front.js' );
    }
}

if(!function_exists('epico_size_guide_plugin_styles_action')) {
    function epico_size_guide_plugin_styles_action() {
		add_action( 'wp_print_styles', 'epico_size_guide_plugin_styles' );
    }
}
epico_size_guide_plugin_styles_action();


/*-----------------------------------------------------------------*/
// Custom Product Tabs for WooCommerce - Remove title
/*-----------------------------------------------------------------*/
if(!function_exists('epico_remove_yikes_custom_tab_heading')) {

    function epico_remove_yikes_custom_tab_heading($heading) {
        return '';
    }
}

if(!function_exists('epico_remove_yikes_custom_tab_heading_filter')) {
    function epico_remove_yikes_custom_tab_heading_filter() {
		add_filter( 'yikes_woocommerce_custom_repeatable_product_tabs_heading', 'epico_remove_yikes_custom_tab_heading' );
    }
}
epico_remove_yikes_custom_tab_heading_filter();
/*-----------------------------------------------------------------*/
// Product gallery shows on products page
/*-----------------------------------------------------------------*/

if ( ! function_exists( 'epico_product_gallery' ) ) {
    function epico_product_gallery_popup() {
		$product_gallery_popup = epico_opt('product_gallery_popup');
		$product_gallery_style = epico_opt('product_gallery_style');

		$style = '';
		if($product_gallery_style != 0)
		{
			$style = ' dark';
		}

		if ($product_gallery_popup != 0 ){
			echo '<a id="product_gallery_popup">'.'<div class="popup-button'. $style .'">'.'<span class="ep-icon icon-expand5" >'.'</span>'.'</div>'.'</a>' ;
		}
	}
}

if ( ! function_exists( 'epico_product_gallery_action' ) ) {
    function epico_product_gallery_action() {
		add_action( 'woocommerce_product_thumbnails', 'epico_product_gallery_popup', 9 );
	}
}

epico_product_gallery_action();


/*-----------------------------------------------------------------*/
// wrap all sidebars
/*-----------------------------------------------------------------*/

if (! function_exists('epico_widget_sidebar')){
	function epico_widget_sidebar($index){
		$footer_areas = array("footer-widget-1","footer-widget-2","footer-widget-3","footer-widget-4","footer-widget-5","footer-widget-6","footer-widget-7");
		if(in_array($index, $footer_areas))
			return;
		
		if( !is_admin()) {
			echo '<div class="sidebar widget-area">';
		}
	}	
}


if (! function_exists('epico_widget_sidebar_end')){
	function epico_widget_sidebar_end($index){
		$footer_areas = array("footer-widget-1","footer-widget-2","footer-widget-3","footer-widget-4","footer-widget-5","footer-widget-6","footer-widget-7");
		if(in_array($index, $footer_areas))
			return;		
		
		if( !is_admin() && $index != "sidebar-store") { // sidebar-store is sidebar of dokan plugin (Due to adding widgets witout dynamic_sidebar when sidebar is empty in dokan! the closing tag in "sidebar-store" addded by "dokan_sidebar_store_after" action  )
			echo '</div>';
		}
	}
}

if (! function_exists('epico_dokan_widget_sidebar_end')){
	function epico_dokan_widget_sidebar_end(){
		if( !is_admin() ) {
			echo '</div>';
		}
	}
}

if (! function_exists('epico_widget_sidebar_action')){
	function epico_widget_sidebar_action(){
		add_action( 'dynamic_sidebar_before', 'epico_widget_sidebar' ,10);
		add_action( 'dynamic_sidebar_after', 'epico_widget_sidebar_end',10 );
		add_action( 'dokan_sidebar_store_after', 'epico_dokan_widget_sidebar_end',10 );
	}
}
epico_widget_sidebar_action();

/*-----------------------------------------------------------------*/
// Custom fields of WC categories
/*-----------------------------------------------------------------*/
if(!function_exists('epico_wc_cat_taxonomy_add_meta_field')) {
	function epico_wc_cat_taxonomy_add_meta_field() {
		?>
		<div class="form-field term-header-image-wrap">
			<label><?php _e( 'Header background image', 'vitrine' ); ?><label>
			<div id="product_cat_background_image" data-default-img="<?php echo wc_placeholder_img_src(); ?>"><img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" width="60%"/></div>
			<input type="hidden" id="header-background-image" name="header-background-image" />
			<button type= type="button" class="upload_wc_cat_header_image_button button"><?php _e( 'Upload/Add image', 'vitrine' ); ?></button>
			<button type="button" class="remove_wc_cat_header_image_button button"><?php _e( 'Remove image', 'vitrine' ); ?></button>
		</div>
		<div class="form-field term-header-color-wrap">
			<label><?php _e( 'Header text color', 'vitrine' ); ?></label>
			<div class="color-field-wrap clear-after">
				<input name="header-text-color" data-alpha="true" type="text" value="" class="colorinput"/>
				<div class="color-view"></div>
			</div>
		</div>
	<?php
	}
}
		
if(!function_exists('epico_wc_cat_taxonomy_edit_meta_field')) {
	function epico_wc_cat_taxonomy_edit_meta_field($term ) {
		
		$image 			= '';
		$header_id 	= absint( get_woocommerce_term_meta( $term->term_id, 'header-background-image', true ) );
		$header_text_color 	= get_woocommerce_term_meta( $term->term_id, 'header-text-color', true );

		if ($header_id) :
			$image = wp_get_attachment_url( $header_id );
		else :
			$image = wc_placeholder_img_src();
		endif;

		?>
		
		<tr class="form-field">
			<th scope="row" valign="top"><label><?php _e( 'Header background image', 'vitrine' ); ?></label></th>
			<td>
				<div id="product_cat_background_image" data-default-img="<?php echo wc_placeholder_img_src(); ?>"><img src="<?php echo esc_url( $image ); ?>" width="60%"/></div>
					<input type="hidden" id="header-background-image" name="header-background-image" value="<?php echo $header_id; ?>" />
					<button type= type="button" class="upload_wc_cat_header_image_button button"><?php _e( 'Upload/Add image', 'vitrine' ); ?></button>
					<button type="button" class="remove_wc_cat_header_image_button button"><?php _e( 'Remove image', 'vitrine' ); ?></button>
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row" valign="top"><label><?php _e( 'Header text color', 'vitrine' ); ?></label></th>
			<td>
				<div class="field color-field clear-after">
					<div class="color-field-wrap clear-after">
						<input name="header-text-color" data-alpha="true" type="text" value="<?php echo esc_attr($header_text_color); ?>" class="colorinput"/>
						<div class="color-view"></div>
					</div>
				</div>
			</td>
		</tr>
	<?php
	}	
}
             
if(!function_exists('epico_save_wc_cat_taxonomy_custom_meta')) {
	function epico_save_wc_cat_taxonomy_custom_meta( $term_id, $tt_id = '', $taxonomy = '' ) {
		if ( isset( $_POST['header-background-image'] ) ) {
			update_woocommerce_term_meta( $term_id, 'header-background-image', absint( $_POST['header-background-image'] ) );
			update_woocommerce_term_meta( $term_id, 'header-text-color', $_POST['header-text-color'] );
		}

		delete_transient( 'wc_term_counts' );	
	}	
}

if(!function_exists('epico_wc_category_custom_field_action')) {
	function epico_wc_category_custom_field_action() {
		add_action( 'product_cat_edit_form_fields', 'epico_wc_cat_taxonomy_edit_meta_field', 15);
		add_action( 'product_cat_add_form_fields', 'epico_wc_cat_taxonomy_add_meta_field', 15 );
		add_action( 'edited_product_cat', 'epico_save_wc_cat_taxonomy_custom_meta', 10, 3 );  
		add_action( 'create_product_cat', 'epico_save_wc_cat_taxonomy_custom_meta', 10, 3 );
	}
}
epico_wc_category_custom_field_action();


/*-----------------------------------------------------------------*/
// Avatar in my account page
/*-----------------------------------------------------------------*/
if(!function_exists ('epico_myaccount_customer_avatar')){
	function epico_myaccount_customer_avatar(){ 
		$current_user = wp_get_current_user();
		if ( $current_user instanceof WP_User ) {
			echo '<div class="myaccount_avatar">' . get_avatar( $current_user->user_email, 100 ) . '<h5>' . $current_user->display_name . '</h5></div>' ;
		}
	}
}

if(!function_exists ('epico_myaccount_customer_avatar_action')){
	function epico_myaccount_customer_avatar_action(){ 
		add_action( 'epico_woocommerce_before_account_navigation', 'epico_myaccount_customer_avatar' );
	}
}
epico_myaccount_customer_avatar_action();


/*-----------------------------------------------------------------*/
// Login/rgister popup
/*-----------------------------------------------------------------*/
if ( ! function_exists( 'epico_login_popup_action' ) ) {
    function epico_login_popup_action() {
		add_action( 'wp_footer', 'epico_load_account_page' );
	}
}
epico_login_popup_action();


if(!function_exists ('epico_load_account_page')){ 
	function epico_load_account_page(){
		
		if((!is_user_logged_in()) && (is_plugin_active( 'woocommerce/woocommerce.php' )) )
		{
			?>
			<div id="customer_login" class="hide-login <?php if(get_option( 'woocommerce_enable_myaccount_registration' ) != 'yes') { echo "no-registration"; } ?>">
				<h2><?php _e( 'Login', 'vitrine' ); ?></h2>
				<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) {
					$myaccount_url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
					echo '<a class="register-link" href="'. $myaccount_url .'">' . esc_html__('Create an account', 'vitrine') . '</a>';
				} ?>

				<form class="woocommerce-form woocommerce-form-login login" method="post">

					<?php do_action( 'woocommerce_login_form_start' ); ?>

					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label for="username"><?php _e( 'Username or email address', 'vitrine' ); ?> <span class="required">*</span></label>
						<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( $_POST['username'] ) : ''; ?>" />
					</p>
					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label for="password"><?php _e( 'Password', 'vitrine' ); ?> <span class="required">*</span></label>
						<input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" />
					</p>

					<?php do_action( 'woocommerce_login_form' ); ?>

					<p class="form-row">
						<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
						<input type="submit" class="woocommerce-Button button" name="login" value="<?php esc_attr_e( 'Login', 'vitrine' ); ?>" />
						<label class="woocommerce-form__label woocommerce-form__label-for-checkbox inline">
							<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php _e( 'Remember me', 'vitrine' ); ?></span>
						</label>
						<span class="woocommerce-LostPassword lost_password">
							<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php _e( 'Lost your password?', 'vitrine' ); ?></a>
						</span>
					</p>

					<?php do_action( 'woocommerce_login_form_end' ); ?>

				</form>
			</div>
			<?php
		}
	}
}


/*-----------------------------------------------------------------*/
// Woocommerce loop - add-to-cart buttons
/*-----------------------------------------------------------------*/
if ( ! function_exists( 'epico_woocommerce_loop_add_to_cart_link_action' ) ) {
    function epico_woocommerce_loop_add_to_cart_link_action() {
		add_filter('woocommerce_product_add_to_cart_text', 'epico_woocommerce_loop_add_to_cart_text', 100, 1);
        add_filter( 'woocommerce_loop_add_to_cart_link', 'epico_woocommerce_loop_add_to_cart_link', 100, 2 );
        add_filter( 'epico_loop_instant_shop_add_to_cart_link', 'epico_loop_instant_shop_add_to_cart_link', 10, 1 );
		add_filter( 'epico_loop_modern_add_to_cart_link', 'epico_loop_modern_add_to_cart_link', 10, 1 );
	}
}
epico_woocommerce_loop_add_to_cart_link_action();


/* Add a wrapper around add-to-cart link */
if ( ! function_exists( 'epico_woocommerce_loop_add_to_cart_link' ) ) {
    function epico_woocommerce_loop_add_to_cart_link($link, $product) {
		
		//Add some class for compatibility with 3rd-party plugins such as wooZone
		$class = sprintf( 'class="button %s product_type_%s %s"',
			$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
			esc_attr( $product->get_type() ),
			esc_attr( $product->get_type() == 'simple' && 'yes'  === get_option( 'woocommerce_enable_ajax_add_to_cart' ) ? 'ajax_add_to_cart':'')
		);

        $link = str_replace('class="button"', $class, $link);
		$link = str_replace('class="ajax_add_to_cart button"', $class, $link);
		
		return '<span class="product-button product_type_' . $product->get_type() .'">' . $link .'</span>';
	}
}


/* Change inner structure of add-to-cart button */
if ( ! function_exists( 'epico_woocommerce_loop_add_to_cart_text' ) ) {
    function epico_woocommerce_loop_add_to_cart_text($text) {
		return '<span class="icon"></span><span class="txt" data-hover="' . esc_attr($text) .'">' . $text .'</span>';
	}
}


/* Change structure of add-to-cart button in instant-shop product style */
if ( ! function_exists( 'epico_loop_instant_shop_add_to_cart_link' ) ) {
    function epico_loop_instant_shop_add_to_cart_link($link) {
        //remove icon
        $newLink = str_replace('<span class="icon"></span>', '', $link);

        //change classes of A tag
        $newLink = str_replace('<span class="product-button product_type_', '<span class="product_type_', $newLink);

        //change classes of wrapper of A tag
        $newLink = str_replace('class="button ', 'class="', $newLink);


        return $newLink;
    }
}


/* Change structure of add-to-cart button in product2 shortcode */
if ( ! function_exists( 'epico_loop_modern_add_to_cart_link' ) ) {
    function epico_loop_modern_add_to_cart_link($link) {


        $pattern = '#<span class="txt"(.*?)</span>#';

        preg_match (  $pattern ,  $link, $result );

        $text_span = $text = '';

        if($result[0])
        {
            $text_span = $result[0];

            //Find the text
            $pattern = '#class="txt" data-hover="(.*?)">#';
            preg_match (  $pattern ,  $text_span, $result );
            if($result[1])
            {
                $text = $result[1];

                $link = str_replace($text_span, '<span class="firts_text txt hidden-v-tablet hidden-phone">' . $text .'</span>
                                                <span class="secound_txt txt hidden-v-tablet hidden-phone">' . $text .'</span>', $link);
            }

            return $link;

        }
        else
        {
            return $link;
        }
    }
}

/*-----------------------------------------------------------------*/
// Cookie Law info  - cookie bar 
/*-----------------------------------------------------------------*/

if( ! function_exists( 'ep_cookies_popup' ) ) {

	add_action( 'wp_footer', 'ep_cookies_popup', 300 );

	function ep_cookies_popup() {

        if( ! epico_opt('cookies_info')  || epico_opt('cookies_info') == '0' ) 
            return;

		$page_id = epico_opt( 'cookies_policy_page' );

		?>
			<div class="ep-cookies-bar">
				<div class="ep-cookies-inner">
					<div class="cookies-info-text">
						<?php echo do_shortcode( epico_opt( 'cookies_text_message' ) ); ?>
					</div>
					<div class="cookies-buttons">
                        <a href="#" class="cookies-accept-btn"><?php esc_html_e( 'Accept' , 'vitrine' ); ?></a>
                        <?php if($page_id): ?>
                             <a href="<?php echo get_page_link($page_id); ?>" class="cookies-more-btn" target="_blank"><?php esc_html_e( 'More info' , 'vitrine' ); ?></a>
                        <?php endif; ?>
					</div>
				</div>
			</div>
		<?php

	}
}

/*** set cookie when woocommerce is not installed */

if( ! function_exists( 'epico_woocommerce_installed' ) ) {
	function epico_woocommerce_installed() {
	    return class_exists( 'WooCommerce' );
	}
}

if( ! epico_woocommerce_installed() ){
	add_action( 'wp_enqueue_scripts', 'ep_custom_frontend_scripts' );
}

if ( ! function_exists ('ep_custom_frontend_scripts' )){
	function ep_custom_frontend_scripts() 
	{
		wp_register_script( 'js-cookie', EPICO_THEME_ASSETS_URI . '/js/js.cookie.js', array( 'jquery' ), '2.1.4', true ); 
		wp_enqueue_script('js-cookie');
	}
}


/*-----------------------------------------------------------------*/
// 					 Maintenance mode
/*-----------------------------------------------------------------*/

if( ! function_exists( 'ep_maintenance_page' ) ) {
	function ep_maintenance_page() {
		if( ! epico_opt( 'maintenance_mode' ) || is_user_logged_in() || epico_opt( 'maintenance_mode' ) == "0"  ) 
			return;
	 	$page_id = epico_opt( 'maintenance_page' );

		if( ! $page_id ) 
			return;
		 
        if( ! is_page( $page_id ) && ! is_user_logged_in() ) { 
            wp_redirect( get_permalink( $page_id ) );
            exit();
        } 
	}
}
if( ! function_exists( 'ep_maintenance_page_action' ) ) {
	function ep_maintenance_page_action() {
		add_action( 'get_header', 'ep_maintenance_page' );
	}
}
ep_maintenance_page_action();


/*-----------------------------------------------------------------*/
// 					Instagram
/*-----------------------------------------------------------------*/

function epicomedia_compress($variable){
	return base64_encode($variable);
}

function epicomedia_decompress($variable){
	return base64_decode($variable);
}

function epicomedia_get_svg($variable){
    return file_get_contents($variable);
}


if( ! function_exists( 'epicomedia_pretty_number' ) ) {
	function epicomedia_pretty_number( $x = 0 ) {
		$x = (int) $x;

		if( $x > 1000000 ) {
			return floor( $x / 1000000 ) . 'M';
		}

		if( $x > 10000 ) {
			return floor( $x / 1000 ) . 'k';
		}
		return $x;
	}
}

if( ! function_exists( 'epicomedia_scrape_instagram' ) ) {
	function epicomedia_scrape_instagram( $username, $slice = 9 ) {
		$username = strtolower( $username );
		$by_hashtag = ( substr( $username, 0, 1 ) == '#' );
		$transient_name = 'instagram-media-new-' . sanitize_title_with_dashes( $username );
		$instagram = get_transient( $transient_name );
		
		if ( false === $instagram ) {
			
			$request_param = ( $by_hashtag ) ? 'explore/tags/' . substr( $username, 1 ) : trim( $username );
			$remote = wp_remote_get( 'https://instagram.com/'. $request_param );
			
			if ( is_wp_error( $remote ) ) {
				return new WP_Error( 'site_down', esc_html__( 'Unable to communicate with Instagram.', 'epicomedia' ) );
			}

			if ( 200 != wp_remote_retrieve_response_code( $remote ) ) {
				return new WP_Error( 'invalid_response', esc_html__( 'Instagram did not return a 200.', 'epicomedia' ) );
			}
				
			$shards = explode( 'window._sharedData = ', $remote['body'] );
			$insta_json = explode( ';</script>', $shards[1] );
			$insta_array = json_decode( $insta_json[0], TRUE );

			if ( ! $insta_array ){
				return new WP_Error( 'bad_json', esc_html__( 'Instagram has returned invalid data.', 'epicomedia' ) );
			}
				
			if ( isset( $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'] ) ) {
				$images = $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'];
			} elseif( $by_hashtag && isset( $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'] ) ) {
		        $images = $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'];
		    } else {
				return new WP_Error( 'bad_json_2', esc_html__( 'Instagram has returned invalid data.', 'epicomedia' ) );
			}

			if ( ! is_array( $images ) ) {
				return new WP_Error( 'bad_array', esc_html__( 'Instagram has returned invalid data.', 'epicomedia' ) );
			}
				
			$instagram = array();
			
			foreach ( $images as $image ) {
				$image = $image['node'];


               /// var_dump($image);

				$caption = esc_html__( 'Instagram Image', 'epicomedia' );
				if ( ! empty( $image['edge_media_to_caption']['edges'][0]['node']['text'] ) ) $caption = $image['edge_media_to_caption']['edges'][0]['node']['text'];

				$image['thumbnail_src'] = preg_replace( "/^https:/i", "", $image['thumbnail_src'] );
				$image['thumbnail'] = preg_replace( "/^https:/i", "", $image['thumbnail_resources'][0]['src'] );
				$image['medium'] = preg_replace( "/^https:/i", "", $image['thumbnail_resources'][2]['src'] );
				$image['large'] = $image['thumbnail_src'];
				

				$type = ( $image['is_video'] ) ? 'video' : 'image';
				
				$instagram[] = array(
					'description'   => $caption,
					'link'		  	=> '//instagram.com/p/' . $image['shortcode'],
					'comments'	  	=> $image['edge_media_to_comment']['count'],
					'likes'		 	=> $image['edge_liked_by']['count'],
					'thumbnail'	 	=> $image['thumbnail'],
					'medium'		=> $image['medium'],
					'large'			=> $image['large'],
					'type'		  	=> $type
				);
			}
		
			// do not set an empty transient - should help catch private or empty accounts
			if ( ! empty( $instagram ) ) {
				$instagram = epicomedia_compress( maybe_serialize( $instagram ) );
				set_transient( $transient_name, $instagram, apply_filters( 'null_instagram_cache_time', HOUR_IN_SECONDS * 2 ) );
			}
		}
		if ( ! empty( $instagram ) ) {
			$instagram = maybe_unserialize( epicomedia_decompress( $instagram ) );
			return array_slice( $instagram, 0, $slice );
		} else {
			return new WP_Error( 'no_images', esc_html__( 'Instagram did not return any images.', 'epicomedia' ) );
		}
	}
}

/*-----------------------------------------------------------------*/
// 					 Social Share buttons
/*-----------------------------------------------------------------*/

if ( ! function_exists( 'ep_social_share' ) ) {
	function ep_social_share() {
		$socialshare = epico_opt("social_share_display"); //theme settings;
		 if ($socialshare !=0){
	?>
			<div class="socialShareContainer">
                <div class="label hidden-phone hidden-v-tablet"> <?php esc_html_e('Share : ','vitrine') ; ?> </div>
				<?php get_template_part('templates/social-share'); ?>
			</div>
	<?php 
		}
	}
}

if ( ! function_exists( 'ep_social_share_action' ) ) {
	function ep_social_share_action() {

		// call social Share in product detail - Quick View  
		add_action( 'quick_view_product_summary', 'ep_social_share', 32 );
		// social Share in product detail 
		add_action( 'woocommerce_single_product_summary', 'ep_social_share', 36 );  // 35 belong compare actions		
	}
}
ep_social_share_action();

/*-----------------------------------------------------------------*/
// 					 Instagram Hashtag
/*-----------------------------------------------------------------*/

if( ! function_exists( 'ep_instagram_hashtag' ) ) {
	function ep_instagram_hashtag() {
		$hashtag = get_post_meta(get_the_ID(),  'product_hashtag', true );
		if( empty( $hashtag ) )
			return;
		?>
		<div class="epico_instagram_product">
			
				<div class="product-instagram">
					<h3> <?php esc_html_e( $hashtag ); ?></h3>
					<?php esc_html_e('Tag your photos with this on Instagram.', 'vitrine') ; ?>
				</div>
				
				<?php echo epico_sc_instgram_feed( array(
						'user' => esc_html( $hashtag ),
						'column' => '6',
						'image_resolution' => 'medium',
						'carousel' => 'enable',
						'enterance_animation'=> 'disable',
						'like' => 'enable',
						'gutter' => 'no',
						'comment' => 'enable',

					) ); ?>
			
		</div>
		<?php
	}
}
if ( ! function_exists( 'ep_instagram_hashtag_action' ) ) {
	function ep_instagram_hashtag_action() {
		add_action( 'woocommerce_after_single_product_summary', 'ep_instagram_hashtag',  300 );
	}
}
ep_instagram_hashtag_action();

