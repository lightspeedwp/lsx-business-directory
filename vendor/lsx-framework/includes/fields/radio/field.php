<div class="row form-group">
	<label class="control-label col-sm-3" for="{{_id}}"><?php echo $field['label']; ?></label>
	<div class="col-sm-9">
	
		<?php
		foreach( $field['options'] as $value=>$label ){ ?>
			<?php echo esc_html( $label ); ?><input style="width:15px" type="radio" name="{{:name}}[value]" class="form-control" value="<?php echo esc_attr( $value ); ?>" {{#is value value="<?php echo $value; ?>"}} checked="checked"{{/is}} />
		<?php } ?>

		<?php if( !empty( $field['description'] ) ){ echo '<p class="description">' . $field['description'] . '</p>'; } ?>
	</div>
</div>