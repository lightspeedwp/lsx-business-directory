var LSX_BD = Object.create( null );

;( function( $, window, document, undefined ) {

    'use strict';
    LSX_BD.document = $(document);

    //Holds the slider function
	LSX_BD.sliders = Object.create( null );
	LSX_BD.industries = Object.create( null );
	LSX_BD.readMore = Object.create( null );
	LSX_BD.document = $(document);

    /**
     * Start the JS Class
     */
    LSX_BD.init = function() {
        //Init the sliders
        LSX_BD.sliders.element = jQuery('.lsx-business-directory-slider');
        if ( 0 <  LSX_BD.sliders.element.length ) {
            LSX_BD.sliders.init();
		}
		// initiate the industries.
        LSX_BD.industries.element = jQuery('.lsx-bd-industries-nav');
        if ( 0 <  LSX_BD.industries.element.length ) {
            LSX_BD.industries.init();
        }
	};

    /**
     * A useful logging class.
     */
	LSX_BD.log = function(message,stringValue,objectValue) {
		if ( '1' === LSX_BD.debug ) {
			if ( undefined !== stringValue && false !== stringValue ) {
				console.log( message + ' ' + stringValue );
			} else {
				console.log( message );
				console.log( objectValue );
			}
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
     * Initiate the Industries functions
     */
    LSX_BD.industries.init = function( ) {
		LSX_BD.industries.onHover();
		LSX_BD.industries.offHover();
		LSX_BD.industries.nav();
	};

    LSX_BD.industries.onHover = function( ) {
		$( LSX_BD.industries.element ).on( 'mouseenter', 'a', function( e ) {
			var holder = $(this).find('img').attr( 'src' );
			$(this).find('img').attr( 'src', $(this).attr( 'data-hover' ) );
			$(this).attr( 'data-original', holder );
		});
	};

    LSX_BD.industries.offHover = function( ) {
		$( LSX_BD.industries.element ).on( 'mouseleave', 'a', function( e ) {
			var holder = $(this).find('img').attr( 'src' );
			$(this).find('img').attr( 'src', $(this).attr( 'data-original' ) );
			$(this).attr( 'data-hover', holder );
		});
	};

    LSX_BD.readMore.init = function () {
        $( '.description.content .more-link' ).each( function() {

            if ( 'Read More' === $( this ).html() ) {
                $( this ).closest( '.description.content' ).each( function() {
                    var visible = true;

                    $( this ).children().each( function() {
                        if ( 'Read More' === $( this ).find( '.more-link' ).html() ) {
                            visible = false;
                        } else if ( ! visible && this.id !== 'sharing' ) {
                            $( this ).hide();
                        }
                    } );
                } );

                $( this ).click( function( event ) {
                    event.preventDefault();
                    $( this ).hide();

                    if ($( this ).hasClass( 'more-link-remove-p' ) ) {
                        var html = '';

                        $( this ).closest( '.description.content' ).children().each( function() {
                            $( this ).show();
                        } );
                    } else {
                        $( this ).closest( '.description.content' ).children().show();
                    }
                } );
            }

        } );
	};

	LSX_BD.industries.nav = function() {
		var pageURL = $(location).attr('href');
		$( '.lsx-bd-industries-nav .btn-wrap' ).each( function() {
			var itemURL = $(this).context.href;
			if ( pageURL === itemURL ) {
				$(this).addClass('current-industry');
				var holder = $(this).find('img').attr( 'src' );
				$(this).find('img').attr( 'src', $(this).attr( 'data-hover' ) );
				$(this).attr( 'data-original', holder );
			}
		});
	};

    /**
     * On document ready.
     *
     * @package    lsx-member-directory
     * @subpackage scripts
     */
    LSX_BD.document.ready( function() {
		if ( undefined !== lsx_bd_params.debug && '1' === lsx_bd_params.debug ) {
			LSX_BD.debug = lsx_bd_params.debug;
		} else {
			LSX_BD.debug = 0;
		}
		LSX_BD.log('LSX BD Params',false,lsx_bd_params);
        LSX_BD.init();
    } );

} )( jQuery, window, document );
