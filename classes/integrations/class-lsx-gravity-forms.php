<?php
namespace lsx\business_directory\classes\integrations;

/**
 * Gravity_Forms Integration class
 *
 * @package lsx-business-directory
 */
class Gravity_Forms {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx\business_directory\classes\Gravity_Forms()
	 */
	protected static $instance = null;

	/**
	 * Contructor
	 */
	public function __construct() {
		add_filter( 'gform_custom_merge_tags', array( $this, 'register_merge_tag' ), 10, 4 );
		add_filter( 'gform_replace_merge_tags', array( $this, 'merge_tag_process' ), 10, 7 );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\business_directory\classes\Gravity_Forms()    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Register Merge Tags Call Back.
	 *
	 * @return void
	 */
	public function register_merge_tag( $merge_tags, $form_id, $fields, $element_id ) {
		$merge_tags[] = array(
			'label' => __( 'Listing Primary Email', 'lsx-business-directory' ),
			'tag'   => '{listing_primary_email}',
		);

		return $merge_tags;
	}

	/**
	 * Process the Gravity Forms Merge Tag.
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
	public function merge_tag_process( $text, $form, $entry, $url_encode, $esc_html, $nl2br, $format ) {
		if ( strpos( $text, '{listing_primary_email}' ) !== false ) {
			$prefix = 'lsx_bd';
			$text   = get_post_meta( get_the_ID(), $prefix . '_primary_email', true );
		}
		return $text;
	}
}
