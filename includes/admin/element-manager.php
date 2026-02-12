<?php
namespace MN_Elements\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * MN Elements Element Manager
 *
 * Admin page for managing widget activation/deactivation
 *
 * @since 1.4.1
 */
class Element_Manager {

	/**
	 * Instance
	 *
	 * @since 1.4.1
	 * @access private
	 * @static
	 *
	 * @var Element_Manager The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Available widgets
	 *
	 * @since 1.4.1
	 * @access private
	 * @var array
	 */
	private $available_widgets = [];

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.4.1
	 * @access public
	 * @static
	 *
	 * @return Element_Manager An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor
	 *
	 * @since 1.4.1
	 * @access public
	 */
	public function __construct() {
		$this->init_available_widgets();
		add_action( 'wp_ajax_mn_elements_save_settings', [ $this, 'save_settings' ] );
	}

	/**
	 * Initialize available widgets
	 *
	 * @since 1.4.1
	 * @access private
	 */
	private function init_available_widgets() {
		$this->available_widgets = [
			'mn-button' => [
				'title' => esc_html__( 'MN Button', 'mn-elements' ),
				'description' => esc_html__( 'Enhanced button widget with animations and custom styling', 'mn-elements' ),
				'icon' => 'eicon-button',
				'category' => 'basic',
				'file' => 'mn-button.php',
				'class' => '\MN_Elements\Widgets\MN_Button',
			],
			'mn-posts' => [
				'title' => esc_html__( 'MN Posts', 'mn-elements' ),
				'description' => esc_html__( 'Advanced posts widget with theme variations and animations', 'mn-elements' ),
				'icon' => 'eicon-posts-grid',
				'category' => 'content',
				'file' => 'mn-posts.php',
				'class' => '\MN_Elements\Widgets\MN_Posts',
			],
			'mn-counter' => [
				'title' => esc_html__( 'MN Counter', 'mn-elements' ),
				'description' => esc_html__( 'Animated counter widget with customizable styling', 'mn-elements' ),
				'icon' => 'eicon-counter',
				'category' => 'content',
				'file' => 'mn-counter.php',
				'class' => '\MN_Elements\Widgets\MN_Counter',
			],
			'mn-running-post' => [
				'title' => esc_html__( 'MN Running Post', 'mn-elements' ),
				'description' => esc_html__( 'Continuous scrolling post ticker with hover controls', 'mn-elements' ),
				'icon' => 'eicon-posts-ticker',
				'category' => 'content',
				'file' => 'mn-running-post.php',
				'class' => '\MN_Elements\Widgets\MN_Running_Post',
			],
			'mn-infolist' => [
				'title' => esc_html__( 'MN Infolist', 'mn-elements' ),
				'description' => esc_html__( 'Manual list widget with images and descriptions', 'mn-elements' ),
				'icon' => 'eicon-editor-list-ul',
				'category' => 'content',
				'file' => 'mn-infolist.php',
				'class' => '\MN_Elements\Widgets\MN_Infolist',
			],
			'mn-video-playlist' => [
				'title' => esc_html__( 'MN Video Playlist', 'mn-elements' ),
				'description' => esc_html__( 'YouTube video playlist with custom layouts', 'mn-elements' ),
				'icon' => 'eicon-youtube',
				'category' => 'media',
				'file' => 'mn-video-playlist.php',
				'class' => '\MN_Elements\Widgets\MN_Video_Playlist',
			],
			'mn-download' => [
				'title' => esc_html__( 'MN Download', 'mn-elements' ),
				'description' => esc_html__( 'File download widget with taxonomy filtering', 'mn-elements' ),
				'icon' => 'eicon-download-button',
				'category' => 'content',
				'file' => 'mn-download.php',
				'class' => '\MN_Elements\Widgets\MN_Download',
			],
			'mn-slideswipe' => [
				'title' => esc_html__( 'MN SlideSwipe', 'mn-elements' ),
				'description' => esc_html__( 'Template-based slider with Swiper.js integration', 'mn-elements' ),
				'icon' => 'eicon-slides',
				'category' => 'media',
				'file' => 'mn-slideswipe.php',
				'class' => '\MN_Elements\Widgets\MN_SlideSwipe',
			],
			'mn-image-comparison' => [
				'title' => esc_html__( 'MN Image Comparison', 'mn-elements' ),
				'description' => esc_html__( 'Before/After image comparison with slider', 'mn-elements' ),
				'icon' => 'eicon-image-before-after',
				'category' => 'media',
				'file' => 'mn-image-comparison.php',
				'class' => '\MN_Elements\Widgets\MN_Image_Comparison',
			],
			'mn-view' => [
				'title' => esc_html__( 'MN View', 'mn-elements' ),
				'description' => esc_html__( 'File viewer with popup/modal for PDF, images, and videos', 'mn-elements' ),
				'icon' => 'eicon-document-file',
				'category' => 'media',
				'file' => 'mn-view.php',
				'class' => '\MN_Elements\Widgets\MN_View',
			],
			'mn-dynamic-tabs' => [
				'title' => esc_html__( 'MN Dynamic Tabs', 'mn-elements' ),
				'description' => esc_html__( 'Dynamic tabs with post queries and auto-slide functionality', 'mn-elements' ),
				'icon' => 'eicon-tabs',
				'category' => 'content',
				'file' => 'mn-dynamic-tabs.php',
				'class' => '\MN_Elements\Widgets\MN_Dynamic_Tabs',
			],
			'mn-logolist' => [
				'title' => esc_html__( 'MN Logolist', 'mn-elements' ),
				'description' => esc_html__( 'Logo display widget with list, grid, and carousel layouts', 'mn-elements' ),
				'icon' => 'eicon-logo',
				'category' => 'media',
				'file' => 'mn-logolist.php',
				'class' => '\MN_Elements\Widgets\MN_Logolist',
			],
			'mn-dual-slider' => [
				'title' => esc_html__( 'MN Dual Slider', 'mn-elements' ),
				'description' => esc_html__( 'Dual synchronized slider with static and dynamic data sources', 'mn-elements' ),
				'icon' => 'eicon-slider-push',
				'category' => 'media',
				'file' => 'mn-dual-slider.php',
				'class' => '\MN_Elements\Widgets\MN_Dual_Slider',
			],
			'mn-heading' => [
				'title' => esc_html__( 'MN Heading', 'mn-elements' ),
				'description' => esc_html__( 'Multi-part heading with gradient effects and individual styling', 'mn-elements' ),
				'icon' => 'eicon-heading',
				'category' => 'basic',
				'file' => 'mn-heading.php',
				'class' => '\MN_Elements\Widgets\MN_Heading',
			],
			'mn-social-reviews' => [
				'title' => esc_html__( 'MN Social Reviews', 'mn-elements' ),
				'description' => esc_html__( 'Display social media and marketplace reviews with static and dynamic options', 'mn-elements' ),
				'icon' => 'eicon-review',
				'category' => 'content',
				'file' => 'mn-social-reviews.php',
				'class' => '\MN_Elements\Widgets\MN_Social_Reviews',
			],
			'mn-sidepanel' => [
				'title' => esc_html__( 'MN Sidepanel', 'mn-elements' ),
				'description' => esc_html__( 'Fixed side panel with slide-in content, similar to BCA website', 'mn-elements' ),
				'icon' => 'eicon-sidebar',
				'category' => 'content',
				'file' => 'mn-sidepanel.php',
				'class' => '\MN_Elements\Widgets\MN_Sidepanel',
			],
			'mn-hero-slider' => [
				'title' => esc_html__( 'MN Hero Slider', 'mn-elements' ),
				'description' => esc_html__( 'Full-width hero slider with background images, titles, and call-to-action buttons', 'mn-elements' ),
				'icon' => 'eicon-slider-full-screen',
				'category' => 'media',
				'file' => 'mn-hero-slider.php',
				'class' => '\MN_Elements\Widgets\MN_Hero_Slider',
			],
			'mn-image-or-icon' => [
				'title' => esc_html__( 'MN Image or Icon', 'mn-elements' ),
				'description' => esc_html__( 'Display image or icon with dynamic field support, filter effects, and advanced styling', 'mn-elements' ),
				'icon' => 'eicon-image-bold',
				'category' => 'basic',
				'file' => 'mn-image-or-icon.php',
				'class' => '\MN_Elements\Widgets\MN_ImageOrIcon',
			],
			'mn-office-hours' => [
				'title' => esc_html__( 'MN Office Hours', 'mn-elements' ),
				'description' => esc_html__( 'Display business hours with flexible layout options and current day highlighting', 'mn-elements' ),
				'icon' => 'eicon-clock-o',
				'category' => 'content',
				'file' => 'mn-office-hours.php',
				'class' => '\MN_Elements\Widgets\MN_Office_Hours',
			],
			'mn-gallery' => [
				'title' => esc_html__( 'MN Gallery', 'mn-elements' ),
				'description' => esc_html__( 'Advanced gallery widget with multiple layouts, lightbox, and dynamic source support', 'mn-elements' ),
				'icon' => 'eicon-gallery-grid',
				'category' => 'media',
				'file' => 'mn-gallery.php',
				'class' => '\MN_Elements\Widgets\MN_Gallery',
			],
			'mn-woocart' => [
				'title' => esc_html__( 'MN WooCart', 'mn-elements' ),
				'description' => esc_html__( 'WooCommerce cart widget with menu and direct display modes, AJAX operations, and full customization', 'mn-elements' ),
				'icon' => 'eicon-cart',
				'category' => 'woocommerce',
				'file' => 'mn-woocart.php',
				'class' => '\MN_Elements\Widgets\MN_WooCart',
			],
			'mn-wachat' => [
				'title' => esc_html__( 'MN Wachat', 'mn-elements' ),
				'description' => esc_html__( 'WhatsApp chat widget with textarea and send button for direct messaging', 'mn-elements' ),
				'icon' => 'eicon-comments',
				'category' => 'content',
				'file' => 'mn-wachat.php',
				'class' => '\MN_Elements\Widgets\MN_Wachat',
			],
			'mn-instafeed' => [
				'title' => esc_html__( 'MN Instafeed', 'mn-elements' ),
				'description' => esc_html__( 'Instagram feed display with manual and API modes, grid, masonry, and carousel layouts', 'mn-elements' ),
				'icon' => 'eicon-instagram-gallery',
				'category' => 'media',
				'file' => 'mn-instafeed.php',
				'class' => '\MN_Elements\Widgets\MN_Instafeed',
			],
			'mn-gootesti' => [
				'title' => esc_html__( 'MN Gootesti', 'mn-elements' ),
				'description' => esc_html__( 'Display Google Business reviews and testimonials with manual input support, multiple layouts, and theme options', 'mn-elements' ),
				'icon' => 'eicon-testimonial',
				'category' => 'content',
				'file' => 'mn-gootesti.php',
				'class' => '\MN_Elements\Widgets\MN_Gootesti',
			],
			'mn-author' => [
				'title' => esc_html__( 'MN Author', 'mn-elements' ),
				'description' => esc_html__( 'Display author information with avatar, biography, and social media links from user profile', 'mn-elements' ),
				'icon' => 'eicon-person',
				'category' => 'content',
				'file' => 'mn-author.php',
				'class' => '\MN_Elements\Widgets\MN_Author',
			],
			'mn-postnav' => [
				'title' => esc_html__( 'MN Postnav', 'mn-elements' ),
				'description' => esc_html__( 'Post navigation with previous/next links and featured image thumbnails in center or inline position', 'mn-elements' ),
				'icon' => 'eicon-post-navigation',
				'category' => 'content',
				'file' => 'mn-postnav.php',
				'class' => '\MN_Elements\Widgets\MN_Postnav',
			],
			'mn-testimony' => [
				'title' => esc_html__( 'MN Testimony', 'mn-elements' ),
				'description' => esc_html__( 'Testimony listing with customizable layout, user profiles, carousel mode, and theme support', 'mn-elements' ),
				'icon' => 'eicon-testimonial',
				'category' => 'content',
				'file' => 'mn-testimony.php',
				'class' => '\MN_Elements\Widgets\MN_Testimony',
			],
			'mn-accordion' => [
				'title' => esc_html__( 'MN Accordion', 'mn-elements' ),
				'description' => esc_html__( 'Accordion widget with dynamic numbering, customizable icons, and FAQ schema support', 'mn-elements' ),
				'icon' => 'eicon-accordion',
				'category' => 'content',
				'file' => 'mn-accordion.php',
				'class' => '\MN_Elements\Widgets\MN_Accordion',
			],
			'mn-woo-product-gallery' => [
				'title' => esc_html__( 'MN Woo Product Gallery', 'mn-elements' ),
				'description' => esc_html__( 'WooCommerce product gallery with thumbnail navigation and zoom functionality', 'mn-elements' ),
				'icon' => 'eicon-woocommerce',
				'category' => 'woocommerce',
				'file' => 'mn-woo-product-gallery.php',
				'class' => '\MN_Elements\Widgets\MN_Woo_Product_Gallery',
			],
			'mn-wooproduct' => [
				'title' => esc_html__( 'MN Woo Product', 'mn-elements' ),
				'description' => esc_html__( 'WooCommerce product listing with grid layout, badges, variations, AJAX add to cart, and pagination', 'mn-elements' ),
				'icon' => 'eicon-products',
				'category' => 'woocommerce',
				'file' => 'mn-wooproduct.php',
				'class' => '\MN_Elements\Widgets\MN_WooProduct',
			],
			'mn-woofilter' => [
				'title' => esc_html__( 'MN Woo Filter', 'mn-elements' ),
				'description' => esc_html__( 'WooCommerce product filter with category, price, attributes, rating, stock filters, and mobile slide-in sidebar', 'mn-elements' ),
				'icon' => 'eicon-filter',
				'category' => 'woocommerce',
				'file' => 'mn-woofilter.php',
				'class' => '\MN_Elements\Widgets\MN_WooFilter',
			],
			'mn-woocart-checkout' => [
				'title' => esc_html__( 'MN Woo Cart/Checkout', 'mn-elements' ),
				'description' => esc_html__( 'WooCommerce cart and checkout widget with mini cart, cart page, checkout page, and cart totals display', 'mn-elements' ),
				'icon' => 'eicon-cart',
				'category' => 'woocommerce',
				'file' => 'mn-woocart-checkout.php',
				'class' => '\MN_Elements\Widgets\MN_WooCart_Checkout',
			],
			'mn-add-to-cart' => [
				'title' => esc_html__( 'MN Add To Cart', 'mn-elements' ),
				'description' => esc_html__( 'Single product add to cart with variation support (text, thumbnail, color swatch), quantity selector, and AJAX functionality', 'mn-elements' ),
				'icon' => 'eicon-product-add-to-cart',
				'category' => 'woocommerce',
				'file' => 'mn-add-to-cart.php',
				'class' => '\MN_Elements\Widgets\MN_Add_To_Cart',
			],
			'mn-mbmenu' => [
				'title' => esc_html__( 'MN Mobile Menu', 'mn-elements' ),
				'description' => esc_html__( 'Mobile-optimized navigation menu with hamburger icon, slide/fade animations, submenu support, and Safari/iOS compatibility', 'mn-elements' ),
				'icon' => 'eicon-nav-menu',
				'category' => 'general',
				'file' => 'mn-mbmenu.php',
				'class' => '\MN_Elements\Widgets\MN_Mobile_Menu',
			],
			'mn-vertical-post' => [
				'title' => esc_html__( 'MN Vertical Post', 'mn-elements' ),
				'description' => esc_html__( 'Vertical carousel post listing with sticky scroll, overlay gradient, and multiple layouts. Supports static items and dynamic posts', 'mn-elements' ),
				'icon' => 'eicon-post-slider',
				'category' => 'general',
				'file' => 'mn-vertical-post.php',
				'class' => '\MN_Elements\Widgets\MN_Vertical_Post',
			],
			'mn-videoplayer' => [
				'title' => esc_html__( 'MN Video Player', 'mn-elements' ),
				'description' => esc_html__( 'Single video player supporting YouTube, Vimeo, and self-hosted sources with inline or modal/popup playback and customizable play button', 'mn-elements' ),
				'icon' => 'eicon-play',
				'category' => 'media',
				'file' => 'mn-videoplayer.php',
				'class' => '\MN_Elements\Widgets\MN_Video_Player',
			],
			'mn-dsmenu' => [
				'title' => esc_html__( 'MN Desktop Menu', 'mn-elements' ),
				'description' => esc_html__( 'Desktop and tablet navigation menu with horizontal/vertical layout, pointer animations, dropdown submenus, and keyboard accessibility', 'mn-elements' ),
				'icon' => 'eicon-nav-menu',
				'category' => 'general',
				'file' => 'mn-dsmenu.php',
				'class' => '\MN_Elements\Widgets\MN_Desktop_Menu',
			],
		];
	}



