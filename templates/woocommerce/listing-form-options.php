<?php
/**
 * Listing form
 */
defined( 'ABSPATH' ) || exit;

$listing_form = lsx_bd_get_option( 'woocommerce_checkout_form_id', false );
if ( false !== $listing_form && get_the_ID() === (int) $listing_form ) {
	$listing_args = array(
		'is_listing' => 'yes',
	);
	$listing_products = wc_get_products( $listing_args );
	if ( ! empty( $listing_products ) ) {
		foreach( $listing_products as $product ) {

		}
	}
}

?>
<fieldset class="<?php echo esc_attr( $class ); ?>-fieldset">
	<legend><?php echo esc_attr( $section_values['label'] ); ?></legend>
	<?php
	if ( ! empty( $section_values['fields'] ) ) {
		foreach ( $section_values['fields'] as $field_key => $field_args ) {
			// This adds the handle of the image field.
			$field_args = wp_parse_args( $field_args, $defaults );
			woocommerce_form_field(
				$field_key,
				$field_args,
				$all_values[ $field_key ]
			);
			if ( false !== $listing_id && 'image' === $field_args['type'] ) {
				?>
				<p>
					<img src="<?php echo esc_url( lsx_bd_get_thumbnail_wrapped( $listing_id, 'lsx-thumbnail-wide' ) ); ?>">
				</p>
				<?php
			}s
		}
	}
	?>
</fieldset>
?>
