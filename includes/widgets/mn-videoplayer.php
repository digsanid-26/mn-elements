<?php
namespace MN_Elements\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Utils;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * MN Video Player Widget
 *
 * Single video player supporting YouTube, Vimeo, and self-hosted (HTML5) sources.
 * Supports inline player and modal/popup playback with customizable play button.
 *
 * @since 3.2.0
 */
class MN_Video_Player extends Widget_Base {

	public function get_name() {
		return 'mn-videoplayer';
	}

	public function get_title() {
		return esc_html__( 'MN Video Player', 'mn-elements' );
	}

	public function get_icon() {
		return 'eicon-play';
	}

	public function get_categories() {
		return [ 'mn-elements' ];
	}

	public function get_keywords() {
		return [ 'video', 'player', 'youtube', 'vimeo', 'html5', 'popup', 'modal', 'lightbox' ];
	}

	public function get_style_depends() {
		return [ 'mn-videoplayer-style' ];
	}

	public function get_script_depends() {
		return [ 'mn-videoplayer-script' ];
	}

	protected function register_controls() {
		$this->register_content_controls();
		$this->register_style_controls();
	}

	/* ───────────────────────────────
	 *  CONTENT CONTROLS
	 * ─────────────────────────────── */
	protected function register_content_controls() {

		/* ── Video Source ── */
		$this->start_controls_section( 'section_video', [
			'label' => esc_html__( 'Video', 'mn-elements' ),
		] );

		$this->add_control( 'video_source', [
			'label'   => esc_html__( 'Source', 'mn-elements' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'youtube',
			'options' => [
				'youtube'      => esc_html__( 'YouTube', 'mn-elements' ),
				'vimeo'        => esc_html__( 'Vimeo', 'mn-elements' ),
				'self_hosted'  => esc_html__( 'Self Hosted / HTML5', 'mn-elements' ),
			],
		] );

		$this->add_control( 'youtube_url', [
			'label'       => esc_html__( 'YouTube URL', 'mn-elements' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
			'placeholder' => 'https://www.youtube.com/watch?v=...',
			'label_block' => true,
			'condition'   => [ 'video_source' => 'youtube' ],
		] );

		$this->add_control( 'vimeo_url', [
			'label'       => esc_html__( 'Vimeo URL', 'mn-elements' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => 'https://vimeo.com/235215203',
			'placeholder' => 'https://vimeo.com/...',
			'label_block' => true,
			'condition'   => [ 'video_source' => 'vimeo' ],
		] );

		$this->add_control( 'hosted_url', [
			'label'      => esc_html__( 'Video File', 'mn-elements' ),
			'type'       => Controls_Manager::MEDIA,
			'media_types' => [ 'video' ],
			'condition'  => [ 'video_source' => 'self_hosted' ],
		] );

		$this->add_control( 'external_url', [
			'label'       => esc_html__( 'Or External URL', 'mn-elements' ),
			'type'        => Controls_Manager::URL,
			'placeholder' => 'https://example.com/video.mp4',
			'label_block' => true,
			'condition'   => [ 'video_source' => 'self_hosted' ],
		] );

		$this->add_control( 'start_time', [
			'label'       => esc_html__( 'Start Time (seconds)', 'mn-elements' ),
			'type'        => Controls_Manager::NUMBER,
			'default'     => '',
			'description' => esc_html__( 'Specify a start time in seconds.', 'mn-elements' ),
		] );

		$this->add_control( 'end_time', [
			'label'       => esc_html__( 'End Time (seconds)', 'mn-elements' ),
			'type'        => Controls_Manager::NUMBER,
			'default'     => '',
			'description' => esc_html__( 'YouTube only. Specify an end time in seconds.', 'mn-elements' ),
			'condition'   => [ 'video_source' => 'youtube' ],
		] );

		$this->end_controls_section();

		/* ── Video Options ── */
		$this->start_controls_section( 'section_video_options', [
			'label' => esc_html__( 'Video Options', 'mn-elements' ),
		] );

		$this->add_control( 'autoplay', [
			'label'   => esc_html__( 'Autoplay', 'mn-elements' ),
			'type'    => Controls_Manager::SWITCHER,
			'default' => '',
		] );

		$this->add_control( 'mute', [
			'label'   => esc_html__( 'Mute', 'mn-elements' ),
			'type'    => Controls_Manager::SWITCHER,
			'default' => '',
		] );

		$this->add_control( 'loop', [
			'label'   => esc_html__( 'Loop', 'mn-elements' ),
			'type'    => Controls_Manager::SWITCHER,
			'default' => '',
		] );

		$this->add_control( 'player_controls', [
			'label'   => esc_html__( 'Player Controls', 'mn-elements' ),
			'type'    => Controls_Manager::SWITCHER,
			'default' => 'yes',
		] );

		$this->add_control( 'modest_branding', [
			'label'     => esc_html__( 'Modest Branding', 'mn-elements' ),
			'type'      => Controls_Manager::SWITCHER,
			'default'   => '',
			'description' => esc_html__( 'Hide YouTube logo.', 'mn-elements' ),
			'condition' => [ 'video_source' => 'youtube' ],
		] );

		$this->add_control( 'privacy_mode', [
			'label'     => esc_html__( 'Privacy Mode', 'mn-elements' ),
			'type'      => Controls_Manager::SWITCHER,
			'default'   => '',
			'description' => esc_html__( 'Use youtube-nocookie.com for enhanced privacy.', 'mn-elements' ),
			'condition' => [ 'video_source' => 'youtube' ],
		] );

		$this->add_control( 'rel', [
			'label'     => esc_html__( 'Suggested Videos', 'mn-elements' ),
			'type'      => Controls_Manager::SWITCHER,
			'default'   => '',
			'description' => esc_html__( 'Show related videos at the end.', 'mn-elements' ),
			'condition' => [ 'video_source' => 'youtube' ],
		] );

		$this->add_control( 'vimeo_title', [
			'label'     => esc_html__( 'Intro Title', 'mn-elements' ),
			'type'      => Controls_Manager::SWITCHER,
			'default'   => 'yes',
			'condition' => [ 'video_source' => 'vimeo' ],
		] );

		$this->add_control( 'vimeo_portrait', [
			'label'     => esc_html__( 'Intro Portrait', 'mn-elements' ),
			'type'      => Controls_Manager::SWITCHER,
			'default'   => 'yes',
			'condition' => [ 'video_source' => 'vimeo' ],
		] );

		$this->add_control( 'vimeo_byline', [
			'label'     => esc_html__( 'Intro Byline', 'mn-elements' ),
			'type'      => Controls_Manager::SWITCHER,
			'default'   => 'yes',
			'condition' => [ 'video_source' => 'vimeo' ],
		] );

		$this->add_control( 'download_button', [
			'label'     => esc_html__( 'Download Button', 'mn-elements' ),
			'type'      => Controls_Manager::SWITCHER,
			'default'   => '',
			'condition' => [ 'video_source' => 'self_hosted' ],
		] );

		$this->add_control( 'poster', [
			'label'     => esc_html__( 'Poster Image', 'mn-elements' ),
			'type'      => Controls_Manager::MEDIA,
			'condition' => [ 'video_source' => 'self_hosted' ],
		] );

		$this->end_controls_section();

		/* ── Display Mode ── */
		$this->start_controls_section( 'section_display', [
			'label' => esc_html__( 'Display Mode', 'mn-elements' ),
		] );

		$this->add_control( 'display_mode', [
			'label'   => esc_html__( 'Display Mode', 'mn-elements' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'inline',
			'options' => [
				'inline' => esc_html__( 'Inline Player', 'mn-elements' ),
				'modal'  => esc_html__( 'Modal / Popup', 'mn-elements' ),
			],
		] );

		$this->add_responsive_control( 'aspect_ratio', [
			'label'   => esc_html__( 'Aspect Ratio', 'mn-elements' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '16-9',
			'options' => [
				'16-9' => '16:9',
				'4-3'  => '4:3',
				'21-9' => '21:9',
				'1-1'  => '1:1',
				'9-16' => '9:16',
			],
			'condition' => [ 'display_mode' => 'inline' ],
		] );

		$this->add_control( 'lazy_load', [
			'label'     => esc_html__( 'Lazy Load (Thumbnail First)', 'mn-elements' ),
			'type'      => Controls_Manager::SWITCHER,
			'default'   => '',
			'description' => esc_html__( 'Show a thumbnail with play button. Video loads on click.', 'mn-elements' ),
			'condition' => [ 'display_mode' => 'inline' ],
		] );

		$this->add_control( 'modal_trigger_type', [
			'label'   => esc_html__( 'Trigger Type', 'mn-elements' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'icon_box',
			'options' => [
				'icon_box'  => esc_html__( 'Icon Box', 'mn-elements' ),
				'thumbnail' => esc_html__( 'Thumbnail', 'mn-elements' ),
			],
			'condition' => [ 'display_mode' => 'modal' ],
		] );

		$this->add_control( 'trigger_shape', [
			'label'   => esc_html__( 'Shape', 'mn-elements' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'square',
			'options' => [
				'square' => esc_html__( 'Square', 'mn-elements' ),
				'circle' => esc_html__( 'Circle', 'mn-elements' ),
			],
			'condition' => [
				'display_mode'       => 'modal',
				'modal_trigger_type' => 'icon_box',
			],
		] );

		$this->add_control( 'custom_thumbnail', [
			'label'     => esc_html__( 'Custom Thumbnail', 'mn-elements' ),
			'type'      => Controls_Manager::MEDIA,
			'description' => esc_html__( 'Override the default video thumbnail.', 'mn-elements' ),
			'conditions' => [
				'relation' => 'or',
				'terms' => [
					[
						'relation' => 'and',
						'terms' => [
							[ 'name' => 'display_mode', 'value' => 'modal' ],
							[ 'name' => 'modal_trigger_type', 'value' => 'thumbnail' ],
						],
					],
					[
						'relation' => 'and',
						'terms' => [
							[ 'name' => 'display_mode', 'value' => 'inline' ],
							[ 'name' => 'lazy_load', 'value' => 'yes' ],
						],
					],
				],
			],
		] );

		$this->end_controls_section();

		/* ── Play Button / Trigger ── */
		$this->start_controls_section( 'section_play_button', [
			'label' => esc_html__( 'Play Button', 'mn-elements' ),
		] );

		$this->add_control( 'play_button_type', [
			'label'   => esc_html__( 'Type', 'mn-elements' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'icon',
			'options' => [
				'icon'   => esc_html__( 'Icon', 'mn-elements' ),
				'image'  => esc_html__( 'Image', 'mn-elements' ),
				'text'   => esc_html__( 'Text Button', 'mn-elements' ),
			],
		] );

		$this->add_control( 'play_icon', [
			'label'   => esc_html__( 'Icon', 'mn-elements' ),
			'type'    => Controls_Manager::ICONS,
			'default' => [
				'value'   => 'fas fa-play',
				'library' => 'fa-solid',
			],
			'condition' => [ 'play_button_type' => 'icon' ],
		] );

		$this->add_control( 'play_image', [
			'label'     => esc_html__( 'Image', 'mn-elements' ),
			'type'      => Controls_Manager::MEDIA,
			'condition' => [ 'play_button_type' => 'image' ],
		] );

		$this->add_control( 'play_text', [
			'label'     => esc_html__( 'Button Text', 'mn-elements' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => esc_html__( 'Play Video', 'mn-elements' ),
			'condition' => [ 'play_button_type' => 'text' ],
		] );

		$this->add_control( 'play_text_icon', [
			'label'   => esc_html__( 'Button Icon', 'mn-elements' ),
			'type'    => Controls_Manager::ICONS,
			'default' => [
				'value'   => 'fas fa-play',
				'library' => 'fa-solid',
			],
			'condition' => [ 'play_button_type' => 'text' ],
		] );

		$this->add_control( 'show_play_animation', [
			'label'   => esc_html__( 'Pulse Animation', 'mn-elements' ),
			'type'    => Controls_Manager::SWITCHER,
			'default' => 'yes',
			'condition' => [ 'play_button_type!' => 'text' ],
		] );

		$this->end_controls_section();

		/* ── Modal Settings ── */
		$this->start_controls_section( 'section_modal', [
			'label'     => esc_html__( 'Modal Settings', 'mn-elements' ),
			'condition' => [ 'display_mode' => 'modal' ],
		] );

		$this->add_responsive_control( 'modal_width', [
			'label'      => esc_html__( 'Modal Width', 'mn-elements' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%', 'vw' ],
			'range'      => [
				'px' => [ 'min' => 300, 'max' => 1920 ],
				'%'  => [ 'min' => 30, 'max' => 100 ],
				'vw' => [ 'min' => 30, 'max' => 100 ],
			],
			'default' => [ 'unit' => '%', 'size' => 80 ],
			'selectors' => [
				'{{WRAPPER}} .mn-vp-modal-content' => 'width: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'modal_aspect_ratio', [
			'label'   => esc_html__( 'Aspect Ratio', 'mn-elements' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '16-9',
			'options' => [
				'16-9' => '16:9',
				'4-3'  => '4:3',
				'21-9' => '21:9',
				'1-1'  => '1:1',
				'9-16' => '9:16',
			],
		] );

		$this->add_control( 'modal_close_on_bg', [
			'label'   => esc_html__( 'Close on Background Click', 'mn-elements' ),
			'type'    => Controls_Manager::SWITCHER,
			'default' => 'yes',
		] );

		$this->add_control( 'modal_close_on_esc', [
			'label'   => esc_html__( 'Close on ESC Key', 'mn-elements' ),
			'type'    => Controls_Manager::SWITCHER,
			'default' => 'yes',
		] );

		$this->add_control( 'show_close_button', [
			'label'   => esc_html__( 'Show Close Button', 'mn-elements' ),
			'type'    => Controls_Manager::SWITCHER,
			'default' => 'yes',
		] );

		$this->end_controls_section();
	}

	/* ───────────────────────────────
	 *  STYLE CONTROLS
	 * ─────────────────────────────── */
	protected function register_style_controls() {

		/* ── Player / Container Style ── */
		$this->start_controls_section( 'section_style_player', [
			'label' => esc_html__( 'Player', 'mn-elements' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'player_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'mn-elements' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .mn-vp-player' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'player_box_shadow',
			'selector' => '{{WRAPPER}} .mn-vp-player',
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'player_border',
			'selector' => '{{WRAPPER}} .mn-vp-player',
		] );

		$this->end_controls_section();

		/* ── Thumbnail Overlay ── */
		$this->start_controls_section( 'section_style_overlay', [
			'label' => esc_html__( 'Thumbnail Overlay', 'mn-elements' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'overlay_color', [
			'label'     => esc_html__( 'Overlay Color', 'mn-elements' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => 'rgba(0,0,0,0.3)',
			'selectors' => [
				'{{WRAPPER}} .mn-vp-thumbnail::after' => 'background: {{VALUE}};',
			],
		] );

		$this->add_control( 'overlay_hover_color', [
			'label'     => esc_html__( 'Overlay Hover Color', 'mn-elements' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => 'rgba(0,0,0,0.5)',
			'selectors' => [
				'{{WRAPPER}} .mn-vp-thumbnail:hover::after' => 'background: {{VALUE}};',
			],
		] );

		$this->end_controls_section();

		/* ── Modal Trigger Box Style ── */
		$this->start_controls_section( 'section_style_trigger_box', [
			'label'     => esc_html__( 'Trigger Box', 'mn-elements' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'display_mode'       => 'modal',
				'modal_trigger_type' => 'icon_box',
			],
		] );

		$this->add_responsive_control( 'trigger_box_size', [
			'label'      => esc_html__( 'Size', 'mn-elements' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [ 'px' => [ 'min' => 60, 'max' => 400 ] ],
			'default'    => [ 'unit' => 'px', 'size' => 150 ],
			'selectors'  => [
				'{{WRAPPER}} .mn-vp-trigger-box' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'trigger_box_bg', [
			'label'     => esc_html__( 'Background Color', 'mn-elements' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#1a1a2e',
			'selectors' => [
				'{{WRAPPER}} .mn-vp-trigger-box' => 'background-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'trigger_box_hover_bg', [
			'label'     => esc_html__( 'Hover Background', 'mn-elements' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#16213e',
			'selectors' => [
				'{{WRAPPER}} .mn-vp-trigger-box:hover' => 'background-color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'trigger_box_border',
			'selector' => '{{WRAPPER}} .mn-vp-trigger-box',
		] );

		$this->add_responsive_control( 'trigger_box_radius', [
			'label'      => esc_html__( 'Border Radius', 'mn-elements' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .mn-vp-trigger-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'trigger_box_shadow',
			'selector' => '{{WRAPPER}} .mn-vp-trigger-box',
		] );

		$this->add_control( 'trigger_box_alignment', [
			'label'   => esc_html__( 'Alignment', 'mn-elements' ),
			'type'    => Controls_Manager::CHOOSE,
			'options' => [
				'flex-start' => [
					'title' => esc_html__( 'Left', 'mn-elements' ),
					'icon'  => 'eicon-h-align-left',
				],
				'center' => [
					'title' => esc_html__( 'Center', 'mn-elements' ),
					'icon'  => 'eicon-h-align-center',
				],
				'flex-end' => [
					'title' => esc_html__( 'Right', 'mn-elements' ),
					'icon'  => 'eicon-h-align-right',
				],
			],
			'default'   => 'center',
			'selectors' => [
				'{{WRAPPER}} .mn-vp-wrapper' => 'display: flex; justify-content: {{VALUE}};',
			],
		] );

		$this->end_controls_section();

		/* ── Play Button Style ── */
		$this->start_controls_section( 'section_style_play', [
			'label' => esc_html__( 'Play Button', 'mn-elements' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'play_icon_size', [
			'label'      => esc_html__( 'Icon / Image Size', 'mn-elements' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [ 'px' => [ 'min' => 16, 'max' => 200 ] ],
			'default'    => [ 'unit' => 'px', 'size' => 60 ],
			'selectors'  => [
				'{{WRAPPER}} .mn-vp-play-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .mn-vp-play-icon svg'  => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .mn-vp-play-icon img'  => 'width: {{SIZE}}{{UNIT}};',
			],
			'condition' => [ 'play_button_type!' => 'text' ],
		] );

		$this->add_control( 'play_color', [
			'label'     => esc_html__( 'Color', 'mn-elements' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [
				'{{WRAPPER}} .mn-vp-play-icon i'   => 'color: {{VALUE}};',
				'{{WRAPPER}} .mn-vp-play-icon svg'  => 'fill: {{VALUE}};',
			],
			'condition' => [ 'play_button_type' => 'icon' ],
		] );

		$this->add_control( 'play_hover_color', [
			'label'     => esc_html__( 'Hover Color', 'mn-elements' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [
				'{{WRAPPER}} .mn-vp-thumbnail:hover .mn-vp-play-icon i'  => 'color: {{VALUE}};',
				'{{WRAPPER}} .mn-vp-thumbnail:hover .mn-vp-play-icon svg' => 'fill: {{VALUE}};',
			],
			'condition' => [ 'play_button_type' => 'icon' ],
		] );

		$this->add_control( 'play_bg_color', [
			'label'     => esc_html__( 'Background', 'mn-elements' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => 'rgba(0,0,0,0.6)',
			'selectors' => [
				'{{WRAPPER}} .mn-vp-play-icon' => 'background-color: {{VALUE}};',
			],
			'condition' => [ 'play_button_type' => 'icon' ],
		] );

		$this->add_control( 'play_bg_hover_color', [
			'label'     => esc_html__( 'Background Hover', 'mn-elements' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => 'rgba(0,0,0,0.8)',
			'selectors' => [
				'{{WRAPPER}} .mn-vp-thumbnail:hover .mn-vp-play-icon' => 'background-color: {{VALUE}};',
			],
			'condition' => [ 'play_button_type' => 'icon' ],
		] );

		$this->add_responsive_control( 'play_bg_size', [
			'label'      => esc_html__( 'Background Size', 'mn-elements' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [ 'px' => [ 'min' => 40, 'max' => 200 ] ],
			'default'    => [ 'unit' => 'px', 'size' => 80 ],
			'selectors'  => [
				'{{WRAPPER}} .mn-vp-play-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
			],
			'condition' => [ 'play_button_type' => 'icon' ],
		] );

		$this->add_responsive_control( 'play_bg_radius', [
			'label'      => esc_html__( 'Border Radius', 'mn-elements' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'default'    => [ 'top' => '50', 'right' => '50', 'bottom' => '50', 'left' => '50', 'unit' => '%' ],
			'selectors'  => [
				'{{WRAPPER}} .mn-vp-play-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition' => [ 'play_button_type' => 'icon' ],
		] );

		$this->add_control( 'pulse_color', [
			'label'     => esc_html__( 'Pulse Animation Color', 'mn-elements' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => 'rgba(255,255,255,0.4)',
			'selectors' => [
				'{{WRAPPER}} .mn-vp-play-icon.has-pulse::before' => 'border-color: {{VALUE}};',
			],
			'condition' => [
				'play_button_type!' => 'text',
				'show_play_animation' => 'yes',
			],
		] );

		// Text button style
		$this->add_control( 'heading_text_btn_style', [
			'label'     => esc_html__( 'Text Button', 'mn-elements' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [ 'play_button_type' => 'text' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'play_text_typography',
			'selector'  => '{{WRAPPER}} .mn-vp-play-text-btn',
			'condition' => [ 'play_button_type' => 'text' ],
		] );

		$this->add_control( 'play_text_color', [
			'label'     => esc_html__( 'Text Color', 'mn-elements' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [
				'{{WRAPPER}} .mn-vp-play-text-btn' => 'color: {{VALUE}};',
			],
			'condition' => [ 'play_button_type' => 'text' ],
		] );

		$this->add_control( 'play_text_bg', [
			'label'     => esc_html__( 'Background', 'mn-elements' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => 'rgba(0,0,0,0.7)',
			'selectors' => [
				'{{WRAPPER}} .mn-vp-play-text-btn' => 'background-color: {{VALUE}};',
			],
			'condition' => [ 'play_button_type' => 'text' ],
		] );

		$this->add_control( 'play_text_hover_color', [
			'label'     => esc_html__( 'Hover Text Color', 'mn-elements' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [
				'{{WRAPPER}} .mn-vp-play-text-btn:hover' => 'color: {{VALUE}};',
			],
			'condition' => [ 'play_button_type' => 'text' ],
		] );

		$this->add_control( 'play_text_hover_bg', [
			'label'     => esc_html__( 'Hover Background', 'mn-elements' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => 'rgba(0,0,0,0.9)',
			'selectors' => [
				'{{WRAPPER}} .mn-vp-play-text-btn:hover' => 'background-color: {{VALUE}};',
			],
			'condition' => [ 'play_button_type' => 'text' ],
		] );

		$this->add_responsive_control( 'play_text_padding', [
			'label'      => esc_html__( 'Padding', 'mn-elements' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .mn-vp-play-text-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition' => [ 'play_button_type' => 'text' ],
		] );

		$this->add_responsive_control( 'play_text_radius', [
			'label'      => esc_html__( 'Border Radius', 'mn-elements' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .mn-vp-play-text-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition' => [ 'play_button_type' => 'text' ],
		] );

		$this->end_controls_section();

		/* ── Modal Style ── */
		$this->start_controls_section( 'section_style_modal', [
			'label'     => esc_html__( 'Modal', 'mn-elements' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'display_mode' => 'modal' ],
		] );

		$this->add_control( 'modal_bg_color', [
			'label'     => esc_html__( 'Backdrop Color', 'mn-elements' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => 'rgba(0,0,0,0.85)',
			'selectors' => [
				'{{WRAPPER}} .mn-vp-modal-overlay' => 'background-color: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'modal_border_radius', [
			'label'      => esc_html__( 'Video Border Radius', 'mn-elements' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .mn-vp-modal-content .mn-vp-player' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_control( 'close_btn_color', [
			'label'     => esc_html__( 'Close Button Color', 'mn-elements' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [
				'{{WRAPPER}} .mn-vp-modal-close' => 'color: {{VALUE}};',
			],
			'condition' => [ 'show_close_button' => 'yes' ],
		] );

		$this->add_responsive_control( 'close_btn_size', [
			'label'      => esc_html__( 'Close Button Size', 'mn-elements' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [ 'px' => [ 'min' => 16, 'max' => 60 ] ],
			'default'    => [ 'unit' => 'px', 'size' => 28 ],
			'selectors'  => [
				'{{WRAPPER}} .mn-vp-modal-close' => 'font-size: {{SIZE}}{{UNIT}};',
			],
			'condition' => [ 'show_close_button' => 'yes' ],
		] );

		$this->end_controls_section();
	}

	/* ───────────────────────────────
	 *  RENDER
	 * ─────────────────────────────── */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$display  = $settings['display_mode'];
		$source   = $settings['video_source'];

		$video_url = $this->get_video_url( $settings );
		if ( empty( $video_url ) ) {
			echo '<p class="mn-vp-notice">' . esc_html__( 'Please provide a video URL.', 'mn-elements' ) . '</p>';
			return;
		}

		$thumb_url = $this->get_thumbnail_url( $settings );
		$show_thumb = ( $display === 'modal' ) || ( $display === 'inline' && $settings['lazy_load'] === 'yes' );

		$aspect = $display === 'modal' ? $settings['modal_aspect_ratio'] : $settings['aspect_ratio'];

		// Build data attributes for JS
		$data_attrs = [
			'source'        => $source,
			'video-url'     => $video_url,
			'display'       => $display,
			'autoplay'      => $settings['autoplay'],
			'mute'          => $settings['mute'],
			'loop'          => $settings['loop'],
			'controls'      => $settings['player_controls'],
			'start'         => $settings['start_time'],
			'end'           => ! empty( $settings['end_time'] ) ? $settings['end_time'] : '',
			'lazy'          => $settings['lazy_load'],
			'modal-close-bg'  => $settings['modal_close_on_bg'] ?? 'yes',
			'modal-close-esc' => $settings['modal_close_on_esc'] ?? 'yes',
		];

		$data_str = '';
		foreach ( $data_attrs as $key => $val ) {
			if ( $val !== '' && $val !== null ) {
				$data_str .= ' data-' . esc_attr( $key ) . '="' . esc_attr( $val ) . '"';
			}
		}

		$pulse_class = '';
		if ( $settings['play_button_type'] !== 'text' && $settings['show_play_animation'] === 'yes' ) {
			$pulse_class = ' has-pulse';
		}

		?>
		<div class="mn-vp-wrapper" <?php echo $data_str; ?>>

			<?php if ( $display === 'inline' && ! $show_thumb ) : ?>
				<!-- Direct inline embed -->
				<div class="mn-vp-player mn-vp-aspect-<?php echo esc_attr( $aspect ); ?>">
					<?php $this->render_embed( $settings ); ?>
				</div>

			<?php elseif ( $display === 'inline' && $show_thumb ) : ?>
				<!-- Lazy-load inline: thumbnail + play button -->
				<div class="mn-vp-player mn-vp-aspect-<?php echo esc_attr( $aspect ); ?>">
					<div class="mn-vp-thumbnail mn-vp-trigger" style="<?php echo $thumb_url ? 'background-image:url(' . esc_url( $thumb_url ) . ')' : ''; ?>">
						<?php $this->render_play_button( $settings, $pulse_class ); ?>
					</div>
					<div class="mn-vp-embed-container"></div>
				</div>

			<?php elseif ( $display === 'modal' ) : ?>
				<?php
				$modal_trigger_type = $settings['modal_trigger_type'] ?? 'icon_box';
				if ( $modal_trigger_type === 'thumbnail' ) : ?>
					<!-- Modal trigger: thumbnail + play button -->
					<div class="mn-vp-thumbnail mn-vp-trigger mn-vp-modal-trigger mn-vp-aspect-<?php echo esc_attr( $aspect ); ?>" style="<?php echo $thumb_url ? 'background-image:url(' . esc_url( $thumb_url ) . ')' : ''; ?>">
						<?php $this->render_play_button( $settings, $pulse_class ); ?>
					</div>
				<?php else :
					$shape = $settings['trigger_shape'] ?? 'square';
				?>
					<!-- Modal trigger: icon box -->
					<div class="mn-vp-trigger mn-vp-modal-trigger mn-vp-trigger-box mn-vp-trigger-box--<?php echo esc_attr( $shape ); ?>">
						<?php $this->render_play_button( $settings, $pulse_class ); ?>
					</div>
				<?php endif; ?>

				<!-- Modal markup -->
				<div class="mn-vp-modal-overlay" style="display:none;">
					<div class="mn-vp-modal-content">
						<?php if ( $settings['show_close_button'] === 'yes' ) : ?>
							<button class="mn-vp-modal-close" aria-label="<?php esc_attr_e( 'Close', 'mn-elements' ); ?>">&times;</button>
						<?php endif; ?>
						<div class="mn-vp-player mn-vp-aspect-<?php echo esc_attr( $aspect ); ?>">
							<div class="mn-vp-embed-container"></div>
						</div>
					</div>
				</div>
			<?php endif; ?>

		</div>
		<?php
	}

	/* ───────────────────────────────
	 *  HELPERS
	 * ─────────────────────────────── */

	/**
	 * Render the play button markup
	 */
	private function render_play_button( $settings, $pulse_class = '' ) {
		$type = $settings['play_button_type'];

		if ( $type === 'icon' ) {
			echo '<span class="mn-vp-play-icon' . esc_attr( $pulse_class ) . '">';
			Icons_Manager::render_icon( $settings['play_icon'], [ 'aria-hidden' => 'true' ] );
			echo '</span>';
		} elseif ( $type === 'image' ) {
			$img = $settings['play_image'];
			if ( ! empty( $img['url'] ) ) {
				echo '<span class="mn-vp-play-icon' . esc_attr( $pulse_class ) . '">';
				echo '<img src="' . esc_url( $img['url'] ) . '" alt="' . esc_attr__( 'Play', 'mn-elements' ) . '">';
				echo '</span>';
			}
		} elseif ( $type === 'text' ) {
			echo '<span class="mn-vp-play-text-btn">';
			if ( ! empty( $settings['play_text_icon']['value'] ) ) {
				Icons_Manager::render_icon( $settings['play_text_icon'], [ 'aria-hidden' => 'true' ] );
			}
			echo '<span>' . esc_html( $settings['play_text'] ) . '</span>';
			echo '</span>';
		}
	}

	/**
	 * Render the actual embed (iframe or video tag)
	 */
	private function render_embed( $settings ) {
		$source = $settings['video_source'];

		if ( $source === 'youtube' ) {
			$embed_url = $this->get_youtube_embed_url( $settings );
			echo '<iframe class="mn-vp-iframe" src="' . esc_url( $embed_url ) . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
		} elseif ( $source === 'vimeo' ) {
			$embed_url = $this->get_vimeo_embed_url( $settings );
			echo '<iframe class="mn-vp-iframe" src="' . esc_url( $embed_url ) . '" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>';
		} elseif ( $source === 'self_hosted' ) {
			$url = $this->get_hosted_url( $settings );
			$attrs = '';
			if ( $settings['player_controls'] === 'yes' ) $attrs .= ' controls';
			if ( $settings['autoplay'] === 'yes' ) $attrs .= ' autoplay';
			if ( $settings['mute'] === 'yes' ) $attrs .= ' muted';
			if ( $settings['loop'] === 'yes' ) $attrs .= ' loop';
			if ( ! empty( $settings['poster']['url'] ) ) $attrs .= ' poster="' . esc_url( $settings['poster']['url'] ) . '"';
			if ( $settings['start_time'] ) $attrs .= ' data-start="' . esc_attr( $settings['start_time'] ) . '"';
			if ( $settings['download_button'] !== 'yes' ) $attrs .= ' controlslist="nodownload"';
			echo '<video class="mn-vp-video" playsinline' . $attrs . '><source src="' . esc_url( $url ) . '" type="video/mp4"></video>';
		}
	}

	/**
	 * Get the raw video URL for data attribute
	 */
	private function get_video_url( $settings ) {
		$source = $settings['video_source'];
		if ( $source === 'youtube' ) {
			return $settings['youtube_url'];
		} elseif ( $source === 'vimeo' ) {
			return $settings['vimeo_url'];
		} elseif ( $source === 'self_hosted' ) {
			return $this->get_hosted_url( $settings );
		}
		return '';
	}

	/**
	 * Get self-hosted video URL
	 */
	private function get_hosted_url( $settings ) {
		if ( ! empty( $settings['hosted_url']['url'] ) ) {
			return $settings['hosted_url']['url'];
		}
		if ( ! empty( $settings['external_url']['url'] ) ) {
			return $settings['external_url']['url'];
		}
		return '';
	}

	/**
	 * Get YouTube embed URL with parameters
	 */
	private function get_youtube_embed_url( $settings ) {
		$video_id = $this->extract_youtube_id( $settings['youtube_url'] );
		$domain   = $settings['privacy_mode'] === 'yes' ? 'www.youtube-nocookie.com' : 'www.youtube.com';

		$params = [];
		if ( $settings['autoplay'] === 'yes' )        $params['autoplay'] = 1;
		if ( $settings['mute'] === 'yes' )             $params['mute'] = 1;
		if ( $settings['loop'] === 'yes' )             { $params['loop'] = 1; $params['playlist'] = $video_id; }
		if ( $settings['player_controls'] !== 'yes' )  $params['controls'] = 0;
		if ( $settings['modest_branding'] === 'yes' )  $params['modestbranding'] = 1;
		if ( $settings['rel'] !== 'yes' )              $params['rel'] = 0;
		if ( ! empty( $settings['start_time'] ) )      $params['start'] = intval( $settings['start_time'] );
		if ( ! empty( $settings['end_time'] ) )        $params['end'] = intval( $settings['end_time'] );

		$query = ! empty( $params ) ? '?' . http_build_query( $params ) : '';
		return 'https://' . $domain . '/embed/' . $video_id . $query;
	}

	/**
	 * Get Vimeo embed URL with parameters
	 */
	private function get_vimeo_embed_url( $settings ) {
		$video_id = $this->extract_vimeo_id( $settings['vimeo_url'] );

		$params = [];
		if ( $settings['autoplay'] === 'yes' )        $params['autoplay'] = 1;
		if ( $settings['mute'] === 'yes' )             $params['muted'] = 1;
		if ( $settings['loop'] === 'yes' )             $params['loop'] = 1;
		if ( $settings['vimeo_title'] !== 'yes' )      $params['title'] = 0;
		if ( $settings['vimeo_portrait'] !== 'yes' )   $params['portrait'] = 0;
		if ( $settings['vimeo_byline'] !== 'yes' )     $params['byline'] = 0;
		if ( ! empty( $settings['start_time'] ) )      $params['#t'] = intval( $settings['start_time'] ) . 's';

		$query = ! empty( $params ) ? '?' . http_build_query( $params ) : '';
		return 'https://player.vimeo.com/video/' . $video_id . $query;
	}

	/**
	 * Extract YouTube video ID from URL
	 */
	private function extract_youtube_id( $url ) {
		$pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i';
		preg_match( $pattern, $url, $matches );
		return isset( $matches[1] ) ? $matches[1] : '';
	}

	/**
	 * Extract Vimeo video ID from URL
	 */
	private function extract_vimeo_id( $url ) {
		$pattern = '/vimeo\.com\/(?:video\/)?(\d+)/i';
		preg_match( $pattern, $url, $matches );
		return isset( $matches[1] ) ? $matches[1] : '';
	}

	/**
	 * Get thumbnail URL (custom or auto-generated from source)
	 */
	private function get_thumbnail_url( $settings ) {
		// Custom thumbnail takes priority
		if ( ! empty( $settings['custom_thumbnail']['url'] ) ) {
			return $settings['custom_thumbnail']['url'];
		}

		$source = $settings['video_source'];

		if ( $source === 'youtube' ) {
			$id = $this->extract_youtube_id( $settings['youtube_url'] );
			return $id ? 'https://img.youtube.com/vi/' . $id . '/maxresdefault.jpg' : '';
		}

		if ( $source === 'vimeo' ) {
			$id = $this->extract_vimeo_id( $settings['vimeo_url'] );
			return $id ? 'https://vumbnail.com/' . $id . '.jpg' : '';
		}

		if ( $source === 'self_hosted' && ! empty( $settings['poster']['url'] ) ) {
			return $settings['poster']['url'];
		}

		return '';
	}
}
