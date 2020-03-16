<?php
namespace lsx\business_directory\classes;

/**
 * Contains the recipe post type
 *
 * @package lsx-business-directory
 */
class Business_Directory {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx\business_directory\classes\Business_Directory()
	 */
	protected static $instance = null;

	/**
	 * Holds post_type slug used as an index
	 *
	 * @since 1.0.0
	 *
	 * @var      string
	 */
	public $slug = 'business-directory';

	/**
	 * Holds the prefix for the fields
	 *
	 * @since 1.0.0
	 *
	 * @var      string
	 */
	public $prefix = 'lsx_bd';

	/**
	 * Contructor
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'init', array( $this, 'register_industry_taxonomy' ) );
		add_action( 'init', array( $this, 'register_region_taxonomy' ) );

		// Register the custom fields.
		add_action( 'cmb2_init', array( $this, 'register_address_custom_fields' ), 10 );
		add_action( 'cmb2_init', array( $this, 'register_branches_custom_fields' ), 15 );
		add_action( 'cmb2_init', array( $this, 'register_contact_custom_fields' ), 20 );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\business_directory\classes\Business_Directory()    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Register the post type.
	 */
	public function register_post_type() {
		$labels = array(
			'name'               => esc_html_x( 'Business Directory', 'post type general name', 'lsx-business-directory' ),
			'singular_name'      => esc_html_x( 'Directory', 'post type general name', 'lsx-business-directory' ),
			'add_new'            => esc_html__( 'Add Business', 'lsx-business-directory' ),
			'add_new_item'       => esc_html__( 'Add New Business', 'lsx-business-directory' ),
			'edit_item'          => esc_html__( 'Edit Business', 'lsx-business-directory' ),
			'new_item'           => esc_html__( 'New Business', 'lsx-business-directory' ),
			'all_items'          => esc_html__( 'All Businesses', 'lsx-business-directory' ),
			'view_item'          => esc_html__( 'View Business', 'lsx-business-directory' ),
			'search_items'       => esc_html__( 'Search Businesses', 'lsx-business-directory' ),
			'not_found'          => esc_html__( 'No businesses defined', 'lsx-business-directory' ),
			'not_found_in_trash' => esc_html__( 'No businesses in the trash', 'lsx-business-directory' ),
			'parent_item_colon'  => '',
			'menu_name'          => esc_html__( 'Business Directory', 'lsx-business-directory' ),
		);

		$supports = array(
			'title',
			'editor',
			'thumbnail',
			'excerpt',
			'custom-fields',
		);

		$details = array(
			'labels'              => $labels,
			'public'              => true,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_icon'           => 'dashicons-list-view',
			'query_var'           => true,
			'rewrite'             => array(
				'slug' => 'listing',
			),
			'exclude_from_search' => false,
			'capability_type'     => 'page',
			'has_archive'         => 'listings',
			'hierarchical'        => false,
			'menu_position'       => null,
			'supports'            => $supports,
		);

		register_post_type( $this->slug, $details );
	}

	/**
	 * Registers the Industry taxonomy for the Business Directory.
	 */
	public function register_industry_taxonomy() {
		$labels = array(
			'name'              => esc_html__( 'Industry', 'lsx-business-directory' ),
			'singular_name'     => esc_html__( 'Industry', 'lsx-business-directory' ),
			'search_items'      => esc_html__( 'Search Industries', 'lsx-business-directory' ),
			'all_items'         => esc_html__( 'Industries', 'lsx-business-directory' ),
			'parent_item'       => esc_html__( 'Parent Industry', 'lsx-business-directory' ),
			'parent_item_colon' => esc_html__( 'Parent Industry:', 'lsx-business-directory' ),
			'edit_item'         => esc_html__( 'Edit Industry', 'lsx-business-directory' ),
			'update_item'       => esc_html__( 'Update Industry', 'lsx-business-directory' ),
			'add_new_item'      => esc_html__( 'Add New Industry', 'lsx-business-directory' ),
			'new_item_name'     => esc_html__( 'New Industry', 'lsx-business-directory' ),
			'menu_name'         => esc_html__( 'Industries', 'lsx-business-directory' ),
		);
		$details = array(
			'labels'              => $labels,
			'hierarchical'        => true,
			'show_ui'             => true,
			'public'              => true,
			'exclude_from_search' => true,
			'show_admin_column'   => true,
			'query_var'           => true,
			'rewrite'             => array( 'industry' ),
		);
		register_taxonomy( 'industry', array( $this->slug ), $details );
	}

