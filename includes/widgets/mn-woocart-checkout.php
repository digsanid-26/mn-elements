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

class MN_WooCart_Checkout extends Widget_Base {

	public function get_name() {
		return 'mn-woocart-checkout';
	}

	public function get_title() {
		return esc_html__( 'MN WooCart/Checkout', 'mn-elements' );
	}

	public function get_icon() {
		return 'eicon-cart';
	}

	public function get_categories() {
		return [ 'mn-elements' ];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'cart', 'checkout', 'mini cart', 'shopping', 'mn' ];
	}

	public function get_style_depends() {
		return [ 'mn-woocart-checkout-style' ];
	}

	public function get_script_depends() {
		return [ 'mn-woocart-checkout-script' ];
	}

	private function is_woocommerce_active() {
		return class_exists( 'WooCommerce' );
	}

	protected function register_controls() {
		$this->register_content_controls();
		$this->register_style_controls();
	}

	protected function register_content_controls() {
		// Widget Type Section
		$this->start_controls_section( 'section_type', [ 'label' => esc_html__( 'Widget Type', 'mn-elements' ) ] );

		$this->add_control( 'widget_type', [
			'label' => esc_html__( 'Type', 'mn-elements' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'mini_cart',
			'options' => [
				'mini_cart' => esc_html__( 'Mini Cart (Icon + Dropdown)', 'mn-elements' ),
				'cart_page' => esc_html__( 'Cart Page', 'mn-elements' ),
				'checkout_page' => esc_html__( 'Checkout Page', 'mn-elements' ),
				'cart_totals' => esc_html__( 'Cart Totals Only', 'mn-elements' ),
			],
		] );

		$this->end_controls_section();

		// Mini Cart Settings
		$this->start_controls_section( 'section_mini_cart', [
			'label' => esc_html__( 'Mini Cart Settings', 'mn-elements' ),
			'condition' => [ 'widget_type' => 'mini_cart' ],
		] );

		$this->add_control( 'cart_icon', [
			'label' => esc_html__( 'Cart Icon', 'mn-elements' ),
			'type' => Controls_Manager::ICONS,
			'default' => [ 'value' => 'fas fa-shopping-cart', 'library' => 'fa-solid' ],
		] );

		$this->add_control( 'show_cart_count', [
			'label' => esc_html__( 'Show Item Count', 'mn-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'default' => 'yes',
		] );

		$this->add_control( 'show_cart_total', [
			'label' => esc_html__( 'Show Cart Total', 'mn-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'default' => 'yes',
		] );

		$this->add_control( 'dropdown_position', [
			'label' => esc_html__( 'Dropdown Position', 'mn-elements' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'right',
			'options' => [
				'left' => esc_html__( 'Left', 'mn-elements' ),
				'right' => esc_html__( 'Right', 'mn-elements' ),
				'center' => esc_html__( 'Center', 'mn-elements' ),
			],
		] );

		$this->add_control( 'dropdown_trigger', [
			'label' => esc_html__( 'Dropdown Trigger', 'mn-elements' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'hover',
			'options' => [
				'hover' => esc_html__( 'Hover', 'mn-elements' ),
				'click' => esc_html__( 'Click', 'mn-elements' ),
			],
		] );

		$this->add_control( 'show_view_cart_btn', [
			'label' => esc_html__( 'Show View Cart Button', 'mn-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'default' => 'yes',
		] );

		$this->add_control( 'show_checkout_btn', [
			'label' => esc_html__( 'Show Checkout Button', 'mn-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'default' => 'yes',
		] );

		$this->add_control( 'empty_cart_message', [
			'label' => esc_html__( 'Empty Cart Message', 'mn-elements' ),
			'type' => Controls_Manager::TEXT,
			'default' => esc_html__( 'Your cart is empty', 'mn-elements' ),
		] );

		$this->end_controls_section();

		// Cart Page Settings
		$this->start_controls_section( 'section_cart_page', [
			'label' => esc_html__( 'Cart Page Settings', 'mn-elements' ),
			'condition' => [ 'widget_type' => 'cart_page' ],
		] );

		$this->add_control( 'show_product_image', [
			'label' => esc_html__( 'Show Product Image', 'mn-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'default' => 'yes',
		] );

		$this->add_control( 'show_product_price', [
			'label' => esc_html__( 'Show Product Price', 'mn-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'default' => 'yes',
		] );

		$this->add_control( 'show_quantity_input', [
			'label' => esc_html__( 'Show Quantity Input', 'mn-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'default' => 'yes',
		] );

		$this->add_control( 'show_subtotal', [
			'label' => esc_html__( 'Show Subtotal', 'mn-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'default' => 'yes',
		] );

		$this->add_control( 'show_remove_btn', [
			'label' => esc_html__( 'Show Remove Button', 'mn-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'default' => 'yes',
		] );

		$this->add_control( 'show_coupon', [
			'label' => esc_html__( 'Show Coupon Field', 'mn-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'default' => 'yes',
		] );

		$this->add_control( 'show_cart_totals', [
			'label' => esc_html__( 'Show Cart Totals', 'mn-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'default' => 'yes',
		] );

		$this->add_control( 'ajax_update', [
			'label' => esc_html__( 'AJAX Update', 'mn-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'default' => 'yes',
			'description' => esc_html__( 'Update cart without page reload', 'mn-elements' ),
		] );

		$this->end_controls_section();

		// Checkout Page Settings
		$this->start_controls_section( 'section_checkout_page', [
			'label' => esc_html__( 'Checkout Page Settings', 'mn-elements' ),
			'condition' => [ 'widget_type' => 'checkout_page' ],
		] );

		$this->add_control( 'checkout_layout', [
			'label' => esc_html__( 'Layout', 'mn-elements' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'two_column',
			'options' => [
				'one_column' => esc_html__( 'One Column', 'mn-elements' ),
				'two_column' => esc_html__( 'Two Columns', 'mn-elements' ),
			],
		] );

		$this->add_control( 'show_order_review', [
			'label' => esc_html__( 'Show Order Review', 'mn-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'default' => 'yes',
		] );

		$this->add_control( 'show_coupon_checkout', [
			'label' => esc_html__( 'Show Coupon Field', 'mn-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'default' => 'yes',
		] );

		$this->add_control( 'show_shipping_methods', [
			'label' => esc_html__( 'Show Shipping Methods', 'mn-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'default' => 'yes',
			'description' => esc_html__( 'Display shipping method selection including Local Pickup if enabled in WooCommerce', 'mn-elements' ),
		] );

		$this->add_control( 'highlight_local_pickup', [
			'label' => esc_html__( 'Highlight Local Pickup', 'mn-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'default' => 'no',
			'description' => esc_html__( 'Add visual emphasis to Local Pickup option', 'mn-elements' ),
			'condition' => [ 'show_shipping_methods' => 'yes' ],
		] );

		$this->add_control( 'heading_thankyou_page', [
			'label' => esc_html__( 'Thank You Page', 'mn-elements' ),
			'type' => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'custom_thankyou_page', [
			'label' => esc_html__( 'Custom Thank You Page', 'mn-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'default' => 'no',
			'description' => esc_html__( 'Redirect to a custom page after successful order instead of the default WooCommerce Thank You page.', 'mn-elements' ),
		] );

		$this->add_control( 'thankyou_page_id', [
			'label' => esc_html__( 'Select Page', 'mn-elements' ),
			'type' => Controls_Manager::SELECT2,
			'options' => $this->get_pages_list(),
			'default' => '',
			'label_block' => true,
			'condition' => [ 'custom_thankyou_page' => 'yes' ],
			'description' => esc_html__( 'Select the page to redirect after a successful order. You can use Elementor to design this page. Order details will be available via query parameters.', 'mn-elements' ),
		] );

		$this->add_control( 'thankyou_page_info', [
			'type' => Controls_Manager::RAW_HTML,
			'raw' => esc_html__( 'The custom Thank You page will receive the order key as a URL parameter so you can display order details. Use shortcode [mn_order_details] on the Thank You page to show order information.', 'mn-elements' ),
			'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			'condition' => [ 'custom_thankyou_page' => 'yes' ],
		] );

		$this->end_controls_section();

		// Field Width Settings
		$this->start_controls_section( 'section_field_widths', [
			'label' => esc_html__( 'Field Widths', 'mn-elements' ),
			'condition' => [ 'widget_type' => 'checkout_page' ],
		] );

		$this->add_control( 'field_widths_info', [
			'type' => Controls_Manager::RAW_HTML,
			'raw' => esc_html__( 'Configure the width of checkout form fields. Use 50% for side-by-side fields.', 'mn-elements' ),
			'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
		] );

		$this->add_responsive_control( 'first_name_width', [
			'label' => esc_html__( 'First Name Width', 'mn-elements' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ '%', 'px' ],
			'range' => [
				'%' => [ 'min' => 10, 'max' => 100 ],
				'px' => [ 'min' => 50, 'max' => 800 ],
			],
			'default' => [ 'unit' => '%', 'size' => 50 ],
			'selectors' => [
				'{{WRAPPER}} .woocommerce-billing-fields #billing_first_name_field' => 'width: {{SIZE}}{{UNIT}}; display: inline-block; vertical-align: top; padding-right: 15px; box-sizing: border-box;',
				'{{WRAPPER}} .woocommerce-shipping-fields #shipping_first_name_field' => 'width: {{SIZE}}{{UNIT}}; display: inline-block; vertical-align: top; padding-right: 15px; box-sizing: border-box;',
			],
		] );

		$this->add_responsive_control( 'last_name_width', [
			'label' => esc_html__( 'Last Name Width', 'mn-elements' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ '%', 'px' ],
			'range' => [
				'%' => [ 'min' => 10, 'max' => 100 ],
				'px' => [ 'min' => 50, 'max' => 800 ],
			],
			'default' => [ 'unit' => '%', 'size' => 50 ],
			'selectors' => [
				'{{WRAPPER}} .woocommerce-billing-fields #billing_last_name_field' => 'width: {{SIZE}}{{UNIT}}; display: inline-block; vertical-align: top; box-sizing: border-box;',
				'{{WRAPPER}} .woocommerce-shipping-fields #shipping_last_name_field' => 'width: {{SIZE}}{{UNIT}}; display: inline-block; vertical-align: top; box-sizing: border-box;',
			],
		] );

		$this->add_responsive_control( 'email_width', [
			'label' => esc_html__( 'Email Width', 'mn-elements' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ '%', 'px' ],
			'range' => [
				'%' => [ 'min' => 10, 'max' => 100 ],
				'px' => [ 'min' => 50, 'max' => 800 ],
			],
			'default' => [ 'unit' => '%', 'size' => 50 ],
			'selectors' => [
				'{{WRAPPER}} .woocommerce-billing-fields #billing_email_field' => 'width: {{SIZE}}{{UNIT}}; display: inline-block; vertical-align: top; padding-right: 15px; box-sizing: border-box;',
			],
		] );

		$this->add_responsive_control( 'phone_width', [
			'label' => esc_html__( 'Phone Width', 'mn-elements' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ '%', 'px' ],
			'range' => [
				'%' => [ 'min' => 10, 'max' => 100 ],
				'px' => [ 'min' => 50, 'max' => 800 ],
			],
			'default' => [ 'unit' => '%', 'size' => 50 ],
			'selectors' => [
				'{{WRAPPER}} .woocommerce-billing-fields #billing_phone_field' => 'width: {{SIZE}}{{UNIT}}; display: inline-block; vertical-align: top; box-sizing: border-box;',
			],
		] );

		$this->add_responsive_control( 'company_width', [
			'label' => esc_html__( 'Company Width', 'mn-elements' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ '%', 'px' ],
			'range' => [
				'%' => [ 'min' => 10, 'max' => 100 ],
				'px' => [ 'min' => 50, 'max' => 800 ],
			],
			'default' => [ 'unit' => '%', 'size' => 100 ],
			'selectors' => [
				'{{WRAPPER}} .woocommerce-billing-fields #billing_company_field' => 'width: {{SIZE}}{{UNIT}}; display: inline-block; vertical-align: top; box-sizing: border-box;',
				'{{WRAPPER}} .woocommerce-shipping-fields #shipping_company_field' => 'width: {{SIZE}}{{UNIT}}; display: inline-block; vertical-align: top; box-sizing: border-box;',
			],
		] );

		$this->add_responsive_control( 'address_1_width', [
			'label' => esc_html__( 'Address Line 1 Width', 'mn-elements' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ '%', 'px' ],
			'range' => [
				'%' => [ 'min' => 10, 'max' => 100 ],
				'px' => [ 'min' => 50, 'max' => 800 ],
			],
			'default' => [ 'unit' => '%', 'size' => 100 ],
			'selectors' => [
				'{{WRAPPER}} .woocommerce-billing-fields #billing_address_1_field' => 'width: {{SIZE}}{{UNIT}}; display: inline-block; vertical-align: top; box-sizing: border-box;',
				'{{WRAPPER}} .woocommerce-shipping-fields #shipping_address_1_field' => 'width: {{SIZE}}{{UNIT}}; display: inline-block; vertical-align: top; box-sizing: border-box;',
			],
		] );

		$this->add_responsive_control( 'address_2_width', [
			'label' => esc_html__( 'Address Line 2 Width', 'mn-elements' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ '%', 'px' ],
			'range' => [
				'%' => [ 'min' => 10, 'max' => 100 ],
				'px' => [ 'min' => 50, 'max' => 800 ],
			],
			'default' => [ 'unit' => '%', 'size' => 100 ],
			'selectors' => [
				'{{WRAPPER}} .woocommerce-billing-fields #billing_address_2_field' => 'width: {{SIZE}}{{UNIT}}; display: inline-block; vertical-align: top; box-sizing: border-box;',
				'{{WRAPPER}} .woocommerce-shipping-fields #shipping_address_2_field' => 'width: {{SIZE}}{{UNIT}}; display: inline-block; vertical-align: top; box-sizing: border-box;',
			],
		] );

		$this->add_responsive_control( 'city_width', [
			'label' => esc_html__( 'City Width', 'mn-elements' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ '%', 'px' ],
			'range' => [
				'%' => [ 'min' => 10, 'max' => 100 ],
				'px' => [ 'min' => 50, 'max' => 800 ],
			],
			'default' => [ 'unit' => '%', 'size' => 50 ],
			'selectors' => [
				'{{WRAPPER}} .woocommerce-billing-fields #billing_city_field' => 'width: {{SIZE}}{{UNIT}}; display: inline-block; vertical-align: top; padding-right: 15px; box-sizing: border-box;',
				'{{WRAPPER}} .woocommerce-shipping-fields #shipping_city_field' => 'width: {{SIZE}}{{UNIT}}; display: inline-block; vertical-align: top; padding-right: 15px; box-sizing: border-box;',
			],
		] );

		$this->add_responsive_control( 'postcode_width', [
			'label' => esc_html__( 'Postcode Width', 'mn-elements' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ '%', 'px' ],
			'range' => [
				'%' => [ 'min' => 10, 'max' => 100 ],
				'px' => [ 'min' => 50, 'max' => 800 ],
			],
			'default' => [ 'unit' => '%', 'size' => 50 ],
			'selectors' => [
				'{{WRAPPER}} .woocommerce-billing-fields #billing_postcode_field' => 'width: {{SIZE}}{{UNIT}}; display: inline-block; vertical-align: top; box-sizing: border-box;',
				'{{WRAPPER}} .woocommerce-shipping-fields #shipping_postcode_field' => 'width: {{SIZE}}{{UNIT}}; display: inline-block; vertical-align: top; box-sizing: border-box;',
			],
		] );

		$this->add_responsive_control( 'country_width', [
			'label' => esc_html__( 'Country Width', 'mn-elements' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ '%', 'px' ],
			'range' => [
				'%' => [ 'min' => 10, 'max' => 100 ],
				'px' => [ 'min' => 50, 'max' => 800 ],
			],
			'default' => [ 'unit' => '%', 'size' => 50 ],
			'selectors' => [
				'{{WRAPPER}} .woocommerce-billing-fields #billing_country_field' => 'width: {{SIZE}}{{UNIT}}; display: inline-block; vertical-align: top; padding-right: 15px; box-sizing: border-box;',
				'{{WRAPPER}} .woocommerce-shipping-fields #shipping_country_field' => 'width: {{SIZE}}{{UNIT}}; display: inline-block; vertical-align: top; padding-right: 15px; box-sizing: border-box;',
			],
		] );

		$this->add_responsive_control( 'state_width', [
			'label' => esc_html__( 'State Width', 'mn-elements' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ '%', 'px' ],
			'range' => [
				'%' => [ 'min' => 10, 'max' => 100 ],
				'px' => [ 'min' => 50, 'max' => 800 ],
			],
			'default' => [ 'unit' => '%', 'size' => 50 ],
			'selectors' => [
				'{{WRAPPER}} .woocommerce-billing-fields #billing_state_field' => 'width: {{SIZE}}{{UNIT}}; display: inline-block; vertical-align: top; box-sizing: border-box;',
				'{{WRAPPER}} .woocommerce-shipping-fields #shipping_state_field' => 'width: {{SIZE}}{{UNIT}}; display: inline-block; vertical-align: top; box-sizing: border-box;',
			],
		] );

		$this->end_controls_section();

		// Labels Section
		$this->start_controls_section( 'section_labels', [ 'label' => esc_html__( 'Labels', 'mn-elements' ) ] );

		$this->add_control( 'view_cart_text', [
			'label' => esc_html__( 'View Cart Text', 'mn-elements' ),
			'type' => Controls_Manager::TEXT,
			'default' => esc_html__( 'View Cart', 'mn-elements' ),
		] );

		$this->add_control( 'checkout_text', [
			'label' => esc_html__( 'Checkout Text', 'mn-elements' ),
			'type' => Controls_Manager::TEXT,
			'default' => esc_html__( 'Checkout', 'mn-elements' ),
		] );

		$this->add_control( 'update_cart_text', [
			'label' => esc_html__( 'Update Cart Text', 'mn-elements' ),
			'type' => Controls_Manager::TEXT,
			'default' => esc_html__( 'Update Cart', 'mn-elements' ),
		] );

		$this->add_control( 'place_order_text', [
			'label' => esc_html__( 'Place Order Text', 'mn-elements' ),
			'type' => Controls_Manager::TEXT,
			'default' => esc_html__( 'Place Order', 'mn-elements' ),
		] );

		$this->end_controls_section();
	}

	protected function register_style_controls() {
		// Icon Style
		$this->start_controls_section( 'section_icon_style', [
			'label' => esc_html__( 'Cart Icon', 'mn-elements' ),
			'tab' => Controls_Manager::TAB_STYLE,
			'condition' => [ 'widget_type' => 'mini_cart' ],
		] );

		$this->add_control( 'icon_color', [
			'label' => esc_html__( 'Icon Color', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#333333',
			'selectors' => [ '{{WRAPPER}} .mn-woocart-icon' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'icon_hover_color', [
			'label' => esc_html__( 'Icon Hover Color', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#007cba',
			'selectors' => [ '{{WRAPPER}} .mn-woocart-trigger:hover .mn-woocart-icon' => 'color: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'icon_size', [
			'label' => esc_html__( 'Icon Size', 'mn-elements' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range' => [ 'px' => [ 'min' => 12, 'max' => 60 ] ],
			'default' => [ 'unit' => 'px', 'size' => 24 ],
			'selectors' => [ '{{WRAPPER}} .mn-woocart-icon' => 'font-size: {{SIZE}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		// Count Badge Style
		$this->start_controls_section( 'section_badge_style', [
			'label' => esc_html__( 'Count Badge', 'mn-elements' ),
			'tab' => Controls_Manager::TAB_STYLE,
			'condition' => [ 'widget_type' => 'mini_cart', 'show_cart_count' => 'yes' ],
		] );

		$this->add_control( 'badge_bg_color', [
			'label' => esc_html__( 'Background Color', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#e74c3c',
			'selectors' => [ '{{WRAPPER}} .mn-woocart-count' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_control( 'badge_text_color', [
			'label' => esc_html__( 'Text Color', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#ffffff',
			'selectors' => [ '{{WRAPPER}} .mn-woocart-count' => 'color: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'badge_size', [
			'label' => esc_html__( 'Badge Size', 'mn-elements' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range' => [ 'px' => [ 'min' => 14, 'max' => 40 ] ],
			'default' => [ 'unit' => 'px', 'size' => 18 ],
			'selectors' => [ '{{WRAPPER}} .mn-woocart-count' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		// Dropdown Style
		$this->start_controls_section( 'section_dropdown_style', [
			'label' => esc_html__( 'Dropdown', 'mn-elements' ),
			'tab' => Controls_Manager::TAB_STYLE,
			'condition' => [ 'widget_type' => 'mini_cart' ],
		] );

		$this->add_control( 'dropdown_bg', [
			'label' => esc_html__( 'Background', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#ffffff',
			'selectors' => [ '{{WRAPPER}} .mn-woocart-dropdown' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [ 'name' => 'dropdown_border', 'selector' => '{{WRAPPER}} .mn-woocart-dropdown' ] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [ 'name' => 'dropdown_shadow', 'selector' => '{{WRAPPER}} .mn-woocart-dropdown' ] );

		$this->add_responsive_control( 'dropdown_width', [
			'label' => esc_html__( 'Width', 'mn-elements' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range' => [ 'px' => [ 'min' => 200, 'max' => 500 ] ],
			'default' => [ 'unit' => 'px', 'size' => 320 ],
			'selectors' => [ '{{WRAPPER}} .mn-woocart-dropdown' => 'width: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'dropdown_padding', [
			'label' => esc_html__( 'Padding', 'mn-elements' ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors' => [ '{{WRAPPER}} .mn-woocart-dropdown' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		// Product Item Style
		$this->start_controls_section( 'section_product_style', [
			'label' => esc_html__( 'Product Items', 'mn-elements' ),
			'tab' => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'product_title_color', [
			'label' => esc_html__( 'Title Color', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#333333',
			'selectors' => [ '{{WRAPPER}} .mn-woocart-product-title a' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'product_price_color', [
			'label' => esc_html__( 'Price Color', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#666666',
			'selectors' => [ '{{WRAPPER}} .mn-woocart-product-price' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'remove_btn_color', [
			'label' => esc_html__( 'Remove Button Color', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#999999',
			'selectors' => [ '{{WRAPPER}} .mn-woocart-remove' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'remove_btn_hover_color', [
			'label' => esc_html__( 'Remove Button Hover', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#e74c3c',
			'selectors' => [ '{{WRAPPER}} .mn-woocart-remove:hover' => 'color: {{VALUE}};' ],
		] );

		$this->end_controls_section();

		// Buttons Style
		$this->start_controls_section( 'section_buttons_style', [
			'label' => esc_html__( 'Buttons', 'mn-elements' ),
			'tab' => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'view_cart_bg', [
			'label' => esc_html__( 'View Cart Background', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#f5f5f5',
			'selectors' => [ '{{WRAPPER}} .mn-woocart-btn-view' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_control( 'view_cart_color', [
			'label' => esc_html__( 'View Cart Color', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#333333',
			'selectors' => [ '{{WRAPPER}} .mn-woocart-btn-view' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'checkout_bg', [
			'label' => esc_html__( 'Checkout Background', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#007cba',
			'selectors' => [ '{{WRAPPER}} .mn-woocart-btn-checkout' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_control( 'checkout_color', [
			'label' => esc_html__( 'Checkout Color', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#ffffff',
			'selectors' => [ '{{WRAPPER}} .mn-woocart-btn-checkout' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'button_border_radius', [
			'label' => esc_html__( 'Border Radius', 'mn-elements' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
			'selectors' => [ '{{WRAPPER}} .mn-woocart-btn' => 'border-radius: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'button_typography', 'selector' => '{{WRAPPER}} .mn-woocart-btn' ] );

		$this->end_controls_section();

		// Totals Style
		$this->start_controls_section( 'section_totals_style', [
			'label' => esc_html__( 'Totals', 'mn-elements' ),
			'tab' => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'totals_label_color', [
			'label' => esc_html__( 'Label Color', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#666666',
			'selectors' => [ '{{WRAPPER}} .mn-woocart-totals-label' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'totals_value_color', [
			'label' => esc_html__( 'Value Color', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#333333',
			'selectors' => [ '{{WRAPPER}} .mn-woocart-totals-value' => 'color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'totals_typography', 'selector' => '{{WRAPPER}} .mn-woocart-totals' ] );

		$this->end_controls_section();

		// Shipping Methods Style
		$this->start_controls_section( 'section_shipping_style', [
			'label' => esc_html__( 'Shipping Methods', 'mn-elements' ),
			'tab' => Controls_Manager::TAB_STYLE,
			'condition' => [ 'widget_type' => 'checkout_page', 'show_shipping_methods' => 'yes' ],
		] );

		$this->add_control( 'shipping_label_color', [
			'label' => esc_html__( 'Label Color', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#333333',
			'selectors' => [ 
				'{{WRAPPER}} #shipping_method li label' => 'color: {{VALUE}};',
				'{{WRAPPER}} .woocommerce-shipping-methods li label' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'shipping_price_color', [
			'label' => esc_html__( 'Price Color', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#666666',
			'selectors' => [ 
				'{{WRAPPER}} #shipping_method li .woocommerce-Price-amount' => 'color: {{VALUE}};',
				'{{WRAPPER}} .woocommerce-shipping-methods .woocommerce-Price-amount' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'local_pickup_bg', [
			'label' => esc_html__( 'Local Pickup Background', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'selectors' => [ 
				'{{WRAPPER}} .mn-highlight-local-pickup #shipping_method li:has(input[value*="local_pickup"])' => 'background-color: {{VALUE}};',
				'{{WRAPPER}} .mn-highlight-local-pickup .woocommerce-shipping-methods li:has(input[value*="local_pickup"])' => 'background-color: {{VALUE}};',
			],
			'condition' => [ 'highlight_local_pickup' => 'yes' ],
		] );

		$this->add_control( 'local_pickup_border_color', [
			'label' => esc_html__( 'Local Pickup Border Color', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'selectors' => [ 
				'{{WRAPPER}} .mn-highlight-local-pickup #shipping_method li:has(input[value*="local_pickup"])' => 'border-color: {{VALUE}};',
				'{{WRAPPER}} .mn-highlight-local-pickup .woocommerce-shipping-methods li:has(input[value*="local_pickup"])' => 'border-color: {{VALUE}};',
			],
			'condition' => [ 'highlight_local_pickup' => 'yes' ],
		] );

		$this->add_responsive_control( 'shipping_item_padding', [
			'label' => esc_html__( 'Item Padding', 'mn-elements' ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors' => [ 
				'{{WRAPPER}} #shipping_method li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'{{WRAPPER}} .woocommerce-shipping-methods li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'shipping_item_spacing', [
			'label' => esc_html__( 'Spacing Between Items', 'mn-elements' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range' => [ 'px' => [ 'min' => 0, 'max' => 30 ] ],
			'selectors' => [ 
				'{{WRAPPER}} #shipping_method li' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .woocommerce-shipping-methods li' => 'margin-bottom: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [ 
			'name' => 'shipping_item_border', 
			'selector' => '{{WRAPPER}} #shipping_method li, {{WRAPPER}} .woocommerce-shipping-methods li',
		] );

		$this->add_control( 'shipping_border_radius', [
			'label' => esc_html__( 'Border Radius', 'mn-elements' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range' => [ 'px' => [ 'min' => 0, 'max' => 20 ] ],
			'selectors' => [ 
				'{{WRAPPER}} #shipping_method li' => 'border-radius: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .woocommerce-shipping-methods li' => 'border-radius: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [ 
			'name' => 'shipping_typography', 
			'selector' => '{{WRAPPER}} #shipping_method li label, {{WRAPPER}} .woocommerce-shipping-methods li label',
		] );

		$this->end_controls_section();

	}

	protected function render() {
		if ( ! $this->is_woocommerce_active() ) {
			echo '<p class="mn-woocart-notice">' . esc_html__( 'WooCommerce is not active.', 'mn-elements' ) . '</p>';
			return;
		}

		$settings = $this->get_settings_for_display();

		switch ( $settings['widget_type'] ) {
			case 'mini_cart':
				$this->render_mini_cart( $settings );
				break;
			case 'cart_page':
				$this->render_cart_page( $settings );
				break;
			case 'checkout_page':
				$this->render_checkout_page( $settings );
				break;
			case 'cart_totals':
				$this->render_cart_totals( $settings );
				break;
		}
	}

	private function render_mini_cart( $settings ) {
		$cart = WC()->cart;
		$cart_count = $cart ? $cart->get_cart_contents_count() : 0;
		$cart_total = $cart ? $cart->get_cart_total() : wc_price( 0 );
		$dropdown_class = 'mn-woocart-dropdown-' . $settings['dropdown_position'];
		$trigger_class = 'mn-woocart-trigger-' . $settings['dropdown_trigger'];
		?>
		<div class="mn-woocart-wrapper mn-woocart-mini <?php echo esc_attr( $trigger_class ); ?>">
			<div class="mn-woocart-trigger">
				<span class="mn-woocart-icon">
					<?php Icons_Manager::render_icon( $settings['cart_icon'], [ 'aria-hidden' => 'true' ] ); ?>
				</span>
				<?php if ( $settings['show_cart_count'] === 'yes' ) : ?>
					<span class="mn-woocart-count"><?php echo esc_html( $cart_count ); ?></span>
				<?php endif; ?>
				<?php if ( $settings['show_cart_total'] === 'yes' ) : ?>
					<span class="mn-woocart-total"><?php echo $cart_total; ?></span>
				<?php endif; ?>
			</div>

			<div class="mn-woocart-dropdown <?php echo esc_attr( $dropdown_class ); ?>">
				<?php if ( $cart && ! $cart->is_empty() ) : ?>
					<div class="mn-woocart-products">
						<?php foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) :
							$product = $cart_item['data'];
							$product_id = $cart_item['product_id'];
							$quantity = $cart_item['quantity'];
							$product_permalink = $product->is_visible() ? $product->get_permalink( $cart_item ) : '';
						?>
							<div class="mn-woocart-product" data-key="<?php echo esc_attr( $cart_item_key ); ?>">
								<div class="mn-woocart-product-image">
									<?php echo $product->get_image( 'thumbnail' ); ?>
								</div>
								<div class="mn-woocart-product-info">
									<div class="mn-woocart-product-title">
										<?php if ( $product_permalink ) : ?>
											<a href="<?php echo esc_url( $product_permalink ); ?>"><?php echo esc_html( $product->get_name() ); ?></a>
										<?php else : ?>
											<?php echo esc_html( $product->get_name() ); ?>
										<?php endif; ?>
									</div>
									<div class="mn-woocart-product-price">
										<?php echo $quantity; ?> × <?php echo $product->get_price_html(); ?>
									</div>
								</div>
								<button type="button" class="mn-woocart-remove" data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>" aria-label="<?php esc_attr_e( 'Remove', 'mn-elements' ); ?>">×</button>
							</div>
						<?php endforeach; ?>
					</div>

					<div class="mn-woocart-subtotal">
						<span class="mn-woocart-totals-label"><?php esc_html_e( 'Subtotal:', 'mn-elements' ); ?></span>
						<span class="mn-woocart-totals-value"><?php echo $cart->get_cart_subtotal(); ?></span>
					</div>

					<div class="mn-woocart-buttons">
						<?php if ( $settings['show_view_cart_btn'] === 'yes' ) : ?>
							<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="mn-woocart-btn mn-woocart-btn-view">
								<?php echo esc_html( $settings['view_cart_text'] ); ?>
							</a>
						<?php endif; ?>
						<?php if ( $settings['show_checkout_btn'] === 'yes' ) : ?>
							<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="mn-woocart-btn mn-woocart-btn-checkout">
								<?php echo esc_html( $settings['checkout_text'] ); ?>
							</a>
						<?php endif; ?>
					</div>
				<?php else : ?>
					<div class="mn-woocart-empty">
						<p><?php echo esc_html( $settings['empty_cart_message'] ); ?></p>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	private function render_cart_page( $settings ) {
		$cart = WC()->cart;
		if ( ! $cart || $cart->is_empty() ) {
			wc_get_template( 'cart/cart-empty.php' );
			return;
		}

		$ajax_class = $settings['ajax_update'] === 'yes' ? 'mn-woocart-ajax' : '';
		?>
		<div class="mn-woocart-wrapper mn-woocart-page <?php echo esc_attr( $ajax_class ); ?>">
			<form class="mn-woocart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
				<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
				
				<table class="mn-woocart-table">
					<thead>
						<tr>
							<?php if ( $settings['show_product_image'] === 'yes' ) : ?>
								<th class="mn-woocart-col-image"><?php esc_html_e( 'Product', 'mn-elements' ); ?></th>
							<?php endif; ?>
							<th class="mn-woocart-col-name"><?php esc_html_e( 'Name', 'mn-elements' ); ?></th>
							<?php if ( $settings['show_product_price'] === 'yes' ) : ?>
								<th class="mn-woocart-col-price"><?php esc_html_e( 'Price', 'mn-elements' ); ?></th>
							<?php endif; ?>
							<?php if ( $settings['show_quantity_input'] === 'yes' ) : ?>
								<th class="mn-woocart-col-quantity"><?php esc_html_e( 'Quantity', 'mn-elements' ); ?></th>
							<?php endif; ?>
							<?php if ( $settings['show_subtotal'] === 'yes' ) : ?>
								<th class="mn-woocart-col-subtotal"><?php esc_html_e( 'Subtotal', 'mn-elements' ); ?></th>
							<?php endif; ?>
							<?php if ( $settings['show_remove_btn'] === 'yes' ) : ?>
								<th class="mn-woocart-col-remove"></th>
							<?php endif; ?>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) :
							$product = $cart_item['data'];
							$product_id = $cart_item['product_id'];
							$product_permalink = $product->is_visible() ? $product->get_permalink( $cart_item ) : '';
						?>
							<tr class="mn-woocart-row" data-key="<?php echo esc_attr( $cart_item_key ); ?>">
								<?php if ( $settings['show_product_image'] === 'yes' ) : ?>
									<td class="mn-woocart-col-image">
										<?php if ( $product_permalink ) : ?>
											<a href="<?php echo esc_url( $product_permalink ); ?>"><?php echo $product->get_image(); ?></a>
										<?php else : ?>
											<?php echo $product->get_image(); ?>
										<?php endif; ?>
									</td>
								<?php endif; ?>
								<td class="mn-woocart-col-name">
									<?php if ( $product_permalink ) : ?>
										<a href="<?php echo esc_url( $product_permalink ); ?>"><?php echo esc_html( $product->get_name() ); ?></a>
									<?php else : ?>
										<?php echo esc_html( $product->get_name() ); ?>
									<?php endif; ?>
									<?php echo wc_get_formatted_cart_item_data( $cart_item ); ?>
								</td>
								<?php if ( $settings['show_product_price'] === 'yes' ) : ?>
									<td class="mn-woocart-col-price"><?php echo $cart->get_product_price( $product ); ?></td>
								<?php endif; ?>
								<?php if ( $settings['show_quantity_input'] === 'yes' ) : ?>
									<td class="mn-woocart-col-quantity">
										<?php
										$product_quantity = woocommerce_quantity_input( [
											'input_name' => "cart[{$cart_item_key}][qty]",
											'input_value' => $cart_item['quantity'],
											'max_value' => $product->get_max_purchase_quantity(),
											'min_value' => '0',
											'product_name' => $product->get_name(),
										], $product, false );
										echo $product_quantity;
										?>
									</td>
								<?php endif; ?>
								<?php if ( $settings['show_subtotal'] === 'yes' ) : ?>
									<td class="mn-woocart-col-subtotal"><?php echo $cart->get_product_subtotal( $product, $cart_item['quantity'] ); ?></td>
								<?php endif; ?>
								<?php if ( $settings['show_remove_btn'] === 'yes' ) : ?>
									<td class="mn-woocart-col-remove">
										<a href="<?php echo esc_url( wc_get_cart_remove_url( $cart_item_key ) ); ?>" class="mn-woocart-remove" aria-label="<?php esc_attr_e( 'Remove', 'mn-elements' ); ?>">×</a>
									</td>
								<?php endif; ?>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>

				<div class="mn-woocart-actions">
					<?php if ( $settings['show_coupon'] === 'yes' && wc_coupons_enabled() ) : ?>
						<div class="mn-woocart-coupon">
							<input type="text" name="coupon_code" class="mn-woocart-coupon-input" placeholder="<?php esc_attr_e( 'Coupon code', 'mn-elements' ); ?>">
							<button type="submit" name="apply_coupon" class="mn-woocart-btn mn-woocart-btn-coupon"><?php esc_html_e( 'Apply', 'mn-elements' ); ?></button>
						</div>
					<?php endif; ?>
					<button type="submit" name="update_cart" class="mn-woocart-btn mn-woocart-btn-update"><?php echo esc_html( $settings['update_cart_text'] ); ?></button>
				</div>
			</form>

			<?php if ( $settings['show_cart_totals'] === 'yes' ) : ?>
				<?php $this->render_cart_totals( $settings ); ?>
			<?php endif; ?>
		</div>
		<?php
	}

	private function render_checkout_page( $settings ) {
		if ( ! is_checkout() && ! \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			echo '<p class="mn-woocart-notice">' . esc_html__( 'This widget should be used on the checkout page.', 'mn-elements' ) . '</p>';
			return;
		}

		// Save custom Thank You page setting to option for the redirect hook
		if ( 'yes' === $settings['custom_thankyou_page'] && ! empty( $settings['thankyou_page_id'] ) ) {
			update_option( 'mn_custom_thankyou_page_id', absint( $settings['thankyou_page_id'] ) );
		} else {
			delete_option( 'mn_custom_thankyou_page_id' );
		}

		$layout_class = 'mn-woocheckout-' . $settings['checkout_layout'];
		$highlight_class = ( 'yes' === $settings['highlight_local_pickup'] ) ? 'mn-highlight-local-pickup' : '';
		$shipping_class = ( 'yes' === $settings['show_shipping_methods'] ) ? 'mn-show-shipping' : 'mn-hide-shipping';
		?>
		<div class="mn-woocart-wrapper mn-woocheckout-page <?php echo esc_attr( $layout_class . ' ' . $highlight_class . ' ' . $shipping_class ); ?>">
			<?php
			// Add filter to ensure shipping methods are displayed
			if ( 'yes' === $settings['show_shipping_methods'] ) {
				add_filter( 'woocommerce_cart_needs_shipping', '__return_true', 999 );
			}
			
			// Use WooCommerce checkout shortcode
			echo do_shortcode( '[woocommerce_checkout]' );
			
			// Remove filter after rendering
			if ( 'yes' === $settings['show_shipping_methods'] ) {
				remove_filter( 'woocommerce_cart_needs_shipping', '__return_true', 999 );
			}
			?>
		</div>
		<?php
	}

	private function get_pages_list() {
		$pages = get_pages( [ 'sort_column' => 'post_title', 'sort_order' => 'ASC' ] );
		$options = [ '' => esc_html__( '— Select Page —', 'mn-elements' ) ];

		foreach ( $pages as $page ) {
			$options[ $page->ID ] = $page->post_title;
		}

		return $options;
	}

	private function render_cart_totals( $settings ) {
		$cart = WC()->cart;
		if ( ! $cart ) return;
		?>
		<div class="mn-woocart-totals-wrapper">
			<h3 class="mn-woocart-totals-title"><?php esc_html_e( 'Cart Totals', 'mn-elements' ); ?></h3>
			<div class="mn-woocart-totals">
				<div class="mn-woocart-totals-row">
					<span class="mn-woocart-totals-label"><?php esc_html_e( 'Subtotal', 'mn-elements' ); ?></span>
					<span class="mn-woocart-totals-value"><?php echo $cart->get_cart_subtotal(); ?></span>
				</div>

				<?php foreach ( $cart->get_coupons() as $code => $coupon ) : ?>
					<div class="mn-woocart-totals-row mn-woocart-coupon-row">
						<span class="mn-woocart-totals-label"><?php printf( esc_html__( 'Coupon: %s', 'mn-elements' ), $code ); ?></span>
						<span class="mn-woocart-totals-value">-<?php echo wc_price( $cart->get_coupon_discount_amount( $code ) ); ?></span>
					</div>
				<?php endforeach; ?>

				<?php if ( $cart->needs_shipping() && $cart->show_shipping() ) : ?>
					<div class="mn-woocart-totals-row">
						<span class="mn-woocart-totals-label"><?php esc_html_e( 'Shipping', 'mn-elements' ); ?></span>
						<span class="mn-woocart-totals-value"><?php wc_cart_totals_shipping_html(); ?></span>
					</div>
				<?php endif; ?>

				<?php foreach ( $cart->get_fees() as $fee ) : ?>
					<div class="mn-woocart-totals-row">
						<span class="mn-woocart-totals-label"><?php echo esc_html( $fee->name ); ?></span>
						<span class="mn-woocart-totals-value"><?php echo wc_price( $fee->total ); ?></span>
					</div>
				<?php endforeach; ?>

				<?php if ( wc_tax_enabled() && ! $cart->display_prices_including_tax() ) : ?>
					<?php foreach ( $cart->get_tax_totals() as $code => $tax ) : ?>
						<div class="mn-woocart-totals-row">
							<span class="mn-woocart-totals-label"><?php echo esc_html( $tax->label ); ?></span>
							<span class="mn-woocart-totals-value"><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>

				<div class="mn-woocart-totals-row mn-woocart-total-row">
					<span class="mn-woocart-totals-label"><?php esc_html_e( 'Total', 'mn-elements' ); ?></span>
					<span class="mn-woocart-totals-value"><?php echo $cart->get_total(); ?></span>
				</div>
			</div>

			<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="mn-woocart-btn mn-woocart-btn-checkout mn-woocart-btn-full">
				<?php echo esc_html( $settings['checkout_text'] ); ?>
			</a>
		</div>
		<?php
	}
}
