<?php
namespace MN_Elements\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * MN WooCommerce Product Gallery Widget
 * 
 * Custom product gallery with vertical thumbnails, navigation arrows, and lightbox
 *
 * @since 1.5.0
 */
class MN_Woo_Product_Gallery extends Widget_Base {

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		
		wp_register_style(
			'mn-woo-product-gallery',
			MN_ELEMENTS_URL . 'assets/css/mn-woo-product-gallery.css',
			[],
			MN_ELEMENTS_VERSION
		);
		
		wp_register_script(
			'mn-woo-product-gallery',
			MN_ELEMENTS_URL . 'assets/js/mn-woo-product-gallery.js',
			[ 'jquery' ],
			MN_ELEMENTS_VERSION,
			true
		);
	}

	public function get_name() {
		return 'mn-woo-product-gallery';
	}

	public function get_title() {
		return esc_html__( 'MN Product Gallery', 'mn-elements' );
	}

	public function get_icon() {
		return 'eicon-product-images';
	}

	public function get_categories() {
		return [ 'mn-elements' ];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'product', 'gallery', 'image', 'lightbox', 'thumbnail' ];
	}

	public function get_script_depends() {
		return [ 'mn-woo-product-gallery' ];
	}

	public function get_style_depends() {
		return [ 'mn-woo-product-gallery' ];
	}

	protected function register_controls() {
		// Content Section
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'mn-elements' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'thumbnail_position',
			[
				'label'   => esc_html__( 'Thumbnail Position', 'mn-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left'   => esc_html__( 'Left', 'mn-elements' ),
					'right'  => esc_html__( 'Right', 'mn-elements' ),
					'top'    => esc_html__( 'Top', 'mn-elements' ),
					'bottom' => esc_html__( 'Bottom', 'mn-elements' ),
				],
				'prefix_class' => 'mn-gallery-pos-',
			]
		);

		$this->add_control(
			'visible_thumbnails',
			[
				'label'   => esc_html__( 'Visible Thumbnails', 'mn-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 2,
				'max'     => 6,
				'default' => 3,
			]
		);

		$this->add_control(
			'show_navigation',
			[
				'label'        => esc_html__( 'Show Navigation Arrows', 'mn-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'mn-elements' ),
				'label_off'    => esc_html__( 'No', 'mn-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'lightbox',
			[
				'label'        => esc_html__( 'Enable Lightbox', 'mn-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'mn-elements' ),
				'label_off'    => esc_html__( 'No', 'mn-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'sale_flash',
			[
				'label'        => esc_html__( 'Show Sale Badge', 'mn-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'mn-elements' ),
				'label_off'    => esc_html__( 'No', 'mn-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'show_zoom_icon',
			[
				'label'        => esc_html__( 'Show Zoom Icon', 'mn-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'mn-elements' ),
				'label_off'    => esc_html__( 'No', 'mn-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => esc_html__( 'Show a zoom/search icon in the center of the main image on hover.', 'mn-elements' ),
				'condition'    => [
					'lightbox' => 'yes',
				],
			]
		);

		$this->add_control(
			'heading_variation_gallery',
			[
				'label'     => esc_html__( 'Variable Product', 'mn-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'variation_images',
			[
				'label'        => esc_html__( 'Include Variation Images', 'mn-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'mn-elements' ),
				'label_off'    => esc_html__( 'No', 'mn-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => esc_html__( 'Include variation images in the gallery for variable products.', 'mn-elements' ),
			]
		);

		$this->add_control(
			'variation_image_swap',
			[
				'label'        => esc_html__( 'Swap Image on Variation Select', 'mn-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'mn-elements' ),
				'label_off'    => esc_html__( 'No', 'mn-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => esc_html__( 'Automatically switch gallery to the variation image when a variation is selected in MN Add to Cart widget.', 'mn-elements' ),
			]
		);

		$this->end_controls_section();

		// Style Section - Main Image
		$this->start_controls_section(
			'section_style_main_image',
			[
				'label' => esc_html__( 'Main Image', 'mn-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'main_image_width',
			[
				'label'      => esc_html__( 'Width', 'mn-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min' => 200,
						'max' => 1000,
					],
					'%' => [
						'min' => 50,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => '%',
					'size' => 75,
				],
				'selectors'  => [
					'{{WRAPPER}} .mn-woo-gallery-main' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'main_image_height',
			[
				'label'      => esc_html__( 'Height', 'mn-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range'      => [
					'px' => [
						'min' => 200,
						'max' => 800,
					],
					'vh' => [
						'min' => 20,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 450,
				],
				'selectors'  => [
					'{{WRAPPER}} .mn-woo-gallery-main' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'main_image_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'mn-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'top'    => 12,
					'right'  => 12,
					'bottom' => 12,
					'left'   => 12,
					'unit'   => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .mn-woo-gallery-main, {{WRAPPER}} .mn-woo-gallery-main img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'main_image_shadow',
				'selector' => '{{WRAPPER}} .mn-woo-gallery-main',
			]
		);

		$this->end_controls_section();

		// Style Section - Thumbnails
		$this->start_controls_section(
			'section_style_thumbnails',
			[
				'label' => esc_html__( 'Thumbnails', 'mn-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'thumbnail_width',
			[
				'label'      => esc_html__( 'Width', 'mn-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 50,
						'max' => 200,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 100,
				],
				'selectors'  => [
					'{{WRAPPER}} .mn-woo-gallery-thumbnails' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-woo-gallery-thumb' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'thumbnail_height',
			[
				'label'      => esc_html__( 'Height', 'mn-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 50,
						'max' => 200,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 80,
				],
				'selectors'  => [
					'{{WRAPPER}} .mn-woo-gallery-thumb' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'thumbnail_spacing',
			[
				'label'      => esc_html__( 'Spacing', 'mn-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 30,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors'  => [
					'{{WRAPPER}} .mn-woo-gallery-thumb' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'thumbnail_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'mn-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'top'    => 8,
					'right'  => 8,
					'bottom' => 8,
					'left'   => 8,
					'unit'   => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .mn-woo-gallery-thumb, {{WRAPPER}} .mn-woo-gallery-thumb img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'thumbnail_opacity',
			[
				'label'     => esc_html__( 'Opacity', 'mn-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => 0.1,
						'max'  => 1,
						'step' => 0.1,
					],
				],
				'default'   => [
					'size' => 0.6,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-woo-gallery-thumb:not(.active)' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_control(
			'thumbnail_active_border_color',
			[
				'label'     => esc_html__( 'Active Border Color', 'mn-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#2271b1',
				'selectors' => [
					'{{WRAPPER}} .mn-woo-gallery-thumb.active' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'thumbnail_active_border_width',
			[
				'label'      => esc_html__( 'Active Border Width', 'mn-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 5,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 2,
				],
				'selectors'  => [
					'{{WRAPPER}} .mn-woo-gallery-thumb.active' => 'border-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Style Section - Gallery Container
		$this->start_controls_section(
			'section_style_container',
			[
				'label' => esc_html__( 'Container', 'mn-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'gallery_gap',
			[
				'label'      => esc_html__( 'Gap Between Thumbnails and Main Image', 'mn-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors'  => [
					'{{WRAPPER}} .mn-woo-gallery-container' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Style Section - Navigation
		$this->start_controls_section(
			'section_style_navigation',
			[
				'label'     => esc_html__( 'Navigation Arrows', 'mn-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_navigation' => 'yes',
				],
			]
		);

		$this->add_control(
			'nav_color',
			[
				'label'     => esc_html__( 'Arrow Color', 'mn-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333333',
				'selectors' => [
					'{{WRAPPER}} .mn-woo-gallery-nav' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_background',
			[
				'label'     => esc_html__( 'Background Color', 'mn-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-woo-gallery-nav' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_hover_color',
			[
				'label'     => esc_html__( 'Hover Arrow Color', 'mn-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-woo-gallery-nav:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_hover_background',
			[
				'label'     => esc_html__( 'Hover Background Color', 'mn-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#2271b1',
				'selectors' => [
					'{{WRAPPER}} .mn-woo-gallery-nav:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'nav_size',
			[
				'label'      => esc_html__( 'Size', 'mn-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 20,
						'max' => 50,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors'  => [
					'{{WRAPPER}} .mn-woo-gallery-nav' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'nav_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'mn-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
					'%' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default'    => [
					'unit' => '%',
					'size' => 50,
				],
				'selectors'  => [
					'{{WRAPPER}} .mn-woo-gallery-nav' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Style Section - Sale Badge
		$this->start_controls_section(
			'section_style_sale_badge',
			[
				'label'     => esc_html__( 'Sale Badge', 'mn-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'sale_flash' => 'yes',
				],
			]
		);

		$this->add_control(
			'sale_badge_color',
			[
				'label'     => esc_html__( 'Text Color', 'mn-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-woo-gallery-sale-badge' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'sale_badge_background',
			[
				'label'     => esc_html__( 'Background Color', 'mn-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#e74c3c',
				'selectors' => [
					'{{WRAPPER}} .mn-woo-gallery-sale-badge' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'sale_badge_padding',
			[
				'label'      => esc_html__( 'Padding', 'mn-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'default'    => [
					'top'    => 5,
					'right'  => 10,
					'bottom' => 5,
					'left'   => 10,
					'unit'   => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .mn-woo-gallery-sale-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'sale_badge_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'mn-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 4,
				],
				'selectors'  => [
					'{{WRAPPER}} .mn-woo-gallery-sale-badge' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		
		global $product;
		
		if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
			global $post;
			if ( $post && $post->post_type === 'product' ) {
				$product = wc_get_product( $post->ID );
			}
		}
		
		if ( ! $product ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="mn-woo-gallery-editor-notice">' . esc_html__( 'This widget only works on Product pages.', 'mn-elements' ) . '</div>';
			}
			return;
		}

		$is_variable        = $product->is_type( 'variable' );
		$include_var_images  = $is_variable && $settings['variation_images'] === 'yes';
		$enable_image_swap   = $is_variable && $settings['variation_image_swap'] === 'yes';

		// Get gallery images
		$gallery_image_ids = $product->get_gallery_image_ids();
		$featured_image_id = $product->get_image_id();
		
		// Combine featured image with gallery
		$all_images = [];
		if ( $featured_image_id ) {
			$all_images[] = $featured_image_id;
		}
		if ( ! empty( $gallery_image_ids ) ) {
			$all_images = array_merge( $all_images, $gallery_image_ids );
		}

		// Build variation data for variable products
		$variation_map = []; // variation_id => image index in $all_images
		if ( $is_variable ) {
			$available_variations = $product->get_available_variations();
			
			foreach ( $available_variations as $variation ) {
				$var_image_id = ! empty( $variation['image_id'] ) ? intval( $variation['image_id'] ) : 0;
				
				if ( ! $var_image_id ) {
					continue;
				}

				// Check if this image is already in the gallery
				$existing_index = array_search( $var_image_id, $all_images );
				
				if ( $existing_index !== false ) {
					// Image already exists in gallery, just map it
					$variation_map[ $variation['variation_id'] ] = [
						'index'      => $existing_index,
						'image_id'   => $var_image_id,
						'attributes' => $variation['attributes'],
					];
				} elseif ( $include_var_images ) {
					// Add variation image to gallery
					$all_images[] = $var_image_id;
					$new_index = count( $all_images ) - 1;
					$variation_map[ $variation['variation_id'] ] = [
						'index'      => $new_index,
						'image_id'   => $var_image_id,
						'attributes' => $variation['attributes'],
					];
				}
			}
		}
		
		if ( empty( $all_images ) ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="mn-woo-gallery-editor-notice">' . esc_html__( 'No product images found.', 'mn-elements' ) . '</div>';
			}
			return;
		}

		// Remove duplicate image IDs while preserving order
		$all_images = array_values( array_unique( $all_images ) );

		// Rebuild variation_map indices after dedup
		if ( ! empty( $variation_map ) ) {
			foreach ( $variation_map as $var_id => &$var_data ) {
				$new_index = array_search( $var_data['image_id'], $all_images );
				if ( $new_index !== false ) {
					$var_data['index'] = $new_index;
				}
			}
			unset( $var_data );
		}

		$visible_thumbnails = intval( $settings['visible_thumbnails'] );
		$show_navigation    = $settings['show_navigation'] === 'yes' && count( $all_images ) > $visible_thumbnails;
		$enable_lightbox    = $settings['lightbox'] === 'yes';
		$show_sale_badge    = $settings['sale_flash'] === 'yes' && $product->is_on_sale();
		$show_zoom_icon     = $enable_lightbox && $settings['show_zoom_icon'] === 'yes';
		$thumb_position     = $settings['thumbnail_position'];
		
		// Prepare wrapper data attributes
		$wrapper_attrs = ' data-visible="' . esc_attr( $visible_thumbnails ) . '"';
		$wrapper_attrs .= ' data-product-id="' . esc_attr( $product->get_id() ) . '"';
		$wrapper_attrs .= ' data-position="' . esc_attr( $thumb_position ) . '"';
		
		if ( $enable_image_swap && ! empty( $variation_map ) ) {
			$wrapper_attrs .= ' data-variation-swap="1"';
			$wrapper_attrs .= " data-variations='" . wp_json_encode( $variation_map ) . "'";
			$wrapper_attrs .= ' data-featured-index="0"';
		}
		
		$widget_id = $this->get_id();
		?>
		<div class="mn-woo-gallery-wrapper"<?php echo $wrapper_attrs; ?>>
			<div class="mn-woo-gallery-container">
				<!-- Thumbnails Column -->
				<div class="mn-woo-gallery-thumbnails-wrapper">
					<?php if ( $show_navigation ) : ?>
					<button type="button" class="mn-woo-gallery-nav mn-woo-gallery-nav-up" aria-label="<?php esc_attr_e( 'Previous', 'mn-elements' ); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"></polyline></svg>
					</button>
					<?php endif; ?>
					
					<div class="mn-woo-gallery-thumbnails" data-scroll-index="0">
						<div class="mn-woo-gallery-thumbnails-inner">
							<?php foreach ( $all_images as $index => $image_id ) : 
								$thumb_url = wp_get_attachment_image_url( $image_id, 'thumbnail' );
								$active_class = $index === 0 ? 'active' : '';
							?>
							<div class="mn-woo-gallery-thumb <?php echo esc_attr( $active_class ); ?>" data-index="<?php echo esc_attr( $index ); ?>" data-image-id="<?php echo esc_attr( $image_id ); ?>">
								<img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( get_post_meta( $image_id, '_wp_attachment_image_alt', true ) ); ?>">
							</div>
							<?php endforeach; ?>
						</div>
					</div>
					
					<?php if ( $show_navigation ) : ?>
					<button type="button" class="mn-woo-gallery-nav mn-woo-gallery-nav-down" aria-label="<?php esc_attr_e( 'Next', 'mn-elements' ); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
					</button>
					<?php endif; ?>
				</div>
				
				<!-- Main Image -->
				<div class="mn-woo-gallery-main"<?php echo $enable_lightbox ? ' data-lightbox="true"' : ''; ?>>
					<?php if ( $show_sale_badge ) : ?>
					<span class="mn-woo-gallery-sale-badge"><?php echo esc_html__( 'Sale!', 'mn-elements' ); ?></span>
					<?php endif; ?>
					<?php foreach ( $all_images as $index => $image_id ) : 
						$full_url = wp_get_attachment_image_url( $image_id, 'full' );
						$large_url = wp_get_attachment_image_url( $image_id, 'large' );
						$active_class = $index === 0 ? 'active' : '';
					?>
					<div class="mn-woo-gallery-main-image <?php echo esc_attr( $active_class ); ?>" data-index="<?php echo esc_attr( $index ); ?>" data-full="<?php echo esc_url( $full_url ); ?>" data-image-id="<?php echo esc_attr( $image_id ); ?>">
						<img src="<?php echo esc_url( $large_url ); ?>" alt="<?php echo esc_attr( get_post_meta( $image_id, '_wp_attachment_image_alt', true ) ); ?>">
					</div>
					<?php endforeach; ?>
					<?php if ( $show_zoom_icon ) : ?>
					<div class="mn-woo-gallery-zoom-icon">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line><line x1="11" y1="8" x2="11" y2="14"></line><line x1="8" y1="11" x2="14" y2="11"></line></svg>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		
		<?php if ( $enable_lightbox ) : ?>
		<!-- Lightbox -->
		<div class="mn-woo-gallery-lightbox" style="display: none;">
			<div class="mn-woo-gallery-lightbox-overlay"></div>
			<div class="mn-woo-gallery-lightbox-content">
				<button type="button" class="mn-woo-gallery-lightbox-close">&times;</button>
				<button type="button" class="mn-woo-gallery-lightbox-prev">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
				</button>
				<div class="mn-woo-gallery-lightbox-image">
					<img src="" alt="">
				</div>
				<button type="button" class="mn-woo-gallery-lightbox-next">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
				</button>
			</div>
		</div>
		<?php endif; ?>
		<?php
	}
}
