<?php
namespace MN_Elements\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * MN Postnav Widget
 */
class MN_Postnav extends Widget_Base {

	public function get_name() {
		return 'mn-postnav';
	}

	public function get_title() {
		return esc_html__( 'MN Postnav', 'mn-elements' );
	}

	public function get_icon() {
		return 'eicon-post-navigation';
	}

	public function get_categories() {
		return [ 'mn-elements' ];
	}

	public function get_keywords() {
		return [ 'post', 'navigation', 'prev', 'next', 'postnav', 'pagination' ];
	}

	protected function register_controls() {
		// Layout Section
		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Layout', 'mn-elements' ),
			]
		);

		$this->add_control(
			'show_borders',
			[
				'label' => esc_html__( 'Show Borders', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'mn-elements' ),
				'label_off' => esc_html__( 'No', 'mn-elements' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_arrow',
			[
				'label' => esc_html__( 'Show Arrow', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'mn-elements' ),
				'label_off' => esc_html__( 'No', 'mn-elements' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'arrow_icon_prev',
			[
				'label' => esc_html__( 'Previous Arrow Icon', 'mn-elements' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-chevron-left',
					'library' => 'fa-solid',
				],
				'condition' => [
					'show_arrow' => 'yes',
				],
			]
		);

		$this->add_control(
			'arrow_icon_next',
			[
				'label' => esc_html__( 'Next Arrow Icon', 'mn-elements' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-chevron-right',
					'library' => 'fa-solid',
				],
				'condition' => [
					'show_arrow' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_label',
			[
				'label' => esc_html__( 'Show Label', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'mn-elements' ),
				'label_off' => esc_html__( 'No', 'mn-elements' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'prev_label',
			[
				'label' => esc_html__( 'Previous Label', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Previous', 'mn-elements' ),
				'condition' => [
					'show_label' => 'yes',
				],
			]
		);

		$this->add_control(
			'next_label',
			[
				'label' => esc_html__( 'Next Label', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Next', 'mn-elements' ),
				'condition' => [
					'show_label' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_title',
			[
				'label' => esc_html__( 'Show Title', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'mn-elements' ),
				'label_off' => esc_html__( 'No', 'mn-elements' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_thumbnail',
			[
				'label' => esc_html__( 'Show Thumbnail', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'mn-elements' ),
				'label_off' => esc_html__( 'No', 'mn-elements' ),
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'thumbnail_position',
			[
				'label' => esc_html__( 'Thumbnail Position', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'center',
				'options' => [
					'center' => esc_html__( 'Center', 'mn-elements' ),
					'inline' => esc_html__( 'Inline with Content', 'mn-elements' ),
				],
				'condition' => [
					'show_thumbnail' => 'yes',
				],
			]
		);

		$this->add_control(
			'thumbnail_shape',
			[
				'label' => esc_html__( 'Thumbnail Shape', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'square',
				'options' => [
					'square' => esc_html__( 'Square', 'mn-elements' ),
					'circle' => esc_html__( 'Circle', 'mn-elements' ),
					'rounded' => esc_html__( 'Rounded', 'mn-elements' ),
				],
				'condition' => [
					'show_thumbnail' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'thumbnail_size',
			[
				'label' => esc_html__( 'Thumbnail Size', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 40,
						'max' => 200,
					],
				],
				'default' => [
					'size' => 80,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-postnav-content .mn-postnav-thumbnail' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-postnav-thumbnail img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_thumbnail' => 'yes',
				],
			]
		);

		$this->add_control(
			'in_same_term',
			[
				'label' => esc_html__( 'In Same Category', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'mn-elements' ),
				'label_off' => esc_html__( 'No', 'mn-elements' ),
				'default' => '',
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Style - Container Section
		$this->start_controls_section(
			'section_style_container',
			[
				'label' => esc_html__( 'Container', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'container_background_color',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-postnav-wrapper' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .mn-postnav-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'container_gap',
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
				'selectors' => [
					'{{WRAPPER}} .mn-postnav-wrapper' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Style - Post Item Section
		$this->start_controls_section(
			'section_style_post_item',
			[
				'label' => esc_html__( 'Post Item', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'post_item_background_color',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-postnav-item' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'post_item_hover_background_color',
			[
				'label' => esc_html__( 'Hover Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-postnav-item:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'post_item_border',
				'selector' => '{{WRAPPER}} .mn-postnav-item',
			]
		);

		$this->add_responsive_control(
			'post_item_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-postnav-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'post_item_box_shadow',
				'selector' => '{{WRAPPER}} .mn-postnav-item',
			]
		);

		$this->add_responsive_control(
			'post_item_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-postnav-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Style - Arrow Section
		$this->start_controls_section(
			'section_style_arrow',
			[
				'label' => esc_html__( 'Arrow', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_arrow' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'arrow_size',
			[
				'label' => esc_html__( 'Size', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mn-postnav-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-postnav-arrow svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'arrow_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-postnav-arrow' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-postnav-arrow svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'arrow_hover_color',
			[
				'label' => esc_html__( 'Hover Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-postnav-item:hover .mn-postnav-arrow' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-postnav-item:hover .mn-postnav-arrow svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Style - Label Section
		$this->start_controls_section(
			'section_style_label',
			[
				'label' => esc_html__( 'Label', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_label' => 'yes',
				],
			]
		);

		$this->add_control(
			'label_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-postnav-label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'label_hover_color',
			[
				'label' => esc_html__( 'Hover Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-postnav-item:hover .mn-postnav-label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'label_typography',
				'selector' => '{{WRAPPER}} .mn-postnav-label',
			]
		);

		$this->add_responsive_control(
			'label_spacing',
			[
				'label' => esc_html__( 'Spacing', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mn-postnav-label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Style - Title Section
		$this->start_controls_section(
			'section_style_title',
			[
				'label' => esc_html__( 'Title', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-postnav-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_hover_color',
			[
				'label' => esc_html__( 'Hover Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-postnav-item:hover .mn-postnav-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .mn-postnav-title',
			]
		);

		$this->end_controls_section();

		// Style - Thumbnail Section
		$this->start_controls_section(
			'section_style_thumbnail',
			[
				'label' => esc_html__( 'Thumbnail', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_thumbnail' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'thumbnail_border',
				'selector' => '{{WRAPPER}} .mn-postnav-thumbnail img',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'thumbnail_box_shadow',
				'selector' => '{{WRAPPER}} .mn-postnav-thumbnail img',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Get adjacent post
	 */
	private function get_adjacent_post_data( $is_previous, $settings ) {
		$in_same_term = isset( $settings['in_same_term'] ) && $settings['in_same_term'] === 'yes';
		
		$adjacent_post = get_adjacent_post( $in_same_term, '', $is_previous );

		if ( ! $adjacent_post ) {
			return null;
		}

		$data = [
			'id' => $adjacent_post->ID,
			'title' => get_the_title( $adjacent_post->ID ),
			'url' => get_permalink( $adjacent_post->ID ),
			'thumbnail' => '',
		];

		// Get thumbnail
		if ( isset( $settings['show_thumbnail'] ) && $settings['show_thumbnail'] === 'yes' ) {
			if ( has_post_thumbnail( $adjacent_post->ID ) ) {
				$data['thumbnail'] = get_the_post_thumbnail_url( $adjacent_post->ID, 'thumbnail' );
			}
		}

		return $data;
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// Check if we're in a single post
		if ( ! is_singular( 'post' ) ) {
			echo '<p>' . esc_html__( 'This widget only works on single post pages.', 'mn-elements' ) . '</p>';
			return;
		}

		$prev_post = $this->get_adjacent_post_data( true, $settings );
		$next_post = $this->get_adjacent_post_data( false, $settings );

		if ( ! $prev_post && ! $next_post ) {
			return;
		}

		$thumbnail_position = isset( $settings['thumbnail_position'] ) ? $settings['thumbnail_position'] : 'center';
		$thumbnail_shape = isset( $settings['thumbnail_shape'] ) ? $settings['thumbnail_shape'] : 'square';
		$show_borders = isset( $settings['show_borders'] ) && $settings['show_borders'] === 'yes';

		$wrapper_classes = [
			'mn-postnav-wrapper',
			'mn-postnav-thumbnail-' . $thumbnail_position,
			'mn-postnav-thumbnail-' . $thumbnail_shape,
		];

		if ( $show_borders ) {
			$wrapper_classes[] = 'mn-postnav-with-borders';
		}

		$this->add_render_attribute( 'wrapper', 'class', $wrapper_classes );

		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<?php if ( $prev_post ) : ?>
				<a href="<?php echo esc_url( $prev_post['url'] ); ?>" class="mn-postnav-item mn-postnav-prev">
					<div class="mn-postnav-content">
						<?php if ( $settings['show_arrow'] === 'yes' ) : ?>
							<div class="mn-postnav-arrow">
								<?php Icons_Manager::render_icon( $settings['arrow_icon_prev'], [ 'aria-hidden' => 'true' ] ); ?>
							</div>
						<?php endif; ?>
						<?php if ( $settings['show_thumbnail'] === 'yes' && $thumbnail_position === 'inline' && ! empty( $prev_post['thumbnail'] ) ) : ?>
								<div class="mn-postnav-thumbnail">
									<img src="<?php echo esc_url( $prev_post['thumbnail'] ); ?>" alt="<?php echo esc_attr( $prev_post['title'] ); ?>">
								</div>
						<?php endif; ?>
						<div class="mn-postnav-text">
							<?php if ( $settings['show_label'] === 'yes' ) : ?>
								<span class="mn-postnav-label"><?php echo esc_html( $settings['prev_label'] ); ?></span>
							<?php endif; ?>
							<?php if ( $settings['show_title'] === 'yes' ) : ?>
								<span class="mn-postnav-title"><?php echo esc_html( $prev_post['title'] ); ?></span>
							<?php endif; ?>
						</div>
					</div>
				</a>
			<?php endif; ?>

			<?php if ( $settings['show_thumbnail'] === 'yes' && $thumbnail_position === 'center' ) : ?>
				<div class="mn-postnav-center">
					<?php if ( $prev_post && ! empty( $prev_post['thumbnail'] ) ) : ?>
						<div class="mn-postnav-thumbnail mn-postnav-thumbnail-prev">
							<a href="<?php echo esc_url( $prev_post['url'] ); ?>">
								<img src="<?php echo esc_url( $prev_post['thumbnail'] ); ?>" alt="<?php echo esc_attr( $prev_post['title'] ); ?>">
							</a>
						</div>
					<?php endif; ?>

					<?php if ( $next_post && ! empty( $next_post['thumbnail'] ) ) : ?>
						<div class="mn-postnav-thumbnail mn-postnav-thumbnail-next">
							<a href="<?php echo esc_url( $next_post['url'] ); ?>">
								<img src="<?php echo esc_url( $next_post['thumbnail'] ); ?>" alt="<?php echo esc_attr( $next_post['title'] ); ?>">
							</a>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( $next_post ) : ?>
				<a href="<?php echo esc_url( $next_post['url'] ); ?>" class="mn-postnav-item mn-postnav-next">
					<div class="mn-postnav-content">						
						<?php if ( $settings['show_arrow'] === 'yes' ) : ?>
							<div class="mn-postnav-arrow">
								<?php Icons_Manager::render_icon( $settings['arrow_icon_next'], [ 'aria-hidden' => 'true' ] ); ?>
							</div>
						<?php endif; ?>
						<?php if ( $settings['show_thumbnail'] === 'yes' && $thumbnail_position === 'inline' && ! empty( $next_post['thumbnail'] ) ) : ?>
							<div class="mn-postnav-thumbnail">
								<img src="<?php echo esc_url( $next_post['thumbnail'] ); ?>" alt="<?php echo esc_attr( $next_post['title'] ); ?>">
							</div>
						<?php endif; ?>
						<div class="mn-postnav-text">
							<?php if ( $settings['show_label'] === 'yes' ) : ?>
								<span class="mn-postnav-label"><?php echo esc_html( $settings['next_label'] ); ?></span>
							<?php endif; ?>
							<?php if ( $settings['show_title'] === 'yes' ) : ?>
								<span class="mn-postnav-title"><?php echo esc_html( $next_post['title'] ); ?></span>
							<?php endif; ?>
						</div>
						
					</div>
				</a>
			<?php endif; ?>
		</div>
		<?php
	}
}
