<?php
namespace lsx\business_directory\classes\admin;

/**
 * Banners Frontend Class
 *
 * @package lsx-business-directory
 */
class Banners {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx\business_directory\classes\admin\Banners()
	 */
	protected static $instance = null;

	/**
	 * Contructor
	 */
	public function __construct() {
		add_action( 'lsx_bd_settings_section_archive', array( $this, 'post_type_archive_banner_settings' ), 5, 2 );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\business_directory\classes\admin\Banners()    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Enable Business Directory Search settings only if LSX Search plugin is enabled.
	 *
	 * @param object $cmb The CMB2() class.
	 * @param string $position either top of bottom.
	 * @return void
	 */
	public function post_type_archive_banner_settings( $cmb, $position ) {

		if ( 'top' === $position ) {
			$cmb->add_field(
				array(
					'name' => esc_html__( 'Disable Banner', 'lsx-business-directory' ),
					'id'   => 'archive_banner_disable',
					'type' => 'checkbox',
				)
			);
			$cmb->add_field(
				array(
					'name' => esc_html__( 'Image', 'lsx-business-directory' ),
					'desc' => esc_html__( 'Upload a banner image for to display above your business listing.', 'lsx-business-directory' ),
					'id'   => 'archive_banner',
					'type' => 'file',
				)
			);
			$cmb->add_field(
				array(
					'name'    => esc_html__( 'Colour', 'lsx-business-directory' ),
					'desc'    => esc_html__( 'Choose a background colour to display in case you don\'t have a banner image.', 'lsx-business-directory' ),
					'id'      => 'archive_banner_colour',
					'type'    => 'colorpicker',
					'default' => '#ffffff',
				)
			);
			$cmb->add_field(
				array(
					'name' => esc_html__( 'Title', 'lsx-business-directory' ),
					'desc' => esc_html__( 'Customize the title for your banner.', 'lsx-business-directory' ),
					'id'   => 'archive_banner_title',
					'type' => 'text',
				)
			);
			$cmb->add_field(
				array(
					'name' => esc_html__( 'Subtitle', 'lsx-business-directory' ),
					'desc' => esc_html__( 'Customize the subtitle for your banner, this will display just below your title.', 'lsx-business-directory' ),
					'id'   => 'archive_banner_subtitle',
					'type' => 'text',
				)
			);
		}
	}
}
