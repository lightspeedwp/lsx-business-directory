<?php
/**
 * Outputs the term for the industry navigation.
 */

global $item_term;
$icon           = lsx_bd_get_term_thumbnail( $item_term->term_id, 'thumbnail' );
$icon_hover_src = lsx_bd_get_term_thumbnail( $item_term->term_id, 'thumbnail_hover', false, true, true );
?>
<a class="btn-wrap lsx-bd-shadow" data-hover="<?php echo esc_html( $icon_hover_src ); ?>" href="<?php echo esc_attr( get_term_link( $item_term ) ); ?>" >
	<?php echo wp_kses_post( $icon ); ?>
	<?php echo esc_html( $item_term->name ); ?>
</a>
