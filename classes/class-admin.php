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
	 * Holds the admin the post type singles.
	 *
	 * @var object \lsx\business_directory\classes\admin\Archive();
	 */
	public $archive;

	/**
	 * Holds the admin for the post type archives.
	 *
	 * @var object \lsx\business_directory\classes\admin\Single();
	 */
	public $single;

	/**
	 * Holds the placeholders admin class
	 *
	 * @var object \lsx\business_directory\classes\admin\Placeholders();
	 */
	public $placeholders;

	/**
	 * Holds the settings page theme functions
	 *
	 * @var object \lsx\business_directory\classes\admin\Settings_Theme();
	 */
	public $settings_theme;

	/**
	 * Holds the slug translations for the post type archives and taxonomies.
	 *
	 * @var object \lsx\business_directory\classes\admin\Translations();
	 */
	public $translations;

	/**
	 * Holds the slug google maps fields
	 *
	 * @var object \lsx\business_directory\classes\admin\Google_maps();
	 */
	public $google_maps;

	/**
	 * Contructor
	 */
	public function __construct() {
		$this->load_classes();
		// Enqueue scripts for all admin pages.
		add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );
		// Configure Settings page.
		add_action( 'cmb2_admin_init', array( $this, 'register_settings_page' ) );
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
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Loads the variable classes and the static classes.
	 */
	private function load_classes() {
		// Load plugin admin related functionality.
		require_once LSX_BD_PATH . 'classes/admin/class-banners.php';
		$this->banners = admin\Banners::get_instance();

		require_once LSX_BD_PATH . 'classes/admin/class-term-thumbnail.php';
		$this->term_thumbnail = admin\Term_Thumbnail::get_instance();

		require_once LSX_BD_PATH . 'classes/admin/class-archive.php';
		$this->archive = admin\Archive::get_instance();

		require_once LSX_BD_PATH . 'classes/admin/class-single.php';
		$this->single = admin\Single::get_instance();

		require_once LSX_BD_PATH . 'classes/admin/class-placeholders.php';
		$this->placeholders = admin\Placeholders::get_instance();

		require_once LSX_BD_PATH . 'classes/admin/class-settings-theme.php';
		$this->settings_theme = admin\Settings_Theme::get_instance();

		require_once LSX_BD_PATH . 'classes/admin/class-translations.php';
		$this->translations = admin\Translations::get_instance();

		//require_once LSX_BD_PATH . 'classes/admin/class-google-maps.php';
		//$this->google_maps = admin\Google_Maps::get_instance();
	}

	/**
	 * Various assest we want loaded for admin pages.
	 *
	 * @return void
	 */
	public function assets() {
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
	public function register_settings_page() {
		$args = array(
			'id'           => 'lsx_bd_settings',
			'title'        => '<h1>' . esc_html__( 'Business Directory Settings', 'lsx-business-directory' ) . ' <span class="version">1.0.0</span></h1>',
			'menu_title'   => esc_html__( 'Settings', 'lsx-business-directory' ), // Falls back to 'title' (above).
			'object_types' => array( 'options-page' ),
			'option_key'   => 'lsx-business-directory-settings', // The option key and admin menu page slug.
			'parent_slug'  => 'edit.php?post_type=business-directory', // Make options page a submenu item of the Business Directory menu.
			'capability'   => 'manage_options', // Cap required to view options-page.
		);
		$cmb = new_cmb2_box( $args );
		do_action( 'lsx_bd_settings_page', $cmb );
	}
}
