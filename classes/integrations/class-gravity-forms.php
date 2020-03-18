<?php
namespace lsx\business_directory\classes\integrations;

/**
 * LSX Ninja Forms Integration class
 *
 * @package lsx-business-directory
 */
class LSX_Gravity {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx\business_directory\classes\LSX_Gravity()
	 */
	protected static $instance = null;

	/**
	 * Contructor
	 */
	public function __construct() {
		add_filter( 'gform_custom_merge_tags', array( $this, 'lsx_bd_gravity_forms_register_merge_tag' ), 10, 4 );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\business_directory\classes\LSX_Gravity()    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Register Merge Tags Call Back.
	 *
	 * @link   https://docs.gravityforms.com/gform_custom_merge_tags/
	 *
	 * @return void
	 */
	public function lsx_bd_gravity_forms_register_merge_tag( $merge_tags, $form_id, $fields, $element_id ) {
		$merge_tags[] = array(
			'label' => 'Listing Primary Email',
			'tag'   => '{listing_primary_email}',
		);

		return $merge_tags;
	}
}
