<?php
namespace MN_Elements\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * MN Social Reviews Widget
 *
 * Display social media and marketplace reviews with static and dynamic options
 *
 * @since 1.6.3
 */
class MN_Social_Reviews extends Widget_Base {

	/**
	 * Get widget name.
	 */
	public function get_name() {
		return 'mn-social-reviews';
	}

	/**
	 * Get widget title.
	 */
	public function get_title() {
		return esc_html__( 'MN Social Reviews', 'mn-elements' );
	}

	/**
	 * Get widget icon.
	 */
	public function get_icon() {
		return 'eicon-review';
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
		return [ 'social', 'review', 'rating', 'testimonial', 'google', 'facebook', 'tripadvisor', 'marketplace', 'mn' ];
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
		// Review Source Section
		$this->start_controls_section(
			'section_review_source',
			[
				'label' => esc_html__( 'Review Source', 'mn-elements' ),
			]
		);

		$this->add_control(
			'source_type',
			[
				'label' => esc_html__( 'Source Type', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'static',
				'options' => [
					'static' => esc_html__( 'Static (Manual)', 'mn-elements' ),
					'dynamic' => esc_html__( 'Dynamic (Embed Code)', 'mn-elements' ),
				],
			]
		);

		$this->end_controls_section();

		// Static Reviews Section
		$this->start_controls_section(
			'section_static_reviews',
			[
				'label' => esc_html__( 'Static Reviews', 'mn-elements' ),
				'condition' => [
					'source_type' => 'static',
				],
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'platform',
			[
				'label' => esc_html__( 'Platform', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'google',
				'options' => [
					'google' => esc_html__( 'Google', 'mn-elements' ),
					'facebook' => esc_html__( 'Facebook', 'mn-elements' ),
					'tripadvisor' => esc_html__( 'TripAdvisor', 'mn-elements' ),
					'airbnb' => esc_html__( 'Airbnb', 'mn-elements' ),
					'amazon' => esc_html__( 'Amazon', 'mn-elements' ),
					'yelp' => esc_html__( 'Yelp', 'mn-elements' ),
					'trustpilot' => esc_html__( 'Trustpilot', 'mn-elements' ),
					'booking' => esc_html__( 'Booking.com', 'mn-elements' ),
					'agoda' => esc_html__( 'Agoda', 'mn-elements' ),
					'tokopedia' => esc_html__( 'Tokopedia', 'mn-elements' ),
					'shopee' => esc_html__( 'Shopee', 'mn-elements' ),
					'bukalapak' => esc_html__( 'Bukalapak', 'mn-elements' ),
					'lazada' => esc_html__( 'Lazada', 'mn-elements' ),
					'custom' => esc_html__( 'Custom', 'mn-elements' ),
				],
			]
		);

		$repeater->add_control(
			'custom_logo',
			[
				'label' => esc_html__( 'Custom Logo', 'mn-elements' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'platform' => 'custom',
				],
			]
		);

		$repeater->add_control(
			'custom_name',
			[
				'label' => esc_html__( 'Custom Platform Name', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Platform Name', 'mn-elements' ),
				'condition' => [
					'platform' => 'custom',
				],
			]
		);

		$repeater->add_control(
			'rating_number',
			[
				'label' => esc_html__( 'Rating Number', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 4.5,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
			]
		);

		$repeater->add_control(
			'review_count',
			[
				'label' => esc_html__( 'Review Count', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 150,
				'min' => 0,
			]
		);

		$repeater->add_control(
			'button_text',
			[
				'label' => esc_html__( 'Button Text', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Review us on', 'mn-elements' ),
				'placeholder' => esc_html__( 'Review us on', 'mn-elements' ),
			]
		);

		$repeater->add_control(
			'review_url',
			[
				'label' => esc_html__( 'Review URL', 'mn-elements' ),
				'type' => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-review-link.com', 'mn-elements' ),
				'default' => [
					'url' => '#',
					'is_external' => true,
				],
			]
		);

		$this->add_control(
			'reviews_list',
			[
				'label' => esc_html__( 'Reviews', 'mn-elements' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'platform' => 'google',
						'rating_number' => 4.8,
						'review_count' => 250,
						'button_text' => esc_html__( 'Review us on', 'mn-elements' ),
					],
					[
						'platform' => 'facebook',
						'rating_number' => 4.6,
						'review_count' => 180,
						'button_text' => esc_html__( 'Review us on', 'mn-elements' ),
					],
					[
						'platform' => 'tripadvisor',
						'rating_number' => 4.7,
						'review_count' => 320,
						'button_text' => esc_html__( 'Review us on', 'mn-elements' ),
					],
				],
				'title_field' => '{{{ platform.charAt(0).toUpperCase() + platform.slice(1) }}} - {{{ rating_number }}} ★',
			]
		);

		$this->end_controls_section();

		// Dynamic Reviews Section
		$this->start_controls_section(
			'section_dynamic_reviews',
			[
				'label' => esc_html__( 'Dynamic Reviews', 'mn-elements' ),
				'condition' => [
					'source_type' => 'dynamic',
				],
			]
		);

		$this->add_control(
			'dynamic_platform',
			[
				'label' => esc_html__( 'Platform', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'google',
				'options' => [
					'google' => esc_html__( 'Google Reviews', 'mn-elements' ),
					'facebook' => esc_html__( 'Facebook Reviews', 'mn-elements' ),
					'tripadvisor' => esc_html__( 'TripAdvisor', 'mn-elements' ),
					'trustpilot' => esc_html__( 'Trustpilot', 'mn-elements' ),
					'custom' => esc_html__( 'Custom Embed Code', 'mn-elements' ),
				],
			]
		);

		$this->add_control(
			'embed_code',
			[
				'label' => esc_html__( 'Embed Code', 'mn-elements' ),
				'type' => Controls_Manager::TEXTAREA,
				'rows' => 10,
				'placeholder' => esc_html__( 'Paste your embed code here...', 'mn-elements' ),
				'description' => esc_html__( 'Paste the embed code provided by the review platform (iframe, script, etc.)', 'mn-elements' ),
			]
		);

		$this->add_control(
			'dynamic_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => '<div style="background: #e8f5e9; padding: 15px; border-left: 3px solid #4caf50; margin-top: 10px;">
					<strong>' . esc_html__( 'How to get embed code:', 'mn-elements' ) . '</strong><br>
					<ul style="margin: 10px 0 0 20px;">
						<li><strong>Google:</strong> Use Google My Business widget or third-party services</li>
						<li><strong>Facebook:</strong> Facebook Page Plugin</li>
						<li><strong>TripAdvisor:</strong> TripAdvisor Widgets</li>
						<li><strong>Trustpilot:</strong> TrustBox widgets</li>
					</ul>
				</div>',
			]
		);

		$this->end_controls_section();

		// Layout Section
		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Layout', 'mn-elements' ),
				'condition' => [
					'source_type' => 'static',
				],
			]
		);

		$this->add_control(
			'display_type',
			[
				'label' => esc_html__( 'Display Type', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'grid',
				'options' => [
					'grid' => esc_html__( 'Grid', 'mn-elements' ),
					'list' => esc_html__( 'List', 'mn-elements' ),
					'inline-list' => esc_html__( 'Inline List', 'mn-elements' ),
					'carousel' => esc_html__( 'Carousel', 'mn-elements' ),
				],
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
					'{{WRAPPER}} .mn-social-reviews-grid .mn-social-reviews-container' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				],
				'condition' => [
					'display_type' => 'grid',
				],
			]
		);

		$this->add_responsive_control(
			'inline_columns',
			[
				'label' => esc_html__( 'Items Per Row', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => '1',
				'tablet_default' => '1',
				'mobile_default' => '1',
				'options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-social-reviews-inline-list .mn-social-reviews-container' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				],
				'condition' => [
					'display_type' => 'inline-list',
				],
			]
		);

		$this->add_control(
			'show_logo',
			[
				'label' => esc_html__( 'Show Logo', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_platform_name',
			[
				'label' => esc_html__( 'Show Platform Name', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_rating_number',
			[
				'label' => esc_html__( 'Show Rating Number', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_stars',
			[
				'label' => esc_html__( 'Show Stars', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_review_count',
			[
				'label' => esc_html__( 'Show Review Count', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_button',
			[
				'label' => esc_html__( 'Show Button', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->end_controls_section();

		// Theme Section
		$this->start_controls_section(
			'section_theme',
			[
				'label' => esc_html__( 'Theme Version', 'mn-elements' ),
				'condition' => [
					'source_type' => 'static',
				],
			]
		);

		$this->add_control(
			'theme_version',
			[
				'label' => esc_html__( 'Theme Version', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Dark', 'mn-elements' ),
				'label_off' => esc_html__( 'Light', 'mn-elements' ),
				'default' => '',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register style controls.
	 */
	protected function register_style_controls() {
		// General Style
		$this->start_controls_section(
			'section_general_style',
			[
				'label' => esc_html__( 'General', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'source_type' => 'static',
				],
			]
		);

		$this->add_responsive_control(
			'item_gap',
			[
				'label' => esc_html__( 'Item Gap', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-social-reviews-grid' => 'gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-social-reviews-list .mn-review-item:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Review Item Style
		$this->start_controls_section(
			'section_review_item_style',
			[
				'label' => esc_html__( 'Review Item', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'source_type' => 'static',
				],
			]
		);

		$this->add_responsive_control(
			'item_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-review-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'item_border',
				'selector' => '{{WRAPPER}} .mn-review-item',
			]
		);

		$this->add_control(
			'item_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-review-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_box_shadow',
				'selector' => '{{WRAPPER}} .mn-review-item',
			]
		);

		$this->add_control(
			'item_background_color',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-review-item' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Logo Style
		$this->start_controls_section(
			'section_logo_style',
			[
				'label' => esc_html__( 'Logo', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'source_type' => 'static',
					'show_logo' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'logo_size',
			[
				'label' => esc_html__( 'Logo Size', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 200,
					],
				],
				'default' => [
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-review-logo img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-review-logo svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Typography Section
		$this->start_controls_section(
			'section_typography',
			[
				'label' => esc_html__( 'Typography', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'source_type' => 'static',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'platform_name_typography',
				'label' => esc_html__( 'Platform Name', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-review-platform-name',
				'condition' => [
					'show_platform_name' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'rating_number_typography',
				'label' => esc_html__( 'Rating Number', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-review-rating-number',
				'condition' => [
					'show_rating_number' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'review_count_typography',
				'label' => esc_html__( 'Review Count', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-review-count',
				'condition' => [
					'show_review_count' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'label' => esc_html__( 'Button', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-review-button',
				'condition' => [
					'show_button' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		// Colors Section
		$this->start_controls_section(
			'section_colors',
			[
				'label' => esc_html__( 'Colors', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'source_type' => 'static',
				],
			]
		);

		$this->add_control(
			'platform_name_color',
			[
				'label' => esc_html__( 'Platform Name Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-review-platform-name' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_platform_name' => 'yes',
				],
			]
		);

		$this->add_control(
			'rating_number_color',
			[
				'label' => esc_html__( 'Rating Number Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-review-rating-number' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_rating_number' => 'yes',
				],
			]
		);

		$this->add_control(
			'stars_color',
			[
				'label' => esc_html__( 'Stars Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffc107',
				'selectors' => [
					'{{WRAPPER}} .mn-review-stars' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_stars' => 'yes',
				],
			]
		);

		$this->add_control(
			'review_count_color',
			[
				'label' => esc_html__( 'Review Count Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-review-count' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_review_count' => 'yes',
				],
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label' => esc_html__( 'Button Background', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-review-button' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'show_button' => 'yes',
				],
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Button Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-review-button' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_button' => 'yes',
				],
			]
		);

		$this->add_control(
			'button_hover_background_color',
			[
				'label' => esc_html__( 'Button Hover Background', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-review-button:hover' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'show_button' => 'yes',
				],
			]
		);

		$this->add_control(
			'button_hover_text_color',
			[
				'label' => esc_html__( 'Button Hover Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-review-button:hover' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_button' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		// Dynamic Embed Style
		$this->start_controls_section(
			'section_dynamic_style',
			[
				'label' => esc_html__( 'Embed Container', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'source_type' => 'dynamic',
				],
			]
		);

		$this->add_responsive_control(
			'embed_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-social-reviews-dynamic' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'embed_background_color',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-social-reviews-dynamic' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output on the frontend.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( $settings['source_type'] === 'dynamic' ) {
			$this->render_dynamic_reviews( $settings );
		} else {
			$this->render_static_reviews( $settings );
		}
	}

	/**
	 * Render static reviews
	 */
	private function render_static_reviews( $settings ) {
		if ( empty( $settings['reviews_list'] ) ) {
			return;
		}

		$theme_class = $settings['theme_version'] ? 'mn-theme-dark' : 'mn-theme-light';
		$display_class = 'mn-social-reviews-' . $settings['display_type'];

		$this->add_render_attribute( 'wrapper', 'class', [
			'mn-social-reviews-wrapper',
			$theme_class,
			$display_class,
		] );

		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<div class="mn-social-reviews-container">
				<?php
				foreach ( $settings['reviews_list'] as $index => $item ) :
					$this->render_review_item( $item, $settings, $index );
				endforeach;
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render single review item
	 */
	private function render_review_item( $item, $settings, $index ) {
		$platform_data = $this->get_platform_data( $item['platform'] );
		
		?>
		<div class="mn-review-item">
			<div class="mn-review-header">
				<?php if ( $settings['show_logo'] === 'yes' ) : ?>
					<div class="mn-review-logo">
						<?php
						if ( $item['platform'] === 'custom' && ! empty( $item['custom_logo']['url'] ) ) {
							echo '<img src="' . esc_url( $item['custom_logo']['url'] ) . '" alt="' . esc_attr( $item['custom_name'] ) . '">';
						} else {
							echo $this->get_platform_logo_svg( $item['platform'] );
						}
						?>
					</div>
				<?php endif; ?>

				<?php if ( $settings['show_platform_name'] === 'yes' ) : ?>
					<div class="mn-review-platform-name">
						<?php 
						echo esc_html( $item['platform'] === 'custom' ? $item['custom_name'] : $platform_data['name'] );
						?>
					</div>
				<?php endif; ?>
			</div>

			<div class="mn-review-rating">
				<?php if ( $settings['show_rating_number'] === 'yes' ) : ?>
					<div class="mn-review-rating-number">
						<?php echo esc_html( number_format( $item['rating_number'], 1 ) ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $settings['show_stars'] === 'yes' ) : ?>
					<div class="mn-review-stars">
						<?php echo $this->render_stars( $item['rating_number'] ); ?>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( $settings['show_review_count'] === 'yes' ) : ?>
				<div class="mn-review-count">
					<?php 
					// For inline-list: show count only, "on Platform" will be added via CSS/text
					if ( $settings['display_type'] === 'inline-list' ) {
						echo '<strong>' . number_format( $item['review_count'] ) . '</strong>';
						echo ' ' . esc_html__( 'on', 'mn-elements' ) . ' ';
						echo '<span class="mn-review-platform-suffix">' . esc_html( $item['platform'] === 'custom' ? $item['custom_name'] : $platform_data['name'] ) . '</span>';
					} else {
						printf( 
							esc_html__( 'Based on %s reviews', 'mn-elements' ),
							'<strong>' . number_format( $item['review_count'] ) . '</strong>'
						);
					}
					?>
				</div>
			<?php endif; ?>

			<?php if ( $settings['show_button'] === 'yes' && ! empty( $item['review_url']['url'] ) ) : ?>
				<?php
				$this->add_link_attributes( 'button_' . $index, $item['review_url'] );
				$platform_name = $item['platform'] === 'custom' ? $item['custom_name'] : $platform_data['name'];
				?>
				<a <?php $this->print_render_attribute_string( 'button_' . $index ); ?> class="mn-review-button">
					<?php echo esc_html( $item['button_text'] . ' ' . $platform_name ); ?>
				</a>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Render dynamic reviews
	 */
	private function render_dynamic_reviews( $settings ) {
		if ( empty( $settings['embed_code'] ) ) {
			echo '<div class="mn-social-reviews-empty">' . esc_html__( 'Please add embed code in the widget settings.', 'mn-elements' ) . '</div>';
			return;
		}

		?>
		<div class="mn-social-reviews-dynamic">
			<?php echo $this->sanitize_embed_code( $settings['embed_code'] ); ?>
		</div>
		<?php
	}

	/**
	 * Sanitize embed code
	 */
	private function sanitize_embed_code( $code ) {
		// Allow iframe and script tags for embed codes
		$allowed_tags = wp_kses_allowed_html( 'post' );
		$allowed_tags['iframe'] = [
			'src' => true,
			'width' => true,
			'height' => true,
			'frameborder' => true,
			'allowfullscreen' => true,
			'style' => true,
			'class' => true,
			'id' => true,
			'loading' => true,
		];
		$allowed_tags['script'] = [
			'src' => true,
			'type' => true,
			'async' => true,
			'defer' => true,
		];

		return wp_kses( $code, $allowed_tags );
	}

	/**
	 * Get platform data
	 */
	private function get_platform_data( $platform ) {
		$platforms = [
			'google' => [
				'name' => 'Google',
				'color' => '#4285f4',
			],
			'facebook' => [
				'name' => 'Facebook',
				'color' => '#1877f2',
			],
			'tripadvisor' => [
				'name' => 'TripAdvisor',
				'color' => '#00af87',
			],
			'airbnb' => [
				'name' => 'Airbnb',
				'color' => '#ff5a5f',
			],
			'amazon' => [
				'name' => 'Amazon',
				'color' => '#ff9900',
			],
			'yelp' => [
				'name' => 'Yelp',
				'color' => '#d32323',
			],
			'trustpilot' => [
				'name' => 'Trustpilot',
				'color' => '#00b67a',
			],
			'booking' => [
				'name' => 'Booking.com',
				'color' => '#003580',
			],
			'agoda' => [
				'name' => 'Agoda',
				'color' => '#ff6600',
			],
			'tokopedia' => [
				'name' => 'Tokopedia',
				'color' => '#42b549',
			],
			'shopee' => [
				'name' => 'Shopee',
				'color' => '#ee4d2d',
			],
			'bukalapak' => [
				'name' => 'Bukalapak',
				'color' => '#e31e52',
			],
			'lazada' => [
				'name' => 'Lazada',
				'color' => '#0f156d',
			],
		];

		return $platforms[ $platform ] ?? [ 'name' => ucfirst( $platform ), 'color' => '#333333' ];
	}

	/**
	 * Get platform logo SVG
	 */
	private function get_platform_logo_svg( $platform ) {
		$logos = [
			'google' => '<svg viewBox="0 0 48 48"><path fill="#4285F4" d="M45.12 24.5c0-1.56-.14-3.06-.4-4.5H24v8.51h11.84c-.51 2.75-2.06 5.08-4.39 6.64v5.52h7.11c4.16-3.83 6.56-9.47 6.56-16.17z"/><path fill="#34A853" d="M24 46c5.94 0 10.92-1.97 14.56-5.33l-7.11-5.52c-1.97 1.32-4.49 2.1-7.45 2.1-5.73 0-10.58-3.87-12.31-9.07H4.34v5.7C7.96 41.07 15.4 46 24 46z"/><path fill="#FBBC05" d="M11.69 28.18C11.25 26.86 11 25.45 11 24s.25-2.86.69-4.18v-5.7H4.34C2.85 17.09 2 20.45 2 24c0 3.55.85 6.91 2.34 9.88l7.35-5.7z"/><path fill="#EA4335" d="M24 10.75c3.23 0 6.13 1.11 8.41 3.29l6.31-6.31C34.91 4.18 29.93 2 24 2 15.4 2 7.96 6.93 4.34 14.12l7.35 5.7c1.73-5.2 6.58-9.07 12.31-9.07z"/></svg>',
			'facebook' => '<svg viewBox="0 0 48 48"><path fill="#1877F2" d="M48 24C48 10.745 37.255 0 24 0S0 10.745 0 24c0 11.979 8.776 21.908 20.25 23.708v-16.77h-6.094V24h6.094v-5.288c0-6.014 3.583-9.337 9.065-9.337 2.625 0 5.372.469 5.372.469v5.906h-3.026c-2.981 0-3.911 1.85-3.911 3.75V24h6.656l-1.064 6.938H27.75v16.77C39.224 45.908 48 35.978 48 24z"/></svg>',
			'tripadvisor' => '<svg viewBox="0 0 48 48"><circle fill="#00AF87" cx="24" cy="24" r="22"/><path fill="#FFF" d="M24 14c-5.52 0-10 4.48-10 10s4.48 10 10 10 10-4.48 10-10-4.48-10-10-10zm0 16c-3.31 0-6-2.69-6-6s2.69-6 6-6 6 2.69 6 6-2.69 6-6 6z"/><circle fill="#FFF" cx="24" cy="24" r="3"/></svg>',
		];

		return $logos[ $platform ] ?? '<svg viewBox="0 0 48 48"><circle fill="#999" cx="24" cy="24" r="20"/></svg>';
	}

	/**
	 * Render stars
	 */
	private function render_stars( $rating ) {
		$full_stars = floor( $rating );
		$half_star = ( $rating - $full_stars ) >= 0.5;
		$empty_stars = 5 - $full_stars - ( $half_star ? 1 : 0 );

		$output = '';

		// Full stars
		for ( $i = 0; $i < $full_stars; $i++ ) {
			$output .= '<span class="mn-star mn-star-full">★</span>';
		}

		// Half star
		if ( $half_star ) {
			$output .= '<span class="mn-star mn-star-half">★</span>';
		}

		// Empty stars
		for ( $i = 0; $i < $empty_stars; $i++ ) {
			$output .= '<span class="mn-star mn-star-empty">☆</span>';
		}

		return $output;
	}
}
