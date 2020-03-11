<?php
namespace lsx\business_directory\classes;

/**
 * LSX Starter Plugin Admin Class.
 *
 * @package lsx-business-directory
 */
class Setup {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx\business_directory\classes\Setup()
	 */
	protected static $instance = null;

	/**
	 * @var object \lsx\business_directory\classes\Post_Type();
	 */
	public $post_types;

	/**
	 * This holds the current facet info.
	 *
	 * @var array
	 */
	public $facet_data = array();

	/**
	 * Contructor
	 */
	public function __construct() {
		// Register custom post types & taxonomies.
		require_once LSX_BD_PATH . 'classes/class-post-type.php';
		$this->post_types = Post_Type::get_instance();

		// We do BD Search setting only at 'admin_init', because we need is_plugin_active() function present to check for LSX Search plugin.
		add_action( 'lsx_bd_settings_page', array( $this, 'configure_settings_search_custom_fields' ), 15, 1 );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\business_directory\classes\Setup()    A single instance of this class.
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
	public function configure_settings_search_custom_fields( $cmb ) {
		if ( is_plugin_active( 'lsx-search/lsx-search.php' ) ) {
			$prefix = 'businessdirectory';
			$this->set_facetwp_vars();

			$cmb->add_field(
				array(
					'id'          => $prefix . '_settings_search',
					'type'        => 'title',
					'name'        => esc_html__( 'Business Directory - Search', 'lsx-business-directory' ),
					'default'     => esc_html__( 'Business Directory - Search', 'lsx-business-directory' ),
					'description' => esc_html__( 'Business Directory search related settings.', 'lsx-business-directory' ),
				)
			);

			$cmb->add_field(
				array(
					'name' => esc_html__( 'Enable Search', 'lsx-business-directory' ),
					'id'   => $prefix . '_business_search_enable',
					'type' => 'checkbox',
				)
			);

			$cmb->add_field(
				array(
					'name'    => esc_html__( 'Layout', 'lsx-business-directory' ),
					'id'      => $prefix . '_business_search_layout',
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

			$cmb->add_field(
				array(
					'name' => esc_html__( 'Collapse', 'lsx-business-directory' ),
					'id'   => $prefix . '_business_search_collapse',
					'type' => 'checkbox',
				)
			);

			$cmb->add_field(
				array(
					'name' => esc_html__( 'Disable Sorting', 'lsx-business-directory' ),
					'id'   => $prefix . '_business_search_disable_sorting',
					'type' => 'checkbox',
				)
			);

			$cmb->add_field(
				array(
					'name' => esc_html__( 'Disable the Date Option', 'lsx-business-directory' ),
					'id'   => $prefix . '_business_search_disable_date',
					'type' => 'checkbox',
				)
			);

			$cmb->add_field(
				array(
					'name' => esc_html__( 'Display Clear Button', 'lsx-business-directory' ),
					'id'   => $prefix . '_business_search_clear_button',
					'type' => 'checkbox',
				)
			);

			$cmb->add_field(
				array(
					'name' => esc_html__( 'Display Result Count', 'lsx-business-directory' ),
					'id'   => $prefix . '_business_search_result_count',
					'type' => 'checkbox',
				)
			);

			$cmb->add_field(
				array(
					'name'        => esc_html__( 'Facets', 'lsx-business-directory' ),
					'description' => esc_html__( 'These are the filters that will appear on your archive page.', 'lsx-business-directory' ),
					'id'          => $prefix . '_business_search_facets',
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
