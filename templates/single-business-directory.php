<?php
/**
 * The Template for displaying all single business items.
 *
 * @package lsx-business-directory
 */

get_header(); ?>

	<div id="primary" class="content-area container <?php echo lsx_main_class(); ?>">

		<?php lsx_content_before(); ?>

		<main id="main" class="site-main" role="main">

		<?php lsx_content_top(); ?>

		<?php while ( have_posts() ) :
			the_post();
			$general_tab_fields = get_post_meta( get_the_ID(), 'general', true );
			$address_tab_field = get_post_meta( get_the_ID(), 'address', true );
			$branches = get_post_meta( get_the_ID(), 'branches', true );
			$info = get_post_meta( get_the_ID(), 'info', true );

			extract( $general_tab_fields );
			extract( $info );
		?>

			<?php lsx_entry_before(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<?php lsx_entry_top(); ?>

				<div class="entry-header business-header">
					<div class="row">
						<div class="col-md-4">
							<div class="entry-image">
								<img src="<?php echo get_thumbnail_wrapped( get_the_ID(), 300, 200 ); ?>">
							</div>
						</div>

						<div class="col-md-8">
							<div class="entry-header-content">
								<h1 class="entry-title"><?php the_title(); ?></h1>

								<div class="entry-meta">
									<div class="category">
										<span><strong>Category: </strong><?php echo get_formatted_taxonomy_str( get_the_ID(), 'lsx-bd-industry' ); ?></span>
									</div>

									<div class="region">
										<span><strong>Region: </strong><?php echo get_formatted_taxonomy_str( get_the_ID(), 'lsx-bd-region' ); ?></span>
									</div>
									<?php if ( $fsb ) : ?>
											<div>
												<span><strong>FSP: </strong><?php echo $fsb; ?></span>
											</div>
									<?php endif; ?>
									<?php if ( $specialist ) : ?>
											<div>
												<span><strong>Specialist: </strong><?php echo $specialist; ?></span>
											</div>
									<?php endif; ?>
									<?php if ( $underwriter ) : ?>
											<div>
												<span><strong>Underwriter: </strong><?php echo $underwriter; ?></span>
											</div>
									<?php endif; ?>
									<?php if ( $underwriters ) : ?>
											<div>
												<span><strong>Underwriter/s: </strong><?php echo $underwriters; ?></span>
											</div>
									<?php endif; ?>

									<?php
										/* Contains HTML for proposed Claim This Listing Button */
										/* lsx_claim_this_listing_button();
										*/
									?>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-4">
						<div class="contact-info business-content-section">
							<h4 class="business-section-title">Contact Information</h4>

							<div class="row">
								<div class="col-md-6">
									<?php if ( $primary_phone ) : ?>
										<div class="telephone">
											<span><strong>Telephone: </strong> <a href="tel:<?php echo str_replace(' ', '', $primary_phone );?>" target="_blank"><?php echo $primary_phone;?></a></span>
										</div>
									<?php endif; ?>

									<?php if ( $primary_email ) : ?>
									<div class="email">
										<span><strong>Email: </strong> <a href="mailto:<?php echo $primary_email; ?>" target="_blank"><?php echo $primary_email; ?></a></span>
									</div>
									<?php endif; ?>

									<?php if ( $website ) : ?>
										<div class="website">
											<span><strong>Website: </strong> <a href="<?php echo $website; ?>" target="_blank"><?php echo $website; ?></a></span>
										</div>
									<?php endif; ?>
								</div>

								<div class="col-md-6">
									<?php
									?>
									<?php if ( !empty( $address_tab_field ) ) : ?>
										<div class="address">
											<span><strong>Address: </strong>
											<?php
												$address_fields = array( 'address', 'address_2', 'address_3', 'address_4', 'state_province', 'country' );
												foreach( $address_fields as $field ) {
													if ( $field_string = $address_tab_field[$field] ) echo $field_string . '<br />';
												}
											?>
											</span>
										</div>
									<?php endif; ?>
								</div>
							</div>
						</div>

						<div class="contact-form business-content-section">
							<h4 class="business-section-title">Contact <?php the_title(); ?></h4>
							<?php
								$form_slug = get_option( 'lsx-business-directory-generic-form' );
								echo Caldera_Forms::render_form( $form_slug );
							?>
						</div>
					</div>

					<div class="col-md-8">
						<div class="business-description business-content-section">
							<h3 class="business-section-title">Description</h3>
							<?php
								the_content();
							?>
						</div>

						<?php if ( !empty( $branches ) && is_array($branches) && isset($branches[0]['branch_name']) && '' !== $branches[0]['branch_name'] ) : ?>
							<div class="branches business-content-section">
								<h3 class="business-section-title">Branches</h3>
								<?php
									foreach( $branches as $branch ) lsx_business_branch( $branch );
								?>
							</div>
						<?php endif; ?>
						<?php
							/* Contains HTML for proposed Promotion Section */
							/* lsx_business_promotion();
							*/
						?>

						<div class="business-map business-content-section">
							<?php
								/*
								* Render the Google Map Div
								* Includes API parameter and calls custom field
								*/
								$address = false;
								$address_fields = array( 'address', 'address_2', 'address_3', 'address_4', 'state_province', 'country' );
								foreach( $address_fields as $field ) {
									if ( isset($address_tab_field[$field]) && '' !== $address_tab_field[$field] ){
										$address[] = $address_tab_field[$field];
									};
								}

								if ( false !== $address ) {
									if ( class_exists( 'Lsx_Options' ) ) {
										$lsx = Lsx_Options::get_single( 'lsx' );
										if ( $api_key = $lsx['gmaps_api_key'] ) {
											echo '<div id="gmap" data-search="' . implode(',',$address) . '" data-api="' . $api_key . '"></div>';
										}
									}
								}

								if ( !empty( $branches ) ) : ?>
									<div id="branch-markers">
										<?php foreach ( $branches as $branch ) : ?>
												<?php if ( $branch[ 'branch_google_maps' ] ) : ?>
													<span class="branch-marker" data-search="<?php echo $branch[ 'branch_google_maps' ]; ?>"></span>
												<?php endif; ?>
										<?php endforeach; ?>
									</div>
								<?php endif; ?>
						</div>
					</div>
				</div>

				<?php
					$terms = wp_get_post_terms( get_the_ID(), 'lsx-bd-industry' );
					$prepped_terms = array();
					foreach( $terms as $term ) {
						array_push( $prepped_terms, $term->term_id );
					}
					$related_business_query = new WP_Query( array(
						'post_type' => 'business-directory',
						'posts_per_page' => 3,
						'tax_query' => array(
							array(
								'taxonomy' => 'lsx-bd-industry',
								'terms'    => $prepped_terms,
							),
						),
					));

					if ( $related_business_query->have_posts() ) : ?>
						<div class="related-businesses">
							<h2>Related Businesses</h2>
							<div class="row">
								<?php while( $related_business_query->have_posts() ) : ?>
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
		/*
		* Adds the Google Maps Javascript Call if a map field was included
		* Variable set to quickly include if script is excluded elsewhere
		*/
		$include_api = false;
		if ( $location && $include_api ) :
			?><script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $api_key; ?>&callback=initMap&libraries=places" async defer></script><?php
		endif;
	?>
<?php get_footer();
