<?php
/**
 * This is the template for the my account, my listings page.
 */
?>
<div class="lsx-bd-my-account listings">
	<?php
	if ( is_wc_endpoint_url( 'listings' ) ) {

		$args        = array(
			'post_type'      => 'business-directory',
			'post_status'    => array( 'publish', 'pending', 'draft' ),
			'posts_per_page' => -1,
			'nopagin'        => true,
			'author'         => WC()->customer->get_id(),
		);
		$my_listings = new WP_Query( $args );
		lsx_business_directory()->integrations->woocommerce->listings_query = $my_listings;
		if ( $my_listings->have_posts() ) {
			lsx_business_template( 'woocommerce/my-listings' );
		} else {
			lsx_business_template( 'woocommerce/no-listings' );
		}
	} elseif ( is_wc_endpoint_url( 'add-listing' ) || is_wc_endpoint_url( 'edit-listing' ) ) {
		lsx_business_template( 'woocommerce/listing-form' );
	}
	?>
</div>
