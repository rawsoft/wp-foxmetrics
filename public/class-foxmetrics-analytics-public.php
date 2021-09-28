<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.foxmetrics.com/
 * @since      1.0.0
 *
 * @package    Foxmetrics_Analytics
 * @subpackage Foxmetrics_Analytics/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Foxmetrics_Analytics
 * @subpackage Foxmetrics_Analytics/public
 * @author     FoxMetrics <rydal@foxmetrics.com>
 */
class Foxmetrics_Analytics_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Foxmetrics_Analytics_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Foxmetrics_Analytics_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/foxmetrics-analytics-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Foxmetrics_Analytics_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Foxmetrics_Analytics_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/foxmetrics-analytics-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Show the FoxMetrics Web Analytics Tracking Code on Head
	 *
	 * @since    1.0.0
	 */
	public function web_analytics_tracking() {
		$foxmetrics_analytics_options = get_option( 'foxmetrics_analytics_settings' );
		if ( !empty($foxmetrics_analytics_options["app_id"]) && !empty($foxmetrics_analytics_options["collector_id"]) ) {
			?>
			<!-- FoxMetrics Web Analytics Start -->
			<script type="text/javascript">
				var _fxm = '<?php echo json_encode($foxmetrics_analytics_options); ?>';
				_fxm.events = _fxm.events || [];
				_fxm.app_id = '<?php echo $foxmetrics_analytics_options["app_id"]; ?>';
				<!-- CONFIG VARS -->
				(function () {
					var fxms = document.createElement('script'); fxms.type = 'text/javascript'; fxms.async = true;
					fxms.src = 'https://<?php echo $foxmetrics_analytics_options["collector_id"]; ?>.cloudfront.net/scripts/<?php echo $foxmetrics_analytics_options["app_id"]; ?>.js';
					var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(fxms, s);
				})();
			</script>
			<!-- FoxMetrics Web Analytics End -->
			<?php
		}
	}

}
