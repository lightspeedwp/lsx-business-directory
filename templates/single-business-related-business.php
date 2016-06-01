<div class="col-md-4">
	<div class="related-business">
		<img src="<?php echo get_thumbnail_wrapped( get_the_ID(), 360, 220); ?>">

		<h3><?php the_title(); ?></h3>

		<span><strong>Category: </strong><?php echo get_formatted_taxonomy_str( get_the_ID(), 'industry' ); ?></span>
	</div>
</div>