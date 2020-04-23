<?php
/**
 * This is the template for the my account, my listings page.
 */
?>
<div class="no-listings">
	<i class="fa fa-list"></i>
	<h3><?php echo esc_attr__( 'You have not added any listings yet,', 'lsx-business-directory' ); ?><br /><?php echo esc_attr__( 'click here to add your business', 'lsx-business-directory' ); ?></h3>
	<?php lsx_bd_add_listing_button(); ?>
</div>
<?php
