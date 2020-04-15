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
}
