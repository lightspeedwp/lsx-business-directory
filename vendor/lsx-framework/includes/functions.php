<?php
/**
 * LST helper functions
 *
 * @package   Lsx
 * @author     LightSpeed Team
 * @license   GPL-2.0+
 * @link      
 * @copyright 2015  LightSpeed Team
 */

/**
 * Register a metabox with LST
 *
 * @since 0.0.1
 *
 */
function lsx_register_metabox( $metabox ){

	$metabox = (array) $metabox;

	if( empty( $metabox['name'] ) ){
		trigger_error( sprintf( __( 'A name is required for your metabox.', 'lsx' ) ) );
	}
	if( empty( $metabox['post_type'] ) ){
		trigger_error( sprintf( __( 'A post_type is required for your metabox.', 'lsx' ) ) );
	}

	$defaults = array(
		'post_type'			=> 	'', // string|array
		'name'				=>	'',
		'section'			=>	'',
		'section_priority'	=>	10,
		'panel'				=>	__( 'General', 'lsx' ),
		'panel_priority'	=>	10,
		'context'			=>	'advanced',
		'priority'			=>	'default',
		'fields'			=>	array()
	);
	$metaboxes = Lsx_Metabox::get_instance();
	$metaboxes->register_metabox( array_merge( $defaults, $metabox ) );
	
}

/**
 * Register a Post Type with LSX
 *
 * @since 0.0.1
 *
 */
function lsx_register_post_type( $type, $args ){
	$post_types = Lsx_post_types::get_instance();
	$post_types->register_post_type( $type, $args );	
}

/**
 * Register a taxonomy with LSX
 *
 * @since 0.0.1
 *
 */
function lsx_register_taxonomy( $taxonomy, $post_type, $args = null ){
	$taxonomies = Lsx_post_types::get_instance();
	$taxonomies->register_taxonomy( $taxonomy, $post_type, $args );	
}

/**
 * Returns an array of countries
 * 
 * @return array()
 * Key 		= Country Code
 * Value 	= Country Name
 */
