<div class="lsx-main-headerwordpress">
		<h2>
		<?php _e( 'LSX', 'lsx' ); ?> <span class="lsx-version"><?php echo LSX_FRAMEWORK_VERSION; ?></span>
		<span style="position: absolute; top: 5px;" id="lsx-save-indicator"><span style="float: none; margin: 10px 0px -5px 10px;" class="spinner"></span></span>
	</h2>
			<div class="updated_notice_box"><?php _e( 'Updated Successfully', 'lsx' ); ?></div>
		<div class="error_notice_box"><?php _e( 'Could not save changes.', 'lsx' ); ?></div>	
		<div class="subsubsub lsx-nav-tabs">
					
					
		</div>		
		<div class="clear"></div>

	<span class="wp-baldrick" id="lsx-field-sync" data-event="refresh" data-target="#lsx-main-canvas" data-callback="lsx_canvas_init" data-type="json" data-request="#lsx-live-config" data-template="#main-ui-template"></span>
</div>
<div class="lsx-sub-headerwordpress">
	<h2 class="lsx-sub-tabs lsx-nav-tabs nav-tab-wrapper">
		<a class="{{#is _current_tab value="#lsx-panel-modules"}}nav-tab-active {{/is}}lsx-nav-tab nav-tab" href="#lsx-panel-modules"><?php _e('Modules', 'lsx') ; ?></a><?php do_action( 'lsx_settings_module_tabs' ); ?>
	</h2>
</div>

<form class="wordpress-main-form has-sub-nav" id="lsx-main-form" action="?page=lsx" method="POST">
	<?php wp_nonce_field( 'lsx', 'lsx-setup' ); ?>
	<input type="hidden" value="lsx" name="id" id="lsx-id">
	<input type="hidden" value="{{_current_tab}}" name="_current_tab" id="lsx-active-tab">

		<div id="lsx-panel-modules" class="lsx-editor-panel" {{#is _current_tab value="#lsx-panel-modules"}}{{else}} style="display:none;" {{/is}}>		
		<h4><?php _e('Module Setup and Config', 'lsx') ; ?> <small class="description"><?php _e('Modules', 'lsx') ; ?></small></h4>
		<?php
		// pull in the general settings template
		include LSX_FRAMEWORK_PATH . 'includes/templates/modules-panel.php';
		?>
	</div>

	<?php do_action( 'lsx_settings_module_templates' ); ?>

	<div class="clear"></div>
	<div class="lsx-footer-bar">
		<button type="submit" class="button button-primary wp-baldrick" data-action="lsx_save_config" data-active-class="none" data-callback="lsx_handle_save" data-load-element="#lsx-save-indicator" data-before="lsx_get_config_object" ><?php _e('Save Changes', 'lsx') ; ?></button>
	</div>	

</form>

{{#unless _current_tab}}
	{{#script}}
		jQuery(function($){
			$('.lsx-nav-tab').first().trigger('click').find('a').trigger('click');
		});
	{{/script}}
{{/unless}}