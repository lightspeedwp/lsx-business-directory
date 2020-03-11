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
	 * Contructor
	 */
	public function __construct() {
		// We do BD Search setting only at 'admin_init', because we need is_plugin_active() function present to check for LSX Search plugin.
		add_action( 'lsx_bd_settings_page', array( $this, 'configure_settings_search_engine_fields' ), 15, 1 );
		add_action( 'lsx_bd_settings_section_archive', array( $this, 'configure_settings_search_archive_fields' ), 15, 1 );
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
	 * @return  void
	 */
	public function configure_settings_search_archive_fields( $cmb ) {
		$this->search_fields( $cmb, 'archive' );
	}

	/**
	 * Enable Business Directory Search settings only if LSX Search plugin is enabled.
	 *
	 * @return  void
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
						'description' => esc_html__( 'If you have created an supplemental engine via SearchWP, then you can control the search settings here.', 'lsx-business-directory' ),
					)
				);
			}

			$cmb->add_field(
				array(
					'name' => esc_html__( 'Enable Search', 'lsx-business-directory' ),
					'id'   => $section . '_search_enable',
					'type' => 'checkbox',
				)
			);

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
						'default' => 'list',
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
					'description' => esc_html__( 'These are the filters that will appear on your page.', 'lsx-business-directory' ),
					'id'          => $section . '_search_facets',
					'type'        => 'multicheck',
					'options'     => $this->facet_data,
				)
			);
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
}
