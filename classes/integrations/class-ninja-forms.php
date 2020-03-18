<?php
namespace lsx\business_directory\classes\integrations;

/**
 * LSX Ninja Forms Integration class
 *
 * @package lsx-business-directory
 */
class Ninja_Forms {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx\business_directory\classes\Ninja_Forms()
	 */
	protected static $instance = null;

	/**
	 * Contructor
	 */
	public function __construct() {
		add_filter( 'ninja_forms_loaded', array( $this, 'register_merge_tag' ) );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\business_directory\classes\Ninja_Forms()    A single instance of this class.
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
	public function register_merge_tag() {

	}
}
