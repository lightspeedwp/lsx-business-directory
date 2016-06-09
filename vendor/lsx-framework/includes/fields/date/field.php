
	<div class="row form-group">
		<label class="control-label col-sm-3" for="{{_id}}"><?php echo $field['label']; ?></label>
		<div class="col-sm-9">
			<input type="text" 
				style="padding-right: 24px;"
				value="{{value}}"
				name="{{:name}}[value]"
				data-field="<?php echo $field_id; ?>"
				data-provide="lsxdatepicker" 
				class="is-lsxdatepicker" 
				id="{{_id}}" 
				data-date-format="<?php if( !empty( $field['format'] ) ){ echo $field['format']; }else{ echo 'yyyy-mm-dd'; } ; ?>"
				<?php if( !empty( $field['start_view'] ) ){ ?>data-date-start-view="<?php echo $field['start_view']; ?>"<?php } ?>
				data-date-autoclose="true"
			><span class="dashicons dashicons-calendar-alt" style="color: rgb(175, 175, 175); margin: 4px -24px;"></span>
			<?php if( !empty( $field['description'] ) ){ echo '<p class="description">' . $field['description'] . '</p>'; } ?>
		</div>
	</div>
