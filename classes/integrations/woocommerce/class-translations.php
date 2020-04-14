<?php
namespace lsx\business_directory\classes\integrations\woocommerce;

/**
 * Handles the translations for the my account tabs.
 *
 * @package lsx-business-directory
 */
class Translations {

	/**
	 * Holds class instance
	 *
	 * @var      object \lsx\business_directory\classes\integrations\woocommerce\Translations()
	 */
	protected static $instance = null;

	/**
	 * Contructor
	 */
	public function __construct() {
		add_action( 'lsx_bd_settings_section_translations', array( $this, 'register_translations' ), 10, 2 );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\business_directory\classes\integrations\woocommerce\Translations()    A single instance of this class.
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
	public function register_translations( $cmb, $place ) {
		if ( 'bottom' === $place ) {
			$cmb->add_field(
				array(
					'name'    => esc_html__( 'Listings Endpoint', 'lsx-business-directory' ),
					'id'      => 'translations_listings_endpoint',
					'type'    => 'text',
					'default' => 'listings',
					'desc'    => __( 'This is the endpoint for the My Account "Listings" page.', 'lsx-business-directory' ),
				)
			);
			$cmb->add_field(
				array(
					'name'    => esc_html__( 'Add Listing Endpoint', 'lsx-business-directory' ),
					'id'      => 'translations_listings_add_endpoint',
					'type'    => 'text',
					'default' => 'add-listing',
					'desc'    => __( 'This is the endpoint for the My Account "Add Listing" page.', 'lsx-business-directory' ),
				)
			);
			$cmb->add_field(
				array(
					'name'    => esc_html__( 'Edit Listing Endpoint', 'lsx-business-directory' ),
					'id'      => 'translations_listings_edit_endpoint',
					'type'    => 'text',
					'default' => 'edit-listing',
					'desc'    => __( 'This is the endpoint for the My Account "Edit Listing" page.', 'lsx-business-directory' ),
				)
			);
		}
	}
}
