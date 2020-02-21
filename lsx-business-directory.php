<?php
/*
 * Plugin Name:	LSX Business Directory
 * Plugin URI:	https://github.com/lightspeeddevelopment/lsx-business-directory
 * Description:	LSX Business Directory plugin for business listings.
 * Author:		LightSpeed
 * Version: 	1.1.0
 * Author URI: 	https://www.lsdev.biz/
 * License: 	GPL3
 * Text Domain: lsx-business-directory
 * Domain Path: /languages/
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'LSX_BUSINESS_DIRECTORY_PATH', plugin_dir_path( __FILE__ ) );
define( 'LSX_BUSINESS_DIRECTORY_CORE', __FILE__ );
define( 'LSX_BUSINESS_DIRECTORY_URL', plugin_dir_url( __FILE__ ) );
define( 'LSX_BUSINESS_DIRECTORY_VER', '1.1.0' );

/* ======================= Below is the Plugin Class init ========================= */

require_once LSX_BUSINESS_DIRECTORY_PATH . '/classes/class-core.php';

/**
 * Plugin kicks off with this function.
 *
 * @return void
 */
function lsx_business_directory() {
	return \lsx_business_directory\classes\Core::get_instance();
}
lsx_business_directory();
