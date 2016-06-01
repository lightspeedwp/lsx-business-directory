jQuery(document).ready(function($) {

	/*$('.lsx-map iframe').hover(function() {
		console.log('HOVERING BITCHES');
	    $(document).bind('mousewheel DOMMouseScroll',function(){ 
	        stopWheel(); 
	    });
	}, function() {
	    $(document).unbind('mousewheel DOMMouseScroll');
	});
	*/


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

function createMarker(place) {
	var placeLoc = place.geometry.location;
	var marker = new google.maps.Marker({
	map: map,
		position: place.geometry.location
	});

	google.maps.event.addListener(marker, 'click', function() {
		infowindow.setContent(place.name);
		infowindow.open(map, this);
	});
}

function initMap() {
	var query = jQuery('#gmap').data( 'search' );
	var key = jQuery('#gmap').data( 'api' );

	map = new google.maps.Map(document.getElementById('gmap'), {     
		zoom: 12,
		scrollwheel: false
    });

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
}