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


require_once LSX_BUSINESS_DIRECTORY_PATH . 'vendor/class-tgm-plugin-activation.php';
add_action( 'tgmpa_register', 'lsx_business_directory_register_required_plugins' );



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

/**
 * Register the LSX Framework as a required plugin
 *
 * @package		lsx-business-directory
 * @category	setup
 */
function lsx_business_directory_register_required_plugins() {
	/*
	 * Array of plugin arrays. Required keys are name and slug.
	* If the source is NOT from the .org repo, then source is also required.
	*/
	$plugins = array(

			array(
					'name'         => 'LSX Framework', // The plugin name.
					'slug'         => 'lsx-framework', // The plugin slug (typically the folder name).
					'source'       => 'https://bitbucket.org/feedmycode/lsx-framework/get/baabe28a14fd.zip', // The plugin source.
					'required'     => true, // If false, the plugin is only 'recommended' instead of required.
					'external_url' => 'https://bitbucket.org/feedmycode/lsx-framework/get/baabe28a14fd.zip', // If set, overrides default API URL and points to an external URL.
			)

	);

	$config = array(
			'id'           => 'tgmpa-lsx-business-directory',                 // Unique ID for hashing notices for multiple instances of TGMPA.
			'default_path' => '',                      // Default absolute path to bundled plugins.
			'menu'         => 'tgmpa-install-plugins', // Menu slug.
			'parent_slug'  => 'themes.php',            // Parent menu slug.
			'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
			'has_notices'  => true,                    // Show admin notices or not.
			'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
			'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
			'is_automatic' => false,                   // Automatically activate plugins after installation or not.
			'message'      => '',                      // Message to output right before the plugins table.
	);

	tgmpa( $plugins, $config );
}