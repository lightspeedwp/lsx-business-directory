<?php
/**
 * All the functions used to "get" the base info for LSX BD
 *
 * @package lsx-business-directory
 */

namespace lsx\business_directory\includes;

use WP_Query;

/**
 * Returns the banner fields for use in registering the custom fields, theme settings etc.
 *
 * @param mixed $prefix Add a prefix to the fields IDS
 * @return array
 */
function get_banner_fields( $prefix = '', $label_prefix = '' ) {
	$fields = array(
		array(
			'name' => esc_html__( 'Disable Banner', 'lsx-business-directory' ),
			'id'   => '_banner_disable',
			'type' => 'checkbox',
		),
		array(
			'name'         => $label_prefix . esc_html__( 'Image', 'lsx-business-directory' ),
			'desc'         => esc_html__( 'Your image should be 1920px x 600px preferably, but no less than 1440px x 430px.', 'lsx-business-directory' ),
			'id'           => '_banner',
			'type'         => 'file',
			'preview_size' => 'lsx-thumbnail-wide',
		),
		array(
			'name'    => $label_prefix . esc_html__( 'Colour', 'lsx-business-directory' ),
			'desc'    => esc_html__( 'Choose a background colour to display in case you don\'t have a banner image.', 'lsx-business-directory' ),
			'id'      => '_banner_colour',
			'type'    => 'colorpicker',
			'default' => '#2b3640',
		),
		array(
			'name' => $label_prefix . esc_html__( 'Title', 'lsx-business-directory' ),
			'desc' => esc_html__( 'Customize the title for your banner.', 'lsx-business-directory' ),
			'id'   => '_banner_title',
			'type' => 'text',
		),
		array(
			'name' => $label_prefix . esc_html__( 'Subtitle', 'lsx-business-directory' ),
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

/**
 * Returns the banner fields for use in registering the custom fields, theme settings etc.
 *
 * @param mixed $prefix Add a prefix to the fields IDS
 * @return array
 */
function get_featured_image_field( $prefix = '', $hover = false ) {
	$fields   = array();
	$fields[] = array(
		'name'         => esc_html__( 'Featured Image', 'lsx-business-directory' ),
		'desc'         => esc_html__( 'Your image should be 800px x 600px preferably, but no less than 360px x 168px', 'lsx-business-directory' ),
		'id'           => '_thumbnail',
		'type'         => 'file',
		'preview_size' => 'lsx-thumbnail-wide',
	);
	if ( false !== $hover ) {
		$fields[] = array(
			'name'         => esc_html__( 'Featured Image (hover)', 'lsx-business-directory' ),
			'desc'         => esc_html__( 'Your image should be 800px x 600px preferably, but no less than 360px x 168px', 'lsx-business-directory' ),
			'id'           => '_thumbnail_hover',
			'type'         => 'file',
			'preview_size' => 'lsx-thumbnail-wide',
		);
	}

	if ( '' !== $prefix ) {
		$fields = apply_field_id_prefixes( $fields, $prefix );
	}
	return $fields;
}

/**
 * Returns the banner fields for use in registering the custom fields, theme settings etc.
 *
 * @param mixed $prefix Add a prefix to the fields IDS
 * @return array
 */
function get_industy_icon_field( $prefix = '', $hover = false ) {
	$fields   = array();
	$fields[] = array(
		'name'         => esc_html__( 'Icon', 'lsx-business-directory' ),
		'desc'         => esc_html__( 'Your image should be 32px x 32px.', 'lsx-business-directory' ),
		'id'           => '_thumbnail',
		'type'         => 'file',
		'preview_size' => array( 32, 32 ),
	);
	if ( false !== $hover ) {
		$fields[] = array(
			'name'         => esc_html__( 'Icon (hover)', 'lsx-business-directory' ),
			'desc'         => esc_html__( 'Your image should be 32px x 32px.', 'lsx-business-directory' ),
			'id'           => '_thumbnail_hover',
			'type'         => 'file',
			'preview_size' => array( 32, 32 ),
		);
	}
	if ( '' !== $prefix ) {
		$fields = apply_field_id_prefixes( $fields, $prefix );
	}
	return $fields;
}

/**
 * Returns the banner fields for use in registering the custom fields, theme settings etc.
 *
 * @param mixed $prefix Add a prefix to the fields IDS
 * @return array
 */
function get_industry_icon_placeholder_field( $prefix = '' ) {
	$fields = array(
		'name'         => esc_html__( 'Industry Placeholder', 'lsx-business-directory' ),
		'desc'         => esc_html__( 'Your image should be 32px x 32 preferably.', 'lsx-business-directory' ),
		'id'           => '_industry_placeholder',
		'type'         => 'file',
		'preview_size' => array( 32, 32 ),
	);
	if ( '' !== $prefix ) {
		$fields = apply_field_id_prefixes( array( $fields ), $prefix );
		$fields = $fields[0];
	}
	return $fields;
}

/**
 * Returns the banner fields for use in registering the custom fields, theme settings etc.
 *
 * @param mixed $prefix Add a prefix to the fields IDS
 * @return array
 */
function get_industry_icon_hover_placeholder_field( $prefix = '' ) {
	$fields = array(
		'name'         => esc_html__( 'Industry Placeholder (hover)', 'lsx-business-directory' ),
		'desc'         => esc_html__( 'Your image should be 32px x 32 preferably.', 'lsx-business-directory' ),
		'id'           => '_industry_hover_placeholder',
		'type'         => 'file',
		'preview_size' => array( 32, 32 ),
	);
	if ( '' !== $prefix ) {
		$fields = apply_field_id_prefixes( array( $fields ), $prefix );
		$fields = $fields[0];
	}
	return $fields;
}

/**
 * Returns the banner fields for use in registering the custom fields, theme settings etc.
 *
 * @param mixed $prefix Add a prefix to the fields IDS
 * @return array
 */
function get_location_featured_placeholder_field( $prefix = '' ) {
	$fields = array(
		'name'         => esc_html__( 'Location Placeholder', 'lsx-business-directory' ),
		'desc'         => esc_html__( 'Your image should be 32px x 32 preferably.', 'lsx-business-directory' ),
		'id'           => '_location_placeholder',
		'type'         => 'file',
		'preview_size' => 'lsx-thumbnail-wide',
	);
	if ( '' !== $prefix ) {
		$fields = apply_field_id_prefixes( array( $fields ), $prefix );
		$fields = $fields[0];
	}
	return $fields;
}

/**
 * Returns the banner fields for use in registering the custom fields, theme settings etc.
 *
 * @param mixed $prefix Add a prefix to the fields IDS
 * @return array
 */
function get_featured_image_placeholder_field( $prefix = '' ) {
	$fields = array(
		'name'         => esc_html__( 'Featured Placeholder', 'lsx-business-directory' ),
		'desc'         => esc_html__( 'Your image should be 800px x 600px preferably, but no less than 360px x 168px.', 'lsx-business-directory' ),
		'id'           => '_thumbnail_placeholder',
		'type'         => 'file',
		'preview_size' => 'lsx-thumbnail-wide',
	);
	if ( '' !== $prefix ) {
		$fields = apply_field_id_prefixes( array( $fields ), $prefix );
		$fields = $fields[0];
	}
	return $fields;
}

/**
 * Returns the banner fields for use in registering the custom fields, theme settings etc.
 *
 * @param mixed $prefix Add a prefix to the fields IDS
 * @return array
 */
function get_banner_image_placeholder_field( $prefix = '' ) {
	$fields = array(
		'name'         => esc_html__( 'Banner Placeholder', 'lsx-business-directory' ),
		'desc'         => esc_html__( 'Your image should be 1920px x 600px preferably, but no less than 1440px x 430px.', 'lsx-business-directory' ),
		'id'           => '_banner_placeholder',
		'type'         => 'file',
		'preview_size' => 'lsx-thumbnail-wide',
	);
	if ( '' !== $prefix ) {
		$fields = apply_field_id_prefixes( array( $fields ), $prefix );
		$fields = $fields[0];
	}
	return $fields;
}

/**
 * Buid array containing options for a select box inside the settings for Business Directory.
 *
 * @return  array  Options array containing all available forms.
 * Key   = Form ID
 * Value = Form Name
 */
function get_available_forms() {
	$forms_data = get_activated_forms();
	$options    = array();

	if ( ! empty( $forms_data ) ) {
		$options = $forms_data;
	} else {
		$options['none'] = esc_html__( 'You have no forms available', 'lsx-business-directory' );
	}

	return $options;
}

/**
 * Checks which form plugin is active, and grabs all available forms from that plugin.
 *
 * @return  array  Array with all available forms for a particular plugin which is enabled.
 */
function get_activated_forms() {
	$all_forms = array();

	if ( class_exists( 'WPForms' ) ) {
		$wpforms = get_wpforms();
		if ( ! empty( $wpforms ) ) {
			$all_forms = array_merge( $all_forms, $wpforms );
		}
	}
	if ( class_exists( 'Ninja_Forms' ) ) {
		$ninja_forms = get_ninja_forms();
		if ( ! empty( $ninja_forms ) ) {
			$all_forms = array_merge( $all_forms, $ninja_forms );
		}
	}
	if ( class_exists( 'GFForms' ) ) {
		$gravity_forms = get_gravity_forms();
		if ( ! empty( $gravity_forms ) ) {
			$all_forms = array_merge( $all_forms, $gravity_forms );
		}
	}
	if ( class_exists( 'Caldera_Forms_Forms' ) ) {
		$caldera_forms = get_caldera_forms();
		if ( ! empty( $caldera_forms ) ) {
			$all_forms = array_merge( $all_forms, $caldera_forms );
		}
	}
	return $all_forms;
}

/**
 * Gets the available WPForms forms.
 *
 * @return  array  Array with all available WPForms forms.
 */
function get_wpforms() {
	global $wpdb;
	$forms = false;

	$args = array(
		'post_type'     => 'wpforms',
		'orderby'       => 'id',
		'order'         => 'ASC',
		'no_found_rows' => true,
		'nopaging'      => true,
	);

	$form_query = new \WP_Query( $args );

	if ( $form_query->have_posts() ) {
		foreach ( $form_query->posts as $post ) {
			$forms[ 'wp_' . $post->ID ] = $post->post_title;
		}
	} else {
		$forms = false;
	}

	return $forms;
}

/**
 * Gets the available Ninja forms.
 *
 * @return  array  Array with all available Ninja forms.
 */
function get_ninja_forms() {
	global $wpdb;

	$results = $wpdb->get_results( "SELECT id,title FROM {$wpdb->prefix}nf3_forms" );
	$forms   = false;

	if ( ! empty( $results ) ) {
		foreach ( $results as $form ) {
			$forms[ 'ninja_' . $form->id ] = $form->title;
		}
	}

	return $forms;
}

/**
 * Gets the available Gravity forms.
 *
 * @return  array  Array with all available Gravity forms.
 */
function get_gravity_forms() {
	global $wpdb;

	$results = \RGFormsModel::get_forms( null, 'title' );
	$forms   = false;

	if ( ! empty( $results ) ) {
		foreach ( $results as $form ) {
			$forms[ 'gravity_' . $form->id ] = $form->title;
		}
	}

	return $forms;
}

/**
 * Gets the available Caldera forms.
 *
 * @return  array  Array with all available Caldera forms.
 */
function get_caldera_forms() {
	global $wpdb;

	$results = \Caldera_Forms_Forms::get_forms( true );
	$forms   = false;

	if ( ! empty( $results ) ) {
		foreach ( $results as $form => $form_data ) {
			$forms[ 'caldera_' . $form ] = $form_data['name'];
		}
	}

	return $forms;
}

/**
 * Gets the available listing form fields
 *
 * @return  array  Array with all available listing fields
 */
function get_listing_form_fields() {
	$fields = array(
		'listing-title' => array(
			'label'  => esc_attr__( 'Listing Details', 'lsx-business-directory' ),
			'fields' => array(
				'lsx_bd_post_title' => array(
					'type'     => 'text',
					'label'    => __( 'Company Name', 'lsx-business-directory' ),
					'class'    => array( 'listing-post-title form-row-wide' ),
					'required' => true,
				),
				'lsx_bd_post_status' => array(
					'type'     => 'select',
					'label'    => __( 'Status', 'lsx-business-directory' ),
					'class'    => array( 'listing-industry form-row-first' ),
					'required' => true,
					'options'  => array(
						'pending' => __( 'Pending', 'lsx-business-directory' ),
						'publish' => __( 'Publish', 'lsx-business-directory' ),
					),
				),
			),
		),
		'contact-information' => array(
			'label'  => esc_attr__( 'Contact Information', 'lsx-business-directory' ),
			'fields' => array(
				'lsx_bd_primary_contact' => array(
					'type'     => 'text',
					'label'    => __( 'Contact Person', 'lsx-business-directory' ),
					'class'    => array( 'listing-primary-contact form-row-first' ),
					'required' => false,
				),
				'lsx_bd_fax' => array(
					'type'     => 'tel',
					'label'    => __( 'Fax Number', 'lsx-business-directory' ),
					'class'    => array( 'listing-fax form-row-last' ),
					'required' => false,
					'validate' => array( 'phone' ),
				),
				'lsx_bd_website' => array(
					'type'     => 'url',
					'label'    => __( 'Website', 'lsx-business-directory' ),
					'class'    => array( 'listing-website form-row-first' ),
					'required' => false,
				),
				'lsx_bd_primary_email' => array(
					'type'     => 'email',
					'label'    => __( 'Email Address (shown on the listing)', 'lsx-business-directory' ),
					'class'    => array( 'listing-primary-email form-row-last' ),
					'required' => false,
					'validate' => array( 'email' ),
				),
				'lsx_bd_primary_phone' => array(
					'type'     => 'tel',
					'label'    => __( 'Phone Number', 'lsx-business-directory' ),
					'class'    => array( 'listing-primary-phone form-row-first' ),
					'required' => false,
					'validate' => array( 'phone' ),
				),
				'lsx_bd_secondary_email' => array(
					'type'     => 'email',
					'label'    => __( 'Email Address (contact form recipient)', 'lsx-business-directory' ),
					'class'    => array( 'listing-secondary-email form-row-last' ),
					'required' => false,
					'validate' => array( 'email' ),
				),
				'lsx_bd_skype' => array(
					'type'     => 'text',
					'label'    => __( 'Skype Username', 'lsx-business-directory' ),
					'class'    => array( 'listing-skype form-row-first' ),
					'required' => false,
				),
				'lsx_bd_whatsapp' => array(
					'type'     => 'tel',
					'label'    => __( 'Whatsapp Contact Number', 'lsx-business-directory' ),
					'class'    => array( 'listing-whatsapp form-row-last' ),
					'required' => false,
					'validate' => array( 'phone' ),
				),
			),
		),
		'social-media-accounts' => array(
			'label'  => esc_attr__( 'Social Media Accounts', 'lsx-business-directory' ),
			'fields' => array(
				'lsx_bd_facebook' => array(
					'type'     => 'text',
					'label'    => __( 'Facebook', 'lsx-business-directory' ),
					'class'    => array( 'listing-facebook form-row-first' ),
					'required' => false,
				),
				'lsx_bd_twitter' => array(
					'type'     => 'text',
					'label'    => __( 'Twitter', 'lsx-business-directory' ),
					'class'    => array( 'listing-twitter form-row-last' ),
					'required' => false,
				),
				'lsx_bd_linkedin' => array(
					'type'     => 'text',
					'label'    => __( 'LinkedIn', 'lsx-business-directory' ),
					'class'    => array( 'listing-linkedin form-row-first' ),
					'required' => false,
				),
				'lsx_bd_instagram' => array(
					'type'     => 'text',
					'label'    => __( 'Instagram', 'lsx-business-directory' ),
					'class'    => array( 'listing-instagram form-row-last' ),
					'required' => false,
				),
				'lsx_bd_youtube' => array(
					'type'     => 'text',
					'label'    => __( 'Youtube', 'lsx-business-directory' ),
					'class'    => array( 'listing-youtube form-row-first' ),
					'required' => false,
				),
				'lsx_bd_pinterest' => array(
					'type'     => 'text',
					'label'    => __( 'Pinterest', 'lsx-business-directory' ),
					'class'    => array( 'listing-pinterest form-row-last' ),
					'required' => false,
				),
			),
		),
		'physical-address' => array(
			'label'  => esc_attr__( 'Physical Address', 'lsx-business-directory' ),
			'fields' => array(
				'lsx_bd_address_street_number' => array(
					'type'     => 'text',
					'label'    => __( 'Street #', 'lsx-business-directory' ),
					'class'    => array( 'listing-address-street-number form-row-first' ),
					'required' => true,
				),
				'lsx_bd_address_street_name' => array(
					'type'     => 'text',
					'label'    => __( 'Street Address', 'lsx-business-directory' ),
					'class'    => array( 'listing-street-name form-row-last' ),
					'required' => true,
				),
				'lsx_bd_address_suburb' => array(
					'type'     => 'text',
					'label'    => __( 'Suburb', 'lsx-business-directory' ),
					'class'    => array( 'listing-address-suburb form-row-wide' ),
					'required' => true,
				),
				'lsx_bd_address_city' => array(
					'type'     => 'text',
					'label'    => __( 'Town / City', 'lsx-business-directory' ),
					'class'    => array( 'listing-address-city form-row-wide' ),
					'required' => true,
				),
				'lsx_bd_address_postal_code' => array(
					'type'     => 'text',
					'label'    => __( 'Postal Code', 'lsx-business-directory' ),
					'class'    => array( 'listing-address-postal-code form-row-wide' ),
					'required' => true,
					'validate' => array( 'postcode' ),
				),
				'lsx_bd_address_country' => array(
					'type'     => 'country',
					'label'    => __( 'Country', 'lsx-business-directory' ),
					'class'    => array( 'listing-address-country form-row-wide' ),
					'required' => true,
				),
				'lsx_bd_address_province' => array(
					//'type'     => 'state',
					'type'     => 'text',
					'label'    => __( 'Province', 'lsx-business-directory' ),
					'class'    => array( 'listing-address-province form-row-wide' ),
					'required' => true,
				),
			),
		),
		'branding' => array(
			'label'  => esc_attr__( 'Branding', 'lsx-business-directory' ),
			'fields' => array(
				'lsx_bd_thumbnail' => array(
					'type'     => 'text',
					'label'    => __( 'Company Logo', 'lsx-business-directory' ),
					'class'    => array( 'listing-logo form-row-first' ),
					'size'     => 'lsx-thumbnail-wide',
				),
				'lsx_bd_banner' => array(
					'type'     => 'text',
					'label'    => __( 'Banner Image', 'lsx-business-directory' ),
					'class'    => array( 'listing-banner form-row-last' ),
					'size'     => 'lsx-thumbnail-wide',
				),
				'lsx_bd__thumbnail_id' => array(
					'label'    => __( 'Company Logo', 'lsx-business-directory' ),
					'type'  => 'text',
					'required' => true,
					'class' => array( 'listing-post-title form-row-first' ),
				),
				'lsx_bd_banner_id' => array(
					'label'    => __( 'Banner Image', 'lsx-business-directory' ),
					'type'  => 'text',
					'class' => array( 'listing-post-title form-row-last' ),
				),
			),
		),
		'company-information' => array(
			'label'  => esc_attr__( 'Company Information', 'lsx-business-directory' ),
			'fields' => array(
				'lsx_bd_tax_industry' => array(
					'type'     => 'select',
					'label'    => __( 'Industry', 'lsx-business-directory' ),
					'class'    => array( 'listing-industry form-row-first' ),
					'required' => true,
					'options'  => get_taxonomy_as_options( 'industry' ),
				),
				'lsx_bd_tax_location' => array(
					'type'     => 'select',
					'label'    => __( 'Location', 'lsx-business-directory' ),
					'class'    => array( 'listing-location form-row-last' ),
					'required' => true,
					'options'  => get_taxonomy_as_options( 'location' ),
				),
				'lsx_bd_post_content' => array(
					'type'     => 'textarea',
					'label'    => __( 'Description', 'lsx-business-directory' ),
					'class'    => array( 'listing-post-content form-row-wide' ),
					'required' => true,
				),
				'lsx_bd_post_excerpt' => array(
					'type'     => 'textarea',
					'label'    => __( 'Excerpt', 'lsx-business-directory' ),
					'class'    => array( 'listing-post-excerpt form-row-first' ),
				),
			),
		),
	);
	$fields = apply_filters( 'lsx_bd_listing_fields', $fields );
	return $fields;
}

/**
 * Gets the default form field default arguments.
 *
 * @return array
 */
function get_listing_form_field_defaults() {
	$defaults = array(
		'type'         => '',
		'class'        => array( 'form-row-wide' ),
		'label'        => '',
		'placeholder'  => '',
		'required'     => false,
		'autocomplete' => false,
		'class'        => array(),
		'label_class'  => array(),
		'input_class'  => array(),
		'validate'     => array(),
	);
	$defaults = apply_filters( 'lsx_bd_listing_field_defaults', $defaults );
	return $defaults;
}

/**
 * Returns the taxonomy options as an array.
 *
 * @param  string $taxonomy
 * @return array
 */
function get_taxonomy_as_options( $taxonomy = '' ) {
	$options = array(
		'' => __( 'Select', 'lsx-business-directory' ),
	);
	if ( '' !== $taxonomy ) {
		$args = array(
			'taxonomy'        => $taxonomy,
			'hide_empty'      => false,
			'suppress_filter' => true,
			'orderby'         => 'name',
			'order'           => 'ASC',
			'number'          => false,
		);
		$terms = get_terms( $args );
		if ( ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$options[ $term->slug ] = $term->name;
			}
		}
	}
	return $options;
}
