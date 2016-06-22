jQuery(document).ready(function($) {

	$('.lsx-map iframe').hover(function() {
		console.log('HOVERING BITCHES');
	    $(document).bind('mousewheel DOMMouseScroll',function(){ 
	        stopWheel(); 
	    });
	}, function() {
	    $(document).unbind('mousewheel DOMMouseScroll');
	});

	if ( typeof( google ) != 'undefined' && typeof( google.maps ) == 'object' ) initMap();
	else {
		var mapTimer = window.setInterval( watchForMap, 500 );
	}

	if(jQuery('#gmap').length){
		initMap();
	}

	function stopWheel(e){
	    if(!e){ /* IE7, IE8, Chrome, Safari */ 
	        e = window.event; 
	        console.log(e);
	    }
	    if(e.preventDefault) { /* Chrome, Safari, Firefox */ 
	        e.preventDefault(); 
	    } 
	    e.returnValue = false; /* IE7, IE8 */
	}
});

function watchForMap( ) {
	if ( typeof( google ) != 'undefined' && typeof( google.maps ) == 'object' ) {
		window.clearInterval( mapTimer );
		initMap();
	} else {
		var mapTimer = window.setTimeout( watchForMap, 500 );
	}
}

function createMarker(place) {
	var placeLoc = place.geometry.location;
	var marker = new google.maps.Marker({
		map: map,
		position: place.geometry.location
	});

	markers.push( marker );

	google.maps.event.addListener(marker, 'click', function() {
		var infowindow = new google.maps.InfoWindow({
			content: place.formatted_address
		});
		infowindow.open(map, this);
	});

	setMapCenterZoom();
}

function initMap() {
	var query = jQuery('#gmap').data( 'search' );
	var key = jQuery('#gmap').data( 'api' );

	map = new google.maps.Map(document.getElementById('gmap'), {     
		zoom: 12,
		scrollwheel: false
    });

    markers = [];

	var service = new google.maps.places.PlacesService(map);
	service.textSearch( {
		query: query
	}, function( results, status ) {
		if (status == google.maps.places.PlacesServiceStatus.OK) {
			var myLocation = results[0].geometry.location;
			map.setCenter( myLocation );
			createMarker(results[0]);
		}
	});

	if ( jQuery( '#branch-markers' ).length ) {
		jQuery( '#branch-markers span' ).each( function() {
			var query = jQuery( this ).data( 'search' );
			service.textSearch( {
				query: query
			}, function( results, status ) {
				if (status == google.maps.places.PlacesServiceStatus.OK) {
					createMarker(results[0]);
				}
			});
		});
	}
}

function setMapCenterZoom() {
	if ( markers.length ) {
		var bounds = new google.maps.LatLngBounds();
		for (var i = 0; i < markers.length; i++) {
			bounds.extend(markers[i].getPosition());
		}

		map.fitBounds(bounds);
	}
}