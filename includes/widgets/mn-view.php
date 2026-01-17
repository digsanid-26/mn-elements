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
 * MN View Widget
 *
 * File viewer widget with popup/modal support for multiple file types
 *
 * @since 1.4.1
 */
class MN_View extends Widget_Base {

	/**
	 * Get widget name.
	 */
	public function get_name() {
		return 'mn-view';
	}

	/**
	 * Get widget title.
	 */
	public function get_title() {
		return esc_html__( 'MN View', 'mn-elements' );
	}

	/**
	 * Get widget icon.
	 */
	public function get_icon() {
		return 'eicon-document-file';
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
		return [ 'view', 'file', 'viewer', 'popup', 'modal', 'pdf', 'image', 'video', 'mn' ];
	}

	/**
	 * Get style dependencies.
	 */
	public function get_style_depends() {
		return [ 'mn-view' ];
	}

	/**
	 * Get script dependencies.
	 */
	public function get_script_depends() {
		return [ 'mn-view' ];
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
		// File Management Section
		$this->start_controls_section(
			'section_file_management',
			[
				'label' => esc_html__( 'File Management', 'mn-elements' ),
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
				'default' => esc_html__( 'View File', 'mn-elements' ),
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
				'default' => esc_html__( 'Click to view this file.', 'mn-elements' ),
				'placeholder' => esc_html__( 'Enter file description', 'mn-elements' ),
				'rows' => 3,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'file_url',
			[
				'label' => esc_html__( 'File URL', 'mn-elements' ),
				'type' => Controls_Manager::MEDIA,
				'media_types' => [ 'image', 'video', 'application' ],
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
				'default' => 'auto',
				'options' => [
					'auto' => esc_html__( 'Auto Detect', 'mn-elements' ),
					'pdf' => esc_html__( 'PDF', 'mn-elements' ),
					'image' => esc_html__( 'Image (JPG, PNG)', 'mn-elements' ),
					'video' => esc_html__( 'Video (MP4)', 'mn-elements' ),
				],
			]
		);

		$this->add_control(
			'files_list',
			[
				'label' => esc_html__( 'Files', 'mn-elements' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'file_title' => esc_html__( 'Sample PDF Document', 'mn-elements' ),
						'file_description' => esc_html__( 'Click to view this PDF document in popup.', 'mn-elements' ),
					],
				],
				'title_field' => '{{{ file_title }}}',
				'condition' => [
					'source_type' => 'manual',
				],
			]
		);

		// Dynamic Source Controls
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

		// Custom Field Mapping
		$this->add_control(
			'title_field_source',
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
			'title_field_name',
			[
				'label' => esc_html__( 'Title Field Name', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter custom field name', 'mn-elements' ),
				'condition' => [
					'source_type' => 'dynamic',
					'title_field_source' => 'custom_field',
				],
			]
		);

		$this->add_control(
			'file_url_field',
			[
				'label' => esc_html__( 'File URL Field', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter custom field name for file URL', 'mn-elements' ),
				'description' => esc_html__( 'Custom field that contains the file URL', 'mn-elements' ),
				'condition' => [
					'source_type' => 'dynamic',
				],
			]
		);

		$this->add_control(
			'description_field_source',
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
			'description_field_name',
			[
				'label' => esc_html__( 'Description Field Name', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter custom field name', 'mn-elements' ),
				'condition' => [
					'source_type' => 'dynamic',
					'description_field_source' => 'custom_field',
				],
			]
		);

		$this->add_control(
			'file_type_field',
			[
				'label' => esc_html__( 'File Type Field', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter custom field name for file type (optional)', 'mn-elements' ),
				'description' => esc_html__( 'Custom field that contains the file type (pdf, image, video)', 'mn-elements' ),
				'condition' => [
					'source_type' => 'dynamic',
				],
			]
		);

		// Taxonomy Filtering
		$this->add_control(
			'taxonomy_filter',
			[
				'label' => esc_html__( 'Filter by Taxonomy', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => array_merge( [ '' => esc_html__( 'No Filter', 'mn-elements' ) ], $this->get_taxonomies() ),
				'condition' => [
					'source_type' => 'dynamic',
				],
			]
		);

		$this->add_control(
			'taxonomy_terms',
			[
				'label' => esc_html__( 'Term IDs', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter term IDs separated by comma (e.g., 1,2,3)', 'mn-elements' ),
				'description' => esc_html__( 'Leave empty to include all terms from selected taxonomy', 'mn-elements' ),
				'condition' => [
					'source_type' => 'dynamic',
					'taxonomy_filter!' => '',
				],
			]
		);

		$this->end_controls_section();

		// Popup Settings Section
		$this->start_controls_section(
			'section_popup_settings',
			[
				'label' => esc_html__( 'Popup Settings', 'mn-elements' ),
			]
		);

		$this->add_responsive_control(
			'popup_width',
			[
				'label' => esc_html__( 'Popup Width', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vw' ],
				'range' => [
					'px' => [
						'min' => 300,
						'max' => 1920,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
					'vw' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 90,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-view-popup-content' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'popup_height',
			[
				'label' => esc_html__( 'Popup Height', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vh' ],
				'range' => [
					'px' => [
						'min' => 200,
						'max' => 1080,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
					'vh' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'vh',
					'size' => 80,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-view-popup-content' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'enable_zoom',
			[
				'label' => esc_html__( 'Enable Zoom', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'mn-elements' ),
				'label_off' => esc_html__( 'No', 'mn-elements' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'zoom_controls',
			[
				'label' => esc_html__( 'Zoom Controls', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'buttons',
				'options' => [
					'buttons' => esc_html__( 'Zoom Buttons', 'mn-elements' ),
					'wheel' => esc_html__( 'Mouse Wheel', 'mn-elements' ),
					'both' => esc_html__( 'Both', 'mn-elements' ),
				],
				'condition' => [
					'enable_zoom' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		// Button Settings Section
		$this->start_controls_section(
			'section_button_settings',
			[
				'label' => esc_html__( 'Button Settings', 'mn-elements' ),
			]
		);

		$this->add_control(
			'button_text',
			[
				'label' => esc_html__( 'Button Text', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'View File', 'mn-elements' ),
				'placeholder' => esc_html__( 'Enter button text', 'mn-elements' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'button_icon',
			[
				'label' => esc_html__( 'Button Icon', 'mn-elements' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-eye',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'icon_position',
			[
				'label' => esc_html__( 'Icon Position', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'before',
				'options' => [
					'before' => esc_html__( 'Before', 'mn-elements' ),
					'after' => esc_html__( 'After', 'mn-elements' ),
				],
				'condition' => [
					'button_icon[value]!' => '',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register style controls.
	 */
	protected function register_style_controls() {
		// File Item Style
		$this->start_controls_section(
			'section_file_item_style',
			[
				'label' => esc_html__( 'File Item', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'file_item_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .mn-view-file-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'file_item_margin',
			[
				'label' => esc_html__( 'Margin', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .mn-view-file-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'file_item_background',
				'label' => esc_html__( 'Background', 'mn-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .mn-view-file-item',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'file_item_border',
				'label' => esc_html__( 'Border', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-view-file-item',
			]
		);

		$this->add_responsive_control(
			'file_item_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-view-file-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'file_item_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-view-file-item',
			]
		);

		$this->end_controls_section();

		// Button Style
		$this->start_controls_section(
			'section_button_style',
			[
				'label' => esc_html__( 'Button', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'label' => esc_html__( 'Typography', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-view-button',
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'mn-elements' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-view-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_background',
				'label' => esc_html__( 'Background', 'mn-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .mn-view-button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'mn-elements' ),
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-view-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_background_hover',
				'label' => esc_html__( 'Background', 'mn-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .mn-view-button:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'label' => esc_html__( 'Border', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-view-button',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-view-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .mn-view-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Popup Style
		$this->start_controls_section(
			'section_popup_style',
			[
				'label' => esc_html__( 'Popup', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'popup_overlay_color',
			[
				'label' => esc_html__( 'Overlay Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0,0.8)',
				'selectors' => [
					'{{WRAPPER}} .mn-view-popup-overlay' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'popup_content_background',
				'label' => esc_html__( 'Content Background', 'mn-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .mn-view-popup-content',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'popup_content_border',
				'label' => esc_html__( 'Content Border', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-view-popup-content',
			]
		);

		$this->add_responsive_control(
			'popup_content_border_radius',
			[
				'label' => esc_html__( 'Content Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-view-popup-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'popup_content_box_shadow',
				'label' => esc_html__( 'Content Box Shadow', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-view-popup-content',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output on the frontend.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$widget_id = $this->get_id();

		// Get files list based on source type
		$files_list = [];
		if ( 'manual' === $settings['source_type'] && ! empty( $settings['files_list'] ) ) {
			$files_list = $settings['files_list'];
		} elseif ( 'dynamic' === $settings['source_type'] ) {
			$files_list = $this->get_dynamic_file_list( $settings );
		}

		?>
		<div class="mn-view-widget" data-widget-id="<?php echo esc_attr( $widget_id ); ?>">
			<?php if ( ! empty( $files_list ) ) : ?>
				<div class="mn-view-files-list">
					<?php foreach ( $files_list as $index => $item ) : ?>
						<?php
						$file_url = '';
						if ( ! empty( $item['file_url']['url'] ) ) {
							$file_url = $item['file_url']['url'];
						} elseif ( ! empty( $item['file_url'] ) && is_string( $item['file_url'] ) ) {
							// Handle dynamic source where file_url might be a string
							$file_url = $item['file_url'];
						}

						if ( empty( $file_url ) ) {
							continue;
						}

						$file_type = $this->detect_file_type( $file_url, $item['file_type'] );
						$item_key = 'file_' . $index;
						?>
						<div class="mn-view-file-item" data-file-type="<?php echo esc_attr( $file_type ); ?>">
							<div class="mn-view-file-info">
								<?php if ( ! empty( $item['file_title'] ) ) : ?>
									<h3 class="mn-view-file-title"><?php echo esc_html( $item['file_title'] ); ?></h3>
								<?php endif; ?>
								
								<?php if ( ! empty( $item['file_description'] ) ) : ?>
									<p class="mn-view-file-description"><?php echo esc_html( $item['file_description'] ); ?></p>
								<?php endif; ?>
							</div>

							<button class="mn-view-button" 
								data-file-url="<?php echo esc_url( $file_url ); ?>"
								data-file-type="<?php echo esc_attr( $file_type ); ?>"
								data-file-title="<?php echo esc_attr( $item['file_title'] ); ?>">
								
								<?php if ( 'before' === $settings['icon_position'] && ! empty( $settings['button_icon']['value'] ) ) : ?>
									<span class="mn-view-button-icon mn-view-icon-before">
										<?php Icons_Manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true' ] ); ?>
									</span>
								<?php endif; ?>

								<span class="mn-view-button-text"><?php echo esc_html( $settings['button_text'] ); ?></span>

								<?php if ( 'after' === $settings['icon_position'] && ! empty( $settings['button_icon']['value'] ) ) : ?>
									<span class="mn-view-button-icon mn-view-icon-after">
										<?php Icons_Manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true' ] ); ?>
									</span>
								<?php endif; ?>
							</button>
						</div>
					<?php endforeach; ?>
				</div>
			<?php else : ?>
				<div class="mn-view-no-files">
					<p><?php echo esc_html__( 'No files found.', 'mn-elements' ); ?></p>
				</div>
			<?php endif; ?>

			<!-- Popup Modal -->
			<div class="mn-view-popup" style="display: none;">
				<div class="mn-view-popup-overlay"></div>
				<div class="mn-view-popup-content">
					<div class="mn-view-popup-header">
						<h3 class="mn-view-popup-title"></h3>
						<div class="mn-view-popup-controls">
							<?php if ( 'yes' === $settings['enable_zoom'] && in_array( $settings['zoom_controls'], [ 'buttons', 'both' ] ) ) : ?>
								<button class="mn-view-zoom-out" title="<?php echo esc_attr__( 'Zoom Out', 'mn-elements' ); ?>">
									<i class="fas fa-search-minus"></i>
								</button>
								<span class="mn-view-zoom-level">100%</span>
								<button class="mn-view-zoom-in" title="<?php echo esc_attr__( 'Zoom In', 'mn-elements' ); ?>">
									<i class="fas fa-search-plus"></i>
								</button>
							<?php endif; ?>
							<button class="mn-view-popup-close" title="<?php echo esc_attr__( 'Close', 'mn-elements' ); ?>">
								<i class="fas fa-times"></i>
							</button>
						</div>
					</div>
					<div class="mn-view-popup-body">
						<div class="mn-view-file-container"></div>
					</div>
				</div>
			</div>
		</div>
		<?php
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
	 * Get available taxonomies
	 */
	private function get_taxonomies() {
		$taxonomies = get_taxonomies( [ 'public' => true ], 'objects' );
		$options = [];

		foreach ( $taxonomies as $taxonomy ) {
			$options[ $taxonomy->name ] = $taxonomy->label;
		}

		return $options;
	}

	/**
	 * Get dynamic file list from WordPress posts
	 */
	private function get_dynamic_file_list( $settings ) {
		$files = [];

		$args = [
			'post_type' => $settings['post_type'],
			'posts_per_page' => $settings['posts_per_page'],
			'orderby' => $settings['orderby'],
			'order' => $settings['order'],
			'post_status' => 'publish',
		];

		// Add taxonomy filter if specified
		if ( ! empty( $settings['taxonomy_filter'] ) ) {
			$tax_query = [
				'taxonomy' => $settings['taxonomy_filter'],
				'field' => 'term_id',
				'operator' => 'IN',
			];

			// Add specific terms if provided
			if ( ! empty( $settings['taxonomy_terms'] ) ) {
				$term_ids = array_map( 'trim', explode( ',', $settings['taxonomy_terms'] ) );
				$term_ids = array_filter( array_map( 'intval', $term_ids ) );
				
				if ( ! empty( $term_ids ) ) {
					$tax_query['terms'] = $term_ids;
					$args['tax_query'] = [ $tax_query ];
				}
			} else {
				// Get all terms from the taxonomy
				$terms = get_terms( [
					'taxonomy' => $settings['taxonomy_filter'],
					'hide_empty' => true,
				] );
				
				if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
					$term_ids = wp_list_pluck( $terms, 'term_id' );
					$tax_query['terms'] = $term_ids;
					$args['tax_query'] = [ $tax_query ];
				}
			}
		}

		$query = new \WP_Query( $args );

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$post_id = get_the_ID();

				// Get title
				$title = '';
				if ( 'post_title' === $settings['title_field_source'] ) {
					$title = get_the_title();
				} elseif ( 'custom_field' === $settings['title_field_source'] && ! empty( $settings['title_field_name'] ) ) {
					$title = $this->get_custom_field_value( $post_id, $settings['title_field_name'] );
				}

				// Get file URL - required field
				$file_url = '';
				if ( ! empty( $settings['file_url_field'] ) ) {
					$file_url = $this->get_custom_field_value( $post_id, $settings['file_url_field'] );
				}

				// Skip if no file URL
				if ( empty( $file_url ) || empty( $title ) ) {
					continue;
				}

				// Get description
				$description = '';
				if ( 'post_excerpt' === $settings['description_field_source'] ) {
					$description = get_the_excerpt();
				} elseif ( 'post_content' === $settings['description_field_source'] ) {
					$description = get_the_content();
				} elseif ( 'custom_field' === $settings['description_field_source'] && ! empty( $settings['description_field_name'] ) ) {
					$description = $this->get_custom_field_value( $post_id, $settings['description_field_name'] );
				}

				// Get file type
				$file_type = 'auto';
				if ( ! empty( $settings['file_type_field'] ) ) {
					$custom_type = $this->get_custom_field_value( $post_id, $settings['file_type_field'] );
					if ( ! empty( $custom_type ) ) {
						$file_type = $custom_type;
					}
				}

				$files[] = [
					'file_title' => $title,
					'file_description' => $description,
					'file_url' => $file_url,
					'file_type' => $file_type,
				];
			}
			wp_reset_postdata();
		}

		return $files;
	}

	/**
	 * Get custom field value with fallback support
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
	 * Detect file type from URL or manual selection
	 */
	private function detect_file_type( $file_url, $manual_type = 'auto' ) {
		if ( 'auto' !== $manual_type ) {
			return $manual_type;
		}

		$extension = strtolower( pathinfo( $file_url, PATHINFO_EXTENSION ) );

		switch ( $extension ) {
			case 'pdf':
				return 'pdf';
			case 'jpg':
			case 'jpeg':
			case 'png':
			case 'gif':
			case 'webp':
				return 'image';
			case 'mp4':
			case 'webm':
			case 'ogg':
				return 'video';
			default:
				return 'pdf'; // Default fallback
		}
	}
}
