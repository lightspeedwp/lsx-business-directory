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
	 * Holds the listing ID if this is an edit submission.
	 *
	 * @var array()
	 */
	public $listing_id = false;

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
	public $post_array = array(
		'post_status' => 'publish',
		'post_type'   => 'business-directory',
	);

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
		$this->listing_id = false;
		add_action( 'template_redirect', array( $this, 'save' ) );
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
	public function save() {
		$nonce_value = wc_get_var( $_REQUEST['lsx-bd-add-listing-nonce'], wc_get_var( $_REQUEST['_wpnonce'], '' ) ); // @codingStandardsIgnoreLine.

		if ( ! wp_verify_nonce( $nonce_value, 'lsx_bd_add_listing' ) ) {
			return false;
		}

		if ( empty( $_POST['action'] ) || 'save_listing_details' !== $_POST['action'] ) {
			return false;
		}

		if ( isset( $_POST['listing_id'] ) && empty( $_POST['listing_id'] ) && '' !== $_POST['listing_id'] && null !== $_POST['listing_id'] ) {
			$this->listing_id = sanitize_text_field( wp_unslash( $_POST['listing_id'] ) );
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
							wc_add_notice( sprintf( __( '%s is a required field.', 'lsx-business-directory' ), $field_args['label'] ), 'error', array( 'id' => $field_key ) );
						}

						// Check the specific values.
						if ( ! empty( $field_value ) ) {
							// Validation and formatting rules.
							if ( ! empty( $field_args['validate'] ) && is_array( $field_args['validate'] ) ) {
								foreach ( $field_args['validate'] as $rule ) {
									switch ( $rule ) {
										case 'postcode':
											$country       = wc_clean( wp_unslash( $_POST[ 'lsx_bd_address_country' ] ) );
											$field_value   = wc_format_postcode( $field_value, $country );

											if ( '' !== $field_value && ! \WC_Validation::is_postcode( $field_value, $country ) ) {
												switch ( $country ) {
													case 'IE':
														$postcode_validation_notice = __( 'Please enter a valid Eircode.', 'lsx-business-directory' );
														break;
													default:
														$postcode_validation_notice = __( 'Please enter a valid postcode / ZIP.', 'lsx-business-directory' );
												}
												wc_add_notice( $postcode_validation_notice, 'error' );
											}
											break;
										case 'phone':
											if ( '' !== $field_value && ! \WC_Validation::is_phone( $field_value ) ) {
												/* translators: %s: Phone number. */
												wc_add_notice( sprintf( __( '%s is not a valid phone number.', 'lsx-business-directory' ), '<strong>' . $field_args['label'] . '</strong>' ), 'error' );
											}
											break;
										case 'email':
											$field_value = strtolower( $field_value );

											if ( ! is_email( $field_value ) ) {
												/* translators: %s: Email address. */
												wc_add_notice( sprintf( __( '%s is not a valid email address.', 'lsx-business-directory' ), '<strong>' . $field_args['label'] . '</strong>' ), 'error' );
											}
											break;
									}
								}
							}
						}

						switch ( $type ) {
							case 'post_title':
							case 'post_content':
							case 'post_excerpt':
								$this->post_array[ $type ] = $field_value;
								break;

							case 'tax_industry':
							case 'tax_location':
								$this->tax_array[ $type ] = $field_value;
								break;

							default:
								$this->meta_array[ $type ] = $field_value;
								break;
						}
					}
				}
			}
		}

		if ( 0 < wc_notice_count( 'error' ) ) {
			return;
		}

		wc_add_notice( __( 'Listing Succesfully Added.', 'lsx-business-directory' ) );

		do_action( 'lsx_bd_save_listing', $this->listing_id, $this );
		$this->save_listing();
		$this->save_meta();

		wp_safe_redirect( wc_get_endpoint_url( 'listings', '', wc_get_page_permalink( 'myaccount' ) ) );
		exit;
	}

	/**
	 * This adds a new listing to the system.
	 *
	 * @return void
	 */
	public function save_listing() {
		if ( false === $this->listing_id || '' === $this->listing_id ) {
			$this->listing_id = wp_insert_post( $this->post_array );
		}
	}

	/**
	 * This saves the custom fields to the listing.
	 *
	 * @return void
	 */
	public function save_meta() {
		if ( false !== $this->listing_id ) {
			foreach ( $this->meta_array as $meta_key => $meta_value ) {
				$previous_value = get_post_meta( $this->listing_id, $meta_key, true );
				update_post_meta( $this->listing_id, $meta_key, $meta_value, $previous_value );
			}
		}
	}

	/**
	 * Saves the listing taxonomy data.
	 *
	 * @return void
	 */
	public function save_tax() {
	}
}
