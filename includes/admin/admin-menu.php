<?php
namespace MN_Elements\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * MN Elements Admin Menu
 *
 * Handles main admin menu and submenus for MN Elements
 *
 * @since 1.4.1
 */
class Admin_Menu {

	/**
	 * Instance
	 *
	 * @since 1.4.1
	 * @access private
	 * @static
	 *
	 * @var Admin_Menu The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Menu slug
	 *
	 * @since 1.4.1
	 * @access private
	 * @var string
	 */
	private $menu_slug = 'mn-elements';

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.4.1
	 * @access public
	 * @static
	 *
	 * @return Admin_Menu An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor
	 *
	 * @since 1.4.1
	 * @access public
	 */
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ] );
	}

	/**
	 * Add admin menu
	 *
	 * @since 1.4.1
	 * @access public
	 */
	public function add_admin_menu() {
		// Main menu
		add_menu_page(
			esc_html__( 'MN Elements', 'mn-elements' ),
			esc_html__( 'MN Elements', 'mn-elements' ),
			'manage_options',
			$this->menu_slug,
			[ $this, 'render_dashboard_page' ],
			$this->get_menu_icon(),
			58.5 // Position after Elementor (58) but before Appearance (59)
		);

		// Dashboard submenu (rename the first item)
		add_submenu_page(
			$this->menu_slug,
			esc_html__( 'Dashboard', 'mn-elements' ),
			esc_html__( 'Dashboard', 'mn-elements' ),
			'manage_options',
			$this->menu_slug,
			[ $this, 'render_dashboard_page' ]
		);

		// Element Manager submenu
		add_submenu_page(
			$this->menu_slug,
			esc_html__( 'Element Manager', 'mn-elements' ),
			esc_html__( 'Element Manager', 'mn-elements' ),
			'manage_options',
			'mn-elements-manager',
			[ $this, 'render_element_manager_page' ]
		);

		// Settings submenu
		add_submenu_page(
			$this->menu_slug,
			esc_html__( 'Settings', 'mn-elements' ),
			esc_html__( 'Settings', 'mn-elements' ),
			'manage_options',
			'mn-elements-settings',
			[ $this, 'render_settings_page' ]
		);

		// System Info submenu
		add_submenu_page(
			$this->menu_slug,
			esc_html__( 'System Info', 'mn-elements' ),
			esc_html__( 'System Info', 'mn-elements' ),
			'manage_options',
			'mn-elements-system-info',
			[ $this, 'render_system_info_page' ]
		);
	}

	/**
	 * Get menu icon
	 *
	 * @since 1.4.1
	 * @access private
	 * @return string
	 */
	private function get_menu_icon() {
		// SVG icon for MN Elements
		return 'data:image/svg+xml;base64,' . base64_encode(
			'<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M2 2h16v16H2V2z" stroke="#9CA3AF" stroke-width="1.5" fill="none"/>
				<path d="M6 6h8v8H6V6z" stroke="#9CA3AF" stroke-width="1.5" fill="none"/>
				<circle cx="10" cy="10" r="2" fill="#9CA3AF"/>
				<path d="M2 10h4M14 10h4M10 2v4M10 14v4" stroke="#9CA3AF" stroke-width="1.5"/>
			</svg>'
		);
	}

	/**
	 * Enqueue admin scripts
	 *
	 * @since 1.4.1
	 * @access public
	 */
	public function enqueue_admin_scripts( $hook ) {
		// Only load on MN Elements admin pages
		if ( strpos( $hook, 'mn-elements' ) === false && strpos( $hook, 'mn_elements' ) === false ) {
			return;
		}

		wp_enqueue_style(
			'mn-elements-admin',
			MN_ELEMENTS_URL . 'assets/css/admin.css',
			[],
			MN_ELEMENTS_VERSION
		);

		wp_enqueue_script(
			'mn-elements-admin',
			MN_ELEMENTS_URL . 'assets/js/admin.js',
			[ 'jquery' ],
			MN_ELEMENTS_VERSION,
			true
		);

		wp_localize_script(
			'mn-elements-admin',
			'mnElementsAdmin',
			[
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce' => wp_create_nonce( 'mn_elements_admin_nonce' ),
				'hook' => $hook, // Debug info
				'strings' => [
					'save' => esc_html__( 'Save Settings', 'mn-elements' ),
					'saving' => esc_html__( 'Saving...', 'mn-elements' ),
					'saved' => esc_html__( 'Settings saved successfully!', 'mn-elements' ),
					'error' => esc_html__( 'An error occurred while saving settings.', 'mn-elements' ),
					'total' => esc_html__( 'Total Widgets', 'mn-elements' ),
					'active' => esc_html__( 'Active', 'mn-elements' ),
					'inactive' => esc_html__( 'Inactive', 'mn-elements' ),
				],
			]
		);
	}

	/**
	 * Render dashboard page
	 *
	 * @since 1.4.1
	 * @access public
	 */
	public function render_dashboard_page() {
		$active_widgets = get_option( 'mn_elements_active_widgets', [] );
		$total_widgets = 10; // Total available widgets
		$active_count = count( $active_widgets );
		?>
		<div class="wrap mn-elements-admin">
			<h1><?php echo esc_html__( 'MN Elements Dashboard', 'mn-elements' ); ?></h1>
			
			<div class="mn-elements-dashboard">
				<div class="mn-elements-welcome-panel">
					<div class="mn-elements-welcome-panel-content">
						<h2><?php echo esc_html__( 'Welcome to MN Elements', 'mn-elements' ); ?></h2>
						<p class="about-description">
							<?php echo esc_html__( 'Powerful collection of custom widgets and effects for Elementor to enhance your website with stunning animations and controls.', 'mn-elements' ); ?>
						</p>
						<div class="mn-elements-welcome-panel-column-container">
							<div class="mn-elements-welcome-panel-column">
								<h3><?php echo esc_html__( 'Quick Stats', 'mn-elements' ); ?></h3>
								<ul>
									<li><strong><?php echo $active_count; ?></strong> <?php echo esc_html__( 'Active Widgets', 'mn-elements' ); ?></li>
									<li><strong><?php echo $total_widgets; ?></strong> <?php echo esc_html__( 'Total Widgets', 'mn-elements' ); ?></li>
									<li><strong><?php echo MN_ELEMENTS_VERSION; ?></strong> <?php echo esc_html__( 'Plugin Version', 'mn-elements' ); ?></li>
								</ul>
							</div>
							<div class="mn-elements-welcome-panel-column">
								<h3><?php echo esc_html__( 'Quick Actions', 'mn-elements' ); ?></h3>
								<ul>
									<li><a href="<?php echo admin_url( 'admin.php?page=mn-elements-manager' ); ?>" class="button button-primary"><?php echo esc_html__( 'Manage Widgets', 'mn-elements' ); ?></a></li>
									<li><a href="<?php echo admin_url( 'admin.php?page=mn-elements-settings' ); ?>" class="button"><?php echo esc_html__( 'Plugin Settings', 'mn-elements' ); ?></a></li>
									<li><a href="<?php echo admin_url( 'admin.php?page=mn-elements-system-info' ); ?>" class="button"><?php echo esc_html__( 'System Info', 'mn-elements' ); ?></a></li>
								</ul>
							</div>
							<div class="mn-elements-welcome-panel-column">
								<h3><?php echo esc_html__( 'Need Help?', 'mn-elements' ); ?></h3>
								<ul>
									<li><a href="https://manakreatif.com/docs/mn-elements" target="_blank"><?php echo esc_html__( 'Documentation', 'mn-elements' ); ?></a></li>
									<li><a href="https://manakreatif.com/support" target="_blank"><?php echo esc_html__( 'Support Forum', 'mn-elements' ); ?></a></li>
									<li><a href="https://github.com/manakreatif/mn-elements" target="_blank"><?php echo esc_html__( 'GitHub Repository', 'mn-elements' ); ?></a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>

				<div class="mn-elements-dashboard-widgets">
					<div class="mn-elements-dashboard-widget">
						<h3><?php echo esc_html__( 'Widget Status', 'mn-elements' ); ?></h3>
						<div class="mn-elements-widget-status">
							<div class="mn-elements-status-item">
								<span class="mn-elements-status-count active"><?php echo $active_count; ?></span>
								<span class="mn-elements-status-label"><?php echo esc_html__( 'Active', 'mn-elements' ); ?></span>
							</div>
							<div class="mn-elements-status-item">
								<span class="mn-elements-status-count inactive"><?php echo $total_widgets - $active_count; ?></span>
								<span class="mn-elements-status-label"><?php echo esc_html__( 'Inactive', 'mn-elements' ); ?></span>
							</div>
						</div>
						<p>
							<a href="<?php echo admin_url( 'admin.php?page=mn-elements-manager' ); ?>" class="button">
								<?php echo esc_html__( 'Manage Widgets', 'mn-elements' ); ?>
							</a>
						</p>
					</div>

					<div class="mn-elements-dashboard-widget">
						<h3><?php echo esc_html__( 'System Status', 'mn-elements' ); ?></h3>
						<div class="mn-elements-system-status">
							<div class="mn-elements-status-item">
								<span class="mn-elements-status-indicator <?php echo defined( 'ELEMENTOR_VERSION' ) ? 'active' : 'inactive'; ?>"></span>
								<span class="mn-elements-status-text">
									<?php echo defined( 'ELEMENTOR_VERSION' ) ? esc_html__( 'Elementor Active', 'mn-elements' ) : esc_html__( 'Elementor Required', 'mn-elements' ); ?>
								</span>
							</div>
							<div class="mn-elements-status-item">
								<span class="mn-elements-status-indicator active"></span>
								<span class="mn-elements-status-text"><?php echo esc_html__( 'MN Elements Active', 'mn-elements' ); ?></span>
							</div>
						</div>
						<p>
							<a href="<?php echo admin_url( 'admin.php?page=mn-elements-system-info' ); ?>" class="button">
								<?php echo esc_html__( 'View System Info', 'mn-elements' ); ?>
							</a>
						</p>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render element manager page
	 *
	 * @since 1.4.1
	 * @access public
	 */
	public function render_element_manager_page() {
		// Include Element Manager if it exists
		if ( class_exists( '\MN_Elements\Admin\Element_Manager' ) ) {
			$element_manager = \MN_Elements\Admin\Element_Manager::instance();
			$element_manager->render_admin_page();
		} else {
			?>
			<div class="wrap">
				<h1><?php echo esc_html__( 'Element Manager', 'mn-elements' ); ?></h1>
				<div class="notice notice-error">
					<p><?php echo esc_html__( 'Element Manager class not found. Please check your installation.', 'mn-elements' ); ?></p>
				</div>
			</div>
			<?php
		}
	}

	/**
	 * Render settings page
	 *
	 * @since 1.4.1
	 * @access public
	 */
	public function render_settings_page() {
		?>
		<div class="wrap mn-elements-admin">
			<h1><?php echo esc_html__( 'MN Elements Settings', 'mn-elements' ); ?></h1>
			<p><?php echo esc_html__( 'Configure global settings for MN Elements plugin.', 'mn-elements' ); ?></p>

			<form method="post" action="options.php">
				<?php
				settings_fields( 'mn_elements_settings' );
				do_settings_sections( 'mn_elements_settings' );
				?>
				
				<table class="form-table">
					<tr>
						<th scope="row"><?php echo esc_html__( 'Load Assets', 'mn-elements' ); ?></th>
						<td>
							<fieldset>
								<label for="mn_elements_load_css">
									<input name="mn_elements_load_css" type="checkbox" id="mn_elements_load_css" value="1" <?php checked( get_option( 'mn_elements_load_css', 1 ) ); ?> />
									<?php echo esc_html__( 'Load CSS files', 'mn-elements' ); ?>
								</label>
								<br>
								<label for="mn_elements_load_js">
									<input name="mn_elements_load_js" type="checkbox" id="mn_elements_load_js" value="1" <?php checked( get_option( 'mn_elements_load_js', 1 ) ); ?> />
									<?php echo esc_html__( 'Load JavaScript files', 'mn-elements' ); ?>
								</label>
								<p class="description"><?php echo esc_html__( 'Uncheck to disable loading of plugin assets if you want to load them manually.', 'mn-elements' ); ?></p>
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php echo esc_html__( 'Performance', 'mn-elements' ); ?></th>
						<td>
							<fieldset>
								<label for="mn_elements_optimize_assets">
									<input name="mn_elements_optimize_assets" type="checkbox" id="mn_elements_optimize_assets" value="1" <?php checked( get_option( 'mn_elements_optimize_assets', 0 ) ); ?> />
									<?php echo esc_html__( 'Optimize asset loading', 'mn-elements' ); ?>
								</label>
								<p class="description"><?php echo esc_html__( 'Only load assets on pages that use MN Elements widgets.', 'mn-elements' ); ?></p>
							</fieldset>
						</td>
					</tr>
				</table>

				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Render system info page
	 *
	 * @since 1.4.1
	 * @access public
	 */
	public function render_system_info_page() {
		global $wp_version;
		?>
		<div class="wrap mn-elements-admin">
			<h1><?php echo esc_html__( 'System Information', 'mn-elements' ); ?></h1>
			<p><?php echo esc_html__( 'System information for debugging and support purposes.', 'mn-elements' ); ?></p>

			<div class="mn-elements-system-info">
				<h2><?php echo esc_html__( 'WordPress Environment', 'mn-elements' ); ?></h2>
				<table class="widefat">
					<tbody>
						<tr>
							<td><strong><?php echo esc_html__( 'WordPress Version', 'mn-elements' ); ?></strong></td>
							<td><?php echo $wp_version; ?></td>
						</tr>
						<tr>
							<td><strong><?php echo esc_html__( 'PHP Version', 'mn-elements' ); ?></strong></td>
							<td><?php echo PHP_VERSION; ?></td>
						</tr>
						<tr>
							<td><strong><?php echo esc_html__( 'Server Software', 'mn-elements' ); ?></strong></td>
							<td><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></td>
						</tr>
						<tr>
							<td><strong><?php echo esc_html__( 'Memory Limit', 'mn-elements' ); ?></strong></td>
							<td><?php echo ini_get( 'memory_limit' ); ?></td>
						</tr>
					</tbody>
				</table>

				<h2><?php echo esc_html__( 'Plugin Information', 'mn-elements' ); ?></h2>
				<table class="widefat">
					<tbody>
						<tr>
							<td><strong><?php echo esc_html__( 'MN Elements Version', 'mn-elements' ); ?></strong></td>
							<td><?php echo MN_ELEMENTS_VERSION; ?></td>
						</tr>
						<tr>
							<td><strong><?php echo esc_html__( 'Elementor Version', 'mn-elements' ); ?></strong></td>
							<td><?php echo defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : esc_html__( 'Not Installed', 'mn-elements' ); ?></td>
						</tr>
						<tr>
							<td><strong><?php echo esc_html__( 'Active Widgets', 'mn-elements' ); ?></strong></td>
							<td><?php echo count( get_option( 'mn_elements_active_widgets', [] ) ); ?></td>
						</tr>
					</tbody>
				</table>

				<h2><?php echo esc_html__( 'Theme Information', 'mn-elements' ); ?></h2>
				<table class="widefat">
					<tbody>
						<tr>
							<td><strong><?php echo esc_html__( 'Active Theme', 'mn-elements' ); ?></strong></td>
							<td><?php echo wp_get_theme()->get( 'Name' ) . ' ' . wp_get_theme()->get( 'Version' ); ?></td>
						</tr>
						<tr>
							<td><strong><?php echo esc_html__( 'Parent Theme', 'mn-elements' ); ?></strong></td>
							<td><?php echo wp_get_theme()->parent() ? wp_get_theme()->parent()->get( 'Name' ) : esc_html__( 'None', 'mn-elements' ); ?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<?php
	}

	/**
	 * Get menu slug
	 *
	 * @since 1.4.1
	 * @access public
	 * @return string
	 */
	public function get_menu_slug() {
		return $this->menu_slug;
	}
}

// Initialize
Admin_Menu::instance();
