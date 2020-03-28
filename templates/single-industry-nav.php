<?php
/**
 * Outputs the term for the industry navigation.
 */

global $item_term;
$thumbnail = lsx_bd_get_term_thumbnail( $item_term->term_id, 'thumbnail' );
?>
<div class="col-md-<?php echo esc_attr( $item_term->col_class ); ?>">
	<div class="term-icon">
		<a href="<?php echo esc_attr( get_term_link( $item_term ) ); ?>" ><?php echo wp_kses_post( $thumbnail ); ?></a>
	</div>
	<div class="term-title">
		<a href="<?php echo esc_attr( get_term_link( $item_term ) ); ?>" ><?php echo esc_html( $item_term->name ); ?></a>
	</div>
</div>
