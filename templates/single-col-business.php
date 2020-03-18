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
			<img src="<?php echo esc_url( $thumbnail ); ?>">
		</div>

		<div class="business-content">
			<div class="business-details">
				<div class="row lsx-flex-col">
					<div class="business-title">
						<h4><?php echo esc_attr( $title ); ?></h4>
					</div>

					<?php if ( $industries ) : ?>
					<div class="industry">
						<span>
							<i class="fa fa-th"></i>
							<strong><?php esc_html_e( 'Industry', 'lsx-business-directory' ); ?>: </strong>
							<?php
							$count = 0;
							foreach ( $industries as $industry ) :
								if ( $count > 0 ) :
								?>,<?php
								endif;
								?>
								<a href="/industry/<?php echo esc_attr( $industry['slug'] ); ?>"><?php echo esc_attr( $industry['name'] ); ?></a><?php
								$count++;
							endforeach;
							?>
						</span>
					</div>
					<?php endif; ?>

					<?php if ( $locations ) : ?>
					<div class="location">
						<span>
							<i class="fa fa-globe"></i>
							<strong><?php esc_html_e( 'Location', 'lsx-business-directory' ); ?>: </strong>
							<?php
							$count = 0;
							foreach ( $locations as $location ) :
								if ( $count > 0 ) :
								?>,<?php
								endif;
								?>
								<a href="/location/<?php echo esc_attr( $location['slug'] ); ?>"><?php echo esc_attr( $location['name'] ); ?></a><?php
								$count++;
							endforeach;
							?>
						</span>
					</div>
					<?php endif; ?>

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
			<button class="read-more-link"><a href="<?php esc_url( the_permalink() ); ?>">View Listing<i class="fa fa-long-arrow-right"></i></a></button>
		</div>
	</article>
</div>
