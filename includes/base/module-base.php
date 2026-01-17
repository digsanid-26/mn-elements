<?php

namespace MNElements\Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Base class for MN Elements modules
 *
 * @since 1.1.0
 */
abstract class Module_Base {

	/**
	 * Module constructor.
	 *
	 * @since 1.1.0
	 * @access public
	 */
	public function __construct() {
		// Override in child classes
	}

	/**
	 * Get module name.
	 *
	 * @since 1.1.0
	 * @access public
	 * @abstract
	 */
	abstract public function get_name();
}
