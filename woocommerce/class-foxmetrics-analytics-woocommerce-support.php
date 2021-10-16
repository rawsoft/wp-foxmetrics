<?php

/**
 * The woocommerce support functionality of the plugin.
 *
 * @link       https://www.foxmetrics.com/
 * @since      1.0.0
 *
 * @package    Foxmetrics_Analytics
 * @subpackage Foxmetrics_Analytics/woocommerce
 */

/**
 * The woocommerce support functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Foxmetrics_Analytics
 * @subpackage Foxmetrics_Analytics/woocommerce
 * @author     FoxMetrics <rydal@foxmetrics.com>
 */
class Foxmetrics_Analytics_WooCommerce_Support {

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
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name.'-woocommerce-support', plugin_dir_url( __FILE__ ) . 'js/foxmetrics-analytics-woocommerce-support.js', array( 'jquery' ), $this->version, false );

		$woocommerce_support_args = array( 
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'is_product' => ( is_product() ? 1 : 0 ),
			'is_shop' => ( is_shop() ? 1 : 0 ),
			'is_order_received' => ( is_wc_endpoint_url( 'order-received' ) ? 1 : 0 ),
			'is_cart' => ( is_cart() ? 1 : 0 ),
		);
		if ( is_product() ) {

			global $post;
			if ( !empty($post->post_type) && ($post->post_type=='product')  ) {

				$product_name_and_cat_name = $this->wc_get_product_name_and_cat_name( $post->ID );
				$woocommerce_support_args['product_id'] = (!empty($product_name_and_cat_name['product_id']) ? $product_name_and_cat_name['product_id'] : '');
				$woocommerce_support_args['product_name'] = (!empty($product_name_and_cat_name['product_name']) ? $product_name_and_cat_name['product_name'] : '');
				$woocommerce_support_args['product_category_name'] = (!empty($product_name_and_cat_name['product_category_name']) ? $product_name_and_cat_name['product_category_name'] : '');
				$woocommerce_support_args['product_price'] = (!empty($product_name_and_cat_name['product_price']) ? $product_name_and_cat_name['product_price'] : '');
			}
		}
		wp_localize_script( $this->plugin_name.'-woocommerce-support', 'FA_WC_Support_Script', $woocommerce_support_args );
	}

	/**
	 * Get product name and cat name from product id
	 *
	 * @since    1.0.1
	 */
	public function wc_get_product_name_and_cat_name( $product_id ) {
		$response = array();
		if ( class_exists( 'woocommerce' ) ) {
			
			if ( !empty($product_id) ) {
				$post = get_post( $product_id );

				if ( !empty($post->post_type) && ($post->post_type=='product')  ) {

					// Get $product object from product ID
					$product = wc_get_product( $post->ID );

					if ( !empty($product) && is_object($product) ) {

						$product_id = $product->get_id();
						$product_name = $product->get_name();
						$product_category_ids = $product->get_category_ids();
						$product_category_name = '';
						if ( !empty($product_category_ids) && !empty($product_category_ids[0]) ) {
							$product_category = get_term( $product_category_ids[0] );
							if ( !empty($product_category) ) {
								$product_category_name = $product_category->name;
							}
						}
						$response['product_id'] = $product_id;
						$response['product_name'] = $product_name;
						$response['product_category_name'] = $product_category_name;
						$response['product_price'] = $product->get_price();
					}
				}
			}
		}
		return $response;
	}

	/**
	 * WooCommerce tracking on product view
	 *
	 * @since    1.0.1
	 */
	public function wc_analytics_tracking_productview() {
		if ( class_exists( 'woocommerce' ) ) {
			if ( is_product() ) {

				global $post;
				if ( !empty($post->post_type) && ($post->post_type=='product')  ) {

					// Get $product object from product ID
					$product = wc_get_product( $post->ID );

					if ( !empty($product) && is_object($product) ) {

						$product_id = $product->get_id();
						$product_name_and_cat_name = $this->wc_get_product_name_and_cat_name( $product_id );
						$product_name = (!empty($product_name_and_cat_name['product_name']) ? $product_name_and_cat_name['product_name'] : '');
						$product_category_name = (!empty($product_name_and_cat_name['product_category_name']) ? $product_name_and_cat_name['product_category_name'] : '');
						// Display the script
						echo "\n"."_fxm.events.push(['_fxm.ecommerce.productview','".$product_id."', '".$product_name."', '".$product_category_name."']);";
					}
				}

			}
		}
	}

	/**
	 * WooCommerce tracking on order thank you page
	 *
	 * @since    1.0.1
	 */
	public function wc_analytics_tracking_order_received() {
		if ( class_exists( 'woocommerce' ) ) {

			if ( !empty($_GET['key']) ) {

				$order_id = wc_get_order_id_by_order_key( $_GET['key'] );
				$order = wc_get_order( $order_id );
				$unique_items_counts = 0;
				$subtotal_with_tax_calc = 0;
				$oln = 1;
				$tax_display = get_option( 'woocommerce_tax_display_cart' );
				if ( !empty($order) ) {

					// Get and Loop Over Order Items
					foreach ( $order->get_items() as $item_id => $item ) {
						if ( !empty($item) ) {

							$unique_items_counts++;
							$product_id = $item->get_product_id();
							$variation_id = $item->get_variation_id();
							$product = $item->get_product();
							$product_name = $item->get_name();
							$quantity = $item->get_quantity();
							$subtotal = $item->get_subtotal();
							$total = $item->get_total();
							$tax = $item->get_subtotal_tax();
							$product_type = $item->get_type();
							$product_name_and_cat_name = $this->wc_get_product_name_and_cat_name( $product_id );
							$product_category_name = (!empty($product_name_and_cat_name['product_category_name']) ? $product_name_and_cat_name['product_category_name'] : '');
							if($tax_display == "excl"){
								echo "\n"."_fxm.events.push(['_fxm.ecommerce.purchaseitem','".$product_id."', '".$product_name."', '".$product_category_name."', '".$quantity."', '".$total."', '".$order_id."', '".$oln."', 0]);";
							}else{
								echo "\n"."_fxm.events.push(['_fxm.ecommerce.purchaseitem','".$product_id."', '".$product_name."', '".$product_category_name."', '".$quantity."', '".$total."', '".$order_id."', '".$oln."', '".$tax."']);";
							}

							if($tax_display == "excl"){
								$subtotal_with_tax_calc += $subtotal;
							}else{
								$subtotal_with_tax_calc += ($subtotal + $tax);
							}
							

							$oln++;
						}
					}

					$subtotal_with_tax_calc = number_format( (float) $subtotal_with_tax_calc, wc_get_price_decimals(), '.', '' );
					
					// Get Order Lines
					//$line_subtotal = $order->get_subtotal();
					
					// $line_subtotal_with_taxes = strip_tags(html_entity_decode($order->get_subtotal_to_display( false, $tax_display )));

					$total_tax = $order->get_total_tax();
					$shipping_total = $order->get_shipping_total();
					$shipping_tax = $order->get_shipping_tax();
					if($tax_display == "excl"){
						$shipping_total_with_taxes = $shipping_total;
					}else{
						$shipping_total_with_taxes = ($shipping_total + $shipping_tax);
					}
					$shipping_total_with_taxes = number_format( (float) $shipping_total_with_taxes, wc_get_price_decimals(), '.', '' );
					
					// $shipping_total_with_taxes = strip_tags(html_entity_decode($order->get_shipping_to_display( $tax_display )));
					
					// Get Order Billing Addresses
					$billing_city = $order->get_billing_city();
					$billing_state = $order->get_billing_state();
					$billing_postcode = $order->get_billing_postcode();

					echo "\n"."_fxm.events.push(['_fxm.ecommerce.order','".$order_id."', '".$subtotal_with_tax_calc."', '".$shipping_total_with_taxes."', '".$total_tax."', '".$billing_city."', '".$billing_state."', '".$billing_postcode."', '".$unique_items_counts."']);";
				}
			}
		}
	}

	/**
	 * WooCommerce tracking on product remove from cart
	 *
	 * @since    1.0.1
	 */
	public function foxmetrics_tracking_cart_remove_item_callback(){

		$result = [];
		$result['success'] = true;

		if ( !empty($_POST['product_id']) ) {

			$product_id = $_POST['product_id'];
			$product_name_and_cat_name = $this->wc_get_product_name_and_cat_name( $product_id );
			$product_name = (!empty($product_name_and_cat_name['product_name']) ? $product_name_and_cat_name['product_name'] : '');
			$product_category_name = (!empty($product_name_and_cat_name['product_category_name']) ? $product_name_and_cat_name['product_category_name'] : '');
			// prepare the script
			$result['event_script'] = "<!-- FoxMetrics Web Analytics Start --><script type='text/javascript'>_fxm.events.push(['_fxm.ecommerce.removecartitem','".$product_id."', '".$product_name."', '".$product_category_name."']);</script><!-- FoxMetrics Web Analytics End -->";
		}
		echo json_encode($result);
		wp_die();
	}

	/**
	 * WooCommerce tracking on product is added to cart
	 *
	 * @since    1.0.1
	 */
	public function foxmetrics_tracking_cart_add_item_callback(){

		$result = [];
		$result['success'] = true;

		if ( !empty($_POST['product_id']) ) {

			$product_id = $_POST['product_id'];
			$quantity = (!empty($_POST['quantity'])) ? $_POST['quantity'] : 1;
			$product_name_and_cat_name = $this->wc_get_product_name_and_cat_name( $product_id );
			$product_name = (!empty($product_name_and_cat_name['product_name']) ? $product_name_and_cat_name['product_name'] : '');
			$product_price = (!empty($product_name_and_cat_name['product_price']) ? $product_name_and_cat_name['product_price'] : '');
			$product_category_name = (!empty($product_name_and_cat_name['product_category_name']) ? $product_name_and_cat_name['product_category_name'] : '');
			// prepare the script
			$result['event_script'] = "<!-- FoxMetrics Web Analytics Start --><script type='text/javascript'>_fxm.events.push(['_fxm.ecommerce.addcartitem','".$product_id."', '".$product_name."', '".$product_category_name."', '".$quantity."', '".$product_price."']);</script><!-- FoxMetrics Web Analytics End -->";
		}
		echo json_encode($result);
		wp_die();
	}


}
