<?php
namespace MN_Elements\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Icons_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * MN Posts Widget
 *
 * Enhanced posts widget with dark/light theme switcher and animated readmore button
 *
 * @since 1.0.3
 */
class MN_Posts extends Widget_Base {

	/**
	 * Get widget name.
	 */
	public function get_name() {
		return 'mn-posts';
	}

	/**
	 * Get widget title.
	 */
	public function get_title() {
		return esc_html__( 'MN Posts', 'mn-elements' );
	}

	/**
	 * Get widget icon.
	 */
	public function get_icon() {
		return 'eicon-post-list';
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
		return [ 'posts', 'blog', 'news', 'articles', 'mn', 'dark', 'light', 'theme', 'woocommerce', 'product' ];
	}

	/**
	 * Get style dependencies.
	 */
	public function get_style_depends() {
		return [ 'mn-posts-style' ];
	}

	/**
	 * Get script dependencies.
	 */
	public function get_script_depends() {
		return [ 'mn-posts-script' ];
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
		// Query Section
		$this->start_controls_section(
			'section_query',
			[
				'label' => esc_html__( 'Query', 'mn-elements' ),
			]
		);

		$this->add_control(
			'query_type',
			[
				'label' => esc_html__( 'Query Type', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'recent',
				'options' => [
					'recent' => esc_html__( 'Recent Posts', 'mn-elements' ),
					'all' => esc_html__( 'All Posts', 'mn-elements' ),
					'single' => esc_html__( 'Single Post', 'mn-elements' ),
					'archive' => esc_html__( 'Archive Query (Current Archive)', 'mn-elements' ),
				],
				'description' => esc_html__( 'Archive Query will automatically use the current archive page context (category, tag, etc.)', 'mn-elements' ),
			]
		);

		$this->add_control(
			'single_post_id',
			[
				'label' => esc_html__( 'Post ID', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => '',
				'placeholder' => esc_html__( 'Enter Post ID', 'mn-elements' ),
				'description' => esc_html__( 'Enter the ID of the specific post you want to display', 'mn-elements' ),
				'condition' => [
					'query_type' => 'single',
				],
			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'label' => esc_html__( 'Posts Per Page', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 6,
				'min' => 1,
				'max' => 100,
				'condition' => [
					'query_type!' => 'single',
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
			'taxonomy',
			[
				'label' => esc_html__( 'Taxonomy', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => $this->get_taxonomies(),
				'description' => esc_html__( 'Select a taxonomy to filter posts by', 'mn-elements' ),
				'condition' => [
					'query_type!' => 'archive',
				],
			]
		);

		$this->add_control(
			'taxonomy_ids',
			[
				'label' => esc_html__( 'Taxonomy IDs', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => esc_html__( 'Enter taxonomy term IDs (comma separated)', 'mn-elements' ),
				'description' => esc_html__( 'Enter specific taxonomy term IDs to filter posts. Leave empty to show all posts from selected taxonomy.', 'mn-elements' ),
				'condition' => [
					'taxonomy!' => '',
					'query_type!' => 'archive',
				],
			]
		);

		$this->add_control(
			'custom_meta_subheading',
			[
				'label' => esc_html__( 'Custom Meta Field', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'custom_meta_field',
			[
				'label' => esc_html__( 'Meta Field Name', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => esc_html__( 'Enter custom meta field name', 'mn-elements' ),
				'description' => esc_html__( 'Enter the name of a custom meta field to display in post meta section', 'mn-elements' ),
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
					'event_date' => esc_html__( 'Event Date (The Events Calendar)', 'mn-elements' ),
				],
			]
		);

		// Event Date Order Options (for The Events Calendar)
		$this->add_control(
			'event_date_type',
			[
				'label' => esc_html__( 'Event Date Type', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'upcoming',
				'options' => [
					'upcoming' => esc_html__( 'Upcoming Events Only', 'mn-elements' ),
					'past' => esc_html__( 'Past Events Only', 'mn-elements' ),
					'all' => esc_html__( 'All Events', 'mn-elements' ),
				],
				'description' => esc_html__( 'Filter events based on their start/end date. Upcoming shows events happening today or in the future.', 'mn-elements' ),
				'condition' => [
					'orderby' => 'event_date',
				],
			]
		);

		$this->add_control(
			'event_date_field',
			[
				'label' => esc_html__( 'Order By Field', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => '_EventStartDate',
				'options' => [
					'_EventStartDate' => esc_html__( 'Event Start Date', 'mn-elements' ),
					'_EventEndDate' => esc_html__( 'Event End Date', 'mn-elements' ),
				],
				'description' => esc_html__( 'Choose which date field to use for ordering.', 'mn-elements' ),
				'condition' => [
					'orderby' => 'event_date',
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

		// Exclude Section
		$this->add_control(
			'exclude_heading',
			[
				'label' => esc_html__( 'Exclude', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'query_type!' => 'single',
				],
			]
		);

		$this->add_control(
			'exclude_current',
			[
				'label' => esc_html__( 'Exclude Current Post', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'description' => esc_html__( 'Exclude the current post from the query (useful for related posts)', 'mn-elements' ),
				'condition' => [
					'query_type!' => 'single',
				],
			]
		);

		$this->add_control(
			'exclude_ids',
			[
				'label' => esc_html__( 'Exclude by Post IDs', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => esc_html__( 'Enter post IDs (comma separated)', 'mn-elements' ),
				'description' => esc_html__( 'Enter specific post IDs to exclude from the query', 'mn-elements' ),
				'condition' => [
					'query_type!' => 'single',
				],
			]
		);

		$this->add_control(
			'exclude_by_offset',
			[
				'label' => esc_html__( 'Offset', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 100,
				'description' => esc_html__( 'Skip this number of posts from the beginning', 'mn-elements' ),
				'condition' => [
					'query_type!' => 'single',
				],
			]
		);

		// Pagination Controls
		$this->add_control(
			'pagination_heading',
			[
				'label' => esc_html__( 'Pagination', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'query_type!' => 'single',
				],
			]
		);

		$this->add_control(
			'enable_pagination',
			[
				'label' => esc_html__( 'Enable Pagination', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'description' => esc_html__( 'Enable pagination for posts listing', 'mn-elements' ),
				'condition' => [
					'query_type!' => 'single',
				],
			]
		);

		$this->add_control(
			'pagination_type',
			[
				'label' => esc_html__( 'Pagination Type', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'numbers',
				'options' => [
					'numbers' => esc_html__( 'Numbers', 'mn-elements' ),
					'prev_next' => esc_html__( 'Previous/Next Only', 'mn-elements' ),
					'numbers_prev_next' => esc_html__( 'Numbers + Previous/Next', 'mn-elements' ),
				],
				'condition' => [
					'enable_pagination' => 'yes',
					'query_type!' => 'single',
				],
			]
		);

		$this->add_control(
			'pagination_page_limit',
			[
				'label' => esc_html__( 'Page Limit', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5,
				'min' => 3,
				'max' => 20,
				'description' => esc_html__( 'Maximum number of page links to show', 'mn-elements' ),
				'condition' => [
					'enable_pagination' => 'yes',
					'pagination_type!' => 'prev_next',
					'query_type!' => 'single',
				],
			]
		);

		$this->add_control(
			'pagination_prev_text',
			[
				'label' => esc_html__( 'Previous Text', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Previous', 'mn-elements' ),
				'condition' => [
					'enable_pagination' => 'yes',
					'pagination_type!' => 'numbers',
					'query_type!' => 'single',
				],
			]
		);

		$this->add_control(
			'pagination_next_text',
			[
				'label' => esc_html__( 'Next Text', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Next', 'mn-elements' ),
				'condition' => [
					'enable_pagination' => 'yes',
					'pagination_type!' => 'numbers',
					'query_type!' => 'single',
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
			'layout_template',
			[
				'label' => esc_html__( 'Layout Template', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'grid-general',
				'options' => [
					'grid-general' => esc_html__( 'Grid - General (Vertical)', 'mn-elements' ),
					'grid-inline' => esc_html__( 'Grid - Inline (Horizontal)', 'mn-elements' ),
					'grid-3column' => esc_html__( 'Grid - 3 Column (Image | Title | Content)', 'mn-elements' ),
					'grid-mixed' => esc_html__( 'Grid - Mixed (2 Row Layout)', 'mn-elements' ),
					'grid-overlay' => esc_html__( 'Grid - Overlay (Image Background)', 'mn-elements' ),
					'list-general' => esc_html__( 'List - General (Vertical)', 'mn-elements' ),
					'list-inline' => esc_html__( 'List - Inline (Horizontal)', 'mn-elements' ),
					'list-3column' => esc_html__( 'List - 3 Column (Image | Title | Content)', 'mn-elements' ),
					'list-mixed' => esc_html__( 'List - Mixed (2 Row Layout)', 'mn-elements' ),
					'list-overlay' => esc_html__( 'List - Overlay (Image Background)', 'mn-elements' ),
					'custom' => esc_html__( 'Custom Order', 'mn-elements' ),
				],
				'description' => esc_html__( 'Grid: Multiple columns. List: Single column stacked. Templates define item structure.', 'mn-elements' ),
			]
		);

		$this->add_control(
			'element_order',
			[
				'label' => esc_html__( 'Element Order', 'mn-elements' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'default' => [ 'image', 'title', 'meta', 'excerpt', 'custom_meta', 'readmore' ],
				'options' => [
					'image' => esc_html__( 'Featured Image', 'mn-elements' ),
					'title' => esc_html__( 'Title', 'mn-elements' ),
					'taxonomy' => esc_html__( 'Taxonomy', 'mn-elements' ),
					'meta' => esc_html__( 'Meta (Date, Author)', 'mn-elements' ),
					'excerpt' => esc_html__( 'Excerpt', 'mn-elements' ),
					'custom_meta' => esc_html__( 'Custom Meta', 'mn-elements' ),
					'readmore' => esc_html__( 'Read More Button', 'mn-elements' ),
				],
				'label_block' => true,
				'description' => esc_html__( 'Drag to reorder elements. Only works with Custom Order template.', 'mn-elements' ),
				'condition' => [
					'layout_template' => 'custom',
				],
			]
		);

		$this->add_control(
			'show_taxonomy',
			[
				'label' => esc_html__( 'Show Taxonomy', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'description' => esc_html__( 'Display post categories or tags', 'mn-elements' ),
			]
		);

		$this->add_control(
			'taxonomy_to_show',
			[
				'label' => esc_html__( 'Taxonomy to Display', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'category',
				'options' => [
					'category' => esc_html__( 'Categories', 'mn-elements' ),
					'post_tag' => esc_html__( 'Tags', 'mn-elements' ),
				],
				'condition' => [
					'show_taxonomy' => 'yes',
				],
			]
		);

		$this->add_control(
			'taxonomy_limit',
			[
				'label' => esc_html__( 'Taxonomy Limit', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 3,
				'min' => 1,
				'max' => 10,
				'description' => esc_html__( 'Maximum number of taxonomy terms to display', 'mn-elements' ),
				'condition' => [
					'show_taxonomy' => 'yes',
				],
			]
		);

		$this->add_control(
			'section_title',
			[
				'label' => esc_html__( 'Section Title', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => esc_html__( 'Latest Posts', 'mn-elements' ),
				'description' => esc_html__( 'Enter a custom title to display above the posts', 'mn-elements' ),
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label' => esc_html__( 'Title HTML Tag', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'h2',
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
				],
				'condition' => [
					'section_title!' => '',
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
					'{{WRAPPER}} .mn-posts-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				],
				'condition' => [
					'layout_template' => [
						'grid-general',
						'grid-inline',
						'grid-3column',
						'grid-mixed',
						'grid-overlay',
					],
				],
			]
		);

		$this->add_control(
			'responsive_columns_heading',
			[
				'label' => esc_html__( 'Responsive Columns', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'layout_template' => [
						'grid-general',
						'grid-inline',
						'grid-3column',
						'grid-mixed',
						'grid-overlay',
					],
				],
			]
		);

		$this->add_control(
			'columns_desktop',
			[
				'label' => esc_html__( 'Desktop (>1200px)', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => esc_html__( 'Default', 'mn-elements' ),
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
				'condition' => [
					'layout_template' => [
						'grid-general',
						'grid-inline',
						'grid-3column',
						'grid-mixed',
						'grid-overlay',
					],
				],
			]
		);

		$this->add_control(
			'columns_laptop',
			[
				'label' => esc_html__( 'Laptop (1025px - 1200px)', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => esc_html__( 'Default', 'mn-elements' ),
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
				'condition' => [
					'layout_template' => [
						'grid-general',
						'grid-inline',
						'grid-3column',
						'grid-mixed',
						'grid-overlay',
					],
				],
			]
		);

		$this->add_control(
			'columns_tablet_landscape',
			[
				'label' => esc_html__( 'Tablet Landscape (769px - 1024px)', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => esc_html__( 'Default', 'mn-elements' ),
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
				'condition' => [
					'layout_template' => [
						'grid-general',
						'grid-inline',
						'grid-3column',
						'grid-mixed',
						'grid-overlay',
					],
				],
			]
		);

		$this->add_control(
			'columns_tablet_portrait',
			[
				'label' => esc_html__( 'Tablet Portrait (481px - 768px)', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => esc_html__( 'Default', 'mn-elements' ),
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
				'condition' => [
					'layout_template' => [
						'grid-general',
						'grid-inline',
						'grid-3column',
						'grid-mixed',
						'grid-overlay',
					],
				],
			]
		);

		$this->add_control(
			'columns_mobile_landscape',
			[
				'label' => esc_html__( 'Mobile Landscape (376px - 480px)', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => esc_html__( 'Default', 'mn-elements' ),
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
				],
				'condition' => [
					'layout_template' => [
						'grid-general',
						'grid-inline',
						'grid-3column',
						'grid-mixed',
						'grid-overlay',
					],
				],
			]
		);

		$this->add_control(
			'columns_mobile_portrait',
			[
				'label' => esc_html__( 'Mobile Portrait (<375px)', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => esc_html__( 'Default', 'mn-elements' ),
					'1' => '1',
					'2' => '2',
				],
				'condition' => [
					'layout_template' => [
						'grid-general',
						'grid-inline',
						'grid-3column',
						'grid-mixed',
						'grid-overlay',
					],
				],
			]
		);

		$this->add_control(
			'equal_column_height',
			[
				'label' => esc_html__( 'Equal Column Height', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'description' => esc_html__( 'Make all post items the same height. Images will be displayed fully without cropping.', 'mn-elements' ),
				'condition' => [
					'layout_template' => [
						'grid-general',
						'grid-inline',
						'grid-3column',
						'grid-mixed',
						'grid-overlay',
					],
				],
			]
		);

		$this->add_control(
			'image_fit_mode',
			[
				'label' => esc_html__( 'Image Fit Mode', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'contain',
				'options' => [
					'contain' => esc_html__( 'Contain (Show Full Image)', 'mn-elements' ),
					'cover' => esc_html__( 'Cover (Fill & Crop)', 'mn-elements' ),
				],
				'description' => esc_html__( 'Contain: Shows the entire image without cropping. Cover: Fills the container and crops if needed.', 'mn-elements' ),
				'condition' => [
					'equal_column_height' => 'yes',
					'show_image' => 'yes',
				],
			]
		);

		$this->add_control(
			'image_background_color',
			[
				'label' => esc_html__( 'Image Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#f5f5f5',
				'description' => esc_html__( 'Background color visible when image does not fill the container (for contain mode).', 'mn-elements' ),
				'selectors' => [
					'{{WRAPPER}} .mn-equal-height .mn-post-image' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'equal_column_height' => 'yes',
					'image_fit_mode' => 'contain',
					'show_image' => 'yes',
				],
			]
		);

		$this->add_control(
			'centered_grid',
			[
				'label' => esc_html__( 'Centered Grid', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'description' => esc_html__( 'Center the grid items horizontally when they do not fill the entire row.', 'mn-elements' ),
				'condition' => [
					'layout_template' => [
						'grid-general',
						'grid-inline',
						'grid-3column',
						'grid-mixed',
					],
				],
			]
		);

		$this->add_responsive_control(
			'list_gap',
			[
				'label' => esc_html__( 'Gap Between Items', 'mn-elements' ),
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
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-posts-list' => 'gap: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'layout_template' => [
						'list-general',
						'list-inline',
						'list-3column',
						'list-mixed',
					],
				],
			]
		);

		$this->add_responsive_control(
			'list_image_height',
			[
				'label' => esc_html__( 'Image Height', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 600,
					],
					'vh' => [
						'min' => 10,
						'max' => 80,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 250,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-posts-list .mn-post-image img' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'layout_template' => [
						'list-general',
						'list-inline',
						'list-3column',
						'list-mixed',
					],
					'show_image' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_image',
			[
				'label' => esc_html__( 'Show Featured Image', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

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
					'show_image' => 'yes',
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
				'condition' => [
					'show_excerpt' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_meta',
			[
				'label' => esc_html__( 'Show Meta', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'description' => esc_html__( 'Enable to show post meta information', 'mn-elements' ),
			]
		);

		$this->add_control(
			'show_date',
			[
				'label' => esc_html__( 'Show Date', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'show_meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'date_format',
			[
				'label' => esc_html__( 'Date Format', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'wp_default',
				'options' => [
					'wp_default' => esc_html__( 'WordPress Default', 'mn-elements' ),
					'custom' => esc_html__( 'Custom Format', 'mn-elements' ),
					'F j, Y' => esc_html__( 'December 25, 2023', 'mn-elements' ),
					'M j, Y' => esc_html__( 'Dec 25, 2023', 'mn-elements' ),
					'j M Y' => esc_html__( '25 Dec 2023', 'mn-elements' ),
					'd/m/Y' => esc_html__( '25/12/2023', 'mn-elements' ),
					'm/d/Y' => esc_html__( '12/25/2023', 'mn-elements' ),
					'Y-m-d' => esc_html__( '2023-12-25', 'mn-elements' ),
					'l, F j, Y' => esc_html__( 'Monday, December 25, 2023', 'mn-elements' ),
					'j F Y' => esc_html__( '25 December 2023', 'mn-elements' ),
				],
				'condition' => [
					'show_meta' => 'yes',
					'show_date' => 'yes',
				],
				'description' => esc_html__( 'Choose date format. WordPress Default uses settings from WP Admin > Settings > General.', 'mn-elements' ),
			]
		);

		$this->add_control(
			'custom_date_format',
			[
				'label' => esc_html__( 'Custom Date Format', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'F j, Y',
				'placeholder' => esc_html__( 'Enter PHP date format (e.g., F j, Y)', 'mn-elements' ),
				'condition' => [
					'show_meta' => 'yes',
					'show_date' => 'yes',
					'date_format' => 'custom',
				],
				'description' => esc_html__( 'Use PHP date format codes. Example: F j, Y = "December 25, 2023". See PHP date() documentation for all format codes.', 'mn-elements' ),
			]
		);

		$this->add_control(
			'date_time_separator',
			[
				'label' => esc_html__( 'Date Time Separator', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => ' at ',
				'placeholder' => esc_html__( 'Enter separator text', 'mn-elements' ),
				'condition' => [
					'show_meta' => 'yes',
					'show_date' => 'yes',
				],
				'description' => esc_html__( 'Text to separate date and time. Leave empty to show only date.', 'mn-elements' ),
			]
		);

		$this->add_control(
			'show_time',
			[
				'label' => esc_html__( 'Show Time', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'condition' => [
					'show_meta' => 'yes',
					'show_date' => 'yes',
				],
				'description' => esc_html__( 'Show time along with date', 'mn-elements' ),
			]
		);

		$this->add_control(
			'time_format',
			[
				'label' => esc_html__( 'Time Format', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'wp_default',
				'options' => [
					'wp_default' => esc_html__( 'WordPress Default', 'mn-elements' ),
					'g:i A' => esc_html__( '3:45 PM', 'mn-elements' ),
					'H:i' => esc_html__( '15:45', 'mn-elements' ),
					'g:i' => esc_html__( '3:45', 'mn-elements' ),
					'H:i:s' => esc_html__( '15:45:30', 'mn-elements' ),
				],
				'condition' => [
					'show_meta' => 'yes',
					'show_date' => 'yes',
					'show_time' => 'yes',
				],
				'description' => esc_html__( 'Choose time format. WordPress Default uses settings from WP Admin > Settings > General.', 'mn-elements' ),
			]
		);

		$this->add_control(
			'custom_time_format',
			[
				'label' => esc_html__( 'Custom Time Format', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'g:i A',
				'placeholder' => esc_html__( 'Enter PHP time format (e.g., g:i A)', 'mn-elements' ),
				'condition' => [
					'show_meta' => 'yes',
					'show_date' => 'yes',
					'show_time' => 'yes',
					'time_format' => 'custom',
				],
				'description' => esc_html__( 'Use PHP time format codes. Example: g:i A = "3:45 PM". See PHP date() documentation for all format codes.', 'mn-elements' ),
			]
		);

		$this->add_control(
			'show_author',
			[
				'label' => esc_html__( 'Show Author', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'show_meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_categories',
			[
				'label' => esc_html__( 'Show Categories', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'condition' => [
					'show_meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_tags',
			[
				'label' => esc_html__( 'Show Tags', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'condition' => [
					'show_meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_comments',
			[
				'label' => esc_html__( 'Show Comments Count', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'condition' => [
					'show_meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_custom_meta',
			[
				'label' => esc_html__( 'Show Custom Meta', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'separator' => 'before',
				'condition' => [
					'show_meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'custom_meta_field_listing',
			[
				'label' => esc_html__( 'Custom Meta Field Name', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => esc_html__( 'Enter custom field name', 'mn-elements' ),
				'description' => esc_html__( 'Enter the custom field name to display in post listing', 'mn-elements' ),
				'condition' => [
					'show_meta' => 'yes',
					'show_custom_meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_price',
			[
				'label' => esc_html__( 'Show Price', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before',
				'description' => esc_html__( 'Display product price (WooCommerce products only)', 'mn-elements' ),
				'condition' => [
					'post_type' => 'product',
				],
			]
		);

		$this->add_control(
			'post_link_heading',
			[
				'label' => esc_html__( 'Post Link Options', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'enable_post_link',
			[
				'label' => esc_html__( 'Make Entire Item Clickable', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'description' => esc_html__( 'Enable this to make the entire post item clickable and redirect to the post. This provides better UX as users can click anywhere on the item.', 'mn-elements' ),
			]
		);

		$this->add_control(
			'post_link_target',
			[
				'label' => esc_html__( 'Open in New Tab', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'condition' => [
					'enable_post_link' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_readmore',
			[
				'label' => esc_html__( 'Show Read More Button', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'description' => esc_html__( 'Show read more button. Not needed if "Make Entire Item Clickable" is enabled.', 'mn-elements' ),
			]
		);

		// Element Spacing
		$this->add_control(
			'element_spacing_heading',
			[
				'label' => esc_html__( 'Element Spacing', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'image_spacing',
			[
				'label' => esc_html__( 'Image Bottom Spacing', 'mn-elements' ),
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
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-post-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_image' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'taxonomy_spacing',
			[
				'label' => esc_html__( 'Taxonomy Bottom Spacing', 'mn-elements' ),
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-post-taxonomy' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_taxonomy' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'title_spacing',
			[
				'label' => esc_html__( 'Title Bottom Spacing', 'mn-elements' ),
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
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-post-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'meta_spacing',
			[
				'label' => esc_html__( 'Meta Bottom Spacing', 'mn-elements' ),
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-post-meta' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_meta' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'excerpt_spacing',
			[
				'label' => esc_html__( 'Excerpt Bottom Spacing', 'mn-elements' ),
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
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-post-excerpt' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_excerpt' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'custom_meta_spacing',
			[
				'label' => esc_html__( 'Custom Meta Bottom Spacing', 'mn-elements' ),
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
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-post-custom-meta' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_custom_meta' => 'yes',
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
			'enable_theme_version',
			[
				'label' => esc_html__( 'Enable Theme Version', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'mn-elements' ),
				'label_off' => esc_html__( 'No', 'mn-elements' ),
				'default' => '',
				'description' => esc_html__( 'Enable theme version styling for post items', 'mn-elements' ),
			]
		);

		$this->add_control(
			'theme_version',
			[
				'label' => esc_html__( 'Theme Style', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'light',
				'options' => [
					'light' => esc_html__( 'Light Theme', 'mn-elements' ),
					'dark' => esc_html__( 'Dark Theme', 'mn-elements' ),
				],
				'condition' => [
					'enable_theme_version' => 'yes',
				],
				'description' => esc_html__( 'Select theme style for post items', 'mn-elements' ),
			]
		);

		$this->end_controls_section();

		// WooCommerce Section
		$this->start_controls_section(
			'section_woocommerce',
			[
				'label' => esc_html__( 'WooCommerce', 'mn-elements' ),
				'condition' => [
					'post_type' => 'product',
				],
			]
		);

		$this->add_control(
			'woo_show_price',
			[
				'label' => esc_html__( 'Show Price', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'description' => esc_html__( 'Display product price', 'mn-elements' ),
			]
		);

		$this->add_control(
			'woo_show_add_to_cart',
			[
				'label' => esc_html__( 'Show Add to Cart', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'description' => esc_html__( 'Display Add to Cart button', 'mn-elements' ),
			]
		);

		$this->add_control(
			'woo_add_to_cart_text',
			[
				'label' => esc_html__( 'Add to Cart Text', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Add to Cart', 'mn-elements' ),
				'placeholder' => esc_html__( 'Add to Cart', 'mn-elements' ),
				'condition' => [
					'woo_show_add_to_cart' => 'yes',
				],
			]
		);

		$this->add_control(
			'woo_show_quantity',
			[
				'label' => esc_html__( 'Show Quantity Selector', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'description' => esc_html__( 'Display quantity input with plus/minus buttons', 'mn-elements' ),
				'condition' => [
					'woo_show_add_to_cart' => 'yes',
				],
			]
		);

		$this->add_control(
			'woo_cart_position',
			[
				'label' => esc_html__( 'Cart Button Position', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'beside_readmore',
				'options' => [
					'beside_readmore' => esc_html__( 'Beside Read More', 'mn-elements' ),
					'below_readmore' => esc_html__( 'Below Read More', 'mn-elements' ),
					'above_readmore' => esc_html__( 'Above Read More', 'mn-elements' ),
				],
				'condition' => [
					'woo_show_add_to_cart' => 'yes',
				],
			]
		);

		$this->add_control(
			'woo_ajax_add_to_cart',
			[
				'label' => esc_html__( 'AJAX Add to Cart', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'description' => esc_html__( 'Add products to cart without page reload', 'mn-elements' ),
				'condition' => [
					'woo_show_add_to_cart' => 'yes',
				],
			]
		);

		$this->add_control(
			'woo_show_stock_status',
			[
				'label' => esc_html__( 'Show Stock Status', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'description' => esc_html__( 'Display product stock status (In Stock/Out of Stock)', 'mn-elements' ),
			]
		);

		$this->add_control(
			'woo_show_sale_badge',
			[
				'label' => esc_html__( 'Show Sale Badge', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'description' => esc_html__( 'Display sale badge on discounted products', 'mn-elements' ),
			]
		);

		$this->end_controls_section();

		// Read More Section
		$this->start_controls_section(
			'section_readmore',
			[
				'label' => esc_html__( 'Read More Button', 'mn-elements' ),
				'condition' => [
					'show_readmore' => 'yes',
				],
			]
		);

		$this->add_control(
			'readmore_text',
			[
				'label' => esc_html__( 'Button Text', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Read More', 'mn-elements' ),
			]
		);

		$this->add_control(
			'readmore_icon',
			[
				'label' => esc_html__( 'Icon', 'mn-elements' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-arrow-right',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'icon_position',
			[
				'label' => esc_html__( 'Icon Position', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'after',
				'options' => [
					'before' => esc_html__( 'Before', 'mn-elements' ),
					'after' => esc_html__( 'After', 'mn-elements' ),
				],
				'condition' => [
					'readmore_icon[value]!' => '',
				],
			]
		);

		// Icon Animation Controls
		$this->add_control(
			'icon_loop_animation',
			[
				'label' => esc_html__( 'Icon Loop Animation', 'mn-elements' ),
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
				'condition' => [
					'readmore_icon[value]!' => '',
				],
			]
		);

		$this->add_control(
			'icon_hover_animation',
			[
				'label' => esc_html__( 'Icon Hover Animation', 'mn-elements' ),
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
				'condition' => [
					'readmore_icon[value]!' => '',
				],
			]
		);

		// URL Options
		$this->add_control(
			'url_options_heading',
			[
				'label' => esc_html__( 'URL Options', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
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
					'custom' => esc_html__( 'Custom URL', 'mn-elements' ),
					'custom_meta' => esc_html__( 'Custom Meta Field', 'mn-elements' ),
					'quickview' => esc_html__( 'Quick View', 'mn-elements' ),
					'add_to_cart' => esc_html__( 'Add to Cart', 'mn-elements' ),
					'direct_checkout' => esc_html__( 'Direct Checkout', 'mn-elements' ),
				],
			]
		);

		$this->add_control(
			'custom_url',
			[
				'label' => esc_html__( 'Custom URL', 'mn-elements' ),
				'type' => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'mn-elements' ),
				'description' => esc_html__( 'Enter custom URL for read more button', 'mn-elements' ),
				'condition' => [
					'url_option' => 'custom',
				],
			]
		);

		$this->add_control(
			'url_meta_field',
			[
				'label' => esc_html__( 'Meta Field Key', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'e.g. external_link', 'mn-elements' ),
				'description' => esc_html__( 'Enter the custom field key that contains the URL', 'mn-elements' ),
				'condition' => [
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
				'condition' => [
					'url_option' => 'custom_meta',
				],
			]
		);

		$this->add_control(
			'quickview_content_heading',
			[
				'label' => esc_html__( 'Quick View Content', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'url_option' => 'quickview',
				],
			]
		);

		$this->add_control(
			'quickview_show_image',
			[
				'label' => esc_html__( 'Show Featured Image', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'url_option' => 'quickview',
				],
			]
		);

		$this->add_control(
			'quickview_show_title',
			[
				'label' => esc_html__( 'Show Title', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'url_option' => 'quickview',
				],
			]
		);

		$this->add_control(
			'quickview_show_meta',
			[
				'label' => esc_html__( 'Show Custom Meta', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'url_option' => 'quickview',
					'custom_meta_field!' => '',
				],
			]
		);

		$this->add_control(
			'quickview_show_content',
			[
				'label' => esc_html__( 'Show Content', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'url_option' => 'quickview',
				],
			]
		);

		$this->add_control(
			'quickview_content_length',
			[
				'label' => esc_html__( 'Content Length', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 50,
				'min' => 10,
				'max' => 200,
				'description' => esc_html__( 'Number of words to show in content', 'mn-elements' ),
				'condition' => [
					'url_option' => 'quickview',
					'quickview_show_content' => 'yes',
				],
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
					'{{WRAPPER}} .mn-posts-grid' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Post Item Style
		$this->start_controls_section(
			'section_post_style',
			[
				'label' => esc_html__( 'Post Item', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'post_border',
				'selector' => '{{WRAPPER}} .mn-post-item',
			]
		);

		$this->add_control(
			'post_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-post-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'post_box_shadow',
				'selector' => '{{WRAPPER}} .mn-post-item',
			]
		);

		$this->add_control(
			'enable_hover_box_shadow',
			[
				'label' => esc_html__( 'Enable Hover Box Shadow', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'mn-elements' ),
				'label_off' => esc_html__( 'No', 'mn-elements' ),
				'default' => '',
				'separator' => 'before',
				'description' => esc_html__( 'Enable box shadow effect on post item hover', 'mn-elements' ),
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'post_box_shadow_hover',
				'selector' => '{{WRAPPER}} .mn-post-item:hover',
				'condition' => [
					'enable_hover_box_shadow' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'post_padding',
			[
				'label' => esc_html__( 'Post Item Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-post-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'description' => esc_html__( 'Padding for the entire post item container.', 'mn-elements' ),
			]
		);

		$this->add_responsive_control(
			'post_content_padding',
			[
				'label' => esc_html__( 'Content Area Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-post-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .mn-post-inline-wrapper .mn-post-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .mn-post-3column-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .mn-post-3column-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .mn-post-mixed-title-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .mn-post-mixed-row2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'description' => esc_html__( 'Padding for content areas (text, title, excerpt).', 'mn-elements' ),
			]
		);

		$this->add_responsive_control(
			'post_margin',
			[
				'label' => esc_html__( 'Post Item Margin', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-post-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'description' => esc_html__( 'Margin around the post item.', 'mn-elements' ),
			]
		);

		$this->add_control(
			'listing_background_color',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-post-item' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'listing_hover_background_color',
			[
				'label' => esc_html__( 'Hover Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-post-item:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'image_style_heading',
			[
				'label' => esc_html__( 'Image Settings', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_image' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'image_width',
			[
				'label' => esc_html__( 'Image Width', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 800,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mn-post-inline-wrapper .mn-post-image' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-post-inline-wrapper .mn-post-content' => 'width: calc(100% - {{SIZE}}{{UNIT}});',
					'{{WRAPPER}} .mn-post-3column-image' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-post-mixed-row1 .mn-post-image' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-post-mixed-row1 .mn-post-mixed-title-wrapper' => 'width: calc(100% - {{SIZE}}{{UNIT}});',
				],
				'condition' => [
					'show_image' => 'yes',
					'layout_template' => [
						'grid-inline',
						'list-inline',
						'grid-3column',
						'list-3column',
						'grid-mixed',
						'list-mixed',
					],
				],
				'description' => esc_html__( 'Adjust image width for Inline, 3 Column, and Mixed templates.', 'mn-elements' ),
			]
		);

		$this->add_responsive_control(
			'image_height',
			[
				'label' => esc_html__( 'Image Height', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh', '%' ],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 800,
						'step' => 1,
					],
					'vh' => [
						'min' => 10,
						'max' => 100,
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
					'size' => 200,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-post-image img' => 'height: {{SIZE}}{{UNIT}}; object-fit: cover;',
				],
				'condition' => [
					'show_image' => 'yes',
				],
				'description' => esc_html__( 'Set fixed height for all images. Use "auto" in custom CSS to maintain aspect ratio.', 'mn-elements' ),
			]
		);

		$this->add_responsive_control(
			'image_min_height',
			[
				'label' => esc_html__( 'Image Min Height', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh', '%' ],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 800,
						'step' => 1,
					],
					'vh' => [
						'min' => 10,
						'max' => 100,
						'step' => 1,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mn-post-image img' => 'min-height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_image' => 'yes',
				],
				'description' => esc_html__( 'Set minimum height for images. Useful for maintaining consistent layout.', 'mn-elements' ),
			]
		);

		$this->add_responsive_control(
			'image_max_height',
			[
				'label' => esc_html__( 'Image Max Height', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh', '%' ],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 1000,
						'step' => 1,
					],
					'vh' => [
						'min' => 10,
						'max' => 100,
						'step' => 1,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mn-post-image img' => 'max-height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_image' => 'yes',
				],
				'description' => esc_html__( 'Set maximum height for images. Prevents images from being too tall.', 'mn-elements' ),
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label' => esc_html__( 'Image Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'top' => 8,
					'right' => 8,
					'bottom' => 8,
					'left' => 8,
					'unit' => 'px',
					'isLinked' => true,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-post-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .mn-post-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'show_image' => 'yes',
				],
			]
		);

		// 3 Column Template Settings
		$this->add_control(
			'3column_heading',
			[
				'label' => esc_html__( '3 Column Layout Settings', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'layout_template' => [
						'grid-3column',
						'list-3column',
					],
				],
			]
		);

		$this->add_responsive_control(
			'3column_title_width',
			[
				'label' => esc_html__( 'Title Column Width', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 20,
						'max' => 60,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 35,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-post-3column-title' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'layout_template' => [
						'grid-3column',
						'list-3column',
					],
				],
			]
		);

		$this->add_responsive_control(
			'3column_content_width',
			[
				'label' => esc_html__( 'Content Column Width', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 20,
						'max' => 60,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-post-3column-content' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'layout_template' => [
						'grid-3column',
						'list-3column',
					],
				],
			]
		);

		$this->add_responsive_control(
			'3column_gap',
			[
				'label' => esc_html__( 'Gap Between Columns', 'mn-elements' ),
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
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-post-3column-wrapper' => 'gap: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'layout_template' => [
						'grid-3column',
						'list-3column',
					],
				],
			]
		);

		$this->add_responsive_control(
			'3column_align_items',
			[
				'label' => esc_html__( 'Align Items', 'mn-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Start', 'mn-elements' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'mn-elements' ),
						'icon' => 'eicon-v-align-middle',
					],
					'flex-end' => [
						'title' => esc_html__( 'End', 'mn-elements' ),
						'icon' => 'eicon-v-align-bottom',
					],
					'stretch' => [
						'title' => esc_html__( 'Stretch', 'mn-elements' ),
						'icon' => 'eicon-v-align-stretch',
					],
				],
				'default' => 'flex-start',
				'selectors' => [
					'{{WRAPPER}} .mn-post-3column-wrapper' => 'align-items: {{VALUE}};',
				],
				'condition' => [
					'layout_template' => [
						'grid-3column',
						'list-3column',
					],
				],
			]
		);

		$this->add_responsive_control(
			'3column_justify_content',
			[
				'label' => esc_html__( 'Justify Content', 'mn-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Start', 'mn-elements' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'mn-elements' ),
						'icon' => 'eicon-h-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'End', 'mn-elements' ),
						'icon' => 'eicon-h-align-right',
					],
					'space-between' => [
						'title' => esc_html__( 'Space Between', 'mn-elements' ),
						'icon' => 'eicon-h-align-stretch',
					],
					'space-around' => [
						'title' => esc_html__( 'Space Around', 'mn-elements' ),
						'icon' => 'eicon-h-align-stretch',
					],
				],
				'default' => 'flex-start',
				'selectors' => [
					'{{WRAPPER}} .mn-post-3column-wrapper' => 'justify-content: {{VALUE}};',
				],
				'condition' => [
					'layout_template' => [
						'grid-3column',
						'list-3column',
					],
				],
			]
		);

		// Inline Template Settings
		$this->add_control(
			'inline_heading',
			[
				'label' => esc_html__( 'Inline Layout Settings', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'layout_template' => [
						'grid-inline',
						'list-inline',
					],
				],
			]
		);

		$this->add_responsive_control(
			'inline_align_items',
			[
				'label' => esc_html__( 'Align Items', 'mn-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Start', 'mn-elements' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'mn-elements' ),
						'icon' => 'eicon-v-align-middle',
					],
					'flex-end' => [
						'title' => esc_html__( 'End', 'mn-elements' ),
						'icon' => 'eicon-v-align-bottom',
					],
					'stretch' => [
						'title' => esc_html__( 'Stretch', 'mn-elements' ),
						'icon' => 'eicon-v-align-stretch',
					],
				],
				'default' => 'flex-start',
				'selectors' => [
					'{{WRAPPER}} .mn-post-inline-wrapper' => 'align-items: {{VALUE}};',
				],
				'condition' => [
					'layout_template' => [
						'grid-inline',
						'list-inline',
					],
				],
			]
		);

		$this->add_responsive_control(
			'inline_gap',
			[
				'label' => esc_html__( 'Gap Between Image & Content', 'mn-elements' ),
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
					'{{WRAPPER}} .mn-post-inline-wrapper' => 'gap: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'layout_template' => [
						'grid-inline',
						'list-inline',
					],
				],
			]
		);

		// Mixed Template Settings
		$this->add_control(
			'mixed_heading',
			[
				'label' => esc_html__( 'Mixed Layout Settings', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'layout_template' => [
						'grid-mixed',
						'list-mixed',
					],
				],
			]
		);

		$this->add_responsive_control(
			'mixed_row1_gap',
			[
				'label' => esc_html__( 'Row 1 Gap (Image + Title)', 'mn-elements' ),
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
					'{{WRAPPER}} .mn-post-mixed-row1' => 'gap: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'layout_template' => [
						'grid-mixed',
						'list-mixed',
					],
				],
			]
		);

		$this->add_responsive_control(
			'mixed_row1_align_items',
			[
				'label' => esc_html__( 'Row 1 Align Items', 'mn-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Start', 'mn-elements' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'mn-elements' ),
						'icon' => 'eicon-v-align-middle',
					],
					'flex-end' => [
						'title' => esc_html__( 'End', 'mn-elements' ),
						'icon' => 'eicon-v-align-bottom',
					],
					'stretch' => [
						'title' => esc_html__( 'Stretch', 'mn-elements' ),
						'icon' => 'eicon-v-align-stretch',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .mn-post-mixed-row1' => 'align-items: {{VALUE}};',
				],
				'condition' => [
					'layout_template' => [
						'grid-mixed',
						'list-mixed',
					],
				],
			]
		);

		$this->add_responsive_control(
			'mixed_rows_gap',
			[
				'label' => esc_html__( 'Gap Between Rows', 'mn-elements' ),
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
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-post-mixed-wrapper' => 'gap: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'layout_template' => [
						'grid-mixed',
						'list-mixed',
					],
				],
			]
		);

		// Overlay Template Settings
		$this->add_control(
			'overlay_heading',
			[
				'label' => esc_html__( 'Overlay Layout Settings', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'layout_template' => [
						'grid-overlay',
						'list-overlay',
					],
				],
			]
		);

		$this->add_responsive_control(
			'overlay_height',
			[
				'label' => esc_html__( 'Card Height', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range' => [
					'px' => [
						'min' => 200,
						'max' => 800,
					],
					'vh' => [
						'min' => 20,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 400,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-post-overlay-wrapper' => 'min-height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'layout_template' => [
						'grid-overlay',
						'list-overlay',
					],
				],
			]
		);

		$this->add_control(
			'overlay_item_bg_color',
			[
				'label' => esc_html__( 'Item Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#1a1a1a',
				'selectors' => [
					'{{WRAPPER}} .mn-post-overlay-wrapper' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'layout_template' => [
						'grid-overlay',
						'list-overlay',
					],
				],
			]
		);

		$this->add_responsive_control(
			'overlay_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'top' => 8,
					'right' => 8,
					'bottom' => 8,
					'left' => 8,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-post-overlay-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .mn-post-overlay-bg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 0;',
				],
				'condition' => [
					'layout_template' => [
						'grid-overlay',
						'list-overlay',
					],
				],
			]
		);

		$this->add_responsive_control(
			'overlay_content_padding',
			[
				'label' => esc_html__( 'Content Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'top' => 30,
					'right' => 30,
					'bottom' => 30,
					'left' => 30,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-post-overlay-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'layout_template' => [
						'grid-overlay',
						'list-overlay',
					],
				],
			]
		);

		$this->add_control(
			'overlay_title_color',
			[
				'label' => esc_html__( 'Title Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-post-overlay-content .mn-post-title' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-post-overlay-content .mn-post-title a' => 'color: {{VALUE}};',
				],
				'condition' => [
					'layout_template' => [
						'grid-overlay',
						'list-overlay',
					],
				],
			]
		);

		$this->add_control(
			'overlay_excerpt_color',
			[
				'label' => esc_html__( 'Excerpt Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(255, 255, 255, 0.85)',
				'selectors' => [
					'{{WRAPPER}} .mn-post-overlay-content .mn-post-excerpt' => 'color: {{VALUE}};',
				],
				'condition' => [
					'layout_template' => [
						'grid-overlay',
						'list-overlay',
					],
				],
			]
		);

		$this->add_control(
			'overlay_button_heading',
			[
				'label' => esc_html__( 'Read More Button', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'layout_template' => [
						'grid-overlay',
						'list-overlay',
					],
				],
			]
		);

		$this->add_control(
			'overlay_button_color',
			[
				'label' => esc_html__( 'Button Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-post-overlay-readmore .mn-post-readmore a' => 'color: {{VALUE}};',
				],
				'condition' => [
					'layout_template' => [
						'grid-overlay',
						'list-overlay',
					],
				],
			]
		);

		$this->add_control(
			'overlay_button_border_color',
			[
				'label' => esc_html__( 'Button Border Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-post-overlay-readmore .mn-post-readmore a' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'layout_template' => [
						'grid-overlay',
						'list-overlay',
					],
				],
			]
		);

		$this->add_control(
			'overlay_button_hover_bg',
			[
				'label' => esc_html__( 'Button Hover Background', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-post-overlay-readmore .mn-post-readmore a:hover' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'layout_template' => [
						'grid-overlay',
						'list-overlay',
					],
				],
			]
		);

		$this->add_control(
			'overlay_button_hover_color',
			[
				'label' => esc_html__( 'Button Hover Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .mn-post-overlay-readmore .mn-post-readmore a:hover' => 'color: {{VALUE}};',
				],
				'condition' => [
					'layout_template' => [
						'grid-overlay',
						'list-overlay',
					],
				],
			]
		);

		$this->end_controls_section();

		// Section Title Style
		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Section Title', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'section_title!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'section_title_typography',
				'selector' => '{{WRAPPER}} .mn-posts-section-title',
			]
		);

		$this->add_control(
			'section_title_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-posts-section-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'section_title_align',
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
					'{{WRAPPER}} .mn-posts-section-title' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'section_title_spacing',
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
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-posts-section-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .mn-post-title',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'meta_typography',
				'label' => esc_html__( 'Meta Typography', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-post-meta',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'excerpt_typography',
				'label' => esc_html__( 'Excerpt Typography', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-post-excerpt',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'custom_meta_typography',
				'label' => esc_html__( 'Custom Meta Typography', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-post-custom-meta',
				'condition' => [
					'show_custom_meta' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'taxonomy_typography',
				'label' => esc_html__( 'Taxonomy Typography', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-taxonomy-term',
				'condition' => [
					'show_taxonomy' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		// Taxonomy Style Section
		$this->start_controls_section(
			'section_taxonomy_style',
			[
				'label' => esc_html__( 'Taxonomy', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_taxonomy' => 'yes',
				],
			]
		);

		$this->add_control(
			'taxonomy_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-taxonomy-term' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'taxonomy_text_color',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-taxonomy-term' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'taxonomy_hover_heading',
			[
				'label' => esc_html__( 'Hover State', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'taxonomy_hover_bg_color',
			[
				'label' => esc_html__( 'Hover Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-taxonomy-term:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'taxonomy_hover_text_color',
			[
				'label' => esc_html__( 'Hover Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-taxonomy-term:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'taxonomy_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'top' => 4,
					'right' => 12,
					'bottom' => 4,
					'left' => 12,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-taxonomy-term' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'taxonomy_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 3,
					'right' => 3,
					'bottom' => 3,
					'left' => 3,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-taxonomy-term' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'taxonomy_border',
				'selector' => '{{WRAPPER}} .mn-taxonomy-term',
			]
		);

		$this->add_responsive_control(
			'taxonomy_gap',
			[
				'label' => esc_html__( 'Gap Between Terms', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 8,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-post-taxonomy' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Text Alignment Section
		$this->start_controls_section(
			'section_text_alignment',
			[
				'label' => esc_html__( 'Text Alignment', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'title_align',
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
					'justify' => [
						'title' => esc_html__( 'Justified', 'mn-elements' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .mn-post-title' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'meta_align',
			[
				'label' => esc_html__( 'Meta Alignment', 'mn-elements' ),
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
					'justify' => [
						'title' => esc_html__( 'Justified', 'mn-elements' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .mn-post-meta' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'show_meta' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'excerpt_align',
			[
				'label' => esc_html__( 'Excerpt Alignment', 'mn-elements' ),
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
					'justify' => [
						'title' => esc_html__( 'Justified', 'mn-elements' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .mn-post-excerpt' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'show_excerpt' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'readmore_align',
			[
				'label' => esc_html__( 'Read More Alignment', 'mn-elements' ),
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
					'{{WRAPPER}} .mn-post-readmore' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'show_readmore' => 'yes',
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
					'{{WRAPPER}} .mn-post-title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'meta_color',
			[
				'label' => esc_html__( 'Meta Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-post-meta' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'excerpt_color',
			[
				'label' => esc_html__( 'Excerpt Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-post-excerpt' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'custom_meta_color',
			[
				'label' => esc_html__( 'Custom Meta Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-post-custom-meta' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_custom_meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'custom_meta_hover_color',
			[
				'label' => esc_html__( 'Custom Meta Hover Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-post-item:hover .mn-post-custom-meta' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_custom_meta' => 'yes',
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
					'{{WRAPPER}} .mn-post-title a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'meta_hover_color',
			[
				'label' => esc_html__( 'Meta Hover Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-post-item:hover .mn-post-meta' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'quickview_background_color',
			[
				'label' => esc_html__( 'Quickview Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-quickview-container' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'url_option' => 'quickview',
				],
			]
		);

		$this->end_controls_section();

		// Read More Button Style
		$this->start_controls_section(
			'section_readmore_style',
			[
				'label' => esc_html__( 'Read More Button', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_readmore' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'readmore_typography',
				'selector' => '{{WRAPPER}} .mn-readmore-button',
			]
		);

		// Position Controls
		$this->add_control(
			'readmore_position_heading',
			[
				'label' => esc_html__( 'Position', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'readmore_position_type',
			[
				'label' => esc_html__( 'Position Type', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'mn-elements' ),
					'relative' => esc_html__( 'Relative', 'mn-elements' ),
					'absolute' => esc_html__( 'Absolute', 'mn-elements' ),
					'fixed' => esc_html__( 'Fixed', 'mn-elements' ),
				],
				'selectors' => [
					'{{WRAPPER}} .mn-readmore-button' => 'position: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'readmore_position_top',
			[
				'label' => esc_html__( 'Top', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vh' ],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
					'em' => [
						'min' => -50,
						'max' => 50,
					],
					'rem' => [
						'min' => -50,
						'max' => 50,
					],
					'vh' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mn-readmore-button' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'readmore_position_type!' => 'default',
				],
			]
		);

		$this->add_responsive_control(
			'readmore_position_right',
			[
				'label' => esc_html__( 'Right', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw' ],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
					'em' => [
						'min' => -50,
						'max' => 50,
					],
					'rem' => [
						'min' => -50,
						'max' => 50,
					],
					'vw' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mn-readmore-button' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'readmore_position_type!' => 'default',
				],
			]
		);

		$this->add_responsive_control(
			'readmore_position_bottom',
			[
				'label' => esc_html__( 'Bottom', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vh' ],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
					'em' => [
						'min' => -50,
						'max' => 50,
					],
					'rem' => [
						'min' => -50,
						'max' => 50,
					],
					'vh' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mn-readmore-button' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'readmore_position_type!' => 'default',
				],
			]
		);

		$this->add_responsive_control(
			'readmore_position_left',
			[
				'label' => esc_html__( 'Left', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw' ],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
					'em' => [
						'min' => -50,
						'max' => 50,
					],
					'rem' => [
						'min' => -50,
						'max' => 50,
					],
					'vw' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mn-readmore-button' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'readmore_position_type!' => 'default',
				],
			]
		);

		$this->add_control(
			'readmore_z_index',
			[
				'label' => esc_html__( 'Z-Index', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 9999,
				'step' => 1,
				'selectors' => [
					'{{WRAPPER}} .mn-readmore-button' => 'z-index: {{VALUE}};',
				],
				'condition' => [
					'readmore_position_type!' => 'default',
				],
			]
		);

		$this->add_control(
			'readmore_button_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-readmore-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'readmore_button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-readmore-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'readmore_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-readmore-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-readmore-icon svg' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'readmore_icon[value]!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'readmore_border',
				'selector' => '{{WRAPPER}} .mn-readmore-button',
			]
		);

		$this->add_control(
			'readmore_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-readmore-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Hover State Controls
		$this->add_control(
			'readmore_hover_heading',
			[
				'label' => esc_html__( 'Hover State', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'readmore_hover_bg_color',
			[
				'label' => esc_html__( 'Hover Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-readmore-button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'readmore_hover_text_color',
			[
				'label' => esc_html__( 'Hover Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-readmore-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'readmore_hover_border_color',
			[
				'label' => esc_html__( 'Hover Border Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-readmore-button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'readmore_hover_icon_color',
			[
				'label' => esc_html__( 'Hover Icon Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-readmore-button:hover .mn-readmore-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-readmore-button:hover .mn-readmore-icon svg' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'readmore_icon[value]!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'readmore_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mn-readmore-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-readmore-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'readmore_icon[value]!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'readmore_icon_spacing',
			[
				'label' => esc_html__( 'Icon Spacing', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mn-readmore-button .mn-readmore-icon.mn-icon-before' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-readmore-button .mn-readmore-icon.mn-icon-after' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'readmore_icon[value]!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'readmore_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-readmore-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Quickview Typography Section
		$this->start_controls_section(
			'section_quickview_typography',
			[
				'label' => esc_html__( 'Quickview Typography', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'url_option' => 'quickview',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'quickview_title_typography',
				'label' => esc_html__( 'Title Typography', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-quickview-title',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'quickview_content_typography',
				'label' => esc_html__( 'Content Typography', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-quickview-post-content',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'quickview_meta_typography',
				'label' => esc_html__( 'Custom Meta Typography', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-quickview-meta',
				'condition' => [
					'quickview_show_meta' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		// WooCommerce Style Section
		$this->start_controls_section(
			'section_woocommerce_style',
			[
				'label' => esc_html__( 'WooCommerce', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'post_type' => 'product',
				],
			]
		);

		// Price Styling
		$this->add_control(
			'woo_price_heading',
			[
				'label' => esc_html__( 'Price', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'woo_show_price' => 'yes',
				],
			]
		);

		$this->add_control(
			'woo_price_color',
			[
				'label' => esc_html__( 'Price Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .mn-product-price' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-product-price .woocommerce-Price-amount' => 'color: {{VALUE}};',
				],
				'condition' => [
					'woo_show_price' => 'yes',
				],
			]
		);

		$this->add_control(
			'woo_sale_price_color',
			[
				'label' => esc_html__( 'Sale Price Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e74c3c',
				'selectors' => [
					'{{WRAPPER}} .mn-product-price ins .woocommerce-Price-amount' => 'color: {{VALUE}};',
				],
				'condition' => [
					'woo_show_price' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'woo_price_typography',
				'selector' => '{{WRAPPER}} .mn-product-price',
				'condition' => [
					'woo_show_price' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'woo_price_spacing',
			[
				'label' => esc_html__( 'Price Spacing', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-product-price' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'woo_show_price' => 'yes',
				],
			]
		);

		// Add to Cart Button Styling
		$this->add_control(
			'woo_cart_heading',
			[
				'label' => esc_html__( 'Add to Cart Button', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'woo_show_add_to_cart' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'woo_cart_typography',
				'selector' => '{{WRAPPER}} .mn-add-to-cart-btn',
				'condition' => [
					'woo_show_add_to_cart' => 'yes',
				],
			]
		);

		$this->start_controls_tabs(
			'woo_cart_tabs',
			[
				'condition' => [
					'woo_show_add_to_cart' => 'yes',
				],
			]
		);

		$this->start_controls_tab(
			'woo_cart_normal',
			[
				'label' => esc_html__( 'Normal', 'mn-elements' ),
			]
		);

		$this->add_control(
			'woo_cart_color',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-add-to-cart-btn' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'woo_cart_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#007cba',
				'selectors' => [
					'{{WRAPPER}} .mn-add-to-cart-btn' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'woo_cart_hover',
			[
				'label' => esc_html__( 'Hover', 'mn-elements' ),
			]
		);

		$this->add_control(
			'woo_cart_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-add-to-cart-btn:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'woo_cart_hover_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#005a87',
				'selectors' => [
					'{{WRAPPER}} .mn-add-to-cart-btn:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'woo_cart_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'default' => [
					'top' => 8,
					'right' => 16,
					'bottom' => 8,
					'left' => 16,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-add-to-cart-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'woo_show_add_to_cart' => 'yes',
				],
			]
		);

		$this->add_control(
			'woo_cart_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 4,
					'right' => 4,
					'bottom' => 4,
					'left' => 4,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-add-to-cart-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'woo_show_add_to_cart' => 'yes',
				],
			]
		);

		// Quantity Styling
		$this->add_control(
			'woo_quantity_heading',
			[
				'label' => esc_html__( 'Quantity Selector', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'woo_show_quantity' => 'yes',
				],
			]
		);

		$this->add_control(
			'woo_quantity_btn_color',
			[
				'label' => esc_html__( 'Button Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .mn-quantity-btn' => 'color: {{VALUE}};',
				],
				'condition' => [
					'woo_show_quantity' => 'yes',
				],
			]
		);

		$this->add_control(
			'woo_quantity_btn_bg',
			[
				'label' => esc_html__( 'Button Background', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#f5f5f5',
				'selectors' => [
					'{{WRAPPER}} .mn-quantity-btn' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'woo_show_quantity' => 'yes',
				],
			]
		);

		// Sale Badge Styling
		$this->add_control(
			'woo_sale_badge_heading',
			[
				'label' => esc_html__( 'Sale Badge', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'woo_show_sale_badge' => 'yes',
				],
			]
		);

		$this->add_control(
			'woo_sale_badge_color',
			[
				'label' => esc_html__( 'Badge Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-sale-badge' => 'color: {{VALUE}};',
				],
				'condition' => [
					'woo_show_sale_badge' => 'yes',
				],
			]
		);

		$this->add_control(
			'woo_sale_badge_bg',
			[
				'label' => esc_html__( 'Badge Background', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e74c3c',
				'selectors' => [
					'{{WRAPPER}} .mn-sale-badge' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'woo_show_sale_badge' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		// Pagination Style Section
		$this->start_controls_section(
			'section_pagination_style',
			[
				'label' => esc_html__( 'Pagination', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'enable_pagination' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_align',
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
					'{{WRAPPER}} .mn-posts-pagination' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_spacing',
			[
				'label' => esc_html__( 'Top Spacing', 'mn-elements' ),
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
					'{{WRAPPER}} .mn-posts-pagination' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'pagination_typography',
				'selector' => '{{WRAPPER}} .mn-posts-pagination a, {{WRAPPER}} .mn-posts-pagination span',
			]
		);

		$this->add_responsive_control(
			'pagination_item_gap',
			[
				'label' => esc_html__( 'Gap Between Items', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-posts-pagination a, {{WRAPPER}} .mn-posts-pagination span' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_item_padding',
			[
				'label' => esc_html__( 'Item Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'top' => 8,
					'right' => 12,
					'bottom' => 8,
					'left' => 12,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-posts-pagination a, {{WRAPPER}} .mn-posts-pagination span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Normal State Colors
		$this->add_control(
			'pagination_normal_heading',
			[
				'label' => esc_html__( 'Normal State', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'pagination_text_color',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .mn-posts-pagination a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'pagination_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#f8f9fa',
				'selectors' => [
					'{{WRAPPER}} .mn-posts-pagination a, {{WRAPPER}} .mn-posts-pagination span' => 'background-color: {{VALUE}};',
				],
			]
		);

		// Hover State Colors
		$this->add_control(
			'pagination_hover_heading',
			[
				'label' => esc_html__( 'Hover State', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'pagination_hover_text_color',
			[
				'label' => esc_html__( 'Hover Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-posts-pagination a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'pagination_hover_bg_color',
			[
				'label' => esc_html__( 'Hover Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#007cba',
				'selectors' => [
					'{{WRAPPER}} .mn-posts-pagination a:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		// Active/Current State Colors
		$this->add_control(
			'pagination_active_heading',
			[
				'label' => esc_html__( 'Active/Current State', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'pagination_active_text_color',
			[
				'label' => esc_html__( 'Active Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-posts-pagination .current' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'pagination_active_bg_color',
			[
				'label' => esc_html__( 'Active Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#007cba',
				'selectors' => [
					'{{WRAPPER}} .mn-posts-pagination .current' => 'background-color: {{VALUE}};',
				],
			]
		);

		// Border Controls
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'pagination_border',
				'selector' => '{{WRAPPER}} .mn-posts-pagination a, {{WRAPPER}} .mn-posts-pagination span',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'pagination_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 4,
					'right' => 4,
					'bottom' => 4,
					'left' => 4,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-posts-pagination a, {{WRAPPER}} .mn-posts-pagination span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Get post types for select control
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
	 * Get taxonomies for select control
	 */
	private function get_taxonomies() {
		$taxonomies = get_taxonomies( [ 'public' => true ], 'objects' );
		$options = [
			'' => esc_html__( 'Select Taxonomy', 'mn-elements' ),
		];

		foreach ( $taxonomies as $taxonomy ) {
			$options[ $taxonomy->name ] = $taxonomy->label;
		}

		return $options;
	}

	/**
	 * Render widget output on the frontend.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		// Get current page for pagination
		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		if ( is_front_page() ) {
			$paged = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;
		}

		// Build query args based on query type
		$query_args = [
			'post_type' => $settings['post_type'],
			'post_status' => 'publish',
		];

		// Add pagination to query if enabled
		if ( $settings['enable_pagination'] === 'yes' && $settings['query_type'] !== 'single' ) {
			$query_args['paged'] = $paged;
		}

		// For archive query, ignore manual taxonomy filtering
		if ( $settings['query_type'] === 'archive' ) {
			// Archive query handles taxonomy automatically
			// Skip manual taxonomy filtering section below
		} else {

		// Add taxonomy filtering if selected
		if ( ! empty( $settings['taxonomy'] ) ) {
			if ( ! empty( $settings['taxonomy_ids'] ) ) {
				// Filter by specific taxonomy term IDs
				$term_ids = array_map( 'trim', explode( ',', $settings['taxonomy_ids'] ) );
				$term_ids = array_filter( array_map( 'intval', $term_ids ) );
				
				if ( ! empty( $term_ids ) ) {
					$query_args['tax_query'] = [
						[
							'taxonomy' => $settings['taxonomy'],
							'field'    => 'term_id',
							'terms'    => $term_ids,
						],
					];
				}
			}
		}
		} // End of manual taxonomy filtering

		// Add exclude options
		if ( $settings['query_type'] !== 'single' ) {
			$exclude_ids = [];
			
			// Exclude current post
			if ( $settings['exclude_current'] === 'yes' && is_singular() ) {
				$exclude_ids[] = get_the_ID();
			}
			
			// Exclude by post IDs
			if ( ! empty( $settings['exclude_ids'] ) ) {
				$manual_exclude = array_map( 'trim', explode( ',', $settings['exclude_ids'] ) );
				$manual_exclude = array_filter( array_map( 'intval', $manual_exclude ) );
				$exclude_ids = array_merge( $exclude_ids, $manual_exclude );
			}
			
			if ( ! empty( $exclude_ids ) ) {
				$query_args['post__not_in'] = $exclude_ids;
			}
			
			// Add offset
			if ( ! empty( $settings['exclude_by_offset'] ) && $settings['exclude_by_offset'] > 0 ) {
				$query_args['offset'] = intval( $settings['exclude_by_offset'] );
			}
		}

		// Handle different query types
		switch ( $settings['query_type'] ) {
			case 'single':
				if ( ! empty( $settings['single_post_id'] ) ) {
					$query_args['p'] = intval( $settings['single_post_id'] );
					$query_args['posts_per_page'] = 1;
				} else {
					// If no post ID specified, don't show anything
					return;
				}
				break;

			case 'archive':
				// Use current archive query context
				$query_args['posts_per_page'] = $settings['posts_per_page'];
				$query_args['orderby'] = $settings['orderby'];
				$query_args['order'] = $settings['order'];
				
				// Get current queried object for archive pages
				if ( is_category() || is_tag() || is_tax() ) {
					$queried_object = get_queried_object();
					if ( $queried_object && isset( $queried_object->term_id ) ) {
						$query_args['tax_query'] = [
							[
								'taxonomy' => $queried_object->taxonomy,
								'field'    => 'term_id',
								'terms'    => $queried_object->term_id,
							],
						];
					}
				} elseif ( is_author() ) {
					// Author archive
					$author_id = get_queried_object_id();
					if ( $author_id ) {
						$query_args['author'] = $author_id;
					}
				} elseif ( is_date() ) {
					// Date archive
					if ( is_year() ) {
						$query_args['year'] = get_query_var( 'year' );
					} elseif ( is_month() ) {
						$query_args['year'] = get_query_var( 'year' );
						$query_args['monthnum'] = get_query_var( 'monthnum' );
					} elseif ( is_day() ) {
						$query_args['year'] = get_query_var( 'year' );
						$query_args['monthnum'] = get_query_var( 'monthnum' );
						$query_args['day'] = get_query_var( 'day' );
					}
				} elseif ( is_post_type_archive() ) {
					// Post type archive - post_type already set in query_args
					// No additional filtering needed
				}
				break;

			case 'all':
				// For 'all' posts, disable pagination or limit if pagination is enabled
				if ( $settings['enable_pagination'] === 'yes' ) {
					$query_args['posts_per_page'] = $settings['posts_per_page'];
				} else {
					$query_args['posts_per_page'] = -1; // Get all posts
				}
				$query_args['orderby'] = $settings['orderby'];
				$query_args['order'] = $settings['order'];
				break;

			case 'recent':
			default:
				$query_args['posts_per_page'] = $settings['posts_per_page'];
				$query_args['orderby'] = $settings['orderby'];
				$query_args['order'] = $settings['order'];
				break;
		}

		// Handle Event Date ordering for The Events Calendar
		if ( $settings['orderby'] === 'event_date' ) {
			$event_date_field = isset( $settings['event_date_field'] ) ? $settings['event_date_field'] : '_EventStartDate';
			$event_date_type = isset( $settings['event_date_type'] ) ? $settings['event_date_type'] : 'upcoming';
			$current_datetime = current_time( 'Y-m-d H:i:s' );
			
			// Set up meta query for date filtering
			$meta_query = [];
			
			switch ( $event_date_type ) {
				case 'upcoming':
					// Show events where end date is today or in the future (event still happening or upcoming)
					$meta_query[] = [
						'key'     => '_EventEndDate',
						'value'   => $current_datetime,
						'compare' => '>=',
						'type'    => 'DATETIME',
					];
					// Default order for upcoming: ASC (nearest event first)
					if ( ! isset( $settings['order'] ) || $settings['order'] === 'DESC' ) {
						$query_args['order'] = 'ASC';
					}
					break;
					
				case 'past':
					// Show events where end date has passed
					$meta_query[] = [
						'key'     => '_EventEndDate',
						'value'   => $current_datetime,
						'compare' => '<',
						'type'    => 'DATETIME',
					];
					// Default order for past: DESC (most recent past event first)
					if ( ! isset( $settings['order'] ) || $settings['order'] === 'ASC' ) {
						$query_args['order'] = 'DESC';
					}
					break;
					
				case 'all':
				default:
					// No date filtering, show all events
					break;
			}
			
			// Add meta query if we have date filtering
			if ( ! empty( $meta_query ) ) {
				if ( isset( $query_args['meta_query'] ) ) {
					$query_args['meta_query'][] = $meta_query[0];
				} else {
					$query_args['meta_query'] = $meta_query;
				}
			}
			
			// Set up ordering by event date meta field
			$query_args['meta_key'] = $event_date_field;
			$query_args['orderby'] = 'meta_value';
			$query_args['meta_type'] = 'DATETIME';
		}

		$posts_query = new \WP_Query( $query_args );

		if ( ! $posts_query->have_posts() ) {
			return;
		}

		// Determine theme class
		$theme_class = '';
		if ( isset( $settings['enable_theme_version'] ) && $settings['enable_theme_version'] === 'yes' ) {
			$theme_style = isset( $settings['theme_version'] ) ? $settings['theme_version'] : 'light';
			$theme_class = 'mn-theme-' . $theme_style;
		}
		
		// Determine layout class based on layout_template
		$layout_template = isset( $settings['layout_template'] ) ? $settings['layout_template'] : 'grid-general';
		$layout_class = ( strpos( $layout_template, 'list-' ) === 0 ) ? 'mn-posts-list' : 'mn-posts-grid';
		
		// Add equal height class if enabled
		$equal_height_class = '';
		if ( isset( $settings['equal_column_height'] ) && $settings['equal_column_height'] === 'yes' ) {
			$equal_height_class = 'mn-equal-height';
			$image_fit_mode = isset( $settings['image_fit_mode'] ) ? $settings['image_fit_mode'] : 'contain';
			$equal_height_class .= ' mn-image-fit-' . $image_fit_mode;
		}
		
		// Add centered grid class if enabled
		$centered_class = '';
		$centered_data_attr = '';
		if ( isset( $settings['centered_grid'] ) && $settings['centered_grid'] === 'yes' ) {
			$centered_class = 'mn-grid-centered';
			// Get column count for centered grid (desktop, tablet, mobile)
			$columns = isset( $settings['columns'] ) ? $settings['columns'] : '3';
			$columns_tablet = isset( $settings['columns_tablet'] ) ? $settings['columns_tablet'] : '2';
			$columns_mobile = isset( $settings['columns_mobile'] ) ? $settings['columns_mobile'] : '1';
			$centered_data_attr = ' data-columns="' . esc_attr( $columns ) . '"';
			$centered_data_attr .= ' data-columns-tablet="' . esc_attr( $columns_tablet ) . '"';
			$centered_data_attr .= ' data-columns-mobile="' . esc_attr( $columns_mobile ) . '"';
		}

		$this->add_render_attribute( 'wrapper', 'class', [
			'mn-posts-wrapper',
			$theme_class
		] );

		// Generate responsive columns CSS
		$responsive_css = $this->generate_responsive_columns_css( $settings );

		?>
		<?php if ( ! empty( $responsive_css ) ) : ?>
		<style type="text/css"><?php echo $responsive_css; ?></style>
		<?php endif; ?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<?php if ( ! empty( $settings['section_title'] ) ) : ?>
				<<?php echo esc_attr( $settings['title_tag'] ); ?> class="mn-posts-section-title">
					<?php echo esc_html( $settings['section_title'] ); ?>
				</<?php echo esc_attr( $settings['title_tag'] ); ?>>
			<?php endif; ?>
			
			<div class="<?php echo esc_attr( trim( $layout_class . ' ' . $equal_height_class . ' ' . $centered_class ) ); ?>"<?php echo $centered_data_attr; ?>>
				<?php
				while ( $posts_query->have_posts() ) :
					$posts_query->the_post();
					$this->render_post_item( $settings );
				endwhile;
				wp_reset_postdata();
				?>
			</div>

			<?php
			// Render pagination if enabled
			if ( $settings['enable_pagination'] === 'yes' && $settings['query_type'] !== 'single' ) :
				$this->render_pagination( $posts_query, $settings );
			endif;
			?>
		</div>
		<?php
		
		// Render quickview modal if needed
		$this->render_quickview_modal( $settings );
		
		// Render YouTube modal if needed
		$this->render_youtube_modal( $settings );
	}

	/**
	 * Render single post item
	 */
	private function render_post_item( $settings ) {
		$filter_class = ( $settings['image_filter_effect'] !== 'none' ) ? 'mn-filter-' . $settings['image_filter_effect'] : '';
		$layout_template = isset( $settings['layout_template'] ) ? $settings['layout_template'] : 'grid-general';
		
		// Extract template type (remove grid-/list- prefix)
		$template_type = str_replace( ['grid-', 'list-'], '', $layout_template );
		$template_class = 'mn-template-' . $template_type;
		
		// Check if entire item should be clickable
		$enable_post_link = isset( $settings['enable_post_link'] ) && $settings['enable_post_link'] === 'yes';
		$post_link_target = isset( $settings['post_link_target'] ) && $settings['post_link_target'] === 'yes' ? '_blank' : '_self';
		$clickable_class = $enable_post_link ? 'mn-post-item-clickable' : '';
		
		?>
		<article class="mn-post-item <?php echo esc_attr( $template_class ); ?> <?php echo esc_attr( $clickable_class ); ?>">
			<?php if ( $enable_post_link ) : ?>
				<a href="<?php the_permalink(); ?>" class="mn-post-item-link" target="<?php echo esc_attr( $post_link_target ); ?>" rel="<?php echo $post_link_target === '_blank' ? 'noopener noreferrer' : ''; ?>">
			<?php endif; ?>
			
			<?php 
			if ( $template_type === 'inline' ) {
				// Inline layout: Image, Title, Excerpt in one row
				$this->render_inline_layout( $settings, $filter_class );
			} elseif ( $template_type === '3column' ) {
				// 3 Column layout: Image | Title | Content
				$this->render_3column_layout( $settings, $filter_class );
			} elseif ( $template_type === 'mixed' ) {
				// Mixed layout: 2 rows (Image+Title, then Content)
				$this->render_mixed_layout( $settings, $filter_class );
			} elseif ( $template_type === 'overlay' ) {
				// Overlay layout: Featured image as background with overlay content
				$this->render_overlay_layout( $settings, $filter_class );
			} elseif ( $template_type === 'custom' ) {
				// Custom layout: User-defined element order
				$this->render_custom_layout( $settings, $filter_class );
			} else {
				// General layout: Default vertical stacking
				$this->render_general_layout( $settings, $filter_class );
			}
			?>
			
			<?php if ( $enable_post_link ) : ?>
				</a>
			<?php endif; ?>
		</article>
		<?php
	}

	/**
	 * Render general (vertical) layout
	 */
	private function render_general_layout( $settings, $filter_class ) {
		?>
		<?php if ( $settings['show_image'] && has_post_thumbnail() ) : ?>
			<div class="mn-post-image <?php echo esc_attr( $filter_class ); ?>">
				<?php $this->render_woo_sale_badge( $settings ); ?>
				<a href="<?php the_permalink(); ?>">
					<?php the_post_thumbnail( 'medium_large' ); ?>
				</a>
			</div>
		<?php endif; ?>

		<div class="mn-post-content">
			<?php $this->render_taxonomy( $settings ); ?>
			<?php $this->render_title( $settings ); ?>
			<?php $this->render_woo_price( $settings ); ?>
			<?php $this->render_woo_stock_status( $settings ); ?>
			<?php $this->render_meta( $settings ); ?>
			<?php $this->render_excerpt( $settings ); ?>
			<?php $this->render_custom_meta( $settings ); ?>
			<?php $this->render_readmore( $settings ); ?>
		</div>
		<?php
	}

	/**
	 * Render inline (horizontal) layout
	 */
	private function render_inline_layout( $settings, $filter_class ) {
		?>
		<div class="mn-post-inline-wrapper">
			<?php if ( $settings['show_image'] && has_post_thumbnail() ) : ?>
				<div class="mn-post-image <?php echo esc_attr( $filter_class ); ?>">
					<?php $this->render_woo_sale_badge( $settings ); ?>
					<a href="<?php the_permalink(); ?>">
						<?php the_post_thumbnail( 'medium' ); ?>
					</a>
				</div>
			<?php endif; ?>

			<div class="mn-post-content">
				<?php $this->render_taxonomy( $settings ); ?>
				<?php $this->render_title( $settings ); ?>
				<?php $this->render_woo_price( $settings ); ?>
				<?php $this->render_woo_stock_status( $settings ); ?>
				<?php $this->render_meta( $settings ); ?>
				<?php $this->render_excerpt( $settings ); ?>
				<?php $this->render_custom_meta( $settings ); ?>
				<?php $this->render_readmore( $settings ); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render 3 column layout: Image | Title | Content
	 */
	private function render_3column_layout( $settings, $filter_class ) {
		?>
		<div class="mn-post-3column-wrapper">
			<!-- Column 1: Image -->
			<?php if ( $settings['show_image'] && has_post_thumbnail() ) : ?>
				<div class="mn-post-3column-image">
					<div class="mn-post-image <?php echo esc_attr( $filter_class ); ?>">
						<?php $this->render_woo_sale_badge( $settings ); ?>
						<a href="<?php the_permalink(); ?>">
							<?php the_post_thumbnail( 'medium' ); ?>
						</a>
					</div>
				</div>
			<?php endif; ?>

			<!-- Column 2: Title -->
			<div class="mn-post-3column-title">
				<?php $this->render_taxonomy( $settings ); ?>
				<?php $this->render_title( $settings ); ?>
				<?php $this->render_woo_price( $settings ); ?>
				<?php $this->render_meta( $settings ); ?>
			</div>

			<!-- Column 3: Content -->
			<div class="mn-post-3column-content">
				<?php $this->render_woo_stock_status( $settings ); ?>
				<?php $this->render_excerpt( $settings ); ?>
				<?php $this->render_custom_meta( $settings ); ?>
				<?php $this->render_readmore( $settings ); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render mixed layout: 2 rows (Image+Title horizontal, then content)
	 */
	private function render_mixed_layout( $settings, $filter_class ) {
		?>
		<div class="mn-post-mixed-wrapper">
			<!-- Row 1: Image + Title (Horizontal) -->
			<div class="mn-post-mixed-row1">
				<?php if ( $settings['show_image'] && has_post_thumbnail() ) : ?>
					<div class="mn-post-image <?php echo esc_attr( $filter_class ); ?>">
						<?php $this->render_woo_sale_badge( $settings ); ?>
						<a href="<?php the_permalink(); ?>">
							<?php the_post_thumbnail( 'medium' ); ?>
						</a>
					</div>
				<?php endif; ?>

				<div class="mn-post-mixed-title-wrapper">
					<?php $this->render_taxonomy( $settings ); ?>
					<?php $this->render_title( $settings ); ?>
					<?php $this->render_woo_price( $settings ); ?>
				</div>
			</div>

			<!-- Row 2: Other Content -->
			<div class="mn-post-mixed-row2">
				<?php $this->render_woo_stock_status( $settings ); ?>
				<?php $this->render_meta( $settings ); ?>
				<?php $this->render_excerpt( $settings ); ?>
				<?php $this->render_custom_meta( $settings ); ?>
				<?php $this->render_readmore( $settings ); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render overlay layout: Featured image as background overlay with solid color item background
	 */
	private function render_overlay_layout( $settings, $filter_class ) {
		$image_url = '';
		if ( has_post_thumbnail() ) {
			$image_url = get_the_post_thumbnail_url( get_the_ID(), 'large' );
		}
		?>
		<div class="mn-post-overlay-wrapper">
			<!-- Background overlay with featured image - covers 80% from top, expands to 100% on hover -->
			<div class="mn-post-overlay-bg" <?php echo $image_url ? 'style="background-image: url(' . esc_url( $image_url ) . ');"' : ''; ?>></div>
			
			<!-- Container 1: Title, Category, Excerpt (80% top area) -->
			<div class="mn-post-overlay-content">
				<?php $this->render_taxonomy( $settings ); ?>				
				<?php $this->render_custom_meta( $settings ); ?>
				<?php $this->render_title( $settings ); ?>
				<?php $this->render_excerpt( $settings ); ?>				
			</div>
			
			<!-- Container 2: Read More Button (20% bottom area) -->
			<div class="mn-post-overlay-readmore">
				<?php $this->render_readmore( $settings ); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render custom layout with user-defined element order
	 */
	private function render_custom_layout( $settings, $filter_class ) {
		$element_order = isset( $settings['element_order'] ) ? $settings['element_order'] : [ 'image', 'title', 'meta', 'excerpt', 'custom_meta', 'readmore' ];
		?>
		<div class="mn-post-custom-wrapper">
			<?php
			foreach ( $element_order as $element ) {
				switch ( $element ) {
					case 'image':
						if ( $settings['show_image'] && has_post_thumbnail() ) {
							?>
							<div class="mn-post-image <?php echo esc_attr( $filter_class ); ?>">
								<a href="<?php the_permalink(); ?>">
									<?php the_post_thumbnail( 'medium_large' ); ?>
								</a>
							</div>
							<?php
						}
						break;
					case 'taxonomy':
						$this->render_taxonomy( $settings );
						break;
					case 'title':
						$this->render_title( $settings );
						break;
					case 'meta':
						$this->render_meta( $settings );
						break;
					case 'excerpt':
						$this->render_excerpt( $settings );
						break;
					case 'custom_meta':
						$this->render_custom_meta( $settings );
						break;
					case 'readmore':
						$this->render_readmore( $settings );
						break;
				}
			}
			?>
		</div>
		<?php
	}

	/**
	 * Render taxonomy element
	 */
	private function render_taxonomy( $settings ) {
		if ( ! isset( $settings['show_taxonomy'] ) || $settings['show_taxonomy'] !== 'yes' ) {
			return;
		}

		$taxonomy = isset( $settings['taxonomy_to_show'] ) ? $settings['taxonomy_to_show'] : 'category';
		$limit = isset( $settings['taxonomy_limit'] ) ? intval( $settings['taxonomy_limit'] ) : 3;
		$terms = get_the_terms( get_the_ID(), $taxonomy );

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			$terms = array_slice( $terms, 0, $limit );
			?>
			<div class="mn-post-taxonomy">
				<?php foreach ( $terms as $term ) : ?>
					<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="mn-taxonomy-term">
						<?php echo esc_html( $term->name ); ?>
					</a>
				<?php endforeach; ?>
			</div>
			<?php
		}
	}

	/**
	 * Render title element
	 */
	private function render_title( $settings ) {
		if ( ! isset( $settings['show_title'] ) || $settings['show_title'] !== 'yes' ) {
			return;
		}
		?>
		<h3 class="mn-post-title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>
		<?php
	}

	/**
	 * Render meta element
	 */
	private function render_meta( $settings ) {
		if ( ! isset( $settings['show_meta'] ) || $settings['show_meta'] !== 'yes' ) {
			return;
		}

		$meta_items = [];

		// Date
		if ( isset( $settings['show_date'] ) && $settings['show_date'] === 'yes' ) {
			$date_format = get_option( 'date_format' ); // Default WordPress format

			// Check if custom date format is selected
			if ( isset( $settings['date_format'] ) && $settings['date_format'] !== 'wp_default' ) {
				if ( $settings['date_format'] === 'custom' && ! empty( $settings['custom_date_format'] ) ) {
					// Use custom format
					$date_format = $settings['custom_date_format'];
				} else {
					// Use predefined format
					$date_format = $settings['date_format'];
				}
			}

			// Check if using Event Date ordering - use Event Start Date instead of post date
			$is_event_date_order = isset( $settings['orderby'] ) && $settings['orderby'] === 'event_date';
			
			if ( $is_event_date_order ) {
				// Get Event Start Date from The Events Calendar meta field
				$event_start_date = get_post_meta( get_the_ID(), '_EventStartDate', true );
				if ( ! empty( $event_start_date ) ) {
					$event_timestamp = strtotime( $event_start_date );
					$formatted_date = date_i18n( $date_format, $event_timestamp );
				} else {
					// Fallback to post date if no event date found
					$formatted_date = get_the_date( $date_format );
				}
			} else {
				$formatted_date = get_the_date( $date_format );
			}
			
			$meta_content = '<span class="mn-post-date"><i class="far fa-calendar-alt"></i> ' . $formatted_date . '</span>';

			// Add time if enabled
			if ( isset( $settings['show_time'] ) && $settings['show_time'] === 'yes' ) {
				$time_format = get_option( 'time_format' ); // Default WordPress time format

				// Check if custom time format is selected
				if ( isset( $settings['time_format'] ) && $settings['time_format'] !== 'wp_default' ) {
					if ( $settings['time_format'] === 'custom' && ! empty( $settings['custom_time_format'] ) ) {
						// Use custom time format
						$time_format = $settings['custom_time_format'];
					} else {
						// Use predefined time format
						$time_format = $settings['time_format'];
					}
				}

				// Use Event Start Time if using Event Date ordering
				if ( $is_event_date_order && ! empty( $event_start_date ) ) {
					$formatted_time = date_i18n( $time_format, $event_timestamp );
				} else {
					$formatted_time = get_the_time( $time_format );
				}
				
				$separator = ( isset( $settings['date_time_separator'] ) && ! empty( $settings['date_time_separator'] ) ) ? $settings['date_time_separator'] : ' at ';
				$meta_content = '<span class="mn-post-date"><i class="far fa-calendar-alt"></i> ' . $formatted_date . '<span class="mn-post-time">' . $separator . $formatted_time . '</span></span>';
			}

			$meta_items[] = $meta_content;
		}

		// Author
		if ( isset( $settings['show_author'] ) && $settings['show_author'] === 'yes' ) {
			$meta_items[] = '<span class="mn-post-author"><i class="far fa-user"></i> ' . esc_html__( 'by', 'mn-elements' ) . ' ' . get_the_author() . '</span>';
		}

		// Categories
		if ( isset( $settings['show_categories'] ) && $settings['show_categories'] === 'yes' ) {
			$categories = get_the_category();
			if ( ! empty( $categories ) ) {
				$category_names = array_map( function( $cat ) {
					return '<a href="' . esc_url( get_category_link( $cat->term_id ) ) . '">' . esc_html( $cat->name ) . '</a>';
				}, $categories );
				$meta_items[] = '<span class="mn-post-categories"><i class="far fa-folder"></i> ' . implode( ', ', $category_names ) . '</span>';
			}
		}

		// Tags
		if ( isset( $settings['show_tags'] ) && $settings['show_tags'] === 'yes' ) {
			$tags = get_the_tags();
			if ( ! empty( $tags ) ) {
				$tag_names = array_map( function( $tag ) {
					return '<a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '">' . esc_html( $tag->name ) . '</a>';
				}, $tags );
				$meta_items[] = '<span class="mn-post-tags"><i class="fas fa-tags"></i> ' . implode( ', ', $tag_names ) . '</span>';
			}
		}

		// Comments
		if ( isset( $settings['show_comments'] ) && $settings['show_comments'] === 'yes' ) {
			$comments_count = get_comments_number();
			if ( $comments_count > 0 ) {
				$meta_items[] = '<span class="mn-post-comments"><i class="far fa-comments"></i> ' . sprintf( _n( '%s Comment', '%s Comments', $comments_count, 'mn-elements' ), number_format_i18n( $comments_count ) ) . '</span>';
			}
		}

		// Custom Meta
		if ( isset( $settings['show_custom_meta'] ) && $settings['show_custom_meta'] === 'yes' && ! empty( $settings['custom_meta_field_listing'] ) ) {
			$custom_meta_value = get_post_meta( get_the_ID(), $settings['custom_meta_field_listing'], true );
			if ( ! empty( $custom_meta_value ) ) {
				$meta_items[] = '<span class="mn-post-custom-meta">' . esc_html( $custom_meta_value ) . '</span>';
			}
		}

		// Output meta if there are items
		if ( ! empty( $meta_items ) ) {
			?>
			<div class="mn-post-meta">
				<?php echo implode( ' ', $meta_items ); ?>
			</div>
			<?php
		}
	}

	/**
	 * Render excerpt element
	 */
	private function render_excerpt( $settings ) {
		if ( ! isset( $settings['show_excerpt'] ) || $settings['show_excerpt'] !== 'yes' ) {
			return;
		}
		?>
		<div class="mn-post-excerpt">
			<?php echo wp_trim_words( get_the_excerpt(), $settings['excerpt_length'], '...' ); ?>
		</div>
		<?php
	}

	/**
	 * Render custom meta element
	 */
	private function render_custom_meta( $settings ) {
		if ( ! isset( $settings['show_custom_meta'] ) || $settings['show_custom_meta'] !== 'yes' || empty( $settings['custom_meta_field_listing'] ) ) {
			return;
		}

		$custom_meta_value = $this->get_custom_field_value( get_the_ID(), $settings['custom_meta_field_listing'] );
		if ( ! empty( $custom_meta_value ) ) {
			?>
			<div class="mn-post-custom-meta">
				<?php echo esc_html( $custom_meta_value ); ?>
			</div>
			<?php
		}
	}

	/**
	 * Render read more element
	 */
	private function render_readmore( $settings ) {
		$show_readmore = isset( $settings['show_readmore'] ) && $settings['show_readmore'] === 'yes';
		$is_product = $settings['post_type'] === 'product' && $this->is_woocommerce_active();
		$cart_position = isset( $settings['woo_cart_position'] ) ? $settings['woo_cart_position'] : 'beside_readmore';
		$show_add_to_cart = $is_product && isset( $settings['woo_show_add_to_cart'] ) && $settings['woo_show_add_to_cart'] === 'yes';

		// If neither readmore nor add to cart should be shown, return early
		if ( ! $show_readmore && ! $show_add_to_cart ) {
			return;
		}

		// Render above readmore position
		if ( $show_add_to_cart && $cart_position === 'above_readmore' ) {
			?>
			<div class="mn-post-add-to-cart mn-cart-above">
				<?php $this->render_woo_add_to_cart( $settings ); ?>
			</div>
			<?php
		}

		// Render readmore and beside position
		if ( $show_readmore || ( $show_add_to_cart && $cart_position === 'beside_readmore' ) ) {
			$wrapper_class = 'mn-post-readmore';
			if ( $show_add_to_cart && $cart_position === 'beside_readmore' ) {
				$wrapper_class .= ' mn-readmore-with-cart';
			}
			?>
			<div class="<?php echo esc_attr( $wrapper_class ); ?>">
				<?php if ( $show_add_to_cart && $cart_position === 'beside_readmore' ) : ?>
					<?php $this->render_woo_add_to_cart( $settings ); ?>
				<?php endif; ?>
				<?php if ( $show_readmore ) : ?>
					<?php $this->render_readmore_button( $settings ); ?>
				<?php endif; ?>
			</div>
			<?php
		}

		// Render below readmore position
		if ( $show_add_to_cart && $cart_position === 'below_readmore' ) {
			?>
			<div class="mn-post-add-to-cart mn-cart-below">
				<?php $this->render_woo_add_to_cart( $settings ); ?>
			</div>
			<?php
		}
	}

	/**
	 * Render read more button
	 */
	private function render_readmore_button( $settings ) {
		$icon_classes = [];
		if ( ! empty( $settings['icon_loop_animation'] ) ) {
			$icon_classes[] = 'mn-icon-loop-' . $settings['icon_loop_animation'];
		}
		if ( ! empty( $settings['icon_hover_animation'] ) ) {
			$icon_classes[] = 'mn-icon-hover-' . $settings['icon_hover_animation'];
		}

		$icon_position_class = 'mn-icon-' . $settings['icon_position'];

		// Determine URL and attributes based on URL option
		$url = '';
		$attributes = '';
		$button_class = 'mn-readmore-button';

		switch ( $settings['url_option'] ) {
			case 'custom':
				if ( ! empty( $settings['custom_url']['url'] ) ) {
					$url = esc_url( $settings['custom_url']['url'] );
					if ( $settings['custom_url']['is_external'] ) {
						$attributes .= ' target="_blank"';
					}
					if ( $settings['custom_url']['nofollow'] ) {
						$attributes .= ' rel="nofollow"';
					}
				} else {
					$url = get_the_permalink();
				}
				break;
			
			case 'add_to_cart':
				if ( $this->is_woocommerce_active() && $settings['post_type'] === 'product' ) {
					$product_id = get_the_ID();
					$url = '#';
					$button_class .= ' mn-add-to-cart-button';
					$attributes .= ' data-product-id="' . esc_attr( $product_id ) . '"';
					$attributes .= ' data-action="add_to_cart"';
				} else {
					$url = get_the_permalink();
				}
				break;
			
			case 'direct_checkout':
				if ( $this->is_woocommerce_active() && $settings['post_type'] === 'product' ) {
					$product_id = get_the_ID();
					$url = '#';
					$button_class .= ' mn-direct-checkout-button';
					$attributes .= ' data-product-id="' . esc_attr( $product_id ) . '"';
					$attributes .= ' data-action="direct_checkout"';
				} else {
					$url = get_the_permalink();
				}
				break;
			
			case 'quickview':
				$url = '#';
				$button_class .= ' mn-quickview-trigger';
				
				// Prepare quickview data (like MN View approach)
				$post_id = get_the_ID();
				$quickview_data = [];
				
				// Left column data
				$left_content = '';
				
				// Featured image
				if ( isset( $settings['quickview_show_image'] ) && $settings['quickview_show_image'] === 'yes' && has_post_thumbnail( $post_id ) ) {
					$left_content .= '<div class="mn-quickview-image">';
					$left_content .= get_the_post_thumbnail( $post_id, 'large' );
					$left_content .= '</div>';
				}
				
				// Title
				if ( isset( $settings['quickview_show_title'] ) && $settings['quickview_show_title'] === 'yes' ) {
					$left_content .= '<h2 class="mn-quickview-title">' . esc_html( get_the_title( $post_id ) ) . '</h2>';
				}
				
				// Post meta (date, author) - hidden in quickview
				// $left_content .= '<div class="mn-quickview-post-meta">';
				// $left_content .= '<span class="mn-quickview-date">' . esc_html( get_the_date( '', $post_id ) ) . '</span>';
				// $left_content .= '<span class="mn-quickview-author">' . esc_html__( 'by', 'mn-elements' ) . ' ' . esc_html( get_the_author_meta( 'display_name' ) ) . '</span>';
				// $left_content .= '</div>';
				
				// Custom meta
				if ( isset( $settings['quickview_show_meta'] ) && $settings['quickview_show_meta'] === 'yes' && ! empty( $settings['custom_meta_field'] ) ) {
					$custom_meta_value = $this->get_custom_field_value( $post_id, $settings['custom_meta_field'] );
					if ( ! empty( $custom_meta_value ) ) {
						$left_content .= '<div class="mn-quickview-meta">' . esc_html( $custom_meta_value ) . '</div>';
					}
				}
				
				// Right column data
				$right_content = '';
				
				// Content
				if ( isset( $settings['quickview_show_content'] ) && $settings['quickview_show_content'] === 'yes' ) {
					$content = get_post_field( 'post_content', $post_id );
					if ( ! empty( $content ) ) {
						// Strip shortcodes and apply basic content filters
						$content = strip_shortcodes( $content );
						$content = wp_strip_all_tags( $content );
						// Trim words if length is specified
						$content_length = isset( $settings['quickview_content_length'] ) ? intval( $settings['quickview_content_length'] ) : 50;
						$content = wp_trim_words( $content, $content_length, '...' );
						$right_content .= '<div class="mn-quickview-post-content">' . esc_html( $content ) . '</div>';
					} else {
						// Fallback to excerpt if no content
						$excerpt = get_the_excerpt( $post_id );
						if ( ! empty( $excerpt ) ) {
							$right_content .= '<div class="mn-quickview-post-content">' . esc_html( $excerpt ) . '</div>';
						}
					}
				}
				
				// Add read more link
				$right_content .= '<div class="mn-quickview-read-more">';
				$right_content .= '<a href="' . esc_url( get_permalink( $post_id ) ) . '" class="mn-quickview-read-more-link">' . esc_html__( 'Read Full Profile', 'mn-elements' ) . '</a>';
				$right_content .= '</div>';
				
				// Add data attributes with content
				$attributes .= ' data-post-id="' . $post_id . '"';
				$attributes .= ' data-left-content="' . esc_attr( $left_content ) . '"';
				$attributes .= ' data-right-content="' . esc_attr( $right_content ) . '"';
				$attributes .= ' data-post-title="' . esc_attr( get_the_title( $post_id ) ) . '"';
				$attributes .= ' data-post-url="' . esc_attr( get_permalink( $post_id ) ) . '"';
				break;
			
			case 'custom_meta':
				$meta_field_key = isset( $settings['url_meta_field'] ) ? $settings['url_meta_field'] : '';
				$link_option = isset( $settings['url_meta_link_option'] ) ? $settings['url_meta_link_option'] : 'same_window';
				
				if ( ! empty( $meta_field_key ) ) {
					$meta_url = get_post_meta( get_the_ID(), $meta_field_key, true );
					if ( ! empty( $meta_url ) ) {
						$url = esc_url( $meta_url );
						
						// Handle link options
						if ( $link_option === 'new_window' ) {
							$attributes .= ' target="_blank" rel="noopener noreferrer"';
						} elseif ( $link_option === 'youtube_modal' ) {
							// Check if it's a YouTube URL
							if ( preg_match( '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $meta_url, $matches ) ) {
								$youtube_id = $matches[1];
								$url = '#';
								$button_class .= ' mn-posts-youtube-trigger';
								$attributes .= ' data-youtube-id="' . esc_attr( $youtube_id ) . '"';
								$attributes .= ' data-youtube-url="' . esc_attr( $meta_url ) . '"';
							}
						}
					} else {
						$url = get_the_permalink();
					}
				} else {
					$url = get_the_permalink();
				}
				break;
			
			case 'default':
			default:
				$url = get_the_permalink();
				break;
		}

		?>
		<a href="<?php echo esc_url( $url ); ?>" class="<?php echo esc_attr( $button_class ); ?>"<?php echo $attributes; ?>>
			<?php if ( $settings['icon_position'] === 'before' && ! empty( $settings['readmore_icon']['value'] ) ) : ?>
				<span class="mn-readmore-icon mn-icon-before <?php echo esc_attr( implode( ' ', $icon_classes ) ); ?>">
					<?php Icons_Manager::render_icon( $settings['readmore_icon'], [ 'aria-hidden' => 'true' ] ); ?>
				</span>
			<?php endif; ?>
			
			<span class="mn-readmore-text"><?php echo esc_html( $settings['readmore_text'] ); ?></span>
			
			<?php if ( $settings['icon_position'] === 'after' && ! empty( $settings['readmore_icon']['value'] ) ) : ?>
				<span class="mn-readmore-icon mn-icon-after <?php echo esc_attr( implode( ' ', $icon_classes ) ); ?>">
					<?php Icons_Manager::render_icon( $settings['readmore_icon'], [ 'aria-hidden' => 'true' ] ); ?>
				</span>
			<?php endif; ?>
		</a>
		<?php
	}

	/**
	 * Render quickview modal content
	 */
	private function render_quickview_modal( $settings ) {
		if ( $settings['url_option'] !== 'quickview' ) {
			return;
		}
		?>
		<div id="mn-quickview-modal" class="mn-quickview-modal" style="display: none;">
			<div class="mn-quickview-overlay"></div>
			<div class="mn-quickview-container">
				<div class="mn-quickview-close">&times;</div>
				<div class="mn-quickview-content">
					<div class="mn-quickview-left">
						<!-- Content will be loaded via JavaScript -->
					</div>
					<div class="mn-quickview-right">
						<!-- Content will be loaded via JavaScript -->
					</div>
				</div>
			</div>
		</div>
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			// Handle quickview trigger (No AJAX - using data from HTML like MN View)
			$(document).on('click', '.mn-quickview-trigger', function(e) {
				e.preventDefault();
				
				// Get data from HTML attributes
				var $trigger = $(this);
				var postId = $trigger.data('post-id');
				var leftContent = $trigger.data('left-content');
				var rightContent = $trigger.data('right-content');
				var postTitle = $trigger.data('post-title');
				var postUrl = $trigger.data('post-url');
				
				if (!postId) {
					console.error('MN Posts Quickview: No post ID found');
					return;
				}
				
				console.log('MN Posts Quickview: Loading post', postId);
				
				// Show modal and populate with data immediately
				$('#mn-quickview-modal').fadeIn(300);
				
				// Populate content from data attributes
				if (leftContent) {
					$('.mn-quickview-left').html(leftContent);
				} else {
					$('.mn-quickview-left').html('<div class="mn-quickview-error">No content available</div>');
				}
				
				if (rightContent) {
					$('.mn-quickview-right').html(rightContent);
				} else {
					$('.mn-quickview-right').html('<div class="mn-quickview-error">No content available</div>');
				}
				
				console.log('MN Posts Quickview: Content loaded successfully');
			});
			
			// Close modal
			$(document).on('click', '.mn-quickview-close, .mn-quickview-overlay', function() {
				$('#mn-quickview-modal').fadeOut(300);
			});
			
			// Close on ESC key
			$(document).keyup(function(e) {
				if (e.keyCode === 27) {
					$('#mn-quickview-modal').fadeOut(300);
				}
			});
		});
		</script>
		<?php
	}

	/**
	 * Render YouTube modal
	 */
	private function render_youtube_modal( $settings ) {
		if ( ! isset( $settings['url_option'] ) || $settings['url_option'] !== 'custom_meta' ) {
			return;
		}
		if ( ! isset( $settings['url_meta_link_option'] ) || $settings['url_meta_link_option'] !== 'youtube_modal' ) {
			return;
		}
		?>
		<div id="mn-youtube-modal" class="mn-youtube-modal" style="display: none;">
			<div class="mn-youtube-modal-overlay"></div>
			<div class="mn-youtube-modal-container">
				<div class="mn-youtube-modal-close">&times;</div>
				<div class="mn-youtube-modal-content">
					<div class="mn-youtube-modal-iframe-wrapper">
						<iframe id="mn-youtube-iframe" src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
					</div>
				</div>
			</div>
		</div>
		<style>
		#mn-youtube-modal {
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
		#mn-youtube-modal .mn-youtube-modal-overlay {
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background: rgba(0, 0, 0, 0.9);
		}
		#mn-youtube-modal .mn-youtube-modal-container {
			position: relative;
			width: 90%;
			max-width: 900px;
			z-index: 1;
		}
		#mn-youtube-modal .mn-youtube-modal-close {
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
		#mn-youtube-modal .mn-youtube-modal-close:hover {
			background: #ff0000;
			color: #fff;
		}
		#mn-youtube-modal .mn-youtube-modal-iframe-wrapper {
			position: relative;
			padding-bottom: 56.25%;
			height: 0;
			overflow: hidden;
		}
		#mn-youtube-modal .mn-youtube-modal-iframe-wrapper iframe {
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
		}
		</style>
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			console.log('MN Posts YouTube Modal: Script loaded');
			
			// Handle YouTube modal trigger
			$(document).on('click', '.mn-posts-youtube-trigger', function(e) {
				e.preventDefault();
				console.log('MN Posts YouTube Modal: Trigger clicked');
				
				var youtubeId = $(this).data('youtube-id');
				console.log('MN Posts YouTube Modal: YouTube ID = ' + youtubeId);
				
				if (!youtubeId) {
					console.error('MN Posts: No YouTube ID found');
					return;
				}
				
				// Set iframe src with autoplay
				var embedUrl = 'https://www.youtube.com/embed/' + youtubeId + '?autoplay=1&rel=0';
				console.log('MN Posts YouTube Modal: Embed URL = ' + embedUrl);
				$('#mn-youtube-iframe').attr('src', embedUrl);
				
				// Show modal
				$('#mn-youtube-modal').fadeIn(300);
			});
			
			// Close YouTube modal
			$(document).on('click', '#mn-youtube-modal .mn-youtube-modal-close, #mn-youtube-modal .mn-youtube-modal-overlay', function() {
				$('#mn-youtube-modal').fadeOut(300, function() {
					// Stop video by clearing src
					$('#mn-youtube-iframe').attr('src', '');
				});
			});
			
			// Close on ESC key
			$(document).keyup(function(e) {
				if (e.keyCode === 27 && $('#mn-youtube-modal').is(':visible')) {
					$('#mn-youtube-modal').fadeOut(300, function() {
						$('#mn-youtube-iframe').attr('src', '');
					});
				}
			});
		});
		</script>
		<?php
	}

	/**
	 * Render pagination
	 */
	private function render_pagination( $posts_query, $settings ) {
		if ( $posts_query->max_num_pages <= 1 ) {
			return;
		}

		$paged = max( 1, get_query_var( 'paged' ) );
		if ( is_front_page() ) {
			$paged = max( 1, get_query_var( 'page' ) );
		}

		$max_pages = $posts_query->max_num_pages;
		$page_limit = intval( $settings['pagination_page_limit'] );
		$pagination_type = $settings['pagination_type'];
		$prev_text = ! empty( $settings['pagination_prev_text'] ) ? $settings['pagination_prev_text'] : esc_html__( 'Previous', 'mn-elements' );
		$next_text = ! empty( $settings['pagination_next_text'] ) ? $settings['pagination_next_text'] : esc_html__( 'Next', 'mn-elements' );

		?>
		<div class="mn-posts-pagination">
			<?php
			switch ( $pagination_type ) {
				case 'prev_next':
					// Show previous button
					if ( $paged > 1 ) {
						$prev_link = get_pagenum_link( $paged - 1 );
						echo '<a href="' . esc_url( $prev_link ) . '" class="mn-pagination-prev">' . esc_html( $prev_text ) . '</a>';
					}
					// Show next button
					if ( $paged < $max_pages ) {
						$next_link = get_pagenum_link( $paged + 1 );
						echo '<a href="' . esc_url( $next_link ) . '" class="mn-pagination-next">' . esc_html( $next_text ) . '</a>';
					}
					break;

				case 'numbers_prev_next':
					// Show previous button
					if ( $paged > 1 ) {
						$prev_link = get_pagenum_link( $paged - 1 );
						echo '<a href="' . esc_url( $prev_link ) . '" class="mn-pagination-prev">' . esc_html( $prev_text ) . '</a>';
					}
					// Show numbers
					$this->render_numbers_pagination( $paged, $max_pages, $page_limit );
					// Show next button
					if ( $paged < $max_pages ) {
						$next_link = get_pagenum_link( $paged + 1 );
						echo '<a href="' . esc_url( $next_link ) . '" class="mn-pagination-next">' . esc_html( $next_text ) . '</a>';
					}
					break;

				case 'numbers':
				default:
					$this->render_numbers_pagination( $paged, $max_pages, $page_limit );
					break;
			}
			?>
		</div>
		<?php
	}

	/**
	 * Render numbers pagination
	 */
	private function render_numbers_pagination( $paged, $max_pages, $page_limit ) {
		// Calculate start and end pages
		$start_page = max( 1, $paged - floor( $page_limit / 2 ) );
		$end_page = min( $max_pages, $start_page + $page_limit - 1 );

		// Adjust start page if we're near the end
		if ( $end_page - $start_page + 1 < $page_limit ) {
			$start_page = max( 1, $end_page - $page_limit + 1 );
		}

		// Show first page and dots if needed
		if ( $start_page > 1 ) {
			echo '<a href="' . esc_url( get_pagenum_link( 1 ) ) . '" class="mn-pagination-number">1</a>';
			if ( $start_page > 2 ) {
				echo '<span class="mn-pagination-dots">...</span>';
			}
		}

		// Show page numbers
		for ( $i = $start_page; $i <= $end_page; $i++ ) {
			if ( $i == $paged ) {
				echo '<span class="current mn-pagination-current">' . $i . '</span>';
			} else {
				echo '<a href="' . esc_url( get_pagenum_link( $i ) ) . '" class="mn-pagination-number">' . $i . '</a>';
			}
		}

		// Show last page and dots if needed
		if ( $end_page < $max_pages ) {
			if ( $end_page < $max_pages - 1 ) {
				echo '<span class="mn-pagination-dots">...</span>';
			}
			echo '<a href="' . esc_url( get_pagenum_link( $max_pages ) ) . '" class="mn-pagination-number">' . $max_pages . '</a>';
		}
	}

	/**
	 * Get custom field value with fallback support (like MN View)
	 */
	private function get_custom_field_value( $post_id, $field_name ) {
		// Try ACF first
		if ( function_exists( 'get_field' ) ) {
			$value = get_field( $field_name, $post_id );
			if ( ! empty( $value ) ) {
				// Handle ACF file field
				if ( is_array( $value ) && isset( $value['url'] ) ) {
					return $value['url'];
				}
				return $value;
			}
		}

		// Fallback to WordPress meta
		$value = get_post_meta( $post_id, $field_name, true );
		
		// Handle attachment ID
		if ( is_numeric( $value ) ) {
			$attachment_url = wp_get_attachment_url( $value );
			if ( $attachment_url ) {
				return $attachment_url;
			}
		}

		return $value;
	}

	/**
	 * Handle AJAX quickview request (deprecated - keeping for backward compatibility)
	 */
	public static function handle_quickview_ajax() {
		// Enable error reporting for debugging
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			ini_set( 'display_errors', 1 );
			error_reporting( E_ALL );
		}

		// Check if request is valid
		if ( ! isset( $_POST['nonce'] ) || ! isset( $_POST['post_id'] ) || ! isset( $_POST['settings'] ) ) {
			wp_send_json_error( 'Missing required parameters' );
		}

		// Verify nonce
		if ( ! wp_verify_nonce( $_POST['nonce'], 'mn_posts_quickview_nonce' ) ) {
			wp_send_json_error( 'Security check failed' );
		}

		$post_id = intval( $_POST['post_id'] );
		$settings = $_POST['settings'];

		if ( ! $post_id ) {
			wp_send_json_error( 'Invalid post ID' );
		}

		$current_post = get_post( $post_id );
		if ( ! $current_post || $current_post->post_status !== 'publish' ) {
			wp_send_json_error( 'Post not found or not published' );
		}

		// Left column content
		$left_content = '';
		
		// Featured image
		if ( isset( $settings['quickview_show_image'] ) && $settings['quickview_show_image'] === 'yes' && has_post_thumbnail( $post_id ) ) {
			$left_content .= '<div class="mn-quickview-image">';
			$left_content .= get_the_post_thumbnail( $post_id, 'large' );
			$left_content .= '</div>';
		}

		// Title
		if ( isset( $settings['quickview_show_title'] ) && $settings['quickview_show_title'] === 'yes' ) {
			$left_content .= '<h2 class="mn-quickview-title">' . esc_html( get_the_title( $post_id ) ) . '</h2>';
		}

		// Post meta (date, author) - hidden in quickview
		// $left_content .= '<div class="mn-quickview-post-meta">';
		// $left_content .= '<span class="mn-quickview-date">' . esc_html( get_the_date( '', $post_id ) ) . '</span>';
		// $left_content .= '<span class="mn-quickview-author">' . esc_html__( 'by', 'mn-elements' ) . ' ' . esc_html( get_the_author_meta( 'display_name', $current_post->post_author ) ) . '</span>';
		// $left_content .= '</div>';

		// Custom meta
		if ( isset( $settings['quickview_show_meta'] ) && $settings['quickview_show_meta'] === 'yes' && ! empty( $settings['custom_meta_field'] ) ) {
			$custom_meta_value = get_post_meta( $post_id, $settings['custom_meta_field'], true );
			if ( ! empty( $custom_meta_value ) ) {
				$left_content .= '<div class="mn-quickview-meta">' . esc_html( $custom_meta_value ) . '</div>';
			}
		}

		// Right column content
		$right_content = '';
		
		// Content
		if ( isset( $settings['quickview_show_content'] ) && $settings['quickview_show_content'] === 'yes' ) {
			$content = get_post_field( 'post_content', $post_id );
			if ( ! empty( $content ) ) {
				// Strip shortcodes and apply basic content filters
				$content = strip_shortcodes( $content );
				$content = wp_strip_all_tags( $content );
				// Trim words if length is specified
				$content_length = isset( $settings['quickview_content_length'] ) ? intval( $settings['quickview_content_length'] ) : 50;
				$content = wp_trim_words( $content, $content_length, '...' );
				$right_content .= '<div class="mn-quickview-post-content">' . esc_html( $content ) . '</div>';
			} else {
				// Fallback to excerpt if no content
				$excerpt = get_the_excerpt( $post_id );
				if ( ! empty( $excerpt ) ) {
					$right_content .= '<div class="mn-quickview-post-content">' . esc_html( $excerpt ) . '</div>';
				}
			}
		}

		// Add read more link
		$right_content .= '<div class="mn-quickview-read-more">';
		$right_content .= '<a href="' . esc_url( get_permalink( $post_id ) ) . '" class="mn-quickview-read-more-link">' . esc_html__( 'Read Full Article', 'mn-elements' ) . '</a>';
		$right_content .= '</div>';

		wp_send_json_success([
			'left_content' => $left_content,
			'right_content' => $right_content,
			'post_title' => get_the_title( $post_id ),
			'post_url' => get_permalink( $post_id )
		]);
	}

	/**
	 * Check if WooCommerce is active
	 */
	private function is_woocommerce_active() {
		return class_exists( 'WooCommerce' );
	}

	/**
	 * Render WooCommerce product price
	 */
	private function render_woo_price( $settings ) {
		if ( ! $this->is_woocommerce_active() ) {
			return;
		}

		if ( $settings['post_type'] !== 'product' ) {
			return;
		}

		// Check both old (woo_show_price) and new (show_price) controls for backward compatibility
		$show_price = isset( $settings['show_price'] ) ? $settings['show_price'] : ( isset( $settings['woo_show_price'] ) ? $settings['woo_show_price'] : 'yes' );
		if ( $show_price !== 'yes' ) {
			return;
		}

		global $product;
		$product = wc_get_product( get_the_ID() );

		if ( ! $product ) {
			return;
		}

		$price_html = $product->get_price_html();
		if ( $price_html ) {
			?>
			<div class="mn-product-price">
				<?php echo $price_html; ?>
			</div>
			<?php
		}
	}

	/**
	 * Render WooCommerce sale badge
	 */
	private function render_woo_sale_badge( $settings ) {
		if ( ! $this->is_woocommerce_active() ) {
			return;
		}

		if ( ! isset( $settings['woo_show_sale_badge'] ) || $settings['woo_show_sale_badge'] !== 'yes' ) {
			return;
		}

		if ( $settings['post_type'] !== 'product' ) {
			return;
		}

		global $product;
		$product = wc_get_product( get_the_ID() );

		if ( ! $product || ! $product->is_on_sale() ) {
			return;
		}
		?>
		<span class="mn-sale-badge"><?php esc_html_e( 'Sale!', 'mn-elements' ); ?></span>
		<?php
	}

	/**
	 * Render WooCommerce stock status
	 */
	private function render_woo_stock_status( $settings ) {
		if ( ! $this->is_woocommerce_active() ) {
			return;
		}

		if ( ! isset( $settings['woo_show_stock_status'] ) || $settings['woo_show_stock_status'] !== 'yes' ) {
			return;
		}

		if ( $settings['post_type'] !== 'product' ) {
			return;
		}

		global $product;
		$product = wc_get_product( get_the_ID() );

		if ( ! $product ) {
			return;
		}

		$stock_status = $product->get_stock_status();
		$stock_class = $stock_status === 'instock' ? 'in-stock' : 'out-of-stock';
		$stock_text = $stock_status === 'instock' ? __( 'In Stock', 'mn-elements' ) : __( 'Out of Stock', 'mn-elements' );
		?>
		<div class="mn-stock-status <?php echo esc_attr( $stock_class ); ?>">
			<?php echo esc_html( $stock_text ); ?>
		</div>
		<?php
	}

	/**
	 * Render WooCommerce Add to Cart button with quantity
	 */
	private function render_woo_add_to_cart( $settings ) {
		if ( ! $this->is_woocommerce_active() ) {
			return;
		}

		if ( ! isset( $settings['woo_show_add_to_cart'] ) || $settings['woo_show_add_to_cart'] !== 'yes' ) {
			return;
		}

		if ( $settings['post_type'] !== 'product' ) {
			return;
		}

		global $product;
		$product = wc_get_product( get_the_ID() );

		if ( ! $product ) {
			return;
		}

		// Check if product is purchasable and in stock
		if ( ! $product->is_purchasable() || ! $product->is_in_stock() ) {
			return;
		}

		$product_id = $product->get_id();
		$product_type = $product->get_type();
		$ajax_enabled = isset( $settings['woo_ajax_add_to_cart'] ) && $settings['woo_ajax_add_to_cart'] === 'yes';
		$show_quantity = isset( $settings['woo_show_quantity'] ) && $settings['woo_show_quantity'] === 'yes';
		$button_text = ! empty( $settings['woo_add_to_cart_text'] ) ? $settings['woo_add_to_cart_text'] : $product->add_to_cart_text();

		// Only show quantity for simple products
		$can_show_quantity = $show_quantity && $product_type === 'simple';

		?>
		<div class="mn-add-to-cart-wrapper" data-product-id="<?php echo esc_attr( $product_id ); ?>">
			<?php if ( $can_show_quantity ) : ?>
			<div class="mn-quantity-wrapper">
				<button type="button" class="mn-quantity-btn mn-quantity-minus" aria-label="<?php esc_attr_e( 'Decrease quantity', 'mn-elements' ); ?>">-</button>
				<input type="number" class="mn-quantity-input" value="1" min="1" max="<?php echo esc_attr( $product->get_max_purchase_quantity() > 0 ? $product->get_max_purchase_quantity() : 99 ); ?>" step="1" aria-label="<?php esc_attr_e( 'Product quantity', 'mn-elements' ); ?>">
				<button type="button" class="mn-quantity-btn mn-quantity-plus" aria-label="<?php esc_attr_e( 'Increase quantity', 'mn-elements' ); ?>">+</button>
			</div>
			<?php endif; ?>

			<?php if ( $product_type === 'simple' ) : ?>
				<a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" 
				   data-quantity="1" 
				   data-product_id="<?php echo esc_attr( $product_id ); ?>"
				   data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>"
				   class="mn-add-to-cart-btn <?php echo $ajax_enabled ? 'ajax_add_to_cart' : ''; ?> add_to_cart_button"
				   aria-label="<?php echo esc_attr( sprintf( __( 'Add "%s" to your cart', 'mn-elements' ), $product->get_name() ) ); ?>">
					<?php echo esc_html( $button_text ); ?>
				</a>
			<?php else : ?>
				<a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" 
				   class="mn-add-to-cart-btn"
				   aria-label="<?php echo esc_attr( sprintf( __( 'View "%s"', 'mn-elements' ), $product->get_name() ) ); ?>">
					<?php echo esc_html( $button_text ); ?>
				</a>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Render WooCommerce elements wrapper (for positioning with readmore)
	 */
	private function render_woo_elements( $settings, $position = 'beside_readmore' ) {
		if ( ! $this->is_woocommerce_active() || $settings['post_type'] !== 'product' ) {
			return;
		}

		$cart_position = isset( $settings['woo_cart_position'] ) ? $settings['woo_cart_position'] : 'beside_readmore';

		if ( $cart_position !== $position ) {
			return;
		}

		$this->render_woo_add_to_cart( $settings );
	}

	/**
	 * Generate responsive columns CSS based on settings
	 */
	private function generate_responsive_columns_css( $settings ) {
		$widget_id = $this->get_id();
		$css = '';
		
		// Check if any responsive column settings are set
		$columns_desktop = isset( $settings['columns_desktop'] ) && ! empty( $settings['columns_desktop'] ) ? $settings['columns_desktop'] : '';
		$columns_laptop = isset( $settings['columns_laptop'] ) && ! empty( $settings['columns_laptop'] ) ? $settings['columns_laptop'] : '';
		$columns_tablet_landscape = isset( $settings['columns_tablet_landscape'] ) && ! empty( $settings['columns_tablet_landscape'] ) ? $settings['columns_tablet_landscape'] : '';
		$columns_tablet_portrait = isset( $settings['columns_tablet_portrait'] ) && ! empty( $settings['columns_tablet_portrait'] ) ? $settings['columns_tablet_portrait'] : '';
		$columns_mobile_landscape = isset( $settings['columns_mobile_landscape'] ) && ! empty( $settings['columns_mobile_landscape'] ) ? $settings['columns_mobile_landscape'] : '';
		$columns_mobile_portrait = isset( $settings['columns_mobile_portrait'] ) && ! empty( $settings['columns_mobile_portrait'] ) ? $settings['columns_mobile_portrait'] : '';
		
		// Desktop (>1200px)
		if ( ! empty( $columns_desktop ) ) {
			$css .= '@media (min-width: 1201px) { ';
			$css .= '.elementor-element-' . $widget_id . ' .mn-posts-grid { grid-template-columns: repeat(' . $columns_desktop . ', 1fr); }';
			$css .= ' } ';
		}
		
		// Laptop (1025px - 1200px)
		if ( ! empty( $columns_laptop ) ) {
			$css .= '@media (min-width: 1025px) and (max-width: 1200px) { ';
			$css .= '.elementor-element-' . $widget_id . ' .mn-posts-grid { grid-template-columns: repeat(' . $columns_laptop . ', 1fr); }';
			$css .= ' } ';
		}
		
		// Tablet Landscape (769px - 1024px)
		if ( ! empty( $columns_tablet_landscape ) ) {
			$css .= '@media (min-width: 769px) and (max-width: 1024px) { ';
			$css .= '.elementor-element-' . $widget_id . ' .mn-posts-grid { grid-template-columns: repeat(' . $columns_tablet_landscape . ', 1fr); }';
			$css .= ' } ';
		}
		
		// Tablet Portrait (481px - 768px)
		if ( ! empty( $columns_tablet_portrait ) ) {
			$css .= '@media (min-width: 481px) and (max-width: 768px) { ';
			$css .= '.elementor-element-' . $widget_id . ' .mn-posts-grid { grid-template-columns: repeat(' . $columns_tablet_portrait . ', 1fr); }';
			$css .= ' } ';
		}
		
		// Mobile Landscape (376px - 480px)
		if ( ! empty( $columns_mobile_landscape ) ) {
			$css .= '@media (min-width: 376px) and (max-width: 480px) { ';
			$css .= '.elementor-element-' . $widget_id . ' .mn-posts-grid { grid-template-columns: repeat(' . $columns_mobile_landscape . ', 1fr); }';
			$css .= ' } ';
		}
		
		// Mobile Portrait (<375px)
		if ( ! empty( $columns_mobile_portrait ) ) {
			$css .= '@media (max-width: 375px) { ';
			$css .= '.elementor-element-' . $widget_id . ' .mn-posts-grid { grid-template-columns: repeat(' . $columns_mobile_portrait . ', 1fr); }';
			$css .= ' } ';
		}
		
		return $css;
	}
}

