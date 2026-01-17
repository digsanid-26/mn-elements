<?php
namespace MN_Elements\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * MN SlideSwipe Widget
 *
 * Template slider widget with swipe functionality
 *
 * @since 1.2.6
 */
class MN_SlideSwipe extends Widget_Base {

	/**
	 * Get widget name.
	 */
	public function get_name() {
		return 'mn-slideswipe';
	}

	/**
	 * Get widget title.
	 */
	public function get_title() {
		return esc_html__( 'MN SlideSwipe', 'mn-elements' );
	}

	/**
	 * Get widget icon.
	 */
	public function get_icon() {
		return 'eicon-slider-push';
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
		return [ 'slide', 'swipe', 'slider', 'template', 'carousel', 'mn' ];
	}

	/**
	 * Get style dependencies.
	 */
	public function get_style_depends() {
		return [ 'mn-slideswipe', 'swiper' ];
	}

	/**
	 * Get script dependencies.
	 */
	public function get_script_depends() {
		return [ 'mn-slideswipe', 'swiper' ];
	}

	/**
	 * Get all Elementor templates
	 */
	private function get_elementor_templates() {
		$templates = [];
		
		$posts = get_posts([
			'post_type' => 'elementor_library',
			'post_status' => 'publish',
			'numberposts' => -1,
			'meta_query' => [
				[
					'key' => '_elementor_template_type',
					'value' => ['page', 'section', 'container'],
					'compare' => 'IN'
				]
			]
		]);

		foreach ($posts as $post) {
			$templates[$post->ID] = $post->post_title;
		}

		return $templates;
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
		// Slide Management Section
		$this->start_controls_section(
			'section_slide_management',
			[
				'label' => esc_html__( 'Slide Management', 'mn-elements' ),
			]
		);

		// Slides Repeater
		$repeater = new Repeater();

		$repeater->add_control(
			'slide_title',
			[
				'label' => esc_html__( 'Slide Title', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Slide Title', 'mn-elements' ),
				'placeholder' => esc_html__( 'Enter slide title', 'mn-elements' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'content_type',
			[
				'label' => esc_html__( 'Content Type', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'template',
				'options' => [
					'template' => esc_html__( 'Elementor Template', 'mn-elements' ),
					'image' => esc_html__( 'Image', 'mn-elements' ),
				],
			]
		);

		$repeater->add_control(
			'template_id',
			[
				'label' => esc_html__( 'Select Template', 'mn-elements' ),
				'type' => Controls_Manager::SELECT2,
				'options' => $this->get_elementor_templates(),
				'label_block' => true,
				'multiple' => false,
				'description' => esc_html__( 'Choose an Elementor template for this slide', 'mn-elements' ),
				'condition' => [
					'content_type' => 'template',
				],
			]
		);

		$repeater->add_control(
			'slide_image',
			[
				'label' => esc_html__( 'Choose Image', 'mn-elements' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'content_type' => 'image',
				],
			]
		);

		$repeater->add_control(
			'slide_background_color',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .mn-slide-content' => 'background-color: {{VALUE}};',
				],
				'description' => esc_html__( 'Optional background color for this slide. Leave empty for transparent.', 'mn-elements' ),
			]
		);

		$repeater->add_control(
			'slide_link_heading',
			[
				'label' => esc_html__( 'Link Settings', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'slide_link',
			[
				'label' => esc_html__( 'Link', 'mn-elements' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'mn-elements' ),
				'description' => esc_html__( 'Make the entire slide clickable. Supports dynamic content.', 'mn-elements' ),
			]
		);

		$this->add_control(
			'slides',
			[
				'label' => esc_html__( 'Slides', 'mn-elements' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'slide_title' => esc_html__( 'Slide #1', 'mn-elements' ),
					],
					[
						'slide_title' => esc_html__( 'Slide #2', 'mn-elements' ),
					],
				],
				'title_field' => '{{{ slide_title }}}',
			]
		);

		$this->end_controls_section();

		// Slider Settings Section
		$this->start_controls_section(
			'section_slider_settings',
			[
				'label' => esc_html__( 'Slider Settings', 'mn-elements' ),
			]
		);

		$this->add_control(
			'effect',
			[
				'label' => esc_html__( 'Transition Effect', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'slide',
				'options' => [
					'slide' => esc_html__( 'Slide', 'mn-elements' ),
					'fade' => esc_html__( 'Fade', 'mn-elements' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'slides_to_show',
			[
				'label' => esc_html__( 'Slides to Show', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 3,
				'min' => 1,
				'max' => 10,
				'step' => 1,
				'condition' => [
					'effect' => 'slide',
				],
			]
		);

		$this->add_control(
			'slides_to_scroll',
			[
				'label' => esc_html__( 'Slides to Scroll', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1,
				'min' => 1,
				'max' => 10,
				'step' => 1,
				'condition' => [
					'effect' => 'slide',
				],
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
				'default' => 'no',
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
				'step' => 100,
				'condition' => [
					'autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'speed',
			[
				'label' => esc_html__( 'Animation Speed (ms)', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 500,
				'min' => 100,
				'max' => 2000,
				'step' => 50,
			]
		);

		$this->add_control(
			'loop',
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

		// Navigation Section
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

		$this->add_control(
			'dots_position',
			[
				'label' => esc_html__( 'Dots Position', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'bottom',
				'options' => [
					'top' => esc_html__( 'Top', 'mn-elements' ),
					'bottom' => esc_html__( 'Bottom', 'mn-elements' ),
				],
				'condition' => [
					'show_dots' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_progress',
			[
				'label' => esc_html__( 'Show Autoplay Progress', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'mn-elements' ),
				'label_off' => esc_html__( 'No', 'mn-elements' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'description' => esc_html__( 'Show progress animation on active dot when autoplay is enabled', 'mn-elements' ),
				'condition' => [
					'show_dots' => 'yes',
					'autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'progress_style',
			[
				'label' => esc_html__( 'Progress Style', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Fill (Default)', 'mn-elements' ),
					'circular' => esc_html__( 'Circular Ring', 'mn-elements' ),
				],
				'condition' => [
					'show_dots' => 'yes',
					'autoplay' => 'yes',
					'show_progress' => 'yes',
				],
			]
		);

		$this->add_control(
			'dots_position_heading',
			[
				'label' => esc_html__( 'Dots Positioning', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_dots' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'dots_position_x',
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
					'unit' => '%',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination' => 'left: {{SIZE}}{{UNIT}}; transform: translateX(-50%);',
				],
				'condition' => [
					'show_dots' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'dots_position_y',
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
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_dots' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		// Responsive Settings
		$this->start_controls_section(
			'section_responsive',
			[
				'label' => esc_html__( 'Responsive Settings', 'mn-elements' ),
			]
		);

		$this->add_control(
			'slides_to_show_tablet',
			[
				'label' => esc_html__( 'Slides to Show (Tablet Portrait)', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => '',
				'min' => 1,
				'max' => 10,
				'step' => 1,
				'placeholder' => esc_html__( 'Same as desktop', 'mn-elements' ),
				'description' => esc_html__( 'Tablet portrait mode (768px - 1023px). Leave empty to use desktop value.', 'mn-elements' ),
				'condition' => [
					'effect' => 'slide',
				],
			]
		);

		$this->add_control(
			'slides_to_show_tablet_landscape',
			[
				'label' => esc_html__( 'Slides to Show (Tablet Landscape)', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => '',
				'min' => 1,
				'max' => 10,
				'step' => 1,
				'placeholder' => esc_html__( 'Same as desktop', 'mn-elements' ),
				'description' => esc_html__( 'Tablet landscape mode (1024px - 1199px). Leave empty to use desktop value.', 'mn-elements' ),
				'condition' => [
					'effect' => 'slide',
				],
			]
		);

		$this->add_control(
			'slides_to_show_mobile',
			[
				'label' => esc_html__( 'Slides to Show (Mobile Portrait)', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => '',
				'min' => 1,
				'max' => 10,
				'step' => 1,
				'placeholder' => esc_html__( 'Same as desktop', 'mn-elements' ),
				'description' => esc_html__( 'Mobile portrait mode (up to 767px). Leave empty to use desktop value.', 'mn-elements' ),
				'condition' => [
					'effect' => 'slide',
				],
			]
		);

		$this->add_control(
			'slides_to_show_mobile_landscape',
			[
				'label' => esc_html__( 'Slides to Show (Mobile Landscape)', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => '',
				'min' => 1,
				'max' => 10,
				'step' => 1,
				'placeholder' => esc_html__( 'Same as desktop', 'mn-elements' ),
				'description' => esc_html__( 'Mobile landscape mode (480px - 767px). Leave empty to use desktop value.', 'mn-elements' ),
				'condition' => [
					'effect' => 'slide',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register style controls.
	 */
	protected function register_style_controls() {
		// Slider Container Style
		$this->start_controls_section(
			'section_slider_style',
			[
				'label' => esc_html__( 'Slider Container', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'slider_height',
			[
				'label' => esc_html__( 'Height', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh', '%' ],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1000,
					],
					'vh' => [
						'min' => 10,
						'max' => 100,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 400,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-slideswipe-container' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'slide_spacing',
			[
				'label' => esc_html__( 'Slide Spacing', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-slide' => 'padding-right: calc({{SIZE}}{{UNIT}} / 2); padding-left: calc({{SIZE}}{{UNIT}} / 2);',
					'{{WRAPPER}} .swiper-wrapper' => 'margin-right: calc(-{{SIZE}}{{UNIT}} / 2); margin-left: calc(-{{SIZE}}{{UNIT}} / 2);',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'slider_background',
				'label' => esc_html__( 'Background', 'mn-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .mn-slideswipe-container',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'slider_border',
				'label' => esc_html__( 'Border', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-slideswipe-container',
			]
		);

		$this->add_responsive_control(
			'slider_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .mn-slideswipe-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'slider_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-slideswipe-container',
			]
		);

		$this->end_controls_section();

		// Image Slides Style
		$this->start_controls_section(
			'section_image_style',
			[
				'label' => esc_html__( 'Image Slides', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
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
						'max' => 2000,
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
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-slide-image' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_max_width',
			[
				'label' => esc_html__( 'Max Width', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vw' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
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
					'{{WRAPPER}} .mn-slide-image' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_height',
			[
				'label' => esc_html__( 'Height', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vh', 'auto' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'vh' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'auto',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-slide-image' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_object_fit',
			[
				'label' => esc_html__( 'Object Fit', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'cover',
				'options' => [
					'cover' => esc_html__( 'Cover', 'mn-elements' ),
					'contain' => esc_html__( 'Contain', 'mn-elements' ),
					'fill' => esc_html__( 'Fill', 'mn-elements' ),
					'none' => esc_html__( 'None', 'mn-elements' ),
				],
				'selectors' => [
					'{{WRAPPER}} .mn-slide-image' => 'object-fit: {{VALUE}};',
				],
				'description' => esc_html__( 'Only works when Height is set to a fixed value (not auto).', 'mn-elements' ),
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .mn-slide-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'label' => esc_html__( 'Border', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-slide-image',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'image_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-slide-image',
			]
		);

		$this->add_control(
			'image_opacity',
			[
				'label' => esc_html__( 'Opacity', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.1,
					],
				],
				'default' => [
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-slide-image' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_control(
			'image_hover_heading',
			[
				'label' => esc_html__( 'Hover Effects', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'image_hover_opacity',
			[
				'label' => esc_html__( 'Hover Opacity', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-slide:hover .mn-slide-image' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_control(
			'image_hover_scale',
			[
				'label' => esc_html__( 'Hover Scale', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0.5,
						'max' => 2,
						'step' => 0.1,
					],
				],
				'default' => [
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-slide:hover .mn-slide-image' => 'transform: scale({{SIZE}});',
				],
			]
		);

		$this->add_control(
			'image_transition',
			[
				'label' => esc_html__( 'Transition Duration', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'ms' ],
				'range' => [
					'ms' => [
						'min' => 0,
						'max' => 2000,
						'step' => 100,
					],
				],
				'default' => [
					'unit' => 'ms',
					'size' => 300,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-slide-image' => 'transition: all {{SIZE}}{{UNIT}} ease;',
				],
			]
		);

		$this->end_controls_section();

		// Navigation Arrows Style
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
					'unit' => 'px',
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-slideswipe-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: calc({{SIZE}}{{UNIT}} / 2);',
				],
			]
		);

		$this->add_responsive_control(
			'arrows_position_x',
			[
				'label' => esc_html__( 'Position X', 'mn-elements' ),
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-slideswipe-arrow-prev' => 'left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-slideswipe-arrow-next' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'arrows_position_y',
			[
				'label' => esc_html__( 'Position Y', 'mn-elements' ),
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
					'{{WRAPPER}} .mn-slideswipe-arrow' => 'top: {{SIZE}}{{UNIT}}; transform: translateY(-50%);',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_arrows_style' );

		$this->start_controls_tab(
			'tab_arrows_normal',
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
					'{{WRAPPER}} .mn-slideswipe-arrow' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'arrows_background',
			[
				'label' => esc_html__( 'Background', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0,0.5)',
				'selectors' => [
					'{{WRAPPER}} .mn-slideswipe-arrow' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_arrows_hover',
			[
				'label' => esc_html__( 'Hover', 'mn-elements' ),
			]
		);

		$this->add_control(
			'arrows_hover_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-slideswipe-arrow:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'arrows_hover_background',
			[
				'label' => esc_html__( 'Background', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0,0.8)',
				'selectors' => [
					'{{WRAPPER}} .mn-slideswipe-arrow:hover' => 'background-color: {{VALUE}};',
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
				'selectors' => [
					'{{WRAPPER}} .mn-slideswipe-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
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
						'max' => 20,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'margin: 0 {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_dots_style' );

		$this->start_controls_tab(
			'tab_dots_normal',
			[
				'label' => esc_html__( 'Normal', 'mn-elements' ),
			]
		);

		$this->add_control(
			'dots_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0,0.3)',
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_dots_active',
			[
				'label' => esc_html__( 'Active', 'mn-elements' ),
			]
		);

		$this->add_control(
			'dots_active_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#007cba',
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'progress_bar_heading',
			[
				'label' => esc_html__( 'Progress Bar', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'autoplay' => 'yes',
					'show_progress' => 'yes',
				],
			]
		);

		$this->add_control(
			'progress_bar_color',
			[
				'label' => esc_html__( 'Progress Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-slideswipe-wrapper.mn-autoplay-active .swiper-pagination-bullet-active::before' => 'background-color: {{VALUE}};',
				],
				'description' => esc_html__( 'Leave empty to use the same color as active dot', 'mn-elements' ),
				'condition' => [
					'autoplay' => 'yes',
					'show_progress' => 'yes',
				],
			]
		);

		$this->add_control(
			'progress_bar_opacity',
			[
				'label' => esc_html__( 'Progress Opacity', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.1,
					],
				],
				'default' => [
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-slideswipe-wrapper.mn-autoplay-active .swiper-pagination-bullet-active::before' => 'opacity: {{SIZE}};',
				],
				'condition' => [
					'autoplay' => 'yes',
					'show_progress' => 'yes',
				],
			]
		);

		$this->add_control(
			'progress_bg_opacity',
			[
				'label' => esc_html__( 'Background Opacity', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.1,
					],
				],
				'default' => [
					'size' => 0.6,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-slideswipe-wrapper.mn-autoplay-active .swiper-pagination-bullet-active::after' => 'opacity: {{SIZE}};',
				],
				'description' => esc_html__( 'Opacity of the background layer behind progress bar', 'mn-elements' ),
				'condition' => [
					'autoplay' => 'yes',
					'show_progress' => 'yes',
					'progress_style' => 'default',
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
		$slides = $settings['slides'];

		if ( empty( $slides ) ) {
			return;
		}

		$widget_id = $this->get_id();
		
		// Base swiper settings
		$swiper_settings = [
			'effect' => $settings['effect'],
			'speed' => $settings['speed'],
			'loop' => $settings['loop'] === 'yes',
			'autoplay' => $settings['autoplay'] === 'yes' ? [
				'delay' => $settings['autoplay_speed'],
				'disableOnInteraction' => false,
			] : false,
			'navigation' => $settings['show_arrows'] === 'yes' ? [
				'nextEl' => '.mn-slideswipe-arrow-next-' . $widget_id,
				'prevEl' => '.mn-slideswipe-arrow-prev-' . $widget_id,
			] : false,
			'pagination' => $settings['show_dots'] === 'yes' ? [
				'el' => '.swiper-pagination-' . $widget_id,
				'clickable' => true,
			] : false,
		];
		
		// Add slide-specific settings only for slide effect
		if ( $settings['effect'] === 'slide' ) {
			// Get slides to show values
			$main_slides = ! empty( $settings['slides_to_show'] ) ? intval( $settings['slides_to_show'] ) : 3;
			$slides_to_scroll = ! empty( $settings['slides_to_scroll'] ) ? intval( $settings['slides_to_scroll'] ) : 1;
			
			// Helper function to get responsive value
			// If value is empty, use main_slides instead
			$get_responsive_value = function( $value ) use ( $main_slides ) {
				// Empty or not set - use main value
				if ( ! isset( $value ) || $value === '' || $value === null ) {
					return $main_slides;
				}
				return intval( $value );
			};
			
			// Get responsive values - if empty, uses main_slides value
			$tablet_landscape_slides = $get_responsive_value( $settings['slides_to_show_tablet_landscape'] ?? '' );
			$tablet_slides = $get_responsive_value( $settings['slides_to_show_tablet'] ?? '' );
			$mobile_landscape_slides = $get_responsive_value( $settings['slides_to_show_mobile_landscape'] ?? '' );
			$mobile_slides = $get_responsive_value( $settings['slides_to_show_mobile'] ?? '' );
			
			$swiper_settings['slidesPerView'] = $mobile_slides; // Mobile default (mobile-first)
			$swiper_settings['slidesPerGroup'] = $slides_to_scroll;
			$swiper_settings['spaceBetween'] = $settings['slide_spacing']['size'] ?? 20;
			$swiper_settings['breakpoints'] = [
				480 => [
					'slidesPerView' => $mobile_landscape_slides, // Mobile landscape
					'slidesPerGroup' => $slides_to_scroll,
				],
				768 => [
					'slidesPerView' => $tablet_slides, // Tablet portrait
					'slidesPerGroup' => $slides_to_scroll,
				],
				1024 => [
					'slidesPerView' => $tablet_landscape_slides, // Tablet landscape
					'slidesPerGroup' => $slides_to_scroll,
				],
				1200 => [
					'slidesPerView' => $main_slides, // Desktop
					'slidesPerGroup' => $slides_to_scroll,
				],
			];
		} else {
			// Fade effect: always 1 slide at a time
			$swiper_settings['slidesPerView'] = 1;
			$swiper_settings['spaceBetween'] = 0;
			$swiper_settings['fadeEffect'] = [
				'crossFade' => true,
			];
		}

		// Build wrapper classes
		$wrapper_classes = ['mn-slideswipe-wrapper'];
		
		// Add progress style class if enabled
		if ( $settings['autoplay'] === 'yes' && $settings['show_dots'] === 'yes' ) {
			if ( $settings['show_progress'] === 'yes' ) {
				if ( $settings['progress_style'] === 'circular' ) {
					$wrapper_classes[] = 'mn-progress-style-circular';
				}
			} else {
				// Disable progress animation
				$wrapper_classes[] = 'mn-progress-disabled';
			}
		}

		?>
		<div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>">
			<?php if ( $settings['show_dots'] === 'yes' && $settings['dots_position'] === 'top' ) : ?>
				<div class="swiper-pagination swiper-pagination-<?php echo esc_attr( $widget_id ); ?>"></div>
			<?php endif; ?>

			<div class="mn-slideswipe-container swiper" data-swiper-settings="<?php echo esc_attr( json_encode( $swiper_settings ) ); ?>">
				<div class="swiper-wrapper">
					<?php foreach ( $slides as $index => $slide ) : 
						// Get link settings
						$has_link = ! empty( $slide['slide_link']['url'] );
						$link_tag = $has_link ? 'a' : 'div';
						$link_attrs = '';
						
						if ( $has_link ) {
							$link_attrs .= ' href="' . esc_url( $slide['slide_link']['url'] ) . '"';
							
							if ( ! empty( $slide['slide_link']['is_external'] ) ) {
								$link_attrs .= ' target="_blank"';
							}
							
							if ( ! empty( $slide['slide_link']['nofollow'] ) ) {
								$link_attrs .= ' rel="nofollow"';
							}
						}
					?>
						<<?php echo esc_attr( $link_tag ); ?><?php echo $link_attrs; ?> class="swiper-slide elementor-repeater-item-<?php echo esc_attr( $slide['_id'] ); ?><?php echo $has_link ? ' mn-slide-has-link' : ''; ?>">
							<div class="mn-slide-content">
								<?php 
								$content_type = ! empty( $slide['content_type'] ) ? $slide['content_type'] : 'template';
								
								if ( $content_type === 'image' ) : 
									if ( ! empty( $slide['slide_image']['url'] ) ) : ?>
										<img src="<?php echo esc_url( $slide['slide_image']['url'] ); ?>" alt="<?php echo esc_attr( $slide['slide_title'] ); ?>" class="mn-slide-image" />
									<?php else : ?>
										<div class="mn-slide-placeholder">
											<h3><?php echo esc_html( $slide['slide_title'] ); ?></h3>
											<p><?php esc_html_e( 'Please select an image for this slide.', 'mn-elements' ); ?></p>
										</div>
									<?php endif;
								else : // Template content
									if ( ! empty( $slide['template_id'] ) ) : ?>
										<?php echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $slide['template_id'] ); ?>
									<?php else : ?>
										<div class="mn-slide-placeholder">
											<h3><?php echo esc_html( $slide['slide_title'] ); ?></h3>
											<p><?php esc_html_e( 'Please select a template for this slide.', 'mn-elements' ); ?></p>
										</div>
									<?php endif;
								endif; ?>
							</div>
						</<?php echo esc_attr( $link_tag ); ?>>
					<?php endforeach; ?>
				</div>
			</div>

			<?php if ( $settings['show_arrows'] === 'yes' ) : ?>
				<div class="mn-slideswipe-arrow mn-slideswipe-arrow-prev mn-slideswipe-arrow-prev-<?php echo esc_attr( $widget_id ); ?>">
					<i class="eicon-chevron-left" aria-hidden="true"></i>
				</div>
				<div class="mn-slideswipe-arrow mn-slideswipe-arrow-next mn-slideswipe-arrow-next-<?php echo esc_attr( $widget_id ); ?>">
					<i class="eicon-chevron-right" aria-hidden="true"></i>
				</div>
			<?php endif; ?>

			<?php if ( $settings['show_dots'] === 'yes' && $settings['dots_position'] === 'bottom' ) : ?>
				<div class="swiper-pagination swiper-pagination-<?php echo esc_attr( $widget_id ); ?>"></div>
			<?php endif; ?>
		</div>
		<?php
	}
}
