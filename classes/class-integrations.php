<?php
namespace lsx_business_directory\classes;

/**
 * Contains all the classes for 3rd party Integrations
 *
 * @package lsx-business-directory
 */
class Integrations {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx_business_directory\classes\Integrations()
	 */
	protected static $instance = null;

	/**
	 * Contructor
	 */
	public function __construct() {
		// Initialize CMB2 framework.
		require_once LSX_BD_PATH . 'vendor/cmb2/init.php';
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx_business_directory\classes\Integrations()    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;

	}
}
