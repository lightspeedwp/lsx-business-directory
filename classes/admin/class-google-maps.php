<?php
namespace lsx\business_directory\classes\admin;

/**
 * THis class holds the translation options
 *
 * @package lsx-business-directory
 */
class Google_Maps {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx\business_directory\classes\admin\Google_Maps()
	 */
	protected static $instance = null;

	/**
	 * Contructor
	 */
	public function __construct() {
		add_action( 'lsx_bd_settings_page', array( $this, 'register_settings' ), 25, 1 );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\business_directory\classes\admin\Google_Maps()    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Configure Business Directory custom fields for the Settings page Translations section.
	 *
	 * @param object $cmb new_cmb2_box().
	 * @return void
	 */
	public function register_settings( $cmb ) {
		$cmb->add_field(
			array(
				'id'          => 'settings_google_maps',
				'type'        => 'title',
				'name'        => __( 'Maps', 'lsx-business-directory' ),
				'default'     => __( 'Maps', 'lsx-business-directory' ),
			)
		);
		do_action( 'lsx_bd_settings_section_google_maps', $cmb, 'top' );
		$cmb->add_field(
			array(
				'name'    => esc_html__( 'API Key', 'lsx-business-directory' ),
				'id'      => 'google_maps_api_key',
				'type'    => 'text',
				'default' => '',
			)
		);
		do_action( 'lsx_bd_settings_section_google_maps', $cmb, 'bottom' );
		$cmb->add_field(
			array(
				'id'   => 'settings_google_maps_closing',
				'type' => 'tab_closing',
			)
		);
	}
}
