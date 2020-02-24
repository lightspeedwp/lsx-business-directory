<?php
/**
 * LSX Starter Plugin functions.
 *
 * @package lsx-business-directory
 */

/**
 * Adds text domain.
 */
function lsx_business_directory_load_plugin_textdomain() {
	load_plugin_textdomain( 'lsx-business-directory', false, basename( LSX_BD_PATH ) . '/languages' );
}
add_action( 'init', 'lsx_business_directory_load_plugin_textdomain' );

// // register CMB2 Business Directory custom fields.
// add_action( 'cmb2_init', 'register_businessdirectory_fields' );
// function register_businessdirectory_fields() {
// $prefix = 'businessdirectory';

// $cmb_images = new_cmb2_box(
// array(
// 'id'           => $prefix . '_images_metabox',
// 'title'        => esc_html__( 'Business Images.', 'lsx-business-directory' ),
// 'object_types' => array( 'business-directory' ),
// 'show_on'      => array(
// 'id' => array( 2 ),
// ), // Specific post IDs to display this metabox
// )
// );

// $cmb_images->add_field(
// array(
// 'name' => esc_html__( 'Featured Image', 'lsx-business-directory' ),
// 'desc' => esc_html__( 'Featured image for a Business.', 'lsx-business-directory' ),
// 'id'   => $prefix . '_business_logo',
// 'type' => 'file',
// )
// );

// $cmb_images->add_field(
// array(
// 'name' => esc_html__( 'Banner Image', 'lsx-business-directory' ),
// 'desc' => esc_html__( 'Banner image for a Business.', 'lsx-business-directory' ),
// 'id'   => $prefix . '_business_banner',
// 'type' => 'file',
// )
// );

// $cmb_images->add_field(
// array(
// 'name'    => esc_html__( 'Banner Colour', 'lsx-business-directory' ),
// 'desc'    => esc_html__( 'Banner colour if Banner image is missing.', 'lsx-business-directory' ),
// 'id'      => $prefix . '_business_banner_colour',
// 'type'    => 'colorpicker',
// 'default' => '#ffffff',
// )
// );

// $cmb_address = new_cmb2_box(
// array(
// 'id'           => $prefix . '_address_metabox',
// 'title'        => esc_html__( 'Business Address.', 'lsx-business-directory' ),
// 'object_types' => array( 'business-directory' ),
// )
// );

// TODO: Google Maps Search
// $cmb_address->add_field(
// array(
// 'name' => esc_html__( 'Google Maps Search', 'lsx-business-directory' ),
// 'id'   => $prefix . '_business_google_maps_search',
// 'type' => 'text',
// )
// );

// $cmb_address->add_field(
// array(
// 'name' => esc_html__( 'Complex Name / Business Park / Street Number', 'lsx-business-directory' ),
// 'id'   => $prefix . '_business_address_1',
// 'type' => 'text',
// )
// );

// $cmb_address->add_field(
// array(
// 'name' => esc_html__( 'Street Name', 'lsx-business-directory' ),
// 'id'   => $prefix . '_business_address_2',
// 'type' => 'text',
// )
// );

// $cmb_address->add_field(
// array(
// 'name' => esc_html__( 'Suburb', 'lsx-business-directory' ),
// 'id'   => $prefix . '_business_address_3',
// 'type' => 'text',
// )
// );

// $cmb_address->add_field(
// array(
// 'name' => esc_html__( 'City', 'lsx-business-directory' ),
// 'id'   => $prefix . '_business_address_4',
// 'type' => 'text',
// )
// );

// $cmb_address->add_field(
// array(
// 'name' => esc_html__( 'Postal Code', 'lsx-business-directory' ),
// 'id'   => $prefix . '_business_postal_code',
// 'type' => 'text',
// )
// );

// $cmb_address->add_field(
// array(
// 'name'             => esc_html__( 'Country', 'lsx-business-directory' ),
// 'id'               => $prefix . '_business_country',
// 'type'             => 'select',
// 'show_option_none' => 'Choose a Country',
// 'default'          => 'ZA',
// 'options'          => get_country_options(),
// )
// );

// $cmb_address->add_field(
// array(
// 'name' => esc_html__( 'State / Province', 'lsx-business-directory' ),
// 'id'   => $prefix . '_business_province',
// 'type' => 'text',
// )
// );

