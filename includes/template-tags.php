<?php
/**
 * @package   LSX_Business_Directory
 * @author    LightSpeed
 * @license   GPL-2.0+
 * @link      
 * @copyright 2015 LightSpeed
 */

function lsx_business_promotion() {
	lsx_business_template( 'single-business-promotion' );
}

function lsx_business_row() {
	lsx_business_template( 'single-row-business' );		
}

function lsx_related_business() {
	lsx_business_template( 'single-business-related-business' );
}

function lsx_claim_this_listing_button() {
	lsx_business_template( 'single-business-claim-button' );
}

function lsx_business_branch( $branch = array() ) {
	lsx_business_template ( 'single-business-branch', $branch );
}

function lsx_business_template ( $filename_base, $parameters = array() ) {
	if( file_exists( get_stylesheet_directory() . '/templates/' . $filename_base . '.php' ) ) {
		include get_stylesheet_directory() . '/templates/' . $filename_base . '.php';
	}elseif( file_exists( LSX_BUSINESS_DIRECTORY_PATH . 'templates/' . $filename_base . '.php' ) ){
		include LSX_BUSINESS_DIRECTORY_PATH . 'templates/' . $filename_base . '.php';
	}
}

/**
 * Generates a comma seperated string from specified taxonomy
 *
 * @package		lsx-business-directory
 * @subpackage	template-tags
 * @category	single
 * 
 * @param 		$id int
 * @param 		$tax String
 */
function get_formatted_taxonomy_str( $id, $tax ) {
	$terms = wp_get_post_terms( $id, $tax );
	$terms_str = '';
	if ( !empty( $terms ) ) {			
		foreach( $terms as $term ) {
			$terms_str .= $term->name . ', ';
		}
		$terms_str = substr( $terms_str, 0, strlen( $terms_str ) - 2 );
	}
	return $terms_str;
}

/**
 * Retrieves post thumbnail and averts werid unexpected behaviour.
 * Could re-wrap here if needed.
 *
 * @package		lsx-business-directory
 * @subpackage	template-tags
 * @category	single
 * 
 * @param 		$id int
 * @param 		$width int
 * @param 		$height int
 */
function get_thumbnail_wrapped( $id, $width, $height ) {
	$image_src = "https://placehold.it/" . (String)$width . 'x' . (String)$height;
	if ( has_post_thumbnail( $id ) ) {
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'large' );
		$image_src = ( strpos($image[0], 'cover-logo.png') === false ) ? $image[0] : $image_src;
	}
	return $image_src;
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