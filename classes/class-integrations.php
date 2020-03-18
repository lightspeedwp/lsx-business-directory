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
	 * Holds the LSX WPForms integration functions.
	 *
	 * @var object \lsx\business_directory\classes\Frontend();
	 */
	public $lsx_wpforms;

	/**
	 * Holds the LSX Ninja Forms integration functions.
	 *
	 * @var object \lsx\business_directory\classes\Frontend();
	 */
	public $lsx_ninja;

	/**
	 * Holds the LSX Gravity Forms integration functions.
	 *
	 * @var object \lsx\business_directory\classes\Frontend();
	 */
	public $lsx_gravity;

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

		require_once LSX_BD_PATH . '/classes/integrations/class-lsx-wpforms.php';
		$this->lsx_wpforms = integrations\LSX_WPForms::get_instance();

		require_once LSX_BD_PATH . '/classes/integrations/class-lsx-gravity-forms.php';
		$this->lsx_gravity = integrations\LSX_Gravity_Forms::get_instance();

		require_once LSX_BD_PATH . '/classes/integrations/class-lsx-ninja-forms.php';
		$this->lsx_ninja = integrations\LSX_Ninja_Forms::get_instance();

		require_once LSX_BD_PATH . '/classes/integrations/class-caldera-forms.php';
		$this->caldera_forms = integrations\Caldera_Forms::get_instance();
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
