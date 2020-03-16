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
	 * Holds the template redirect filters.
	 *
	 * @var object \lsx\business_directory\classes\frontend\Template_Redirects();
	 */
	public $template_redirects;

	/**
	 * Holds the frontend banner actions and filters.
	 *
	 * @var object \lsx\business_directory\classes\frontend\Banners();
	 */
	public $banners;

	/**
	 * Enquiry form.
	 *
	 * @var object \lsx\business_directory\classes\frontend\Enquiry();
	 */
	public $enquiry;

	/**
	 * Contructor
	 */
	public function __construct() {
		$this->load_classes();
		add_filter( 'body_class', array( $this, 'body_class' ), 10, 1 );
		add_action( 'wp_enqueue_scripts', array( $this, 'assets' ), 5 );
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
		require_once LSX_BD_PATH . 'classes/frontend/class-template-redirects.php';
		$this->template_redirects = frontend\Template_Redirects::get_instance();

		require_once LSX_BD_PATH . 'classes/frontend/class-banners.php';
		$this->banners = frontend\Banners::get_instance();

		require_once LSX_BD_PATH . 'classes/frontend/class-enquiry.php';
		$this->enquiry = frontend\Enquiry::get_instance();
	}

	/**
	 * Adds a body class to all the business directory pages.
	 *
	 * @param array $classes The current <body> tag classes.
	 * @return array
	 */
	public function body_class( $classes = array() ) {
		if ( is_singular( 'business-directory' ) || is_post_type_archive( 'business-directory' ) || is_tax( array( 'lsx-bd-industry', 'lsx-bd-region' ) ) || is_search() ) {
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
		if ( is_search() ) {
			$title = get_query_var( 's' );
		}
		$title = apply_filters( 'lsx_bd_archive_banner_title', $title );
		return $title;
	}
}
