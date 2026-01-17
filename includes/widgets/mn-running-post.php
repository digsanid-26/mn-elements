<?php
namespace MN_Elements\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * MN Running Post Widget
 *
 * Displays post titles in a continuous running animation from right to left
 * with hover pause and click redirect functionality
 *
 * @since 1.1.6
 */
class MN_Running_Post extends Widget_Base {

	/**
	 * Get widget name.
	 */
	public function get_name() {
		return 'mn-running-post';
	}

	/**
	 * Get widget title.
	 */
	public function get_title() {
		return esc_html__( 'MN Running Post', 'mn-elements' );
	}

	/**
	 * Get widget icon.
	 */
	public function get_icon() {
		return 'eicon-animation-text';
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
		return [ 'running', 'post', 'animation', 'ticker', 'scroll', 'news' ];
	}

	/**
	 * Register widget controls.
	 */
	protected function register_controls() {

		// Content Tab
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_CONTENT,
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
			'posts_per_page',
			[
				'label' => esc_html__( 'Posts Per Page', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 10,
				'min' => 1,
				'max' => 100,
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

		$this->add_control(
			'separator_text',
			[
				'label' => esc_html__( 'Separator Text', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => ' • ',
				'placeholder' => esc_html__( 'Enter separator text', 'mn-elements' ),
			]
		);

		$this->end_controls_section();

		// Animation Settings
		$this->start_controls_section(
			'animation_section',
			[
				'label' => esc_html__( 'Animation Settings', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'animation_speed',
			[
				'label' => esc_html__( 'Animation Speed', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 's' ],
				'range' => [
					's' => [
						'min' => 10,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 's',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-running-post-content' => 'animation-duration: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'pause_on_hover',
			[
				'label' => esc_html__( 'Pause on Hover', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'mn-elements' ),
				'label_off' => esc_html__( 'No', 'mn-elements' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->end_controls_section();

		// Style Tab - Container
		$this->start_controls_section(
			'container_style_section',
			[
				'label' => esc_html__( 'Container', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'container_background',
				'label' => esc_html__( 'Background', 'mn-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .mn-running-post-container',
			]
		);

		$this->add_responsive_control(
			'container_height',
			[
				'label' => esc_html__( 'Height', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range' => [
					'px' => [
						'min' => 30,
						'max' => 200,
						'step' => 1,
					],
					'vh' => [
						'min' => 5,
						'max' => 50,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 60,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-running-post-container' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'container_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-running-post-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'container_border',
				'label' => esc_html__( 'Border', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-running-post-container',
			]
		);

		$this->add_responsive_control(
			'container_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-running-post-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'container_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'mn-elements' ),
				'selector' => '{{WRAPPER}} .mn-running-post-container',
			]
		);

		$this->end_controls_section();

		// Style Tab - Text
		$this->start_controls_section(
			'text_style_section',
			[
				'label' => esc_html__( 'Text', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'label' => esc_html__( 'Typography', 'mn-elements' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .mn-running-post-item',
			]
		);

		$this->start_controls_tabs( 'text_style_tabs' );

		$this->start_controls_tab(
			'text_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'mn-elements' ),
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-running-post-item' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'text_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'mn-elements' ),
			]
		);

		$this->add_control(
			'text_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-running-post-item:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'separator_color',
			[
				'label' => esc_html__( 'Separator Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .mn-running-post-separator' => 'color: {{VALUE}};',
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
	 * Render widget output on the frontend.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		// Query arguments
		$args = [
			'post_type' => $settings['post_type'],
			'posts_per_page' => $settings['posts_per_page'],
			'orderby' => $settings['orderby'],
			'order' => $settings['order'],
			'post_status' => 'publish',
		];

		$query = new \WP_Query( $args );

		if ( ! $query->have_posts() ) {
			echo '<div class="mn-running-post-no-posts">' . esc_html__( 'No posts found.', 'mn-elements' ) . '</div>';
			return;
		}

		$pause_class = ( 'yes' === $settings['pause_on_hover'] ) ? ' mn-running-post-pause-hover' : '';
		?>

		<div class="mn-running-post-container<?php echo esc_attr( $pause_class ); ?>">
			<div class="mn-running-post-wrapper">
				<div class="mn-running-post-content">
					<?php
					$post_titles = [];
					while ( $query->have_posts() ) {
						$query->the_post();
						$post_titles[] = [
							'title' => get_the_title(),
							'url' => get_permalink(),
						];
					}
					wp_reset_postdata();

					// Duplicate content for seamless loop
					$all_posts = array_merge( $post_titles, $post_titles );

					foreach ( $all_posts as $index => $post_data ) {
						?>
						<a href="<?php echo esc_url( $post_data['url'] ); ?>" class="mn-running-post-item" target="_blank">
							<?php echo esc_html( $post_data['title'] ); ?>
						</a>
						<?php if ( $index < count( $all_posts ) - 1 ) : ?>
							<span class="mn-running-post-separator"><?php echo esc_html( $settings['separator_text'] ); ?></span>
						<?php endif; ?>
						<?php
					}
					?>
				</div>
			</div>
		</div>

		<?php
	}

	/**
	 * Render widget output in the editor.
	 */
	protected function content_template() {
		?>
		<#
		var pauseClass = ( 'yes' === settings.pause_on_hover ) ? ' mn-running-post-pause-hover' : '';
		#>

		<div class="mn-running-post-container{{{ pauseClass }}}">
			<div class="mn-running-post-wrapper">
				<div class="mn-running-post-content">
					<a href="#" class="mn-running-post-item">Sample Post Title 1</a>
					<span class="mn-running-post-separator">{{{ settings.separator_text }}}</span>
					<a href="#" class="mn-running-post-item">Sample Post Title 2</a>
					<span class="mn-running-post-separator">{{{ settings.separator_text }}}</span>
					<a href="#" class="mn-running-post-item">Sample Post Title 3</a>
					<span class="mn-running-post-separator">{{{ settings.separator_text }}}</span>
					<a href="#" class="mn-running-post-item">Sample Post Title 1</a>
					<span class="mn-running-post-separator">{{{ settings.separator_text }}}</span>
					<a href="#" class="mn-running-post-item">Sample Post Title 2</a>
					<span class="mn-running-post-separator">{{{ settings.separator_text }}}</span>
					<a href="#" class="mn-running-post-item">Sample Post Title 3</a>
				</div>
			</div>
		</div>
		<?php
	}
}
