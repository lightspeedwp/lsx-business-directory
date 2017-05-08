<div class="col-md-4">
	<div class="related-business">
		<a href="<?php the_permalink() ?>"><img src="<?php echo get_thumbnail_wrapped( get_the_ID(), 360, 220); ?>"></a>

		<h3><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h3>

		<span><strong>Category: </strong><?php echo get_formatted_taxonomy_str( get_the_ID(), 'lsx-bd-industry' ); ?></span>
	</div>
</div>
