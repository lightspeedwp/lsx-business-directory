<?php
/**
 * The Template for displaying all single business items.
 *
 * @package lsx-business-directory
 */

get_header(); ?>

	<div id="primary" class="content-area <?php echo lsx_main_class(); ?>">

		<?php lsx_content_before(); ?>
		
		<main id="main" class="site-main" role="main">

		<?php lsx_content_top(); ?>
		
		<?php while ( have_posts() ) :
			the_post();
			$general_tab_fields = get_post_meta( get_the_ID(), 'general', true );
			$address_tab_field = get_post_meta( get_the_ID(), 'address', true );
			extract( $general_tab_fields );
		?>

			<?php lsx_entry_before(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<?php lsx_entry_top(); ?>
				
				<div class="entry-header business-header">
					<div class="row">
						<div class="col-md-3">
							<div class="entry-image">
								<img src="<?php echo get_thumbnail_wrapped( get_the_ID(), 300, 200 ); ?>">
							</div>
						</div>

						<div class="col-md-9">
							<div class="entry-header-content">
								<h1 class="entry-title"><?php the_title(); ?></h1>

								<div class="entry-meta">
									<div class="category">
										<span><strong>Category: </strong><?php echo get_formatted_taxonomy_str( get_the_ID(), 'industry' ); ?></span>
									</div>

									<div class="region">
										<span><strong>Region: </strong><?php echo get_formatted_taxonomy_str( get_the_ID(), 'region' ); ?></span>
									</div>

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
					<div class="business-content-right col-md-4">
						<div class="contact-form business-content-section">
							<h4 class="business-section-title">Contact <?php the_title(); ?></h4>
							<?php
								$form_slug = get_option( 'lsx-business-directory-generic-form' );
								echo Caldera_Forms::render_form( $form_slug );
							?>
						</div>

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
					</div>

					<div class="business-content-left col-md-8">
						<div class="business-description business-content-section">
							<h3 class="business-section-title">Description</h3>
							<?php
								the_content();
							?>
						</div>

						<div class="branches business-content-section">
							<h3 class="business-section-title">Branches</h3>

							<div class="branch">
								<h4>The Branch Title</h4>

								<div class="branch-content">
									<div class="row">
										<div class="col-md-4">
											<div class="branch-telephone">
												<span><strong>Telephone: </strong> <a href="tel:+27215555555" target="_blank">+27 (21) 555 5555</a></span>
											</div>

											<div class="branch-email">
												<span><strong>Email: </strong> <a href="mailto:info@business.co.za" target="_blank">info@business.co.za</a></span>
											</div>

											<div class="branch-website">
												<span><strong>Website: </strong> <a href="www.business.co.za" target="_blank">www.business.co.za</a></span>
											</div>
										</div>

										<div class="col-md-8">
											<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Phasellus hendrerit. Pellentesque aliquet nibh nec urna. In nisi neque, aliquet vel, dapibus id, mattis vel, nisi. Sed pretium, ligula sollicitudin laoreet viverra, tortor libero sodales leo, eget blandit nunc tortor eu nibh. Nullam mollis. Ut justo. Suspendisse potenti.</p>
										</div>
									</div>
								</div>
							</div>

							<div class="branch">
								<h4>The Branch Title</h4>

								<div class="branch-content">
									<div class="row">
										<div class="col-md-4">
											<div class="branch-telephone">
												<span><strong>Telephone: </strong> <a href="tel:+27215555555" target="_blank">+27 (21) 555 5555</a></span>
											</div>

											<div class="branch-email">
												<span><strong>Email: </strong> <a href="mailto:info@business.co.za" target="_blank">info@business.co.za</a></span>
											</div>

											<div class="branch-website">
												<span><strong>Website: </strong> <a href="www.business.co.za" target="_blank">www.business.co.za</a></span>
											</div>
										</div>

										<div class="col-md-8">
											<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Phasellus hendrerit. Pellentesque aliquet nibh nec urna. In nisi neque, aliquet vel, dapibus id, mattis vel, nisi. Sed pretium, ligula sollicitudin laoreet viverra, tortor libero sodales leo, eget blandit nunc tortor eu nibh. Nullam mollis. Ut justo. Suspendisse potenti.</p>
										</div>
									</div>
								</div>
							</div>

							<div class="branch">
								<h4>The Branch Title</h4>

								<div class="branch-content">
									<div class="row">
										<div class="col-md-4">
											<div class="branch-telephone">
												<span><strong>Telephone: </strong> <a href="tel:+27215555555" target="_blank">+27 (21) 555 5555</a></span>
											</div>

											<div class="branch-email">
												<span><strong>Email: </strong> <a href="mailto:info@business.co.za" target="_blank">info@business.co.za</a></span>
											</div>

											<div class="branch-website">
												<span><strong>Website: </strong> <a href="www.business.co.za" target="_blank">www.business.co.za</a></span>
											</div>
										</div>

										<div class="col-md-8">
											<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Phasellus hendrerit. Pellentesque aliquet nibh nec urna. In nisi neque, aliquet vel, dapibus id, mattis vel, nisi. Sed pretium, ligula sollicitudin laoreet viverra, tortor libero sodales leo, eget blandit nunc tortor eu nibh. Nullam mollis. Ut justo. Suspendisse potenti.</p>
										</div>
									</div>
								</div>
							</div>
						</div>

						<?php
							/* Contains HTML for proposed Promotion Section */
							/* lsx_business_promotion();
							*/
						?>						
					</div>
				</div>

				<div class="business-map business-content-section">
					<?php
						/*
						* Render the Google Map Div
						* Includes API parameter and calls custom field
						*/
						if ( $location = $address_tab_field['location'] ) {
							$api_key = 'AIzaSyAKJbi0J495DFnSkV1EO5Jyh37bCJZjeaM';
							echo '<div id="gmap" data-search="' . $location . '" data-api="' . $api_key . '"></div>';
						}
					?>
				</div>
				<?php
					$terms = wp_get_post_terms( get_the_ID(), 'industry' );
					$prepped_terms = array();
					foreach( $terms as $term ) {
						array_push( $prepped_terms, $term->term_id ); 
					}
					$related_business_query = new WP_Query( array(
						'post_type' => 'business-directory',
						'posts_per_page' => 3,
						'tax_query' => array(
							array(
								'taxonomy' => 'industry',
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
		*/
		if ( $location ) : 
			?><script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $api_key; ?>&callback=initMap&libraries=places" async defer></script><?php
		endif;
	?>
<?php get_footer();