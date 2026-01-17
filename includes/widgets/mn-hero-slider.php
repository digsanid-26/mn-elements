<?php
namespace MN_Elements\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class MN_Hero_Slider extends Widget_Base {

	public function get_name() {
		return 'mn-hero-slider';
	}

	public function get_title() {
		return esc_html__( 'MN Hero Slider', 'mn-elements' );
	}

	public function get_icon() {
		return 'eicon-slider-full-screen';
	}

	public function get_categories() {
		return [ 'mn-elements' ];
	}

	public function get_keywords() {
		return [ 'hero', 'slider', 'carousel', 'banner', 'slideshow' ];
	}

	protected function register_controls() {

		// Hero Slides Management
		$this->start_controls_section(
			'section_hero_slides',
			[
				'label' => esc_html__( 'Hero Slides', 'mn-elements' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'hero_title',
			[
				'label' => esc_html__( 'Hero Title', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Hero Title', 'mn-elements' ),
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'hero_subtitle',
			[
				'label' => esc_html__( 'Hero Subtitle', 'mn-elements' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Hero subtitle description goes here', 'mn-elements' ),
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'hero_background',
			[
				'label' => esc_html__( 'Hero Background', 'mn-elements' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'button_1_heading',
			[
				'label' => esc_html__( 'Button 1', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'button_1_text',
			[
				'label' => esc_html__( 'Button 1 Text', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Learn More', 'mn-elements' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'button_1_link',
			[
				'label' => esc_html__( 'Button 1 Link', 'mn-elements' ),
				'type' => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'mn-elements' ),
				'default' => [
					'url' => '#',
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'button_1_icon',
			[
				'label' => esc_html__( 'Button 1 Icon', 'mn-elements' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
			]
		);

		$repeater->add_control(
			'button_2_heading',
			[
				'label' => esc_html__( 'Button 2', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'button_2_text',
			[
				'label' => esc_html__( 'Button 2 Text', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Get Started', 'mn-elements' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'button_2_link',
			[
				'label' => esc_html__( 'Button 2 Link', 'mn-elements' ),
				'type' => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'mn-elements' ),
				'default' => [
					'url' => '#',
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'button_2_icon',
			[
				'label' => esc_html__( 'Button 2 Icon', 'mn-elements' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
			]
		);

		$this->add_control(
			'hero_slides',
			[
				'label' => esc_html__( 'Hero Slides', 'mn-elements' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'hero_title' => esc_html__( 'Welcome to Our Website', 'mn-elements' ),
						'hero_subtitle' => esc_html__( 'Discover amazing features and services', 'mn-elements' ),
						'button_1_text' => esc_html__( 'Learn More', 'mn-elements' ),
						'button_2_text' => esc_html__( 'Get Started', 'mn-elements' ),
					],
					[
						'hero_title' => esc_html__( 'Build Your Dream', 'mn-elements' ),
						'hero_subtitle' => esc_html__( 'Start creating something amazing today', 'mn-elements' ),
						'button_1_text' => esc_html__( 'View Demo', 'mn-elements' ),
						'button_2_text' => esc_html__( 'Contact Us', 'mn-elements' ),
					],
				],
				'title_field' => '{{{ hero_title }}}',
			]
		);

		$this->end_controls_section();

		// Slider Settings
		$this->start_controls_section(
			'section_slider_settings',
			[
				'label' => esc_html__( 'Slider Settings', 'mn-elements' ),
			]
		);

		$this->add_responsive_control(
			'hero_height',
			[
				'label' => esc_html__( 'Hero Height', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh', '%' ],
				'range' => [
					'px' => [
						'min' => 300,
						'max' => 1200,
						'step' => 10,
					],
					'vh' => [
						'min' => 30,
						'max' => 100,
						'step' => 1,
					],
					'%' => [
						'min' => 30,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'vh',
					'size' => 80,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-hero-slide' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'transition_effect',
			[
				'label' => esc_html__( 'Transition Effect', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'slide',
				'options' => [
					'slide' => esc_html__( 'Slide', 'mn-elements' ),
					'fade' => esc_html__( 'Fade', 'mn-elements' ),
					'slide-fade' => esc_html__( 'Slide + Fade', 'mn-elements' ),
					'carousel' => esc_html__( 'Carousel', 'mn-elements' ),
					'zoom' => esc_html__( 'Zoom', 'mn-elements' ),
					'flip' => esc_html__( 'Flip', 'mn-elements' ),
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label' => esc_html__( 'Autoplay', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'mn-elements' ),
				'label_off' => esc_html__( 'No', 'mn-elements' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'label' => esc_html__( 'Autoplay Speed (ms)', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5000,
				'min' => 1000,
				'max' => 10000,
				'step' => 500,
				'condition' => [
					'autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'pause_on_hover',
			[
				'label' => esc_html__( 'Pause on Hover', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'mn-elements' ),
				'label_off' => esc_html__( 'No', 'mn-elements' ),
				'return_value' => 'yes',
				'default' => 'yes',
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
				'default' => 800,
				'min' => 300,
				'max' => 2000,
				'step' => 100,
			]
		);

		$this->add_control(
			'infinite_loop',
			[
				'label' => esc_html__( 'Infinite Loop', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'mn-elements' ),
				'label_off' => esc_html__( 'No', 'mn-elements' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->end_controls_section();

		// Navigation Settings
		$this->start_controls_section(
			'section_navigation',
			[
				'label' => esc_html__( 'Navigation', 'mn-elements' ),
			]
		);

		$this->add_control(
			'show_arrows',
			[
				'label' => esc_html__( 'Show Arrows', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'mn-elements' ),
				'label_off' => esc_html__( 'No', 'mn-elements' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_dots',
			[
				'label' => esc_html__( 'Show Dots', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'mn-elements' ),
				'label_off' => esc_html__( 'No', 'mn-elements' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->end_controls_section();

		// STYLE TAB
		// Hero Content Style
		$this->start_controls_section(
			'section_content_style',
			[
				'label' => esc_html__( 'Hero Content', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'content_alignment',
			[
				'label' => esc_html__( 'Content Alignment', 'mn-elements' ),
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
					'{{WRAPPER}} .mn-hero-content' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'content_vertical_position',
			[
				'label' => esc_html__( 'Vertical Position', 'mn-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Top', 'mn-elements' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => esc_html__( 'Middle', 'mn-elements' ),
						'icon' => 'eicon-v-align-middle',
					],
					'flex-end' => [
						'title' => esc_html__( 'Bottom', 'mn-elements' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .mn-hero-slide' => 'align-items: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_max_width',
			[
				'label' => esc_html__( 'Content Max Width', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 300,
						'max' => 1400,
						'step' => 10,
					],
					'%' => [
						'min' => 30,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 800,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-hero-content' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label' => esc_html__( 'Content Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'top' => 40,
					'right' => 40,
					'bottom' => 40,
					'left' => 40,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-hero-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'content_background',
			[
				'label' => esc_html__( 'Content Background', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-hero-content' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'content_border',
				'selector' => '{{WRAPPER}} .mn-hero-content',
			]
		);

		$this->add_responsive_control(
			'content_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-hero-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_box_shadow',
				'selector' => '{{WRAPPER}} .mn-hero-content',
			]
		);

		$this->end_controls_section();

		// Background Overlay Style
		$this->start_controls_section(
			'section_overlay_style',
			[
				'label' => esc_html__( 'Background Overlay', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'overlay_background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .mn-hero-overlay',
			]
		);

		$this->add_control(
			'overlay_opacity',
			[
				'label' => esc_html__( 'Overlay Opacity', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.1,
					],
				],
				'default' => [
					'size' => 0.5,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-hero-overlay' => 'opacity: {{SIZE}};',
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
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-hero-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .mn-hero-title',
			]
		);

		$this->add_responsive_control(
			'title_spacing',
			[
				'label' => esc_html__( 'Bottom Spacing', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
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
					'{{WRAPPER}} .mn-hero-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'title_text_shadow',
				'selector' => '{{WRAPPER}} .mn-hero-title',
			]
		);

		$this->end_controls_section();

		// Subtitle Style
		$this->start_controls_section(
			'section_subtitle_style',
			[
				'label' => esc_html__( 'Subtitle', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'subtitle_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-hero-subtitle' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'subtitle_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'selector' => '{{WRAPPER}} .mn-hero-subtitle',
			]
		);

		$this->add_responsive_control(
			'subtitle_spacing',
			[
				'label' => esc_html__( 'Bottom Spacing', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-hero-subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'subtitle_text_shadow',
				'selector' => '{{WRAPPER}} .mn-hero-subtitle',
			]
		);

		$this->end_controls_section();

		// Buttons Container Style
		$this->start_controls_section(
			'section_buttons_container_style',
			[
				'label' => esc_html__( 'Buttons Container', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'buttons_gap',
			[
				'label' => esc_html__( 'Gap Between Buttons', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-hero-buttons' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Button 1 Style
		$this->start_controls_section(
			'section_button_1_style',
			[
				'label' => esc_html__( 'Button 1', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_1_typography',
				'selector' => '{{WRAPPER}} .mn-hero-button-1',
			]
		);

		$this->add_responsive_control(
			'button_1_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'default' => [
					'top' => 15,
					'right' => 35,
					'bottom' => 15,
					'left' => 35,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-hero-button-1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'button_1_tabs' );

		$this->start_controls_tab(
			'button_1_normal',
			[
				'label' => esc_html__( 'Normal', 'mn-elements' ),
			]
		);

		$this->add_control(
			'button_1_text_color',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-hero-button-1' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-hero-button-1 svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_1_background',
			[
				'label' => esc_html__( 'Background', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#007cba',
				'selectors' => [
					'{{WRAPPER}} .mn-hero-button-1' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_1_hover',
			[
				'label' => esc_html__( 'Hover', 'mn-elements' ),
			]
		);

		$this->add_control(
			'button_1_hover_text_color',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-hero-button-1:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-hero-button-1:hover svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_1_hover_background',
			[
				'label' => esc_html__( 'Background', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-hero-button-1:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'button_1_border',
				'selector' => '{{WRAPPER}} .mn-hero-button-1',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_1_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 5,
					'right' => 5,
					'bottom' => 5,
					'left' => 5,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-hero-button-1' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_1_box_shadow',
				'selector' => '{{WRAPPER}} .mn-hero-button-1',
			]
		);

		$this->end_controls_section();

		// Button 2 Style
		$this->start_controls_section(
			'section_button_2_style',
			[
				'label' => esc_html__( 'Button 2', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_2_typography',
				'selector' => '{{WRAPPER}} .mn-hero-button-2',
			]
		);

		$this->add_responsive_control(
			'button_2_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'default' => [
					'top' => 15,
					'right' => 35,
					'bottom' => 15,
					'left' => 35,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-hero-button-2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'button_2_tabs' );

		$this->start_controls_tab(
			'button_2_normal',
			[
				'label' => esc_html__( 'Normal', 'mn-elements' ),
			]
		);

		$this->add_control(
			'button_2_text_color',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-hero-button-2' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-hero-button-2 svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_2_background',
			[
				'label' => esc_html__( 'Background', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'transparent',
				'selectors' => [
					'{{WRAPPER}} .mn-hero-button-2' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_2_hover',
			[
				'label' => esc_html__( 'Hover', 'mn-elements' ),
			]
		);

		$this->add_control(
			'button_2_hover_text_color',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-hero-button-2:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-hero-button-2:hover svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_2_hover_background',
			[
				'label' => esc_html__( 'Background', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-hero-button-2:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'button_2_border',
				'selector' => '{{WRAPPER}} .mn-hero-button-2',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_2_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 5,
					'right' => 5,
					'bottom' => 5,
					'left' => 5,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-hero-button-2' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_2_box_shadow',
				'selector' => '{{WRAPPER}} .mn-hero-button-2',
			]
		);

		$this->end_controls_section();

		// Arrows Style
		$this->start_controls_section(
			'section_arrows_style',
			[
				'label' => esc_html__( 'Navigation Arrows', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_arrows' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'arrows_size',
			[
				'label' => esc_html__( 'Size', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-hero-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: calc({{SIZE}}{{UNIT}} / 2);',
				],
			]
		);

		$this->add_responsive_control(
			'arrows_position_x',
			[
				'label' => esc_html__( 'Horizontal Position', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -200,
						'max' => 200,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-hero-arrow-prev' => 'left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-hero-arrow-next' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'arrows_position_y',
			[
				'label' => esc_html__( 'Vertical Position', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -200,
						'max' => 200,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-hero-arrow' => 'top: {{SIZE}}{{UNIT}}; transform: translateY(-50%);',
				],
			]
		);

		$this->start_controls_tabs( 'arrows_tabs' );

		$this->start_controls_tab(
			'arrows_normal',
			[
				'label' => esc_html__( 'Normal', 'mn-elements' ),
			]
		);

		$this->add_control(
			'arrows_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-hero-arrow' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'arrows_background',
			[
				'label' => esc_html__( 'Background', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0, 0, 0, 0.5)',
				'selectors' => [
					'{{WRAPPER}} .mn-hero-arrow' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'arrows_hover',
			[
				'label' => esc_html__( 'Hover', 'mn-elements' ),
			]
		);

		$this->add_control(
			'arrows_hover_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-hero-arrow:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'arrows_hover_background',
			[
				'label' => esc_html__( 'Background', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-hero-arrow:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'arrows_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .mn-hero-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Dots Style
		$this->start_controls_section(
			'section_dots_style',
			[
				'label' => esc_html__( 'Navigation Dots', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_dots' => 'yes',
				],
			]
		);

		$this->add_control(
			'dots_position',
			[
				'label' => esc_html__( 'Position', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'bottom',
				'options' => [
					'top' => esc_html__( 'Top', 'mn-elements' ),
					'bottom' => esc_html__( 'Bottom', 'mn-elements' ),
				],
			]
		);

		$this->add_responsive_control(
			'dots_offset',
			[
				'label' => esc_html__( 'Offset', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
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
					'{{WRAPPER}} .mn-hero-dots' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'dots_position' => 'bottom',
				],
			]
		);

		$this->add_responsive_control(
			'dots_offset_top',
			[
				'label' => esc_html__( 'Offset', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
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
					'{{WRAPPER}} .mn-hero-dots' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'dots_position' => 'top',
				],
			]
		);

		$this->add_responsive_control(
			'dots_size',
			[
				'label' => esc_html__( 'Size', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 30,
					],
				],
				'default' => [
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-hero-dot' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'dots_spacing',
			[
				'label' => esc_html__( 'Spacing', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 30,
					],
				],
				'default' => [
					'size' => 8,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-hero-dots' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'dots_tabs' );

		$this->start_controls_tab(
			'dots_normal',
			[
				'label' => esc_html__( 'Normal', 'mn-elements' ),
			]
		);

		$this->add_control(
			'dots_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(255, 255, 255, 0.5)',
				'selectors' => [
					'{{WRAPPER}} .mn-hero-dot' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'dots_active',
			[
				'label' => esc_html__( 'Active', 'mn-elements' ),
			]
		);

		$this->add_control(
			'dots_active_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-hero-dot.active' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'dots_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .mn-hero-dot' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$slides = $settings['hero_slides'];

		if ( empty( $slides ) ) {
			return;
		}

		$slider_settings = [
			'autoplay' => $settings['autoplay'] === 'yes',
			'autoplaySpeed' => $settings['autoplay_speed'],
			'pauseOnHover' => $settings['pause_on_hover'] === 'yes',
			'animationSpeed' => $settings['animation_speed'],
			'infinite' => $settings['infinite_loop'] === 'yes',
			'transitionEffect' => $settings['transition_effect'],
		];

		$this->add_render_attribute( 'wrapper', [
			'class' => 'mn-hero-slider-wrapper',
			'data-settings' => wp_json_encode( $slider_settings ),
		] );

		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<div class="mn-hero-slider">
				<?php foreach ( $slides as $index => $slide ) : ?>
					<div class="mn-hero-slide <?php echo $index === 0 ? 'active' : ''; ?>" style="background-image: url(<?php echo esc_url( $slide['hero_background']['url'] ); ?>);">
						<div class="mn-hero-overlay"></div>
						<div class="mn-hero-content">
							<?php if ( ! empty( $slide['hero_title'] ) ) : ?>
								<h2 class="mn-hero-title"><?php echo esc_html( $slide['hero_title'] ); ?></h2>
							<?php endif; ?>

							<?php if ( ! empty( $slide['hero_subtitle'] ) ) : ?>
								<div class="mn-hero-subtitle"><?php echo esc_html( $slide['hero_subtitle'] ); ?></div>
							<?php endif; ?>

							<?php if ( ! empty( $slide['button_1_text'] ) || ! empty( $slide['button_2_text'] ) ) : ?>
								<div class="mn-hero-buttons">
									<?php if ( ! empty( $slide['button_1_text'] ) ) : ?>
										<?php
										$button_1_link = $slide['button_1_link']['url'];
										$button_1_target = $slide['button_1_link']['is_external'] ? ' target="_blank"' : '';
										$button_1_nofollow = $slide['button_1_link']['nofollow'] ? ' rel="nofollow"' : '';
										?>
										<a href="<?php echo esc_url( $button_1_link ); ?>" class="mn-hero-button mn-hero-button-1"<?php echo $button_1_target . $button_1_nofollow; ?>>
											<?php if ( ! empty( $slide['button_1_icon']['value'] ) ) : ?>
												<?php \Elementor\Icons_Manager::render_icon( $slide['button_1_icon'], [ 'aria-hidden' => 'true' ] ); ?>
											<?php endif; ?>
											<span><?php echo esc_html( $slide['button_1_text'] ); ?></span>
										</a>
									<?php endif; ?>

									<?php if ( ! empty( $slide['button_2_text'] ) ) : ?>
										<?php
										$button_2_link = $slide['button_2_link']['url'];
										$button_2_target = $slide['button_2_link']['is_external'] ? ' target="_blank"' : '';
										$button_2_nofollow = $slide['button_2_link']['nofollow'] ? ' rel="nofollow"' : '';
										?>
										<a href="<?php echo esc_url( $button_2_link ); ?>" class="mn-hero-button mn-hero-button-2"<?php echo $button_2_target . $button_2_nofollow; ?>>
											<?php if ( ! empty( $slide['button_2_icon']['value'] ) ) : ?>
												<?php \Elementor\Icons_Manager::render_icon( $slide['button_2_icon'], [ 'aria-hidden' => 'true' ] ); ?>
											<?php endif; ?>
											<span><?php echo esc_html( $slide['button_2_text'] ); ?></span>
										</a>
									<?php endif; ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<?php if ( $settings['show_arrows'] === 'yes' ) : ?>
				<div class="mn-hero-navigation">
					<button class="mn-hero-arrow mn-hero-arrow-prev" aria-label="Previous Slide">
						<i class="eicon-chevron-left" aria-hidden="true"></i>
					</button>
					<button class="mn-hero-arrow mn-hero-arrow-next" aria-label="Next Slide">
						<i class="eicon-chevron-right" aria-hidden="true"></i>
					</button>
				</div>
			<?php endif; ?>

			<?php if ( $settings['show_dots'] === 'yes' ) : ?>
				<div class="mn-hero-dots <?php echo esc_attr( 'dots-' . $settings['dots_position'] ); ?>">
					<?php foreach ( $slides as $index => $slide ) : ?>
						<button class="mn-hero-dot <?php echo $index === 0 ? 'active' : ''; ?>" data-slide="<?php echo esc_attr( $index ); ?>" aria-label="Go to slide <?php echo esc_attr( $index + 1 ); ?>"></button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}
}
