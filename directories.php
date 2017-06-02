<?php
/**
 * @package   LSX_Business_Directory
 * @author    LightSpeed
 * @license   GPL-2.0+
 * @link
 * @copyright 2015 LightSpeed
 *
 * @wordpress-plugin
 * Plugin Name: LSX Business Directory
 * Plugin URI:  http://lsdev.biz/
 * Description: Business Directory addon for the LSX Framework
 * Version:     1.0.0
 * Author:      LightSpeed Team
 * Author URI:  https://www.lsdev.biz/
 * Text Domain: lsx-business-directory
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define('LSX_BUSINESS_DIRECTORY_PATH',  plugin_dir_path( __FILE__ ) );
define('LSX_BUSINESS_DIRECTORY_CORE',  __FILE__ );
define('LSX_BUSINESS_DIRECTORY_URL',  plugin_dir_url( __FILE__ ) );
define('LSX_BUSINESS_DIRECTORY_VER',  '1.0.0' );
if ( ! defined( 'LSX_BUSINESS_DIRECTORY_ARCHIVE_SLUG' ) ) {
	define('LSX_BUSINESS_DIRECTORY_ARCHIVE_SLUG',  'business-directory' );
}
if ( ! defined( 'LSX_BUSINESS_DIRECTORY_LABEL' ) ) {
	define('LSX_BUSINESS_DIRECTORY_LABEL',  __('Business Directory', 'lsx-business-directory') );
}

/* ======================= The API Classes ========================= */

if ( ! class_exists( 'LSX_API_Manager' ) ) {
	require_once( 'classes/class-lsx-api-manager.php' );
}

/**
 * Run when the plugin is active, and generate a unique password for the site instance.
 */
function lsx_business_directory_activate_plugin() {
	$lsx_to_password = get_option( 'lsx_api_instance', false );

	if ( false === $lsx_to_password ) {
		update_option( 'lsx_api_instance', LSX_API_Manager::generatePassword() );
	}
}
register_activation_hook( __FILE__, 'lsx_business_directory_activate_plugin' );

/**
 *	Grabs the email and api key from the LSX Currency Settings.
 */
function lsx_business_directory_options_pages_filter( $pages ) {
	$pages[] = 'lsx-settings';
	$pages[] = 'lsx-to-settings';
	return $pages;
}
add_filter( 'lsx_api_manager_options_pages', 'lsx_business_directory_options_pages_filter', 10, 1 );

function lsx_business_directory_api_admin_init() {
	global $lsx_business_directory_api_manager;

	if ( class_exists( 'Tour_Operator' ) ) {
		$options = get_option( '_lsx-to_settings', false );
	} else {
		$options = get_option( '_lsx_settings', false );

		if ( false === $options ) {
			$options = get_option( '_lsx_lsx-settings', false );
		}
	}

	$data = array(
		'api_key' => '',
		'email'   => '',
	);

	if ( false !== $options && isset( $options['api'] ) ) {
		if ( isset( $options['api']['lsx-business-directory_api_key'] ) && '' !== $options['api']['lsx-business-directory_api_key'] ) {
			$data['api_key'] = $options['api']['lsx-business-directory_api_key'];
		}

		if ( isset( $options['api']['lsx-business-directory_email'] ) && '' !== $options['api']['lsx-business-directory_email'] ) {
			$data['email'] = $options['api']['lsx-business-directory_email'];
		}
	}

	$instance = get_option( 'lsx_api_instance', false );

	if ( false === $instance ) {
		$instance = LSX_API_Manager::generatePassword();
	}

	$api_array = array(
		'product_id' => 'LSX Business Directory',
		'version'    => '1.0.0',
		'instance'   => $instance,
		'email'      => $data['email'],
		'api_key'    => $data['api_key'],
		'file'       => 'directories.php',
	);

	$lsx_business_directory_api_manager = new LSX_API_Manager( $api_array );
}
add_action( 'admin_init', 'lsx_business_directory_api_admin_init' );

/* ======================= Below is the Plugin Class init ========================= */

if(!class_exists('Lsx')){
	require_once( LSX_BUSINESS_DIRECTORY_PATH . 'vendor/lsx-framework/plugincore.php' );
}

/**
 * Register the module
 *
 * @package		lsx-business-directory
 * @category	setup
 *
 * @param		$modules	array
 * @return		$modules	array
 */
add_filter( 'lsx_get_modules', 'lsx_business_directories_register_module' );
function lsx_business_directories_register_module( $modules ){

	$modules[] = array(
      'Name' => __('Business Directory', 'lsx-business-directory'),
      'ModuleURI' => '',
      'Version' => '1.0.0',
      'Description' => __('Business Directory addon for the LSX Framework', 'lsx-business-directory'),
      'Author' => __( 'LightSpeed Team', 'lsx-business-directory'),
      'AuthorURI' => 'https://www.lsdev.biz/',
      'TextDomain' => 'lsx-business-directory',
      'DomainPath' => '/languages',
      'file' => LSX_BUSINESS_DIRECTORY_PATH . 'module.php'
    );

	return $modules;
}
