<?php
namespace lsx\business_directory\classes;

/**
 * Contains the recipe post type
 *
 * @package lsx-business-directory
 */
class Custom_Post_Type {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx\business_directory\classes\Custom_Post_Type()
	 */
	protected static $instance = null;

	/**
	 * Holds post_type slug used as an index
	 *
	 * @since 1.0.0
	 *
	 * @var      string
	 */
	public $slug = 'custom_post_type';

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
	 * @return    object \lsx\business_directory\classes\Custom_Post_Type()    A single instance of this class.
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
			'name'               => esc_html__( 'Custom', 'lsx-business-directory' ),
			'singular_name'      => esc_html__( 'Customs', 'lsx-business-directory' ),
			'add_new'            => esc_html_x( 'Add New', 'post type general name', 'lsx-business-directory' ),
			'add_new_item'       => esc_html__( 'Add New', 'lsx-business-directory' ),
			'edit_item'          => esc_html__( 'Edit', 'lsx-business-directory' ),
			'new_item'           => esc_html__( 'New', 'lsx-business-directory' ),
			'all_items'          => esc_html__( 'All', 'lsx-business-directory' ),
			'view_item'          => esc_html__( 'View', 'lsx-business-directory' ),
			'search_items'       => esc_html__( 'Search', 'lsx-business-directory' ),
			'not_found'          => esc_html__( 'None found', 'lsx-business-directory' ),
			'not_found_in_trash' => esc_html__( 'None found in Trash', 'lsx-business-directory' ),
			'parent_item_colon'  => '',
			'menu_name'          => esc_html__( 'Customs', 'lsx-business-directory' ),
		);
		$args   = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'menu_icon'          => 'dashicons-welcome-write-blog',
			'query_var'          => true,
			'rewrite'            => array(
				'slug' => 'custom-post-type',
			),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array(
				'title',
				'editor',
				'thumbnail',
				'excerpt',
			),
		);
		register_post_type( $this->slug, $args );
	}

	/**
	 * Register the Week taxonomy.
	 */
	public function taxonomy_setup() {
		$labels = array(
			'name'              => esc_html_x( 'Term', 'taxonomy general name', 'lsx-business-directory' ),
			'singular_name'     => esc_html_x( 'Term', 'taxonomy singular name', 'lsx-business-directory' ),
			'search_items'      => esc_html__( 'Search', 'lsx-business-directory' ),
			'all_items'         => esc_html__( 'All', 'lsx-business-directory' ),
			'parent_item'       => esc_html__( 'Parent', 'lsx-business-directory' ),
			'parent_item_colon' => esc_html__( 'Parent:', 'lsx-business-directory' ),
			'edit_item'         => esc_html__( 'Edit', 'lsx-business-directory' ),
			'update_item'       => esc_html__( 'Update', 'lsx-business-directory' ),
			'add_new_item'      => esc_html__( 'Add New', 'lsx-business-directory' ),
			'new_item_name'     => esc_html__( 'New Name', 'lsx-business-directory' ),
			'menu_name'         => esc_html__( 'Terms', 'lsx-business-directory' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array(
				'slug' => 'term',
			),
		);

		register_taxonomy( 'term', array( 'custom_post_type' ), $args );
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
