<?php
/**
 * Listing form
 */
defined( 'ABSPATH' ) || exit;

global $listing_active_sub_id;

$listing_args = array(
	'is_listing' => 'yes',
);

$selected_option         = '';
$listing_subscription_id = false;
$listing_subscription    = false;

// Find if we have a listing product ID set.
if ( isset( $_POST['lsx_bd_plan_id'] ) ) { // @codingStandardsIgnoreLine
	$selected_option = sanitize_text_field( $_POST['lsx_bd_plan_id'] ); // @codingStandardsIgnoreLine
}

// Next make sure we have some listing products to choose or switch to.
$listing_products = wc_get_products( $listing_args );
if ( ! empty( $listing_products ) ) {
	$options = array();

	// First lets check for a current subscription, and add that as our top option.
	$listing_id = get_query_var( 'edit-listing', false );
	if ( false !== $listing_id ) {
		$listing_subscription_id = get_post_meta( $listing_id, '_lsx_bd_order_id', true );

		if ( false !== $listing_subscription_id && '' !== $listing_subscription_id ) {
			$listing_subscription = wcs_get_subscription( $listing_subscription_id );
			if ( ! empty( $listing_subscription ) ) {

				// We save the listing ID to a variable. Then we check to see if the subscription is active. if it isnt, then dont add a value forcing the user to purchae a new subscription.
				$listing_option_value = $listing_subscription_id;
				if ( 'active' !== $listing_subscription->get_status() ) {
					$listing_option_value = '';
				} else {
					$selected_option = $listing_subscription_id;
				}

				// Generate a label for the current subscription.
				$label = sprintf(
					/* translators: %s: The subscription info */
					__( 'Subscription <a href="%1$s">#%2$s - %3$s</a> - %5$s - Next Payment %4$s', 'lsx-business-directory' ),
					$listing_subscription->get_view_order_url(),
					$listing_subscription_id,
					$listing_subscription->get_status(),
					$listing_subscription->get_date_to_display( 'next_payment' ),
					$listing_subscription->get_formatted_order_total()
				);
				$options[ $listing_option_value ] = $label;
				$options['dummy_option']          = __( 'Choose a new subscription', 'lsx-business-directory' );
			}
		}
	}

	foreach ( $listing_products as $product ) {
		if ( $product->is_visible() ) {
			if ( in_array( $product->get_type(), array( 'variable', 'variable-subscription' ) ) ) {
				$children = $product->get_visible_children();
				foreach ( $children as $child ) {
					if ( ( false === $listing_subscription ) || ( false !== $listing_subscription && ! $listing_subscription->has_product( $child ) ) ) {
						$child                       = wc_get_product( $child );
						$options[ $child->get_id() ] = get_the_title( $child->get_id() ) . ' - ' . strip_tags( $child->get_price_html() );
					}
				}
			} else {
				if ( ( false === $listing_subscription ) || ( false !== $listing_subscription && ! $listing_subscription->has_product( $child ) ) ) {
					$options[ $product->get_id() ] = $product->get_title() . ' - ' . strip_tags( $product->get_price_html() );
				}
			}
		}
	}
	?>
	<fieldset class="listing-plan-fieldset">
		<?php
		if ( false !== $listing_id ) {
			?>
			<legend><?php esc_attr_e( 'Feature this listing', 'lsx-business-directory' ); ?></legend>
			<?php
		} else {
			?>
			<legend><?php esc_attr_e( 'Choose your plan', 'lsx-business-directory' ); ?></legend>
			<?php
		}
		?>
		<?php
		$field_args = array(
			'type'     => 'radio',
			'label'    => __( 'Available options', 'lsx-business-directory' ),
			'class'    => array( 'listing-options form-row-wide' ),
			'required' => true,
			'options'  => $options,
		);
		lsx_bd_form_field(
			'lsx_bd_plan_id',
			$field_args,
			$selected_option
		);
		$listing_active_sub_id = $selected_option;
		?>
	</fieldset>
	<?php
}
?>
