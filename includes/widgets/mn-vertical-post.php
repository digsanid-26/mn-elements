<?php
namespace MN_Elements\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * MN Vertical Post Widget
 *
 * Vertical carousel post listing with sticky scroll and overlay gradient
 *
 * @since 3.0.6
 */
class MN_Vertical_Post extends Widget_Base {

	public function get_name() {
		return 'mn-vertical-post';
	}

	public function get_title() {
		return esc_html__( 'MN Vertical Post', 'mn-elements' );
	}

	public function get_icon() {
		return 'eicon-post-slider';
	}

	public function get_categories() {
		return [ 'mn-elements' ];
	}

	public function get_keywords() {
		return [ 'vertical', 'post', 'carousel', 'slider', 'sticky', 'scroll', 'mn' ];
	}

	public function get_style_depends() {
		return [ 'mn-vertical-post' ];
	}

	public function get_script_depends() {
		return [ 'mn-vertical-post' ];
	}

	protected function register_controls() {
		$this->register_content_controls();
		$this->register_style_controls();
	}

	protected function register_content_controls() {
		// Content Type Section
		$this->start_controls_section(
			'section_content_type',
			[
				'label' => esc_html__( 'Content Type', 'mn-elements' ),
			]
		);

		$this->add_control(
			'content_source',
			[
				'label' => esc_html__( 'Content Source', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'dynamic',
				'options' => [
					'static' => esc_html__( 'Static Items', 'mn-elements' ),
					'dynamic' => esc_html__( 'Dynamic Posts', 'mn-elements' ),
				],
			]
		);

		$this->end_controls_section();

		// Static Items Section
		$this->start_controls_section(
			'section_static_items',
			[
				'label' => esc_html__( 'Static Items', 'mn-elements' ),
				'condition' => [
					'content_source' => 'static',
				],
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'item_title',
			[
				'label' => esc_html__( 'Title', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Item Title', 'mn-elements' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'item_excerpt',
			[
				'label' => esc_html__( 'Excerpt', 'mn-elements' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Item excerpt text goes here...', 'mn-elements' ),
				'rows' => 3,
			]
		);

		$repeater->add_control(
			'item_date',
			[
				'label' => esc_html__( 'Date', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => date( 'F j, Y' ),
			]
		);

		$repeater->add_control(
			'item_image',
			[
				'label' => esc_html__( 'Image', 'mn-elements' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control(
			'item_link',
			[
				'label' => esc_html__( 'Link', 'mn-elements' ),
				'type' => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'mn-elements' ),
				'default' => [
					'url' => '#',
				],
			]
		);

		$this->add_control(
			'static_items',
			[
				'label' => esc_html__( 'Items', 'mn-elements' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'item_title' => esc_html__( 'First Item', 'mn-elements' ),
						'item_excerpt' => esc_html__( 'This is the first item excerpt...', 'mn-elements' ),
					],
					[
						'item_title' => esc_html__( 'Second Item', 'mn-elements' ),
						'item_excerpt' => esc_html__( 'This is the second item excerpt...', 'mn-elements' ),
					],
					[
						'item_title' => esc_html__( 'Third Item', 'mn-elements' ),
						'item_excerpt' => esc_html__( 'This is the third item excerpt...', 'mn-elements' ),
					],
				],
				'title_field' => '{{{ item_title }}}',
			]
		);

		$this->end_controls_section();

		// Dynamic Query Section
		$this->start_controls_section(
			'section_query',
			[
				'label' => esc_html__( 'Query', 'mn-elements' ),
				'condition' => [
					'content_source' => 'dynamic',
				],
			]
		);

		$this->add_control(
			'post_type',
			[
				'label' => esc_html__( 'Post Type', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'post',
				'options' => $this->get_post_types(),
			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'label' => esc_html__( 'Posts Per Page', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5,
				'min' => 1,
				'max' => 20,
			]
		);

		$this->add_control(
			'orderby',
			[
				'label' => esc_html__( 'Order By', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'date' => esc_html__( 'Date', 'mn-elements' ),
					'title' => esc_html__( 'Title', 'mn-elements' ),
					'menu_order' => esc_html__( 'Menu Order', 'mn-elements' ),
					'rand' => esc_html__( 'Random', 'mn-elements' ),
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label' => esc_html__( 'Order', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => [
					'ASC' => esc_html__( 'Ascending', 'mn-elements' ),
					'DESC' => esc_html__( 'Descending', 'mn-elements' ),
				],
			]
		);

		$this->add_control(
			'taxonomy',
			[
				'label' => esc_html__( 'Taxonomy', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => $this->get_taxonomies(),
			]
		);

		$this->add_control(
			'taxonomy_ids',
			[
				'label' => esc_html__( 'Taxonomy IDs', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter IDs (comma separated)', 'mn-elements' ),
				'condition' => [
					'taxonomy!' => '',
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
			'layout_type',
			[
				'label' => esc_html__( 'Layout Type', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'layout_1',
				'options' => [
					'layout_1' => esc_html__( 'Layout 1 - Full Width Image', 'mn-elements' ),
					'layout_2' => esc_html__( 'Layout 2 - Side by Side', 'mn-elements' ),
				],
			]
		);

		$this->add_control(
			'show_title',
			[
				'label' => esc_html__( 'Show Title', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_excerpt',
			[
				'label' => esc_html__( 'Show Excerpt', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'excerpt_length',
			[
				'label' => esc_html__( 'Excerpt Length', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 20,
				'min' => 5,
				'max' => 100,
				'condition' => [
					'show_excerpt' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_date',
			[
				'label' => esc_html__( 'Show Date', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'date_format',
			[
				'label' => esc_html__( 'Date Format', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'F j, Y',
				'description' => esc_html__( 'PHP date format', 'mn-elements' ),
				'condition' => [
					'show_date' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image_size',
				'default' => 'large',
			]
		);

		$this->end_controls_section();

		// Carousel Settings Section
		$this->start_controls_section(
			'section_carousel',
			[
				'label' => esc_html__( 'Carousel Settings', 'mn-elements' ),
			]
		);

		$this->add_control(
			'disable_carousel_mobile',
			[
				'label' => esc_html__( 'Disable Carousel on Mobile', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'description' => esc_html__( 'Show regular listing instead of vertical carousel on mobile devices', 'mn-elements' ),
			]
		);

		$this->add_control(
			'enable_sticky_scroll',
			[
				'label' => esc_html__( 'Enable Sticky Scroll', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'description' => esc_html__( 'Section scroll will be hijacked by carousel navigation', 'mn-elements' ),
			]
		);

		$this->add_control(
			'enable_mouse_scroll',
			[
				'label' => esc_html__( 'Enable Mouse Scroll', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'visible_percentage',
			[
				'label' => esc_html__( 'Visible Percentage', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'%' => [
						'min' => 10,
						'max' => 50,
					],
				],
				'default' => [
					'size' => 20,
					'unit' => '%',
				],
				'description' => esc_html__( 'Percentage of prev/next items visible', 'mn-elements' ),
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label' => esc_html__( 'Autoplay', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
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
				'condition' => [
					'autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'transition_speed',
			[
				'label' => esc_html__( 'Transition Speed (ms)', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 600,
				'min' => 200,
				'max' => 2000,
			]
		);

		$this->add_control(
			'enable_infinity_loop',
			[
				'label' => esc_html__( 'Enable Infinity Loop', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'description' => esc_html__( 'Connect first and last posts for seamless infinite scrolling', 'mn-elements' ),
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
				'default' => 'yes',
			]
		);

		$this->add_control(
			'arrow_position',
			[
				'label' => esc_html__( 'Arrow Position', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [
					'left' => esc_html__( 'Left', 'mn-elements' ),
					'right' => esc_html__( 'Right', 'mn-elements' ),
					'center' => esc_html__( 'Center', 'mn-elements' ),
				],
				'condition' => [
					'show_arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'arrow_up_icon',
			[
				'label' => esc_html__( 'Up Arrow Icon', 'mn-elements' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-chevron-up',
					'library' => 'fa-solid',
				],
				'condition' => [
					'show_arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'arrow_down_icon',
			[
				'label' => esc_html__( 'Down Arrow Icon', 'mn-elements' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-chevron-down',
					'library' => 'fa-solid',
				],
				'condition' => [
					'show_arrows' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		// Hover Arrow Section
		$this->start_controls_section(
			'section_hover_arrow',
			[
				'label' => esc_html__( 'Hover Arrow', 'mn-elements' ),
			]
		);

		$this->add_control(
			'show_hover_arrow',
			[
				'label' => esc_html__( 'Show Hover Arrow', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'description' => esc_html__( 'Show arrow on image hover', 'mn-elements' ),
			]
		);

		$this->add_control(
			'hover_arrow_icon',
			[
				'label' => esc_html__( 'Hover Arrow Icon', 'mn-elements' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-arrow-right',
					'library' => 'fa-solid',
				],
				'condition' => [
					'show_hover_arrow' => 'yes',
				],
			]
		);

		$this->add_control(
			'url_option',
			[
				'label' => esc_html__( 'URL Option', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default (Permalink)', 'mn-elements' ),
					'custom' => esc_html__( 'Custom URL (from item)', 'mn-elements' ),
					'custom_meta' => esc_html__( 'Custom Meta Field', 'mn-elements' ),
				],
				'condition' => [
					'show_hover_arrow' => 'yes',
					'content_source' => 'dynamic',
				],
			]
		);

		$this->add_control(
			'url_meta_field',
			[
				'label' => esc_html__( 'Meta Field Key', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'e.g. video_url', 'mn-elements' ),
				'description' => esc_html__( 'Enter the custom field key that contains the URL', 'mn-elements' ),
				'condition' => [
					'show_hover_arrow' => 'yes',
					'content_source' => 'dynamic',
					'url_option' => 'custom_meta',
				],
			]
		);

		$this->add_control(
			'url_meta_link_option',
			[
				'label' => esc_html__( 'Link Option', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'same_window',
				'options' => [
					'same_window' => esc_html__( 'Open in Same Window', 'mn-elements' ),
					'new_window' => esc_html__( 'Open in New Window', 'mn-elements' ),
					'youtube_modal' => esc_html__( 'Open YouTube in Modal', 'mn-elements' ),
				],
				'description' => esc_html__( 'YouTube Modal only works with YouTube URLs from Custom Meta Field', 'mn-elements' ),
				'condition' => [
					'show_hover_arrow' => 'yes',
					'content_source' => 'dynamic',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_controls() {
		// Container Style
		$this->start_controls_section(
			'section_container_style',
			[
				'label' => esc_html__( 'Container', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'container_height',
			[
				'label' => esc_html__( 'Container Height', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range' => [
					'px' => [
						'min' => 400,
						'max' => 1200,
					],
					'vh' => [
						'min' => 50,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 100,
					'unit' => 'vh',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-vpost-container' => 'height: {{SIZE}}{{UNIT}};',
				],
				'description' => esc_html__( 'Total visible area including active slide and partial prev/next slides', 'mn-elements' ),
			]
		);

		$this->add_responsive_control(
			'item_height',
			[
				'label' => esc_html__( 'Slide Height', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh', '%' ],
				'range' => [
					'px' => [
						'min' => 200,
						'max' => 1000,
					],
					'vh' => [
						'min' => 30,
						'max' => 90,
					],
					'%' => [
						'min' => 50,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 60,
					'unit' => 'vh',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-vpost-item' => 'height: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}}; max-height: {{SIZE}}{{UNIT}};',
				],
				'description' => esc_html__( 'Fixed height for each slide. Container height minus slide height will be distributed to show prev/next slides', 'mn-elements' ),
			]
		);

		$this->add_responsive_control(
			'item_gap',
			[
				'label' => esc_html__( 'Gap Between Slides', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'vh' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'em' => [
						'min' => 0,
						'max' => 10,
					],
					'vh' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'default' => [
					'size' => 0,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-vpost-track' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'container_background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .mn-vpost-container',
			]
		);

		$this->add_responsive_control(
			'container_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-vpost-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Overlay Style
		$this->start_controls_section(
			'section_overlay_style',
			[
				'label' => esc_html__( 'Overlay Gradient', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'overlay_color_top',
			[
				'label' => esc_html__( 'Top Overlay Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
			]
		);

		$this->add_control(
			'overlay_color_bottom',
			[
				'label' => esc_html__( 'Bottom Overlay Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
			]
		);

		$this->add_responsive_control(
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
					'size' => 0.8,
				],
				'tablet_default' => [
					'size' => 0.8,
				],
				'mobile_default' => [
					'size' => 0,
				],
				'description' => esc_html__( 'Set to 0 on mobile to hide overlay when using mobile listing mode', 'mn-elements' ),
			]
		);

		$this->end_controls_section();

		// Item Style
		$this->start_controls_section(
			'section_item_style',
			[
				'label' => esc_html__( 'Item', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'item_background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .mn-vpost-item',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'item_border',
				'selector' => '{{WRAPPER}} .mn-vpost-item',
			]
		);

		$this->add_responsive_control(
			'item_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-vpost-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .mn-vpost-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_box_shadow',
				'selector' => '{{WRAPPER}} .mn-vpost-item',
			]
		);

		$this->add_responsive_control(
			'item_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-vpost-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Image Style
		$this->start_controls_section(
			'section_image_style',
			[
				'label' => esc_html__( 'Image', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'image_height',
			[
				'label' => esc_html__( 'Height', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 800,
					],
					'%' => [
						'min' => 30,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mn-vpost-image' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'image_object_fit',
			[
				'label' => esc_html__( 'Object Fit', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'cover',
				'options' => [
					'cover' => esc_html__( 'Cover', 'mn-elements' ),
					'contain' => esc_html__( 'Contain', 'mn-elements' ),
					'fill' => esc_html__( 'Fill', 'mn-elements' ),
				],
				'selectors' => [
					'{{WRAPPER}} .mn-vpost-image img' => 'object-fit: {{VALUE}};',
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
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .mn-vpost-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .mn-vpost-title',
			]
		);

		$this->add_responsive_control(
			'title_spacing',
			[
				'label' => esc_html__( 'Spacing', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mn-vpost-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Excerpt Style
		$this->start_controls_section(
			'section_excerpt_style',
			[
				'label' => esc_html__( 'Excerpt', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'excerpt_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#666666',
				'selectors' => [
					'{{WRAPPER}} .mn-vpost-excerpt' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'excerpt_typography',
				'selector' => '{{WRAPPER}} .mn-vpost-excerpt',
			]
		);

		$this->add_responsive_control(
			'excerpt_spacing',
			[
				'label' => esc_html__( 'Spacing', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mn-vpost-excerpt' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Date Style
		$this->start_controls_section(
			'section_date_style',
			[
				'label' => esc_html__( 'Date', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'date_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#999999',
				'selectors' => [
					'{{WRAPPER}} .mn-vpost-date' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'date_typography',
				'selector' => '{{WRAPPER}} .mn-vpost-date',
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
			'arrow_size',
			[
				'label' => esc_html__( 'Icon Size', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 12,
						'max' => 48,
					],
				],
				'default' => [
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-vpost-arrow i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-vpost-arrow svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'arrow_button_size',
			[
				'label' => esc_html__( 'Button Size', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 30,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-vpost-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'arrows_style_tabs' );

		$this->start_controls_tab(
			'arrows_normal',
			[
				'label' => esc_html__( 'Normal', 'mn-elements' ),
			]
		);

		$this->add_control(
			'arrow_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-vpost-arrow' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'arrow_background',
			[
				'label' => esc_html__( 'Background', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0, 0, 0, 0.5)',
				'selectors' => [
					'{{WRAPPER}} .mn-vpost-arrow' => 'background-color: {{VALUE}};',
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
			'arrow_hover_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-vpost-arrow:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'arrow_hover_background',
			[
				'label' => esc_html__( 'Background', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0, 0, 0, 0.8)',
				'selectors' => [
					'{{WRAPPER}} .mn-vpost-arrow:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'arrow_spacing',
			[
				'label' => esc_html__( 'Spacing', 'mn-elements' ),
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
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'arrow_offset',
			[
				'label' => esc_html__( 'Offset', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
					'%' => [
						'min' => 0,
						'max' => 50,
					],
				],
			]
		);

		$this->end_controls_section();

		// Hover Arrow Style
		$this->start_controls_section(
			'section_hover_arrow_style',
			[
				'label' => esc_html__( 'Hover Arrow', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_hover_arrow' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'hover_arrow_size',
			[
				'label' => esc_html__( 'Icon Size', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 12,
						'max' => 48,
					],
				],
				'default' => [
					'size' => 24,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-vpost-hover-arrow i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-vpost-hover-arrow svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'hover_arrow_button_size',
			[
				'label' => esc_html__( 'Button Size', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 30,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 60,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-vpost-hover-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hover_arrow_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-vpost-hover-arrow' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_arrow_background',
			[
				'label' => esc_html__( 'Background', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0, 0, 0, 0.7)',
				'selectors' => [
					'{{WRAPPER}} .mn-vpost-hover-arrow' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function get_post_types() {
		$post_types = get_post_types( [ 'public' => true ], 'objects' );
		$options = [];
		
		foreach ( $post_types as $post_type ) {
			if ( $post_type->name === 'attachment' ) {
				continue;
			}
			$options[ $post_type->name ] = $post_type->label;
		}
		
		return $options;
	}

	protected function get_taxonomies() {
		$taxonomies = get_taxonomies( [ 'public' => true ], 'objects' );
		$options = [ '' => esc_html__( 'None', 'mn-elements' ) ];
		
		foreach ( $taxonomies as $taxonomy ) {
			$options[ $taxonomy->name ] = $taxonomy->label;
		}
		
		return $options;
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		
		$wrapper_classes = [
			'mn-vpost-wrapper',
			'mn-vpost-layout-' . ( ! empty( $settings['layout_type'] ) ? $settings['layout_type'] : 'layout_1' ),
		];
		
		if ( ! empty( $settings['enable_sticky_scroll'] ) && 'yes' === $settings['enable_sticky_scroll'] ) {
			$wrapper_classes[] = 'mn-vpost-sticky';
		}
		
		$disable_carousel_mobile = ! empty( $settings['disable_carousel_mobile'] ) && 'yes' === $settings['disable_carousel_mobile'];
		if ( $disable_carousel_mobile ) {
			$wrapper_classes[] = 'mn-vpost-mobile-listing';
		}
		
		$this->add_render_attribute( 'wrapper', 'class', $wrapper_classes );
		$item_height_value = ! empty( $settings['item_height']['size'] ) ? $settings['item_height']['size'] : 60;
		$item_height_unit = ! empty( $settings['item_height']['unit'] ) ? $settings['item_height']['unit'] : 'vh';
		$item_gap_value = ! empty( $settings['item_gap']['size'] ) ? $settings['item_gap']['size'] : 0;
		$item_gap_unit = ! empty( $settings['item_gap']['unit'] ) ? $settings['item_gap']['unit'] : 'px';
		
		// Get responsive overlay opacity values
		$overlay_opacity_desktop = ! empty( $settings['overlay_opacity']['size'] ) ? $settings['overlay_opacity']['size'] : 0.8;
		$overlay_opacity_tablet = ! empty( $settings['overlay_opacity_tablet']['size'] ) ? $settings['overlay_opacity_tablet']['size'] : $overlay_opacity_desktop;
		$overlay_opacity_mobile = ! empty( $settings['overlay_opacity_mobile']['size'] ) ? $settings['overlay_opacity_mobile']['size'] : 0;
		
		$this->add_render_attribute( 'wrapper', 'data-settings', wp_json_encode( [
			'stickyScroll' => ! empty( $settings['enable_sticky_scroll'] ) && 'yes' === $settings['enable_sticky_scroll'],
			'mouseScroll' => ! empty( $settings['enable_mouse_scroll'] ) && 'yes' === $settings['enable_mouse_scroll'],
			'autoplay' => ! empty( $settings['autoplay'] ) && 'yes' === $settings['autoplay'],
			'autoplaySpeed' => ! empty( $settings['autoplay_speed'] ) ? $settings['autoplay_speed'] : 5000,
			'transitionSpeed' => ! empty( $settings['transition_speed'] ) ? $settings['transition_speed'] : 600,
			'visiblePercentage' => ! empty( $settings['visible_percentage']['size'] ) ? $settings['visible_percentage']['size'] : 20,
			'overlayColorTop' => ! empty( $settings['overlay_color_top'] ) ? $settings['overlay_color_top'] : '#000000',
			'overlayColorBottom' => ! empty( $settings['overlay_color_bottom'] ) ? $settings['overlay_color_bottom'] : '#000000',
			'overlayOpacity' => $overlay_opacity_desktop,
			'overlayOpacityTablet' => $overlay_opacity_tablet,
			'overlayOpacityMobile' => $overlay_opacity_mobile,
			'infinityLoop' => ! empty( $settings['enable_infinity_loop'] ) && 'yes' === $settings['enable_infinity_loop'],
			'itemHeight' => $item_height_value,
			'itemHeightUnit' => $item_height_unit,
			'itemGap' => $item_gap_value,
			'itemGapUnit' => $item_gap_unit,
			'disableCarouselMobile' => $disable_carousel_mobile,
		] ) );
		
		
		// Add CSS variables for mobile listing mode
		$mobile_image_height = ! empty( $settings['item_height_mobile']['size'] ) ? $settings['item_height_mobile']['size'] : ( ! empty( $settings['item_height']['size'] ) ? $settings['item_height']['size'] : 250 );
		$mobile_image_height_unit = ! empty( $settings['item_height_mobile']['unit'] ) ? $settings['item_height_mobile']['unit'] : ( ! empty( $settings['item_height']['unit'] ) ? $settings['item_height']['unit'] : 'px' );
		$mobile_gap = ! empty( $settings['item_gap_mobile']['size'] ) ? $settings['item_gap_mobile']['size'] : ( ! empty( $settings['item_gap']['size'] ) ? $settings['item_gap']['size'] : 20 );
		$mobile_gap_unit = ! empty( $settings['item_gap_mobile']['unit'] ) ? $settings['item_gap_mobile']['unit'] : ( ! empty( $settings['item_gap']['unit'] ) ? $settings['item_gap']['unit'] : 'px' );
		
		?>
		<style>
			.elementor-element-<?php echo $this->get_id(); ?> .mn-vpost-wrapper {
				--mn-vpost-image-height: <?php echo esc_attr( $mobile_image_height . $mobile_image_height_unit ); ?>;
				--mn-vpost-gap: <?php echo esc_attr( $mobile_gap . $mobile_gap_unit ); ?>;
			}
		</style>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<div class="mn-vpost-container">
				<div class="mn-vpost-track">
				<?php
				$content_source = ! empty( $settings['content_source'] ) ? $settings['content_source'] : 'dynamic';
				if ( 'static' === $content_source ) {
					$this->render_static_items( $settings );
				} else {
					$this->render_dynamic_posts( $settings );
				}
				?>
			</div>
			
			<?php if ( ! empty( $settings['show_arrows'] ) && 'yes' === $settings['show_arrows'] ) : ?>
				<div class="mn-vpost-navigation mn-vpost-nav-<?php echo esc_attr( ! empty( $settings['arrow_position'] ) ? $settings['arrow_position'] : 'right' ); ?>">
					<button class="mn-vpost-arrow mn-vpost-arrow-up" aria-label="<?php esc_attr_e( 'Previous', 'mn-elements' ); ?>">
						<?php 
						if ( ! empty( $settings['arrow_up_icon'] ) ) {
							Icons_Manager::render_icon( $settings['arrow_up_icon'], [ 'aria-hidden' => 'true' ] );
						}
						?>
					</button>
					<button class="mn-vpost-arrow mn-vpost-arrow-down" aria-label="<?php esc_attr_e( 'Next', 'mn-elements' ); ?>">
						<?php 
						if ( ! empty( $settings['arrow_down_icon'] ) ) {
							Icons_Manager::render_icon( $settings['arrow_down_icon'], [ 'aria-hidden' => 'true' ] );
						}
						?>
					</button>
				</div>
			<?php endif; ?>
			</div>
		</div>
		<?php
		
		// Render YouTube modal if needed
		$this->render_youtube_modal( $settings );
	}

	/**
	 * Render YouTube modal for video playback
	 */
	protected function render_youtube_modal( $settings ) {
		$url_option = ! empty( $settings['url_option'] ) ? $settings['url_option'] : 'default';
		$link_option = ! empty( $settings['url_meta_link_option'] ) ? $settings['url_meta_link_option'] : 'same_window';
		
		if ( $url_option !== 'custom_meta' || $link_option !== 'youtube_modal' ) {
			return;
		}
		?>
		<div id="mn-vpost-youtube-modal" class="mn-youtube-modal" style="display: none;">
			<div class="mn-youtube-modal-overlay"></div>
			<div class="mn-youtube-modal-container">
				<div class="mn-youtube-modal-close">&times;</div>
				<div class="mn-youtube-modal-content">
					<div class="mn-youtube-modal-iframe-wrapper">
						<iframe id="mn-vpost-youtube-iframe" src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
					</div>
				</div>
			</div>
		</div>
		<style>
		#mn-vpost-youtube-modal {
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			z-index: 99999;
			display: flex;
			align-items: center;
			justify-content: center;
			background: transparent;
		}
		#mn-vpost-youtube-modal .mn-youtube-modal-overlay {
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background: rgba(0, 0, 0, 0.9);
		}
		#mn-vpost-youtube-modal .mn-youtube-modal-container {
			position: relative;
			width: 90%;
			max-width: 900px;
			z-index: 1;
		}
		#mn-vpost-youtube-modal .mn-youtube-modal-close {
			position: absolute;
			top: -40px;
			right: 0;
			width: 36px;
			height: 36px;
			background: #fff;
			color: #000;
			font-size: 24px;
			line-height: 36px;
			text-align: center;
			cursor: pointer;
			border-radius: 50%;
			transition: all 0.3s ease;
		}
		#mn-vpost-youtube-modal .mn-youtube-modal-close:hover {
			background: #ff0000;
			color: #fff;
		}
		#mn-vpost-youtube-modal .mn-youtube-modal-iframe-wrapper {
			position: relative;
			padding-bottom: 56.25%;
			height: 0;
			overflow: hidden;
		}
		#mn-vpost-youtube-modal .mn-youtube-modal-iframe-wrapper iframe {
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
		}
		</style>
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			// Handle YouTube modal trigger
			$(document).on('click', '.mn-vpost-youtube-trigger', function(e) {
				e.preventDefault();
				
				var youtubeId = $(this).data('youtube-id');
				if (!youtubeId) {
					console.error('MN Vertical Post: No YouTube ID found');
					return;
				}
				
				// Set iframe src with autoplay
				var embedUrl = 'https://www.youtube.com/embed/' + youtubeId + '?autoplay=1&rel=0';
				$('#mn-vpost-youtube-iframe').attr('src', embedUrl);
				
				// Show modal
				$('#mn-vpost-youtube-modal').fadeIn(300);
			});
			
			// Close YouTube modal
			$(document).on('click', '#mn-vpost-youtube-modal .mn-youtube-modal-close, #mn-vpost-youtube-modal .mn-youtube-modal-overlay', function() {
				$('#mn-vpost-youtube-modal').fadeOut(300, function() {
					// Stop video by clearing src
					$('#mn-vpost-youtube-iframe').attr('src', '');
				});
			});
			
			// Close on ESC key
			$(document).keyup(function(e) {
				if (e.keyCode === 27 && $('#mn-vpost-youtube-modal').is(':visible')) {
					$('#mn-vpost-youtube-modal').fadeOut(300, function() {
						$('#mn-vpost-youtube-iframe').attr('src', '');
					});
				}
			});
		});
		</script>
		<?php
	}

	protected function render_static_items( $settings ) {
		if ( empty( $settings['static_items'] ) ) {
			return;
		}
		
		foreach ( $settings['static_items'] as $index => $item ) {
			$this->render_item( [
				'title' => ! empty( $item['item_title'] ) ? $item['item_title'] : '',
				'excerpt' => ! empty( $item['item_excerpt'] ) ? $item['item_excerpt'] : '',
				'date' => ! empty( $item['item_date'] ) ? $item['item_date'] : '',
				'image_url' => ! empty( $item['item_image']['url'] ) ? $item['item_image']['url'] : '',
				'link' => ! empty( $item['item_link']['url'] ) ? $item['item_link']['url'] : '',
				'link_external' => ! empty( $item['item_link']['is_external'] ) ? $item['item_link']['is_external'] : false,
				'link_nofollow' => ! empty( $item['item_link']['nofollow'] ) ? $item['item_link']['nofollow'] : false,
			], $settings, $index );
		}
	}

	protected function render_dynamic_posts( $settings ) {
		$args = [
			'post_type' => ! empty( $settings['post_type'] ) ? $settings['post_type'] : 'post',
			'posts_per_page' => ! empty( $settings['posts_per_page'] ) ? $settings['posts_per_page'] : 5,
			'orderby' => ! empty( $settings['orderby'] ) ? $settings['orderby'] : 'date',
			'order' => ! empty( $settings['order'] ) ? $settings['order'] : 'DESC',
			'post_status' => 'publish',
		];
		
		if ( ! empty( $settings['taxonomy'] ) && ! empty( $settings['taxonomy_ids'] ) ) {
			$term_ids = array_map( 'trim', explode( ',', $settings['taxonomy_ids'] ) );
			$args['tax_query'] = [
				[
					'taxonomy' => $settings['taxonomy'],
					'field' => 'term_id',
					'terms' => $term_ids,
				],
			];
		}
		
		$query = new \WP_Query( $args );
		
		if ( $query->have_posts() ) {
			$index = 0;
			while ( $query->have_posts() ) {
				$query->the_post();
				
				$excerpt = '';
				if ( ! empty( $settings['show_excerpt'] ) && 'yes' === $settings['show_excerpt'] ) {
					$excerpt_length = ! empty( $settings['excerpt_length'] ) ? $settings['excerpt_length'] : 20;
					$excerpt = wp_trim_words( get_the_excerpt(), $excerpt_length, '...' );
				}
				
				$date_format = ! empty( $settings['date_format'] ) ? $settings['date_format'] : 'F j, Y';
				$image_size = ! empty( $settings['image_size_size'] ) ? $settings['image_size_size'] : 'large';
				
				// Determine URL based on URL Option
				$link = get_permalink();
				$link_external = false;
				$link_nofollow = false;
				$is_youtube_modal = false;
				$youtube_id = '';
				
				$url_option = ! empty( $settings['url_option'] ) ? $settings['url_option'] : 'default';
				$link_option = ! empty( $settings['url_meta_link_option'] ) ? $settings['url_meta_link_option'] : 'same_window';
				
				if ( $url_option === 'custom_meta' ) {
					$meta_field_key = ! empty( $settings['url_meta_field'] ) ? $settings['url_meta_field'] : '';
					
					if ( ! empty( $meta_field_key ) ) {
						$meta_url = get_post_meta( get_the_ID(), $meta_field_key, true );
						if ( ! empty( $meta_url ) ) {
							$link = $meta_url;
							
							if ( $link_option === 'new_window' ) {
								$link_external = true;
							} elseif ( $link_option === 'youtube_modal' ) {
								// Check if it's a YouTube URL
								if ( preg_match( '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $meta_url, $matches ) ) {
									$youtube_id = $matches[1];
									$is_youtube_modal = true;
								}
							}
						}
					}
				} elseif ( $url_option === 'default' ) {
					// Apply link option to default permalink
					if ( $link_option === 'new_window' ) {
						$link_external = true;
					}
				}
				
				$this->render_item( [
					'title' => get_the_title(),
					'excerpt' => $excerpt,
					'date' => get_the_date( $date_format ),
					'image_url' => get_the_post_thumbnail_url( get_the_ID(), $image_size ),
					'link' => $link,
					'link_external' => $link_external,
					'link_nofollow' => $link_nofollow,
					'is_youtube_modal' => $is_youtube_modal,
					'youtube_id' => $youtube_id,
				], $settings, $index );
				
				$index++;
			}
			wp_reset_postdata();
		}
	}

	protected function render_item( $item, $settings, $index ) {
		$item_classes = [ 'mn-vpost-item' ];
		if ( 0 === $index ) {
			$item_classes[] = 'mn-vpost-active';
		}
		
		$link_attrs = '';
		$link_class = 'mn-vpost-hover-arrow';
		if ( ! empty( $item['link'] ) ) {
			// Check if YouTube modal
			if ( ! empty( $item['is_youtube_modal'] ) && ! empty( $item['youtube_id'] ) ) {
				$link_attrs = 'href="#"';
				$link_attrs .= ' data-youtube-id="' . esc_attr( $item['youtube_id'] ) . '"';
				$link_class .= ' mn-vpost-youtube-trigger';
			} else {
				$link_attrs = 'href="' . esc_url( $item['link'] ) . '"';
				if ( ! empty( $item['link_external'] ) ) {
					$link_attrs .= ' target="_blank" rel="noopener noreferrer"';
				}
				if ( ! empty( $item['link_nofollow'] ) ) {
					$link_attrs .= ' rel="nofollow"';
				}
			}
		}
		
		$layout_type = ! empty( $settings['layout_type'] ) ? $settings['layout_type'] : 'layout_1';
		$show_hover_arrow = ! empty( $settings['show_hover_arrow'] ) && 'yes' === $settings['show_hover_arrow'];
		$show_title = ! empty( $settings['show_title'] ) && 'yes' === $settings['show_title'];
		$show_excerpt = ! empty( $settings['show_excerpt'] ) && 'yes' === $settings['show_excerpt'];
		$show_date = ! empty( $settings['show_date'] ) && 'yes' === $settings['show_date'];
		?>
		<div class="<?php echo esc_attr( implode( ' ', $item_classes ) ); ?>" data-index="<?php echo esc_attr( $index ); ?>">
			<?php if ( 'layout_1' === $layout_type ) : ?>
				<!-- Layout 1: Full Width Image -->
				<div class="mn-vpost-image-wrapper">
					<?php if ( ! empty( $item['image_url'] ) ) : ?>
						<div class="mn-vpost-image">
							<img src="<?php echo esc_url( $item['image_url'] ); ?>" alt="<?php echo esc_attr( ! empty( $item['title'] ) ? $item['title'] : '' ); ?>">
							<?php if ( $show_hover_arrow && ! empty( $item['link'] ) ) : ?>
								<a <?php echo $link_attrs; ?> class="<?php echo esc_attr( $link_class ); ?>">
									<?php 
									if ( ! empty( $settings['hover_arrow_icon'] ) ) {
										Icons_Manager::render_icon( $settings['hover_arrow_icon'], [ 'aria-hidden' => 'true' ] );
									}
									?>
								</a>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
				<div class="mn-vpost-content mn-vpost-content-row">
					<div class="mn-vpost-content-left">
						<?php if ( $show_title && ! empty( $item['title'] ) ) : ?>
							<h3 class="mn-vpost-title"><?php echo esc_html( $item['title'] ); ?></h3>
						<?php endif; ?>
						<?php if ( $show_excerpt && ! empty( $item['excerpt'] ) ) : ?>
							<div class="mn-vpost-excerpt"><?php echo esc_html( $item['excerpt'] ); ?></div>
						<?php endif; ?>
					</div>
					<div class="mn-vpost-content-right">
						<?php if ( $show_date && ! empty( $item['date'] ) ) : ?>
							<div class="mn-vpost-date"><?php echo esc_html( $item['date'] ); ?></div>
						<?php endif; ?>
					</div>
				</div>
			<?php else : ?>
				<!-- Layout 2: Side by Side -->
				<div class="mn-vpost-content mn-vpost-content-cols">
					<div class="mn-vpost-col-left">
						<?php if ( ! empty( $item['image_url'] ) ) : ?>
							<div class="mn-vpost-image">
								<img src="<?php echo esc_url( $item['image_url'] ); ?>" alt="<?php echo esc_attr( ! empty( $item['title'] ) ? $item['title'] : '' ); ?>">
								<?php if ( $show_hover_arrow && ! empty( $item['link'] ) ) : ?>
									<a <?php echo $link_attrs; ?> class="<?php echo esc_attr( $link_class ); ?>">
										<?php 
										if ( ! empty( $settings['hover_arrow_icon'] ) ) {
											Icons_Manager::render_icon( $settings['hover_arrow_icon'], [ 'aria-hidden' => 'true' ] );
										}
										?>
									</a>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>
					<div class="mn-vpost-col-right">
						<?php if ( $show_title && ! empty( $item['title'] ) ) : ?>
							<h3 class="mn-vpost-title"><?php echo esc_html( $item['title'] ); ?></h3>
						<?php endif; ?>
						<?php if ( $show_excerpt && ! empty( $item['excerpt'] ) ) : ?>
							<div class="mn-vpost-excerpt"><?php echo esc_html( $item['excerpt'] ); ?></div>
						<?php endif; ?>
						<?php if ( $show_date && ! empty( $item['date'] ) ) : ?>
							<div class="mn-vpost-date"><?php echo esc_html( $item['date'] ); ?></div>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}
}
