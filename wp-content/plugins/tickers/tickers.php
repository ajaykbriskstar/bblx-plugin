<?php

/**
 * that starts the plugin.
 *
 * @since             1.0.0
 * @package           Tickers
 *
 * @wordpress-plugin
 * Plugin Name:       BondBloxx Products
 * Description:       
 * Version:           1.0.0
 * Author:            SigmaLogic
 * Author URI:        SigmaLogic
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tickers
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'TICKERS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-tickers-activator.php
 */
function activate_tickers() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-tickers-activator.php';
	Tickers_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-tickers-deactivator.php
 */
function deactivate_tickers() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-tickers-deactivator.php';
	Tickers_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_tickers' );
register_deactivation_hook( __FILE__, 'deactivate_tickers' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-tickers.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_tickers() {

	$plugin = new Tickers();
	$plugin->run();

}
run_tickers();
