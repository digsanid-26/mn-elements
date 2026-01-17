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
 * MN Infolist Widget
 *
 * Manual information list widget with MN Posts styling and features
 *
 * @since 1.0.4
 */
class MN_Infolist extends Widget_Base {

	/**
	 * Get widget name.
	 */
	public function get_name() {
		return 'mn-infolist';
	}

	/**
	 * Get widget title.
	 */
	public function get_title() {
		return esc_html__( 'MN Infolist', 'mn-elements' );
	}

	/**
	 * Get widget icon.
	 */
	public function get_icon() {
		return 'eicon-bullet-list';
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
		return [ 'info', 'list', 'manual', 'content', 'mn', 'dark', 'light', 'theme' ];
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
		// List Management Section (replaces Query)
		$this->start_controls_section(
			'section_list',
			[
				'label' => esc_html__( 'List Management', 'mn-elements' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Info Item Title', 'mn-elements' ),
				'placeholder' => esc_html__( 'Enter title', 'mn-elements' ),
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'media_type',
			[
				'label' => esc_html__( 'Media Type', 'mn-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'image' => [
						'title' => esc_html__( 'Image', 'mn-elements' ),
						'icon' => 'eicon-image',
					],
					'icon' => [
						'title' => esc_html__( 'Icon', 'mn-elements' ),
						'icon' => 'eicon-star',
					],
				],
				'default' => 'image',
				'toggle' => false,
			]
		);

		$repeater->add_control(
			'image',
			[
				'label' => esc_html__( 'Image', 'mn-elements' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'media_type' => 'image',
				],
			]
		);

		$repeater->add_control(
			'icon',
			[
				'label' => esc_html__( 'Icon', 'mn-elements' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'fa-solid',
				],
				'condition' => [
					'media_type' => 'icon',
				],
			]
		);

		$repeater->add_control(
			'description',
			[
				'label' => esc_html__( 'Description', 'mn-elements' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Enter your description here. This is a sample text for the info item description.', 'mn-elements' ),
				'placeholder' => esc_html__( 'Enter description', 'mn-elements' ),
				'rows' => 4,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'readmore_label',
			[
				'label' => esc_html__( 'Read More Label', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Read More', 'mn-elements' ),
				'placeholder' => esc_html__( 'Enter read more text', 'mn-elements' ),
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' => esc_html__( 'Link', 'mn-elements' ),
				'type' => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'mn-elements' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'readmore_icon',
			[
				'label' => esc_html__( 'Read More Icon', 'mn-elements' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-arrow-right',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'info_list',
			[
				'label' => esc_html__( 'Info Items', 'mn-elements' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'title' => esc_html__( 'First Info Item', 'mn-elements' ),
						'description' => esc_html__( 'This is the description for the first info item. You can add any content here.', 'mn-elements' ),
						'readmore_label' => esc_html__( 'Learn More', 'mn-elements' ),
						'media_type' => 'icon',
						'icon' => [
							'value' => 'fas fa-star',
							'library' => 'fa-solid',
						],
					],
					[
						'title' => esc_html__( 'Second Info Item', 'mn-elements' ),
						'description' => esc_html__( 'This is the description for the second info item. You can customize all content.', 'mn-elements' ),
						'readmore_label' => esc_html__( 'Read More', 'mn-elements' ),
						'media_type' => 'icon',
						'icon' => [
							'value' => 'fas fa-heart',
							'library' => 'fa-solid',
						],
					],
					[
						'title' => esc_html__( 'Third Info Item', 'mn-elements' ),
						'description' => esc_html__( 'This is the description for the third info item. Add your own content here.', 'mn-elements' ),
						'readmore_label' => esc_html__( 'View Details', 'mn-elements' ),
						'media_type' => 'icon',
						'icon' => [
							'value' => 'fas fa-thumbs-up',
							'library' => 'fa-solid',
						],
					],
				],
				'title_field' => '{{{ title }}}',
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
			'section_title',
			[
				'label' => esc_html__( 'Section Title', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => esc_html__( 'Info List', 'mn-elements' ),
				'description' => esc_html__( 'Enter a custom title to display above the info list', 'mn-elements' ),
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
					'{{WRAPPER}} .mn-infolist-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				],
			]
		);

		$this->add_control(
			'center_last_odd',
			[
				'label' => esc_html__( 'Center Last Odd Item', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'description' => esc_html__( 'When enabled, if the total items is odd, the last item will be centered in the grid.', 'mn-elements' ),
				'prefix_class' => 'mn-infolist-center-odd-',
			]
		);

		$this->add_control(
			'show_image',
			[
				'label' => esc_html__( 'Show Image', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'icon_position',
			[
				'label' => esc_html__( 'Icon Position', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'top-center',
				'options' => [
					'top-center' => esc_html__( 'Top Center', 'mn-elements' ),
					'left-top' => esc_html__( 'Left Top', 'mn-elements' ),
					'right-top' => esc_html__( 'Right Top', 'mn-elements' ),
				],
				'condition' => [
					'show_image' => 'yes',
				],
				'prefix_class' => 'mn-icon-position-',
			]
		);

		$this->add_control(
			'preserve_icon_position_mobile',
			[
				'label' => esc_html__( 'Preserve Icon Position on Mobile', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'description' => esc_html__( 'When enabled, the icon position (Left/Right) will be preserved on mobile devices instead of switching to Top Center.', 'mn-elements' ),
				'condition' => [
					'show_image' => 'yes',
					'icon_position!' => 'top-center',
				],
				'prefix_class' => 'mn-preserve-icon-mobile-',
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
			'show_description',
			[
				'label' => esc_html__( 'Show Description', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_general_icon_numbering',
			[
				'label' => esc_html__( 'Show General Icon / Numbering', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
			]
		);

		$this->add_control(
			'general_icon_numbering_type',
			[
				'label' => esc_html__( 'Type', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => [
					'icon' => esc_html__( 'General Icon', 'mn-elements' ),
					'numbering' => esc_html__( 'Numbering', 'mn-elements' ),
					'alpha' => esc_html__( 'Alpha (1st Letter)', 'mn-elements' ),
				],
				'condition' => [
					'show_general_icon_numbering' => 'yes',
				],
				'description' => esc_html__( 'Alpha extracts first letter from text within parentheses in title, e.g., "Title (A)" shows "A"', 'mn-elements' ),
			]
		);

		$this->add_control(
			'general_icon',
			[
				'label' => esc_html__( 'General Icon', 'mn-elements' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-check',
					'library' => 'fa-solid',
				],
				'condition' => [
					'show_general_icon_numbering' => 'yes',
					'general_icon_numbering_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'general_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
					'em' => [
						'min' => 0.5,
						'max' => 5,
					],
					'rem' => [
						'min' => 0.5,
						'max' => 5,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-infolist-general-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_general_icon_numbering' => 'yes',
					'general_icon_numbering_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'general_icon_frame_size',
			[
				'label' => esc_html__( 'Frame Size', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 150,
					],
					'em' => [
						'min' => 1,
						'max' => 8,
					],
					'rem' => [
						'min' => 1,
						'max' => 8,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-infolist-icon-numbering' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_general_icon_numbering' => 'yes',
				],
			]
		);

		$this->add_control(
			'general_icon_frame_background',
			[
				'label' => esc_html__( 'Frame Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-infolist-icon-numbering' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'show_general_icon_numbering' => 'yes',
				],
			]
		);

		$this->add_control(
			'icon_numbering_alignment',
			[
				'label' => esc_html__( 'Alignment', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => esc_html__( 'Left', 'mn-elements' ),
					'right' => esc_html__( 'Right', 'mn-elements' ),
				],
				'condition' => [
					'show_general_icon_numbering' => 'yes',
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
			'enable_theme',
			[
				'label' => esc_html__( 'Enable Theme Version', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'mn-elements' ),
				'label_off' => esc_html__( 'No', 'mn-elements' ),
				'default' => '',
				'description' => esc_html__( 'Enable predefined theme styling', 'mn-elements' ),
			]
		);

		$this->add_control(
			'theme_version',
			[
				'label' => esc_html__( 'Select Theme', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'light',
				'options' => [
					'light' => esc_html__( 'Light', 'mn-elements' ),
					'dark' => esc_html__( 'Dark', 'mn-elements' ),
				],
				'condition' => [
					'enable_theme' => 'yes',
				],
				'description' => esc_html__( 'Choose between light and dark theme versions', 'mn-elements' ),
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
					'{{WRAPPER}} .mn-infolist-grid' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Info Item Style
		$this->start_controls_section(
			'section_item_style',
			[
				'label' => esc_html__( 'Info Item', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'item_border',
				'selector' => '{{WRAPPER}} .mn-info-item',
			]
		);

		$this->add_control(
			'item_border_hover_color',
			[
				'label' => esc_html__( 'Border Hover Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-info-item:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'item_border_border!' => '',
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
					'{{WRAPPER}} .mn-info-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_box_shadow',
				'selector' => '{{WRAPPER}} .mn-info-item',
			]
		);

		$this->add_responsive_control(
			'item_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-info-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'item_margin',
			[
				'label' => esc_html__( 'Margin', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-info-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'item_background_color',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-info-item' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'item_hover_background_color',
			[
				'label' => esc_html__( 'Hover Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-info-item:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_width',
			[
				'label' => esc_html__( 'Image Width', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vw' ],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 1000,
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
				'selectors' => [
					'{{WRAPPER}} .mn-info-image' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-info-image img' => 'width: 100%;',
				],
				'condition' => [
					'show_image' => 'yes',
				],
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
					'{{WRAPPER}} .mn-info-image' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-info-image img' => 'height: 100%;',
				],
				'condition' => [
					'show_image' => 'yes',
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
					'scale-down' => esc_html__( 'Scale Down', 'mn-elements' ),
					'none' => esc_html__( 'None', 'mn-elements' ),
				],
				'selectors' => [
					'{{WRAPPER}} .mn-info-image img' => 'object-fit: {{VALUE}};',
				],
				'condition' => [
					'show_image' => 'yes',
				],
			]
		);

		$this->add_control(
			'image_object_position',
			[
				'label' => esc_html__( 'Object Position', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'center center',
				'options' => [
					'center center' => esc_html__( 'Center Center', 'mn-elements' ),
					'center top' => esc_html__( 'Center Top', 'mn-elements' ),
					'center bottom' => esc_html__( 'Center Bottom', 'mn-elements' ),
					'left top' => esc_html__( 'Left Top', 'mn-elements' ),
					'left center' => esc_html__( 'Left Center', 'mn-elements' ),
					'left bottom' => esc_html__( 'Left Bottom', 'mn-elements' ),
					'right top' => esc_html__( 'Right Top', 'mn-elements' ),
					'right center' => esc_html__( 'Right Center', 'mn-elements' ),
					'right bottom' => esc_html__( 'Right Bottom', 'mn-elements' ),
				],
				'selectors' => [
					'{{WRAPPER}} .mn-info-image img' => 'object-position: {{VALUE}};',
				],
				'condition' => [
					'show_image' => 'yes',
					'image_object_fit!' => 'fill',
				],
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label' => esc_html__( 'Image Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-info-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .mn-info-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'show_image' => 'yes',
				],
			]
		);

		$this->add_control(
			'image_aspect_ratio',
			[
				'label' => esc_html__( 'Aspect Ratio', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => esc_html__( 'Default', 'mn-elements' ),
					'1/1' => '1:1 (Square)',
					'4/3' => '4:3 (Landscape)',
					'3/4' => '3:4 (Portrait)',
					'16/9' => '16:9 (Wide)',
					'9/16' => '9:16 (Tall)',
					'21/9' => '21:9 (Ultrawide)',
					'3/2' => '3:2',
					'2/3' => '2:3',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-info-image' => 'aspect-ratio: {{VALUE}};',
				],
				'condition' => [
					'show_image' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		// Icon Style
		$this->start_controls_section(
			'section_icon_style',
			[
				'label' => esc_html__( 'Icon', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		// Icon Color Tabs
		$this->start_controls_tabs( 'icon_colors' );

		$this->start_controls_tab(
			'icon_colors_normal',
			[
				'label' => esc_html__( 'Normal', 'mn-elements' ),
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#007cba',
				'selectors' => [
					'{{WRAPPER}} .mn-info-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-info-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-info-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_colors_hover',
			[
				'label' => esc_html__( 'Hover', 'mn-elements' ),
			]
		);

		$this->add_control(
			'icon_hover_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-info-item:hover .mn-info-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-info-item:hover .mn-info-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-info-item:hover .mn-info-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 's', 'ms' ],
				'default' => [
					'unit' => 's',
					'size' => 0.3,
				],
				'range' => [
					's' => [
						'min' => 0,
						'max' => 3,
						'step' => 0.1,
					],
					'ms' => [
						'min' => 0,
						'max' => 3000,
						'step' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mn-info-icon i' => 'transition: color {{SIZE}}{{UNIT}}, transform {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-info-icon svg' => 'transition: fill {{SIZE}}{{UNIT}}, transform {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-info-icon' => 'transition: color {{SIZE}}{{UNIT}}, background-color {{SIZE}}{{UNIT}}, border-color {{SIZE}}{{UNIT}}, transform {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Size', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw' ],
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 200,
						'step' => 1,
					],
					'%' => [
						'min' => 6,
						'max' => 100,
					],
					'em' => [
						'min' => 0.5,
						'max' => 10,
						'step' => 0.1,
					],
					'vw' => [
						'min' => 6,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 48,
				],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .mn-info-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-info-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_gap',
			[
				'label' => esc_html__( 'Gap', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'%' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors' => [
					// Top Center - margin bottom for gap between icon and content
					'{{WRAPPER}}.mn-icon-position-top-center .mn-info-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					// Left Top - margin right for gap between icon and content
					'{{WRAPPER}}.mn-icon-position-left-top .mn-info-icon' => 'margin-right: {{SIZE}}{{UNIT}}; margin-bottom: 0;',
					// Right Top - margin left for gap between icon and content
					'{{WRAPPER}}.mn-icon-position-right-top .mn-info-icon' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: 0; margin-bottom: 0;',
				],
			]
		);

		$this->add_responsive_control(
			'icon_self_align',
			[
				'label' => esc_html__( 'Horizontal Alignment', 'mn-elements' ),
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
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .mn-info-icon' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_self_vertical_align',
			[
				'label' => esc_html__( 'Vertical Alignment', 'mn-elements' ),
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
				],
				'default' => 'flex-start',
				'selectors' => [
					'{{WRAPPER}} .mn-info-icon' => 'align-self: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_vertical_offset',
			[
				'label' => esc_html__( 'Adjust Vertical Position', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => -50,
						'max' => 50,
					],
					'em' => [
						'min' => -5,
						'max' => 5,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mn-info-icon' => 'transform: translateY({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->add_control(
			'icon_view',
			[
				'label' => esc_html__( 'View', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'mn-elements' ),
					'framed' => esc_html__( 'Framed', 'mn-elements' ),
					'stacked' => esc_html__( 'Stacked', 'mn-elements' ),
				],
				'prefix_class' => 'mn-icon-view-',
			]
		);

		$this->add_control(
			'icon_shape',
			[
				'label' => esc_html__( 'Shape', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'circle',
				'options' => [
					'circle' => esc_html__( 'Circle', 'mn-elements' ),
					'square' => esc_html__( 'Square', 'mn-elements' ),
					'rounded' => esc_html__( 'Rounded', 'mn-elements' ),
				],
				'condition' => [
					'icon_view!' => 'default',
				],
				'prefix_class' => 'mn-icon-shape-',
			]
		);

		$this->add_control(
			'icon_background_color',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-info-icon' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'icon_view' => 'stacked',
				],
			]
		);

		$this->add_control(
			'icon_hover_background_color',
			[
				'label' => esc_html__( 'Hover Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-info-item:hover .mn-info-icon' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'icon_view!' => 'default',
				],
			]
		);

		$this->add_control(
			'icon_border_color_framed',
			[
				'label' => esc_html__( 'Border Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#007cba',
				'selectors' => [
					'{{WRAPPER}} .mn-info-icon' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'icon_view' => 'framed',
				],
			]
		);

		$this->add_control(
			'icon_hover_border_color_framed',
			[
				'label' => esc_html__( 'Hover Border Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-info-item:hover .mn-info-icon' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'icon_view' => 'framed',
				],
			]
		);

		$this->add_responsive_control(
			'icon_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-info-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_margin',
			[
				'label' => esc_html__( 'Margin', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-info-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-info-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'icon_box_shadow',
				'selector' => '{{WRAPPER}} .mn-info-icon',
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
				'selector' => '{{WRAPPER}} .mn-infolist-section-title',
			]
		);

		$this->add_control(
			'section_title_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-infolist-section-title' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .mn-infolist-section-title' => 'text-align: {{VALUE}};',
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
					'{{WRAPPER}} .mn-infolist-section-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .mn-info-title',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'label' => esc_html__( 'Description Typography', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-info-description',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'readmore_typography',
				'label' => esc_html__( 'Read More Typography', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-readmore-button',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'numbering_typography',
				'label' => esc_html__( 'Numbering Typography', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-infolist-numbering',
				'condition' => [
					'show_general_icon_numbering' => 'yes',
					'general_icon_numbering_type' => 'numbering',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'alpha_typography',
				'label' => esc_html__( 'Alpha Typography', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-infolist-alpha',
				'condition' => [
					'show_general_icon_numbering' => 'yes',
					'general_icon_numbering_type' => 'alpha',
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
					'{{WRAPPER}} .mn-info-title' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'description_align',
			[
				'label' => esc_html__( 'Description Alignment', 'mn-elements' ),
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
					'{{WRAPPER}} .mn-info-description' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'show_description' => 'yes',
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
					'{{WRAPPER}} .mn-info-readmore' => 'text-align: {{VALUE}};',
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
					'{{WRAPPER}} .mn-info-title' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .mn-info-item:hover .mn-info-title' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .mn-info-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'description_hover_color',
			[
				'label' => esc_html__( 'Description Hover Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-info-item:hover .mn-info-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'readmore_bg_color',
			[
				'label' => esc_html__( 'Read More Background', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-readmore-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'readmore_text_color',
			[
				'label' => esc_html__( 'Read More Text Color', 'mn-elements' ),
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
				'label' => esc_html__( 'Read More Icon Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-readmore-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-readmore-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'general_icon_numbering_color',
			[
				'label' => esc_html__( 'Icon / Numbering / Alpha Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-infolist-general-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-infolist-general-icon svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .mn-infolist-numbering' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-infolist-alpha' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_general_icon_numbering' => 'yes',
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
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'readmore_button_typography',
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

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'readmore_button_border',
				'selector' => '{{WRAPPER}} .mn-readmore-button',
			]
		);

		$this->add_control(
			'readmore_button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-readmore-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'readmore_button_padding',
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
	}

	/**
	 * Render widget output on the frontend.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['info_list'] ) ) {
			return;
		}

		// Theme class
		$theme_class = '';
		if ( isset( $settings['enable_theme'] ) && $settings['enable_theme'] === 'yes' ) {
			$theme_class = 'mn-theme-' . $settings['theme_version'];
		}

		$wrapper_classes = [ 'mn-infolist-wrapper' ];
		if ( ! empty( $theme_class ) ) {
			$wrapper_classes[] = $theme_class;
		}

		$this->add_render_attribute( 'wrapper', 'class', $wrapper_classes );

		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<?php if ( ! empty( $settings['section_title'] ) ) : ?>
				<<?php echo esc_attr( $settings['title_tag'] ); ?> class="mn-infolist-section-title">
					<?php echo esc_html( $settings['section_title'] ); ?>
				</<?php echo esc_attr( $settings['title_tag'] ); ?>>
			<?php endif; ?>
			
			<div class="mn-infolist-grid">
				<?php
				foreach ( $settings['info_list'] as $index => $item ) :
					$this->render_info_item( $item, $settings, $index );
				endforeach;
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render single info item
	 */
	private function render_info_item( $item, $settings, $index ) {
		$filter_class = ( $settings['image_filter_effect'] !== 'none' ) ? 'mn-filter-' . $settings['image_filter_effect'] : '';
		$has_link = ! empty( $item['link']['url'] );
		$alignment_class = isset( $settings['icon_numbering_alignment'] ) ? 'mn-align-' . $settings['icon_numbering_alignment'] : 'mn-align-left';
		
		if ( $has_link ) {
			$this->add_link_attributes( 'link_' . $index, $item['link'] );
		}
		// Extract alpha character from title if type is 'alpha'
		$alpha_char = '';
		if ( $settings['show_general_icon_numbering'] === 'yes' && $settings['general_icon_numbering_type'] === 'alpha' ) {
			// Extract text within parentheses from title
			if ( preg_match('/\(([^)]+)\)/', $item['title'], $matches) ) {
				// Get first character from matched text
				$alpha_char = mb_substr( trim( $matches[1] ), 0, 1 );
				$alpha_char = mb_strtoupper( $alpha_char ); // Convert to uppercase
			}
		}
		?>
		<article class="mn-info-item <?php echo esc_attr( $alignment_class ); ?>">
			<?php if ( $settings['show_general_icon_numbering'] === 'yes' ) : ?>
				<div class="mn-infolist-icon-numbering">
					<?php if ( $settings['general_icon_numbering_type'] === 'icon' && ! empty( $settings['general_icon']['value'] ) ) : ?>
						<span class="mn-infolist-general-icon">
							<?php Icons_Manager::render_icon( $settings['general_icon'], [ 'aria-hidden' => 'true' ] ); ?>
						</span>
					<?php elseif ( $settings['general_icon_numbering_type'] === 'numbering' ) : ?>
						<span class="mn-infolist-numbering"><?php echo esc_html( $index + 1 ); ?></span>
					<?php elseif ( $settings['general_icon_numbering_type'] === 'alpha' && ! empty( $alpha_char ) ) : ?>
						<span class="mn-infolist-alpha"><?php echo esc_html( $alpha_char ); ?></span>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<?php if ( $settings['show_image'] ) : ?>
				<?php if ( isset( $item['media_type'] ) && $item['media_type'] === 'icon' && ! empty( $item['icon']['value'] ) ) : ?>
					<!-- Icon Display -->
					<div class="mn-info-media mn-info-icon-wrapper">
						<?php if ( $has_link ) : ?>
							<a <?php $this->print_render_attribute_string( 'link_' . $index ); ?>>
						<?php endif; ?>
						<div class="mn-info-icon">
							<?php Icons_Manager::render_icon( $item['icon'], [ 'aria-hidden' => 'true' ] ); ?>
						</div>
						<?php if ( $has_link ) : ?>
							</a>
						<?php endif; ?>
					</div>
				<?php elseif ( ! empty( $item['image']['url'] ) ) : ?>
					<!-- Image Display -->
					<div class="mn-info-media mn-info-image <?php echo esc_attr( $filter_class ); ?>">
						<?php if ( $has_link ) : ?>
							<a <?php $this->print_render_attribute_string( 'link_' . $index ); ?>>
						<?php endif; ?>
						<img src="<?php echo esc_url( $item['image']['url'] ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>">
						<?php if ( $has_link ) : ?>
							</a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			<?php endif; ?>

			<div class="mn-info-content">
				<?php if ( $settings['show_title'] && ! empty( $item['title'] ) ) : ?>
					<h3 class="mn-info-title">
						<?php if ( $has_link ) : ?>
							<a <?php $this->print_render_attribute_string( 'link_' . $index ); ?>><?php echo esc_html( $item['title'] ); ?></a>
						<?php else : ?>
							<?php echo esc_html( $item['title'] ); ?>
						<?php endif; ?>
					</h3>
				<?php endif; ?>

				<?php if ( $settings['show_description'] && ! empty( $item['description'] ) ) : ?>
					<div class="mn-info-description">
						<?php echo wp_kses_post( $item['description'] ); ?>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $item['readmore_label'] ) && $has_link ) : ?>
					<div class="mn-info-readmore">
						<a <?php $this->print_render_attribute_string( 'link_' . $index ); ?> class="mn-readmore-button">
							<span class="mn-readmore-text"><?php echo esc_html( $item['readmore_label'] ); ?></span>
							<?php if ( ! empty( $item['readmore_icon']['value'] ) ) : ?>
								<span class="mn-readmore-icon mn-icon-after">
									<?php Icons_Manager::render_icon( $item['readmore_icon'], [ 'aria-hidden' => 'true' ] ); ?>
								</span>
							<?php endif; ?>
						</a>
					</div>
				<?php endif; ?>
			</div>
		</article>
		<?php
	}
}
