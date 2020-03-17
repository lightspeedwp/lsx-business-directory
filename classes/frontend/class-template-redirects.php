<?php
namespace lsx\business_directory\classes\frontend;

/**
 * This class handles the redirects for the Post Type single, post type archive and taxonomy templates.
 *
 * @package lsx-business-directory
 */
class Template_Redirects {

	/**
	 * Holds class instance
	 *
	 * @var      object \lsx\business_directory\classes\frontend\Template_Redirects()
	 */
	protected static $instance = null;

	/**
	 * Contructor
	 */
	public function __construct() {
		// Handle the template redirects.
		add_filter( 'template_include', array( $this, 'archive_template_include' ), 99 );
		add_filter( 'template_include', array( $this, 'single_template_include' ), 99 );
		add_filter( 'template_include', array( $this, 'taxonomy_template_include' ), 99 );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @return    object \lsx\business_directory\classes\frontend\Template_Redirects()    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Archive template redirect.
	 *
	 * @param  string $template The path to the template to load.
	 * @return string
	 */
	public function archive_template_include( $template ) {
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
	 * Single template redirect.
	 *
	 * @param  string $template The path to the template to load.
	 * @return string
	 */
	public function single_template_include( $template ) {
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
	 * Taxonomy template redirect.
	 *
	 * @param  string $template The path to the template to load.
	 * @return string
	 */
	public function taxonomy_template_include( $template ) {
		$applicable_taxonomies = array( 'industry', 'location' );
		if ( is_main_query() && is_tax( $applicable_taxonomies ) ) {
			if ( '' == locate_template( array( 'taxonomy-business-directory.php' ) ) && file_exists( LSX_BD_PATH . 'templates/taxonomy-business-directory.php' ) ) {
				$template = LSX_BD_PATH . 'templates/taxonomy-business-directory.php';
			}
		}
		return $template;
	}
}
