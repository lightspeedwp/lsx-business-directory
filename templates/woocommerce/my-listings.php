<?php
/**
 * This is the template for the my account, my listings page.
 */

$is_selling_listings = false;
if ( 'on' === lsx_bd_get_option( 'woocommerce_enable_checkout', false ) ) {
	$is_selling_listings = true;
}
?>
<div class="my-listings">
	<p>
		<?php lsx_bd_add_listing_button(); ?>
	</p>
	<table class="shop_table shop_table_responsive">
		<thead>
			<tr>
				<th><span class="nobr"></th>
				<th><span class="nobr"><?php echo esc_attr__( 'Name', 'lsx-business-directory' ); ?></span></th>
				<th><span class="nobr"><?php echo esc_attr__( 'Date Listed', 'lsx-business-directory' ); ?></span></th>
				<?php if ( $is_selling_listings ) { ?>
					<th><span class="nobr"><?php echo esc_attr__( 'Subscription', 'lsx-business-directory' ); ?></span></th>
				<?php } ?>
				<th><span class="nobr"><?php echo esc_attr__( 'Actions', 'lsx-business-directory' ); ?></span></th>
			</tr>
		</thead>

		<tbody>
			<?php
			while ( lsx_business_directory()->integrations->woocommerce->listings_query->have_posts() ) {
				lsx_business_directory()->integrations->woocommerce->listings_query->the_post();
				?>
				<tr>
					<td data-title="logo" style="width:150px;">
						<img width="126px" src="<?php echo esc_url( lsx_bd_get_thumbnail_wrapped( get_the_ID(), 'lsx-thumbnail-wide' ) ); ?>">
					</td>
					<td data-title="Name">
						<?php the_title(); ?>
					</td>
					<td data-title="Total">
						<?php
							$date = get_post_datetime();
							echo esc_attr( $date->format( 'd/m/Y' ) );
						?>
					</td>
					<?php
					if ( $is_selling_listings ) {
						?>
						<td data-title="Subscription">
							<?php lsx_bd_subscription_details(); ?>
						</td>
						<?php
					}
					?>
					<td data-title="Actions">
						<?php lsx_bd_edit_listing_button(); ?>
						<?php lsx_bd_view_listing_button(); ?>
					</td>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>
</div>
<?php
