	<div class="row form-group">
		<label class="control-label col-sm-3" for="{{_id}}"><?php echo $field['label']; ?></label>
		<div class="col-sm-9">
			<?php
				
				$modal_title = __('Select File', 'lsx');
				if( !empty( $field['modal_title'] ) ){
					$modal_title = $field['modal_title'];
				}
				$modal_button = __('Use File', 'lsx');
				if( !empty( $field['modal_button'] ) ){
					$modal_button = $field['modal_button'];
				}
			
			?>
			<?php if( empty( $field['repeatable'] ) ){ ?>
				{{#if value/selection/thumbnail}}
				<button type="button" class="button image-picker-remover" data-target="#lsx-image-banners-{{_id}}_selection"><span class="dashicons dashicons-no-alt"></span></button>
				{{/if}}
			<?php } ?>
			<div class="image-picker-content">
				<div id="picker_{{_id}}" {{#unless value/selection/thumbnail}}required="required"{{/unless}} 
					class="image-picker-side-bar {{#unless value/selection/thumbnail}}dashicons dashicons-format-image{{/unless}} image-picker-preview"
					data-target="#lsx-image-banners-{{_id}}_selection" 
					data-title="<?php echo $modal_title; ?>"
					data-button="<?php echo $modal_button; ?>" 
					
					<?php if( !empty( $field['library'] ) ){ ?>data-library='<?php echo json_encode( $field['library'] ); ?>'<?php } ?>
		
					style="{{#if value/selection/thumbnail}}background: url('{{value/selection/thumbnail}}') no-repeat scroll {{#unless value/selection/sizes}}center 21px{{else}}center center / contain{{/unless}} #fff;box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);{{/if}}width:100%;font-size: 130px; color: rgb(227, 227, 227); cursor: pointer;height:136px;text-indent:21px;"></div>
				{{#unless value/selection/sizes}}{{#if value/selection/filename}}<div class="imagepicker-filename">{{value/selection/filename}}</div>{{/if}}{{/unless}}
			</div>
			<input id="lsx-image-banners-{{_id}}_selection" data-field="<?php echo $field_id; ?>" name="{{:name}}[value][selection]" required="required" data-live-sync="true" type="hidden" value="{{json value/selection}}">
		
			<?php if( !empty( $field['repeatable'] ) ){ ?>
			{{#unless value/selection}}
				{{#script}}
					jQuery( function($){
						$('#picker_{{_id}}').trigger('click');
					});
				{{/script}}
			{{/unless}}
			<?php } ?>
		</div>
	</div>


