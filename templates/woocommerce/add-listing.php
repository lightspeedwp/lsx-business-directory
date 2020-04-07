<?php
/**
 * Add Listing form
 */

defined( 'ABSPATH' ) || exit;

do_action( 'lsx_bd_before_add_listing_form' ); ?>

<form class="woocommerce-EditAccountForm edit-account" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> >

	<?php do_action( 'lsx_bd_add_listing_form_start' ); ?>

	<?php
	$sections = \lsx\business_directory\includes\get_listing_form_fields();
	$defaults = array(
		'type'         => '',
		'class'        => array( 'form-row-wide' ),
		'label'        => '',
		'placeholder'  => '',
		'required'     => false,
		'autocomplete' => false,
		'class'        => array(),
		'label_class'  => array(),
		'input_class'  => array(),
	);

	if ( ! empty( $sections ) ) {
		foreach ( $sections as $section_key => $section_values ) {
			$class = str_replace( '_', '-', $section_key );
			?>
			<fieldset class="<?php esc_attr( $class ); ?>-fieldset">
				<legend><?php esc_attr( $section_values['label'] ); ?></legend>
				<?php
				if ( ! empty( $section_values['fields'] ) ) {
					foreach ( $section_values['fields'] as $field_key => $field_values ) {
						$field_values = wp_parse_args( $field_values, $defaults );
						woocommerce_form_field(
							$field_key,
							$field_values,
							''
						);
					}
				}
				?>
			</fieldset>
			<?php
		}
	}
	?>

	<?php do_action( 'lsx_bd_add_listing_form' ); ?>

	<p>
		<?php wp_nonce_field( 'lsx_bd_add_listing', 'lsx-bd-add-listing-nonce' ); ?>
		<button type="submit" class="woocommerce-Button button" name="save_listing_details" value="<?php esc_attr_e( 'Save', 'lsx-business-directory' ); ?>"><?php esc_html_e( 'Save', 'lsx-business-directory' ); ?></button>
		<input type="hidden" name="action" value="save_listing_details" />
	</p>

	<?php do_action( 'lsx_bd_add_listing_form_end' ); ?>
</form>

<?php do_action( 'lsx_bd_after_add_listing_form' ); ?>