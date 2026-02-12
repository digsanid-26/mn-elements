<?php
namespace MN_Elements\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

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
		return [ 'tabs', 'dynamic', 'posts', 'query', 'grid', 'slide' ];
	}

	protected function register_controls() {
		$start = is_rtl() ? 'end' : 'start';
		$end = is_rtl() ? 'start' : 'end';
		
		// Query Panel - Moved before Tab Manager
		$this->start_controls_section(
			'section_query',
			[
				'label' => esc_html__( 'Query', 'mn-elements' ),
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
				'default' => 6,
				'min' => 1,
				'max' => 100,
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

		// Tab Manager Panel
		$this->start_controls_section(
			'section_tab_manager',
			[
				'label' => esc_html__( 'Tab Manager', 'mn-elements' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'tab_title',
			[
				'label' => esc_html__( 'Tab Title', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Tab Title', 'mn-elements' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'tab_description',
			[
				'label' => esc_html__( 'Tab Description', 'mn-elements' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Tab description text', 'mn-elements' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'content_type',
			[
				'label' => esc_html__( 'Content Type', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'query',
				'options' => [
					'query' => esc_html__( 'Use Global Query', 'mn-elements' ),
					'post_ids' => esc_html__( 'Specific Post IDs', 'mn-elements' ),
					'taxonomy' => esc_html__( 'Taxonomy Filter', 'mn-elements' ),
				],
			]
		);

		$repeater->add_control(
			'post_ids',
			[
				'label' => esc_html__( 'Post IDs', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( '1,2,3,4', 'mn-elements' ),
				'description' => esc_html__( 'Enter comma-separated post IDs', 'mn-elements' ),
				'condition' => [
					'content_type' => 'post_ids',
				],
			]
		);

		$repeater->add_control(
			'taxonomy',
			[
				'label' => esc_html__( 'Taxonomy', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_taxonomies(),
				'condition' => [
					'content_type' => 'taxonomy',
				],
			]
		);

		$repeater->add_control(
			'taxonomy_ids',
			[
				'label' => esc_html__( 'Term IDs', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( '1,2,3', 'mn-elements' ),
				'description' => esc_html__( 'Enter comma-separated term IDs', 'mn-elements' ),
				'condition' => [
					'content_type' => 'taxonomy',
				],
			]
		);

		$this->add_control(
			'tabs',
			[
				'label' => esc_html__( 'Tabs', 'mn-elements' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'tab_title' => esc_html__( 'Recent Posts', 'mn-elements' ),
						'tab_description' => esc_html__( 'Latest posts from the blog', 'mn-elements' ),
						'content_type' => 'query',
					],
					[
						'tab_title' => esc_html__( 'Featured Posts', 'mn-elements' ),
						'tab_description' => esc_html__( 'Featured category posts', 'mn-elements' ),
						'content_type' => 'taxonomy',
						'taxonomy' => 'category',
						'taxonomy_ids' => '1',
					],
				],
				'title_field' => '{{{ tab_title }}}',
			]
		);

		$this->end_controls_section();

		// Layout Panel
		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Layout', 'mn-elements' ),
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label' => esc_html__( 'Columns', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-dynamic-tabs-content' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				],
			]
		);

		$this->add_control(
			'show_image',
			[
				'label' => esc_html__( 'Show Featured Image', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'mn-elements' ),
				'label_off' => esc_html__( 'Hide', 'mn-elements' ),
				'return_value' => 'yes',
				'default' => 'yes',
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
			'show_excerpt',
			[
				'label' => esc_html__( 'Show Excerpt', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'mn-elements' ),
				'label_off' => esc_html__( 'Hide', 'mn-elements' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_read_more',
			[
				'label' => esc_html__( 'Show Read More', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'mn-elements' ),
				'label_off' => esc_html__( 'Hide', 'mn-elements' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->end_controls_section();

		// Content Slider Panel
		$this->start_controls_section(
			'section_content_slider',
			[
				'label' => esc_html__( 'Content Slider', 'mn-elements' ),
			]
		);

		$this->add_control(
			'enable_content_slider',
			[
				'label' => esc_html__( 'Enable Content Slider', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'mn-elements' ),
				'label_off' => esc_html__( 'No', 'mn-elements' ),
				'return_value' => 'yes',
				'default' => 'no',
				'description' => esc_html__( 'Enable slider for posts within each tab content', 'mn-elements' ),
			]
		);

		$this->add_control(
			'slider_autoplay',
			[
				'label' => esc_html__( 'Autoplay', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'mn-elements' ),
				'label_off' => esc_html__( 'No', 'mn-elements' ),
				'return_value' => 'yes',
				'default' => 'no',
				'condition' => [
					'enable_content_slider' => 'yes',
				],
			]
		);

		$this->add_control(
			'slider_speed',
			[
				'label' => esc_html__( 'Autoplay Speed (ms)', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 3000,
				'min' => 1000,
				'max' => 10000,
				'step' => 500,
				'condition' => [
					'enable_content_slider' => 'yes',
					'slider_autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'slider_pause_on_hover',
			[
				'label' => esc_html__( 'Pause on Hover', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'mn-elements' ),
				'label_off' => esc_html__( 'No', 'mn-elements' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'enable_content_slider' => 'yes',
					'slider_autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'slides_to_show',
			[
				'label' => esc_html__( 'Slides to Show', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => '3',
				'options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
				'condition' => [
					'enable_content_slider' => 'yes',
				],
			]
		);

		$this->add_control(
			'slides_to_scroll',
			[
				'label' => esc_html__( 'Slides to Scroll', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => '1',
				'options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
				],
				'condition' => [
					'enable_content_slider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'slider_gap',
			[
				'label' => esc_html__( 'Gap', 'mn-elements' ),
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
					'size' => 20,
				],
				'condition' => [
					'enable_content_slider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-content-slider .mn-post-item' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-content-slider .mn-post-item:last-child' => 'margin-right: 0;',
				],
			]
		);

		$this->add_control(
			'show_navigation',
			[
				'label' => esc_html__( 'Show Navigation', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'mn-elements' ),
				'label_off' => esc_html__( 'Hide', 'mn-elements' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'enable_content_slider' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_dots',
			[
				'label' => esc_html__( 'Show Dots', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'mn-elements' ),
				'label_off' => esc_html__( 'Hide', 'mn-elements' ),
				'return_value' => 'yes',
				'default' => 'no',
				'condition' => [
					'enable_content_slider' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		// Style Controls
		$this->register_style_controls();
	}

	protected function register_style_controls() {
		$start = is_rtl() ? 'end' : 'start';
		$end = is_rtl() ? 'start' : 'end';
		
		// Tab Control Panel
		$this->start_controls_section(
			'section_tab_control',
			[
				'label' => esc_html__( 'Tab Control', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		// Layout & Position Heading
		$this->add_control(
			'tab_layout_heading',
			[
				'label' => esc_html__( 'Layout & Position', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'tabs_direction',
			[
				'label' => esc_html__( 'Direction', 'mn-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'horizontal',
				'options' => [
					'vertical' => [
						'title' => esc_html__( 'Vertical', 'mn-elements' ),
						'icon' => 'eicon-h-align-' . ( is_rtl() ? 'right' : 'left' ),
					],
					'horizontal' => [
						'title' => esc_html__( 'Horizontal', 'mn-elements' ),
						'icon' => 'eicon-v-align-top',
					],
				],
				'prefix_class' => 'mn-tabs-view-',
				'separator' => 'after',
			]
		);

		$this->add_control(
			'tabs_align_horizontal',
			[
				'label' => esc_html__( 'Alignment', 'mn-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'' => [
						'title' => esc_html__( 'Start', 'mn-elements' ),
						'icon' => "eicon-align-$start-h",
					],
					'center' => [
						'title' => esc_html__( 'Center', 'mn-elements' ),
						'icon' => 'eicon-align-center-h',
					],
					'end' => [
						'title' => esc_html__( 'End', 'mn-elements' ),
						'icon' => "eicon-align-$end-h",
					],
					'stretch' => [
						'title' => esc_html__( 'Stretch', 'mn-elements' ),
						'icon' => 'eicon-align-stretch-h',
					],
				],
				'prefix_class' => 'mn-tabs-alignment-',
				'condition' => [
					'tabs_direction' => 'horizontal',
				],
			]
		);

		$this->add_control(
			'tabs_align_vertical',
			[
				'label' => esc_html__( 'Alignment', 'mn-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'' => [
						'title' => esc_html__( 'Start', 'mn-elements' ),
						'icon' => 'eicon-align-start-v',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'mn-elements' ),
						'icon' => 'eicon-align-center-v',
					],
					'end' => [
						'title' => esc_html__( 'End', 'mn-elements' ),
						'icon' => 'eicon-align-end-v',
					],
					'stretch' => [
						'title' => esc_html__( 'Stretch', 'mn-elements' ),
						'icon' => 'eicon-align-stretch-v',
					],
				],
				'prefix_class' => 'mn-tabs-alignment-',
				'condition' => [
					'tabs_direction' => 'vertical',
				],
			]
		);

		$this->add_control(
			'tabs_text_align',
			[
				'label' => esc_html__( 'Text Alignment', 'mn-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'mn-elements' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'mn-elements' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'mn-elements' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .mn-tab-item' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'navigation_width',
			[
				'label' => esc_html__( 'Navigation Width', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'unit' => '%',
				],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 500,
					],
					'%' => [
						'min' => 10,
						'max' => 50,
					],
					'em' => [
						'min' => 1,
						'max' => 50,
					],
					'rem' => [
						'min' => 1,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mn-tabs-nav' => 'width: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'tabs_direction' => 'vertical',
				],
			]
		);

		// Spacing & Dimensions Heading
		$this->add_control(
			'tab_spacing_heading',
			[
				'label' => esc_html__( 'Spacing & Dimensions', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tabs_margin',
			[
				'label' => esc_html__( 'Margin', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .mn-tabs-nav' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'tabs_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .mn-tab-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'tabs_gap',
			[
				'label' => esc_html__( 'Gap Between Tabs', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'em' => [
						'min' => 0,
						'max' => 10,
					],
					'rem' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-tabs-nav' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Border & Style Heading
		$this->add_control(
			'tab_border_heading',
			[
				'label' => esc_html__( 'Border & Style', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
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
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .mn-tab-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'tab_border_width_old',
			[
				'label' => esc_html__( 'Border Width (Legacy)', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'default' => [
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-tab-item' => 'border-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Typography & Colors Heading
		$this->add_control(
			'tab_typography_heading',
			[
				'label' => esc_html__( 'Typography & Colors', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tab_typography',
				'label' => esc_html__( 'Typography', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-tab-title',
			]
		);

		// Tab States
		$this->start_controls_tabs( 'tabs_style_tabs' );

		// Normal State
		$this->start_controls_tab(
			'tab_style_normal',
			[
				'label' => esc_html__( 'Normal', 'mn-elements' ),
			]
		);

		$this->add_control(
			'tab_text_color',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-tab-item .mn-tab-title' => 'color: {{VALUE}};',
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

		$this->add_control(
			'tab_border_color',
			[
				'label' => esc_html__( 'Border Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-tab-item' => 'border-color: {{VALUE}};',
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
			'tab_text_color_hover',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-tab-item:hover .mn-tab-title' => 'color: {{VALUE}};',
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

		$this->add_control(
			'tab_border_color_hover',
			[
				'label' => esc_html__( 'Border Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-tab-item:hover' => 'border-color: {{VALUE}};',
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
			'tab_text_color_active',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-tab-item.active .mn-tab-title' => 'color: {{VALUE}};',
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

		$this->add_control(
			'tab_border_color_active',
			[
				'label' => esc_html__( 'Border Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-tab-item.active' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Tab Text Style Section
		$this->start_controls_section(
			'section_tab_text_style',
			[
				'label' => esc_html__( 'Tab Text', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'tab_margin',
			[
				'label' => esc_html__( 'Margin', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-tab-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
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

		// Active Tab Style
		$this->start_controls_section(
			'section_tabs_active_style',
			[
				'label' => esc_html__( 'Active Tab', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'active_tab_border_color',
			[
				'label' => esc_html__( 'Border Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-tab-item.active' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'active_tab_background_color',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-tab-item.active' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'active_tab_text_color',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-tab-item.active .mn-tab-title' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-tab-item.active .mn-tab-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Tab Content Style
		$this->start_controls_section(
			'section_content_style',
			[
				'label' => esc_html__( 'Content', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'content_background_color',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-tabs-content' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'content_border',
				'selector' => '{{WRAPPER}} .mn-tabs-content',
			]
		);

		$this->add_control(
			'content_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-tabs-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .mn-tabs-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_box_shadow',
				'selector' => '{{WRAPPER}} .mn-tabs-content',
			]
		);

		$this->end_controls_section();

		// Post Items Style
		$this->start_controls_section(
			'section_post_items_style',
			[
				'label' => esc_html__( 'Post Items', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'posts_gap',
			[
				'label' => esc_html__( 'Gap', 'mn-elements' ),
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
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-dynamic-tabs-content' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'post_background_color',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-post-item' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'post_border',
				'selector' => '{{WRAPPER}} .mn-post-item',
			]
		);

		$this->add_control(
			'post_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-post-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'post_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-post-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'post_box_shadow',
				'selector' => '{{WRAPPER}} .mn-post-item',
			]
		);

		$this->end_controls_section();

		// Post Title Style
		$this->start_controls_section(
			'section_post_title_style',
			[
				'label' => esc_html__( 'Post Title', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'post_title_typography',
				'selector' => '{{WRAPPER}} .mn-post-title',
			]
		);

		$this->add_control(
			'post_title_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-post-title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'post_title_hover_color',
			[
				'label' => esc_html__( 'Hover Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-post-title a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'post_title_spacing',
			[
				'label' => esc_html__( 'Spacing', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mn-post-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Post Excerpt Style
		$this->start_controls_section(
			'section_post_excerpt_style',
			[
				'label' => esc_html__( 'Post Excerpt', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'post_excerpt_typography',
				'selector' => '{{WRAPPER}} .mn-post-excerpt',
			]
		);

		$this->add_control(
			'post_excerpt_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-post-excerpt' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'post_excerpt_spacing',
			[
				'label' => esc_html__( 'Spacing', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mn-post-excerpt' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Read More Button Style
		$this->start_controls_section(
			'section_readmore_style',
			[
				'label' => esc_html__( 'Read More Button', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'readmore_typography',
				'selector' => '{{WRAPPER}} .mn-readmore-btn',
			]
		);

		$this->start_controls_tabs( 'tabs_readmore_style' );

		$this->start_controls_tab(
			'tab_readmore_normal',
			[
				'label' => esc_html__( 'Normal', 'mn-elements' ),
			]
		);

		$this->add_control(
			'readmore_text_color',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-readmore-btn' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'readmore_background_color',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-readmore-btn' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_readmore_hover',
			[
				'label' => esc_html__( 'Hover', 'mn-elements' ),
			]
		);

		$this->add_control(
			'readmore_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-readmore-btn:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'readmore_background_hover_color',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-readmore-btn:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'readmore_border',
				'selector' => '{{WRAPPER}} .mn-readmore-btn',
			]
		);

		$this->add_control(
			'readmore_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-readmore-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'readmore_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-readmore-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function get_post_types() {
		$post_types = get_post_types( [ 'public' => true ], 'objects' );
		$options = [];
		
		foreach ( $post_types as $post_type ) {
			$options[ $post_type->name ] = $post_type->label;
		}
		
		return $options;
	}

	protected function get_taxonomies() {
		$taxonomies = get_taxonomies( [ 'public' => true ], 'objects' );
		$options = [ '' => esc_html__( 'Select Taxonomy', 'mn-elements' ) ];
		
		foreach ( $taxonomies as $taxonomy ) {
			$options[ $taxonomy->name ] = $taxonomy->label;
		}
		
		return $options;
	}

	protected function get_tab_posts( $tab, $settings ) {
		$args = [
			'post_type' => $settings['post_type'],
			'posts_per_page' => $settings['posts_per_page'],
			'orderby' => $settings['orderby'],
			'order' => $settings['order'],
			'post_status' => 'publish',
		];

		// Handle different content types
		if ( $tab['content_type'] === 'post_ids' && ! empty( $tab['post_ids'] ) ) {
			$post_ids = array_map( 'trim', explode( ',', $tab['post_ids'] ) );
			$post_ids = array_filter( array_map( 'intval', $post_ids ) );
			if ( ! empty( $post_ids ) ) {
				$args['post__in'] = $post_ids;
				$args['orderby'] = 'post__in';
			}
		} elseif ( $tab['content_type'] === 'taxonomy' && ! empty( $tab['taxonomy'] ) && ! empty( $tab['taxonomy_ids'] ) ) {
			$term_ids = array_map( 'trim', explode( ',', $tab['taxonomy_ids'] ) );
			$term_ids = array_filter( array_map( 'intval', $term_ids ) );
			if ( ! empty( $term_ids ) ) {
				$args['tax_query'] = [
					[
						'taxonomy' => $tab['taxonomy'],
						'field' => 'term_id',
						'terms' => $term_ids,
					],
				];
			}
		}
		// For 'query' content type, use global settings as is

		$query = new \WP_Query( $args );
		$posts = [];
		
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$posts[] = get_post();
			}
			wp_reset_postdata();
		}
		
		return $posts;
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$tabs = $settings['tabs'];

		if ( empty( $tabs ) ) {
			return;
		}

		$widget_id = $this->get_id();
		
		// Prepare data attributes for JavaScript
		$data_attrs = [
			'data-widget-id' => $widget_id,
			'data-enable-slider' => $settings['enable_content_slider'] === 'yes' ? 'true' : 'false',
			'data-autoplay' => $settings['slider_autoplay'] === 'yes' ? 'true' : 'false',
			'data-speed' => $settings['slider_speed'] ?? 3000,
			'data-pause-hover' => $settings['slider_pause_on_hover'] === 'yes' ? 'true' : 'false',
			'data-slides-to-show' => $settings['slides_to_show'] ?? 3,
			'data-slides-to-scroll' => $settings['slides_to_scroll'] ?? 1,
			'data-show-navigation' => $settings['show_navigation'] === 'yes' ? 'true' : 'false',
			'data-show-dots' => $settings['show_dots'] === 'yes' ? 'true' : 'false',
		];
		
		$data_string = '';
		foreach ( $data_attrs as $key => $value ) {
			$data_string .= ' ' . $key . '="' . esc_attr( $value ) . '"';
		}
		?>
		<div class="mn-dynamic-tabs"<?php echo $data_string; ?>>
			<!-- Tab Navigation -->
			<div class="mn-tabs-nav">
				<?php foreach ( $tabs as $index => $tab ) : ?>
					<div class="mn-tab-item <?php echo $index === 0 ? 'active' : ''; ?>" 
						 data-tab="<?php echo esc_attr( $index ); ?>"
						 role="tab"
						 tabindex="<?php echo $index === 0 ? '0' : '-1'; ?>"
						 aria-selected="<?php echo $index === 0 ? 'true' : 'false'; ?>">
						<h3 class="mn-tab-title"><?php echo esc_html( $tab['tab_title'] ); ?></h3>
						<?php if ( ! empty( $tab['tab_description'] ) ) : ?>
							<p class="mn-tab-description"><?php echo esc_html( $tab['tab_description'] ); ?></p>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>

			<!-- Tab Content -->
			<div class="mn-tabs-content">
				<?php foreach ( $tabs as $index => $tab ) : ?>
					<div class="mn-tab-content <?php echo $index === 0 ? 'active' : ''; ?>" 
						 data-tab="<?php echo esc_attr( $index ); ?>"
						 data-tab-title="<?php echo esc_attr( $tab['tab_title'] ); ?>"
						 role="tabpanel"
						 aria-hidden="<?php echo $index === 0 ? 'false' : 'true'; ?>">
						
						<!-- Always use slider wrapper, but conditionally enable slider functionality -->
						<div class="mn-content-slider-wrapper">
							<div class="mn-content-slider" data-tab-index="<?php echo esc_attr( $index ); ?>">
								<?php 
								// Always use slider layout, but conditionally enable slider functionality
								$content_class = 'mn-dynamic-tabs-slider';
								$is_slider_disabled = $settings['enable_content_slider'] !== 'yes';
								if ( $is_slider_disabled ) {
									$content_class .= ' slider-disabled';
								}
								?>
								<div class="<?php echo esc_attr( $content_class ); ?>">
									<?php
									$posts = $this->get_tab_posts( $tab, $settings );
									if ( ! empty( $posts ) ) :
										foreach ( $posts as $post_index => $post ) :
											setup_postdata( $post );
											?>
											<div class="mn-post-item" data-post-index="<?php echo esc_attr( $post_index ); ?>">
												<?php if ( $settings['show_image'] === 'yes' && has_post_thumbnail( $post->ID ) ) : ?>
													<div class="mn-post-thumbnail">
														<a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>">
															<?php echo get_the_post_thumbnail( $post->ID, 'medium' ); ?>
														</a>
													</div>
												<?php endif; ?>

												<div class="mn-post-content">
											<?php if ( $settings['show_title'] === 'yes' ) : ?>
												<h3 class="mn-post-title">
													<a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>">
														<?php echo esc_html( get_the_title( $post->ID ) ); ?>
													</a>
												</h3>
											<?php endif; ?>

											<?php if ( $settings['show_excerpt'] === 'yes' ) : ?>
												<div class="mn-post-excerpt">
													<?php echo wp_trim_words( get_the_excerpt( $post->ID ), 20 ); ?>
												</div>
											<?php endif; ?>

											<?php if ( $settings['show_read_more'] === 'yes' ) : ?>
												<div class="mn-post-readmore">
													<a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>" class="mn-readmore-btn">
														<?php echo esc_html__( 'Read More', 'mn-elements' ); ?>
													</a>
												</div>
											<?php endif; ?>
										</div>
									</div>
										<?php
									endforeach;
									wp_reset_postdata();
								else :
									?>
									<div class="mn-no-posts">
										<?php echo esc_html__( 'No posts found.', 'mn-elements' ); ?>
									</div>
									<?php
								endif;
								?>
								</div>
								
								<!-- Always show navigation controls, but conditionally enable them -->
								<?php 
								$slides_to_show = 3; // Default desktop
								$show_navigation = $settings['enable_content_slider'] === 'yes' && $settings['show_navigation'] === 'yes';
								$show_dots = $settings['enable_content_slider'] === 'yes' && $settings['show_dots'] === 'yes';
								?>
								
								<!-- Navigation Controls -->
								<?php if ( $show_navigation && count( $posts ) > $slides_to_show ) : ?>
									<div class="mn-slider-controls">
										<button class="mn-slider-prev" aria-label="<?php echo esc_attr__( 'Previous', 'mn-elements' ); ?>">
											<i class="eicon-chevron-left"></i>
										</button>
										<button class="mn-slider-next" aria-label="<?php echo esc_attr__( 'Next', 'mn-elements' ); ?>">
											<i class="eicon-chevron-right"></i>
										</button>
									</div>
								<?php endif; ?>
								
								<!-- Dots Indicator -->
								<?php if ( $show_dots && count( $posts ) > $slides_to_show ) : ?>
									<div class="mn-slider-dots">
										<?php 
										$total_slides = ceil( count( $posts ) / $settings['slides_to_scroll'] );
										for ( $i = 0; $i < $total_slides; $i++ ) : ?>
											<button class="mn-slider-dot <?php echo $i === 0 ? 'active' : ''; ?>" 
													data-slide="<?php echo esc_attr( $i ); ?>"
													aria-label="<?php echo esc_attr( sprintf( __( 'Go to slide %d', 'mn-elements' ), $i + 1 ) ); ?>">
											</button>
										<?php endfor; ?>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}
}
