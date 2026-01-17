<?php
namespace MN_Elements\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * MN Video Playlist Widget
 *
 * YouTube video playlist widget with multiple layout options
 *
 * @since 1.0.5
 */
class MN_Video_Playlist extends Widget_Base {

	/**
	 * Constructor
	 */
	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		
		// Register AJAX handler for cache clearing
		add_action( 'wp_ajax_mn_video_playlist_clear_cache', [ $this, 'ajax_clear_cache' ] );
	}

	/**
	 * AJAX handler for clearing YouTube cache
	 */
	public function ajax_clear_cache() {
		// Verify nonce for security
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'mn_video_playlist_nonce' ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}

		$source_type = isset( $_POST['source_type'] ) ? sanitize_text_field( $_POST['source_type'] ) : '';
		$id = isset( $_POST['id'] ) ? sanitize_text_field( $_POST['id'] ) : '';

		if ( empty( $id ) ) {
			wp_send_json_error( 'Missing ID' );
		}

		// Check if helper class and method exist
		if ( class_exists( '\MN_Elements\Helpers\MN_YouTube_API' ) && method_exists( '\MN_Elements\Helpers\MN_YouTube_API', 'clear_cache' ) ) {
			$type = ( $source_type === 'youtube_channel' ) ? 'channel' : 'playlist';
			\MN_Elements\Helpers\MN_YouTube_API::clear_cache( $id, $type );
			wp_send_json_success( 'Cache cleared' );
		}

		wp_send_json_error( 'Helper class not available' );
	}

	/**
	 * Get widget name.
	 */
	public function get_name() {
		return 'mn-video-playlist';
	}

	/**
	 * Get widget title.
	 */
	public function get_title() {
		return esc_html__( 'MN Video Playlist', 'mn-elements' );
	}

	/**
	 * Get widget icon.
	 */
	public function get_icon() {
		return 'eicon-video-playlist';
	}

	/**
	 * Get widget categories.
	 */
	public function get_categories() {
		return [ 'mn-elements' ];
	}

	/**
	 * Get widget keywords.
	 */
	public function get_keywords() {
		return [ 'video', 'playlist', 'youtube', 'player', 'mn', 'dark', 'light', 'theme' ];
	}

	/**
	 * Get script dependencies.
	 */
	public function get_script_depends() {
		return [ 'mn-video-playlist' ];
	}

	/**
	 * Register widget controls.
	 */
	protected function register_controls() {
		$this->register_content_controls();
		$this->register_style_controls();
	}

	/**
	 * Register content controls.
	 */
	protected function register_content_controls() {
		// Video Management Section
		$this->start_controls_section(
			'section_video_management',
			[
				'label' => esc_html__( 'Video Management', 'mn-elements' ),
			]
		);

		$this->add_control(
			'source_type',
			[
				'label' => esc_html__( 'Source', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'manual',
				'options' => [
					'manual' => esc_html__( 'Manual', 'mn-elements' ),
					'dynamic' => esc_html__( 'Dynamic (Posts)', 'mn-elements' ),
					'youtube_playlist' => esc_html__( 'YouTube Playlist', 'mn-elements' ),
					'youtube_channel' => esc_html__( 'YouTube Channel', 'mn-elements' ),
				],
				'description' => esc_html__( 'Choose video source: Manual entry, WordPress posts, YouTube Playlist, or YouTube Channel', 'mn-elements' ),
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'video_title',
			[
				'label' => esc_html__( 'Video Title', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Video Title', 'mn-elements' ),
				'placeholder' => esc_html__( 'Enter video title', 'mn-elements' ),
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'youtube_url',
			[
				'label' => esc_html__( 'YouTube URL', 'mn-elements' ),
				'type' => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://www.youtube.com/watch?v=VIDEO_ID', 'mn-elements' ),
				'description' => esc_html__( 'Enter YouTube video URL', 'mn-elements' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'video_description',
			[
				'label' => esc_html__( 'Video Description', 'mn-elements' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Enter video description here.', 'mn-elements' ),
				'placeholder' => esc_html__( 'Enter video description', 'mn-elements' ),
				'rows' => 3,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'video_duration',
			[
				'label' => esc_html__( 'Duration', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '00:00', 'mn-elements' ),
				'placeholder' => esc_html__( '05:30', 'mn-elements' ),
				'description' => esc_html__( 'Video duration (optional)', 'mn-elements' ),
			]
		);

		$this->add_control(
			'video_list',
			[
				'label' => esc_html__( 'Video Playlist', 'mn-elements' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'video_title' => esc_html__( 'First Video', 'mn-elements' ),
						'youtube_url' => [
							'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
						],
						'video_description' => esc_html__( 'This is the first video in the playlist.', 'mn-elements' ),
						'video_duration' => '03:32',
					],
					[
						'video_title' => esc_html__( 'Second Video', 'mn-elements' ),
						'youtube_url' => [
							'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
						],
						'video_description' => esc_html__( 'This is the second video in the playlist.', 'mn-elements' ),
						'video_duration' => '04:15',
					],
					[
						'video_title' => esc_html__( 'Third Video', 'mn-elements' ),
						'youtube_url' => [
							'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
						],
						'video_description' => esc_html__( 'This is the third video in the playlist.', 'mn-elements' ),
						'video_duration' => '02:48',
					],
				],
				'title_field' => '{{{ video_title }}}',
				'condition' => [
					'source_type' => 'manual',
				],
			]
		);

		// Dynamic Query Controls
		$this->add_control(
			'posts_per_page',
			[
				'label' => esc_html__( 'Posts Per Page', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 6,
				'min' => 1,
				'max' => 100,
				'condition' => [
					'source_type' => 'dynamic',
				],
			]
		);

		$this->add_control(
			'post_type',
			[
				'label' => esc_html__( 'Post Type', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'post',
				'options' => $this->get_post_types(),
				'condition' => [
					'source_type' => 'dynamic',
				],
			]
		);

		$this->add_control(
			'taxonomy',
			[
				'label' => esc_html__( 'Taxonomy', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => $this->get_taxonomies(),
				'condition' => [
					'source_type' => 'dynamic',
				],
			]
		);

		$this->add_control(
			'term_ids',
			[
				'label' => esc_html__( 'Term IDs', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( '1,2,3', 'mn-elements' ),
				'description' => esc_html__( 'Enter term IDs separated by commas. Leave empty to show all posts.', 'mn-elements' ),
				'condition' => [
					'source_type' => 'dynamic',
					'taxonomy!' => '',
				],
			]
		);

		$this->add_control(
			'orderby',
			[
				'label' => esc_html__( 'Order By', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'date' => esc_html__( 'Date', 'mn-elements' ),
					'title' => esc_html__( 'Title', 'mn-elements' ),
					'menu_order' => esc_html__( 'Menu Order', 'mn-elements' ),
					'rand' => esc_html__( 'Random', 'mn-elements' ),
				],
				'condition' => [
					'source_type' => 'dynamic',
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label' => esc_html__( 'Order', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => [
					'ASC' => esc_html__( 'Ascending', 'mn-elements' ),
					'DESC' => esc_html__( 'Descending', 'mn-elements' ),
				],
				'condition' => [
					'source_type' => 'dynamic',
				],
			]
		);

		// Custom Fields for Dynamic Source
		$this->add_control(
			'custom_field_heading',
			[
				'label' => esc_html__( 'Custom Fields', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'source_type' => 'dynamic',
				],
			]
		);

		$this->add_control(
			'title_field',
			[
				'label' => esc_html__( 'Title Field', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'post_title',
				'options' => [
					'post_title' => esc_html__( 'Post Title', 'mn-elements' ),
					'custom_field' => esc_html__( 'Custom Field', 'mn-elements' ),
				],
				'condition' => [
					'source_type' => 'dynamic',
				],
			]
		);

		$this->add_control(
			'title_custom_field',
			[
				'label' => esc_html__( 'Title Custom Field Name', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'video_title', 'mn-elements' ),
				'description' => esc_html__( 'Enter custom field name for video title (supports JetEngine and ACF)', 'mn-elements' ),
				'condition' => [
					'source_type' => 'dynamic',
					'title_field' => 'custom_field',
				],
			]
		);

		$this->add_control(
			'youtube_url_field',
			[
				'label' => esc_html__( 'YouTube URL Custom Field Name', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'youtube_url', 'mn-elements' ),
				'description' => esc_html__( 'Enter custom field name for YouTube URL (supports JetEngine and ACF)', 'mn-elements' ),
				'condition' => [
					'source_type' => 'dynamic',
				],
			]
		);

		$this->add_control(
			'description_field',
			[
				'label' => esc_html__( 'Description Field', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'post_excerpt',
				'options' => [
					'post_excerpt' => esc_html__( 'Post Excerpt', 'mn-elements' ),
					'post_content' => esc_html__( 'Post Content', 'mn-elements' ),
					'custom_field' => esc_html__( 'Custom Field', 'mn-elements' ),
				],
				'condition' => [
					'source_type' => 'dynamic',
				],
			]
		);

		$this->add_control(
			'description_custom_field',
			[
				'label' => esc_html__( 'Description Custom Field Name', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'video_description', 'mn-elements' ),
				'description' => esc_html__( 'Enter custom field name for video description (supports JetEngine and ACF)', 'mn-elements' ),
				'condition' => [
					'source_type' => 'dynamic',
					'description_field' => 'custom_field',
				],
			]
		);

		$this->add_control(
			'duration_custom_field',
			[
				'label' => esc_html__( 'Duration Custom Field Name', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'video_duration', 'mn-elements' ),
				'description' => esc_html__( 'Enter custom field name for video duration (supports JetEngine and ACF)', 'mn-elements' ),
				'condition' => [
					'source_type' => 'dynamic',
				],
			]
		);

		// YouTube API Settings
		$this->add_control(
			'youtube_api_heading',
			[
				'label' => esc_html__( 'YouTube API Settings', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'source_type' => [ 'youtube_playlist', 'youtube_channel' ],
				],
			]
		);

		$this->add_control(
			'youtube_api_key',
			[
				'label' => esc_html__( 'YouTube API Key', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter your YouTube API Key', 'mn-elements' ),
				'description' => sprintf(
					'%s <a href="https://console.developers.google.com/" target="_blank">%s</a>',
					esc_html__( 'Get your API key from', 'mn-elements' ),
					esc_html__( 'Google Cloud Console', 'mn-elements' )
				),
				'condition' => [
					'source_type' => [ 'youtube_playlist', 'youtube_channel' ],
				],
			]
		);

		$this->add_control(
			'youtube_playlist_id',
			[
				'label' => esc_html__( 'YouTube Playlist ID', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'PLxxxxxxxxxxxxxxxxxxxxxxxx', 'mn-elements' ),
				'description' => esc_html__( 'Enter YouTube Playlist ID (found in playlist URL)', 'mn-elements' ),
				'condition' => [
					'source_type' => 'youtube_playlist',
				],
			]
		);

		$this->add_control(
			'youtube_channel_id',
			[
				'label' => esc_html__( 'YouTube Channel ID', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'UCxxxxxxxxxxxxxxxxxxxxxxxx', 'mn-elements' ),
				'description' => esc_html__( 'Enter YouTube Channel ID (found in channel URL)', 'mn-elements' ),
				'condition' => [
					'source_type' => 'youtube_channel',
				],
			]
		);

		$this->add_control(
			'youtube_max_results',
			[
				'label' => esc_html__( 'Max Videos', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 10,
				'min' => 1,
				'max' => 50,
				'description' => esc_html__( 'Maximum number of videos to fetch from YouTube', 'mn-elements' ),
				'condition' => [
					'source_type' => [ 'youtube_playlist', 'youtube_channel' ],
				],
			]
		);

		$this->add_control(
			'youtube_cache_duration',
			[
				'label' => esc_html__( 'Cache Duration', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => '21600',
				'options' => [
					'3600' => esc_html__( '1 Hour', 'mn-elements' ),
					'21600' => esc_html__( '6 Hours', 'mn-elements' ),
					'43200' => esc_html__( '12 Hours', 'mn-elements' ),
					'86400' => esc_html__( '24 Hours', 'mn-elements' ),
				],
				'description' => esc_html__( 'How long to cache YouTube data before fetching new data', 'mn-elements' ),
				'condition' => [
					'source_type' => [ 'youtube_playlist', 'youtube_channel' ],
				],
			]
		);

		$this->add_control(
			'youtube_clear_cache',
			[
				'label' => esc_html__( 'Clear Cache', 'mn-elements' ),
				'type' => Controls_Manager::BUTTON,
				'text' => esc_html__( 'Clear YouTube Cache', 'mn-elements' ),
				'description' => esc_html__( 'Click to force refresh YouTube data (useful after adding new videos)', 'mn-elements' ),
				'condition' => [
					'source_type' => [ 'youtube_playlist', 'youtube_channel' ],
				],
			]
		);

		// YouTube Playlist Ordering Controls
		$this->add_control(
			'youtube_order_heading',
			[
				'label' => esc_html__( 'Playlist Ordering', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'source_type' => 'youtube_playlist',
				],
			]
		);

		$this->add_control(
			'youtube_playlist_orderby',
			[
				'label' => esc_html__( 'Order By', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default (Playlist Order)', 'mn-elements' ),
					'title' => esc_html__( 'Title (A-Z)', 'mn-elements' ),
					'title_desc' => esc_html__( 'Title (Z-A)', 'mn-elements' ),
					'published' => esc_html__( 'Published Date (Newest)', 'mn-elements' ),
					'published_desc' => esc_html__( 'Published Date (Oldest)', 'mn-elements' ),
				],
				'description' => esc_html__( 'Sort videos in playlist by selected criteria', 'mn-elements' ),
				'condition' => [
					'source_type' => 'youtube_playlist',
				],
			]
		);

		$this->end_controls_section();

		// Layout Section
		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Layout', 'mn-elements' ),
			]
		);

		$this->add_control(
			'playlist_title',
			[
				'label' => esc_html__( 'Playlist Title', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'My Video Playlist', 'mn-elements' ),
				'placeholder' => esc_html__( 'Enter playlist title', 'mn-elements' ),
				'description' => esc_html__( 'Enter a title for your video playlist', 'mn-elements' ),
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label' => esc_html__( 'Title HTML Tag', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'h2',
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
				],
				'condition' => [
					'playlist_title!' => '',
				],
			]
		);

		$this->add_control(
			'layout_type',
			[
				'label' => esc_html__( 'Layout Type', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'top',
				'options' => [
					'top' => esc_html__( 'Video Top - Playlist Bottom (Horizontal)', 'mn-elements' ),
					'left' => esc_html__( 'Video Left 75% - Playlist Right 25%', 'mn-elements' ),
					'right' => esc_html__( 'Video Right 75% - Playlist Left 25%', 'mn-elements' ),
					'carousel' => esc_html__( 'Loop Carousel with Modal', 'mn-elements' ),
				],
				'description' => esc_html__( 'Choose the layout arrangement for video player and playlist', 'mn-elements' ),
			]
		);

		$this->add_control(
			'show_playlist_title',
			[
				'label' => esc_html__( 'Show Video Titles in Playlist', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_playlist_description',
			[
				'label' => esc_html__( 'Show Video Descriptions in Playlist', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_playlist_duration',
			[
				'label' => esc_html__( 'Show Video Duration in Playlist', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'autoplay_next',
			[
				'label' => esc_html__( 'Autoplay Next Video', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'description' => esc_html__( 'Automatically play next video when current video ends', 'mn-elements' ),
			]
		);

		$this->add_control(
			'autoplay_first',
			[
				'label' => esc_html__( 'Autoplay First Video', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'description' => esc_html__( 'Automatically play the first video when page loads (muted for browser policy compliance)', 'mn-elements' ),
				'condition' => [
					'layout_type!' => 'carousel',
				],
			]
		);

		$this->add_control(
			'open_video_modal',
			[
				'label' => esc_html__( 'Open Video on Modal/Popup', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'description' => esc_html__( 'Open video in modal/popup when playlist item is clicked', 'mn-elements' ),
				'separator' => 'before',
				'condition' => [
					'layout_type!' => 'carousel',
				],
			]
		);

		$this->add_responsive_control(
			'modal_width',
			[
				'label' => esc_html__( 'Modal Width', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px', 'vw' ],
				'range' => [
					'%' => [
						'min' => 50,
						'max' => 100,
					],
					'px' => [
						'min' => 500,
						'max' => 1920,
					],
					'vw' => [
						'min' => 50,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 90,
				],
				'condition' => [
					'layout_type!' => 'carousel',
					'open_video_modal' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-video-modal .mn-modal-content' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'modal_max_width',
			[
				'label' => esc_html__( 'Modal Max Width', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 800,
						'max' => 1920,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 1200,
				],
				'condition' => [
					'layout_type!' => 'carousel',
					'open_video_modal' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-video-modal .mn-modal-content' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Carousel Settings
		$this->add_control(
			'carousel_heading',
			[
				'label' => esc_html__( 'Carousel Settings', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'layout_type' => 'carousel',
				],
			]
		);

		$this->add_control(
			'carousel_speed',
			[
				'label' => esc_html__( 'Animation Speed (ms)', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 30000,
				'min' => 5000,
				'max' => 60000,
				'step' => 1000,
				'description' => esc_html__( 'Animation duration in milliseconds. Higher values = slower animation.', 'mn-elements' ),
				'condition' => [
					'layout_type' => 'carousel',
				],
			]
		);

		$this->add_control(
			'carousel_pause_hover',
			[
				'label' => esc_html__( 'Pause on Hover', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'layout_type' => 'carousel',
				],
			]
		);

		$this->end_controls_section();

		// Theme Section
		$this->start_controls_section(
			'section_theme',
			[
				'label' => esc_html__( 'Theme Version', 'mn-elements' ),
			]
		);

		$this->add_control(
			'theme_version',
			[
				'label' => esc_html__( 'Theme Version', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Dark', 'mn-elements' ),
				'label_off' => esc_html__( 'Light', 'mn-elements' ),
				'default' => '',
				'description' => esc_html__( 'Toggle between light and dark theme versions', 'mn-elements' ),
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register style controls.
	 */
	protected function register_style_controls() {
		// General Style
		$this->start_controls_section(
			'section_general_style',
			[
				'label' => esc_html__( 'General', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'container_height',
			[
				'label' => esc_html__( 'Container Height', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range' => [
					'px' => [
						'min' => 300,
						'max' => 1000,
					],
					'vh' => [
						'min' => 30,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 500,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-video-playlist-container' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'container_gap',
			[
				'label' => esc_html__( 'Gap Between Video and Playlist', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-video-playlist-container' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Video Player Style
		$this->start_controls_section(
			'section_player_style',
			[
				'label' => esc_html__( 'Video Player', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'layout_type!' => 'carousel',
				],
			]
		);

		// Video Player Size Heading
		$this->add_control(
			'player_size_heading',
			[
				'label' => esc_html__( 'Video Player Size', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_responsive_control(
			'player_width',
			[
				'label' => esc_html__( 'Player Width (%)', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 50,
						'max' => 100,
					],
				],
				'default' => [
					'size' => '',
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-layout-left .mn-video-player' => 'flex: 0 0 {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-layout-right .mn-video-player' => 'flex: 0 0 {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-layout-top .mn-video-player' => 'flex: 0 0 {{SIZE}}{{UNIT}};',
				],
				'description' => esc_html__( 'Control video player width. Leave empty for default layout proportions.', 'mn-elements' ),
			]
		);

		$this->add_responsive_control(
			'player_height',
			[
				'label' => esc_html__( 'Player Height', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range' => [
					'px' => [
						'min' => 200,
						'max' => 800,
					],
					'vh' => [
						'min' => 20,
						'max' => 80,
					],
				],
				'default' => [
					'size' => '',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-video-player' => 'height: {{SIZE}}{{UNIT}};',
				],
				'description' => esc_html__( 'Set custom height for video player. Leave empty for automatic height.', 'mn-elements' ),
			]
		);

		$this->add_control(
			'player_aspect_ratio',
			[
				'label' => esc_html__( 'Aspect Ratio', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => '16-9',
				'options' => [
					'16-9' => esc_html__( '16:9 (Widescreen)', 'mn-elements' ),
					'4-3' => esc_html__( '4:3 (Standard)', 'mn-elements' ),
					'21-9' => esc_html__( '21:9 (Ultrawide)', 'mn-elements' ),
					'1-1' => esc_html__( '1:1 (Square)', 'mn-elements' ),
					'custom' => esc_html__( 'Custom', 'mn-elements' ),
				],
				'description' => esc_html__( 'Set video player aspect ratio for better responsive behavior.', 'mn-elements' ),
			]
		);

		// Video Player Style Heading
		$this->add_control(
			'player_style_heading',
			[
				'label' => esc_html__( 'Video Player Style', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'player_border',
				'selector' => '{{WRAPPER}} .mn-video-player',
			]
		);

		$this->add_control(
			'player_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-video-player' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .mn-video-player iframe' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'player_box_shadow',
				'selector' => '{{WRAPPER}} .mn-video-player',
			]
		);

		$this->end_controls_section();

		// Playlist Style
		$this->start_controls_section(
			'section_playlist_style',
			[
				'label' => esc_html__( 'Playlist', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'layout_type!' => 'carousel',
				],
			]
		);

		// Playlist Size Heading
		$this->add_control(
			'playlist_size_heading',
			[
				'label' => esc_html__( 'Playlist Size', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_responsive_control(
			'playlist_width',
			[
				'label' => esc_html__( 'Playlist Width (%)', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 20,
						'max' => 50,
					],
				],
				'default' => [
					'size' => '',
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-layout-left .mn-video-playlist' => 'flex: 0 0 {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-layout-right .mn-video-playlist' => 'flex: 0 0 {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-layout-top .mn-video-playlist' => 'flex: 0 0 {{SIZE}}{{UNIT}};',
				],
				'description' => esc_html__( 'Control playlist width. Leave empty for default layout proportions.', 'mn-elements' ),
			]
		);

		$this->add_responsive_control(
			'playlist_max_height',
			[
				'label' => esc_html__( 'Playlist Max Height', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range' => [
					'px' => [
						'min' => 200,
						'max' => 800,
					],
					'vh' => [
						'min' => 20,
						'max' => 80,
					],
				],
				'default' => [
					'size' => 480,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-playlist-items' => 'max-height: {{SIZE}}{{UNIT}};',
				],
				'description' => esc_html__( 'Maximum height for playlist scrollable area.', 'mn-elements' ),
			]
		);

		$this->add_responsive_control(
			'playlist_item_height',
			[
				'label' => esc_html__( 'Playlist Item Height', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 80,
						'max' => 200,
					],
				],
				'default' => [
					'size' => '',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-layout-left .mn-playlist-item-thumbnail' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-layout-right .mn-playlist-item-thumbnail' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-layout-top .mn-playlist-item-thumbnail' => 'height: {{SIZE}}{{UNIT}};',
				],
				'description' => esc_html__( 'Height of playlist item thumbnails. Leave empty for default.', 'mn-elements' ),
			]
		);

		// Playlist Style Heading
		$this->add_control(
			'playlist_style_heading',
			[
				'label' => esc_html__( 'Playlist Style', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'playlist_background_color',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-video-playlist' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'playlist_border',
				'selector' => '{{WRAPPER}} .mn-video-playlist',
			]
		);

		$this->add_control(
			'playlist_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-video-playlist' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'playlist_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-video-playlist' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Playlist Item Style
		$this->start_controls_section(
			'section_playlist_item_style',
			[
				'label' => esc_html__( 'Playlist Items', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'item_background_color',
			[
				'label' => esc_html__( 'Item Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-playlist-item' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'item_hover_background_color',
			[
				'label' => esc_html__( 'Item Hover Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-playlist-item:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'item_active_background_color',
			[
				'label' => esc_html__( 'Active Item Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-playlist-item.active' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'item_padding',
			[
				'label' => esc_html__( 'Item Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-playlist-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'item_margin',
			[
				'label' => esc_html__( 'Item Margin', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-playlist-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Typography Section
		$this->start_controls_section(
			'section_typography',
			[
				'label' => esc_html__( 'Typography', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'playlist_title_typography',
				'label' => esc_html__( 'Playlist Title Typography', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-video-playlist-title',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'video_title_typography',
				'label' => esc_html__( 'Video Title Typography', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-playlist-item-title',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'video_description_typography',
				'label' => esc_html__( 'Video Description Typography', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-playlist-item-description',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'video_duration_typography',
				'label' => esc_html__( 'Video Duration Typography', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-playlist-item-duration',
			]
		);

		$this->end_controls_section();

		// Colors Section
		$this->start_controls_section(
			'section_colors',
			[
				'label' => esc_html__( 'Colors', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'playlist_title_color',
			[
				'label' => esc_html__( 'Playlist Title Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-video-playlist-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'video_title_color',
			[
				'label' => esc_html__( 'Video Title Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-playlist-item-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'video_description_color',
			[
				'label' => esc_html__( 'Video Description Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-playlist-item-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'video_duration_color',
			[
				'label' => esc_html__( 'Video Duration Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-playlist-item-duration' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Modal Style Section
		$this->start_controls_section(
			'section_modal_style',
			[
				'label' => esc_html__( 'Modal', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'open_video_modal' => 'yes',
				],
			]
		);

		$this->add_control(
			'modal_overlay_color',
			[
				'label' => esc_html__( 'Overlay Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0, 0, 0, 0.9)',
				'selectors' => [
					'{{WRAPPER}} .mn-modal-overlay' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'modal_background_color',
			[
				'label' => esc_html__( 'Modal Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-video-modal .mn-modal-content' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'modal_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-video-modal .mn-modal-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'modal_box_shadow',
				'selector' => '{{WRAPPER}} .mn-video-modal .mn-modal-content',
			]
		);

		$this->add_control(
			'modal_close_button_heading',
			[
				'label' => esc_html__( 'Close Button', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'modal_close_button_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-modal-close' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'modal_close_button_bg',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0, 0, 0, 0.8)',
				'selectors' => [
					'{{WRAPPER}} .mn-modal-close' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'modal_close_button_hover_bg',
			[
				'label' => esc_html__( 'Hover Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ff0000',
				'selectors' => [
					'{{WRAPPER}} .mn-modal-close:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'modal_close_button_size',
			[
				'label' => esc_html__( 'Size', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 60,
					],
				],
				'default' => [
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-modal-close' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Get post types for select control
	 */
	private function get_post_types() {
		$post_types = get_post_types( [ 'public' => true ], 'objects' );
		$options = [];

		foreach ( $post_types as $post_type ) {
			$options[ $post_type->name ] = $post_type->label;
		}

		return $options;
	}

	/**
	 * Get taxonomies for select control
	 */
	private function get_taxonomies() {
		$taxonomies = get_taxonomies( [ 'public' => true ], 'objects' );
		$options = [
			'' => esc_html__( 'Select Taxonomy', 'mn-elements' ),
		];

		foreach ( $taxonomies as $taxonomy ) {
			$options[ $taxonomy->name ] = $taxonomy->label;
		}

		return $options;
	}

	/**
	 * Get YouTube playlist videos
	 */
	private function get_youtube_playlist_videos( $settings ) {
		// Check if helper class exists
		if ( ! class_exists( '\MN_Elements\Helpers\MN_YouTube_API' ) ) {
			return [];
		}

		$api_key = isset( $settings['youtube_api_key'] ) ? $settings['youtube_api_key'] : '';
		$playlist_id = isset( $settings['youtube_playlist_id'] ) ? $settings['youtube_playlist_id'] : '';
		$max_results = isset( $settings['youtube_max_results'] ) ? $settings['youtube_max_results'] : 10;
		$orderby = isset( $settings['youtube_playlist_orderby'] ) ? $settings['youtube_playlist_orderby'] : 'default';

		if ( empty( $api_key ) || empty( $playlist_id ) ) {
			return [];
		}

		// Force clear cache when in Elementor editor to ensure fresh data
		// This helps when switching between ordering options
		if ( defined( 'ELEMENTOR_VERSION' ) && is_admin() ) {
			// Always clear cache in admin/editor to show latest changes
			if ( method_exists( '\MN_Elements\Helpers\MN_YouTube_API', 'clear_cache' ) ) {
				\MN_Elements\Helpers\MN_YouTube_API::clear_cache( $playlist_id, 'playlist' );
			}
		}

		// Check if method exists before calling
		if ( ! method_exists( '\MN_Elements\Helpers\MN_YouTube_API', 'get_playlist_videos' ) ) {
			return [];
		}

		$videos = \MN_Elements\Helpers\MN_YouTube_API::get_playlist_videos( $playlist_id, $api_key, $max_results, $orderby );

		if ( is_wp_error( $videos ) ) {
			// Log error for debugging
			error_log( 'MN Video Playlist YouTube Error: ' . $videos->get_error_message() );
			return [];
		}

		return $videos;
	}

	/**
	 * Get YouTube channel videos
	 */
	private function get_youtube_channel_videos( $settings ) {
		// Check if helper class exists
		if ( ! class_exists( '\MN_Elements\Helpers\MN_YouTube_API' ) ) {
			return [];
		}

		$api_key = isset( $settings['youtube_api_key'] ) ? $settings['youtube_api_key'] : '';
		$channel_id = isset( $settings['youtube_channel_id'] ) ? $settings['youtube_channel_id'] : '';
		$max_results = isset( $settings['youtube_max_results'] ) ? $settings['youtube_max_results'] : 10;

		if ( empty( $api_key ) || empty( $channel_id ) ) {
			return [];
		}

		// Check if method exists before calling
		if ( ! method_exists( '\MN_Elements\Helpers\MN_YouTube_API', 'get_channel_videos' ) ) {
			return [];
		}

		$videos = \MN_Elements\Helpers\MN_YouTube_API::get_channel_videos( $channel_id, $api_key, $max_results );

		if ( is_wp_error( $videos ) ) {
			// Log error for debugging
			error_log( 'MN Video Playlist YouTube Error: ' . $videos->get_error_message() );
			return [];
		}

		return $videos;
	}

	/**
	 * Get dynamic video list from posts
	 */
	private function get_dynamic_video_list( $settings ) {
		$args = [
			'post_type' => $settings['post_type'],
			'posts_per_page' => $settings['posts_per_page'],
			'orderby' => $settings['orderby'],
			'order' => $settings['order'],
			'post_status' => 'publish',
		];

		// Add taxonomy query if taxonomy and term IDs are specified
		if ( ! empty( $settings['taxonomy'] ) && ! empty( $settings['term_ids'] ) ) {
			$term_ids = array_map( 'trim', explode( ',', $settings['term_ids'] ) );
			$term_ids = array_filter( array_map( 'intval', $term_ids ) );
			
			if ( ! empty( $term_ids ) ) {
				$args['tax_query'] = [
					[
						'taxonomy' => $settings['taxonomy'],
						'field'    => 'term_id',
						'terms'    => $term_ids,
						'operator' => 'IN',
					],
				];
			}
		}

		$query = new \WP_Query( $args );
		$video_list = [];

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$post_id = get_the_ID();

				// Get video title
				$video_title = '';
				if ( $settings['title_field'] === 'post_title' ) {
					$video_title = get_the_title();
				} elseif ( $settings['title_field'] === 'custom_field' && ! empty( $settings['title_custom_field'] ) ) {
					$video_title = $this->get_custom_field_value( $post_id, $settings['title_custom_field'] );
				}

				// Get YouTube URL from custom field
				$youtube_url = '';
				if ( ! empty( $settings['youtube_url_field'] ) ) {
					$youtube_url = $this->get_custom_field_value( $post_id, $settings['youtube_url_field'] );
				}

				// Get video description
				$video_description = '';
				if ( $settings['description_field'] === 'post_excerpt' ) {
					$video_description = get_the_excerpt();
				} elseif ( $settings['description_field'] === 'post_content' ) {
					$video_description = get_the_content();
				} elseif ( $settings['description_field'] === 'custom_field' && ! empty( $settings['description_custom_field'] ) ) {
					$video_description = $this->get_custom_field_value( $post_id, $settings['description_custom_field'] );
				}

				// Get video duration
				$video_duration = '';
				if ( ! empty( $settings['duration_custom_field'] ) ) {
					$video_duration = $this->get_custom_field_value( $post_id, $settings['duration_custom_field'] );
				}

				// Only add if we have a valid YouTube URL
				if ( ! empty( $youtube_url ) && ! empty( $video_title ) ) {
					$video_list[] = [
						'video_title' => $video_title,
						'youtube_url' => [
							'url' => $youtube_url,
						],
						'video_description' => $video_description,
						'video_duration' => $video_duration,
					];
				}
			}
			wp_reset_postdata();
		}

		return $video_list;
	}

	/**
	 * Get custom field value with support for ACF and JetEngine
	 */
	private function get_custom_field_value( $post_id, $field_name ) {
		$value = '';

		// Try ACF first
		if ( function_exists( 'get_field' ) ) {
			$value = get_field( $field_name, $post_id );
		}

		// If no ACF value, try JetEngine
		if ( empty( $value ) && function_exists( 'jet_engine' ) ) {
			$value = get_post_meta( $post_id, $field_name, true );
		}

		// Fallback to standard meta
		if ( empty( $value ) ) {
			$value = get_post_meta( $post_id, $field_name, true );
		}

		return $value;
	}

	/**
	 * Extract YouTube video ID from URL
	 */
	private function get_youtube_id( $url ) {
		$video_id = '';
		
		// Check if URL is empty or not a string
		if ( empty( $url ) || ! is_string( $url ) ) {
			return $video_id;
		}
		
		if ( preg_match( '/youtube\.com\/watch\?v=([^\&\?\/]+)/', $url, $id ) ) {
			$video_id = $id[1];
		} elseif ( preg_match( '/youtube\.com\/embed\/([^\&\?\/]+)/', $url, $id ) ) {
			$video_id = $id[1];
		} elseif ( preg_match( '/youtu\.be\/([^\&\?\/]+)/', $url, $id ) ) {
			$video_id = $id[1];
		}
		
		return $video_id;
	}

	/**
	 * Render widget output on the frontend.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		// Get video list based on source type
		$video_list = [];
		if ( $settings['source_type'] === 'dynamic' ) {
			$video_list = $this->get_dynamic_video_list( $settings );
		} elseif ( $settings['source_type'] === 'youtube_playlist' ) {
			$video_list = $this->get_youtube_playlist_videos( $settings );
		} elseif ( $settings['source_type'] === 'youtube_channel' ) {
			$video_list = $this->get_youtube_channel_videos( $settings );
		} else {
			$video_list = $settings['video_list'];
		}

		if ( empty( $video_list ) ) {
			return;
		}

		// Render carousel layout if selected
		if ( $settings['layout_type'] === 'carousel' ) {
			$this->render_carousel_layout( $video_list, $settings );
			return;
		}

		$theme_class = $settings['theme_version'] ? 'mn-theme-dark' : 'mn-theme-light';
		$layout_class = 'mn-layout-' . $settings['layout_type'];

		$this->add_render_attribute( 'wrapper', 'class', [
			'mn-video-playlist-wrapper',
			$theme_class,
			$layout_class
		] );
		
		$this->add_render_attribute( 'wrapper', 'data-widget-id', $this->get_id() );
		$this->add_render_attribute( 'wrapper', 'data-autoplay-next', $settings['autoplay_next'] ? 'true' : 'false' );
		$this->add_render_attribute( 'wrapper', 'data-autoplay-first', $settings['autoplay_first'] ? 'true' : 'false' );
		$this->add_render_attribute( 'wrapper', 'data-modal-mode', $settings['open_video_modal'] ? 'true' : 'false' );

		$this->add_render_attribute( 'container', 'class', 'mn-video-playlist-container' );

		// Get first video for initial load
		$first_video = $video_list[0];
		// Handle both array and string formats for youtube_url
		$first_video_url = '';
		if ( isset( $first_video['youtube_url']['url'] ) ) {
			$first_video_url = $first_video['youtube_url']['url'];
		} elseif ( isset( $first_video['youtube_url'] ) && is_string( $first_video['youtube_url'] ) ) {
			$first_video_url = $first_video['youtube_url'];
		}
		$first_video_id = $this->get_youtube_id( $first_video_url );

		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<?php if ( ! empty( $settings['playlist_title'] ) ) : ?>
				<<?php echo esc_attr( $settings['title_tag'] ); ?> class="mn-video-playlist-title">
					<?php echo esc_html( $settings['playlist_title'] ); ?>
				</<?php echo esc_attr( $settings['title_tag'] ); ?>>
			<?php endif; ?>
			
			<div <?php $this->print_render_attribute_string( 'container' ); ?>>
				<?php
				$aspect_ratio = isset( $settings['player_aspect_ratio'] ) ? $settings['player_aspect_ratio'] : '16-9';
				$player_class = 'mn-video-player';
				?>
				<div class="<?php echo esc_attr( $player_class ); ?>" data-aspect-ratio="<?php echo esc_attr( $aspect_ratio ); ?>">
					<?php
					$iframe_params = 'enablejsapi=1&rel=0';
					if ( $settings['autoplay_first'] ) {
						$iframe_params .= '&autoplay=1&mute=1';
					}
					?>
					<iframe 
						id="mn-video-iframe-<?php echo esc_attr( $this->get_id() ); ?>"
						width="100%" 
						height="100%" 
						src="https://www.youtube.com/embed/<?php echo esc_attr( $first_video_id ); ?>?<?php echo esc_attr( $iframe_params ); ?>" 
						frameborder="0" 
						allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
						allowfullscreen>
					</iframe>
				</div>

				<div class="mn-video-playlist">
					<div class="mn-playlist-items">
						<?php
						foreach ( $video_list as $index => $video ) :
							$this->render_playlist_item( $video, $settings, $index );
						endforeach;
						?>
					</div>
				</div>
			</div>

			<?php if ( $settings['open_video_modal'] ) : ?>
			<!-- Modal for video playback -->
			<div class="mn-video-modal" style="display: none;">
				<div class="mn-modal-overlay"></div>
				<div class="mn-modal-content">
					<button class="mn-modal-close" aria-label="Close">&times;</button>
					<div class="mn-modal-video-container">
						<iframe 
							id="mn-modal-iframe-<?php echo esc_attr( $this->get_id() ); ?>"
							width="100%" 
							height="100%" 
							src="" 
							frameborder="0" 
							allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
							allowfullscreen>
						</iframe>
					</div>
				</div>
			</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Render carousel layout
	 */
	private function render_carousel_layout( $video_list, $settings ) {
		$theme_class = $settings['theme_version'] ? 'mn-theme-dark' : 'mn-theme-light';
		$carousel_speed = isset( $settings['carousel_speed'] ) ? $settings['carousel_speed'] : 30000;
		$pause_hover = isset( $settings['carousel_pause_hover'] ) && $settings['carousel_pause_hover'] === 'yes';

		$this->add_render_attribute( 'carousel-wrapper', 'class', [
			'mn-video-carousel-wrapper',
			$theme_class
		] );
		
		$this->add_render_attribute( 'carousel-wrapper', 'data-widget-id', $this->get_id() );
		$this->add_render_attribute( 'carousel-wrapper', 'data-carousel-speed', $carousel_speed );
		$this->add_render_attribute( 'carousel-wrapper', 'data-pause-hover', $pause_hover ? 'true' : 'false' );

		?>
		<div <?php $this->print_render_attribute_string( 'carousel-wrapper' ); ?>>
			<?php if ( ! empty( $settings['playlist_title'] ) ) : ?>
				<<?php echo esc_attr( $settings['title_tag'] ); ?> class="mn-video-playlist-title">
					<?php echo esc_html( $settings['playlist_title'] ); ?>
				</<?php echo esc_attr( $settings['title_tag'] ); ?>>
			<?php endif; ?>
			
			<div class="mn-video-carousel-container">
				<div class="mn-carousel-items">
					<?php
					foreach ( $video_list as $index => $video ) :
						$this->render_carousel_item( $video, $settings, $index );
					endforeach;
					?>
				</div>
			</div>
			
			<!-- Modal for video playback -->
			<div class="mn-video-modal" style="display: none;">
				<div class="mn-modal-overlay"></div>
				<div class="mn-modal-content">
					<button class="mn-modal-close" aria-label="Close">&times;</button>
					<div class="mn-modal-video-container">
						<iframe 
							id="mn-modal-iframe-<?php echo esc_attr( $this->get_id() ); ?>"
							width="100%" 
							height="100%" 
							src="" 
							frameborder="0" 
							allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
							allowfullscreen>
						</iframe>
					</div>
					<div class="mn-modal-info">
						<h3 class="mn-modal-title"></h3>
						<p class="mn-modal-description"></p>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render single carousel item
	 */
	private function render_carousel_item( $video, $settings, $index ) {
		// Get YouTube URL - handle both array and string formats
		$youtube_url = '';
		if ( isset( $video['youtube_url']['url'] ) ) {
			$youtube_url = $video['youtube_url']['url'];
		} elseif ( isset( $video['youtube_url'] ) && is_string( $video['youtube_url'] ) ) {
			$youtube_url = $video['youtube_url'];
		}
		
		$video_id = $this->get_youtube_id( $youtube_url );
		
		// Skip if no valid video ID
		if ( empty( $video_id ) ) {
			return;
		}
		
		$show_title = isset( $settings['show_playlist_title'] ) && $settings['show_playlist_title'] === 'yes';
		$show_description = isset( $settings['show_playlist_description'] ) && $settings['show_playlist_description'] === 'yes';
		$show_duration = isset( $settings['show_playlist_duration'] ) && $settings['show_playlist_duration'] === 'yes';
		
		?>
		<div class="mn-carousel-item" 
			 data-video-id="<?php echo esc_attr( $video_id ); ?>"
			 data-video-title="<?php echo esc_attr( $video['video_title'] ); ?>"
			 data-video-description="<?php echo esc_attr( $video['video_description'] ); ?>"
			 data-index="<?php echo esc_attr( $index ); ?>">
			
			<div class="mn-carousel-item-thumbnail">
				<img src="https://img.youtube.com/vi/<?php echo esc_attr( $video_id ); ?>/mqdefault.jpg" 
					 alt="<?php echo esc_attr( $video['video_title'] ); ?>">
				<div class="mn-carousel-play-icon" aria-hidden="true"></div>
				<?php if ( $show_duration && ! empty( $video['video_duration'] ) ) : ?>
					<div class="mn-carousel-item-duration"><?php echo esc_html( $video['video_duration'] ); ?></div>
				<?php endif; ?>
			</div>
			
			<?php if ( $show_title || $show_description ) : ?>
			<div class="mn-carousel-item-content">
				<?php if ( $show_title ) : ?>
					<h4 class="mn-carousel-item-title"><?php echo esc_html( $video['video_title'] ); ?></h4>
				<?php endif; ?>
				<?php if ( $show_description ) : ?>
					<p class="mn-carousel-item-description"><?php echo esc_html( $video['video_description'] ); ?></p>
				<?php endif; ?>
			</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Render single playlist item
	 */
	private function render_playlist_item( $video, $settings, $index ) {
		// Get YouTube URL - handle both array and string formats
		$youtube_url = '';
		if ( isset( $video['youtube_url']['url'] ) ) {
			$youtube_url = $video['youtube_url']['url'];
		} elseif ( isset( $video['youtube_url'] ) && is_string( $video['youtube_url'] ) ) {
			$youtube_url = $video['youtube_url'];
		}
		
		$video_id = $this->get_youtube_id( $youtube_url );
		$active_class = ( $index === 0 ) ? 'active' : '';
		
		// Skip if no valid video ID
		if ( empty( $video_id ) ) {
			return;
		}
		
		?>
		<div class="mn-playlist-item <?php echo esc_attr( $active_class ); ?>" 
			 data-video-id="<?php echo esc_attr( $video_id ); ?>" 
			 data-index="<?php echo esc_attr( $index ); ?>">
			
			<div class="mn-playlist-item-thumbnail">
				<img src="https://img.youtube.com/vi/<?php echo esc_attr( $video_id ); ?>/mqdefault.jpg" 
					 alt="<?php echo esc_attr( $video['video_title'] ); ?>">
				<div class="mn-playlist-play-icon" aria-hidden="true"></div>
			</div>

			<div class="mn-playlist-item-content">
				<?php if ( $settings['show_playlist_title'] && ! empty( $video['video_title'] ) ) : ?>
					<h4 class="mn-playlist-item-title">
						<?php echo esc_html( $video['video_title'] ); ?>
					</h4>
				<?php endif; ?>

				<?php if ( $settings['show_playlist_description'] && ! empty( $video['video_description'] ) ) : ?>
					<div class="mn-playlist-item-description">
						<?php echo wp_kses_post( $video['video_description'] ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $settings['show_playlist_duration'] && ! empty( $video['video_duration'] ) ) : ?>
					<div class="mn-playlist-item-duration">
						<?php echo esc_html( $video['video_duration'] ); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}
