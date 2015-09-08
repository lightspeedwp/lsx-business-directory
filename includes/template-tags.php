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


/**
 * A button linking to the business archive
 *
 * @package		lsx-business-directory
 * @subpackage	template-tags
 * @category	single
 * 
 * @param		$text	String
 */
function lsx_business_directory_button($text = false,$class = false,$return = false){
	if(false === $text){
		$text = __('View the business directory.','lsx-business-directory');
	}
	$classes = 'directory btn btn-default';
	if(false !== $class){
		$classes .= ' '.$class;
	}
	$text = apply_filters('lsx_business_directory_button_text', $text);
	$link = '<a class="'.$classes.'" href="'.home_url(LSX_BUSINESS_DIRECTORY_ARCHIVE_SLUG).'/" title="'.__('View the business directory.','lsx-business-directory').'">'.$text.'</a>';
	if(false === $return){
		echo $link;
	}else{
		return $link;
	}
}

function lsx_business_directory_button_shortcode( $atts ) {
	$atts = shortcode_atts( array(
			'text' => __('View the business directory.','lsx-business-directory'),
			'class' => ''
	), $atts, 'lsx_directory_button' );

	return lsx_business_directory_button($atts['text'],$atts['class'],true);
}
add_shortcode( 'lsx_directory_button', 'lsx_business_directory_button_shortcode' );