	/**
	 * Render admin page
	 *
	 * @since 1.4.1
	 * @access public
	 */
	public function render_admin_page() {
		$active_widgets = get_option( 'mn_elements_active_widgets', array_keys( $this->available_widgets ) );
		?>
		<div class="wrap mn-elements-admin">
			<h1><?php echo esc_html__( 'MN Elements Manager', 'mn-elements' ); ?></h1>
			<p><?php echo esc_html__( 'Enable or disable widgets to optimize your site performance. Only enabled widgets will be loaded.', 'mn-elements' ); ?></p>

			<form id="mn-elements-settings-form" method="post">
				<?php wp_nonce_field( 'mn_elements_admin_nonce', 'mn_elements_nonce' ); ?>
				
				<div class="mn-elements-header-actions">
					<button type="button" id="mn-enable-all" class="button"><?php echo esc_html__( 'Enable All', 'mn-elements' ); ?></button>
					<button type="button" id="mn-disable-all" class="button"><?php echo esc_html__( 'Disable All', 'mn-elements' ); ?></button>
					<button type="submit" class="button button-primary"><?php echo esc_html__( 'Save Settings', 'mn-elements' ); ?></button>
				</div>

				<div class="mn-elements-widgets-grid">
					<?php foreach ( $this->get_widgets_by_category() as $category => $widgets ) : ?>
						<div class="mn-elements-category">
							<h2><?php echo esc_html( $this->get_category_title( $category ) ); ?></h2>
							<div class="mn-elements-widgets-row">
								<?php foreach ( $widgets as $widget_key => $widget ) : ?>
									<div class="mn-elements-widget-card">
										<div class="mn-elements-widget-header">
											<div class="mn-elements-widget-icon">
												<i class="<?php echo esc_attr( $widget['icon'] ); ?>"></i>
											</div>
											<div class="mn-elements-widget-info">
												<h3><?php echo esc_html( $widget['title'] ); ?></h3>
												<p><?php echo esc_html( $widget['description'] ); ?></p>
											</div>
											<div class="mn-elements-widget-toggle">
												<label class="mn-elements-switch">
													<input type="checkbox" 
														   name="active_widgets[]" 
														   value="<?php echo esc_attr( $widget_key ); ?>"
														   <?php checked( in_array( $widget_key, $active_widgets ) ); ?>>
													<span class="mn-elements-slider"></span>
												</label>
											</div>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>

				<div class="mn-elements-footer-actions">
					<button type="submit" class="button button-primary button-large"><?php echo esc_html__( 'Save Settings', 'mn-elements' ); ?></button>
				</div>
			</form>
		</div>
		<?php
	}

