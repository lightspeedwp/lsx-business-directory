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

			// These can be removed if an action is run later in the `wp_head`.
			add_filter( 'lsx_bd_single_listing_banner_title', array( $this, 'single_listing_default_banner_title' ), 10, 1 );
			add_filter( 'lsx_bd_single_listing_banner_colour', array( $this, 'single_listing_default_banner_colour' ), 10, 1 );
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
			add_filter( 'lsx_bd_single_business_title', array( $this, 'change_single_business_listing_tag' ), 10, 1 );

			$image    = apply_filters( 'lsx_bd_single_listing_banner_image', get_post_meta( get_the_ID(), 'lsx_bd_banner', true ) );
			$colour   = apply_filters( 'lsx_bd_single_listing_banner_colour', get_post_meta( get_the_ID(), 'lsx_bd_banner_colour', true ) );
			$title    = apply_filters( 'lsx_bd_single_listing_banner_title', get_post_meta( get_the_ID(), 'lsx_bd_banner_title', true ) );
			$subtitle = apply_filters( 'lsx_bd_single_listing_banner_subtitle', get_post_meta( get_the_ID(), 'lsx_bd_banner_subtitle', true ) );

			// Generate the background atts.
			$background_image_attr = '';
			$css_classes           = '';
			if ( '' === $image || false === $image ) {
				$background_image_attr = 'background-color:' . $colour;
			} else {
				$background_image_attr = 'background-image:url(' . $image . ')';
				$css_classes           = apply_filters( 'lsx_bd_single_listing_css_class', 'has-background-img' );
			}
			$background_image_attr = apply_filters( 'lsx_bd_single_listing_style_attr', $background_image_attr );
			?>
			<div class="business-banner lsx-full-width">
				<div class="wp-block-cover alignfull has-background-dim <?php echo esc_html( $css_classes ); ?>" style="<?php echo esc_html( $background_image_attr ); ?>">
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

	/**
	 * Adds the single listing title to the banner if there is none.
	 *
	 * @param string $title
	 * @return void
	 */
	public function single_listing_default_banner_title( $title ) {
		if ( '' === $title || false === $title ) {
			$title = get_the_title();
		}
		return $title;
	}
	/**
	 * Adds the default banner colour if there is none
	 *
	 * @param string $colour
	 * @return void
	 */
	public function single_listing_default_banner_colour( $colour ) {
		if ( false === $colour || '' === $colour ) {
			$colour = '#333';
		}
		return $colour;
	}

	/**
	 * Changes the single business listing title to an H2.
	 *
	 * @param string $title The listing title wrapped in an <h1>.
	 * @return string
	 */
	public function change_single_business_listing_tag( $title ) {
		$title = '<h2 class="entry-title">' . get_the_title() . '</h2>';
		return $title;
	}
}
