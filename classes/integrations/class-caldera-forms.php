<?php
namespace lsx\business_directory\classes\integrations;

/**
 * Caldera Forms integration class
 *
 * @package lsx-business-directory
 */
class Caldera_Forms {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx\business_directory\classes\Caldera_Forms()
	 */
	protected static $instance = null;

	/**
	 * Contructor
	 */
	public function __construct() {
		add_filter( 'caldera_forms_do_magic_tag', array( $this, 'process_magic_tag' ), 10, 2 );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\business_directory\classes\Caldera_Forms()    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Process the Caldera Forms Magic Tag.
	 *
	 * @param  string $value
	 * @param  string $magic_tag
	 * @return string
	 */
	public function process_magic_tag( $value, $magic_tag ) {
		// make sure we only act on the right magic tag, and when we have a valid entry (positive integer).
		if ( '{listing_primary_email}' === $magic_tag ) {
			$prefix = 'lsx_bd';
			$value  = get_post_meta( get_the_ID(), $prefix . '_primary_email', true );
		}

		return $value;
	}
}
