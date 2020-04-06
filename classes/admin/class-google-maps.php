<?php
namespace lsx\business_directory\classes\admin;

/**
 * THis class holds the translation options
 *
 * @package lsx-business-directory
 */
class Google_Maps {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx\business_directory\classes\admin\Google_Maps()
	 */
	protected static $instance = null;

	/**
	 * Holds the admin for the post type archives.
	 *
	 * @var object \lsx\business_directory\classes\admin\Single();
	 */
	public $single;

	/**
	 * Contructor
	 */
	public function __construct() {
		add_action( 'lsx_bd_settings_page', array( $this, 'register_settings' ), 25, 1 );
		add_filter( 'cmb2_render_google_map', array( $this, 'render_field' ), 10, 5 );
		add_filter( 'cmb2_sanitize_google_map', array( $this, 'sanitize_field' ), 10, 4 );
		add_action( 'init', array( $this, 'init' ), 1000 );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\business_directory\classes\admin\Google_Maps()    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Register the maps fields after the addresses.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'cmb2_init', array( $this, 'register_custom_fields' ), 25 );
	}

	/**
	 * Configure Business Directory custom fields for the Settings page Translations section.
	 *
	 * @param object $cmb new_cmb2_box().
	 * @return void
	 */
	public function register_settings( $cmb ) {
		$cmb->add_field(
			array(
				'id'      => 'settings_google_maps',
				'type'    => 'title',
				'name'    => __( 'Maps', 'lsx-business-directory' ),
				'default' => __( 'Maps', 'lsx-business-directory' ),
			)
		);
		do_action( 'lsx_bd_settings_section_google_maps', $cmb, 'top' );
		$cmb->add_field(
			array(
				'name'    => esc_html__( 'API Key', 'lsx-business-directory' ),
				'id'      => 'google_maps_api_key',
				'type'    => 'text',
				'default' => '',
			)
		);
		$cmb->add_field(
			array(
				'name' => esc_html__( 'Enable Placeholder', 'lsx-business-directory' ),
				'id'   => 'google_maps_enable_placeholder',
				'type' => 'checkbox',
			)
		);
		$cmb->add_field(
			array(
				'name'         => esc_html__( 'Placeholder', 'lsx-business-directory' ),
				'desc'         => esc_html__( 'Your image should be 800px x 800px preferably, but no less than 350px x 350px.', 'lsx-business-directory' ),
				'id'           => 'google_maps_placeholder',
				'type'         => 'file',
				'preview_size' => 'lsx-thumbnail-sqaure',
			)
		);
		do_action( 'lsx_bd_settings_section_google_maps', $cmb, 'bottom' );
		$cmb->add_field(
			array(
				'id'   => 'settings_google_maps_closing',
				'type' => 'tab_closing',
			)
		);
	}
	/**
	 * Render field.
	 */
	public function render_field( $field, $field_escaped_value, $field_object_id, $field_object_type, $field_type_object ) {

		// Get the Google API key from the field's parameters.
		$api_key = $field->args( 'api_key' );

		// Allow a custom hook to specify the key.
		$api_key = lsx_bd_get_option( 'google_maps_api_key', '' );

		$this->setup_admin_scripts( $api_key );

		echo wp_kses_post( '<input type="text" class="large-text google-map-search" id="' . $field->args( 'id' ) . '" />' );

		echo wp_kses_post( '<div class="google-map"></div>' );

		$field_type_object->_desc( true, true );

		echo wp_kses_post(
			$field_type_object->input(
				array(
					'id'    => 'lsx_bd_google_map_latitude',
					'type'  => 'hidden',
					'name'  => $field->args( '_name' ) . '[latitude]',
					'value' => isset( $field_escaped_value['latitude'] ) ? $field_escaped_value['latitude'] : '',
					'class' => 'google-map-latitude',
					'desc'  => '',
				)
			)
		);
		echo wp_kses_post(
			$field_type_object->input(
				array(
					'id'    => 'lsx_bd_google_map_longitude',
					'type'  => 'hidden',
					'name'  => $field->args( '_name' ) . '[longitude]',
					'value' => isset( $field_escaped_value['longitude'] ) ? $field_escaped_value['longitude'] : '',
					'class' => 'google-map-longitude',
					'desc'  => '',
				)
			)
		);
	}

	/**
	 * Optionally save the latitude/longitude values into two custom fields.
	 */
	public function sanitize_field( $override_value, $value, $object_id, $field_args ) {
		if ( isset( $field_args['split_values'] ) && $field_args['split_values'] ) {
			if ( ! empty( $value['latitude'] ) ) {
				update_post_meta( $object_id, $field_args['id'] . '_latitude', $value['latitude'] );
			}

			if ( ! empty( $value['longitude'] ) ) {
				update_post_meta( $object_id, $field_args['id'] . '_longitude', $value['longitude'] );
			}
		}

		return $value;
	}

	/**
	 * Enqueue scripts and styles.
	 */
	public function setup_admin_scripts( $api_key ) {
		if ( defined( 'SCRIPT_DEBUG' ) ) {
			$prefix = 'src/';
			$suffix = '';
		} else {
			$prefix = '';
			$suffix = '.min';
		}
		wp_register_script( 'google-maps-api', 'https://maps.googleapis.com/maps/api/js?key=' . $api_key . '&libraries=places', null, LSX_BD_VER, true );
		wp_enqueue_script( 'lsx-bd-google-maps', LSX_BD_URL . 'assets/js/' . $prefix . 'lsx-bd-admin-maps' . $suffix . '.js', array( 'google-maps-api', 'jquery' ), LSX_BD_VER, true );
		//wp_enqueue_style( 'sx-bd-google-maps', plugins_url( 'css/style.css', __FILE__ ), array(), LSX_BD_VER );
	}

	/**
	 * Registers the Business Directory contact custom fields.
	 *
	 * @return void
	 */
	public function register_custom_fields() {
		$cmb_contact = new_cmb2_box(
			array(
				'id'           => 'lsx_bd_google_map_metabox',
				'title'        => esc_html__( 'Map Details', 'lsx-business-directory' ),
				'object_types' => array( 'business-directory' ),
			)
		);

		$cmb_contact->add_field(
			array(
				'name'         => esc_html__( 'Set your pin', 'lsx-business-directory' ),
				'id'           => 'lsx_bd_map_location',
				'type'         => 'google_map',
				'split_values' => true,
			)
		);
		if ( false !== lsx_bd_get_option( 'google_maps_enable_placeholder', false ) ) {
			$cmb_contact->add_field(
				array(
					'name'         => esc_html__( 'Map Thumbnail', 'lsx-business-directory' ),
					'desc'         => esc_html__( 'Your image should be 800px x 600px preferably, but no less than 360px x 168px', 'lsx-business-directory' ),
					'id'           => 'lsx_bd_map_thumbnail',
					'type'         => 'file',
					'preview_size' => 'lsx-thumbnail-wide',
				)
			);
		}
	}
}
