<?php
namespace lsx\business_directory\classes;

/**
 * LSX Starter Plugin Post Class.
 *
 * @package lsx-business-directory
 */
class Post_Type {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx\business_directory\classes\Post_Type()
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
		// configure our custom post types in here
		$this->enable_post_types();
		add_filter( 'lsx_business_directory_post_types', array( $this, 'enable_post_types' ) );
		// foreach ( $this->post_types as $post_type ) {
		// require_once LSX_BD_PATH . 'classes/class-' . $post_type . '.php';
		// $classname        = ucwords( $post_type );
		// $this->$post_type = call_user_func_array( '\\lsx_business_directory\classes\\' . $classname . '::get_instance', array() );
		// }
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\business_directory\classes\Post_Type()    A single instance of this class.
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
			'custom-post-type',
		);
		return $post_types;
	}
}
