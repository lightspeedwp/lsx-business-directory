<?php
namespace lsx\business_directory\classes;

/**
 * LSX Starter Plugin Admin Class.
 *
 * @package lsx-business-directory
 */
class Admin {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx\business_directory\classes\Admin()
	 */
	protected static $instance = null;

	/**
	 * Holds the admin banner actions and filters.
	 *
	 * @var object \lsx\business_directory\classes\admin\Banners();
	 */
	public $banners;

	/**
	 * Contructor
	 */
	public function __construct() {
		$this->load_classes();
		// Enqueue scripts for all admin pages.
		add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );

		// Configure Settings page.
		add_action( 'cmb2_admin_init', array( $this, 'configure_settings_fields' ) );
		add_action( 'lsx_bd_settings_page', array( $this, 'configure_settings_single_fields' ), 1, 1 );
		add_action( 'lsx_bd_settings_page', array( $this, 'configure_settings_archive_fields' ), 2, 1 );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\member_directory\classes\Admin()    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Loads the variable classes and the static classes.
	 */
	private function load_classes() {
		// Load plugin admin related functionality.
		require_once LSX_BD_PATH . 'classes/frontend/class-banners.php';
		$this->banners = frontend\Banners::get_instance();
	}

	/**
	 * Various assest we want loaded for admin pages.
	 *
	 * @return void
	 */
	public function assets() {
		// wp_enqueue_media();
		wp_enqueue_script( 'media-upload' );
		wp_enqueue_script( 'thickbox' );
		wp_enqueue_style( 'thickbox' );

		wp_enqueue_script( 'lsx-business-directory-admin', LSX_BD_URL . 'assets/js/lsx-business-directory-admin.min.js', array( 'jquery' ), LSX_BD_VER, true );
		wp_enqueue_style( 'lsx-business-directory-admin', LSX_BD_URL . 'assets/css/lsx-business-directory-admin.css', array(), LSX_BD_VER );
	}
	/**
	 * Configure Business Directory custom fields for the Settings page.
	 *
	 * @return void
	 */
	public function configure_settings_fields() {
		$this->cmb = new_cmb2_box(
			array(
				'id'           => 'lsx_bd_settings',
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
	public function configure_settings_single_fields( $cmb ) {
		$cmb->add_field(
			array(
				'id'          => 'settings_single',
				'type'        => 'title',
				'name'        => __( 'Single', 'lsx-business-directory' ),
				'default'     => __( 'Single', 'lsx-business-directory' ),
				'description' => __( 'The settings for the single business directory view.', 'lsx-business-directory' ),
			)
		);

		$cmb->add_field(
			array(
				'name'             => esc_html__( 'Enquiry Form', 'lsx-business-directory' ),
				'id'               => 'single_enquiry_form',
				'type'             => 'select',
				'show_option_none' => 'Choose a Form',
				'options'          => lsx_bd_get_available_forms(),
			)
		);
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
		do_action( 'lsx_bd_settings_section_archive', $this->cmb, 'top' );
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
		do_action( 'lsx_bd_settings_section_archive', $this->cmb, 'bottom' );
	}
}
