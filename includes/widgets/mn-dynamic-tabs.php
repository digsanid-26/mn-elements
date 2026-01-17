<?php
namespace MN_Elements\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * MN Dynamic Tabs Widget
 * 
 * Advanced dynamic tabs widget with static and dynamic content support
 * Version: 3.0.0
 * Features: Static/Dynamic Tab Types, Multiple Templates, Hero Tabber, Query System
 */
class MN_Dynamic_Tabs extends Widget_Base {

	public function get_name() {
		return 'mn-dynamic-tabs';
	}

	public function get_title() {
		return esc_html__( 'MN Dynamic Tabs', 'mn-elements' );
	}

	public function get_icon() {
		return 'eicon-tabs';
	}

	public function get_categories() {
		return [ 'mn-elements' ];
	}

	public function get_keywords() {
		return [ 'tabs', 'dynamic', 'posts', 'query', 'grid', 'slide', 'slider' ];
	}

	public function get_script_depends() {
		return [ 'mn-dynamic-tabs' ];
	}

	public function get_style_depends() {
		return [ 'mn-dynamic-tabs' ];
	}

	protected function register_controls() {
		// Settings Panel
		$this->register_settings_controls();
		
		// Content Controls
		$this->register_content_controls();
		
		// Query Controls
		$this->register_query_controls();
		
		// Style Controls  
		$this->register_style_controls();
	}

