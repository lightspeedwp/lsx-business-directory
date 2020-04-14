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
}
