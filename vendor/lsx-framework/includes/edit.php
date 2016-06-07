<?php
$lsx = Lsx_Options::get_single( 'lsx' );
$modules = Lsx::get_modules();


?>
<div class="wrap" id="lsx-main-canvas">
	<span class="wp-baldrick spinner" style="float: none; display: block;" data-target="#lsx-main-canvas" data-callback="lsx_canvas_init" data-type="json" data-request="#lsx-live-config" data-event="click" data-template="#main-ui-template" data-autoload="true"></span>
</div>

<div class="clear"></div>

<input type="hidden" class="clear" autocomplete="off" id="lsx-live-config" style="width:100%;" value="<?php echo esc_attr( json_encode($lsx) ); ?>">

<script type="text/html" id="main-ui-template">
	<?php
	// pull in the join table card template
	include LSX_FRAMEWORK_PATH . 'includes/templates/main-ui.php';
	?>	
</script>





