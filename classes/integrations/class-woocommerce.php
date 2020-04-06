<?php
namespace lsx\business_directory\classes\integrations;

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
	 * Holds the array of WC query vars
	 *
	 * @var array()
	 */
	public $query_vars = array();

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
	 * Initiator
	 */
	public function init() {
		if ( function_exists( 'WC' ) ) {
			$this->init_query_vars();
			add_filter( 'woocommerce_get_query_vars', array( $this, 'add_query_vars' ) );
			add_filter( 'woocommerce_account_menu_items', array( $this, 'register_my_account_tabs' ) );
			add_action( 'woocommerce_account_listings_endpoint', array( $this, 'endpoint_content' ) );
			add_action( 'lsx_bd_settings_section_translations', array( $this, 'register_translations' ), 10, 2 );
		}
	}

	/**
	 * Init query vars by loading options.
	 *
	 * @since 2.0
	 */
	public function init_query_vars() {
		$this->query_vars = array(
			'listings' => $this->get_my_listings_endpoint(),
		);
	}

	/**
	 * Hooks into `woocommerce_get_query_vars` to make sure query vars defined in
	 * this class are also considered `WC_Query` query vars.
	 *
	 * @param  array $query_vars
	 * @return array
	 */
	public function add_query_vars( $query_vars ) {
		return array_merge( $query_vars, $this->query_vars );
	}

	/**
	 * Reset the woocommerce_myaccount_view_subscriptions_endpoint option name to woocommerce_myaccount_view_subscription_endpoint
	 *
	 * @return mixed Value set for the option
	 * @since 2.2.18
	 */
	private function get_my_listings_endpoint() {
		$value = lsx_bd_get_option( 'translations_listings_endpoint', false );
		if ( false !== $value && '' !== $value ) {
			$endpoint = $value;
		} else {
			$endpoint = 'listings';
		}
		return $endpoint;
	}

	/**
	 * Registers the My Listing My account tab
	 *
	 * @param  array $menu_links
	 * @return void
	 */
	public function register_my_account_tabs( $menu_links ) {
		$new_links = array(
			$this->get_my_listings_endpoint() => __( 'Listings', 'lsx-business-directory' ),
		);
		$menu_links = array_slice( $menu_links, 0, 1, true ) + $new_links + array_slice( $menu_links, 1, null, true );
		return $menu_links;
	}

	/**
	 * Gets the endpoint content
	 *
	 * @return void
	 */
	public function endpoint_content() {
		lsx_business_template( 'woocommerce/listings' );
	}

	/**
	 * Configure Business Directory custom fields for the Settings page Translations section.
	 *
	 * @param object $cmb new_cmb2_box().
	 * @return void
	 */
	public function register_translations( $cmb, $place ) {
		if ( 'bottom' === $place ) {
			$cmb->add_field(
				array(
					'name'    => esc_html__( 'Listings Endpoint', 'lsx-business-directory' ),
					'id'      => 'translations_listings_endpoint',
					'type'    => 'text',
					'default' => 'listings',
					'desc'    => __( 'This is the endpoint for the My Account "Listings" page.', 'lsx-business-directory' ),
				)
			);
		}
	}
}