function lsx_get_country_options(){
	return array(
			'AF' => __( 'Afghanistan', 'lsx' ),
			'AX' => __( '&#197;land Islands', 'lsx' ),
			'AL' => __( 'Albania', 'lsx' ),
			'DZ' => __( 'Algeria', 'lsx' ),
			'AD' => __( 'Andorra', 'lsx' ),
			'AO' => __( 'Angola', 'lsx' ),
			'AI' => __( 'Anguilla', 'lsx' ),
			'AQ' => __( 'Antarctica', 'lsx' ),
			'AG' => __( 'Antigua and Barbuda', 'lsx' ),
			'AR' => __( 'Argentina', 'lsx' ),
			'AM' => __( 'Armenia', 'lsx' ),
			'AW' => __( 'Aruba', 'lsx' ),
			'AU' => __( 'Australia', 'lsx' ),
			'AT' => __( 'Austria', 'lsx' ),
			'AZ' => __( 'Azerbaijan', 'lsx' ),
			'BS' => __( 'Bahamas', 'lsx' ),
			'BH' => __( 'Bahrain', 'lsx' ),
			'BD' => __( 'Bangladesh', 'lsx' ),
			'BB' => __( 'Barbados', 'lsx' ),
			'BY' => __( 'Belarus', 'lsx' ),
			'BE' => __( 'Belgium', 'lsx' ),
			'PW' => __( 'Belau', 'lsx' ),
			'BZ' => __( 'Belize', 'lsx' ),
			'BJ' => __( 'Benin', 'lsx' ),
			'BM' => __( 'Bermuda', 'lsx' ),
			'BT' => __( 'Bhutan', 'lsx' ),
			'BO' => __( 'Bolivia', 'lsx' ),
			'BQ' => __( 'Bonaire, Saint Eustatius and Saba', 'lsx' ),
			'BA' => __( 'Bosnia and Herzegovina', 'lsx' ),
			'BW' => __( 'Botswana', 'lsx' ),
			'BV' => __( 'Bouvet Island', 'lsx' ),
			'BR' => __( 'Brazil', 'lsx' ),
			'IO' => __( 'British Indian Ocean Territory', 'lsx' ),
			'VG' => __( 'British Virgin Islands', 'lsx' ),
			'BN' => __( 'Brunei', 'lsx' ),
			'BG' => __( 'Bulgaria', 'lsx' ),
			'BF' => __( 'Burkina Faso', 'lsx' ),
			'BI' => __( 'Burundi', 'lsx' ),
			'KH' => __( 'Cambodia', 'lsx' ),
			'CM' => __( 'Cameroon', 'lsx' ),
			'CA' => __( 'Canada', 'lsx' ),
			'CV' => __( 'Cape Verde', 'lsx' ),
			'KY' => __( 'Cayman Islands', 'lsx' ),
			'CF' => __( 'Central African Republic', 'lsx' ),
			'TD' => __( 'Chad', 'lsx' ),
			'CL' => __( 'Chile', 'lsx' ),
			'CN' => __( 'China', 'lsx' ),
			'CX' => __( 'Christmas Island', 'lsx' ),
			'CC' => __( 'Cocos (Keeling) Islands', 'lsx' ),
			'CO' => __( 'Colombia', 'lsx' ),
			'KM' => __( 'Comoros', 'lsx' ),
			'CG' => __( 'Congo (Brazzaville)', 'lsx' ),
			'CD' => __( 'Congo (Kinshasa)', 'lsx' ),
			'CK' => __( 'Cook Islands', 'lsx' ),
			'CR' => __( 'Costa Rica', 'lsx' ),
			'HR' => __( 'Croatia', 'lsx' ),
			'CU' => __( 'Cuba', 'lsx' ),
			'CW' => __( 'Cura&Ccedil;ao', 'lsx' ),
			'CY' => __( 'Cyprus', 'lsx' ),
			'CZ' => __( 'Czech Republic', 'lsx' ),
			'DK' => __( 'Denmark', 'lsx' ),
			'DJ' => __( 'Djibouti', 'lsx' ),
			'DM' => __( 'Dominica', 'lsx' ),
			'DO' => __( 'Dominican Republic', 'lsx' ),
			'EC' => __( 'Ecuador', 'lsx' ),
			'EG' => __( 'Egypt', 'lsx' ),
			'SV' => __( 'El Salvador', 'lsx' ),
			'GQ' => __( 'Equatorial Guinea', 'lsx' ),
			'ER' => __( 'Eritrea', 'lsx' ),
			'EE' => __( 'Estonia', 'lsx' ),
			'ET' => __( 'Ethiopia', 'lsx' ),
			'FK' => __( 'Falkland Islands', 'lsx' ),
			'FO' => __( 'Faroe Islands', 'lsx' ),
			'FJ' => __( 'Fiji', 'lsx' ),
			'FI' => __( 'Finland', 'lsx' ),
			'FR' => __( 'France', 'lsx' ),
			'GF' => __( 'French Guiana', 'lsx' ),
			'PF' => __( 'French Polynesia', 'lsx' ),
			'TF' => __( 'French Southern Territories', 'lsx' ),
			'GA' => __( 'Gabon', 'lsx' ),
			'GM' => __( 'Gambia', 'lsx' ),
			'GE' => __( 'Georgia', 'lsx' ),
			'DE' => __( 'Germany', 'lsx' ),
			'GH' => __( 'Ghana', 'lsx' ),
			'GI' => __( 'Gibraltar', 'lsx' ),
			'GR' => __( 'Greece', 'lsx' ),
			'GL' => __( 'Greenland', 'lsx' ),
			'GD' => __( 'Grenada', 'lsx' ),
			'GP' => __( 'Guadeloupe', 'lsx' ),
			'GT' => __( 'Guatemala', 'lsx' ),
			'GG' => __( 'Guernsey', 'lsx' ),
			'GN' => __( 'Guinea', 'lsx' ),
			'GW' => __( 'Guinea-Bissau', 'lsx' ),
			'GY' => __( 'Guyana', 'lsx' ),
			'HT' => __( 'Haiti', 'lsx' ),
			'HM' => __( 'Heard Island and McDonald Islands', 'lsx' ),
			'HN' => __( 'Honduras', 'lsx' ),
			'HK' => __( 'Hong Kong', 'lsx' ),
			'HU' => __( 'Hungary', 'lsx' ),
			'IS' => __( 'Iceland', 'lsx' ),
			'IN' => __( 'India', 'lsx' ),
			'ID' => __( 'Indonesia', 'lsx' ),
			'IR' => __( 'Iran', 'lsx' ),
			'IQ' => __( 'Iraq', 'lsx' ),
			'IE' => __( 'Republic of Ireland', 'lsx' ),
			'IM' => __( 'Isle of Man', 'lsx' ),
			'IL' => __( 'Israel', 'lsx' ),
			'IT' => __( 'Italy', 'lsx' ),
			'CI' => __( 'Ivory Coast', 'lsx' ),
			'JM' => __( 'Jamaica', 'lsx' ),
			'JP' => __( 'Japan', 'lsx' ),
			'JE' => __( 'Jersey', 'lsx' ),
			'JO' => __( 'Jordan', 'lsx' ),
			'KZ' => __( 'Kazakhstan', 'lsx' ),
			'KE' => __( 'Kenya', 'lsx' ),
			'KI' => __( 'Kiribati', 'lsx' ),
			'KW' => __( 'Kuwait', 'lsx' ),
			'KG' => __( 'Kyrgyzstan', 'lsx' ),
			'LA' => __( 'Laos', 'lsx' ),
			'LV' => __( 'Latvia', 'lsx' ),
			'LB' => __( 'Lebanon', 'lsx' ),
			'LS' => __( 'Lesotho', 'lsx' ),
			'LR' => __( 'Liberia', 'lsx' ),
			'LY' => __( 'Libya', 'lsx' ),
			'LI' => __( 'Liechtenstein', 'lsx' ),
			'LT' => __( 'Lithuania', 'lsx' ),
			'LU' => __( 'Luxembourg', 'lsx' ),
			'MO' => __( 'Macao S.A.R., China', 'lsx' ),
			'MK' => __( 'Macedonia', 'lsx' ),
			'MG' => __( 'Madagascar', 'lsx' ),
			'MW' => __( 'Malawi', 'lsx' ),
			'MY' => __( 'Malaysia', 'lsx' ),
			'MV' => __( 'Maldives', 'lsx' ),
			'ML' => __( 'Mali', 'lsx' ),
			'MT' => __( 'Malta', 'lsx' ),
			'MH' => __( 'Marshall Islands', 'lsx' ),
			'MQ' => __( 'Martinique', 'lsx' ),
			'MR' => __( 'Mauritania', 'lsx' ),
			'MU' => __( 'Mauritius', 'lsx' ),
			'YT' => __( 'Mayotte', 'lsx' ),
			'MX' => __( 'Mexico', 'lsx' ),
			'FM' => __( 'Micronesia', 'lsx' ),
			'MD' => __( 'Moldova', 'lsx' ),
			'MC' => __( 'Monaco', 'lsx' ),
			'MN' => __( 'Mongolia', 'lsx' ),
			'ME' => __( 'Montenegro', 'lsx' ),
			'MS' => __( 'Montserrat', 'lsx' ),
			'MA' => __( 'Morocco', 'lsx' ),
			'MZ' => __( 'Mozambique', 'lsx' ),
			'MM' => __( 'Myanmar', 'lsx' ),
			'NA' => __( 'Namibia', 'lsx' ),
			'NR' => __( 'Nauru', 'lsx' ),
			'NP' => __( 'Nepal', 'lsx' ),
			'NL' => __( 'Netherlands', 'lsx' ),
			'AN' => __( 'Netherlands Antilles', 'lsx' ),
			'NC' => __( 'New Caledonia', 'lsx' ),
			'NZ' => __( 'New Zealand', 'lsx' ),
			'NI' => __( 'Nicaragua', 'lsx' ),
			'NE' => __( 'Niger', 'lsx' ),
			'NG' => __( 'Nigeria', 'lsx' ),
			'NU' => __( 'Niue', 'lsx' ),
			'NF' => __( 'Norfolk Island', 'lsx' ),
			'KP' => __( 'North Korea', 'lsx' ),
			'NO' => __( 'Norway', 'lsx' ),
			'OM' => __( 'Oman', 'lsx' ),
			'PK' => __( 'Pakistan', 'lsx' ),
			'PS' => __( 'Palestinian Territory', 'lsx' ),
			'PA' => __( 'Panama', 'lsx' ),
			'PG' => __( 'Papua New Guinea', 'lsx' ),
			'PY' => __( 'Paraguay', 'lsx' ),
			'PE' => __( 'Peru', 'lsx' ),
			'PH' => __( 'Philippines', 'lsx' ),
			'PN' => __( 'Pitcairn', 'lsx' ),
			'PL' => __( 'Poland', 'lsx' ),
			'PT' => __( 'Portugal', 'lsx' ),
			'QA' => __( 'Qatar', 'lsx' ),
			'RE' => __( 'Reunion', 'lsx' ),
			'RO' => __( 'Romania', 'lsx' ),
			'RU' => __( 'Russia', 'lsx' ),
			'RW' => __( 'Rwanda', 'lsx' ),
			'BL' => __( 'Saint Barth&eacute;lemy', 'lsx' ),
			'SH' => __( 'Saint Helena', 'lsx' ),
			'KN' => __( 'Saint Kitts and Nevis', 'lsx' ),
			'LC' => __( 'Saint Lucia', 'lsx' ),
			'MF' => __( 'Saint Martin (French part)', 'lsx' ),
			'SX' => __( 'Saint Martin (Dutch part)', 'lsx' ),
			'PM' => __( 'Saint Pierre and Miquelon', 'lsx' ),
			'VC' => __( 'Saint Vincent and the Grenadines', 'lsx' ),
			'SM' => __( 'San Marino', 'lsx' ),
			'ST' => __( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe', 'lsx' ),
			'SA' => __( 'Saudi Arabia', 'lsx' ),
			'SN' => __( 'Senegal', 'lsx' ),
			'RS' => __( 'Serbia', 'lsx' ),
			'SC' => __( 'Seychelles', 'lsx' ),
			'SL' => __( 'Sierra Leone', 'lsx' ),
			'SG' => __( 'Singapore', 'lsx' ),
			'SK' => __( 'Slovakia', 'lsx' ),
			'SI' => __( 'Slovenia', 'lsx' ),
			'SB' => __( 'Solomon Islands', 'lsx' ),
			'SO' => __( 'Somalia', 'lsx' ),
			'ZA' => __( 'South Africa', 'lsx' ),
			'GS' => __( 'South Georgia/Sandwich Islands', 'lsx' ),
			'KR' => __( 'South Korea', 'lsx' ),
			'SS' => __( 'South Sudan', 'lsx' ),
			'ES' => __( 'Spain', 'lsx' ),
			'LK' => __( 'Sri Lanka', 'lsx' ),
			'SD' => __( 'Sudan', 'lsx' ),
			'SR' => __( 'Suriname', 'lsx' ),
			'SJ' => __( 'Svalbard and Jan Mayen', 'lsx' ),
			'SZ' => __( 'Swaziland', 'lsx' ),
			'SE' => __( 'Sweden', 'lsx' ),
			'CH' => __( 'Switzerland', 'lsx' ),
			'SY' => __( 'Syria', 'lsx' ),
			'TW' => __( 'Taiwan', 'lsx' ),
			'TJ' => __( 'Tajikistan', 'lsx' ),
			'TZ' => __( 'Tanzania', 'lsx' ),
			'TH' => __( 'Thailand', 'lsx' ),
			'TL' => __( 'Timor-Leste', 'lsx' ),
			'TG' => __( 'Togo', 'lsx' ),
			'TK' => __( 'Tokelau', 'lsx' ),
			'TO' => __( 'Tonga', 'lsx' ),
			'TT' => __( 'Trinidad and Tobago', 'lsx' ),
			'TN' => __( 'Tunisia', 'lsx' ),
			'TR' => __( 'Turkey', 'lsx' ),
			'TM' => __( 'Turkmenistan', 'lsx' ),
			'TC' => __( 'Turks and Caicos Islands', 'lsx' ),
			'TV' => __( 'Tuvalu', 'lsx' ),
			'UG' => __( 'Uganda', 'lsx' ),
			'UA' => __( 'Ukraine', 'lsx' ),
			'AE' => __( 'United Arab Emirates', 'lsx' ),
			'GB' => __( 'United Kingdom (UK)', 'lsx' ),
			'US' => __( 'United States (US)', 'lsx' ),
			'UY' => __( 'Uruguay', 'lsx' ),
			'UZ' => __( 'Uzbekistan', 'lsx' ),
			'VU' => __( 'Vanuatu', 'lsx' ),
			'VA' => __( 'Vatican', 'lsx' ),
			'VE' => __( 'Venezuela', 'lsx' ),
			'VN' => __( 'Vietnam', 'lsx' ),
			'WF' => __( 'Wallis and Futuna', 'lsx' ),
			'EH' => __( 'Western Sahara', 'lsx' ),
			'WS' => __( 'Western Samoa', 'lsx' ),
			'YE' => __( 'Yemen', 'lsx' ),
			'ZM' => __( 'Zambia', 'lsx' ),
			'ZW' => __( 'Zimbabwe', 'lsx' )
	);
}

/**
 * Returns the name of a country code you supply. Returns false if no country code is found
 * @param $code	String
 * @return String
 * Key 		= Country Code
 * Value 	= Country Name
 */
function lsx_country_name($code = false){
	$title = $code;
	if(false !== $code){
		$countries = lsx_get_country_options();
		if(isset($countries[$code])){
			$title = $countries[$code];
		}
	}
	return $title;
}