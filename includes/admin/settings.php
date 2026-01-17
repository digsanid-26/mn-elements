<?php
namespace MN_Elements\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * MN Elements Settings
 *
 * Handles plugin settings registration and management
 *
 * @since 1.4.1
 */
class Settings {

	/**
	 * Instance
	 *
	 * @since 1.4.1
	 * @access private
	 * @static
	 *
	 * @var Settings The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.4.1
	 * @access public
	 * @static
	 *
	 * @return Settings An instance of the class.
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
		add_action( 'admin_init', [ $this, 'register_settings' ] );
	}

	/**
	 * Register settings
	 *
	 * @since 1.4.1
	 * @access public
	 */
	public function register_settings() {
		// Register settings
		register_setting( 'mn_elements_settings', 'mn_elements_load_css', [
			'type' => 'boolean',
			'default' => true,
			'sanitize_callback' => 'rest_sanitize_boolean',
		] );

		register_setting( 'mn_elements_settings', 'mn_elements_load_js', [
			'type' => 'boolean',
			'default' => true,
			'sanitize_callback' => 'rest_sanitize_boolean',
		] );

		register_setting( 'mn_elements_settings', 'mn_elements_optimize_assets', [
			'type' => 'boolean',
			'default' => false,
			'sanitize_callback' => 'rest_sanitize_boolean',
		] );
	}

	/**
	 * Get setting value
	 *
	 * @since 1.4.1
	 * @access public
	 * @param string $setting_name
	 * @param mixed $default
	 * @return mixed
	 */
	public function get_setting( $setting_name, $default = null ) {
		return get_option( $setting_name, $default );
	}

	/**
	 * Update setting value
	 *
	 * @since 1.4.1
	 * @access public
	 * @param string $setting_name
	 * @param mixed $value
	 * @return bool
	 */
	public function update_setting( $setting_name, $value ) {
		return update_option( $setting_name, $value );
	}

	/**
	 * Delete setting
	 *
	 * @since 1.4.1
	 * @access public
	 * @param string $setting_name
	 * @return bool
	 */
	public function delete_setting( $setting_name ) {
		return delete_option( $setting_name );
	}
}

// Initialize
Settings::instance();
