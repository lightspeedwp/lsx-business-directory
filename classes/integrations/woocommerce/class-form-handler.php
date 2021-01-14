<?php
namespace lsx\business_directory\classes\integrations\woocommerce;

use stdClass;

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
		'post_status' => 'pending',
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
	 * Holds the url to redirect to after processing the form.
	 *
	 * @var string
	 */
	public $redirect;

	/**
	 * Contructor
	 */
	public function __construct() {
		$this->listing_id = false;
		add_action( 'template_redirect', array( $this, 'save' ) );
		add_filter( 'template_include', array( $this, 'preview_template_include' ), 2, 1 );
		add_action( 'wp_head', array( $this, 'preview_handler' ) );
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
		if ( ! function_exists( 'wc_get_var' ) ) {
			return;
		}
		$nonce_value = wc_get_var( $_REQUEST['lsx-bd-add-listing-nonce'], wc_get_var( $_REQUEST['_wpnonce'], '' ) ); // @codingStandardsIgnoreLine.

		if ( ! wp_verify_nonce( $nonce_value, 'lsx_bd_add_listing' ) ) {
			return false;
		}

		if ( empty( $_POST['action'] ) || ( 'save_listing_details' !== $_POST['action'] && 'edit_listing_details' !== $_POST['action'] ) || is_wc_endpoint_url( 'preview-listing' ) ) {
			return false;
		} else {
			$this->action = sanitize_text_field( wp_unslash( $_POST['action'] ) );
		}

		if ( isset( $_POST['listing_id'] ) && ! empty( $_POST['listing_id'] ) && '' !== $_POST['listing_id'] && null !== $_POST['listing_id'] && 0 !== $_POST['listing_id'] ) {
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
						$type       = str_replace( array( 'lsx_bd_', 'tax_', '_upload' ), '', $field_key );

						if ( in_array( $field_key, array( 'lsx_bd__thumbnail_id', 'lsx_bd_banner_id' ) ) ) {
							continue;
						}

						// Get Value.
						if ( 'checkbox' === $field_args['type'] ) {
							$field_value = (int) isset( $_POST[ $field_key ] );
						} else {
							$field_value = isset( $_POST[ $field_key ] ) ? wc_clean( wp_unslash( $_POST[ $field_key ] ) ) : '';
						}

						if ( 'lsx_bd_post_content' === $field_key ) {
							$field_value = wpautop( $_POST['lsx_bd_post_content'] ); // @codingStandardsIgnoreLine.
						}

						// Validation: Required fields.
						if ( ! empty( $field_args['required'] ) && empty( $field_value ) ) {
							wc_add_notice( sprintf( __( '%s is a required field.', 'lsx-business-directory' ), $field_args['label'] ), 'error', array( 'id' => $field_key ) );// @codingStandardsIgnoreLine
						}

						// Check the specific values.
						if ( ! empty( $field_value ) ) {
							// Validation and formatting rules.
							if ( ! empty( $field_args['validate'] ) && is_array( $field_args['validate'] ) ) {
								foreach ( $field_args['validate'] as $rule ) {
									switch ( $rule ) {
										case 'postcode':
											$country       = wc_clean( wp_unslash( $_POST[ 'lsx_bd_address_country' ] ) ); // @codingStandardsIgnoreLine
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
							case 'post_status':
								$this->post_array[ $type ] = $field_value;
								break;

							case 'industry':
							case 'location':
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

		// If we are charging for listings then we need to check for a selected product.
		if ( 'on' === lsx_bd_get_option( 'woocommerce_enable_checkout', false ) ) {
			$lsx_bd_plan_id = filter_input( INPUT_POST, 'lsx_bd_plan_id' );
			if ( empty( $lsx_bd_plan_id ) || '' === $lsx_bd_plan_id ) {
				/* translators: %s: Subscription Field Label */
				wc_add_notice(
					__( 'Please select an available listing product.', 'lsx-business-directory' ),
					'error',
					array(
						'id' => 'lsx_bd_plan_id',
					)
				);
			}
		}

		if ( 0 < wc_notice_count( 'error' ) ) {
			return;
		}

		do_action( 'lsx_bd_save_listing', $this->listing_id, $this );

		if ( 'save_listing_details' === $this->action ) {
			wc_add_notice( $this->post_array['post_title'] . ' ' . __( 'succesfully added.', 'lsx-business-directory' ) );
			$this->redirect = wc_get_endpoint_url( lsx_bd_get_option( 'translations_listings_endpoint', 'my-listings' ), '', wc_get_page_permalink( 'myaccount' ) );
		} else {
			/* translators: %s: my-account */
			wc_add_notice(
				sprintf(
					/* translators: %s: My Account link and the View listing link */
					__( 'Listing updated succesfully. Go back to <a href="%1$s">my listings</a> or view your <a href="%2$s">listing</a>', 'lsx-business-directory' ),
					wc_get_endpoint_url( lsx_bd_get_option( 'translations_listings_endpoint', 'my-listings' ), '', wc_get_page_permalink( 'myaccount' ) ),
					get_permalink( $this->listing_id )
				)
			);
			$this->redirect = wc_get_endpoint_url( lsx_bd_get_option( 'translations_listings_edit_endpoint', 'edit-listing' ) . '/' . $this->listing_id . '/', '', wc_get_page_permalink( 'myaccount' ) );
		}

		$this->save_listing();
		$this->save_meta();
		$this->save_tax();
		$this->save_images();
		$this->set_redirect();

		wp_safe_redirect( $this->redirect );
		exit;
	}

	/**
	 * This adds a new listing to the system.
	 *
	 * @return void
	 */
	public function save_listing() {
		if ( 'on' === lsx_bd_get_option( 'woocommerce_enable_checkout', false ) ) {
			$this->meta_array['lsx_bd_subscription_product'] = isset( $_POST['lsx_bd_plan_id'] ) ? wc_clean( wp_unslash( $_POST['lsx_bd_plan_id'] ) ) : '';// @codingStandardsIgnoreLine
		}
		$this->post_array['post_name'] = sanitize_title_with_dashes( $this->post_array['post_title'] );
		if ( 'save_listing_details' === $this->action && ( false === $this->listing_id || '' === $this->listing_id ) ) {
			$this->listing_id = wp_insert_post( $this->post_array );
		} else {
			$this->post_array['ID'] = $this->listing_id;
			wp_update_post( $this->post_array );
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
				delete_post_meta( $this->listing_id, 'lsx_bd_' . $meta_key );
				add_post_meta( $this->listing_id, 'lsx_bd_' . $meta_key, $meta_value, true );
			}
		}
	}

	/**
	 * This saves the images and attaches them to the listing.
	 *
	 * @return void
	 */
	public function save_images() {
		$att_id = false;

		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';

		if ( ! empty( $_FILES ) ) {
			foreach ( $_FILES as $file_key => $file_array ) {
				if ( ! empty( $file_array ) && isset( $file_array['error'] ) && 0 === (int) $file_array['error'] ) {
					$att_id = media_handle_sideload( $file_array, $this->listing_id );
					if ( false !== $att_id && '' !== $att_id ) {
						$meta_key = str_replace( '_upload', '', $file_key );
						if ( 'lsx_bd__thumbnail_id' === $meta_key ) {
							$meta_key = str_replace( 'lsx_bd_', '', $meta_key );
						}
						$previous_val = get_post_meta( $this->listing_id, $meta_key, true );
						update_post_meta( $this->listing_id, $meta_key, $att_id, $previous_val );

						if ( '_thumbnail_id' !== $meta_key ) {
							$meta_key     = str_replace( '_id', '', $meta_key );
							$previous_val = get_post_meta( $this->listing_id, $meta_key, true );
							update_post_meta( $this->listing_id, $meta_key, get_permalink( $att_id ), $previous_val );
						}
					}
				}
			}
		}
		return $att_id;
	}

	/**
	 * Saves the listing taxonomy data.
	 *
	 * @return void
	 */
	public function save_tax() {
		$parent = false;
		foreach ( $this->tax_array as $taxonomy => $name ) {
			$term = term_exists( $name, $taxonomy );
			if ( ! $term ) {
				if ( false !== $parent ) {
					$parent = array(
						'parent' => $parent,
					);
				}
				$term = wp_insert_term( trim( $name ), $taxonomy, $parent );
				if ( is_wp_error( $term ) ) {
					echo wp_kses_post( $term->get_error_message() );
				} else {
					wp_set_object_terms( $this->listing_id, intval( $term['term_id'] ), $taxonomy, false );
				}
			} else {
				wp_set_object_terms( $this->listing_id, intval( $term['term_id'] ), $taxonomy, false );
			}
		}
	}

	/**
	 * Sets the appropriate URL to redirect to.
	 *
	 * @return void
	 */
	public function set_redirect() {
		if ( 'save_listing_details' === $this->action ) {
			wc_add_notice( $this->post_array['post_title'] . ' ' . __( 'succesfully added.', 'lsx-business-directory' ) );
			$this->redirect = wc_get_endpoint_url( lsx_bd_get_option( 'translations_listings_endpoint', 'my-listings' ), '', wc_get_page_permalink( 'myaccount' ) );
		} else {
			$this->redirect = wc_get_endpoint_url( lsx_bd_get_option( 'translations_listings_edit_endpoint', 'edit-listing' ) . '/' . $this->listing_id . '/', '', wc_get_page_permalink( 'myaccount' ) );
		}

		// Make sure our URL has an ID to save to the Cart.
		if ( 'on' === lsx_bd_get_option( 'woocommerce_enable_checkout', false ) ) {
			$this->maybe_switching_subscription();
		}
	}

	/**
	 * If the current subscription product is the same as the subscription, then its the active one, then you are switching.
	 *
	 * @return void
	 */
	public function maybe_switching_subscription() {
		if ( false !== $this->listing_id && '' !== $this->listing_id ) {
			$current_subscription = get_post_meta( $this->listing_id, '_lsx_bd_order_id', true );
			if ( false !== $current_subscription && '' !== $current_subscription ) {
				// if the current subscription product is the same as the subscription, then its the active one, then you are switching.
				if ( (int) $current_subscription !== (int) $this->meta_array['lsx_bd_subscription_product'] ) {
					$product      = wc_get_product( $this->meta_array['lsx_bd_subscription_product'] );
					$subscription = wcs_get_subscription( $current_subscription );
					if ( ! empty( $subscription ) ) {
						$items = $subscription->get_items();
						foreach ( $items as $item_key => $item_value ) {
							$this->redirect = add_query_arg(
								array(
									'switch-subscription' => $current_subscription,
									'item'                => $item_key,
									'_wcsnonce'           => wp_create_nonce( 'wcs_switch_request' ),
									'lsx_bd_id'           => $this->listing_id,
								),
								$product->add_to_cart_url()
							);
						}
					}
				}
			} else {
				$product        = wc_get_product( $this->meta_array['lsx_bd_subscription_product'] );
				$this->redirect = add_query_arg( 'lsx_bd_id', $this->listing_id, $product->add_to_cart_url() );
			}
		} else {
			$product        = wc_get_product( $this->meta_array['lsx_bd_subscription_product'] );
			$this->redirect = add_query_arg( 'lsx_bd_id', $this->listing_id, $product->add_to_cart_url() );
		}
	}

	/**
	 * Archive template redirect.
	 *
	 * @param  string $template The path to the template to load.
	 * @return string
	 */
	public function preview_template_include( $template ) {

		if ( ! function_exists( 'wc_get_var' ) ) {
			return $template;
		}
		$nonce_value = wc_get_var( $_REQUEST['lsx-bd-add-listing-nonce'], wc_get_var( $_REQUEST['_wpnonce'], '' ) ); // @codingStandardsIgnoreLine.
		if ( ! wp_verify_nonce( $nonce_value, 'lsx_bd_add_listing' ) ) {
			return $template;
		}
		if ( __( 'Preview', 'lsx-business-directory' ) === filter_input( INPUT_POST, 'preview_listing_details' ) ) {
			if ( empty( locate_template( array( 'single-business-directory.php' ) ) ) && file_exists( LSX_BD_PATH . 'templates/single-business-directory.php' ) ) {
				$template = LSX_BD_PATH . 'templates/single-business-directory.php';
			}
		}
		return $template;
	}

	/**
	 * Setup the post data with our preview information
	 *
	 * @return void
	 */
	public function preview_handler() {
		if ( function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url( 'preview-listing' ) ) {
			global $post;

			$user_id = get_current_user_id();
			if ( $user_id <= 0 ) {
				return;
			}
			$customer = new \WC_Customer( $user_id );
			if ( ! $customer ) {
				return;
			}

			$preview_object = new stdClass();

			$time_now = date( 'Y-m-d g:i:s', strtotime( 'now' ) );

			$preview_object->ID                    = get_query_var( 'preview-listing' );
			$preview_object->post_author           = $user_id;
			$preview_object->post_date             = $time_now;
			$preview_object->post_date_gmt         = $time_now;
			$preview_object->post_content          = $this->post_custom_fields( '', '', 'lsx_bd_post_content' );
			$preview_object->post_title            = $this->post_custom_fields( '', '', 'lsx_bd_post_title' );
			$preview_object->post_excerpt          = $this->post_custom_fields( '', '', 'lsx_bd_post_excerpt' );
			$preview_object->post_status           = 'publish';
			$preview_object->comment_status        = 'closed';
			$preview_object->ping_status           = 'closed';
			$preview_object->post_name             = sanitize_key( $this->post_custom_fields( '', '', 'lsx_bd_post_title' ) );
			$preview_object->to_ping               = '';
			$preview_object->pinged                = '';
			$preview_object->post_modified         = $time_now;
			$preview_object->post_modified_gmt     = $time_now;
			$preview_object->post_content_filtered = '';
			$preview_object->post_parent           = 0;
			$preview_object->guid                  = '/&p=' . get_query_var( 'preview-listing' );
			$preview_object->menu_order            = 0;
			$preview_object->post_type             = 'business-directory';
			$preview_object->post_mime_type        = '';
			$preview_object->comment_count         = 0;
			$preview_object->filter                = 'raw';
			$post                                  = $preview_object; // @codingStandardsIgnoreLine.

			add_filter( 'get_post_metadata', array( $this, 'post_custom_fields' ), 11, 3 );
		}
	}

	/**
	 * Replace the custom fields with their preview values.
	 */
	public function post_custom_fields( $meta, $post_id, $meta_key ) {
		$value = filter_input( INPUT_POST, $meta_key );
		if ( ! empty( $value ) && '' !== $value ) {
			$meta = $value;
		}
		if ( 'lsx_bd_banner' === $meta_key || 'lsx_bd__thumbnail' === $meta_key ) {

			$size = 'full';
			if ( 'lsx_bd__thumbnail' === $meta_key ) {
				$size = 'lsx-thumbnail-wide';
			}
			$meta_key .= '_id';

			$image_id = filter_input( INPUT_POST, $meta_key );
			if ( ! empty( $image_id ) && '' !== $image_id ) {
				$image_src = wp_get_attachment_image_src( $image_id, $size );
				if ( ! empty( $image_src ) ) {
					$meta = $image_src[0];
				}
			}

			$meta_key .= '_upload';
			if ( isset( $_FILES[ $meta_key ] ) ) {
				if ( '' !== $_FILES[ $meta_key ]['tmp_name'] && ! empty( $_FILES[ $meta_key ]['tmp_name'] ) ) {
					$image_src = getimagesize( $_FILES[ $meta_key ]['tmp_name'] ); // @codingStandardsIgnoreLine
					$meta      = 'data:' . $image_src['mime'] . ";base64," . base64_encode( file_get_contents( $_FILES[ $meta_key ]['tmp_name'] ) ); // @codingStandardsIgnoreLine	
				}
			}
		}
		return $meta;
	}
}
