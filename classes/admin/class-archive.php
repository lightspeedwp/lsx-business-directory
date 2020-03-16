<?php
namespace lsx\business_directory\classes\admin;

/**
 * Controls the admin for the post type archives.
 *
 * @package lsx-business-directory
 */
class Archive {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx\business_directory\classes\admin\Archive()
	 */
	protected static $instance = null;

	/**
	 * Contructor
	 */
	public function __construct() {
		add_action( 'lsx_bd_settings_page', array( $this, 'configure_settings_archive_fields' ), 2, 1 );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\business_directory\classes\admin\Archive()    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	/**
	 * Configure Business Directory custom fields for the Settings page General section.
	 *
	 * @param object $cmb new_cmb2_box().
	 * @return void
	 */
	public function configure_settings_archive_fields( $cmb ) {
		$cmb->add_field(
			array(
				'id'          => 'settings_archive',
				'type'        => 'title',
				'name'        => __( 'Archive', 'lsx-business-directory' ),
				'default'     => __( 'Archive', 'lsx-business-directory' ),
				'description' => __( 'Business Directory post type archive settings.', 'lsx-business-directory' ),
			)
		);
		do_action( 'lsx_bd_settings_section_archive', $cmb, 'top' );
		$cmb->add_field(
			array(
				'name'             => esc_html__( 'Layout option', 'lsx-business-directory' ),
				'id'               => 'archive_grid_list',
				'type'             => 'radio',
				'show_option_none' => false,
				'options'          => array(
					'grid' => esc_html__( 'Grid', 'lsx-business-directory' ),
					'list' => esc_html__( 'List', 'lsx-business-directory' ),
				),
			)
		);
		do_action( 'lsx_bd_settings_section_archive', $cmb, 'bottom' );
	}
}
