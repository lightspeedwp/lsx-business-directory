<div class="lsx-bd-my-account listings">
	<?php
	if ( is_wc_endpoint_url( 'listings' ) ) {
		?>
		<div class="no-listings">
			<i class="fa fa-list"></i>
			<h3><?php echo esc_attr( 'You have not added any listings yet,', 'lsx-business-directory' ); ?><br /><?php echo esc_attr( 'click here to add your business', 'lsx-business-directory' ); ?></h3>
			<a class="btn btn-secondary" href=""><?php echo esc_attr( 'Add new listing', 'lsx-business-directory' ); ?> <i class="fa fa-plus-circle"></i></a>
		</div>
		<?php
	}
	?>
</div>
