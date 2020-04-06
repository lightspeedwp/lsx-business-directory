<?php
	$prefix        = 'lsx_bd';
	$thumbnail     = lsx_bd_get_thumbnail_wrapped( get_the_ID(), 'lsx-thumbnail-wide' );
	$title         = get_the_title();
	$industries    = lsx_bd_get_formatted_taxonomy_str( get_the_ID(), 'industry', true );
	$locations     = lsx_bd_get_formatted_taxonomy_str( get_the_ID(), 'location', true );
	$primary_phone = get_post_meta( get_the_ID(), $prefix . '_primary_phone', true );
	$primary_email = get_post_meta( get_the_ID(), $prefix . '_primary_email', true );
	$industry_slug = lsx_bd_get_option( 'translations_industry_slug', false );
	$location_slug = lsx_bd_get_option( 'translations_location_slug', false );

	if ( false === $industry_slug ) {
		$industry_slug = 'industry';
	}
	if ( false === $location_slug ) {
		$location_slug = 'location';
	}
?>
<div class="business-row-wrapper col-xs-12 col-sm-12 col-md-12">
	<article class="business row-layout row">
		<div class="entry-layout">
			<div class="business-thumbnail col-xs-12 col-sm-12 col-md-4">
				<a href="<?php esc_url( the_permalink() ); ?>">
					<img src="<?php echo esc_url( $thumbnail ); ?>">
				</a>
			</div>

			<div class="business-content col-xs-12 col-sm-12 col-md-8">
				<div class="business-details">
					<div class="row">
						<h4 class="business-title"><a href="<?php esc_url( the_permalink() ); ?>"><?php the_title(); ?></a></h4>
					</div>

					<div class="row">
						<div class="business-meta lsx-flex-col">
							<?php lsx_bd_archive_listing_meta( '<div class="lsx-flex-row">', '</div>', true, 'col-xs-6 col-sm-6 col-md-6' ); ?>

							<?php lsx_bd_archive_listing_contact_info( '<div class="lsx-flex-row">', '</div>', true, 'col-xs-6 col-sm-6 col-md-6' ); ?>
						</div>

						<div class="business-excerpt lsx-flex-col">
							<?php lsx_bd_archive_listing_excerpt(); ?>
							<span class="read-more-link"><a href="<?php esc_url( the_permalink() ); ?>"><?php esc_html_e( 'Read More', 'lsx-business-directory' ); ?><i class="fa fa-long-arrow-right"></i></a></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</article>
</div>
