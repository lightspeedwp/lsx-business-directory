<?php
namespace lsx\business_directory\classes;

/**
 * LSX Starter Plugin Frontend Class.
 *
 * @package lsx-business-directory
 */
class Frontend {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx\business_directory\classes\Frontend()
	 */
	protected static $instance = null;

	/**
	 * Holds the frontend banner actions and filters.
	 *
	 * @var object \lsx\business_directory\classes\frontend\Banners();
	 */
	public $banners;

	/**
	 * Contructor
	 */
	public function __construct() {
		$this->load_classes();
		add_filter( 'body_class', array( $this, 'body_class' ), 10, 1 );
		add_action( 'wp_enqueue_scripts', array( $this, 'assets' ), 5 );

		// Handle the template redirects.
		add_filter( 'template_include', array( $this, 'archive_template_include' ), 99 );
		add_filter( 'template_include', array( $this, 'single_template_include' ), 99 );
		add_filter( 'template_include', array( $this, 'taxonomy_template_include' ), 99 );

		add_filter( 'get_the_archive_title', array( $this, 'get_the_archive_title' ), 100 );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\business_directory\classes\Frontend()    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Loads the variable classes and the static classes.
	 */
	private function load_classes() {
		// Load plugin admin related functionality.
		require_once LSX_BD_PATH . 'classes/frontend/class-banners.php';
		$this->banners = frontend\Banners::get_instance();
	}

	/**
	 * Adds a body class to all the business directory pages.
	 *
	 * @param array $classes The current <body> tag classes.
	 * @return array
	 */
	public function body_class( $classes = array() ) {
		if ( is_post_type_archive( 'business-direcotry' ) || is_tax( array( 'lsx-bd-industry', 'lsx-bd-region' ) ) ) {
			$classes[] = 'lsx-business-directory-page';
		}
		return $classes;
	}

	/**
	 * Registers the plugin frontend assets
	 *
	 * @return void
	 */
	public function assets() {
		wp_enqueue_script( 'lsx-business-directory', LSX_BD_URL . 'assets/js/lsx-business-directory.min.js', array( 'jquery' ), LSX_BD_VER, true );

		/*
		* Adds the Google Maps Javascript Call if a map field was included
		* Variable set to quickly include if script is excluded elsewhere
		* NOTE: placed here from the bottom of single-business-directory.php to fix Travis errors
		*/
		if ( $location && $include_api ) {
			$api_key    = 'api_key';
			$script_url = "https://maps.googleapis.com/maps/api/js?key=$api_key&callback=initMap&libraries=places";
			wp_enqueue_script( 'lsx-business-directory', $script_url, array(), LSX_BD_VER, true );
		}

		$params = apply_filters(
			'lsx_business_directory_js_params',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
			)
		);

		wp_localize_script( 'lsx-business-directory', 'lsx_customizer_params', $params );

		wp_enqueue_style( 'lsx-business-directory', LSX_BD_URL . 'assets/css/lsx-business-directory.css', array(), LSX_BD_VER );
		wp_style_add_data( 'lsx-business-directory', 'rtl', 'replace' );
	}

	/**
	 * Archive template.
	 */
	public function archive_template_include( $template ) {
		// $applicable_post_types = apply_filters( 'lsx_business_directory_archive_template', array() );
		$applicable_post_types = array( 'business-directory' );
		if ( ! empty( $applicable_post_types ) && is_main_query() && is_post_type_archive( $applicable_post_types ) ) {
			$post_type = get_post_type();

			if ( empty( locate_template( array( 'archive-' . $post_type . '.php' ) ) ) && file_exists( LSX_BD_PATH . 'templates/archive-' . $post_type . '.php' ) ) {
				$template = LSX_BD_PATH . 'templates/archive-' . $post_type . '.php';
			}
		}
		return $template;
	}

	/**
	 * Single template.
	 */
	public function single_template_include( $template ) {
		// $applicable_post_types = apply_filters( 'lsx_business_directory_single_template', array() );
		$applicable_post_types = array( 'business-directory' );
		if ( ! empty( $applicable_post_types ) && is_main_query() && is_singular( $applicable_post_types ) ) {
			$post_type = get_post_type();
			if ( empty( locate_template( array( 'single-' . $post_type . '.php' ) ) ) && file_exists( LSX_BD_PATH . 'templates/single-' . $post_type . '.php' ) ) {
				$template = LSX_BD_PATH . 'templates/single-' . $post_type . '.php';
			}
		}
		return $template;
	}

	/**
	 * Redirect WordPress to the taxonomy located in the plugin
	 *
	 * @param     $template string
	 * @return    string
	 */
	public function taxonomy_template_include( $template ) {
		// $applicable_taxonomies = apply_filters( 'lsx_business_directory_taxonomies_template', array() );
		$applicable_taxonomies = array( 'lsx-bd-industry', 'lsx-bd-region' );
		if ( is_main_query() && is_tax( $applicable_taxonomies ) ) {
			if ( '' == locate_template( array( 'taxonomy-business-directory.php' ) ) && file_exists( LSX_BD_PATH . 'templates/taxonomy-business-directory.php' ) ) {
				$template = LSX_BD_PATH . 'templates/taxonomy-business-directory.php';
			}
		}
		return $template;
	}

	/**
	 * Remove the "Archives:" from the post type recipes.
	 *
	 * @param string $title the term title.
	 * @return string
	 */
	public function get_the_archive_title( $title ) {
		if ( is_post_type_archive( 'business-directory' ) ) {
			$title = __( 'Business Directory', 'lsx-health-plan' );
		}
		if ( is_tax( array( 'lsx-bd-industry', 'lsx-bd-region' ) ) ) {
			$queried_object = get_queried_object();
			if ( isset( $queried_object->name ) ) {
				$title = $queried_object->name;
			}
		}
		$title = apply_filters( 'lsx_bd_archive_banner_title', $title );
		return $title;
	}
}
