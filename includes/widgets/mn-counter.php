<?php
namespace MN_Elements\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Icons_Manager;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * MN Counter Widget
 *
 * Enhanced counter widget with description field and grid/list layout options
 *
 * @since 1.0.4
 */
class MN_Counter extends Widget_Base {

	/**
	 * Get widget name.
	 */
	public function get_name() {
		return 'mn-counter';
	}

	/**
	 * Get widget title.
	 */
	public function get_title() {
		return esc_html__( 'MN Counter', 'mn-elements' );
	}

	/**
	 * Get widget icon.
	 */
	public function get_icon() {
		return 'eicon-counter';
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
		return [ 'counter', 'number', 'stats', 'count', 'mn', 'grid', 'list' ];
	}

	/**
	 * Get script dependencies.
	 */
	public function get_script_depends() {
		return [ 'mn-counter' ];
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
		// Counter Section
		$this->start_controls_section(
			'section_counter',
			[
				'label' => esc_html__( 'Counter', 'mn-elements' ),
			]
		);

		$this->add_control(
			'starting_number',
			[
				'label' => esc_html__( 'Starting Number', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
			]
		);

		$this->add_control(
			'ending_number',
			[
				'label' => esc_html__( 'Ending Number', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 100,
			]
		);

		$this->add_control(
			'prefix',
			[
				'label' => esc_html__( 'Number Prefix', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => esc_html__( '$', 'mn-elements' ),
			]
		);

		$this->add_control(
			'suffix',
			[
				'label' => esc_html__( 'Number Suffix', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => esc_html__( '+', 'mn-elements' ),
			]
		);

		$this->add_control(
			'duration',
			[
				'label' => esc_html__( 'Animation Duration', 'mn-elements' ) . ' (ms)',
				'type' => Controls_Manager::NUMBER,
				'default' => 2000,
				'min' => 100,
				'step' => 100,
			]
		);

		$this->add_control(
			'thousand_separator',
			[
				'label' => esc_html__( 'Thousand Separator', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => esc_html__( 'Show', 'mn-elements' ),
				'label_off' => esc_html__( 'Hide', 'mn-elements' ),
			]
		);

		$this->add_control(
			'thousand_separator_char',
			[
				'label' => esc_html__( 'Separator', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'condition' => [
					'thousand_separator' => 'yes',
				],
				'options' => [
					'' => 'Default',
					'.' => 'Dot',
					' ' => 'Space',
					'_' => 'Underline',
					"'" => 'Apostrophe',
				],
			]
		);

		$this->end_controls_section();

		// Content Section
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'mn-elements' ),
			]
		);

		$this->add_control(
			'selected_icon',
			[
				'label' => esc_html__( 'Icon', 'mn-elements' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'Cool Number', 'mn-elements' ),
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label' => esc_html__( 'Title HTML Tag', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h3',
				'condition' => [
					'title!' => '',
				],
			]
		);

		$this->add_control(
			'description',
			[
				'label' => esc_html__( 'Description', 'mn-elements' ),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default' => esc_html__( 'Add a brief description for this counter.', 'mn-elements' ),
				'placeholder' => esc_html__( 'Enter your description here...', 'mn-elements' ),
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
			'layout_style',
			[
				'label' => esc_html__( 'Layout Style', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'grid',
				'options' => [
					'grid' => esc_html__( 'Grid Style', 'mn-elements' ),
					'list' => esc_html__( 'List Style', 'mn-elements' ),
				],
				'prefix_class' => 'mn-counter-layout-',
			]
		);

		$this->add_control(
			'icon_position',
			[
				'label' => esc_html__( 'Icon Position', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'top',
				'options' => [
					'top' => esc_html__( 'Top', 'mn-elements' ),
					'left' => esc_html__( 'Left', 'mn-elements' ),
					'right' => esc_html__( 'Right', 'mn-elements' ),
				],
				'prefix_class' => 'mn-counter-icon-position-',
				'condition' => [
					'layout_style' => 'grid',
					'selected_icon[value]!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'text_align',
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
					'{{WRAPPER}} .mn-counter' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'layout_style' => 'grid',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register style controls.
	 */
	protected function register_style_controls() {
		// Number Style
		$this->start_controls_section(
			'section_number_style',
			[
				'label' => esc_html__( 'Number', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'number_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-counter-number-wrapper' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'number_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .mn-counter-number-wrapper',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Stroke::get_type(),
			[
				'name' => 'number_stroke',
				'selector' => '{{WRAPPER}} .mn-counter-number-wrapper',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'number_shadow',
				'selector' => '{{WRAPPER}} .mn-counter-number-wrapper',
			]
		);

		$this->add_responsive_control(
			'number_spacing',
			[
				'label' => esc_html__( 'Bottom Spacing', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-counter-number-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Icon Style
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

		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-counter-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-counter-icon svg' => 'fill: {{VALUE}};',
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
				'default' => [
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-counter-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-counter-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
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
				'default' => [
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}}.mn-counter-icon-position-top .mn-counter-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.mn-counter-icon-position-left .mn-counter-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.mn-counter-icon-position-right .mn-counter-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_rotate',
			[
				'label' => esc_html__( 'Rotate', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'deg' ],
				'default' => [
					'size' => 0,
					'unit' => 'deg',
				],
				'range' => [
					'deg' => [
						'min' => 0,
						'max' => 360,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mn-counter-icon i, {{WRAPPER}} .mn-counter-icon svg' => 'transform: rotate({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->end_controls_section();

		// Title Style
		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Title', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'title!' => '',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-counter-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'selector' => '{{WRAPPER}} .mn-counter-title',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Stroke::get_type(),
			[
				'name' => 'title_stroke',
				'selector' => '{{WRAPPER}} .mn-counter-title',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'title_shadow',
				'selector' => '{{WRAPPER}} .mn-counter-title',
			]
		);

		$this->add_responsive_control(
			'title_spacing',
			[
				'label' => esc_html__( 'Bottom Spacing', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-counter-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
				'condition' => [
					'description!' => '',
				],
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-counter-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'selector' => '{{WRAPPER}} .mn-counter-description',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'description_shadow',
				'selector' => '{{WRAPPER}} .mn-counter-description',
			]
		);

		$this->end_controls_section();

		// Layout Style
		$this->start_controls_section(
			'section_layout_style',
			[
				'label' => esc_html__( 'Layout', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'counter_gap',
			[
				'label' => esc_html__( 'Column Gap', 'mn-elements' ),
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
					'{{WRAPPER}}.mn-counter-layout-list .mn-counter' => 'gap: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'layout_style' => 'list',
				],
			]
		);

		$this->add_responsive_control(
			'counter_column_width',
			[
				'label' => esc_html__( 'Counter Column Width', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 10,
						'max' => 80,
					],
				],
				'default' => [
					'size' => 30,
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}}.mn-counter-layout-list .mn-counter-number-column' => 'flex: 0 0 {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.mn-counter-layout-list .mn-counter-content-column' => 'flex: 1;',
				],
				'condition' => [
					'layout_style' => 'list',
				],
			]
		);

		$this->add_responsive_control(
			'counter_column_alignment',
			[
				'label' => esc_html__( 'Counter Column Alignment', 'mn-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Top', 'mn-elements' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'mn-elements' ),
						'icon' => 'eicon-v-align-middle',
					],
					'flex-end' => [
						'title' => esc_html__( 'Bottom', 'mn-elements' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}}.mn-counter-layout-list .mn-counter-number-column' => 'align-items: {{VALUE}};',
				],
				'condition' => [
					'layout_style' => 'list',
				],
			]
		);

		$this->add_responsive_control(
			'content_column_alignment',
			[
				'label' => esc_html__( 'Content Column Alignment', 'mn-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Top', 'mn-elements' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'mn-elements' ),
						'icon' => 'eicon-v-align-middle',
					],
					'flex-end' => [
						'title' => esc_html__( 'Bottom', 'mn-elements' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}}.mn-counter-layout-list .mn-counter-content-column' => 'justify-content: {{VALUE}};',
				],
				'condition' => [
					'layout_style' => 'list',
				],
			]
		);

		$this->add_responsive_control(
			'counter_text_align',
			[
				'label' => esc_html__( 'Counter Text Align', 'mn-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'mn-elements' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'mn-elements' ),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'Right', 'mn-elements' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}}.mn-counter-layout-list .mn-counter-number-column' => 'display: flex; align-items: center;',
					'{{WRAPPER}}.mn-counter-layout-list .mn-counter-number-wrapper' => 'justify-content: {{VALUE}}; width: 100%;',
				],
				'condition' => [
					'layout_style' => 'list',
				],
			]
		);

		$this->add_responsive_control(
			'content_text_align',
			[
				'label' => esc_html__( 'Content Text Align', 'mn-elements' ),
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
					'{{WRAPPER}}.mn-counter-layout-list .mn-counter-content-column' => 'text-align: {{VALUE}};',
					'{{WRAPPER}}.mn-counter-layout-list .mn-counter-title' => 'text-align: {{VALUE}};',
					'{{WRAPPER}}.mn-counter-layout-list .mn-counter-description' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'layout_style' => 'list',
				],
			]
		);

		$this->add_responsive_control(
			'counter_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-counter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
	
		$this->add_render_attribute( 'counter', 'class', 'mn-counter' );
		$this->add_render_attribute( 'counter-number', 'class', 'mn-counter-number-wrapper' );
	
		$this->add_render_attribute(
			'counter-value',
			[
				'class' => 'mn-counter-number',
				'data-duration' => $settings['duration'],
				'data-to-value' => $settings['ending_number'],
				'data-from-value' => $settings['starting_number'],
			]
		);
	
		if ( ! empty( $settings['thousand_separator'] ) ) {
			$delimiter = empty( $settings['thousand_separator_char'] ) ? ',' : $settings['thousand_separator_char'];
			$this->add_render_attribute( 'counter-value', 'data-delimiter', $delimiter );
		}
	
		$title_tag = Utils::validate_html_tag( $settings['title_tag'] );
		$has_icon = ! empty( $settings['selected_icon']['value'] );
		?>
		<div <?php $this->print_render_attribute_string( 'counter' ); ?>>
			<?php if ( $settings['layout_style'] === 'list' ) : ?>
				<div class="mn-counter-number-column">
					<?php if ( $has_icon ) : ?>
						<div class="mn-counter-icon">
							<?php Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] ); ?>
						</div>
					<?php endif; ?>
					<div <?php $this->print_render_attribute_string( 'counter-number' ); ?>>
						<?php if ( ! empty( $settings['prefix'] ) ) : ?>
							<span class="mn-counter-number-prefix"><?php echo esc_html( $settings['prefix'] ); ?></span>
						<?php endif; ?>
						<span <?php $this->print_render_attribute_string( 'counter-value' ); ?>><?php echo esc_html( $settings['starting_number'] ); ?></span>
						<?php if ( ! empty( $settings['suffix'] ) ) : ?>
							<span class="mn-counter-number-suffix"><?php echo esc_html( $settings['suffix'] ); ?></span>
						<?php endif; ?>
					</div>
				</div>
				<div class="mn-counter-content-column">
					<?php if ( ! empty( $settings['title'] ) ) : ?>
						<<?php Utils::print_validated_html_tag( $title_tag ); ?> class="mn-counter-title"><?php echo esc_html( $settings['title'] ); ?></<?php Utils::print_validated_html_tag( $title_tag ); ?>>
					<?php endif; ?>
					<?php if ( ! empty( $settings['description'] ) ) : ?>
						<div class="mn-counter-description"><?php echo wp_kses_post( $settings['description'] ); ?></div>
					<?php endif; ?>
				</div>
			<?php else : ?>
				<?php if ( $has_icon ) : ?>
					<div class="mn-counter-icon">
						<?php Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] ); ?>
					</div>
				<?php endif; ?>
				<div <?php $this->print_render_attribute_string( 'counter-number' ); ?>>
					<?php if ( ! empty( $settings['prefix'] ) ) : ?>
						<span class="mn-counter-number-prefix"><?php echo esc_html( $settings['prefix'] ); ?></span>
					<?php endif; ?>
					<span <?php $this->print_render_attribute_string( 'counter-value' ); ?>><?php echo esc_html( $settings['starting_number'] ); ?></span>
					<?php if ( ! empty( $settings['suffix'] ) ) : ?>
						<span class="mn-counter-number-suffix"><?php echo esc_html( $settings['suffix'] ); ?></span>
					<?php endif; ?>
				</div>
				<?php if ( ! empty( $settings['title'] ) ) : ?>
					<<?php Utils::print_validated_html_tag( $title_tag ); ?> class="mn-counter-title"><?php echo esc_html( $settings['title'] ); ?></<?php Utils::print_validated_html_tag( $title_tag ); ?>>
				<?php endif; ?>
				<?php if ( ! empty( $settings['description'] ) ) : ?>
					<div class="mn-counter-description"><?php echo wp_kses_post( $settings['description'] ); ?></div>
				<?php endif; ?>
			<?php endif; ?>
		</div>
		<?php
	}
}