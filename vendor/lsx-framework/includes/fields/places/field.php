
	<div class="row form-group">
		<label class="control-label col-sm-3" for="{{_id}}"><?php echo $field['label']; ?></label>
		<div class="col-sm-9">
			<input type="text" value="{{value}}" data-field="<?php echo $field_id; ?>" name="{{:name}}[value]" id="{{_id}}" class="form-control">
			<?php if( !empty( $field['description'] ) ){ echo '<p class="description">' . $field['description'] . '</p>'; } ?>
		</div>
	</div>
{{#script}}
//<script>

var autocomplete{{_id}};
jQuery( function( $ ){
	var field = $('#{{_id}}');

	function initAutocomplete{{_id}}() {
	  // Create the autocomplete object, restricting the search to geographical location types. ( Addresses )
	  autocomplete{{_id}} = new google.maps.places.Autocomplete( field[0] , {
	  	types: ['geocode']});

	  // When the user selects an address from the dropdown, load address and populate bound fields // TODO : BINDING!
	  autocomplete{{_id}}.addListener('place_changed', function(){
			var place = autocomplete{{_id}}.getPlace();
			// Fix Field Binding!
			<?php if( !empty( $field['binding'] ) ){ ?>
			var field_binding = <?php echo json_encode( $field['binding'] ); ?>;
			
			for (var bound_field in field_binding) {
				$('[data-field="' + field_binding[bound_field][0] + '"]').val( '' );
			}
			for (var i = 0; i < place.address_components.length; i++) {
				var addressType = place.address_components[i].types[0];
				if (field_binding[addressType]) {
					var val = place.address_components[i][field_binding[addressType][1]];
					var setfield = $('[data-field="' + field_binding[addressType][0] + '"]'); 
					if( setfield.data('select2') ){
						setfield.select2( "val", val ).trigger('change');
					}else{
						setfield.val( val ).trigger('change');
					}
				}
			}
			<?php } ?>

			field.trigger('change');
	  });
	}
	// init
	initAutocomplete{{_id}}();
});

{{/script}}