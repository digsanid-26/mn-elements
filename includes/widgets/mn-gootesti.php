<?php
namespace MN_Elements\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * MN Gootesti Widget
 * Display Google Business Reviews and Testimonials
 */
class MN_Gootesti extends Widget_Base {

	public function get_name() {
		return 'mn-gootesti';
	}

	public function get_title() {
		return esc_html__( 'MN Gootesti', 'mn-elements' );
	}

	public function get_icon() {
		return 'eicon-testimonial';
	}

	public function get_categories() {
		return [ 'mn-elements' ];
	}

	public function get_keywords() {
		return [ 'google', 'review', 'testimonial', 'rating', 'business', 'places' ];
	}

	public function get_script_depends() {
		return [ 'mn-gootesti' ];
	}

	public function get_style_depends() {
		return [ 'mn-gootesti' ];
	}

	protected function register_controls() {
		$this->register_source_controls();
		$this->register_display_controls();
		$this->register_slider_controls();
		$this->register_style_controls();
	}

	/**
	 * Register source controls
	 */
	private function register_source_controls() {
		$this->start_controls_section(
			'section_source',
			[
				'label' => esc_html__( 'Review Source', 'mn-elements' ),
			]
		);

		$this->add_control(
			'review_source',
			[
				'label' => esc_html__( 'Source', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'manual',
				'options' => [
					'google' => esc_html__( 'Google Business', 'mn-elements' ),
					'manual' => esc_html__( 'Manual Input', 'mn-elements' ),
				],
			]
		);

		// Google API Settings
		$this->add_control(
			'google_api_heading',
			[
				'label' => esc_html__( 'Google API Settings', 'mn-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'review_source' => 'google',
				],
			]
		);

		$this->add_control(
			'google_api_key',
			[
				'label' => esc_html__( 'Google API Key', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => 'AIzaSyD...',
				'description' => esc_html__( 'Get API key from Google Cloud Console. Enable Places API.', 'mn-elements' ),
				'condition' => [
					'review_source' => 'google',
				],
			]
		);

		$this->add_control(
			'google_place_id',
			[
				'label' => esc_html__( 'Place ID', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => 'ChIJ...',
				'description' => esc_html__( 'Find your Place ID using Google Place ID Finder.', 'mn-elements' ),
				'condition' => [
					'review_source' => 'google',
				],
			]
		);

		$this->add_control(
			'cache_duration',
			[
				'label' => esc_html__( 'Cache Duration (hours)', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 24,
				'min' => 1,
				'max' => 168,
				'description' => esc_html__( 'Cache reviews to reduce API calls and costs.', 'mn-elements' ),
				'condition' => [
					'review_source' => 'google',
				],
			]
		);

		$this->add_control(
			'min_rating',
			[
				'label' => esc_html__( 'Minimum Rating', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 4,
				'min' => 1,
				'max' => 5,
				'step' => 0.5,
				'description' => esc_html__( 'Only show reviews with this rating or higher.', 'mn-elements' ),
				'condition' => [
					'review_source' => 'google',
				],
			]
		);

		// Manual Reviews
		$repeater = new Repeater();

		$repeater->add_control(
			'reviewer_name',
			[
				'label' => esc_html__( 'Reviewer Name', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'John Doe', 'mn-elements' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'reviewer_avatar',
			[
				'label' => esc_html__( 'Avatar', 'mn-elements' ),
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
			'rating',
			[
				'label' => esc_html__( 'Rating', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5,
				'min' => 1,
				'max' => 5,
				'step' => 0.5,
			]
		);

		$repeater->add_control(
			'review_text',
			[
				'label' => esc_html__( 'Review Text', 'mn-elements' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Great service and excellent quality!', 'mn-elements' ),
				'rows' => 5,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'review_date',
			[
				'label' => esc_html__( 'Review Date', 'mn-elements' ),
				'type' => Controls_Manager::DATE_TIME,
				'default' => date( 'Y-m-d H:i' ),
			]
		);

		$this->add_control(
			'manual_reviews',
			[
				'label' => esc_html__( 'Reviews', 'mn-elements' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'reviewer_name' => esc_html__( 'John Doe', 'mn-elements' ),
						'rating' => 5,
						'review_text' => esc_html__( 'Excellent service! Highly recommended.', 'mn-elements' ),
					],
					[
						'reviewer_name' => esc_html__( 'Jane Smith', 'mn-elements' ),
						'rating' => 5,
						'review_text' => esc_html__( 'Very professional and friendly staff.', 'mn-elements' ),
					],
					[
						'reviewer_name' => esc_html__( 'Mike Johnson', 'mn-elements' ),
						'rating' => 5,
						'review_text' => esc_html__( 'Amazing experience from start to finish!', 'mn-elements' ),
					],
				],
				'title_field' => '{{{ reviewer_name }}} - {{{ rating }}} ★',
				'condition' => [
					'review_source' => 'manual',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register display controls
	 */
	private function register_display_controls() {
		$this->start_controls_section(
			'section_display',
			[
				'label' => esc_html__( 'Display Settings', 'mn-elements' ),
			]
		);

		$this->add_control(
			'layout_type',
			[
				'label' => esc_html__( 'Layout', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'grid',
				'options' => [
					'grid' => esc_html__( 'Grid', 'mn-elements' ),
					'list' => esc_html__( 'List', 'mn-elements' ),
					'slider' => esc_html__( 'Slider', 'mn-elements' ),
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
				],
				'condition' => [
					'layout_type' => 'grid',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-gootesti-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				],
			]
		);

		$this->add_control(
			'max_reviews',
			[
				'label' => esc_html__( 'Maximum Reviews', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 6,
				'min' => 1,
				'max' => 50,
			]
		);

		$this->add_control(
			'show_avatar',
			[
				'label' => esc_html__( 'Show Avatar', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_name',
			[
				'label' => esc_html__( 'Show Name', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_rating',
			[
				'label' => esc_html__( 'Show Rating', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
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
				'type' => Controls_Manager::SELECT,
				'default' => 'relative',
				'options' => [
					'relative' => esc_html__( 'Relative (2 days ago)', 'mn-elements' ),
					'date' => esc_html__( 'Date (Jan 15, 2024)', 'mn-elements' ),
					'full' => esc_html__( 'Full (January 15, 2024)', 'mn-elements' ),
				],
				'condition' => [
					'show_date' => 'yes',
				],
			]
		);

		$this->add_control(
			'excerpt_length',
			[
				'label' => esc_html__( 'Review Excerpt Length', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 150,
				'min' => 0,
				'max' => 500,
				'description' => esc_html__( 'Number of characters. 0 for full text.', 'mn-elements' ),
			]
		);

		$this->add_control(
			'show_read_more',
			[
				'label' => esc_html__( 'Show Read More', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'excerpt_length!' => 0,
				],
			]
		);

		$this->add_control(
			'theme_version',
			[
				'label' => esc_html__( 'Theme', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'light',
				'options' => [
					'light' => esc_html__( 'Light', 'mn-elements' ),
					'dark' => esc_html__( 'Dark', 'mn-elements' ),
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register slider controls
	 */
	private function register_slider_controls() {
		$this->start_controls_section(
			'section_slider',
			[
				'label' => esc_html__( 'Slider Settings', 'mn-elements' ),
				'condition' => [
					'layout_type' => 'slider',
				],
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label' => esc_html__( 'Autoplay', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
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
			'show_arrows',
			[
				'label' => esc_html__( 'Show Arrows', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_dots',
			[
				'label' => esc_html__( 'Show Dots', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register style controls
	 */
	private function register_style_controls() {
		// Review Card Style
		$this->start_controls_section(
			'section_card_style',
			[
				'label' => esc_html__( 'Review Card', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'card_gap',
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
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-gootesti-grid' => 'gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-gootesti-list .mn-review-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'card_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'top' => 25,
					'right' => 25,
					'bottom' => 25,
					'left' => 25,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-review-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'card_border',
				'selector' => '{{WRAPPER}} .mn-review-item',
			]
		);

		$this->add_responsive_control(
			'card_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-review-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'card_box_shadow',
				'selector' => '{{WRAPPER}} .mn-review-item',
			]
		);

		$this->end_controls_section();

		// Avatar Style
		$this->start_controls_section(
			'section_avatar_style',
			[
				'label' => esc_html__( 'Avatar', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_avatar' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'avatar_size',
			[
				'label' => esc_html__( 'Size', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 30,
						'max' => 150,
					],
				],
				'default' => [
					'size' => 60,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-reviewer-avatar' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'avatar_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 50,
					'right' => 50,
					'bottom' => 50,
					'left' => 50,
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-reviewer-avatar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Name Style
		$this->start_controls_section(
			'section_name_style',
			[
				'label' => esc_html__( 'Name', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_name' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'name_typography',
				'selector' => '{{WRAPPER}} .mn-reviewer-name',
			]
		);

		$this->add_control(
			'name_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-reviewer-name' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Rating Style
		$this->start_controls_section(
			'section_rating_style',
			[
				'label' => esc_html__( 'Rating', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_rating' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'star_size',
			[
				'label' => esc_html__( 'Star Size', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 50,
					],
				],
				'default' => [
					'size' => 16,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-rating-stars' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'star_color',
			[
				'label' => esc_html__( 'Star Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFB800',
				'selectors' => [
					'{{WRAPPER}} .mn-rating-stars' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Review Text Style
		$this->start_controls_section(
			'section_text_style',
			[
				'label' => esc_html__( 'Review Text', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'selector' => '{{WRAPPER}} .mn-review-text',
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-review-text' => 'color: {{VALUE}};',
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
				'condition' => [
					'show_date' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'date_typography',
				'selector' => '{{WRAPPER}} .mn-review-date',
			]
		);

		$this->add_control(
			'date_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-review-date' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Get reviews from Google Places API
	 */
	private function get_google_reviews( $settings ) {
		$api_key = isset( $settings['google_api_key'] ) ? $settings['google_api_key'] : '';
		$place_id = isset( $settings['google_place_id'] ) ? $settings['google_place_id'] : '';
		$cache_duration = isset( $settings['cache_duration'] ) ? intval( $settings['cache_duration'] ) : 24;

		if ( empty( $api_key ) || empty( $place_id ) ) {
			return [];
		}

		// Check cache
		$cache_key = 'mn_gootesti_' . md5( $place_id );
		$cached_reviews = get_transient( $cache_key );

		if ( false !== $cached_reviews && is_array( $cached_reviews ) ) {
			return $cached_reviews;
		}

		// Fetch from Google API
		$url = add_query_arg(
			[
				'place_id' => $place_id,
				'key' => $api_key,
				'fields' => 'reviews',
			],
			'https://maps.googleapis.com/maps/api/place/details/json'
		);

		$response = wp_remote_get( $url, [ 'timeout' => 15 ] );

		if ( is_wp_error( $response ) ) {
			return [];
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		if ( empty( $data['result']['reviews'] ) || ! is_array( $data['result']['reviews'] ) ) {
			return [];
		}

		$reviews = [];
		foreach ( $data['result']['reviews'] as $review ) {
			$reviews[] = [
				'author_name' => isset( $review['author_name'] ) ? $review['author_name'] : '',
				'author_photo' => isset( $review['profile_photo_url'] ) ? $review['profile_photo_url'] : '',
				'rating' => isset( $review['rating'] ) ? floatval( $review['rating'] ) : 5,
				'text' => isset( $review['text'] ) ? $review['text'] : '',
				'time' => isset( $review['time'] ) ? $review['time'] : time(),
			];
		}

		// Cache the results
		set_transient( $cache_key, $reviews, $cache_duration * HOUR_IN_SECONDS );

		return $reviews;
	}

	/**
	 * Get reviews based on source
	 */
	private function get_reviews( $settings ) {
		$reviews = [];

		if ( $settings['review_source'] === 'google' ) {
			$reviews = $this->get_google_reviews( $settings );
		} else {
			// Manual reviews
			if ( ! empty( $settings['manual_reviews'] ) ) {
				foreach ( $settings['manual_reviews'] as $review ) {
					$reviews[] = [
						'author_name' => isset( $review['reviewer_name'] ) ? $review['reviewer_name'] : '',
						'author_photo' => isset( $review['reviewer_avatar']['url'] ) ? $review['reviewer_avatar']['url'] : '',
						'rating' => isset( $review['rating'] ) ? floatval( $review['rating'] ) : 5,
						'text' => isset( $review['review_text'] ) ? $review['review_text'] : '',
						'time' => isset( $review['review_date'] ) ? strtotime( $review['review_date'] ) : time(),
					];
				}
			}
		}

		// Filter by minimum rating (Google only)
		if ( $settings['review_source'] === 'google' && isset( $settings['min_rating'] ) ) {
			$min_rating = floatval( $settings['min_rating'] );
			$reviews = array_filter( $reviews, function( $review ) use ( $min_rating ) {
				return isset( $review['rating'] ) && $review['rating'] >= $min_rating;
			});
		}

		// Limit reviews
		$max_reviews = isset( $settings['max_reviews'] ) ? intval( $settings['max_reviews'] ) : 6;
		$reviews = array_slice( $reviews, 0, $max_reviews );

		return $reviews;
	}

	/**
	 * Format date
	 */
	private function format_date( $timestamp, $format ) {
		if ( empty( $timestamp ) ) {
			return '';
		}

		switch ( $format ) {
			case 'relative':
				return human_time_diff( $timestamp, current_time( 'timestamp' ) ) . ' ' . esc_html__( 'ago', 'mn-elements' );
			case 'date':
				return date_i18n( 'M j, Y', $timestamp );
			case 'full':
				return date_i18n( 'F j, Y', $timestamp );
			default:
				return date_i18n( get_option( 'date_format' ), $timestamp );
		}
	}

	/**
	 * Render stars
	 */
	private function render_stars( $rating ) {
		$rating = floatval( $rating );
		$full_stars = floor( $rating );
		$half_star = ( $rating - $full_stars ) >= 0.5;
		$empty_stars = 5 - $full_stars - ( $half_star ? 1 : 0 );

		$output = '<div class="mn-rating-stars">';
		
		// Full stars
		for ( $i = 0; $i < $full_stars; $i++ ) {
			$output .= '<i class="eicon-star"></i>';
		}
		
		// Half star
		if ( $half_star ) {
			$output .= '<i class="eicon-star-half"></i>';
		}
		
		// Empty stars
		for ( $i = 0; $i < $empty_stars; $i++ ) {
			$output .= '<i class="eicon-star-o"></i>';
		}
		
		$output .= '</div>';
		
		return $output;
	}

	/**
	 * Render single review
	 */
	private function render_review( $review, $settings ) {
		$excerpt_length = isset( $settings['excerpt_length'] ) ? intval( $settings['excerpt_length'] ) : 150;
		$review_text = isset( $review['text'] ) ? $review['text'] : '';
		
		// Truncate text if needed
		$is_truncated = false;
		if ( $excerpt_length > 0 && mb_strlen( $review_text ) > $excerpt_length ) {
			$review_text = mb_substr( $review_text, 0, $excerpt_length ) . '...';
			$is_truncated = true;
		}
		?>
		<div class="mn-review-item">
			<div class="mn-review-header">
				<?php if ( $settings['show_avatar'] === 'yes' && ! empty( $review['author_photo'] ) ) : ?>
					<div class="mn-reviewer-avatar">
						<img src="<?php echo esc_url( $review['author_photo'] ); ?>" alt="<?php echo esc_attr( $review['author_name'] ); ?>">
					</div>
				<?php endif; ?>
				
				<div class="mn-reviewer-info">
					<?php if ( $settings['show_name'] === 'yes' && ! empty( $review['author_name'] ) ) : ?>
						<div class="mn-reviewer-name"><?php echo esc_html( $review['author_name'] ); ?></div>
					<?php endif; ?>
					
					<?php if ( $settings['show_rating'] === 'yes' ) : ?>
						<?php echo $this->render_stars( $review['rating'] ); ?>
					<?php endif; ?>
				</div>
			</div>
			
			<?php if ( ! empty( $review_text ) ) : ?>
				<div class="mn-review-text"><?php echo esc_html( $review_text ); ?></div>
				<?php if ( $is_truncated && $settings['show_read_more'] === 'yes' ) : ?>
					<div class="mn-review-read-more"><?php esc_html_e( 'Read more', 'mn-elements' ); ?></div>
				<?php endif; ?>
			<?php endif; ?>
			
			<?php if ( $settings['show_date'] === 'yes' && ! empty( $review['time'] ) ) : ?>
				<div class="mn-review-date">
					<?php echo esc_html( $this->format_date( $review['time'], $settings['date_format'] ) ); ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Render widget output
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$reviews = $this->get_reviews( $settings );

		if ( empty( $reviews ) ) {
			echo '<div class="mn-gootesti-empty">';
			if ( $settings['review_source'] === 'google' ) {
				esc_html_e( 'No reviews found. Please check your API Key and Place ID.', 'mn-elements' );
			} else {
				esc_html_e( 'No reviews added yet.', 'mn-elements' );
			}
			echo '</div>';
			return;
		}

		$layout_class = 'mn-gootesti-' . $settings['layout_type'];
		$theme_class = 'mn-theme-' . $settings['theme_version'];
		
		$wrapper_classes = [
			'mn-gootesti-wrapper',
			$theme_class,
		];

		?>
		<div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>">
			<?php if ( $settings['layout_type'] === 'slider' ) : ?>
				<div class="mn-gootesti-slider" 
					 data-autoplay="<?php echo esc_attr( $settings['autoplay'] ); ?>"
					 data-autoplay-speed="<?php echo esc_attr( $settings['autoplay_speed'] ); ?>"
					 data-show-arrows="<?php echo esc_attr( $settings['show_arrows'] ); ?>"
					 data-show-dots="<?php echo esc_attr( $settings['show_dots'] ); ?>">
					<div class="mn-gootesti-slides">
						<?php foreach ( $reviews as $review ) : ?>
							<div class="mn-gootesti-slide">
								<?php $this->render_review( $review, $settings ); ?>
							</div>
						<?php endforeach; ?>
					</div>
					
					<?php if ( $settings['show_arrows'] === 'yes' ) : ?>
						<button class="mn-slider-arrow mn-slider-prev"><i class="eicon-chevron-left"></i></button>
						<button class="mn-slider-arrow mn-slider-next"><i class="eicon-chevron-right"></i></button>
					<?php endif; ?>
					
					<?php if ( $settings['show_dots'] === 'yes' ) : ?>
						<div class="mn-slider-dots"></div>
					<?php endif; ?>
				</div>
			<?php else : ?>
				<div class="<?php echo esc_attr( $layout_class ); ?>">
					<?php foreach ( $reviews as $review ) : ?>
						<?php $this->render_review( $review, $settings ); ?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}
}