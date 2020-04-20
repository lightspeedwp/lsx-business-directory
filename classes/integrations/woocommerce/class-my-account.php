<?php
namespace lsx\business_directory\classes\integrations\woocommerce;

/**
 * Handles the My Account Menus
 *
 * @package lsx-business-directory
 */
class My_Account {

	/**
	 * Holds class instance
	 *
	 * @var      object \lsx\business_directory\classes\integrations\woocommerce\My_Account()
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
	 * @return    object \lsx\business_directory\classes\integrations\woocommerce\My_Account()    A single instance of this class.
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
			add_action( 'woocommerce_account_add-listing_endpoint', array( $this, 'endpoint_content' ) );
			add_action( 'woocommerce_account_edit-listing_endpoint', array( $this, 'endpoint_content' ) );
			add_filter( 'woocommerce_account_menu_item_classes', array( $this, 'menu_item_classes' ), 10, 2 );
		}
	}
	/**
	 * Init query vars by loading options.
	 *
	 * @since 2.0
	 */
	public function init_query_vars() {
		$this->query_vars = array(
			'listings'        => lsx_bd_get_option( 'translations_listings_endpoint', 'listings' ),
			'add-listing'     => lsx_bd_get_option( 'translations_listings_add_endpoint', 'add-listing' ),
			'preview-listing' => lsx_bd_get_option( 'translations_listings_preview_endpoint', 'preview-listing' ),
			'edit-listing'    => lsx_bd_get_option( 'translations_listings_edit_endpoint', 'edit-listing' ),
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
	 * Registers the My Listing My account tab
	 *
	 * @param  array $menu_links
	 * @return void
	 */
	public function register_my_account_tabs( $menu_links ) {
		$new_links  = array(
			lsx_bd_get_option( 'translations_listings_endpoint', 'listings' ) => __( 'Listings', 'lsx-business-directory' ),
		);
		$menu_links = array_slice( $menu_links, 0, 1, true ) + $new_links + array_slice( $menu_links, 1, null, true );
		return $menu_links;
	}

	/**
	 * Highlight the listings menu item if you are adding or editing a listing.
	 *
	 * @param  array $classes
	 * @param  string $endpoint
	 * @return array
	 */
	public function menu_item_classes( $classes, $endpoint ) {
		global $wp;
		if ( lsx_bd_get_option( 'translations_listings_endpoint', 'listings' ) === $endpoint && ( isset( $wp->query_vars['add-listing'] ) || isset( $wp->query_vars['edit-listing'] ) || isset( $wp->query_vars['preview-listing'] ) ) ) {
			$classes[] = 'is-active';
		}
		return $classes;
	}

	/**
	 * Gets the endpoint content
	 *
	 * @return void
	 */
	public function endpoint_content() {
		lsx_business_template( 'woocommerce/listings' );
	}
}
