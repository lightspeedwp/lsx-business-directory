<?php
/**
 * @package   LSX_Business_Directory
 * @author    LightSpeed
 * @license   GPL-2.0+
 * @link      
 * @copyright 2015 LightSpeed
 */

/**
 * The content and title
 *
 * @package		lsx-business-directory
 * @subpackage	template-tags
 * @category	single
 */
function lsx_business_entry(){
	// load content
	if( function_exists( 'caldera_metaplate_from_file' ) && file_exists( get_stylesheet_directory() . '/templates/metaplate-single-business-content.html' ) ){
		echo caldera_metaplate_from_file( get_stylesheet_directory() . 'templates/metaplate-single-business-content.html', get_the_id() );
	}elseif( function_exists( 'caldera_metaplate_from_file' ) && file_exists( LSX_BUSINESS_DIRECTORY_PATH . 'templates/metaplate-single-business-content.html' ) ){
		echo caldera_metaplate_from_file( LSX_BUSINESS_DIRECTORY_PATH . 'templates/metaplate-single-business-content.html', get_the_id() );
	}else{
		_e('Please activate the Caldera Metaplate Plugin','lsx-business-directory');
	}	
}