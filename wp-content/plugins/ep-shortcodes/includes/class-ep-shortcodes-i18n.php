<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://themeforest.net/user/epicomedia/portfolio
 * @since      1.0.0
 *
 * @package    EPICO_Shortcodes
 * @subpackage EPICO_Shortcodes/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    EPICO_Shortcodes
 * @subpackage EPICO_Shortcodes/includes
 * @author     EpicoMedia <help.epicomedia@gmail.com>
 */
class EPICO_Shortcodes_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'ep-shortcodes',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
