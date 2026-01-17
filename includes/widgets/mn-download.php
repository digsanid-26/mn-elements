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
 * MN Download Widget
 *
 * File download widget with manual and dynamic source options
 *
 * @since 1.2.2
 */
class MN_Download extends Widget_Base {

	/**
	 * Get widget name.
	 */
	public function get_name() {
		return 'mn-download';
	}

	/**
	 * Get widget title.
	 */
	public function get_title() {
		return esc_html__( 'MN Download', 'mn-elements' );
	}

	/**
	 * Get widget icon.
	 */
	public function get_icon() {
		return 'eicon-download-button';
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
		return [ 'download', 'file', 'attachment', 'document', 'mn', 'dark', 'light', 'theme' ];
	}

	/**
	 * Get style dependencies.
	 */
	public function get_style_depends() {
		return [ 'mn-download' ];
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
		// Download Management Section
		$this->start_controls_section(
			'section_download_management',
			[
				'label' => esc_html__( 'Download Management', 'mn-elements' ),
			]
		);

		$this->add_control(
			'source_type',
			[
				'label' => esc_html__( 'Source', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'manual',
				'options' => [
					'manual' => esc_html__( 'Manual', 'mn-elements' ),
					'dynamic' => esc_html__( 'Dynamic', 'mn-elements' ),
				],
			]
		);

		// Manual Source - Repeater
		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'file_title',
			[
				'label' => esc_html__( 'File Title', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Download File', 'mn-elements' ),
				'placeholder' => esc_html__( 'Enter file title', 'mn-elements' ),
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'file_description',
			[
				'label' => esc_html__( 'File Description', 'mn-elements' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'File description goes here.', 'mn-elements' ),
				'placeholder' => esc_html__( 'Enter file description', 'mn-elements' ),
				'rows' => 3,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'file_type',
			[
				'label' => esc_html__( 'File Type', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'pdf',
				'options' => [
					'pdf' => esc_html__( 'PDF', 'mn-elements' ),
					'doc' => esc_html__( 'DOC', 'mn-elements' ),
					'docx' => esc_html__( 'DOCX', 'mn-elements' ),
					'xls' => esc_html__( 'XLS', 'mn-elements' ),
					'xlsx' => esc_html__( 'XLSX', 'mn-elements' ),
					'ppt' => esc_html__( 'PPT', 'mn-elements' ),
					'pptx' => esc_html__( 'PPTX', 'mn-elements' ),
					'zip' => esc_html__( 'ZIP', 'mn-elements' ),
					'rar' => esc_html__( 'RAR', 'mn-elements' ),
					'jpg' => esc_html__( 'JPG', 'mn-elements' ),
					'png' => esc_html__( 'PNG', 'mn-elements' ),
					'mp3' => esc_html__( 'MP3', 'mn-elements' ),
					'mp4' => esc_html__( 'MP4', 'mn-elements' ),
					'other' => esc_html__( 'Other', 'mn-elements' ),
				],
			]
		);

		$repeater->add_control(
			'download_url',
			[
				'label' => esc_html__( 'Download URL', 'mn-elements' ),
				'type' => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://example.com/file.pdf', 'mn-elements' ),
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'download_list',
			[
				'label' => esc_html__( 'Download Files', 'mn-elements' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'file_title' => esc_html__( 'Sample Document', 'mn-elements' ),
						'file_description' => esc_html__( 'This is a sample document for download.', 'mn-elements' ),
						'file_type' => 'pdf',
						'download_url' => [
							'url' => '#',
						],
					],
					[
						'file_title' => esc_html__( 'Sample Spreadsheet', 'mn-elements' ),
						'file_description' => esc_html__( 'This is a sample spreadsheet for download.', 'mn-elements' ),
						'file_type' => 'xlsx',
						'download_url' => [
							'url' => '#',
						],
					],
				],
				'title_field' => '{{{ file_title }}}',
				'condition' => [
					'source_type' => 'manual',
				],
			]
		);

		// Dynamic Query Controls
		$this->add_control(
			'posts_per_page',
			[
				'label' => esc_html__( 'Posts Per Page', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 6,
				'min' => 1,
				'max' => 100,
				'condition' => [
					'source_type' => 'dynamic',
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
				'condition' => [
					'source_type' => 'dynamic',
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
				'condition' => [
					'source_type' => 'dynamic',
				],
			]
		);

		$this->add_control(
			'term_ids',
			[
				'label' => esc_html__( 'Term IDs', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter term IDs separated by commas (e.g., 1,2,3)', 'mn-elements' ),
				'description' => esc_html__( 'Leave empty to include all terms from selected taxonomy.', 'mn-elements' ),
				'condition' => [
					'source_type' => 'dynamic',
					'taxonomy!' => '',
				],
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
				'condition' => [
					'source_type' => 'dynamic',
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
				'condition' => [
					'source_type' => 'dynamic',
				],
			]
		);

		// Custom Fields for Dynamic Source
		$this->add_control(
			'custom_field_heading',
			[
				'label' => esc_html__( 'Custom Fields', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'source_type' => 'dynamic',
				],
			]
		);

		$this->add_control(
			'title_field',
			[
				'label' => esc_html__( 'Title Field', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'post_title',
				'options' => [
					'post_title' => esc_html__( 'Post Title', 'mn-elements' ),
					'custom_field' => esc_html__( 'Custom Field', 'mn-elements' ),
				],
				'condition' => [
					'source_type' => 'dynamic',
				],
			]
		);

		$this->add_control(
			'title_custom_field',
			[
				'label' => esc_html__( 'Title Custom Field Name', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'file_title', 'mn-elements' ),
				'description' => esc_html__( 'Enter custom field name for file title (supports JetEngine and ACF)', 'mn-elements' ),
				'condition' => [
					'source_type' => 'dynamic',
					'title_field' => 'custom_field',
				],
			]
		);

		$this->add_control(
			'download_url_field',
			[
				'label' => esc_html__( 'Download URL Custom Field Name', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'download_url', 'mn-elements' ),
				'description' => esc_html__( 'Enter custom field name for download URL (supports JetEngine and ACF)', 'mn-elements' ),
				'condition' => [
					'source_type' => 'dynamic',
				],
			]
		);

		$this->add_control(
			'description_field',
			[
				'label' => esc_html__( 'Description Field', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'post_excerpt',
				'options' => [
					'post_excerpt' => esc_html__( 'Post Excerpt', 'mn-elements' ),
					'post_content' => esc_html__( 'Post Content', 'mn-elements' ),
					'custom_field' => esc_html__( 'Custom Field', 'mn-elements' ),
				],
				'condition' => [
					'source_type' => 'dynamic',
				],
			]
		);

		$this->add_control(
			'description_custom_field',
			[
				'label' => esc_html__( 'Description Custom Field Name', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'file_description', 'mn-elements' ),
				'description' => esc_html__( 'Enter custom field name for file description (supports JetEngine and ACF)', 'mn-elements' ),
				'condition' => [
					'source_type' => 'dynamic',
					'description_field' => 'custom_field',
				],
			]
		);

		$this->add_control(
			'file_type_field',
			[
				'label' => esc_html__( 'File Type Custom Field Name', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'file_type', 'mn-elements' ),
				'description' => esc_html__( 'Enter custom field name for file type (supports JetEngine and ACF)', 'mn-elements' ),
				'condition' => [
					'source_type' => 'dynamic',
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
				'default' => 'grid',
				'options' => [
					'grid' => esc_html__( 'Grid', 'mn-elements' ),
					'list' => esc_html__( 'List', 'mn-elements' ),
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
					'6' => '6',
				],
				'condition' => [
					'layout_type' => 'grid',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-download-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
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
			'show_description',
			[
				'label' => esc_html__( 'Show Description', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_file_type',
			[
				'label' => esc_html__( 'Show File Type', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
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
				'selector' => '{{WRAPPER}} .mn-download-item-title',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'label' => esc_html__( 'Description Typography', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-download-item-description',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'label' => esc_html__( 'Button Typography', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-download-button',
			]
		);

		$this->end_controls_section();

		// Download Item Style Section
		$this->start_controls_section(
			'section_download_item_style',
			[
				'label' => esc_html__( 'Download Item', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'item_background',
				'label' => esc_html__( 'Background', 'mn-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .mn-download-item',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'item_background_hover',
				'label' => esc_html__( 'Background Hover', 'mn-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .mn-download-item:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'item_border',
				'label' => esc_html__( 'Border', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-download-item',
			]
		);

		$this->add_control(
			'item_border_hover_color',
			[
				'label' => esc_html__( 'Border Hover Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-download-item:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'item_border_border!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'item_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .mn-download-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'item_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .mn-download-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-download-item',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_box_shadow_hover',
				'label' => esc_html__( 'Box Shadow Hover', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-download-item:hover',
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
					'{{WRAPPER}} .mn-download-item-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => esc_html__( 'Description Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-download-item-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_color',
			[
				'label' => esc_html__( 'Button Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-download-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label' => esc_html__( 'Button Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-download-button' => 'background-color: {{VALUE}};',
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
		$options = [ '' => esc_html__( 'Select Taxonomy', 'mn-elements' ) ];

		foreach ( $taxonomies as $taxonomy ) {
			$options[ $taxonomy->name ] = $taxonomy->label;
		}

		return $options;
	}

	/**
	 * Get dynamic download list from posts
	 */
	private function get_dynamic_download_list( $settings ) {
		$args = [
			'post_type' => $settings['post_type'],
			'posts_per_page' => $settings['posts_per_page'],
			'orderby' => $settings['orderby'],
			'order' => $settings['order'],
			'post_status' => 'publish',
		];

		// Add taxonomy query if taxonomy and term_ids are set
		if ( ! empty( $settings['taxonomy'] ) && ! empty( $settings['term_ids'] ) ) {
			$term_ids = array_map( 'trim', explode( ',', $settings['term_ids'] ) );
			$term_ids = array_map( 'intval', $term_ids );
			$term_ids = array_filter( $term_ids );

			if ( ! empty( $term_ids ) ) {
				$args['tax_query'] = [
					[
						'taxonomy' => $settings['taxonomy'],
						'field'    => 'term_id',
						'terms'    => $term_ids,
					],
				];
			}
		}

		$query = new \WP_Query( $args );
		$download_list = [];

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$post_id = get_the_ID();

				// Get file title
				$file_title = '';
				if ( $settings['title_field'] === 'post_title' ) {
					$file_title = get_the_title();
				} elseif ( $settings['title_field'] === 'custom_field' && ! empty( $settings['title_custom_field'] ) ) {
					$file_title = $this->get_custom_field_value( $post_id, $settings['title_custom_field'] );
				}

				// Get download URL from custom field
				$download_url = '';
				if ( ! empty( $settings['download_url_field'] ) ) {
					$download_url = $this->get_custom_field_value( $post_id, $settings['download_url_field'] );
				}

				// Get file description
				$file_description = '';
				if ( $settings['description_field'] === 'post_excerpt' ) {
					$file_description = get_the_excerpt();
				} elseif ( $settings['description_field'] === 'post_content' ) {
					$file_description = get_the_content();
				} elseif ( $settings['description_field'] === 'custom_field' && ! empty( $settings['description_custom_field'] ) ) {
					$file_description = $this->get_custom_field_value( $post_id, $settings['description_custom_field'] );
				}

				// Get file type
				$file_type = 'other';
				if ( ! empty( $settings['file_type_field'] ) ) {
					$file_type = $this->get_custom_field_value( $post_id, $settings['file_type_field'] );
				}

				// Only add if we have a valid download URL and title
				if ( ! empty( $download_url ) && ! empty( $file_title ) ) {
					$download_list[] = [
						'file_title' => $file_title,
						'download_url' => [
							'url' => $download_url,
						],
						'file_description' => $file_description,
						'file_type' => $file_type,
					];
				}
			}
			wp_reset_postdata();
		}

		return $download_list;
	}

	/**
	 * Get custom field value with support for ACF and JetEngine
	 */
	private function get_custom_field_value( $post_id, $field_name ) {
		$value = '';

		// Try ACF first
		if ( function_exists( 'get_field' ) ) {
			$value = get_field( $field_name, $post_id );
		}

		// If no ACF value, try JetEngine
		if ( empty( $value ) && function_exists( 'jet_engine' ) ) {
			$value = get_post_meta( $post_id, $field_name, true );
		}

		// Fallback to standard meta
		if ( empty( $value ) ) {
			$value = get_post_meta( $post_id, $field_name, true );
		}

		return $value;
	}

	/**
	 * Render widget output on the frontend.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		// Get download list based on source type
		$download_list = [];
		if ( $settings['source_type'] === 'dynamic' ) {
			$download_list = $this->get_dynamic_download_list( $settings );
		} else {
			$download_list = $settings['download_list'];
		}

		if ( empty( $download_list ) ) {
			return;
		}

		$theme_class = $settings['theme_version'] ? 'mn-theme-dark' : 'mn-theme-light';
		$layout_class = 'mn-layout-' . $settings['layout_type'];

		$this->add_render_attribute( 'wrapper', 'class', [
			'mn-download-wrapper',
			$theme_class,
			$layout_class
		] );

		$container_class = $settings['layout_type'] === 'grid' ? 'mn-download-grid' : 'mn-download-list';
		$this->add_render_attribute( 'container', 'class', [
			'mn-download-container',
			$container_class
		] );

		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<div <?php $this->print_render_attribute_string( 'container' ); ?>>
				<?php
				foreach ( $download_list as $index => $download ) :
					$this->render_download_item( $download, $settings, $index );
				endforeach;
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render single download item
	 */
	private function render_download_item( $download, $settings, $index ) {
		$download_url = '';
		if ( isset( $download['download_url']['url'] ) ) {
			$download_url = $download['download_url']['url'];
		}

		if ( empty( $download_url ) ) {
			return;
		}

		// Get file type icon
		$file_type = isset( $download['file_type'] ) ? $download['file_type'] : 'other';
		$file_icon = $this->get_file_type_icon( $file_type );

		?>
		<div class="mn-download-item">
			<div class="mn-download-item-content">
				<div class="mn-download-item-header">
					<?php if ( $settings['show_file_type'] && ! empty( $file_type ) ) : ?>
						<div class="mn-download-item-icon">
							<i class="<?php echo esc_attr( $file_icon ); ?>" aria-hidden="true"></i>
						</div>
					<?php endif; ?>
					
					<?php if ( $settings['show_title'] && ! empty( $download['file_title'] ) ) : ?>
						<h4 class="mn-download-item-title">
							<?php echo esc_html( $download['file_title'] ); ?>
						</h4>
					<?php endif; ?>
				</div>
				
				<?php if ( $settings['show_description'] && ! empty( $download['file_description'] ) ) : ?>
					<div class="mn-download-item-description">
						<?php echo wp_kses_post( $download['file_description'] ); ?>
					</div>
				<?php endif; ?>
				
				<div class="mn-download-item-footer">
					<?php if ( $settings['show_file_type'] && ! empty( $file_type ) ) : ?>
						<div class="mn-download-item-meta">
							<span class="mn-download-item-type">
								<?php echo esc_html( strtoupper( $file_type ) ); ?>
							</span>
						</div>
					<?php endif; ?>
					
					<a href="<?php echo esc_url( $download_url ); ?>" 
					   class="mn-download-button" 
					   download>
						<i class="eicon-download" aria-hidden="true"></i>
						<?php esc_html_e( 'Download', 'mn-elements' ); ?>
					</a>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Get file type icon
	 */
	private function get_file_type_icon( $file_type ) {
		$icons = [
			'pdf' => 'eicon-document-file',
			'doc' => 'eicon-document-file',
			'docx' => 'eicon-document-file',
			'xls' => 'eicon-table',
			'xlsx' => 'eicon-table',
			'ppt' => 'eicon-slides',
			'pptx' => 'eicon-slides',
			'zip' => 'eicon-archive-title',
			'rar' => 'eicon-archive-title',
			'jpg' => 'eicon-image',
			'png' => 'eicon-image',
			'mp3' => 'eicon-headphones',
			'mp4' => 'eicon-video-camera',
			'other' => 'eicon-document-file',
		];

		return isset( $icons[ $file_type ] ) ? $icons[ $file_type ] : $icons['other'];
	}
}
