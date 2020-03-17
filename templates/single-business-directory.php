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
			$business_primary_email      = get_post_meta( get_the_ID(), $prefix . '_primary_email', true );
			$business_secondary_email    = get_post_meta( get_the_ID(), $prefix . '_secondary_email', true );
			$business_primary_phone      = get_post_meta( get_the_ID(), $prefix . '_primary_phone', true );
			$business_secondary_phone    = get_post_meta( get_the_ID(), $prefix . '_secondary_phone', true );
			$business_fax                = get_post_meta( get_the_ID(), $prefix . '_fax', true );
			$business_website            = get_post_meta( get_the_ID(), $prefix . '_website', true );
			$industries    				 = get_formatted_taxonomy_str( get_the_ID(), 'industry', true );

			$business_contact_name		 = "James La Guma";
			$business_skype		         = "d0minar";
			$business_whatsapp		     = "0810565236";
			$address                     = array();

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
						<div class="col-xs-12 col-sm-12 col-md-4 business-content-left">
							<div class="entry-image">
								<img src="<?php echo esc_url( get_thumbnail_wrapped( get_the_ID(), 300, 200 ) ); ?>">
							</div>

							<div class="col-xs-12 col-sm-12 col-md-4">
								<div class="contact-info business-content-section">
									<div class="row">
										<?php if ( $business_primary_phone ) : ?>
										<div class="telephone lsx-flex-row">
											<div class="col1"><i class="fas fa-phone-square-alt"></i><strong><?php esc_html_e( 'Telephone', 'lsx-business-directory' ); ?>: </strong></div>
											<div class="col2"><a href="tel:<?php echo esc_attr( str_replace( ' ', '', $business_primary_phone ) ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_attr( $business_primary_phone ); ?></a></div>
										</div>
										<?php endif; ?>

										<?php if ( $business_contact_name ) : ?>
										<div class="contact lsx-flex-row">
											<div class="col1"><i class="fas fa-user"></i><strong><?php esc_html_e( 'Contact', 'lsx-business-directory' ); ?>: </strong></div>
											<div class="col2"><?php echo esc_attr( $business_contact_name ); ?></div>
										</div>
										<?php endif; ?>

										<?php if ( $business_primary_email ) : ?>
										<div class="email lsx-flex-row">
											<div class="col1"><i class="fas fa-envelope-square"></i><strong><?php esc_html_e( 'Email', 'lsx-business-directory' ); ?>: </strong></div>
											<div class="col2"><a href="mailto:<?php echo esc_attr( $business_primary_email ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_attr( $business_primary_email ); ?></a></div>
										</div>
										<?php endif; ?>

										<?php if ( $business_website ) : ?>
										<div class="website lsx-flex-row">
											<div class="col1"><i class="fas fa-home"></i><strong><?php esc_html_e( 'Website', 'lsx-business-directory' ); ?>: </strong></div>
											<div class="col2"><a href="<?php echo esc_attr( $business_website ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_attr( $business_website ); ?></a></div>
										</div>
										<?php endif; ?>

										<?php if ( $business_skype ) : ?>
										<div class="skype lsx-flex-row">
											<div class="col1"><i class="fab fa-skype"></i><strong><?php esc_html_e( 'Skype', 'lsx-business-directory' ); ?>: </strong></div>
											<div class="col2"><?php echo esc_attr( $business_skype ); ?></div>
										</div>
										<?php endif; ?>

										<?php if ( $business_whatsapp ) : ?>
										<div class="whatsapp lsx-flex-row">
											<div class="col1"><i class="fab fa-whatsapp"></i><strong><?php esc_html_e( 'Whatsapp', 'lsx-business-directory' ); ?>: </strong></div>
											<div class="col2"><?php echo esc_attr( $business_whatsapp ); ?></div>
										</div>
										<?php endif; ?>

										<?php if ( ! empty( $address ) ) : ?>
										<div class="address lsx-flex-row">
											<div class="col1"><i class="fas fa-map-marker-alt"></i><strong><?php esc_html_e( 'Address', 'lsx-business-directory' ); ?>: </strong></div>
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
								</div>
							</div>

							<?php if ( $business_enquiry_form ) : ?>
							<div class="button col-xs-12 col-sm-12 col-md-12">
								<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#enquiry-form-modal"><?php esc_html_e( 'Contact', 'lsx-business-directory' ); ?> <?php the_title(); ?></button>
							</div>
							<?php endif; ?>
						</div>

						<div class="col-md-8 business-content-right">
							<div class="entry-header-content">
								<?php lsx_business_listing_title(); ?>

								<div class="entry-meta lsx-flex-row">
									<div class="industry col-xs-6 col-sm-6 col-md-6">
										<span>
											<i class="fas fa-th"></i>
											<strong><?php esc_html_e( 'Industry', 'lsx-business-directory' ); ?>: </strong>
											<?php
											$count = 0;
											foreach ( $industries as $industry ) :
												if ( $count > 0 ) :
													?>,
												<?php endif;
												?>
													<a href="/industry/<?php echo esc_attr( $industry['slug'] ); ?>"><?php echo esc_attr( $industry['name'] ); ?></a>
													<?php
													$count++;
											endforeach;
											?>
										</span>
									</div>

									<div class="location col-xs-6 col-sm-6 col-md-6">
										<span><strong><?php esc_html_e( 'Location', 'lsx-business-directory' ); ?>: </strong><?php echo esc_attr( get_formatted_taxonomy_str( get_the_ID(), 'location' ) ); ?></span>
									</div>

									<?php
									/**
									 * Contains HTML for proposed "Claim This Listing" Button
									 *
									 * lsx_claim_this_listing_button();
									 */
									?>
								</div>

								<div class="business-description business-content-section">
									<?php the_content(); ?>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-8">
						<?php if ( ! empty( $branches ) && is_array( $branches ) && isset( $branches[0]['branch_name'] ) && '' !== $branches[0]['branch_name'] ) : ?>
							<div class="branches business-content-section">
								<h3 class="business-section-title"><?php esc_html_e( 'Branches', 'lsx-business-directory' ); ?></h3>
								<?php
								foreach ( $branches as $branch ) {
									lsx_business_branch( $branch ); // TODO
								}
								?>
							</div>
						<?php endif; ?>
						<?php
						/**
						 * Contains HTML for proposed Promotion Section.
						 *
						 * lsx_business_promotion();
						 */
						?>

						<div class="business-map business-content-section">
							<?php
							/*
							 * Render the Google Map Div
							 * Includes API parameter and calls custom field
							 */
							if ( ! empty( $address ) ) {
								if ( class_exists( 'Lsx_Options' ) ) {
									$lsx         = Lsx_Options::get_single( 'lsx' );
									$api_key     = $lsx['gmaps_api_key'];
									$address_str = implode( ',', $address );

									if ( ! empty( $api_key ) ) {
										echo '<div id="gmap" data-search="' . esc_attr( $address_str ) . '" data-api="' . esc_attr( $api_key ) . '"></div>';
									}
								}
							}

							if ( ! empty( $branches ) ) :
								?>
								<div id="branch-markers">
									<?php foreach ( $branches as $branch ) : ?>
										<?php if ( $branch['branch_google_maps'] ) : ?>
											<span class="branch-marker" data-search="<?php echo esc_attr( $branch['branch_google_maps'] ); ?>"></span>
										<?php endif; ?>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<?php
				$terms         = wp_get_post_terms( get_the_ID(), 'industry' );
				$prepped_terms = array();

				foreach ( $terms as $term ) {
					array_push( $prepped_terms, $term->term_id );
				}

				$related_business_query = new WP_Query(
					array(
						'post_type'      => 'business-directory',
						'posts_per_page' => 3,
						'tax_query'      => array(
							array(
								'taxonomy' => 'industry',
								'terms'    => $prepped_terms,
							),
						),
					)
				);

				if ( $related_business_query->have_posts() ) :
					?>
					<div class="related-businesses">
						<h2><?php esc_html_e( 'Related Businesses', 'lsx-business-directory' ); ?></h2>
						<div class="row">
							<?php while ( $related_business_query->have_posts() ) : ?>
								<?php $related_business_query->the_post(); ?>
								<?php lsx_related_business(); ?>
							<?php endwhile; ?>
						</div>
					</div>
				<?php endif; ?>

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
