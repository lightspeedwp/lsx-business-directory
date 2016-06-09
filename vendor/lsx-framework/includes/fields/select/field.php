<div class="row form-group">
	<label class="control-label col-sm-3" for="{{_id}}"><?php echo $field['label']; ?></label>
	<div class="col-sm-9">
	<?php if( !empty( $field['filtered_options'] ) && !empty( $field['filtered_by'] ) ){ ?>		
		{{#find ../<?php echo $field['filtered_by']; ?> "value"}}
			{{#find ../../<?php echo $field_id; ?> "_node_point"}}
				<input type="text" data-field="<?php echo $field_id; ?>" class="form-control" id="{{_id}}_backup" name="{{:name}}[value]" value="{{value}}" data-live-sync="true">
			{{/find}}
			<?php foreach( $field['filtered_options'] as $required_value=>$required_options ){
				$field['options'] = $required_options;
				 ; ?>
			{{#is value value="<?php echo $required_value; ?>"}}
				{{#find ../../../<?php echo $field_id; ?> "_node_point"}}
				<select value="{{value}}" data-live-sync="true" data-field="<?php echo $field_id; ?>" name="{{:name}}[value]" id="{{_id}}" class="form-control">
					<option></option>
					<?php
					foreach( $field['options'] as $value=>$label ){ 

						?>
						<option value="<?php echo esc_attr( $value ); ?>" {{#is value value="<?php echo $value; ?>"}} selected="selected"{{/is}}><?php echo esc_html( $label ); ?></option>
					<?php } ?>
					<?php if( !empty( $field['other'] ) ){ ?>
					<option value="_other" {{#is value value="_other"}}selected="selected"{{/is}}>Other</option>
					<?php
					}
					?>
				</select>
				{{/find}}
				{{#find ../../../<?php echo $field_id; ?> "_node_point"}}
					{{#script}}
						jQuery('#{{_id}}_backup').hide();
					{{/script}}
				{{/find}}
			{{/is}}				
		<?php  } ?>
		{{else}}
			
			<input type="text" data-field="<?php echo $field_id; ?>" class="form-control" name="{{:name}}[value]" value="{{value}}" data-live-sync="true">
			
		{{/find}}




		<?php
		}else{
				?>
				<select value="{{value}}" data-live-sync="true" data-field="<?php echo $field_id; ?>" name="{{:name}}[value]" id="{{_id}}" class="form-control">
					<option></option>
					<?php
					foreach( $field['options'] as $value=>$label ){ ?>
						<option value="<?php echo esc_attr( $value ); ?>" {{#is value value="<?php echo $value; ?>"}} selected="selected"{{/is}}><?php echo esc_html( $label ); ?></option>
					<?php } ?>
					<?php if( !empty( $field['other'] ) ){ ?>
					<option value="_other" {{#is value value="_other"}}selected="selected"{{/is}}>Other</option>
					<?php
					}
					?>
				</select>

				<?php
			}
		 ?>

		<?php if( !empty( $field['filtered_options'] ) && !empty( $field['filtered_by'] ) ){ ?>

		<?php } ?>
		{{#is value value="_other"}}
		if other: <input type="text" data-field="<?php echo $field_id; ?>" name="{{:name}}[other]" value="{{other}}">
		{{/is}}
		<?php if( !empty( $field['description'] ) ){ echo '<p class="description">' . $field['description'] . '</p>'; } ?>
	</div>
</div>
{{#script}}
	jQuery( function($){
		$("#{{_id}}").select2();
	});
{{/script}}