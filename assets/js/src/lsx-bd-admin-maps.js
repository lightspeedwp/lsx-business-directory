/**
 * LSX Starter Plugin scripts (admin).
 *
 * @package lsx-business-directory
 */

var LSX_BD_GMAPS = Object.create( null );

;( function( $, window, document, undefined ) {

	'use strict';

    LSX_BD_GMAPS.document = $( document );

	var maps = [];

    LSX_BD_GMAPS.init = function() {
        if ( 0 < $( '.cmb-type-google-map' ).length ) {
			$( '.cmb-type-google-map' ).each( function() {
				LSX_BD_GMAPS.initializeMap( $( this ) );
			});
			LSX_BD_GMAPS.watchRowActions();
        }
    };

	LSX_BD_GMAPS.initializeMap = function ( mapInstance ) {
		var searchInput = mapInstance.find( '.google-map-search' );
		var mapCanvas = mapInstance.find( '.google-map' );
		var latitude = mapInstance.find( '.google-map-latitude' );
		var longitude = mapInstance.find( '.google-map-longitude' );
		var latLng = new google.maps.LatLng( -33.9152193,18.3751927 );
		var zoom = 5;

		console.log(searchInput);

		// If we have saved values, let's set the position and zoom level
		if ( latitude.val().length > 0 && longitude.val().length > 0 ) {
			latLng = new google.maps.LatLng( latitude.val(), longitude.val() );
			zoom = 17;
		}

		// Map
		var mapOptions = {
			center: latLng,
			zoom: zoom
		};
		var map = new google.maps.Map( mapCanvas[0], mapOptions );

		// Marker
		var markerOptions = {
			map: map,
			draggable: true,
			title: 'Drag to set the exact location'
		};
		var marker = new google.maps.Marker( markerOptions );

		if ( latitude.val().length > 0 && longitude.val().length > 0 ) {
			marker.setPosition( latLng );
		}

		// Search
		var autocomplete = new google.maps.places.Autocomplete( searchInput[0] );
		autocomplete.bindTo( 'bounds', map );

		google.maps.event.addListener( autocomplete, 'place_changed', function() {
			var place = autocomplete.getPlace();
			if ( ! place.geometry ) {
				return;
			}

			if ( place.geometry.viewport ) {
				map.fitBounds( place.geometry.viewport );
			} else {
				map.setCenter( place.geometry.location );
				map.setZoom( 17 );
			}

			marker.setPosition( place.geometry.location );

			latitude.val( place.geometry.location.lat() );
			longitude.val( place.geometry.location.lng() );
		});

		$( searchInput ).keypress( function( event ) {
			if ( 13 === event.keyCode ) {
				event.preventDefault();
			}
		});

		// Allow marker to be repositioned
		google.maps.event.addListener( marker, 'drag', function() {
			latitude.val( marker.getPosition().lat() );
			longitude.val( marker.getPosition().lng() );
		});

		maps.push( map );
	}

	// Resize map when meta box is opened
	if ( typeof postboxes !== 'undefined' ) {
		postboxes.pbshow = function () {
			var arrayLength = maps.length;
			for (var i = 0; i < arrayLength; i++) {
				var mapCenter = maps[i].getCenter();
				google.maps.event.trigger(maps[i], 'resize');
				maps[i].setCenter(mapCenter);
			}
		};
	}

	LSX_BD_GMAPS.watchRowActions = function() {
		// When a new row is added, reinitialize Google Maps
		$( '.cmb-repeatable-group' ).on( 'cmb2_add_row', function( event, newRow ) {
			var groupWrap = $( newRow ).closest( '.cmb-repeatable-group' );
			groupWrap.find( '.cmb-type-google-map' ).each( function() {
				LSX_BD_GMAPS.initializeMap( $( this ) );
			});
		});
	};

	/**
     * On document ready.
     *
     * @package    lsx-member-directory
     * @subpackage scripts
     */
    LSX_BD_GMAPS.document.ready( function() {
        LSX_BD_GMAPS.init();
    } );

} )( jQuery, window, document );
