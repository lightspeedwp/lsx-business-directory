var lsx_canvas = {},
	lsx_config_object = {},
	lsx_get_new_line_id,
	lsx_get_config_object,
	lsx_record_change,
	lsx_canvas_init,
	lsx_get_default_setting,
	lsx_set_repeatable_titles;

jQuery( function( $ ){

	  $.fn.buildObject = function(){
	    var form = $(this);

	    var fields       = form.find('[name]'),
	        json         = {},
	        arraynames   = {};
	    for( var v = 0; v < fields.length; v++){
	      var field     = $( fields[v] ),
	        name    = field.prop('name').replace(/\]/gi,'').split('['),
	        value     = field.val(),
	        lineconf  = {};
	        if( field.is(':radio') || field.is(':checkbox') ){
	          if( !field.is(':checked') ){
	            continue;
	          }
	        }

	      for(var i = name.length-1; i >= 0; i--){
	        var nestname = name[i];
	        if(nestname.length === 0){
	          lineconf = [];
	          if( typeof arraynames[name[i-1]] === 'undefined'){
	            arraynames[name[i-1]] = 0;
	          }else{
	            arraynames[name[i-1]] += 1;
	          }
	          nestname = arraynames[name[i-1]];
	        }
	        if(i === name.length-1){
	          if( value ){
	            if( value === 'true' ){
	              value = true;
	            }else if( value === 'false' ){
	              value = false;
	            }else if( !isNaN( parseFloat( value ) ) && parseFloat( value ).toString() === value ){
	              value = parseFloat( value );
	            }else if( value.substr(0,1) === '{' || value.substr(0,1) === '[' ){
	              try {
	                value = JSON.parse( value );

	              } catch (e) {
	                //console.log( e );
	              }
	            }
	          }
	          lineconf[nestname] = value;
	        }else{
	          var newobj = lineconf;
	          lineconf = {};
	          lineconf[nestname] = newobj;
	        }   
	      }
	      $.extend(true, json, lineconf);
	    };
	    return json;
	  }

	// internal function declarationas
	lsx_get_config_object = function(el){
		// new sync first
		$('#lsx-id').trigger('change');
		var clicked 	= $(el),
			config 		= $('#' + app ).val(),
			required 	= $('[required]'),
			clean		= true;

		for( var input = 0; input < required.length; input++ ){
			if( required[input].value.length <= 0 && $( required[input] ).is(':visible') ){
				$( required[input] ).addClass('lsx-input-error');
				clean = false;
			}else{
				$( required[input] ).removeClass('lsx-input-error');
			}
		}
		if( clean ){
			lsx_canvas = config;
		}
		clicked.data( 'config', config );
		return clean;
	}

	lsx_record_change = function( app ){
		// hook and rebuild the fields list
		var appkey = app.data('app'),
			appMaster = jQuery('#' + app.data('app') );
		
		lsx_config_object[appkey] = app.buildObject();
		appMaster.val( JSON.stringify( lsx_config_object[appkey] ) );
		jQuery(document).trigger('record_change');
		appMaster.trigger('sync');

	}
	
	lsx_canvas_init = function( obj ){
		var app = obj.params.target.data('app');
		if( !app ){
			alert('no app defined');
			return;
		}
		if( !lsx_canvas[app] ){
			// bind changes
			obj.params.target.on('keydown keyup change','input, select, textarea', function(e) {				
				lsx_config_object[app] = obj.params.target.buildObject(); // perhaps load into memory to keep it live.
				jQuery('#' + app ).val( JSON.stringify( lsx_config_object[app] ) ).trigger('change');
			});

			lsx_canvas[app] = jQuery('#' + app).val();
			lsx_config_object[app] = JSON.parse( lsx_canvas[app] ); // perhaps load into memory to keep it live.
		}
		if( $('.color-field').length ){

			$('.color-field').wpColorPicker({
				change: function(obj){
					var picker = $( this );
					if( picker.data('sync') ){
						clearTimeout( picker.data('sync') );
					}
					var pickerSync = setTimeout( function(){
						picker.trigger('change');
					}, 100 );
					picker.data( 'sync', pickerSync );
				}
			});
		}
		if( $('.repeatable-field-wrap').length ){
			$( ".field-part-wrap" ).sortable({
				items: ".repeatable-field-wrap",
				update: function(){
					lsx_config_object[app] = obj.params.target.buildObject(); // perhaps load into memory to keep it live.
					jQuery('#' + app ).val( JSON.stringify( lsx_config_object[app] ) ).trigger('change');
					//jQuery('#lsx-id').trigger('change');
				}
			});
		}
		if( $('.repeatable-group-wrap').length ){
			
			$( ".lsx-grid" ).each(function(){
				lsx_set_repeatable_titles($(this));
			});			
			
			$( ".lsx-grid" ).sortable({
				items: ".repeatable-group-wrap",
				update: function(event,ui){
					lsx_config_object[app] = obj.params.target.buildObject(); // perhaps load into memory to keep it live.
					jQuery('#' + app ).val( JSON.stringify( lsx_config_object[app] ) ).trigger('change');
					lsx_set_repeatable_titles(ui.item.parents('.lsx-grid'));
				}
			});

			
		}
		
		// live change init
		$('[data-init-change]').trigger('change');
		$('[data-auto-focus]').focus().select();

		jQuery(document).trigger('lsx_canvas_init');
	}
	lsx_get_default_setting = function(obj){

		var id = 'nd' + Math.round(Math.random() * 99866) + Math.round(Math.random() * 99866),
			trigger = ( obj.trigger ? obj.trigger : obj.params.trigger ),
			app = obj.trigger.closest('.lsx-metabox-wrapper').data('app'),
			sub_id = ( trigger.data('group') ? trigger.data('group') : 'nd' + Math.round(Math.random() * 99766) + Math.round(Math.random() * 99866) ),
			nodes;

		
		// add simple node
		if( trigger.data('addNode') ){
			// new node? add one
			var newnode = { "_id" : id };
			nodes = trigger.data('addNode').split('.');
			var node_point_record = nodes.join('.') + '.' + id,
				node_defaults = JSON.parse( '{ "_id" : "' + id + '", "_node_point" : "' + node_point_record + '" }' );
			if( trigger.data('nodeDefault') && typeof trigger.data('nodeDefault') === 'object' ){				
				$.extend( true, node_defaults, trigger.data('nodeDefault') );
			}			
			var node_string = '{ "' + nodes.join( '": { "') + '" : { "' + id + '" : ' + JSON.stringify( node_defaults );
			for( var cls = 0; cls <= nodes.length; cls++){
				node_string += '}';
			}
			var new_nodes = JSON.parse( node_string );
			$.extend( true, lsx_config_object[app], new_nodes );
		}
		// remove simple node (all)
		if( trigger.data('removeNode') ){
			// new node? add one
			if( lsx_config_object[app][trigger.data('removeNode')] ){
				delete lsx_config_object[app][trigger.data('removeNode')];
			}

		}

		jQuery('#' + app ).val( JSON.stringify( lsx_config_object[app] ) ).trigger('sync');
	}

	lsx_get_new_line_id = function( obj ){
		
		var s = [], itoh = '0123456789ABCDEF';
		for (var i = 0; i <6; i++){
			s[i] = Math.floor(Math.random()*0x10);
		}


		var out = {
			ID : 'lsx' + s.join('')
		};

		return out;
	}
	
	//This simply iterates through repeatable fields and assigns numbers to them.
	lsx_set_repeatable_titles = function( obj ){
		var counter = 1;
		
		//TODO  Move this to the tours module, somehow
		itinerary = false;
		if(obj.attr('data-panel-id') !== undefined && 'days' == obj.attr('data-panel-id')){
			itinerary = true;
		}
		obj.find('.repeatable-group-wrap').each(function(){
			
			var title_prefix = $(this).find('.lsx-group-collapsable-title').attr('data-item-title')+':';
			var title = title_prefix + ' ' + counter;
			var maybe_title = '';
			if(false == itinerary){
				
				maybe_title = $(this).find('input[type="text"]').val();
				if(undefined !== maybe_title && '' !== maybe_title){
					title = maybe_title;
				}
				counter++;
			}else{
				nights = 1;	
				$(this).find('.form-group').each(function(){
					if($(this).find('label').html() == 'Nights'){
						nights = $(this).find('input').val();
						
						if('' != nights && undefined != nights){
							nights = parseInt(nights);
						}
					}
				});
				
				if(1 != nights && '' != nights && undefined != nights){
					counter = counter + nights;
					title = title + ' - ' + (counter-1);	
				}else{
					counter++;
				}					
			}
			
			$(this).find('.lsx-group-collapsable-title').html(title)
		});
		
	}	

	$(document).on('click', '.lsx-metabox-tab > li > a', function(e){
		
		e.preventDefault();
		var clicked = $(this),
			parent = clicked.closest('.inside');

		parent.find('.lsx-tab-body').hide();
		parent.find('.lsx-metabox-tab li').removeClass('active');
		parent.find( '.active-tab').val( clicked.attr('href').replace('#', '') ).trigger('change');
		$( clicked.attr('href') ).show();
		clicked.parent().addClass('active');
		$( document ).trigger( 'metaboxTabbed' );
	});


	// row remover global neeto
	$(document).on('click', '[data-remove-parent]', function(e){
		var clicked = $(this),
			parent = clicked.closest(clicked.data('removeParent')),
			app = clicked.closest('.lsx-metabox-wrapper');

		if( clicked.data('confirm') ){
			if( !confirm(clicked.data('confirm')) ){
				return;
			}
		}
		parent.remove();
		lsx_record_change( app );
	});
	
	// row remover global neeto
	$(document).on('click', '[data-remove-element]', function(e){
		var clicked = $(this),
			elements = $(clicked.data('removeElement'));
		if( clicked.data('confirm') ){
			if( !confirm(clicked.data('confirm')) ){
				return;
			}
		}
		elements.remove();
		lsx_record_change();
	});

	// initialize live sync rebuild
	$(document).on('change', '[data-live-sync]', function(e){
		var changed 	= $(this),
			app 		= changed.closest('.lsx-metabox-wrapper');
		lsx_record_change( app );
	});

	// initialise baldrick triggers
	$('.lsx-baldrick').baldrick({
		request     : ajaxurl,
		method      : 'POST',
		before		: function(el){
			
			var tr = $(el);

			if( tr.data('addNode') && !tr.data('request') ){
				tr.data('request', 'lsx_get_default_setting');
			}
		}
	});

	// quick hack to help
	//caldera_forms_form
	$(document).on('click', '[data-page]', function(e){
		e.preventDefault();
		var clicked = $(this),
			parent = clicked.closest('.caldera_forms_form');

		parent.find('.caldera-form-page').hide();
		parent.find('.breadcrumb li').removeClass('active');
		
		parent.find('[data-formpage="' + clicked.data('page') + '"]').show();
		clicked.parent().addClass('active');
	});

	$(document).on('click', '.button.lsx-remove-repeatable', function(){

		var clicked = $(this),
			parent = clicked.closest('.lsx-repeatable-wrap');

		if( clicked.data('confirm') ){
			if( ! confirm( clicked.data('confirm') ) ){
				return;
			}
		}

		parent.remove();

	});
	
	

} );