<?php

	$primary_phone = $primary_email = $website = false;

	$general_tab_fields = get_post_meta( get_the_ID(), 'general', true );
	if(!is_array($general_tab_fields)){
		$general_tab_fields = array($general_tab_fields);
	}
	extract( $general_tab_fields );

?>
<article class="business">
	<div class="row">
		<div class="business-thumbnail col-md-4">
			<img src="<?php echo get_thumbnail_wrapped( get_the_ID(), 270, 200 ); ?>">
		</div>

		<div class="business-content col-md-8">
			<h4 class="business-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>

			<div class="business-details">
				<div class="row">
					<div class="business-meta col-md-6">
						<div class="category">
							<span><strong>Category: </strong><?php echo get_formatted_taxonomy_str( get_the_ID(), 'lsx-bd-industry' ); ?></span>
						</div>

						<div class="region">
							<span><strong>Region: </strong><?php echo get_formatted_taxonomy_str( get_the_ID(), 'lsx-bd-region' ); ?></span>
						</div>

						<?php if ( $primary_phone ) : ?>
							<div class="telephone">
								<span><strong>Telephone: </strong> <a href="tel:<?php echo str_replace(' ', '', $primary_phone );?>" target="_blank"><?php echo $primary_phone;?></a></span>
							</div>
						<?php endif; ?>

						<?php if ( $primary_email ) : ?>
						<div class="email">
							<span><strong>Email: </strong> <a href="mailto:<?php echo $primary_email; ?>" target="_blank"><?php echo $primary_email; ?></a></span>
						</div>
						<?php endif; ?>

						<?php if ( $website ) : ?>
							<div class="website">
								<span><strong>Website: </strong> <a href="<?php echo $website; ?>" target="_blank"><?php echo $website; ?></a></span>
							</div>
						<?php endif; ?>
					</div>

					<div class="business-excerpt col-md-6">
						<?php echo get_the_excerpt(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</article>
