<?php
/**
 * The template for the business directory post type archive.
 *
 * @package     LSX_Business_Directory
 * @subpackage  template
 * @category    archive
 */
?>
<?php get_header(); ?>

	<div id="primary" class="content-area <?php echo esc_attr( lsx_main_class() ); ?>">

		<?php lsx_content_before(); ?>

		<main id="main" class="site-main" role="main">

			<?php lsx_content_top(); ?>

			<header class="page-header tours-archive-header">
				<?php if ( is_post_type_archive() ) { ?>
					<h1 class="page-title"><?php esc_html_e( 'Business Directory', 'lsx-business-directory' ); ?></h1>
				<?php } else { ?>
					<h1 class="page-title">
					<?php
					if ( is_tax() ) {
						$taxo          = get_taxonomy( get_queried_object()->taxonomy );
						$post_type     = $taxo->object_type;
						$post_type_obj = get_post_type_object( $post_type[0] );
						echo esc_attr( $post_type_obj->labels->name ) . ': ' . single_term_title( '', false );
					} else {
						the_archive_title();
					}
					?>
					</h1>
				<?php } ?>

			</header><!-- .entry-header -->


			<?php if ( is_tax() ) { ?>
				<div class="entry-content">
					<?php the_archive_description(); ?>
				</div>
			<?php } ?>


					<?php if ( have_posts() ) : ?>
						<?php
						$lsx_search_active = false; // TODO: detect if lsx search is on
						$lsx_layout        = 'row'; // TODO: row or col, get this settings somewhere
						$layout_classes    = 'facetwp-template'; // default one for list

						if ( 'col' === $lsx_layout ) {
							$layout_classes = 'facetwp-template lsx-grid';
						}

						if ( $lsx_search_active ) {
							$filter_classes     = 'col-md-3';
							$search_classes     = 'business-filters';
							$pagination_classes = '';
							$results_classes    = 'col-md-9';
						} else {
							$filter_classes     = 'lsx-hide';
							$search_classes     = 'lsx-hide';
							$pagination_classes = 'lsx-hide';
							$results_classes    = 'col-md-12';
						}

						?>
						<div class="lsx-business-directory-wrapper">
							<div class="row">

								<div class="<?php print esc_arrt( $filter_classes ); ?>">
									<div class="business-facets">
										<h3><?php esc_html_e( 'Refine the Results', 'lsx-business-directory' ); ?></h3>
										<h4><?php esc_html_e( 'Keyword Search', 'lsx-business-directory' ); ?></h4>
										<?php echo do_shortcode( '[facetwp facet="post_search"]' ); ?>
										<h4><?php esc_html_e( 'Industry', 'lsx-business-directory' ); ?></h4>
										<?php echo do_shortcode( '[facetwp facet="industries"]' ); ?>
										<h4><?php esc_html_e( 'Region', 'lsx-business-directory' ); ?></h4>
										<?php echo do_shortcode( '[facetwp facet="regions"]' ); ?>
									</div>
								</div>

								<div class="<?php print esc_arrt( $results_classes ); ?>">

									<div class="business-listings">

										<div class="<?php print esc_arrt( $search_classes ); ?>">
											<div class="business-filters-top">
												<?php echo do_shortcode( '[facetwp sort="true"]' ); ?>
												<?php echo do_shortcode( '[facetwp per_page="true"]' ); ?>
											</div>

											<div class="business-filters-bottom">
												<?php echo do_shortcode( '[facetwp facet="alphabet"]' ); ?>
												<?php echo do_shortcode( '[facetwp pager="true"]' ); ?>
											</div>
										</div>
										<div class="<?php print esc_arrt( $layout_classes ); ?>">
										<?php
										while ( have_posts() ) :
											the_post();

											if ( 'col' === $lsx_layout ) {
												lsx_business_col();
											} else {
												lsx_business_row();
											}
											?>
										<?php endwhile; ?>
										</div>
										<div class="<?php print esc_arrt( $pagination_classes ); ?>"><?php echo do_shortcode( '[facetwp pager="true"]' ); ?></div>
									</div>
								</div>
							</div>
						</div>

					<?php else : ?>

						<section class="no-results not-found">
							<header class="page-header">
								<h1 class="page-title"><?php esc_html_e( 'Nothing Found', 'lsx-business-directory' ); ?></h1>
							</header><!-- .page-header -->

							<div class="page-content">
								<?php if ( current_user_can( 'publish_posts' ) ) : ?>

									<p>
										<?php
										// translators: %1$s is replaced with a link.
										printf( esc_html__( 'Ready to publish your first tour? <a href="%1$s">Get started here</a>.', 'lsx-business-directory' ), esc_url( admin_url( 'post-new.php?post_type=business-directory' ) ) );
										?>
									</p>

								<?php else : ?>

									<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'lsx-business-directory' ); ?></p>
									<?php get_search_form(); ?>

								<?php endif; ?>
							</div><!-- .page-content -->
						</section><!-- .no-results -->

					<?php endif; ?>


			<div class="clearfix"></div>

		</main><!-- #main -->

		<?php // lsx_content_after(); ?>

	</div><!-- #primary -->

<?php get_footer(); ?>
