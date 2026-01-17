<?php
namespace MN_Elements\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * MN Gallery Widget
 */
class MN_Gallery extends Widget_Base {

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		
		// Register AJAX handler for taxonomy loading
		add_action( 'wp_ajax_mn_gallery_get_taxonomies', [ $this, 'ajax_get_taxonomies' ] );
	}

	public function get_name() {
		return 'mn-gallery';
	}

	public function get_title() {
		return esc_html__( 'MN Gallery', 'mn-elements' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'mn-elements' ];
	}

	public function get_keywords() {
		return [ 'gallery', 'image', 'photo', 'lightbox', 'slideshow' ];
	}

	public function get_script_depends() {
		return [ 'mn-gallery' ];
	}

	public function get_style_depends() {
		return [ 'mn-gallery' ];
	}

	/**
	 * AJAX handler to get taxonomies for a post type
	 */
	public function ajax_get_taxonomies() {
		check_ajax_referer( 'mn-gallery-editor', 'nonce' );
		
		$post_type = isset( $_POST['post_type'] ) ? sanitize_text_field( $_POST['post_type'] ) : 'post';
		$taxonomies = $this->get_taxonomies_for_post_type( $post_type );
		
		wp_send_json_success( $taxonomies );
	}

	protected function register_controls() {
		// Gallery Management Section
		$this->start_controls_section(
			'section_gallery',
			[
				'label' => esc_html__( 'Gallery Management', 'mn-elements' ),
			]
		);

		$this->add_control(
			'gallery_source',
			[
				'label' => esc_html__( 'Gallery Source', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'manual',
				'options' => [
					'manual' => esc_html__( 'Manual Upload', 'mn-elements' ),
					'dynamic' => esc_html__( 'Dynamic (Custom Field)', 'mn-elements' ),
					'featured' => esc_html__( 'Featured Images', 'mn-elements' ),
				],
			]
		);

		// Manual Gallery
		$this->add_control(
			'gallery_images',
			[
				'label' => esc_html__( 'Add Images', 'mn-elements' ),
				'type' => Controls_Manager::GALLERY,
				'default' => [],
				'condition' => [
					'gallery_source' => 'manual',
				],
			]
		);

		// Dynamic Gallery Controls
		$this->add_control(
			'dynamic_gallery_field',
			[
				'label' => esc_html__( 'Gallery Field Name', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'gallery_images',
				'description' => esc_html__( 'Enter the custom field name that contains gallery images (ACF Gallery, JetEngine Gallery, etc.)', 'mn-elements' ),
				'condition' => [
					'gallery_source' => 'dynamic',
				],
			]
		);

		$this->add_control(
			'dynamic_post_id',
			[
				'label' => esc_html__( 'Post ID', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'description' => esc_html__( 'Leave empty to use current post ID, or enter specific post ID', 'mn-elements' ),
				'condition' => [
					'gallery_source' => 'dynamic',
				],
			]
		);

		// Featured Images Controls
		$this->add_control(
			'featured_post_type',
			[
				'label' => esc_html__( 'Post Type', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'post',
				'options' => $this->get_post_types(),
				'condition' => [
					'gallery_source' => 'featured',
				],
			]
		);

		$this->add_control(
			'featured_taxonomy',
			[
				'label' => esc_html__( 'Filter by Taxonomy', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [ '' => esc_html__( 'All', 'mn-elements' ) ],
				'condition' => [
					'gallery_source' => 'featured',
				],
				'description' => esc_html__( 'Select post type first to see available taxonomies', 'mn-elements' ),
			]
		);

		$this->add_control(
			'featured_terms',
			[
				'label' => esc_html__( 'Term IDs', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'description' => esc_html__( 'Enter comma-separated term IDs (e.g., 1,2,3). Leave empty to include all terms.', 'mn-elements' ),
				'condition' => [
					'gallery_source' => 'featured',
					'featured_taxonomy!' => '',
				],
			]
		);

		$this->add_control(
			'featured_posts_per_page',
			[
				'label' => esc_html__( 'Posts Per Page', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 10,
				'min' => 1,
				'max' => 100,
				'condition' => [
					'gallery_source' => 'featured',
				],
			]
		);

		$this->add_control(
			'featured_orderby',
			[
				'label' => esc_html__( 'Order By', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'date' => esc_html__( 'Date', 'mn-elements' ),
					'title' => esc_html__( 'Title', 'mn-elements' ),
					'modified' => esc_html__( 'Modified', 'mn-elements' ),
					'rand' => esc_html__( 'Random', 'mn-elements' ),
					'menu_order' => esc_html__( 'Menu Order', 'mn-elements' ),
				],
				'condition' => [
					'gallery_source' => 'featured',
				],
			]
		);

		$this->add_control(
			'featured_order',
			[
				'label' => esc_html__( 'Order', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => [
					'ASC' => esc_html__( 'Ascending', 'mn-elements' ),
					'DESC' => esc_html__( 'Descending', 'mn-elements' ),
				],
				'condition' => [
					'gallery_source' => 'featured',
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
					'grid' => esc_html__( 'Standard Grid', 'mn-elements' ),
					'slideshow' => esc_html__( 'Slideshow', 'mn-elements' ),
					'mixed' => esc_html__( 'Mixed Layout (1 Big + 4 Grid)', 'mn-elements' ),
					'masonry' => esc_html__( 'Masonry', 'mn-elements' ),
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
				'condition' => [
					'layout_type' => 'grid',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-gallery-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				],
			]
		);

		$this->add_control(
			'image_size',
			[
				'label' => esc_html__( 'Image Size', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'medium_large',
				'options' => [
					'thumbnail' => esc_html__( 'Thumbnail', 'mn-elements' ),
					'medium' => esc_html__( 'Medium', 'mn-elements' ),
					'medium_large' => esc_html__( 'Medium Large', 'mn-elements' ),
					'large' => esc_html__( 'Large', 'mn-elements' ),
					'full' => esc_html__( 'Full Size', 'mn-elements' ),
				],
			]
		);

		$this->add_control(
			'enable_lightbox',
			[
				'label' => esc_html__( 'Enable Lightbox', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_caption',
			[
				'label' => esc_html__( 'Show Caption', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
			]
		);

		// Slideshow specific controls
		$this->add_control(
			'slideshow_heading',
			[
				'label' => esc_html__( 'Slideshow Settings', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'layout_type' => 'slideshow',
				],
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label' => esc_html__( 'Autoplay', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'layout_type' => 'slideshow',
				],
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
				'step' => 500,
				'condition' => [
					'layout_type' => 'slideshow',
					'autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_arrows',
			[
				'label' => esc_html__( 'Show Arrows', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'layout_type' => 'slideshow',
				],
			]
		);

		$this->add_control(
			'show_dots',
			[
				'label' => esc_html__( 'Show Dots', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'layout_type' => 'slideshow',
				],
			]
		);

		$this->end_controls_section();

		// Style Section - Gallery Items
		$this->start_controls_section(
			'section_gallery_style',
			[
				'label' => esc_html__( 'Gallery Items', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'gallery_gap',
			[
				'label' => esc_html__( 'Gap', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-gallery-grid' => 'gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-gallery-mixed .mn-gallery-grid-small' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'selector' => '{{WRAPPER}} .mn-gallery-item img',
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-gallery-item img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'image_box_shadow',
				'selector' => '{{WRAPPER}} .mn-gallery-item img',
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
	 * Get featured images from posts
	 */
	private function get_featured_images( $settings ) {
		$images = [];
		
		$args = [
			'post_type' => isset( $settings['featured_post_type'] ) ? $settings['featured_post_type'] : 'post',
			'posts_per_page' => isset( $settings['featured_posts_per_page'] ) ? intval( $settings['featured_posts_per_page'] ) : 10,
			'orderby' => isset( $settings['featured_orderby'] ) ? $settings['featured_orderby'] : 'date',
			'order' => isset( $settings['featured_order'] ) ? $settings['featured_order'] : 'DESC',
			'meta_query' => [
				[
					'key' => '_thumbnail_id',
					'compare' => 'EXISTS',
				],
			],
		];

		// Add taxonomy filter if specified
		if ( ! empty( $settings['featured_taxonomy'] ) ) {
			$taxonomy = $settings['featured_taxonomy'];
			$terms = [];
			
			if ( ! empty( $settings['featured_terms'] ) ) {
				// Parse comma-separated term IDs
				$term_ids = array_map( 'trim', explode( ',', $settings['featured_terms'] ) );
				$term_ids = array_filter( $term_ids, 'is_numeric' );
				$terms = array_map( 'intval', $term_ids );
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
				$thumbnail_id = get_post_thumbnail_id();
				
				if ( $thumbnail_id ) {
					$images[] = [
						'id' => $thumbnail_id,
						'url' => wp_get_attachment_url( $thumbnail_id ),
					];
				}
			}
			wp_reset_postdata();
		}

		return $images;
	}

	/**
	 * Check if current user can see debug info
	 */
	private function can_show_debug() {
		return current_user_can( 'manage_options' ) && defined( 'WP_DEBUG' ) && WP_DEBUG;
	}

	/**
	 * Get custom field value with JetEngine, ACF and WordPress meta fallback
	 */
	private function get_custom_field_value( $field_name, $post_id = null ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		// Debug logging (only for admins with WP_DEBUG)
		if ( $this->can_show_debug() ) {
			error_log( "MN Gallery Debug - Post ID: $post_id, Field Name: $field_name" );
		}

		// Try JetEngine first if available
		if ( function_exists( 'jet_engine' ) && jet_engine()->listings ) {
			// Set current object for JetEngine context
			$current_object = get_post( $post_id );
			if ( $current_object ) {
				jet_engine()->listings->data->set_current_object( $current_object );
				$value = jet_engine()->listings->data->get_meta( $field_name );
				if ( $this->can_show_debug() ) {
					error_log( "MN Gallery Debug - JetEngine Value: " . print_r( $value, true ) );
				}
				if ( $value ) {
					return $value;
				}
			}
		}

		// Try ACF
		if ( function_exists( 'get_field' ) ) {
			$value = get_field( $field_name, $post_id );
			if ( $this->can_show_debug() ) {
				error_log( "MN Gallery Debug - ACF Value: " . print_r( $value, true ) );
			}
			if ( $value ) {
				return $value;
			}
		}

		// Fallback to WordPress meta
		$value = get_post_meta( $post_id, $field_name, true );
		if ( $this->can_show_debug() ) {
			error_log( "MN Gallery Debug - Meta Value: " . print_r( $value, true ) );
		}
		
		// Try without 'true' parameter to get all values
		if ( ! $value ) {
			$all_values = get_post_meta( $post_id, $field_name );
			if ( $this->can_show_debug() ) {
				error_log( "MN Gallery Debug - All Meta Values: " . print_r( $all_values, true ) );
			}
			if ( ! empty( $all_values ) ) {
				$value = $all_values[0];
			}
		}

		return $value;
	}

	/**
	 * Apply JetEngine gallery filters if available
	 */
	private function apply_jetengine_filters( $gallery_data, $layout_type ) {
		// Skip JetEngine processing - we handle the data ourselves
		// JetEngine expects array format but we get string format
		// Our processing is more robust for various data formats
		return $gallery_data;
	}

	/**
	 * Process image data using JetEngine Tools if available
	 */
	private function process_image_data( $img_data ) {
		// Use JetEngine Tools if available (more robust)
		if ( class_exists( '\Jet_Engine_Tools' ) ) {
			$processed = \Jet_Engine_Tools::get_attachment_image_data_array( $img_data );
			if ( $processed && isset( $processed['id'] ) && isset( $processed['url'] ) ) {
				return [
					'id' => $processed['id'],
					'url' => $processed['url'],
				];
			}
		}

		// Fallback to manual processing
		if ( is_array( $img_data ) ) {
			if ( isset( $img_data['ID'] ) ) {
				// ACF format: array with ID, url, etc.
				return [
					'id' => $img_data['ID'],
					'url' => isset( $img_data['url'] ) ? $img_data['url'] : wp_get_attachment_url( $img_data['ID'] ),
				];
			} elseif ( isset( $img_data['id'] ) ) {
				// Alternative format
				return [
					'id' => $img_data['id'],
					'url' => isset( $img_data['url'] ) ? $img_data['url'] : wp_get_attachment_url( $img_data['id'] ),
				];
			}
		} elseif ( is_numeric( $img_data ) ) {
			// Attachment ID
			return [
				'id' => intval( $img_data ),
				'url' => wp_get_attachment_url( intval( $img_data ) ),
			];
		} elseif ( is_string( $img_data ) && is_numeric( $img_data ) ) {
			// String number (attachment ID)
			$attachment_id = intval( $img_data );
			return [
				'id' => $attachment_id,
				'url' => wp_get_attachment_url( $attachment_id ),
			];
		} elseif ( is_string( $img_data ) && filter_var( $img_data, FILTER_VALIDATE_URL ) ) {
			// URL string - convert to attachment ID
			$attachment_id = attachment_url_to_postid( $img_data );
			if ( $attachment_id ) {
				return [
					'id' => $attachment_id,
					'url' => $img_data,
				];
			} else {
				// If we can't find attachment ID, use URL directly
				return [
					'id' => 0,
					'url' => $img_data,
				];
			}
		}

		return false;
	}

	/**
	 * Get gallery images based on source
	 */
	private function get_gallery_images( $settings ) {
		$images = [];

		if ( $settings['gallery_source'] === 'manual' ) {
			$images = $settings['gallery_images'];
		} elseif ( $settings['gallery_source'] === 'featured' ) {
			// Featured images source
			$images = $this->get_featured_images( $settings );
		} else {
			// Dynamic source
			$post_id = ! empty( $settings['dynamic_post_id'] ) ? intval( $settings['dynamic_post_id'] ) : get_the_ID();
			$field_name = $settings['dynamic_gallery_field'];
			
			if ( $this->can_show_debug() ) {
				error_log( "MN Gallery Debug - Getting images for Post ID: $post_id, Field: $field_name" );
			}
			
			$gallery_data = $this->get_custom_field_value( $field_name, $post_id );
			
			if ( $this->can_show_debug() ) {
				error_log( "MN Gallery Debug - Gallery Data Type: " . gettype( $gallery_data ) );
				error_log( "MN Gallery Debug - Gallery Data: " . print_r( $gallery_data, true ) );
			}

			// Apply JetEngine filters if available
			if ( $gallery_data ) {
				$layout_type = isset( $settings['layout_type'] ) ? $settings['layout_type'] : 'grid';
				$gallery_data = $this->apply_jetengine_filters( $gallery_data, $layout_type );
			}
			
			if ( $gallery_data ) {
				// Handle different field formats
				if ( is_array( $gallery_data ) ) {
					foreach ( $gallery_data as $index => $item ) {
						if ( $this->can_show_debug() ) {
							error_log( "MN Gallery Debug - Processing item $index: " . print_r( $item, true ) );
						}
						
						$processed_image = $this->process_image_data( $item );
						if ( $processed_image && isset( $processed_image['url'] ) && $processed_image['url'] ) {
							$images[] = $processed_image;
						}
					}
				} elseif ( is_string( $gallery_data ) ) {
					// Handle serialized data or comma-separated IDs/URLs
					if ( strpos( $gallery_data, ',' ) !== false ) {
						// Comma-separated IDs or URLs (JetEngine format)
						$items = explode( ',', $gallery_data );
						if ( $this->can_show_debug() ) {
							error_log( "MN Gallery Debug - Processing comma-separated items: " . count( $items ) );
						}
						foreach ( $items as $index => $item ) {
							$item = trim( $item );
							if ( $this->can_show_debug() ) {
								error_log( "MN Gallery Debug - Processing item $index: $item" );
							}
							$processed_image = $this->process_image_data( $item );
							if ( $processed_image && isset( $processed_image['url'] ) && $processed_image['url'] ) {
								$images[] = $processed_image;
								if ( $this->can_show_debug() ) {
									error_log( "MN Gallery Debug - Successfully processed item $index: " . print_r( $processed_image, true ) );
								}
							} else {
								if ( $this->can_show_debug() ) {
									error_log( "MN Gallery Debug - Failed to process item $index: $item" );
								}
							}
						}
					} elseif ( is_numeric( $gallery_data ) ) {
						// Single ID as string
						$processed_image = $this->process_image_data( $gallery_data );
						if ( $processed_image && isset( $processed_image['url'] ) && $processed_image['url'] ) {
							$images[] = $processed_image;
						}
					} else {
						// Try to unserialize
						$unserialized = maybe_unserialize( $gallery_data );
						if ( is_array( $unserialized ) ) {
							// Recursively process unserialized data
							foreach ( $unserialized as $item ) {
								$processed_image = $this->process_image_data( $item );
								if ( $processed_image && isset( $processed_image['url'] ) && $processed_image['url'] ) {
									$images[] = $processed_image;
								}
							}
						}
					}
				} else {
					// Single item (numeric or other format)
					$processed_image = $this->process_image_data( $gallery_data );
					if ( $processed_image && isset( $processed_image['url'] ) && $processed_image['url'] ) {
						$images[] = $processed_image;
					}
				}
			}
			
			if ( $this->can_show_debug() ) {
				error_log( "MN Gallery Debug - Final images count: " . count( $images ) );
			}
		}

		return $images;
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$images = $this->get_gallery_images( $settings );

		if ( empty( $images ) ) {
			$debug_info = '';
			if ( $settings['gallery_source'] === 'dynamic' && $this->can_show_debug() ) {
				$post_id = ! empty( $settings['dynamic_post_id'] ) ? intval( $settings['dynamic_post_id'] ) : get_the_ID();
				$field_name = $settings['dynamic_gallery_field'];
				$debug_info = sprintf( 
					' (Post ID: %d, Field: %s)', 
					$post_id, 
					$field_name 
				);
			}
			echo '<div class="mn-gallery-empty">' . esc_html__( 'No images found.', 'mn-elements' ) . esc_html( $debug_info ) . '</div>';
			return;
		}

		$this->add_render_attribute( 'wrapper', 'class', 'mn-gallery-wrapper' );
		$this->add_render_attribute( 'wrapper', 'class', 'mn-gallery-layout-' . $settings['layout_type'] );
		
		if ( $settings['enable_lightbox'] === 'yes' ) {
			$this->add_render_attribute( 'wrapper', 'class', 'mn-gallery-lightbox-enabled' );
		}

		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<?php
			switch ( $settings['layout_type'] ) {
				case 'slideshow':
					$this->render_slideshow( $images, $settings );
					break;
				case 'mixed':
					$this->render_mixed_layout( $images, $settings );
					break;
				case 'masonry':
					$this->render_masonry( $images, $settings );
					break;
				default:
					$this->render_grid( $images, $settings );
					break;
			}
			?>
		</div>
		<?php
	}

	private function render_grid( $images, $settings ) {
		?>
		<div class="mn-gallery-grid">
			<?php foreach ( $images as $index => $image ) : ?>
				<?php $this->render_gallery_item( $image, $settings, $index ); ?>
			<?php endforeach; ?>
		</div>
		<?php
	}

	private function render_slideshow( $images, $settings ) {
		?>
		<div class="mn-gallery-slideshow" 
			 data-autoplay="<?php echo esc_attr( $settings['autoplay'] ); ?>"
			 data-autoplay-speed="<?php echo esc_attr( $settings['autoplay_speed'] ); ?>">
			<div class="mn-slideshow-container">
				<?php foreach ( $images as $index => $image ) : ?>
					<div class="mn-slideshow-slide <?php echo $index === 0 ? 'active' : ''; ?>">
						<?php $this->render_gallery_item( $image, $settings, $index ); ?>
					</div>
				<?php endforeach; ?>
			</div>
			
			<?php if ( $settings['show_arrows'] === 'yes' ) : ?>
				<button class="mn-slideshow-arrow mn-slideshow-prev" aria-label="<?php esc_attr_e( 'Previous', 'mn-elements' ); ?>">
					<i class="eicon-chevron-left"></i>
				</button>
				<button class="mn-slideshow-arrow mn-slideshow-next" aria-label="<?php esc_attr_e( 'Next', 'mn-elements' ); ?>">
					<i class="eicon-chevron-right"></i>
				</button>
			<?php endif; ?>
			
			<?php if ( $settings['show_dots'] === 'yes' ) : ?>
				<div class="mn-slideshow-dots">
					<?php foreach ( $images as $index => $image ) : ?>
						<button class="mn-slideshow-dot <?php echo $index === 0 ? 'active' : ''; ?>" data-slide="<?php echo esc_attr( $index ); ?>"></button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	private function render_mixed_layout( $images, $settings ) {
		if ( count( $images ) < 2 ) {
			$this->render_grid( $images, $settings );
			return;
		}
		?>
		<div class="mn-gallery-mixed">
			<div class="mn-gallery-main">
				<?php $this->render_gallery_item( $images[0], $settings, 0 ); ?>
			</div>
			<div class="mn-gallery-grid-small">
				<?php 
				$remaining_images = array_slice( $images, 1, 4 );
				foreach ( $remaining_images as $index => $image ) : 
				?>
					<?php $this->render_gallery_item( $image, $settings, $index + 1 ); ?>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}

	private function render_masonry( $images, $settings ) {
		?>
		<div class="mn-gallery-masonry">
			<?php foreach ( $images as $index => $image ) : ?>
				<?php $this->render_gallery_item( $image, $settings, $index ); ?>
			<?php endforeach; ?>
		</div>
		<?php
	}

	private function render_gallery_item( $image, $settings, $index ) {
		$image_id = $image['id'];
		
		// Handle cases where we have URL but no attachment ID
		if ( $image_id && $image_id > 0 ) {
			$image_url = wp_get_attachment_image_url( $image_id, $settings['image_size'] );
			$full_image_url = wp_get_attachment_image_url( $image_id, 'full' );
			$caption = wp_get_attachment_caption( $image_id );
			$alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
		} else {
			// Use the URL directly if no attachment ID
			$image_url = $image['url'];
			$full_image_url = $image['url'];
			$caption = '';
			$alt = '';
		}
		
		// Fallback to original URL if attachment functions fail
		if ( ! $image_url ) {
			$image_url = $image['url'];
		}
		if ( ! $full_image_url ) {
			$full_image_url = $image['url'];
		}

		$lightbox_attrs = '';
		if ( $settings['enable_lightbox'] === 'yes' ) {
			$lightbox_attrs = 'data-lightbox="mn-gallery" data-src="' . esc_url( $full_image_url ) . '"';
			if ( $caption ) {
				$lightbox_attrs .= ' data-caption="' . esc_attr( $caption ) . '"';
			}
		}
		// Skip if no valid image URL
		if ( ! $image_url ) {
			return;
		}
		?>
		<div class="mn-gallery-item" data-index="<?php echo esc_attr( $index ); ?>">
			<div class="mn-gallery-image" <?php echo $lightbox_attrs; ?>>
				<img src="<?php echo esc_url( $image_url ); ?>" 
					 alt="<?php echo esc_attr( $alt ); ?>"
					 loading="lazy">
				<?php if ( $settings['enable_lightbox'] === 'yes' ) : ?>
					<div class="mn-gallery-overlay">
						<i class="eicon-search-plus"></i>
					</div>
				<?php endif; ?>
			</div>
			<?php if ( $settings['show_caption'] === 'yes' && $caption ) : ?>
				<div class="mn-gallery-caption">
					<?php echo esc_html( $caption ); ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}
}
