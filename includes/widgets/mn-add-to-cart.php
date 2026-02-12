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

/**
 * MN Add To Cart Widget
 */
class MN_Add_To_Cart extends Widget_Base {

	public function get_name() {
		return 'mn-add-to-cart';
	}

	public function get_title() {
		return esc_html__( 'MN Add To Cart', 'mn-elements' );
	}

	public function get_icon() {
		return 'eicon-product-add-to-cart';
	}

	public function get_categories() {
		return [ 'mn-elements' ];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'add to cart', 'product', 'variation', 'cart' ];
	}

	public function get_style_depends() {
		return [ 'mn-add-to-cart-style' ];
	}

	public function get_script_depends() {
		return [ 'mn-add-to-cart-script' ];
	}

	private function is_woocommerce_active() {
		return class_exists( 'WooCommerce' );
	}

	protected function register_controls() {
		$this->register_content_controls();
		$this->register_style_controls();
	}

	protected function register_content_controls() {
		// Product Settings
		$this->start_controls_section( 'section_product', [
			'label' => esc_html__( 'Product Settings', 'mn-elements' ),
		] );

		$this->add_control( 'product_id', [
			'label' => esc_html__( 'Product ID', 'mn-elements' ),
			'type' => Controls_Manager::TEXT,
			'default' => '',
			'description' => esc_html__( 'Leave empty to use current product (for single product page)', 'mn-elements' ),
		] );

		$this->end_controls_section();

		// Variation Settings
		$this->start_controls_section( 'section_variations', [
			'label' => esc_html__( 'Variations', 'mn-elements' ),
		] );

		$this->add_control( 'show_variations', [
			'label' => esc_html__( 'Show Variations', 'mn-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'default' => 'yes',
		] );

		$this->add_control( 'variation_type', [
			'label' => esc_html__( 'Variation Display Type', 'mn-elements' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'auto',
			'options' => [
				'auto' => esc_html__( 'Auto Detect', 'mn-elements' ),
				'text' => esc_html__( 'Text', 'mn-elements' ),
				'thumb' => esc_html__( 'Thumbnail', 'mn-elements' ),
				'color' => esc_html__( 'Color Swatch', 'mn-elements' ),
				'dropdown' => esc_html__( 'Dropdown', 'mn-elements' ),
			],
			'condition' => [
				'show_variations' => 'yes',
			],
		] );

		$this->add_control( 'show_variation_label', [
			'label' => esc_html__( 'Show Variation Label', 'mn-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'default' => 'yes',
			'condition' => [
				'show_variations' => 'yes',
			],
		] );

		$this->add_control( 'show_clear_button', [
			'label' => esc_html__( 'Show Clear Selection', 'mn-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'default' => 'yes',
			'condition' => [
				'show_variations' => 'yes',
			],
		] );

		$this->end_controls_section();

		// Quantity Settings
		$this->start_controls_section( 'section_quantity', [
			'label' => esc_html__( 'Quantity', 'mn-elements' ),
		] );

		$this->add_control( 'show_quantity', [
			'label' => esc_html__( 'Show Quantity Selector', 'mn-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'default' => 'yes',
		] );

		$this->add_control( 'quantity_style', [
			'label' => esc_html__( 'Quantity Style', 'mn-elements' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'default',
			'options' => [
				'default' => esc_html__( 'Default Input', 'mn-elements' ),
				'plusminus' => esc_html__( 'Plus/Minus Buttons', 'mn-elements' ),
			],
			'condition' => [
				'show_quantity' => 'yes',
			],
		] );

		$this->add_control( 'min_quantity', [
			'label' => esc_html__( 'Minimum Quantity', 'mn-elements' ),
			'type' => Controls_Manager::NUMBER,
			'default' => 1,
			'min' => 1,
			'condition' => [
				'show_quantity' => 'yes',
			],
		] );

		$this->add_control( 'max_quantity', [
			'label' => esc_html__( 'Maximum Quantity', 'mn-elements' ),
			'type' => Controls_Manager::NUMBER,
			'default' => '',
			'description' => esc_html__( 'Leave empty for no limit', 'mn-elements' ),
			'condition' => [
				'show_quantity' => 'yes',
			],
		] );

		$this->end_controls_section();

		// Button Settings
		$this->start_controls_section( 'section_button', [
			'label' => esc_html__( 'Button', 'mn-elements' ),
		] );

		$this->add_control( 'button_text', [
			'label' => esc_html__( 'Button Text', 'mn-elements' ),
			'type' => Controls_Manager::TEXT,
			'default' => esc_html__( 'Add to Cart', 'mn-elements' ),
		] );

		$this->add_control( 'show_button_icon', [
			'label' => esc_html__( 'Show Icon', 'mn-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'default' => 'yes',
		] );

		$this->add_control( 'button_icon_position', [
			'label' => esc_html__( 'Icon Position', 'mn-elements' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'left',
			'options' => [
				'left' => esc_html__( 'Left', 'mn-elements' ),
				'right' => esc_html__( 'Right', 'mn-elements' ),
			],
			'condition' => [
				'show_button_icon' => 'yes',
			],
		] );

		$this->add_control( 'ajax_add_to_cart', [
			'label' => esc_html__( 'AJAX Add to Cart', 'mn-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'default' => 'yes',
		] );

		$this->add_control( 'redirect_after_add', [
			'label' => esc_html__( 'Redirect After Add', 'mn-elements' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'none',
			'options' => [
				'none' => esc_html__( 'No Redirect', 'mn-elements' ),
				'cart' => esc_html__( 'Cart Page', 'mn-elements' ),
				'checkout' => esc_html__( 'Checkout Page', 'mn-elements' ),
			],
		] );

		$this->add_control( 'heading_buy_now', [
			'label'     => esc_html__( 'Buy Now Button', 'mn-elements' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'show_buy_now', [
			'label' => esc_html__( 'Show Buy Now Button', 'mn-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'default' => '',
			'description' => esc_html__( 'Add a Buy Now button that adds the product to cart and redirects directly to checkout.', 'mn-elements' ),
		] );

		$this->add_control( 'buy_now_text', [
			'label' => esc_html__( 'Buy Now Text', 'mn-elements' ),
			'type' => Controls_Manager::TEXT,
			'default' => esc_html__( 'Buy Now', 'mn-elements' ),
			'condition' => [
				'show_buy_now' => 'yes',
			],
		] );

		$this->add_control( 'show_buy_now_icon', [
			'label' => esc_html__( 'Show Icon', 'mn-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'default' => 'yes',
			'condition' => [
				'show_buy_now' => 'yes',
			],
		] );

		$this->add_control( 'buy_now_layout', [
			'label' => esc_html__( 'Buttons Layout', 'mn-elements' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'inline',
			'options' => [
				'inline' => esc_html__( 'Inline (Side by Side)', 'mn-elements' ),
				'stacked' => esc_html__( 'Stacked (Below)', 'mn-elements' ),
			],
			'condition' => [
				'show_buy_now' => 'yes',
			],
		] );

		$this->end_controls_section();

		// Messages Settings
		$this->start_controls_section( 'section_messages', [
			'label' => esc_html__( 'Messages', 'mn-elements' ),
		] );

		$this->add_control( 'show_stock_status', [
			'label' => esc_html__( 'Show Stock Status', 'mn-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'default' => 'yes',
		] );

		$this->add_control( 'show_price', [
			'label' => esc_html__( 'Show Price', 'mn-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'default' => 'yes',
		] );

		$this->add_control( 'success_message', [
			'label' => esc_html__( 'Success Message', 'mn-elements' ),
			'type' => Controls_Manager::TEXT,
			'default' => esc_html__( 'Product added to cart!', 'mn-elements' ),
		] );

		$this->end_controls_section();
	}

	protected function register_style_controls() {
		// Variation Style
		$this->start_controls_section( 'section_variation_style', [
			'label' => esc_html__( 'Variations', 'mn-elements' ),
			'tab' => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'variation_shape', [
			'label' => esc_html__( 'Shape', 'mn-elements' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'rounded',
			'options' => [
				'circle' => esc_html__( 'Circle', 'mn-elements' ),
				'square' => esc_html__( 'Square', 'mn-elements' ),
				'rounded' => esc_html__( 'Rounded', 'mn-elements' ),
			],
		] );

		$this->add_responsive_control( 'variation_size', [
			'label' => esc_html__( 'Size', 'mn-elements' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range' => [
				'px' => [
					'min' => 24,
					'max' => 80,
				],
			],
			'default' => [
				'unit' => 'px',
				'size' => 40,
			],
			'selectors' => [
				'{{WRAPPER}} .mn-atc-variation-item' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'variation_gap', [
			'label' => esc_html__( 'Gap', 'mn-elements' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range' => [
				'px' => [
					'min' => 0,
					'max' => 30,
				],
			],
			'default' => [
				'unit' => 'px',
				'size' => 10,
			],
			'selectors' => [
				'{{WRAPPER}} .mn-atc-variations' => 'gap: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'variation_border_color', [
			'label' => esc_html__( 'Border Color', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#ddd',
			'selectors' => [
				'{{WRAPPER}} .mn-atc-variation-item' => 'border-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'variation_active_border', [
			'label' => esc_html__( 'Active Border Color', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#007cba',
			'selectors' => [
				'{{WRAPPER}} .mn-atc-variation-item.active' => 'border-color: {{VALUE}};',
			],
		] );

		$this->end_controls_section();

		// Quantity Style
		$this->start_controls_section( 'section_quantity_style', [
			'label' => esc_html__( 'Quantity', 'mn-elements' ),
			'tab' => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name' => 'quantity_typography',
			'selector' => '{{WRAPPER}} .mn-atc-quantity input',
		] );

		$this->add_control( 'quantity_bg', [
			'label' => esc_html__( 'Background', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#ffffff',
			'selectors' => [
				'{{WRAPPER}} .mn-atc-quantity input' => 'background-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'quantity_border_color', [
			'label' => esc_html__( 'Border Color', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#ddd',
			'selectors' => [
				'{{WRAPPER}} .mn-atc-quantity input' => 'border-color: {{VALUE}};',
			],
		] );

		$this->end_controls_section();

		// Button Style
		$this->start_controls_section( 'section_button_style', [
			'label' => esc_html__( 'Button', 'mn-elements' ),
			'tab' => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name' => 'button_typography',
			'selector' => '{{WRAPPER}} .mn-atc-button',
		] );

		$this->start_controls_tabs( 'button_tabs' );

		$this->start_controls_tab( 'button_normal', [
			'label' => esc_html__( 'Normal', 'mn-elements' ),
		] );

		$this->add_control( 'button_bg', [
			'label' => esc_html__( 'Background', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#007cba',
			'selectors' => [
				'{{WRAPPER}} .mn-atc-button' => 'background-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'button_color', [
			'label' => esc_html__( 'Text Color', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#ffffff',
			'selectors' => [
				'{{WRAPPER}} .mn-atc-button' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'button_hover', [
			'label' => esc_html__( 'Hover', 'mn-elements' ),
		] );

		$this->add_control( 'button_bg_hover', [
			'label' => esc_html__( 'Background', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#005a87',
			'selectors' => [
				'{{WRAPPER}} .mn-atc-button:hover' => 'background-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'button_color_hover', [
			'label' => esc_html__( 'Text Color', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#ffffff',
			'selectors' => [
				'{{WRAPPER}} .mn-atc-button:hover' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control( 'button_padding', [
			'label' => esc_html__( 'Padding', 'mn-elements' ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors' => [
				'{{WRAPPER}} .mn-atc-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'button_border_radius', [
			'label' => esc_html__( 'Border Radius', 'mn-elements' ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors' => [
				'{{WRAPPER}} .mn-atc-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();

		// Buy Now Button Style
		$this->start_controls_section( 'section_buy_now_style', [
			'label' => esc_html__( 'Buy Now Button', 'mn-elements' ),
			'tab' => Controls_Manager::TAB_STYLE,
			'condition' => [
				'show_buy_now' => 'yes',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name' => 'buy_now_typography',
			'selector' => '{{WRAPPER}} .mn-atc-buy-now',
		] );

		$this->start_controls_tabs( 'buy_now_tabs' );

		$this->start_controls_tab( 'buy_now_normal', [
			'label' => esc_html__( 'Normal', 'mn-elements' ),
		] );

		$this->add_control( 'buy_now_bg', [
			'label' => esc_html__( 'Background', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#27ae60',
			'selectors' => [
				'{{WRAPPER}} .mn-atc-buy-now' => 'background-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'buy_now_color', [
			'label' => esc_html__( 'Text Color', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#ffffff',
			'selectors' => [
				'{{WRAPPER}} .mn-atc-buy-now' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'buy_now_hover', [
			'label' => esc_html__( 'Hover', 'mn-elements' ),
		] );

		$this->add_control( 'buy_now_bg_hover', [
			'label' => esc_html__( 'Background', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#1e8449',
			'selectors' => [
				'{{WRAPPER}} .mn-atc-buy-now:hover' => 'background-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'buy_now_color_hover', [
			'label' => esc_html__( 'Text Color', 'mn-elements' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#ffffff',
			'selectors' => [
				'{{WRAPPER}} .mn-atc-buy-now:hover' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control( 'buy_now_padding', [
			'label' => esc_html__( 'Padding', 'mn-elements' ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors' => [
				'{{WRAPPER}} .mn-atc-buy-now' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'buy_now_border_radius', [
			'label' => esc_html__( 'Border Radius', 'mn-elements' ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors' => [
				'{{WRAPPER}} .mn-atc-buy-now' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	protected function render() {
		if ( ! $this->is_woocommerce_active() ) {
			echo '<p class="mn-atc-notice">' . esc_html__( 'WooCommerce is not active.', 'mn-elements' ) . '</p>';
			return;
		}

		$settings = $this->get_settings_for_display();
		
		// Get product
		$product_id = ! empty( $settings['product_id'] ) ? intval( $settings['product_id'] ) : get_the_ID();
		$product = wc_get_product( $product_id );

		if ( ! $product ) {
			echo '<p class="mn-atc-notice">' . esc_html__( 'Product not found.', 'mn-elements' ) . '</p>';
			return;
		}

		$is_variable = $product->is_type( 'variable' );
		$is_in_stock = $product->is_in_stock();
		$shape_class = 'mn-atc-shape-' . $settings['variation_shape'];
		$ajax_class = $settings['ajax_add_to_cart'] === 'yes' ? 'mn-atc-ajax' : '';
		$show_buy_now = $settings['show_buy_now'] === 'yes';
		$buy_now_layout = $show_buy_now ? 'mn-atc-buynow-' . $settings['buy_now_layout'] : '';
		
		?>
		<div class="mn-atc-wrapper <?php echo esc_attr( $shape_class . ' ' . $ajax_class . ' ' . $buy_now_layout ); ?>" 
			data-product-id="<?php echo esc_attr( $product_id ); ?>"
			data-redirect="<?php echo esc_attr( $settings['redirect_after_add'] ); ?>">
			
			<?php if ( $settings['show_price'] === 'yes' ) : ?>
				<div class="mn-atc-price">
					<?php echo $product->get_price_html(); ?>
				</div>
			<?php endif; ?>

			<?php if ( $settings['show_stock_status'] === 'yes' ) : ?>
				<div class="mn-atc-stock <?php echo $is_in_stock ? 'in-stock' : 'out-of-stock'; ?>">
					<?php echo $is_in_stock ? esc_html__( 'In Stock', 'mn-elements' ) : esc_html__( 'Out of Stock', 'mn-elements' ); ?>
				</div>
			<?php endif; ?>

			<?php if ( $is_variable && $settings['show_variations'] === 'yes' ) : ?>
				<?php $this->render_variations( $product, $settings ); ?>
			<?php endif; ?>

			<div class="mn-atc-actions">
				<?php if ( $settings['show_quantity'] === 'yes' && $is_in_stock ) : ?>
					<?php $this->render_quantity( $product, $settings ); ?>
				<?php endif; ?>

				<?php if ( $is_in_stock ) : ?>
					<button type="button" class="mn-atc-button" <?php echo $is_variable ? 'disabled' : ''; ?>>
						<?php if ( $settings['show_button_icon'] === 'yes' && $settings['button_icon_position'] === 'left' ) : ?>
							<i class="fas fa-shopping-cart"></i>
						<?php endif; ?>
						<span><?php echo esc_html( $settings['button_text'] ); ?></span>
						<?php if ( $settings['show_button_icon'] === 'yes' && $settings['button_icon_position'] === 'right' ) : ?>
							<i class="fas fa-shopping-cart"></i>
						<?php endif; ?>
					</button>
					<?php if ( $show_buy_now ) : ?>
						<button type="button" class="mn-atc-buy-now" <?php echo $is_variable ? 'disabled' : ''; ?>>
							<?php if ( $settings['show_buy_now_icon'] === 'yes' ) : ?>
								<i class="fas fa-bolt"></i>
							<?php endif; ?>
							<span><?php echo esc_html( $settings['buy_now_text'] ); ?></span>
						</button>
					<?php endif; ?>
				<?php else : ?>
					<button type="button" class="mn-atc-button" disabled>
						<?php esc_html_e( 'Out of Stock', 'mn-elements' ); ?>
					</button>
				<?php endif; ?>
			</div>

			<div class="mn-atc-message"></div>
		</div>
		<?php
	}

	private function render_variations( $product, $settings ) {
		$attributes = $product->get_variation_attributes();
		$variation_type = $settings['variation_type'];
		?>
		<div class="mn-atc-variations-wrapper">
			<?php foreach ( $attributes as $attr_name => $options ) : 
				$attr_label = wc_attribute_label( $attr_name );
				$display_type = $this->get_variation_display_type( $attr_name, $variation_type );
				?>
				<div class="mn-atc-variation-group" data-attribute="<?php echo esc_attr( $attr_name ); ?>">
					<?php if ( $settings['show_variation_label'] === 'yes' ) : ?>
						<label class="mn-atc-variation-label">
							<?php echo esc_html( $attr_label ); ?>:
							<span class="mn-atc-selected-value"></span>
						</label>
					<?php endif; ?>
					
					<?php if ( $display_type === 'dropdown' ) : ?>
						<select class="mn-atc-variation-select" data-attribute="<?php echo esc_attr( $attr_name ); ?>">
							<option value=""><?php printf( esc_html__( 'Choose %s', 'mn-elements' ), $attr_label ); ?></option>
							<?php foreach ( $options as $option ) : ?>
								<option value="<?php echo esc_attr( $option ); ?>"><?php echo esc_html( $option ); ?></option>
							<?php endforeach; ?>
						</select>
					<?php else : ?>
						<div class="mn-atc-variations" data-type="<?php echo esc_attr( $display_type ); ?>">
							<?php foreach ( $options as $option ) : 
								$this->render_variation_item( $option, $attr_name, $display_type, $product );
							endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>

			<?php if ( $settings['show_clear_button'] === 'yes' ) : ?>
				<button type="button" class="mn-atc-clear-variations"><?php esc_html_e( 'Clear', 'mn-elements' ); ?></button>
			<?php endif; ?>
		</div>
		<?php
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

	private function render_variation_item( $option, $attr_name, $display_type, $product ) {
		$item_class = 'mn-atc-variation-item';
		
		if ( $display_type === 'color' ) {
			$color_value = $this->get_attribute_color_value( $option, $attr_name, $product );
			$item_class .= ' mn-atc-variation-color';
			?>
			<span class="<?php echo esc_attr( $item_class ); ?>" 
				data-value="<?php echo esc_attr( $option ); ?>"
				style="background-color: <?php echo esc_attr( $color_value ); ?>;"
				title="<?php echo esc_attr( $option ); ?>">
			</span>
			<?php
		} elseif ( $display_type === 'thumb' ) {
			$image_url = $this->get_variation_image( $option, $attr_name, $product );
			$item_class .= ' mn-atc-variation-thumb';
			?>
			<span class="<?php echo esc_attr( $item_class ); ?>" 
				data-value="<?php echo esc_attr( $option ); ?>"
				style="background-image: url('<?php echo esc_url( $image_url ); ?>');"
				title="<?php echo esc_attr( $option ); ?>">
			</span>
			<?php
		} else {
			$item_class .= ' mn-atc-variation-text';
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
		$available_variations = $product->get_available_variations();
		
		foreach ( $available_variations as $variation ) {
			$variation_obj = wc_get_product( $variation['variation_id'] );
			if ( ! $variation_obj ) continue;
			
			$variation_attributes = $variation_obj->get_attributes();
			
			foreach ( $variation_attributes as $key => $value ) {
				if ( strtolower( $value ) === strtolower( $option ) ) {
					$image_id = $variation_obj->get_image_id();
					if ( $image_id ) {
						return wp_get_attachment_image_url( $image_id, 'thumbnail' );
					}
				}
			}
		}
		
		return wp_get_attachment_image_url( $product->get_image_id(), 'thumbnail' );
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
		$colors = [
			'red' => '#e74c3c', 'blue' => '#3498db', 'green' => '#27ae60',
			'yellow' => '#f1c40f', 'orange' => '#e67e22', 'purple' => '#9b59b6',
			'pink' => '#e91e63', 'black' => '#000000', 'white' => '#ffffff',
			'gray' => '#95a5a6', 'grey' => '#95a5a6', 'brown' => '#795548',
			'navy' => '#34495e', 'beige' => '#f5f5dc'
		];
		$lower = strtolower( $color_name );
		return isset( $colors[ $lower ] ) ? $colors[ $lower ] : '#cccccc';
	}

	private function render_quantity( $product, $settings ) {
		$min = $settings['min_quantity'];
		$max = ! empty( $settings['max_quantity'] ) ? $settings['max_quantity'] : '';
		$style = $settings['quantity_style'];
		
		if ( $style === 'plusminus' ) {
			?>
			<div class="mn-atc-quantity mn-atc-quantity-plusminus">
				<button type="button" class="mn-atc-qty-btn mn-atc-qty-minus">−</button>
				<input type="number" class="mn-atc-qty-input" value="<?php echo esc_attr( $min ); ?>" min="<?php echo esc_attr( $min ); ?>" <?php echo $max ? 'max="' . esc_attr( $max ) . '"' : ''; ?>>
				<button type="button" class="mn-atc-qty-btn mn-atc-qty-plus">+</button>
			</div>
			<?php
		} else {
			?>
			<div class="mn-atc-quantity mn-atc-quantity-default">
				<input type="number" class="mn-atc-qty-input" value="<?php echo esc_attr( $min ); ?>" min="<?php echo esc_attr( $min ); ?>" <?php echo $max ? 'max="' . esc_attr( $max ) . '"' : ''; ?>>
			</div>
			<?php
		}
	}
}
