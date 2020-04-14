<?php
namespace lsx\business_directory\classes\integrations\woocommerce;

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
		if ( function_exists( 'WC' ) ) {
			add_filter( 'woocommerce_cart_contents_changed', array( $this, 'maybe_clear_cart' ), 10, 1 );
		}
	}

	/**
	 * Checks to see if the current product is a listing product or service.
	 *
	 * @return void
	 */
	public function maybe_clear_cart( $cart_contents = array() ) {
		if ( ! empty( $cart_contents ) ) {
			$new_contents = array();
			foreach( $cart_contents as $key => $product_in_cart ) {
				$product_id = $product_in_cart['data']->get_id();
				$is_listing = get_post_meta( $product_id, '_lsx_bd_listing', true );
				if ( 'yes' === $is_listing ) {
					$new_contents[ $key ] = $product_in_cart;
				}
			}

			if ( ! empty( $new_contents ) ) {
				$cart_contents = $new_contents;
			}
		}
		return $cart_contents;
	}
}
