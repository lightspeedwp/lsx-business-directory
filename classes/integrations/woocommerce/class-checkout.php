<?php
namespace lsx\business_directory\classes\integrations\woocommerce;

use WC_Product_Query;
use WC_Query;
use WP_Query;

/**
 * Handles the Checkout actions
 *
 * @package lsx-business-directory
 */
class Checkout {

	/**
	 * Holds class instance
	 *
	 * @var      object \lsx\business_directory\classes\integrations\woocommerce\Checkout()
	 */
	protected static $instance = null;

	/**
	 * If we should redirect to the add listing form or not.
	 *
	 * @var boolean
	 */
	private $should_redirect = false;

	public $order_listing_item = false;

	/**
	 * Contructor
	 */
	public function __construct() {
		if ( 'on' === lsx_bd_get_option( 'woocommerce_enable_checkout', false ) ) {
			add_action( 'woocommerce_loaded', array( $this, 'attach_dependant_hooks' ) );
		}
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\business_directory\classes\integrations\woocommerce\Checkout()    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Attach_dependant_hooks.
	 */
	public function attach_dependant_hooks() {
		if ( class_exists( 'WC_Subscriptions' ) && ! \WC_Subscriptions::is_woocommerce_pre( '3.0' ) ) {
			add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'maybe_clear_cart' ), 20, 6 );
			add_filter( 'woocommerce_thankyou_order_received_text', array( $this, 'order_received_text' ), 20, 2 );
			add_filter( 'woocommerce_add_cart_item_data', array( $this, 'add_listing_id_to_cart' ), 10, 3 );
			add_action( 'woocommerce_checkout_order_processed', array( $this, 'mark_order_as_listing' ), 20, 3 );
			add_action( 'woocommerce_checkout_create_subscription', array( $this, 'mark_subscription_as_listing' ), 20, 4 );
			add_filter( 'woocommerce_get_item_data', array( $this, 'get_item_data_cart_text' ), 10, 2 );
			add_action( 'woocommerce_order_status', array( $this, 'process_order_status' ), 20, 3 );
			add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'add_listing_id_to_order_item' ), 10, 4 );
		}
	}

	/**
	 * Checks to see if the current product is a listing product or service.
	 *
	 * @param boolean $passed
	 * @param string $product_id
	 * @param string $quantity
	 * @return boolean
	 */
	public function maybe_clear_cart( $passed, $product_id, $quantity, $variation_id = false, $variations = array(), $cart_item_data = array() ) {
		if ( ! WC()->cart->is_empty() ) {
			if ( false !== $variation_id ) {
				$product_id = $variation_id;
			}
			$is_listing = get_post_meta( $product_id, '_lsx_bd_listing', true );
			if ( 'yes' === $is_listing ) {
				WC()->cart->empty_cart();
				$this->should_redirect = true;
			}
		}
		return $passed;
	}

	/**
	 * Saves the a custom field to the order for easy querying.
	 *
	 * @param int $order_id
	 * @param array $posted_data
	 * @param \WC_Order() $order
	 * @return void
	 */
	public function mark_order_as_listing( $order_id, $posted_data, $order ) {
		$order_items = $order->get_items();
		$listing_set = false;
		if ( ! empty( $order_items ) ) {
			/**
			 * @var $item \WC_Order_Item_Product
			 */
			foreach ( $order_items as $item_id => $item ) {
				$product_id = $item->get_product_id();

				if ( false !== $product_id && '' !== $product_id ) {
					$is_listing = get_post_meta( $product_id, '_lsx_bd_listing', true );
					if ( 'yes' === $is_listing ) {
						add_post_meta( $order_id, '_lsx_bd_listing', 'yes', true );
						$listing_set = true;
						$this->order_listing_item = $item;
					}
				}
			}
		}
		return $listing_set;
	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $subscription
	 * @param [type] $posted_data
	 * @param [type] $order
	 * @param [type] $cart
	 * @return void
	 */
	public function mark_subscription_as_listing( $subscription, $posted_data, $order, $cart ) {
		$is_listing_order = $this->mark_order_as_listing( $order->get_id(), $posted_data, $order );
		if ( true === $is_listing_order ) {
			add_post_meta( $subscription->get_id(), '_lsx_bd_listing', 'yes', true );
			$this->maybe_needs_listing( $subscription );
		}
	}

	/**
	 * Checks to see if the current order needs a subscription.
	 *
	 * @return void
	 */
	public function maybe_needs_listing( $subscription ) {
		$subscription_items = $subscription->get_items();
		$item_key           = false;
		$listing_id         = false;

		if ( ! empty( $subscription_items ) ) {
			/**
			 * @var $item \WC_Order_Item_Product
			 */
			foreach ( $subscription_items as $item_id => $item ) {
				$listing_id = $item->get_meta( __( 'Listing', 'lsx-business-directory' ) );

				if ( empty( $listing_id ) ) {
					$product_id = $item->get_product_id();
					if ( false !== $product_id && '' !== $product_id ) {
						$is_listing = get_post_meta( $product_id, '_lsx_bd_listing', true );
						if ( 'yes' === $is_listing ) {
							$listing_title = $subscription->get_billing_company();
							if ( '' === $listing_title ) {
								$listing_title = __( 'Listing for #', 'lsx-business-directory' ) . $subscription->get_id();
							}
							$listing_array = array(
								'post_status' => 'draft',
								'post_title'  => $listing_title,
								'post_type'   => 'business-directory',
								'post_author' => $subscription->get_customer_id(),
							);
							$listing_id    = wp_insert_post( $listing_array );
							$item->add_meta_data( __( 'Listing', 'lsx-business-directory' ), $listing_id, true );
							$item->save_meta_data();
							add_post_meta( $subscription->get_id(), '_lsx_bd_listing_id', $listing_id, false );
							add_post_meta( $listing_id, '_lsx_bd_order_id', $subscription->get_id(), true );

							// Make sure the order saves the ID as well.
							$this->order_listing_item->add_meta_data( __( 'Listing', 'lsx-business-directory' ), $listing_id, true );
							$this->order_listing_item->save_meta_data();
						}
					}
				}
			}
		}
	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $text
	 * @param [type] $order
	 * @return void
	 */
	public function order_received_text( $text, $order ) {
		$is_listing = get_post_meta( $order->get_id(), '_lsx_bd_listing', true );
		if ( 'yes' === $is_listing && in_array( $order->get_status(), array( 'complete', 'processing' ) ) ) {
			$url         = wc_get_endpoint_url( lsx_bd_get_option( 'translations_listings_add_endpoint', 'add-listing' ), '', wc_get_page_permalink( 'myaccount' ) );
			// :translators: %s: Add Listing link
			$append_text = lsx_bd_get_option( 'woocommerce_thank_you_text', __( sprintf( 'Head on over to your <a href="%s">My Account</a> dashboard and add your listing.', $url ), 'lsx-business-directory' ) );// @codingStandardsIgnoreLine
			$text        .= ' ' . $append_text;
		}
		return $text;
	}

	/**
	 * Saves the listing ID to the cart data, so we can attach it to the order later.
	 *
	 * @param [type] $cart_item_data
	 * @param [type] $product_id
	 * @param [type] $variation_id
	 * @return void
	 */
	public function add_listing_id_to_cart( $cart_item_data, $product_id, $variation_id ) {
		$listing_id = filter_input( INPUT_GET, 'lsx_bd_id' );
		if ( empty( $listing_id ) || '' === $listing_id ) {
			return $listing_id;
		}
		$cart_item_data['lsx_bd_id'] = $listing_id;
		return $cart_item_data;
	}

	/**
	 * Add the listings ID to the order.
	 *
	 * @param \WC_Order_Item_Product $item
	 * @param string                $cart_item_key
	 * @param array                 $values
	 * @param \WC_Order              $order
	 */
	public function add_listing_id_to_order_item( $item, $cart_item_key, $values, $order ) {
		if ( empty( $values['lsx_bd_id'] ) ) {
			return;
		}
		$item->add_meta_data( __( 'Listing', 'lsx-business-directory' ), $values['lsx_bd_id'] );
		if ( ! empty( $values['lsx_bd_id'] ) && ! empty( $order->get_id() ) ) {
			add_post_meta( $order->get_id(), '_lsx_bd_listing_id', $values['lsx_bd_id'], false );

			// Preserve any previous orders, and get ready for the new one.
			$this->preserve_previous_orders( $values['lsx_bd_id'] );
			add_post_meta( $values['lsx_bd_id'], '_lsx_bd_order_id', $order->get_id(), true );
		}
	}

	/**
	 * If the listing had any previous orders attached to it, we preserve those in a custom field `_lsx_bd_previous_order_ids`;
	 *
	 * @param [type] $item_id
	 * @return void
	 */
	public function preserve_previous_orders( $item_id ) {
		$current_order = get_post_meta( $item_id, '_lsx_bd_order_id', true );
		// If then current order isnt empty then we need to save it as a "history".
		if ( ! empty( $current_order ) ) {
			$new_order_array = array();
			$previous_orders = get_post_meta( $item_id, '_lsx_bd_previous_order_ids', true );
			if ( is_array( $previous_orders ) ) {
				if ( empty( $previous_orders ) ) {
					$new_order_array[] = $current_order;
				} else {
					$new_order_array = array_merge( $previous_orders, array( $current_order ) );
				}
			} else {
				$new_order_array[] = $current_order;
			}
			delete_post_meta( $item_id, '_lsx_bd_previous_order_ids' );
			add_post_meta( $item_id, '_lsx_bd_previous_order_ids', $new_order_array, true );
			delete_post_meta( $item_id, '_lsx_bd_order_id' );
		}
	}

	/**
	 * Display text in the cart.
	 *
	 * @param array $item_data
	 * @param array $cart_item
	 *
	 * @return array
	 */
	public function get_item_data_cart_text( $item_data, $cart_item ) {
		if ( empty( $cart_item['lsx_bd_id'] ) ) {
			return $item_data;
		}

		$item_data[] = array(
			'key'     => __( 'Listing', 'lsx-business-directory' ),
			'value'   => wc_clean( $cart_item['lsx_bd_id'] ),
			'display' => get_the_title( $cart_item['lsx_bd_id'] ),
		);

		return $item_data;
	}
}
