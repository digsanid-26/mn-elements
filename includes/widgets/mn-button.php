<?php
namespace MN_Elements\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Icons_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * MN Button Widget
 *
 * Enhanced button widget with individual icon resize and animation controls
 *
 * @since 1.0.2
 */
class MN_Button extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 1.0.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mn-button';
	}

	/**
	 * Get widget title.
	 *
	 * @since 1.0.2
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'MN Button', 'mn-elements' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.0.2
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-button';
	}

	/**
	 * Get widget categories.
	 *
	 * @since 1.0.2
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
	 * @since 1.0.2
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'button', 'link', 'cta', 'mn', 'animation', 'icon' ];
	}

	/**
	 * Register widget controls.
	 *
	 * @since 1.0.2
	 * @access protected
	 */
	protected function register_controls() {
		$this->register_content_controls();
		$this->register_style_controls();
	}

	/**
	 * Register content controls.
	 *
	 * @since 1.0.2
	 * @access protected
	 */
	protected function register_content_controls() {
		// Button Content Section
		$this->start_controls_section(
			'section_button',
			[
				'label' => esc_html__( 'Button', 'mn-elements' ),
			]
		);

		$this->add_control(
			'text',
			[
				'label' => esc_html__( 'Text', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Click here', 'mn-elements' ),
				'placeholder' => esc_html__( 'Click here', 'mn-elements' ),
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
				'default' => [
					'url' => '#',
				],
			]
		);

		
		$this->add_control(
			'size',
			[
				'label' => esc_html__( 'Size', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'sm',
				'options' => [
					'xs' => esc_html__( 'Extra Small', 'mn-elements' ),
					'sm' => esc_html__( 'Small', 'mn-elements' ),
					'md' => esc_html__( 'Medium', 'mn-elements' ),
					'lg' => esc_html__( 'Large', 'mn-elements' ),
					'xl' => esc_html__( 'Extra Large', 'mn-elements' ),
				],
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'selected_icon',
			[
				'label' => esc_html__( 'Icon', 'mn-elements' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin' => 'inline',
				'label_block' => false,
			]
		);

		$this->add_control(
			'icon_align',
			[
				'label' => esc_html__( 'Icon Position', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => esc_html__( 'Before', 'mn-elements' ),
					'right' => esc_html__( 'After', 'mn-elements' ),
				],
				'condition' => [
					'selected_icon[value]!' => '',
				],
			]
		);

		$this->add_control(
			'icon_indent',
			[
				'label' => esc_html__( 'Icon Spacing', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mn-button .mn-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-button .mn-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'selected_icon[value]!' => '',
				],
			]
		);

		$this->end_controls_section();

		// Background Animation Section
		$this->start_controls_section(
			'section_background_animation',
			[
				'label' => esc_html__( 'Background Animation', 'mn-elements' ),
			]
		);

		$this->add_control(
			'background_animation',
			[
				'label' => esc_html__( 'Animation Effect', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => esc_html__( 'None', 'mn-elements' ),
					'gradient-loop' => esc_html__( 'Rotate Gradient Loop', 'mn-elements' ),
					'linear-gradient-loop' => esc_html__( 'Linear Gradient Loop', 'mn-elements' ),
					'gradient-wave' => esc_html__( 'Gradient Wave', 'mn-elements' ),
					'shimmer' => esc_html__( 'Shimmer', 'mn-elements' ),
					'pulse-glow' => esc_html__( 'Pulse Glow', 'mn-elements' ),
					'border-flow' => esc_html__( 'Border Flow', 'mn-elements' ),
					'ripple' => esc_html__( 'Ripple Effect', 'mn-elements' ),
					'slide-shine' => esc_html__( 'Slide Shine', 'mn-elements' ),
				],
				'description' => esc_html__( 'Add animated background effects to your button', 'mn-elements' ),
			]
		);

		$this->add_control(
			'animation_speed',
			[
				'label' => esc_html__( 'Animation Speed', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'normal',
				'options' => [
					'slow' => esc_html__( 'Slow', 'mn-elements' ),
					'normal' => esc_html__( 'Normal', 'mn-elements' ),
					'fast' => esc_html__( 'Fast', 'mn-elements' ),
				],
				'condition' => [
					'background_animation!' => '',
				],
			]
		);

		$this->add_control(
			'gradient_color_1',
			[
				'label' => esc_html__( 'Gradient Color 1', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#667eea',
				'condition' => [
					'background_animation' => ['gradient-loop', 'linear-gradient-loop', 'gradient-wave'],
				],
			]
		);

		$this->add_control(
			'gradient_color_2',
			[
				'label' => esc_html__( 'Gradient Color 2', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#764ba2',
				'condition' => [
					'background_animation' => ['gradient-loop', 'linear-gradient-loop', 'gradient-wave'],
				],
			]
		);

		$this->add_control(
			'gradient_color_3',
			[
				'label' => esc_html__( 'Gradient Color 3', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#f093fb',
				'condition' => [
					'background_animation' => ['gradient-loop', 'linear-gradient-loop', 'gradient-wave'],
				],
			]
		);

		$this->add_control(
			'gradient_direction',
			[
				'label' => esc_html__( 'Gradient Direction', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [
					'left' => esc_html__( 'Left', 'mn-elements' ),
					'right' => esc_html__( 'Right', 'mn-elements' ),
				],
				'condition' => [
					'background_animation' => ['gradient-loop', 'linear-gradient-loop', 'gradient-wave'],
				],
			]
		);

		$this->add_control(
			'glow_color',
			[
				'label' => esc_html__( 'Glow Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#667eea',
				'condition' => [
					'background_animation' => 'pulse-glow',
				],
			]
		);

		$this->end_controls_section();

		// Icon Animation Section
		$this->start_controls_section(
			'section_icon_animation',
			[
				'label' => esc_html__( 'Icon Animation', 'mn-elements' ),
				'condition' => [
					'selected_icon[value]!' => '',
				],
			]
		);

		$this->add_control(
			'icon_loop_animation',
			[
				'label' => esc_html__( 'Loop Animation', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'none' => esc_html__( 'None', 'mn-elements' ),
					'pulse' => esc_html__( 'Pulse', 'mn-elements' ),
					'bounce' => esc_html__( 'Bounce', 'mn-elements' ),
					'shake' => esc_html__( 'Shake', 'mn-elements' ),
					'rotate' => esc_html__( 'Rotate', 'mn-elements' ),
					'swing' => esc_html__( 'Swing', 'mn-elements' ),
					'flash' => esc_html__( 'Flash', 'mn-elements' ),
					'push' => esc_html__( 'Push', 'mn-elements' ),
				],
			]
		);

		$this->add_control(
			'icon_hover_animation',
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
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register style controls.
	 *
	 * @since 1.0.2
	 * @access protected
	 */
	protected function register_style_controls() {
		// Button Style Section
		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Button', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'button_position',
			[
				'label' => esc_html__( 'Position', 'mn-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => esc_html__( 'Left', 'mn-elements' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'mn-elements' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'mn-elements' ),
						'icon' => 'eicon-h-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Stretch', 'mn-elements' ),
						'icon' => 'eicon-h-align-stretch',
					],
				],
				'prefix_class' => 'elementor%s-align-',
				'default' => '',
			]
		);

		$start = is_rtl() ? 'right' : 'left';
		$end = is_rtl() ? 'left' : 'right';

		$this->add_responsive_control(
			'content_align',
			[
				'label' => esc_html__( 'Alignment', 'mn-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start'    => [
						'title' => esc_html__( 'Start', 'mn-elements' ),
						'icon' => 'eicon-text-align-' . $start,
					],
					'center' => [
						'title' => esc_html__( 'Center', 'mn-elements' ),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'End', 'mn-elements' ),
						'icon' => 'eicon-text-align-' . $end,
					],
					'space-between' => [
						'title' => esc_html__( 'Space Between', 'mn-elements' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-button .mn-button-content-wrapper' => 'justify-content: {{VALUE}};',
				],
				'condition' => [
					'button_position' => 'justify',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .mn-button',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'text_shadow',
				'selector' => '{{WRAPPER}} .mn-button',
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'mn-elements' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-button' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'label' => esc_html__( 'Background', 'mn-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .mn-button',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'global' => [
							'default' => Global_Colors::COLOR_ACCENT,
						],
					],
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'mn-elements' ),
			]
		);

		$this->add_control(
			'hover_color',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-button:hover, {{WRAPPER}} .mn-button:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-button:hover svg, {{WRAPPER}} .mn-button:focus svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_background_hover',
				'label' => esc_html__( 'Background', 'mn-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .mn-button:hover, {{WRAPPER}} .mn-button:focus',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-button:hover, {{WRAPPER}} .mn-button:focus' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'mn-elements' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'selector' => '{{WRAPPER}} .mn-button',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .mn-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .mn-button',
			]
		);

		$this->add_responsive_control(
			'text_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Icon Style Section
		$this->start_controls_section(
			'section_icon_style',
			[
				'label' => esc_html__( 'Icon', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'selected_icon[value]!' => '',
				],
			]
		);

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
				'selectors' => [
					'{{WRAPPER}} .mn-button .mn-button-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-button .mn-button-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-button .mn-button-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-button .mn-button-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_hover_color',
			[
				'label' => esc_html__( 'Hover Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-button:hover .mn-button-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-button:hover .mn-button-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * @since 1.0.2
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'wrapper', 'class', 'mn-button-wrapper' );

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'button', $settings['link'] );
			$this->add_render_attribute( 'button', 'class', 'mn-button-link' );
		}

		$this->add_render_attribute( 'button', 'class', 'mn-button' );
		$this->add_render_attribute( 'button', 'role', 'button' );

		if ( ! empty( $settings['button_css_id'] ) ) {
			$this->add_render_attribute( 'button', 'id', $settings['button_css_id'] );
		}

		if ( ! empty( $settings['size'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-size-' . $settings['size'] );
		}

		if ( $settings['hover_animation'] ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-animation-' . $settings['hover_animation'] );
		}

		// Background animation classes
		if ( ! empty( $settings['background_animation'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'mn-bg-' . $settings['background_animation'] );
			
			if ( ! empty( $settings['animation_speed'] ) ) {
				$this->add_render_attribute( 'button', 'class', 'mn-speed-' . $settings['animation_speed'] );
			}
			
			// Add gradient colors as CSS variables
			if ( in_array( $settings['background_animation'], ['gradient-loop', 'linear-gradient-loop', 'gradient-wave'] ) ) {
				$gradient_vars = [];
				
				// Get gradient colors with defaults
				$color_1 = ! empty( $settings['gradient_color_1'] ) ? $settings['gradient_color_1'] : '#667eea';
				$color_2 = ! empty( $settings['gradient_color_2'] ) ? $settings['gradient_color_2'] : '#764ba2';
				$color_3 = ! empty( $settings['gradient_color_3'] ) ? $settings['gradient_color_3'] : '#f093fb';
				
				// Build CSS variables array
				$gradient_vars[] = '--mn-gradient-1: ' . esc_attr( $color_1 );
				$gradient_vars[] = '--mn-gradient-2: ' . esc_attr( $color_2 );
				$gradient_vars[] = '--mn-gradient-3: ' . esc_attr( $color_3 );
				
				// Apply as inline style
				$this->add_render_attribute( 'button', 'style', implode( '; ', $gradient_vars ) . ';' );
				
				// Add gradient direction class
				$gradient_direction = ! empty( $settings['gradient_direction'] ) ? $settings['gradient_direction'] : 'right';
				$this->add_render_attribute( 'button', 'class', 'mn-gradient-' . $gradient_direction );
			}
			
			// Add glow color as CSS variable
			if ( $settings['background_animation'] === 'pulse-glow' && ! empty( $settings['glow_color'] ) ) {
				$this->add_render_attribute( 'button', 'style', '--mn-glow-color: ' . esc_attr( $settings['glow_color'] ) . ';' );
			}
		}

		// Icon animation classes
		$icon_classes = [];
		if ( ! empty( $settings['icon_loop_animation'] ) ) {
			$icon_classes[] = 'mn-icon-loop-' . $settings['icon_loop_animation'];
		}
		if ( ! empty( $settings['icon_hover_animation'] ) ) {
			$icon_classes[] = 'mn-icon-hover-' . $settings['icon_hover_animation'];
		}

		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<a <?php $this->print_render_attribute_string( 'button' ); ?>>
				<?php $this->render_text(); ?>
			</a>
		</div>
		<?php
	}

	/**
	 * Render button text.
	 *
	 * @since 1.0.2
	 * @access protected
	 */
	protected function render_text() {
		$settings = $this->get_settings_for_display();
		$migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
		$is_new = empty( $settings['icon'] ) && Icons_Manager::is_migration_allowed();

		if ( ! $is_new && empty( $settings['icon_align'] ) ) {
			$settings['icon_align'] = $this->get_settings( 'icon_align' );
		}

		$this->add_render_attribute( [
			'content-wrapper' => [
				'class' => 'mn-button-content-wrapper',
			],
			'icon-align' => [
				'class' => [
					'mn-button-icon',
					'mn-align-icon-' . $settings['icon_align'],
				],
			],
			'text' => [
				'class' => 'mn-button-text',
			],
		] );

		// Icon animation classes
		if ( ! empty( $settings['icon_loop_animation'] ) ) {
			$this->add_render_attribute( 'icon-align', 'class', 'mn-icon-loop-' . $settings['icon_loop_animation'] );
		}
		if ( ! empty( $settings['icon_hover_animation'] ) ) {
			$this->add_render_attribute( 'icon-align', 'class', 'mn-icon-hover-' . $settings['icon_hover_animation'] );
		}

		?>
		<span <?php $this->print_render_attribute_string( 'content-wrapper' ); ?>>
			<?php if ( ! empty( $settings['icon'] ) || ! empty( $settings['selected_icon']['value'] ) ) : ?>
			<span <?php $this->print_render_attribute_string( 'icon-align' ); ?>>
				<?php if ( $is_new || $migrated ) :
					Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
				else : ?>
					<i class="<?php echo esc_attr( $settings['icon'] ); ?>" aria-hidden="true"></i>
				<?php endif; ?>
			</span>
			<?php endif; ?>
			<span <?php $this->print_render_attribute_string( 'text' ); ?>><?php $this->print_unescaped_setting( 'text' ); ?></span>
		</span>
		<?php
	}
}
