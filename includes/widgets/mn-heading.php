<?php
namespace MN_Elements\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * MN Heading Widget
 *
 * Multi-part heading widget with gradient effects and individual styling
 *
 * @since 1.5.9
 */
class MN_Heading extends Widget_Base {

	/**
	 * Get widget name.
	 */
	public function get_name() {
		return 'mn-heading';
	}

	/**
	 * Get widget title.
	 */
	public function get_title() {
		return esc_html__( 'MN Heading', 'mn-elements' );
	}

	/**
	 * Get widget icon.
	 */
	public function get_icon() {
		return 'eicon-heading';
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
		return [ 'heading', 'title', 'text', 'gradient', 'multi-part', 'mn' ];
	}

	/**
	 * Get style depends.
	 */
	public function get_style_depends() {
		return [ 'mn-heading' ];
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
		// Heading Parts Section
		$this->start_controls_section(
			'section_heading_parts',
			[
				'label' => esc_html__( 'Heading Parts', 'mn-elements' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'text',
			[
				'label' => esc_html__( 'Text', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Heading Part', 'mn-elements' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'text_source',
			[
				'label' => esc_html__( 'Text Source', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'static',
				'options' => [
					'static' => esc_html__( 'Static', 'mn-elements' ),
					'dynamic' => esc_html__( 'Dynamic', 'mn-elements' ),
				],
			]
		);

		$repeater->add_control(
			'dynamic_field',
			[
				'label' => esc_html__( 'Dynamic Field', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'post_title',
				'options' => [
					'post_title' => esc_html__( 'Post Title', 'mn-elements' ),
					'post_excerpt' => esc_html__( 'Post Excerpt', 'mn-elements' ),
					'post_date' => esc_html__( 'Post Date', 'mn-elements' ),
					'post_author' => esc_html__( 'Post Author', 'mn-elements' ),
					'site_title' => esc_html__( 'Site Title', 'mn-elements' ),
					'site_tagline' => esc_html__( 'Site Tagline', 'mn-elements' ),
					'custom_field' => esc_html__( 'Custom Field', 'mn-elements' ),
				],
				'condition' => [
					'text_source' => 'dynamic',
				],
			]
		);

		$repeater->add_control(
			'custom_field_name',
			[
				'label' => esc_html__( 'Custom Field Name', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'field_name', 'mn-elements' ),
				'description' => esc_html__( 'Enter the meta key/field name. Supports JetEngine, ACF, and WordPress custom fields.', 'mn-elements' ),
				'condition' => [
					'text_source' => 'dynamic',
					'dynamic_field' => 'custom_field',
				],
			]
		);

		$repeater->add_control(
			'line_break',
			[
				'label' => esc_html__( 'Line Break After', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'description' => esc_html__( 'Add line break after this part', 'mn-elements' ),
			]
		);

		$repeater->add_responsive_control(
			'heading_gap',
			[
				'label' => esc_html__( 'Heading Gap', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'em' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'description' => esc_html__( 'Vertical spacing after this part when line break is enabled', 'mn-elements' ),
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}.mn-has-line-break' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'line_break' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'use_individual_style',
			[
				'label' => esc_html__( 'Individual Style', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'separator' => 'before',
			]
		);

		// Icon Controls
		$repeater->add_control(
			'show_icon',
			[
				'label' => esc_html__( 'Show Icon', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'condition' => [
					'use_individual_style' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'selected_icon',
			[
				'label' => esc_html__( 'Icon', 'mn-elements' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'fa-solid',
				],
				'condition' => [
					'use_individual_style' => 'yes',
					'show_icon' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'icon_position',
			[
				'label' => esc_html__( 'Icon Position', 'mn-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'mn-elements' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'mn-elements' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => 'left',
				'condition' => [
					'use_individual_style' => 'yes',
					'show_icon' => 'yes',
				],
			]
		);

		// Individual Link
		$repeater->add_control(
			'individual_link',
			[
				'label' => esc_html__( 'Link', 'mn-elements' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'mn-elements' ),
				'condition' => [
					'use_individual_style' => 'yes',
				],
			]
		);

		// Individual Typography
		$repeater->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'individual_typography',
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}',
				'condition' => [
					'use_individual_style' => 'yes',
				],
			]
		);

		// Individual Effect
		$repeater->add_control(
			'individual_effect',
			[
				'label' => esc_html__( 'Effect', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => esc_html__( 'None', 'mn-elements' ),
					'gradient' => esc_html__( 'Gradient Color', 'mn-elements' ),
					'underline' => esc_html__( 'Stylish Underline', 'mn-elements' ),
					'highlight' => esc_html__( 'Highlight Background', 'mn-elements' ),
					'outline' => esc_html__( 'Outline Text', 'mn-elements' ),
					'shadow' => esc_html__( 'Text Shadow', 'mn-elements' ),
				],
				'condition' => [
					'use_individual_style' => 'yes',
				],
			]
		);

		// Gradient Colors
		$repeater->add_control(
			'individual_gradient_color_1',
			[
				'label' => esc_html__( 'Gradient Color 1', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#667eea',
				'condition' => [
					'use_individual_style' => 'yes',
					'individual_effect' => 'gradient',
				],
			]
		);

		$repeater->add_control(
			'individual_gradient_color_2',
			[
				'label' => esc_html__( 'Gradient Color 2', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#764ba2',
				'condition' => [
					'use_individual_style' => 'yes',
					'individual_effect' => 'gradient',
				],
			]
		);

		$repeater->add_control(
			'individual_gradient_angle',
			[
				'label' => esc_html__( 'Gradient Angle', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 360,
					],
				],
				'default' => [
					'size' => 45,
				],
				'condition' => [
					'use_individual_style' => 'yes',
					'individual_effect' => 'gradient',
				],
			]
		);

		// Solid Color
		$repeater->add_control(
			'individual_color',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'use_individual_style' => 'yes',
					'individual_effect!' => 'gradient',
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'color: {{VALUE}};',
				],
			]
		);

		// Underline Color
		$repeater->add_control(
			'individual_underline_color',
			[
				'label' => esc_html__( 'Underline Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#667eea',
				'condition' => [
					'use_individual_style' => 'yes',
					'individual_effect' => 'underline',
				],
			]
		);

		$repeater->add_control(
			'individual_underline_height',
			[
				'label' => esc_html__( 'Underline Height', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],
				'default' => [
					'size' => 3,
				],
				'condition' => [
					'use_individual_style' => 'yes',
					'individual_effect' => 'underline',
				],
			]
		);

		// Highlight Background
		$repeater->add_control(
			'individual_highlight_color',
			[
				'label' => esc_html__( 'Highlight Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffd700',
				'condition' => [
					'use_individual_style' => 'yes',
					'individual_effect' => 'highlight',
				],
			]
		);

		// Outline
		$repeater->add_control(
			'individual_outline_width',
			[
				'label' => esc_html__( 'Outline Width', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 5,
					],
				],
				'default' => [
					'size' => 2,
				],
				'condition' => [
					'use_individual_style' => 'yes',
					'individual_effect' => 'outline',
				],
			]
		);

		$repeater->add_control(
			'individual_outline_color',
			[
				'label' => esc_html__( 'Outline Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'condition' => [
					'use_individual_style' => 'yes',
					'individual_effect' => 'outline',
				],
			]
		);

		$this->add_control(
			'heading_parts',
			[
				'label' => esc_html__( 'Heading Parts', 'mn-elements' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'text' => esc_html__( 'Create', 'mn-elements' ),
						'line_break' => 'no',
					],
					[
						'text' => esc_html__( 'Amazing', 'mn-elements' ),
						'line_break' => 'no',
						'use_individual_style' => 'yes',
						'individual_effect' => 'gradient',
					],
					[
						'text' => esc_html__( 'Headings', 'mn-elements' ),
						'line_break' => 'no',
					],
				],
				'title_field' => '{{{ text }}}',
			]
		);

		$this->end_controls_section();

		// Settings Section
		$this->start_controls_section(
			'section_settings',
			[
				'label' => esc_html__( 'Settings', 'mn-elements' ),
			]
		);

		$this->add_control(
			'html_tag',
			[
				'label' => esc_html__( 'HTML Tag', 'mn-elements' ),
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
					'p' => 'p',
				],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => esc_html__( 'Alignment', 'mn-elements' ),
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
					'justify' => [
						'title' => esc_html__( 'Justified', 'mn-elements' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .mn-heading-wrapper' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'link',
			[
				'label' => esc_html__( 'Link', 'mn-elements' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'mn-elements' ),
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register style controls.
	 */
	protected function register_style_controls() {
		// General Style Section
		$this->start_controls_section(
			'section_general_style',
			[
				'label' => esc_html__( 'General Style', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'selector' => '{{WRAPPER}} .mn-heading, {{WRAPPER}} .mn-heading-part',
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-heading-part' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'text_shadow',
				'selector' => '{{WRAPPER}} .mn-heading-part',
			]
		);

		$this->add_responsive_control(
			'part_spacing',
			[
				'label' => esc_html__( 'Part Spacing', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
					'em' => [
						'min' => 0,
						'max' => 5,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 8,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-heading-part' => 'margin-right: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Icon Style Section
		$this->start_controls_section(
			'section_icon_style',
			[
				'label' => esc_html__( 'Icon', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Size', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 100,
					],
					'em' => [
						'min' => 0.5,
						'max' => 10,
					],
				],
				'default' => [
					'size' => 16,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-heading-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-heading-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_gap',
			[
				'label' => esc_html__( 'Gap', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'size' => 8,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-heading-part-wrapper.mn-icon-left .mn-heading-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-heading-part-wrapper.mn-icon-right .mn-heading-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_horizontal_align',
			[
				'label' => esc_html__( 'Horizontal Alignment', 'mn-elements' ),
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
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .mn-heading-icon' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_vertical_align',
			[
				'label' => esc_html__( 'Vertical Alignment', 'mn-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Top', 'mn-elements' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => esc_html__( 'Middle', 'mn-elements' ),
						'icon' => 'eicon-v-align-middle',
					],
					'flex-end' => [
						'title' => esc_html__( 'Bottom', 'mn-elements' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .mn-heading-part-wrapper' => 'align-items: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_vertical_offset',
			[
				'label' => esc_html__( 'Adjust Vertical Position', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => -20,
						'max' => 20,
					],
					'em' => [
						'min' => -2,
						'max' => 2,
					],
				],
				'default' => [
					'size' => 0,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-heading-icon' => 'transform: translateY({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->start_controls_tabs( 'icon_colors' );

		$this->start_controls_tab(
			'icon_colors_normal',
			[
				'label' => esc_html__( 'Normal', 'mn-elements' ),
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-heading-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-heading-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_colors_hover',
			[
				'label' => esc_html__( 'Hover', 'mn-elements' ),
			]
		);

		$this->add_control(
			'icon_color_hover',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-heading-part-wrapper:hover .mn-heading-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-heading-part-wrapper:hover .mn-heading-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'icon_transition',
			[
				'label' => esc_html__( 'Transition Duration', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 's', 'ms' ],
				'default' => [
					'unit' => 's',
					'size' => 0.3,
				],
				'range' => [
					's' => [
						'min' => 0,
						'max' => 3,
						'step' => 0.1,
					],
					'ms' => [
						'min' => 0,
						'max' => 3000,
						'step' => 100,
					],
				],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .mn-heading-icon i' => 'transition: color {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-heading-icon svg' => 'transition: fill {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Global Effects Section
		$this->start_controls_section(
			'section_global_effects',
			[
				'label' => esc_html__( 'Global Effects', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'global_effect',
			[
				'label' => esc_html__( 'Effect', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => esc_html__( 'None', 'mn-elements' ),
					'gradient' => esc_html__( 'Gradient Color', 'mn-elements' ),
					'underline' => esc_html__( 'Stylish Underline', 'mn-elements' ),
					'highlight' => esc_html__( 'Highlight Background', 'mn-elements' ),
					'outline' => esc_html__( 'Outline Text', 'mn-elements' ),
				],
				'description' => esc_html__( 'This effect will apply to all parts without individual styling', 'mn-elements' ),
			]
		);

		// Gradient Settings
		$this->add_control(
			'gradient_heading',
			[
				'label' => esc_html__( 'Gradient Settings', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'global_effect' => 'gradient',
				],
			]
		);

		$this->add_control(
			'gradient_color_1',
			[
				'label' => esc_html__( 'Color 1', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#667eea',
				'condition' => [
					'global_effect' => 'gradient',
				],
			]
		);

		$this->add_control(
			'gradient_color_2',
			[
				'label' => esc_html__( 'Color 2', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#764ba2',
				'condition' => [
					'global_effect' => 'gradient',
				],
			]
		);

		$this->add_control(
			'gradient_angle',
			[
				'label' => esc_html__( 'Angle', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 360,
					],
				],
				'default' => [
					'size' => 45,
				],
				'condition' => [
					'global_effect' => 'gradient',
				],
			]
		);

		// Underline Settings
		$this->add_control(
			'underline_heading',
			[
				'label' => esc_html__( 'Underline Settings', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'global_effect' => 'underline',
				],
			]
		);

		$this->add_control(
			'underline_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#667eea',
				'condition' => [
					'global_effect' => 'underline',
				],
			]
		);

		$this->add_control(
			'underline_height',
			[
				'label' => esc_html__( 'Height', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],
				'default' => [
					'size' => 3,
				],
				'condition' => [
					'global_effect' => 'underline',
				],
			]
		);

		$this->add_control(
			'underline_offset',
			[
				'label' => esc_html__( 'Offset', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'default' => [
					'size' => 5,
				],
				'condition' => [
					'global_effect' => 'underline',
				],
			]
		);

		// Highlight Settings
		$this->add_control(
			'highlight_heading',
			[
				'label' => esc_html__( 'Highlight Settings', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'global_effect' => 'highlight',
				],
			]
		);

		$this->add_control(
			'highlight_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffd700',
				'condition' => [
					'global_effect' => 'highlight',
				],
			]
		);

		$this->add_control(
			'highlight_opacity',
			[
				'label' => esc_html__( 'Opacity', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 30,
				],
				'condition' => [
					'global_effect' => 'highlight',
				],
			]
		);

		// Outline Settings
		$this->add_control(
			'outline_heading',
			[
				'label' => esc_html__( 'Outline Settings', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'global_effect' => 'outline',
				],
			]
		);

		$this->add_control(
			'outline_width',
			[
				'label' => esc_html__( 'Width', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 5,
					],
				],
				'default' => [
					'size' => 2,
				],
				'condition' => [
					'global_effect' => 'outline',
				],
			]
		);

		$this->add_control(
			'outline_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'condition' => [
					'global_effect' => 'outline',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Get dynamic content based on field type
	 */
	protected function get_dynamic_content( $field_type, $custom_field_name = '' ) {
		switch ( $field_type ) {
			case 'post_title':
				return get_the_title();
			
			case 'post_excerpt':
				return get_the_excerpt();
			
			case 'post_date':
				return get_the_date();
			
			case 'post_author':
				return get_the_author();
			
			case 'site_title':
				return get_bloginfo( 'name' );
			
			case 'site_tagline':
				return get_bloginfo( 'description' );
			
			case 'custom_field':
				if ( ! empty( $custom_field_name ) ) {
					// Try JetEngine first
					if ( function_exists( 'jet_engine' ) ) {
						$value = get_post_meta( get_the_ID(), $custom_field_name, true );
						if ( ! empty( $value ) ) {
							// Handle JetEngine image field (returns ID)
							if ( is_numeric( $value ) ) {
								$image_url = wp_get_attachment_url( $value );
								if ( $image_url ) {
									return $image_url;
								}
							}
							return $value;
						}
					}
					
					// Try ACF
					if ( function_exists( 'get_field' ) ) {
						$value = get_field( $custom_field_name );
						if ( ! empty( $value ) ) {
							return $value;
						}
					}
					
					// Fallback to WordPress meta
					return get_post_meta( get_the_ID(), $custom_field_name, true );
				}
				return '';
			
			default:
				return '';
		}
	}

	/**
	 * Render widget output on the frontend.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['heading_parts'] ) ) {
			return;
		}

		$html_tag = $settings['html_tag'];
		$has_link = ! empty( $settings['link']['url'] );

		$this->add_render_attribute( 'wrapper', 'class', 'mn-heading-wrapper' );

		if ( $has_link ) {
			$this->add_link_attributes( 'link', $settings['link'] );
		}

		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<?php if ( $has_link ) : ?>
				<a <?php $this->print_render_attribute_string( 'link' ); ?>>
			<?php endif; ?>

			<<?php echo esc_attr( $html_tag ); ?> class="mn-heading">
				<?php foreach ( $settings['heading_parts'] as $index => $part ) :
					$part_key = 'part_' . $index;
					$part_class = 'mn-heading-part elementor-repeater-item-' . $part['_id'];
					
					// Get text content
					$text_content = '';
					if ( $part['text_source'] === 'dynamic' ) {
						$text_content = $this->get_dynamic_content( 
							$part['dynamic_field'], 
							isset( $part['custom_field_name'] ) ? $part['custom_field_name'] : '' 
						);
					} else {
						$text_content = $part['text'];
					}

					if ( empty( $text_content ) ) {
						continue;
					}

					// Add effect classes and inline styles
					$effect_class = '';
					$inline_style = '';
					
					// Add line break class if enabled
					if ( $part['line_break'] === 'yes' ) {
						$part_class .= ' mn-has-line-break';
					}

					if ( $part['use_individual_style'] === 'yes' ) {
						// Individual styling
						$effect = isset( $part['individual_effect'] ) ? $part['individual_effect'] : 'none';
						
						switch ( $effect ) {
							case 'gradient':
								$effect_class = 'mn-effect-gradient';
								$color1 = isset( $part['individual_gradient_color_1'] ) ? $part['individual_gradient_color_1'] : '#667eea';
								$color2 = isset( $part['individual_gradient_color_2'] ) ? $part['individual_gradient_color_2'] : '#764ba2';
								$angle = isset( $part['individual_gradient_angle']['size'] ) ? $part['individual_gradient_angle']['size'] : 45;
								$inline_style = sprintf(
									'background: linear-gradient(%sdeg, %s, %s); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;',
									$angle, $color1, $color2
								);
								break;
							
							case 'underline':
								$effect_class = 'mn-effect-underline';
								$color = isset( $part['individual_underline_color'] ) ? $part['individual_underline_color'] : '#667eea';
								$height = isset( $part['individual_underline_height']['size'] ) ? $part['individual_underline_height']['size'] : 3;
								$inline_style = sprintf(
									'--underline-color: %s; --underline-height: %spx;',
									$color, $height
								);
								break;
							
							case 'highlight':
								$effect_class = 'mn-effect-highlight';
								$color = isset( $part['individual_highlight_color'] ) ? $part['individual_highlight_color'] : '#ffd700';
								$inline_style = sprintf( 'background-color: %s;', $color );
								break;
							
							case 'outline':
								$effect_class = 'mn-effect-outline';
								$width = isset( $part['individual_outline_width']['size'] ) ? $part['individual_outline_width']['size'] : 2;
								$color = isset( $part['individual_outline_color'] ) ? $part['individual_outline_color'] : '#000000';
								$inline_style = sprintf(
									'-webkit-text-stroke: %spx %s; -webkit-text-fill-color: transparent;',
									$width, $color
								);
								break;
						}
					} else {
						// Global styling
						$global_effect = $settings['global_effect'];
						
						switch ( $global_effect ) {
							case 'gradient':
								$effect_class = 'mn-effect-gradient mn-global-gradient';
								$color1 = $settings['gradient_color_1'];
								$color2 = $settings['gradient_color_2'];
								$angle = $settings['gradient_angle']['size'];
								$inline_style = sprintf(
									'background: linear-gradient(%sdeg, %s, %s); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;',
									$angle, $color1, $color2
								);
								break;
							
							case 'underline':
								$effect_class = 'mn-effect-underline mn-global-underline';
								$color = $settings['underline_color'];
								$height = $settings['underline_height']['size'];
								$offset = $settings['underline_offset']['size'];
								$inline_style = sprintf(
									'--underline-color: %s; --underline-height: %spx; --underline-offset: %spx;',
									$color, $height, $offset
								);
								break;
							
							case 'highlight':
								$effect_class = 'mn-effect-highlight mn-global-highlight';
								$color = $settings['highlight_color'];
								$opacity = $settings['highlight_opacity']['size'] / 100;
								$rgba = $this->hex_to_rgba( $color, $opacity );
								$inline_style = sprintf( 'background-color: %s;', $rgba );
								break;
							
							case 'outline':
								$effect_class = 'mn-effect-outline mn-global-outline';
								$width = $settings['outline_width']['size'];
								$color = $settings['outline_color'];
								$inline_style = sprintf(
									'-webkit-text-stroke: %spx %s; -webkit-text-fill-color: transparent;',
									$width, $color
								);
								break;
						}
					}

					$this->add_render_attribute( $part_key, [
						'class' => $part_class . ' ' . $effect_class,
						'style' => $inline_style,
					] );
				
					// Check if individual link exists
					$has_individual_link = ! empty( $part['individual_link']['url'] );
					$link_key = 'link_' . $index;
					
					if ( $has_individual_link ) {
						$this->add_link_attributes( $link_key, $part['individual_link'] );
					}
					
					// Check if icon is enabled
					$has_icon = $part['use_individual_style'] === 'yes' && ! empty( $part['show_icon'] ) && $part['show_icon'] === 'yes' && ! empty( $part['selected_icon']['value'] );
					$icon_position = isset( $part['icon_position'] ) ? $part['icon_position'] : 'left';
					
					// Wrapper key for icon + text
					$wrapper_key = 'wrapper_' . $index;
					$wrapper_class = 'mn-heading-part-wrapper';
					if ( $has_icon ) {
						$wrapper_class .= ' mn-has-icon mn-icon-' . $icon_position;
					}
					$this->add_render_attribute( $wrapper_key, 'class', $wrapper_class );
					?>
					<?php if ( $has_individual_link ) : ?>
						<a <?php $this->print_render_attribute_string( $link_key ); ?>>
					<?php endif; ?>
					<?php if ( $has_icon ) : ?>
					<span <?php $this->print_render_attribute_string( $wrapper_key ); ?>>
						<?php if ( $icon_position === 'left' ) : ?>
							<span class="mn-heading-icon">
								<?php \Elementor\Icons_Manager::render_icon( $part['selected_icon'], [ 'aria-hidden' => 'true' ] ); ?>
							</span>
						<?php endif; ?>
						<span <?php $this->print_render_attribute_string( $part_key ); ?>>
							<?php echo esc_html( $text_content ); ?>
						</span>
						<?php if ( $icon_position === 'right' ) : ?>
							<span class="mn-heading-icon">
								<?php \Elementor\Icons_Manager::render_icon( $part['selected_icon'], [ 'aria-hidden' => 'true' ] ); ?>
							</span>
						<?php endif; ?>
					</span>
					<?php else : ?>
					<span <?php $this->print_render_attribute_string( $part_key ); ?>>
						<?php echo esc_html( $text_content ); ?>
					</span>
					<?php endif; ?>
					<?php if ( $has_individual_link ) : ?>
						</a>
					<?php endif; ?>
				<?php endforeach; ?>
			</<?php echo esc_attr( $html_tag ); ?>>

			<?php if ( $has_link ) : ?>
				</a>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Convert hex color to rgba
	 */
	protected function hex_to_rgba( $hex, $alpha = 1 ) {
		$hex = str_replace( '#', '', $hex );
		
		if ( strlen( $hex ) === 3 ) {
			$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
			$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
			$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
		} else {
			$r = hexdec( substr( $hex, 0, 2 ) );
			$g = hexdec( substr( $hex, 2, 2 ) );
			$b = hexdec( substr( $hex, 4, 2 ) );
		}
		
		return "rgba($r, $g, $b, $alpha)";
	}
}
