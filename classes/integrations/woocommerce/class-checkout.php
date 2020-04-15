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
		add_action( 'init', array( $this, 'init' ) );
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
	 * Initiator
	 */
	public function init() {
		if ( function_exists( 'WC' ) && 'on' === lsx_bd_get_option( 'woocommerce_enable_checkout_form', false ) ) {
			add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'maybe_clear_cart' ), 20, 6 );
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
}
