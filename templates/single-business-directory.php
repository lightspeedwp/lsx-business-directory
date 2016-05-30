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

									<a href="#" class="btn">Claim this listing</a>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="business-content-right col-md-4">
						<div class="contact-form business-content-section">
							<h4 class="business-section-title">Contact <?php the_title(); ?></h4>

							<form>
								<div class="ginput_container">
									<input type="text" placeholder="Name">
									<input type="text" placeholder="Email Address">
									<textarea placeholder="Message"></textarea>
								</div>
								<input type="submit" class="btn" value="Submit">
							</form>
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

						<div class="promotions business-content-section">
							<h3 class="business-section-title">Promotions</h3>

							<div class="promotion">
								<div class="promotion-thumbnail">
									<img src="http://placehold.it/300x220">
								</div>

								<div class="promotion-content">
									<h4>Promotion Title</h4>

									<div class="promotion-category">
										<span><strong>Category: </strong>The Promotion Category</span>
									</div>

									<div class="promotion-price">
										<strong>R99.99</strong>
									</div>

									<div class="promotion-description">
										<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Phasellus hendrerit. Pellentesque aliquet nibh nec urna. In nisi neque, aliquet vel, dapibus id, mattis vel, nisi. Sed pretium, ligula sollicitudin laoreet viverra, tortor libero sodales leo, eget blandit nunc tortor eu nibh. Nullam mollis. Ut justo. Suspendisse potenti.</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="business-map business-content-section">
					<?php
						if ( $location = $address_tab_field['location'] ) {
							$embed_string = 'https://www.google.com/maps/embed/v1/place?key=' . 'AIzaSyAKJbi0J495DFnSkV1EO5Jyh37bCJZjeaM' . '&q=' . urlencode( $location );
							echo '<iframe src="' . $embed_string . '" width="1200" height="400" frameborder="0" style="border:0" allowfullscreen></iframe>';
						}
					?>
				</div>

				<div class="related-businesses">
					<h2>Related Businesses</h2>

					<div class="row">
						<div class="col-md-4">
							<div class="related-business">
								<img src="http://placehold.it/360x220">

								<h3>Business Title</h3>

								<span><strong>Category: </strong>The Business Category</span>
							</div>
						</div>

						<div class="col-md-4">
							<div class="related-business">
								<img src="http://placehold.it/360x220">

								<h3>Business Title</h3>

								<span><strong>Category: </strong>The Business Category</span>
							</div>
						</div>

						<div class="col-md-4">
							<div class="related-business">
								<img src="http://placehold.it/360x220">

								<h3>Business Title</h3>

								<span><strong>Category: </strong>The Business Category</span>
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

<?php get_footer();