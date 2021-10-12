<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.foxmetrics.com/
 * @since      1.0.0
 *
 * @package    Foxmetrics_Analytics
 * @subpackage Foxmetrics_Analytics/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Foxmetrics_Analytics
 * @subpackage Foxmetrics_Analytics/includes
 * @author     FoxMetrics <rydal@foxmetrics.com>
 */
class Foxmetrics_Analytics {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Foxmetrics_Analytics_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'FOXMETRICS_ANALYTICS_VERSION' ) ) {
			$this->version = FOXMETRICS_ANALYTICS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'foxmetrics-analytics';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_woocommerce_support_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Foxmetrics_Analytics_Loader. Orchestrates the hooks of the plugin.
	 * - Foxmetrics_Analytics_i18n. Defines internationalization functionality.
	 * - Foxmetrics_Analytics_Admin. Defines all hooks for the admin area.
	 * - Foxmetrics_Analytics_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-foxmetrics-analytics-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-foxmetrics-analytics-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-foxmetrics-analytics-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-foxmetrics-analytics-public.php';

		/**
		 * WooCommerce is activated.
		 * The class responsible for defining all actions that support for WooCommerce.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'woocommerce/class-foxmetrics-analytics-woocommerce-support.php';

		$this->loader = new Foxmetrics_Analytics_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Foxmetrics_Analytics_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Foxmetrics_Analytics_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Foxmetrics_Analytics_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'register_settings_page_menu' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings_page_fields' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Foxmetrics_Analytics_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_head', $plugin_public, 'web_analytics_tracking' );

	}

	/**
	 * Register all of the hooks related to the woocommerce support functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_woocommerce_support_hooks() {

		$plugin_woocommerce_support = new Foxmetrics_Analytics_WooCommerce_Support( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wc_analytics_tracking_productview', $plugin_woocommerce_support, 'wc_analytics_tracking_productview' );
		$this->loader->add_action( 'wc_analytics_tracking_order_received', $plugin_woocommerce_support, 'wc_analytics_tracking_order_received' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_woocommerce_support, 'enqueue_scripts' );
		/* AJAX Callback */
		$this->loader->add_action( 'wp_ajax_foxmetrics_tracking_cart_remove_item', $plugin_woocommerce_support, 'foxmetrics_tracking_cart_remove_item_callback' );
		$this->loader->add_action( 'wp_ajax_nopriv_foxmetrics_tracking_cart_remove_item', $plugin_woocommerce_support, 'foxmetrics_tracking_cart_remove_item_callback' );
		$this->loader->add_action( 'wp_ajax_foxmetrics_tracking_cart_add_item', $plugin_woocommerce_support, 'foxmetrics_tracking_cart_add_item_callback' );
		$this->loader->add_action( 'wp_ajax_nopriv_foxmetrics_tracking_cart_add_item', $plugin_woocommerce_support, 'foxmetrics_tracking_cart_add_item_callback' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Foxmetrics_Analytics_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}