<?php
namespace lsx\business_directory\classes\admin;

/**
 * Banners Frontend Class
 *
 * @package lsx-business-directory
 */
class Banners {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx\business_directory\classes\admin\Banners()
	 */
	protected static $instance = null;

	/**
	 * Contructor
	 */
	public function __construct() {
		add_action( 'lsx_bd_settings_section_archive', array( $this, 'register_archive_fields' ), 5, 2 );
		add_action( 'lsx_bd_settings_section_engine', array( $this, 'register_search_fields' ), 5, 2 );
		add_action( 'cmb2_init', array( $this, 'register_single_fields' ), 5 );
		add_action( 'cmb2_init', array( $this, 'register_taxonomy_fields' ), 5 );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\business_directory\classes\admin\Banners()    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Enable Business Directory Search settings only if LSX Search plugin is enabled.
	 *
	 * @param object $cmb The CMB2() class.
	 * @param string $position either top of bottom.
	 * @return void
	 */
	public function register_archive_fields( $cmb, $position ) {
		if ( 'top' === $position ) {
			$fields = \lsx\business_directory\includes\get_banner_fields( 'archive', esc_html__( 'Banner', 'lsx-business-directory' ) . ' ' );
			foreach ( $fields as $field ) {
				$cmb->add_field( $field );
			}
		}
	}

	/**
	 * Enable Business Directory Search settings only if LSX Search plugin is enabled.
	 *
	 * @param object $cmb The CMB2() class.
	 * @param string $position either top of bottom.
	 * @return void
	 */
	public function register_search_fields( $cmb, $position ) {
		if ( 'top' === $position ) {
			$fields = \lsx\business_directory\includes\get_banner_fields( 'engine' );
			foreach ( $fields as $field ) {
				$cmb->add_field( $field );
			}
		}
	}

	/**
	 * Configure Business Directory custom fields.
	 *
	 * @return void
	 */
	public function register_single_fields() {
		$cmb_images = new_cmb2_box(
			array(
				'id'           => 'lsx_bd_single_banner_images_metabox',
				'title'        => esc_html__( 'Business Banner', 'lsx-business-directory' ),
				'object_types' => array( 'business-directory' ),
			)
		);
		$fields     = \lsx\business_directory\includes\get_banner_fields( 'lsx_bd' );
		foreach ( $fields as $field ) {
			$cmb_images->add_field( $field );
		}
	}

	/**
	 * Configure Business Directory custom fields.
	 *
	 * @return void
	 */
	public function register_taxonomy_fields() {
		$cmb    = new_cmb2_box(
			array(
				'id'           => 'lsx_bd_term_banner_images_metabox',
				'title'        => esc_html__( 'Banner', 'lsx-business-directory' ),
				'object_types' => array( 'term' ),
				'taxonomies'   => array( 'industry', 'location' ),
			)
		);
		$fields = \lsx\business_directory\includes\get_banner_fields( 'lsx_bd', esc_html__( 'Banner', 'lsx-business-directory' ) . ' ' );
		foreach ( $fields as $field ) {
			$cmb->add_field( $field );
		}
	}
}
