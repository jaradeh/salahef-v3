<?php

function epico_vc_add_custom_fields() {
    if (!function_exists('vc_add_shortcode_param')){
        return false;
    }

    // add icon box option for vc
    vc_add_shortcode_param('vc_icons', 'epico_vc_icons_field', EPICO_THEME_URI  . '/extendvc/js/vc_icon.js' );

    // add date option for vc
    vc_add_shortcode_param('vc_date', 'epico_vc_date_field' );

    // add image select option for vc
    vc_add_shortcode_param('vc_imageselect', 'epico_vc_imageselect_field', EPICO_THEME_URI  . '/extendvc/js/vc_imageselect.js' );
        
    // add range field for vc
    vc_add_shortcode_param('vc_rangefield', 'epico_vc_range_field', EPICO_THEME_URI  . '/extendvc/js/vc_rangefield.js' );

    // add checkbox field for vc
    vc_add_shortcode_param('vc_multiselect', 'epico_vc_multi_select', EPICO_THEME_URI  . '/extendvc/js/vc_multiselect.js' );

}

add_action( 'admin_init', 'epico_vc_add_custom_fields');

//Separators
add_shortcode('vc_separator', 'epico_sc_separator');

//Title with horizontal line
add_shortcode('vc_text_separator', 'epico_sc_title');

//Team Member
add_shortcode('team_member', 'epico_sc_team_member');

//Testimonials shortcode 
add_shortcode('testimonial', 'epico_sc_testimonial');

//Testimonial item shortcode 
add_shortcode('testimonial_item', 'epico_sc_testimonial_item');

//Pie Chart
add_shortcode( 'piechart', 'epico_sc_piechart' );

//Horizontal progress bar
add_shortcode( 'progressbar', 'epico_sc_progressbar' );

//Social Icon 
add_shortcode( 'socialIcon', 'epico_sc_socialIocn' );

//Social Link 
add_shortcode( 'socialLink', 'epico_sc_socialLink' );

//Text-Box
add_shortcode( 'textbox', 'epico_sc_textbox' );

//Custom textbox
add_shortcode( 'custom_textbox', 'epico_sc_custom_textbox' );

//Custom Title 
add_shortcode( 'custom_title', 'epico_sc_customTitle' );

//Image-Box
add_shortcode( 'imagebox', 'epico_sc_imagebox' );

//Animated Text
add_shortcode( 'animatedtext', 'epico_sc_animated_text' );

//Banner
add_shortcode( 'banner', 'epico_sc_banner' );

//Custom image-box - Creative image box
add_shortcode( 'custom_imagebox', 'epico_sc_custom_imagebox' );

//Icon-Box-custom ( creative iconbox ) 
add_shortcode( 'iconbox_custom', 'epico_sc_iconbox_custom' );

//Icon-Box-top No border 
add_shortcode( 'iconbox_top_noborder', 'epico_sc_iconbox_noborder' );

//Icon-Box-Rectangle
add_shortcode( 'iconbox_rectangle', 'epico_sc_iconbox_rectangle' );

//Icon-Box-Circle
add_shortcode( 'iconbox_circle', 'epico_sc_iconbox_circle' );

//Icon-Box-left
add_shortcode( 'iconbox_left', 'epico_sc_iconbox_left' );

//Countdown
add_shortcode( 'countdown', 'epico_sc_countdown' );

//Counter Box
add_shortcode( 'conterbox', 'epico_sc_conterbox' );

//Embed Video
add_shortcode( 'embed_video', 'epico_sc_embed_video' );

// Audio SoundCloud
add_shortcode( 'audio_soundcloud', 'epico_sc_audio_soundcloud' );

//Tabs
add_shortcode( 'tab_group', 'epico_sc_tab_group' );
add_shortcode( 'tab', 'epico_sc_tab' );

//Button
add_shortcode('button', 'epico_sc_button');

// VC Toggle Counter Box
add_shortcode( 'vc_toggle', 'epico_sc_vctoggle' );

//VC carousel
add_shortcode( 'image_carousel', 'epico_sc_imagecarousel' );

//Showcase
add_shortcode( 'showcase', 'epico_sc_showcase' );

// Showcase Items 
add_shortcode( 'showcase_item', 'epico_sc_showcase_item' );

// Portfolio
add_shortcode( 'portfolio', 'epico_sc_portfolio' );

//  Portfolio inner
add_shortcode( 'portfolio_inner', 'epico_sc_portfolio_inner' );

// Gallery
add_shortcode( 'ep_gallery', 'epico_sc_gallery' );

// Carousel Gallery
add_shortcode( 'gallery_carousel', 'epico_sc_gallery_carousel' );

// Newsletter(subscribtion form)
add_shortcode( 'ep_newsletter', 'epico_sc_newsletter' );

// Woocommerce shortcodes
add_shortcode( 'ep_instagram', 'epico_sc_instgram_feed' );

//  Masonry Blog - Cart blog 
add_shortcode( 'ep_masonry_blog', 'epico_sc_blog_masonry' );


function epico_sc_woocommerce_shortcodes_changes()
{
    //Remove WC shortcodes
    remove_shortcode('product');
    remove_shortcode('products');
    remove_shortcode('recent_products');
    remove_shortcode('sale_products');
    remove_shortcode('best_selling_products');
    remove_shortcode('top_rated_products');
    remove_shortcode('featured_products');
    remove_shortcode('product_attribute');
    remove_shortcode('product_categories');
    remove_shortcode('product_category');
    remove_shortcode('product_page');

    //Add WC shortcodes and define handler for them
    add_shortcode( 'product', 'epico_sc_product' );
    add_shortcode( 'product_2', 'epico_sc_product_2' );
    add_shortcode( 'products', 'epico_products' );
    add_shortcode( 'recent_products', 'epico_recent_products' );
    add_shortcode( 'sale_products', 'epico_sale_products' );
    add_shortcode( 'best_selling_products', 'epico_best_selling_products' );
    add_shortcode( 'top_rated_products', 'epico_top_rated_products' );
    add_shortcode( 'featured_products', 'epico_featured_products' );
    add_shortcode( 'product_attribute', 'epico_product_attribute' );
    add_shortcode( 'product_categories', 'epico_product_categories' );
    add_shortcode( 'product_category', 'epico_product_category' );
    add_shortcode( 'product_page', 'epico_product_page' );
}

add_action( 'init', 'epico_sc_woocommerce_shortcodes_changes');
 
// Remove below Scripts becuase cause bug and We replace Our functionality
function epico_remove_VC_scripts( $handles = array() ) {

    wp_deregister_style( 'vc_tta_style' );
    wp_dequeue_style('vc_tta_style');

    wp_deregister_script('vc_accordion_script');
    wp_dequeue_script('vc_accordion_script');

    wp_deregister_script('vc_tta_autoplay_script');
    wp_dequeue_script('vc_tta_autoplay_script');

    wp_deregister_script('vc_tabs_script');
    wp_dequeue_script('vc_tabs_script');

    wp_deregister_script('waypoints');
    wp_dequeue_script('waypoints');
}

add_action('wp_footer', 'epico_remove_VC_scripts');


?>