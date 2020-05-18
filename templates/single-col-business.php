<div class="col-xs-12 col-sm-6 col-md-4">
	<article class="business col-layout">
		<div class="business-thumbnail">
			<a href="<?php esc_url( the_permalink() ); ?>">
				<img src="<?php echo esc_url( lsx_bd_get_thumbnail_wrapped( get_the_ID(), 'lsx-thumbnail-wide' ) ); ?>">
			</a>
		</div>

		<div class="business-content">
			<div class="business-details">
				<div class="lsx-flex-col">
					<div class="business-title">
						<h4><a href="<?php esc_url( the_permalink() ); ?>"><?php the_title(); ?></a></h4>
					</div>

					<?php lsx_bd_archive_listing_meta(); ?>

					<?php lsx_bd_archive_listing_contact_info(); ?>

					<?php lsx_bd_archive_listing_excerpt( '<div class="description">', '</div>' ); ?>
				</div>
			</div>
		</div>

		<div class="business-button">
			<a class="btn btn-primary" href="<?php esc_url( the_permalink() ); ?>"><?php esc_html_e( 'View Listing', 'lsx-business-directory' ); ?><i class="fa fa-long-arrow-right"></i></a>
		</div>
	</article>
</div>
