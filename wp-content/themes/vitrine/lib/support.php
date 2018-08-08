<?php

function  epico_add_image_size_retina($name, $width = 0, $height = 0, $crop = false)
{
    add_image_size($name, $width, $height, $crop);
    add_image_size("$name@2x", $width*2, $height*2, $crop);
}

/*-----------------------------------------------------------------------------------*/
/*  Configure WP2.9+ Thumbnails
/*-----------------------------------------------------------------------------------*/

if ( function_exists( 'add_theme_support' ) ) {
    
    // Adds theme support for woocommerce 
    add_theme_support('woocommerce');

    add_theme_support( 'post-thumbnails' );

    //featured image thumbnail show in  admin columns
    add_image_size( 'epico_admin-list-thumb', 50, 50, true );
    
     epico_add_image_size_retina( 'post-thumbnail', 830, 420, true ); // Default post-thumbnail name for multi-post-thumbnail plugin
     epico_add_image_size_retina( 'epico_post-thumbnail-fullwidth', 830, 420, true );
    
    //Portfolio thumbnails
     epico_add_image_size_retina('epico_thumbnail-square', 340 ,340, true);
     epico_add_image_size_retina('epico_thumbnail-big', 680 , 680, true);
     epico_add_image_size_retina('epico_thumbnail-slim', 340 , 680, true);
     epico_add_image_size_retina('epico_thumbnail-hslim', 680, 340, true);

    //Auto height images used in masonry style 
     epico_add_image_size_retina('epico_thumbnail-auto-height', 400,9999,false);



    //Auto-height product images used in masonry style 
    if(function_exists('wc_get_image_size')) {
        $image_dimension = wc_get_image_size('shop_catalog');
         epico_add_image_size_retina('epico_product_thumbnail-auto-height', $image_dimension['width'],9999,false);
    }

    //Fixed height images used in masonry blog
     epico_add_image_size_retina('epico_blog_thumbnail-fixed-height', 400,400,true);
    
     epico_add_image_size_retina('epico_blog_navigation', 600,195,true);
    //Fixed height images used in blog detail navigation+ 10% zooming amount
    
    //Portfolio single
     epico_add_image_size_retina('epico_portfolio-single', 1140, 655, true);//More suited for wide images

    //Portfolio detail gallery
     epico_add_image_size_retina('epico_portfolio-detail-gallery', 1920, 1080, true);//More suited for wide images
    
    //Standard blog detail
     epico_add_image_size_retina('epico_standard-blog-detail', 1170, 539, true);//More suited for wide images

}

/*-----------------------------------------------------------------------------------*/
/*  RSS Feeds
/*-----------------------------------------------------------------------------------*/

add_theme_support( 'automatic-feed-links' );

/*-----------------------------------------------------------------------------------*/
/*  Post Formats
/*-----------------------------------------------------------------------------------*/

add_theme_support( 'post-formats', array('gallery' , 'video', 'audio' , 'link', 'quote' ) );

/*-----------------------------------------------------------------------------------*/
/*  Custom Header/Background
/*-----------------------------------------------------------------------------------*/

add_theme_support('custom-header');
add_theme_support('custom-background');
add_theme_support("title-tag" );
add_theme_support('automatic-feed-links');