;( function( $, window, document, undefined ) {

	'use strict';
	
	LSX_BD.maps = Object.create( null );

    /**
     * Initiate the Maps
     */
	LSX_BD.maps.init = function( ) {

        //Init the sliders
        LSX_BD.maps.element = jQuery('.lsx-bd-map');
        if ( 0 <  LSX_BD.maps.element.length ) {
			LSX_BD.maps.element.find('iframe').hover(function() {
				$(document).bind('mousewheel DOMMouseScroll',function(){
					LSX_BD.maps.stopWheel();
				});
			}, function() {
				$(document).unbind('mousewheel DOMMouseScroll');
			});
		
			if ( typeof( google ) != 'undefined' && typeof( google.maps ) == 'object' ) {
				LSX_BD.maps.initMap();
			} else {
				var mapTimer = window.setTimeout( LSX_BD.maps.pollForMap, 500 );
			}
        }
	};

	LSX_BD.maps.stopWheel = function( e ){
		if(!e){ /* IE7, IE8, Chrome, Safari */
			e = window.event;
			console.log(e);
		}
		if(e.preventDefault) { /* Chrome, Safari, Firefox */
			e.preventDefault();
		}
		e.returnValue = false; /* IE7, IE8 */
	}

	LSX_BD.maps.initMap = function() {
		var query           = LSX_BD.maps.element.data( 'search' );
		LSX_BD.maps.markers = [];
		LSX_BD.maps.map     = new google.maps.Map( LSX_BD.maps.element.eq(0)[0], {
			zoom: 8,
			scrollwheel: false
		});
		LSX_BD.maps.element.addClass('map-loading');

		LSX_BD.log('Map Element',false,LSX_BD.maps.element);
		LSX_BD.log('Google Map Element',false,LSX_BD.maps.map);
		LSX_BD.log('Map data-search',query);
		
		var service = new google.maps.places.PlacesService( LSX_BD.maps.map );
		service.textSearch( {
			query: query
		}, function( results, status ) {
			LSX_BD.log('Google Map Results',false,results);
			LSX_BD.log('Google Map Status',false,status);
			if (status == google.maps.places.PlacesServiceStatus.OK) {
				var myLocation = results[0].geometry.location;
				LSX_BD.maps.map.setCenter( myLocation );
				LSX_BD.maps.map.setZoom(8);
				LSX_BD.maps.createMarker(results[0]);
			}
		});
	
		/*if ( jQuery( '#branch-markers' ).length ) {
			jQuery( '#branch-markers span' ).each( function() {
				var query = jQuery( this ).data( 'search' );
				service.textSearch( {
					query: query
				}, function( results, status ) {
					if (status == google.maps.places.PlacesServiceStatus.OK) {
						LSX_BD.maps.createMarker(results[0]);
					}
				});
			});
		}*/
	};
	
	LSX_BD.maps.setMapCenterZoom = function () {
		if ( LSX_BD.maps.markers.length ) {
			var bounds = new google.maps.LatLngBounds();
			for (var i = 0; i < LSX_BD.maps.markers.length; i++) {
				bounds.extend(LSX_BD.maps.markers[i].getPosition());
			}
	
			LSX_BD.maps.map.fitBounds(bounds);
			LSX_BD.maps.map.setZoom(8);
		}
	};

	LSX_BD.maps.pollForMap = function ( ) {
		if ( typeof( google ) != 'undefined' && typeof( google.maps ) == 'object' ) {
			LSX_BD.maps.initMap();
		} else {
			var mapTimer = window.setTimeout( LSX_BD.maps.pollForMap, 500 );
		}
	};
	
	LSX_BD.maps.createMarker = function(place) {
		var marker = new google.maps.Marker({
			map: LSX_BD.maps.map,
			position: place.geometry.location
		});
	
		LSX_BD.maps.markers.push( marker );
	
		google.maps.event.addListener(marker, 'click', function() {
			var infowindow = new google.maps.InfoWindow({
				content: place.formatted_address
			});
			infowindow.open(LSX_BD.maps.map, this);
		});
	
		LSX_BD.maps.setMapCenterZoom();
	};

	LSX_BD.maps.watchMapTriggers = function() {
		LSX_BD.document.on( 'click', '.lsx-bd-map-placeholder, .placeholder-text', function( event ) {
			jQuery.getScript( lsx_bd_maps_params.google_url,function() {
				LSX_BD.maps.init();
			});
		});
	},

    /**
     * On document ready.
     *
     * @package    lsx-member-directory
     * @subpackage scripts
     */
    LSX_BD.document.ready( function() {
		LSX_BD.log('LSX BD Map Params',false,lsx_bd_maps_params);
		if ( '1' === lsx_bd_maps_params.debug ) {
			LSX_BD.maps.init();
		} else {
			LSX_BD.maps.watchMapTriggers();
		}
    } );

} )( jQuery, window, document );
