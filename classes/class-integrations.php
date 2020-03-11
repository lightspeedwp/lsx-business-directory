<?php
namespace lsx\business_directory\classes;

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
	 * @var      object \lsx\business_directory\classes\Integrations()
	 */
	protected static $instance = null;

	/**
	 * Holds the LSX Search integration functions.
	 * 
	 * @var object \lsx\business_directory\classes\Frontend();
	 */
	public $lsx_search;

	/**
	 * This holds the current facet info.
	 *
	 * @var array
	 */
	public $facet_data = array();

	/**
	 * Contructor
	 */
	public function __construct() {
		// Load plugin settings related functionality.
		require_once LSX_BD_PATH . '/classes/integrations/class-lsx-search.php';
		$this->lsx_search = integrations\LSX_Search::get_instance();
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\business_directory\classes\Integrations()    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;

	}
}
