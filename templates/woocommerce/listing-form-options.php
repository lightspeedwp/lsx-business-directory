<?php
/**
 * Listing form
 */
defined( 'ABSPATH' ) || exit;

$listing_args = array(
	'is_listing' => 'yes',
);

// Find if we have a listing product ID set.
$listing_product_id = '';
if ( isset( $_POST['lsx_bd_plan_id'] ) ) { // phpcs:ignore
	$listing_product_id = sanitize_text_field( $_POST['lsx_bd_plan_id'] ); // phpcs:ignore
}
$listing_products = wc_get_products( $listing_args );
if ( ! empty( $listing_products ) ) {
	$options = array();
	foreach ( $listing_products as $product ) {
		if ( $product->is_visible() ) {
			if ( in_array( $product->get_type(), array( 'variable', 'variable-subscription' ) ) ) {
				$children = $product->get_visible_children();
				foreach ( $children as $child ) {
					$child                       = wc_get_product( $child );
					$options[ $child->get_id() ] = get_the_title( $child->get_id() ) . ' - ' . $child->get_price_html();
				}
			} else {
				$options[ $product->get_id() ] = $product->get_title() . ' - ' . $product->get_price_html();
			}
		}
	}
	$listing_id = get_query_var( 'edit-listing', false );
	if ( false !== $listing_id ) {
		$listing_subscription_id = get_post_meta( $listing_id, '_lsx_bd_order_id', true );
		if ( false !== $listing_subscription_id && '' !== $listing_subscription_id ) {
			$listing_subscription = wc_get_order( $listing_subscription_id );
			if ( ! empty( $listing_subscription ) ) {
				$label = sprintf(
					/* translators: %s: The subscription info */
					__( 'Subscription <a href="%1$s">#%2$s - %3$s</a>', 'woocommerce' ),
					$listing_subscription->get_view_order_url(),
					$listing_subscription_id,
					$listing_subscription->get_status()
				);
				$options[ $listing_subscription_id ] = $label;
				$listing_product_id                  = $listing_subscription_id;
			}
		}
	}
	?>
	<fieldset class="listing-plan-fieldset">
		<legend><?php esc_attr_e( 'Choose your plan', 'lsx-business-directory' ); ?></legend>
		<?php
		$field_args = array(
			'type'     => 'radio',
			'label'    => __( 'Available options', 'lsx-business-directory' ),
			'class'    => array( 'listing-options form-row-wide' ),
			'required' => true,
			'options'  => $options,
		);
		woocommerce_form_field(
			'lsx_bd_plan_id',
			$field_args,
			$listing_product_id
		);
		?>
	</fieldset>
	<?php
}
?>
