<?php
/*
Plugin Name: MNKY | Visual Composer Pricing Boxes
Plugin URI: http://themeforest.net/user/MNKY
Description: Simple pricing box add-on for Visual Composer.
Version: 1.0.1
Author: MNKY
Author URI: http://mnkythemes.com/
License: Envato Marketplaces Licence
License URI: Envato Marketplace Item License Certificate 
*/


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) die;


// Define constantss
define( 'VCPB_PLUGIN_MAIN', __FILE__);
define( 'VCPB_PLUGIN_PATH', plugin_dir_path(__FILE__) );
define( 'VCPB_PLUGIN_URL', plugin_dir_url( __FILE__ ) );


// Error notice
function vcpb_extend_error() {
	$plugin_data = get_plugin_data(__FILE__);
	echo '
	<div class="updated">
		<p>'.sprintf(__('<strong>%s</strong> requires <strong><a href="http://bit.ly/vcomposer" target="_blank">Visual Composer</a></strong> plugin to be installed and activated on your site.', 'aperture'), $plugin_data['Name']).'</p>
	</div>';
}


// Execute after all plugins loaded
add_action( 'plugins_loaded', 'vcpb_core_extend' );
function vcpb_core_extend() {

	// Display notice if Visual Composer is not installed or activated
	if ( !function_exists( 'vc_map' ) ) {
		add_action('admin_notices', 'vcpb_extend_error'); 
		return; 
	}
	
	// Enqueue front-end CSS
	add_action('wp_enqueue_scripts', 'vcpb_extend_scripts');
	function vcpb_extend_scripts() {
		wp_register_style( 'vc-pricing-box-front', VCPB_PLUGIN_URL . 'assets/pricing-box-frontend.css', array('js_composer_front') );
		wp_enqueue_style( 'vc-pricing-box-front' );
	}
	
	// Add pricing box shortcode
	require_once ('extend-vc.php');

}