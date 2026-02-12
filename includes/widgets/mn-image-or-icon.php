<?php
namespace MN_Elements\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Icons_Manager;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * MN ImageOrIcon Widget
 *
 * Display image or icon with dynamic field support and filter effects
 *
 * @since 1.0.0
 */
class MN_ImageOrIcon extends Widget_Base {

	/**
	 * Get widget name.
	 */
	public function get_name() {
		return 'mn-image-or-icon';
	}

	/**
	 * Get widget title.
	 */
	public function get_title() {
		return esc_html__( 'MN Image or Icon', 'mn-elements' );
	}

	/**
	 * Get widget icon.
	 */
	public function get_icon() {
		return 'eicon-image-bold';
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
		return [ 'image', 'icon', 'media', 'picture', 'photo', 'dynamic', 'acf', 'jetengine' ];
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
		// Source Section
		$this->start_controls_section(
			'section_source',
			[
				'label' => esc_html__( 'Source', 'mn-elements' ),
			]
		);

		$this->add_control(
			'source_type',
			[
				'label' => esc_html__( 'Source Type', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'image',
				'options' => [
					'image' => esc_html__( 'Image', 'mn-elements' ),
					'icon' => esc_html__( 'Icon', 'mn-elements' ),
				],
			]
		);

		// Image Controls
		$this->add_control(
			'image',
			[
				'label' => esc_html__( 'Choose Image', 'mn-elements' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'source_type' => 'image',
				],
			]
		);

		$this->add_control(
			'image_custom_field',
			[
				'label' => esc_html__( 'Or Use Custom Field', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'custom_field_name', 'mn-elements' ),
				'description' => esc_html__( 'Enter ACF or JetEngine custom field name. Leave empty to use image above.', 'mn-elements' ),
				'condition' => [
					'source_type' => 'image',
				],
			]
		);

		// Icon Controls
		$this->add_control(
			'icon',
			[
				'label' => esc_html__( 'Choose Icon', 'mn-elements' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'fa-solid',
				],
				'condition' => [
					'source_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_custom_field',
			[
				'label' => esc_html__( 'Or Use Custom Field', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'custom_field_name', 'mn-elements' ),
				'description' => esc_html__( 'Enter ACF or JetEngine custom field name for icon class. Leave empty to use icon above.', 'mn-elements' ),
				'condition' => [
					'source_type' => 'icon',
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

		// Image Layout Controls
		$this->add_control(
			'image_filter_effect',
			[
				'label' => esc_html__( 'Image Filter Effect', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => esc_html__( 'None', 'mn-elements' ),
					'grayscale' => esc_html__( 'Grayscale to Color', 'mn-elements' ),
					'blur' => esc_html__( 'Blur to Sharp', 'mn-elements' ),
					'sepia' => esc_html__( 'Sepia to Color', 'mn-elements' ),
					'saturate' => esc_html__( 'Desaturated to Saturated', 'mn-elements' ),
					'brightness' => esc_html__( 'Dark to Bright', 'mn-elements' ),
					'contrast' => esc_html__( 'Low to High Contrast', 'mn-elements' ),
				],
				'condition' => [
					'source_type' => 'image',
				],
			]
		);

		// Icon Layout Controls
		$this->add_control(
			'icon_view',
			[
				'label' => esc_html__( 'View', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' => esc_html__( 'Default', 'mn-elements' ),
					'stacked' => esc_html__( 'Stacked', 'mn-elements' ),
					'framed' => esc_html__( 'Framed', 'mn-elements' ),
				],
				'default' => 'default',
				'prefix_class' => 'mn-view-',
				'condition' => [
					'source_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_shape',
			[
				'label' => esc_html__( 'Shape', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'circle' => esc_html__( 'Circle', 'mn-elements' ),
					'square' => esc_html__( 'Square', 'mn-elements' ),
				],
				'default' => 'circle',
				'condition' => [
					'icon_view!' => 'default',
					'source_type' => 'icon',
				],
				'prefix_class' => 'mn-shape-',
			]
		);

		// Common Controls
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
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
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

		// Icon Animation Section
		$this->start_controls_section(
			'section_icon_animation',
			[
				'label' => esc_html__( 'Icon Animation', 'mn-elements' ),
				'condition' => [
					'source_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_loop_animation',
			[
				'label' => esc_html__( 'Loop Animation', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => esc_html__( 'None', 'mn-elements' ),
					'pulse' => esc_html__( 'Pulse', 'mn-elements' ),
					'bounce' => esc_html__( 'Bounce Up', 'mn-elements' ),
					'bounce-down' => esc_html__( 'Bounce Down', 'mn-elements' ),
					'shake' => esc_html__( 'Shake', 'mn-elements' ),
					'rotate' => esc_html__( 'Rotate', 'mn-elements' ),
					'swing' => esc_html__( 'Swing', 'mn-elements' ),
					'flash' => esc_html__( 'Flash', 'mn-elements' ),
					'push' => esc_html__( 'Push', 'mn-elements' ),
				],
			]
		);

		$this->add_control(
			'icon_hover_animation_custom',
			[
				'label' => esc_html__( 'Hover Animation', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => esc_html__( 'None', 'mn-elements' ),
					'grow' => esc_html__( 'Grow', 'mn-elements' ),
					'shrink' => esc_html__( 'Shrink', 'mn-elements' ),
					'rotate-90' => esc_html__( 'Rotate 90°', 'mn-elements' ),
					'rotate-180' => esc_html__( 'Rotate 180°', 'mn-elements' ),
					'rotate-360' => esc_html__( 'Rotate 360°', 'mn-elements' ),
					'wobble' => esc_html__( 'Wobble', 'mn-elements' ),
					'buzz' => esc_html__( 'Buzz', 'mn-elements' ),
					'slide-up' => esc_html__( 'Slide Up', 'mn-elements' ),
					'slide-down' => esc_html__( 'Slide Down', 'mn-elements' ),
					'slide-left' => esc_html__( 'Slide Left', 'mn-elements' ),
					'slide-right' => esc_html__( 'Slide Right', 'mn-elements' ),
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register style controls.
	 */
	protected function register_style_controls() {
		// Image Style
		$this->start_controls_section(
			'section_style_image',
			[
				'label' => esc_html__( 'Image', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'source_type' => 'image',
				],
			]
		);

		$this->add_responsive_control(
			'image_width',
			[
				'label' => esc_html__( 'Width', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vw' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'vw' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mn-image-or-icon-image img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_height',
			[
				'label' => esc_html__( 'Height', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'vh' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mn-image-or-icon-image img' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_object_fit',
			[
				'label' => esc_html__( 'Object Fit', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'Default', 'mn-elements' ),
					'fill' => esc_html__( 'Fill', 'mn-elements' ),
					'cover' => esc_html__( 'Cover', 'mn-elements' ),
					'contain' => esc_html__( 'Contain', 'mn-elements' ),
					'none' => esc_html__( 'None', 'mn-elements' ),
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-image-or-icon-image img' => 'object-fit: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_spacing',
			[
				'label' => esc_html__( 'Spacing', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mn-image-or-icon-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'selector' => '{{WRAPPER}} .mn-image-or-icon-image img',
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-image-or-icon-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'image_box_shadow',
				'selector' => '{{WRAPPER}} .mn-image-or-icon-image img',
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'image_css_filters',
				'selector' => '{{WRAPPER}} .mn-image-or-icon-image img',
			]
		);

		$this->end_controls_section();

		// Icon Style
		$this->start_controls_section(
			'section_style_icon',
			[
				'label' => esc_html__( 'Icon', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'source_type' => 'icon',
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
			'icon_primary_color',
			[
				'label' => esc_html__( 'Primary Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}.mn-view-stacked .mn-image-or-icon-icon' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.mn-view-framed .mn-image-or-icon-icon, {{WRAPPER}}.mn-view-default .mn-image-or-icon-icon' => 'color: {{VALUE}}; border-color: {{VALUE}};',
					'{{WRAPPER}}.mn-view-framed .mn-image-or-icon-icon svg, {{WRAPPER}}.mn-view-default .mn-image-or-icon-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_secondary_color',
			[
				'label' => esc_html__( 'Secondary Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'condition' => [
					'icon_view!' => 'default',
				],
				'selectors' => [
					'{{WRAPPER}}.mn-view-framed .mn-image-or-icon-icon' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.mn-view-stacked .mn-image-or-icon-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}}.mn-view-stacked .mn-image-or-icon-icon svg' => 'fill: {{VALUE}};',
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
			'icon_hover_primary_color',
			[
				'label' => esc_html__( 'Primary Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}.mn-view-stacked .mn-image-or-icon-icon:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.mn-view-framed .mn-image-or-icon-icon:hover, {{WRAPPER}}.mn-view-default .mn-image-or-icon-icon:hover' => 'color: {{VALUE}}; border-color: {{VALUE}};',
					'{{WRAPPER}}.mn-view-framed .mn-image-or-icon-icon:hover svg, {{WRAPPER}}.mn-view-default .mn-image-or-icon-icon:hover svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_hover_secondary_color',
			[
				'label' => esc_html__( 'Secondary Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'condition' => [
					'icon_view!' => 'default',
				],
				'selectors' => [
					'{{WRAPPER}}.mn-view-framed .mn-image-or-icon-icon:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.mn-view-stacked .mn-image-or-icon-icon:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}}.mn-view-stacked .mn-image-or-icon-icon:hover svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'mn-elements' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Size', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],
				'default' => [
					'size' => 50,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-image-or-icon-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-image-or-icon-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'icon_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-image-or-icon-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_spacing',
			[
				'label' => esc_html__( 'Spacing', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mn-image-or-icon-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_rotate',
			[
				'label' => esc_html__( 'Rotate', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'deg' ],
				'default' => [
					'size' => 0,
					'unit' => 'deg',
				],
				'tablet_default' => [
					'unit' => 'deg',
				],
				'mobile_default' => [
					'unit' => 'deg',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-image-or-icon-icon i, {{WRAPPER}} .mn-image-or-icon-icon svg' => 'transform: rotate({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->add_control(
			'icon_border_style',
			[
				'label' => esc_html__( 'Border Style', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'None', 'mn-elements' ),
					'solid' => esc_html__( 'Solid', 'mn-elements' ),
					'dashed' => esc_html__( 'Dashed', 'mn-elements' ),
					'dotted' => esc_html__( 'Dotted', 'mn-elements' ),
					'double' => esc_html__( 'Double', 'mn-elements' ),
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-image-or-icon-icon' => 'border-style: {{VALUE}};',
				],
				'condition' => [
					'icon_view' => 'default',
				],
			]
		);

		$this->add_control(
			'icon_border_width',
			[
				'label' => esc_html__( 'Border Width', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'default' => [
					'size' => 3,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-image-or-icon-icon' => 'border-width: {{SIZE}}{{UNIT}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'icon_view',
							'value' => 'framed',
						],
						[
							'name' => 'icon_border_style',
							'operator' => '!==',
							'value' => '',
						],
					],
				],
			]
		);

		$this->add_control(
			'icon_border_color',
			[
				'label' => esc_html__( 'Border Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-image-or-icon-icon' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'icon_view' => 'default',
					'icon_border_style!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'icon_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-image-or-icon-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'icon_box_shadow',
				'selector' => '{{WRAPPER}} .mn-image-or-icon-icon',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'icon_text_shadow',
				'selector' => '{{WRAPPER}} .mn-image-or-icon-icon',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Get custom field value with ACF and JetEngine support
	 */
	private function get_custom_field_value( $field_name ) {
		if ( empty( $field_name ) ) {
			return '';
		}

		// Try ACF first
		if ( function_exists( 'get_field' ) ) {
			$value = get_field( $field_name );
			if ( ! empty( $value ) ) {
				// Handle ACF image field
				if ( is_array( $value ) && isset( $value['url'] ) ) {
					return $value['url'];
				}
				// Handle ACF file/image ID
				if ( is_numeric( $value ) ) {
					$url = wp_get_attachment_url( $value );
					if ( $url ) {
						return $url;
					}
				}
				return $value;
			}
		}

		// Fallback to WordPress meta
		$value = get_post_meta( get_the_ID(), $field_name, true );
		
		// Handle attachment ID
		if ( is_numeric( $value ) ) {
			$url = wp_get_attachment_url( $value );
			if ( $url ) {
				return $url;
			}
		}

		return $value;
	}

	/**
	 * Render widget output on the frontend.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$has_link = ! empty( $settings['link']['url'] );
		$link_attrs = '';

		if ( $has_link ) {
			$this->add_link_attributes( 'link', $settings['link'] );
			$link_attrs = $this->get_render_attribute_string( 'link' );
		}

		?>
		<div class="mn-image-or-icon-wrapper">
			<?php if ( $settings['source_type'] === 'image' ) : ?>
				<?php $this->render_image( $settings, $has_link, $link_attrs ); ?>
			<?php else : ?>
				<?php $this->render_icon( $settings, $has_link, $link_attrs ); ?>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Render image
	 */
	private function render_image( $settings, $has_link, $link_attrs ) {
		// Get image URL
		$image_url = '';
		
		// Check custom field first
		if ( ! empty( $settings['image_custom_field'] ) ) {
			$image_url = $this->get_custom_field_value( $settings['image_custom_field'] );
		}
		
		// Fallback to uploaded image
		if ( empty( $image_url ) && ! empty( $settings['image']['url'] ) ) {
			$image_url = $settings['image']['url'];
		}

		if ( empty( $image_url ) ) {
			return;
		}

		$filter_class = ( $settings['image_filter_effect'] !== 'none' ) ? 'mn-filter-' . $settings['image_filter_effect'] : '';

		?>
		<div class="mn-image-or-icon-image <?php echo esc_attr( $filter_class ); ?>">
			<?php if ( $has_link ) : ?>
				<a <?php echo $link_attrs; ?>>
			<?php endif; ?>
			
			<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" />
			
			<?php if ( $has_link ) : ?>
				</a>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Render icon
	 */
	private function render_icon( $settings, $has_link, $link_attrs ) {
		$icon_html = '';
		
		// Check custom field first
		if ( ! empty( $settings['icon_custom_field'] ) ) {
			$icon_class = $this->get_custom_field_value( $settings['icon_custom_field'] );
			if ( ! empty( $icon_class ) ) {
				$icon_html = '<i class="' . esc_attr( $icon_class ) . '"></i>';
			}
		}
		
		// Fallback to selected icon
		if ( empty( $icon_html ) && ! empty( $settings['icon']['value'] ) ) {
			ob_start();
			Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );
			$icon_html = ob_get_clean();
		}

		if ( empty( $icon_html ) ) {
			return;
		}

		// Build animation classes
		$animation_classes = [];
		
		// Elementor hover animation (from Style tab)
		if ( ! empty( $settings['icon_hover_animation'] ) ) {
			$animation_classes[] = 'elementor-animation-' . $settings['icon_hover_animation'];
		}
		
		// Loop animation
		if ( ! empty( $settings['icon_loop_animation'] ) && $settings['icon_loop_animation'] !== 'none' ) {
			$animation_classes[] = 'mn-icon-loop-' . $settings['icon_loop_animation'];
		}
		
		// Custom hover animation (from Icon Animation section)
		if ( ! empty( $settings['icon_hover_animation_custom'] ) ) {
			$animation_classes[] = 'mn-icon-hover-' . $settings['icon_hover_animation_custom'];
		}
		
		$animation_class = implode( ' ', $animation_classes );

		?>
		<div class="mn-image-or-icon-icon-wrapper">
			<?php if ( $has_link ) : ?>
				<a <?php echo $link_attrs; ?> class="mn-image-or-icon-icon <?php echo esc_attr( $animation_class ); ?>">
			<?php else : ?>
				<div class="mn-image-or-icon-icon <?php echo esc_attr( $animation_class ); ?>">
			<?php endif; ?>
			
			<?php echo $icon_html; ?>
			
			<?php if ( $has_link ) : ?>
				</a>
			<?php else : ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}
}
