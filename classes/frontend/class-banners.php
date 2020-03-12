<?php
namespace lsx\business_directory\classes\frontend;

/**
 * Banners Frontend Class
 *
 * @package lsx-business-directory
 */
class Banners {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx\business_directory\classes\frontend\Banners()
	 */
	protected static $instance = null;

	/**
	 * Contructor
	 */
	public function __construct() {
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\business_directory\classes\frontend\Banners()    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Outputs the single
	 *
	 * @return void
	 */
	public function single_listing_banner() {
		?>
		<div class="wp-block-cover alignfull has-background-dim" style="background-image:url(https://lsx-business-directory.lsdev.biz/wp-content/uploads/2020/03/taranaki-mountain-scaled.jpg)">
			<div class="wp-block-cover__inner-container">
				<h2 class="has-text-align-center">LightSpeed Apex</h2>
				<p class="has-text-align-center">Designs at its peak</p>
			</div>
		</div>
		<?php
	}
}
