<?php
namespace lsx\business_directory\classes\admin;

/**
 * Featured Images for the Business Directory Taxonomies.
 *
 * @package lsx-business-directory
 */
class Term_Thumbnail {

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
		add_action( 'cmb2_init', array( $this, 'register_industy_icon' ), 1 );
		add_action( 'cmb2_init', array( $this, 'register_industy_icon' ), 1 );
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
	public function register_industy_icon() {
		$cmb    = new_cmb2_box(
			array(
				'id'           => '_term_thumbnail',
				'title'        => esc_html__( 'Industry Icon', 'lsx-business-directory' ),
				'object_types' => array( 'term' ),
				'taxonomies'   => array( 'industry' ),
			)
		);
		$fields = \lsx\business_directory\includes\get_industy_icon_field( 'lsx_bd', true );
		foreach ( $fields as $field ) {
			$cmb->add_field( $field );
		}
	}

	/**
	 * Configure Taxonomy Featured Images
	 *
	 * @return void
	 */
	public function register_term_thumbnail() {
		$cmb    = new_cmb2_box(
			array(
				'id'           => '_term_thumbnail',
				'title'        => esc_html__( 'Featured Image', 'lsx-business-directory' ),
				'object_types' => array( 'term' ),
				'taxonomies'   => array( 'location' ),
			)
		);
		$fields = \lsx\business_directory\includes\get_featured_image_field( 'lsx_bd', true );
		foreach ( $fields as $field ) {
			$cmb->add_field( $field );
		}
	}
}
