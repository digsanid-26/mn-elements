<?php
namespace MN_Elements;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * MN Elements Widgets Manager
 *
 * Handles registration and management of custom widgets with conditional loading
 *
 * @since 1.0.2
 */
class Widgets_Manager {

	/**
	 * Instance
	 *
	 * @since 1.0.2
	 * @access private
	 * @static
	 *
	 * @var Widgets_Manager The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.2
	 * @access public
	 * @static
	 *
	 * @return Widgets_Manager An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Available widgets configuration
	 *
	 * @since 1.4.1
	 * @access private
	 * @var array
	 */
	private $available_widgets = [];

	/**
	 * Constructor
	 *
	 * @since 1.0.2
	 * @access public
	 */
	public function __construct() {
		$this->init_available_widgets();
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
		add_action( 'elementor/elements/categories_registered', [ $this, 'add_elementor_widget_categories' ] );
	}

	/**
	 * Add widget categories
	 *
	 * @since 1.0.2
	 * @access public
	 */
	public function add_elementor_widget_categories( $elements_manager ) {
		$elements_manager->add_category(
			'mn-elements',
			[
				'title' => esc_html__( 'MN Elements', 'mn-elements' ),
				'icon' => 'fa fa-plug',
			]
		);
	}

	/**
	 * Initialize available widgets configuration
	 *
	 * @since 1.4.1
	 * @access private
	 */
	private function init_available_widgets() {
		$this->available_widgets = [
			'mn-button' => [
				'file' => 'mn-button.php',
				'class' => '\MN_Elements\Widgets\MN_Button',
			],
			'mn-posts' => [
				'file' => 'mn-posts.php',
				'class' => '\MN_Elements\Widgets\MN_Posts',
			],
			'mn-counter' => [
				'file' => 'mn-counter.php',
				'class' => '\MN_Elements\Widgets\MN_Counter',
			],
			'mn-running-post' => [
				'file' => 'mn-running-post.php',
				'class' => '\MN_Elements\Widgets\MN_Running_Post',
			],
			'mn-infolist' => [
				'file' => 'mn-infolist.php',
				'class' => '\MN_Elements\Widgets\MN_Infolist',
			],
			'mn-office-hours' => [
				'file' => 'mn-office-hours.php',
				'class' => '\MN_Elements\Widgets\MN_Office_Hours',
			],
			'mn-gallery' => [
				'file' => 'mn-gallery.php',
				'class' => '\MN_Elements\Widgets\MN_Gallery',
			],
			'mn-video-playlist' => [
				'file' => 'mn-video-playlist.php',
				'class' => '\MN_Elements\Widgets\MN_Video_Playlist',
			],
			'mn-download' => [
				'file' => 'mn-download.php',
				'class' => '\MN_Elements\Widgets\MN_Download',
			],
			'mn-slideswipe' => [
				'file' => 'mn-slideswipe.php',
				'class' => '\MN_Elements\Widgets\MN_SlideSwipe',
			],
			'mn-image-comparison' => [
				'file' => 'mn-image-comparison.php',
				'class' => '\MN_Elements\Widgets\MN_Image_Comparison',
			],
			'mn-view' => [
				'file' => 'mn-view.php',
				'class' => '\MN_Elements\Widgets\MN_View',
			],
			'mn-dynamic-tabs' => [
				'file' => 'mn-dynamic-tabs.php',
				'class' => '\MN_Elements\Widgets\MN_Dynamic_Tabs',
			],
			'mn-logolist' => [
				'file' => 'mn-logolist.php',
				'class' => '\MN_Elements\Widgets\MN_Logolist',
			],
			'mn-dual-slider' => [
				'file' => 'mn-dual-slider.php',
				'class' => '\MN_Elements\Widgets\MN_Dual_Slider',
			],
			'mn-heading' => [
				'file' => 'mn-heading.php',
				'class' => '\MN_Elements\Widgets\MN_Heading',
			],
			'mn-social-reviews' => [
				'file' => 'mn-social-reviews.php',
				'class' => '\MN_Elements\Widgets\MN_Social_Reviews',
			],
			'mn-sidepanel' => [
				'file' => 'mn-sidepanel.php',
				'class' => '\MN_Elements\Widgets\MN_Sidepanel',
			],
			'mn-hero-slider' => [
				'file' => 'mn-hero-slider.php',
				'class' => '\MN_Elements\Widgets\MN_Hero_Slider',
			],
			'mn-image-or-icon' => [
				'file' => 'mn-image-or-icon.php',
				'class' => '\MN_Elements\Widgets\MN_ImageOrIcon',
			],
			'mn-woocart' => [
				'file' => 'mn-woocart.php',
				'class' => '\MN_Elements\Widgets\MN_WooCart',
			],
			'mn-wachat' => [
				'file' => 'mn-wachat.php',
				'class' => '\MN_Elements\Widgets\MN_Wachat',
			],
			'mn-instafeed' => [
				'file' => 'mn-instafeed.php',
				'class' => '\MN_Elements\Widgets\MN_Instafeed',
			],
			'mn-gootesti' => [
				'file' => 'mn-gootesti.php',
				'class' => '\MN_Elements\Widgets\MN_Gootesti',
			],
			'mn-author' => [
				'file' => 'mn-author.php',
				'class' => '\MN_Elements\Widgets\MN_Author',
			],
			'mn-postnav' => [
				'file' => 'mn-postnav.php',
				'class' => '\MN_Elements\Widgets\MN_Postnav',
			],
			'mn-testimony' => [
				'file' => 'mn-testimony.php',
				'class' => '\MN_Elements\Widgets\MN_Testimony',
			],
			'mn-accordion' => [
				'file' => 'mn-accordion.php',
				'class' => '\MN_Elements\Widgets\MN_Accordion',
			],
			'mn-woo-product-gallery' => [
				'file' => 'mn-woo-product-gallery.php',
				'class' => '\MN_Elements\Widgets\MN_Woo_Product_Gallery',
			],
			'mn-wooproduct' => [
				'file' => 'mn-wooproduct.php',
				'class' => '\MN_Elements\Widgets\MN_WooProduct',
			],
			'mn-woofilter' => [
				'file' => 'mn-woofilter.php',
				'class' => '\MN_Elements\Widgets\MN_WooFilter',
			],
			'mn-woocart-checkout' => [
				'file' => 'mn-woocart-checkout.php',
				'class' => '\MN_Elements\Widgets\MN_WooCart_Checkout',
			],
			'mn-add-to-cart' => [
				'file' => 'mn-add-to-cart.php',
				'class' => '\MN_Elements\Widgets\MN_Add_To_Cart',
			],
			'mn-mbmenu' => [
				'file' => 'mn-mbmenu.php',
				'class' => '\MN_Elements\Widgets\MN_Mobile_Menu',
			],
			'mn-vertical-post' => [
				'file' => 'mn-vertical-post.php',
				'class' => '\MN_Elements\Widgets\MN_Vertical_Post',
			],
			'mn-videoplayer' => [
				'file' => 'mn-videoplayer.php',
				'class' => '\MN_Elements\Widgets\MN_Video_Player',
			],
		];
	}

