<?php
/**
 * Plugin Name: MN Elements
 * Plugin URI:  https://github.com/digsanid-26/mn-elements
 * Description: Kumpulan widget dan efek kustom untuk Elementor yang dapat memperkaya halaman web Anda dengan animasi dan kontrol yang menarik.
 * Version:     3.1.8
 * Author:      DigsanID
 * Author URI:  https://digsan.id
 * Text Domain: mn-elements
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

// Define plugin constants
define( 'MN_ELEMENTS_VERSION', '3.1.8' );
define( 'MN_ELEMENTS_PATH', plugin_dir_path( __FILE__ ) );
define( 'MN_ELEMENTS_URL', plugin_dir_url( __FILE__ ) );

// If class `MN_Elements` doesn't exists yet.
if ( ! class_exists( 'MN_Elements' ) ) {

	/**
	 * Sets up and initializes the plugin.
	 */
	class MN_Elements {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    object
		 */
		private static $instance = null;

		/**
		 * Plugin version
		 *
		 * @var string
		 */
		private $version = '3.1.8';

		/**
		 * Holder for base plugin URL
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    string
		 */
		private $plugin_url = null;

		/**
		 * Holder for base plugin path
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    string
		 */
		private $plugin_path = null;

		/**
		 * Sets up needed actions/filters for the plugin to initialize.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function __construct() {

			// Internationalize the text strings used.
			add_action( 'init', array( $this, 'lang' ), -999 );
			
			// Load files.
			add_action( 'init', array( $this, 'init' ), -999 );

			// Register activation and deactivation hook.
			register_activation_hook( __FILE__, array( $this, 'activation' ) );
			register_deactivation_hook( __FILE__, array( $this, 'deactivation' ) );
		}

		/**
		 * Returns plugin version
		 *
		 * @return string
		 */
		public function get_version() {
			return $this->version;
		}

		/**
		 * Manually init required modules.
		 *
		 * @return void
		 */
		public function init() {

			if ( ! $this->has_elementor() ) {
				add_action( 'admin_notices', array( $this, 'required_plugins_notice' ) );
				return;
			}

			$this->load_files();

			mn_elements_container_extension()->init();
			mn_elements_assets()->init();

			// Register AJAX handlers
			$this->register_ajax_handlers();

			do_action( 'mn-elements/init', $this );
		}

		/**
		 * Show required plugins notice.
		 *
		 * @return void
		 */
		public function required_plugins_notice() {
			$screen = get_current_screen();

			if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
				return;
			}

			$plugin = 'elementor/elementor.php';

			$installed_plugins      = get_plugins();
			$is_elementor_installed = isset( $installed_plugins[ $plugin ] );

			if ( $is_elementor_installed ) {
				if ( ! current_user_can( 'activate_plugins' ) ) {
					return;
				}

				$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );

				$message = sprintf( '<p>%s</p>', esc_html__( 'MN Elements memerlukan Elementor untuk diaktifkan.', 'mn-elements' ) );
				$message .= sprintf( '<p><a href="%s" class="button-primary">%s</a></p>', $activation_url, esc_html__( 'Aktifkan Elementor Sekarang', 'mn-elements' ) );
			} else {
				if ( ! current_user_can( 'install_plugins' ) ) {
					return;
				}

				$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );

				$message = sprintf( '<p>%s</p>', esc_html__( 'MN Elements memerlukan Elementor untuk diinstal.', 'mn-elements' ) );
				$message .= sprintf( '<p><a href="%s" class="button-primary">%s</a></p>', $install_url, esc_html__( 'Instal Elementor Sekarang', 'mn-elements' ) );
			}

			printf( '<div class="notice notice-warning is-dismissible"><p>%s</p></div>', wp_kses_post( $message ) );
		}

		/**
		 * Load required files
		 *
		 * @return void
		 */
		public function load_files() {
			// Load helper classes
			require $this->plugin_path( 'includes/helpers/class-mn-youtube-api.php' );
			
			require $this->plugin_path( 'includes/container-extension.php' );
			require $this->plugin_path( 'includes/assets.php' );
			require $this->plugin_path( 'includes/widgets-manager.php' );
			require $this->plugin_path( 'includes/ajax-handlers.php' );
			
			// Load admin components only in admin
			if ( is_admin() ) {
				require $this->plugin_path( 'includes/admin/settings.php' );
				require $this->plugin_path( 'includes/admin/admin-menu.php' );
				require $this->plugin_path( 'includes/admin/element-manager.php' );
			}
		}

		/**
		 * Register AJAX handlers
		 *
		 * @return void
		 */
		public function register_ajax_handlers() {
			// MN Posts quickview AJAX handler
			add_action( 'wp_ajax_mn_posts_quickview', array( 'MN_Elements\\Widgets\\MN_Posts', 'handle_quickview_ajax' ) );
			add_action( 'wp_ajax_nopriv_mn_posts_quickview', array( 'MN_Elements\\Widgets\\MN_Posts', 'handle_quickview_ajax' ) );
			
			// MN WooCart AJAX handlers
			add_action( 'wp_ajax_mn_update_cart_quantity', array( $this, 'handle_update_cart_quantity' ) );
			add_action( 'wp_ajax_nopriv_mn_update_cart_quantity', array( $this, 'handle_update_cart_quantity' ) );
			add_action( 'wp_ajax_mn_remove_cart_item', array( $this, 'handle_remove_cart_item' ) );
			add_action( 'wp_ajax_nopriv_mn_remove_cart_item', array( $this, 'handle_remove_cart_item' ) );
			add_action( 'wp_ajax_mn_refresh_cart_widget', array( $this, 'handle_refresh_cart_widget' ) );
			add_action( 'wp_ajax_nopriv_mn_refresh_cart_widget', array( $this, 'handle_refresh_cart_widget' ) );

			// MN Add To Cart AJAX handlers
			add_action( 'wp_ajax_mn_add_to_cart', array( $this, 'handle_add_to_cart' ) );
			add_action( 'wp_ajax_nopriv_mn_add_to_cart', array( $this, 'handle_add_to_cart' ) );
			add_action( 'wp_ajax_mn_get_variation_data', array( $this, 'handle_get_variation_data' ) );
			add_action( 'wp_ajax_nopriv_mn_get_variation_data', array( $this, 'handle_get_variation_data' ) );
		}

		/**
		 * Check if theme has elementor
		 *
		 * @return boolean
		 */
		public function has_elementor() {
			return defined( 'ELEMENTOR_VERSION' );
		}

		/**
		 * [elementor description]
		 * @return [type] [description]
		 */
		public function elementor() {
			return \Elementor\Plugin::$instance;
		}

		/**
		 * Returns path to file or dir inside plugin folder
		 *
		 * @param  string $path Path inside plugin dir.
		 * @return string
		 */
		public function plugin_path( $path = null ) {

			if ( ! $this->plugin_path ) {
				$this->plugin_path = trailingslashit( plugin_dir_path( __FILE__ ) );
			}

			return $this->plugin_path . $path;
		}

		/**
		 * Returns url to file or dir inside plugin folder
		 *
		 * @param  string $path Path inside plugin dir.
		 * @return string
		 */
		public function plugin_url( $path = null ) {

			if ( ! $this->plugin_url ) {
				$this->plugin_url = trailingslashit( plugin_dir_url( __FILE__ ) );
			}

			return $this->plugin_url . $path;
		}

		/**
		 * Loads the translation files.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function lang() {
			load_plugin_textdomain( 'mn-elements', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		/**
		 * Do some stuff on plugin activation
		 *
		 * @since  1.0.0
		 * @return void
		 */
		public function activation() {
			// Set default active widgets on first activation
			if ( ! get_option( 'mn_elements_active_widgets' ) ) {
				$default_widgets = [
					'mn-button',
					'mn-posts', 
					'mn-counter',
					'mn-running-post',
					'mn-infolist',
					'mn-video-playlist',
					'mn-download',
					'mn-slideswipe',
					'mn-image-comparison',
					'mn-view'
				];
				update_option( 'mn_elements_active_widgets', $default_widgets );
			}
		}

		/**
		 * Do some stuff on plugin deactivation
		 *
		 * @since  1.0.0
		 * @return void
		 */
		public function deactivation() {
		}

		/**
		 * Handle update cart quantity AJAX
		 *
		 * @since 1.7.5
		 * @return void
		 */
		public function handle_update_cart_quantity() {
			if ( ! class_exists( 'WooCommerce' ) ) {
				wp_send_json_error( 'WooCommerce not active' );
			}

			$cart_item_key = isset( $_POST['cart_item_key'] ) ? sanitize_text_field( $_POST['cart_item_key'] ) : '';
			$quantity = isset( $_POST['quantity'] ) ? absint( $_POST['quantity'] ) : 1;

			if ( empty( $cart_item_key ) ) {
				wp_send_json_error( 'Invalid cart item key' );
			}

			$cart = WC()->cart;
			$cart->set_quantity( $cart_item_key, $quantity, true );
			$cart->calculate_totals();

			wp_send_json_success( array(
				'cart_count' => $cart->get_cart_contents_count(),
				'cart_total' => $cart->get_cart_subtotal(),
				'cart_subtotal' => $cart->get_cart_subtotal(),
			) );
		}

		/**
		 * Handle remove cart item AJAX
		 *
		 * @since 1.7.5
		 * @return void
		 */
		public function handle_remove_cart_item() {
			if ( ! class_exists( 'WooCommerce' ) ) {
				wp_send_json_error( 'WooCommerce not active' );
			}

			$cart_item_key = isset( $_POST['cart_item_key'] ) ? sanitize_text_field( $_POST['cart_item_key'] ) : '';

			if ( empty( $cart_item_key ) ) {
				wp_send_json_error( 'Invalid cart item key' );
			}

			$cart = WC()->cart;
			$removed = $cart->remove_cart_item( $cart_item_key );

			if ( $removed ) {
				wp_send_json_success( array(
					'cart_count' => $cart->get_cart_contents_count(),
					'cart_total' => $cart->get_cart_subtotal(),
				) );
			} else {
				wp_send_json_error( 'Failed to remove item' );
			}
		}

		/**
		 * Handle add to cart AJAX
		 *
		 * @since 3.0.5
		 * @return void
		 */
		public function handle_add_to_cart() {
			if ( ! class_exists( 'WooCommerce' ) ) {
				wp_send_json_error( array( 'message' => 'WooCommerce not active' ) );
			}

			// Verify nonce
			if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'mn_add_to_cart_nonce' ) ) {
				wp_send_json_error( array( 'message' => esc_html__( 'Security check failed', 'mn-elements' ) ) );
			}

			$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
			$variation_id = isset( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : 0;
			$quantity = isset( $_POST['quantity'] ) ? absint( $_POST['quantity'] ) : 1;
			$variations = isset( $_POST['variations'] ) ? (array) $_POST['variations'] : array();

			if ( ! $product_id ) {
				wp_send_json_error( array( 'message' => esc_html__( 'Invalid product', 'mn-elements' ) ) );
			}

			$product = wc_get_product( $product_id );
			if ( ! $product ) {
				wp_send_json_error( array( 'message' => esc_html__( 'Product not found', 'mn-elements' ) ) );
			}

			// Sanitize and format variations - ensure proper format
			$sanitized_variations = array();
			foreach ( $variations as $key => $value ) {
				$key = sanitize_text_field( $key );
				$value = sanitize_text_field( $value );
				
				// Normalize key format - WooCommerce expects lowercase 'attribute_' prefix
				$key = strtolower( $key );
				if ( strpos( $key, 'attribute_' ) !== 0 ) {
					$key = 'attribute_' . $key;
				}
				
				$sanitized_variations[ $key ] = $value;
			}

			// Add to cart
			if ( $product->is_type( 'variable' ) ) {
				// If variation_id not provided, try to find it
				if ( ! $variation_id && ! empty( $sanitized_variations ) ) {
					$data_store = \WC_Data_Store::load( 'product' );
					$variation_id = $data_store->find_matching_product_variation( $product, $sanitized_variations );
				}
				
				if ( $variation_id ) {
					$added = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $sanitized_variations );
				} else {
					wp_send_json_error( array( 
						'message' => esc_html__( 'Please select product options.', 'mn-elements' ),
						'debug' => array(
							'variations_sent' => $variations,
							'variations_formatted' => $sanitized_variations
						)
					) );
					return;
				}
			} else {
				$added = WC()->cart->add_to_cart( $product_id, $quantity );
			}

			if ( $added ) {
				// Get updated fragments
				ob_start();
				woocommerce_mini_cart();
				$mini_cart = ob_get_clean();

				$data = array(
					'message' => esc_html__( 'Product added to cart!', 'mn-elements' ),
					'fragments' => apply_filters( 'woocommerce_add_to_cart_fragments', array(
						'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
					) ),
					'cart_hash' => WC()->cart->get_cart_hash(),
					'cart_count' => WC()->cart->get_cart_contents_count(),
				);

				wp_send_json_success( $data );
			} else {
				wp_send_json_error( array( 'message' => esc_html__( 'Could not add to cart. Please try again.', 'mn-elements' ) ) );
			}
		}

		/**
		 * Handle get variation data AJAX
		 *
		 * @since 3.0.5
		 * @return void
		 */
		public function handle_get_variation_data() {
			if ( ! class_exists( 'WooCommerce' ) ) {
				wp_send_json_error( array( 'message' => 'WooCommerce not active' ) );
			}

			// Verify nonce
			if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'mn_add_to_cart_nonce' ) ) {
				wp_send_json_error( array( 'message' => esc_html__( 'Security check failed', 'mn-elements' ) ) );
			}

			$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
			$variations = isset( $_POST['variations'] ) ? (array) $_POST['variations'] : array();

			if ( ! $product_id ) {
				wp_send_json_error( array( 'message' => esc_html__( 'Invalid product', 'mn-elements' ) ) );
			}

			$product = wc_get_product( $product_id );
			if ( ! $product || ! $product->is_type( 'variable' ) ) {
				wp_send_json_error( array( 'message' => esc_html__( 'Invalid variable product', 'mn-elements' ) ) );
			}

			// Sanitize and format variations - ensure proper format
			$sanitized_variations = array();
			foreach ( $variations as $key => $value ) {
				$key = sanitize_text_field( $key );
				$value = sanitize_text_field( $value );
				
				// Normalize key format - WooCommerce expects lowercase 'attribute_' prefix
				$key = strtolower( $key );
				if ( strpos( $key, 'attribute_' ) !== 0 ) {
					$key = 'attribute_' . $key;
				}
				
				$sanitized_variations[ $key ] = $value;
			}

			// Find matching variation
			$data_store = \WC_Data_Store::load( 'product' );
			$variation_id = $data_store->find_matching_product_variation( $product, $sanitized_variations );

			if ( $variation_id ) {
				$variation = wc_get_product( $variation_id );
				
				wp_send_json_success( array(
					'variation_id' => $variation_id,
					'price_html' => $variation->get_price_html(),
					'is_in_stock' => $variation->is_in_stock(),
					'stock_quantity' => $variation->get_stock_quantity(),
				) );
			} else {
				wp_send_json_error( array( 
					'message' => esc_html__( 'No matching variation found', 'mn-elements' ),
					'debug' => array(
						'variations_sent' => $variations,
						'variations_formatted' => $sanitized_variations,
						'available_attributes' => array_keys( $product->get_variation_attributes() )
					)
				) );
			}
		}

		/**
		 * Handle refresh cart widget AJAX
		 *
		 * @since 1.7.5
		 * @return void
		 */
		public function handle_refresh_cart_widget() {
			if ( ! class_exists( 'WooCommerce' ) ) {
				wp_send_json_error( 'WooCommerce not active' );
			}

			$cart = WC()->cart;
			$cart_items = $cart->get_cart();
			
			ob_start();
			
			// Cart Items
			echo '<div class="mn-woocart-items">';
			if ( empty( $cart_items ) ) {
				echo '<div class="mn-woocart-empty">' . esc_html__( 'Your cart is empty.', 'mn-elements' ) . '</div>';
			} else {
				foreach ( $cart_items as $cart_item_key => $cart_item ) {
					$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 ) {
						$product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
						$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
						$product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
						?>
						<div class="mn-woocart-item" data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>">
							<div class="mn-woocart-item-row">
								<div class="mn-woocart-item-thumbnail">
									<?php echo wp_kses_post( $thumbnail ); ?>
								</div>
								<div class="mn-woocart-item-details">
									<div class="mn-woocart-item-name"><?php echo wp_kses_post( $product_name ); ?></div>
									<div class="mn-woocart-item-price"><?php echo wp_kses_post( $product_price ); ?></div>
									<div class="mn-woocart-item-quantity">
										<button type="button" class="mn-woocart-qty-btn mn-woocart-qty-minus" data-action="minus">-</button>
										<input type="number" class="mn-woocart-qty-input" value="<?php echo esc_attr( $cart_item['quantity'] ); ?>" min="0" readonly>
										<button type="button" class="mn-woocart-qty-btn mn-woocart-qty-plus" data-action="plus">+</button>
									</div>
								</div>
								<button type="button" class="mn-woocart-remove" data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>" title="<?php esc_attr_e( 'Remove item', 'mn-elements' ); ?>">
									<i class="eicon-close"></i>
								</button>
							</div>
						</div>
						<?php
					}
				}
			}
			echo '</div>';
			
			// Cart Footer with buttons
			if ( ! empty( $cart_items ) ) {
				?>
				<div class="mn-woocart-footer">
					<div class="mn-woocart-subtotal">
						<span><?php esc_html_e( 'Subtotal:', 'mn-elements' ); ?></span>
						<span class="mn-woocart-subtotal-amount"><?php echo wp_kses_post( $cart->get_cart_subtotal() ); ?></span>
					</div>
					<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="mn-woocart-view-cart button">
						<?php esc_html_e( 'View Cart', 'mn-elements' ); ?>
					</a>
					<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="mn-woocart-checkout button">
						<?php esc_html_e( 'Checkout', 'mn-elements' ); ?>
					</a>
				</div>
				<?php
			}
			
			$html = ob_get_clean();

			wp_send_json_success( array(
				'html' => $html,
				'cart_count' => $cart->get_cart_contents_count(),
				'cart_total' => $cart->get_cart_subtotal(),
				'cart_subtotal' => $cart->get_cart_subtotal(),
			) );
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return object
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}
}

if ( ! function_exists( 'mn_elements' ) ) {

	/**
	 * Returns instanse of the plugin class.
	 *
	 * @since  1.0.0
	 * @return object
	 */
	function mn_elements() {
		return MN_Elements::get_instance();
	}
}

mn_elements();
