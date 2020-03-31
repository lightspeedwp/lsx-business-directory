<?php
namespace lsx\business_directory\classes;

/**
 * This class loads the other classes and function files
 *
 * @package lsx-business-directory
 */
class Core {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx\business_directory\classes\Core()
	 */
	protected static $instance = null;

	/**
	 * @var object \lsx\business_directory\classes\Setup();
	 */
	public $setup;

	/**
	 * @var object \lsx\business_directory\classes\Admin();
	 */
	public $admin;

	/**
	 * @var object \lsx\business_directory\classes\Frontend();
	 */
	public $frontend;

	/**
	 * @var object \lsx\business_directory\classes\Integrations();
	 */
	public $integrations;

	/**
	 * The post types available
	 *
	 * @var array
	 */
	public $post_types = array();

	/**
	 * Contructor
	 */
	public function __construct() {
		$this->load_vendors();
		$this->load_includes();
		$this->load_classes();
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\business_directory\classes\Core()    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Loads the variable classes and the static classes.
	 */
	private function load_classes() {
		// Load plugin admin related functionality.
		require_once LSX_BD_PATH . 'classes/class-admin.php';
		$this->admin = Admin::get_instance();

		// Load front-end related functionality.
		require_once LSX_BD_PATH . '/classes/class-frontend.php';
		$this->frontend = Frontend::get_instance();

		// Load 3rd party integrations here.
		require_once LSX_BD_PATH . '/classes/class-integrations.php';
		$this->integrations = Integrations::get_instance();

		// Load plugin settings related functionality.
		require_once LSX_BD_PATH . '/classes/class-setup.php';
		$this->setup = Setup::get_instance();
	}

	/**
	 * Loads the plugin functions.
	 */
	private function load_includes() {
		require_once LSX_BD_PATH . '/includes/helpers.php';
		require_once LSX_BD_PATH . '/includes/getters.php';
		require_once LSX_BD_PATH . '/includes/functions.php';
		require_once LSX_BD_PATH . '/includes/shortcodes.php';
		require_once LSX_BD_PATH . '/includes/template-tags.php';
	}

	/**
	 * Loads the plugin functions.
	 */
	private function load_vendors() {
		// Configure custom fields.
		if ( ! class_exists( 'CMB2' ) ) {
			require_once LSX_BD_PATH . 'vendor/CMB2/init.php';
		}
	}

	/**
	 * Returns the post types currently active
	 *
	 * @return void
	 */
	public function get_post_types() {
		$post_types = apply_filters( 'lsx_business_directory_post_types', $this->post_types );

		foreach ( $post_types as $index => $post_type ) {
			$is_disabled = \cmb2_get_option( 'lsx_bd_options', $post_type . '_disabled', false );

			if ( true === $is_disabled || 1 === $is_disabled || 'on' === $is_disabled ) {
				unset( $post_types[ $index ] );
			}
		}

		return $post_types;
	}
}
