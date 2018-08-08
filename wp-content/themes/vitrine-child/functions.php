<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// Enqueue child theme style 
if ( !function_exists( 'vitrine_child_theme_assets' ) ) {
	
    function vitrine_child_theme_assets() {
		if ( is_rtl() ) {
			wp_enqueue_style( 'epico_vitrine_child_theme_style', trailingslashit( get_stylesheet_directory_uri() ) . 'style.css', array( 'epico_icomoon-style','epico_theme-style','epico_woocommerce-style','epico_responsive-style', 'rtl-style') );  
		} else { 
			wp_enqueue_style( 'epico_vitrine_child_theme_style', trailingslashit( get_stylesheet_directory_uri() ) . 'style.css', array( 'epico_icomoon-style','epico_theme-style','epico_woocommerce-style','epico_responsive-style' ) );  
		}
	}
	
}
add_action( 'wp_enqueue_scripts', 'vitrine_child_theme_assets' );


/*----------------------------------------
    write your codes in the following
-----------------------------------------*/



