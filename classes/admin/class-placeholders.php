<?php
namespace lsx\business_directory\classes\admin;

/**
 * Featured Images for the Business Directory Taxonomies.
 *
 * @package lsx-business-directory
 */
class Placeholders {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx\business_directory\classes\admin\Placeholders()
	 */
	protected static $instance = null;

	/**
	 * Contructor
	 */
	public function __construct() {
		add_action( 'lsx_bd_settings_section_single', array( $this, 'register_single_placeholder_fields' ), 5, 2 );
		add_action( 'lsx_bd_settings_section_archive', array( $this, 'register_archive_placeholder_fields' ), 4, 2 );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\business_directory\classes\admin\Placeholders()    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Registers the Single tabs placeholder settings.
	 *
	 * @param object $cmb The CMB2() class.
	 * @param string $position either top of bottom.
	 * @return void
	 */
	public function register_single_placeholder_fields( $cmb, $position ) {
		if ( 'bottom' === $position ) {
			$fields   = array();
			$fields[] = \lsx\business_directory\includes\get_featured_image_placeholder_field( 'single' );
			$fields[] = \lsx\business_directory\includes\get_banner_image_placeholder_field( 'single' );
			foreach ( $fields as $field ) {
				$cmb->add_field( $field );
			}
		}
	}

	/**
	 * Registers the Single tabs placeholder settings
	 *
	 * @param object $cmb The CMB2() class.
	 * @param string $position either top of bottom.
	 * @return void
	 */
	public function register_archive_placeholder_fields( $cmb, $position ) {
		if ( 'top' === $position ) {
			$fields   = array();
			$fields[] = \lsx\business_directory\includes\get_industry_icon_placeholder_field( 'archive' );
			$fields[] = \lsx\business_directory\includes\get_industry_icon_hover_placeholder_field( 'archive' );
			$fields[] = \lsx\business_directory\includes\get_location_featured_placeholder_field( 'archive' );
			$fields[] = \lsx\business_directory\includes\get_banner_image_placeholder_field( 'archive' );
			foreach ( $fields as $field ) {
				$cmb->add_field( $field );
			}
		}
	}
}
