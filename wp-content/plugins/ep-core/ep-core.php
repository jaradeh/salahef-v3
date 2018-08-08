<?php

/**
 *
 * @link              https://themeforest.net/user/epicomedia/portfolio
 * @since             1.0.0
 * @package           EpicoMedia core
 *
 * @wordpress-plugin
 * Plugin Name:       EpicoMedia core
 * Plugin URI:        https://themeforest.net/user/epicomedia/portfolio
 * Description:       Add custom post types.
 * Version:           2.1
 * Author:            EpicoMedia
 * Author URI:        https://themeforest.net/user/epicomedia/portfolio
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ep-core
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ep-core-activator.php
 */
function activate_epico_core() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ep-core-activator.php';
	EPICO_core_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ep-core-deactivator.php
 */
function deactivate_epico_core() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ep-core-deactivator.php';
	EPICO_core_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_epico_core' );
register_deactivation_hook( __FILE__, 'deactivate_epico_core' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ep-core.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_epico_core() {

	$plugin = new EpicoMedia_core();
	$plugin->run();

}
run_epico_core();
