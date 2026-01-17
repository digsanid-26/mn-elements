<?php
namespace MN_Elements\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Utils;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * MN Image Comparison Widget
 *
 * Before/After image comparison widget with slider functionality
 *
 * @since 1.2.7
 */
class MN_Image_Comparison extends Widget_Base {

	/**
	 * Get widget name.
	 */
	public function get_name() {
		return 'mn-image-comparison';
	}

	/**
	 * Get widget title.
	 */
	public function get_title() {
		return esc_html__( 'MN Image Comparison', 'mn-elements' );
	}

	/**
	 * Get widget icon.
	 */
	public function get_icon() {
		return 'eicon-image-before-after';
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
		return [ 'image', 'comparison', 'before', 'after', 'slider', 'compare', 'mn' ];
	}

	/**
	 * Get style dependencies.
	 */
	public function get_style_depends() {
		return [ 'mn-image-comparison' ];
	}

	/**
	 * Get script dependencies.
	 */
	public function get_script_depends() {
		return [ 'mn-image-comparison' ];
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
		// Items Section
		$this->start_controls_section(
			'section_items',
			[
				'label' => esc_html__( 'Items', 'mn-elements' ),
			]
		);

		$this->add_control(
			'before_image',
			[
				'label' => esc_html__( 'Before Image', 'mn-elements' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'before_label',
			[
				'label' => esc_html__( 'Before Label', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Before', 'mn-elements' ),
				'placeholder' => esc_html__( 'Enter before label', 'mn-elements' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'before_description',
			[
				'label' => esc_html__( 'Before Description', 'mn-elements' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'This is the before image description.', 'mn-elements' ),
				'placeholder' => esc_html__( 'Enter before description', 'mn-elements' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'after_image',
			[
				'label' => esc_html__( 'After Image', 'mn-elements' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'after_label',
			[
				'label' => esc_html__( 'After Label', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'After', 'mn-elements' ),
				'placeholder' => esc_html__( 'Enter after label', 'mn-elements' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'after_description',
			[
				'label' => esc_html__( 'After Description', 'mn-elements' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'This is the after image description.', 'mn-elements' ),
				'placeholder' => esc_html__( 'Enter after description', 'mn-elements' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->end_controls_section();

		// Layout Section
		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Layout', 'mn-elements' ),
			]
		);

		$this->add_control(
			'orientation',
			[
				'label' => esc_html__( 'Orientation Style', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => [
					'horizontal' => esc_html__( 'Horizontal', 'mn-elements' ),
					'vertical' => esc_html__( 'Vertical', 'mn-elements' ),
				],
			]
		);

		$this->add_control(
			'comparison_style',
			[
				'label' => esc_html__( 'Comparison Style', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'square',
				'options' => [
					'square' => esc_html__( 'Square (1:1)', 'mn-elements' ),
					'wide' => esc_html__( 'Wide (2:1)', 'mn-elements' ),
					'tall' => esc_html__( 'Tall (1:2)', 'mn-elements' ),
				],
			]
		);

		$this->add_responsive_control(
			'comparison_height',
			[
				'label' => esc_html__( 'Custom Height', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh', '%' ],
				'range' => [
					'px' => [
						'min' => 200,
						'max' => 1000,
					],
					'vh' => [
						'min' => 20,
						'max' => 100,
					],
					'%' => [
						'min' => 20,
						'max' => 100,
					],
				],
				'condition' => [
					'comparison_style' => 'custom',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-image-comparison-container' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'initial_position',
			[
				'label' => esc_html__( 'Initial Position (%)', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 50,
				],
				'description' => esc_html__( 'Initial position of the comparison slider (0-100%)', 'mn-elements' ),
			]
		);

		$this->add_control(
			'label_position',
			[
				'label' => esc_html__( 'Label Position', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'overlay',
				'options' => [
					'overlay' => esc_html__( 'Overlay on Images', 'mn-elements' ),
					'below' => esc_html__( 'Below Images', 'mn-elements' ),
					'none' => esc_html__( 'Hide Labels', 'mn-elements' ),
				],
				'description' => esc_html__( 'Choose where to display the labels and descriptions', 'mn-elements' ),
			]
		);

		$this->end_controls_section();

		// Theme Version Section
		$this->start_controls_section(
			'section_theme_version',
			[
				'label' => esc_html__( 'Theme Version', 'mn-elements' ),
			]
		);

		$this->add_control(
			'theme_version',
			[
				'label' => esc_html__( 'Theme Version', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'light',
				'options' => [
					'light' => esc_html__( 'Light', 'mn-elements' ),
					'dark' => esc_html__( 'Dark', 'mn-elements' ),
				],
				'prefix_class' => 'mn-image-comparison-theme-',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register style controls.
	 */
	protected function register_style_controls() {
		// Container Style
		$this->start_controls_section(
			'section_container_style',
			[
				'label' => esc_html__( 'Container', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'container_background',
				'label' => esc_html__( 'Background', 'mn-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .mn-image-comparison-wrapper',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'container_border',
				'label' => esc_html__( 'Border', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-image-comparison-container',
			]
		);

		$this->add_responsive_control(
			'container_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .mn-image-comparison-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'container_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-image-comparison-container',
			]
		);

		$this->add_responsive_control(
			'container_margin',
			[
				'label' => esc_html__( 'Margin', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .mn-image-comparison-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'container_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .mn-image-comparison-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Slider Handle Style
		$this->start_controls_section(
			'section_handle_style',
			[
				'label' => esc_html__( 'Slider Handle', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'handle_size',
			[
				'label' => esc_html__( 'Handle Size', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 80,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-comparison-handle' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'handle_color',
			[
				'label' => esc_html__( 'Handle Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-comparison-handle' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .mn-comparison-handle::before' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-comparison-handle::after' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'handle_icon_color',
			[
				'label' => esc_html__( 'Handle Icon Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .mn-comparison-handle i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'handle_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-comparison-handle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'handle_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-comparison-handle',
			]
		);

		$this->end_controls_section();

		// Divider Line Style
		$this->start_controls_section(
			'section_divider_style',
			[
				'label' => esc_html__( 'Divider Line', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'divider_width',
			[
				'label' => esc_html__( 'Line Width', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-comparison-divider' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-comparison-divider.vertical' => 'height: {{SIZE}}{{UNIT}}; width: 100%;',
				],
			]
		);

		$this->add_control(
			'divider_color',
			[
				'label' => esc_html__( 'Line Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-comparison-divider' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Labels Style
		$this->start_controls_section(
			'section_labels_style',
			[
				'label' => esc_html__( 'Labels', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'label_typography',
				'label' => esc_html__( 'Label Typography', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-comparison-label',
			]
		);

		$this->add_control(
			'label_color',
			[
				'label' => esc_html__( 'Label Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-comparison-label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'label_background',
			[
				'label' => esc_html__( 'Label Background', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-comparison-label' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'label_padding',
			[
				'label' => esc_html__( 'Label Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .mn-comparison-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'label_border_radius',
			[
				'label' => esc_html__( 'Label Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-comparison-label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Description Style
		$this->start_controls_section(
			'section_description_style',
			[
				'label' => esc_html__( 'Description', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'label' => esc_html__( 'Description Typography', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-comparison-description',
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => esc_html__( 'Description Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-comparison-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'description_margin',
			[
				'label' => esc_html__( 'Description Margin', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .mn-comparison-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		$widget_id = $this->get_id();

		// Get image URLs
		$before_image_url = $settings['before_image']['url'] ?? '';
		$after_image_url = $settings['after_image']['url'] ?? '';

		if ( empty( $before_image_url ) || empty( $after_image_url ) ) {
			return;
		}

		// Prepare comparison settings
		$comparison_settings = [
			'orientation' => $settings['orientation'],
			'initialPosition' => $settings['initial_position']['size'] ?? 50,
			'widgetId' => $widget_id,
		];

		$label_position = $settings['label_position'] ?? 'overlay';
		?>
		<div class="mn-image-comparison-wrapper mn-image-comparison-<?php echo esc_attr( $settings['orientation'] ); ?> mn-comparison-style-<?php echo esc_attr( $settings['comparison_style'] ); ?> mn-label-position-<?php echo esc_attr( $label_position ); ?>">
			<div class="mn-image-comparison-container" data-comparison-settings="<?php echo esc_attr( json_encode( $comparison_settings ) ); ?>">
				<!-- Before Image -->
				<div class="mn-comparison-before">
					<img src="<?php echo esc_url( $before_image_url ); ?>" alt="<?php echo esc_attr( $settings['before_label'] ); ?>" />
					<?php if ( $label_position === 'overlay' && ! empty( $settings['before_label'] ) ) : ?>
						<div class="mn-comparison-label mn-comparison-label-before">
							<?php echo esc_html( $settings['before_label'] ); ?>
						</div>
					<?php endif; ?>
				</div>

				<!-- After Image -->
				<div class="mn-comparison-after">
					<img src="<?php echo esc_url( $after_image_url ); ?>" alt="<?php echo esc_attr( $settings['after_label'] ); ?>" />
					<?php if ( $label_position === 'overlay' && ! empty( $settings['after_label'] ) ) : ?>
						<div class="mn-comparison-label mn-comparison-label-after">
							<?php echo esc_html( $settings['after_label'] ); ?>
						</div>
					<?php endif; ?>
				</div>

				<!-- Comparison Divider -->
				<div class="mn-comparison-divider <?php echo esc_attr( $settings['orientation'] ); ?>">
					<div class="mn-comparison-handle">
						<?php if ( $settings['orientation'] === 'horizontal' ) : ?>
							<i class="eicon-h-align-stretch" aria-hidden="true"></i>
						<?php else : ?>
							<i class="eicon-v-align-stretch" aria-hidden="true"></i>
						<?php endif; ?>
					</div>
				</div>

				<!-- Zoom Icon -->
				<div class="mn-comparison-zoom-icon" title="<?php echo esc_attr__( 'View Full Size', 'mn-elements' ); ?>">
					<i class="eicon-zoom-in-bold" aria-hidden="true"></i>
				</div>
			</div>

			<!-- Zoom Popup Modal -->
			<div class="mn-comparison-zoom-popup" style="display: none;">
				<div class="mn-comparison-zoom-overlay"></div>
				<div class="mn-comparison-zoom-content">
					<div class="mn-comparison-zoom-close">
						<i class="eicon-close" aria-hidden="true"></i>
					</div>
					<div class="mn-comparison-zoom-images">
						<div class="mn-comparison-zoom-before">
							<img src="<?php echo esc_url( $before_image_url ); ?>" alt="<?php echo esc_attr( $settings['before_label'] ); ?>" />
							<?php if ( ! empty( $settings['before_label'] ) ) : ?>
								<div class="mn-comparison-zoom-label"><?php echo esc_html( $settings['before_label'] ); ?></div>
							<?php endif; ?>
						</div>
						<div class="mn-comparison-zoom-after">
							<img src="<?php echo esc_url( $after_image_url ); ?>" alt="<?php echo esc_attr( $settings['after_label'] ); ?>" />
							<?php if ( ! empty( $settings['after_label'] ) ) : ?>
								<div class="mn-comparison-zoom-label"><?php echo esc_html( $settings['after_label'] ); ?></div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>

			<!-- Content Below Images -->
			<?php if ( $label_position === 'below' || ( $label_position !== 'none' && ( ! empty( $settings['before_description'] ) || ! empty( $settings['after_description'] ) ) ) ) : ?>
			<div class="mn-comparison-content">
				<div class="mn-comparison-content-row">
					<?php if ( ! empty( $settings['before_label'] ) || ! empty( $settings['before_description'] ) ) : ?>
						<div class="mn-comparison-content-before">
							<?php if ( $label_position === 'below' && ! empty( $settings['before_label'] ) ) : ?>
								<h4 class="mn-comparison-content-title"><?php echo esc_html( $settings['before_label'] ); ?></h4>
							<?php endif; ?>
							<?php if ( ! empty( $settings['before_description'] ) ) : ?>
								<div class="mn-comparison-description">
									<?php echo wp_kses_post( $settings['before_description'] ); ?>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $settings['after_label'] ) || ! empty( $settings['after_description'] ) ) : ?>
						<div class="mn-comparison-content-after">
							<?php if ( $label_position === 'below' && ! empty( $settings['after_label'] ) ) : ?>
								<h4 class="mn-comparison-content-title"><?php echo esc_html( $settings['after_label'] ); ?></h4>
							<?php endif; ?>
							<?php if ( ! empty( $settings['after_description'] ) ) : ?>
								<div class="mn-comparison-description">
									<?php echo wp_kses_post( $settings['after_description'] ); ?>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<?php endif; ?>
		</div>
		<?php
	}
}
