<?php
/**
 * @package   LSX_Business_Directory
 * @author    LightSpeed
 * @license   GPL-2.0+
 * @link      
 * @copyright 2015 LightSpeed
 */

/**
 * Main plugin class.
 *
 * @package LSX_Business_Directory
 * @author  LightSpeed
 */
class LSX_Business_Directory extends Lsx {

	/**
	 * The slug for this plugin
	 *
	 * @since 1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'lsx-business-directory';

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object|Lsx_Landing_Pages
	 */
	protected static $instance = null;

	/**
	 * Holds the option screen prefix
	 *
	 * @since 1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	
	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		// activate property post type
		add_action( 'init', array( $this, 'register_post_types' ) );

		// Redirect Single Template
		add_filter( 'template_include', array( $this, 'post_type_single_template_include'), 99 );
		
		// Load front style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}


	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object|Lsx_Landing_Pages    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;

	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since 1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain( $this->plugin_slug, false, basename( LSX_BUSINESS_DIRECTORY_PATH ) . '/languages');

	}
	
	/**
	 * Register and enqueue front-specific style sheet.
	 *
	 * @since 1.0.0
	 *
	 * @return    null
	 */
	public function enqueue_scripts() {
		
		wp_enqueue_style('lsx_landing_pages_style', LSX_BUSINESS_DIRECTORY_URL.'/assets/css/style.css');
	}
	
	
	/**
	 * Register the landing pages post type.
	 *
	 *
	 * @return    null
	 */
	public function register_post_types() {
	
		// define the properties post type
		$args = array(
				'labels' 				=> array(
						'name' 				=> __('Business Directory', 'lsx-business-directory'),
						'singular_name' 	=> __('Business Directory', 'lsx-business-directory'),
						'add_new' 			=> __('Add Business', 'lsx-business-directory'),
						'add_new_item' 		=> __('Add New Business', 'lsx-business-directory'),
						'edit_item' 		=> __('Edit Business', 'lsx-business-directory'),
						'all_items' 		=> __('All Businesses', 'lsx-business-directory'),
						'view_item' 		=> __('View Directory', 'lsx-business-directory'),
						'search_items' 		=> __('Search Directory', 'lsx-business-directory'),
						'not_found' 		=> __('No businesses defined', 'lsx-business-directory'),
						'not_found_in_trash'=> __('No businesses in the trash', 'lsx-business-directory'),
						'parent_item_colon' => '',
						'menu_name' 		=> __('Business Directory', 'lsx-business-directory')
				),
				'public' 				=>	true,
				'publicly_queryable'	=>	true,
				'show_ui' 				=>	true,
				'show_in_menu' 			=>	true,
				'query_var' 			=>	true,
				'rewrite' 				=>	array( 'slug' => 'business' ),
				'exclude_from_search' 	=>	false,
				'capability_type' 		=>	'page',
				'has_archive' 			=>	'business-directory',
				'hierarchical' 			=>	false,
				'menu_position' 		=>	null,
				'menu_icon'				=>	"dashicons-list-view",
				'supports' 				=> array(
											'title',
											'editor',
											'thumbnail',
											'excerpt',
											'custom-fields'
											),
		);
	
		// register post type
		lsx_register_post_type('business-directory', $args);
	}

	/**
	 * Redirect wordpress to the single template located in the plugin
	 *
	 * @param	$template
	 *
	 * @return	$template
	 */
	public function post_type_single_template_include( $template ) {
	
		if ( is_main_query()
		&& is_singular('directory')
		&& '' == locate_template( array( 'single-directory.php' ) )
		&& file_exists( LSX_BUSINESS_DIRECTORY_PATH.'templates/' . "single-directory.php" )) {
			$template = LSX_BUSINESS_DIRECTORY_PATH.'templates/' . "single-directory.php";
		}
		return $template;
	}	


	/**
	 * Bind meta data objects
	 *
	 * @since 0.0.1
	 *
	 * @return array landing-page post objects
	 */
	public function build_metaplate_data( $data, $metaplate ){
		global $post;

		if( $post->post_type !== 'business-directory' ){
			return $data;
		}
		
		self::remove_jetpack_sharing();

		// add content
		ob_start();
		the_content();
		$data['post_content'] = ob_get_clean();
		//add exceprt
		$data['excerpt'] = str_replace('[&hellip;]', '', get_the_excerpt() );

		// permalink
		$data['permalink'] = get_the_permalink();

		// add taxonomies in with a taxonomy. alias
		$taxonomies = get_object_taxonomies( $post );
		if( !empty( $taxonomies ) ){
			foreach ( $taxonomies as $taxonomy_name  ) {

				$taxonomy = get_taxonomy( $taxonomy_name );
				$data['taxonomy'][ $taxonomy_name ] = $data[ $taxonomy_name ] = wp_get_post_terms( $post->ID, $taxonomy_name, array("fields" => "all") );

			}
		}
		if( !empty( get_the_post_thumbnail() ) ){
			$data['post_thumbnail'] = lsx_get_thumbnail( 'full' );	
		}
		
		$classes = get_post_class();
		$data['post_class'] = implode( ' ', $classes );

		
		$data['global']['site_url'] = site_url();

		//var_dump( $data );
		return $data;
	}
}