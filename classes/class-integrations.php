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
	 * Holds the LSX WPForms integration functions.
	 *
	 * @var object \lsx\business_directory\classes\integrations\WPForms();
	 */
	public $wpforms;

	/**
	 * Holds the LSX Ninja Forms integration functions.
	 *
	 * @var object \lsx\business_directory\classes\Frontend();
	 */
	public $lsx_ninja;

	/**
	 * Holds the Gravity Forms integration functions.
	 *
	 * @var object \lsx\business_directory\classes\integrations\Gravity_Forms();
	 */
	public $gravity_forms;

	/**
	 * Holds the Caldera Forms integration functions.
	 *
	 * @var object \lsx\business_directory\classes\integrations\Caldera_Forms();
	 */
	public $caldera_forms;

	/**
	 * Holds the Woocommerce integration functions.
	 *
	 * @var object \lsx\business_directory\classes\integrations\Woocommerce();
	 */
	public $woocommerce;

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
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		require_once LSX_BD_PATH . '/classes/integrations/class-wpforms.php';
		$this->wpforms = integrations\WPForms::get_instance();

		require_once LSX_BD_PATH . '/classes/integrations/class-gravity-forms.php';
		$this->gravity_forms = integrations\Gravity_Forms::get_instance();

		require_once LSX_BD_PATH . '/classes/integrations/class-ninja-forms.php';
		$this->ninja_forms = integrations\Ninja_Forms::get_instance();

		require_once LSX_BD_PATH . '/classes/integrations/class-caldera-forms.php';
		$this->caldera_forms = integrations\Caldera_Forms::get_instance();

		require_once LSX_BD_PATH . '/classes/integrations/class-woocommerce.php';
		$this->woocommerce = integrations\Woocommerce::get_instance();
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
