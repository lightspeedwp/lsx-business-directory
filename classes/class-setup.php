<?php
namespace lsx\business_directory\classes;

/**
 * LSX Starter Plugin Admin Class.
 *
 * @package lsx-business-directory
 */
class Setup {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx\business_directory\classes\Setup()
	 */
	protected static $instance = null;

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
		$this->enable_post_types();
		add_filter( 'lsx_business_directory_post_types', array( $this, 'enable_post_types' ) );
		foreach ( $this->post_types as $post_type ) {
			require_once LSX_BD_PATH . 'classes/setup/class-' . $post_type . '.php';
			$classname        = str_replace( ' ', '_', ucwords( str_replace( '-', ' ', $post_type ) ) );
			$this->$post_type = call_user_func_array( '\\lsx\\business_directory\classes\\' . $classname . '::get_instance', array() );
		}
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\business_directory\classes\Setup()    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	/**
	 * Enable our post types
	 *
	 * @return void
	 */
	public function enable_post_types() {
		$this->post_types = array(
			// make sure that only letters and dashes are used (no underscores)
			'business-directory',
		);
		return $this->post_types;
	}
}
