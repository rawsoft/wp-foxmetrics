<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.foxmetrics.com/
 * @since             1.0.0
 * @package           Foxmetrics_Analytics
 *
 * @wordpress-plugin
 * Plugin Name:       FoxMetrics
 * Plugin URI:        https://www.foxmetrics.com/
 * Description:       FoxMetrics helps you overcome the challenges with siloed systems and products. It captures, stores, and unlocks data generated from the web, mobile, and other sources and drive value from customer behavioral data.
 * Version:           1.0.0
 * Author:            FoxMetrics
 * Author URI:        https://www.foxmetrics.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       foxmetrics
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
define( 'FOXMETRICS_ANALYTICS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-foxmetrics-analytics-activator.php
 */
function activate_foxmetrics_analytics() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-foxmetrics-analytics-activator.php';
	Foxmetrics_Analytics_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-foxmetrics-analytics-deactivator.php
 */
function deactivate_foxmetrics_analytics() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-foxmetrics-analytics-deactivator.php';
	Foxmetrics_Analytics_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_foxmetrics_analytics' );
register_deactivation_hook( __FILE__, 'deactivate_foxmetrics_analytics' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-foxmetrics-analytics.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_foxmetrics_analytics() {

	$plugin = new Foxmetrics_Analytics();
	$plugin->run();

}
run_foxmetrics_analytics();
