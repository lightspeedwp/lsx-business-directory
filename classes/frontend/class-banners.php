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
		add_action( 'wp_head', array( $this, 'wp_head' ) );
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
	public function wp_head() {
		if ( is_singular( 'business-directory' ) ) {
			add_action( 'lsx_header_wrap_after', array( $this, 'single_listing_banner' ) );
		}
	}

	/**
	 * Outputs the single
	 *
	 * @return void
	 */
	public function single_listing_banner() {
		$disable = get_post_meta( get_the_ID(), 'lsx_bd_banner_disable', true );
		if ( true !== $disable && 'on' !== $disable ) {
			$image  = get_post_meta( get_the_ID(), 'lsx_bd_banner', true );
			$colour = get_post_meta( get_the_ID(), 'lsx_bd_banner_colour', true );
			if ( false === $colour || '' === $colour ) {
				$colour = '#333';
			}
			$title    = get_post_meta( get_the_ID(), 'lsx_bd_banner_title', true );
			$subtitle = get_post_meta( get_the_ID(), 'lsx_bd_banner_subtitle', true );

			$background_image_attr = '';
			if ( '' === $image || false === $image ) {
				$background_image_attr = 'background-color:' . $colour;
			} else {
				$background_image_attr = 'background-image:url(' . $image . ')';
			}
			?>
			<div class="business-banner lsx-full-width">
				<div class="wp-block-cover alignfull has-background-dim" style="<?php echo esc_html( $background_image_attr ); ?>">
					<div class="wp-block-cover__inner-container">
						<?php if ( '' !== $title && false !== $title ) { ?>
							<h1 class="has-text-align-center archive-title"><?php echo esc_html( $title ); ?></h1>
						<?php } ?>

						<?php if ( '' !== $subtitle && false !== $subtitle ) { ?>
							<p class="has-text-align-center"><?php echo esc_html( $subtitle ); ?></p>
						<?php } ?>
					</div>
				</div>
				<div style="height:50px" aria-hidden="true" class="wp-block-spacer"></div>
			</div>
			<?php
		}
	}
}
