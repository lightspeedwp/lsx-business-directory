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
 * Version:     1.0.2
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
