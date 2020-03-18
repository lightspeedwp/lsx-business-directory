<?php
namespace lsx\business_directory\classes\integrations;

/**
 * LSX WPForms Integration class
 *
 * @package lsx-business-directory
 */
class WPForms {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx\business_directory\classes\WPForms()
	 */
	protected static $instance = null;

	/**
	 * Contructor
	 */
	public function __construct() {
		add_filter( 'wpforms_smart_tags', array( $this, 'lsx_bd_wpforms_register_smarttag' ) );
		add_filter( 'wpforms_smart_tag_process', array( $this, 'lsx_bd_wpforms_smart_tag_process' ), 10, 2 );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\business_directory\classes\WPForms()    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Register the Smart Tag so it will be available to select in the form builder.
	 *
	 * @link   https://wpforms.com/developers/how-to-create-a-custom-smart-tag/
	 *
	 * @param  array $tags
	 * @return array
	 */
	public function lsx_bd_wpforms_register_smarttag( $tags ) {
		// Key is the tag, item is the tag name.
		$tags['listing_primary_email'] = 'Listing Primary Email';
		return $tags;
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
}
