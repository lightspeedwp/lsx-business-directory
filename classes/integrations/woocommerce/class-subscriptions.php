<?php
namespace lsx\business_directory\classes\integrations\woocommerce;

/**
 * Handles the integration with WC Subscriptions.
 *
 * @package lsx-business-directory
 */
class Subscriptions {

	/**
	 * Holds class instance
	 *
	 * @var      object \lsx\business_directory\classes\integrations\woocommerce\Subscriptions()
	 */
	protected static $instance = null;

	/**
	 * Contructor
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\business_directory\classes\integrations\woocommerce\Subscriptions()    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	/**
	 * Initiator
	 */
	public function init() {
		if ( function_exists( 'WC' ) && 'on' === lsx_bd_get_option( 'woocommerce_enable_checkout', false ) ) {
			add_action( 'lsx_bd_listing_form_start', array( $this, 'add_product_selection_to_form' ) );
			add_filter( 'woocommerce_form_field_text', array( $this, 'replace_image_field' ), 10, 4 );
			add_filter( 'woocommerce_form_field_text', array( $this, 'replace_image_id_field' ), 10, 4 );
			add_filter( 'woocommerce_product_data_store_cpt_get_products_query', array( $this, 'handle_listing_query_var' ), 10, 2 );
		}
	}
	/**
	 * Redirects to the listing form.
	 *
	 * @param string $url
	 * @param string $adding_to_cart
	 * @return boolean
	 */
	public function add_product_selection_to_form() {
		lsx_business_template( 'woocommerce/listing-form-options' );
	}
	/**
	 * Change the post_thumbnail into a file upload field.
	 *
	 * @param string $field
	 * @param string $key
	 * @param array $args
	 * @param string $value
	 * @return string
	 */
	public function replace_image_field( $field, $key, $args, $value ) {
		if ( in_array( $key, array( 'lsx_bd_thumbnail', 'lsx_bd_banner' ) ) ) {
			$field = '';
		}
		return $field;
	}

	/**
	 * Change the post_thumbnail ID into a hidden field with a thumbnail if set.
	 *
	 * @param string $field
	 * @param string $key
	 * @param array $args
	 * @param string $value
	 * @return string
	 */
	public function replace_image_id_field( $field, $key, $args, $value ) {
		if ( in_array( $key, array( 'lsx_bd__thumbnail_id', 'lsx_bd_banner_id' ) ) ) {
			$field = str_replace( 'woocommerce-input-wrapper', 'woocommerce-file-wrapper', $field );
			$field = str_replace( 'type="text"', 'type="hidden"', $field );

			$image = '';
			if ( ! empty( $value ) && '' !== $value ) {
				$image      .= '<input type="file" class="input-text form-control hidden" name="' . esc_attr( $args['id'] ) . '_upload" id="' . esc_attr( $args['id'] ) . '_upload" placeholder="" value="">';
				$temp_image = wp_get_attachment_image_src( $value, 'lsx-thumbnail-wide' );
				$image_src  = ( strpos( $image[0], 'cover-logo.png' ) === false ) ? $temp_image[0] : '';
				if ( '' !== $image_src ) {
					$image .= '<img src="' . $image_src . '">';
				}
				$image .= '<a class="remove-image" href="#"><i class="fa fa-close"></i> ' . __( 'Remove image', 'lsx-business-directory' ) . '</a>';
			} else {
				$image .= '<input type="file" class="input-text form-control" name="' . esc_attr( $args['id'] ) . '_upload" id="' . esc_attr( $args['id'] ) . '_upload" placeholder="" value="">';
				$image .= '<a class="remove-image hidden" href="#"><i class="fa fa-close"></i> ' . __( 'Remove image', 'lsx-business-directory' ) . '</a>';
			}

			$field = str_replace( '<span class="woocommerce-file-wrapper">', $image . '<span class="woocommerce-file-wrapper">', $field );
		}
		return $field;
	}
	/**
	 * Handle a custom 'customvar' query var to get products with the 'customvar' meta.
	 * @param array $query - Args for WP_Query.
	 * @param array $query_vars - Query vars from WC_Product_Query.
	 * @return array modified $query
	 */
	public function handle_listing_query_var( $query, $query_vars ) {
		if ( ! empty( $query_vars['is_listing'] ) ) {
			$query['meta_query'][] = array(
				'key'   => '_lsx_bd_listing',
				'value' => 'yes',
			);
		}
		return $query;
	}
}
