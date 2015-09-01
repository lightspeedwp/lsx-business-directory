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
			
			<?php if ( have_posts() ) : $count = 0; ?>

	
				<div class="lsx-business-directory-wrapper filter-items-wrapper">
					<div id="lsx-business-directory-scroll-wrapper" class="filter-items-container lsx-business-directory masonry">
	
						<?php while ( have_posts() ) : the_post(); $count++; ?>
							
							<?php 
							// load content
							if( function_exists( 'caldera_metaplate_from_file' ) && file_exists( get_stylesheet_directory() . '/templates/metaplate-content-business.html' ) ){
								echo caldera_metaplate_from_file( get_stylesheet_directory() . 'templates/metaplate-content-business.html', get_the_id() );
							}elseif( function_exists( 'caldera_metaplate_from_file' ) && file_exists( LSX_BUSINESS_DIRECTORY_PATH . 'templates/metaplate-content-business.html' ) ){
								echo caldera_metaplate_from_file( LSX_BUSINESS_DIRECTORY_PATH . 'templates/metaplate-content-business.html', get_the_id() );
							}else{
								get_template_part( 'content', 'business-directory' );
							}							
							?>
	
						<?php endwhile; ?>
	
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

		<?php lsx_content_after(); ?>
		
	</div><!-- #primary -->
	
<?php get_footer(); ?>