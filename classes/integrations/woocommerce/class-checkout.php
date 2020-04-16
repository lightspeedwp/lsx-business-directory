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

	/**
	 * Contructor
	 */
	public function __construct() {
		if ( 'on' === lsx_bd_get_option( 'woocommerce_enable_checkout', false ) ) {
			add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'maybe_clear_cart' ), 20, 6 );
			add_action( 'woocommerce_checkout_order_processed', array( $this, 'mark_order_as_listing' ), 20, 3 );
			add_action( 'woocommerce_checkout_create_subscription', array( $this, 'mark_subscription_as_listing' ), 20, 4 );
			add_filter( 'woocommerce_thankyou_order_received_text', array( $this, 'order_received_text' ), 20, 2 );
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
	 * @param object $order WC_Order()
	 * @return void
	 */
	public function mark_order_as_listing( $order_id, $posted_data, $order ) {
		$order_items = $order->get_items();
		$listing_set = false;
		if ( ! empty( $order_items ) ) {
			foreach ( $order_items as $item_id => $item ) {
				$product_id = $item->get_product_id();
				if ( false !== $product_id && '' !== $product_id ) {
					$is_listing = get_post_meta( $product_id, '_lsx_bd_listing', true );
					if ( 'yes' === $is_listing ) {
						add_post_meta( $order_id, '_lsx_bd_listing', 'yes', true );
						$listing_set = true;
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
			$append_text = lsx_bd_get_option( 'woocommerce_thank_you_text', __( sprintf( 'Head on over to your <a href="%s">My Account</a> dashboard and add your listing.', $url ), 'lsx-business-directory' ) );
			$text        .= ' ' . $append_text;
		}
		return $text;
	}
}
