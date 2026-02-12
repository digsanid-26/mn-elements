<?php
namespace MN_Elements\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MN_Mobile_Menu extends Widget_Base {

	public function get_name() {
		return 'mn-mbmenu';
	}

	public function get_title() {
		return esc_html__( 'MN Mobile Menu', 'mn-elements' );
	}

	public function get_icon() {
		return 'eicon-nav-menu';
	}

	public function get_categories() {
		return [ 'mn-elements' ];
	}

	public function get_keywords() {
		return [ 'menu', 'mobile', 'navigation', 'hamburger', 'nav', 'mn' ];
	}

	public function get_script_depends() {
		return [ 'mn-mbmenu' ];
	}

	public function get_style_depends() {
		return [ 'mn-mbmenu' ];
	}

	protected function register_controls() {
		$this->register_content_controls();
		$this->register_style_controls();
	}

	protected function register_content_controls() {
		$this->start_controls_section(
			'section_menu',
			[
				'label' => esc_html__( 'Menu', 'mn-elements' ),
			]
		);

		$menus = $this->get_available_menus();

		if ( ! empty( $menus ) ) {
			$this->add_control(
				'menu',
				[
					'label' => esc_html__( 'Select Menu', 'mn-elements' ),
					'type' => Controls_Manager::SELECT,
					'options' => $menus,
					'default' => array_keys( $menus )[0],
					'description' => esc_html__( 'Select a WordPress menu to display', 'mn-elements' ),
				]
			);
		} else {
			$this->add_control(
				'menu_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => sprintf(
						__( 'No menus found. Please create a menu in <a href="%s" target="_blank">WordPress Admin</a>.', 'mn-elements' ),
						admin_url( 'nav-menus.php' )
					),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
				]
			);
		}

		$this->end_controls_section();

		$this->start_controls_section(
			'section_hamburger',
			[
				'label' => esc_html__( 'Hamburger Icon', 'mn-elements' ),
			]
		);

		$this->add_control(
			'hamburger_style',
			[
				'label' => esc_html__( 'Icon Style', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default (3 Lines)', 'mn-elements' ),
					'arrow' => esc_html__( 'Arrow Transform', 'mn-elements' ),
					'cross' => esc_html__( 'Cross Transform', 'mn-elements' ),
					'minimal' => esc_html__( 'Minimal', 'mn-elements' ),
				],
			]
		);

		$this->add_responsive_control(
			'hamburger_position',
			[
				'label' => esc_html__( 'Position', 'mn-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
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
				],
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .mn-mbmenu-wrapper' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Layout', 'mn-elements' ),
			]
		);

		$this->add_control(
			'menu_position',
			[
				'label' => esc_html__( 'Menu Position', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => esc_html__( 'Left', 'mn-elements' ),
					'right' => esc_html__( 'Right', 'mn-elements' ),
					'full' => esc_html__( 'Full Screen', 'mn-elements' ),
				],
			]
		);

		$this->add_responsive_control(
			'menu_width',
			[
				'label' => esc_html__( 'Menu Width', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vw' ],
				'range' => [
					'px' => [
						'min' => 200,
						'max' => 600,
					],
					'%' => [
						'min' => 50,
						'max' => 100,
					],
					'vw' => [
						'min' => 50,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 320,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-mbmenu-panel' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'menu_position!' => 'full',
				],
			]
		);

		$this->add_control(
			'animation_type',
			[
				'label' => esc_html__( 'Animation Type', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'slide',
				'options' => [
					'slide' => esc_html__( 'Slide', 'mn-elements' ),
					'fade' => esc_html__( 'Fade', 'mn-elements' ),
					'slide-fade' => esc_html__( 'Slide + Fade', 'mn-elements' ),
				],
			]
		);

		$this->add_control(
			'animation_speed',
			[
				'label' => esc_html__( 'Animation Speed (ms)', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 300,
				'min' => 100,
				'max' => 1000,
				'step' => 50,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_header',
			[
				'label' => esc_html__( 'Menu Header', 'mn-elements' ),
			]
		);

		$this->add_control(
			'show_header',
			[
				'label' => esc_html__( 'Show Header', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'mn-elements' ),
				'label_off' => esc_html__( 'No', 'mn-elements' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'header_logo',
			[
				'label' => esc_html__( 'Logo', 'mn-elements' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => '',
				],
				'condition' => [
					'show_header' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'header_logo_width',
			[
				'label' => esc_html__( 'Logo Width', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 300,
					],
					'%' => [
						'min' => 20,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 150,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-mbmenu-header-logo img' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_header' => 'yes',
					'header_logo[url]!' => '',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_footer',
			[
				'label' => esc_html__( 'Menu Footer', 'mn-elements' ),
			]
		);

		$this->add_control(
			'show_footer',
			[
				'label' => esc_html__( 'Show Footer', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'mn-elements' ),
				'label_off' => esc_html__( 'No', 'mn-elements' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->add_control(
			'footer_content',
			[
				'label' => esc_html__( 'Footer Content', 'mn-elements' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => '',
				'condition' => [
					'show_footer' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_controls() {
		$this->start_controls_section(
			'section_hamburger_style',
			[
				'label' => esc_html__( 'Hamburger Icon', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'hamburger_size',
			[
				'label' => esc_html__( 'Icon Size', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 60,
					],
				],
				'default' => [
					'size' => 30,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-mbmenu-toggle-line' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'hamburger_spacing',
			[
				'label' => esc_html__( 'Line Spacing', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'default' => [
					'size' => 3,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-mbmenu-toggle' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hamburger_line_height',
			[
				'label' => esc_html__( 'Line Height', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 6,
					],
				],
				'default' => [
					'size' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-mbmenu-toggle-line' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hamburger_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .mn-mbmenu-toggle-line' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hamburger_hover_color',
			[
				'label' => esc_html__( 'Hover Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-mbmenu-toggle:hover .mn-mbmenu-toggle-line' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hamburger_heading_background',
			[
				'label' => esc_html__( 'Button Background', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'hamburger_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'transparent',
				'selectors' => [
					'{{WRAPPER}} .mn-mbmenu-toggle' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hamburger_bg_hover_color',
			[
				'label' => esc_html__( 'Background Hover Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-mbmenu-toggle:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hamburger_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-mbmenu-toggle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'hamburger_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-mbmenu-toggle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_panel_style',
			[
				'label' => esc_html__( 'Menu Panel', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'panel_background',
				'label' => esc_html__( 'Background', 'mn-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .mn-mbmenu-panel',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'default' => '#ffffff',
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'panel_box_shadow',
				'selector' => '{{WRAPPER}} .mn-mbmenu-panel',
			]
		);

		$this->add_responsive_control(
			'panel_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-mbmenu-panel' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_overlay_style',
			[
				'label' => esc_html__( 'Overlay', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'overlay_color',
			[
				'label' => esc_html__( 'Overlay Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0,0.5)',
				'selectors' => [
					'{{WRAPPER}} .mn-mbmenu-overlay' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_menu_items_style',
			[
				'label' => esc_html__( 'Menu Items', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'menu_typography',
				'label' => esc_html__( 'Typography', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-mbmenu-nav a',
			]
		);

		$this->start_controls_tabs( 'tabs_menu_item_style' );

		$this->start_controls_tab(
			'tab_menu_item_normal',
			[
				'label' => esc_html__( 'Normal', 'mn-elements' ),
			]
		);

		$this->add_control(
			'menu_item_color',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .mn-mbmenu-nav a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'menu_item_background',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-mbmenu-nav a' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_menu_item_hover',
			[
				'label' => esc_html__( 'Hover', 'mn-elements' ),
			]
		);

		$this->add_control(
			'menu_item_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-mbmenu-nav a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'menu_item_hover_background',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-mbmenu-nav a:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_menu_item_active',
			[
				'label' => esc_html__( 'Active', 'mn-elements' ),
			]
		);

		$this->add_control(
			'menu_item_active_color',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-mbmenu-nav .current-menu-item > a, {{WRAPPER}} .mn-mbmenu-nav .current-menu-ancestor > a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'menu_item_active_background',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-mbmenu-nav .current-menu-item > a, {{WRAPPER}} .mn-mbmenu-nav .current-menu-ancestor > a' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'menu_item_align',
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
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .mn-mbmenu-nav a' => 'text-align: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'menu_item_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-mbmenu-nav a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'menu_item_spacing',
			[
				'label' => esc_html__( 'Spacing Between Items', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mn-mbmenu-nav li' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'menu_item_border',
				'selector' => '{{WRAPPER}} .mn-mbmenu-nav a',
			]
		);

		$this->add_control(
			'menu_item_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-mbmenu-nav a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_submenu_style',
			[
				'label' => esc_html__( 'Sub Menu', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'submenu_typography',
				'label' => esc_html__( 'Typography', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-mbmenu-nav .sub-menu a',
			]
		);

		$this->add_control(
			'submenu_indent',
			[
				'label' => esc_html__( 'Indent', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-mbmenu-nav .sub-menu' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'submenu_color',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-mbmenu-nav .sub-menu a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'submenu_hover_color',
			[
				'label' => esc_html__( 'Hover Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-mbmenu-nav .sub-menu a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_header_style',
			[
				'label' => esc_html__( 'Header', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_header' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'header_background',
				'label' => esc_html__( 'Background', 'mn-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .mn-mbmenu-header',
			]
		);

		$this->add_responsive_control(
			'header_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-mbmenu-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'header_border',
				'selector' => '{{WRAPPER}} .mn-mbmenu-header',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_footer_style',
			[
				'label' => esc_html__( 'Footer', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_footer' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'footer_background',
				'label' => esc_html__( 'Background', 'mn-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .mn-mbmenu-footer',
			]
		);

		$this->add_control(
			'footer_text_color',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-mbmenu-footer' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'footer_typography',
				'label' => esc_html__( 'Typography', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-mbmenu-footer',
			]
		);

		$this->add_responsive_control(
			'footer_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-mbmenu-footer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'footer_border',
				'selector' => '{{WRAPPER}} .mn-mbmenu-footer',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_close_button_style',
			[
				'label' => esc_html__( 'Close Button', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'close_button_size',
			[
				'label' => esc_html__( 'Size', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 60,
					],
				],
				'default' => [
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-mbmenu-close' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'close_button_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .mn-mbmenu-close' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'close_button_hover_color',
			[
				'label' => esc_html__( 'Hover Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-mbmenu-close:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function get_available_menus() {
		$menus = wp_get_nav_menus();
		$options = [];

		foreach ( $menus as $menu ) {
			$options[ $menu->term_id ] = $menu->name;
		}

		return $options;
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$menu_id = $settings['menu'];

		if ( empty( $menu_id ) ) {
			return;
		}

		$this->add_render_attribute( 'wrapper', 'class', 'mn-mbmenu-wrapper' );
		$this->add_render_attribute( 'toggle', 'class', 'mn-mbmenu-toggle' );
		$this->add_render_attribute( 'toggle', 'class', 'mn-mbmenu-toggle-' . $settings['hamburger_style'] );
		$this->add_render_attribute( 'toggle', 'aria-label', esc_attr__( 'Toggle Menu', 'mn-elements' ) );
		$this->add_render_attribute( 'toggle', 'role', 'button' );
		$this->add_render_attribute( 'toggle', 'tabindex', '0' );

		$this->add_render_attribute( 'panel', 'class', 'mn-mbmenu-panel' );
		$this->add_render_attribute( 'panel', 'class', 'mn-mbmenu-position-' . $settings['menu_position'] );
		$this->add_render_attribute( 'panel', 'class', 'mn-mbmenu-animation-' . $settings['animation_type'] );
		$this->add_render_attribute( 'panel', 'data-animation-speed', $settings['animation_speed'] );

		?>
		<style>.mn-mbmenu-panel:not(.active){visibility:hidden!important;overflow:hidden!important}.mn-mbmenu-overlay:not(.active){visibility:hidden!important;opacity:0!important}.mn-mbmenu-wrapper:not(.mn-mbmenu-ready) .mn-mbmenu-panel{visibility:hidden!important;overflow:hidden!important}</style>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<button <?php $this->print_render_attribute_string( 'toggle' ); ?>>
				<span class="mn-mbmenu-toggle-line"></span>
				<span class="mn-mbmenu-toggle-line"></span>
				<span class="mn-mbmenu-toggle-line"></span>
			</button>

			<div class="mn-mbmenu-overlay"></div>

			<div <?php $this->print_render_attribute_string( 'panel' ); ?>>
				<button class="mn-mbmenu-close" aria-label="<?php echo esc_attr__( 'Close Menu', 'mn-elements' ); ?>">
					<span>&times;</span>
				</button>

				<?php if ( 'yes' === $settings['show_header'] ) : ?>
					<div class="mn-mbmenu-header">
						<?php if ( ! empty( $settings['header_logo']['url'] ) ) : ?>
							<div class="mn-mbmenu-header-logo">
								<img src="<?php echo esc_url( $settings['header_logo']['url'] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<div class="mn-mbmenu-content">
					<nav class="mn-mbmenu-nav">
						<?php
						wp_nav_menu( [
							'menu' => $menu_id,
							'container' => false,
							'menu_class' => 'mn-mbmenu-list',
							'fallback_cb' => false,
							'walker' => new \Walker_Nav_Menu(),
						] );
						?>
					</nav>
				</div>

				<?php if ( 'yes' === $settings['show_footer'] && ! empty( $settings['footer_content'] ) ) : ?>
					<div class="mn-mbmenu-footer">
						<?php echo wp_kses_post( $settings['footer_content'] ); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}
