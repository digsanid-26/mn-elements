<?php
namespace MN_Elements\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * MN Sidepanel Widget
 *
 * @since 1.7.0
 */
class MN_Sidepanel extends Widget_Base {

	/**
	 * Get widget name.
	 */
	public function get_name() {
		return 'mn-sidepanel';
	}

	/**
	 * Get widget title.
	 */
	public function get_title() {
		return esc_html__( 'MN Sidepanel', 'mn-elements' );
	}

	/**
	 * Get widget icon.
	 */
	public function get_icon() {
		return 'eicon-sidebar';
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
		return [ 'sidepanel', 'sidebar', 'slide', 'panel', 'menu', 'navigation' ];
	}

	/**
	 * Register widget controls.
	 */
	protected function register_controls() {

		// Panel Items Section
		$this->start_controls_section(
			'section_panel_items',
			[
				'label' => esc_html__( 'Panel Items', 'mn-elements' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'item_title',
			[
				'label' => esc_html__( 'Title', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Menu Item', 'mn-elements' ),
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'item_icon',
			[
				'label' => esc_html__( 'Icon', 'mn-elements' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'fa-solid',
				],
			]
		);

		$repeater->add_control(
			'content_type',
			[
				'label' => esc_html__( 'Content Type', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'editor',
				'options' => [
					'editor' => esc_html__( 'Editor', 'mn-elements' ),
					'template' => esc_html__( 'Saved Template', 'mn-elements' ),
				],
			]
		);

		$repeater->add_control(
			'item_content',
			[
				'label' => esc_html__( 'Content', 'mn-elements' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'Panel content goes here...', 'mn-elements' ),
				'condition' => [
					'content_type' => 'editor',
				],
			]
		);

		$repeater->add_control(
			'saved_template',
			[
				'label' => esc_html__( 'Choose Template', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_saved_templates(),
				'condition' => [
					'content_type' => 'template',
				],
			]
		);

		$repeater->add_control(
			'hide_on_mobile',
			[
				'label' => esc_html__( 'Hide on Mobile', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'separator' => 'before',
				'description' => esc_html__( 'Hide this trigger button on mobile devices (below 768px)', 'mn-elements' ),
			]
		);

		$this->add_control(
			'panel_items',
			[
				'label' => esc_html__( 'Items', 'mn-elements' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'item_title' => esc_html__( 'Login', 'mn-elements' ),
						'item_icon' => [
							'value' => 'fas fa-sign-in-alt',
							'library' => 'fa-solid',
						],
						'item_content' => '<p><strong>Login to your account</strong></p><p>Access your dashboard and manage your account.</p>',
					],
					[
						'item_title' => esc_html__( 'Products', 'mn-elements' ),
						'item_icon' => [
							'value' => 'fas fa-box',
							'library' => 'fa-solid',
						],
						'item_content' => '<p><strong>Our Products</strong></p><p>Explore our range of products and services.</p>',
					],
					[
						'item_title' => esc_html__( 'Contact', 'mn-elements' ),
						'item_icon' => [
							'value' => 'fas fa-envelope',
							'library' => 'fa-solid',
						],
						'item_content' => '<p><strong>Get in Touch</strong></p><p>Contact us for support and inquiries.</p>',
					],
				],
				'title_field' => '{{{ item_title }}}',
			]
		);

		$this->end_controls_section();

		// Panel Settings Section
		$this->start_controls_section(
			'section_panel_settings',
			[
				'label' => esc_html__( 'Panel Settings', 'mn-elements' ),
			]
		);

		$this->add_control(
			'panel_position',
			[
				'label' => esc_html__( 'Position', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [
					'left' => esc_html__( 'Left', 'mn-elements' ),
					'right' => esc_html__( 'Right', 'mn-elements' ),
				],
			]
		);

		$this->add_responsive_control(
			'panel_width',
			[
				'label' => esc_html__( 'Panel Width', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vw' ],
				'range' => [
					'px' => [
						'min' => 200,
						'max' => 800,
					],
					'%' => [
						'min' => 20,
						'max' => 100,
					],
					'vw' => [
						'min' => 20,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 400,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-sidepanel__content' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'panel_height',
			[
				'label' => esc_html__( 'Panel Height (Mobile)', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vh' ],
				'range' => [
					'px' => [
						'min' => 300,
						'max' => 1000,
					],
					'%' => [
						'min' => 50,
						'max' => 100,
					],
					'vh' => [
						'min' => 50,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'vh',
					'size' => 80,
				],
				'tablet_default' => [
					'unit' => 'vh',
					'size' => 80,
				],
				'mobile_default' => [
					'unit' => 'vh',
					'size' => 90,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-sidepanel__content' => 'height: {{SIZE}}{{UNIT}};',
				],
				'description' => esc_html__( 'This setting is primarily for mobile/tablet view when panel opens from bottom.', 'mn-elements' ),
			]
		);

		$this->add_responsive_control(
			'trigger_width',
			[
				'label' => esc_html__( 'Trigger Width (Default)', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'auto' ],
				'range' => [
					'px' => [
						'min' => 40,
						'max' => 200,
					],
				],
				'default' => [
					'unit' => 'auto',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-sidepanel__trigger' => 'width: {{SIZE}}{{UNIT}};',
				],
				'description' => esc_html__( 'Set width when only icon is visible. Use "auto" for compact width.', 'mn-elements' ),
			]
		);

		$this->add_responsive_control(
			'trigger_width_hover',
			[
				'label' => esc_html__( 'Trigger Width (Hover)', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'auto' ],
				'range' => [
					'px' => [
						'min' => 40,
						'max' => 300,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 180,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-sidepanel__trigger:hover' => 'width: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .mn-sidepanel__trigger-item:hover' => 'width: 100%;',
				],
				'description' => esc_html__( 'Set width when trigger is hovered (icon + title visible). Use "auto" for natural width.', 'mn-elements' ),
			]
		);

		$this->add_control(
			'show_close_button',
			[
				'label' => esc_html__( 'Show Close Button', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'close_on_outside_click',
			[
				'label' => esc_html__( 'Close on Outside Click', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'auto_close',
			[
				'label' => esc_html__( 'Auto Close Panel', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'description' => esc_html__( 'Automatically close panel after specified delay.', 'mn-elements' ),
			]
		);

		$this->add_control(
			'auto_close_delay',
			[
				'label' => esc_html__( 'Auto Close Delay (seconds)', 'mn-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5,
				'min' => 1,
				'max' => 60,
				'step' => 1,
				'condition' => [
					'auto_close' => 'yes',
				],
			]
		);

		$this->add_control(
			'default_open',
			[
				'label' => esc_html__( 'Open by Default', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
			]
		);

		$this->add_control(
			'push_body',
			[
				'label' => esc_html__( 'Push Body Content', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'description' => esc_html__( 'When enabled, adds fixed padding to body content, preventing content from being hidden behind the fixed sidepanel trigger.', 'mn-elements' ),
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'push_body_offset',
			[
				'label' => esc_html__( 'Push Body Offset', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1,
					],
					'em' => [
						'min' => 0,
						'max' => 30,
						'step' => 0.1,
					],
					'%' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 60,
				],
				'condition' => [
					'push_body' => 'yes',
				],
				'description' => esc_html__( 'Set the amount of padding to add to body content. Independent from trigger width.', 'mn-elements' ),
			]
		);

		$this->add_control(
			'icon_position',
			[
				'label' => esc_html__( 'Icon Position', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'before',
				'options' => [
					'before' => esc_html__( 'Before Title', 'mn-elements' ),
					'after' => esc_html__( 'After Title', 'mn-elements' ),
				],
				'prefix_class' => 'mn-sidepanel-icon-',
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Style - Panel Container
		$this->start_controls_section(
			'section_panel_style',
			[
				'label' => esc_html__( 'Panel Container', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'panel_background',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-sidepanel__content' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'panel_shadow',
				'selector' => '{{WRAPPER}} .mn-sidepanel__content',
			]
		);

		$this->add_responsive_control(
			'panel_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-sidepanel__content-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Style - Trigger Items
		$this->start_controls_section(
			'section_trigger_style',
			[
				'label' => esc_html__( 'Trigger Items', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'trigger_container_background',
			[
				'label' => esc_html__( 'Container Background', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#0066cc',
				'selectors' => [
					'{{WRAPPER}} .mn-sidepanel__trigger' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'trigger_background',
			[
				'label' => esc_html__( 'Item Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mn-sidepanel__trigger-item' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'trigger_color',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mn-sidepanel__trigger-item' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-sidepanel__trigger-item i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-sidepanel__trigger-item svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'trigger_hover_background',
			[
				'label' => esc_html__( 'Hover Background', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#0052a3',
				'selectors' => [
					'{{WRAPPER}} .mn-sidepanel__trigger-item:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .mn-sidepanel__trigger-item.active' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'trigger_hover_color',
			[
				'label' => esc_html__( 'Hover Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-sidepanel__trigger-item:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-sidepanel__trigger-item:hover i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-sidepanel__trigger-item:hover svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .mn-sidepanel__trigger-item.active' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-sidepanel__trigger-item.active i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-sidepanel__trigger-item.active svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'trigger_typography',
				'selector' => '{{WRAPPER}} .mn-sidepanel__trigger-title',
			]
		);

		$this->add_responsive_control(
			'trigger_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 50,
					],
				],
				'default' => [
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .mn-sidepanel__trigger-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-sidepanel__trigger-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'trigger_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .mn-sidepanel__trigger-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Style - Content Area
		$this->start_controls_section(
			'section_content_style',
			[
				'label' => esc_html__( 'Content Area', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'content_text_color',
			[
				'label' => esc_html__( 'Text Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-sidepanel__panel-content' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .mn-sidepanel__panel-content',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Get saved templates
	 */
	private function get_saved_templates() {
		$templates = get_posts([
			'post_type' => 'elementor_library',
			'posts_per_page' => -1,
			'meta_query' => [
				[
					'key' => '_elementor_template_type',
					'value' => ['page', 'section'],
					'compare' => 'IN',
				],
			],
		]);

		$options = [ '' => esc_html__( 'Select Template', 'mn-elements' ) ];

		foreach ( $templates as $template ) {
			$options[ $template->ID ] = $template->post_title;
		}

		return $options;
	}

	/**
	 * Render widget output on the frontend.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$position = $settings['panel_position'];
		$default_open = $settings['default_open'] === 'yes' ? 'open' : '';
		$close_outside = $settings['close_on_outside_click'] === 'yes' ? 'true' : 'false';
		$push_body = $settings['push_body'] === 'yes' ? 'true' : 'false';
		$auto_close = $settings['auto_close'] === 'yes' ? 'true' : 'false';
		$auto_close_delay = isset( $settings['auto_close_delay'] ) ? $settings['auto_close_delay'] : 5;
		
		// Get push body offset (independent from trigger width)
		$push_body_offset = isset( $settings['push_body_offset']['size'] ) ? $settings['push_body_offset']['size'] : 60;
		$push_body_offset_unit = isset( $settings['push_body_offset']['unit'] ) ? $settings['push_body_offset']['unit'] : 'px';

		?>
		<div class="mn-sidepanel mn-sidepanel--<?php echo esc_attr( $position ); ?> <?php echo esc_attr( $default_open ); ?>" 
		     data-close-outside="<?php echo esc_attr( $close_outside ); ?>" 
		     data-push-body="<?php echo esc_attr( $push_body ); ?>"
		     data-auto-close="<?php echo esc_attr( $auto_close ); ?>"
		     data-auto-close-delay="<?php echo esc_attr( $auto_close_delay ); ?>"
		     data-push-body-offset="<?php echo esc_attr( $push_body_offset . $push_body_offset_unit ); ?>"
		     data-position="<?php echo esc_attr( $position ); ?>">
			
			<!-- Trigger Items -->
			<div class="mn-sidepanel__trigger">
				<?php foreach ( $settings['panel_items'] as $index => $item ) : 
					$icon_position = isset( $settings['icon_position'] ) ? $settings['icon_position'] : 'before';
					$hide_mobile_class = isset( $item['hide_on_mobile'] ) && $item['hide_on_mobile'] === 'yes' ? 'mn-hide-mobile' : '';
				?>
					<div class="mn-sidepanel__trigger-item <?php echo esc_attr( $hide_mobile_class ); ?>" data-panel-index="<?php echo esc_attr( $index ); ?>">
						<div class="mn-sidepanel__trigger-wrapper mn-sidepanel__trigger-wrapper--icon-<?php echo esc_attr( $icon_position ); ?>">
							<?php if ( $icon_position === 'before' && ! empty( $item['item_icon']['value'] ) ) : ?>
								<span class="mn-sidepanel__trigger-icon">
									<?php Icons_Manager::render_icon( $item['item_icon'], [ 'aria-hidden' => 'true' ] ); ?>
								</span>
							<?php endif; ?>
							<span class="mn-sidepanel__trigger-title"><?php echo esc_html( $item['item_title'] ); ?></span>
							<?php if ( $icon_position === 'after' && ! empty( $item['item_icon']['value'] ) ) : ?>
								<span class="mn-sidepanel__trigger-icon">
									<?php Icons_Manager::render_icon( $item['item_icon'], [ 'aria-hidden' => 'true' ] ); ?>
								</span>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<!-- Content Panel -->
			<div class="mn-sidepanel__content">
				<?php if ( $settings['show_close_button'] === 'yes' ) : ?>
					<button class="mn-sidepanel__close" type="button">
						<i class="eicon-close"></i>
					</button>
				<?php endif; ?>

				<div class="mn-sidepanel__content-inner">
					<?php foreach ( $settings['panel_items'] as $index => $item ) : ?>
						<div class="mn-sidepanel__panel-content" data-panel-index="<?php echo esc_attr( $index ); ?>" style="display: none;">
							<?php
							if ( $item['content_type'] === 'template' && ! empty( $item['saved_template'] ) ) {
								echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $item['saved_template'] );
							} else {
								echo wp_kses_post( $item['item_content'] );
							}
							?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<!-- Overlay -->
			<div class="mn-sidepanel__overlay"></div>
		</div>
		<?php
	}
}
