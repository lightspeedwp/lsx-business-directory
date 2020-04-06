var LSX_BD = Object.create( null );

;( function( $, window, document, undefined ) {

    'use strict';

    console.log(window.innerWidth);

    LSX_BD.document = $(document);

    //Holds the slider function
	LSX_BD.sliders = Object.create( null );

    /**
     * Start the JS Class
     */
    LSX_BD.init = function() {

        //Init the sliders
        LSX_BD.sliders.element = jQuery('.lsx-business-directory-slider');
        if ( 0 <  LSX_BD.sliders.element.length ) {
            LSX_BD.sliders.init();
		}

        //Init the sliders
        LSX_BD.maps.element = jQuery('.lsx-map');
        if ( 0 <  LSX_BD.maps.element.length ) {
            LSX_BD.maps.init();
        }
    };

    /**
     * Initiate the Sliders
     */
    LSX_BD.sliders.init = function( ) {
        LSX_BD.sliders.element.each( function() {
			var slidesToScroll = 3;
			var slidesToShow = 3;
			var overrides = $(this).attr( 'data-lsx-slick' );
			if ( undefined !== overrides && false !== overrides ) {
				overrides = jQuery.parseJSON( overrides );
				if ( undefined !== overrides.slidesToShow && '' !== overrides.slidesToShow ) {
					slidesToShow = overrides.slidesToShow;
				}
				if ( undefined !== overrides.slidesToScroll && '' !== overrides.slidesToScroll ) {
					slidesToScroll = overrides.slidesToScroll;
				}
			}
            $(this).slick({
                dots: true,
                infinite: false,
                speed: 300,
                slidesToShow: slidesToShow,
                slidesToScroll: slidesToScroll,
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: slidesToShow,
                            slidesToScroll: slidesToScroll,
                            infinite: true,
                            dots: true
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                ]
            });
        } );
	};

    /**
     * Initiate the Maps
     */
	LSX_BD.maps.init = function( ) {
		LSX_BD.maps.element.find('iframe').hover(function() {
			$(document).bind('mousewheel DOMMouseScroll',function(){
				LSX_BD.maps.stopWheel();
			});
		}, function() {
			$(document).unbind('mousewheel DOMMouseScroll');
		});
	
		if ( typeof( google ) != 'undefined' && typeof( google.maps ) == 'object' && jQuery('#gmap').length ) {
			initMap();
		} else {
			var mapTimer = window.setTimeout( watchForMap, 500 );
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
				map.setZoom(5);
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
	};
	
	LSX_BD.maps.setMapCenterZoom = function () {
		if ( markers.length ) {
			var bounds = new google.maps.LatLngBounds();
			for (var i = 0; i < markers.length; i++) {
				bounds.extend(markers[i].getPosition());
			}
	
			map.fitBounds(bounds);
			map.setZoom(5);
		}
	};

	LSX_BD.maps.watchForMap = function ( ) {
		if ( typeof( google ) != 'undefined' && typeof( google.maps ) == 'object' && jQuery('#gmap').length ) {
			initMap();
		} else {
			var mapTimer = window.setTimeout( watchForMap, 500 );
		}
	};
	
	LSX_BD.maps.createMarker = function(place) {
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
	};

    /**
     * On document ready.
     *
     * @package    lsx-member-directory
     * @subpackage scripts
     */
    LSX_BD.document.ready( function() {
        LSX_BD.init();
    } );

} )( jQuery, window, document );
