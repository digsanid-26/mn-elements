<?php
namespace MN_Elements\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class MN_Office_Hours extends Widget_Base {

	public function get_name() {
		return 'mn-office-hours';
	}

	public function get_title() {
		return esc_html__( 'MN Office Hours', 'mn-elements' );
	}

	public function get_icon() {
		return 'eicon-clock-o';
	}

	public function get_categories() {
		return [ 'mn-elements' ];
	}

	public function get_keywords() {
		return [ 'office', 'hours', 'time', 'schedule', 'working', 'business', 'opening' ];
	}

	protected function register_controls() {

		// Office Hours Content
		$this->start_controls_section(
			'section_office_hours',
			[
				'label' => esc_html__( 'Office Hours', 'mn-elements' ),
			]
		);

		// Days of the week
		$days = [
			'monday' => esc_html__( 'Monday', 'mn-elements' ),
			'tuesday' => esc_html__( 'Tuesday', 'mn-elements' ),
			'wednesday' => esc_html__( 'Wednesday', 'mn-elements' ),
			'thursday' => esc_html__( 'Thursday', 'mn-elements' ),
			'friday' => esc_html__( 'Friday', 'mn-elements' ),
			'saturday' => esc_html__( 'Saturday', 'mn-elements' ),
			'sunday' => esc_html__( 'Sunday', 'mn-elements' ),
		];

		foreach ( $days as $day_key => $day_label ) {
			$this->add_control(
				$day_key . '_heading',
				[
					'label' => $day_label,
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_control(
				$day_key . '_status',
				[
					'label' => esc_html__( 'Status', 'mn-elements' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'open',
					'options' => [
						'open' => esc_html__( 'Open', 'mn-elements' ),
						'closed' => esc_html__( 'Closed', 'mn-elements' ),
						'24hours' => esc_html__( '24 Hours', 'mn-elements' ),
					],
				]
			);

			$this->add_control(
				$day_key . '_open_time',
				[
					'label' => esc_html__( 'Opening Time', 'mn-elements' ),
					'type' => Controls_Manager::TEXT,
					'default' => '09:00',
					'placeholder' => '09:00',
					'condition' => [
						$day_key . '_status' => 'open',
					],
				]
			);

			$this->add_control(
				$day_key . '_close_time',
				[
					'label' => esc_html__( 'Closing Time', 'mn-elements' ),
					'type' => Controls_Manager::TEXT,
					'default' => '17:00',
					'placeholder' => '17:00',
					'condition' => [
						$day_key . '_status' => 'open',
					],
				]
			);

			$this->add_control(
				$day_key . '_break_time',
				[
					'label' => esc_html__( 'Break Time (Optional)', 'mn-elements' ),
					'type' => Controls_Manager::TEXT,
					'placeholder' => '12:00 - 13:00',
					'condition' => [
						$day_key . '_status' => 'open',
					],
				]
			);
		}

		$this->end_controls_section();

		// Layout Settings
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
				'default' => 'horizontal',
				'options' => [
					'horizontal' => esc_html__( 'Horizontal (Label Left, Time Right)', 'mn-elements' ),
					'vertical' => esc_html__( 'Vertical (Label Top, Time Bottom)', 'mn-elements' ),
				],
			]
		);

		$this->add_control(
			'show_current_day',
			[
				'label' => esc_html__( 'Highlight Current Day', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'mn-elements' ),
				'label_off' => esc_html__( 'No', 'mn-elements' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'time_format',
			[
				'label' => esc_html__( 'Time Format', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => '24hour',
				'options' => [
					'24hour' => esc_html__( '24 Hour (09:00 - 17:00)', 'mn-elements' ),
					'12hour' => esc_html__( '12 Hour (9:00 AM - 5:00 PM)', 'mn-elements' ),
				],
			]
		);

		$this->add_control(
			'closed_text',
			[
				'label' => esc_html__( 'Closed Text', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Closed', 'mn-elements' ),
				'placeholder' => esc_html__( 'Closed', 'mn-elements' ),
			]
		);

		$this->add_control(
			'24hours_text',
			[
				'label' => esc_html__( '24 Hours Text', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '24 Hours', 'mn-elements' ),
				'placeholder' => esc_html__( '24 Hours', 'mn-elements' ),
			]
		);

		$this->end_controls_section();

		// Style Controls
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

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'container_background',
				'selector' => '{{WRAPPER}} .mn-office-hours',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'container_border',
				'selector' => '{{WRAPPER}} .mn-office-hours',
			]
		);

		$this->add_control(
			'container_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-office-hours' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'container_box_shadow',
				'selector' => '{{WRAPPER}} .mn-office-hours',
			]
		);

		$this->add_responsive_control(
			'container_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-office-hours' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Day Item Style
		$this->start_controls_section(
			'section_day_item_style',
			[
				'label' => esc_html__( 'Day Item', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'day_item_spacing',
			[
				'label' => esc_html__( 'Item Spacing', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-office-day:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'day_item_background',
				'selector' => '{{WRAPPER}} .mn-office-day',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'day_item_border',
				'selector' => '{{WRAPPER}} .mn-office-day',
			]
		);

		$this->add_control(
			'day_item_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-office-day' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'day_item_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-office-day' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Day Label Style
		$this->start_controls_section(
			'section_day_label_style',
			[
				'label' => esc_html__( 'Day Label', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'day_label_typography',
				'selector' => '{{WRAPPER}} .mn-office-day-label',
			]
		);

		$this->add_control(
			'day_label_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .mn-office-day-label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'day_label_margin',
			[
				'label' => esc_html__( 'Margin', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-office-day-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Time Style
		$this->start_controls_section(
			'section_time_style',
			[
				'label' => esc_html__( 'Time', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'time_typography',
				'selector' => '{{WRAPPER}} .mn-office-time',
			]
		);

		$this->add_control(
			'time_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#666666',
				'selectors' => [
					'{{WRAPPER}} .mn-office-time' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'closed_time_color',
			[
				'label' => esc_html__( 'Closed Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e74c3c',
				'selectors' => [
					'{{WRAPPER}} .mn-office-time.closed' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'24hours_time_color',
			[
				'label' => esc_html__( '24 Hours Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#27ae60',
				'selectors' => [
					'{{WRAPPER}} .mn-office-time.hours-24' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'time_margin',
			[
				'label' => esc_html__( 'Margin', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-office-time' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Current Day Style
		$this->start_controls_section(
			'section_current_day_style',
			[
				'label' => esc_html__( 'Current Day Highlight', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_current_day' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'current_day_background',
				'selector' => '{{WRAPPER}} .mn-office-day.current-day',
			]
		);

		$this->add_control(
			'current_day_label_color',
			[
				'label' => esc_html__( 'Label Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#007cba',
				'selectors' => [
					'{{WRAPPER}} .mn-office-day.current-day .mn-office-day-label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'current_day_time_color',
			[
				'label' => esc_html__( 'Time Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#007cba',
				'selectors' => [
					'{{WRAPPER}} .mn-office-day.current-day .mn-office-time' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'current_day_border',
				'selector' => '{{WRAPPER}} .mn-office-day.current-day',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		
		$layout_class = 'mn-layout-' . $settings['layout_type'];
		$current_day = strtolower( date( 'l' ) ); // Get current day in lowercase

		$days = [
			'monday' => esc_html__( 'Monday', 'mn-elements' ),
			'tuesday' => esc_html__( 'Tuesday', 'mn-elements' ),
			'wednesday' => esc_html__( 'Wednesday', 'mn-elements' ),
			'thursday' => esc_html__( 'Thursday', 'mn-elements' ),
			'friday' => esc_html__( 'Friday', 'mn-elements' ),
			'saturday' => esc_html__( 'Saturday', 'mn-elements' ),
			'sunday' => esc_html__( 'Sunday', 'mn-elements' ),
		];

		?>
		<div class="mn-office-hours <?php echo esc_attr( $layout_class ); ?>">
			<?php foreach ( $days as $day_key => $day_label ) : ?>
				<?php
				$is_current_day = ( $settings['show_current_day'] === 'yes' && $day_key === $current_day );
				$current_day_class = $is_current_day ? 'current-day' : '';
				$status = $settings[ $day_key . '_status' ];
				?>
				<div class="mn-office-day <?php echo esc_attr( $current_day_class ); ?>">
					<div class="mn-office-day-label">
						<?php echo esc_html( $day_label ); ?>
					</div>
					<div class="mn-office-time <?php echo esc_attr( $status === 'closed' ? 'closed' : ( $status === '24hours' ? 'hours-24' : '' ) ); ?>">
						<?php
						if ( $status === 'closed' ) {
							echo esc_html( $settings['closed_text'] );
						} elseif ( $status === '24hours' ) {
							echo esc_html( $settings['24hours_text'] );
						} else {
							$open_time = $settings[ $day_key . '_open_time' ];
							$close_time = $settings[ $day_key . '_close_time' ];
							$break_time = $settings[ $day_key . '_break_time' ];
							
							if ( $settings['time_format'] === '12hour' ) {
								$open_time = $this->convert_to_12hour( $open_time );
								$close_time = $this->convert_to_12hour( $close_time );
							}
							
							echo esc_html( $open_time . ' - ' . $close_time );
							
							if ( ! empty( $break_time ) ) {
								echo '<br><small>(' . esc_html__( 'Break:', 'mn-elements' ) . ' ' . esc_html( $break_time ) . ')</small>';
							}
						}
						?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}

	private function convert_to_12hour( $time ) {
		if ( empty( $time ) ) {
			return $time;
		}
		
		$timestamp = strtotime( $time );
		if ( $timestamp === false ) {
			return $time;
		}
		
		return date( 'g:i A', $timestamp );
	}
}
