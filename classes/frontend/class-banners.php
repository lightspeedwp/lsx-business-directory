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
			$args = array(
				'image'    => get_post_meta( get_the_ID(), 'lsx_bd_banner', true ),
				'colour'   => get_post_meta( get_the_ID(), 'lsx_bd_banner_colour', true ),
				'title'    => get_post_meta( get_the_ID(), 'lsx_bd_banner_title', true ),
				'subtitle' => get_post_meta( get_the_ID(), 'lsx_bd_banner_subtitle', true ),
			);
			$this->do_banner( $args );
		}
	}

	/**
	 * Outputs the banners based on the arguments.
	 *
	 * @param array $args The parameters for the banner
	 * @return void
	 */
	public function do_banner( $args = array() ) {
		$defaults = array(
			'image'    => false,
			'colour'   => '#333',
			'title'    => '',
			'subtitle' => '',
		);
		$args     = wp_parse_args( $args, $defaults );
		// Generate the banner style attributes.
		$background_image_attr = '';
		if ( '' === $args['image'] || false === $args['image'] ) {
			$background_image_attr = 'background-color:' . $args['colour'];
		} else {
			$background_image_attr = 'background-image:url(' . $args['image'] . ')';
		}
		?>
		<div class="business-banner lsx-full-width">
			<div class="wp-block-cover alignfull has-background-dim" style="<?php echo esc_html( $background_image_attr ); ?>">
				<div class="wp-block-cover__inner-container">
					<?php if ( '' !== $args['title'] && false !== $args['title'] ) { ?>
						<h1 class="has-text-align-center archive-title"><?php echo esc_html( $args['title'] ); ?></h1>
					<?php } ?>

					<?php if ( '' !== $args['subtitle'] && false !== $args['subtitle'] ) { ?>
						<p class="has-text-align-center"><?php echo esc_html( $args['subtitle'] ); ?></p>
					<?php } ?>
				</div>
			</div>
			<div style="height:50px" aria-hidden="true" class="wp-block-spacer"></div>
		</div>
		<?php
	}
}
