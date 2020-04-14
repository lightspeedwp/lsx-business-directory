<?php
/**
 * Registers the shortcode for LSX BD
 *
 * @package lsx-business-directory
 */

namespace lsx\business_directory\includes;

/**
 * Returns the related businesses for the current listing.
 *
 * @param array $atts The shortcode atts.
 * @return html
 */
function related_listings( $atts = array() ) {
	ob_start();
	lsx_bd_related_listings( $atts );
	$content = ob_get_clean();
	return $content;
}
add_shortcode( 'lsx_bd_related_listings', '\lsx\business_directory\includes\related_listings' );

/**
 * Returns the recent businesses added to the directory
 *
 * @param array $atts The shortcode atts.
 * @return html
 */
function recent_listings( $atts = array() ) {
	ob_start();
	lsx_bd_recent_listings( $atts );
	$content = ob_get_clean();
	return $content;
}
add_shortcode( 'lsx_bd_recent_listings', '\lsx\business_directory\includes\recent_listings' );

/**
 * Returns the featured businesses.
 *
 * @param array $atts The shortcode atts.
 * @return html
 */
function featured_listings( $atts = array() ) {
	ob_start();
	lsx_bd_featured_listings( $atts );
	$content = ob_get_clean();
	return $content;
}
add_shortcode( 'lsx_bd_featured_listings', '\lsx\business_directory\includes\featured_listings' );

/**
 * Returns the featured businesses.
 *
 * @param array $atts The shortcode atts.
 * @return html
 */
function random_listings( $atts = array() ) {
	ob_start();
	lsx_bd_random_listings( $atts );
	$content = ob_get_clean();
	return $content;
}
add_shortcode( 'lsx_bd_random_listings', '\lsx\business_directory\includes\random_listings' );

/**
 * Returns the industries in nav type block.
 *
 * @param array $atts The shortcode atts.
 * @return html
 */
function industries_nav( $atts = array() ) {
	ob_start();
	lsx_bd_industries_nav( $atts );
	$content = ob_get_clean();
	return $content;
}
add_shortcode( 'lsx_bd_industries_nav', '\lsx\business_directory\includes\industries_nav' );

/**
 * Returns the add listing form
 *
 * @param array $atts The shortcode atts.
 * @return html
 */
function listing_form( $atts = array() ) {
	ob_start();
	lsx_business_template( 'woocommerce/listing-form' );
	$content = ob_get_clean();
	return $content;
}
add_shortcode( 'lsx_bd_listing_form', '\lsx\business_directory\includes\listing_form' );
