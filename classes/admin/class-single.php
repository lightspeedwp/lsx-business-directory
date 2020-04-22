<?php
namespace lsx\business_directory\classes\admin;

/**
 * Featured Images for the Business Directory Taxonomies.
 *
 * @package lsx-business-directory
 */
class Single {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx\business_directory\classes\admin\Single()
	 */
	protected static $instance = null;

	/**
	 * Contructor
	 */
	public function __construct() {
		add_action( 'lsx_bd_settings_page', array( $this, 'register_settings_single' ), 1, 1 );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\business_directory\classes\admin\Single()    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Configure Business Directory custom fields for the Settings page Single section.
	 *
	 * @param object $cmb new_cmb2_box().
	 * @return void
	 */
	public function register_settings_single( $cmb ) {
		$cmb->add_field(
			array(
				'id'          => 'settings_single',
				'type'        => 'title',
				'name'        => __( 'Single', 'lsx-business-directory' ),
				'default'     => __( 'Single', 'lsx-business-directory' ),
				'description' => __( 'The settings for the single business directory view.', 'lsx-business-directory' ),
			)
		);
		do_action( 'lsx_bd_settings_section_single', $cmb, 'top' );
		$cmb->add_field(
			array(
				'name'             => esc_html__( 'Enquiry Form', 'lsx-business-directory' ),
				'id'               => 'single_enquiry_form',
				'type'             => 'select',
				'show_option_none' => 'Choose a Form',
				'options'          => \lsx\business_directory\includes\get_available_forms(),
			)
		);
		do_action( 'lsx_bd_settings_section_single', $cmb, 'bottom' );
		$cmb->add_field(
			array(
				'name'    => esc_html__( 'Enable Related Listings', 'lsx-business-directory' ),
				'id'      => 'single_enable_related_listings',
				'type'    => 'checkbox',
				'default' => 1,
			)
		);

		$cmb->add_field(
			array(
				'id'   => 'settings_single_closing',
				'type' => 'tab_closing',
			)
		);
	}
}
