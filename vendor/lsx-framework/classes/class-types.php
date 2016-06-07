<?php
/**
 * LSX Types
 *
 * @package   Lsx post_type
 * @author     LightSpeed Team
 * @license   GPL-2.0+
 * @link      
 * @copyright 2015  LightSpeed Team
 */

/**
 * Plugin class.
 * @package Lsx_post_type
 * @author   LightSpeed Team
 */
class Lsx_post_types {

	/**
	 * The slug for this plugin
	 *
	 * @since 0.0.1
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'lsx';

	/**
	 * List of post_types for registered
	 *
	 * @since 0.0.1
	 *
	 * @var      array
	 */
	protected $post_types = array();

	/**
	 * List of taxonomies for registering
	 *
	 * @since 0.0.1
	 *
	 * @var      array
	 */
	protected $taxonomies = array();

	/**
	 * Holds class isntance
	 *
	 * @since 0.0.1
	 *
	 * @var      object|Lsx
	 */
	protected static $instance = null;

	/**
	 * Holds the option screen prefix
	 *
	 * @since 0.0.1
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since 0.0.1
	 *
	 * @access private
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// register post types with WordPress
		add_action( 'init', array( $this, 'register_types' ), 50 );

	}
	

	/**
	 * Registeres a post_type
	 *
	 * @since 0.0.1
	 *
	 */
	public function register_post_type( $type, $args ) {		

		$this->post_types[ $type ] = $args;
	}

	/**
	 * Registeres a post_type
	 *
	 * @since 0.0.1
	 *
	 */
	public function register_taxonomy( $taxonomy, $post_type, $args ) {		


		// check if taxonomy exists first
		if( !empty( $this->taxonomies[ $taxonomy ] ) ){
			$this->taxonomies[ $taxonomy ]['post_types'] = array_merge( $this->taxonomies[ $taxonomy ]['post_types'], (array) $post_type );
		}else{
			$this->taxonomies[ $taxonomy ] = array(
				'post_types'	=>	(array) $post_type,
				'args'			=>	$args
			);
		}		
	}

	/**
	 * Registeres all types with WordPress
	 *
	 * @since 0.0.1
	 *
	 */
	public function register_types( ) {

		$reglist = array();
		//go over list and register each type with wordpress
		foreach( $this->post_types as $type => $args ){
			register_post_type( $type, $args );
			$reglist[] = sha1( $type );
		}

		//go over list and register each taxonomy with wordpress
		foreach( $this->taxonomies as $taxonomy => $args ){
			register_taxonomy( $taxonomy, $args['post_types'], $args['args'] );
			$reglist[] = sha1( $taxonomy );
		}

		// make registery into a string key
		$regkey = sha1( json_encode( $reglist ) );
		// check internal registry for a change
		$registry = get_option( '_lsx_types_reg' );
		if ( $registry !== $regkey ) {
			flush_rewrite_rules( true );
			update_option( '_lsx_types_reg', $regkey );
		}

	}


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since 0.0.1
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain( $this->plugin_slug, FALSE, basename( LSX_FRAMEWORK_PATH ) . '/languages');

	}


	/**
	 * Return an instance of this class.
	 *
	 * @since 0.0.1
	 *
	 * @return    object|Lsx    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}


}
