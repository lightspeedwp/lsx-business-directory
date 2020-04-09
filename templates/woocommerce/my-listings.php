<?php
/**
 * This is the template for the my account, my listings page.
 */
?>
<div class="my-listings">
	<p>
		<?php lsx_bd_add_listing_button(); ?>
	</p>
	<table class="shop_table shop_table_responsive">
		<thead>
			<tr>
				<th><span class="nobr"></th>
				<th><span class="nobr"><?php echo esc_attr( 'Name', 'lsx-business-directory' ); ?></span></th>
				<th><span class="nobr"><?php echo esc_attr( 'Date Listed', 'lsx-business-directory' ); ?></span></th>
				<th><span class="nobr"><?php echo esc_attr( 'Actions', 'lsx-business-directory' ); ?></span></th>
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
					<td data-title="Actions">
						<a href="#" class="btn tertiary-border-btn"><?php echo esc_attr( 'Edit', 'lsx-business-directory' ); ?></a>
						<a href="<?php the_permalink(); ?>" class="btn btn-primary"><?php echo esc_attr( 'View', 'lsx-business-directory' ); ?></a>
					</td>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>
</div>
<?php
