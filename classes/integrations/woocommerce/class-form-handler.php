<?php
namespace lsx\business_directory\classes\integrations\woocommerce;

/**
 * Add / Edit listing form handler
 *
 * @package lsx-business-directory
 */
class Form_Handler {

	/**
	 * Holds class instance
	 *
	 * @var      object \lsx\business_directory\classes\integrations\woocommerce\Form_Handler()
	 */
	protected static $instance = null;

	/**
	 * Holds the array of WC query vars
	 *
	 * @var array()
	 */
	public $query_vars = array();

	/**
	 * Holds the array of sections and their fields
	 *
	 * @var array()
	 */
	public $sections = array();

	/**
	 * Holds the defaults for the fields
	 *
	 * @var array()
	 */
	public $default = array();

	/**
	 * Holds the values for wp_update_post
	 *
	 * @var array()
	 */
	public $listing_array = array();

	/**
	 * Holds the values for the custom fields
	 *
	 * @var array()
	 */
	public $meta_array = array();

	/**
	 * Holds the values for the taxonomies
	 *
	 * @var array()
	 */
	public $tax_array = array();

	/**
	 * Contructor
	 */
	public function __construct() {
		add_action( 'template_redirect', array( $this, 'save_listing' ) );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\business_directory\classes\integrations\woocommerce\Form_Handler()    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Save and and update a billing or shipping address if the
	 * form was submitted through the user account page.
	 */
	public static function save_listing() {
		global $wp;

		$nonce_value = wc_get_var( $_REQUEST['lsx-bd-add-listing-nonce'], wc_get_var( $_REQUEST['_wpnonce'], '' ) ); // @codingStandardsIgnoreLine.

		if ( ! wp_verify_nonce( $nonce_value, 'lsx_bd_add_listing' ) ) {
			return;
		}

		if ( empty( $_POST['action'] ) || 'save_listing_details' !== $_POST['action'] ) {
			return;
		}

		wc_nocache_headers();

		$user_id = get_current_user_id();

		if ( $user_id <= 0 ) {
			return;
		}

		$customer = new \WC_Customer( $user_id );

		if ( ! $customer ) {
			return;
		}

		$this->sections = \lsx\business_directory\includes\get_listing_form_fields();
		$this->defaults = \lsx\business_directory\includes\get_listing_form_field_defaults();
		if ( ! empty( $this->sections ) ) {
			foreach ( $this->sections as $section_key => $section_values ) {
				if ( ! empty( $section_values['fields'] ) ) {
					foreach ( $section_values['fields'] as $field_key => $field_args ) {
						$field_args = wp_parse_args( $field_args, $this->defaults );
						$type       = str_replace( 'lsx_bd_', '', $field_key );

						// Get Value.
						if ( 'checkbox' === $field_args['type'] ) {
							$field_value = (int) isset( $_POST[ $field_key ] );
						} else {
							$field_value = isset( $_POST[ $field_key ] ) ? wc_clean( wp_unslash( $_POST[ $field_key ] ) ) : '';
						}

						// Validation: Required fields.
						if ( ! empty( $field_args['required'] ) && empty( $field_value ) ) {
							wc_add_notice( sprintf( __( '%s is a required field.', 'woocommerce' ), $field_args['label'] ), 'error', array( 'id' => $field_key ) );
						}

						switch ( $type ) {
							case 'post_title':
								break;
							case 'post_content':
								break;

							case 'post_excerpt':
								break;

							case 'tax_industry':
							case 'tax_location':
								break;

							default:
								break;
						}
					}
				}
			}
		}

		/*foreach ( $address as $key => $field ) {
			if ( ! isset( $field['type'] ) ) {
				$field['type'] = 'text';
			}

			// Get Value.
			if ( 'checkbox' === $field['type'] ) {
				$value = (int) isset( $_POST[ $key ] );
			} else {
				$value = isset( $_POST[ $key ] ) ? wc_clean( wp_unslash( $_POST[ $key ] ) ) : '';
			}

			// Hook to allow modification of value.
			$value = apply_filters( 'lsx_bd_process_myaccount_field_' . $key, $value );

			// Validation: Required fields.
			if ( ! empty( $field['required'] ) && empty( $value ) ) {
				wc_add_notice( sprintf( __( '%s is a required field.', 'woocommerce' ), $field['label'] ), 'error', array( 'id' => $key ) );
			}

			if ( ! empty( $value ) ) {
				// Validation and formatting rules.
				if ( ! empty( $field['validate'] ) && is_array( $field['validate'] ) ) {
					foreach ( $field['validate'] as $rule ) {
						switch ( $rule ) {
							case 'postcode':
								$country = wc_clean( wp_unslash( $_POST[ $load_address . '_country' ] ) );
								$value   = wc_format_postcode( $value, $country );

								if ( '' !== $value && ! WC_Validation::is_postcode( $value, $country ) ) {
									switch ( $country ) {
										case 'IE':
											$postcode_validation_notice = __( 'Please enter a valid Eircode.', 'woocommerce' );
											break;
										default:
											$postcode_validation_notice = __( 'Please enter a valid postcode / ZIP.', 'woocommerce' );
									}
									wc_add_notice( $postcode_validation_notice, 'error' );
								}
								break;
							case 'phone':
								if ( '' !== $value && ! WC_Validation::is_phone( $value ) ) {
									wc_add_notice( sprintf( __( '%s is not a valid phone number.', 'woocommerce' ), '<strong>' . $field['label'] . '</strong>' ), 'error' );
								}
								break;
							case 'email':
								$value = strtolower( $value );

								if ( ! is_email( $value ) ) {
									wc_add_notice( sprintf( __( '%s is not a valid email address.', 'woocommerce' ), '<strong>' . $field['label'] . '</strong>' ), 'error' );
								}
								break;
						}
					}
				}
			}
		}*/

		if ( 0 < wc_notice_count( 'error' ) ) {
			return;
		}

		wc_add_notice( __( 'Listing Succesfully Added.', 'woocommerce' ) );

		do_action( 'lsx_bd_save_listing', $user_id );

		wp_safe_redirect( wc_get_endpoint_url( 'add-listing', '', wc_get_page_permalink( 'myaccount' ) ) );
		exit;
	}
}
