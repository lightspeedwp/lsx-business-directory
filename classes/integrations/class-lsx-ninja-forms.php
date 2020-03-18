<?php
namespace lsx\business_directory\classes\integrations;

/**
 * LSX Ninja Forms Integration class
 *
 * @package lsx-business-directory
 */
class LSX_Ninja_Forms {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx\business_directory\classes\LSX_Gravity()
	 */
	protected static $instance = null;

	/**
	 * Contructor
	 */
	public function __construct() {
		add_filter( 'ninja_forms_loaded', array( $this, 'lsx_bd_ninja_forms_register_merge_tag' ) );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\business_directory\classes\LSX_Gravity()    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Register Merge Tags Call Back.
	 *
	 * @link   http://developer.ninjaforms.com/codex/merge-tags/
	 *
	 * @return void
	 */
	public function lsx_bd_ninja_forms_register_merge_tag() {

	}
}