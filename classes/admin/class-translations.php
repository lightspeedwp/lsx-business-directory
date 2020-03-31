<?php
namespace lsx\business_directory\classes\admin;

/**
 * THis class holds the translation options
 *
 * @package lsx-business-directory
 */
class Translations {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx\business_directory\classes\admin\Translations()
	 */
	protected static $instance = null;

	/**
	 * Contructor
	 */
	public function __construct() {
		add_action( 'lsx_bd_settings_page', array( $this, 'register_settings_translations' ), 30, 1 );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\business_directory\classes\admin\Translations()    A single instance of this class.
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
	public function register_settings_translations( $cmb ) {
		$cmb->add_field(
			array(
				'id'          => 'settings_translations',
				'type'        => 'title',
				'name'        => __( 'Translations', 'lsx-business-directory' ),
				'default'     => __( 'Translations', 'lsx-business-directory' ),
				'description' => __( 'Change the slugs for your the listings, industry and regions. Once you have saved your settings, open the <a href="/wp-admin/options-permalink.php">permalinks</a> page to flush the rewrite rules.  This is only for the URLs.', 'lsx-business-directory' ),
			)
		);
		do_action( 'lsx_bd_settings_section_translations', $cmb, 'top' );
		$cmb->add_field(
			array(
				'name'    => esc_html__( 'Listing Single', 'lsx-business-directory' ),
				'id'      => 'translations_listing_single_slug',
				'type'    => 'text',
				'default' => 'listing',
			)
		);
		$cmb->add_field(
			array(
				'name'    => esc_html__( 'Listings Archive', 'lsx-business-directory' ),
				'id'      => 'translations_listing_archive_slug',
				'type'    => 'text',
				'default' => 'listings',
			)
		);
		$cmb->add_field(
			array(
				'name'    => esc_html__( 'Industries Archives', 'lsx-business-directory' ),
				'id'      => 'translations_industry_slug',
				'type'    => 'text',
				'default' => 'industry',
			)
		);
		$cmb->add_field(
			array(
				'name'    => esc_html__( 'Locations Archives', 'lsx-business-directory' ),
				'id'      => 'translations_location_slug',
				'type'    => 'text',
				'default' => 'location',
			)
		);
		do_action( 'lsx_bd_settings_section_translations', $cmb, 'bottom' );
		$cmb->add_field(
			array(
				'id'   => 'settings_translations_closing',
				'type' => 'tab_closing',
			)
		);
	}
}