// $branches_group_field_id = $cmb_address->add_field(
// array(
// 'id'          => $prefix . '_business_branches',
// 'type'        => 'group',
// 'description' => esc_html__( 'Business Branches', 'lsx-business-directory' ),
// 'repeatable'  => true,
// 'options'     => array(
// 'group_title'    => esc_html__( 'Branch {#}', 'lsx-business-directory' ), // since version 1.1.4, {#} gets replaced by row number
// 'add_button'     => esc_html__( 'Add Another Branch', 'lsx-business-directory' ),
// 'remove_button'  => esc_html__( 'Remove Branch', 'lsx-business-directory' ),
// 'sortable'       => true,
// 'closed'         => true, // true to have the groups closed by default
// 'remove_confirm' => esc_html__( 'Are you sure you want to remove thie Branch?', 'lsx-business-directory' ),
// ),
// )
// );

// Id's for group's fields only need to be unique for the group. Prefix is not needed.
// $cmb_address->add_group_field(
// $branches_group_field_id,
// array(
// 'name' => esc_html__( 'Branch Name', 'lsx-business-directory' ),
// 'id'   => 'branch_name',
// 'type' => 'text',
// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
// )
// );

// $cmb_address->add_group_field(
// $branches_group_field_id,
// array(
// 'name' => esc_html__( 'Branch Telephone', 'lsx-business-directory' ),
// 'id'   => 'branch_phone',
// 'type' => 'text',
// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
// )
// );

// $cmb_address->add_group_field(
// $branches_group_field_id,
// array(
// 'name' => esc_html__( 'Branch Email', 'lsx-business-directory' ),
// 'id'   => 'branch_email',
// 'type' => 'text_email',
// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
// )
// );

// $cmb_address->add_group_field(
// $branches_group_field_id,
// array(
// 'name' => esc_html__( 'Branch Website', 'lsx-business-directory' ),
// 'id'   => 'branch_website',
// 'type' => 'text_url',
// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
// )
// );

// TODO: Google Maps Search.
// $cmb_address->add_group_field(
// $branches_group_field_id,
// array(
// 'name' => esc_html__( 'Branch Google Maps Search', 'lsx-business-directory' ),
// 'id'   => 'branch_google_maps_search',
// 'type' => 'text',
// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
// )
// );

// $cmb_contact = new_cmb2_box(
// array(
// 'id'           => $prefix . '_contact_metabox',
// 'title'        => esc_html__( 'Business Contact Details.', 'lsx-business-directory' ),
// 'object_types' => array( 'business-directory' ),
// )
// );

// $cmb_contact->add_field(
// array(
// 'name' => esc_html__( 'Business Primary Email.', 'lsx-business-directory' ),
// 'id'   => $prefix . '_primary_email',
// 'type' => 'text_email',
// )
// );

// $cmb_contact->add_field(
// array(
// 'name' => esc_html__( 'Business Secondary Email.', 'lsx-business-directory' ),
// 'id'   => $prefix . '_secondary_email',
// 'type' => 'text_email',
// )
// );

// $cmb_contact->add_field(
// array(
// 'name' => esc_html__( 'Business Primary Phone.', 'lsx-business-directory' ),
// 'id'   => $prefix . '_primary_phone',
// 'type' => 'text',
// )
// );

// $cmb_contact->add_field(
// array(
// 'name' => esc_html__( 'Business Secondary Phone.', 'lsx-business-directory' ),
// 'id'   => $prefix . '_secondary_phone',
// 'type' => 'text',
// )
// );

// $cmb_contact->add_field(
// array(
// 'name' => esc_html__( 'Business Fax.', 'lsx-business-directory' ),
// 'id'   => $prefix . '_fax',
// 'type' => 'text',
// )
// );

// $cmb_contact->add_field(
// array(
// 'name' => esc_html__( 'Business Website.', 'lsx-business-directory' ),
// 'id'   => $prefix . '_website',
// 'type' => 'text_url',
// )
// );
// }

/**
 * Returns an array of countries
 *
 * @return array()
 * Key          = Country Code
 * Value        = Country Name
 */
