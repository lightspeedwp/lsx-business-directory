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

			add_action( 'woocommerce_subscription_status_changed', array( $this, 'subscription_status_changed' ), 10, 4 );
			add_filter( 'woocommerce_display_item_meta', array( $this, 'show_listing_link' ), 10, 3 );
			add_filter( 'woocommerce_form_field_radio', array( $this, 'replace_dummy_option' ), 10, 4 );
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

	/**
	 * Handles the status of the listings when the activation changes.
	 *
	 * @param string $subscription_id
	 * @param string $status_from
	 * @param string $status_to
	 * @param object \WC_Subscription() $subscription_obj
	 * @return void
	 */
	public function subscription_status_changed( $subscription_id, $status_from, $status_to, $subscription_obj ) {
		if ( '' !== $status_to ) {
			$listing_ids = get_post_meta( $subscription_id, '_lsx_bd_listing_id', false );
			if ( ! empty( $listing_ids ) ) {

				foreach ( $listing_ids as $listing_id ) {
					switch ( $status_to ) {
						case 'active':
							$post_status = get_post_status( $listing_id );
							break;

						default:
							$post_status = 'draft';
							break;
					}

					wp_update_post(
						array(
							'ID'          => $listing_id,
							'post_status' => $post_status,
						)
					);
				}
			}
		}
	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $html
	 * @param [type] $item
	 * @param array $args
	 * @return void
	 */
	public function show_listing_link( $html, $item, $args = array() ) {
		$strings = array();
		$html    = '';
		$args    = wp_parse_args(
			$args,
			array(
				'before'       => '<ul class="wc-item-meta"><li>',
				'after'        => '</li></ul>',
				'separator'    => '</li><li>',
				'echo'         => true,
				'autop'        => false,
				'label_before' => '<strong class="wc-item-meta-label">',
				'label_after'  => ':</strong> ',
			)
		);
		foreach ( $item->get_formatted_meta_data() as $meta_id => $meta ) {
			if ( __( 'Listing', 'lsx-business-directory' ) === $meta->display_key ) {
				$listing_id = trim( strip_tags( $meta->display_value ) );
				$title      = get_the_title( $listing_id );
				$title      = str_replace( __( 'Listing for #', 'lsx-business-directory' ), __( ' for #', 'lsx-business-directory' ), $title );
				$value      = '<a href="' . get_permalink( $listing_id ) . '">' . $title . '</a>';
			} else {
				$value = $args['autop'] ? wp_kses_post( $meta->display_value ) : wp_kses_post( make_clickable( trim( $meta->display_value ) ) );
			}
			$strings[] = $args['label_before'] . wp_kses_post( $meta->display_key ) . $args['label_after'] . $value;
		}
		if ( $strings ) {
			$html = $args['before'] . implode( $args['separator'], $strings ) . $args['after'];
		}
		return $html;
	}


	/**
	 * Replace the dummy option with a title.
	 *
	 * @param string $field
	 * @param string $key
	 * @param array  $args
	 * @param string $value
	 * @return void
	 */
	public function replace_dummy_option( $field, $key, $args, $value ) {
		$search = 'id="lsx_bd_plan_id_dummy_option"';
		$title  = $search . ' style="display:none" disabled="disabled"';
		$field  = str_replace( $search, $title, $field );

		$search = 'value="dummy_option"';
		$title  = 'value=""';
		$field  = str_replace( $search, $title, $field );

		$search = 'for="lsx_bd_plan_id_dummy_option"';
		$title  = $search . ' style="width:100%;"';
		$field  = str_replace( $search, $title, $field );

		return $field;
	}
}
