<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.foxmetrics.com/
 * @since      1.0.0
 *
 * @package    Foxmetrics_Analytics
 * @subpackage Foxmetrics_Analytics/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Foxmetrics_Analytics
 * @subpackage Foxmetrics_Analytics/admin
 * @author     FoxMetrics <rydal@foxmetrics.com>
 */
class Foxmetrics_Analytics_Admin {

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
	 * The options value of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $foxmetrics_analytics_options    The options value of this plugin.
	 */
	private $foxmetrics_analytics_options;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/foxmetrics-analytics-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/foxmetrics-analytics-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the Pages and Menu for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function register_settings_page_menu() {

		add_menu_page(
			__( 'FoxMetrics', 'foxmetrics' ), // page_title
			__( 'FoxMetrics', 'foxmetrics' ), // menu_title
			'manage_options', // capability
			'foxmetrics-analytics', // menu_slug
			array( $this, 'foxmetrics_analytics_create_admin_page' ), // function
		);
	}

	/**
	 * Register the Pages and Menu for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function foxmetrics_analytics_create_admin_page() {
		$this->foxmetrics_analytics_options = get_option( 'foxmetrics_analytics_settings' ); ?>
		<div class="wrap">
			<h2><?php echo __( 'FoxMetrics Analytics', 'foxmetrics' ); ?></h2>
			<?php settings_errors(); ?>
			<form method="post" action="options.php">
				<?php
				settings_fields( 'foxmetrics_analytics_page' );
				do_settings_sections( 'foxmetrics_analytics_page' );
				submit_button();
				?>
			</form>
		</div>
	<?php }

	/**
	 * Register the Pages and Menu for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function register_settings_page_fields() {

		register_setting( 'foxmetrics_analytics_page', 'foxmetrics_analytics_settings' );

		add_settings_section(
			'foxmetrics_analytics_general_section', 
			__( 'General', 'foxmetrics' ), 
			'',
			'foxmetrics_analytics_page'
		);
		add_settings_field( 
			'app_id', 
			__( 'Application ID', 'foxmetrics' ), 
			array( $this, 'foxmetrics_analytics_app_id_render' ), 
			'foxmetrics_analytics_page', 
			'foxmetrics_analytics_general_section' 
		);
		add_settings_field( 
			'collector_id', 
			__( 'Collector ID', 'foxmetrics' ), 
			array( $this, 'foxmetrics_analytics_collector_id_render' ), 
			'foxmetrics_analytics_page', 
			'foxmetrics_analytics_general_section' 
		);
		add_settings_field( 
			'privacy_mode', 
			__( 'Privacy Mode', 'foxmetrics' ), 
			array( $this, 'foxmetrics_analytics_privacy_mode_render' ), 
			'foxmetrics_analytics_page', 
			'foxmetrics_analytics_general_section' 
		);
		add_settings_field( 
			'cross_domains', 
			__( 'Cross Domains', 'foxmetrics' ), 
			array( $this, 'foxmetrics_analytics_cross_domains_render' ), 
			'foxmetrics_analytics_page', 
			'foxmetrics_analytics_general_section' 
		);


		add_settings_section(
			'foxmetrics_analytics_auto_track_section', 
			__( 'Auto Track', 'foxmetrics' ), 
			'',
			'foxmetrics_analytics_page'
		);
		add_settings_field( 
			'auto_track', 
			__( 'Auto Track', 'foxmetrics' ), 
			array( $this, 'foxmetrics_analytics_auto_track_render' ), 
			'foxmetrics_analytics_page', 
			'foxmetrics_analytics_auto_track_section' 
		);
		add_settings_field( 
			'auto_track_scrolls', 
			__( 'Auto Track Scrolls', 'foxmetrics' ), 
			array( $this, 'foxmetrics_analytics_auto_track_scrolls_render' ), 
			'foxmetrics_analytics_page', 
			'foxmetrics_analytics_auto_track_section' 
		);
		add_settings_field( 
			'auto_track_outbound', 
			__( 'Auto Track Outbound', 'foxmetrics' ), 
			array( $this, 'foxmetrics_analytics_auto_track_outbound_render' ), 
			'foxmetrics_analytics_page', 
			'foxmetrics_analytics_auto_track_section' 
		);
		add_settings_field( 
			'auto_track_sitesearch', 
			__( 'Auto Track Sitesearch', 'foxmetrics' ), 
			array( $this, 'foxmetrics_analytics_auto_track_sitesearch_render' ), 
			'foxmetrics_analytics_page', 
			'foxmetrics_analytics_auto_track_section' 
		);
		add_settings_field( 
			'auto_track_downloads', 
			__( 'Auto Track Downloads', 'foxmetrics' ), 
			array( $this, 'foxmetrics_analytics_auto_track_downloads_render' ), 
			'foxmetrics_analytics_page', 
			'foxmetrics_analytics_auto_track_section' 
		);
		add_settings_field( 
			'auto_track_errors', 
			__( 'Auto Track Errors', 'foxmetrics' ), 
			array( $this, 'foxmetrics_analytics_auto_track_errors_render' ), 
			'foxmetrics_analytics_page', 
			'foxmetrics_analytics_auto_track_section' 
		);


		add_settings_section(
			'foxmetrics_analytics_troubleshooting_section', 
			__( 'Troubleshooting', 'foxmetrics' ), 
			'',
			'foxmetrics_analytics_page'
		);
		add_settings_field( 
			'debug_mode', 
			__( 'Debug Mode', 'foxmetrics' ), 
			array( $this, 'foxmetrics_analytics_debug_mode_render' ), 
			'foxmetrics_analytics_page', 
			'foxmetrics_analytics_troubleshooting_section' 
		);
		add_settings_field( 
			'log_verbose', 
			__( 'Verbose Logging', 'foxmetrics' ), 
			array( $this, 'foxmetrics_analytics_log_verbose_render' ), 
			'foxmetrics_analytics_page', 
			'foxmetrics_analytics_troubleshooting_section' 
		);
	}

	/**
	 * View of Settings Field
	 *
	 * @since    1.0.0
	 */
	public function foxmetrics_analytics_app_id_render() { 
		$options = $this->foxmetrics_analytics_options; ?>
		<input type='text' class="regular-text" name='foxmetrics_analytics_settings[app_id]' value='<?php echo !empty($options['app_id']) ? esc_html($options['app_id']) : '' ; ?>'>
		<?php
	}
	/**
	 * View of Settings Field
	 *
	 * @since    1.0.0
	 */
	public function foxmetrics_analytics_collector_id_render() { 
		$options = $this->foxmetrics_analytics_options; ?>
		<input type='text' class="regular-text" name='foxmetrics_analytics_settings[collector_id]' value='<?php echo !empty($options['collector_id']) ? esc_html($options['collector_id']) : 'd35tca7vmefkrc' ; ?>'>
		<?php
	}
	/**
	 * View of Settings Field
	 *
	 * @since    1.0.0
	 */
	public function foxmetrics_analytics_privacy_mode_render() { 
		$options = $this->foxmetrics_analytics_options;
		$privacy_mode = !empty($options['privacy_mode']) ? esc_html($options['privacy_mode']) : 'unrestricted' ; ?>
		<select name='foxmetrics_analytics_settings[privacy_mode]' class="regular-text">
			<option value="unrestricted" <?php echo ($privacy_mode=='unrestricted') ? 'selected="selected"' : ''; ?> ><?php echo __( 'Unrestricted', 'foxmetrics' ); ?></option>
			<option value="strict" <?php echo ($privacy_mode=='strict') ? 'selected="selected"' : ''; ?> ><?php echo __( 'Strict', 'foxmetrics' ); ?></option>
		</select>
		<?php
	}
	/**
	 * View of Settings Field
	 *
	 * @since    1.0.0
	 */
	public function foxmetrics_analytics_cross_domains_render() { 
		$options = $this->foxmetrics_analytics_options; ?>
		<input type='text' class="regular-text" name='foxmetrics_analytics_settings[cross_domains]' value='<?php echo !empty($options['cross_domains']) ? $options['cross_domains'] : '' ; ?>'>
		<?php
	}

