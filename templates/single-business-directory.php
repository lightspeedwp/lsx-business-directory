<?php
/**
 * The Template for displaying all single business items.
 *
 * @package lsx-business-directory
 */

get_header(); ?>

	<div id="primary" class="content-area container <?php echo esc_attr( lsx_main_class() ); ?>">

		<?php lsx_content_before(); ?>

		<main id="main" class="site-main" role="main">

		<?php lsx_content_top(); ?>

		<?php
		while ( have_posts() ) :
			the_post();
			$prefix                      = 'lsx_bd';
			$business_enquiry_form       = lsx_bd_get_option( 'single_enquiry_form' );
			$business_banner             = get_post_meta( get_the_ID(), $prefix . '_banner', true );
			$business_google_maps_search = get_post_meta( get_the_ID(), $prefix . '_address_google_maps_search', true );
			$business_address_1          = get_post_meta( get_the_ID(), $prefix . '_address_street_number', true );
			$business_address_2          = get_post_meta( get_the_ID(), $prefix . '_address_street_name', true );
			$business_address_3          = get_post_meta( get_the_ID(), $prefix . '_address_suburb', true );
			$business_address_4          = get_post_meta( get_the_ID(), $prefix . '_address_city', true );
			$business_postal_code        = get_post_meta( get_the_ID(), $prefix . '_address_postal_code', true );
			$business_country            = get_post_meta( get_the_ID(), $prefix . '_address_country', true );
			$business_province           = get_post_meta( get_the_ID(), $prefix . '_address_province', true );
			$business_business_branches  = get_post_meta( get_the_ID(), $prefix . '_branches', true );
			$business_contact_name       = get_post_meta( get_the_ID(), $prefix . '_contact_person', true );
			$business_primary_email      = get_post_meta( get_the_ID(), $prefix . '_primary_email', true );
			$business_secondary_email    = get_post_meta( get_the_ID(), $prefix . '_secondary_email', true );
			$business_primary_phone      = get_post_meta( get_the_ID(), $prefix . '_primary_phone', true );
			$business_secondary_phone    = get_post_meta( get_the_ID(), $prefix . '_secondary_phone', true );
			$business_fax                = get_post_meta( get_the_ID(), $prefix . '_fax', true );
			$business_website            = get_post_meta( get_the_ID(), $prefix . '_website', true );
			$business_skype              = get_post_meta( get_the_ID(), $prefix . '_skype', true );
			$business_whatsapp           = get_post_meta( get_the_ID(), $prefix . '_whatsapp', true );
			$business_facebook           = get_post_meta( get_the_ID(), $prefix . '_facebook', true );
			$business_twitter            = get_post_meta( get_the_ID(), $prefix . '_twitter', true );
			$business_linkedin           = get_post_meta( get_the_ID(), $prefix . '_linkedin', true );
			$business_instagram          = get_post_meta( get_the_ID(), $prefix . '_instagram', true );
			$business_youtube            = get_post_meta( get_the_ID(), $prefix . '_youtube', true );
			$business_pinterest          = get_post_meta( get_the_ID(), $prefix . '_pinterest', true );
			$address                     = array();

			if ( ! empty( $business_whatsapp ) ) {
				$business_whatsapp = preg_replace( '/[^0-9]/', '', $business_whatsapp );
			}

			if ( $business_address_1 ) {
				$address[] = $business_address_1;
			}

			if ( $business_address_2 ) {
				$address[] = $business_address_2;
			}

			if ( $business_address_3 ) {
				$address[] = $business_address_3;
			}

			if ( $business_address_4 ) {
				$address[] = $business_address_4;
			}

			if ( $business_province ) {
				$address[] = $business_province;
			}

			if ( $business_country ) {
				$address[] = $business_country;
			}
			?>

			<?php lsx_entry_before(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<?php lsx_entry_top(); ?>

				<div class="entry-header business-header">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-4">
							<div class="business-content-left">
								<div class="entry-image">
									<img src="<?php echo esc_url( lsx_bd_get_thumbnail_wrapped( get_the_ID(), 'lsx-thumbnail-wide' ) ); // @codingStandardsIgnoreLine ?>">
								</div>

								<div class="contact-info business-content-section">
									<?php if ( $business_primary_phone ) : ?>
									<div class="telephone lsx-flex-row">
										<div class="col1"><i class="fa fa-phone-square"></i><strong><?php esc_html_e( 'Telephone', 'lsx-business-directory' ); ?>: </strong></div>
										<div class="col2"><a href="tel:<?php echo esc_attr( str_replace( ' ', '', $business_primary_phone ) ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_attr( $business_primary_phone ); ?></a></div>
									</div>
									<?php endif; ?>

									<?php if ( $business_contact_name ) : ?>
									<div class="contact lsx-flex-row">
										<div class="col1"><i class="fa fa-user"></i><strong><?php esc_html_e( 'Contact', 'lsx-business-directory' ); ?>: </strong></div>
										<div class="col2"><?php echo esc_attr( $business_contact_name ); ?></div>
									</div>
									<?php endif; ?>

									<?php if ( $business_primary_email ) : ?>
									<div class="email lsx-flex-row">
										<div class="col1"><i class="fa fa-envelope-square"></i><strong><?php esc_html_e( 'Email', 'lsx-business-directory' ); ?>: </strong></div>
										<div class="col2"><a href="mailto:<?php echo esc_attr( $business_primary_email ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_attr( $business_primary_email ); ?></a></div>
									</div>
									<?php endif; ?>

									<?php if ( $business_website ) : ?>
									<div class="website lsx-flex-row">
										<div class="col1"><i class="fa fa-home"></i><strong><?php esc_html_e( 'Website', 'lsx-business-directory' ); ?>: </strong></div>
										<div class="col2"><a href="<?php echo esc_attr( $business_website ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_attr( $business_website ); ?></a></div>
									</div>
									<?php endif; ?>

									<?php if ( $business_skype ) : ?>
									<div class="skype lsx-flex-row">
										<div class="col1"><i class="fa fa-skype"></i><strong><?php esc_html_e( 'Skype', 'lsx-business-directory' ); ?>: </strong></div>
										<div class="col2 lsx-flex-center"><a target="_blank" href="skype:<?php echo esc_attr( $business_skype ); ?>?call"><i class="fa fa-skype"></i>Call: <?php echo esc_attr( $business_skype ); ?></a></div>
									</div>
									<?php endif; ?>

									<?php if ( $business_whatsapp ) : ?>
									<div class="whatsapp lsx-flex-row">
										<div class="col1"><i class="fa fa-whatsapp"></i><strong><?php esc_html_e( 'Whatsapp', 'lsx-business-directory' ); ?>: </strong></div>
										<div class="col2 lsx-flex-center"><a target="_blank" href="https://wa.me/<?php echo esc_attr( $business_whatsapp ); ?>">Click to Chat</a></div>
									</div>
									<?php endif; ?>

									<?php if ( ! empty( $address ) ) : ?>
									<div class="address lsx-flex-row">
										<div class="col1"><i class="fa fa-map-marker"></i><strong><?php esc_html_e( 'Address', 'lsx-business-directory' ); ?>: </strong></div>
										<div class="col2">
											<?php
											foreach ( $address as $field_string ) {
												echo esc_attr( $field_string ) . '<br />';
											}
											?>
										</div>
									</div>
									<?php endif; ?>
								</div>

								<?php if ( $business_enquiry_form ) : ?>
									<div class="button">
										<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#enquiry-form-modal"><?php esc_html_e( 'Contact', 'lsx-business-directory' ); ?> <?php the_title(); ?></button>
									</div>
								<?php endif; ?>
							</div>
							<?php lsx_bd_listing_map( '<div class="business-content-left map-wrapper"><h3 class="title">' . esc_attr__( 'Map', 'lsx-business-directory' ) . '</h3>', '</div>' ); ?>
						</div>

						<div class="col-md-8 business-content-right">
							<div class="entry-header-content">
								<?php lsx_bd_listing_title(); ?>

								<?php lsx_bd_single_listing_meta(); ?>

								<div class="social-links lsx-flex-row">
									<?php if ( $business_facebook ) : ?>
									<div><a href="<?php echo esc_url( $business_facebook ); ?>" target="_blank"><i class="fa fa-facebook-f"></i></a></div>
									<?php endif; ?>
									<?php if ( $business_twitter ) : ?>
									<div><a href="<?php echo esc_url( $business_twitter ); ?>" target="_blank"><i class="fa fa-twitter"></i></a></div>
									<?php endif; ?>
									<?php if ( $business_linkedin ) : ?>
									<div><a href="<?php echo esc_url( $business_linkedin ); ?>" target="_blank"><i class="fa fa-linkedin"></i></a></div>
									<?php endif; ?>
									<?php if ( $business_instagram ) : ?>
									<div><a href="<?php echo esc_url( $business_instagram ); ?>" target="_blank"><i class="fa fa-instagram"></i></a></div>
									<?php endif; ?>
									<?php if ( $business_youtube ) : ?>
									<div><a href="<?php echo esc_url( $business_youtube ); ?>" target="_blank"><i class="fa fa-youtube-play"></i></a></div>
									<?php endif; ?>
									<?php if ( $business_pinterest ) : ?>
									<div><a href="<?php echo esc_url( $business_pinterest ); ?>" target="_blank"><i class="fa fa-pinterest"></i></a></div>
									<?php endif; ?>
								</div>

								<div class="business-description business-content-section">
									<?php lsx_bd_listing_content(); ?>
								</div>
							</div>
						</div>
					</div>
				</div>

				<?php lsx_entry_bottom(); ?>

			</article><!-- #post-## -->

			<?php lsx_entry_after(); ?>

		<?php endwhile; // end of the loop. ?>

		<?php lsx_content_bottom(); ?>

		</main><!-- #main -->

		<?php lsx_content_after(); ?>

	</div><!-- #primary -->

<?php
get_footer();
