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
		add_action( 'init', array( $this, 'register_taxonomies') );

		// Redirect Templates
		add_filter( 'template_include', array( $this, 'post_type_single_template_include'), 99 );
		add_filter( 'template_include', array( $this, 'post_type_archive_template_include'), 99 );
		
		// Load front style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		
		
		add_image_size( 'lsx-business-logo', 350, 350, true );
		
		//Populate the data for the meta plate
		add_filter( 'metaplate_data', array( $this, 'build_metaplate_data' ), 11, 2 );
		
		//Set the single to 1 column
		add_filter( 'lsx_bootstrap_column_size', array( $this, 'single_layout_filter' )  );

		// Add Business Directory Settings Menu
		add_action( 'admin_menu', array( $this, 'add_business_directory_settings' ) );

		// Add Directory Settings Menu Fields
		add_action( 'admin_init', array( $this, 'init_business_directory_settings' ) );

		// Checks if a Caldera Form is being submitted and applies filter
		// Deactivated after internal request. Keeping it here as it could be useful for future use
		// add_action( 'init', array( $this, 'caldera_form_submission_conditional' ) );		
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
		
		//wp_enqueue_style('lsx_landing_pages_style', LSX_BUSINESS_DIRECTORY_URL.'/assets/css/style.css');
		wp_enqueue_script('lsx_business_directory_script', LSX_BUSINESS_DIRECTORY_URL . 'assets/js/lsx-business-directory.js', array('jquery'), null, false);

		wp_enqueue_style( 'lsx_business_directory_style', LSX_BUSINESS_DIRECTORY_URL . 'assets/css/frontend.css' );
		
		//Set some parameters that we can use in the JS
		/*$is_portfolio = false;
		$param_array = array(
				'is_portfolio' => $is_portfolio
		);
		//Set the columns for the archives
		$param_array['columns'] = apply_filters('lsx_archive_column_number',3);
		wp_localize_script( 'lsx_script', 'lsx_params', $param_array );		*/
	}
	
	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since 1.0.0
	 *
	 * @return    null
	 */
	public function admin_enqueue_scripts() {
		wp_enqueue_style('lsx_business_directory_admin_css', LSX_BUSINESS_DIRECTORY_URL . 'assets/css/admin-style.css');
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
						'name' 				=> LSX_BUSINESS_DIRECTORY_LABEL,
						'singular_name' 	=> __('Directory', 'lsx-business-directory'),
						'add_new' 			=> __('Add Business', 'lsx-business-directory'),
						'add_new_item' 		=> __('Add New Business', 'lsx-business-directory'),
						'edit_item' 		=> __('Edit Business', 'lsx-business-directory'),
						'all_items' 		=> __('All Businesses', 'lsx-business-directory'),
						'view_item' 		=> __('View', 'lsx-business-directory'),
						'search_items' 		=> __('Search Businesses', 'lsx-business-directory'),
						'not_found' 		=> __('No businesses defined', 'lsx-business-directory'),
						'not_found_in_trash'=> __('No businesses in the trash', 'lsx-business-directory'),
						'parent_item_colon' => '',
						'menu_name' 		=> LSX_BUSINESS_DIRECTORY_LABEL
				),
				'public' 				=>	true,
				'publicly_queryable'	=>	true,
				'show_ui' 				=>	true,
				'show_in_menu' 			=>	true,
				'query_var' 			=>	true,
				'rewrite' 				=>	array( 'slug' => 'business' ),
				'exclude_from_search' 	=>	false,
				'capability_type' 		=>	'page',
				'has_archive' 			=>	LSX_BUSINESS_DIRECTORY_ARCHIVE_SLUG,
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
	 * Register the global post types.
	 *
	 *
	 * @return    null
	 */
	public function register_taxonomies() {
			
		$labels = array(
				'name' => _x( 'Industry', 'lsx-tour-operators' ),
				'singular_name' => _x( 'Industry', 'lsx-tour-operators' ),
				'search_items' =>  __( 'Search Industries' , 'lsx-tour-operators' ),
				'all_items' => __( 'Industries' , 'lsx-tour-operators' ),
				'parent_item' => __( 'Parent Industry' , 'lsx-tour-operators' ),
				'parent_item_colon' => __( 'Parent Industry:' , 'lsx-tour-operators' ),
				'edit_item' => __( 'Edit Industry' , 'lsx-tour-operators' ),
				'update_item' => __( 'Update Industry' , 'lsx-tour-operators' ),
				'add_new_item' => __( 'Add New Industry' , 'lsx-tour-operators' ),
				'new_item_name' => __( 'New Industry' , 'lsx-tour-operators' ),
				'menu_name' => __( 'Industry' , 'lsx-tour-operators' ),
		);
	
		// Now register the taxonomy
		register_taxonomy('industry',array('business-directory'), array(
				'hierarchical' => true,
				'labels' => $labels,
				'show_ui' => true,
				'public' => true,
				'exclude_from_search' => true,
				'show_admin_column' => true,
				'query_var' => true,
				'rewrite' => array('industry'),
		));
	
		$labels = array(
				'name' => _x( 'Region', 'lsx-tour-operators' ),
				'singular_name' => _x( 'Region', 'lsx-tour-operators' ),
				'search_items' =>  __( 'Search Regions' , 'lsx-tour-operators' ),
				'all_items' => __( 'Regions' , 'lsx-tour-operators' ),
				'parent_item' => __( 'Parent Region' , 'lsx-tour-operators' ),
				'parent_item_colon' => __( 'Parent Region:' , 'lsx-tour-operators' ),
				'edit_item' => __( 'Edit Region' , 'lsx-tour-operators' ),
				'update_item' => __( 'Update Region' , 'lsx-tour-operators' ),
				'add_new_item' => __( 'Add New Region' , 'lsx-tour-operators' ),
				'new_item_name' => __( 'New Region' , 'lsx-tour-operators' ),
				'menu_name' => __( 'Regions' , 'lsx-tour-operators' ),
		);
	
	
		// Now register the taxonomy
		register_taxonomy('region',array('business-directory'), array(
				'hierarchical' => true,
				'labels' => $labels,
				'show_ui' => true,
				'public' => true,
				'exclude_from_search' => true,
				'show_admin_column' => true,
				'query_var' => true,
				'rewrite' => array('region'),
		));
	
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
		&& is_singular('business-directory')
		&& '' == locate_template( array( 'single-business-directory.php' ) )
		&& file_exists( LSX_BUSINESS_DIRECTORY_PATH.'templates/' . "single-business-directory.php" )) {
			$template = LSX_BUSINESS_DIRECTORY_PATH.'templates/' . "single-business-directory.php";
		}
		return $template;
	}	
	
	/**
	 * Redirect wordpress to the archive template located in the plugin
	 *
	 * @param	$template
	 *
	 * @return	$template
	 */
	public function post_type_archive_template_include( $template ) {
			
		if ( is_main_query()
		 && ( is_post_type_archive('business-directory') ) 
		 && '' == locate_template( array( 'archive-business-directory.php' ) )
		 && file_exists( LSX_BUSINESS_DIRECTORY_PATH.'/templates/' . "archive-business-directory.php" )) {
			$template = LSX_BUSINESS_DIRECTORY_PATH.'/templates/' . "archive-business-directory.php";
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
		global $post,$lsx_maps;
		
		if( $post->post_type !== 'business-directory' ){
			return $data;
		}

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
		if( !empty( get_the_post_thumbnail($post->ID) ) ){
			$data['post_thumbnail'] = get_the_post_thumbnail( $post->ID,'lsx-business-logo' );	
		}
		
		$classes = get_post_class();
		$data['post_class'] = implode( ' ', $classes );

		if($data['location']){
			$data['map'] = $lsx_maps->map_output($data['location']);
		}		
		$data['global']['site_url'] = site_url();
		
		$data = apply_filters('lsx-business-directory-metaplate-data',$data);
		return $data;
	}
	
	/**
	 * Set the single business directory to 1 column
	 *
	 * @since 0.0.1
	 *
	 * @param	$default_size	string  1c, 2cr, 2cl
	 * @return	$default_size	string
	 */
	public function single_layout_filter( $default_size ){
		if(is_main_query() && is_singular('business-directory')){
			$default_size = '1c';
		}
		return $default_size;
	}

	/**
	 * Add Settings Menu to Business Directory Admin Section
	 *
	 * @since 2.1
	 *
	 */
	public function add_business_directory_settings() {
		add_submenu_page( 'edit.php?post_type=business-directory', 'Settings', 'Settings', 'manage_options', 'business-directory-settings', array( $this, 'display_business_directory_settings' ) );
	}

	/**
	 * Register Settings for Settings Menu under Business Directory Admin Section
	 *
	 * @since 2.1
	 *
	 */
	public function init_business_directory_settings() {
	    register_setting( 'business-directory-settings-group', 'lsx-business-directory-generic-form' );
	    add_settings_section( 'business-directory-settings-section-one', 'Business Directory Settings', array( $this, 'business_directory_settings_section'), 'lsx-business-directory-form' );
	    add_settings_field( 'lsx-caldera-form', 'Generic Caldera Form', array( $this, 'business_directory_generic_form_caldera_field') , 'lsx-business-directory-form', 'business-directory-settings-section-one' );
	}

	/**
	 * Renders First Section for Settings Menu under Business Directory Admin Section
	 *
	 * @since 2.1
	 *
	 */
	public function business_directory_settings_section() {
		// do nothing
	}

	/**
	 * Renders Caldera form for Settings Menu under Business Directory Admin Section
	 *
	 * @since 2.1
	 *
	 */
	public function business_directory_generic_form_caldera_field() {
	    $setting = get_option( 'lsx-business-directory-generic-form' );

	    if ( class_exists( 'Caldera_Forms') ) {

		    $forms = Caldera_Forms::get_forms( true );
		    if ( !empty( $forms ) ) {
		    	echo '<select name="lsx-business-directory-generic-form">';		    	
		    	foreach( $forms as $form ) {
		    		$state = '';
		    		if ( $setting == $form['ID'] ) $state = 'selected';		    		
		    		echo '<option value="' . $form["ID"] . '" ' . $state . '>' . $form['name'] . '</option>';
		    	}
		    	echo '</select>';
		    }
		}
	}

	/**
	 * Add Settings Menu under Business Directory Admin Section
	 *
	 * @since 2.1
	 *
	 */
	public function display_business_directory_settings() { ?>
		<div class="wrap">
	        <form action="options.php" method="POST">
	            <?php settings_fields( 'business-directory-settings-group' ); ?>
	            <?php do_settings_sections( 'lsx-business-directory-form' ); ?>
	            <?php submit_button(); ?>
	        </form>
	    </div>
	<?php }

	/**
	 * Check $_POST variable for Caldera Form submission and apply pre-send form filter
	 *
	 * @since 2.1
	 *
	 */
	function caldera_form_submission_conditional() {
		$submitted_form = $_POST;
		if ( isset( $submitted_form ) ) {
			if( isset( $submitted_form['_cf_frm_id'] ) ) {
				add_filter( 'caldera_forms_get_form-' . $submitted_form['_cf_frm_id'], array( $this, 'business_recipient_filter' ), 1, 2 );
			}
		}
	}

	/**
	 * Check $_POST variable for Caldera Form submission and apply pre-send form filter
	 *
	 * @since 2.1
	 * @param	$form			array  	Array with form settings
	 * @param 	$id_name		string 	Caldera Form Slug
	 * @return	$form 			array 	Array with form settings
	 */
	function business_recipient_filter( $form, $id_name ) {
		// Check if this is the form selected in the Business Directory settings page
		if ( get_option( 'lsx-business-directory-generic-form' ) == $id_name ) {
			$submitted_form = $_POST;
			if ( isset( $submitted_form['_wp_http_referer'] ) && $referrer = $submitted_form['_wp_http_referer'] ) {
				$referrer_array = explode( '/', substr( $referrer, 1, strlen( $referrer ) - 1 ) );
				if ( !empty( $referrer_array ) ) {
					// Are we on a single business directory?
					if ( $referrer_array[0] == 'business' ) {
						$business = get_page_by_path( $referrer_array[1], OBJECT, 'business-directory' );
						if ( !empty( $business ) ) {
							$general_tab_fields = get_post_meta( $business->ID, 'general', true );
							if ( isset( $general_tab_fields['primary_email'] ) && $primary_email = $general_tab_fields['primary_email'] ) {
								$form['mailer']['recipients'] = $primary_email;
							}
						}
					}
				}
			}
		}
		return $form;
	}
}