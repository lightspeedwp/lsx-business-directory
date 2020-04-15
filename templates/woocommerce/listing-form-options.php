<?php
/**
 * Listing form
 */
defined( 'ABSPATH' ) || exit;

$listing_args = array(
	'is_listing' => 'yes',
);
$listing_product_id = '';
if ( isset( $_POST['lsx_bd_plan_id'] ) ) { // phpcs:ignore
	$listing_product_id = sanitize_text_field( $_POST['lsx_bd_plan_id'] ); // phpcs:ignore
}
$listing_products = wc_get_products( $listing_args );
if ( ! empty( $listing_products ) ) {
	$options = array();
	foreach ( $listing_products as $product ) {
		if ( $product->is_visible() ) {
			if ( 'variable' === $product->get_type() ) {
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
