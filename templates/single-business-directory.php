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
				
				<?php lsx_business_entry(); ?>
				
				<?php lsx_entry_bottom(); ?>

			</article><!-- #post-## -->

			<?php lsx_entry_after(); ?>

		<?php endwhile; // end of the loop. ?>
		
		<?php lsx_business_tabs(); ?>
		
		<?php lsx_content_bottom(); ?>	

		</main><!-- #main -->			

		<?php lsx_content_after(); ?>
		
	</div><!-- #primary -->

<?php get_footer();