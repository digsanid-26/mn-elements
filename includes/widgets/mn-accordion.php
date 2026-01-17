<?php
namespace MN_Elements\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

/**
 * MN Accordion Widget
 *
 * Elementor widget that displays collapsible content with dynamic numbering feature.
 *
 * @since 1.0.0
 */
class MN_Accordion extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mn-accordion';
	}

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'MN Accordion', 'mn-elements' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-accordion';
	}

	/**
	 * Get widget categories.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'mn-elements' ];
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'accordion', 'tabs', 'toggle', 'faq', 'collapse', 'numbering' ];
	}

	/**
	 * Get script dependencies.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Script dependencies.
	 */
	public function get_script_depends() {
		return [ 'mn-accordion' ];
	}

	/**
	 * Get style dependencies.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Style dependencies.
	 */
	public function get_style_depends() {
		return [ 'mn-accordion' ];
	}

	/**
	 * Register accordion widget controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		// Content Section
		$this->start_controls_section(
			'section_accordion',
			[
				'label' => esc_html__( 'Accordion', 'mn-elements' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'tab_title',
			[
				'label' => esc_html__( 'Title', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Accordion Title', 'mn-elements' ),
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'tab_content',
			[
				'label' => esc_html__( 'Content', 'mn-elements' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'Accordion Content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'mn-elements' ),
			]
		);

		$this->add_control(
			'tabs',
			[
				'label' => esc_html__( 'Accordion Items', 'mn-elements' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'tab_title' => esc_html__( 'Accordion #1', 'mn-elements' ),
						'tab_content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'mn-elements' ),
					],
					[
						'tab_title' => esc_html__( 'Accordion #2', 'mn-elements' ),
						'tab_content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'mn-elements' ),
					],
					[
						'tab_title' => esc_html__( 'Accordion #3', 'mn-elements' ),
						'tab_content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'mn-elements' ),
					],
				],
				'title_field' => '{{{ tab_title }}}',
			]
		);

		$this->add_control(
			'title_html_tag',
			[
				'label' => esc_html__( 'Title HTML Tag', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
				],
				'default' => 'div',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'faq_schema',
			[
				'label' => esc_html__( 'FAQ Schema', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'description' => esc_html__( 'Enable FAQ structured data for SEO.', 'mn-elements' ),
			]
		);

		$this->end_controls_section();

		// Numbering Section
		$this->start_controls_section(
			'section_numbering',
			[
				'label' => esc_html__( 'Numbering', 'mn-elements' ),
			]
		);

		$this->add_control(
			'show_numbering',
			[
				'label' => esc_html__( 'Show Numbering', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'description' => esc_html__( 'Display dynamic numbering (01, 02, 03...) for each accordion item.', 'mn-elements' ),
			]
		);

		$this->add_control(
			'numbering_format',
			[
				'label' => esc_html__( 'Numbering Format', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'01' => '01, 02, 03...',
					'1' => '1, 2, 3...',
					'01.' => '01., 02., 03...',
					'1.' => '1., 2., 3...',
					'(01)' => '(01), (02), (03)...',
					'(1)' => '(1), (2), (3)...',
				],
				'default' => '01',
				'condition' => [
					'show_numbering' => 'yes',
				],
			]
		);

		$this->add_control(
			'numbering_position',
			[
				'label' => esc_html__( 'Numbering Position', 'mn-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'before_icon' => [
						'title' => esc_html__( 'Before Icon', 'mn-elements' ),
						'icon' => 'eicon-h-align-left',
					],
					'after_icon' => [
						'title' => esc_html__( 'After Icon', 'mn-elements' ),
						'icon' => 'eicon-h-align-center',
					],
					'before_title' => [
						'title' => esc_html__( 'Before Title', 'mn-elements' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => 'before_icon',
				'toggle' => false,
				'condition' => [
					'show_numbering' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		// Icon Section
		$this->start_controls_section(
			'section_icon',
			[
				'label' => esc_html__( 'Icon', 'mn-elements' ),
			]
		);

		$this->add_control(
			'selected_icon',
			[
				'label' => esc_html__( 'Icon', 'mn-elements' ),
				'type' => Controls_Manager::ICONS,
				'separator' => 'before',
				'fa4compatibility' => 'icon',
				'default' => [
					'value' => 'fas fa-plus',
					'library' => 'fa-solid',
				],
				'recommended' => [
					'fa-solid' => [
						'chevron-down',
						'angle-down',
						'angle-double-down',
						'caret-down',
						'caret-square-down',
						'plus',
						'plus-circle',
					],
					'fa-regular' => [
						'caret-square-down',
						'plus-square',
					],
				],
				'skin' => 'inline',
				'label_block' => false,
			]
		);

		$this->add_control(
			'selected_active_icon',
			[
				'label' => esc_html__( 'Active Icon', 'mn-elements' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon_active',
				'default' => [
					'value' => 'fas fa-minus',
					'library' => 'fa-solid',
				],
				'recommended' => [
					'fa-solid' => [
						'chevron-up',
						'angle-up',
						'angle-double-up',
						'caret-up',
						'caret-square-up',
						'minus',
						'minus-circle',
					],
					'fa-regular' => [
						'caret-square-up',
						'minus-square',
					],
				],
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'selected_icon[value]!' => '',
				],
			]
		);

		$this->add_control(
			'icon_align',
			[
				'label' => esc_html__( 'Icon Position', 'mn-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Start', 'mn-elements' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'End', 'mn-elements' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => is_rtl() ? 'right' : 'left',
				'toggle' => false,
				'condition' => [
					'selected_icon[value]!' => '',
				],
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
			'accordion_type',
			[
				'label' => esc_html__( 'Accordion Type', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'accordion' => esc_html__( 'Accordion (One at a time)', 'mn-elements' ),
					'toggle' => esc_html__( 'Toggle (Multiple open)', 'mn-elements' ),
				],
				'default' => 'accordion',
			]
		);

		$this->add_control(
			'default_active',
			[
				'label' => esc_html__( 'Default Active Item', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 20,
				'default' => 1,
				'description' => esc_html__( 'Set 0 to collapse all items by default.', 'mn-elements' ),
			]
		);

		$this->add_control(
			'animation_duration',
			[
				'label' => esc_html__( 'Animation Duration (ms)', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1000,
						'step' => 50,
					],
				],
				'default' => [
					'size' => 300,
				],
			]
		);

		$this->end_controls_section();

		// Style: Accordion Container
		$this->start_controls_section(
			'section_style_accordion',
			[
				'label' => esc_html__( 'Accordion', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'accordion_spacing',
			[
				'label' => esc_html__( 'Item Spacing', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'size' => 0,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-accordion-item + .mn-accordion-item' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'accordion_border',
				'selector' => '{{WRAPPER}} .mn-accordion-item',
			]
		);

		$this->add_responsive_control(
			'accordion_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-accordion-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'accordion_box_shadow',
				'selector' => '{{WRAPPER}} .mn-accordion-item',
			]
		);

		$this->end_controls_section();

		// Style: Title
		$this->start_controls_section(
			'section_style_title',
			[
				'label' => esc_html__( 'Title', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'title_tabs' );

		$this->start_controls_tab(
			'title_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'mn-elements' ),
			]
		);

		$this->add_control(
			'title_background',
			[
				'label' => esc_html__( 'Background', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-accordion-title' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-accordion-title' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-accordion-title .mn-accordion-icon svg' => 'fill: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'title_active_tab',
			[
				'label' => esc_html__( 'Active', 'mn-elements' ),
			]
		);

		$this->add_control(
			'title_active_background',
			[
				'label' => esc_html__( 'Background', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-accordion-item.mn-active .mn-accordion-title' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_active_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-accordion-item.mn-active .mn-accordion-title' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-accordion-item.mn-active .mn-accordion-title .mn-accordion-icon svg' => 'fill: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .mn-accordion-title-text',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'title_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .mn-accordion-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default' => [
					'top' => '15',
					'right' => '20',
					'bottom' => '15',
					'left' => '20',
					'unit' => 'px',
				],
			]
		);

		$this->end_controls_section();

		// Style: Numbering
		$this->start_controls_section(
			'section_style_numbering',
			[
				'label' => esc_html__( 'Numbering', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_numbering' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'numbering_tabs' );

		$this->start_controls_tab(
			'numbering_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'mn-elements' ),
			]
		);

		$this->add_control(
			'numbering_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-accordion-number' => 'color: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
			]
		);

		$this->add_control(
			'numbering_background',
			[
				'label' => esc_html__( 'Background', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-accordion-number' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'numbering_active_tab',
			[
				'label' => esc_html__( 'Active', 'mn-elements' ),
			]
		);

		$this->add_control(
			'numbering_active_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-accordion-item.mn-active .mn-accordion-number' => 'color: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
			]
		);

		$this->add_control(
			'numbering_active_background',
			[
				'label' => esc_html__( 'Background', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-accordion-item.mn-active .mn-accordion-number' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'numbering_typography',
				'selector' => '{{WRAPPER}} .mn-accordion-number',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'numbering_size',
			[
				'label' => esc_html__( 'Size', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mn-accordion-number' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'numbering_spacing',
			[
				'label' => esc_html__( 'Spacing', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'size' => 15,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-accordion-number' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'numbering_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-accordion-number' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Style: Icon
		$this->start_controls_section(
			'section_style_icon',
			[
				'label' => esc_html__( 'Icon', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'selected_icon[value]!' => '',
				],
			]
		);

		$this->start_controls_tabs( 'icon_tabs' );

		$this->start_controls_tab(
			'icon_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'mn-elements' ),
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-accordion-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-accordion-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_active_tab',
			[
				'label' => esc_html__( 'Active', 'mn-elements' ),
			]
		);

		$this->add_control(
			'icon_active_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-accordion-item.mn-active .mn-accordion-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-accordion-item.mn-active .mn-accordion-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Size', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mn-accordion-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-accordion-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'icon_spacing',
			[
				'label' => esc_html__( 'Spacing', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'size' => 15,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-accordion-icon-left .mn-accordion-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-accordion-icon-right .mn-accordion-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Style: Content
		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__( 'Content', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'content_background',
			[
				'label' => esc_html__( 'Background', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-accordion-content' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'content_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-accordion-content' => 'color: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .mn-accordion-content',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .mn-accordion-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default' => [
					'top' => '15',
					'right' => '20',
					'bottom' => '15',
					'left' => '20',
					'unit' => 'px',
				],
			]
		);

		$this->add_control(
			'show_separator',
			[
				'label' => esc_html__( 'Show Separator', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before',
				'description' => esc_html__( 'Show border line between title and content.', 'mn-elements' ),
			]
		);

		$this->add_control(
			'separator_color',
			[
				'label' => esc_html__( 'Separator Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-accordion-content.mn-has-separator' => 'border-top-color: {{VALUE}};',
				],
				'condition' => [
					'show_separator' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'separator_width',
			[
				'label' => esc_html__( 'Separator Width', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'default' => [
					'size' => 1,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-accordion-content.mn-has-separator' => 'border-top-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_separator' => 'yes',
				],
			]
		);

		$this->add_control(
			'separator_style',
			[
				'label' => esc_html__( 'Separator Style', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'solid' => esc_html__( 'Solid', 'mn-elements' ),
					'dashed' => esc_html__( 'Dashed', 'mn-elements' ),
					'dotted' => esc_html__( 'Dotted', 'mn-elements' ),
					'double' => esc_html__( 'Double', 'mn-elements' ),
				],
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .mn-accordion-content.mn-has-separator' => 'border-top-style: {{VALUE}};',
				],
				'condition' => [
					'show_separator' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Format number based on selected format.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @param int $number The number to format.
	 * @param string $format The format type.
	 *
	 * @return string Formatted number.
	 */
	private function format_number( $number, $format ) {
		switch ( $format ) {
			case '01':
				return str_pad( $number, 2, '0', STR_PAD_LEFT );
			case '1':
				return (string) $number;
			case '01.':
				return str_pad( $number, 2, '0', STR_PAD_LEFT ) . '.';
			case '1.':
				return $number . '.';
			case '(01)':
				return '(' . str_pad( $number, 2, '0', STR_PAD_LEFT ) . ')';
			case '(1)':
				return '(' . $number . ')';
			default:
				return str_pad( $number, 2, '0', STR_PAD_LEFT );
		}
	}

	/**
	 * Render accordion widget output on the frontend.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$id_int = substr( $this->get_id_int(), 0, 3 );

		$has_icon = ! empty( $settings['selected_icon']['value'] );
		$show_numbering = $settings['show_numbering'] === 'yes';
		$numbering_format = $settings['numbering_format'];
		$numbering_position = $settings['numbering_position'];
		$icon_align = isset( $settings['icon_align'] ) ? $settings['icon_align'] : 'left';
		$default_active = isset( $settings['default_active'] ) ? intval( $settings['default_active'] ) : 1;
		$accordion_type = isset( $settings['accordion_type'] ) ? $settings['accordion_type'] : 'accordion';
		$animation_duration = isset( $settings['animation_duration']['size'] ) ? $settings['animation_duration']['size'] : 300;
		$show_separator = isset( $settings['show_separator'] ) && $settings['show_separator'] === 'yes';

		$this->add_render_attribute( 'accordion', [
			'class' => 'mn-accordion',
			'data-accordion-type' => $accordion_type,
			'data-default-active' => $default_active,
			'data-animation-duration' => $animation_duration,
		] );
		?>
		<div <?php $this->print_render_attribute_string( 'accordion' ); ?>>
			<?php
			foreach ( $settings['tabs'] as $index => $item ) :
				$tab_count = $index + 1;
				$is_active = ( $default_active === $tab_count );

				$tab_title_key = $this->get_repeater_setting_key( 'tab_title', 'tabs', $index );
				$tab_content_key = $this->get_repeater_setting_key( 'tab_content', 'tabs', $index );

				$this->add_render_attribute( $tab_title_key, [
					'id' => 'mn-accordion-title-' . $id_int . $tab_count,
					'class' => [ 'mn-accordion-title', 'mn-accordion-icon-' . $icon_align ],
					'data-tab' => $tab_count,
					'role' => 'button',
					'aria-controls' => 'mn-accordion-content-' . $id_int . $tab_count,
					'aria-expanded' => $is_active ? 'true' : 'false',
					'tabindex' => '0',
				] );

				$content_classes = [ 'mn-accordion-content' ];
				if ( $show_separator ) {
					$content_classes[] = 'mn-has-separator';
				}

				$this->add_render_attribute( $tab_content_key, [
					'id' => 'mn-accordion-content-' . $id_int . $tab_count,
					'class' => $content_classes,
					'data-tab' => $tab_count,
					'role' => 'region',
					'aria-labelledby' => 'mn-accordion-title-' . $id_int . $tab_count,
				] );

				if ( ! $is_active ) {
					$this->add_render_attribute( $tab_content_key, 'hidden', 'hidden' );
				}

				$item_class = 'mn-accordion-item';
				if ( $is_active ) {
					$item_class .= ' mn-active';
				}

				$formatted_number = $this->format_number( $tab_count, $numbering_format );
				?>
				<div class="<?php echo esc_attr( $item_class ); ?>">
					<<?php echo esc_html( $settings['title_html_tag'] ); ?> <?php $this->print_render_attribute_string( $tab_title_key ); ?>>
						<?php
						// Numbering before icon
						if ( $show_numbering && $numbering_position === 'before_icon' ) :
							?>
							<span class="mn-accordion-number"><?php echo esc_html( $formatted_number ); ?></span>
						<?php endif; ?>

						<?php if ( $has_icon && $icon_align === 'left' ) : ?>
							<span class="mn-accordion-icon" aria-hidden="true">
								<span class="mn-accordion-icon-closed"><?php Icons_Manager::render_icon( $settings['selected_icon'] ); ?></span>
								<span class="mn-accordion-icon-opened"><?php Icons_Manager::render_icon( $settings['selected_active_icon'] ); ?></span>
							</span>
						<?php endif; ?>

						<?php
						// Numbering after icon
						if ( $show_numbering && $numbering_position === 'after_icon' ) :
							?>
							<span class="mn-accordion-number"><?php echo esc_html( $formatted_number ); ?></span>
						<?php endif; ?>

						<?php
						// Numbering before title
						if ( $show_numbering && $numbering_position === 'before_title' ) :
							?>
							<span class="mn-accordion-number"><?php echo esc_html( $formatted_number ); ?></span>
						<?php endif; ?>

						<span class="mn-accordion-title-text"><?php $this->print_unescaped_setting( 'tab_title', 'tabs', $index ); ?></span>

						<?php if ( $has_icon && $icon_align === 'right' ) : ?>
							<span class="mn-accordion-icon" aria-hidden="true">
								<span class="mn-accordion-icon-closed"><?php Icons_Manager::render_icon( $settings['selected_icon'] ); ?></span>
								<span class="mn-accordion-icon-opened"><?php Icons_Manager::render_icon( $settings['selected_active_icon'] ); ?></span>
							</span>
						<?php endif; ?>
					</<?php echo esc_html( $settings['title_html_tag'] ); ?>>
					<div <?php $this->print_render_attribute_string( $tab_content_key ); ?>>
						<div class="mn-accordion-content-inner">
							<?php $this->print_text_editor( $item['tab_content'] ); ?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>

			<?php
			// FAQ Schema
			if ( isset( $settings['faq_schema'] ) && 'yes' === $settings['faq_schema'] ) {
				$json = [
					'@context' => 'https://schema.org',
					'@type' => 'FAQPage',
					'mainEntity' => [],
				];

				foreach ( $settings['tabs'] as $index => $item ) {
					$json['mainEntity'][] = [
						'@type' => 'Question',
						'name' => wp_strip_all_tags( $item['tab_title'] ),
						'acceptedAnswer' => [
							'@type' => 'Answer',
							'text' => $this->parse_text_editor( $item['tab_content'] ),
						],
					];
				}
				?>
				<script type="application/ld+json"><?php echo wp_json_encode( $json ); ?></script>
			<?php } ?>
		</div>
		<?php
	}

	/**
	 * Render accordion widget output in the editor.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function content_template() {
		?>
		<#
		var iconHTML = elementor.helpers.renderIcon( view, settings.selected_icon, {}, 'i', 'object' );
		var iconActiveHTML = elementor.helpers.renderIcon( view, settings.selected_active_icon, {}, 'i', 'object' );
		var tabindex = view.getIDInt().toString().substr( 0, 3 );
		var showNumbering = settings.show_numbering === 'yes';
		var numberingFormat = settings.numbering_format;
		var numberingPosition = settings.numbering_position;
		var iconAlign = settings.icon_align || 'left';
		var defaultActive = parseInt( settings.default_active ) || 1;
		var accordionType = settings.accordion_type || 'accordion';
		var animationDuration = settings.animation_duration.size || 300;
		var showSeparator = settings.show_separator === 'yes';

		function formatNumber( number, format ) {
			var padded = number < 10 ? '0' + number : number.toString();
			switch ( format ) {
				case '01':
					return padded;
				case '1':
					return number.toString();
				case '01.':
					return padded + '.';
				case '1.':
					return number + '.';
				case '(01)':
					return '(' + padded + ')';
				case '(1)':
					return '(' + number + ')';
				default:
					return padded;
			}
		}
		#>
		<div class="mn-accordion" data-accordion-type="{{ accordionType }}" data-default-active="{{ defaultActive }}" data-animation-duration="{{ animationDuration }}">
			<# _.each( settings.tabs, function( item, index ) {
				var tabCount = index + 1;
				var isActive = defaultActive === tabCount;
				var tabTitleKey = view.getRepeaterSettingKey( 'tab_title', 'tabs', index );
				var tabContentKey = view.getRepeaterSettingKey( 'tab_content', 'tabs', index );
				var formattedNumber = formatNumber( tabCount, numberingFormat );
				var itemClass = 'mn-accordion-item';
				if ( isActive ) {
					itemClass += ' mn-active';
				}

				view.addRenderAttribute( tabTitleKey, {
					'id': 'mn-accordion-title-' + tabindex + tabCount,
					'class': [ 'mn-accordion-title', 'mn-accordion-icon-' + iconAlign ],
					'data-tab': tabCount,
					'role': 'button',
					'aria-controls': 'mn-accordion-content-' + tabindex + tabCount,
					'aria-expanded': isActive ? 'true' : 'false',
					'tabindex': '0'
				} );

				var contentClasses = [ 'mn-accordion-content' ];
				if ( showSeparator ) {
					contentClasses.push( 'mn-has-separator' );
				}

				view.addRenderAttribute( tabContentKey, {
					'id': 'mn-accordion-content-' + tabindex + tabCount,
					'class': contentClasses,
					'data-tab': tabCount,
					'role': 'region',
					'aria-labelledby': 'mn-accordion-title-' + tabindex + tabCount
				} );

				if ( ! isActive ) {
					view.addRenderAttribute( tabContentKey, 'hidden', 'hidden' );
				}

				var titleHTMLTag = elementor.helpers.validateHTMLTag( settings.title_html_tag );
			#>
			<div class="{{ itemClass }}">
				<{{{ titleHTMLTag }}} {{{ view.getRenderAttributeString( tabTitleKey ) }}}>
					<# if ( showNumbering && numberingPosition === 'before_icon' ) { #>
						<span class="mn-accordion-number">{{{ formattedNumber }}}</span>
					<# } #>

					<# if ( settings.selected_icon.value && iconAlign === 'left' ) { #>
						<span class="mn-accordion-icon" aria-hidden="true">
							<span class="mn-accordion-icon-closed">{{{ iconHTML.value }}}</span>
							<span class="mn-accordion-icon-opened">{{{ iconActiveHTML.value }}}</span>
						</span>
					<# } #>

					<# if ( showNumbering && numberingPosition === 'after_icon' ) { #>
						<span class="mn-accordion-number">{{{ formattedNumber }}}</span>
					<# } #>

					<# if ( showNumbering && numberingPosition === 'before_title' ) { #>
						<span class="mn-accordion-number">{{{ formattedNumber }}}</span>
					<# } #>

					<span class="mn-accordion-title-text">{{{ item.tab_title }}}</span>

					<# if ( settings.selected_icon.value && iconAlign === 'right' ) { #>
						<span class="mn-accordion-icon" aria-hidden="true">
							<span class="mn-accordion-icon-closed">{{{ iconHTML.value }}}</span>
							<span class="mn-accordion-icon-opened">{{{ iconActiveHTML.value }}}</span>
						</span>
					<# } #>
				</{{{ titleHTMLTag }}}>
				<div {{{ view.getRenderAttributeString( tabContentKey ) }}}>
					<div class="mn-accordion-content-inner">{{{ item.tab_content }}}</div>
				</div>
			</div>
			<# } ); #>
		</div>
		<?php
	}
}