	/**
	 * View of Settings Field
	 *
	 * @since    1.0.0
	 */
	public function foxmetrics_analytics_auto_track_render() { 
		$options = $this->foxmetrics_analytics_options;

			if(!isset($options['auto_track'])){
				$checked = 'checked';
			}
			$checked = (isset($options['auto_track']) && !empty($options['auto_track'])) ? 'checked' : '' ; 
			if(!$options){
				$checked = 'checked'; 
			}
		?>
		<input type='checkbox' class="regular-text" name='foxmetrics_analytics_settings[auto_track]' value='1' <?php echo esc_html($checked); ?>>
		<?php
	}
	/**
	 * View of Settings Field
	 *
	 * @since    1.0.0
	 */
	public function foxmetrics_analytics_auto_track_scrolls_render() { 
		$options = $this->foxmetrics_analytics_options;
		$checked = !empty($options['auto_track_scrolls']) ? 'checked' : '' ; ?>
		<input type='checkbox' class="regular-text" name='foxmetrics_analytics_settings[auto_track_scrolls]' value='1' <?php echo esc_html($checked); ?>>
		<?php
	}
	/**
	 * View of Settings Field
	 *
	 * @since    1.0.0
	 */
	public function foxmetrics_analytics_auto_track_outbound_render() { 
		$options = $this->foxmetrics_analytics_options;
		$checked = !empty($options['auto_track_outbound']) ? 'checked' : '' ; ?>
		<input type='checkbox' class="regular-text" name='foxmetrics_analytics_settings[auto_track_outbound]' value='1' <?php echo esc_html($checked); ?>>
		<?php
	}
	/**
	 * View of Settings Field
	 *
	 * @since    1.0.0
	 */
	public function foxmetrics_analytics_auto_track_sitesearch_render() { 
		$options = $this->foxmetrics_analytics_options;
		$checked = !empty($options['auto_track_sitesearch']) ? 'checked' : '' ; ?>
		<input type='checkbox' class="regular-text" name='foxmetrics_analytics_settings[auto_track_sitesearch]' value='1' <?php echo esc_html($checked); ?>>
		<?php
	}
	/**
	 * View of Settings Field
	 *
	 * @since    1.0.0
	 */
	public function foxmetrics_analytics_auto_track_downloads_render() { 
		$options = $this->foxmetrics_analytics_options;
		$checked = !empty($options['auto_track_downloads']) ? 'checked' : '' ; ?>
		<input type='checkbox' class="regular-text" name='foxmetrics_analytics_settings[auto_track_downloads]' value='1' <?php echo esc_html($checked); ?>>
		<?php
	}
	/**
	 * View of Settings Field
	 *
	 * @since    1.0.0
	 */
	public function foxmetrics_analytics_auto_track_errors_render() { 
		$options = $this->foxmetrics_analytics_options;
		$checked = !empty($options['auto_track_errors']) ? 'checked' : '' ; ?>
		<input type='checkbox' class="regular-text" name='foxmetrics_analytics_settings[auto_track_errors]' value='1' <?php echo esc_html($checked); ?>>
		<?php
	}

	/**
	 * View of Settings Field
	 *
	 * @since    1.0.0
	 */
	public function foxmetrics_analytics_debug_mode_render() { 
		$options = $this->foxmetrics_analytics_options;
		$checked = !empty($options['debug_mode']) ? 'checked' : '' ; ?>
		<input type='checkbox' class="regular-text" name='foxmetrics_analytics_settings[debug_mode]' value='1' <?php echo esc_html($checked); ?>>
		<?php
	}
	/**
	 * View of Settings Field
	 *
	 * @since    1.0.0
	 */
	public function foxmetrics_analytics_log_verbose_render() { 
		$options = $this->foxmetrics_analytics_options;
		$checked = !empty($options['log_verbose']) ? 'checked' : '' ; ?>
		<input type='checkbox' class="regular-text" name='foxmetrics_analytics_settings[log_verbose]' value='1' <?php echo esc_html($checked); ?>>
		<?php
	}
}