	/**
	 * Register Settings Controls
	 */
	protected function register_settings_controls() {
		$this->start_controls_section(
			'section_settings',
			[
				'label' => esc_html__( 'Settings', 'mn-elements' ),
			]
		);

		$this->add_control(
			'tab_type',
			[
				'label' => esc_html__( 'Tab Type', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'static',
				'options' => [
					'static' => esc_html__( 'Static', 'mn-elements' ),
					'dynamic' => esc_html__( 'Dynamic', 'mn-elements' ),
				],
				'description' => esc_html__( 'Static: Manual tab creation. Dynamic: Auto-generate from posts/custom posts.', 'mn-elements' ),
			]
		);

		$this->add_control(
			'template_type',
			[
				'label' => esc_html__( 'Template', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'mn-elements' ),
					'hero' => esc_html__( 'Hero Tabber', 'mn-elements' ),
				],
				'prefix_class' => 'mn-template-',
				'description' => esc_html__( 'Default: Standard tab layout. Hero Tabber: Full-width background with animated transitions.', 'mn-elements' ),
			]
		);

		$this->add_control(
			'hero_trigger',
			[
				'label' => esc_html__( 'Trigger', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'click',
				'options' => [
					'click' => esc_html__( 'Click', 'mn-elements' ),
					'hover' => esc_html__( 'Hover', 'mn-elements' ),
				],
				'condition' => [
					'template_type' => 'hero',
				],
			]
		);

		$this->add_control(
			'tab_menu_heading',
			[
				'label' => esc_html__( 'Tab Menu Elements', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'show_tab_title',
			[
				'label' => esc_html__( 'Show Title', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'mn-elements' ),
				'label_off' => esc_html__( 'Hide', 'mn-elements' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'description' => esc_html__( 'Show/hide title in tab menu items. Useful when using icon-only tabs.', 'mn-elements' ),
			]
		);

		$this->add_control(
			'show_tab_description',
			[
				'label' => esc_html__( 'Show Description', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'mn-elements' ),
				'label_off' => esc_html__( 'Hide', 'mn-elements' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'description' => esc_html__( 'Show/hide description in tab menu items.', 'mn-elements' ),
			]
		);

		$this->add_control(
			'show_tab_button',
			[
				'label' => esc_html__( 'Show Button', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'mn-elements' ),
				'label_off' => esc_html__( 'Hide', 'mn-elements' ),
				'return_value' => 'yes',
				'default' => 'no',
				'description' => esc_html__( 'Show/hide button in tab menu items (Hero template only).', 'mn-elements' ),
				'condition' => [
					'template_type' => 'hero',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register Content Controls
	 */
	protected function register_content_controls() {
		// Tab Manager Panel
		$this->start_controls_section(
			'section_tabs',
			[
				'label' => esc_html__( 'Tabs', 'mn-elements' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'tab_title',
			[
				'label' => esc_html__( 'Tab Title', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Tab Title', 'mn-elements' ),
				'placeholder' => esc_html__( 'Tab Title', 'mn-elements' ),
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'tab_description',
			[
				'label' => esc_html__( 'Description', 'mn-elements' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Tab description...', 'mn-elements' ),
				'placeholder' => esc_html__( 'Type description here', 'mn-elements' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		// Tab Icon
		$repeater->add_control(
			'tab_icon_heading',
			[
				'label' => esc_html__( 'Tab Icon', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'tab_icon',
			[
				'label' => esc_html__( 'Icon', 'mn-elements' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => '',
					'library' => 'solid',
				],
				'description' => esc_html__( 'Choose an icon or upload custom icon for this tab.', 'mn-elements' ),
			]
		);

		$repeater->add_control(
			'tab_icon_position',
			[
				'label' => esc_html__( 'Icon Position', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => esc_html__( 'Left', 'mn-elements' ),
					'right' => esc_html__( 'Right', 'mn-elements' ),
					'top' => esc_html__( 'Top', 'mn-elements' ),
					'bottom' => esc_html__( 'Bottom', 'mn-elements' ),
				],
				'condition' => [
					'tab_icon[value]!' => '',
				],
			]
		);

		$repeater->add_control(
			'tab_background_image',
			[
				'label' => esc_html__( 'Background Image', 'mn-elements' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'content_type',
			[
				'label' => esc_html__( 'Content Type', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'content',
				'options' => [
					'content' => esc_html__( 'Text Content', 'mn-elements' ),
					'template' => esc_html__( 'Elementor Template', 'mn-elements' ),
					'video' => esc_html__( 'Video Background', 'mn-elements' ),
				],
				'description' => esc_html__( 'Choose type of content for this tab.', 'mn-elements' ),
			]
		);

		$repeater->add_control(
			'tab_content',
			[
				'label' => esc_html__( 'Tab Content', 'mn-elements' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'Tab content goes here...', 'mn-elements' ),
				'placeholder' => esc_html__( 'Type your content here', 'mn-elements' ),
				'condition' => [
					'content_type' => 'content',
				],
			]
		);

		$repeater->add_control(
			'template_id',
			[
				'label' => esc_html__( 'Select Template', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_elementor_templates(),
				'condition' => [
					'content_type' => 'template',
				],
				'description' => esc_html__( 'Choose an Elementor template to display.', 'mn-elements' ),
			]
		);

		$repeater->add_control(
			'video_url',
			[
				'label' => esc_html__( 'Video URL', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'https://example.com/video.mp4', 'mn-elements' ),
				'condition' => [
					'content_type' => 'video',
				],
				'description' => esc_html__( 'Enter video URL (MP4 format recommended).', 'mn-elements' ),
			]
		);

		$repeater->add_control(
			'video_poster',
			[
				'label' => esc_html__( 'Video Poster Image', 'mn-elements' ),
				'type' => Controls_Manager::MEDIA,
				'media_type' => 'image',
				'condition' => [
					'content_type' => 'video',
				],
				'description' => esc_html__( 'Image to show before video plays.', 'mn-elements' ),
			]
		);

		$repeater->add_control(
			'video_overlay_content',
			[
				'label' => esc_html__( 'Overlay Content', 'mn-elements' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'Overlay content for video...', 'mn-elements' ),
				'placeholder' => esc_html__( 'Content to display over video', 'mn-elements' ),
				'condition' => [
					'content_type' => 'video',
				],
				'description' => esc_html__( 'Content to display on top of video background.', 'mn-elements' ),
			]
		);

		$repeater->add_control(
			'button_label',
			[
				'label' => esc_html__( 'Button Label', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Learn More', 'mn-elements' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'custom_url',
			[
				'label' => esc_html__( 'Custom URL', 'mn-elements' ),
				'type' => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'mn-elements' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'tabs',
			[
				'label' => esc_html__( 'Tabs Items', 'mn-elements' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'tab_title' => esc_html__( 'Tab #1', 'mn-elements' ),
						'tab_description' => esc_html__( 'Description for tab 1', 'mn-elements' ),
						'content_type' => 'content',
						'tab_content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'mn-elements' ),
						'button_label' => esc_html__( 'Learn More', 'mn-elements' ),
					],
					[
						'tab_title' => esc_html__( 'Tab #2', 'mn-elements' ),
						'tab_description' => esc_html__( 'Description for tab 2', 'mn-elements' ),
						'content_type' => 'content',
						'tab_content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'mn-elements' ),
						'button_label' => esc_html__( 'Learn More', 'mn-elements' ),
					],
					[
						'tab_title' => esc_html__( 'Tab #3', 'mn-elements' ),
						'tab_description' => esc_html__( 'Description for tab 3', 'mn-elements' ),
						'content_type' => 'content',
						'tab_content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'mn-elements' ),
						'button_label' => esc_html__( 'Learn More', 'mn-elements' ),
					],
				],
				'title_field' => '{{{ tab_title }}}',
				'condition' => [
					'tab_type' => 'static',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register Query Controls
	 */
	protected function register_query_controls() {
		$this->start_controls_section(
			'section_query',
			[
				'label' => esc_html__( 'Query', 'mn-elements' ),
				'condition' => [
					'tab_type' => 'dynamic',
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
			'orderby',
			[
				'label' => esc_html__( 'Order By', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'date' => esc_html__( 'Date', 'mn-elements' ),
					'title' => esc_html__( 'Title', 'mn-elements' ),
					'modified' => esc_html__( 'Modified', 'mn-elements' ),
					'rand' => esc_html__( 'Random', 'mn-elements' ),
					'menu_order' => esc_html__( 'Menu Order', 'mn-elements' ),
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
	}

	/**
	 * Register Style Controls
	 */
	protected function register_style_controls() {
		// Tab Navigation Style
		$this->start_controls_section(
			'section_tab_style',
			[
				'label' => esc_html__( 'Tab Navigation', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		// Navigation Position & Mode
		$this->add_control(
			'tab_nav_position_heading',
			[
				'label' => esc_html__( 'Position & Mode', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'tab_nav_mode',
			[
				'label' => esc_html__( 'Navigation Mode', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fixed',
				'options' => [
					'fixed' => esc_html__( 'Fixed (Outside Content)', 'mn-elements' ),
					'overlay' => esc_html__( 'Overlay (Inside Content)', 'mn-elements' ),
				],
				'prefix_class' => 'mn-nav-mode-',
				'description' => esc_html__( 'Fixed: Navigation outside content box. Overlay: Navigation overlays on content.', 'mn-elements' ),
			]
		);

		$this->add_responsive_control(
			'tab_position',
			[
				'label' => esc_html__( 'Position', 'mn-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'top-left' => [
						'title' => esc_html__( 'Top Left', 'mn-elements' ),
						'icon' => 'eicon-h-align-left',
					],
					'top-center' => [
						'title' => esc_html__( 'Top Center', 'mn-elements' ),
						'icon' => 'eicon-h-align-center',
					],
					'top-right' => [
						'title' => esc_html__( 'Top Right', 'mn-elements' ),
						'icon' => 'eicon-h-align-right',
					],
					'middle-left' => [
						'title' => esc_html__( 'Middle Left', 'mn-elements' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle-center' => [
						'title' => esc_html__( 'Middle Center', 'mn-elements' ),
						'icon' => 'eicon-v-align-middle',
					],
					'middle-right' => [
						'title' => esc_html__( 'Middle Right', 'mn-elements' ),
						'icon' => 'eicon-v-align-bottom',
					],
					'bottom-left' => [
						'title' => esc_html__( 'Bottom Left', 'mn-elements' ),
						'icon' => 'eicon-h-align-left',
					],
					'bottom-center' => [
						'title' => esc_html__( 'Bottom Center', 'mn-elements' ),
						'icon' => 'eicon-h-align-center',
					],
					'bottom-right' => [
						'title' => esc_html__( 'Bottom Right', 'mn-elements' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => 'top-center',
				'toggle' => false,
				'description' => esc_html__( 'Position of tab navigation relative to content.', 'mn-elements' ),
			]
		);

		// Navigation Layout
		$this->add_control(
			'tab_nav_layout_heading',
			[
				'label' => esc_html__( 'Navigation Layout', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tab_nav_direction',
			[
				'label' => esc_html__( 'Direction', 'mn-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'row' => [
						'title' => esc_html__( 'Horizontal', 'mn-elements' ),
						'icon' => 'eicon-navigation-horizontal',
					],
					'column' => [
						'title' => esc_html__( 'Vertical', 'mn-elements' ),
						'icon' => 'eicon-navigation-vertical',
					],
				],
				'default' => 'row',
				'selectors' => [
					'{{WRAPPER}} .mn-tabs-nav' => 'flex-direction: {{VALUE}};',
				],
				'description' => esc_html__( 'Set tab navigation direction (horizontal or vertical).', 'mn-elements' ),
			]
		);

		$this->add_responsive_control(
			'tab_nav_align',
			[
				'label' => esc_html__( 'Alignment', 'mn-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Start', 'mn-elements' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'mn-elements' ),
						'icon' => 'eicon-h-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'End', 'mn-elements' ),
						'icon' => 'eicon-h-align-right',
					],
					'space-between' => [
						'title' => esc_html__( 'Space Between', 'mn-elements' ),
						'icon' => 'eicon-h-align-stretch',
					],
				],
				'default' => 'flex-start',
				'selectors' => [
					'{{WRAPPER}} .mn-tabs-nav' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'tab_nav_gap',
			[
				'label' => esc_html__( 'Gap Between Items', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-tabs-nav' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'tab_nav_width',
			[
				'label' => esc_html__( 'Navigation Width', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vw' ],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1000,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mn-tabs-nav' => 'width: {{SIZE}}{{UNIT}};',
				],
				'description' => esc_html__( 'Set maximum width for tab navigation container.', 'mn-elements' ),
			]
		);

		// Scrolling Behavior
		$this->add_control(
			'tab_nav_scroll_heading',
			[
				'label' => esc_html__( 'Scrolling Behavior', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tab_nav_wrap',
			[
				'label' => esc_html__( 'Wrap Items', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'mn-elements' ),
				'label_off' => esc_html__( 'No', 'mn-elements' ),
				'return_value' => 'wrap',
				'default' => 'wrap',
				'selectors' => [
					'{{WRAPPER}} .mn-tabs-nav' => 'flex-wrap: {{VALUE}};',
				],
				'description' => esc_html__( 'Allow tab items to wrap to next line when space is limited.', 'mn-elements' ),
			]
		);

		$this->add_control(
			'tab_nav_scroll',
			[
				'label' => esc_html__( 'Enable Horizontal Scroll', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'mn-elements' ),
				'label_off' => esc_html__( 'No', 'mn-elements' ),
				'return_value' => 'yes',
				'default' => 'no',
				'condition' => [
					'tab_nav_wrap!' => 'wrap',
				],
			]
		);

		$this->add_control(
			'tab_nav_scroll_note',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => esc_html__( 'Note: Horizontal scroll is automatically enabled on mobile devices.', 'mn-elements' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'condition' => [
					'tab_nav_scroll' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'tab_nav_offset_x',
			[
				'label' => esc_html__( 'Horizontal Offset', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -200,
						'max' => 200,
					],
					'%' => [
						'min' => -50,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-tabs-nav' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
				],
				'description' => esc_html__( 'Fine-tune horizontal position of tab navigation.', 'mn-elements' ),
			]
		);

		$this->add_responsive_control(
			'tab_nav_offset_y',
			[
				'label' => esc_html__( 'Vertical Offset', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -200,
						'max' => 200,
					],
					'%' => [
						'min' => -50,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-tabs-nav' => 'margin-top: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'description' => esc_html__( 'Fine-tune vertical position of tab navigation.', 'mn-elements' ),
			]
		);

		$this->add_responsive_control(
			'tab_nav_width_vertical',
			[
				'label' => esc_html__( 'Vertical Tab Width', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 150,
						'max' => 500,
					],
					'%' => [
						'min' => 10,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 250,
				],
				'selectors' => [
					'{{WRAPPER}}.mn-tab-position-middle-left .mn-tabs-nav, {{WRAPPER}}.mn-tab-position-middle-right .mn-tabs-nav' => 'flex: 0 0 {{SIZE}}{{UNIT}};',
				],
				'description' => esc_html__( 'Set width for vertical tab navigation (left/right positions).', 'mn-elements' ),
			]
		);

		$this->add_responsive_control(
			'tab_item_height',
			[
				'label' => esc_html__( 'Tab Height', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh', 'auto' ],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 300,
					],
					'vh' => [
						'min' => 5,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mn-tab-item' => 'min-height: {{SIZE}}{{UNIT}};',
				],
				'description' => esc_html__( 'Set minimum height for each tab item. Use "auto" for natural height.', 'mn-elements' ),
			]
		);

		$this->add_responsive_control(
			'tab_item_align',
			[
				'label' => esc_html__( 'Vertical Align', 'mn-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Top', 'mn-elements' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'mn-elements' ),
						'icon' => 'eicon-v-align-middle',
					],
					'flex-end' => [
						'title' => esc_html__( 'Bottom', 'mn-elements' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .mn-tab-item' => 'display: flex; align-items: {{VALUE}};',
					'{{WRAPPER}} .mn-tab-item-content' => 'width: 100%;',
				],
			]
		);

		// Tab Title Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tab_title_typography',
				'label' => esc_html__( 'Title Typography', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-tab-title',
			]
		);

		// Tab Description Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tab_desc_typography',
				'label' => esc_html__( 'Description Typography', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-tab-description',
			]
		);

		// Tab Icon Styling
		$this->add_control(
			'tab_icon_heading',
			[
				'label' => esc_html__( 'Icon', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tab_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-tab-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-tab-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'tab_icon_spacing',
			[
				'label' => esc_html__( 'Icon Spacing', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 8,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-tab-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-tab-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-tab-icon-top' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-tab-icon-bottom' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_style_tabs' );

		// Normal State
		$this->start_controls_tab(
			'tab_style_normal',
			[
				'label' => esc_html__( 'Normal', 'mn-elements' ),
			]
		);

		$this->add_control(
			'tab_title_color',
			[
				'label' => esc_html__( 'Title Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-tab-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tab_desc_color',
			[
				'label' => esc_html__( 'Description Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-tab-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tab_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-tab-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-tab-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tab_background_color',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-tab-item' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		// Hover State
		$this->start_controls_tab(
			'tab_style_hover',
			[
				'label' => esc_html__( 'Hover', 'mn-elements' ),
			]
		);

		$this->add_control(
			'tab_title_color_hover',
			[
				'label' => esc_html__( 'Title Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-tab-item:hover .mn-tab-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tab_desc_color_hover',
			[
				'label' => esc_html__( 'Description Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-tab-item:hover .mn-tab-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tab_icon_color_hover',
			[
				'label' => esc_html__( 'Icon Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-tab-item:hover .mn-tab-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-tab-item:hover .mn-tab-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tab_background_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-tab-item:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		// Active State
		$this->start_controls_tab(
			'tab_style_active',
			[
				'label' => esc_html__( 'Active', 'mn-elements' ),
			]
		);

		$this->add_control(
			'tab_title_color_active',
			[
				'label' => esc_html__( 'Title Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-tab-item.active .mn-tab-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tab_desc_color_active',
			[
				'label' => esc_html__( 'Description Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-tab-item.active .mn-tab-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tab_icon_color_active',
			[
				'label' => esc_html__( 'Icon Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-tab-item.active .mn-tab-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-tab-item.active .mn-tab-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tab_background_color_active',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-tab-item.active' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'tab_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .mn-tab-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'tab_border',
				'selector' => '{{WRAPPER}} .mn-tab-item',
			]
		);

		$this->add_responsive_control(
			'tab_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-tab-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Content Style Section
		$this->start_controls_section(
			'section_content_style',
			[
				'label' => esc_html__( 'Content', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'label' => esc_html__( 'Typography', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-tab-content',
			]
		);

		$this->add_control(
			'content_text_color',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-tab-content' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-tab-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'content_background_color',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-tab-content' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'content_border',
				'selector' => '{{WRAPPER}} .mn-tab-content',
			]
		);

		$this->end_controls_section();

		// Button Style Section
		$this->start_controls_section(
			'section_button_style',
			[
				'label' => esc_html__( 'Button', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .mn-tab-button',
			]
		);

		$this->start_controls_tabs( 'button_style_tabs' );

		$this->start_controls_tab(
			'button_style_normal',
			[
				'label' => esc_html__( 'Normal', 'mn-elements' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-tab-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-tab-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_style_hover',
			[
				'label' => esc_html__( 'Hover', 'mn-elements' ),
			]
		);

		$this->add_control(
			'button_text_color_hover',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-tab-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-tab-button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'button_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-tab-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .mn-tab-button',
			]
		);

		$this->add_responsive_control(
			'button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-tab-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Hero Overlay Style
		$this->start_controls_section(
			'section_hero_overlay_style',
			[
				'label' => esc_html__( 'Hero Overlay', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'template_type' => 'hero',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'hero_overlay_background',
				'label' => esc_html__( 'Background', 'mn-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .mn-hero-overlay',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'default' => 'rgba(0,0,0,0.5)',
					],
				],
			]
		);

		$this->add_control(
			'hero_overlay_opacity',
			[
				'label' => esc_html__( 'Opacity', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.01,
					],
				],
				'default' => [
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-hero-overlay' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_responsive_control(
			'hero_min_height',
			[
				'label' => esc_html__( 'Min Height', 'mn-elements' ),
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
					'unit' => 'vh',
					'size' => 70,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-hero-tabber' => 'min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
}

/**
 * Get available post types
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
 * Get Elementor templates
 */
protected function get_elementor_templates() {
	$templates = get_posts( [
		'post_type' => 'elementor_library',
		'posts_per_page' => -1,
		'post_status' => 'publish',
	] );

	$options = [ '0' => esc_html__( 'Select Template', 'mn-elements' ) ];
	foreach ( $templates as $template ) {
		$options[ $template->ID ] = $template->post_title;
	}
	
	return $options;
}

/**
 * Get tabs data (static or dynamic)
 */
protected function get_tabs_data() {
	$settings = $this->get_settings_for_display();
	$tabs_data = [];

	if ( $settings['tab_type'] === 'dynamic' ) {
		// Dynamic query
		$query_args = [
				'post_type' => $settings['post_type'],
				'posts_per_page' => $settings['posts_per_page'],
				'orderby' => $settings['orderby'],
				'order' => $settings['order'],
				'post_status' => 'publish',
				'ignore_sticky_posts' => true,
		];

			$query = new \WP_Query( $query_args );

			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					$post_id = get_the_ID();

					$tabs_data[] = [
						'tab_title' => get_the_title(),
						'tab_description' => get_the_excerpt(),
						'content_type' => 'content',
						'tab_background_image' => [
							'url' => get_the_post_thumbnail_url( $post_id, 'full' ),
						],
						'tab_content' => get_the_content(),
						'button_label' => esc_html__( 'Read More', 'mn-elements' ),
						'custom_url' => [
							'url' => get_permalink(),
						],
					];
				}
				wp_reset_postdata();
			}
		} else {
			// Static tabs
			$tabs_data = $settings['tabs'];
		}

		return $tabs_data;
	}

	/**
	 * Render the widget
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$tabs_data = $this->get_tabs_data();

		if ( empty( $tabs_data ) ) {
			return;
		}

		$widget_id = $this->get_id();
		$template_type = $settings['template_type'];
		$tab_position = isset( $settings['tab_position'] ) ? $settings['tab_position'] : '';
		$hero_trigger = $settings['hero_trigger'];

		if ( $template_type === 'hero' ) {
			$this->render_hero_template( $tabs_data, $widget_id, $hero_trigger, $tab_position, $settings );
		} else {
			$this->render_default_template( $tabs_data, $widget_id, $tab_position, $settings );
		}
	}

	/**
	 * Render Default Template
	 */
	protected function render_default_template( $tabs_data, $widget_id, $tab_position, $settings ) {
		$wrapper_class = 'mn-dynamic-tabs mn-template-default';
		if ( ! empty( $tab_position ) ) {
			$wrapper_class .= ' mn-tab-position-' . esc_attr( $tab_position );
		}
		?>
		<div class="<?php echo esc_attr( $wrapper_class ); ?>" data-widget-id="<?php echo esc_attr( $widget_id ); ?>" data-position="<?php echo esc_attr( $tab_position ); ?>">
			<!-- Tab Navigation -->
			<div class="mn-tabs-nav" role="tablist">
				<?php foreach ( $tabs_data as $index => $tab ) : 
					$icon_position = ! empty( $tab['tab_icon_position'] ) ? $tab['tab_icon_position'] : 'left';
					$has_icon = ! empty( $tab['tab_icon']['value'] );
					$item_class = 'mn-tab-item';
					if ( $has_icon ) {
						$item_class .= ' mn-tab-has-icon mn-tab-icon-position-' . $icon_position;
					}
					$item_class .= $index === 0 ? ' active' : '';
				?>
					<div class="<?php echo esc_attr( $item_class ); ?>" 
						 data-tab="<?php echo esc_attr( $index ); ?>" 
						 role="tab" 
						 aria-selected="<?php echo $index === 0 ? 'true' : 'false'; ?>"
						 tabindex="<?php echo $index === 0 ? '0' : '-1'; ?>">
						<?php
						// Render icon and title based on position
						if ( $has_icon && ( $icon_position === 'top' || $icon_position === 'left' ) ) {
							$this->render_tab_icon( $tab, $icon_position );
						}
						
						// Render title
						if ( $settings['show_tab_title'] === 'yes' ) : ?>
							<span class="mn-tab-title"><?php echo esc_html( $tab['tab_title'] ); ?></span>
						<?php endif;
						
						// Render icon for right/bottom positions
						if ( $has_icon && ( $icon_position === 'right' || $icon_position === 'bottom' ) ) {
							$this->render_tab_icon( $tab, $icon_position );
						}
						?>
						
						<?php if ( $settings['show_tab_description'] === 'yes' && ! empty( $tab['tab_description'] ) ) : ?>
							<span class="mn-tab-description"><?php echo esc_html( $tab['tab_description'] ); ?></span>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>

			<!-- Tab Content -->
			<div class="mn-tabs-content">
				<?php foreach ( $tabs_data as $index => $tab ) : ?>
					<div class="mn-tab-content<?php echo $index === 0 ? ' active' : ''; ?>" 
						 data-tab="<?php echo esc_attr( $index ); ?>" 
						 role="tabpanel" 
						 aria-hidden="<?php echo $index === 0 ? 'false' : 'true'; ?>">
						<?php $this->render_tab_content( $tab, $settings ); ?>
						<?php if ( $settings['show_tab_button'] === 'yes' && ! empty( $tab['button_label'] ) && ! empty( $tab['custom_url']['url'] ) ) : ?>
							<a href="<?php echo esc_url( $tab['custom_url']['url'] ); ?>" 
							   class="mn-tab-button"
							   <?php echo ! empty( $tab['custom_url']['is_external'] ) ? 'target="_blank"' : ''; ?>
							   <?php echo ! empty( $tab['custom_url']['nofollow'] ) ? 'rel="nofollow"' : ''; ?>>
								<?php echo esc_html( $tab['button_label'] ); ?>
							</a>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render individual tab content based on content type
	 */
	protected function render_tab_content( $tab, $settings ) {
		$content_type = isset( $tab['content_type'] ) ? $tab['content_type'] : 'content';
		
		switch ( $content_type ) {
			case 'template':
				$this->render_template_content( $tab );
				break;
			case 'video':
				$this->render_video_content( $tab );
				break;
			case 'content':
			default:
				$this->render_text_content( $tab );
				break;
		}
	}

	/**
	 * Render text content
	 */
	protected function render_text_content( $tab ) {
		echo wp_kses_post( $tab['tab_content'] );
	}

	/**
	 * Render Elementor template content
	 */
	protected function render_template_content( $tab ) {
		if ( ! empty( $tab['template_id'] ) && $tab['template_id'] != '0' ) {
			$template_id = intval( $tab['template_id'] );
			$frontend = \Elementor\Plugin::$instance->frontend;
			echo $frontend->get_builder_content_for_display( $template_id );
		} else {
			echo '<p>' . esc_html__( 'Please select a template.', 'mn-elements' ) . '</p>';
		}
	}

	/**
	 * Render video background content
	 */
	protected function render_video_content( $tab ) {
		if ( empty( $tab['video_url'] ) ) {
			echo '<p>' . esc_html__( 'Please provide a video URL.', 'mn-elements' ) . '</p>';
			return;
		}
		
		$video_url = esc_url( $tab['video_url'] );
		$poster_image = ! empty( $tab['video_poster']['url'] ) ? esc_url( $tab['video_poster']['url'] ) : '';
		$overlay_content = ! empty( $tab['video_overlay_content'] ) ? $tab['video_overlay_content'] : '';
		
		?>
		<div class="mn-video-container">
			<video class="mn-video-background" 
				   autoplay 
				   muted 
				   loop 
				   playsinline
				   <?php echo ! empty( $poster_image ) ? 'poster="' . $poster_image . '"' : ''; ?>>
				<source src="<?php echo $video_url; ?>" type="video/mp4">
				<?php echo esc_html__( 'Your browser does not support the video tag.', 'mn-elements' ); ?>
			</video>
			
			<?php if ( ! empty( $overlay_content ) ) : ?>
				<div class="mn-video-overlay">
					<?php echo wp_kses_post( $overlay_content ); ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Render tab icon
	 */
	protected function render_tab_icon( $tab, $position ) {
		if ( empty( $tab['tab_icon']['value'] ) ) {
			return;
		}

		$icon_class = 'mn-tab-icon mn-tab-icon-' . esc_attr( $position );
		?>
		<span class="<?php echo esc_attr( $icon_class ); ?>">
			<?php \Elementor\Icons_Manager::render_icon( $tab['tab_icon'], [ 'aria-hidden' => 'true' ] ); ?>
		</span>
		<?php
	}

	/**
	 * Render Hero Template
	 */
	protected function render_hero_template( $tabs_data, $widget_id, $hero_trigger, $tab_position, $settings ) {
		$wrapper_class = 'mn-dynamic-tabs mn-template-hero mn-hero-trigger-' . esc_attr( $hero_trigger );
		if ( ! empty( $tab_position ) ) {
			$wrapper_class .= ' mn-tab-position-' . esc_attr( $tab_position );
		}
		?>
		<div class="<?php echo esc_attr( $wrapper_class ); ?>" data-widget-id="<?php echo esc_attr( $widget_id ); ?>" data-position="<?php echo esc_attr( $tab_position ); ?>">
			<div class="mn-hero-tabber">
				<!-- Background Images -->
				<div class="mn-hero-backgrounds">
					<?php foreach ( $tabs_data as $index => $tab ) : ?>
						<?php if ( ! empty( $tab['tab_background_image']['url'] ) ) : ?>
							<div class="mn-hero-bg<?php echo $index === 0 ? ' active' : ''; ?>" 
								 data-tab="<?php echo esc_attr( $index ); ?>"
								 style="background-image: url(<?php echo esc_url( $tab['tab_background_image']['url'] ); ?>);"></div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>

				<!-- Overlay -->
				<div class="mn-hero-overlay"></div>

				<!-- Content Container -->
				<div class="mn-hero-container">
					<!-- Tab Navigation -->
					<div class="mn-tabs-nav" role="tablist">
						<?php foreach ( $tabs_data as $index => $tab ) : 
							$icon_position = ! empty( $tab['tab_icon_position'] ) ? $tab['tab_icon_position'] : 'left';
							$has_icon = ! empty( $tab['tab_icon']['value'] );
							$item_class = 'mn-tab-item';
							if ( $has_icon ) {
								$item_class .= ' mn-tab-has-icon mn-tab-icon-position-' . $icon_position;
							}
							$item_class .= $index === 0 ? ' active' : '';
						?>
							<div class="<?php echo esc_attr( $item_class ); ?>" 
								 data-tab="<?php echo esc_attr( $index ); ?>" 
								 role="tab" 
								 aria-selected="<?php echo $index === 0 ? 'true' : 'false'; ?>"
								 tabindex="<?php echo $index === 0 ? '0' : '-1'; ?>">
								<div class="mn-tab-item-content">
									<?php
									// Render icon and title based on position
									if ( $has_icon && ( $icon_position === 'top' || $icon_position === 'left' ) ) {
										$this->render_tab_icon( $tab, $icon_position );
									}
									
									// Render title
									if ( $settings['show_tab_title'] === 'yes' ) : ?>
										<span class="mn-tab-title"><?php echo esc_html( $tab['tab_title'] ); ?></span>
									<?php endif;
									
									// Render icon for right/bottom positions
									if ( $has_icon && ( $icon_position === 'right' || $icon_position === 'bottom' ) ) {
										$this->render_tab_icon( $tab, $icon_position );
									}
									?>
									
									<?php if ( $settings['show_tab_description'] === 'yes' && ! empty( $tab['tab_description'] ) ) : ?>
										<span class="mn-tab-description"><?php echo esc_html( $tab['tab_description'] ); ?></span>
									<?php endif; ?>
									<?php if ( $settings['show_tab_button'] === 'yes' && ! empty( $tab['button_label'] ) && ! empty( $tab['custom_url']['url'] ) ) : ?>
										<a href="<?php echo esc_url( $tab['custom_url']['url'] ); ?>" 
										   class="mn-tab-button"
										   <?php echo ! empty( $tab['custom_url']['is_external'] ) ? 'target="_blank"' : ''; ?>
										   <?php echo ! empty( $tab['custom_url']['nofollow'] ) ? 'rel="nofollow"' : ''; ?>>
											<?php echo esc_html( $tab['button_label'] ); ?>
										</a>
									<?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
