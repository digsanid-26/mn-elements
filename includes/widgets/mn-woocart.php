<?php
namespace MN_Elements\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * MN WooCart Widget
 *
 * WooCommerce cart widget with menu and modal display options
 *
 * @since 1.3.0
 */
class MN_WooCart extends Widget_Base {

	public function get_name() {
		return 'mn-woocart';
	}

	public function get_title() {
		return esc_html__( 'MN WooCart', 'mn-elements' );
	}

	public function get_icon() {
		return 'eicon-cart';
	}

	public function get_categories() {
		return [ 'mn-elements' ];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'cart', 'shop', 'basket', 'checkout' ];
	}

	protected function register_controls() {
		// Display Settings
		$this->start_controls_section(
			'section_display',
			[
				'label' => esc_html__( 'Display Settings', 'mn-elements' ),
			]
		);

		$this->add_control(
			'display_type',
			[
				'label' => esc_html__( 'Display Type', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'menu',
				'options' => [
					'menu' => esc_html__( 'Menu (Click to Open)', 'mn-elements' ),
					'direct' => esc_html__( 'Direct Display', 'mn-elements' ),
				],
			]
		);

		$this->add_control(
			'cart_icon',
			[
				'label' => esc_html__( 'Cart Icon', 'mn-elements' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-shopping-cart',
					'library' => 'fa-solid',
				],
				'condition' => [
					'display_type' => 'menu',
				],
			]
		);

		$this->add_control(
			'show_count',
			[
				'label' => esc_html__( 'Show Item Count', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'display_type' => 'menu',
				],
			]
		);

		$this->add_control(
			'show_total',
			[
				'label' => esc_html__( 'Show Cart Total', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'display_type' => 'menu',
				],
			]
		);

		$this->add_control(
			'total_format',
			[
				'label' => esc_html__( 'Total Format', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'with_currency',
				'options' => [
					'with_currency' => esc_html__( 'With Currency Symbol', 'mn-elements' ),
					'without_currency' => esc_html__( 'Amount Only', 'mn-elements' ),
					'currency_code' => esc_html__( 'With Currency Code', 'mn-elements' ),
				],
				'condition' => [
					'display_type' => 'menu',
					'show_total' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		// Cart Items Settings
		$this->start_controls_section(
			'section_cart_items',
			[
				'label' => esc_html__( 'Cart Items', 'mn-elements' ),
			]
		);

		$this->add_control(
			'show_thumbnail',
			[
				'label' => esc_html__( 'Show Thumbnail', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_price',
			[
				'label' => esc_html__( 'Show Price', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_quantity',
			[
				'label' => esc_html__( 'Show Quantity Controls', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'custom_field',
			[
				'label' => esc_html__( 'Custom Field (Meta Key)', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'e.g., _product_subtitle', 'mn-elements' ),
			]
		);

		$this->add_control(
			'show_remove',
			[
				'label' => esc_html__( 'Show Remove Button', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->end_controls_section();

		// Style controls will continue in next part...
		$this->register_style_controls();
	}

	protected function register_style_controls() {
		// Trigger Button Style
		$this->start_controls_section(
			'section_style_trigger',
			[
				'label' => esc_html__( 'Trigger Button', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'display_type' => 'menu',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'trigger_typography',
				'selector' => '{{WRAPPER}} .mn-woocart-trigger',
			]
		);

		$this->add_control(
			'trigger_icon_size',
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
					'size' => 24,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-trigger i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-woocart-trigger svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'trigger_style_tabs' );

		$this->start_controls_tab(
			'trigger_normal',
			[
				'label' => esc_html__( 'Normal', 'mn-elements' ),
			]
		);

		$this->add_control(
			'trigger_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-trigger i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-woocart-trigger svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'trigger_color',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-trigger' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-woocart-count' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-woocart-total' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'trigger_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-trigger' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'trigger_hover',
			[
				'label' => esc_html__( 'Hover', 'mn-elements' ),
			]
		);

		$this->add_control(
			'trigger_hover_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-trigger:hover i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-woocart-trigger:hover svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'trigger_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-trigger:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-woocart-trigger:hover .mn-woocart-count' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-woocart-trigger:hover .mn-woocart-total' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'trigger_hover_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-trigger:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'trigger_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-trigger' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'trigger_border',
				'selector' => '{{WRAPPER}} .mn-woocart-trigger',
			]
		);

		$this->add_control(
			'trigger_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-trigger' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Cart Badge Style
		$this->start_controls_section(
			'section_style_badge',
			[
				'label' => esc_html__( 'Cart Badge', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'display_type' => 'menu',
					'show_count' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'badge_typography',
				'selector' => '{{WRAPPER}} .mn-woocart-count',
			]
		);

		$this->add_control(
			'badge_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-count' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'badge_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e74c3c',
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-count' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'badge_size',
			[
				'label' => esc_html__( 'Size', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 14,
						'max' => 40,
					],
				],
				'default' => [
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-count' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Cart Container Style
		$this->start_controls_section(
			'section_style_container',
			[
				'label' => esc_html__( 'Cart Container', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'container_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-dropdown' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .mn-woocart-direct' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'container_width',
			[
				'label' => esc_html__( 'Width', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 250,
						'max' => 600,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 400,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-dropdown' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-woocart-direct' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'container_max_height',
			[
				'label' => esc_html__( 'Max Height', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range' => [
					'px' => [
						'min' => 200,
						'max' => 800,
					],
					'vh' => [
						'min' => 20,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 500,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-items' => 'max-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'container_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-dropdown' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .mn-woocart-direct' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'container_border',
				'selector' => '{{WRAPPER}} .mn-woocart-dropdown, {{WRAPPER}} .mn-woocart-direct',
			]
		);

		$this->add_control(
			'container_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-dropdown' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .mn-woocart-direct' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'container_box_shadow',
				'selector' => '{{WRAPPER}} .mn-woocart-dropdown, {{WRAPPER}} .mn-woocart-direct',
			]
		);

		$this->end_controls_section();

		// Cart Items Style
		$this->start_controls_section(
			'section_style_items',
			[
				'label' => esc_html__( 'Cart Items', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'item_spacing',
			[
				'label' => esc_html__( 'Item Spacing', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'item_padding',
			[
				'label' => esc_html__( 'Item Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'item_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-item' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'item_border',
				'selector' => '{{WRAPPER}} .mn-woocart-item',
			]
		);

		$this->add_control(
			'item_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Product Name Style
		$this->start_controls_section(
			'section_style_product_name',
			[
				'label' => esc_html__( 'Product Name', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'product_name_typography',
				'selector' => '{{WRAPPER}} .mn-woocart-item-name',
			]
		);

		$this->add_control(
			'product_name_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-item-name' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Price Style
		$this->start_controls_section(
			'section_style_price',
			[
				'label' => esc_html__( 'Price', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_price' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'price_typography',
				'selector' => '{{WRAPPER}} .mn-woocart-item-price',
			]
		);

		$this->add_control(
			'price_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-item-price' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Custom Field Style
		$this->start_controls_section(
			'section_style_custom_field',
			[
				'label' => esc_html__( 'Custom Field', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'custom_field!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'custom_field_typography',
				'selector' => '{{WRAPPER}} .mn-woocart-item-custom',
			]
		);

		$this->add_control(
			'custom_field_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-item-custom' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Quantity Controls Style
		$this->start_controls_section(
			'section_style_quantity',
			[
				'label' => esc_html__( 'Quantity Controls', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_quantity' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'quantity_typography',
				'selector' => '{{WRAPPER}} .mn-woocart-qty-input',
			]
		);

		$this->add_control(
			'quantity_button_size',
			[
				'label' => esc_html__( 'Button Size', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 50,
					],
				],
				'default' => [
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-qty-btn' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'quantity_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-qty-input' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-woocart-qty-btn' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'quantity_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-qty-input' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .mn-woocart-qty-btn' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'quantity_border_color',
			[
				'label' => esc_html__( 'Border Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-qty-input' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .mn-woocart-qty-btn' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Remove Button Style
		$this->start_controls_section(
			'section_style_remove',
			[
				'label' => esc_html__( 'Remove Button', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_remove' => 'yes',
				],
			]
		);

		$this->add_control(
			'remove_size',
			[
				'label' => esc_html__( 'Size', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 12,
						'max' => 30,
					],
				],
				'default' => [
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-remove' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'remove_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e74c3c',
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-remove' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'remove_hover_color',
			[
				'label' => esc_html__( 'Hover Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#c0392b',
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-remove:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Buttons Style
		$this->start_controls_section(
			'section_style_buttons',
			[
				'label' => esc_html__( 'Buttons', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		// View Cart Button
		$this->add_control(
			'view_cart_heading',
			[
				'label' => esc_html__( 'View Cart Button', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'view_cart_typography',
				'selector' => '{{WRAPPER}} .mn-woocart-view-cart',
			]
		);

		$this->start_controls_tabs( 'view_cart_style_tabs' );

		$this->start_controls_tab(
			'view_cart_normal',
			[
				'label' => esc_html__( 'Normal', 'mn-elements' ),
			]
		);

		$this->add_control(
			'view_cart_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-view-cart' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'view_cart_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#f5f5f5',
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-view-cart' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'view_cart_border',
				'selector' => '{{WRAPPER}} .mn-woocart-view-cart',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'view_cart_hover',
			[
				'label' => esc_html__( 'Hover', 'mn-elements' ),
			]
		);

		$this->add_control(
			'view_cart_hover_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-view-cart:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'view_cart_hover_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e0e0e0',
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-view-cart:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'view_cart_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-view-cart:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'view_cart_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-view-cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'view_cart_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-view-cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Checkout Button
		$this->add_control(
			'checkout_heading',
			[
				'label' => esc_html__( 'Checkout Button', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'checkout_typography',
				'selector' => '{{WRAPPER}} .mn-woocart-checkout',
			]
		);

		$this->start_controls_tabs( 'checkout_style_tabs' );

		$this->start_controls_tab(
			'checkout_normal',
			[
				'label' => esc_html__( 'Normal', 'mn-elements' ),
			]
		);

		$this->add_control(
			'checkout_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-checkout' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'checkout_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#27ae60',
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-checkout' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'checkout_border',
				'selector' => '{{WRAPPER}} .mn-woocart-checkout',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'checkout_hover',
			[
				'label' => esc_html__( 'Hover', 'mn-elements' ),
			]
		);

		$this->add_control(
			'checkout_hover_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-checkout:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'checkout_hover_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#229954',
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-checkout:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'checkout_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-checkout:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'checkout_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-checkout' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'checkout_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-woocart-checkout' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'checkout_box_shadow',
				'selector' => '{{WRAPPER}} .mn-woocart-checkout',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			echo '<div class="mn-woocart-notice">' . esc_html__( 'WooCommerce is not installed or activated.', 'mn-elements' ) . '</div>';
			return;
		}

		$settings = $this->get_settings_for_display();
		$widget_id = $this->get_id();
		$cart = WC()->cart;

		if ( ! $cart ) {
			return;
		}

		$cart_count = $cart->get_cart_contents_count();
		$cart_total = $this->get_formatted_cart_total( $cart, $settings );

		?>
		<div class="mn-woocart-wrapper mn-woocart-<?php echo esc_attr( $settings['display_type'] ); ?>" data-widget-id="<?php echo esc_attr( $widget_id ); ?>">
			
			<?php if ( $settings['display_type'] === 'menu' ) : ?>
				<button class="mn-woocart-trigger" type="button">
					<?php Icons_Manager::render_icon( $settings['cart_icon'], [ 'aria-hidden' => 'true' ] ); ?>
					<?php if ( $settings['show_count'] === 'yes' ) : ?>
						<span class="mn-woocart-count"><?php echo esc_html( $cart_count ); ?></span>
					<?php endif; ?>
					<?php if ( $settings['show_total'] === 'yes' ) : ?>
						<span class="mn-woocart-total"><?php echo wp_kses_post( $cart_total ); ?></span>
					<?php endif; ?>
				</button>
			<?php endif; ?>

			<div class="mn-woocart-<?php echo esc_attr( $settings['display_type'] === 'menu' ? 'dropdown' : 'direct' ); ?>">
				<?php $this->render_cart_items( $settings ); ?>
			</div>
		</div>
		<?php
	}

	protected function render_cart_items( $settings ) {
		$cart = WC()->cart;
		$cart_items = $cart->get_cart();

		if ( empty( $cart_items ) ) {
			echo '<div class="mn-woocart-empty">' . esc_html__( 'Your cart is empty.', 'mn-elements' ) . '</div>';
			return;
		}

		?>
		<div class="mn-woocart-items">
			<?php foreach ( $cart_items as $cart_item_key => $cart_item ) :
				$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 ) :
					$product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
					$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
					$product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
					?>
					<div class="mn-woocart-item" data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>">
						<div class="mn-woocart-item-row">
							<?php if ( $settings['show_thumbnail'] === 'yes' ) : ?>
								<div class="mn-woocart-item-thumbnail">
									<?php echo wp_kses_post( $thumbnail ); ?>
								</div>
							<?php endif; ?>

							<div class="mn-woocart-item-details">
								<div class="mn-woocart-item-name"><?php echo wp_kses_post( $product_name ); ?></div>
								
								<?php if ( $settings['show_price'] === 'yes' ) : ?>
									<div class="mn-woocart-item-price"><?php echo wp_kses_post( $product_price ); ?></div>
								<?php endif; ?>

								<?php if ( ! empty( $settings['custom_field'] ) ) :
									$custom_value = get_post_meta( $product_id, $settings['custom_field'], true );
									if ( $custom_value ) : ?>
										<div class="mn-woocart-item-custom"><?php echo esc_html( $custom_value ); ?></div>
									<?php endif;
								endif; ?>

								<?php if ( $settings['show_quantity'] === 'yes' ) : ?>
									<div class="mn-woocart-item-quantity">
										<button type="button" class="mn-woocart-qty-btn mn-woocart-qty-minus" data-action="minus">-</button>
										<input type="number" class="mn-woocart-qty-input" value="<?php echo esc_attr( $cart_item['quantity'] ); ?>" min="0" readonly>
										<button type="button" class="mn-woocart-qty-btn mn-woocart-qty-plus" data-action="plus">+</button>
									</div>
								<?php endif; ?>
							</div>

							<?php if ( $settings['show_remove'] === 'yes' ) : ?>
								<button type="button" class="mn-woocart-remove" data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>" title="<?php esc_attr_e( 'Remove item', 'mn-elements' ); ?>">
									<i class="eicon-close"></i>
								</button>
							<?php endif; ?>
						</div>
					</div>
				<?php endif;
			endforeach; ?>
		</div>

		<div class="mn-woocart-footer">
			<div class="mn-woocart-subtotal">
				<span><?php esc_html_e( 'Subtotal:', 'mn-elements' ); ?></span>
				<span class="mn-woocart-subtotal-amount"><?php echo wp_kses_post( $cart->get_cart_subtotal() ); ?></span>
			</div>
			<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="mn-woocart-view-cart button">
				<?php esc_html_e( 'View Cart', 'mn-elements' ); ?>
			</a>
			<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="mn-woocart-checkout button">
				<?php esc_html_e( 'Checkout', 'mn-elements' ); ?>
			</a>
		</div>
		<?php
	}

	private function get_formatted_cart_total( $cart, $settings ) {
		$total_format = isset( $settings['total_format'] ) ? $settings['total_format'] : 'with_currency';
		$cart_total = $cart->get_cart_contents_total() + $cart->get_cart_contents_tax();
		
		switch ( $total_format ) {
			case 'without_currency':
				// Amount only without currency symbol
				return number_format( $cart_total, wc_get_price_decimals(), wc_get_price_decimal_separator(), wc_get_price_thousand_separator() );
				
			case 'currency_code':
				// Amount with currency code (e.g., "100 USD")
				$currency_code = get_woocommerce_currency();
				$formatted_amount = number_format( $cart_total, wc_get_price_decimals(), wc_get_price_decimal_separator(), wc_get_price_thousand_separator() );
				return $formatted_amount . ' ' . $currency_code;
				
			case 'with_currency':
			default:
				// Default WooCommerce format with currency symbol
				return $cart->get_cart_total();
		}
	}
}
