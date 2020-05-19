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
	 * Holds the widget class.
	 *
	 * @var object \lsx\business_directory\classes\frontend\Widget();
	 */
	public $widget;

	/**
	 * Holds the placeholders class.
	 *
	 * @var object \lsx\business_directory\classes\frontend\Placeholders();
	 */
	public $placeholders;

	/**
	 * Holds the google maps class.
	 *
	 * @var object \lsx\business_directory\classes\frontend\Google_Maps();
	 */
	public $google_maps;

	/**
	 * Contructor
	 */
	public function __construct() {
		$this->load_classes();
		add_filter( 'body_class', array( $this, 'body_class' ), 200, 1 );
		add_filter( 'post_class', array( $this, 'post_class' ), 200, 1 );
		add_action( 'wp_enqueue_scripts', array( $this, 'assets' ), 5 );
		add_filter( 'get_the_archive_title', array( $this, 'get_the_archive_title' ), 100 );
		add_filter( 'wp_kses_allowed_html', array( $this, 'wp_kses_allowed_html' ), 10, 2 );
		add_action( 'lsx_content_wrap_before', array( $this, 'industries_shortcode' ) );
		add_filter( 'get_the_excerpt', array( $this, 'crop_excerpt' ), 1, 15 );
		add_action( 'lsx_content_bottom', array( $this, 'add_related_listings_to_single' ) );
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

		require_once LSX_BD_PATH . 'classes/frontend/class-widget.php';
		$this->widget = frontend\Widget::get_instance();

		require_once LSX_BD_PATH . 'classes/frontend/class-placeholders.php';
		$this->placeholders = frontend\Placeholders::get_instance();

		//require_once LSX_BD_PATH . 'classes/frontend/class-google-maps.php';
		//$this->google_maps = frontend\Google_Maps::get_instance();
	}

	/**
	 * Adds a body class to all the business directory pages.
	 *
	 * @param array $classes The current <body> tag classes.
	 * @return array
	 */
	public function body_class( $classes = array() ) {
		if ( is_singular( 'business-directory' ) || is_post_type_archive( 'business-directory' ) || is_tax( array( 'industry', 'location' ) ) || is_search() || ( lsx_bd_is_preview() ) ) {
			$classes[] = 'lsx-business-directory-page';

			if ( is_singular( 'business-directory' ) || ( lsx_bd_is_preview() ) ) {
				$classes[] = 'lsx-body-full-width';
				if ( function_exists( 'has_blocks' ) && has_blocks( get_the_ID() ) && ( ! is_search() ) && ( ! is_archive() ) ) {
					$key = array_search( 'using-gutenberg', $classes );
					if ( false !== $key ) {
						unset( $classes[ $key ] );
					}
				}
				if ( lsx_bd_is_preview() ) {
					$classes[] = 'single';
					$classes[] = 'single-business-directory';
					$key       = array_search( 'woocommerce-page', $classes );
					if ( false !== $key ) {
						unset( $classes[ $key ] );
					}
				}
			} else {
				$classes[] = 'lsx-body-full-width';
				$prefix    = 'archive';
				if ( is_search() ) {
					$prefix = 'engine';
				}
				$layout = lsx_bd_get_option( $prefix . '_grid_list' );
				if ( false !== $layout && '' !== $layout && 'grid' === $layout ) {
					$classes[] = 'lsx-body-grid-layout';
				} else {
					$classes[] = 'lsx-body-list-layout';
				}
			}
		}
		return $classes;
	}

	/**
	 * Adds a body class to all the business directory pages.
	 *
	 * @param array $classes The current <body> tag classes.
	 * @return array
	 */
	public function post_class( $classes = array() ) {
		if ( lsx_bd_is_preview() ) {
			$classes[] = 'business-directory';
			$classes[] = 'type-business-directory';
			$classes[] = 'has-post-thumbnail';
		}
		return $classes;
	}

	/**
	 * Registers the plugin frontend assets
	 *
	 * @return void
	 */
	public function assets() {
		if ( defined( 'SCRIPT_DEBUG' ) ) {
			$prefix = 'src/';
			$suffix = '';
			$debug  = true;
		} else {
			$prefix = '';
			$suffix = '.min';
			$debug  = false;
		}
		wp_enqueue_script( 'lsx-bd-frontend', LSX_BD_URL . 'assets/js/' . $prefix . 'lsx-bd-frontend' . $suffix . '.js', array( 'jquery' ), LSX_BD_VER, true );
		$param_array = array(
			'debug' => $debug,
		);
		wp_localize_script( 'lsx-bd-frontend', 'lsx_bd_params', $param_array );

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
		if ( is_tax( array( 'industry', 'location' ) ) ) {
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
	/**
	 * Add our data paramters to the list of allowed ones by WP.
	 *
	 * @param array $allowedtags
	 * @param string $context
	 * @return array
	 */
	public function wp_kses_allowed_html( $allowedtags, $context ) {
		$allowedtags['div']['data-lsx-slick'] = true;
		$allowedtags['div']['data-slick']     = true;
		$allowedtags['a']['onmouseover']      = true;
		return $allowedtags;
	}

	/**
	 * Adds Industries shortcode to the Listing archive
	 *
	 * @param [type] $title
	 * @return void
	 */
	public function industries_shortcode() {
		if ( ( is_post_type_archive( 'business-directory' ) || is_tax( 'industry' ) ) && 'on' === lsx_bd_get_option( 'archive_industry_buttons', false ) ) {
			$industries_shortcode = '[lsx_bd_industries_nav title_text=""]';
			echo do_shortcode( $industries_shortcode );
		}
	}

	/**
	 * Crop huge excerpts on archive and widget items.
	 */
	public function crop_excerpt( $wpse_excerpt ) {
		if ( empty( $wpse_excerpt ) ) {
			$wpse_excerpt = get_the_content( '' );
		}

		if ( ! empty( $wpse_excerpt ) ) {
			$wpse_excerpt = strip_shortcodes( $wpse_excerpt );
			$wpse_excerpt = apply_filters( 'the_content', $wpse_excerpt );
			$wpse_excerpt = str_replace( ']]>', ']]>', $wpse_excerpt );
			$wpse_excerpt = strip_tags( $wpse_excerpt, apply_filters( 'excerpt_strip_tags', '<h1>,<h2>,<h3>,<h4>,<h5>,<h6>,<a>,<button>,<blockquote>,<p>,<br>,<b>,<strong>,<i>,<u>,<ul>,<ol>,<li>,<span>,<div>' ) );

			$excerpt_word_count = 25;
			$excerpt_word_count = apply_filters( 'excerpt_length', $excerpt_word_count );

			$tokens         = array();
			$excerpt_output = '';
			$has_more       = false;
			$count          = 0;

			preg_match_all( '/(<[^>]+>|[^<>\s]+)\s*/u', $wpse_excerpt, $tokens );

			foreach ( $tokens[0] as $token ) {
				if ( $count >= $excerpt_word_count ) {
					$excerpt_output .= trim( $token );
					$has_more = true;
					break;
				}

				$count++;
				$excerpt_output .= $token;
			}

			$wpse_excerpt = trim( force_balance_tags( $excerpt_output ) );

			if ( $has_more ) {
				$excerpt_end = '<a class="moretag" href="' . esc_url( get_permalink() ) . '">' . esc_html__( 'More', 'lsx' ) . '</a>';
				$excerpt_end = apply_filters( 'excerpt_more', ' ' . $excerpt_end );

				$pos = strrpos( $wpse_excerpt, '</' );

				if ( false !== $pos ) {
					// Inside last HTML tag
					$wpse_excerpt = substr_replace( $wpse_excerpt, $excerpt_end, $pos, 0 ); /* Add read more next to last word */
				} else {
					// After the content
					$wpse_excerpt .= $excerpt_end; /*Add read more in new paragraph */
				}
			}
		}
		return $wpse_excerpt;
	}

	/**
	 * Adds the related listing shortcode to the bottom of the single listing.
	 *
	 * @return void
	 */
	public function add_related_listings_to_single() {
		if ( is_singular( 'business-directory' ) ) {
			$should_add = apply_filters( 'lsx_bd_enable_related_listings', lsx_bd_get_option( 'single_enable_related_listings', true ) );

			// We disable the related listings on the single preview.
			if ( lsx_bd_is_preview() ) {
				$should_add = false;
			}
			if ( true === $should_add || 1 === $should_add || '1' === $should_add ) {
				lsx_bd_related_listings();
			}
		}
	}
}
