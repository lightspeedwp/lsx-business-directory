<?php
namespace lsx\business_directory\classes\integrations\woocommerce;

/**
 * Handles the admin fields for the WooCommerce integration.
 *
 * @package lsx-business-directory
 */
class Admin {

	/**
	 * Holds class instance
	 *
	 * @var      object \lsx\business_directory\classes\integrations\woocommerce\Admin()
	 */
	protected static $instance = null;

	/**
	 * Contructor
	 */
	public function __construct() {
		add_action( 'lsx_bd_settings_section_translations', array( $this, 'register_translations' ), 10, 2 );
		add_action( 'lsx_bd_settings_page', array( $this, 'register_settings_woocommerce' ), 30, 1 );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\business_directory\classes\integrations\woocommerce\Admin()    A single instance of this class.
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
	public function register_settings_woocommerce( $cmb ) {
		$pages     = get_pages();
		$all_pages = array(
			'' => __( 'Select a page', 'lsx-business-directory' ),
		);
		if ( ! empty( $pages ) ) {
			foreach ( $pages as $page ) {
				$all_pages[ $page->ID ] = $page->post_title;
			}
		}
		$cmb->add_field(
			array(
				'id'          => 'settings_woocommerce',
				'type'        => 'title',
				'name'        => __( 'WooCommerce', 'lsx-business-directory' ),
				'default'     => __( 'WooCommerce', 'lsx-business-directory' ),
				'description' => __( 'Configure your store to sell listings.', 'lsx-business-directory' ),
			)
		);
		do_action( 'lsx_bd_settings_section_woocommerce', $cmb, 'top' );
		$cmb->add_field(
			array(
				'name'        => esc_html__( 'Enable Paid Listings', 'lsx-business-directory' ),
				'id'          => 'woocommerce_enable_checkout',
				'type'        => 'checkbox',
				'description' => __( 'Force customers to purchase a subscription before being able to add a listing.', 'lsx-business-directory' ),
			)
		);
		$cmb->add_field(
			array(
				'name'        => esc_html__( 'Thank You Text', 'lsx-business-directory' ),
				'id'          => 'woocommerce_thank_you_text',
				'type'        => 'textarea',
				'default'     => __( 'Head on over to your <a href="/my-account/">My Account</a> dashboard and add your listing.', 'lsx-business-directory' ),
				'description' => __( 'Display a note to the customer after checkout. This is only shown if the order contained a "listing" product and has a status of "complete"', 'lsx-business-directory' ),
			)
		);
		do_action( 'lsx_bd_settings_section_woocommerce', $cmb, 'bottom' );
		$cmb->add_field(
			array(
				'id'   => 'settings_woocommerce_closing',
				'type' => 'tab_closing',
			)
		);
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
					'name'    => esc_html__( 'Preview Listing Endpoint', 'lsx-business-directory' ),
					'id'      => 'translations_listings_preview_endpoint',
					'type'    => 'text',
					'default' => 'preview-listing',
					'desc'    => __( 'This is the endpoint for the My Account "Preview Listing" page.', 'lsx-business-directory' ),
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
