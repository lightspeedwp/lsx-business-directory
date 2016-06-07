<div class="row form-group">
	<label class="control-label col-sm-3" for="{{_id}}"><?php echo $field['label']; ?></label>
	<div class="col-sm-9">
		<?php foreach( $field['options'] as $value=>$label ){ ?>
			<div><input data-live-sync="true" name="{{:name}}[value][<?php echo $value; ?>]" data-field="<?php echo $field_id; ?>" type="checkbox" id="{{_id}}" value="<?php echo esc_attr( $value ); ?>" {{#is value value="<?php echo $value; ?>"}} selected="selected"{{/is}}> <?php echo esc_html( $label ); ?></div>
		<?php } ?>
		<?php if( !empty( $field['other'] ) ){ ?>
		<option value="_other" {{#is value value="_other"}}selected="selected"{{/is}}>Other</option>
		<?php } ?>
		{{#is value value="_other"}}
		if other: <input type="text" name="{{:name}}[other]" value="{{other}}" data-field="<?php echo $field_id; ?>">
		{{/is}}
		<?php if( !empty( $field['description'] ) ){ echo '<p class="description">' . $field['description'] . '</p>'; } ?>
	</div>
</div>
