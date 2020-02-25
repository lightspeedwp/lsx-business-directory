<?php
namespace lsx\business_directory\classes;

/**
 * LSX Starter Plugin Admin Class.
 *
 * @package lsx-business-directory
 */
class Setup {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx\business_directory\classes\Setup()
	 */
	protected static $instance = null;

	/**
	 * @var object \lsx\business_directory\classes\Post_Type();
	 */
	public $post_types;

	/**
	 * Contructor
	 */
	public function __construct() {
		// Register custom post types & taxonomies.
		require_once LSX_BD_PATH . 'classes/class-post-type.php';
		$this->post_types = Post_Type::get_instance();
		// Configure custom fields.
		add_action( 'cmb2_init', array( $this, 'configure_business_directory_custom_fields' ) );
		// Configure Settings page.
		add_action( 'cmb2_admin_init', array( $this, 'configure_settings_custom_fields' ) );
		add_action( 'lsx_bd_settings_page', array( $this, 'general_settings' ), 1, 1 );
		add_action( 'cmb2_before_form', array( $this, 'add_description_to_cmb2_settings_page' ) );

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\business_directory\classes\Setup()    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Configure Business Directory custom fields.
	 *
	 * @return void
	 */
	public function configure_business_directory_custom_fields() {
		$prefix = 'businessdirectory';

		$cmb_images = new_cmb2_box(
			array(
				'id'           => $prefix . '_images_metabox',
				'title'        => esc_html__( 'Business Images.', 'lsx-business-directory' ),
				'object_types' => array( 'business-directory' ),
			)
		);

		$cmb_images->add_field(
			array(
				'name' => esc_html__( 'Featured Image', 'lsx-business-directory' ),
				'desc' => esc_html__( 'Featured image for a Business.', 'lsx-business-directory' ),
				'id'   => $prefix . '_business_logo',
				'type' => 'file',
			)
		);

		$cmb_images->add_field(
			array(
				'name' => esc_html__( 'Banner Image', 'lsx-business-directory' ),
				'desc' => esc_html__( 'Banner image for a Business.', 'lsx-business-directory' ),
				'id'   => $prefix . '_business_banner',
				'type' => 'file',
			)
		);

		$cmb_images->add_field(
			array(
				'name'    => esc_html__( 'Banner Colour', 'lsx-business-directory' ),
				'desc'    => esc_html__( 'Banner colour if Banner image is missing.', 'lsx-business-directory' ),
				'id'      => $prefix . '_business_banner_colour',
				'type'    => 'colorpicker',
				'default' => '#ffffff',
			)
		);

		$cmb_address = new_cmb2_box(
			array(
				'id'           => $prefix . '_address_metabox',
				'title'        => esc_html__( 'Business Address.', 'lsx-business-directory' ),
				'object_types' => array( 'business-directory' ),
			)
		);

		// TODO: Google Maps Search
		$cmb_address->add_field(
			array(
				'name' => esc_html__( 'Google Maps Search', 'lsx-business-directory' ),
				'id'   => $prefix . '_business_google_maps_search',
				'type' => 'text',
			)
		);

		$cmb_address->add_field(
			array(
				'name' => esc_html__( 'Complex Name / Business Park / Street Number', 'lsx-business-directory' ),
				'id'   => $prefix . '_business_address_1',
				'type' => 'text',
			)
		);

		$cmb_address->add_field(
			array(
				'name' => esc_html__( 'Street Name', 'lsx-business-directory' ),
				'id'   => $prefix . '_business_address_2',
				'type' => 'text',
			)
		);

		$cmb_address->add_field(
			array(
				'name' => esc_html__( 'Suburb', 'lsx-business-directory' ),
				'id'   => $prefix . '_business_address_3',
				'type' => 'text',
			)
		);

		$cmb_address->add_field(
			array(
				'name' => esc_html__( 'City', 'lsx-business-directory' ),
				'id'   => $prefix . '_business_address_4',
				'type' => 'text',
			)
		);

		$cmb_address->add_field(
			array(
				'name' => esc_html__( 'Postal Code', 'lsx-business-directory' ),
				'id'   => $prefix . '_business_postal_code',
				'type' => 'text',
			)
		);

		$cmb_address->add_field(
			array(
				'name'             => esc_html__( 'Country', 'lsx-business-directory' ),
				'id'               => $prefix . '_business_country',
				'type'             => 'select',
				'show_option_none' => 'Choose a Country',
				'default'          => 'ZA',
				'options'          => get_country_options(),
			)
		);

		$cmb_address->add_field(
			array(
				'name' => esc_html__( 'State / Province', 'lsx-business-directory' ),
				'id'   => $prefix . '_business_province',
				'type' => 'text',
			)
		);

		$branches_group_field_id = $cmb_address->add_field(
			array(
				'id'          => $prefix . '_business_branches',
				'type'        => 'group',
				'description' => esc_html__( 'Business Branches', 'lsx-business-directory' ),
				'repeatable'  => true,
				'options'     => array(
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
			// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
			)
		);

		$cmb_address->add_group_field(
			$branches_group_field_id,
			array(
				'name' => esc_html__( 'Branch Telephone', 'lsx-business-directory' ),
				'id'   => 'branch_phone',
				'type' => 'text',
			// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
			)
		);

		$cmb_address->add_group_field(
			$branches_group_field_id,
			array(
				'name' => esc_html__( 'Branch Email', 'lsx-business-directory' ),
				'id'   => 'branch_email',
				'type' => 'text_email',
			// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
			)
		);

		$cmb_address->add_group_field(
			$branches_group_field_id,
			array(
				'name' => esc_html__( 'Branch Website', 'lsx-business-directory' ),
				'id'   => 'branch_website',
				'type' => 'text_url',
			// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
			)
		);

		// TODO: Google Maps Search.
		$cmb_address->add_group_field(
			$branches_group_field_id,
			array(
				'name' => esc_html__( 'Branch Google Maps Search', 'lsx-business-directory' ),
				'id'   => 'branch_google_maps_search',
				'type' => 'text',
			// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
			)
		);

		$cmb_contact = new_cmb2_box(
			array(
				'id'           => $prefix . '_contact_metabox',
				'title'        => esc_html__( 'Business Contact Details.', 'lsx-business-directory' ),
				'object_types' => array( 'business-directory' ),
			)
		);

		$cmb_contact->add_field(
			array(
				'name' => esc_html__( 'Business Primary Email.', 'lsx-business-directory' ),
				'id'   => $prefix . '_primary_email',
				'type' => 'text_email',
			)
		);

		$cmb_contact->add_field(
			array(
				'name' => esc_html__( 'Business Secondary Email.', 'lsx-business-directory' ),
				'id'   => $prefix . '_secondary_email',
				'type' => 'text_email',
			)
		);

		$cmb_contact->add_field(
			array(
				'name' => esc_html__( 'Business Primary Phone.', 'lsx-business-directory' ),
				'id'   => $prefix . '_primary_phone',
				'type' => 'text',
			)
		);

		$cmb_contact->add_field(
			array(
				'name' => esc_html__( 'Business Secondary Phone.', 'lsx-business-directory' ),
				'id'   => $prefix . '_secondary_phone',
				'type' => 'text',
			)
		);

		$cmb_contact->add_field(
			array(
				'name' => esc_html__( 'Business Fax.', 'lsx-business-directory' ),
				'id'   => $prefix . '_fax',
				'type' => 'text',
			)
		);

		$cmb_contact->add_field(
			array(
				'name' => esc_html__( 'Business Website.', 'lsx-business-directory' ),
				'id'   => $prefix . '_website',
				'type' => 'text_url',
			)
		);
	}

	/**
	 * Configure Business Directory custom fields for the Settings page.
	 *
	 * @return void
	 */
	public function configure_settings_custom_fields() {
		$prefix = 'businessdirectory';

		$cmb = new_cmb2_box(
			array(
				'id'           => $prefix . '_settings',
				'title'        => esc_html__( 'Single', 'lsx-business-directory' ),
				'menu_title'   => esc_html__( 'Settings', 'lsx-business-directory' ), // Falls back to 'title' (above).
				'desc'         => esc_html__( 'field description (optional)', 'lsx-business-directory' ),
				'object_types' => array( 'options-page' ),
				'option_key'   => 'lsx-business-directory-settings', // The option key and admin menu page slug.
				'parent_slug'  => 'edit.php?post_type=business-directory', // Make options page a submenu item of the Business Directory menu.
				'capability'   => 'manage_options', // Cap required to view options-page.
			)
		);

		do_action( 'lsx_bd_settings_page', $cmb );
	}

	/**
	 * Configure custom fields for the general settings.
	 *
	 * @param object $cmb new_cmb2_box().
	 * @return void
	 */
	public function general_settings( $cmb ) {
		$prefix = 'businessdirectory';

		$cmb->add_field(
			array(
				'name'             => esc_html__( 'Enquiry Form', 'lsx-business-directory' ),
				'id'               => $prefix . '_business_enquiry_form',
				'type'             => 'select',
				'show_option_none' => 'Choose a Form',
				'options'          => get_available_forms(),
			)
		);
	}

	/**
	 * Add a description to the Settings Page.
	 *
	 * @param object $cmb_id    id of cmb2 box.
	 * @return void
	 */
	public function add_description_to_cmb2_settings_page( $cmb_id ) {
		$prefix          = 'businessdirectory';
		$settings_box_id = $prefix . '_settings';

		if ( $settings_box_id !== $cmb_id ) {
			return;
		}

		echo '<div class="lsx-bd-settings-description">The settings for the single business directory view</div>';
	}
}
