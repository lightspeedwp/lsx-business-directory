<?php
/**
 * LSX Business Directory template functions.
 *
 * @package lsx-business-directory
 */

/**
 * Retrieves post thumbnail and averts werid unexpected behaviour.
 * Could re-wrap here if needed.
 *
 * @package     lsx-business-directory
 * @subpackage  template-tags
 * @category    single
 *
 * @param       $id int
 * @param       $width int
 * @param       $height int
 */
function get_thumbnail_wrapped( $id, $width, $height ) {
	$image_src = 'https://placehold.it/' . (string) $width . 'x' . (string) $height;

	if ( has_post_thumbnail( $id ) ) {
		$image     = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'large' );
		$image_src = ( strpos( $image[0], 'cover-logo.png' ) === false ) ? $image[0] : $image_src;
	}

	return $image_src;
}

/**
 * Generates a comma seperated string from specified taxonomy.
 *
 * @package     lsx-business-directory
 * @subpackage  template-tags
 * @category    single
 *
 * @param       $id int
 * @param       $tax String
 */
function get_formatted_taxonomy_str( $id, $tax ) {
	$terms     = wp_get_post_terms( $id, $tax );
	$terms_str = '';

	if ( ! empty( $terms ) ) {
		foreach ( $terms as $term ) {
			$terms_str .= $term->name . ', ';
		}

		$terms_str = substr( $terms_str, 0, strlen( $terms_str ) - 2 );
	}

	if ( $tax == 'post_format' && $terms_str == '' ) {
		$terms_str = 'Standard';
	}

	return $terms_str;
}