	/**
	 * Register widgets
	 *
	 * @since 1.0.2
	 * @access public
	 */
	public function register_widgets( $widgets_manager ) {
		// Get active widgets from Element Manager
		$active_widgets = get_option( 'mn_elements_active_widgets', array_keys( $this->available_widgets ) );

		// Only load and register active widgets
		foreach ( $active_widgets as $widget_key ) {
			if ( ! isset( $this->available_widgets[ $widget_key ] ) ) {
				continue;
			}

			$widget_config = $this->available_widgets[ $widget_key ];
			$widget_file = MN_ELEMENTS_PATH . 'includes/widgets/' . $widget_config['file'];
			
			// Check if widget file exists
			if ( ! file_exists( $widget_file ) ) {
				continue;
			}

			// Include widget file
			require_once( $widget_file );

			// Check if widget class exists
			if ( ! class_exists( $widget_config['class'] ) ) {
				continue;
			}

			// Register widget
			$widgets_manager->register( new $widget_config['class']() );
		}
	}

	/**
	 * Get available widgets
	 *
	 * @since 1.4.1
	 * @access public
	 * @return array
	 */
	public function get_available_widgets() {
		return $this->available_widgets;
	}

	/**
	 * Get active widgets
	 *
	 * @since 1.4.1
	 * @access public
	 * @return array
	 */
	public function get_active_widgets() {
		return get_option( 'mn_elements_active_widgets', array_keys( $this->available_widgets ) );
	}

	/**
	 * Check if widget is active
	 *
	 * @since 1.4.1
	 * @access public
	 * @param string $widget_key
	 * @return bool
	 */
	public function is_widget_active( $widget_key ) {
		$active_widgets = $this->get_active_widgets();
		return in_array( $widget_key, $active_widgets );
	}
}

// Initialize
Widgets_Manager::instance();
