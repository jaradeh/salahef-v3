<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists('amzAffReviewProductTab') ) {
class amzAffReviewProductTab {

	const VERSION = "1.0";

	private $tab_title;
	private $tab_data = false;
	private $config = array();


	public function __construct() {
		global $WooZone;
		
		$this->tab_title = __('Amazon Customer Reviews', 'woozone');
		
		$this->config = is_object($WooZone) ? $WooZone->settings() : array();
		
		if ( isset($this->config['show_review_tab']) && ($this->config['show_review_tab'] == 'yes') ) {
			add_action( 'init', array( $this, 'init' ));
		}

		// Installation
		if ( is_admin() && !defined('DOING_AJAX') ) {
			$this->install();
		}
	}

	//***************************************
	// FRONTEND SECTION IS IMPLEMENTED IN /lib/frontend/frontend.class.php
	//***************************************



	//***************************************
	// BACKEND
	//***************************************

	public function init() {
		
		if ( is_admin() ) {
			add_action('woocommerce_product_write_panel_tabs', array($this, 'product_write_panel_tab'));

			//add_action('woocommerce_product_write_panels', array($this, 'product_write_panel'));
			add_action('woocommerce_product_data_panels', array($this, 'product_write_panel'));

			add_action('woocommerce_process_product_meta', array($this, 'product_save_data'), 10, 2);
		}
	}

	// Adds a new tab to the Product Data postbox in the admin product interface
	public function product_write_panel_tab() {

		$html = array();

		$html[] = '<li class="amzaff_woo_product_tabs amzaff_woo_product_tab">';
		$html[] = 	'<a href="#product_tabs">'; //color: #555555; line-height: 16px; padding: 9px; text-shadow: 0 1px 1px #FFFFFF;
		$html[] = 		'<span>' . $this->tab_title . '</span>';
		$html[] = 	'</a>';
		$html[] = '</li>';

		$html = implode( PHP_EOL, $html );
		echo $html;
	}

	// Adds the panel to the Product Data postbox in the product interface
	public function product_write_panel() {
		global $post;  // the product

		// pull the custom tab data out of the database
		$tab_data = get_post_meta( $post->ID, 'amzaff_woo_product_tabs', true );
		$tab_data = maybe_unserialize( $tab_data );

		if ( empty($tab_data) || ! is_array($tab_data) ) {
			$tab_data = array( array('content' => '') );
		}

		foreach ($tab_data as $tab) {
			// display the custom tab panel
			echo '<div id="product_tabs" class="panel woocommerce_options_panel">';
			$this->woocommerce_wp_textarea_input( array(
				'id' => '_tab_content',
				'label' => __('Content'),
				'placeholder' => __('HTML and text to display.'),
				'value' => $tab['content'],
				'style' => 'width: 70%; height: 21.5em;'
			));
			echo '</div>';
		}
	}

	private function woocommerce_wp_textarea_input( $field=array() ) {
		global $thepostid, $post;

		if ( ! $thepostid ) {
			$thepostid = $post->ID;
		}
		if ( ! isset($field['placeholder']) ) {
			$field['placeholder'] = '';
		}
		if ( ! isset($field['class']) ) {
			$field['class'] = 'short';
		}
		if ( ! isset($field['value']) ) {
			$tab_data = get_post_meta( $thepostid, 'amzaff_woo_product_tabs', true );
			$tab_data = maybe_unserialize( $tab_data );

			$field['value'] = get_post_meta($thepostid, $field['id'], true);
		}

		echo '<p class="form-field '.$field['id'].'_field">';
		echo '<label for="'.$field['id'].'">'.$field['label'].'</label>';
		echo '<textarea class="'.$field['class'].'" name="'.$field['id'].'" id="'.$field['id'].'" placeholder="'.$field['placeholder'].'" rows="2" cols="20"' . (isset($field['style']) ? ' style="'.$field['style'].'"' : '' ) .'">' . esc_textarea( $field['value'] ) . '</textarea> ';
		if ( isset($field['description']) && $field['description'] ) {
			echo '<span class="description">' .$field['description'] . '</span>';
		}
		echo '</p>';
	}

	// Save the data inputed into the product boxes, as post meta data identified by the name 'amzaff_woo_product_tabs'
	public function product_save_data( $post_id, $post ) {

		$tab_content = stripslashes( $_POST['_tab_content'] );

		if ( empty($tab_content) && get_post_meta($post_id, 'amzaff_woo_product_tabs', true) ) {
			// clean up if the custom tabs are removed
			delete_post_meta($post_id, 'amzaff_woo_product_tabs');
		}
		if ( ! empty($tab_content) ) {
			$tab_data = array();

			// save the data to the database
			$tab_data[] = array( 'id' => 'amzAff-customer-review', 'content' => $tab_content );
			update_post_meta($post_id, 'amzaff_woo_product_tabs', $tab_data);
		}
	}



	//***************************************
	// ACTIVATION & INSTALL
	//***************************************

	// Run every time since the activation hook is not executed when updating a plugin
	private function install() {
		if ( get_option('woocommerce_custom_product_tabs_lite_db_version') != self::VERSION ) {
			$this->upgrade();

			// new version number
			update_option('woocommerce_custom_product_tabs_lite_db_version', self::VERSION);
		}
	}

	// Run when plugin version number changes
	private function upgrade() {
		global $wpdb;

		if ( !get_option('woocommerce_custom_product_tabs_lite_db_version') ) {
			// this is one of the couple of original users who installed before I had a version option in the db
			// rename the post meta option 'product_tabs' to 'amzaff_woo_product_tabs'
			$wpdb->query("UPDATE {$wpdb->postmeta} SET meta_key='amzaff_woo_product_tabs' WHERE meta_key='product_tabs';");
		}
	}

	// Runs various functions when the plugin first activates (and every time
	// its activated after first being deactivated), and verifies that
	// the WooCommerce plugin is installed and active @see register_activation_hook()
	public static function on_activation() {
		// checks if the woocommerce plugin is running and disables this plugin if it's not (and displays a message)
		if ( !is_plugin_active('woocommerce/woocommerce.php') || !is_plugin_active('envato-wordpress-toolkit/woocommerce.php') ) {
			deactivate_plugins(plugin_basename(__FILE__));
			wp_die( __('In order for The WooCommerce Product Tabs to work, you need to install and activate <a href="http://www.woothemes.com/woocommerce/" target="_blank">WooCommerce</a> first. <a href="' . admin_url('plugins.php') . '"> <br> &laquo; Go Back</a>') );
		}

		// set version number
		update_option('woocommerce_custom_product_tabs_lite_db_version', self::VERSION);
	}
}

$woocommerce_product_tabs_lite = new amzAffReviewProductTab();
} // class exists check

/**
 * run the plugin activation hook
 */
register_activation_hook( __FILE__, array('amzAffReviewProductTab', 'on_activation') );