	/**
	 * Registers the Industry region for the Business Directory.
	 */
	public function register_region_taxonomy() {
		$labels = array(
			'name'              => esc_html__( 'Region', 'lsx-business-directory' ),
			'singular_name'     => esc_html__( 'Region', 'lsx-business-directory' ),
			'search_items'      => esc_html__( 'Search Regions', 'lsx-business-directory' ),
			'all_items'         => esc_html__( 'Regions', 'lsx-business-directory' ),
			'parent_item'       => esc_html__( 'Parent Region', 'lsx-business-directory' ),
			'parent_item_colon' => esc_html__( 'Parent Region:', 'lsx-business-directory' ),
			'edit_item'         => esc_html__( 'Edit Region', 'lsx-business-directory' ),
			'update_item'       => esc_html__( 'Update Region', 'lsx-business-directory' ),
			'add_new_item'      => esc_html__( 'Add New Region', 'lsx-business-directory' ),
			'new_item_name'     => esc_html__( 'New Region', 'lsx-business-directory' ),
			'menu_name'         => esc_html__( 'Regions', 'lsx-business-directory' ),
		);
		$details = array(
			'labels'              => $labels,
			'hierarchical'        => true,
			'show_ui'             => true,
			'public'              => true,
			'exclude_from_search' => true,
			'show_admin_column'   => true,
			'query_var'           => true,
			'rewrite'             => array( 'region' ),
		);
		register_taxonomy( 'region', array( $this->slug ), $details );
	}

	/**
	 * Adds the post type to the different arrays.
	 *
	 * @param array $post_types
	 * @return array
	 */
	public function enable_post_type( $post_types = array() ) {
		$post_types[] = $this->slug;
		return $post_types;
	}

	/**
	 * Registers the Business Directory address custom fields.
	 *
	 * @return void
	 */
	public function register_address_custom_fields() {
		$cmb_address = new_cmb2_box(
			array(
				'id'           => $this->prefix . '_address_metabox',
				'title'        => esc_html__( 'Business Address', 'lsx-business-directory' ),
				'object_types' => array( 'business-directory' ),
			)
		);

		// TODO: Google Maps Search
		$cmb_address->add_field(
			array(
				'name' => esc_html__( 'Google Maps Search', 'lsx-business-directory' ),
				'id'   => $this->prefix . '_address_google_maps_search',
				'type' => 'text',
			)
		);

		$cmb_address->add_field(
			array(
				'name' => esc_html__( 'Complex Name / Business Park / Street Number', 'lsx-business-directory' ),
				'id'   => $this->prefix . '_address_street_number',
				'type' => 'text',
			)
		);

		$cmb_address->add_field(
			array(
				'name' => esc_html__( 'Street Name', 'lsx-business-directory' ),
				'id'   => $this->prefix . '_address_street_name',
				'type' => 'text',
			)
		);

		$cmb_address->add_field(
			array(
				'name' => esc_html__( 'Suburb', 'lsx-business-directory' ),
				'id'   => $this->prefix . '_address_suburb',
				'type' => 'text',
			)
		);

		$cmb_address->add_field(
			array(
				'name' => esc_html__( 'City', 'lsx-business-directory' ),
				'id'   => $this->prefix . '_address_city',
				'type' => 'text',
			)
		);

		$cmb_address->add_field(
			array(
				'name' => esc_html__( 'Postal Code', 'lsx-business-directory' ),
				'id'   => $this->prefix . '_address_postal_code',
				'type' => 'text',
			)
		);

		$cmb_address->add_field(
			array(
				'name'             => esc_html__( 'Country', 'lsx-business-directory' ),
				'id'               => $this->prefix . '_address_country',
				'type'             => 'select',
				'show_option_none' => 'Choose a Country',
				'default'          => 'ZA',
				'options'          => lsx_bd_get_country_options(),
			)
		);
		$cmb_address->add_field(
			array(
				'name' => esc_html__( 'State / Province', 'lsx-business-directory' ),
				'id'   => $this->prefix . '_address_province',
				'type' => 'text',
			)
		);
	}

