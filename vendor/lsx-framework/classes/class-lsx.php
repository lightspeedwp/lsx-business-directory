<?php
/**
 * LST.
 *
 * @package   Lsx
 * @author     LightSpeed Team
 * @license   GPL-2.0+
 * @link      
 * @copyright 2015  LightSpeed Team
 */

/**
 * Plugin class.
 * @package Lsx
 * @author   LightSpeed Team
 */
class Lsx {

	/**
	 * The slug for this plugin
	 *
	 * @since 0.0.1
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'lsx';

	/**
	 * Holds class isntance
	 *
	 * @since 0.0.1
	 *
	 * @var      object|Lsx
	 */
	protected static $instance = null;

	/**
	 * Holds the option screen prefix
	 *
	 * @since 0.0.1
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since 0.0.1
	 *
	 * @access private
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_stylescripts' ) );

		// init modules
		self::get_modules();
		
		// init metaboxes
		if( class_exists( 'Lsx_Metabox' ) ){
			Lsx_Metabox::get_instance();
		}
	}


	/**
	 * Return an instance of this class.
	 *
	 * @since 0.0.1
	 *
	 * @return    object|Lsx    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Returns an array of all module core filed
	 *
	 * @return array Array of module core files
	 */
	public static function get_modules() {

		$lsx = Lsx_Options::get_single( 'lsx' );

		$absolute_path = untrailingslashit( LSX_FRAMEWORK_PATH . 'modules' );
		$modules = array();

		$default_headers = array(
			'Name' => 'Module Name',
			'ModuleURI' => 'Module URI',
			'Version' => 'Version',
			'Description' => 'Description',
			'Author' => 'Author',
			'AuthorURI' => 'Author URI',
			'TextDomain' => 'Text Domain',
			'DomainPath' => 'Domain Path',
		);
		if ( $dir = @opendir( $absolute_path ) ) {
			while ( false !== $file = readdir( $dir ) ) {
				if ( '.' == substr( $file, 0, 1 ) ) {
					continue;
				}
				if ( ! is_file( $file ) && file_exists( $absolute_path . '/' . $file . '/' . 'module.php' )  ) {
					// modules must be in a folder
					$module = get_file_data( $absolute_path . '/' . $file . '/' . 'module.php', $default_headers, 'module' );//$absolute_path . '/' . $file . '/' . 'module.php';
					$module['Internal'] = true;
					$module['file']	= $absolute_path . '/' . $file . '/' . 'module.php';
					$modules[] = $module;

				}

			}
			closedir( $dir );
		}

		// do activation
		$modules = apply_filters( 'lsx_get_modules', $modules );
		foreach( $modules as $module ){
			//if( !empty( $lsx['active_module'][ $file ] ) || empty( $module['Internal'] ) ){
				if( file_exists( $module['file'] ) ){
					include_once $module['file'];
				}
			//}
		}
		return $modules;
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since 0.0.1
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain( $this->plugin_slug, FALSE, basename( LSX_FRAMEWORK_PATH ) . '/languages');

	}

	
	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since 0.0.1
	 *
	 * @return    null
	 */
	public function enqueue_admin_stylescripts() {

		$screen = get_current_screen();

		if( !is_object( $screen ) ){
			return;
		}

		
		
		if( false !== strpos( $screen->base, 'lsx' ) ){

			wp_enqueue_style( 'lsx-core-style', LSX_FRAMEWORK_URL . '/assets/css/styles.css' );
			wp_enqueue_style( 'lsx-baldrick-modals', LSX_FRAMEWORK_URL . '/assets/css/modals.css' );
			wp_enqueue_style( 'wp-color-picker' );

			wp_enqueue_script( 'lsx-wp-baldrick', LSX_FRAMEWORK_URL . '/assets/js/wp-baldrick-full.js', array( 'jquery' ) , false, true );
			wp_enqueue_script( 'jquery-ui-autocomplete' );
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'lsx-core-script', LSX_FRAMEWORK_URL . '/assets/js/scripts.js', array( 'lsx-wp-baldrick' ) , false );			
			wp_enqueue_script( 'wp-color-picker' );			
		
		}


	}

}