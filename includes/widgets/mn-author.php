<?php
namespace MN_Elements\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * MN Author Widget
 */
class MN_Author extends Widget_Base {

	public function get_name() {
		return 'mn-author';
	}

	public function get_title() {
		return esc_html__( 'MN Author', 'mn-elements' );
	}

	public function get_icon() {
		return 'eicon-person';
	}

	public function get_categories() {
		return [ 'mn-elements' ];
	}

	public function get_keywords() {
		return [ 'author', 'user', 'profile', 'bio', 'biography', 'social' ];
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
			'author_layout',
			[
				'label' => esc_html__( 'Layout', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => esc_html__( 'Image Left', 'mn-elements' ),
					'top' => esc_html__( 'Image Top', 'mn-elements' ),
					'right' => esc_html__( 'Image Right', 'mn-elements' ),
				],
			]
		);

		$this->add_control(
			'author_source',
			[
				'label' => esc_html__( 'Author Source', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'current',
				'options' => [
					'current' => esc_html__( 'Current Post Author', 'mn-elements' ),
					'custom' => esc_html__( 'Custom Author', 'mn-elements' ),
				],
			]
		);

		$this->add_control(
			'custom_author_id',
			[
				'label' => esc_html__( 'Select Author', 'mn-elements' ),
				'type' => Controls_Manager::SELECT2,
				'options' => $this->get_authors(),
				'condition' => [
					'author_source' => 'custom',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'avatar_size',
				'default' => 'thumbnail',
			]
		);

		$this->add_control(
			'link_to',
			[
				'label' => esc_html__( 'Link', 'mn-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => esc_html__( 'None', 'mn-elements' ),
					'archive' => esc_html__( 'Author Archive', 'mn-elements' ),
					'website' => esc_html__( 'Author Website', 'mn-elements' ),
				],
			]
		);

		$this->end_controls_section();

		// Content Section
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'mn-elements' ),
			]
		);

		$this->add_control(
			'show_avatar',
			[
				'label' => esc_html__( 'Show Avatar', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'mn-elements' ),
				'label_off' => esc_html__( 'Hide', 'mn-elements' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_name',
			[
				'label' => esc_html__( 'Show Name', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'mn-elements' ),
				'label_off' => esc_html__( 'Hide', 'mn-elements' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_biography',
			[
				'label' => esc_html__( 'Show Biography', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'mn-elements' ),
				'label_off' => esc_html__( 'Hide', 'mn-elements' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_link',
			[
				'label' => esc_html__( 'Show Link', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'mn-elements' ),
				'label_off' => esc_html__( 'Hide', 'mn-elements' ),
				'default' => 'yes',
				'condition' => [
					'link_to!' => 'none',
				],
			]
		);

		$this->add_control(
			'link_text',
			[
				'label' => esc_html__( 'Link Text', 'mn-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'View Posts', 'mn-elements' ),
				'condition' => [
					'show_link' => 'yes',
					'link_to!' => 'none',
				],
			]
		);

		$this->add_control(
			'show_social_media',
			[
				'label' => esc_html__( 'Show Social Media', 'mn-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'mn-elements' ),
				'label_off' => esc_html__( 'Hide', 'mn-elements' ),
				'default' => '',
				'separator' => 'before',
				'description' => esc_html__( 'Display social media icons from user profile meta fields', 'mn-elements' ),
			]
		);

		$this->end_controls_section();

		// Style - Box Section
		$this->start_controls_section(
			'section_style_box',
			[
				'label' => esc_html__( 'Box', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'text_align',
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
				'selectors' => [
					'{{WRAPPER}} .mn-author-box' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'box_background_color',
			[
				'label' => esc_html__( 'Background Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-author-box' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'box_border',
				'selector' => '{{WRAPPER}} .mn-author-box',
			]
		);

		$this->add_responsive_control(
			'box_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-author-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'selector' => '{{WRAPPER}} .mn-author-box',
			]
		);

		$this->add_responsive_control(
			'box_padding',
			[
				'label' => esc_html__( 'Padding', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-author-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Style - Avatar Section
		$this->start_controls_section(
			'section_style_avatar',
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
						'max' => 300,
					],
				],
				'default' => [
					'size' => 96,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-author-avatar img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'avatar_border',
				'selector' => '{{WRAPPER}} .mn-author-avatar img',
			]
		);

		$this->add_responsive_control(
			'avatar_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mn-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mn-author-avatar img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'avatar_spacing',
			[
				'label' => esc_html__( 'Spacing', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.mn-author-layout-left .mn-author-avatar' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.mn-author-layout-right .mn-author-avatar' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.mn-author-layout-top .mn-author-avatar' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Style - Name Section
		$this->start_controls_section(
			'section_style_name',
			[
				'label' => esc_html__( 'Name', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_name' => 'yes',
				],
			]
		);

		$this->add_control(
			'name_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-author-name' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'name_typography',
				'selector' => '{{WRAPPER}} .mn-author-name',
			]
		);

		$this->add_responsive_control(
			'name_spacing',
			[
				'label' => esc_html__( 'Spacing', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mn-author-name' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Style - Biography Section
		$this->start_controls_section(
			'section_style_biography',
			[
				'label' => esc_html__( 'Biography', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_biography' => 'yes',
				],
			]
		);

		$this->add_control(
			'biography_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-author-biography' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'biography_typography',
				'selector' => '{{WRAPPER}} .mn-author-biography',
			]
		);

		$this->add_responsive_control(
			'biography_spacing',
			[
				'label' => esc_html__( 'Spacing', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mn-author-biography' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Style - Link Section
		$this->start_controls_section(
			'section_style_link',
			[
				'label' => esc_html__( 'Link', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_link' => 'yes',
					'link_to!' => 'none',
				],
			]
		);

		$this->add_control(
			'link_color',
			[
				'label' => esc_html__( 'Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-author-link' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'link_hover_color',
			[
				'label' => esc_html__( 'Hover Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-author-link:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'link_typography',
				'selector' => '{{WRAPPER}} .mn-author-link',
			]
		);

		$this->end_controls_section();

		// Style - Social Media Section
		$this->start_controls_section(
			'section_style_social',
			[
				'label' => esc_html__( 'Social Media', 'mn-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_social_media' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'social_icon_size',
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
					'size' => 16,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-author-social a' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mn-author-social a svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'social_icon_spacing',
			[
				'label' => esc_html__( 'Icon Spacing', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'size' => 10,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .mn-author-social a:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'social_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-author-social a' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-author-social a svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'social_icon_hover_color',
			[
				'label' => esc_html__( 'Icon Hover Color', 'mn-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mn-author-social a:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mn-author-social a:hover svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'social_spacing',
			[
				'label' => esc_html__( 'Top Spacing', 'mn-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mn-author-social' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Get list of authors
	 */
	private function get_authors() {
		$users = get_users( [
			'who' => 'authors',
			'orderby' => 'display_name',
		] );

		$options = [];
		foreach ( $users as $user ) {
			$options[ $user->ID ] = $user->display_name;
		}

		return $options;
	}

	/**
	 * Get author ID based on settings
	 */
	private function get_author_id( $settings ) {
		if ( $settings['author_source'] === 'custom' && ! empty( $settings['custom_author_id'] ) ) {
			return $settings['custom_author_id'];
		}

		// Get current post author
		$post = get_post();
		return $post ? $post->post_author : get_current_user_id();
	}

	/**
	 * Get author social media links
	 */
	private function get_author_social_links( $author_id ) {
		$social_links = [];

		// Facebook
		$facebook = get_user_meta( $author_id, 'facebook', true );
		if ( ! empty( $facebook ) ) {
			$social_links['facebook'] = [
				'url' => $facebook,
				'icon' => 'fab fa-facebook',
			];
		}

		// LinkedIn
		$linkedin = get_user_meta( $author_id, 'linkedin', true );
		if ( ! empty( $linkedin ) ) {
			$social_links['linkedin'] = [
				'url' => $linkedin,
				'icon' => 'fab fa-linkedin',
			];
		}

		// Website
		$website = get_user_meta( $author_id, 'website', true );
		if ( empty( $website ) ) {
			$website = get_the_author_meta( 'user_url', $author_id );
		}
		if ( ! empty( $website ) ) {
			$social_links['website'] = [
				'url' => $website,
				'icon' => 'fas fa-globe',
			];
		}

		// Instagram
		$instagram = get_user_meta( $author_id, 'instagram', true );
		if ( ! empty( $instagram ) ) {
			$social_links['instagram'] = [
				'url' => $instagram,
				'icon' => 'fab fa-instagram',
			];
		}

		// YouTube
		$youtube = get_user_meta( $author_id, 'youtube', true );
		if ( ! empty( $youtube ) ) {
			$social_links['youtube'] = [
				'url' => $youtube,
				'icon' => 'fab fa-youtube',
			];
		}

		return $social_links;
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$author_id = $this->get_author_id( $settings );

		if ( ! $author_id ) {
			return;
		}

		$author_name = get_the_author_meta( 'display_name', $author_id );
		$author_bio = get_the_author_meta( 'description', $author_id );
		$author_url = get_author_posts_url( $author_id );
		$author_website = get_the_author_meta( 'user_url', $author_id );

		// Determine link URL
		$link_url = '';
		if ( $settings['link_to'] === 'archive' ) {
			$link_url = $author_url;
		} elseif ( $settings['link_to'] === 'website' && ! empty( $author_website ) ) {
			$link_url = $author_website;
		}

		// Layout class
		$layout_class = 'mn-author-layout-' . $settings['author_layout'];
		$this->add_render_attribute( 'wrapper', 'class', [ 'mn-author-box', $layout_class ] );

		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<?php if ( $settings['show_avatar'] === 'yes' ) : ?>
				<div class="mn-author-avatar">
					<?php echo get_avatar( $author_id, 96 ); ?>
				</div>
			<?php endif; ?>

			<div class="mn-author-content">
				<?php if ( $settings['show_name'] === 'yes' ) : ?>
					<h3 class="mn-author-name"><?php echo esc_html( $author_name ); ?></h3>
				<?php endif; ?>

				<?php if ( $settings['show_biography'] === 'yes' && ! empty( $author_bio ) ) : ?>
					<div class="mn-author-biography"><?php echo wp_kses_post( wpautop( $author_bio ) ); ?></div>
				<?php endif; ?>

				<?php if ( $settings['show_social_media'] === 'yes' ) : ?>
					<?php
					$social_links = $this->get_author_social_links( $author_id );
					if ( ! empty( $social_links ) ) :
					?>
						<div class="mn-author-social">
							<?php foreach ( $social_links as $platform => $data ) : ?>
								<a href="<?php echo esc_url( $data['url'] ); ?>" target="_blank" rel="noopener noreferrer" title="<?php echo esc_attr( ucfirst( $platform ) ); ?>">
									<i class="<?php echo esc_attr( $data['icon'] ); ?>"></i>
								</a>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				<?php endif; ?>

				<?php if ( $settings['show_link'] === 'yes' && ! empty( $link_url ) ) : ?>
					<a href="<?php echo esc_url( $link_url ); ?>" class="mn-author-link">
						<?php echo esc_html( $settings['link_text'] ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}
