<?php
namespace lsx\business_directory\classes\integrations;

use Yoast\WP\SEO\WordPress\Integration;

/**
 * Woocommerce Integration class
 *
 * @package lsx-business-directory
 */
class Woocommerce {

	/**
	 * Holds class instance
	 *
	 * @var      object \lsx\business_directory\classes\Woocommerce()
	 */
	protected static $instance = null;

	/**
	 * Holds the Admin class
	 *
	 * @var      object \lsx\business_directory\classes\integrations\woocommerce\Admin()
	 */
	public $admin = null;

	/**
	 * Holds the form handler class
	 *
	 * @var      object \lsx\business_directory\classes\integrations\woocommerce\Form_Handler()
	 */
	public $form_handler = null;

	/**
	 * Holds the My Account class
	 *
	 * @var      object \lsx\business_directory\classes\integrations\woocommerce\My_Account()
	 */
	public $my_account = null;

	/**
	 * Holds the subscriptions class
	 *
	 * @var      object \lsx\business_directory\classes\integrations\woocommerce\Subscriptions()
	 */
	public $subscriptions = null;

	/**
	 * Holds the Edit Subscriptions class
	 *
	 * @var      object \lsx\business_directory\classes\integrations\woocommerce\Subscriptions_Edit()
	 */
	public $subscriptions_edit = null;

	/**
	 * Holds the Checkout class
	 *
	 * @var      object \lsx\business_directory\classes\integrations\woocommerce\Checkout()
	 */
	public $checkout = null;

	/**
	 * Holds the array of WC query vars
	 *
	 * @var array()
	 */
	public $query_vars = array();

	/**
	 * Contructor
	 */
	public function __construct() {
		$this->load_classes();
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 5 );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\business_directory\classes\Woocommerce()    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Loads the variable classes and the static classes.
	 */
	private function load_classes() {
		require_once LSX_BD_PATH . '/classes/integrations/woocommerce/class-admin.php';
		$this->admin = woocommerce\Admin::get_instance();

		require_once LSX_BD_PATH . '/classes/integrations/woocommerce/class-my-account.php';
		$this->my_account = woocommerce\My_Account::get_instance();

		require_once LSX_BD_PATH . '/classes/integrations/woocommerce/class-form-handler.php';
		$this->form_handler = woocommerce\Form_Handler::get_instance();

		require_once LSX_BD_PATH . '/classes/integrations/woocommerce/class-subscriptions.php';
		$this->subscriptions = woocommerce\Subscriptions::get_instance();

		require_once LSX_BD_PATH . '/classes/integrations/woocommerce/class-subscriptions-edit.php';
		$this->subscriptions_edit = woocommerce\Subscriptions_Edit::get_instance();

		require_once LSX_BD_PATH . '/classes/integrations/woocommerce/class-checkout.php';
		$this->checkout = woocommerce\Checkout::get_instance();
	}



	/**
	 * Register and enqueue front-specific style sheet.
	 *
	 * @since 1.0.0
	 *
	 * @return    null
	 */
	public function enqueue_scripts() {
		if ( defined( 'SCRIPT_DEBUG' ) ) {
			$prefix = 'src/';
			$suffix = '';
		} else {
			$prefix = '';
			$suffix = '.min';
		}
		$dependacies = array( 'jquery', 'lsx-bd-frontend' );
		wp_enqueue_script( 'lsx-bd-listing-form', LSX_BD_URL . 'assets/js/' . $prefix . 'lsx-bd-listing-form' . $suffix . '.js', $dependacies, LSX_BD_VER, true );
		/*$param_array = array(
			'api_key'     => $this->api_key,
			'google_url'  => $google_url,
			'placeholder' => $placeholder,
		);
		wp_localize_script( 'lsx-bd-frontend-maps', 'lsx_bd_maps_params', $param_array );*/
	}
}
