<?php
namespace MN_Elements\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * MN Instafeed Widget
 *
 * Instagram feed widget with manual and API modes
 *
 * @since 1.7.7
 */
class MN_Instafeed extends Widget_Base {

	public function get_name() {
		return 'mn-instafeed';
	}

	public function get_title() {
		return esc_html__( 'MN Instafeed', 'mn-elements' );
	}

	public function get_icon() {
		return 'eicon-instagram-gallery';
	}

	public function get_categories() {
		return [ 'mn-elements' ];
	}

	public function get_keywords() {
		return [ 'instagram', 'feed', 'social', 'gallery', 'photos' ];
	}

	public function get_script_depends() {
		return [ 'mn-instafeed' ];
	}

	public function get_style_depends() {
		return [ 'mn-instafeed' ];
	}

	protected function register_controls() {

		// Feed Source Section
		$this->start_controls_section(
			'section_feed_source',
			[
				'label' => esc_html__( 'Feed Source', 'mn-elements' ),
			]
		);

		$this->add_control(
			'feed_source',
			[
				'label' => esc_html__( 'Source', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'manual',
				'options' => [
					'manual' => esc_html__( 'Manual Feed', 'mn-elements' ),
					'api' => esc_html__( 'Instagram API', 'mn-elements' ),
				],
			]
		);

		// Manual Feed
		$repeater = new Repeater();

		$repeater->add_control(
			'image',
			[
				'label' => esc_html__( 'Image', 'mn-elements' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control(
			'caption',
			[
				'label' => esc_html__( 'Caption', 'mn-elements' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => '',
				'placeholder' => esc_html__( 'Enter caption', 'mn-elements' ),
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' => esc_html__( 'Link', 'mn-elements' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://instagram.com/p/xxxxx',
				'default' => [
					'url' => '',
					'is_external' => true,
				],
			]
		);

		$repeater->add_control(
			'likes',
			[
				'label' => esc_html__( 'Likes Count', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
			]
		);

		$repeater->add_control(
			'comments',
			[
				'label' => esc_html__( 'Comments Count', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
			]
		);

		$this->add_control(
			'manual_feed',
			[
				'label' => esc_html__( 'Instagram Posts', 'mn-elements' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'image' => [
							'url' => \Elementor\Utils::get_placeholder_image_src(),
						],
						'caption' => esc_html__( 'Post 1', 'mn-elements' ),
						'likes' => 150,
						'comments' => 25,
					],
					[
						'image' => [
							'url' => \Elementor\Utils::get_placeholder_image_src(),
						],
						'caption' => esc_html__( 'Post 2', 'mn-elements' ),
						'likes' => 200,
						'comments' => 30,
					],
					[
						'image' => [
							'url' => \Elementor\Utils::get_placeholder_image_src(),
						],
						'caption' => esc_html__( 'Post 3', 'mn-elements' ),
						'likes' => 180,
						'comments' => 20,
					],
				],
				'title_field' => '{{{ caption }}}',
				'condition' => [
					'feed_source' => 'manual',
				],
			]
		);

		// Instagram API Settings
		$this->add_control(
			'api_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => '<div style="background:#f8f9fa;padding:15px;border-radius:4px;margin-bottom:15px;">
					<strong>' . esc_html__( 'Instagram Basic Display API', 'mn-elements' ) . '</strong><br>
					' . esc_html__( 'You need to create an Instagram App and get Access Token.', 'mn-elements' ) . '<br>
					<a href="https://developers.facebook.com/docs/instagram-basic-display-api/getting-started" target="_blank">' . esc_html__( 'Learn More', 'mn-elements' ) . '</a>
				</div>',
				'condition' => [
					'feed_source' => 'api',
				],
			]
		);

		$this->add_control(
			'access_token',
			[
				'label' => esc_html__( 'Access Token', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => 'IGQVJxxxxxxxxxx',
				'description' => esc_html__( 'Enter your Instagram Access Token', 'mn-elements' ),
				'condition' => [
					'feed_source' => 'api',
				],
			]
		);

		$this->add_control(
			'api_limit',
			[
				'label' => esc_html__( 'Number of Posts', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 9,
				'min' => 1,
				'max' => 25,
				'condition' => [
					'feed_source' => 'api',
				],
			]
		);

		$this->add_control(
			'cache_duration',
			[
				'label' => esc_html__( 'Cache Duration (hours)', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 6,
				'min' => 1,
				'max' => 48,
				'description' => esc_html__( 'How long to cache Instagram data', 'mn-elements' ),
				'condition' => [
					'feed_source' => 'api',
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
			'layout',
			[
				'label' => esc_html__( 'Layout', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'grid',
				'options' => [
					'grid' => esc_html__( 'Grid', 'mn-elements' ),
					'masonry' => esc_html__( 'Masonry', 'mn-elements' ),
					'carousel' => esc_html__( 'Carousel', 'mn-elements' ),
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
					'layout!' => 'carousel',
				],
			]
		);

		$this->add_responsive_control(
			'gap',
			[
				'label' => esc_html__( 'Gap', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-instafeed-grid' => 'gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-instafeed-masonry' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'image_ratio',
			[
				'label' => esc_html__( 'Image Ratio', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => '1-1',
				'options' => [
					'1-1' => '1:1 (Square)',
					'4-3' => '4:3 (Landscape)',
					'3-4' => '3:4 (Portrait)',
					'16-9' => '16:9 (Wide)',
					'original' => 'Original',
				],
			]
		);

		// Carousel Settings
		$this->add_control(
			'carousel_heading',
			[
				'label' => esc_html__( 'Carousel Settings', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'layout' => 'carousel',
				],
			]
		);

		$this->add_responsive_control(
			'slides_to_show',
			[
				'label' => esc_html__( 'Slides to Show', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 3,
				'tablet_default' => 2,
				'mobile_default' => 1,
				'min' => 1,
				'max' => 6,
				'condition' => [
					'layout' => 'carousel',
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
					'layout' => 'carousel',
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
					'layout' => 'carousel',
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
					'layout' => 'carousel',
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
					'layout' => 'carousel',
				],
			]
		);

		$this->end_controls_section();

		// Content Options
		$this->start_controls_section(
			'section_content_options',
			[
				'label' => esc_html__( 'Content Options', 'mn-elements' ),
			]
		);

		$this->add_control(
			'show_caption',
			[
				'label' => esc_html__( 'Show Caption', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'caption_length',
			[
				'label' => esc_html__( 'Caption Length', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 100,
				'min' => 10,
				'max' => 500,
				'condition' => [
					'show_caption' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_likes',
			[
				'label' => esc_html__( 'Show Likes', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_comments',
			[
				'label' => esc_html__( 'Show Comments', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_overlay',
			[
				'label' => esc_html__( 'Show Hover Overlay', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->end_controls_section();

		$this->register_style_controls();
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
			'container_width',
			[
				'label' => esc_html__( 'Width', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vw' ],
				'range' => [
					'px' => [
						'min' => 200,
						'max' => 2000,
						'step' => 10,
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
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-instafeed-wrapper' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'container_max_width',
			[
				'label' => esc_html__( 'Max Width', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vw' ],
				'range' => [
					'px' => [
						'min' => 200,
						'max' => 2000,
						'step' => 10,
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
				'selectors' => [
					'{{WRAPPER}} .mn-instafeed-wrapper' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'container_alignment',
			[
				'label' => esc_html__( 'Alignment', 'mn-elements' ),
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
				'selectors_dictionary' => [
					'left' => 'margin-left: 0; margin-right: auto;',
					'center' => 'margin-left: auto; margin-right: auto;',
					'right' => 'margin-left: auto; margin-right: 0;',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-instafeed-wrapper' => '{{VALUE}}',
				],
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
			Group_Control_Border::get_type(),
			[
				'name' => 'item_border',
				'selector' => '{{WRAPPER}} .mn-instafeed-item',
			]
		);

		$this->add_responsive_control(
			'item_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-instafeed-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .mn-instafeed-item img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_box_shadow',
				'selector' => '{{WRAPPER}} .mn-instafeed-item',
			]
		);

		$this->end_controls_section();

		// Overlay Style
		$this->start_controls_section(
			'section_overlay_style',
			[
				'label' => esc_html__( 'Overlay', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_overlay' => 'yes',
				],
			]
		);

		$this->add_control(
			'overlay_background',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0,0.7)',
				'selectors' => [
					'{{WRAPPER}} .mn-instafeed-overlay' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'overlay_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-instafeed-stats i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'overlay_text_color',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-instafeed-overlay' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'overlay_typography',
				'selector' => '{{WRAPPER}} .mn-instafeed-caption',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$feed_source = $settings['feed_source'];
		$layout = $settings['layout'];

		$posts = [];

		if ( $feed_source === 'manual' ) {
			$posts = isset( $settings['manual_feed'] ) ? $settings['manual_feed'] : [];
		} elseif ( $feed_source === 'api' ) {
			$posts = $this->get_instagram_posts( $settings );
		}

		// Check if posts is array and not empty
		if ( ! is_array( $posts ) || empty( $posts ) ) {
			echo '<div class="mn-instafeed-error">' . esc_html__( 'No posts to display. Please add posts or configure Instagram API.', 'mn-elements' ) . '</div>';
			return;
		}

		$wrapper_class = 'mn-instafeed-' . $layout;
		$columns = $settings['columns'] ?? 3;
		?>
		<div class="mn-instafeed-wrapper" data-layout="<?php echo esc_attr( $layout ); ?>">
			<div class="<?php echo esc_attr( $wrapper_class ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>">
				<?php foreach ( $posts as $post ) : ?>
					<?php $this->render_post_item( $post, $settings ); ?>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}

	protected function render_post_item( $post, $settings ) {
		// Handle image URL for both manual feed and API
		$image_url = '';
		
		// Check if image field exists and extract URL
		if ( isset( $post['image'] ) ) {
			// If it's an array with 'url' key (Elementor media control format)
			if ( is_array( $post['image'] ) && isset( $post['image']['url'] ) ) {
				$image_url = $post['image']['url'];
			} 
			// If it's an array with 'id' key, get URL from attachment
			elseif ( is_array( $post['image'] ) && isset( $post['image']['id'] ) ) {
				$image_url = wp_get_attachment_url( $post['image']['id'] );
			}
			// If it's just a string URL
			elseif ( is_string( $post['image'] ) ) {
				$image_url = $post['image'];
			}
		} 
		// Fallback for API feed
		elseif ( isset( $post['media_url'] ) ) {
			$image_url = $post['media_url'];
		}
		
		// Handle link URL for both manual feed and API
		$link = '#';
		if ( isset( $post['link'] ) ) {
			if ( is_array( $post['link'] ) && isset( $post['link']['url'] ) ) {
				$link = $post['link']['url'];
			} elseif ( is_string( $post['link'] ) ) {
				$link = $post['link'];
			}
		} elseif ( isset( $post['permalink'] ) ) {
			$link = $post['permalink'];
		}
		
		$caption = $post['caption'] ?? '';
		$likes = $post['likes'] ?? $post['like_count'] ?? 0;
		$comments = $post['comments'] ?? $post['comments_count'] ?? 0;

		// Skip if no image URL
		if ( empty( $image_url ) ) {
			return;
		}

		$ratio_class = 'ratio-' . str_replace( '-', '-', $settings['image_ratio'] );
		?>
		<div class="mn-instafeed-item <?php echo esc_attr( $ratio_class ); ?>">
			<a href="<?php echo esc_url( $link ); ?>" target="_blank" rel="noopener noreferrer" class="mn-instafeed-link">
				<div class="mn-instafeed-image">
					<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( wp_trim_words( $caption, 5 ) ); ?>">
				</div>
				
				<?php if ( $settings['show_overlay'] === 'yes' ) : ?>
					<div class="mn-instafeed-overlay">
						<div class="mn-instafeed-content">
							<?php if ( $settings['show_caption'] === 'yes' && ! empty( $caption ) ) : ?>
								<div class="mn-instafeed-caption">
									<?php echo esc_html( wp_trim_words( $caption, $settings['caption_length'] / 5 ) ); ?>
								</div>
							<?php endif; ?>
							
							<?php if ( $settings['show_likes'] === 'yes' || $settings['show_comments'] === 'yes' ) : ?>
								<div class="mn-instafeed-stats">
									<?php if ( $settings['show_likes'] === 'yes' ) : ?>
										<span class="mn-instafeed-likes">
											<i class="fas fa-heart"></i>
											<?php echo esc_html( $this->format_number( $likes ) ); ?>
										</span>
									<?php endif; ?>
									
									<?php if ( $settings['show_comments'] === 'yes' ) : ?>
										<span class="mn-instafeed-comments">
											<i class="fas fa-comment"></i>
											<?php echo esc_html( $this->format_number( $comments ) ); ?>
										</span>
									<?php endif; ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				<?php endif; ?>
			</a>
		</div>
		<?php
	}

	protected function get_instagram_posts( $settings ) {
		$access_token = $settings['access_token'];
		$limit = $settings['api_limit'] ?? 9;
		$cache_duration = $settings['cache_duration'] ?? 6;

		if ( empty( $access_token ) ) {
			return [];
		}

		$cache_key = 'mn_instafeed_' . md5( $access_token );
		$cached_data = get_transient( $cache_key );

		if ( false !== $cached_data ) {
			return $cached_data;
		}

		$url = 'https://graph.instagram.com/me/media?fields=id,caption,media_type,media_url,permalink,thumbnail_url,timestamp,like_count,comments_count&access_token=' . $access_token . '&limit=' . $limit;

		$response = wp_remote_get( $url );

		if ( is_wp_error( $response ) ) {
			return [];
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		if ( empty( $data['data'] ) ) {
			return [];
		}

		set_transient( $cache_key, $data['data'], $cache_duration * HOUR_IN_SECONDS );

		return $data['data'];
	}

	protected function format_number( $number ) {
		if ( $number >= 1000000 ) {
			return round( $number / 1000000, 1 ) . 'M';
		} elseif ( $number >= 1000 ) {
			return round( $number / 1000, 1 ) . 'K';
		}
		return $number;
	}
}