	/**
	 * Registers the Business Directory branches custom fields.
	 *
	 * @return void
	 */
	public function register_branches_custom_fields() {
		$cmb_address = new_cmb2_box(
			array(
				'id'           => $this->prefix . '_branches_metabox',
				'title'        => esc_html__( 'Business Branches', 'lsx-business-directory' ),
				'object_types' => array( 'business-directory' ),
			)
		);

		$branches_group_field_id = $cmb_address->add_field(
			array(
				'id'         => $this->prefix . '_business_branches',
				'type'       => 'group',
				'repeatable' => true,
				'options'    => array(
					'group_title'    => esc_html__( 'Branch {#}', 'lsx-business-directory' ), // since version 1.1.4, {#} gets replaced by row number
					'add_button'     => esc_html__( 'Add Another Branch', 'lsx-business-directory' ),
					'remove_button'  => esc_html__( 'Remove Branch', 'lsx-business-directory' ),
					'sortable'       => true,
					'closed'         => true, // true to have the groups closed by default
					'remove_confirm' => esc_html__( 'Are you sure you want to remove thie Branch?', 'lsx-business-directory' ),
				),
			)
		);

		// Id's for group's fields only need to be unique for the group. Prefix is not needed.
		$cmb_address->add_group_field(
			$branches_group_field_id,
			array(
				'name' => esc_html__( 'Branch Name', 'lsx-business-directory' ),
				'id'   => 'branch_name',
				'type' => 'text',
			)
		);

		$cmb_address->add_group_field(
			$branches_group_field_id,
			array(
				'name' => esc_html__( 'Branch Telephone', 'lsx-business-directory' ),
				'id'   => 'branch_phone',
				'type' => 'text',
			)
		);

		$cmb_address->add_group_field(
			$branches_group_field_id,
			array(
				'name' => esc_html__( 'Branch Email', 'lsx-business-directory' ),
				'id'   => 'branch_email',
				'type' => 'text_email',
			)
		);

		$cmb_address->add_group_field(
			$branches_group_field_id,
			array(
				'name' => esc_html__( 'Branch Website', 'lsx-business-directory' ),
				'id'   => 'branch_website',
				'type' => 'text_url',
			)
		);

		// TODO: Google Maps Search.
		$cmb_address->add_group_field(
			$branches_group_field_id,
			array(
				'name' => esc_html__( 'Branch Google Maps Search', 'lsx-business-directory' ),
				'id'   => 'branch_google_maps_search',
				'type' => 'text',
			)
		);
	}

	/**
	 * Registers the Business Directory contact custom fields.
	 *
	 * @return void
	 */
	public function register_contact_custom_fields() {
		$cmb_contact = new_cmb2_box(
			array(
				'id'           => $this->prefix . '_contact_metabox',
				'title'        => esc_html__( 'Business Contact Details', 'lsx-business-directory' ),
				'object_types' => array( 'business-directory' ),
			)
		);

		$cmb_contact->add_field(
			array(
				'name' => esc_html__( 'Primary Email.', 'lsx-business-directory' ),
				'id'   => $this->prefix . '_primary_email',
				'type' => 'text_email',
			)
		);

		$cmb_contact->add_field(
			array(
				'name' => esc_html__( 'Secondary Email.', 'lsx-business-directory' ),
				'id'   => $this->prefix . '_secondary_email',
				'type' => 'text_email',
			)
		);

		$cmb_contact->add_field(
			array(
				'name' => esc_html__( 'Primary Phone.', 'lsx-business-directory' ),
				'id'   => $this->prefix . '_primary_phone',
				'type' => 'text',
			)
		);

		$cmb_contact->add_field(
			array(
				'name' => esc_html__( 'Secondary Phone.', 'lsx-business-directory' ),
				'id'   => $this->prefix . '_secondary_phone',
				'type' => 'text',
			)
		);

		$cmb_contact->add_field(
			array(
				'name' => esc_html__( 'Fax.', 'lsx-business-directory' ),
				'id'   => $this->prefix . '_fax',
				'type' => 'text',
			)
		);

		$cmb_contact->add_field(
			array(
				'name' => esc_html__( 'Website.', 'lsx-business-directory' ),
				'id'   => $this->prefix . '_website',
				'type' => 'text_url',
			)
		);
	}
}
