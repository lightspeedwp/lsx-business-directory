<?php
/**
 * The template for the business directory post type archive.
 *
 * @package     LSX_Business_Directory
 * @subpackage  template
 * @category    archive
 */
get_header(); ?>

<?php lsx_content_wrap_before(); ?>

<div id="primary" class="content-area <?php echo esc_attr( lsx_main_class() ); ?>">

	<?php lsx_content_before(); ?>

	<main id="main" class="site-main" role="main">

		<?php lsx_content_top(); ?>

		<?php if ( have_posts() ) : ?>
			<?php
			$layout   = lsx_bd_get_option( 'archive_grid_list' );
			$template = LSX_BD_PATH . '/templates/single-row-business.php';

			if ( false !== $layout && '' !== $layout && 'grid' === $layout ) {
				$template = LSX_BD_PATH . '/templates/single-col-business.php';
			}
			?>
			<div class="post-wrapper archive-plan">
				<?php
				while ( have_posts() ) :
					the_post();
					include $template;
				endwhile;
				?>
			</div>

			<?php lsx_paging_nav(); ?>

		<?php else : ?>

			<?php get_template_part( 'partials/content', 'none' ); ?>

		<?php endif; ?>

		<?php lsx_content_bottom(); ?>

	</main><!-- #main -->

	<?php lsx_content_after(); ?>

</div><!-- #primary -->

<?php lsx_content_wrap_after(); ?>

<?php get_sidebar(); ?>

<?php
get_footer();
