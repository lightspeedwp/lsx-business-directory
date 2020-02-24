<?php
namespace lsx\business_directory\classes;

/**
 * Contains the recipe post type
 *
 * @package lsx-business-directory
 */
class Business_Directory {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx\business_directory\classes\Business_Directory()
	 */
	protected static $instance = null;

	/**
	 * Holds post_type slug used as an index
	 *
	 * @since 1.0.0
	 *
	 * @var      string
	 */
	public $slug = 'business-directory';

	/**
	 * Contructor
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'init', array( $this, 'taxonomy_setup' ) );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\business_directory\classes\Business_Directory()    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Register the post type.
	 */
	public function register_post_type() {
		$labels = array(
			'name'               => esc_html_x( 'Business Directory', 'post type general name', 'lsx-business-directory' ),
			'singular_name'      => esc_html_x( 'Directory', 'post type general name', 'lsx-business-directory' ),
			'add_new'            => esc_html__( 'Add Business', 'lsx-business-directory' ),
			'add_new_item'       => esc_html__( 'Add New Business', 'lsx-business-directory' ),
			'edit_item'          => esc_html__( 'Edit Business', 'lsx-business-directory' ),
			'new_item'           => esc_html__( 'New Business', 'lsx-business-directory' ),
			'all_items'          => esc_html__( 'All Businesses', 'lsx-business-directory' ),
			'view_item'          => esc_html__( 'View Business', 'lsx-business-directory' ),
			'search_items'       => esc_html__( 'Search Businesses', 'lsx-business-directory' ),
			'not_found'          => esc_html__( 'No businesses defined', 'lsx-business-directory' ),
			'not_found_in_trash' => esc_html__( 'No businesses in the trash', 'lsx-business-directory' ),
			'parent_item_colon'  => '',
			'menu_name'          => esc_html__( 'Business Directory', 'lsx-business-directory' ),
		);

		$supports = array(
			'title',
			'editor',
			'thumbnail',
			'excerpt',
			'custom-fields',
		);

		$details = array(
			'labels'              => $labels,
			'public'              => true,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_icon'           => 'dashicons-list-view',
			'query_var'           => true,
			'rewrite'             => array(
				'slug' => 'business',
			),
			'exclude_from_search' => false,
			'capability_type'     => 'page',
			'has_archive'         => 'business-directory',
			'hierarchical'        => false,
			'menu_position'       => null,
			'supports'            => $supports,
		);

		register_post_type( $this->slug, $details );
	}

	/**
	 * Register the Week taxonomy.
	 */
	public function taxonomy_setup() {
		$labels = array(
			'name'              => esc_html__( 'Industry', 'lsx-business-directory' ),
			'singular_name'     => esc_html__( 'Industry', 'lsx-business-directory' ),
			'search_items'      => esc_html__( 'Search Industries', 'lsx-business-directory' ),
			'all_items'         => esc_html__( 'Industries', 'lsx-business-directory' ),
			'parent_item'       => esc_html__( 'Parent Industry', 'lsx-business-directory' ),
			'parent_item_colon' => esc_html__( 'Parent Industry:', 'lsx-business-directory' ),
			'edit_item'         => esc_html__( 'Edit Industry', 'lsx-business-directory' ),
			'update_item'       => esc_html__( 'Update Industry', 'lsx-business-directory' ),
			'add_new_item'      => esc_html__( 'Add New Industry', 'lsx-business-directory' ),
			'new_item_name'     => esc_html__( 'New Industry', 'lsx-business-directory' ),
			'menu_name'         => esc_html__( 'Industries', 'lsx-business-directory' ),
		);

		$details = array(
			'labels'              => $labels,
			'hierarchical'        => true,
			'show_ui'             => true,
			'public'              => true,
			'exclude_from_search' => true,
			'show_admin_column'   => true,
			'query_var'           => true,
			'rewrite'             => array( 'industry' ),
		);

		register_taxonomy( 'lsx-bd-industry', array( $this->slug ), $details );

		$labels = array(
			'name'              => esc_html__( 'Region', 'lsx-business-directory' ),
			'singular_name'     => esc_html__( 'Region', 'lsx-business-directory' ),
			'search_items'      => esc_html__( 'Search Regions', 'lsx-business-directory' ),
			'all_items'         => esc_html__( 'Regions', 'lsx-business-directory' ),
			'parent_item'       => esc_html__( 'Parent Region', 'lsx-business-directory' ),
			'parent_item_colon' => esc_html__( 'Parent Region:', 'lsx-business-directory' ),
			'edit_item'         => esc_html__( 'Edit Region', 'lsx-business-directory' ),
			'update_item'       => esc_html__( 'Update Region', 'lsx-business-directory' ),
			'add_new_item'      => esc_html__( 'Add New Region', 'lsx-business-directory' ),
			'new_item_name'     => esc_html__( 'New Region', 'lsx-business-directory' ),
			'menu_name'         => esc_html__( 'Regions', 'lsx-business-directory' ),
		);

		$details = array(
			'labels'              => $labels,
			'hierarchical'        => true,
			'show_ui'             => true,
			'public'              => true,
			'exclude_from_search' => true,
			'show_admin_column'   => true,
			'query_var'           => true,
			'rewrite'             => array( 'region' ),
		);

		register_taxonomy( 'lsx-bd-region', array( $this->slug ), $details );
	}
	/**
	 * Adds the post type to the different arrays.
	 *
	 * @param array $post_types
	 * @return array
	 */
	public function enable_post_type( $post_types = array() ) {
		$post_types[] = $this->slug;
		return $post_types;
	}
}
