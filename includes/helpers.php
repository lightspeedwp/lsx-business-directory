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
 * @param string $prefix
 * @return string
 */
function get_placeholder( $size = 'lsx-thumbnail-wide', $key = 'single_thumbnail', $prefix = '' ) {
	$placeholder             = '';
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
			case 'archive_industry':
				$placeholder = LSX_BD_URL . 'assets/img/placeholder-industry-icon.svg';
				break;

			case 'archive_industry_hover':
				$placeholder = LSX_BD_URL . 'assets/img/placeholder-industry-icon-hover.svg';
				break;

			case 'thumbnail':
				$placeholder = LSX_BD_URL . 'assets/img/placeholder-' . $prefix . 'thumbnail.jpg';
				break;

			case 'lsx-thumbnail-square':
				$placeholder = LSX_BD_URL . 'assets/img/placeholder-' . $prefix . '350x350.jpg';
				break;

			case 'lsx-thumbnail-single':
				$placeholder = LSX_BD_URL . 'assets/img/placeholder-' . $prefix . '750x350.jpg';
				break;

			case 'full':
			case 'lsx-banner':
				$placeholder = LSX_BD_URL . 'assets/img/placeholder-' . $prefix . '1920x600.jpg';
				break;

			case 'lsx-thumbnail-carousel':
				$placeholder = LSX_BD_URL . 'assets/img/placeholder-' . $prefix . '350x230.jpg';
				break;

			case 'lsx-thumbnail-wide':
			default:
				$placeholder = LSX_BD_URL . 'assets/img/placeholder-' . $prefix . '360x168.jpg';
				break;
		}
	}
	return $placeholder;
}

/**
 * Gets the values for the fields you supply.
 *
 * @param array $sections \lsx\business_directory\includes\get_listing_form_fields();
 * @param int $listing_id This is the listing ID of the values you want to get.
 * @return array
 */
function get_listing_form_field_values( $sections = array(), $listing_id = false ) {
	$values   = array();
	$defaults = \lsx\business_directory\includes\get_listing_form_field_defaults();
	if ( ! empty( $sections ) ) {
		foreach ( $sections as $section_key => $section_values ) {
			if ( ! empty( $section_values['fields'] ) ) {
				foreach ( $section_values['fields'] as $field_key => $field_args ) {
					$field_args = wp_parse_args( $field_args, $defaults );
					$type       = str_replace( 'lsx_bd_', '', $field_key );

					$field_value = '';
					if ( false !== $listing_id && ! isset( $_POST[ $field_key ] ) ) {
						switch ( $type ) {
							case 'post_title':
								break;
							case 'post_content':
								break;

							case 'post_excerpt':
								break;

							case 'tax_industry':
							case 'tax_location':
								break;

							default:
								break;
						}
					} elseif ( isset( $_POST[ $field_key ] ) ) {
						if ( 'checkbox' === $field_args['type'] ) {
							$field_value = (int) isset( $_POST[ $field_key ] );
						} else {
							$field_value = wc_clean( wp_unslash( $_POST[ $field_key ] ) );
						}
					}
					$values[ $field_key ] = $field_value;
				}
			}
		}
	}
	return $values;
}
