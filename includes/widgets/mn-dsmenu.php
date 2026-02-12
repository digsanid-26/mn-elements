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

class MN_Desktop_Menu extends Widget_Base {

	public function get_name() {
		return 'mn-dsmenu';
	}

	public function get_title() {
		return esc_html__( 'MN Desktop Menu', 'mn-elements' );
	}

	public function get_icon() {
		return 'eicon-nav-menu';
	}

	public function get_categories() {
		return [ 'mn-elements' ];
	}

	public function get_keywords() {
		return [ 'menu', 'desktop', 'navigation', 'nav', 'header', 'horizontal', 'mn' ];
	}

	public function get_script_depends() {
		return [ 'mn-dsmenu' ];
	}

	public function get_style_depends() {
		return [ 'mn-dsmenu' ];
	}

	protected function register_controls() {
		$this->register_content_controls();
		$this->register_style_controls();
	}

	protected function register_content_controls() {

		// ── Section: Menu ──
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
					'label'       => esc_html__( 'Select Menu', 'mn-elements' ),
					'type'        => Controls_Manager::SELECT,
					'options'     => $menus,
					'default'     => array_keys( $menus )[0],
					'description' => sprintf(
						__( 'Go to <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'mn-elements' ),
						admin_url( 'nav-menus.php' )
					),
				]
			);
		} else {
			$this->add_control(
				'menu_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw'  => sprintf(
						__( 'No menus found. <a href="%s" target="_blank">Create a menu</a>.', 'mn-elements' ),
						admin_url( 'nav-menus.php' )
					),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
				]
			);
		}

		$this->end_controls_section();

		// ── Section: Layout ──
		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Layout', 'mn-elements' ),
			]
		);

		$this->add_control(
			'layout',
			[
				'label'   => esc_html__( 'Layout', 'mn-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => [
					'horizontal' => esc_html__( 'Horizontal', 'mn-elements' ),
					'vertical'   => esc_html__( 'Vertical', 'mn-elements' ),
				],
				'prefix_class' => 'mn-dsmenu-layout-',
			]
		);

		$this->add_responsive_control(
			'align_items',
			[
				'label'   => esc_html__( 'Alignment', 'mn-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Start', 'mn-elements' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'mn-elements' ),
						'icon'  => 'eicon-h-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'End', 'mn-elements' ),
						'icon'  => 'eicon-h-align-right',
					],
					'space-between' => [
						'title' => esc_html__( 'Stretch', 'mn-elements' ),
						'icon'  => 'eicon-h-align-stretch',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mn-dsmenu-nav > ul' => 'justify-content: {{VALUE}};',
				],
				'condition' => [
					'layout' => 'horizontal',
				],
			]
		);

		$this->end_controls_section();

		// ── Section: Pointer ──
		$this->start_controls_section(
			'section_pointer',
			[
				'label' => esc_html__( 'Pointer & Animation', 'mn-elements' ),
			]
		);

		$this->add_control(
			'pointer',
			[
				'label'   => esc_html__( 'Pointer', 'mn-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'underline',
				'options' => [
					'none'       => esc_html__( 'None', 'mn-elements' ),
					'underline'  => esc_html__( 'Underline', 'mn-elements' ),
					'overline'   => esc_html__( 'Overline', 'mn-elements' ),
					'double'     => esc_html__( 'Double Line', 'mn-elements' ),
					'framed'     => esc_html__( 'Framed', 'mn-elements' ),
					'background' => esc_html__( 'Background', 'mn-elements' ),
				],
				'prefix_class' => 'mn-dsmenu-pointer-',
			]
		);

		$this->add_control(
			'animation_line',
			[
				'label'   => esc_html__( 'Animation', 'mn-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'fade',
				'options' => [
					'fade'     => esc_html__( 'Fade', 'mn-elements' ),
					'slide'    => esc_html__( 'Slide', 'mn-elements' ),
					'grow'     => esc_html__( 'Grow', 'mn-elements' ),
					'drop-in'  => esc_html__( 'Drop In', 'mn-elements' ),
					'drop-out' => esc_html__( 'Drop Out', 'mn-elements' ),
					'none'     => esc_html__( 'None', 'mn-elements' ),
				],
				'condition' => [
					'pointer' => [ 'underline', 'overline', 'double' ],
				],
				'prefix_class' => 'mn-dsmenu-animation-',
			]
		);

		$this->add_control(
			'animation_framed',
			[
				'label'   => esc_html__( 'Animation', 'mn-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'fade',
				'options' => [
					'fade'    => esc_html__( 'Fade', 'mn-elements' ),
					'grow'    => esc_html__( 'Grow', 'mn-elements' ),
					'shrink'  => esc_html__( 'Shrink', 'mn-elements' ),
					'draw'    => esc_html__( 'Draw', 'mn-elements' ),
					'corners' => esc_html__( 'Corners', 'mn-elements' ),
					'none'    => esc_html__( 'None', 'mn-elements' ),
				],
				'condition' => [
					'pointer' => 'framed',
				],
				'prefix_class' => 'mn-dsmenu-animation-',
			]
		);

		$this->add_control(
			'animation_background',
			[
				'label'   => esc_html__( 'Animation', 'mn-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'fade',
				'options' => [
					'fade'       => esc_html__( 'Fade', 'mn-elements' ),
					'grow'       => esc_html__( 'Grow', 'mn-elements' ),
					'shrink'     => esc_html__( 'Shrink', 'mn-elements' ),
					'sweep-left' => esc_html__( 'Sweep Left', 'mn-elements' ),
					'sweep-right'=> esc_html__( 'Sweep Right', 'mn-elements' ),
					'none'       => esc_html__( 'None', 'mn-elements' ),
				],
				'condition' => [
					'pointer' => 'background',
				],
				'prefix_class' => 'mn-dsmenu-animation-',
			]
		);

		$this->end_controls_section();

		// ── Section: Submenu ──
		$this->start_controls_section(
			'section_submenu',
			[
				'label' => esc_html__( 'Submenu', 'mn-elements' ),
			]
		);

		$this->add_control(
			'submenu_indicator',
			[
				'label'   => esc_html__( 'Submenu Indicator', 'mn-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'chevron',
				'options' => [
					'none'    => esc_html__( 'None', 'mn-elements' ),
					'chevron' => esc_html__( 'Chevron', 'mn-elements' ),
					'plus'    => esc_html__( 'Plus', 'mn-elements' ),
					'caret'   => esc_html__( 'Caret', 'mn-elements' ),
				],
			]
		);

		$this->add_control(
			'submenu_animation',
			[
				'label'   => esc_html__( 'Dropdown Animation', 'mn-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'fade-up',
				'options' => [
					'none'      => esc_html__( 'None', 'mn-elements' ),
					'fade'      => esc_html__( 'Fade', 'mn-elements' ),
					'fade-up'   => esc_html__( 'Fade Up', 'mn-elements' ),
					'fade-down' => esc_html__( 'Fade Down', 'mn-elements' ),
					'slide'     => esc_html__( 'Slide Down', 'mn-elements' ),
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_controls() {

		// ── Style: Main Menu ──
		$this->start_controls_section(
			'section_style_main_menu',
			[
				'label' => esc_html__( 'Main Menu', 'mn-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'menu_typography',
				'selector' => '{{WRAPPER}} .mn-dsmenu-nav > ul > li > a',
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
				'label'     => esc_html__( 'Text Color', 'mn-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333333',
				'selectors' => [
					'{{WRAPPER}} .mn-dsmenu-nav > ul > li > a' => 'color: {{VALUE}};',
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
				'label'     => esc_html__( 'Text Color', 'mn-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-dsmenu-nav > ul > li > a:hover, {{WRAPPER}} .mn-dsmenu-nav > ul > li:hover > a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'pointer_color',
			[
				'label'     => esc_html__( 'Pointer Color', 'mn-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#557df3',
				'selectors' => [
					'{{WRAPPER}} .mn-dsmenu-nav > ul > li > a::before, {{WRAPPER}} .mn-dsmenu-nav > ul > li > a::after' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.mn-dsmenu-pointer-framed .mn-dsmenu-nav > ul > li > a::before' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'pointer!' => 'none',
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
				'label'     => esc_html__( 'Text Color', 'mn-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-dsmenu-nav > ul > li.current-menu-item > a, {{WRAPPER}} .mn-dsmenu-nav > ul > li.current-menu-ancestor > a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'pointer_active_color',
			[
				'label'     => esc_html__( 'Pointer Color', 'mn-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-dsmenu-nav > ul > li.current-menu-item > a::before, {{WRAPPER}} .mn-dsmenu-nav > ul > li.current-menu-item > a::after, {{WRAPPER}} .mn-dsmenu-nav > ul > li.current-menu-ancestor > a::before, {{WRAPPER}} .mn-dsmenu-nav > ul > li.current-menu-ancestor > a::after' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.mn-dsmenu-pointer-framed .mn-dsmenu-nav > ul > li.current-menu-item > a::before, {{WRAPPER}}.mn-dsmenu-pointer-framed .mn-dsmenu-nav > ul > li.current-menu-ancestor > a::before' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'pointer!' => 'none',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'menu_item_padding',
			[
				'label'      => esc_html__( 'Padding', 'mn-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'separator'  => 'before',
				'selectors'  => [
					'{{WRAPPER}} .mn-dsmenu-nav > ul > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'menu_item_spacing',
			[
				'label' => esc_html__( 'Space Between', 'mn-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mn-dsmenu-nav > ul' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'pointer_width',
			[
				'label' => esc_html__( 'Pointer Width', 'mn-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 8,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mn-dsmenu-nav > ul > li > a::before, {{WRAPPER}} .mn-dsmenu-nav > ul > li > a::after' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.mn-dsmenu-pointer-framed .mn-dsmenu-nav > ul > li > a::before' => 'border-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'pointer!' => [ 'none', 'background' ],
				],
			]
		);

		$this->end_controls_section();

		// ── Style: Dropdown / Submenu ──
		$this->start_controls_section(
			'section_style_dropdown',
			[
				'label' => esc_html__( 'Dropdown', 'mn-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'dropdown_typography',
				'selector' => '{{WRAPPER}} .mn-dsmenu-nav .sub-menu a',
			]
		);

		$this->start_controls_tabs( 'tabs_dropdown_style' );

		$this->start_controls_tab(
			'tab_dropdown_normal',
			[
				'label' => esc_html__( 'Normal', 'mn-elements' ),
			]
		);

		$this->add_control(
			'dropdown_color',
			[
				'label'     => esc_html__( 'Text Color', 'mn-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333333',
				'selectors' => [
					'{{WRAPPER}} .mn-dsmenu-nav .sub-menu a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'dropdown_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'mn-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-dsmenu-nav .sub-menu' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_dropdown_hover',
			[
				'label' => esc_html__( 'Hover', 'mn-elements' ),
			]
		);

		$this->add_control(
			'dropdown_hover_color',
			[
				'label'     => esc_html__( 'Text Color', 'mn-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-dsmenu-nav .sub-menu a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'dropdown_hover_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'mn-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f5f5f5',
				'selectors' => [
					'{{WRAPPER}} .mn-dsmenu-nav .sub-menu a:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_dropdown_active',
			[
				'label' => esc_html__( 'Active', 'mn-elements' ),
			]
		);

		$this->add_control(
			'dropdown_active_color',
			[
				'label'     => esc_html__( 'Text Color', 'mn-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-dsmenu-nav .sub-menu .current-menu-item > a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'dropdown_active_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'mn-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-dsmenu-nav .sub-menu .current-menu-item > a' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'dropdown_padding',
			[
				'label'      => esc_html__( 'Item Padding', 'mn-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'separator'  => 'before',
				'selectors'  => [
					'{{WRAPPER}} .mn-dsmenu-nav .sub-menu a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'dropdown_width',
			[
				'label' => esc_html__( 'Dropdown Width', 'mn-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 500,
					],
				],
				'default' => [
					'size' => 220,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-dsmenu-nav .sub-menu' => 'min-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'dropdown_border',
				'selector' => '{{WRAPPER}} .mn-dsmenu-nav .sub-menu',
			]
		);

		$this->add_control(
			'dropdown_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'mn-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .mn-dsmenu-nav .sub-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'dropdown_box_shadow',
				'selector' => '{{WRAPPER}} .mn-dsmenu-nav .sub-menu',
			]
		);

		$this->add_control(
			'dropdown_top_distance',
			[
				'label' => esc_html__( 'Top Distance', 'mn-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mn-dsmenu-nav > ul > li > .sub-menu' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// ── Style: Submenu Indicator ──
		$this->start_controls_section(
			'section_style_indicator',
			[
				'label'     => esc_html__( 'Submenu Indicator', 'mn-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'submenu_indicator!' => 'none',
				],
			]
		);

		$this->add_control(
			'indicator_color',
			[
				'label'     => esc_html__( 'Color', 'mn-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-dsmenu-indicator' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'indicator_size',
			[
				'label' => esc_html__( 'Size', 'mn-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 20,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mn-dsmenu-indicator svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'indicator_gap',
			[
				'label' => esc_html__( 'Gap', 'mn-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mn-dsmenu-indicator' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function get_available_menus() {
		$menus   = wp_get_nav_menus();
		$options = [];

		foreach ( $menus as $menu ) {
			$options[ $menu->term_id ] = $menu->name;
		}

		return $options;
	}

	protected function get_indicator_svg( $type ) {
		switch ( $type ) {
			case 'chevron':
				return '<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>';
			case 'plus':
				return '<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>';
			case 'caret':
				return '<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="currentColor"><path d="M7 10l5 5 5-5z"/></svg>';
			default:
				return '';
		}
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['menu'] ) ) {
			return;
		}

		$menu_id          = $settings['menu'];
		$indicator_type   = $settings['submenu_indicator'];
		$submenu_anim     = $settings['submenu_animation'];
		$indicator_svg    = $this->get_indicator_svg( $indicator_type );

		$nav_attrs  = ' data-indicator="' . esc_attr( $indicator_type ) . '"';
		$nav_attrs .= ' data-submenu-animation="' . esc_attr( $submenu_anim ) . '"';

		if ( ! empty( $indicator_svg ) ) {
			$nav_attrs .= ' data-indicator-svg="' . esc_attr( $indicator_svg ) . '"';
		}

		?>
		<nav class="mn-dsmenu-nav"<?php echo $nav_attrs; ?> role="navigation" aria-label="<?php esc_attr_e( 'Desktop Navigation', 'mn-elements' ); ?>">
			<?php
			wp_nav_menu( [
				'menu'        => $menu_id,
				'container'   => false,
				'menu_class'  => 'mn-dsmenu-list',
				'fallback_cb' => false,
				'depth'       => 0,
			] );
			?>
		</nav>
		<?php
	}
}
