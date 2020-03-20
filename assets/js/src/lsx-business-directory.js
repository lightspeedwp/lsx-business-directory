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
    };

    /**
     * Initiate the Sliders
     */
    LSX_BD.sliders.init = function( ) {
        LSX_BD.sliders.element.each( function() {
            $(this).slick({
                dots: true,
                infinite: false,
                speed: 300,
                slidesToShow: 3,
                slidesToScroll: 3,
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3,
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
     * On document ready.
     *
     * @package    lsx-member-directory
     * @subpackage scripts
     */
    LSX_BD.document.ready( function() {
        LSX_BD.init();
    } );

} )( jQuery, window, document );
