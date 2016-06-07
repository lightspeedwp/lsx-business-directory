var map;
var infowindow;

function initialize() {
    var latlng = {lat: -33.92945, lng: 18.45345};
    
    var myOptions = {
        zoom: 16,
        center: latlng,
        scrollwheel: false,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    
    
    document.getElementById("lsx-map").style.visibility = 'hidden';
    map = new google.maps.Map(document.getElementById("lsx-map"), myOptions);
    var text_query = document.getElementById("lsx-map").getAttribute("data-address");
    
    infowindow = new google.maps.InfoWindow();
    var service = new google.maps.places.PlacesService(map);
    service.textSearch({
      location: latlng,
      radius: '5000',
      query: text_query
    }, lsx_map_places_callback);    
    
}
google.maps.event.addDomListener(window, "load", initialize);

function lsx_map_places_callback(results, status) {
	
  if (status === google.maps.places.PlacesServiceStatus.OK) {
    for (var i = 0; i < results.length; i++) {
    	map.setCenter(results[i].geometry.location);	
    	lsx_map_createMarker(results[i]);
    }
  }
  document.getElementById("lsx-map").style.visibility = 'inherit';
}

function lsx_map_createMarker(place) {
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