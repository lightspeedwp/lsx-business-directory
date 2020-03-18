<?php
namespace lsx\business_directory\classes\integrations;

/**
 * LSX Ninja Forms Integration class
 *
 * @package lsx-business-directory
 */
class Ninja_Forms {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx\business_directory\classes\integrations\Ninja_Forms()
	 */
	protected static $instance = null;

	/**
	 * Holds the Caldera Forms integration functions.
	 *
	 * @var object \lsx\business_directory\classes\integrations\Ninja_Forms();
	 */
	public $merge_tag;

	/**
	 * Contructor
	 */
	public function __construct() {
		add_filter( 'ninja_forms_merge_tags_other', array( $this, 'register_merge_tag' ), 10, 1 );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\business_directory\classes\integrations\Ninja_Forms()    A single instance of this class.
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
	 * @link   http://developer.ninjaforms.com/codex/merge-tags/
	 *
	 * @return void
	 */
	public function register_merge_tag( $other_tags = array() ) {
		$other_tags['listing_primary_email'] = array(
			'id'       => 'listing_primary_email',
			'tag'      => '{listing_primary_email}',
			'label'    => __( 'Listing Primary Email', 'lsx-business-directory' ),
			'callback' => array( $this, 'tag_process' ),
		);
		return $other_tags;
	}

	/**
	 * Process the Merge Tag.
	 *
	 * @param  string $content
	 * @return string
	 */
	public function tag_process() {
		$prefix                = 'lsx_bd';
		$listing_primary_email = get_post_meta( get_the_ID(), $prefix . '_primary_email', true );
		return $listing_primary_email;
	}
}
