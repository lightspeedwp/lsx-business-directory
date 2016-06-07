// Uploading files
var image_picker_frame;

jQuery(function($){

	
	$(document).on('click', '.image-picker-remover', function( e ){
		
		e.preventDefault();
		var clicked = $( this ),
			target = $( clicked.data('target') );

		if( !target.length ){
			alert(' No Target set');
			return;
		}

		target.val('{}').trigger('change');
	});

	$(document).on('click', '.image-picker-preview', function( e ){
		
		e.preventDefault();
		var clicked = $( this ),
			target = $( clicked.data('target') ),
			options = {
				title: clicked.data( 'title' ),
				button: {
					text: clicked.data( 'button' ),
				},
				//library: { type: 'image'},
				multiple: true
			};
			if( clicked.data('library') ){
				options.library = clicked.data('library');
			}

		if( !target.length ){
			alert(' No Target set');
			return;
		}
		if ( !image_picker_frame ) {
			// Create the media frame.
			image_picker_frame = wp.media( options );
		}
		var cancel_select = function( e ){
			if( typeof image_picker_frame.state().get('selection').first() === 'undefined' ){
				if( !target.val() ){
					target.closest('.lsx-fieldtype-image').find('.lsx-remove-repeatable').trigger('click');
				}
			}
			image_picker_frame.off( 'close', cancel_select);
		}
		var select_handler = function(e){
			attachment = image_picker_frame.state().get('selection').first().toJSON();
			if( attachment.sizes ){
				if( typeof attachment.sizes.medium  !== 'undefined' ){
					attachment.thumbnail = attachment.sizes.medium.url;
				}else if( typeof attachment.sizes.full !== 'undefined' ){
					attachment.thumbnail = attachment.sizes.full.url;
				}else{
					attachment.thumbnail = attachment.url;
				}
			}else{
				attachment.thumbnail = attachment.icon;
			}
			target.val( JSON.stringify( attachment ) ).trigger('change');
			image_picker_frame.off( 'select', select_handler);
		};
		image_picker_frame.on( 'select', select_handler);
		image_picker_frame.on( 'close', cancel_select);
		image_picker_frame.open();
	});

})
