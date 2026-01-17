<?php
namespace MN_Elements\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * MN Testimony Widget
 *
 * Testimony listing widget with advanced customization
 *
 * @since 1.8.3
 */
class MN_Testimony extends Widget_Base {

	public function get_name() {
		return 'mn-testimony';
	}

	public function get_title() {
		return esc_html__( 'MN Testimony', 'mn-elements' );
	}

	public function get_icon() {
		return 'eicon-testimonial';
	}

	public function get_categories() {
		return [ 'mn-elements' ];
	}

	public function get_keywords() {
		return [ 'testimony', 'testimonial', 'review', 'quote', 'carousel', 'slider', 'mn' ];
	}

	public function get_script_depends() {
		return [ 'mn-testimony' ];
	}

	public function get_style_depends() {
		return [ 'mn-testimony' ];
	}

	protected function register_controls() {
		$this->register_content_controls();
		$this->register_layout_controls();
		$this->register_carousel_controls();
		$this->register_style_controls();
	}

	/**
	 * Register content controls
	 */
	protected function register_content_controls() {
		$this->start_controls_section(
			'section_testimonies',
			[
				'label' => esc_html__( 'Testimonies', 'mn-elements' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'content',
			[
				'label' => esc_html__( 'Testimony Content', 'mn-elements' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis.', 'mn-elements' ),
				'rows' => 5,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'user_name',
			[
				'label' => esc_html__( 'User Name', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'John Doe', 'mn-elements' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'user_info',
			[
				'label' => esc_html__( 'User Info', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'CEO, Company Name', 'mn-elements' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'user_avatar',
			[
				'label' => esc_html__( 'User Avatar', 'mn-elements' ),
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
			'testimonies',
			[
				'label' => esc_html__( 'Testimony Items', 'mn-elements' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'content' => esc_html__( 'Amazing service! The team was professional and delivered beyond expectations.', 'mn-elements' ),
						'user_name' => esc_html__( 'John Doe', 'mn-elements' ),
						'user_info' => esc_html__( 'CEO, Tech Company', 'mn-elements' ),
					],
					[
						'content' => esc_html__( 'I highly recommend their services. Quality work and great communication.', 'mn-elements' ),
						'user_name' => esc_html__( 'Jane Smith', 'mn-elements' ),
						'user_info' => esc_html__( 'Marketing Director', 'mn-elements' ),
					],
					[
						'content' => esc_html__( 'Outstanding results! They truly understand customer needs.', 'mn-elements' ),
						'user_name' => esc_html__( 'Mike Johnson', 'mn-elements' ),
						'user_info' => esc_html__( 'Business Owner', 'mn-elements' ),
					],
				],
				'title_field' => '{{{ user_name }}}',
			]
		);

		$this->end_controls_section();

		// Theme Version Section
		$this->start_controls_section(
			'section_theme',
			[
				'label' => esc_html__( 'Theme Version', 'mn-elements' ),
			]
		);

		$this->add_control(
			'enable_theme',
			[
				'label' => esc_html__( 'Enable Theme', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
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
				'condition' => [
					'enable_theme' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register layout controls
	 */
	protected function register_layout_controls() {
		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Layout', 'mn-elements' ),
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
					'{{WRAPPER}} .mn-testimony-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				],
				'condition' => [
					'display_type' => 'grid',
				],
			]
		);

		$this->add_responsive_control(
			'gap',
			[
				'label' => esc_html__( 'Gap', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-testimony-grid' => 'gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-testimony-carousel .mn-testimony-item' => 'margin: 0 calc({{SIZE}}{{UNIT}} / 2);',
				],
			]
		);

		$this->add_control(
			'heading_element_order',
			[
				'label' => esc_html__( 'Element Order', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_position',
			[
				'label' => esc_html__( 'Content Position', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'top',
				'options' => [
					'top' => esc_html__( 'Top', 'mn-elements' ),
					'bottom' => esc_html__( 'Bottom', 'mn-elements' ),
					'left' => esc_html__( 'Left', 'mn-elements' ),
					'right' => esc_html__( 'Right', 'mn-elements' ),
				],
			]
		);

		$this->add_control(
			'alignment',
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
					'{{WRAPPER}} .mn-testimony-item' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'heading_user_style',
			[
				'label' => esc_html__( 'User Layout', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'user_layout',
			[
				'label' => esc_html__( 'User Layout Style', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => [
					'horizontal' => esc_html__( 'Horizontal (Avatar Left)', 'mn-elements' ),
					'horizontal-right' => esc_html__( 'Horizontal (Avatar Right)', 'mn-elements' ),
					'vertical' => esc_html__( 'Vertical (Avatar Top)', 'mn-elements' ),
				],
			]
		);

		$this->add_control(
			'heading_visibility',
			[
				'label' => esc_html__( 'Element Visibility', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'show_content',
			[
				'label' => esc_html__( 'Show Content', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_avatar',
			[
				'label' => esc_html__( 'Show Avatar', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_user_name',
			[
				'label' => esc_html__( 'Show User Name', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_user_info',
			[
				'label' => esc_html__( 'Show User Info', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_quote_icon',
			[
				'label' => esc_html__( 'Show Quote Icon', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register carousel controls
	 */
	protected function register_carousel_controls() {
		$this->start_controls_section(
			'section_carousel',
			[
				'label' => esc_html__( 'Carousel Settings', 'mn-elements' ),
				'condition' => [
					'display_type' => 'carousel',
				],
			]
		);

		$this->add_responsive_control(
			'slides_to_show',
			[
				'label' => esc_html__( 'Slides to Show', 'mn-elements' ),
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
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label' => esc_html__( 'Autoplay', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'label' => esc_html__( 'Autoplay Speed (ms)', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 3000,
				'min' => 1000,
				'max' => 10000,
				'step' => 500,
				'condition' => [
					'autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'animation_speed',
			[
				'label' => esc_html__( 'Animation Speed (ms)', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 500,
				'min' => 100,
				'max' => 2000,
				'step' => 100,
			]
		);

		$this->add_control(
			'infinite_loop',
			[
				'label' => esc_html__( 'Infinite Loop', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'pause_on_hover',
			[
				'label' => esc_html__( 'Pause on Hover', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_arrows',
			[
				'label' => esc_html__( 'Show Arrows', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_dots',
			[
				'label' => esc_html__( 'Show Dots', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register style controls
	 */
	protected function register_style_controls() {
		// Item Style
		$this->start_controls_section(
			'section_item_style',
			[
				'label' => esc_html__( 'Item', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'item_background_color',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-testimony-item' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'item_hover_background_color',
			[
				'label' => esc_html__( 'Hover Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-testimony-item:hover' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .mn-testimony-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'item_border',
				'selector' => '{{WRAPPER}} .mn-testimony-item',
			]
		);

		$this->add_control(
			'item_border_hover_color',
			[
				'label' => esc_html__( 'Border Hover Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-testimony-item:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'item_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-testimony-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_box_shadow',
				'selector' => '{{WRAPPER}} .mn-testimony-item',
			]
		);

		$this->end_controls_section();

		// Content Style
		$this->start_controls_section(
			'section_content_style',
			[
				'label' => esc_html__( 'Content', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'content_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-testimony-content' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'content_hover_color',
			[
				'label' => esc_html__( 'Hover Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-testimony-item:hover .mn-testimony-content' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .mn-testimony-content',
			]
		);

		$this->add_responsive_control(
			'content_spacing',
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
					'{{WRAPPER}} .mn-testimony-content' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Quote Icon Style
		$this->start_controls_section(
			'section_quote_style',
			[
				'label' => esc_html__( 'Quote Icon', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_quote_icon' => 'yes',
				],
			]
		);

		$this->add_control(
			'quote_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-testimony-quote' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'quote_hover_color',
			[
				'label' => esc_html__( 'Hover Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-testimony-item:hover .mn-testimony-quote' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'quote_size',
			[
				'label' => esc_html__( 'Size', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-testimony-quote' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'quote_spacing',
			[
				'label' => esc_html__( 'Spacing', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mn-testimony-quote' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Avatar Style
		$this->start_controls_section(
			'section_avatar_style',
			[
				'label' => esc_html__( 'Avatar', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_avatar' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'avatar_size',
			[
				'label' => esc_html__( 'Size', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 30,
						'max' => 200,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 60,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-testimony-avatar' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'avatar_border',
				'selector' => '{{WRAPPER}} .mn-testimony-avatar',
			]
		);

		$this->add_control(
			'avatar_border_hover_color',
			[
				'label' => esc_html__( 'Border Hover Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-testimony-item:hover .mn-testimony-avatar' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'avatar_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => '50',
					'right' => '50',
					'bottom' => '50',
					'left' => '50',
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-testimony-avatar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'avatar_box_shadow',
				'selector' => '{{WRAPPER}} .mn-testimony-avatar',
			]
		);

		$this->end_controls_section();

		// User Name Style
		$this->start_controls_section(
			'section_user_name_style',
			[
				'label' => esc_html__( 'User Name', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_user_name' => 'yes',
				],
			]
		);

		$this->add_control(
			'user_name_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-testimony-user-name' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'user_name_hover_color',
			[
				'label' => esc_html__( 'Hover Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-testimony-item:hover .mn-testimony-user-name' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'user_name_typography',
				'selector' => '{{WRAPPER}} .mn-testimony-user-name',
			]
		);

		$this->add_responsive_control(
			'user_name_spacing',
			[
				'label' => esc_html__( 'Spacing', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mn-testimony-user-name' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// User Info Style
		$this->start_controls_section(
			'section_user_info_style',
			[
				'label' => esc_html__( 'User Info', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_user_info' => 'yes',
				],
			]
		);

		$this->add_control(
			'user_info_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-testimony-user-info' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'user_info_hover_color',
			[
				'label' => esc_html__( 'Hover Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-testimony-item:hover .mn-testimony-user-info' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'user_info_typography',
				'selector' => '{{WRAPPER}} .mn-testimony-user-info',
			]
		);

		$this->end_controls_section();

		// Navigation Style
		$this->start_controls_section(
			'section_navigation_style',
			[
				'label' => esc_html__( 'Navigation', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'display_type' => 'carousel',
				],
			]
		);

		$this->add_control(
			'heading_arrows',
			[
				'label' => esc_html__( 'Arrows', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'show_arrows' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'arrow_size',
			[
				'label' => esc_html__( 'Arrow Size', 'mn-elements' ),
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
					'{{WRAPPER}} .mn-testimony-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'arrow_color',
			[
				'label' => esc_html__( 'Arrow Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-testimony-arrow' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'arrow_hover_color',
			[
				'label' => esc_html__( 'Arrow Hover Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-testimony-arrow:hover' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'arrow_background_color',
			[
				'label' => esc_html__( 'Arrow Background', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-testimony-arrow' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'show_arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'arrow_hover_background_color',
			[
				'label' => esc_html__( 'Arrow Hover Background', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-testimony-arrow:hover' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'show_arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'arrow_border_radius',
			[
				'label' => esc_html__( 'Arrow Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-testimony-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'show_arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'heading_dots',
			[
				'label' => esc_html__( 'Dots', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_dots' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'dot_size',
			[
				'label' => esc_html__( 'Dot Size', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 30,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-testimony-dot' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_dots' => 'yes',
				],
			]
		);

		$this->add_control(
			'dot_color',
			[
				'label' => esc_html__( 'Dot Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-testimony-dot' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'show_dots' => 'yes',
				],
			]
		);

		$this->add_control(
			'dot_active_color',
			[
				'label' => esc_html__( 'Dot Active Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-testimony-dot.active' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'show_dots' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'dots_spacing',
			[
				'label' => esc_html__( 'Dots Spacing', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mn-testimony-dots' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_dots' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['testimonies'] ) ) {
			return;
		}

		// Theme class
		$theme_class = '';
		if ( isset( $settings['enable_theme'] ) && $settings['enable_theme'] === 'yes' ) {
			$theme_class = 'mn-theme-' . $settings['theme_version'];
		}

		// Content position class
		$content_position_class = 'mn-content-' . $settings['content_position'];

		// User layout class
		$user_layout_class = 'mn-user-' . $settings['user_layout'];

		// Display type class
		$display_class = $settings['display_type'] === 'carousel' ? 'mn-testimony-carousel' : 'mn-testimony-grid';

		$wrapper_classes = [
			'mn-testimony-wrapper',
			$theme_class,
			$content_position_class,
			$user_layout_class,
		];

		// Carousel data attributes
		$carousel_data = '';
		if ( $settings['display_type'] === 'carousel' ) {
			$carousel_data = sprintf(
				'data-autoplay="%s" data-autoplay-speed="%d" data-animation-speed="%d" data-infinite="%s" data-pause-hover="%s" data-slides="%s" data-slides-tablet="%s" data-slides-mobile="%s"',
				$settings['autoplay'] === 'yes' ? 'true' : 'false',
				$settings['autoplay_speed'],
				$settings['animation_speed'],
				$settings['infinite_loop'] === 'yes' ? 'true' : 'false',
				$settings['pause_on_hover'] === 'yes' ? 'true' : 'false',
				$settings['slides_to_show'],
				isset( $settings['slides_to_show_tablet'] ) ? $settings['slides_to_show_tablet'] : '2',
				isset( $settings['slides_to_show_mobile'] ) ? $settings['slides_to_show_mobile'] : '1'
			);
		}

		?>
		<div class="<?php echo esc_attr( implode( ' ', array_filter( $wrapper_classes ) ) ); ?>">
			<?php if ( $settings['display_type'] === 'carousel' && $settings['show_arrows'] === 'yes' ) : ?>
				<button class="mn-testimony-arrow mn-testimony-prev" aria-label="Previous">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<polyline points="15,18 9,12 15,6"></polyline>
					</svg>
				</button>
			<?php endif; ?>

			<div class="<?php echo esc_attr( $display_class ); ?>" <?php echo $carousel_data; ?>>
				<?php if ( $settings['display_type'] === 'carousel' ) : ?>
					<div class="mn-testimony-track">
				<?php endif; ?>

				<?php foreach ( $settings['testimonies'] as $index => $item ) : ?>
					<?php $this->render_testimony_item( $item, $settings, $index ); ?>
				<?php endforeach; ?>

				<?php if ( $settings['display_type'] === 'carousel' ) : ?>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( $settings['display_type'] === 'carousel' && $settings['show_arrows'] === 'yes' ) : ?>
				<button class="mn-testimony-arrow mn-testimony-next" aria-label="Next">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<polyline points="9,6 15,12 9,18"></polyline>
					</svg>
				</button>
			<?php endif; ?>

			<?php if ( $settings['display_type'] === 'carousel' && $settings['show_dots'] === 'yes' ) : ?>
				<div class="mn-testimony-dots">
					<?php foreach ( $settings['testimonies'] as $index => $item ) : ?>
						<button class="mn-testimony-dot <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo esc_attr( $index ); ?>"></button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Render single testimony item
	 */
	private function render_testimony_item( $item, $settings, $index ) {
		?>
		<article class="mn-testimony-item">
			<?php if ( $settings['show_quote_icon'] === 'yes' ) : ?>
				<div class="mn-testimony-quote">
					<svg viewBox="0 0 24 24" fill="currentColor">
						<path d="M6 17h3l2-4V7H5v6h3zm8 0h3l2-4V7h-6v6h3z"/>
					</svg>
				</div>
			<?php endif; ?>

			<?php if ( $settings['content_position'] === 'top' || $settings['content_position'] === 'left' ) : ?>
				<?php $this->render_content( $item, $settings ); ?>
				<?php $this->render_user( $item, $settings ); ?>
			<?php else : ?>
				<?php $this->render_user( $item, $settings ); ?>
				<?php $this->render_content( $item, $settings ); ?>
			<?php endif; ?>
		</article>
		<?php
	}

	/**
	 * Render testimony content
	 */
	private function render_content( $item, $settings ) {
		if ( $settings['show_content'] !== 'yes' || empty( $item['content'] ) ) {
			return;
		}
		?>
		<div class="mn-testimony-content">
			<?php echo esc_html( $item['content'] ); ?>
		</div>
		<?php
	}

	/**
	 * Render user section
	 */
	private function render_user( $item, $settings ) {
		$show_avatar = $settings['show_avatar'] === 'yes' && ! empty( $item['user_avatar']['url'] );
		$show_name = $settings['show_user_name'] === 'yes' && ! empty( $item['user_name'] );
		$show_info = $settings['show_user_info'] === 'yes' && ! empty( $item['user_info'] );

		if ( ! $show_avatar && ! $show_name && ! $show_info ) {
			return;
		}
		?>
		<div class="mn-testimony-user">
			<?php if ( $show_avatar ) : ?>
				<div class="mn-testimony-avatar">
					<img src="<?php echo esc_url( $item['user_avatar']['url'] ); ?>" alt="<?php echo esc_attr( $item['user_name'] ); ?>">
				</div>
			<?php endif; ?>

			<?php if ( $show_name || $show_info ) : ?>
				<div class="mn-testimony-user-details">
					<?php if ( $show_name ) : ?>
						<div class="mn-testimony-user-name"><?php echo esc_html( $item['user_name'] ); ?></div>
					<?php endif; ?>

					<?php if ( $show_info ) : ?>
						<div class="mn-testimony-user-info"><?php echo esc_html( $item['user_info'] ); ?></div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}
}
