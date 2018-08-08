<?php

/**
 *
 * @link              https://themeforest.net/user/epicomedia/portfolio
 * @since             1.0.0
 * @package           Vitrine Shortcodes
 *
 * @wordpress-plugin
 * Plugin Name:       Vitrine Shortcodes
 * Plugin URI:        https://themeforest.net/user/epicomedia/portfolio
 * Description:       Add Shortcodes to vitrine Theme.
 * Version:           2.1
 * Author:            EpicoMedia
 * Author URI:        https://themeforest.net/user/epicomedia/portfolio
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ep-shortcodes
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ep-shortcodes-activator.php
 */
function activate_epico_shortcodes() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ep-shortcodes-activator.php';
	EPICO_Shortcodes_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ep-shortcodes-deactivator.php
 */
function deactivate_epico_shortcodes() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ep-shortcodes-deactivator.php';
	EPICO_Shortcodes_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_epico_shortcodes' );
register_deactivation_hook( __FILE__, 'deactivate_epico_shortcodes' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ep-shortcodes.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_epico_shortcodes() {

	$plugin = new EPICO_Shortcodes();
	$plugin->run();

}
run_epico_shortcodes();
