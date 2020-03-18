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
		add_filter( 'wpforms_smart_tag_process', array( $this, 'lsx_bd_wpforms_smart_tag_process' ), 10, 2 );
		add_filter( 'caldera_forms_do_magic_tag', array( $this, 'lsx_bd_caldera_magic_tag_process' ), 10, 2 );
		add_filter( 'gform_replace_merge_tags', array( $this, 'lsx_bd_gravity_merge_tag_process' ), 10, 7 );
	}

	/**
	 * Process the WPForms Smart Tag.
	 *
	 * @link   https://wpforms.com/developers/how-to-create-a-custom-smart-tag/
	 *
	 * @param  string $content
	 * @param  string $tag
	 * @return string
	 */
	public function lsx_bd_smart_tag_process( $content, $tag ) {
		// Only run if it is our desired tag.
		if ( 'listing_primary_email' === $tag ) {
			$prefix                = 'lsx_bd';
			$listing_primary_email = get_post_meta( get_the_ID(), $prefix . '_primary_email', true );
			$content               = str_replace( '{listing_primary_email}', $listing_primary_email, $content );
		}

		return $content;
	}

	/**
	 * Process the Caldera Forms Magic Tag.
	 *
	 * @link   https://calderaforms.com/doc/caldera_forms_do_magic_tag/
	 *
	 * @param  string $value
	 * @param  string $magic_tag
	 * @return string
	 */
	public function lsx_bd_caldera_magic_tag_process( $value, $magic_tag ) {
		// make sure we only act on the right magic tag, and when we have a valid entry (positive integer)
		if ( '{listing_primary_email}' === $magic_tag ) {
			$prefix = 'lsx_bd';
			$value  = get_post_meta( get_the_ID(), $prefix . '_primary_email', true );
		}

		return $value;
	}

	/**
	 * Process the Gravity Forms Merge Tag.
	 *
	 * @link   https://docs.gravityforms.com/gform_replace_merge_tags/
	 *
	 * @param   string  $text
	 * @param   object  $form
	 * @param   object  $entry
	 * @param   boolean $url_encode
	 * @param   boolean $esc_html
	 * @param   boolean $nl2br
	 * @param   string  $format
	 *
	 * @return  string
	 */
	public function lsx_bd_gravity_merge_tag_process( $text, $form, $entry, $url_encode, $esc_html, $nl2br, $format ) {
		if ( strpos( $text, '{listing_primary_email}' ) !== false ) {
			$prefix = 'lsx_bd';
			$text   = get_post_meta( get_the_ID(), $prefix . '_primary_email', true );
		}

		return $text;
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
