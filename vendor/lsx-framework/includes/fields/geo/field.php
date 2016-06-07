<?php

global $post;

$lsx = Lsx_Options::get_single( 'lsx' );
if( false === $lsx ){
	$lsx = array();
}else{
	if( !empty( $lsx['gmaps_api_key'] ) ){
		$apikey = $lsx['gmaps_api_key'];
		$mapsapi = '//maps.googleapis.com/maps/api/js?key=' . $apikey. '&sensor=false&libraries=places';
		wp_register_script( 'googlemaps', $mapsapi );
		wp_enqueue_script( 'googlemaps' );
	}
}

if ( empty( $apikey ) ){ ?>
	<p>A Google Maps API Key is required.</a></p>
<?php 
return;
}

?>

	<div class="row form-group">
		<label class="control-label col-sm-3" for="{{_id}}"><?php echo $field['label']; ?></label>
		<div class="col-sm-9">
			<div class="row" style="margin-bottom:10px;">
				<div class="col-sm-10">
	            	<input class="form-control" data-field="<?php echo $field_id; ?>" type="text" name="{{:name}}[value]" id="{{_id}}" value="{{value}}" />
				</div>
				<div class="col-sm-2">
					<a id="{{_id}}_button" class="button">Geocode Address</a>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div id="{{_id}}_preview" style="float:right; width:100%; height:180px; border:1px solid #DFDFDF"></div>
				</div>
			</div>

		</div>
	</div>
<?php if( !empty( $field['description'] ) ){ echo '<p class="description">' . $field['description'] . '</p>'; } ?>
{{#script}}
//<script type="text/javascript">
	

	jQuery( function( $ ){
        var map;
        var location;
        var markersArray = [];
        var geocoder = new google.maps.Geocoder();
        
        geoLocate = function( pos, update ){
        	var lookup = {};

        	if( typeof pos === 'object' ){
        		lookup.latLng = pos;
        	}else{
        		lookup.address = pos;
        	}

			geocoder.geocode( lookup, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					
                    var latlng = results[0].geometry.location;
                    map.setCenter(latlng);
                    setMarker(latlng);

					$('#{{_id}}').attr('value', results[0].formatted_address );
					$('#fld-latitude').val( latlng.lat() );
					$('#fld-longitude').val( latlng.lng() );

					if( update ){
						$('#fld-driving_latitude').val( latlng.lat() );
						$('#fld-driving_longitude').val( latlng.lng() );
						var suburb = false;
						for( var i = 0; i < results[0].address_components.length; i++ ){						
							var place = results[0].address_components[i];							

							switch( place.types[0] ){
								case "country":
									$('#fld-country').val( place.long_name );
								break;
								case "administrative_area_level_1":
									$('#fld-province').val( place.long_name );
								break;
								case "administrative_area_level_2":
									//$('#fld-region').val( place.long_name );
								break;
								case "locality":
									$('#fld-city').val( place.long_name );
								break;
								case "sublocality_level_1":
									if( false === suburb ){
										suburb = true;
										$('#fld-suburb').val( place.long_name );
									}
								break;
								case "sublocality_level_2":
									if( false === suburb ){
										suburb = true;
										$('#fld-suburb').val( place.long_name );
									}

								break;
								case "sublocality_level_3":
									if( false === suburb ){
										suburb = true;
										$('#fld-suburb').val( place.long_name );
									}

								break;
								case "sublocality_level_4":
									if( false === suburb ){
										suburb = true;
										$('#fld-suburb').val( place.long_name );
									}

								break;
							}
						}
						//latitude
						//longitude
						//driving_latitude
						//driving_longitude
						//area
					}
				}else{
					alert("Geocode was not successful for the following reason: " + status);
				}
			});
        }

        setMarker = function(latlng) {
            clearMarkers();
            var marker = new google.maps.Marker({
                map: map,
                position: latlng,
                draggable:true
            });
			google.maps.event.addListener(marker, 'mouseup', function( ev ) {

	            geoLocate( this.getPosition() );
			});            
            markersArray.push(marker);
        }

        clearMarkers = function() {
            for (var i = 0; i < markersArray.length; i++) {
                markersArray[i].setMap(null);
            }
            markersArray.length = 0;
        }

        $( document ).on('metaboxTabbed', function(){
        	google.maps.event.trigger(map, "resize");
        	if( markersArray.length === 1 ){
        		map.setCenter( markersArray[0].getPosition() );
        	}
        });

        // Trigger geocode
        $(document).on('click', '#{{_id}}_button', function() {

            var address = $('#{{_id}}').val();
            geoLocate( address, true );
        });

        // Setup the default map
        location = $('#{{_id}}').val();

        map = new google.maps.Map(document.getElementById('{{_id}}_preview'), {
            mapTypeControl: false,
            zoom: 11,
            center: new google.maps.LatLng(-29.9103, 24.44373),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

		google.maps.event.addListener(map, 'click', function( ev ) {
			setMarker( ev.latLng );
			geoLocate( ev.latLng );
		});

		if( location ){
			geoLocate( location );
		}

    });


{{/script}}