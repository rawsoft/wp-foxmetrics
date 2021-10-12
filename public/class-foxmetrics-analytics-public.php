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
			$fxm_src = 'https://'.$foxmetrics_analytics_options["collector_id"].'.cloudfront.net/scripts/'.$foxmetrics_analytics_options["app_id"].'.js'
			?>
			<!-- FoxMetrics Web Analytics Start -->
			<script type="text/javascript">
				var _fxm = _fxm || {};
				_fxm.events = _fxm.events || [];
				_fxm.app_id = '<?php echo esc_html($foxmetrics_analytics_options["app_id"]); ?>';
				_fxm.collector_id = '<?php echo esc_html($foxmetrics_analytics_options["collector_id"]); ?>';
				_fxm.privacy_mode = '<?php echo esc_html($foxmetrics_analytics_options["privacy_mode"]); ?>';
				_fxm.auto_track = <?php echo (isset($foxmetrics_analytics_options["auto_track"]) && $foxmetrics_analytics_options["auto_track"] == "1")? "true": "false"; ?>;
				_fxm.auto_track_scrolls = <?php echo (isset($foxmetrics_analytics_options["auto_track_scrolls"]) && $foxmetrics_analytics_options["auto_track_scrolls"] == "1")? "true": "false"; ?>;
				_fxm.auto_track_outbound = <?php echo (isset($foxmetrics_analytics_options["auto_track_outbound"]) && $foxmetrics_analytics_options["auto_track_outbound"] == "1")? "true": "false"; ?>;
				_fxm.auto_track_sitesearch = <?php echo (isset($foxmetrics_analytics_options["auto_track_sitesearch"]) && $foxmetrics_analytics_options["auto_track_sitesearch"] == "1")? "true": "false"; ?>;
				_fxm.auto_track_downloads = <?php echo (isset($foxmetrics_analytics_options["auto_track_downloads"]) && $foxmetrics_analytics_options["auto_track_downloads"] == "1")? "true": "false"; ?>;
				_fxm.auto_track_errors = <?php echo (isset($foxmetrics_analytics_options["auto_track_errors"]) && $foxmetrics_analytics_options["auto_track_errors"] == "1")? "true": "false"; ?>;
				_fxm.debug_mode = <?php echo (isset($foxmetrics_analytics_options["debug_mode"]) && $foxmetrics_analytics_options["debug_mode"] == "1")? "true": "false"; ?>;
				_fxm.log_verbose = <?php echo (isset($foxmetrics_analytics_options["log_verbose"]) && $foxmetrics_analytics_options["log_verbose"] == "1")? "true": "false"; ?>;

				_fxm.cross_domains = <?php 
					if(isset($foxmetrics_analytics_options["cross_domains"]) && !empty($foxmetrics_analytics_options["cross_domains"])){
						$cross_domains_array = explode(",", $foxmetrics_analytics_options["cross_domains"]);
						$cross_domains_array = array_map('trim', $cross_domains_array);
						echo json_encode($cross_domains_array); 
					}else{
						echo json_encode(array()); 
					}
				?>;
				<?php 
				if ( class_exists( 'woocommerce' ) ) {
					if ( is_product() ) {
						do_action( 'wc_analytics_tracking_productview' );
					}
					if(is_wc_endpoint_url( 'order-received' )) {
						do_action( 'wc_analytics_tracking_order_received' );
					}
				}
				?>				
				(function () {
					var fxms = document.createElement('script'); fxms.type = 'text/javascript'; fxms.async = true;
					fxms.src = '<?php echo esc_url($fxm_src); ?>';
					var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(fxms, s);
				})();
			</script>
			<!-- FoxMetrics Web Analytics End -->
			<?php
		}
	}

}
