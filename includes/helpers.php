<?php
/**
 * All the functions used to "get" the base info for LSX BD
 *
 * @package lsx-business-directory
 */

namespace lsx\business_directory\includes;

/**
 * Adds a prefix to the ['id'] value in the $fields array
 *
 * @param array $fields
 * @param string $prefix
 * @return array
 */
function apply_field_id_prefixes( $fields = array(), $prefix = '' ) {
	if ( ! empty( $fields ) ) {
		foreach ( $fields as &$field ) {
			if ( isset( $field['id'] ) ) {
				$field['id'] = $prefix . $field['id'];
			}
		}
	}
	return $fields;
}

/**
 * Gets the placeholder image
 *
 * @param string $id
 * @param string $size
 * @param string $type
 * @return string
 */
function get_placeholder( $size = 'lsx-thumbnail-wide', $key = 'single_thumbnail' ) {
	$placeholder = '';
	$possible_placeholder_id = lsx_bd_get_option( $key . '_placeholder_id' );
	if ( '' !== $possible_placeholder_id ) {
		$image = wp_get_attachment_image_src( $possible_placeholder_id, $size );
		if ( is_array( $image ) && isset( $image[0] ) ) {
			$placeholder = $image[0];
		}
	}

	// Default to the images stored in the plugin.
	if ( '' === $placeholder ) {
		switch ( $size ) {
			case 'thumbnail':
				$placeholder = LSX_BD_URL . 'assets/img/placeholder-thumbnail.jpg';
				break;

			case 'lsx-thumbnail-square':
				$placeholder = LSX_BD_URL . 'assets/img/placeholder-350x350.jpg';
				break;

			case 'lsx-thumbnail-single':
				$placeholder = LSX_BD_URL . 'assets/img/placeholder-750x350.jpg';
				break;

			case 'full':
			case 'lsx-banner':
				$placeholder = LSX_BD_URL . 'assets/img/placeholder-1920x600.jpg';
				break;

			case 'lsx-thumbnail-carousel':
				$placeholder = LSX_BD_URL . 'assets/img/placeholder-350x230.jpg';
				break;

			case 'lsx-thumbnail-wide':
			default:
				$placeholder = LSX_BD_URL . 'assets/img/placeholder-360x168.jpg';
				break;
		}
	}
	return $placeholder;
}
