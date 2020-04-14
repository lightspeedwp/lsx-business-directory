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
	 * Holds the form handler class
	 *
	 * @var      object \lsx\business_directory\classes\integrations\woocommerce\Form_Handler()
	 */
	public $form_handler = null;

	/**
	 * Holds the form handler class
	 *
	 * @var      object \lsx\business_directory\classes\integrations\woocommerce\Subscriptions()
	 */
	public $subscriptions = null;

	/**
	 * Holds the Translations class
	 *
	 * @var      object \lsx\business_directory\classes\integrations\woocommerce\Translations()
	 */
	public $translations = null;

	/**
	 * Holds the My Account class
	 *
	 * @var      object \lsx\business_directory\classes\integrations\woocommerce\My_Account()
	 */
	public $my_account = null;

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
		require_once LSX_BD_PATH . '/classes/integrations/woocommerce/class-translations.php';
		$this->translations = woocommerce\Translations::get_instance();

		require_once LSX_BD_PATH . '/classes/integrations/woocommerce/class-my-account.php';
		$this->my_account = woocommerce\My_Account::get_instance();

		require_once LSX_BD_PATH . '/classes/integrations/woocommerce/class-form-handler.php';
		$this->form_handler = woocommerce\Form_Handler::get_instance();

		require_once LSX_BD_PATH . '/classes/integrations/woocommerce/class-subscriptions.php';
		$this->subscriptions = woocommerce\Subscriptions::get_instance();
	}

	/**
	 * Initiator
	 */
	public function init() {
		if ( function_exists( 'WC' ) ) {
			add_filter( 'woocommerce_form_field_text', array( $this, 'replace_image_field' ), 10, 4 );
			add_filter( 'woocommerce_form_field_text', array( $this, 'replace_image_id_field' ), 10, 4 );
		}
	}

	/**
	 * Change the post_thumbnail into a file upload field.
	 *
	 * @param string $field
	 * @param string $key
	 * @param array $args
	 * @param string $value
	 * @return string
	 */
	public function replace_image_field( $field, $key, $args, $value ) {
		if ( in_array( $key, array( 'lsx_bd_thumbnail', 'lsx_bd_banner' ) ) ) {
			$field = '';
		}
		return $field;
	}

	/**
	 * Change the post_thumbnail ID into a hidden field with a thumbnail if set.
	 *
	 * @param string $field
	 * @param string $key
	 * @param array $args
	 * @param string $value
	 * @return string
	 */
	public function replace_image_id_field( $field, $key, $args, $value ) {
		if ( in_array( $key, array( 'lsx_bd__thumbnail_id', 'lsx_bd_banner_id' ) ) ) {
			$field = str_replace( 'woocommerce-input-wrapper', 'woocommerce-file-wrapper', $field );
			$field = str_replace( 'type="text"', 'type="hidden"', $field );

			$image = '';
			if ( ! empty( $value ) && '' !== $value ) {
				$image      .= '<input type="file" class="input-text form-control hidden" name="' . esc_attr( $args['id'] ) . '_upload" id="' . esc_attr( $args['id'] ) . '_upload" placeholder="" value="">';
				$temp_image = wp_get_attachment_image_src( $value, 'lsx-thumbnail-wide' );
				$image_src  = ( strpos( $image[0], 'cover-logo.png' ) === false ) ? $temp_image[0] : '';
				if ( '' !== $image_src ) {
					$image .= '<img src="' . $image_src . '">';
				}
				$image .= '<a class="remove-image" href="#"><i class="fa fa-close"></i> ' . __( 'Remove image', 'lsx-business-directory' ) . '</a>';
			} else {
				$image .= '<input type="file" class="input-text form-control" name="' . esc_attr( $args['id'] ) . '_upload" id="' . esc_attr( $args['id'] ) . '_upload" placeholder="" value="">';
				$image .= '<a class="remove-image hidden" href="#"><i class="fa fa-close"></i> ' . __( 'Remove image', 'lsx-business-directory' ) . '</a>';
			}

			$field = str_replace( '<span class="woocommerce-file-wrapper">', $image . '<span class="woocommerce-file-wrapper">', $field );
		}
		return $field;
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
