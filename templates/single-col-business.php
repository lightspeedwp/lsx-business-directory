<?php
	$prefix        = 'lsx_bd';
	$thumbnail     = lsx_bd_get_thumbnail_wrapped( get_the_ID(), 'lsx-thumbnail-wide' );
	$title         = get_the_title();
	$industries    = lsx_bd_get_formatted_taxonomy_str( get_the_ID(), 'industry', true );
	$locations     = lsx_bd_get_formatted_taxonomy_str( get_the_ID(), 'location', true );
	$primary_phone = get_post_meta( get_the_ID(), $prefix . '_primary_phone', true );
	$primary_email = get_post_meta( get_the_ID(), $prefix . '_primary_email', true );
?>
<div class="col-xs-12 col-sm-6 col-md-4">
	<article class="business col-layout">
		<div class="business-thumbnail">
			<a href="<?php esc_url( the_permalink() ); ?>">
				<img src="<?php echo esc_url( $thumbnail ); ?>">
			</a>
		</div>

		<div class="business-content">
			<div class="business-details">
				<div class="lsx-flex-col">
					<div class="business-title">
						<h4><a href="<?php esc_url( the_permalink() ); ?>"><?php echo esc_attr( $title ); ?></a></h4>
					</div>

					<?php lsx_bd_archive_listing_meta(); ?>

					<?php if ( $primary_phone ) : ?>
					<div class="telephone">
						<span><i class="fa fa-phone-square"></i> <strong><?php esc_html_e( 'Phone', 'lsx-business-directory' ); ?>: </strong> <a href="tel:<?php echo esc_attr( str_replace( ' ', '', $primary_phone ) ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_attr( $primary_phone ); ?></a></span>
					</div>
					<?php endif; ?>

					<?php if ( $primary_email ) : ?>
					<div class="email">
						<span><i class="fa fa-envelope-square"></i> <strong><?php esc_html_e( 'Email', 'lsx-business-directory' ); ?>: </strong> <a href="mailto:<?php echo esc_attr( $primary_email ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_attr( $primary_email ); ?></a></span>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<div class="business-button">
			<a class="btn btn-primary" href="<?php esc_url( the_permalink() ); ?>">View Listing<i class="fa fa-long-arrow-right"></i></a>
		</div>
	</article>
</div>
