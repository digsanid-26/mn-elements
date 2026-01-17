<?php
namespace MN_Elements\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * MN Dual Slider Widget
 *
 * Dual synchronized slider widget with static and dynamic data sources
 *
 * @since 1.5.8
 */
class MN_Dual_Slider extends Widget_Base {

	/**
	 * Get widget name.
	 */
	public function get_name() {
		return 'mn-dual-slider';
	}

	/**
	 * Get widget title.
	 */
	public function get_title() {
		return esc_html__( 'MN Dual Slider', 'mn-elements' );
	}

	/**
	 * Get widget icon.
	 */
	public function get_icon() {
		return 'eicon-slider-push';
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
		return [ 'slider', 'carousel', 'dual', 'sync', 'hero', 'mn' ];
	}

	/**
	 * Get style depends.
	 */
	public function get_style_depends() {
		return [ 'mn-dual-slider' ];
	}

	/**
	 * Get script depends.
	 */
	public function get_script_depends() {
		return [ 'mn-dual-slider' ];
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
		// Data Source Section
		$this->start_controls_section(
			'section_data_source',
			[
				'label' => esc_html__( 'Data Source', 'mn-elements' ),
			]
		);

		$this->add_control(
			'source_type',
			[
				'label' => esc_html__( 'Source Type', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'static',
				'options' => [
					'static' => esc_html__( 'Static (Manual)', 'mn-elements' ),
					'dynamic' => esc_html__( 'Dynamic (Query)', 'mn-elements' ),
				],
			]
		);

		$this->end_controls_section();

		// Static Slides Section
		$this->start_controls_section(
			'section_static_slides',
			[
				'label' => esc_html__( 'Slides', 'mn-elements' ),
				'condition' => [
					'source_type' => 'static',
				],
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'slide_title',
			[
				'label' => esc_html__( 'Title', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Slide Title', 'mn-elements' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'slide_subtitle',
			[
				'label' => esc_html__( 'Subtitle', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '#YourHashtag', 'mn-elements' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'slide_description',
			[
				'label' => esc_html__( 'Description', 'mn-elements' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Slide description goes here...', 'mn-elements' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'slide_image',
			[
				'label' => esc_html__( 'Image', 'mn-elements' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'slide_video',
			[
				'label' => esc_html__( 'Video URL (Optional)', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'YouTube URL or direct video file', 'mn-elements' ),
				'description' => esc_html__( 'Supports YouTube URLs and direct video files (.mp4, .webm)', 'mn-elements' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'slide_category',
			[
				'label' => esc_html__( 'Category', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Category', 'mn-elements' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'slide_link',
			[
				'label' => esc_html__( 'Link URL', 'mn-elements' ),
				'type' => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'mn-elements' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'explore_link',
			[
				'label' => esc_html__( 'Explore URL', 'mn-elements' ),
				'type' => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'mn-elements' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'slides',
			[
				'label' => esc_html__( 'Slides', 'mn-elements' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'slide_title' => esc_html__( 'First Slide', 'mn-elements' ),
						'slide_subtitle' => esc_html__( '#ElevatingYourFuture', 'mn-elements' ),
						'slide_description' => esc_html__( 'This is the first slide description.', 'mn-elements' ),
						'slide_category' => esc_html__( 'Technology', 'mn-elements' ),
					],
					[
						'slide_title' => esc_html__( 'Second Slide', 'mn-elements' ),
						'slide_subtitle' => esc_html__( '#Innovation', 'mn-elements' ),
						'slide_description' => esc_html__( 'This is the second slide description.', 'mn-elements' ),
						'slide_category' => esc_html__( 'Design', 'mn-elements' ),
					],
					[
						'slide_title' => esc_html__( 'Third Slide', 'mn-elements' ),
						'slide_subtitle' => esc_html__( '#Excellence', 'mn-elements' ),
						'slide_description' => esc_html__( 'This is the third slide description.', 'mn-elements' ),
						'slide_category' => esc_html__( 'Business', 'mn-elements' ),
					],
				],
				'title_field' => '{{{ slide_title }}}',
			]
		);

		$this->end_controls_section();

		// Dynamic Query Section
		$this->start_controls_section(
			'section_dynamic_query',
			[
				'label' => esc_html__( 'Query Settings', 'mn-elements' ),
				'condition' => [
					'source_type' => 'dynamic',
				],
			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'label' => esc_html__( 'Posts Per Page', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5,
				'min' => 1,
				'max' => 20,
			]
		);

		$this->add_control(
			'post_type',
			[
				'label' => esc_html__( 'Post Type', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_post_types(),
				'default' => 'post',
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
			]
		);

		$this->end_controls_section();

		// Dynamic Field Mapping Section
		$this->start_controls_section(
			'section_field_mapping',
			[
				'label' => esc_html__( 'Field Mapping', 'mn-elements' ),
				'condition' => [
					'source_type' => 'dynamic',
				],
			]
		);

		$this->add_control(
			'title_source',
			[
				'label' => esc_html__( 'Title Source', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'post_title',
				'options' => [
					'post_title' => esc_html__( 'Post Title', 'mn-elements' ),
					'custom_field' => esc_html__( 'Custom Field', 'mn-elements' ),
				],
			]
		);

		$this->add_control(
			'title_field',
			[
				'label' => esc_html__( 'Title Field Name', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'title_source' => 'custom_field',
				],
			]
		);

		$this->add_control(
			'subtitle_field',
			[
				'label' => esc_html__( 'Subtitle Field Name', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'subtitle', 'mn-elements' ),
			]
		);

		$this->add_control(
			'description_source',
			[
				'label' => esc_html__( 'Description Source', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'post_excerpt',
				'options' => [
					'post_excerpt' => esc_html__( 'Post Excerpt', 'mn-elements' ),
					'post_content' => esc_html__( 'Post Content', 'mn-elements' ),
					'custom_field' => esc_html__( 'Custom Field', 'mn-elements' ),
				],
			]
		);

		$this->add_control(
			'description_field',
			[
				'label' => esc_html__( 'Description Field Name', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'description_source' => 'custom_field',
				],
			]
		);

		$this->add_control(
			'image_source',
			[
				'label' => esc_html__( 'Image Source', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'featured_image',
				'options' => [
					'featured_image' => esc_html__( 'Featured Image', 'mn-elements' ),
					'custom_field' => esc_html__( 'Custom Field', 'mn-elements' ),
				],
			]
		);

		$this->add_control(
			'image_field',
			[
				'label' => esc_html__( 'Image Field Name', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'image_source' => 'custom_field',
				],
			]
		);

		$this->add_control(
			'video_field',
			[
				'label' => esc_html__( 'Video Field Name', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'video_url', 'mn-elements' ),
			]
		);

		$this->add_control(
			'category_field',
			[
				'label' => esc_html__( 'Category Field Name', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'category', 'mn-elements' ),
			]
		);

		$this->end_controls_section();

		// Layout Settings Section
		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Layout Settings', 'mn-elements' ),
			]
		);

		$this->add_responsive_control(
			'main_slider_width',
			[
				'label' => esc_html__( 'Main Slider Width (%)', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 50,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 72,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-dual-slider-main' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'thumb_slider_width',
			[
				'label' => esc_html__( 'Thumbnail Slider Width (%)', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 28,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-dual-slider-thumb' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'slider_gap',
			[
				'label' => esc_html__( 'Gap Between Sliders', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-dual-slider-container' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'show_thumbnail',
			[
				'label' => esc_html__( 'Show Thumbnail Slider', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'mn-elements' ),
				'label_off' => esc_html__( 'Hide', 'mn-elements' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->end_controls_section();

		// Slider Settings Section
		$this->start_controls_section(
			'section_slider_settings',
			[
				'label' => esc_html__( 'Slider Settings', 'mn-elements' ),
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label' => esc_html__( 'Autoplay', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'mn-elements' ),
				'label_off' => esc_html__( 'No', 'mn-elements' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'label' => esc_html__( 'Autoplay Speed (ms)', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5000,
				'min' => 1000,
				'max' => 30000,
				'step' => 500,
				'condition' => [
					'autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'transition_speed',
			[
				'label' => esc_html__( 'Transition Speed (ms)', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 600,
				'min' => 200,
				'max' => 2000,
				'step' => 100,
			]
		);

		$this->add_control(
			'loop',
			[
				'label' => esc_html__( 'Infinite Loop', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'mn-elements' ),
				'label_off' => esc_html__( 'No', 'mn-elements' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_navigation',
			[
				'label' => esc_html__( 'Show Navigation Arrows', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'mn-elements' ),
				'label_off' => esc_html__( 'Hide', 'mn-elements' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->end_controls_section();

		// Video Settings Section
		$this->start_controls_section(
			'section_video_settings',
			[
				'label' => esc_html__( 'Video Settings', 'mn-elements' ),
			]
		);

		$this->add_control(
			'video_muted',
			[
				'label' => esc_html__( 'Mute Video by Default', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'mn-elements' ),
				'label_off' => esc_html__( 'No', 'mn-elements' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_audio_toggle',
			[
				'label' => esc_html__( 'Show Audio Toggle Button', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'mn-elements' ),
				'label_off' => esc_html__( 'Hide', 'mn-elements' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->end_controls_section();

		// Content Display Section
		$this->start_controls_section(
			'section_content_display',
			[
				'label' => esc_html__( 'Content Display', 'mn-elements' ),
			]
		);

		$this->add_control(
			'show_title',
			[
				'label' => esc_html__( 'Show Title', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'mn-elements' ),
				'label_off' => esc_html__( 'Hide', 'mn-elements' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_subtitle',
			[
				'label' => esc_html__( 'Show Subtitle', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'mn-elements' ),
				'label_off' => esc_html__( 'Hide', 'mn-elements' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_description',
			[
				'label' => esc_html__( 'Show Description', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'mn-elements' ),
				'label_off' => esc_html__( 'Hide', 'mn-elements' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_category',
			[
				'label' => esc_html__( 'Show Category', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'mn-elements' ),
				'label_off' => esc_html__( 'Hide', 'mn-elements' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_cta_button',
			[
				'label' => esc_html__( 'Show CTA Button', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'mn-elements' ),
				'label_off' => esc_html__( 'Hide', 'mn-elements' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'cta_button_text',
			[
				'label' => esc_html__( 'CTA Button Text', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Explore Now', 'mn-elements' ),
				'condition' => [
					'show_cta_button' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register style controls.
	 */
	protected function register_style_controls() {
		// Main Slider Style Section
		$this->start_controls_section(
			'section_main_slider_style',
			[
				'label' => esc_html__( 'Main Slider', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'main_slider_height',
			[
				'label' => esc_html__( 'Height', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range' => [
					'px' => [
						'min' => 200,
						'max' => 1000,
					],
					'vh' => [
						'min' => 20,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 450,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-dual-slider-main' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'main_slider_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-dual-slider-main, {{WRAPPER}} .mn-dual-slider-main .mn-slide' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'overlay_background',
				'label' => esc_html__( 'Overlay Gradient', 'mn-elements' ),
				'types' => [ 'gradient' ],
				'selector' => '{{WRAPPER}} .mn-slide-overlay',
			]
		);

		$this->end_controls_section();

		// Thumbnail Slider Style Section
		$this->start_controls_section(
			'section_thumb_slider_style',
			[
				'label' => esc_html__( 'Thumbnail Slider', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_thumbnail' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'thumb_slider_height',
			[
				'label' => esc_html__( 'Height', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 500,
					],
					'vh' => [
						'min' => 10,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 450,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-dual-slider-thumb' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'thumb_slider_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-dual-slider-thumb, {{WRAPPER}} .mn-dual-slider-thumb .mn-slide' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Caption Style Section
		$this->start_controls_section(
			'section_caption_style',
			[
				'label' => esc_html__( 'Caption', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => esc_html__( 'Title Typography', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-slide-title',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Title Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-slide-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'subtitle_typography',
				'label' => esc_html__( 'Subtitle Typography', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-slide-subtitle',
			]
		);

		$this->add_control(
			'subtitle_color',
			[
				'label' => esc_html__( 'Subtitle Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#d0d5dd',
				'selectors' => [
					'{{WRAPPER}} .mn-slide-subtitle' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Navigation Style Section
		$this->start_controls_section(
			'section_navigation_style',
			[
				'label' => esc_html__( 'Navigation', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_navigation' => 'yes',
				],
			]
		);

		$this->add_control(
			'nav_bg_desktop',
			[
				'label' => esc_html__( 'Desktop Background Image', 'mn-elements' ),
				'type' => Controls_Manager::MEDIA,
				'description' => esc_html__( 'Upload custom background for desktop navigation', 'mn-elements' ),
			]
		);

		$this->add_control(
			'nav_bg_mobile',
			[
				'label' => esc_html__( 'Mobile Background Image', 'mn-elements' ),
				'type' => Controls_Manager::MEDIA,
				'description' => esc_html__( 'Upload custom background for mobile navigation', 'mn-elements' ),
			]
		);

		$this->add_responsive_control(
			'nav_button_size',
			[
				'label' => esc_html__( 'Button Size', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 30,
						'max' => 80,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 52,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-nav-button' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'nav_button_color',
			[
				'label' => esc_html__( 'Button Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#d0d5dd',
				'selectors' => [
					'{{WRAPPER}} .mn-nav-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_button_hover_color',
			[
				'label' => esc_html__( 'Button Hover Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#475467',
				'selectors' => [
					'{{WRAPPER}} .mn-nav-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_button_bg_color',
			[
				'label' => esc_html__( 'Button Background', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-nav-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_button_hover_bg_color',
			[
				'label' => esc_html__( 'Button Hover Background', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#f9fafb',
				'selectors' => [
					'{{WRAPPER}} .mn-nav-button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'nav_button_border',
				'selector' => '{{WRAPPER}} .mn-nav-button',
			]
		);

		$this->add_control(
			'nav_button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-nav-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// CTA Section Style
		$this->start_controls_section(
			'section_cta_style',
			[
				'label' => esc_html__( 'CTA Section', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'cta_background',
				'label' => esc_html__( 'Background', 'mn-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .mn-cta-section',
			]
		);

		$this->add_responsive_control(
			'cta_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-cta-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'cta_category_typography',
				'label' => esc_html__( 'Category Typography', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-cta-category',
			]
		);

		$this->add_control(
			'cta_category_color',
			[
				'label' => esc_html__( 'Category Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .mn-cta-category' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'cta_description_typography',
				'label' => esc_html__( 'Description Typography', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-cta-description',
			]
		);

		$this->add_control(
			'cta_description_color',
			[
				'label' => esc_html__( 'Description Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#475467',
				'selectors' => [
					'{{WRAPPER}} .mn-cta-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// CTA Button Style
		$this->start_controls_section(
			'section_cta_button_style',
			[
				'label' => esc_html__( 'CTA Button', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_cta_button' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'cta_button_typography',
				'selector' => '{{WRAPPER}} .mn-cta-button',
			]
		);

		$this->start_controls_tabs( 'cta_button_tabs' );

		$this->start_controls_tab(
			'cta_button_normal',
			[
				'label' => esc_html__( 'Normal', 'mn-elements' ),
			]
		);

		$this->add_control(
			'cta_button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-cta-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'cta_button_background',
				'label' => esc_html__( 'Background', 'mn-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .mn-cta-button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'cta_button_hover',
			[
				'label' => esc_html__( 'Hover', 'mn-elements' ),
			]
		);

		$this->add_control(
			'cta_button_hover_text_color',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-cta-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'cta_button_hover_background',
				'label' => esc_html__( 'Background', 'mn-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .mn-cta-button:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'cta_button_border',
				'selector' => '{{WRAPPER}} .mn-cta-button',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'cta_button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-cta-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'cta_button_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-cta-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Get post types for query.
	 */
	protected function get_post_types() {
		$post_types = get_post_types( [ 'public' => true ], 'objects' );
		$options = [];
		
		foreach ( $post_types as $post_type ) {
			$options[ $post_type->name ] = $post_type->label;
		}
		
		return $options;
	}

	/**
	 * Get custom field value with ACF support.
	 */
	protected function get_custom_field_value( $post_id, $field_name ) {
		if ( empty( $field_name ) ) {
			return '';
		}

		// Try ACF first
		if ( function_exists( 'get_field' ) ) {
			$value = get_field( $field_name, $post_id );
			
			// Handle ACF file/image fields
			if ( is_array( $value ) && isset( $value['url'] ) ) {
				return $value['url'];
			}
			
			// Handle ACF attachment ID
			if ( is_numeric( $value ) ) {
				$attachment_url = wp_get_attachment_url( $value );
				if ( $attachment_url ) {
					return $attachment_url;
				}
			}
			
			if ( ! empty( $value ) ) {
				return $value;
			}
		}

		// Fallback to WordPress meta
		$value = get_post_meta( $post_id, $field_name, true );
		
		// Handle attachment ID
		if ( is_numeric( $value ) ) {
			$attachment_url = wp_get_attachment_url( $value );
			if ( $attachment_url ) {
				return $attachment_url;
			}
		}
		
		return $value;
	}

	/**
	 * Get slides data based on source type.
	 */
	protected function get_slides_data() {
		$settings = $this->get_settings_for_display();
		$slides = [];

		if ( $settings['source_type'] === 'static' ) {
			// Static source
			foreach ( $settings['slides'] as $slide ) {
				$slides[] = [
					'title' => $slide['slide_title'],
					'subtitle' => $slide['slide_subtitle'],
					'description' => $slide['slide_description'],
					'image' => $slide['slide_image']['url'],
					'video' => $slide['slide_video'],
					'category' => $slide['slide_category'],
					'link' => ! empty( $slide['slide_link']['url'] ) ? $slide['slide_link']['url'] : '',
					'explore_link' => ! empty( $slide['explore_link']['url'] ) ? $slide['explore_link']['url'] : '',
				];
			}
		} else {
			// Dynamic source
			$args = [
				'post_type' => $settings['post_type'],
				'posts_per_page' => $settings['posts_per_page'],
				'orderby' => $settings['orderby'],
				'order' => $settings['order'],
				'post_status' => 'publish',
			];

			$query = new \WP_Query( $args );

			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					$post_id = get_the_ID();

					// Get title
					$title = $settings['title_source'] === 'post_title' 
						? get_the_title() 
						: $this->get_custom_field_value( $post_id, $settings['title_field'] );

					// Get subtitle
					$subtitle = $this->get_custom_field_value( $post_id, $settings['subtitle_field'] );

					// Get description
					$description = '';
					if ( $settings['description_source'] === 'post_excerpt' ) {
						$description = get_the_excerpt();
					} elseif ( $settings['description_source'] === 'post_content' ) {
						$description = wp_trim_words( get_the_content(), 20 );
					} else {
						$description = $this->get_custom_field_value( $post_id, $settings['description_field'] );
					}

					// Get image
					$image = '';
					if ( $settings['image_source'] === 'featured_image' ) {
						$image = get_the_post_thumbnail_url( $post_id, 'full' );
					} else {
						$image = $this->get_custom_field_value( $post_id, $settings['image_field'] );
					}

					// Get video
					$video = $this->get_custom_field_value( $post_id, $settings['video_field'] );

					// Get category
					$category = $this->get_custom_field_value( $post_id, $settings['category_field'] );

					$slides[] = [
						'title' => $title,
						'subtitle' => $subtitle,
						'description' => $description,
						'image' => $image,
						'video' => $video,
						'category' => $category,
						'link' => get_permalink(),
						'explore_link' => get_permalink(),
					];
				}
				wp_reset_postdata();
			}
		}

		return $slides;
	}

	/**
	 * Detect and convert YouTube URLs to embed format.
	 */
	protected function get_youtube_embed_url( $url ) {
		if ( empty( $url ) ) {
			return false;
		}

		$patterns = [
			'/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/',
			'/youtube\.com\/watch\?.*v=([a-zA-Z0-9_-]{11})/',
		];

		foreach ( $patterns as $pattern ) {
			if ( preg_match( $pattern, $url, $matches ) ) {
				return 'https://www.youtube.com/embed/' . $matches[1] . '?autoplay=1&mute=1&loop=1&playlist=' . $matches[1] . '&controls=0&showinfo=0&rel=0&modestbranding=1';
			}
		}

		return false;
	}

	/**
	 * Check if URL is a video file.
	 */
	protected function is_video_file( $url ) {
		if ( empty( $url ) ) {
			return false;
		}

		$video_extensions = [ 'mp4', 'webm', 'ogg', 'avi', 'mov', 'wmv', 'flv', 'm4v' ];
		$extension = strtolower( pathinfo( parse_url( $url, PHP_URL_PATH ), PATHINFO_EXTENSION ) );

		return in_array( $extension, $video_extensions );
	}

	/**
	 * Render widget output on the frontend.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$slides = $this->get_slides_data();

		if ( empty( $slides ) ) {
			echo '<p>' . esc_html__( 'No slides found.', 'mn-elements' ) . '</p>';
			return;
		}

		$widget_id = $this->get_id();
		$autoplay = $settings['autoplay'] === 'yes' ? 'true' : 'false';
		$autoplay_speed = $settings['autoplay_speed'];
		$transition_speed = $settings['transition_speed'];
		$loop = $settings['loop'] === 'yes' ? 'true' : 'false';
		$video_muted = $settings['video_muted'] === 'yes';
		$show_audio_toggle = $settings['show_audio_toggle'] === 'yes';

		?>
		<div class="mn-dual-slider-wrapper" 
			data-widget-id="<?php echo esc_attr( $widget_id ); ?>"
			data-autoplay="<?php echo esc_attr( $autoplay ); ?>"
			data-autoplay-speed="<?php echo esc_attr( $autoplay_speed ); ?>"
			data-transition-speed="<?php echo esc_attr( $transition_speed ); ?>"
			data-loop="<?php echo esc_attr( $loop ); ?>"
			data-video-muted="<?php echo esc_attr( $video_muted ? '1' : '0' ); ?>">
			
			<div class="mn-dual-slider-container">
				<!-- Main Slider -->
				<div class="mn-dual-slider-main">
					<div class="mn-slides-container">
						<?php foreach ( $slides as $index => $slide ) : ?>
							<div class="mn-slide <?php echo $index === 0 ? 'active' : ''; ?>" 
								data-index="<?php echo esc_attr( $index ); ?>"
								data-title="<?php echo esc_attr( $slide['title'] ); ?>"
								data-subtitle="<?php echo esc_attr( $slide['subtitle'] ); ?>">
								
								<!-- Slide Media -->
								<?php if ( ! empty( $slide['video'] ) ) : ?>
									<?php 
									$youtube_embed = $this->get_youtube_embed_url( $slide['video'] );
									$is_video_file = $this->is_video_file( $slide['video'] );
									?>
									
									<?php if ( $youtube_embed ) : ?>
										<!-- YouTube Embed -->
										<iframe class="mn-slide-media mn-slide-youtube" 
											src="<?php echo esc_url( $youtube_embed ); ?>" 
											frameborder="0" 
											allow="autoplay; encrypted-media" 
											allowfullscreen>
										</iframe>
									<?php elseif ( $is_video_file ) : ?>
										<!-- Direct Video File -->
										<video class="mn-slide-media mn-slide-video" 
											autoplay 
											<?php echo $video_muted ? 'muted' : ''; ?> 
											loop 
											playsinline>
											<source src="<?php echo esc_url( $slide['video'] ); ?>" type="video/mp4">
										</video>
										<?php if ( $show_audio_toggle ) : ?>
											<button class="mn-audio-toggle">
												<i class="eicon-volume-mute"></i>
											</button>
										<?php endif; ?>
									<?php endif; ?>
								<?php elseif ( ! empty( $slide['image'] ) ) : ?>
									<img src="<?php echo esc_url( $slide['image'] ); ?>" 
										alt="<?php echo esc_attr( $slide['title'] ); ?>" 
										class="mn-slide-media">
								<?php endif; ?>

								<!-- Overlay -->
								<div class="mn-slide-overlay"></div>

								<!-- Caption -->
								<div class="mn-slide-caption">
									<?php if ( $settings['show_subtitle'] === 'yes' && ! empty( $slide['subtitle'] ) ) : ?>
										<h4 class="mn-slide-subtitle"><?php echo esc_html( $slide['subtitle'] ); ?></h4>
									<?php endif; ?>
									
									<?php if ( $settings['show_title'] === 'yes' && ! empty( $slide['title'] ) ) : ?>
										<h1 class="mn-slide-title"><?php echo esc_html( $slide['title'] ); ?></h1>
									<?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>

					<!-- Navigation -->
					<?php if ( $settings['show_navigation'] === 'yes' ) : ?>
						<div class="mn-navigation-container">
							<?php if ( ! empty( $settings['nav_bg_desktop']['url'] ) ) : ?>
								<div class="mn-nav-bg-desktop">
									<img src="<?php echo esc_url( $settings['nav_bg_desktop']['url'] ); ?>" alt="Navigation Background">
								</div>
							<?php endif; ?>
							
							<?php if ( ! empty( $settings['nav_bg_mobile']['url'] ) ) : ?>
								<div class="mn-nav-bg-mobile">
									<img src="<?php echo esc_url( $settings['nav_bg_mobile']['url'] ); ?>" alt="Navigation Background">
								</div>
							<?php endif; ?>
							
							<div class="mn-nav-buttons">
								<button class="mn-nav-button mn-nav-prev">
									<i class="eicon-chevron-left"></i>
								</button>
								<button class="mn-nav-button mn-nav-next">
									<i class="eicon-chevron-right"></i>
								</button>
							</div>
						</div>
					<?php endif; ?>
				</div>

				<!-- Thumbnail Slider -->
				<?php if ( $settings['show_thumbnail'] === 'yes' ) : ?>
					<div class="mn-dual-slider-thumb">
						<div class="mn-slides-container">
							<?php foreach ( $slides as $index => $slide ) : ?>
								<div class="mn-slide <?php echo $index === 1 ? 'active' : ''; ?>" 
									data-index="<?php echo esc_attr( $index ); ?>">
									
									<?php if ( ! empty( $slide['image'] ) ) : ?>
										<img src="<?php echo esc_url( $slide['image'] ); ?>" 
											alt="<?php echo esc_attr( $slide['title'] ); ?>" 
											class="mn-slide-media">
									<?php endif; ?>

									<div class="mn-slide-caption">
										<?php if ( ! empty( $slide['title'] ) ) : ?>
											<p class="mn-slide-title"><?php echo esc_html( $slide['title'] ); ?></p>
										<?php endif; ?>
										
										<?php if ( ! empty( $slide['category'] ) ) : ?>
											<h5 class="mn-slide-category"><?php echo esc_html( $slide['category'] ); ?></h5>
										<?php endif; ?>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>
			</div>

			<!-- CTA Section -->
			<div class="mn-cta-section">
				<div class="mn-cta-slides">
					<?php foreach ( $slides as $index => $slide ) : ?>
						<div class="mn-cta-slide <?php echo $index === 0 ? 'active' : ''; ?>" 
							data-index="<?php echo esc_attr( $index ); ?>">
							
							<?php if ( $settings['show_category'] === 'yes' && ! empty( $slide['category'] ) ) : ?>
								<h2 class="mn-cta-category"><?php echo esc_html( $slide['category'] ); ?></h2>
							<?php endif; ?>
							
							<div class="mn-cta-content">
								<?php if ( $settings['show_description'] === 'yes' && ! empty( $slide['description'] ) ) : ?>
									<p class="mn-cta-description"><?php echo esc_html( $slide['description'] ); ?></p>
								<?php endif; ?>
								
								<?php if ( $settings['show_cta_button'] === 'yes' && ! empty( $slide['explore_link'] ) ) : ?>
									<a href="<?php echo esc_url( $slide['explore_link'] ); ?>" class="mn-cta-button">
										<?php echo esc_html( $settings['cta_button_text'] ); ?>
										<i class="eicon-arrow-right"></i>
									</a>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<?php
	}
}
