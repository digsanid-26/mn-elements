<?php
namespace MN_Elements\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MN_WooFilter extends Widget_Base {

	public function get_name() {
		return 'mn-woofilter';
	}

	public function get_title() {
		return esc_html__( 'MN WooFilter', 'mn-elements' );
	}

	public function get_icon() {
		return 'eicon-filter';
	}

	public function get_categories() {
		return [ 'mn-elements' ];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'filter', 'product', 'category', 'price', 'attribute', 'mn' ];
	}

	public function get_style_depends() {
		return [ 'mn-woofilter-style' ];
	}

	public function get_script_depends() {
		return [ 'mn-woofilter-script' ];
	}

	private function is_woocommerce_active() {
		return class_exists( 'WooCommerce' );
	}

	protected function register_controls() {
		$this->register_filter_controls();
		$this->register_style_controls();
	}

	protected function register_filter_controls() {
		// Filter Types Section
		$this->start_controls_section( 'section_filter_types', [ 'label' => esc_html__( 'Filter Types', 'mn-elements' ) ] );

		$this->add_control( 'filter_layout', [
			'label' => esc_html__( 'Layout', 'mn-elements' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'vertical',
			'options' => [
				'vertical' => esc_html__( 'Vertical', 'mn-elements' ),
				'horizontal' => esc_html__( 'Horizontal', 'mn-elements' ),
			],
		] );

		$this->add_control( 'show_category_filter', [
			'label' => esc_html__( 'Show Category Filter', 'mn-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'default' => 'yes',
		] );

		$this->add_control( 'category_filter_type', [
			'label' => esc_html__( 'Category Display Type', 'mn-elements' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'checkbox',
			'options' => [
				'checkbox' => esc_html__( 'Checkbox List', 'mn-elements' ),
				'dropdown' => esc_html__( 'Dropdown', 'mn-elements' ),
				'buttons' => esc_html__( 'Buttons', 'mn-elements' ),
			],
			'condition' => [ 'show_category_filter' => 'yes' ],
		] );

		$this->add_control( 'show_price_filter', [
			'label' => esc_html__( 'Show Price Filter', 'mn-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'default' => 'yes',
		] );

		$this->add_control( 'price_filter_type', [
			'label' => esc_html__( 'Price Display Type', 'mn-elements' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'range',
			'options' => [
				'range' => esc_html__( 'Range Slider', 'mn-elements' ),
				'input' => esc_html__( 'Min/Max Input', 'mn-elements' ),
				'preset' => esc_html__( 'Preset Ranges', 'mn-elements' ),
			],
			'condition' => [ 'show_price_filter' => 'yes' ],
		] );

		$this->add_control( 'show_attribute_filter', [
			'label' => esc_html__( 'Show Attribute Filters', 'mn-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'default' => 'yes',
		] );

		$this->add_control( 'attribute_filters', [
			'label' => esc_html__( 'Select Attributes', 'mn-elements' ),
			'type' => Controls_Manager::SELECT2,
			'multiple' => true,
			'options' => $this->get_product_attributes(),
			'condition' => [ 'show_attribute_filter' => 'yes' ],
		] );

		$this->add_control( 'show_rating_filter', [
			'label' => esc_html__( 'Show Rating Filter', 'mn-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'default' => '',
		] );

		$this->add_control( 'show_stock_filter', [
			'label' => esc_html__( 'Show Stock Status Filter', 'mn-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'default' => '',
		] );

		$this->add_control( 'show_sale_filter', [
			'label' => esc_html__( 'Show On Sale Filter', 'mn-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'default' => '',
		] );

		$this->add_control( 'show_search', [
			'label' => esc_html__( 'Show Search Box', 'mn-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'default' => '',
		] );

		$this->add_control( 'show_reset_button', [
			'label' => esc_html__( 'Show Reset Button', 'mn-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'default' => 'yes',
		] );

		$this->add_control( 'ajax_filter', [
			'label' => esc_html__( 'AJAX Filtering', 'mn-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'default' => 'yes',
			'description' => esc_html__( 'Filter products without page reload', 'mn-elements' ),
		] );

		$this->add_control( 'target_widget_id', [
			'label' => esc_html__( 'Target Product Widget ID', 'mn-elements' ),
			'type' => Controls_Manager::TEXT,
			'placeholder' => esc_html__( 'Leave empty for auto-detect', 'mn-elements' ),
			'description' => esc_html__( 'Enter the CSS ID of the MN WooProduct widget to filter', 'mn-elements' ),
		] );

		$this->end_controls_section();

		// Labels Section
		$this->start_controls_section( 'section_labels', [ 'label' => esc_html__( 'Labels', 'mn-elements' ) ] );

		$this->add_control( 'category_label', [
			'label' => esc_html__( 'Category Label', 'mn-elements' ),
			'type' => Controls_Manager::TEXT,
			'default' => esc_html__( 'Categories', 'mn-elements' ),
		] );

		$this->add_control( 'price_label', [
			'label' => esc_html__( 'Price Label', 'mn-elements' ),
			'type' => Controls_Manager::TEXT,
			'default' => esc_html__( 'Price', 'mn-elements' ),
		] );

		$this->add_control( 'rating_label', [
			'label' => esc_html__( 'Rating Label', 'mn-elements' ),
			'type' => Controls_Manager::TEXT,
			'default' => esc_html__( 'Rating', 'mn-elements' ),
		] );

		$this->add_control( 'stock_label', [
			'label' => esc_html__( 'Stock Label', 'mn-elements' ),
			'type' => Controls_Manager::TEXT,
			'default' => esc_html__( 'Availability', 'mn-elements' ),
		] );

		$this->add_control( 'search_placeholder', [
			'label' => esc_html__( 'Search Placeholder', 'mn-elements' ),
			'type' => Controls_Manager::TEXT,
			'default' => esc_html__( 'Search products...', 'mn-elements' ),
		] );

		$this->add_control( 'reset_button_text', [
			'label' => esc_html__( 'Reset Button Text', 'mn-elements' ),
			'type' => Controls_Manager::TEXT,
			'default' => esc_html__( 'Reset Filters', 'mn-elements' ),
		] );

		$this->add_control( 'apply_button_text', [
			'label' => esc_html__( 'Apply Button Text', 'mn-elements' ),
			'type' => Controls_Manager::TEXT,
			'default' => esc_html__( 'Apply Filters', 'mn-elements' ),
		] );

		$this->end_controls_section();

		// Mobile Sidebar Section
		$this->start_controls_section( 'section_mobile_sidebar', [ 'label' => esc_html__( 'Mobile Sidebar', 'mn-elements' ) ] );

		$this->add_control( 'mobile_filter_button_text', [
			'label' => esc_html__( 'Filter Button Text', 'mn-elements' ),
			'type' => Controls_Manager::TEXT,
			'default' => esc_html__( 'Filter', 'mn-elements' ),
		] );

		$this->add_control( 'mobile_sidebar_title', [
			'label' => esc_html__( 'Sidebar Title', 'mn-elements' ),
			'type' => Controls_Manager::TEXT,
			'default' => esc_html__( 'Filters', 'mn-elements' ),
		] );

		$this->add_control( 'mobile_sidebar_position', [
			'label' => esc_html__( 'Sidebar Position', 'mn-elements' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'left',
			'options' => [
				'left' => esc_html__( 'Slide from Left', 'mn-elements' ),
				'right' => esc_html__( 'Slide from Right', 'mn-elements' ),
			],
		] );

		$this->end_controls_section();
	}

	protected function register_style_controls() {
		// Container Style
		$this->start_controls_section( 'section_container_style', [ 'label' => esc_html__( 'Container', 'mn-elements' ), 'tab' => Controls_Manager::TAB_STYLE ] );

		$this->add_control( 'container_background', [
			'label' => esc_html__( 'Background', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#ffffff',
			'selectors' => [ '{{WRAPPER}} .mn-woofilter-wrapper' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [ 'name' => 'container_border', 'selector' => '{{WRAPPER}} .mn-woofilter-wrapper' ] );

		$this->add_control( 'container_border_radius', [
			'label' => esc_html__( 'Border Radius', 'mn-elements' ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors' => [ '{{WRAPPER}} .mn-woofilter-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'container_padding', [
			'label' => esc_html__( 'Padding', 'mn-elements' ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors' => [ '{{WRAPPER}} .mn-woofilter-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		// Filter Group Style
		$this->start_controls_section( 'section_filter_group_style', [ 'label' => esc_html__( 'Filter Groups', 'mn-elements' ), 'tab' => Controls_Manager::TAB_STYLE ] );

		$this->add_control( 'filter_title_color', [
			'label' => esc_html__( 'Title Color', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#333333',
			'selectors' => [ '{{WRAPPER}} .mn-woofilter-title' => 'color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'filter_title_typography', 'selector' => '{{WRAPPER}} .mn-woofilter-title' ] );

		$this->add_responsive_control( 'filter_group_spacing', [
			'label' => esc_html__( 'Group Spacing', 'mn-elements' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
			'default' => [ 'unit' => 'px', 'size' => 20 ],
			'selectors' => [ '{{WRAPPER}} .mn-woofilter-group' => 'margin-bottom: {{SIZE}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		// Checkbox/Radio Style
		$this->start_controls_section( 'section_checkbox_style', [ 'label' => esc_html__( 'Checkboxes & Options', 'mn-elements' ), 'tab' => Controls_Manager::TAB_STYLE ] );

		$this->add_control( 'checkbox_color', [
			'label' => esc_html__( 'Checkbox Color', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#007cba',
			'selectors' => [ '{{WRAPPER}} .mn-woofilter-checkbox:checked' => 'accent-color: {{VALUE}};' ],
		] );

		$this->add_control( 'option_text_color', [
			'label' => esc_html__( 'Option Text Color', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#666666',
			'selectors' => [ '{{WRAPPER}} .mn-woofilter-option label' => 'color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'option_typography', 'selector' => '{{WRAPPER}} .mn-woofilter-option label' ] );

		$this->end_controls_section();

		// Button Style
		$this->start_controls_section( 'section_button_style', [ 'label' => esc_html__( 'Buttons', 'mn-elements' ), 'tab' => Controls_Manager::TAB_STYLE ] );

		$this->add_control( 'apply_button_bg', [
			'label' => esc_html__( 'Apply Button Background', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#007cba',
			'selectors' => [ '{{WRAPPER}} .mn-woofilter-apply' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_control( 'apply_button_color', [
			'label' => esc_html__( 'Apply Button Color', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#ffffff',
			'selectors' => [ '{{WRAPPER}} .mn-woofilter-apply' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'reset_button_bg', [
			'label' => esc_html__( 'Reset Button Background', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#f5f5f5',
			'selectors' => [ '{{WRAPPER}} .mn-woofilter-reset' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_control( 'reset_button_color', [
			'label' => esc_html__( 'Reset Button Color', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#333333',
			'selectors' => [ '{{WRAPPER}} .mn-woofilter-reset' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'button_border_radius', [
			'label' => esc_html__( 'Border Radius', 'mn-elements' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
			'selectors' => [ '{{WRAPPER}} .mn-woofilter-btn' => 'border-radius: {{SIZE}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		// Price Slider Style
		$this->start_controls_section( 'section_price_slider_style', [ 'label' => esc_html__( 'Price Slider', 'mn-elements' ), 'tab' => Controls_Manager::TAB_STYLE ] );

		$this->add_control( 'slider_track_color', [
			'label' => esc_html__( 'Track Color', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#e0e0e0',
			'selectors' => [ '{{WRAPPER}} .mn-woofilter-price-slider-wrapper' => '--mn-slider-track-color: {{VALUE}};' ],
		] );

		$this->add_control( 'slider_range_color', [
			'label' => esc_html__( 'Range Color', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#007cba',
			'selectors' => [ '{{WRAPPER}} .mn-woofilter-price-slider-wrapper' => '--mn-slider-range-color: {{VALUE}};' ],
		] );

		$this->add_control( 'slider_handle_color', [
			'label' => esc_html__( 'Handle Color', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#007cba',
			'selectors' => [ '{{WRAPPER}} .mn-woofilter-price-slider-wrapper' => '--mn-slider-handle-color: {{VALUE}};' ],
		] );

		$this->add_control( 'slider_handle_size', [
			'label' => esc_html__( 'Handle Size', 'mn-elements' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range' => [ 'px' => [ 'min' => 12, 'max' => 36, 'step' => 1 ] ],
			'default' => [ 'size' => 18, 'unit' => 'px' ],
			'selectors' => [ '{{WRAPPER}} .mn-woofilter-price-slider-wrapper' => '--mn-slider-handle-size: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_control( 'slider_handle_border_radius', [
			'label' => esc_html__( 'Handle Border Radius', 'mn-elements' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%' ],
			'range' => [
				'px' => [ 'min' => 0, 'max' => 20, 'step' => 1 ],
				'%'  => [ 'min' => 0, 'max' => 50, 'step' => 1 ],
			],
			'default' => [ 'size' => 50, 'unit' => '%' ],
			'selectors' => [ '{{WRAPPER}} .mn-woofilter-price-slider-wrapper' => '--mn-slider-handle-radius: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_control( 'slider_track_height', [
			'label' => esc_html__( 'Track Height', 'mn-elements' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range' => [ 'px' => [ 'min' => 2, 'max' => 16, 'step' => 1 ] ],
			'default' => [ 'size' => 6, 'unit' => 'px' ],
			'selectors' => [ '{{WRAPPER}} .mn-woofilter-price-slider-wrapper' => '--mn-slider-track-height: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_control( 'slider_track_border_radius', [
			'label' => esc_html__( 'Track Border Radius', 'mn-elements' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range' => [ 'px' => [ 'min' => 0, 'max' => 20, 'step' => 1 ] ],
			'default' => [ 'size' => 3, 'unit' => 'px' ],
			'selectors' => [ '{{WRAPPER}} .mn-woofilter-price-slider-wrapper' => '--mn-slider-track-radius: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_control( 'slider_price_color', [
			'label' => esc_html__( 'Price Text Color', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#333333',
			'selectors' => [ '{{WRAPPER}} .mn-woofilter-price-display' => 'color: {{VALUE}};' ],
		] );

		$this->end_controls_section();

		// Mobile Sidebar Style
		$this->start_controls_section( 'section_mobile_sidebar_style', [ 'label' => esc_html__( 'Mobile Sidebar', 'mn-elements' ), 'tab' => Controls_Manager::TAB_STYLE ] );

		$this->add_control( 'mobile_trigger_bg', [
			'label' => esc_html__( 'Trigger Button Background', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#007cba',
			'selectors' => [ '{{WRAPPER}} .mn-woofilter-mobile-trigger' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_control( 'mobile_trigger_color', [
			'label' => esc_html__( 'Trigger Button Color', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#ffffff',
			'selectors' => [ '{{WRAPPER}} .mn-woofilter-mobile-trigger' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'mobile_trigger_border_radius', [
			'label' => esc_html__( 'Trigger Button Border Radius', 'mn-elements' ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'default' => [ 'top' => '4', 'right' => '4', 'bottom' => '4', 'left' => '4', 'unit' => 'px' ],
			'selectors' => [ '{{WRAPPER}} .mn-woofilter-mobile-trigger' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_control( 'heading_mobile_apply_btn', [
			'label' => esc_html__( 'Apply Button', 'mn-elements' ),
			'type' => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'mobile_apply_bg', [
			'label' => esc_html__( 'Background Color', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#007cba',
			'selectors' => [ '{{WRAPPER}} .mn-woofilter-sidebar-footer .mn-woofilter-apply' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_control( 'mobile_apply_color', [
			'label' => esc_html__( 'Text Color', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#ffffff',
			'selectors' => [ '{{WRAPPER}} .mn-woofilter-sidebar-footer .mn-woofilter-apply' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'heading_mobile_reset_btn', [
			'label' => esc_html__( 'Reset Button', 'mn-elements' ),
			'type' => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'mobile_reset_bg', [
			'label' => esc_html__( 'Background Color', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#f5f5f5',
			'selectors' => [ '{{WRAPPER}} .mn-woofilter-sidebar-footer .mn-woofilter-reset' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_control( 'mobile_reset_color', [
			'label' => esc_html__( 'Text Color', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#333333',
			'selectors' => [ '{{WRAPPER}} .mn-woofilter-sidebar-footer .mn-woofilter-reset' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'heading_mobile_sidebar_general', [
			'label' => esc_html__( 'Sidebar', 'mn-elements' ),
			'type' => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'mobile_sidebar_bg', [
			'label' => esc_html__( 'Sidebar Background', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#ffffff',
			'selectors' => [ '{{WRAPPER}} .mn-woofilter-sidebar' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_control( 'mobile_sidebar_header_bg', [
			'label' => esc_html__( 'Sidebar Header Background', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#f8f9fa',
			'selectors' => [ '{{WRAPPER}} .mn-woofilter-sidebar-header' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_control( 'mobile_overlay_color', [
			'label' => esc_html__( 'Overlay Color', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => 'rgba(0, 0, 0, 0.5)',
			'selectors' => [ '{{WRAPPER}} .mn-woofilter-overlay' => 'background-color: {{VALUE}};' ],
		] );

		$this->end_controls_section();
	}

	private function get_product_attributes() {
		$attributes = [];
		if ( ! $this->is_woocommerce_active() ) return $attributes;

		$attribute_taxonomies = wc_get_attribute_taxonomies();
		if ( ! empty( $attribute_taxonomies ) ) {
			foreach ( $attribute_taxonomies as $attribute ) {
				$attributes[ 'pa_' . $attribute->attribute_name ] = $attribute->attribute_label;
			}
		}
		return $attributes;
	}

	private function get_product_categories() {
		$categories = [];
		if ( ! $this->is_woocommerce_active() ) return $categories;

		$terms = get_terms( [ 'taxonomy' => 'product_cat', 'hide_empty' => true ] );
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$categories[] = [
					'id' => $term->term_id,
					'name' => $term->name,
					'slug' => $term->slug,
					'count' => $term->count,
				];
			}
		}
		return $categories;
	}

	private function get_price_range() {
		global $wpdb;
		$sql = "SELECT MIN( CAST( pm.meta_value AS DECIMAL(10,2) ) ) AS min_price,
		               MAX( CAST( pm.meta_value AS DECIMAL(10,2) ) ) AS max_price
		        FROM {$wpdb->postmeta} pm
		        INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
		        WHERE pm.meta_key = '_price'
		          AND pm.meta_value != ''
		          AND pm.meta_value > 0
		          AND p.post_status = 'publish'
		          AND p.post_type IN ('product', 'product_variation')";
		$result = $wpdb->get_row( $sql );
		$min = $result ? floor( floatval( $result->min_price ) ) : 0;
		$max = $result ? ceil( floatval( $result->max_price ) ) : 0;
		return [ 'min' => $min, 'max' => $max ];
	}

	protected function render() {
		if ( ! $this->is_woocommerce_active() ) {
			echo '<p class="mn-woofilter-notice">' . esc_html__( 'WooCommerce is not active.', 'mn-elements' ) . '</p>';
			return;
		}

		$settings = $this->get_settings_for_display();
		$layout_class = 'mn-woofilter-' . $settings['filter_layout'];
		$ajax_class = $settings['ajax_filter'] === 'yes' ? 'mn-woofilter-ajax' : '';
		$target_id = ! empty( $settings['target_widget_id'] ) ? $settings['target_widget_id'] : '';
		$sidebar_position = isset( $settings['mobile_sidebar_position'] ) && $settings['mobile_sidebar_position'] === 'right' ? 'mn-woofilter-sidebar-right' : '';
		$mobile_button_text = isset( $settings['mobile_filter_button_text'] ) ? $settings['mobile_filter_button_text'] : esc_html__( 'Filter', 'mn-elements' );
		$mobile_sidebar_title = isset( $settings['mobile_sidebar_title'] ) ? $settings['mobile_sidebar_title'] : esc_html__( 'Filters', 'mn-elements' );
		?>
		<div class="mn-woofilter-wrapper <?php echo esc_attr( $layout_class . ' ' . $ajax_class . ' ' . $sidebar_position ); ?>" data-target="<?php echo esc_attr( $target_id ); ?>">
			
			<!-- Mobile Filter Trigger Button -->
			<button type="button" class="mn-woofilter-mobile-trigger">
				<i class="fas fa-sliders-h"></i>
				<span><?php echo esc_html( $mobile_button_text ); ?></span>
				<span class="mn-woofilter-count-badge"></span>
			</button>

			<!-- Mobile Overlay -->
			<div class="mn-woofilter-overlay"></div>

			<!-- Mobile Sidebar -->
			<div class="mn-woofilter-sidebar">
				<div class="mn-woofilter-sidebar-header">
					<h3 class="mn-woofilter-sidebar-title"><?php echo esc_html( $mobile_sidebar_title ); ?></h3>
					<button type="button" class="mn-woofilter-sidebar-close">×</button>
				</div>
				<div class="mn-woofilter-sidebar-content">
					<?php $this->render_filter_form( $settings ); ?>
				</div>
				<div class="mn-woofilter-sidebar-footer">
					<button type="button" class="mn-woofilter-btn mn-woofilter-reset"><?php echo esc_html( $settings['reset_button_text'] ); ?></button>
					<button type="button" class="mn-woofilter-btn mn-woofilter-apply"><?php echo esc_html( $settings['apply_button_text'] ); ?></button>
				</div>
			</div>

			<!-- Desktop Filter Form -->
			<?php $this->render_filter_form( $settings ); ?>

		</div>
		<?php
	}

	/**
	 * Render the filter form (used for both desktop and mobile sidebar)
	 */
	private function render_filter_form( $settings ) {
		// For AJAX mode, we don't need form action as JS handles it
		// For non-AJAX, redirect to shop page
		$form_action = wc_get_page_permalink( 'shop' );
		// For AJAX, use the current page URL (where the widget is placed)
		$ajax_url = is_shop() ? wc_get_page_permalink( 'shop' ) : get_permalink();
		if ( ! $ajax_url ) {
			$ajax_url = home_url( $_SERVER['REQUEST_URI'] );
			$ajax_url = strtok( $ajax_url, '?' ); // Remove existing query string
		}
		?>
		<form class="mn-woofilter-form" method="get" action="<?php echo esc_url( $form_action ); ?>" data-ajax-url="<?php echo esc_url( $ajax_url ); ?>">
			
			<?php if ( $settings['show_search'] === 'yes' ) : ?>
				<div class="mn-woofilter-group mn-woofilter-search-group">
					<input type="text" name="s" class="mn-woofilter-search" placeholder="<?php echo esc_attr( $settings['search_placeholder'] ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>">
					<input type="hidden" name="post_type" value="product">
				</div>
			<?php endif; ?>

			<?php if ( $settings['show_category_filter'] === 'yes' ) : ?>
				<?php $this->render_category_filter( $settings ); ?>
			<?php endif; ?>

			<?php if ( $settings['show_price_filter'] === 'yes' ) : ?>
				<?php $this->render_price_filter( $settings ); ?>
			<?php endif; ?>

			<?php if ( $settings['show_attribute_filter'] === 'yes' && ! empty( $settings['attribute_filters'] ) ) : ?>
				<?php $this->render_attribute_filters( $settings ); ?>
			<?php endif; ?>

			<?php if ( $settings['show_rating_filter'] === 'yes' ) : ?>
				<?php $this->render_rating_filter( $settings ); ?>
			<?php endif; ?>

			<?php if ( $settings['show_stock_filter'] === 'yes' ) : ?>
				<?php $this->render_stock_filter( $settings ); ?>
			<?php endif; ?>

			<?php if ( $settings['show_sale_filter'] === 'yes' ) : ?>
				<?php $this->render_sale_filter( $settings ); ?>
			<?php endif; ?>

			<div class="mn-woofilter-buttons">
				<?php if ( $settings['ajax_filter'] !== 'yes' ) : ?>
					<button type="submit" class="mn-woofilter-btn mn-woofilter-apply"><?php echo esc_html( $settings['apply_button_text'] ); ?></button>
				<?php endif; ?>
				<?php if ( $settings['show_reset_button'] === 'yes' ) : ?>
					<button type="button" class="mn-woofilter-btn mn-woofilter-reset"><?php echo esc_html( $settings['reset_button_text'] ); ?></button>
				<?php endif; ?>
			</div>

		</form>
		<?php
	}

	private function render_category_filter( $settings ) {
		$categories = $this->get_product_categories();
		if ( empty( $categories ) ) return;

		$current_cat = isset( $_GET['product_cat'] ) ? sanitize_text_field( $_GET['product_cat'] ) : '';
		?>
		<div class="mn-woofilter-group mn-woofilter-category-group">
			<h4 class="mn-woofilter-title"><?php echo esc_html( $settings['category_label'] ); ?></h4>
			<div class="mn-woofilter-options mn-woofilter-category-<?php echo esc_attr( $settings['category_filter_type'] ); ?>">
				<?php if ( $settings['category_filter_type'] === 'dropdown' ) : ?>
					<select name="product_cat" class="mn-woofilter-select">
						<option value=""><?php esc_html_e( 'All Categories', 'mn-elements' ); ?></option>
						<?php foreach ( $categories as $cat ) : ?>
							<option value="<?php echo esc_attr( $cat['slug'] ); ?>" <?php selected( $current_cat, $cat['slug'] ); ?>>
								<?php echo esc_html( $cat['name'] ); ?> (<?php echo esc_html( $cat['count'] ); ?>)
							</option>
						<?php endforeach; ?>
					</select>
				<?php elseif ( $settings['category_filter_type'] === 'buttons' ) : ?>
					<?php foreach ( $categories as $cat ) : ?>
						<button type="button" class="mn-woofilter-cat-btn <?php echo $current_cat === $cat['slug'] ? 'active' : ''; ?>" data-value="<?php echo esc_attr( $cat['slug'] ); ?>">
							<?php echo esc_html( $cat['name'] ); ?>
						</button>
					<?php endforeach; ?>
					<input type="hidden" name="product_cat" value="<?php echo esc_attr( $current_cat ); ?>">
				<?php else : ?>
					<?php foreach ( $categories as $cat ) : ?>
						<div class="mn-woofilter-option">
							<label>
								<input type="checkbox" name="product_cat[]" value="<?php echo esc_attr( $cat['slug'] ); ?>" class="mn-woofilter-checkbox" <?php checked( in_array( $cat['slug'], (array) explode( ',', $current_cat ) ) ); ?>>
								<?php echo esc_html( $cat['name'] ); ?> <span class="count">(<?php echo esc_html( $cat['count'] ); ?>)</span>
							</label>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	private function render_price_filter( $settings ) {
		$price_range = $this->get_price_range();
		$min_price = isset( $_GET['min_price'] ) ? floatval( $_GET['min_price'] ) : $price_range['min'];
		$max_price = isset( $_GET['max_price'] ) ? floatval( $_GET['max_price'] ) : $price_range['max'];
		$currency_symbol = get_woocommerce_currency_symbol();
		?>
		<div class="mn-woofilter-group mn-woofilter-price-group">
			<h4 class="mn-woofilter-title"><?php echo esc_html( $settings['price_label'] ); ?></h4>
			<div class="mn-woofilter-options">
			<?php if ( $settings['price_filter_type'] === 'range' ) : ?>
				<div class="mn-woofilter-price-slider-wrapper" data-min="<?php echo esc_attr( $price_range['min'] ); ?>" data-max="<?php echo esc_attr( $price_range['max'] ); ?>" data-currency="<?php echo esc_attr( $currency_symbol ); ?>">
					<div class="mn-woofilter-price-slider">
						<div class="mn-woofilter-price-range"></div>
						<input type="range" class="mn-woofilter-price-handle mn-price-min" min="<?php echo esc_attr( $price_range['min'] ); ?>" max="<?php echo esc_attr( $price_range['max'] ); ?>" value="<?php echo esc_attr( $min_price ); ?>">
						<input type="range" class="mn-woofilter-price-handle mn-price-max" min="<?php echo esc_attr( $price_range['min'] ); ?>" max="<?php echo esc_attr( $price_range['max'] ); ?>" value="<?php echo esc_attr( $max_price ); ?>">
					</div>
					<div class="mn-woofilter-price-display">
						<span class="mn-price-min-display"><?php echo $currency_symbol . number_format( $min_price ); ?></span>
						<span class="mn-price-separator">-</span>
						<span class="mn-price-max-display"><?php echo $currency_symbol . number_format( $max_price ); ?></span>
					</div>
					<input type="hidden" name="min_price" value="<?php echo esc_attr( $min_price ); ?>">
					<input type="hidden" name="max_price" value="<?php echo esc_attr( $max_price ); ?>">
				</div>
			<?php elseif ( $settings['price_filter_type'] === 'input' ) : ?>
				<div class="mn-woofilter-price-inputs">
					<input type="number" name="min_price" class="mn-woofilter-input mn-price-input-min" placeholder="<?php esc_attr_e( 'Min', 'mn-elements' ); ?>" value="<?php echo esc_attr( $min_price != $price_range['min'] ? $min_price : '' ); ?>">
					<span class="mn-price-separator">-</span>
					<input type="number" name="max_price" class="mn-woofilter-input mn-price-input-max" placeholder="<?php esc_attr_e( 'Max', 'mn-elements' ); ?>" value="<?php echo esc_attr( $max_price != $price_range['max'] ? $max_price : '' ); ?>">
				</div>
			<?php else : ?>
				<div class="mn-woofilter-price-presets">
					<?php
					$presets = [
						[ 'min' => 0, 'max' => 50, 'label' => $currency_symbol . '0 - ' . $currency_symbol . '50' ],
						[ 'min' => 50, 'max' => 100, 'label' => $currency_symbol . '50 - ' . $currency_symbol . '100' ],
						[ 'min' => 100, 'max' => 200, 'label' => $currency_symbol . '100 - ' . $currency_symbol . '200' ],
						[ 'min' => 200, 'max' => 500, 'label' => $currency_symbol . '200 - ' . $currency_symbol . '500' ],
						[ 'min' => 500, 'max' => '', 'label' => $currency_symbol . '500+' ],
					];
					foreach ( $presets as $preset ) :
						$is_active = ( $min_price == $preset['min'] && ( $max_price == $preset['max'] || ( empty( $preset['max'] ) && $max_price == $price_range['max'] ) ) );
					?>
						<div class="mn-woofilter-option">
							<label>
								<input type="radio" name="price_preset" value="<?php echo esc_attr( $preset['min'] . '-' . $preset['max'] ); ?>" class="mn-woofilter-radio" <?php checked( $is_active ); ?>>
								<?php echo esc_html( $preset['label'] ); ?>
							</label>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
			</div><!-- .mn-woofilter-options -->
		</div>
		<?php
	}

	private function render_attribute_filters( $settings ) {
		foreach ( $settings['attribute_filters'] as $attribute ) {
			$terms = get_terms( [ 'taxonomy' => $attribute, 'hide_empty' => true ] );
			if ( empty( $terms ) || is_wp_error( $terms ) ) continue;

			$attr_label = wc_attribute_label( $attribute );
			$current_value = isset( $_GET[ 'filter_' . str_replace( 'pa_', '', $attribute ) ] ) ? sanitize_text_field( $_GET[ 'filter_' . str_replace( 'pa_', '', $attribute ) ] ) : '';
			$is_color = stripos( $attribute, 'color' ) !== false || stripos( $attribute, 'colour' ) !== false;
			?>
			<div class="mn-woofilter-group mn-woofilter-attribute-group" data-attribute="<?php echo esc_attr( $attribute ); ?>">
				<h4 class="mn-woofilter-title"><?php echo esc_html( $attr_label ); ?></h4>
				<div class="mn-woofilter-options <?php echo $is_color ? 'mn-woofilter-color-options' : ''; ?>">
					<?php foreach ( $terms as $term ) : ?>
						<div class="mn-woofilter-option <?php echo $is_color ? 'mn-woofilter-color-option' : ''; ?>">
							<label>
								<input type="checkbox" name="filter_<?php echo esc_attr( str_replace( 'pa_', '', $attribute ) ); ?>[]" value="<?php echo esc_attr( $term->slug ); ?>" class="mn-woofilter-checkbox" <?php checked( in_array( $term->slug, explode( ',', $current_value ) ) ); ?>>
								<?php if ( $is_color ) : ?>
									<span class="mn-woofilter-color-swatch" style="background-color: <?php echo esc_attr( $this->get_color_value( $term->name ) ); ?>" title="<?php echo esc_attr( $term->name ); ?>"></span>
								<?php else : ?>
									<?php echo esc_html( $term->name ); ?>
								<?php endif; ?>
							</label>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<?php
		}
	}

	private function get_color_value( $color_name ) {
		$colors = [ 'red' => '#e74c3c', 'blue' => '#3498db', 'green' => '#27ae60', 'yellow' => '#f1c40f', 'orange' => '#e67e22', 'purple' => '#9b59b6', 'pink' => '#e91e63', 'black' => '#000000', 'white' => '#ffffff', 'gray' => '#95a5a6', 'grey' => '#95a5a6', 'brown' => '#795548', 'navy' => '#34495e', 'beige' => '#f5f5dc' ];
		return isset( $colors[ strtolower( $color_name ) ] ) ? $colors[ strtolower( $color_name ) ] : '#cccccc';
	}

	private function render_rating_filter( $settings ) {
		$current_rating = isset( $_GET['rating_filter'] ) ? intval( $_GET['rating_filter'] ) : 0;
		?>
		<div class="mn-woofilter-group mn-woofilter-rating-group">
			<h4 class="mn-woofilter-title"><?php echo esc_html( $settings['rating_label'] ); ?></h4>
			<div class="mn-woofilter-options">
				<?php for ( $i = 5; $i >= 1; $i-- ) : ?>
					<div class="mn-woofilter-option mn-woofilter-rating-option">
						<label>
							<input type="radio" name="rating_filter" value="<?php echo esc_attr( $i ); ?>" class="mn-woofilter-radio" <?php checked( $current_rating, $i ); ?>>
							<span class="mn-woofilter-stars">
								<?php for ( $j = 1; $j <= 5; $j++ ) : ?>
									<span class="<?php echo $j <= $i ? 'star-filled' : 'star-empty'; ?>">★</span>
								<?php endfor; ?>
							</span>
							<?php if ( $i < 5 ) echo esc_html__( '& Up', 'mn-elements' ); ?>
						</label>
					</div>
				<?php endfor; ?>
			</div>
		</div>
		<?php
	}

	private function render_stock_filter( $settings ) {
		$in_stock = isset( $_GET['in_stock'] ) ? sanitize_text_field( $_GET['in_stock'] ) : '';
		?>
		<div class="mn-woofilter-group mn-woofilter-stock-group">
			<h4 class="mn-woofilter-title"><?php echo esc_html( $settings['stock_label'] ); ?></h4>
			<div class="mn-woofilter-options">
				<div class="mn-woofilter-option">
					<label>
						<input type="checkbox" name="in_stock" value="1" class="mn-woofilter-checkbox" <?php checked( $in_stock, '1' ); ?>>
						<?php esc_html_e( 'In Stock Only', 'mn-elements' ); ?>
					</label>
				</div>
			</div>
		</div>
		<?php
	}

	private function render_sale_filter( $settings ) {
		$on_sale = isset( $_GET['on_sale'] ) ? sanitize_text_field( $_GET['on_sale'] ) : '';
		?>
		<div class="mn-woofilter-group mn-woofilter-sale-group">
			<h4 class="mn-woofilter-title"><?php esc_html_e( 'Sale', 'mn-elements' ); ?></h4>
			<div class="mn-woofilter-options">
				<div class="mn-woofilter-option">
					<label>
						<input type="checkbox" name="on_sale" value="1" class="mn-woofilter-checkbox" <?php checked( $on_sale, '1' ); ?>>
						<?php esc_html_e( 'On Sale', 'mn-elements' ); ?>
					</label>
				</div>
			</div>
		</div>
		<?php
	}
}
