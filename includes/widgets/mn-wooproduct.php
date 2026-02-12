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

class MN_WooProduct extends Widget_Base {

	public function get_name() {
		return 'mn-wooproduct';
	}

	public function get_title() {
		return esc_html__( 'MN WooProduct', 'mn-elements' );
	}

	public function get_icon() {
		return 'eicon-products';
	}

	public function get_categories() {
		return [ 'mn-elements' ];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'product', 'shop', 'store', 'ecommerce', 'mn', 'cart', 'checkout' ];
	}

	public function get_style_depends() {
		return [ 'mn-wooproduct-style' ];
	}

	public function get_script_depends() {
		return [ 'mn-wooproduct-script' ];
	}

	private function is_woocommerce_active() {
		return class_exists( 'WooCommerce' );
	}

	protected function register_controls() {
		$this->register_query_controls();
		$this->register_layout_controls();
		$this->register_image_controls();
		$this->register_content_controls();
		$this->register_badge_controls();
		$this->register_counter_controls();
		$this->register_button_controls();
		$this->register_style_controls();
	}

	protected function register_query_controls() {
		$this->start_controls_section( 'section_query', [ 'label' => esc_html__( 'Query', 'mn-elements' ) ] );

		$this->add_control( 'query_type', [
			'label' => esc_html__( 'Query Type', 'mn-elements' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'recent',
			'options' => [
				'recent' => esc_html__( 'Recent Products', 'mn-elements' ),
				'featured' => esc_html__( 'Featured Products', 'mn-elements' ),
				'sale' => esc_html__( 'On Sale Products', 'mn-elements' ),
				'best_selling' => esc_html__( 'Best Selling', 'mn-elements' ),
				'top_rated' => esc_html__( 'Top Rated', 'mn-elements' ),
				'archive' => esc_html__( 'Archive Query', 'mn-elements' ),
				'custom' => esc_html__( 'Custom Query', 'mn-elements' ),
			],
		] );

		$this->add_control( 'products_per_page', [
			'label' => esc_html__( 'Products Per Page', 'mn-elements' ),
			'type' => Controls_Manager::NUMBER,
			'default' => 8,
			'min' => 1,
			'max' => 100,
		] );

		$this->add_control( 'product_categories', [
			'label' => esc_html__( 'Product Categories', 'mn-elements' ),
			'type' => Controls_Manager::SELECT2,
			'multiple' => true,
			'options' => $this->get_product_categories(),
			'condition' => [ 'query_type!' => 'archive' ],
		] );

		$this->add_control( 'orderby', [
			'label' => esc_html__( 'Order By', 'mn-elements' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'date',
			'options' => [
				'date' => esc_html__( 'Date', 'mn-elements' ),
				'title' => esc_html__( 'Title', 'mn-elements' ),
				'price' => esc_html__( 'Price', 'mn-elements' ),
				'popularity' => esc_html__( 'Popularity', 'mn-elements' ),
				'rating' => esc_html__( 'Rating', 'mn-elements' ),
				'rand' => esc_html__( 'Random', 'mn-elements' ),
			],
			'condition' => [ 'query_type' => 'custom' ],
		] );

		$this->add_control( 'order', [
			'label' => esc_html__( 'Order', 'mn-elements' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'DESC',
			'options' => [ 'ASC' => 'Ascending', 'DESC' => 'Descending' ],
			'condition' => [ 'query_type' => 'custom' ],
		] );

		$this->add_control( 'exclude_out_of_stock', [
			'label' => esc_html__( 'Exclude Out of Stock', 'mn-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'default' => '',
		] );

		$this->add_control( 'enable_pagination', [
			'label' => esc_html__( 'Enable Pagination', 'mn-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'default' => '',
		] );

		$this->end_controls_section();
	}

	protected function register_layout_controls() {
		$this->start_controls_section( 'section_layout', [ 'label' => esc_html__( 'Layout', 'mn-elements' ) ] );

		$this->add_control( 'layout_template', [
			'label' => esc_html__( 'Layout Template', 'mn-elements' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'template-1',
			'options' => [ 'template-1' => esc_html__( 'Template 1 - Card Style', 'mn-elements' ) ],
		] );

		$this->add_responsive_control( 'columns', [
			'label' => esc_html__( 'Columns', 'mn-elements' ),
			'type' => Controls_Manager::SELECT,
			'default' => '4',
			'tablet_default' => '3',
			'mobile_default' => '2',
			'options' => [ '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6' ],
			'selectors' => [ '{{WRAPPER}} .mn-wooproduct-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);' ],
		] );

		$this->add_responsive_control( 'column_gap', [
			'label' => esc_html__( 'Column Gap', 'mn-elements' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range' => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
			'default' => [ 'unit' => 'px', 'size' => 20 ],
			'selectors' => [ '{{WRAPPER}} .mn-wooproduct-grid' => 'gap: {{SIZE}}{{UNIT}};' ],
		] );

		$this->end_controls_section();
	}

	protected function register_image_controls() {
		$this->start_controls_section( 'section_image', [ 'label' => esc_html__( 'Product Image', 'mn-elements' ) ] );

		$this->add_control( 'show_image', [ 'label' => esc_html__( 'Show Image', 'mn-elements' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' ] );

		$this->add_control( 'image_type', [
			'label' => esc_html__( 'Image Type', 'mn-elements' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'featured',
			'options' => [ 'featured' => esc_html__( 'Featured Image Only', 'mn-elements' ), 'gallery' => esc_html__( 'Gallery with Navigation', 'mn-elements' ) ],
			'condition' => [ 'show_image' => 'yes' ],
		] );

		$this->add_responsive_control( 'image_height', [
			'label' => esc_html__( 'Image Height', 'mn-elements' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range' => [ 'px' => [ 'min' => 100, 'max' => 600 ] ],
			'default' => [ 'unit' => 'px', 'size' => 250 ],
			'selectors' => [ '{{WRAPPER}} .mn-wooproduct-image' => 'height: {{SIZE}}{{UNIT}};', '{{WRAPPER}} .mn-wooproduct-image img' => 'height: {{SIZE}}{{UNIT}};' ],
			'condition' => [ 'show_image' => 'yes' ],
		] );

		$this->end_controls_section();
	}

	protected function register_content_controls() {
		$this->start_controls_section( 'section_content', [ 'label' => esc_html__( 'Product Content', 'mn-elements' ) ] );

		$this->add_control( 'show_category', [ 'label' => esc_html__( 'Show Category', 'mn-elements' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' ] );
		$this->add_control( 'show_title', [ 'label' => esc_html__( 'Show Title', 'mn-elements' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' ] );
		$this->add_control( 'show_price', [ 'label' => esc_html__( 'Show Price', 'mn-elements' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' ] );
		$this->add_control( 'show_variations', [ 'label' => esc_html__( 'Show Variations', 'mn-elements' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' ] );
		$this->add_control( 'variations_display_limit', [
			'label' => esc_html__( 'Variations Display Limit', 'mn-elements' ),
			'type' => Controls_Manager::NUMBER,
			'default' => 4,
			'min' => 1,
			'max' => 10,
			'condition' => [ 'show_variations' => 'yes' ],
		] );

		$this->end_controls_section();
	}

	protected function register_badge_controls() {
		$this->start_controls_section( 'section_badge', [ 'label' => esc_html__( 'Badges', 'mn-elements' ) ] );

		$this->add_control( 'show_badge', [ 'label' => esc_html__( 'Show Badges', 'mn-elements' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' ] );
		$this->add_control( 'show_sale_badge', [ 'label' => esc_html__( 'Show Sale Badge', 'mn-elements' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'condition' => [ 'show_badge' => 'yes' ] ] );
		$this->add_control( 'sale_badge_type', [
			'label' => esc_html__( 'Sale Badge Type', 'mn-elements' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'percentage',
			'options' => [ 'text' => esc_html__( 'Text (Sale!)', 'mn-elements' ), 'percentage' => esc_html__( 'Percentage (-XX%)', 'mn-elements' ) ],
			'condition' => [ 'show_badge' => 'yes', 'show_sale_badge' => 'yes' ],
		] );
		$this->add_control( 'show_featured_badge', [ 'label' => esc_html__( 'Show Featured/Hot Badge', 'mn-elements' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'condition' => [ 'show_badge' => 'yes' ] ] );
		$this->add_control( 'featured_badge_text', [ 'label' => esc_html__( 'Featured Badge Text', 'mn-elements' ), 'type' => Controls_Manager::TEXT, 'default' => 'Hot', 'condition' => [ 'show_badge' => 'yes', 'show_featured_badge' => 'yes' ] ] );
		$this->add_control( 'show_out_of_stock_badge', [ 'label' => esc_html__( 'Show Out of Stock Badge', 'mn-elements' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'condition' => [ 'show_badge' => 'yes' ] ] );

		$this->end_controls_section();
	}

	protected function register_counter_controls() {
		$this->start_controls_section( 'section_counter', [ 'label' => esc_html__( 'Counter & Reviews', 'mn-elements' ) ] );

		$this->add_control( 'show_counter', [ 'label' => esc_html__( 'Show Counter Section', 'mn-elements' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' ] );
		$this->add_control( 'show_rating', [ 'label' => esc_html__( 'Show Rating Stars', 'mn-elements' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'condition' => [ 'show_counter' => 'yes' ] ] );
		$this->add_control( 'show_review_count', [ 'label' => esc_html__( 'Show Review Count', 'mn-elements' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'condition' => [ 'show_counter' => 'yes' ] ] );
		$this->add_control( 'show_sold_count', [ 'label' => esc_html__( 'Show Sold Count', 'mn-elements' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'condition' => [ 'show_counter' => 'yes' ] ] );

		$this->end_controls_section();
	}

	protected function register_button_controls() {
		$this->start_controls_section( 'section_buttons', [ 'label' => esc_html__( 'Action Buttons', 'mn-elements' ) ] );

		$this->add_control( 'show_add_to_cart', [ 'label' => esc_html__( 'Show Add to Cart', 'mn-elements' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' ] );
		$this->add_control( 'add_to_cart_text', [ 'label' => esc_html__( 'Add to Cart Text', 'mn-elements' ), 'type' => Controls_Manager::TEXT, 'default' => 'Add to Cart', 'condition' => [ 'show_add_to_cart' => 'yes' ] ] );
		$this->add_control( 'show_checkout', [ 'label' => esc_html__( 'Show Checkout Button', 'mn-elements' ), 'type' => Controls_Manager::SWITCHER, 'default' => '' ] );
		$this->add_control( 'checkout_text', [ 'label' => esc_html__( 'Checkout Text', 'mn-elements' ), 'type' => Controls_Manager::TEXT, 'default' => 'Buy Now', 'condition' => [ 'show_checkout' => 'yes' ] ] );
		$this->add_control( 'show_read_more', [ 'label' => esc_html__( 'Show Read More', 'mn-elements' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' ] );
		$this->add_control( 'read_more_text', [ 'label' => esc_html__( 'Read More Text', 'mn-elements' ), 'type' => Controls_Manager::TEXT, 'default' => 'View Details', 'condition' => [ 'show_read_more' => 'yes' ] ] );
		$this->add_control( 'ajax_add_to_cart', [ 'label' => esc_html__( 'AJAX Add to Cart', 'mn-elements' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' ] );

		$this->end_controls_section();
	}

	protected function register_style_controls() {
		// Card Style
		$this->start_controls_section( 'section_card_style', [ 'label' => esc_html__( 'Product Card', 'mn-elements' ), 'tab' => Controls_Manager::TAB_STYLE ] );
		$this->add_control( 'card_background', [ 'label' => esc_html__( 'Background', 'mn-elements' ), 'type' => Controls_Manager::COLOR, 'default' => '#ffffff', 'selectors' => [ '{{WRAPPER}} .mn-wooproduct-item' => 'background-color: {{VALUE}};' ] ] );
		$this->add_group_control( Group_Control_Border::get_type(), [ 'name' => 'card_border', 'selector' => '{{WRAPPER}} .mn-wooproduct-item' ] );
		$this->add_control( 'card_border_radius', [ 'label' => esc_html__( 'Border Radius', 'mn-elements' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%' ], 'selectors' => [ '{{WRAPPER}} .mn-wooproduct-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [ 'name' => 'card_box_shadow', 'selector' => '{{WRAPPER}} .mn-wooproduct-item' ] );
		$this->end_controls_section();

		// Title Style
		$this->start_controls_section( 'section_title_style', [ 'label' => esc_html__( 'Title', 'mn-elements' ), 'tab' => Controls_Manager::TAB_STYLE ] );
		$this->add_control( 'title_color', [ 'label' => esc_html__( 'Color', 'mn-elements' ), 'type' => Controls_Manager::COLOR, 'default' => '#333333', 'selectors' => [ '{{WRAPPER}} .mn-wooproduct-title a' => 'color: {{VALUE}};' ] ] );
		$this->add_control( 'title_hover_color', [ 'label' => esc_html__( 'Hover Color', 'mn-elements' ), 'type' => Controls_Manager::COLOR, 'default' => '#007cba', 'selectors' => [ '{{WRAPPER}} .mn-wooproduct-title a:hover' => 'color: {{VALUE}};' ] ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'title_typography', 'selector' => '{{WRAPPER}} .mn-wooproduct-title' ] );
		$this->end_controls_section();

		// Price Style
		$this->start_controls_section( 'section_price_style', [ 'label' => esc_html__( 'Price', 'mn-elements' ), 'tab' => Controls_Manager::TAB_STYLE ] );
		$this->add_control( 'price_color', [ 'label' => esc_html__( 'Regular Price', 'mn-elements' ), 'type' => Controls_Manager::COLOR, 'default' => '#333333', 'selectors' => [ '{{WRAPPER}} .mn-wooproduct-price' => 'color: {{VALUE}};' ] ] );
		$this->add_control( 'sale_price_color', [ 'label' => esc_html__( 'Sale Price', 'mn-elements' ), 'type' => Controls_Manager::COLOR, 'default' => '#e74c3c', 'selectors' => [ '{{WRAPPER}} .mn-wooproduct-price ins' => 'color: {{VALUE}};' ] ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'price_typography', 'selector' => '{{WRAPPER}} .mn-wooproduct-price' ] );
		$this->end_controls_section();

		// Badge Style
		$this->start_controls_section( 'section_badge_style', [ 'label' => esc_html__( 'Badges', 'mn-elements' ), 'tab' => Controls_Manager::TAB_STYLE ] );
		$this->add_control( 'sale_badge_bg', [ 'label' => esc_html__( 'Sale Badge BG', 'mn-elements' ), 'type' => Controls_Manager::COLOR, 'default' => '#e74c3c', 'selectors' => [ '{{WRAPPER}} .mn-wooproduct-badge-sale' => 'background-color: {{VALUE}};' ] ] );
		$this->add_control( 'featured_badge_bg', [ 'label' => esc_html__( 'Featured Badge BG', 'mn-elements' ), 'type' => Controls_Manager::COLOR, 'default' => '#f39c12', 'selectors' => [ '{{WRAPPER}} .mn-wooproduct-badge-featured' => 'background-color: {{VALUE}};' ] ] );
		$this->end_controls_section();

		// Variations Style
		$this->start_controls_section( 'section_variations_style', [ 'label' => esc_html__( 'Variations', 'mn-elements' ), 'tab' => Controls_Manager::TAB_STYLE ] );
		
		$this->add_control( 'variation_type', [
			'label' => esc_html__( 'Variation Type', 'mn-elements' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'auto',
			'options' => [
				'auto' => esc_html__( 'Auto Detect', 'mn-elements' ),
				'text' => esc_html__( 'Text', 'mn-elements' ),
				'thumb' => esc_html__( 'Thumbnail', 'mn-elements' ),
				'color' => esc_html__( 'Color Swatch', 'mn-elements' ),
			],
			'description' => esc_html__( 'Auto Detect uses attribute slug prefix (color-/warna- for color, img-/gambar- for thumbnail, size-/ukuran- for text)', 'mn-elements' ),
		] );
		
		$this->add_control( 'variation_shape', [ 'label' => esc_html__( 'Shape', 'mn-elements' ), 'type' => Controls_Manager::SELECT, 'default' => 'circle', 'options' => [ 'circle' => 'Circle', 'square' => 'Square', 'rounded' => 'Rounded' ] ] );
		$this->add_responsive_control( 'variation_size', [ 'label' => esc_html__( 'Size', 'mn-elements' ), 'type' => Controls_Manager::SLIDER, 'size_units' => [ 'px' ], 'range' => [ 'px' => [ 'min' => 16, 'max' => 50 ] ], 'default' => [ 'unit' => 'px', 'size' => 24 ], 'selectors' => [ '{{WRAPPER}} .mn-wooproduct-variation-item' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};' ] ] );
		$this->add_responsive_control( 'variation_gap', [ 'label' => esc_html__( 'Gap', 'mn-elements' ), 'type' => Controls_Manager::SLIDER, 'size_units' => [ 'px' ], 'range' => [ 'px' => [ 'min' => 0, 'max' => 20 ] ], 'default' => [ 'unit' => 'px', 'size' => 5 ], 'selectors' => [ '{{WRAPPER}} .mn-wooproduct-variations' => 'gap: {{SIZE}}{{UNIT}};' ] ] );
		$this->end_controls_section();

		// Buttons Style
		$this->start_controls_section( 'section_buttons_style', [ 'label' => esc_html__( 'Buttons', 'mn-elements' ), 'tab' => Controls_Manager::TAB_STYLE ] );
		$this->add_control( 'add_to_cart_bg', [ 'label' => esc_html__( 'Add to Cart BG', 'mn-elements' ), 'type' => Controls_Manager::COLOR, 'default' => '#007cba', 'selectors' => [ '{{WRAPPER}} .mn-wooproduct-btn-cart' => 'background-color: {{VALUE}};' ] ] );
		$this->add_control( 'add_to_cart_color', [ 'label' => esc_html__( 'Add to Cart Color', 'mn-elements' ), 'type' => Controls_Manager::COLOR, 'default' => '#ffffff', 'selectors' => [ '{{WRAPPER}} .mn-wooproduct-btn-cart' => 'color: {{VALUE}};' ] ] );
		$this->add_control( 'checkout_bg', [ 'label' => esc_html__( 'Checkout BG', 'mn-elements' ), 'type' => Controls_Manager::COLOR, 'default' => '#27ae60', 'selectors' => [ '{{WRAPPER}} .mn-wooproduct-btn-checkout' => 'background-color: {{VALUE}};' ] ] );
		$this->add_control( 'read_more_color', [ 'label' => esc_html__( 'Read More Color', 'mn-elements' ), 'type' => Controls_Manager::COLOR, 'default' => '#007cba', 'selectors' => [ '{{WRAPPER}} .mn-wooproduct-btn-readmore' => 'color: {{VALUE}};' ] ] );
		$this->add_control( 'buttons_border_radius', [ 'label' => esc_html__( 'Border Radius', 'mn-elements' ), 'type' => Controls_Manager::SLIDER, 'size_units' => [ 'px' ], 'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ], 'selectors' => [ '{{WRAPPER}} .mn-wooproduct-btn' => 'border-radius: {{SIZE}}{{UNIT}};' ] ] );
		$this->end_controls_section();

		// Counter Style
		$this->start_controls_section( 'section_counter_style', [ 'label' => esc_html__( 'Counter & Reviews', 'mn-elements' ), 'tab' => Controls_Manager::TAB_STYLE ] );
		$this->add_control( 'star_color', [ 'label' => esc_html__( 'Star Color', 'mn-elements' ), 'type' => Controls_Manager::COLOR, 'default' => '#f1c40f', 'selectors' => [ '{{WRAPPER}} .mn-wooproduct-rating .star-filled' => 'color: {{VALUE}};' ] ] );
		$this->add_control( 'counter_text_color', [ 'label' => esc_html__( 'Counter Text', 'mn-elements' ), 'type' => Controls_Manager::COLOR, 'default' => '#666666', 'selectors' => [ '{{WRAPPER}} .mn-wooproduct-counter' => 'color: {{VALUE}};' ] ] );
		$this->end_controls_section();
	}

	private function get_product_categories() {
		$categories = [];
		if ( ! $this->is_woocommerce_active() ) return $categories;
		$terms = get_terms( [ 'taxonomy' => 'product_cat', 'hide_empty' => false ] );
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$categories[ $term->term_id ] = $term->name;
			}
		}
		return $categories;
	}

	protected function render() {
		if ( ! $this->is_woocommerce_active() ) {
			echo '<p class="mn-wooproduct-notice">' . esc_html__( 'WooCommerce is not active.', 'mn-elements' ) . '</p>';
			return;
		}

		$settings = $this->get_settings_for_display();
		$products = $this->get_products( $settings );

		if ( empty( $products->posts ) ) {
			echo '<p class="mn-wooproduct-notice">' . esc_html__( 'No products found.', 'mn-elements' ) . '</p>';
			return;
		}

		$variation_shape = isset( $settings['variation_shape'] ) ? $settings['variation_shape'] : 'circle';
		?>
		<?php
		// Pass relevant settings to AJAX handler via data attribute
		$ajax_settings = array(
			'show_image'            => $settings['show_image'],
			'show_badge'            => $settings['show_badge'],
			'show_sale_badge'       => $settings['show_sale_badge'],
			'sale_badge_type'       => isset( $settings['sale_badge_type'] ) ? $settings['sale_badge_type'] : 'text',
			'show_featured_badge'   => $settings['show_featured_badge'],
			'featured_badge_text'   => isset( $settings['featured_badge_text'] ) ? $settings['featured_badge_text'] : 'Featured',
			'show_out_of_stock_badge' => $settings['show_out_of_stock_badge'],
			'show_category'         => $settings['show_category'],
			'show_title'            => $settings['show_title'],
			'show_price'            => $settings['show_price'],
			'show_variations'       => $settings['show_variations'],
			'show_counter'          => $settings['show_counter'],
			'show_rating'           => isset( $settings['show_rating'] ) ? $settings['show_rating'] : 'no',
			'show_review_count'     => isset( $settings['show_review_count'] ) ? $settings['show_review_count'] : 'no',
			'show_sold_count'       => isset( $settings['show_sold_count'] ) ? $settings['show_sold_count'] : 'no',
			'show_add_to_cart'      => $settings['show_add_to_cart'],
			'add_to_cart_text'      => isset( $settings['add_to_cart_text'] ) ? $settings['add_to_cart_text'] : 'Add to Cart',
			'ajax_add_to_cart'      => isset( $settings['ajax_add_to_cart'] ) ? $settings['ajax_add_to_cart'] : 'yes',
			'show_checkout'         => isset( $settings['show_checkout'] ) ? $settings['show_checkout'] : 'no',
			'checkout_text'         => isset( $settings['checkout_text'] ) ? $settings['checkout_text'] : 'Buy Now',
			'show_read_more'        => $settings['show_read_more'],
			'read_more_text'        => isset( $settings['read_more_text'] ) ? $settings['read_more_text'] : 'View Details',
			'image_type'            => isset( $settings['image_type'] ) ? $settings['image_type'] : 'single',
			'variation_type'        => isset( $settings['variation_type'] ) ? $settings['variation_type'] : 'text',
			'variations_display_limit' => isset( $settings['variations_display_limit'] ) ? $settings['variations_display_limit'] : 4,
		);
		?>
		<div class="mn-wooproduct-wrapper mn-wooproduct-<?php echo esc_attr( $settings['layout_template'] ); ?>" data-variation-shape="<?php echo esc_attr( $variation_shape ); ?>" data-per-page="<?php echo esc_attr( $settings['products_per_page'] ); ?>" data-widget-settings="<?php echo esc_attr( wp_json_encode( $ajax_settings ) ); ?>">
			<div class="mn-wooproduct-grid">
				<?php foreach ( $products->posts as $product_id ) { $this->render_product_item( $product_id, $settings ); } ?>
			</div>
			<?php if ( $settings['enable_pagination'] === 'yes' ) { $this->render_pagination( $products, $settings ); } ?>
		</div>
		<?php
	}

	private function get_products( $settings ) {
		$args = [ 'post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => $settings['products_per_page'], 'fields' => 'ids' ];
		$paged = max( 1, get_query_var( 'paged' ) );
		if ( is_front_page() ) $paged = max( 1, get_query_var( 'page' ) );
		$args['paged'] = $paged;

		switch ( $settings['query_type'] ) {
			case 'featured':
				$args['tax_query'][] = [ 'taxonomy' => 'product_visibility', 'field' => 'name', 'terms' => 'featured' ];
				break;
			case 'sale':
				$args['meta_query'][] = [ 'key' => '_sale_price', 'value' => '', 'compare' => '!=' ];
				break;
			case 'best_selling':
				$args['meta_key'] = 'total_sales';
				$args['orderby'] = 'meta_value_num';
				$args['order'] = 'DESC';
				break;
			case 'top_rated':
				$args['meta_key'] = '_wc_average_rating';
				$args['orderby'] = 'meta_value_num';
				$args['order'] = 'DESC';
				break;
			case 'custom':
				$args['orderby'] = $settings['orderby'];
				$args['order'] = $settings['order'];
				break;
		}

		if ( ! empty( $settings['product_categories'] ) && $settings['query_type'] !== 'archive' ) {
			$args['tax_query'][] = [ 'taxonomy' => 'product_cat', 'field' => 'term_id', 'terms' => $settings['product_categories'] ];
		}

		if ( $settings['exclude_out_of_stock'] === 'yes' ) {
			$args['meta_query'][] = [ 'key' => '_stock_status', 'value' => 'outofstock', 'compare' => '!=' ];
		}

		// MN WooFilter compatibility - Apply URL filter parameters
		$args = $this->apply_filter_params( $args );

		if ( isset( $args['tax_query'] ) && count( $args['tax_query'] ) > 1 ) $args['tax_query']['relation'] = 'AND';
		if ( isset( $args['meta_query'] ) && count( $args['meta_query'] ) > 1 ) $args['meta_query']['relation'] = 'AND';

		return new \WP_Query( $args );
	}

	/**
	 * Apply MN WooFilter URL parameters to product query
	 */
	private function apply_filter_params( $args ) {
		// Initialize arrays if not set
		if ( ! isset( $args['tax_query'] ) ) {
			$args['tax_query'] = [];
		}
		if ( ! isset( $args['meta_query'] ) ) {
			$args['meta_query'] = [];
		}

		// Category filter from URL
		if ( isset( $_GET['product_cat'] ) && ! empty( $_GET['product_cat'] ) ) {
			$cat_value = $_GET['product_cat'];
			if ( is_array( $cat_value ) ) {
				$cats = array_map( 'sanitize_text_field', $cat_value );
			} else {
				$cats = array_filter( explode( ',', sanitize_text_field( $cat_value ) ) );
			}
			if ( ! empty( $cats ) ) {
				$args['tax_query'][] = [ 'taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => $cats ];
			}
		}

		// Price filter
		if ( isset( $_GET['min_price'] ) || isset( $_GET['max_price'] ) ) {
			$min_price = isset( $_GET['min_price'] ) ? floatval( $_GET['min_price'] ) : 0;
			$max_price = isset( $_GET['max_price'] ) ? floatval( $_GET['max_price'] ) : PHP_INT_MAX;
			$args['meta_query'][] = [
				'key' => '_price',
				'value' => [ $min_price, $max_price ],
				'compare' => 'BETWEEN',
				'type' => 'DECIMAL(10,2)'
			];
		}

		// Attribute filters (filter_color, filter_size, etc.)
		foreach ( $_GET as $key => $value ) {
			if ( strpos( $key, 'filter_' ) === 0 && ! empty( $value ) ) {
				$attribute = sanitize_text_field( str_replace( 'filter_', '', $key ) );
				if ( is_array( $value ) ) {
					$terms = array_map( 'sanitize_text_field', $value );
				} else {
					$terms = array_filter( explode( ',', sanitize_text_field( $value ) ) );
				}
				if ( ! empty( $terms ) ) {
					$args['tax_query'][] = [ 'taxonomy' => 'pa_' . $attribute, 'field' => 'slug', 'terms' => $terms ];
				}
			}
		}

		// Rating filter
		if ( isset( $_GET['rating_filter'] ) && ! empty( $_GET['rating_filter'] ) ) {
			$rating = intval( $_GET['rating_filter'] );
			if ( $rating > 0 ) {
				$args['meta_query'][] = [
					'key' => '_wc_average_rating',
					'value' => $rating,
					'compare' => '>=',
					'type' => 'DECIMAL(10,2)'
				];
			}
		}

		// In stock filter
		if ( isset( $_GET['in_stock'] ) && $_GET['in_stock'] === '1' ) {
			$args['meta_query'][] = [ 'key' => '_stock_status', 'value' => 'instock', 'compare' => '=' ];
		}

		// On sale filter
		if ( isset( $_GET['on_sale'] ) && $_GET['on_sale'] === '1' ) {
			$sale_ids = wc_get_product_ids_on_sale();
			if ( ! empty( $sale_ids ) ) {
				$existing = isset( $args['post__in'] ) ? $args['post__in'] : [];
				$args['post__in'] = ! empty( $existing ) ? array_intersect( $existing, $sale_ids ) : $sale_ids;
				if ( empty( $args['post__in'] ) ) {
					$args['post__in'] = [ 0 ]; // No products match
				}
			} else {
				$args['post__in'] = [ 0 ]; // No products on sale
			}
		}

		// Search filter
		if ( isset( $_GET['s'] ) && ! empty( $_GET['s'] ) ) {
			$args['s'] = sanitize_text_field( $_GET['s'] );
		}

		return $args;
	}

	private function render_product_item( $product_id, $settings ) {
		$product = wc_get_product( $product_id );
		if ( ! $product ) return;

		$is_variable = $product->is_type( 'variable' );
		$is_on_sale = $product->is_on_sale();
		$is_featured = $product->is_featured();
		$is_in_stock = $product->is_in_stock();
		?>
		<article class="mn-wooproduct-item" data-product-id="<?php echo esc_attr( $product_id ); ?>">
			<?php if ( $settings['show_image'] === 'yes' ) $this->render_product_image( $product, $settings, $is_on_sale, $is_featured, $is_in_stock ); ?>
			<div class="mn-wooproduct-content">
				<?php if ( $settings['show_category'] === 'yes' ) $this->render_product_category( $product ); ?>
				<?php if ( $settings['show_title'] === 'yes' ) $this->render_product_title( $product ); ?>
				<?php if ( $settings['show_variations'] === 'yes' && $is_variable ) $this->render_product_variations( $product, $settings ); ?>
				<?php if ( $settings['show_price'] === 'yes' ) $this->render_product_price( $product ); ?>
				<?php if ( $settings['show_counter'] === 'yes' ) $this->render_product_counter( $product, $settings ); ?>
				<?php $this->render_product_buttons( $product, $settings, $is_in_stock ); ?>
			</div>
		</article>
		<?php
	}

	private function render_product_image( $product, $settings, $is_on_sale, $is_featured, $is_in_stock ) {
		$product_id = $product->get_id();
		$gallery_ids = $product->get_gallery_image_ids();
		$has_gallery = $settings['image_type'] === 'gallery' && ! empty( $gallery_ids );
		?>
		<div class="mn-wooproduct-image-wrapper">
			<?php if ( $settings['show_badge'] === 'yes' ) $this->render_product_badges( $product, $settings, $is_on_sale, $is_featured, $is_in_stock ); ?>
			<?php if ( $settings['show_category'] === 'yes' ) : ?>
				<div class="mn-wooproduct-category-overlay"><?php $this->render_product_category( $product ); ?></div>
			<?php endif; ?>
			<div class="mn-wooproduct-image<?php echo $has_gallery ? ' mn-wooproduct-gallery' : ''; ?>" data-product-id="<?php echo esc_attr( $product_id ); ?>">
				<?php if ( $has_gallery ) : ?>
					<div class="mn-wooproduct-gallery-slides">
						<div class="mn-wooproduct-gallery-slide active">
							<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>"><?php echo $product->get_image( 'woocommerce_thumbnail' ); ?></a>
						</div>
						<?php foreach ( $gallery_ids as $gallery_id ) : ?>
							<div class="mn-wooproduct-gallery-slide">
								<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>"><?php echo wp_get_attachment_image( $gallery_id, 'woocommerce_thumbnail' ); ?></a>
							</div>
						<?php endforeach; ?>
					</div>
					<button class="mn-wooproduct-gallery-nav mn-wooproduct-gallery-prev"><i class="fas fa-chevron-left"></i></button>
					<button class="mn-wooproduct-gallery-nav mn-wooproduct-gallery-next"><i class="fas fa-chevron-right"></i></button>
				<?php else : ?>
					<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>"><?php echo $product->get_image( 'woocommerce_thumbnail' ); ?></a>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	private function render_product_badges( $product, $settings, $is_on_sale, $is_featured, $is_in_stock ) {
		?>
		<div class="mn-wooproduct-badges">
			<?php if ( $settings['show_sale_badge'] === 'yes' && $is_on_sale ) : ?>
				<span class="mn-wooproduct-badge mn-wooproduct-badge-sale">
					<?php if ( $settings['sale_badge_type'] === 'percentage' && $product->is_type( 'simple' ) ) {
						$regular = (float) $product->get_regular_price();
						$sale = (float) $product->get_sale_price();
						if ( $regular > 0 ) echo '-' . round( ( ( $regular - $sale ) / $regular ) * 100 ) . '%';
						else echo esc_html__( 'Sale!', 'mn-elements' );
					} else { echo esc_html__( 'Sale!', 'mn-elements' ); } ?>
				</span>
			<?php endif; ?>
			<?php if ( $settings['show_featured_badge'] === 'yes' && $is_featured ) : ?>
				<span class="mn-wooproduct-badge mn-wooproduct-badge-featured"><?php echo esc_html( $settings['featured_badge_text'] ); ?></span>
			<?php endif; ?>
			<?php if ( $settings['show_out_of_stock_badge'] === 'yes' && ! $is_in_stock ) : ?>
				<span class="mn-wooproduct-badge mn-wooproduct-badge-outofstock"><?php esc_html_e( 'Out of Stock', 'mn-elements' ); ?></span>
			<?php endif; ?>
		</div>
		<?php
	}

	private function render_product_category( $product ) {
		$terms = get_the_terms( $product->get_id(), 'product_cat' );
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			$term = $terms[0];
			echo '<div class="mn-wooproduct-category"><a href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $term->name ) . '</a></div>';
		}
	}

	private function render_product_title( $product ) {
		echo '<h3 class="mn-wooproduct-title"><a href="' . esc_url( get_permalink( $product->get_id() ) ) . '">' . esc_html( $product->get_name() ) . '</a></h3>';
	}

	private function render_product_variations( $product, $settings ) {
		if ( ! $product->is_type( 'variable' ) ) return;
		$attributes = $product->get_variation_attributes();
		$limit = isset( $settings['variations_display_limit'] ) ? (int) $settings['variations_display_limit'] : 4;
		$variation_type_setting = isset( $settings['variation_type'] ) ? $settings['variation_type'] : 'auto';
		?>
		<div class="mn-wooproduct-variations-wrapper">
			<?php foreach ( $attributes as $attr_name => $options ) :
				$attr_label = wc_attribute_label( $attr_name );
				$total = count( $options );
				$display_options = array_slice( $options, 0, $limit );
				$remaining = $total - $limit;
				$display_type = $this->get_variation_display_type( $attr_name, $variation_type_setting );
				?>
				<div class="mn-wooproduct-variations" data-attribute="<?php echo esc_attr( $attr_name ); ?>">
					<?php foreach ( $display_options as $option ) : 
						$this->render_variation_item( $option, $attr_name, $display_type, $product );
					endforeach; ?>
					<?php if ( $remaining > 0 ) : ?>
						<span class="mn-wooproduct-variation-counter">+<?php echo $remaining; ?></span>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}

	private function render_variation_item( $option, $attr_name, $variation_type, $product ) {
		$item_class = 'mn-wooproduct-variation-item';
		
		if ( $variation_type === 'color' ) {
			// Color swatch mode
			$color_value = $this->get_attribute_color_value( $option, $attr_name, $product );
			$item_class .= ' mn-variation-color';
			?>
			<span class="<?php echo esc_attr( $item_class ); ?>" 
				style="background-color: <?php echo esc_attr( $color_value ); ?>;"
				data-value="<?php echo esc_attr( $option ); ?>"
				title="<?php echo esc_attr( $option ); ?>">
			</span>
			<?php
		} elseif ( $variation_type === 'thumb' ) {
			// Thumbnail mode
			$image_url = $this->get_variation_image( $option, $attr_name, $product );
			$item_class .= ' mn-variation-thumb';
			?>
			<span class="<?php echo esc_attr( $item_class ); ?>" 
				style="background-image: url('<?php echo esc_url( $image_url ); ?>');"
				data-value="<?php echo esc_attr( $option ); ?>"
				title="<?php echo esc_attr( $option ); ?>">
			</span>
			<?php
		} else {
			// Text mode (default)
			$item_class .= ' mn-variation-text';
			?>
			<span class="<?php echo esc_attr( $item_class ); ?>" 
				data-value="<?php echo esc_attr( $option ); ?>"
				title="<?php echo esc_attr( $option ); ?>">
				<?php echo esc_html( $option ); ?>
			</span>
			<?php
		}
	}

	private function get_variation_image( $option, $attr_name, $product ) {
		// Get all available variations
		$available_variations = $product->get_available_variations();
		
		// Try to find variation with this attribute value
		foreach ( $available_variations as $variation ) {
			$variation_obj = wc_get_product( $variation['variation_id'] );
			if ( ! $variation_obj ) continue;
			
			$variation_attributes = $variation_obj->get_attributes();
			$attr_key = str_replace( 'pa_', '', $attr_name );
			
			// Check if this variation has the attribute value we're looking for
			foreach ( $variation_attributes as $key => $value ) {
				if ( strtolower( $value ) === strtolower( $option ) || 
					 strtolower( str_replace( 'attribute_', '', $key ) ) === strtolower( $attr_key ) ) {
					// Get variation image
					$image_id = $variation_obj->get_image_id();
					if ( $image_id ) {
						return wp_get_attachment_image_url( $image_id, 'thumbnail' );
					}
				}
			}
		}
		
		// Fallback to product main image
		return wp_get_attachment_image_url( $product->get_image_id(), 'thumbnail' );
	}

	private function get_variation_display_type( $attr_name, $setting_type ) {
		if ( $setting_type !== 'auto' ) {
			return $setting_type;
		}

		// Extract slug from attribute name (remove pa_ prefix for taxonomy attributes)
		$slug = strtolower( $attr_name );
		if ( strpos( $slug, 'pa_' ) === 0 ) {
			$slug = substr( $slug, 3 );
		}

		// Color swatch: slug starts with color-, colour-, warna- or exact match
		$color_prefixes = [ 'color-', 'colour-', 'warna-' ];
		$color_exact    = [ 'color', 'colour', 'warna' ];
		foreach ( $color_prefixes as $prefix ) {
			if ( strpos( $slug, $prefix ) === 0 ) {
				return 'color';
			}
		}
		if ( in_array( $slug, $color_exact, true ) ) {
			return 'color';
		}

		// Image/Thumbnail: slug starts with img-, image-, gambar- or exact match
		$image_prefixes = [ 'img-', 'image-', 'gambar-' ];
		$image_exact    = [ 'img', 'image', 'gambar' ];
		foreach ( $image_prefixes as $prefix ) {
			if ( strpos( $slug, $prefix ) === 0 ) {
				return 'thumb';
			}
		}
		if ( in_array( $slug, $image_exact, true ) ) {
			return 'thumb';
		}

		// Text/Label: slug starts with size-, ukuran- or exact match
		$text_prefixes = [ 'size-', 'ukuran-' ];
		$text_exact    = [ 'size', 'ukuran' ];
		foreach ( $text_prefixes as $prefix ) {
			if ( strpos( $slug, $prefix ) === 0 ) {
				return 'text';
			}
		}
		if ( in_array( $slug, $text_exact, true ) ) {
			return 'text';
		}

		// Fallback: check attribute label for non-taxonomy / custom attributes
		$label_lower = strtolower( wc_attribute_label( $attr_name ) );

		if ( strpos( $label_lower, 'color' ) !== false || strpos( $label_lower, 'colour' ) !== false || strpos( $label_lower, 'warna' ) !== false ) {
			return 'color';
		}
		if ( strpos( $label_lower, 'image' ) !== false || strpos( $label_lower, 'gambar' ) !== false ) {
			return 'thumb';
		}
		if ( strpos( $label_lower, 'size' ) !== false || strpos( $label_lower, 'ukuran' ) !== false ) {
			return 'text';
		}

		return 'text';
	}

	private function get_attribute_color_value( $option, $attr_name, $product = null ) {
		$color_meta_keys = [
			'atribut_warna',
			'product_attribute_color',
			'color',
			'colour',
			'warna',
			'_color',
			'attribute_color',
		];

		// 1. Check term meta (taxonomy attribute terms)
		$attr_key = str_replace( 'pa_', '', $attr_name );
		$term = get_term_by( 'slug', sanitize_title( $option ), 'pa_' . $attr_key );
		
		if ( $term ) {
			foreach ( $color_meta_keys as $meta_key ) {
				$color = get_term_meta( $term->term_id, $meta_key, true );
				if ( ! empty( $color ) ) {
					return $color;
				}
			}
		}

		// 2. Check post meta on product_variation (JetEngine fields on variation level)
		if ( $product && $product->is_type( 'variable' ) ) {
			$available_variations = $product->get_available_variations();
			foreach ( $available_variations as $variation ) {
				$var_attributes = $variation['attributes'];
				$attr_key_full = 'attribute_pa_' . $attr_key;
				$attr_key_raw  = 'attribute_' . $attr_name;

				$var_val = '';
				if ( isset( $var_attributes[ $attr_key_full ] ) ) {
					$var_val = $var_attributes[ $attr_key_full ];
				} elseif ( isset( $var_attributes[ $attr_key_raw ] ) ) {
					$var_val = $var_attributes[ $attr_key_raw ];
				}

				if ( strtolower( $var_val ) !== strtolower( $option ) ) {
					continue;
				}

				$variation_id = $variation['variation_id'];
				foreach ( $color_meta_keys as $meta_key ) {
					$color = get_post_meta( $variation_id, $meta_key, true );
					if ( ! empty( $color ) ) {
						return $color;
					}
				}
			}
		}
		
		return $this->get_color_value( $option );
	}

	private function get_color_value( $color_name ) {
		$colors = [ 'red' => '#e74c3c', 'blue' => '#3498db', 'green' => '#27ae60', 'yellow' => '#f1c40f', 'orange' => '#e67e22', 'purple' => '#9b59b6', 'pink' => '#e91e63', 'black' => '#000000', 'white' => '#ffffff', 'gray' => '#95a5a6', 'grey' => '#95a5a6', 'brown' => '#795548', 'navy' => '#34495e', 'beige' => '#f5f5dc' ];
		$lower = strtolower( $color_name );
		return isset( $colors[ $lower ] ) ? $colors[ $lower ] : '#cccccc';
	}

	private function render_product_price( $product ) {
		echo '<div class="mn-wooproduct-price">' . $product->get_price_html() . '</div>';
	}

	private function render_product_counter( $product, $settings ) {
		$rating = $product->get_average_rating();
		$review_count = $product->get_review_count();
		$total_sales = $product->get_total_sales();
		?>
		<div class="mn-wooproduct-counter">
			<?php if ( $settings['show_rating'] === 'yes' ) : ?>
				<div class="mn-wooproduct-rating">
					<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
						<span class="<?php echo $i <= round( $rating ) ? 'star-filled' : 'star-empty'; ?>">★</span>
					<?php endfor; ?>
				</div>
			<?php endif; ?>
			<?php if ( $settings['show_review_count'] === 'yes' ) : ?>
				<span class="mn-wooproduct-review-count">(<?php echo esc_html( $review_count ); ?>)</span>
			<?php endif; ?>
			<?php if ( $settings['show_sold_count'] === 'yes' && $total_sales > 0 ) : ?>
				<span class="mn-wooproduct-sold-count"><i class="fas fa-shopping-bag"></i> <?php printf( esc_html__( '%s sold', 'mn-elements' ), $total_sales ); ?></span>
			<?php endif; ?>
		</div>
		<?php
	}

	private function render_product_buttons( $product, $settings, $is_in_stock ) {
		$product_id = $product->get_id();
		$ajax_class = $settings['ajax_add_to_cart'] === 'yes' ? 'mn-ajax-add-to-cart' : '';
		?>
		<div class="mn-wooproduct-buttons">
			<div class="mn-wooproduct-buttons-left">
				<?php if ( $settings['show_add_to_cart'] === 'yes' && $is_in_stock ) :
						$is_variable = $product->is_type( 'variable' );
						$btn_text = $is_variable ? esc_html__( 'Pilih Opsi', 'mn-elements' ) : esc_html( $settings['add_to_cart_text'] );
						$btn_url  = $is_variable ? get_permalink( $product_id ) : $product->add_to_cart_url();
						$btn_ajax = $is_variable ? '' : $ajax_class;
					?>
					<a href="<?php echo esc_url( $btn_url ); ?>" class="mn-wooproduct-btn mn-wooproduct-btn-cart <?php echo esc_attr( $btn_ajax ); ?>" data-product-id="<?php echo esc_attr( $product_id ); ?>" data-quantity="1">
						<?php echo $btn_text; ?>
					</a>
				<?php endif; ?>
				<?php if ( $settings['show_checkout'] === 'yes' && $is_in_stock ) : ?>
					<a href="<?php echo esc_url( wc_get_checkout_url() . '?add-to-cart=' . $product_id ); ?>" class="mn-wooproduct-btn mn-wooproduct-btn-checkout">
						<?php echo esc_html( $settings['checkout_text'] ); ?>
					</a>
				<?php endif; ?>
			</div>
			<?php if ( $settings['show_read_more'] === 'yes' ) : ?>
				<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>" class="mn-wooproduct-btn mn-wooproduct-btn-readmore">
					<?php echo esc_html( $settings['read_more_text'] ); ?>
				</a>
			<?php endif; ?>
		</div>
		<?php
	}

	private function render_pagination( $products, $settings ) {
		if ( $products->max_num_pages <= 1 ) return;
		$paged = max( 1, get_query_var( 'paged' ) );
		if ( is_front_page() ) $paged = max( 1, get_query_var( 'page' ) );
		?>
		<div class="mn-wooproduct-pagination">
			<?php
			echo paginate_links( [
				'total' => $products->max_num_pages,
				'current' => $paged,
				'prev_text' => '&laquo;',
				'next_text' => '&raquo;',
			] );
			?>
		</div>
		<?php
	}
}
