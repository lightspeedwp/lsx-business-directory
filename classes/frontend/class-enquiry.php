<?php
namespace lsx\business_directory\classes\frontend;

/**
 * Enquiry Frontend Class
 *
 * @package lsx-business-directory
 */
class Enquiry {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx\business_directory\classes\frontend\Enquiry()
	 */
	protected static $instance = null;

	/**
	 * Holds the enquiry form ID.
	 *
	 * @var int
	 */
	public $form_id = false;

	/**
	 * Holds the enquiry form type.
	 *
	 * @var int
	 */
	public $form_type = false;

	/**
	 * Holds the enquiry form model.
	 *
	 * @var object
	 */
	public $form = false;

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function __construct() {
		add_action( 'wp_footer', array( $this, 'output_enquiry_form' ) );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\business_directory\classes\frontend\Enquiry()    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Outputs the enquiry form wrapped in a bootstrap modal.
	 *
	 * @return  void
	 */
	public function output_enquiry_form() {
		$enquiry_form = lsx_bd_get_option( 'single_enquiry_form' );

		if ( $enquiry_form ) {
			$enquiry_form_data = explode( '_', $enquiry_form );
			$this->form_type   = $enquiry_form_data[0];
			$this->form_id     = $enquiry_form_data[1];
		}

		if ( false !== $this->form_id && null !== $this->form_id && 0 !== $this->form_id ) {
			?>
			<div id="enquiry-form-modal" class="modal" tabindex="-1" role="dialog">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h3 class="modal-title"><?php esc_html_e( 'Contact', 'lsx-business-directory' ); ?> <?php the_title(); ?></h3>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<?php
							switch ( $this->form_type ) {
								case 'wp':
									if ( class_exists( 'WPForms' ) ) {
										echo do_shortcode( '[wpforms id="' . $this->form_id . '"]' );
									} else {
										echo 'Enable WPForms plugin.';
									}
									break;
								case 'ninja':
									if ( class_exists( 'Ninja_Forms' ) ) {
										echo do_shortcode( '[ninja_form id="' . $this->form_id . '"]' );
									} else {
										echo 'Enable Ninja Forms plugin.';
									}
									break;
								case 'gravity':
									if ( class_exists( 'GFForms' ) ) {
										// $this->form = \RGFormsModel::get_form( $this->form_id );
										// $form       = \GFForms::get_form( $this->form_id, false, false, false, null, true );
										// echo $form; // @codingStandardsIgnoreLine
										echo do_shortcode( '[gravityform id="' . $this->form_id . '" title="false" description="false" ajax="true"]' );
									} else {
										echo 'Enable Gravity Forms plugin.';
									}
									break;
								case 'caldera':
									if ( class_exists( 'Caldera_Forms_Forms' ) ) {
										echo do_shortcode( '[caldera_form id="' . $this->form_id . '"]' );
									} else {
										echo 'Enable Caldera Forms plugin.';
									}
									break;
								default:
									echo 'NO FORM';
							}
							?>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
	}
}
