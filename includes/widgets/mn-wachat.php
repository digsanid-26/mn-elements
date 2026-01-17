<?php
namespace MN_Elements\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * MN Wachat Widget
 *
 * WhatsApp chat widget with textarea and send button
 *
 * @since 1.0.0
 */
class MN_Wachat extends Widget_Base {

	/**
	 * Get widget name.
	 */
	public function get_name() {
		return 'mn-wachat';
	}

	/**
	 * Get widget title.
	 */
	public function get_title() {
		return esc_html__( 'MN Wachat', 'mn-elements' );
	}

	/**
	 * Get widget icon.
	 */
	public function get_icon() {
		return 'eicon-comments';
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
		return [ 'whatsapp', 'chat', 'message', 'contact', 'wa' ];
	}

	/**
	 * Register widget controls.
	 */
	protected function register_controls() {

		// Display Mode Section
		$this->start_controls_section(
			'section_display_mode',
			[
				'label' => esc_html__( 'Display Mode', 'mn-elements' ),
			]
		);

		$this->add_control(
			'display_mode',
			[
				'label' => esc_html__( 'Display Mode', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'form',
				'options' => [
					'form' => esc_html__( 'WA Form', 'mn-elements' ),
					'floating' => esc_html__( 'Floating WA', 'mn-elements' ),
					'quick_order' => esc_html__( 'Quick Order', 'mn-elements' ),
				],
				'description' => esc_html__( 'Choose between inline form, floating button with popup, or quick order button', 'mn-elements' ),
			]
		);

		$this->add_control(
			'floating_position',
			[
				'label' => esc_html__( 'Floating Position', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'bottom-right',
				'options' => [
					'bottom-right' => esc_html__( 'Bottom Right', 'mn-elements' ),
					'bottom-left' => esc_html__( 'Bottom Left', 'mn-elements' ),
				],
				'condition' => [
					'display_mode' => 'floating',
				],
			]
		);

		$this->add_responsive_control(
			'floating_bottom_offset',
			[
				'label' => esc_html__( 'Bottom Offset', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'default' => [
					'size' => 30,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-wachat-floating-button' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'display_mode' => 'floating',
				],
			]
		);

		$this->add_responsive_control(
			'floating_side_offset',
			[
				'label' => esc_html__( 'Side Offset', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'default' => [
					'size' => 30,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-wachat-floating-button.position-bottom-right' => 'right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-wachat-floating-button.position-bottom-left' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'display_mode' => 'floating',
				],
			]
		);

		$this->end_controls_section();

		// Quick Order Settings Section
		$this->start_controls_section(
			'section_quick_order',
			[
				'label' => esc_html__( 'Quick Order Settings', 'mn-elements' ),
				'condition' => [
					'display_mode' => 'quick_order',
				],
			]
		);

		$this->add_control(
			'quick_order_button_text',
			[
				'label' => esc_html__( 'Button Label', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Order Now', 'mn-elements' ),
				'placeholder' => esc_html__( 'Enter button label', 'mn-elements' ),
			]
		);

		$this->add_control(
			'quick_order_icon',
			[
				'label' => esc_html__( 'Button Icon', 'mn-elements' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fab fa-whatsapp',
					'library' => 'fa-brands',
				],
			]
		);

		$this->add_control(
			'quick_order_icon_position',
			[
				'label' => esc_html__( 'Icon Position', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => esc_html__( 'Left', 'mn-elements' ),
					'right' => esc_html__( 'Right', 'mn-elements' ),
				],
			]
		);

		$this->add_control(
			'quick_order_message',
			[
				'label' => esc_html__( 'Fixed Message', 'mn-elements' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Hi, I want to order from page: [page_title]', 'mn-elements' ),
				'placeholder' => esc_html__( 'Enter your fixed message', 'mn-elements' ),
				'description' => esc_html__( 'Use [page_title] or [post_title] to include the current page/post title dynamically.', 'mn-elements' ),
				'rows' => 5,
			]
		);

		$this->add_control(
			'quick_order_full_width',
			[
				'label' => esc_html__( 'Full Width Button', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'mn-elements' ),
				'label_off' => esc_html__( 'No', 'mn-elements' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->add_responsive_control(
			'quick_order_align',
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
				],
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .mn-wachat-quick-order-wrapper' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'quick_order_full_width' => '',
				],
			]
		);

		$this->end_controls_section();

		// CS Profile Section
		$this->start_controls_section(
			'section_cs_profile',
			[
				'label' => esc_html__( 'CS Profile', 'mn-elements' ),
			]
		);

		$this->add_control(
			'show_cs_profile',
			[
				'label' => esc_html__( 'Show CS Profile', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'mn-elements' ),
				'label_off' => esc_html__( 'Hide', 'mn-elements' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'cs_photo',
			[
				'label' => esc_html__( 'CS Photo', 'mn-elements' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'show_cs_profile' => 'yes',
				],
			]
		);

		$this->add_control(
			'cs_name',
			[
				'label' => esc_html__( 'CS Name', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Customer Service', 'mn-elements' ),
				'placeholder' => esc_html__( 'Enter CS name', 'mn-elements' ),
				'condition' => [
					'show_cs_profile' => 'yes',
				],
			]
		);

		$this->add_control(
			'cs_title',
			[
				'label' => esc_html__( 'CS Title/Position', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Support Team', 'mn-elements' ),
				'placeholder' => esc_html__( 'Enter CS title', 'mn-elements' ),
				'condition' => [
					'show_cs_profile' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_status_indicator',
			[
				'label' => esc_html__( 'Show Status Indicator', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'mn-elements' ),
				'label_off' => esc_html__( 'Hide', 'mn-elements' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'show_cs_profile' => 'yes',
				],
			]
		);

		$this->add_control(
			'status_mode',
			[
				'label' => esc_html__( 'Status Mode', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'working_hours',
				'options' => [
					'always_online' => esc_html__( 'Always Online', 'mn-elements' ),
					'always_offline' => esc_html__( 'Always Offline', 'mn-elements' ),
					'working_hours' => esc_html__( 'Based on Working Hours', 'mn-elements' ),
				],
				'condition' => [
					'show_cs_profile' => 'yes',
					'show_status_indicator' => 'yes',
				],
			]
		);

		$this->add_control(
			'online_text',
			[
				'label' => esc_html__( 'Online Text', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Online', 'mn-elements' ),
				'condition' => [
					'show_cs_profile' => 'yes',
					'show_status_indicator' => 'yes',
				],
			]
		);

		$this->add_control(
			'offline_text',
			[
				'label' => esc_html__( 'Offline Text', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Offline', 'mn-elements' ),
				'condition' => [
					'show_cs_profile' => 'yes',
					'show_status_indicator' => 'yes',
				],
			]
		);

		$this->add_control(
			'working_hours_heading',
			[
				'label' => esc_html__( 'Working Hours', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_cs_profile' => 'yes',
					'show_status_indicator' => 'yes',
					'status_mode' => 'working_hours',
				],
			]
		);

		$this->add_control(
			'timezone',
			[
				'label' => esc_html__( 'Timezone', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'Asia/Jakarta',
				'options' => [
					'Asia/Jakarta' => 'WIB (UTC+7)',
					'Asia/Makassar' => 'WITA (UTC+8)',
					'Asia/Jayapura' => 'WIT (UTC+9)',
					'Asia/Singapore' => 'Singapore (UTC+8)',
					'Asia/Kuala_Lumpur' => 'Malaysia (UTC+8)',
					'Asia/Bangkok' => 'Bangkok (UTC+7)',
				],
				'condition' => [
					'show_cs_profile' => 'yes',
					'show_status_indicator' => 'yes',
					'status_mode' => 'working_hours',
				],
			]
		);

		$this->add_control(
			'start_time',
			[
				'label' => esc_html__( 'Start Time', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => '09:00',
				'placeholder' => '09:00',
				'description' => esc_html__( 'Format: HH:MM (24-hour format)', 'mn-elements' ),
				'condition' => [
					'show_cs_profile' => 'yes',
					'show_status_indicator' => 'yes',
					'status_mode' => 'working_hours',
				],
			]
		);

		$this->add_control(
			'end_time',
			[
				'label' => esc_html__( 'End Time', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => '17:00',
				'placeholder' => '17:00',
				'description' => esc_html__( 'Format: HH:MM (24-hour format)', 'mn-elements' ),
				'condition' => [
					'show_cs_profile' => 'yes',
					'show_status_indicator' => 'yes',
					'status_mode' => 'working_hours',
				],
			]
		);

		$this->add_control(
			'working_days',
			[
				'label' => esc_html__( 'Working Days', 'mn-elements' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'default' => ['1', '2', '3', '4', '5'],
				'options' => [
					'0' => esc_html__( 'Sunday', 'mn-elements' ),
					'1' => esc_html__( 'Monday', 'mn-elements' ),
					'2' => esc_html__( 'Tuesday', 'mn-elements' ),
					'3' => esc_html__( 'Wednesday', 'mn-elements' ),
					'4' => esc_html__( 'Thursday', 'mn-elements' ),
					'5' => esc_html__( 'Friday', 'mn-elements' ),
					'6' => esc_html__( 'Saturday', 'mn-elements' ),
				],
				'condition' => [
					'show_cs_profile' => 'yes',
					'show_status_indicator' => 'yes',
					'status_mode' => 'working_hours',
				],
			]
		);

		$this->end_controls_section();

		// WhatsApp Settings Section
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'WhatsApp Settings', 'mn-elements' ),
			]
		);

		$this->add_control(
			'phone_number',
			[
				'label' => esc_html__( 'WhatsApp Number', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => '628123456789',
				'description' => esc_html__( 'Enter WhatsApp number with country code (e.g., 628123456789)', 'mn-elements' ),
				'default' => '',
			]
		);

		$this->add_control(
			'placeholder_text',
			[
				'label' => esc_html__( 'Placeholder Text', 'mn-elements' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Type your message here...', 'mn-elements' ),
				'placeholder' => esc_html__( 'Enter placeholder text', 'mn-elements' ),
			]
		);

		$this->add_control(
			'button_text',
			[
				'label' => esc_html__( 'Button Text', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Chat Now', 'mn-elements' ),
			]
		);

		$this->add_control(
			'button_icon',
			[
				'label' => esc_html__( 'Button Icon', 'mn-elements' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fab fa-whatsapp',
					'library' => 'fa-brands',
				],
			]
		);

		$this->add_control(
			'icon_position',
			[
				'label' => esc_html__( 'Icon Position', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => esc_html__( 'Left', 'mn-elements' ),
					'right' => esc_html__( 'Right', 'mn-elements' ),
				],
			]
		);

		$this->add_responsive_control(
			'icon_spacing',
			[
				'label' => esc_html__( 'Icon Spacing', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'size' => 8,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-wachat-button .mn-wachat-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-wachat-button .mn-wachat-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Textarea Style
		$this->start_controls_section(
			'section_textarea_style',
			[
				'label' => esc_html__( 'Textarea', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'textarea_typography',
				'selector' => '{{WRAPPER}} .mn-wachat-textarea',
			]
		);

		$this->add_control(
			'textarea_text_color',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .mn-wachat-textarea' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'textarea_placeholder_color',
			[
				'label' => esc_html__( 'Placeholder Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#999999',
				'selectors' => [
					'{{WRAPPER}} .mn-wachat-textarea::placeholder' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'textarea_background',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-wachat-textarea' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'textarea_height',
			[
				'label' => esc_html__( 'Height', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 500,
					],
				],
				'default' => [
					'size' => 120,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-wachat-textarea' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'textarea_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'top' => 15,
					'right' => 15,
					'bottom' => 15,
					'left' => 15,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-wachat-textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'textarea_border',
				'selector' => '{{WRAPPER}} .mn-wachat-textarea',
			]
		);

		$this->add_responsive_control(
			'textarea_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 8,
					'right' => 8,
					'bottom' => 8,
					'left' => 8,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-wachat-textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'textarea_box_shadow',
				'selector' => '{{WRAPPER}} .mn-wachat-textarea',
			]
		);

		$this->end_controls_section();

		// Button Style
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
				'selector' => '{{WRAPPER}} .mn-wachat-button',
			]
		);

		$this->add_responsive_control(
			'button_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-wachat-button i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-wachat-button svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'button_style_tabs' );

		// Normal State
		$this->start_controls_tab(
			'button_normal',
			[
				'label' => esc_html__( 'Normal', 'mn-elements' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-wachat-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#25D366',
				'selectors' => [
					'{{WRAPPER}} .mn-wachat-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		// Hover State
		$this->start_controls_tab(
			'button_hover',
			[
				'label' => esc_html__( 'Hover', 'mn-elements' ),
			]
		);

		$this->add_control(
			'button_text_color_hover',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-wachat-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_hover',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#128C7E',
				'selectors' => [
					'{{WRAPPER}} .mn-wachat-button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_border_color_hover',
			[
				'label' => esc_html__( 'Border Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-wachat-button:hover' => 'border-color: {{VALUE}};',
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
				'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'top' => 15,
					'right' => 30,
					'bottom' => 15,
					'left' => 30,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-wachat-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_margin',
			[
				'label' => esc_html__( 'Margin', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'top' => 15,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-wachat-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .mn-wachat-button',
			]
		);

		$this->add_responsive_control(
			'button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 8,
					'right' => 8,
					'bottom' => 8,
					'left' => 8,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-wachat-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .mn-wachat-button',
			]
		);

		$this->add_responsive_control(
			'button_align',
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
					'{{WRAPPER}} .mn-wachat-button-wrapper' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Quick Order Button Style
		$this->start_controls_section(
			'section_quick_order_style',
			[
				'label' => esc_html__( 'Quick Order Button', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'display_mode' => 'quick_order',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'quick_order_typography',
				'selector' => '{{WRAPPER}} .mn-wachat-quick-order-button',
			]
		);

		$this->add_responsive_control(
			'quick_order_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-wachat-quick-order-button i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-wachat-quick-order-button svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'quick_order_icon_spacing',
			[
				'label' => esc_html__( 'Icon Spacing', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-wachat-quick-order-button .mn-wachat-qo-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-wachat-quick-order-button .mn-wachat-qo-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'quick_order_style_tabs' );

		// Normal State
		$this->start_controls_tab(
			'quick_order_normal',
			[
				'label' => esc_html__( 'Normal', 'mn-elements' ),
			]
		);

		$this->add_control(
			'quick_order_text_color',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-wachat-quick-order-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'quick_order_background',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#25D366',
				'selectors' => [
					'{{WRAPPER}} .mn-wachat-quick-order-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		// Hover State
		$this->start_controls_tab(
			'quick_order_hover',
			[
				'label' => esc_html__( 'Hover', 'mn-elements' ),
			]
		);

		$this->add_control(
			'quick_order_text_color_hover',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-wachat-quick-order-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'quick_order_background_hover',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#128C7E',
				'selectors' => [
					'{{WRAPPER}} .mn-wachat-quick-order-button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'quick_order_border_color_hover',
			[
				'label' => esc_html__( 'Border Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-wachat-quick-order-button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'quick_order_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'top' => 15,
					'right' => 30,
					'bottom' => 15,
					'left' => 30,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-wachat-quick-order-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'quick_order_border',
				'selector' => '{{WRAPPER}} .mn-wachat-quick-order-button',
			]
		);

		$this->add_responsive_control(
			'quick_order_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 8,
					'right' => 8,
					'bottom' => 8,
					'left' => 8,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-wachat-quick-order-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'quick_order_box_shadow',
				'selector' => '{{WRAPPER}} .mn-wachat-quick-order-button',
			]
		);

		$this->end_controls_section();

		// CS Profile Style
		$this->start_controls_section(
			'section_cs_profile_style',
			[
				'label' => esc_html__( 'CS Profile', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_cs_profile' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'cs_profile_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'top' => 15,
					'right' => 15,
					'bottom' => 15,
					'left' => 15,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-wachat-cs-profile' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'cs_profile_margin',
			[
				'label' => esc_html__( 'Margin', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 15,
					'left' => 0,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-wachat-cs-profile' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'cs_profile_background',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#f8f9fa',
				'selectors' => [
					'{{WRAPPER}} .mn-wachat-cs-profile' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'cs_profile_border',
				'selector' => '{{WRAPPER}} .mn-wachat-cs-profile',
			]
		);

		$this->add_responsive_control(
			'cs_profile_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 8,
					'right' => 8,
					'bottom' => 8,
					'left' => 8,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-wachat-cs-profile' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'cs_photo_size',
			[
				'label' => esc_html__( 'Photo Size', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 30,
						'max' => 150,
					],
				],
				'default' => [
					'size' => 60,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-wachat-cs-photo' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'cs_photo_border_radius',
			[
				'label' => esc_html__( 'Photo Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'%' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'size' => 50,
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-wachat-cs-photo' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'cs_name_typography',
				'label' => esc_html__( 'Name Typography', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-wachat-cs-name',
			]
		);

		$this->add_control(
			'cs_name_color',
			[
				'label' => esc_html__( 'Name Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .mn-wachat-cs-name' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'cs_title_typography',
				'label' => esc_html__( 'Title Typography', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-wachat-cs-title',
			]
		);

		$this->add_control(
			'cs_title_color',
			[
				'label' => esc_html__( 'Title Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#666666',
				'selectors' => [
					'{{WRAPPER}} .mn-wachat-cs-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'status_heading',
			[
				'label' => esc_html__( 'Status Indicator', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_status_indicator' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'status_typography',
				'label' => esc_html__( 'Status Typography', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-wachat-status-text',
				'condition' => [
					'show_status_indicator' => 'yes',
				],
			]
		);

		$this->add_control(
			'online_color',
			[
				'label' => esc_html__( 'Online Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#25D366',
				'selectors' => [
					'{{WRAPPER}} .mn-wachat-status.online .mn-wachat-status-dot' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .mn-wachat-status.online .mn-wachat-status-text' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_status_indicator' => 'yes',
				],
			]
		);

		$this->add_control(
			'offline_color',
			[
				'label' => esc_html__( 'Offline Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#999999',
				'selectors' => [
					'{{WRAPPER}} .mn-wachat-status.offline .mn-wachat-status-dot' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .mn-wachat-status.offline .mn-wachat-status-text' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_status_indicator' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'status_dot_size',
			[
				'label' => esc_html__( 'Dot Size', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 4,
						'max' => 20,
					],
				],
				'default' => [
					'size' => 8,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-wachat-status-dot' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_status_indicator' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Check if CS is online based on working hours
	 */
	private function is_online( $settings ) {
		$status_mode = $settings['status_mode'];

		if ( $status_mode === 'always_online' ) {
			return true;
		}

		if ( $status_mode === 'always_offline' ) {
			return false;
		}

		// Check working hours
		$timezone = $settings['timezone'];
		$start_time = $settings['start_time'];
		$end_time = $settings['end_time'];
		$working_days = $settings['working_days'];

		if ( empty( $working_days ) ) {
			return false;
		}

		try {
			$tz = new \DateTimeZone( $timezone );
			$now = new \DateTime( 'now', $tz );
			$current_day = $now->format( 'w' ); // 0 (Sunday) to 6 (Saturday)
			$current_time = $now->format( 'H:i' );

			// Check if today is a working day
			if ( ! in_array( $current_day, $working_days ) ) {
				return false;
			}

			// Check if current time is within working hours
			if ( $current_time >= $start_time && $current_time <= $end_time ) {
				return true;
			}
		} catch ( \Exception $e ) {
			return false;
		}

		return false;
	}

	/**
	 * Render widget output on the frontend.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$phone_number = $settings['phone_number'];
		$display_mode = $settings['display_mode'];

		if ( empty( $phone_number ) ) {
			echo '<div class="mn-wachat-error">' . esc_html__( 'Please enter WhatsApp number in widget settings.', 'mn-elements' ) . '</div>';
			return;
		}

		if ( $display_mode === 'floating' ) {
			$this->render_floating_mode( $settings );
		} elseif ( $display_mode === 'quick_order' ) {
			$this->render_quick_order_mode( $settings );
		} else {
			$this->render_form_mode( $settings );
		}
	}

	/**
	 * Process message shortcodes
	 * Replaces [page_title] and [post_title] with actual page/post title
	 */
	private function process_message_shortcodes( $message ) {
		$title = '';
		
		// Get the current page/post title
		if ( is_singular() ) {
			$title = get_the_title();
		} elseif ( is_archive() ) {
			$title = get_the_archive_title();
		} elseif ( is_home() ) {
			$title = get_bloginfo( 'name' );
		} else {
			// Fallback to queried object
			$queried_object = get_queried_object();
			if ( $queried_object && isset( $queried_object->post_title ) ) {
				$title = $queried_object->post_title;
			} else {
				$title = get_bloginfo( 'name' );
			}
		}
		
		// Replace shortcodes
		$message = str_replace( '[page_title]', $title, $message );
		$message = str_replace( '[post_title]', $title, $message );
		
		return $message;
	}

	/**
	 * Render WA Form mode (inline form)
	 */
	protected function render_form_mode( $settings ) {
		$phone_number = $settings['phone_number'];
		$placeholder = $settings['placeholder_text'];
		$button_text = $settings['button_text'];
		$icon_position = $settings['icon_position'];
		$show_cs_profile = $settings['show_cs_profile'];

		// Check online status
		$is_online = $this->is_online( $settings );
		$status_class = $is_online ? 'online' : 'offline';
		$status_text = $is_online ? $settings['online_text'] : $settings['offline_text'];
		?>
		<div class="mn-wachat-wrapper mn-wachat-form-mode">
			<?php if ( $show_cs_profile === 'yes' ) : ?>
				<div class="mn-wachat-cs-profile">
					<div class="mn-wachat-cs-photo-wrapper">
						<img src="<?php echo esc_url( $settings['cs_photo']['url'] ); ?>" alt="<?php echo esc_attr( $settings['cs_name'] ); ?>" class="mn-wachat-cs-photo">
						<?php if ( $settings['show_status_indicator'] === 'yes' ) : ?>
							<span class="mn-wachat-status-badge <?php echo esc_attr( $status_class ); ?>"></span>
						<?php endif; ?>
					</div>
					<div class="mn-wachat-cs-info">
						<div class="mn-wachat-cs-name"><?php echo esc_html( $settings['cs_name'] ); ?></div>
						<div class="mn-wachat-cs-title"><?php echo esc_html( $settings['cs_title'] ); ?></div>
						<?php if ( $settings['show_status_indicator'] === 'yes' ) : ?>
							<div class="mn-wachat-status <?php echo esc_attr( $status_class ); ?>">
								<span class="mn-wachat-status-dot"></span>
								<span class="mn-wachat-status-text"><?php echo esc_html( $status_text ); ?></span>
							</div>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
			<textarea 
				class="mn-wachat-textarea" 
				placeholder="<?php echo esc_attr( $placeholder ); ?>"
				rows="5"
			></textarea>
			
			<div class="mn-wachat-button-wrapper">
				<button 
					class="mn-wachat-button" 
					data-phone="<?php echo esc_attr( $phone_number ); ?>"
				>
					<?php if ( $icon_position === 'left' && ! empty( $settings['button_icon']['value'] ) ) : ?>
						<span class="mn-wachat-icon mn-wachat-icon-left">
							<?php \Elementor\Icons_Manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true' ] ); ?>
						</span>
					<?php endif; ?>
					
					<span class="mn-wachat-button-text"><?php echo esc_html( $button_text ); ?></span>
					
					<?php if ( $icon_position === 'right' && ! empty( $settings['button_icon']['value'] ) ) : ?>
						<span class="mn-wachat-icon mn-wachat-icon-right">
							<?php \Elementor\Icons_Manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true' ] ); ?>
						</span>
					<?php endif; ?>
				</button>
			</div>
		</div>
		<?php
	}

	/**
	 * Render Floating WA mode (floating button with popup)
	 */
	protected function render_floating_mode( $settings ) {
		$phone_number = $settings['phone_number'];
		$placeholder = $settings['placeholder_text'];
		$button_text = $settings['button_text'];
		$icon_position = $settings['icon_position'];
		$show_cs_profile = $settings['show_cs_profile'];
		$floating_position = $settings['floating_position'];

		// Check online status
		$is_online = $this->is_online( $settings );
		$status_class = $is_online ? 'online' : 'offline';
		$status_text = $is_online ? $settings['online_text'] : $settings['offline_text'];

		$position_class = 'position-' . $floating_position;
		?>
		<div class="mn-wachat-wrapper mn-wachat-floating-mode">
			<!-- Floating Button -->
			<div class="mn-wachat-floating-button <?php echo esc_attr( $position_class ); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
					<path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
				</svg>
				<?php if ( $settings['show_status_indicator'] === 'yes' ) : ?>
					<span class="mn-wachat-floating-badge <?php echo esc_attr( $status_class ); ?>"></span>
				<?php endif; ?>
			</div>

			<!-- Popup Dialog -->
			<div class="mn-wachat-popup <?php echo esc_attr( $position_class ); ?>" style="display: none;">
				<div class="mn-wachat-popup-header">
					<div class="mn-wachat-popup-title">
						<?php echo esc_html__( 'Start Chat', 'mn-elements' ); ?>
					</div>
					<button class="mn-wachat-popup-close">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<line x1="18" y1="6" x2="6" y2="18"></line>
							<line x1="6" y1="6" x2="18" y2="18"></line>
						</svg>
					</button>
				</div>
				<div class="mn-wachat-popup-content">
					<?php if ( $show_cs_profile === 'yes' ) : ?>
						<div class="mn-wachat-cs-profile">
							<div class="mn-wachat-cs-photo-wrapper">
								<img src="<?php echo esc_url( $settings['cs_photo']['url'] ); ?>" alt="<?php echo esc_attr( $settings['cs_name'] ); ?>" class="mn-wachat-cs-photo">
								<?php if ( $settings['show_status_indicator'] === 'yes' ) : ?>
									<span class="mn-wachat-status-badge <?php echo esc_attr( $status_class ); ?>"></span>
								<?php endif; ?>
							</div>
							<div class="mn-wachat-cs-info">
								<div class="mn-wachat-cs-name"><?php echo esc_html( $settings['cs_name'] ); ?></div>
								<div class="mn-wachat-cs-title"><?php echo esc_html( $settings['cs_title'] ); ?></div>
								<?php if ( $settings['show_status_indicator'] === 'yes' ) : ?>
									<div class="mn-wachat-status <?php echo esc_attr( $status_class ); ?>">
										<span class="mn-wachat-status-dot"></span>
										<span class="mn-wachat-status-text"><?php echo esc_html( $status_text ); ?></span>
									</div>
								<?php endif; ?>
							</div>
						</div>
					<?php endif; ?>
					<textarea 
						class="mn-wachat-textarea" 
						placeholder="<?php echo esc_attr( $placeholder ); ?>"
						rows="5"
					></textarea>
					
					<div class="mn-wachat-button-wrapper">
						<button 
							class="mn-wachat-button" 
							data-phone="<?php echo esc_attr( $phone_number ); ?>"
						>
							<?php if ( $icon_position === 'left' && ! empty( $settings['button_icon']['value'] ) ) : ?>
								<span class="mn-wachat-icon mn-wachat-icon-left">
									<?php \Elementor\Icons_Manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true' ] ); ?>
								</span>
							<?php endif; ?>
							
							<span class="mn-wachat-button-text"><?php echo esc_html( $button_text ); ?></span>
							
							<?php if ( $icon_position === 'right' && ! empty( $settings['button_icon']['value'] ) ) : ?>
								<span class="mn-wachat-icon mn-wachat-icon-right">
									<?php \Elementor\Icons_Manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true' ] ); ?>
								</span>
							<?php endif; ?>
						</button>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render Quick Order mode (simple button with fixed message)
	 */
	protected function render_quick_order_mode( $settings ) {
		$phone_number = $settings['phone_number'];
		$button_text = ! empty( $settings['quick_order_button_text'] ) ? $settings['quick_order_button_text'] : esc_html__( 'Order Now', 'mn-elements' );
		$icon_position = ! empty( $settings['quick_order_icon_position'] ) ? $settings['quick_order_icon_position'] : 'left';
		$message = ! empty( $settings['quick_order_message'] ) ? $settings['quick_order_message'] : '';
		$full_width = $settings['quick_order_full_width'] === 'yes';
		
		// Process message shortcodes
		$processed_message = $this->process_message_shortcodes( $message );
		
		// Build WhatsApp URL
		$whatsapp_url = 'https://wa.me/' . $phone_number;
		if ( ! empty( $processed_message ) ) {
			$whatsapp_url .= '?text=' . rawurlencode( $processed_message );
		}
		
		$button_class = 'mn-wachat-quick-order-button';
		if ( $full_width ) {
			$button_class .= ' mn-wachat-qo-full-width';
		}
		?>
		<div class="mn-wachat-wrapper mn-wachat-quick-order-mode">
			<div class="mn-wachat-quick-order-wrapper">
				<a 
					href="<?php echo esc_url( $whatsapp_url ); ?>" 
					class="<?php echo esc_attr( $button_class ); ?>"
					target="_blank"
					rel="noopener noreferrer"
				>
					<?php if ( $icon_position === 'left' && ! empty( $settings['quick_order_icon']['value'] ) ) : ?>
						<span class="mn-wachat-qo-icon mn-wachat-qo-icon-left">
							<?php \Elementor\Icons_Manager::render_icon( $settings['quick_order_icon'], [ 'aria-hidden' => 'true' ] ); ?>
						</span>
					<?php endif; ?>
					
					<span class="mn-wachat-qo-button-text"><?php echo esc_html( $button_text ); ?></span>
					
					<?php if ( $icon_position === 'right' && ! empty( $settings['quick_order_icon']['value'] ) ) : ?>
						<span class="mn-wachat-qo-icon mn-wachat-qo-icon-right">
							<?php \Elementor\Icons_Manager::render_icon( $settings['quick_order_icon'], [ 'aria-hidden' => 'true' ] ); ?>
						</span>
					<?php endif; ?>
				</a>
			</div>
		</div>
		<?php
	}
}
