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
		
		<?php while ( have_posts() ) : the_post(); ?>

			<?php lsx_entry_before(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<?php lsx_entry_top(); ?>
				
				<div class="entry-header business-header">
					<div class="row">
						<div class="col-md-3">
							<div class="entry-image">
								<img src="http://placehold.it/300x200">
							</div>
						</div>

						<div class="col-md-9">
							<div class="entry-header-content">
								<h1 class="entry-title"><?php the_title(); ?></h1>

								<div class="entry-meta">
									<div class="category">
										<span><strong>Category: </strong>The Business Category</span>
									</div>

									<div class="region">
										<span><strong>Region: </strong>The Business Region</span>
									</div>

									<a href="#" class="btn">Claim this listing</a>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="business-content-right col-md-4">
						<div class="business-contact-form">
							<h3>Contact <?php the_title(); ?></h3>

							<form>
								<input type="text" placeholder="Name">
								<input type="text" placeholder="Email Address">
								<textarea placeholder="Message"></textarea>
								<input type="submit" value="Submit">
							</form>
						</div>

						<div class="business-contact-info">
							<h3>Contact Information</h3>

							<div class="row">
								<div class="col-md-6">
								<div class="business-telephone">
										<span><strong>Telephone: </strong> <a href="tel:+27215555555" target="_blank">+27 (21) 555 5555</a></span>
									</div>

									<div class="business-email">
										<span><strong>Email: </strong> <a href="mailto:info@business.co.za" target="_blank">info@business.co.za</a></span>
									</div>

									<div class="business-website">
										<span><strong>Website: </strong> <a href="www.business.co.za" target="_blank">www.business.co.za</a></span>
									</div>
								</div>

								<div class="col-md-6">
									<div class="business-address">
										<span><strong>Address: </strong> Street Address<br>Suburb<br>City<br>Postal Code</span>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="business-content-left col-md-8">
						<div class="business-description">
							<h2>Description</h2>
							<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Phasellus hendrerit. Pellentesque aliquet nibh nec urna. In nisi neque, aliquet vel, dapibus id, mattis vel, nisi. Sed pretium, ligula sollicitudin laoreet viverra, tortor libero sodales leo, eget blandit nunc tortor eu nibh. Nullam mollis. Ut justo. Suspendisse potenti.

Sed egestas, ante et vulputate volutpat, eros pede semper est, vitae luctus metus libero eu augue. Morbi purus libero, faucibus adipiscing, commodo quis, gravida id, est. Sed lectus. Praesent elementum hendrerit tortor. Sed semper lorem at felis. Vestibulum volutpat, lacus a ultrices sagittis, mi neque euismod dui, eu pulvinar nunc sapien ornare nisl. Phasellus pede arcu, dapibus eu, fermentum et, dapibus sed, urna.</p>
						</div>

						<div class="branches">
							<h2>Branches</h2>

							<div class="branch">
								<h3>The Branch Title</h3>

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

						<div class="promotions">
							<h2>Promotions</h2>

							<div class="promotion">
								<div class="promotion-thumbnail">
									<img src="http://placehold.it/300x250">
								</div>

								<div class="promotion-content">
									<h3>Promotion Title</h3>

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

				<div class="business-map">
					<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3310.4539806727535!2d18.451261315211813!3d-33.929449980639504!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1dcc5da1b2446d25%3A0x351a974567826b35!2s46+Devon+St%2C+Woodstock%2C+Cape+Town%2C+7915!5e0!3m2!1sen!2sza!4v1460629602884" width="1200" height="400" frameborder="0" style="border:0" allowfullscreen></iframe>
				</div>

				<div class="related-businesses">
					<h2>Related Businesses</h2>

					<div class="row">
						<div class="col-md-4">
							<div class="related-business">
								<img src="http://placehold.it/300x250">

								<h3>Business Title</h3>

								<span><strong>Category: </strong>The Business Category</span>
							</div>
						</div>

						<div class="col-md-4">
							<div class="related-business">
								<img src="http://placehold.it/300x250">

								<h3>Business Title</h3>

								<span><strong>Category: </strong>The Business Category</span>
							</div>
						</div>

						<div class="col-md-4">
							<div class="related-business">
								<img src="http://placehold.it/300x250">

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