<?php

/**
 * MN Elements Container Extension
 *
 * @package   mn-elements
 * @author    Manakreatif
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'MN_Elements_Container_Extension' ) ) {

	/**
	 * Define MN_Elements_Container_Extension class
	 */
	class MN_Elements_Container_Extension {

		/**
		 * Container Data
		 *
		 * @var array
		 */
		public $containers_data = array();

		/**
		 * A reference to an instance of this class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    object
		 */
		private static $instance = null;

		/**
		 * Init Handler
		 */
		public function init() {
			// Container extension initialization
			// Future container enhancements can be added here
		}


		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return object
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}
}

/**
 * Returns instance of MN_Elements_Container_Extension
 *
 * @return object
 */
function mn_elements_container_extension() {
	return MN_Elements_Container_Extension::get_instance();
}
