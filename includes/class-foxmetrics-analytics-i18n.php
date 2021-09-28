<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.foxmetrics.com/
 * @since      1.0.0
 *
 * @package    Foxmetrics_Analytics
 * @subpackage Foxmetrics_Analytics/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Foxmetrics_Analytics
 * @subpackage Foxmetrics_Analytics/includes
 * @author     FoxMetrics <rydal@foxmetrics.com>
 */
class Foxmetrics_Analytics_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'foxmetrics',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