	/**
	 * Get widgets grouped by category
	 *
	 * @since 1.4.1
	 * @access private
	 * @return array
	 */
	private function get_widgets_by_category() {
		$categorized = [];
		foreach ( $this->available_widgets as $key => $widget ) {
			$category = $widget['category'];
			$categorized[ $category ][ $key ] = $widget;
		}
		return $categorized;
	}

	/**
	 * Get category title
	 *
	 * @since 1.4.1
	 * @access private
	 * @param string $category
	 * @return string
	 */
	private function get_category_title( $category ) {
		$titles = [
			'basic' => esc_html__( 'Basic Widgets', 'mn-elements' ),
			'content' => esc_html__( 'Content Widgets', 'mn-elements' ),
			'media' => esc_html__( 'Media Widgets', 'mn-elements' ),
		];
		return $titles[ $category ] ?? ucfirst( $category );
	}

	/**
	 * Save settings via AJAX
	 *
	 * @since 1.4.1
	 * @access public
	 */
	public function save_settings() {
		// Check if this is an AJAX request
		if ( ! wp_doing_ajax() ) {
			wp_die( esc_html__( 'Invalid request', 'mn-elements' ) );
		}

		// Verify nonce
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'mn_elements_admin_nonce' ) ) {
			wp_send_json_error( [
				'message' => esc_html__( 'Security check failed', 'mn-elements' )
			] );
		}

		// Check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( [
				'message' => esc_html__( 'You do not have permission to perform this action', 'mn-elements' )
			] );
		}

		// Get active widgets from POST data
		$active_widgets = isset( $_POST['active_widgets'] ) ? $_POST['active_widgets'] : [];
		
		// Ensure it's an array
		if ( ! is_array( $active_widgets ) ) {
			$active_widgets = [];
		}

		// Sanitize and validate widgets
		$valid_widgets = [];
		foreach ( $active_widgets as $widget ) {
			$widget = sanitize_text_field( $widget );
			if ( array_key_exists( $widget, $this->available_widgets ) ) {
				$valid_widgets[] = $widget;
			}
		}

		// Save to database
		$result = update_option( 'mn_elements_active_widgets', $valid_widgets );

		if ( $result !== false ) {
			wp_send_json_success( [
				'message' => esc_html__( 'Settings saved successfully!', 'mn-elements' ),
				'active_count' => count( $valid_widgets ),
				'total_count' => count( $this->available_widgets ),
				'active_widgets' => $valid_widgets,
			] );
		} else {
			wp_send_json_error( [
				'message' => esc_html__( 'Failed to save settings. Please try again.', 'mn-elements' )
			] );
		}
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
Element_Manager::instance();
