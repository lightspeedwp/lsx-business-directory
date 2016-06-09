
	<div class="row form-group">
		<label class="control-label col-sm-3" for="{{_id}}"><?php echo $field['label']; ?></label>		
		<div class="col-sm-9">
			<textarea name="{{:name}}[value]" id="{{_id}}" data-field="<?php echo $field_id; ?>" rows="8" class="form-control">{{value}}</textarea>
			<?php if( !empty( $field['description'] ) ){ echo '<p class="description">' . $field['description'] . '</p>'; } ?>
		</div>
	</div>