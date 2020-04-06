<?php
namespace lsx\business_directory\classes\integrations;

/**
 * LSX Search Integration class
 *
 * @package lsx-business-directory
 */
class LSX_Search {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx\business_directory\classes\LSX_Search()
	 */
	protected static $instance = null;

	/**
	 * This hold the current search prefix.
	 *
	 * @var string
	 */
	public $prefix = '';

	/**
	 * This hold the current layout chosen via the settings.
	 *
	 * @var string
	 */
	public $current_layout = '';

	/**
	 * Contructor
	 */
	public function __construct() {
		// We do BD Search setting only at 'admin_init', because we need is_plugin_active() function present to check for LSX Search plugin.
		add_action( 'lsx_bd_settings_page', array( $this, 'configure_settings_search_engine_fields' ), 15, 1 );
		add_action( 'lsx_bd_settings_section_archive', array( $this, 'configure_settings_search_archive_fields' ), 15, 2 );

		add_action( 'wp', array( $this, 'maybe_enqueue_search_filters' ), 5 );
		add_filter( 'lsx_get_template_part', array( $this, 'search_content_part_filter' ), 10, 1 );
		add_filter( 'lsx_post_wrapper_class', array( $this, 'lsx_post_wrapper_class' ), 10, 1 );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\business_directory\classes\LSX_Search()    A single instance of this class.
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
	 * @return  void
	 */
	public function configure_settings_search_engine_fields( $cmb ) {
		$this->search_fields( $cmb, 'engine' );
	}

	/**
	 * Enable Business Directory Search settings only if LSX Search plugin is enabled.
	 *
	 * @param object $cmb The CMB2() class.
	 * @param string $position either top of bottom.
	 * @return void
	 */
	public function configure_settings_search_archive_fields( $cmb, $position ) {
		if ( 'bottom' === $position ) {
			$this->search_fields( $cmb, 'archive' );
		}
	}

	/**
	 * Enable Business Directory Search settings only if LSX Search plugin is enabled.
	 *
	 * @param object $cmb The CMB2() class.
	 * @param string $section either engine,archive or single.
	 * @return void
	 */
	public function search_fields( $cmb, $section ) {
		if ( is_plugin_active( 'lsx-search/lsx-search.php' ) ) {
			$this->set_facetwp_vars();
			if ( 'engine' === $section ) {
				$cmb->add_field(
					array(
						'id'          => 'settings_' . $section . '_search',
						'type'        => 'title',
						'name'        => esc_html__( 'Search', 'lsx-business-directory' ),
						'default'     => esc_html__( 'Search', 'lsx-business-directory' ),
						'description' => esc_html__( 'Control the filters which show on your WordPress search results page.', 'lsx-business-directory' ),
					)
				);
				do_action( 'lsx_bd_settings_section_engine', $cmb, 'top' );
				$cmb->add_field(
					array(
						'name'        => esc_html__( 'Enable Search Filters', 'lsx-business-directory' ),
						'id'          => $section . '_search_enable',
						'description' => esc_html__( 'Display FacetWP filters on your search results page.', 'lsx-business-directory' ),
						'type'        => 'checkbox',
					)
				);
			} else {
				$cmb->add_field(
					array(
						'name'        => esc_html__( 'Enable Search Filters', 'lsx-business-directory' ),
						'id'          => $section . '_search_enable',
						'description' => esc_html__( 'Display FacetWP filters on your listing archive pages.', 'lsx-business-directory' ),
						'type'        => 'checkbox',
					)
				);
			}

			$cmb->add_field(
				array(
					'name'    => esc_html__( 'Layout', 'lsx-business-directory' ),
					'id'      => $section . '_search_layout',
					'type'    => 'select',
					'options' => array(
						''    => esc_html__( 'Follow the theme layout', 'lsx-business-directory' ),
						'1c'  => esc_html__( '1 column', 'lsx-business-directory' ),
						'2cr' => esc_html__( '2 columns / Content on right', 'lsx-business-directory' ),
						'2cl' => esc_html__( '2 columns / Content on left', 'lsx-business-directory' ),
					),
					'default' => '',
				)
			);

			if ( 'engine' === $section ) {
				$cmb->add_field(
					array(
						'name'             => esc_html__( 'Grid vs List', 'lsx-business-directory' ),
						'id'               => $section . '_grid_list',
						'type'             => 'radio',
						'show_option_none' => false,
						'options'          => array(
							'grid' => esc_html__( 'Grid', 'lsx-business-directory' ),
							'list' => esc_html__( 'List', 'lsx-business-directory' ),
						),
						'default'          => 'list',
					)
				);
				$cmb->add_field(
					array(
						'name'        => esc_html__( 'Display Excerpt', 'lsx-business-directory' ),
						'id'          => $section . '_excerpt_enable',
						'type'        => 'checkbox',
						'description' => __( 'Display the excerpt of a listing.', 'lsx-business-directory' ),
					)
				);
			}

			$cmb->add_field(
				array(
					'name' => esc_html__( 'Collapse', 'lsx-business-directory' ),
					'id'   => $section . '_search_collapse',
					'type' => 'checkbox',
				)
			);

			$cmb->add_field(
				array(
					'name' => esc_html__( 'Disable Sorting', 'lsx-business-directory' ),
					'id'   => $section . '_search_disable_sorting',
					'type' => 'checkbox',
				)
			);

			$cmb->add_field(
				array(
					'name' => esc_html__( 'Disable the Date Option', 'lsx-business-directory' ),
					'id'   => $section . '_search_disable_date',
					'type' => 'checkbox',
				)
			);

			$cmb->add_field(
				array(
					'name' => esc_html__( 'Display Clear Button', 'lsx-business-directory' ),
					'id'   => $section . '_search_clear_button',
					'type' => 'checkbox',
				)
			);

			$cmb->add_field(
				array(
					'name' => esc_html__( 'Display Result Count', 'lsx-business-directory' ),
					'id'   => $section . '_search_result_count',
					'type' => 'checkbox',
				)
			);
			$cmb->add_field(
				array(
					'name'        => esc_html__( 'Facets', 'lsx-business-directory' ),
					'description' => esc_html__( 'Choose your filters above, these will display on the page. Edit your FacetWP Facets to change the display of each of them.', 'lsx-business-directory' ),
					'id'          => $section . '_search_facets',
					'type'        => 'multicheck',
					'options'     => $this->facet_data,
				)
			);
			if ( 'engine' === $section ) {
				do_action( 'lsx_bd_settings_section_engine', $cmb, 'bottom' );
				$cmb->add_field(
					array(
						'id'   => 'settings_search_closing',
						'type' => 'tab_closing',
					)
				);
			}
		}
	}

	/**
	 * Sets the FacetWP variables.
	 *
	 * @return  void
	 */
	public function set_facetwp_vars() {
		if ( function_exists( '\FWP' ) ) {
			$facet_data = \FWP()->helper->get_facets();
		}
		$this->facet_data = array();
		if ( ! empty( $facet_data ) && is_array( $facet_data ) ) {
			foreach ( $facet_data as $facet ) {
				$this->facet_data[ $facet['name'] ] = $facet['label'];
			}
		}
	}

	/**
	 * This function runs on the 'init' action, it checks to see if the search is enabled , and then enqueues the relevant filters.
	 */
	public function maybe_enqueue_search_filters() {
		if ( is_plugin_active( 'lsx-search/lsx-search.php' ) ) {
			add_filter( 'lsx_search_enabled', array( $this, 'lsx_search_enabled' ), 10, 1 );
			add_filter( 'lsx_search_prefix', array( $this, 'lsx_search_prefix' ), 10, 1 );
			add_filter( 'lsx_search_options', array( $this, 'lsx_search_options' ), 10, 1 );
		}
	}

	/**
	 * Enables the search if it is the business directory archive.
	 *
	 * @var boolean $enabled
	 * @return boolean
	 */
	public function lsx_search_enabled( $enabled = false ) {
		if ( is_post_type_archive( 'business-directory' ) || is_tax( array( 'industry', 'location' ) ) || is_search() ) {
			$is_enabled = lsx_bd_get_option( 'archive_search_enable', false );
			if ( 'on' === $is_enabled ) {
				$enabled = true;
			}
		}
		return $enabled;
	}

	/**
	 * Enables the search if it is the business directory archive.
	 *
	 * @var string $enabled
	 * @return string
	 */
	public function lsx_search_prefix( $prefix = '' ) {
		if ( is_post_type_archive( 'business-directory' ) || is_tax( array( 'industry', 'location' ) ) || is_search() ) {
			$prefix = 'archive';
		}
		return $prefix;
	}

	/**
	 * Adds the recipe options to the lsx search options.
	 *
	 * @param array $options
	 * @return array
	 */
	public function lsx_search_options( $options = array() ) {
		if ( is_post_type_archive( 'business-directory' ) || is_tax( array( 'industry', 'location' ) ) || is_search() ) {
			if ( is_search() ) {
				$this->prefix = 'engine';
			} else {
				$this->prefix = 'archive';
			}
			$active_facets    = lsx_bd_get_option( $this->prefix . '_search_facets', array() );
			$facets           = array();
			$current_taxonomy = get_query_var( 'taxonomy' );
			$this->layout     = lsx_bd_get_option( $this->prefix . '_grid_list' );
			if ( ! empty( $active_facets ) ) {
				foreach ( $active_facets as $index => $facet_name ) {
					/*if ( ! ( 'industry' === $current_taxonomy && 'industries' === $facet_name ) &&
						 ! ( 'location' === $current_taxonomy && 'regions' === $facet_name ) ) {*/
						$facets[ $facet_name ] = 'on';
					/*}*/
				}
			}
			$options['display'] = array(
				'search_enable'                => lsx_bd_get_option( 'archive_search_enable', false ),
				'archive_disable_all_sorting'  => lsx_bd_get_option( $this->prefix . '_search_disable_sorting', false ),
				'archive_disable_date_sorting' => lsx_bd_get_option( $this->prefix . '_search_disable_date', false ),
				'archive_layout'               => lsx_bd_get_option( $this->prefix . '_search_layout', '2cr' ),
				'archive_layout_map'           => lsx_bd_get_option( $this->prefix . '_grid_list', 'list' ),
				'archive_display_result_count' => lsx_bd_get_option( $this->prefix . '_search_result_count', 'on' ),
				'enable_collapse'              => lsx_bd_get_option( $this->prefix . '_search_collapse', false ),
				'archive_facets'               => $facets,
				'archive_display_clear_button' => lsx_bd_get_option( $this->prefix . '_search_clear_button', false ),
			);
		}
		return $options;
	}

	/**
	 * Adds the grid post class as needed.
	 *
	 * @param string $class
	 * @return void
	 */
	public function lsx_post_wrapper_class( $class = '' ) {
		if ( is_search() ) {
			if ( false !== $this->layout && '' !== $this->layout && 'grid' === $this->layout ) {
				$class .= 'lsx-grid';
			}
		}
		return $class;
	}

	/**
	 * Redirects the content part to the correct template part
	 *
	 * @param  string $template
	 * @return string
	 */
	public function search_content_part_filter( $template = '' ) {
		if ( is_search() ) {
			if ( false !== $this->layout && '' !== $this->layout && 'grid' === $this->layout ) {
				$template = LSX_BD_PATH . 'templates/single-col-business.php';
			} else {
				$template = LSX_BD_PATH . 'templates/single-row-business.php';
			}
		}
		return $template;
	}
}
