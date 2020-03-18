<?php
namespace lsx\business_directory\classes\frontend;

/**
 * Enquiry Placeholders Class
 *
 * @package lsx-business-directory
 */
class Placeholders {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx\business_directory\classes\frontend\Placeholders()
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function __construct() {
		add_filter( 'lsx_bd_banner_image', array( $this, 'banner_image_placeholder' ), 10, 1 );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\business_directory\classes\frontend\Enquiry()    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Overwrites the banner image with the placeholder if it is set.
	 *
	 * @param  string $banner
	 * @return string
	 */
	public function banner_image_placeholder( $banner = '' ) {
		if ( '' === $banner || false === $banner ) {
			$image_src = \lsx\business_directory\includes\get_placeholder( 'full', 'archive_banner' );
			if ( '' !== $image_src && false !== $image_src ) {
				$banner = $image_src;
			}
		}
		return $banner;
	}
}
