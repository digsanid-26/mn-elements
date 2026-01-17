<?php
/**
 * MN Elements Assets
 *
 * @package   mn-elements
 * @author    Manakreatif
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'MN_Elements_Assets' ) ) {

	/**
	 * Define MN_Elements_Assets class
	 */
	class MN_Elements_Assets {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Localize data
		 *
		 * @var array
		 */
		public $elements_data = array();

		/**
		 * Constructor for the class
		 */
		public function init() {
			add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'editor_styles' ) );
			add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'enqueue_styles' ) );
			add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 );
			add_action( 'elementor/frontend/before_register_scripts', array( $this, 'register_scripts' ) );
			add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'editor_scripts' ) );
		}

		/**
		 * Register plugin scripts
		 *
		 * @return void
		 */
		public function register_scripts() {
			wp_register_script(
				'mn-counter',
				mn_elements()->plugin_url( 'assets/js/mn-counter.js' ),
				array( 'jquery' ),
				mn_elements()->get_version(),
				true
			);

			wp_register_script(
				'mn-video-playlist',
				mn_elements()->plugin_url( 'assets/js/mn-video-playlist.js' ),
				array( 'jquery' ),
				mn_elements()->get_version(),
				true
			);

			wp_register_script(
				'mn-gallery',
				mn_elements()->plugin_url( 'assets/js/mn-gallery.js' ),
				array( 'jquery' ),
				mn_elements()->get_version(),
				true
			);

			// Register Swiper library
			wp_register_style(
				'swiper',
				'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css',
				array(),
				'8.4.7'
			);

			wp_register_script(
				'swiper',
				'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js',
				array(),
				'8.4.7',
				true
			);

			wp_register_script(
				'mn-slideswipe',
				mn_elements()->plugin_url( 'assets/js/mn-slideswipe.js' ),
				array( 'jquery', 'swiper' ),
				mn_elements()->get_version(),
				true
			);

			wp_register_script(
				'mn-woocart',
				mn_elements()->plugin_url( 'assets/js/mn-woocart.js' ),
				array( 'jquery' ),
				mn_elements()->get_version(),
				true
			);

			wp_register_script(
				'mn-image-comparison',
				mn_elements()->plugin_url( 'assets/js/mn-image-comparison.js' ),
				array( 'jquery' ),
				mn_elements()->get_version(),
				true
			);

			wp_register_script(
				'mn-view',
				mn_elements()->plugin_url( 'assets/js/mn-view.js' ),
				array( 'jquery' ),
				mn_elements()->get_version(),
				true
			);

			wp_register_script(
				'mn-dynamic-tabs',
				mn_elements()->plugin_url( 'assets/js/mn-dynamic-tabs.js' ),
				array( 'jquery' ),
				mn_elements()->get_version(),
				true
			);

			wp_register_script(
				'mn-logolist',
				mn_elements()->plugin_url( 'assets/js/mn-logolist.js' ),
				array( 'jquery' ),
				mn_elements()->get_version(),
				true
			);

			wp_register_script(
				'mn-dual-slider',
				mn_elements()->plugin_url( 'assets/js/mn-dual-slider.js' ),
				array( 'jquery' ),
				mn_elements()->get_version(),
				true
			);

			wp_register_script(
				'mn-social-reviews',
				mn_elements()->plugin_url( 'assets/js/mn-social-reviews.js' ),
				array( 'jquery' ),
				mn_elements()->get_version(),
				true
			);

			wp_register_script(
				'mn-sidepanel',
				mn_elements()->plugin_url( 'assets/js/mn-sidepanel.js' ),
				array( 'jquery' ),
				mn_elements()->get_version(),
				true
			);

			wp_register_script(
				'mn-hero-slider',
				mn_elements()->plugin_url( 'assets/js/mn-hero-slider.js' ),
				array( 'jquery' ),
				mn_elements()->get_version(),
				true
			);

			wp_register_script(
				'mn-wachat',
				mn_elements()->plugin_url( 'assets/js/mn-wachat.js' ),
				array( 'jquery' ),
				mn_elements()->get_version(),
				true
			);

			wp_register_script(
				'mn-instafeed',
				mn_elements()->plugin_url( 'assets/js/mn-instafeed.js' ),
				array( 'jquery' ),
				mn_elements()->get_version(),
				true
			);

			wp_register_style(
				'mn-instafeed',
				mn_elements()->plugin_url( 'assets/css/mn-instafeed.css' ),
				array( 'mn-elements-frontend' ),
				mn_elements()->get_version()
			);

			wp_register_script(
				'mn-gootesti',
				mn_elements()->plugin_url( 'assets/js/mn-gootesti.js' ),
				array( 'jquery' ),
				mn_elements()->get_version(),
				true
			);

			wp_register_style(
				'mn-gootesti',
				mn_elements()->plugin_url( 'assets/css/mn-gootesti.css' ),
				array( 'mn-elements-frontend' ),
				mn_elements()->get_version()
			);

			wp_register_style(
				'mn-author',
				mn_elements()->plugin_url( 'assets/css/mn-author.css' ),
				array( 'mn-elements-frontend' ),
				mn_elements()->get_version()
			);

			wp_register_style(
				'mn-postnav',
				mn_elements()->plugin_url( 'assets/css/mn-postnav.css' ),
				array( 'mn-elements-frontend' ),
				mn_elements()->get_version()
			);

			wp_register_script(
				'mn-testimony',
				mn_elements()->plugin_url( 'assets/js/mn-testimony.js' ),
				array( 'jquery' ),
				mn_elements()->get_version(),
				true
			);

			wp_register_style(
				'mn-testimony',
				mn_elements()->plugin_url( 'assets/css/mn-testimony.css' ),
				array( 'mn-elements-frontend' ),
				mn_elements()->get_version()
			);

			wp_register_script(
				'mn-accordion',
				mn_elements()->plugin_url( 'assets/js/mn-accordion.js' ),
				array( 'jquery' ),
				mn_elements()->get_version(),
				true
			);

			wp_register_style(
				'mn-accordion',
				mn_elements()->plugin_url( 'assets/css/mn-accordion.css' ),
				array( 'mn-elements-frontend' ),
				mn_elements()->get_version()
			);

			// MN Posts script and style
			wp_register_script(
				'mn-posts-script',
				mn_elements()->plugin_url( 'assets/js/mn-posts.js' ),
				array( 'jquery' ),
				mn_elements()->get_version(),
				true
			);

			wp_register_style(
				'mn-posts-style',
				mn_elements()->plugin_url( 'assets/css/mn-posts.css' ),
				array( 'mn-elements-frontend' ),
				mn_elements()->get_version()
			);
		}

		/**
		 * Enqueue public-facing stylesheets.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function enqueue_styles() {

			wp_enqueue_style(
				'mn-elements-frontend',
				mn_elements()->plugin_url( 'assets/css/mn-elements-frontend.css' ),
				false,
				mn_elements()->get_version()
			);

			wp_enqueue_style(
				'mn-button',
				mn_elements()->plugin_url( 'assets/css/mn-button.css' ),
				array( 'mn-elements-frontend' ),
				mn_elements()->get_version()
			);


			wp_enqueue_style(
				'mn-posts',
				mn_elements()->plugin_url( 'assets/css/mn-posts.css' ),
				array( 'mn-elements-frontend' ),
				mn_elements()->get_version()
			);

			wp_enqueue_style(
				'mn-counter',
				mn_elements()->plugin_url( 'assets/css/mn-counter.css' ),
				array( 'mn-elements-frontend' ),
				mn_elements()->get_version()
			);

			wp_enqueue_style(
				'mn-running-post',
				mn_elements()->plugin_url( 'assets/css/mn-running-post.css' ),
				array( 'mn-elements-frontend' ),
				mn_elements()->get_version()
			);

			wp_enqueue_style(
				'mn-infolist',
				mn_elements()->plugin_url( 'assets/css/mn-infolist.css' ),
				array( 'mn-elements-frontend' ),
				mn_elements()->get_version()
			);

			wp_enqueue_style(
				'mn-office-hours',
				mn_elements()->plugin_url( 'assets/css/mn-office-hours.css' ),
				array( 'mn-elements-frontend' ),
				mn_elements()->get_version()
			);

			wp_enqueue_style(
				'mn-gallery',
				mn_elements()->plugin_url( 'assets/css/mn-gallery.css' ),
				array( 'mn-elements-frontend' ),
				mn_elements()->get_version()
			);

			wp_enqueue_style(
				'mn-video-playlist',
				mn_elements()->plugin_url( 'assets/css/mn-video-playlist.css' ),
				array( 'mn-elements-frontend' ),
				mn_elements()->get_version()
			);

			wp_enqueue_style(
				'mn-download',
				mn_elements()->plugin_url( 'assets/css/mn-download.css' ),
				array( 'mn-elements-frontend' ),
				mn_elements()->get_version()
			);

			wp_enqueue_style(
				'mn-slideswipe',
				mn_elements()->plugin_url( 'assets/css/mn-slideswipe.css' ),
				array( 'mn-elements-frontend', 'swiper' ),
				mn_elements()->get_version()
			);

			wp_enqueue_style(
				'mn-woocart',
				mn_elements()->plugin_url( 'assets/css/mn-woocart.css' ),
				array( 'mn-elements-frontend' ),
				mn_elements()->get_version()
			);

			wp_enqueue_style(
				'mn-image-comparison',
				mn_elements()->plugin_url( 'assets/css/mn-image-comparison.css' ),
				array( 'mn-elements-frontend' ),
				mn_elements()->get_version()
			);

			wp_enqueue_style(
				'mn-view',
				mn_elements()->plugin_url( 'assets/css/mn-view.css' ),
				array( 'mn-elements-frontend' ),
				mn_elements()->get_version()
			);

			wp_enqueue_style(
				'mn-dynamic-tabs',
				mn_elements()->plugin_url( 'assets/css/mn-dynamic-tabs.css' ),
				array( 'mn-elements-frontend' ),
				mn_elements()->get_version()
			);

			wp_enqueue_style(
				'mn-logolist',
				mn_elements()->plugin_url( 'assets/css/mn-logolist.css' ),
				array( 'mn-elements-frontend' ),
				mn_elements()->get_version()
			);

			wp_enqueue_style(
				'mn-dual-slider',
				mn_elements()->plugin_url( 'assets/css/mn-dual-slider.css' ),
				array( 'mn-elements-frontend' ),
				mn_elements()->get_version()
			);

			wp_enqueue_style(
				'mn-heading',
				mn_elements()->plugin_url( 'assets/css/mn-heading.css' ),
				array( 'mn-elements-frontend' ),
				mn_elements()->get_version()
			);

			wp_enqueue_style(
				'mn-social-reviews',
				mn_elements()->plugin_url( 'assets/css/mn-social-reviews.css' ),
				array( 'mn-elements-frontend' ),
				mn_elements()->get_version()
			);

			wp_enqueue_style(
				'mn-sidepanel',
				mn_elements()->plugin_url( 'assets/css/mn-sidepanel.css' ),
				array( 'mn-elements-frontend' ),
				mn_elements()->get_version()
			);

			wp_enqueue_style(
				'mn-hero-slider',
				mn_elements()->plugin_url( 'assets/css/mn-hero-slider.css' ),
				array( 'mn-elements-frontend' ),
				mn_elements()->get_version()
			);
			wp_enqueue_style(
				'mn-image-or-icon',
				mn_elements()->plugin_url( 'assets/css/mn-image-or-icon.css' ),
				array( 'mn-elements-frontend' ),
				mn_elements()->get_version()
			);

			wp_enqueue_style(
				'mn-wachat',
				mn_elements()->plugin_url( 'assets/css/mn-wachat.css' ),
				array( 'mn-elements-frontend' ),
				mn_elements()->get_version()
			);

			wp_enqueue_style(
				'mn-instafeed',
				mn_elements()->plugin_url( 'assets/css/mn-instafeed.css' ),
				array( 'mn-elements-frontend' ),
				mn_elements()->get_version()
			);

			wp_enqueue_style(
				'mn-gootesti',
				mn_elements()->plugin_url( 'assets/css/mn-gootesti.css' ),
				array( 'mn-elements-frontend' ),
				mn_elements()->get_version()
			);

			wp_enqueue_style(
				'mn-author',
				mn_elements()->plugin_url( 'assets/css/mn-author.css' ),
				array( 'mn-elements-frontend' ),
				mn_elements()->get_version()
			);

			wp_enqueue_style(
				'mn-postnav',
				mn_elements()->plugin_url( 'assets/css/mn-postnav.css' ),
				array( 'mn-elements-frontend' ),
				mn_elements()->get_version()
			);

			wp_enqueue_style(
				'mn-accordion',
				mn_elements()->plugin_url( 'assets/css/mn-accordion.css' ),
				array( 'mn-elements-frontend' ),
				mn_elements()->get_version()
			);
		}

		/**
		 * Enqueue plugin scripts only with elementor scripts
		 *
		 * @return void
		 */
		public function enqueue_scripts() {
		// Enqueue YouTube API for video playlist
		wp_enqueue_script( 'youtube-api', 'https://www.youtube.com/iframe_api', array(), null, true );
		
		// Enqueue video playlist script
		wp_enqueue_script( 'mn-video-playlist' );
		
		// Enqueue counter script
		wp_enqueue_script( 'mn-counter' );
		
		// Enqueue slideswipe script
		wp_enqueue_script( 'mn-slideswipe' );
		
		// Enqueue woocart script
		wp_enqueue_script( 'mn-woocart' );
		
		// Enqueue image comparison script
		wp_enqueue_script( 'mn-image-comparison' );
		
		// Enqueue view script
		wp_enqueue_script( 'mn-view' );
		
		// Enqueue dynamic tabs script
		wp_enqueue_script( 'mn-dynamic-tabs' );
		
		// Enqueue logolist script
		wp_enqueue_script( 'mn-logolist' );
		
		// Enqueue dual slider script
		wp_enqueue_script( 'mn-dual-slider' );
		
		// Enqueue social reviews script
		wp_enqueue_script( 'mn-social-reviews' );
		
		// Enqueue sidepanel script
		wp_enqueue_script( 'mn-sidepanel' );
		
		// Enqueue hero slider script
		wp_enqueue_script( 'mn-hero-slider' );

		// Enqueue gallery script
		wp_enqueue_script( 'mn-gallery' );
		
		// Enqueue wachat script
		wp_enqueue_script( 'mn-wachat' );

		// Enqueue instafeed script
		wp_enqueue_script( 'mn-instafeed' );

		// Enqueue gootesti script
		wp_enqueue_script( 'mn-gootesti' );

		// Enqueue MN Posts script
		wp_enqueue_script( 'mn-posts-script' );

		// Enqueue accordion script
		wp_enqueue_script( 'mn-accordion' );
		
		// Localize MN Posts script for AJAX
		wp_localize_script(
			'mn-posts-script',
			'mn_posts_params',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'mn_posts_nonce' ),
			)
		);

	}

	/**
	 * Enqueue elementor editor-related styles
	 *
	 * @return void
	 */
	public function editor_styles() {

		wp_enqueue_style(
			'mn-elements-editor',
			mn_elements()->plugin_url( 'assets/css/mn-elements-editor.css' ),
			array(),
			mn_elements()->get_version()
		);
	}

	/**
	 * Enqueue editor scripts
	 *
	 * @return void
	 */
	public function editor_scripts() {
		wp_enqueue_script(
			'mn-elements-editor',
			mn_elements()->plugin_url( 'assets/js/mn-elements-editor.js' ),
			array( 'jquery' ),
			mn_elements()->get_version(),
			true
		);

		// Enqueue MN Gallery editor script
		wp_enqueue_script(
			'mn-gallery-editor',
			mn_elements()->plugin_url( 'assets/js/mn-gallery-editor.js' ),
			array( 'jquery', 'elementor-editor' ),
			mn_elements()->get_version(),
			true
		);

		// Localize script with nonce for AJAX
		wp_localize_script(
			'mn-gallery-editor',
			'mnGalleryEditor',
			array(
				'nonce' => wp_create_nonce( 'mn-gallery-editor' ),
			)
		);

		// Enqueue MN Logolist editor script
		wp_enqueue_script(
			'mn-logolist-editor',
			mn_elements()->plugin_url( 'assets/js/mn-logolist-editor.js' ),
			array( 'jquery', 'elementor-editor' ),
			mn_elements()->get_version(),
			true
		);

		// Localize script with nonce for AJAX
		wp_localize_script(
			'mn-logolist-editor',
			'mnLogolistEditor',
			array(
				'nonce' => wp_create_nonce( 'mn-logolist-editor' ),
			)
		);

		// Enqueue MN Video Playlist editor script
		wp_enqueue_script(
			'mn-video-playlist-editor',
			mn_elements()->plugin_url( 'assets/js/mn-video-playlist-editor.js' ),
			array( 'jquery', 'elementor-editor' ),
			mn_elements()->get_version(),
			true
		);

		// Localize script with data for AJAX
		wp_localize_script(
			'mn-video-playlist-editor',
			'mnVideoPlaylistEditor',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce' => wp_create_nonce( 'mn_video_playlist_nonce' ),
			)
		);
	}

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
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

function mn_elements_assets() {
	return MN_Elements_Assets::get_instance();
}
