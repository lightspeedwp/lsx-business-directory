<table class="wp-list-table widefat plugins">
	<thead>
		<tr>
			<th style="" class="manage-column column-cb check-column" id="cb" scope="col"><input class="check-all" name="checked_all" style="margin: 12px 0px -12px 14px;" value="1" type="checkbox" {{#if checked_all}}checked="checked"{{/if}}></th>
			<th style="" class="manage-column column-name" id="name" scope="col"><?php _e( 'Module', 'lsx' ); ?></th>
			<th style="" class="manage-column column-description" id="description" scope="col"><?php _e( 'Description', 'lsx' ); ?></th>
		</tr>
	</thead>

	<tbody id="the-list">
		
		<?php foreach( $modules as $module ){
			if( empty( $module['Internal'] ) ){
				continue;
			}
		?>
		<tr data-slug="advanced-custom-fields" class="{{#if active_module/<?php echo basename( dirname( $module['file'] ) ); ?>}}active{{else}}inactive{{/if}}" id="advanced-custom-fields">
			<th class="check-column" scope="row">
				<input id="module_<?php echo basename( dirname( $module['file'] ) ); ?>" class="wp-baldricks checkboxes-list" data-live-sync="true" data-event="change" data-action="lsx_save_config" data-active-class="none" data-callback="lsx_record_change" data-load-element="#lsx-save-indicator" data-before="lsx_get_config_object" type="checkbox" name="active_module[<?php echo basename( dirname( $module['file'] ) ); ?>]" value="1" {{#if active_module/<?php echo basename( dirname( $module['file'] ) ); ?>}}checked="checked"{{/if}}>
			</th>
			<td class="plugin-title">
				<strong><label for="module_<?php echo basename( dirname( $module['file'] ) ); ?>"><?php echo $module['Name']; ?></label></strong>
				<p class="description"><?php echo basename( dirname( $module['file'] ) ) . '/' . basename( $module['file'] ); ?></p>
			</td>
			<td class="column-description desc">
				<div class="plugin-description">
					<p><?php if( !empty( $module['Description'] ) ){ echo $module['Description']; } ?></p>
				</div>
				<div class="inactive second plugin-version-author-uri">
					<?php if( !empty( $module['Version'] ) ) { ?>Version <?php echo $module['Version']; ?><?php } ?>
					<?php if( !empty( $module['Author'] ) ) { ?> | By <?php if( !empty( $module['AuthorURI'] ) ) { ?><a href="<?php echo $module['AuthorURI']; ?>"><?php } ?><?php echo $module['Author']; ?><?php if( !empty( $module['AuthorURI'] ) ) { ?></a><?php } ?><?php } ?>
					<?php if( !empty( $module['ModuleURI'] ) ) { ?> | <a href="http://lsdev.biz"><?php _e( 'Visit plugin site', 'lsx' ); ?></a><?php } ?>
				</div>
			</td>
		</tr>
		
		<?php } ?>

	</tbody>
</table>