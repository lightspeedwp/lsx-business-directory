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
	 * This holds the current facet info.
	 *
	 * @var array
	 */
	public $facet_data = array();

	/**
	 * This holds the current cmb2 variable.
	 *
	 * @var array
	 */
	public $cmb;

	/**
	 * Contructor
	 */
	public function __construct() {
		// Register custom post types & taxonomies.
		require_once LSX_BD_PATH . 'classes/class-post-type.php';
		$this->post_types = Post_Type::get_instance();

		// Configure custom fields.
		if ( ! class_exists( 'CMB2' ) ) {
			require_once LSX_BD_PATH . 'vendor/CMB2/init.php';
		}
		add_action( 'cmb2_init', array( $this, 'configure_business_directory_custom_fields' ) );
		// Configure Settings page.
		add_action( 'cmb2_admin_init', array( $this, 'configure_settings_custom_fields' ) );
		add_action( 'lsx_bd_settings_page', array( $this, 'configure_settings_single_custom_fields' ), 1, 1 );
		add_action( 'lsx_bd_settings_page', array( $this, 'configure_settings_general_custom_fields' ), 2, 1 );
		// We do BD Search setting only at 'admin_init', because we need is_plugin_active() function present to check for LSX Search plugin.
		add_action( 'admin_init', array( $this, 'configure_settings_search_custom_fields' ) );
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
				'title'        => esc_html__( 'Business Images', 'lsx-business-directory' ),
				'object_types' => array( 'business-directory' ),
			)
		);

		$cmb_images->add_field(
			array(
				'name' => esc_html__( 'Featured Image', 'lsx-business-directory' ),
				'desc' => esc_html__( 'Featured image for a Business', 'lsx-business-directory' ),
				'id'   => $prefix . '_business_logo',
				'type' => 'file',
			)
		);

		$cmb_images->add_field(
			array(
				'name' => esc_html__( 'Banner Image', 'lsx-business-directory' ),
				'desc' => esc_html__( 'Banner image for a Business', 'lsx-business-directory' ),
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
				'title'        => esc_html__( 'Business Address', 'lsx-business-directory' ),
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

		$this->cmb = new_cmb2_box(
			array(
				'id'           => $prefix . '_settings',
				'title'        => esc_html__( 'Business Directory Settings', 'lsx-business-directory' ),
				'menu_title'   => esc_html__( 'Settings', 'lsx-business-directory' ), // Falls back to 'title' (above).
				'object_types' => array( 'options-page' ),
				'option_key'   => 'lsx-business-directory-settings', // The option key and admin menu page slug.
				'parent_slug'  => 'edit.php?post_type=business-directory', // Make options page a submenu item of the Business Directory menu.
				'capability'   => 'manage_options', // Cap required to view options-page.
			)
		);

		do_action( 'lsx_bd_settings_page', $this->cmb );
	}

	/**
	 * Configure Business Directory custom fields for the Settings page Single section.
	 *
	 * @param object $cmb new_cmb2_box().
	 * @return void
	 */
	public function configure_settings_single_custom_fields( $cmb ) {
		$prefix = 'businessdirectory';

		$cmb->add_field(
			array(
				'id'          => $prefix . '_settings_single',
				'type'        => 'title',
				'name'        => __( 'Single', 'lsx-business-directory' ),
				'default'     => __( 'Single', 'lsx-business-directory' ),
				'description' => __( 'The settings for the single business directory view.', 'lsx-business-directory' ),
			)
		);

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
	 * Configure Business Directory custom fields for the Settings page General section.
	 *
	 * @param object $cmb new_cmb2_box().
	 * @return void
	 */
	public function configure_settings_general_custom_fields( $cmb ) {
		$prefix = 'businessdirectory';

		$cmb->add_field(
			array(
				'id'          => $prefix . '_settings_general',
				'type'        => 'title',
				'name'        => __( 'General', 'lsx-business-directory' ),
				'default'     => __( 'General', 'lsx-business-directory' ),
				'description' => __( 'Business Directory general settings.', 'lsx-business-directory' ),
			)
		);

		$cmb->add_field(
			array(
				'name'             => esc_html__( 'Layout option', 'lsx-business-directory' ),
				'id'               => $prefix . '_business_layout_option',
				'type'             => 'radio',
				'show_option_none' => false,
				'options'          => array(
					'grid' => esc_html__( 'Grid', 'lsx-business-directory' ),
					'list' => esc_html__( 'List', 'lsx-business-directory' ),
				),
			)
		);
	}

	/**
	 * Enable Business Directory Search settings only if LSX Search plugin is enabled.
	 *
	 * @return  void
	 */
	public function configure_settings_search_custom_fields() {
		if ( is_plugin_active( 'lsx-search/lsx-search.php' ) ) {
			$cmb    = $this->cmb;
			$prefix = 'businessdirectory';
			$this->set_facetwp_vars();

			$cmb->add_field(
				array(
					'id'          => $prefix . '_settings_search',
					'type'        => 'title',
					'name'        => esc_html__( 'Business Directory - Search', 'lsx-business-directory' ),
					'default'     => esc_html__( 'Business Directory - Search', 'lsx-business-directory' ),
					'description' => esc_html__( 'Business Directory search related settings.', 'lsx-business-directory' ),
				)
			);

			$cmb->add_field(
				array(
					'name' => esc_html__( 'Enable Search', 'lsx-business-directory' ),
					'id'   => $prefix . '_business_search_enable',
					'type' => 'checkbox',
				)
			);

			$cmb->add_field(
				array(
					'name'    => esc_html__( 'Layout', 'lsx-business-directory' ),
					'id'      => $prefix . '_business_search_layout',
					'type'    => 'select',
					'options' => array(
						''    => esc_html__( 'Follow the theme layout', 'lsx-business-directory' ),
						'1c'  => esc_html__( '1 column', 'lsx-business-directory' ),
						'2cr' => esc_html__( '2 columns / Content on right', 'lsx-business-directory' ),
						'2cl' => esc_html__( '2 columns / Content on left', 'lsx-business-directory' ),
					),
					'default' => '',
				)
			);

			$cmb->add_field(
				array(
					'name' => esc_html__( 'Collapse', 'lsx-business-directory' ),
					'id'   => $prefix . '_business_search_collapse',
					'type' => 'checkbox',
				)
			);

			$cmb->add_field(
				array(
					'name' => esc_html__( 'Disable Sorting', 'lsx-business-directory' ),
					'id'   => $prefix . '_business_search_disable_sorting',
					'type' => 'checkbox',
				)
			);

			$cmb->add_field(
				array(
					'name' => esc_html__( 'Disable the Date Option', 'lsx-business-directory' ),
					'id'   => $prefix . '_business_search_disable_date',
					'type' => 'checkbox',
				)
			);

			$cmb->add_field(
				array(
					'name' => esc_html__( 'Display Clear Button', 'lsx-business-directory' ),
					'id'   => $prefix . '_business_search_clear_button',
					'type' => 'checkbox',
				)
			);

			$cmb->add_field(
				array(
					'name' => esc_html__( 'Display Result Count', 'lsx-business-directory' ),
					'id'   => $prefix . '_business_search_result_count',
					'type' => 'checkbox',
				)
			);

			$cmb->add_field(
				array(
					'name'        => esc_html__( 'Facets', 'lsx-business-directory' ),
					'description' => esc_html__( 'These are the filters that will appear on your archive page.', 'lsx-business-directory' ),
					'id'          => $prefix . '_business_search_facets',
					'type'        => 'multicheck',
					'options'     => $this->facet_data,
				)
			);
		}
	}

	/**
	 * Sets the FacetWP variables.
	 *
	 * @return  void
	 */
	public function set_facetwp_vars() {
		if ( function_exists( '\FWP' ) ) {
			$facet_data = \FWP()->helper->get_facets();
		}

		$this->facet_data = array();
		if ( ! empty( $facet_data ) && is_array( $facet_data ) ) {
			foreach ( $facet_data as $facet ) {
				$this->facet_data[ $facet['name'] ] = $facet['label'];
			}
		}
	}
}
