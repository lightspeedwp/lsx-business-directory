<?php
/**
 * The template for the business directory post type archive.
 * 
 * @package		LSX_Business_Directory
 * @subpackage	template
 * @category	archive
 */ 
?>
<?php get_header(); ?>

	<div id="primary" class="content-area col-sm-12">

		<?php lsx_content_before(); ?>

		<main id="main" class="site-main" role="main">

			<?php lsx_content_top(); ?>

			<header class="page-header tours-archive-header">
				<?php if(is_post_type_archive()){ ?>
					<h1 class="page-title"><?php _e('Business Directory','lsx-business-directory'); ?></h1>
				<?php } else { ?>
					<h1 class="page-title"><?php 
					if( is_tax() ){
						$taxo = get_taxonomy( get_queried_object()->taxonomy );
						$post_type = $taxo->object_type;
						$post_type_obj = get_post_type_object( $post_type[0] );
						echo $post_type_obj->labels->name . ': ' .single_term_title( '', false );
					}else{
						the_archive_title();
					}
					?></h1>
				<?php } ?>	

			</header><!-- .entry-header -->

			
			<?php if(is_tax()){ ?>
				<div class="entry-content">		
					<?php the_archive_description(); ?>
				</div>
			<?php } ?>

				<div class="facetwp-template">
				<?php if ( have_posts() ) : $count = 0; ?>
		
					<div class="lsx-business-directory-wrapper">
						<div class="row">

							<div class="col-md-3">
								<div class="business-facets">
									<h3>Refine the Results</h3>
									<?php echo do_shortcode('[facetwp facet="type"]'); ?>
								</div>
							</div>

							<div class="col-md-9">

								<div class="business-listings">

									<div class="business-filters">
										<?php echo do_shortcode('[facetwp sort="true"]'); ?>
										<?php echo do_shortcode('[facetwp per_page="true"]'); ?>
										<?php echo do_shortcode('[facetwp pager="true"]'); ?>
									</div>
				
									<?php while ( have_posts() ) : the_post(); $count++; ?>
										
										<?php 
										/* Commenting out this stuff for now, static loop below

										// load content
										if( function_exists( 'caldera_metaplate_from_file' ) && file_exists( get_stylesheet_directory() . '/templates/metaplate-content-business.html' ) ){
											echo caldera_metaplate_from_file( get_stylesheet_directory() . 'templates/metaplate-content-business.html', get_the_id() );
										}elseif( function_exists( 'caldera_metaplate_from_file' ) && file_exists( LSX_BUSINESS_DIRECTORY_PATH . 'templates/metaplate-content-business.html' ) ){
											echo caldera_metaplate_from_file( LSX_BUSINESS_DIRECTORY_PATH . 'templates/metaplate-content-business.html', get_the_id() );
										}else{
											get_template_part( 'content', 'business-directory' );
										}	
										*/						
										?>

										<article class="business">
											<div class="row">
												<div class="business-thumbnail col-md-4">
													<img src="http://placehold.it/270x200">
												</div>

												<div class="business-content col-md-8">
													<h4 class="business-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>

													<div class="business-details">
														<div class="row">
															<div class="business-meta col-md-6">
																<div class="category">
																	<span><strong>Category: </strong>The Business Category</span>
																</div>

																<div class="region">
																	<span><strong>Region: </strong>The Business Region</span>
																</div>

																<div class="telephone">
																	<span><strong>Telephone: </strong> <a href="tel:+27215555555" target="_blank">+27 (21) 555 5555</a></span>
																</div>

																<div class="email">
																	<span><strong>Email: </strong> <a href="mailto:info@business.co.za" target="_blank">info@business.co.za</a></span>
																</div>

																<div class="website">
																	<span><strong>Website: </strong> <a href="www.business.co.za" target="_blank">www.business.co.za</a></span>
																</div>
															</div>

															<div class="business-excerpt col-md-6">
																<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Phasellus hendrerit. Pellentesque aliquet nibh nec urna. In nisi neque, aliquet vel, dapibus id, mattis vel, nisi. Sed pretium, ligula sollicitudin laoreet ...</p>
															</div>
														</div>
													</div>
												</div>
											</div>
										</article>
				
									<?php endwhile; ?>
				
								</div>
							</div>
						</div>
					</div>
						
				<?php else : ?>

					<section class="no-results not-found">
						<header class="page-header">
							<h1 class="page-title"><?php _e( 'Nothing Found', 'lsx-business-directory' ); ?></h1>
						</header><!-- .page-header -->

						<div class="page-content">
							<?php if ( current_user_can( 'publish_posts' ) ) : ?>

								<p><?php printf( __( 'Ready to publish your first tour? <a href="%1$s">Get started here</a>.', 'lsx-business-directory' ), esc_url( admin_url( 'post-new.php?post_type=business-directory' ) ) ); ?></p>

							<?php else : ?>

								<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'lsx-business-directory' ); ?></p>
								<?php get_search_form(); ?>

							<?php endif; ?>
						</div><!-- .page-content -->
					</section><!-- .no-results -->

				<?php endif; ?>	
				</div>

			<div class="clearfix"></div>

		</main><!-- #main -->

		<?php lsx_content_after(); ?>
		
	</div><!-- #primary -->
	
<?php get_footer(); ?>