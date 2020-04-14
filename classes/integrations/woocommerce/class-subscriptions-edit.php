<?php
namespace lsx\business_directory\classes\integrations\woocommerce;
/**
 * Subscriptions_Edit Class
 */

/**
 * Product Retailers Admin Edit Screen
 *
 * @since 1.0.0
 */
class Subscriptions_Edit {

	/**
	 * Holds class instance
	 *
	 * @var      object \lsx\business_directory\classes\Woocommerce()
	 */
	protected static $instance = null;

	/**
	 * Holds the array of fields
	 * @var array
	 */
	public $fields = array();

	/**
	 * Initialize and setup the retailer add/edit screen.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Add in the checkboxes.
		add_action( 'woocommerce_variation_options', array( $this, 'variation_settings_fields' ), 10, 3 );

		// Save Custom Field Data.
		add_action( 'woocommerce_process_product_meta', array( $this, 'save_product_meta' ), 30, 2 );
		add_action( 'woocommerce_save_product_variation', array( $this, 'save_variation_meta' ), 10, 2 );

		add_filter( 'product_type_options', array( $this, 'register_product_type' ), 10, 1 );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\business_directory\classes\integrations\woocommerce\Subscriptions_Edit()    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Return array of product type options.
	 *
	 * @return array
	 */
	public function register_product_type( $filters = array() ) {
		$filters['listing'] = array(
			'id'            => '_lsx_bd_listing',
			'wrapper_class' => 'show_if_simple show_if_variation',
			'label'         => __( 'Listing', 'lsx-business-directory' ),
			'description'   => __( 'Listing Products allows you to sell listings.', 'lsx-business-directory' ),
			'default'       => 'no',
		);
		return $filters;
	}

	/**
	 *
	 * Enqueue the admin scripts for adding in the date field.
	 *
	 * @param $post_id
	 * @param $post
	 *
	 * @return bool
	 */
	public function save_product_meta( $post_id, $post ) {

		if ( ! ( isset( $_POST['woocommerce_meta_nonce'] ) || wp_verify_nonce( sanitize_key( $_POST['woocommerce_meta_nonce'] ), 'woocommerce_save_data' ) ) ) {
			return false;
		}

		// Checkbox.
		$woocommerce_checkbox = isset( $_POST['_lsx_bd_listing'] ) ? 'yes' : 'no';
		update_post_meta( $post_id, '_lsx_bd_listing', $woocommerce_checkbox );
	}

	/**
	 * Enqueue the admin scripts for adding in the date field.
	 */
	public function save_variation_meta( $variation_id, $i ) {
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$checkbox = isset( $_POST['_lsx_bd_listing'][ $i ] ) ? 'yes' : 'no';
		if ( 'yes' === $checkbox ) {
			update_post_meta( $variation_id, '_lsx_bd_listing', $checkbox );
		} else {
			delete_post_meta( $variation_id, '_lsx_bd_listing' );
		}
	}

	/**
	 * Create the checkbox enable for variations
	 */
	public function variation_settings_fields( $loop, $variation_data, $variation ) {
		?>
		<label class="tips" data-tip="<?php esc_attr_e( 'Listing Products allows you to sell listings.', 'lsx-business-directory' ); ?>">
			<?php esc_html_e( 'Listing', 'lsx-business-directory' ); ?>:
			<input type="checkbox" class="checkbox variable_is_listing" name="_lsx_bd_listing[<?php echo esc_attr( $loop ); ?>]" <?php checked( get_post_meta( $variation->ID, '_lsx_bd_listing', true ), 'yes' ); ?> />
		</label>
		<?php
	}
}
