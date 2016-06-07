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

		<?php //lsx_content_before(); ?>

		<main id="main" class="site-main" role="main">

			<?php //lsx_content_top(); ?>

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

				
					<?php if ( have_posts() ) : ?>
						<div class="lsx-business-directory-wrapper">
							<div class="row">

								<div class="col-md-3">
									<div class="business-facets">
										<h3>Refine the Results</h3>
										<h4>Industry</h4>
										<?php echo do_shortcode('[facetwp facet="industry"]'); ?>
										<h4>Region</h4>
										<?php echo do_shortcode('[facetwp facet="region"]'); ?>
									</div>
								</div>

								<div class="col-md-9">

									<div class="business-listings">

										<div class="business-filters">
											<div class="business-filters-top">
												<?php echo do_shortcode('[facetwp sort="true"]'); ?>
												<?php echo do_shortcode('[facetwp per_page="true"]'); ?>
											</div>
											
											<div class="business-filters-bottom">
												<?php echo do_shortcode('[facetwp facet="alphabet"]'); ?>
												<?php echo do_shortcode('[facetwp pager="true"]'); ?>
											</div>
										</div>
										<div class="facetwp-template">
										<?php while ( have_posts() ) :
												the_post();		
												lsx_business_row();									
											?>										
										<?php endwhile; ?>
										</div>
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
				

			<div class="clearfix"></div>

		</main><!-- #main -->

		<?php //lsx_content_after(); ?>
		
	</div><!-- #primary -->
	
<?php get_footer(); ?>