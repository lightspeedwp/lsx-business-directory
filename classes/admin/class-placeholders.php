<?php
namespace lsx\business_directory\classes\admin;

/**
 * Featured Images for the Business Directory Taxonomies.
 *
 * @package lsx-business-directory
 */
class Placeholders {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx\business_directory\classes\admin\Term_Thumbnail()
	 */
	protected static $instance = null;

	/**
	 * Contructor
	 */
	public function __construct() {
		add_action( 'cmb2_init', array( $this, 'register_taxonomy_fields' ), 1 );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\business_directory\classes\admin\Term_Thumbnail()    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Configure Taxonomy Featured Images
	 *
	 * @return void
	 */
	public function register_taxonomy_fields() {
		$cmb    = new_cmb2_box(
			array(
				'id'           => 'lsx_bd_term_details_metabox',
				'title'        => esc_html__( 'Featured Image', 'lsx-business-directory' ),
				'object_types' => array( 'term' ),
				'taxonomies'   => array( 'lsx-bd-industry', 'lsx-bd-region' ),
			)
		);
		$fields = \lsx\business_directory\includes\get_featured_image_field( 'lsx_bd' );
		foreach ( $fields as $field ) {
			$cmb->add_field( $field );
		}
	}
}
