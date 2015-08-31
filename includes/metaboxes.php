<?php
/**
 * Metaboxes for this plugin
 *
 * @package   LSX_Business_Directory
 * @author    LightSpeed
 * @license   GPL-2.0+
 * @link
 * @copyright 2015 LightSpeed
 */

/**
 * A simple function that registers the metaboxes. Triggers imeditenly after its declared.
 *
 * @package 	LSX_Business_Directory
 * @subpackage	metaboxes
 * @category	setup
 */
function lsx_business_directory_register_metaboxes(){
	// this is a base array of a metabox to reuse . this makes is easier.
	$metabox_base = array(
		'post_type'			=> 	array( 'business-directory' ), // array of post types this should be in
		'name'				=>	__('Details', 'lsx-business-directory'), // the label/name of the metabox 
		'section'			=>	'', // section creates heading in the body. metaboxes pages can join existing metaboxes made by LST
		'section_priority'	=>	10, // proirity to includ ewhen adding to existing metaboxes
		'panel'				=>	__( 'General', 'lsx-business-directory' ), // tab panbel to add to
		'panel_priority'	=>	10, // tab panel position priority.
		'context'			=>	'advanced', // metabox type ( core , advanced, side )
		'priority'			=>	'high', // priority of the box in editor
		'fields'			=>	array() // fields this metabox had
	);
	
	/*
	 * register the general tab
	 *
	*/
	$general_metabox		= $metabox_base;
	$general_metabox['id'] 			= 'general';
	$general_metabox['panel'] 		= __( 'General', 'lsx-business-directory' );
	$general_metabox['fields'] 		= array(
		'primary_email'		=>	array( 'label' => __('Primary Email', 'lsx-business-directory') ),
		'secondary_email'	=>	array( 'label' => __('Secondary Email', 'lsx-business-directory') ),
		'primary_phone'		=>	array( 'label' => __('Primary Phone', 'lsx-business-directory') ),
		'secondary_phone'	=>	array( 'label' => __('Secondary Phone', 'lsx-business-directory') ),
		'fax_number'		=>	array( 'label' => __('Fax Number', 'lsx-business-directory') ),
		'website'			=>	array( 'label' => __('Website', 'lsx-business-directory') ),	
	);
	// register it
	lsx_register_metabox( $general_metabox );
	
	$address_metabox		= $metabox_base;
	$address_metabox['id'] 			= 'address';
	$address_metabox['panel'] 		= __( 'Address', 'lsx-business-directory' );
	$address_metabox['fields'] 		= array(
			'address'			=>	array( 'label' => __('Complex Name / Business Park / Street Number', 'lsx-business-directory') ),
			'address_2'			=>	array( 'label' => __('Street Name', 'lsx-business-directory')),
			'address_3'			=>	array( 'label' => __('Suburb', 'lsx-business-directory')),
			'address_4'			=>	array( 'label' => __('City', 'lsx-business-directory')),
			'postal_code'		=>	array( 'label' => __('Postal Code', 'lsx-business-directory')),
			'country'			=>	array( 'label' => __('Country', 'lsx-business-directory'), 'type' => 'country')
	);
	// register it
	lsx_register_metabox( $address_metabox );	
	
}
lsx_business_directory_register_metaboxes();