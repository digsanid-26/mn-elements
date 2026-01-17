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
 * MN Logolist Widget
 *
 * Logo display widget with list, grid, and carousel layouts
 *
 * @since 1.0.5
 */
class MN_Logolist extends Widget_Base {

	/**
	 * Constructor
	 */
	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		
		// Register AJAX handler for taxonomy loading
		add_action( 'wp_ajax_mn_logolist_get_taxonomies', [ $this, 'ajax_get_taxonomies' ] );
	}

	/**
	 * AJAX handler for getting taxonomies
	 */
	public function ajax_get_taxonomies() {
		check_ajax_referer( 'mn-logolist-editor', 'nonce' );
		
		$post_type = isset( $_POST['post_type'] ) ? sanitize_text_field( $_POST['post_type'] ) : 'post';
		$taxonomies = $this->get_taxonomies_for_post_type( $post_type );
		
		wp_send_json_success( $taxonomies );
	}

	/**
	 * Get widget name.
	 */
	public function get_name() {
		return 'mn-logolist';
	}

	/**
	 * Get widget title.
	 */
	public function get_title() {
		return esc_html__( 'MN Logolist', 'mn-elements' );
	}

	/**
	 * Get widget icon.
	 */
	public function get_icon() {
		return 'eicon-logo';
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
		return [ 'logo', 'list', 'grid', 'carousel', 'brand', 'client', 'partner', 'mn' ];
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
		// Logo Source Section
		$this->start_controls_section(
			'section_logo_source',
			[
				'label' => esc_html__( 'Logo Source', 'mn-elements' ),
			]
		);

		$this->add_control(
			'logo_source',
			[
				'label' => esc_html__( 'Source', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'manual' => esc_html__( 'Manual Input', 'mn-elements' ),
					'dynamic' => esc_html__( 'Dynamic (Post Type)', 'mn-elements' ),
				],
				'default' => 'manual',
				'description' => esc_html__( 'Choose between manual logo input or dynamic loading from WordPress posts', 'mn-elements' ),
			]
		);

		$this->end_controls_section();

		// Logo Management Section (Manual)
		$this->start_controls_section(
			'section_logo_management',
			[
				'label' => esc_html__( 'Logo Management', 'mn-elements' ),
				'condition' => [
					'logo_source' => 'manual',
				],
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'logo_image',
			[
				'label' => esc_html__( 'Logo Image', 'mn-elements' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'logo_title',
			[
				'label' => esc_html__( 'Logo Title', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Logo Title', 'mn-elements' ),
				'placeholder' => esc_html__( 'Enter logo title', 'mn-elements' ),
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'link_type',
			[
				'label' => esc_html__( 'Link Type', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'url',
				'options' => [
					'url' => esc_html__( 'Custom URL', 'mn-elements' ),
					'media' => esc_html__( 'Media File (Lightbox)', 'mn-elements' ),
				],
			]
		);

		$repeater->add_control(
			'custom_url',
			[
				'label' => esc_html__( 'Custom URL', 'mn-elements' ),
				'type' => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'mn-elements' ),
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'link_type' => 'url',
				],
			]
		);

		$repeater->add_control(
			'media_file',
			[
				'label' => esc_html__( 'Media File', 'mn-elements' ),
				'type' => Controls_Manager::MEDIA,
				'media_types' => [ 'image', 'video' ],
				'description' => esc_html__( 'Select image or video file to open in lightbox', 'mn-elements' ),
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'link_type' => 'media',
				],
			]
		);

		$this->add_control(
			'logo_list',
			[
				'label' => esc_html__( 'Logo Items', 'mn-elements' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'logo_title' => esc_html__( 'Logo 1', 'mn-elements' ),
					],
					[
						'logo_title' => esc_html__( 'Logo 2', 'mn-elements' ),
					],
					[
						'logo_title' => esc_html__( 'Logo 3', 'mn-elements' ),
					],
					[
						'logo_title' => esc_html__( 'Logo 4', 'mn-elements' ),
					],
				],
				'title_field' => '{{{ logo_title }}}',
			]
		);

		$this->end_controls_section();

		// Dynamic Source Section
		$this->start_controls_section(
			'section_dynamic_source',
			[
				'label' => esc_html__( 'Dynamic Source', 'mn-elements' ),
				'condition' => [
					'logo_source' => 'dynamic',
				],
			]
		);

		// Post Type & Taxonomy
		$this->add_control(
			'dynamic_post_type',
			[
				'label' => esc_html__( 'Post Type', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_post_types(),
				'default' => 'post',
			]
		);

		$this->add_control(
			'dynamic_taxonomy',
			[
				'label' => esc_html__( 'Filter by Taxonomy', 'mn-elements' ),
				'type' => Controls_Manager::SELECT2,
				'options' => $this->get_all_taxonomies(),
				'default' => '',
				'label_block' => true,
				'description' => esc_html__( 'Select taxonomy to filter posts. Taxonomies are filtered based on selected post type.', 'mn-elements' ),
			]
		);

		$this->add_control(
			'dynamic_terms',
			[
				'label' => esc_html__( 'Taxonomy Terms', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( '1,2,3', 'mn-elements' ),
				'description' => esc_html__( 'Enter term IDs separated by comma. Leave empty to include all terms.', 'mn-elements' ),
				'condition' => [
					'dynamic_taxonomy!' => '',
				],
			]
		);

		// Custom Meta Fields
		$this->add_control(
			'dynamic_meta_heading',
			[
				'label' => esc_html__( 'Custom Meta Fields', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'description' => esc_html__( 'Supports ACF, JetEngine, and WordPress custom fields', 'mn-elements' ),
			]
		);

		$this->add_control(
			'dynamic_logo_meta_key',
			[
				'label' => esc_html__( 'Logo Image Meta Key', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'logo_image',
				'placeholder' => 'logo_image',
				'description' => esc_html__( 'Meta field key that contains logo image (ID or URL)', 'mn-elements' ),
			]
		);

		$this->add_control(
			'dynamic_title_source',
			[
				'label' => esc_html__( 'Title Source', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'post_title' => esc_html__( 'Post Title', 'mn-elements' ),
					'meta_field' => esc_html__( 'Custom Meta Field', 'mn-elements' ),
				],
				'default' => 'post_title',
			]
		);

		$this->add_control(
			'dynamic_title_meta_key',
			[
				'label' => esc_html__( 'Title Meta Key', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => 'logo_title',
				'description' => esc_html__( 'Meta field key for custom title', 'mn-elements' ),
				'condition' => [
					'dynamic_title_source' => 'meta_field',
				],
			]
		);

		$this->add_control(
			'dynamic_description_meta_key',
			[
				'label' => esc_html__( 'Description Meta Key', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => 'logo_description',
				'description' => esc_html__( 'Optional: Meta field key for logo description', 'mn-elements' ),
			]
		);

		$this->add_control(
			'dynamic_link_source',
			[
				'label' => esc_html__( 'Link Source', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'mn-elements' ),
					'post_url' => esc_html__( 'Post URL', 'mn-elements' ),
					'meta_field' => esc_html__( 'Custom Meta Field', 'mn-elements' ),
				],
				'default' => 'none',
			]
		);

		$this->add_control(
			'dynamic_link_meta_key',
			[
				'label' => esc_html__( 'Link Meta Key', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => 'logo_url',
				'description' => esc_html__( 'Meta field key for custom URL', 'mn-elements' ),
				'condition' => [
					'dynamic_link_source' => 'meta_field',
				],
			]
		);

		// Query Settings
		$this->add_control(
			'dynamic_query_heading',
			[
				'label' => esc_html__( 'Query Settings', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'dynamic_posts_per_page',
			[
				'label' => esc_html__( 'Posts Per Page', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 10,
				'min' => 1,
				'max' => 100,
			]
		);

		$this->add_control(
			'dynamic_orderby',
			[
				'label' => esc_html__( 'Order By', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'date' => esc_html__( 'Date', 'mn-elements' ),
					'title' => esc_html__( 'Title', 'mn-elements' ),
					'menu_order' => esc_html__( 'Menu Order', 'mn-elements' ),
					'rand' => esc_html__( 'Random', 'mn-elements' ),
				],
				'default' => 'date',
			]
		);

		$this->add_control(
			'dynamic_order',
			[
				'label' => esc_html__( 'Order', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'DESC' => esc_html__( 'Descending', 'mn-elements' ),
					'ASC' => esc_html__( 'Ascending', 'mn-elements' ),
				],
				'default' => 'DESC',
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
			'display_type',
			[
				'label' => esc_html__( 'Display Type', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'grid',
				'options' => [
					'list' => esc_html__( 'List', 'mn-elements' ),
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
				'default' => '4',
				'tablet_default' => '3',
				'mobile_default' => '2',
				'options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
					'7' => '7',
					'8' => '8',
					'9' => '9',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-logolist-grid .mn-logolist-container' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				],
				'condition' => [
					'display_type!' => 'carousel',
				],
			]
		);

		$this->add_control(
			'carousel_slides_to_show',
			[
				'label' => esc_html__( 'Slides to Show', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => '4',
				'options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
					'7' => '7',
					'8' => '8',
					'9' => '9',
				],
				'condition' => [
					'display_type' => 'carousel',
				],
			]
		);

		$this->add_control(
			'image_resolution',
			[
				'label' => esc_html__( 'Image Resolution', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'medium',
				'options' => [
					'thumbnail' => esc_html__( 'Thumbnail (150x150)', 'mn-elements' ),
					'medium' => esc_html__( 'Medium (300x300)', 'mn-elements' ),
					'medium_large' => esc_html__( 'Medium Large (768x768)', 'mn-elements' ),
					'large' => esc_html__( 'Large (1024x1024)', 'mn-elements' ),
					'full' => esc_html__( 'Full Size', 'mn-elements' ),
					'custom' => esc_html__( 'Custom', 'mn-elements' ),
				],
			]
		);

		$this->add_responsive_control(
			'custom_image_width',
			[
				'label' => esc_html__( 'Custom Width', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 2000,
						'step' => 10,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 300,
				],
				'condition' => [
					'image_resolution' => 'custom',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-logo-image img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'custom_image_height',
			[
				'label' => esc_html__( 'Custom Height', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 2000,
						'step' => 10,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 200,
				],
				'condition' => [
					'image_resolution' => 'custom',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-logo-image img' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'custom_image_object_fit',
			[
				'label' => esc_html__( 'Object Fit', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'contain',
				'options' => [
					'contain' => esc_html__( 'Contain', 'mn-elements' ),
					'cover' => esc_html__( 'Cover', 'mn-elements' ),
					'fill' => esc_html__( 'Fill', 'mn-elements' ),
					'scale-down' => esc_html__( 'Scale Down', 'mn-elements' ),
					'none' => esc_html__( 'None', 'mn-elements' ),
				],
				'condition' => [
					'image_resolution' => 'custom',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-logo-image img' => 'object-fit: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'image_stretch',
			[
				'label' => esc_html__( 'Image Stretch', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'description' => esc_html__( 'Stretch images to fill container', 'mn-elements' ),
			]
		);

		$this->add_control(
			'enable_link',
			[
				'label' => esc_html__( 'Enable Link', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'description' => esc_html__( 'Enable clickable links for logos', 'mn-elements' ),
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

		// Carousel specific controls
		$this->add_control(
			'carousel_heading',
			[
				'label' => esc_html__( 'Carousel Settings', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'display_type' => 'carousel',
				],
			]
		);

		$this->add_control(
			'carousel_speed',
			[
				'label' => esc_html__( 'Animation Speed (ms)', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 3000,
				'min' => 1000,
				'max' => 30000,
				'step' => 500,
				'description' => esc_html__( 'Animation duration in milliseconds. Higher values = slower animation.', 'mn-elements' ),
				'condition' => [
					'display_type' => 'carousel',
				],
			]
		);

		$this->add_control(
			'carousel_pause_on_hover',
			[
				'label' => esc_html__( 'Pause on Hover', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'display_type' => 'carousel',
				],
			]
		);

		$this->add_control(
			'carousel_direction',
			[
				'label' => esc_html__( 'Direction', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'ltr' => esc_html__( 'Left to Right', 'mn-elements' ),
					'rtl' => esc_html__( 'Right to Left', 'mn-elements' ),
				],
				'default' => 'ltr',
				'description' => esc_html__( 'Animation direction for carousel', 'mn-elements' ),
				'condition' => [
					'display_type' => 'carousel',
				],
			]
		);

		$this->end_controls_section();

		// Theme Section
		$this->start_controls_section(
			'section_theme',
			[
				'label' => esc_html__( 'Theme Version', 'mn-elements' ),
			]
		);

		$this->add_control(
			'theme_version',
			[
				'label' => esc_html__( 'Theme Version', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Dark', 'mn-elements' ),
				'label_off' => esc_html__( 'Light', 'mn-elements' ),
				'default' => '',
				'description' => esc_html__( 'Toggle between light and dark theme versions', 'mn-elements' ),
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register style controls.
	 */
	protected function register_style_controls() {
		// General Style
		$this->start_controls_section(
			'section_general_style',
			[
				'label' => esc_html__( 'General', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'column_gap',
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
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-logolist-grid .mn-logolist-container' => 'gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-logolist-list .mn-logo-item:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Logo Item Style
		$this->start_controls_section(
			'section_logo_item_style',
			[
				'label' => esc_html__( 'Logo Item', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'logo_item_border',
				'selector' => '{{WRAPPER}} .mn-logo-item',
			]
		);

		$this->add_control(
			'logo_item_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-logo-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'logo_item_box_shadow',
				'selector' => '{{WRAPPER}} .mn-logo-item',
			]
		);

		$this->add_responsive_control(
			'logo_item_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-logo-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'logo_image_height',
			[
				'label' => esc_html__( 'Image Container Height', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vh' ],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
						'step' => 1,
					],
					'vh' => [
						'min' => 10,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 80,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-logo-image' => 'height: {{SIZE}}{{UNIT}};',
				],
				'description' => esc_html__( 'Set the height of logo image container. Default is 80px for grid/carousel and 60px for list layout.', 'mn-elements' ),
			]
		);

		$this->add_responsive_control(
			'logo_image_width',
			[
				'label' => esc_html__( 'Image Width', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vw' ],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
						'step' => 1,
					],
					'vw' => [
						'min' => 10,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 150,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-logo-image' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-logo-image img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'description' => esc_html__( 'Set the width of logo image. This controls the actual image size.', 'mn-elements' ),
				'condition' => [
					'image_as_background' => 'no',
				],
			]
		);

		$this->add_responsive_control(
			'logo_image_size_direct',
			[
				'label' => esc_html__( 'Image Size (Direct)', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 150,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-logo-image' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'description' => esc_html__( 'Set both width and height of logo image for square dimensions. Best for background images.', 'mn-elements' ),
				'condition' => [
					'image_as_background' => 'yes',
				],
			]
		);

		$this->add_control(
			'image_as_background',
			[
				'label' => esc_html__( 'Make Image as Background', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'description' => esc_html__( 'Use logo as background image instead of img tag for better control over dimensions', 'mn-elements' ),
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'logo_image_alignment',
			[
				'label' => esc_html__( 'Image Alignment', 'mn-elements' ),
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
				'default' => 'center',
				'toggle' => false,
				'selectors' => [
					'{{WRAPPER}} .mn-logo-image' => 'justify-content: {{left:flex-start}}{{center:center}}{{right:flex-end}} !important;',
				],
				'condition' => [
					'image_as_background' => 'no',
				],
			]
		);

		$this->add_responsive_control(
			'logo_image_vertical_alignment',
			[
				'label' => esc_html__( 'Image Vertical Alignment', 'mn-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'mn-elements' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => esc_html__( 'Middle', 'mn-elements' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'mn-elements' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'middle',
				'toggle' => false,
				'selectors' => [
					'{{WRAPPER}} .mn-logo-image' => 'align-items: {{top:flex-start}}{{middle:center}}{{bottom:flex-end}} !important;',
				],
				'condition' => [
					'image_as_background' => 'no',
				],
			]
		);

		$this->add_control(
			'logo_item_background_color',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-logo-item' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_position_heading',
			[
				'label' => esc_html__( 'Title Position', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'title_position',
			[
				'label' => esc_html__( 'Title Position', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'bottom',
				'options' => [
					'top' => esc_html__( 'Top', 'mn-elements' ),
					'middle' => esc_html__( 'Middle', 'mn-elements' ),
					'bottom' => esc_html__( 'Bottom', 'mn-elements' ),
				],
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'title_alignment',
			[
				'label' => esc_html__( 'Title Alignment', 'mn-elements' ),
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
				'toggle' => false,
				'selectors' => [
					'{{WRAPPER}} .mn-logo-title' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'background_size',
			[
				'label' => esc_html__( 'Background Size', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'contain',
				'options' => [
					'auto' => esc_html__( 'Auto', 'mn-elements' ),
					'cover' => esc_html__( 'Cover', 'mn-elements' ),
					'contain' => esc_html__( 'Contain', 'mn-elements' ),
				],
				'selectors' => [
					'{{WRAPPER}} .mn-logo-image' => 'background-size: {{VALUE}};',
				],
				'condition' => [
					'image_as_background' => 'yes',
				],
			]
		);

		$this->add_control(
			'background_position',
			[
				'label' => esc_html__( 'Background Position', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'center center',
				'options' => [
					'left top' => esc_html__( 'Left Top', 'mn-elements' ),
					'left center' => esc_html__( 'Left Center', 'mn-elements' ),
					'left bottom' => esc_html__( 'Left Bottom', 'mn-elements' ),
					'center top' => esc_html__( 'Center Top', 'mn-elements' ),
					'center center' => esc_html__( 'Center Center', 'mn-elements' ),
					'center bottom' => esc_html__( 'Center Bottom', 'mn-elements' ),
					'right top' => esc_html__( 'Right Top', 'mn-elements' ),
					'right center' => esc_html__( 'Right Center', 'mn-elements' ),
					'right bottom' => esc_html__( 'Right Bottom', 'mn-elements' ),
				],
				'selectors' => [
					'{{WRAPPER}} .mn-logo-image' => 'background-position: {{VALUE}};',
				],
				'condition' => [
					'image_as_background' => 'yes',
				],
			]
		);

		$this->add_control(
			'logo_item_hover_background_color',
			[
				'label' => esc_html__( 'Hover Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-logo-item:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Typography Section
		$this->start_controls_section(
			'section_typography',
			[
				'label' => esc_html__( 'Typography', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => esc_html__( 'Title Typography', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-logo-title',
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		// Colors Section
		$this->start_controls_section(
			'section_colors',
			[
				'label' => esc_html__( 'Colors', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Title Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-logo-title' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'title_hover_color',
			[
				'label' => esc_html__( 'Title Hover Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-logo-item:hover .mn-logo-title' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Get available post types
	 */
	private function get_post_types() {
		$post_types = get_post_types( [ 'public' => true ], 'objects' );
		$options = [];
		foreach ( $post_types as $post_type ) {
			$options[ $post_type->name ] = $post_type->label;
		}
		return $options;
	}

	/**
	 * Get all public taxonomies grouped by post type
	 */
	private function get_all_taxonomies() {
		$options = [ '' => esc_html__( 'All', 'mn-elements' ) ];
		
		// Get all public post types
		$post_types = get_post_types( [ 'public' => true ], 'objects' );
		
		foreach ( $post_types as $post_type ) {
			$taxonomies = get_object_taxonomies( $post_type->name, 'objects' );
			
			foreach ( $taxonomies as $taxonomy ) {
				if ( $taxonomy->public && ! isset( $options[ $taxonomy->name ] ) ) {
					// Add post type label as prefix for clarity
					$label = $taxonomy->label;
					if ( count( $taxonomy->object_type ) > 1 ) {
						// If taxonomy is used by multiple post types, show all
						$label .= ' (' . implode( ', ', array_map( function( $pt ) {
							$pt_obj = get_post_type_object( $pt );
							return $pt_obj ? $pt_obj->label : $pt;
						}, $taxonomy->object_type ) ) . ')';
					} else {
						// Show single post type
						$label .= ' (' . $post_type->label . ')';
					}
					$options[ $taxonomy->name ] = $label;
				}
			}
		}
		
		return $options;
	}

	/**
	 * Get taxonomies for a specific post type
	 */
	private function get_taxonomies_for_post_type( $post_type ) {
		$taxonomies = get_object_taxonomies( $post_type, 'objects' );
		$options = [ '' => esc_html__( 'All', 'mn-elements' ) ];
		foreach ( $taxonomies as $taxonomy ) {
			if ( $taxonomy->public ) {
				$options[ $taxonomy->name ] = $taxonomy->label;
			}
		}
		return $options;
	}

	/**
	 * Get meta field value with support for ACF, JetEngine, and WordPress meta
	 */
	private function get_meta_field_value( $post_id, $meta_key ) {
		if ( empty( $meta_key ) ) {
			return '';
		}

		$value = '';

		// Try JetEngine first
		if ( function_exists( 'jet_engine' ) ) {
			$value = get_post_meta( $post_id, $meta_key, true );
			if ( ! empty( $value ) ) {
				return $value;
			}
		}

		// Try ACF
		if ( function_exists( 'get_field' ) ) {
			$acf_value = get_field( $meta_key, $post_id );
			if ( ! empty( $acf_value ) ) {
				return $acf_value;
			}
		}

		// Fallback to WordPress meta
		$value = get_post_meta( $post_id, $meta_key, true );

		return $value;
	}

	/**
	 * Get logos from dynamic source
	 */
	private function get_dynamic_logos( $settings ) {
		$logos = [];
		
		$args = [
			'post_type' => isset( $settings['dynamic_post_type'] ) ? $settings['dynamic_post_type'] : 'post',
			'posts_per_page' => isset( $settings['dynamic_posts_per_page'] ) ? intval( $settings['dynamic_posts_per_page'] ) : 10,
			'orderby' => isset( $settings['dynamic_orderby'] ) ? $settings['dynamic_orderby'] : 'date',
			'order' => isset( $settings['dynamic_order'] ) ? $settings['dynamic_order'] : 'DESC',
			'post_status' => 'publish',
			'ignore_sticky_posts' => true,
		];

		// Add taxonomy filter if specified
		if ( ! empty( $settings['dynamic_taxonomy'] ) ) {
			$taxonomy = $settings['dynamic_taxonomy'];
			$terms = [];
			
			if ( ! empty( $settings['dynamic_terms'] ) ) {
				$term_ids = explode( ',', $settings['dynamic_terms'] );
				$terms = array_map( 'intval', array_map( 'trim', $term_ids ) );
			}
			
			if ( ! empty( $terms ) ) {
				$args['tax_query'] = [
					[
						'taxonomy' => $taxonomy,
						'field' => 'term_id',
						'terms' => $terms,
					],
				];
			}
		}

		$query = new \WP_Query( $args );

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$post_id = get_the_ID();
				
				// Get logo image (supports ACF, JetEngine, WordPress meta)
				$logo_meta_key = ! empty( $settings['dynamic_logo_meta_key'] ) ? $settings['dynamic_logo_meta_key'] : 'logo_image';
				$logo_value = $this->get_meta_field_value( $post_id, $logo_meta_key );
				
				// Handle both image ID and URL
				$logo_url = '';
				if ( is_array( $logo_value ) && isset( $logo_value['url'] ) ) {
					// ACF image field
					$logo_url = $logo_value['url'];
				} elseif ( is_numeric( $logo_value ) ) {
					// It's an attachment ID
					$logo_url = wp_get_attachment_image_url( $logo_value, 'full' );
				} elseif ( filter_var( $logo_value, FILTER_VALIDATE_URL ) ) {
					// It's a URL
					$logo_url = $logo_value;
				}
				
				if ( empty( $logo_url ) ) {
					continue; // Skip if no logo image
				}
				
				// Get title
				$title = '';
				if ( isset( $settings['dynamic_title_source'] ) && $settings['dynamic_title_source'] === 'meta_field' ) {
					$title_meta_key = ! empty( $settings['dynamic_title_meta_key'] ) ? $settings['dynamic_title_meta_key'] : '';
					if ( $title_meta_key ) {
						$title = $this->get_meta_field_value( $post_id, $title_meta_key );
					}
				}
				if ( empty( $title ) ) {
					$title = get_the_title();
				}
				
				// Get description (optional)
				$description = '';
				if ( ! empty( $settings['dynamic_description_meta_key'] ) ) {
					$description = $this->get_meta_field_value( $post_id, $settings['dynamic_description_meta_key'] );
				}
				
				// Get link
				$link = '';
				$link_type = 'url';
				if ( isset( $settings['dynamic_link_source'] ) ) {
					if ( $settings['dynamic_link_source'] === 'post_url' ) {
						$link = get_permalink();
					} elseif ( $settings['dynamic_link_source'] === 'meta_field' && ! empty( $settings['dynamic_link_meta_key'] ) ) {
						$link = $this->get_meta_field_value( $post_id, $settings['dynamic_link_meta_key'] );
					}
				}
				
				$logos[] = [
					'logo_image' => [
						'url' => $logo_url,
						'id' => is_numeric( $logo_value ) ? $logo_value : 0,
					],
					'logo_title' => $title,
					'logo_description' => $description,
					'link_type' => $link_type,
					'custom_url' => [
						'url' => $link,
						'is_external' => false,
						'nofollow' => false,
					],
				];
			}
			wp_reset_postdata();
		}

		return $logos;
	}

	/**
	 * Render widget output on the frontend.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		// Get logos based on source
		$logo_list = [];
		if ( isset( $settings['logo_source'] ) && $settings['logo_source'] === 'dynamic' ) {
			$logo_list = $this->get_dynamic_logos( $settings );
		} else {
			$logo_list = isset( $settings['logo_list'] ) ? $settings['logo_list'] : [];
		}

		if ( empty( $logo_list ) ) {
			return;
		}

		$theme_class = $settings['theme_version'] ? 'mn-theme-dark' : 'mn-theme-light';
		$display_class = 'mn-logolist-' . $settings['display_type'];
		$stretch_class = $settings['image_stretch'] === 'yes' ? 'mn-image-stretch' : '';

		$this->add_render_attribute( 'wrapper', 'class', [
			'mn-logolist-wrapper',
			$theme_class,
			$display_class,
			$stretch_class
		] );

		// Add data attribute for custom resolution
		if ( $settings['image_resolution'] === 'custom' ) {
			$this->add_render_attribute( 'wrapper', 'data-image-resolution', 'custom' );
		}

		if ( $settings['display_type'] === 'carousel' ) {
			$this->add_render_attribute( 'wrapper', 'data-carousel-speed', $settings['carousel_speed'] );
			$this->add_render_attribute( 'wrapper', 'data-carousel-slides', $settings['carousel_slides_to_show'] );
			$this->add_render_attribute( 'wrapper', 'data-pause-hover', $settings['carousel_pause_on_hover'] );
			$this->add_render_attribute( 'wrapper', 'data-carousel-direction', $settings['carousel_direction'] );
		}

		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<div class="mn-logolist-container">
				<?php
				foreach ( $logo_list as $index => $item ) :
					$this->render_logo_item( $item, $settings, $index );
				endforeach;
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render single logo item
	 */
	private function render_logo_item( $item, $settings, $index ) {
		$link_type = $item['link_type'] ?? 'url';
		$has_link = $settings['enable_link'] === 'yes';
		$is_lightbox = $link_type === 'media' && ! empty( $item['media_file']['url'] );
		$is_custom_url = $link_type === 'url' && ! empty( $item['custom_url']['url'] );
		
		if ( $has_link && $is_custom_url ) {
			$this->add_link_attributes( 'link_' . $index, $item['custom_url'] );
		}

		// Get image URL based on resolution setting
		$image_url = $this->get_image_url( $item['logo_image'], $settings['image_resolution'] );
		
		// Get title position settings
		$title_position = $settings['title_position'] ?? 'bottom';
		$title_alignment = $settings['title_alignment'] ?? 'center';
		$image_as_background = $settings['image_as_background'] === 'yes';
		
		// Build content classes based on title position
		$content_classes = ['mn-logo-content'];
		if ( $settings['display_type'] === 'list' ) {
			$content_classes[] = 'mn-logo-list-layout';
		} else {
			$content_classes[] = 'mn-logo-title-' . $title_position;
		}
		
		// Build image classes
		$image_classes = ['mn-logo-image'];
		if ( $image_as_background ) {
			$image_classes[] = 'mn-logo-background';
		}
		?>
		<div class="mn-logo-item">
			<?php if ( $has_link && $is_lightbox ) : ?>
				<?php
				$media_url = $item['media_file']['url'];
				$media_type = $this->get_media_type( $media_url );
				?>
				<a href="#" 
				   class="mn-logo-link mn-lightbox-trigger" 
				   data-media-url="<?php echo esc_url( $media_url ); ?>" 
				   data-media-type="<?php echo esc_attr( $media_type ); ?>"
				   data-media-title="<?php echo esc_attr( $item['logo_title'] ); ?>">
			<?php elseif ( $has_link && $is_custom_url ) : ?>
				<a <?php $this->print_render_attribute_string( 'link_' . $index ); ?> class="mn-logo-link">
			<?php endif; ?>
			
			<div class="<?php echo esc_attr( implode( ' ', $content_classes ) ); ?>">
				<?php 
				// Render title at top if position is set to top
				if ( $settings['show_title'] && ! empty( $item['logo_title'] ) && $title_position === 'top' ) : 
				?>
					<div class="mn-logo-title"><?php echo esc_html( $item['logo_title'] ); ?></div>
				<?php endif; ?>
				
				<?php if ( ! empty( $image_url ) ) : ?>
					<div class="<?php echo esc_attr( implode( ' ', $image_classes ) ); ?>" 
						<?php if ( $image_as_background ) : ?>
							style="background-image: url('<?php echo esc_url( $image_url ); ?>');"
						<?php endif; ?>>
						<?php if ( ! $image_as_background ) : ?>
							<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $item['logo_title'] ); ?>">
						<?php endif; ?>
					</div>
				<?php endif; ?>
				
				<?php 
				// Render title at middle if position is set to middle
				if ( $settings['show_title'] && ! empty( $item['logo_title'] ) && $title_position === 'middle' ) : 
				?>
					<div class="mn-logo-title mn-title-middle"><?php echo esc_html( $item['logo_title'] ); ?></div>
				<?php endif; ?>
				
				<?php 
				// Render title at bottom (default) if position is set to bottom or not specified
				if ( $settings['show_title'] && ! empty( $item['logo_title'] ) && $title_position === 'bottom' ) : 
				?>
					<div class="mn-logo-title"><?php echo esc_html( $item['logo_title'] ); ?></div>
				<?php endif; ?>
			</div>
			
			<?php if ( $has_link && ( $is_lightbox || $is_custom_url ) ) : ?>
				</a>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Get image URL based on resolution setting
	 */
	private function get_image_url( $image, $resolution ) {
		if ( empty( $image['id'] ) ) {
			return $image['url'] ?? '';
		}

		// For custom resolution, return the full size image
		// CSS will handle the sizing through the custom width/height controls
		if ( $resolution === 'custom' ) {
			$image_url = wp_get_attachment_image_url( $image['id'], 'full' );
			return $image_url ? $image_url : $image['url'];
		}

		$image_url = wp_get_attachment_image_url( $image['id'], $resolution );
		return $image_url ? $image_url : $image['url'];
	}

	/**
	 * Get media type from URL
	 */
	private function get_media_type( $url ) {
		$extension = strtolower( pathinfo( $url, PATHINFO_EXTENSION ) );
		
		$image_extensions = [ 'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp' ];
		$video_extensions = [ 'mp4', 'webm', 'ogg', 'mov', 'avi' ];
		
		if ( in_array( $extension, $image_extensions ) ) {
			return 'image';
		} elseif ( in_array( $extension, $video_extensions ) ) {
			return 'video';
		}
		
		return 'image'; // Default to image
	}
}
