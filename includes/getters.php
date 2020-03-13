<?php
/**
 * All the functions used to "get" the base info for LSX BD
 *
 * @package lsx-business-directory
 */

namespace lsx\business_directory\includes;

/**
 * Returns the banner fields for use in registering the custom fields, theme settings etc.
 *
 * @param mixed $prefix Add a prefix to the fields IDS
 * @return array
 */
function get_banner_fields( $prefix = '' ) {
	$fields = array(
		array(
			'name' => esc_html__( 'Disable Banner', 'lsx-business-directory' ),
			'id'   => '_banner_disable',
			'type' => 'checkbox',
		),
		array(
			'name' => esc_html__( 'Image', 'lsx-business-directory' ),
			'desc' => esc_html__( 'Upload a banner image for to display above your business listing.', 'lsx-business-directory' ),
			'id'   => '_banner',
			'type' => 'file',
		),
		array(
			'name'    => esc_html__( 'Colour', 'lsx-business-directory' ),
			'desc'    => esc_html__( 'Choose a background colour to display in case you don\'t have a banner image.', 'lsx-business-directory' ),
			'id'      => '_banner_colour',
			'type'    => 'colorpicker',
			'default' => '#2b3640',
		),
		array(
			'name' => esc_html__( 'Title', 'lsx-business-directory' ),
			'desc' => esc_html__( 'Customize the title for your banner.', 'lsx-business-directory' ),
			'id'   => '_banner_title',
			'type' => 'text',
		),
		array(
			'name' => esc_html__( 'Subtitle', 'lsx-business-directory' ),
			'desc' => esc_html__( 'Customize the subtitle for your banner, this will display just below your title.', 'lsx-business-directory' ),
			'id'   => '_banner_subtitle',
			'type' => 'text',
		),
	);
	if ( '' !== $prefix ) {
		$fields = apply_field_id_prefixes( $fields, $prefix );
	}
	return $fields;
}

