<?php
	$prefix        = 'lsx_bd';
	$thumbnail     = get_thumbnail_wrapped( get_the_ID(), 265, 150 );
	$title         = get_the_title();
	$industries    = get_formatted_taxonomy_str( get_the_ID(), 'industry', true );
	$region        = get_formatted_taxonomy_str( get_the_ID(), 'region' );
	$primary_phone = get_post_meta( get_the_ID(), $prefix . '_primary_phone', true );
	$primary_email = get_post_meta( get_the_ID(), $prefix . '_primary_email', true );
?>
<article class="business row-layout">
	<div class="row">
		<div class="business-thumbnail col-md-4">
			<img src="<?php echo esc_url( $thumbnail ); ?>">
		</div>

		<div class="business-content col-md-8">
			<div class="business-details">
				<div class="row">
					<h4 class="business-title"><a href="<?php esc_url( the_permalink() ); ?>"><?php echo esc_attr( $title ); ?></a></h4>
				</div>

				<div class="row">
					<div class="business-meta col-md-12 lsx-flex-col">
						<div class="col-md-12 lsx-flex-row">
							<div class="category col-md-6">
								<span>
									<i class="fas fa-th"></i>
									<strong><?php esc_html_e( 'Industry', 'lsx-business-directory' ); ?>: </strong>
									<?php
									$count = 0;
									foreach ( $industries as $industry ) :
										if ( $count > 0 ) :
											?>,
										<?php endif;
										?>
											<a href="/industry/<?php echo esc_attr( $industry['slug'] ); ?>"><?php echo esc_attr( $industry['name'] ); ?></a>
											<?php
											$count++;
									endforeach;
									?>
								</span>
							</div>

							<div class="region col-md-6">
								<span><i class="fas fa-globe-africa"></i><strong><?php esc_html_e( 'Region', 'lsx-business-directory' ); ?>: </strong><?php echo esc_attr( $region ); ?></span>
							</div>
						</div>

						<div class="col-md-12 lsx-flex-row">
							<?php if ( $primary_phone ) : ?>
								<div class="telephone col-md-6">
									<span><i class="fas fa-phone-square-alt"></i><strong><?php esc_html_e( 'Phone', 'lsx-business-directory' ); ?>: </strong> <a href="tel:<?php echo esc_attr( str_replace( ' ', '', $primary_phone ) ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_attr( $primary_phone ); ?></a></span>
								</div>
							<?php endif; ?>

							<?php if ( $primary_email ) : ?>
							<div class="email col-md-6">
								<span><i class="fas fa-envelope-square"></i><strong><?php esc_html_e( 'Email', 'lsx-business-directory' ); ?>: </strong> <a href="mailto:<?php echo esc_attr( $primary_email ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_attr( $primary_email ); ?></a></span>
							</div>
							<?php endif; ?>
						</div>
					</div>

					<div class="business-excerpt col-md-12 lsx-flex-col">
						<?php echo esc_attr( get_the_excerpt() ); ?>
						<span class="read-more-link"><a href="<?php esc_url( the_permalink() ); ?>"><?php esc_html_e( 'Read More', 'lsx-business-directory' ); ?><i class="fas fa-long-arrow-alt-right"></i></a></span>
					</div>
				</div>
			</div>
		</div>
	</div>
</article>
