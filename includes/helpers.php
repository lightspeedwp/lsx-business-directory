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