function get_country_options() {
	return array(
		'AF' => esc_html__( 'Afghanistan', 'lsx-business-directory' ),
		'AX' => esc_html__( '&#197;land Islands', 'lsx-business-directory' ),
		'AL' => esc_html__( 'Albania', 'lsx-business-directory' ),
		'DZ' => esc_html__( 'Algeria', 'lsx-business-directory' ),
		'AD' => esc_html__( 'Andorra', 'lsx-business-directory' ),
		'AO' => esc_html__( 'Angola', 'lsx-business-directory' ),
		'AI' => esc_html__( 'Anguilla', 'lsx-business-directory' ),
		'AQ' => esc_html__( 'Antarctica', 'lsx-business-directory' ),
		'AG' => esc_html__( 'Antigua and Barbuda', 'lsx-business-directory' ),
		'AR' => esc_html__( 'Argentina', 'lsx-business-directory' ),
		'AM' => esc_html__( 'Armenia', 'lsx-business-directory' ),
		'AW' => esc_html__( 'Aruba', 'lsx-business-directory' ),
		'AU' => esc_html__( 'Australia', 'lsx-business-directory' ),
		'AT' => esc_html__( 'Austria', 'lsx-business-directory' ),
		'AZ' => esc_html__( 'Azerbaijan', 'lsx-business-directory' ),
		'BS' => esc_html__( 'Bahamas', 'lsx-business-directory' ),
		'BH' => esc_html__( 'Bahrain', 'lsx-business-directory' ),
		'BD' => esc_html__( 'Bangladesh', 'lsx-business-directory' ),
		'BB' => esc_html__( 'Barbados', 'lsx-business-directory' ),
		'BY' => esc_html__( 'Belarus', 'lsx-business-directory' ),
		'BE' => esc_html__( 'Belgium', 'lsx-business-directory' ),
		'PW' => esc_html__( 'Belau', 'lsx-business-directory' ),
		'BZ' => esc_html__( 'Belize', 'lsx-business-directory' ),
		'BJ' => esc_html__( 'Benin', 'lsx-business-directory' ),
		'BM' => esc_html__( 'Bermuda', 'lsx-business-directory' ),
		'BT' => esc_html__( 'Bhutan', 'lsx-business-directory' ),
		'BO' => esc_html__( 'Bolivia', 'lsx-business-directory' ),
		'BQ' => esc_html__( 'Bonaire, Saint Eustatius and Saba', 'lsx-business-directory' ),
		'BA' => esc_html__( 'Bosnia and Herzegovina', 'lsx-business-directory' ),
		'BW' => esc_html__( 'Botswana', 'lsx-business-directory' ),
		'BV' => esc_html__( 'Bouvet Island', 'lsx-business-directory' ),
		'BR' => esc_html__( 'Brazil', 'lsx-business-directory' ),
		'IO' => esc_html__( 'British Indian Ocean Territory', 'lsx-business-directory' ),
		'VG' => esc_html__( 'British Virgin Islands', 'lsx-business-directory' ),
		'BN' => esc_html__( 'Brunei', 'lsx-business-directory' ),
		'BG' => esc_html__( 'Bulgaria', 'lsx-business-directory' ),
		'BF' => esc_html__( 'Burkina Faso', 'lsx-business-directory' ),
		'BI' => esc_html__( 'Burundi', 'lsx-business-directory' ),
		'KH' => esc_html__( 'Cambodia', 'lsx-business-directory' ),
		'CM' => esc_html__( 'Cameroon', 'lsx-business-directory' ),
		'CA' => esc_html__( 'Canada', 'lsx-business-directory' ),
		'CV' => esc_html__( 'Cape Verde', 'lsx-business-directory' ),
		'KY' => esc_html__( 'Cayman Islands', 'lsx-business-directory' ),
		'CF' => esc_html__( 'Central African Republic', 'lsx-business-directory' ),
		'TD' => esc_html__( 'Chad', 'lsx-business-directory' ),
		'CL' => esc_html__( 'Chile', 'lsx-business-directory' ),
		'CN' => esc_html__( 'China', 'lsx-business-directory' ),
		'CX' => esc_html__( 'Christmas Island', 'lsx-business-directory' ),
		'CC' => esc_html__( 'Cocos (Keeling) Islands', 'lsx-business-directory' ),
		'CO' => esc_html__( 'Colombia', 'lsx-business-directory' ),
		'KM' => esc_html__( 'Comoros', 'lsx-business-directory' ),
		'CG' => esc_html__( 'Congo (Brazzaville)', 'lsx-business-directory' ),
		'CD' => esc_html__( 'Congo (Kinshasa)', 'lsx-business-directory' ),
		'CK' => esc_html__( 'Cook Islands', 'lsx-business-directory' ),
		'CR' => esc_html__( 'Costa Rica', 'lsx-business-directory' ),
		'HR' => esc_html__( 'Croatia', 'lsx-business-directory' ),
		'CU' => esc_html__( 'Cuba', 'lsx-business-directory' ),
		'CW' => esc_html__( 'Cura&Ccedil;ao', 'lsx-business-directory' ),
		'CY' => esc_html__( 'Cyprus', 'lsx-business-directory' ),
		'CZ' => esc_html__( 'Czech Republic', 'lsx-business-directory' ),
		'DK' => esc_html__( 'Denmark', 'lsx-business-directory' ),
		'DJ' => esc_html__( 'Djibouti', 'lsx-business-directory' ),
		'DM' => esc_html__( 'Dominica', 'lsx-business-directory' ),
		'DO' => esc_html__( 'Dominican Republic', 'lsx-business-directory' ),
		'EC' => esc_html__( 'Ecuador', 'lsx-business-directory' ),
		'EG' => esc_html__( 'Egypt', 'lsx-business-directory' ),
		'SV' => esc_html__( 'El Salvador', 'lsx-business-directory' ),
		'GQ' => esc_html__( 'Equatorial Guinea', 'lsx-business-directory' ),
		'ER' => esc_html__( 'Eritrea', 'lsx-business-directory' ),
		'EE' => esc_html__( 'Estonia', 'lsx-business-directory' ),
		'ET' => esc_html__( 'Ethiopia', 'lsx-business-directory' ),
		'FK' => esc_html__( 'Falkland Islands', 'lsx-business-directory' ),
		'FO' => esc_html__( 'Faroe Islands', 'lsx-business-directory' ),
		'FJ' => esc_html__( 'Fiji', 'lsx-business-directory' ),
		'FI' => esc_html__( 'Finland', 'lsx-business-directory' ),
		'FR' => esc_html__( 'France', 'lsx-business-directory' ),
		'GF' => esc_html__( 'French Guiana', 'lsx-business-directory' ),
		'PF' => esc_html__( 'French Polynesia', 'lsx-business-directory' ),
		'TF' => esc_html__( 'French Southern Territories', 'lsx-business-directory' ),
		'GA' => esc_html__( 'Gabon', 'lsx-business-directory' ),
		'GM' => esc_html__( 'Gambia', 'lsx-business-directory' ),
		'GE' => esc_html__( 'Georgia', 'lsx-business-directory' ),
		'DE' => esc_html__( 'Germany', 'lsx-business-directory' ),
		'GH' => esc_html__( 'Ghana', 'lsx-business-directory' ),
		'GI' => esc_html__( 'Gibraltar', 'lsx-business-directory' ),
		'GR' => esc_html__( 'Greece', 'lsx-business-directory' ),
		'GL' => esc_html__( 'Greenland', 'lsx-business-directory' ),
		'GD' => esc_html__( 'Grenada', 'lsx-business-directory' ),
		'GP' => esc_html__( 'Guadeloupe', 'lsx-business-directory' ),
		'GT' => esc_html__( 'Guatemala', 'lsx-business-directory' ),
		'GG' => esc_html__( 'Guernsey', 'lsx-business-directory' ),
		'GN' => esc_html__( 'Guinea', 'lsx-business-directory' ),
		'GW' => esc_html__( 'Guinea-Bissau', 'lsx-business-directory' ),
		'GY' => esc_html__( 'Guyana', 'lsx-business-directory' ),
		'HT' => esc_html__( 'Haiti', 'lsx-business-directory' ),
		'HM' => esc_html__( 'Heard Island and McDonald Islands', 'lsx-business-directory' ),
		'HN' => esc_html__( 'Honduras', 'lsx-business-directory' ),
		'HK' => esc_html__( 'Hong Kong', 'lsx-business-directory' ),
		'HU' => esc_html__( 'Hungary', 'lsx-business-directory' ),
		'IS' => esc_html__( 'Iceland', 'lsx-business-directory' ),
		'IN' => esc_html__( 'India', 'lsx-business-directory' ),
		'ID' => esc_html__( 'Indonesia', 'lsx-business-directory' ),
		'IR' => esc_html__( 'Iran', 'lsx-business-directory' ),
		'IQ' => esc_html__( 'Iraq', 'lsx-business-directory' ),
		'IE' => esc_html__( 'Republic of Ireland', 'lsx-business-directory' ),
		'IM' => esc_html__( 'Isle of Man', 'lsx-business-directory' ),
		'IL' => esc_html__( 'Israel', 'lsx-business-directory' ),
		'IT' => esc_html__( 'Italy', 'lsx-business-directory' ),
		'CI' => esc_html__( 'Ivory Coast', 'lsx-business-directory' ),
		'JM' => esc_html__( 'Jamaica', 'lsx-business-directory' ),
		'JP' => esc_html__( 'Japan', 'lsx-business-directory' ),
		'JE' => esc_html__( 'Jersey', 'lsx-business-directory' ),
		'JO' => esc_html__( 'Jordan', 'lsx-business-directory' ),
		'KZ' => esc_html__( 'Kazakhstan', 'lsx-business-directory' ),
		'KE' => esc_html__( 'Kenya', 'lsx-business-directory' ),
		'KI' => esc_html__( 'Kiribati', 'lsx-business-directory' ),
		'KW' => esc_html__( 'Kuwait', 'lsx-business-directory' ),
		'KG' => esc_html__( 'Kyrgyzstan', 'lsx-business-directory' ),
		'LA' => esc_html__( 'Laos', 'lsx-business-directory' ),
		'LV' => esc_html__( 'Latvia', 'lsx-business-directory' ),
		'LB' => esc_html__( 'Lebanon', 'lsx-business-directory' ),
		'LS' => esc_html__( 'Lesotho', 'lsx-business-directory' ),
		'LR' => esc_html__( 'Liberia', 'lsx-business-directory' ),
		'LY' => esc_html__( 'Libya', 'lsx-business-directory' ),
		'LI' => esc_html__( 'Liechtenstein', 'lsx-business-directory' ),
		'LT' => esc_html__( 'Lithuania', 'lsx-business-directory' ),
		'LU' => esc_html__( 'Luxembourg', 'lsx-business-directory' ),
		'MO' => esc_html__( 'Macao S.A.R., China', 'lsx-business-directory' ),
		'MK' => esc_html__( 'Macedonia', 'lsx-business-directory' ),
		'MG' => esc_html__( 'Madagascar', 'lsx-business-directory' ),
		'MW' => esc_html__( 'Malawi', 'lsx-business-directory' ),
		'MY' => esc_html__( 'Malaysia', 'lsx-business-directory' ),
		'MV' => esc_html__( 'Maldives', 'lsx-business-directory' ),
		'ML' => esc_html__( 'Mali', 'lsx-business-directory' ),
		'MT' => esc_html__( 'Malta', 'lsx-business-directory' ),
		'MH' => esc_html__( 'Marshall Islands', 'lsx-business-directory' ),
		'MQ' => esc_html__( 'Martinique', 'lsx-business-directory' ),
		'MR' => esc_html__( 'Mauritania', 'lsx-business-directory' ),
		'MU' => esc_html__( 'Mauritius', 'lsx-business-directory' ),
		'YT' => esc_html__( 'Mayotte', 'lsx-business-directory' ),
		'MX' => esc_html__( 'Mexico', 'lsx-business-directory' ),
		'FM' => esc_html__( 'Micronesia', 'lsx-business-directory' ),
		'MD' => esc_html__( 'Moldova', 'lsx-business-directory' ),
		'MC' => esc_html__( 'Monaco', 'lsx-business-directory' ),
		'MN' => esc_html__( 'Mongolia', 'lsx-business-directory' ),
		'ME' => esc_html__( 'Montenegro', 'lsx-business-directory' ),
		'MS' => esc_html__( 'Montserrat', 'lsx-business-directory' ),
		'MA' => esc_html__( 'Morocco', 'lsx-business-directory' ),
		'MZ' => esc_html__( 'Mozambique', 'lsx-business-directory' ),
		'MM' => esc_html__( 'Myanmar', 'lsx-business-directory' ),
		'NA' => esc_html__( 'Namibia', 'lsx-business-directory' ),
		'NR' => esc_html__( 'Nauru', 'lsx-business-directory' ),
		'NP' => esc_html__( 'Nepal', 'lsx-business-directory' ),
		'NL' => esc_html__( 'Netherlands', 'lsx-business-directory' ),
		'AN' => esc_html__( 'Netherlands Antilles', 'lsx-business-directory' ),
		'NC' => esc_html__( 'New Caledonia', 'lsx-business-directory' ),
		'NZ' => esc_html__( 'New Zealand', 'lsx-business-directory' ),
		'NI' => esc_html__( 'Nicaragua', 'lsx-business-directory' ),
		'NE' => esc_html__( 'Niger', 'lsx-business-directory' ),
		'NG' => esc_html__( 'Nigeria', 'lsx-business-directory' ),
		'NU' => esc_html__( 'Niue', 'lsx-business-directory' ),
		'NF' => esc_html__( 'Norfolk Island', 'lsx-business-directory' ),
		'KP' => esc_html__( 'North Korea', 'lsx-business-directory' ),
		'NO' => esc_html__( 'Norway', 'lsx-business-directory' ),
		'OM' => esc_html__( 'Oman', 'lsx-business-directory' ),
		'PK' => esc_html__( 'Pakistan', 'lsx-business-directory' ),
		'PS' => esc_html__( 'Palestinian Territory', 'lsx-business-directory' ),
		'PA' => esc_html__( 'Panama', 'lsx-business-directory' ),
		'PG' => esc_html__( 'Papua New Guinea', 'lsx-business-directory' ),
		'PY' => esc_html__( 'Paraguay', 'lsx-business-directory' ),
		'PE' => esc_html__( 'Peru', 'lsx-business-directory' ),
		'PH' => esc_html__( 'Philippines', 'lsx-business-directory' ),
		'PN' => esc_html__( 'Pitcairn', 'lsx-business-directory' ),
		'PL' => esc_html__( 'Poland', 'lsx-business-directory' ),
		'PT' => esc_html__( 'Portugal', 'lsx-business-directory' ),
		'QA' => esc_html__( 'Qatar', 'lsx-business-directory' ),
		'RE' => esc_html__( 'Reunion', 'lsx-business-directory' ),
		'RO' => esc_html__( 'Romania', 'lsx-business-directory' ),
		'RU' => esc_html__( 'Russia', 'lsx-business-directory' ),
		'RW' => esc_html__( 'Rwanda', 'lsx-business-directory' ),
		'BL' => esc_html__( 'Saint Barth&eacute;lemy', 'lsx-business-directory' ),
		'SH' => esc_html__( 'Saint Helena', 'lsx-business-directory' ),
		'KN' => esc_html__( 'Saint Kitts and Nevis', 'lsx-business-directory' ),
		'LC' => esc_html__( 'Saint Lucia', 'lsx-business-directory' ),
		'MF' => esc_html__( 'Saint Martin (French part)', 'lsx-business-directory' ),
		'SX' => esc_html__( 'Saint Martin (Dutch part)', 'lsx-business-directory' ),
		'PM' => esc_html__( 'Saint Pierre and Miquelon', 'lsx-business-directory' ),
		'VC' => esc_html__( 'Saint Vincent and the Grenadines', 'lsx-business-directory' ),
		'SM' => esc_html__( 'San Marino', 'lsx-business-directory' ),
		'ST' => esc_html__( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe', 'lsx-business-directory' ),
		'SA' => esc_html__( 'Saudi Arabia', 'lsx-business-directory' ),
		'SN' => esc_html__( 'Senegal', 'lsx-business-directory' ),
		'RS' => esc_html__( 'Serbia', 'lsx-business-directory' ),
		'SC' => esc_html__( 'Seychelles', 'lsx-business-directory' ),
		'SL' => esc_html__( 'Sierra Leone', 'lsx-business-directory' ),
		'SG' => esc_html__( 'Singapore', 'lsx-business-directory' ),
		'SK' => esc_html__( 'Slovakia', 'lsx-business-directory' ),
		'SI' => esc_html__( 'Slovenia', 'lsx-business-directory' ),
		'SB' => esc_html__( 'Solomon Islands', 'lsx-business-directory' ),
		'SO' => esc_html__( 'Somalia', 'lsx-business-directory' ),
		'ZA' => esc_html__( 'South Africa', 'lsx-business-directory' ),
		'GS' => esc_html__( 'South Georgia/Sandwich Islands', 'lsx-business-directory' ),
		'KR' => esc_html__( 'South Korea', 'lsx-business-directory' ),
		'SS' => esc_html__( 'South Sudan', 'lsx-business-directory' ),
		'ES' => esc_html__( 'Spain', 'lsx-business-directory' ),
		'LK' => esc_html__( 'Sri Lanka', 'lsx-business-directory' ),
		'SD' => esc_html__( 'Sudan', 'lsx-business-directory' ),
		'SR' => esc_html__( 'Suriname', 'lsx-business-directory' ),
		'SJ' => esc_html__( 'Svalbard and Jan Mayen', 'lsx-business-directory' ),
		'SZ' => esc_html__( 'Swaziland', 'lsx-business-directory' ),
		'SE' => esc_html__( 'Sweden', 'lsx-business-directory' ),
		'CH' => esc_html__( 'Switzerland', 'lsx-business-directory' ),
		'SY' => esc_html__( 'Syria', 'lsx-business-directory' ),
		'TW' => esc_html__( 'Taiwan', 'lsx-business-directory' ),
		'TJ' => esc_html__( 'Tajikistan', 'lsx-business-directory' ),
		'TZ' => esc_html__( 'Tanzania', 'lsx-business-directory' ),
		'TH' => esc_html__( 'Thailand', 'lsx-business-directory' ),
		'TL' => esc_html__( 'Timor-Leste', 'lsx-business-directory' ),
		'TG' => esc_html__( 'Togo', 'lsx-business-directory' ),
		'TK' => esc_html__( 'Tokelau', 'lsx-business-directory' ),
		'TO' => esc_html__( 'Tonga', 'lsx-business-directory' ),
		'TT' => esc_html__( 'Trinidad and Tobago', 'lsx-business-directory' ),
		'TN' => esc_html__( 'Tunisia', 'lsx-business-directory' ),
		'TR' => esc_html__( 'Turkey', 'lsx-business-directory' ),
		'TM' => esc_html__( 'Turkmenistan', 'lsx-business-directory' ),
		'TC' => esc_html__( 'Turks and Caicos Islands', 'lsx-business-directory' ),
		'TV' => esc_html__( 'Tuvalu', 'lsx-business-directory' ),
		'UG' => esc_html__( 'Uganda', 'lsx-business-directory' ),
		'UA' => esc_html__( 'Ukraine', 'lsx-business-directory' ),
		'AE' => esc_html__( 'United Arab Emirates', 'lsx-business-directory' ),
		'GB' => esc_html__( 'United Kingdom (UK)', 'lsx-business-directory' ),
		'US' => esc_html__( 'United States (US)', 'lsx-business-directory' ),
		'UY' => esc_html__( 'Uruguay', 'lsx-business-directory' ),
		'UZ' => esc_html__( 'Uzbekistan', 'lsx-business-directory' ),
		'VU' => esc_html__( 'Vanuatu', 'lsx-business-directory' ),
		'VA' => esc_html__( 'Vatican', 'lsx-business-directory' ),
		'VE' => esc_html__( 'Venezuela', 'lsx-business-directory' ),
		'VN' => esc_html__( 'Vietnam', 'lsx-business-directory' ),
		'WF' => esc_html__( 'Wallis and Futuna', 'lsx-business-directory' ),
		'EH' => esc_html__( 'Western Sahara', 'lsx-business-directory' ),
		'WS' => esc_html__( 'Western Samoa', 'lsx-business-directory' ),
		'YE' => esc_html__( 'Yemen', 'lsx-business-directory' ),
		'ZM' => esc_html__( 'Zambia', 'lsx-business-directory' ),
		'ZW' => esc_html__( 'Zimbabwe', 'lsx-business-directory' ),
	);
}