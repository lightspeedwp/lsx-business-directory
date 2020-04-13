;( function( $, window, document, undefined ) {

	'use strict';
	
	LSX_BD.listingForm = Object.create( null );

    /**
     * Initiate the Maps
     */
	LSX_BD.listingForm.init = function( ) {

        //Init the sliders
        LSX_BD.listingForm.element = jQuery('form.listing-form');
        if ( 0 <  LSX_BD.listingForm.element.length ) {
			LSX_BD.listingForm.removeImage();
        }
	};

	LSX_BD.listingForm.removeImage = function( ) {
        //Init the sliders
        if ( 0 <  LSX_BD.listingForm.element.find('.remove-image').length ) {
			LSX_BD.listingForm.element.find('.remove-image').on( 'click', function( e ) {
				e.preventDefault();
				var parentRow = jQuery(this).parents('.form-row');
				// Clear the hidden ID.
				parentRow.find('input[type="hidden"]').val('');
				parentRow.find('img').remove();
				parentRow.find('input[type="file"]').removeClass('hidden');
				jQuery(this).addClass('hidden');
			});
        }
	};

    /**
     * On document ready.
     *
     * @package    lsx-member-directory
     * @subpackage scripts
     */
    LSX_BD.document.ready( function() {
		//LSX_BD.log('LSX BD Map Params',false,lsx_bd_maps_params);
		LSX_BD.listingForm.init();
    } );

} )( jQuery, window, document );
