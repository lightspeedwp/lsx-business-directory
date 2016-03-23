jQuery(document).ready(function($) {
	$('.lsx-map iframe').hover(function() {
		console.log('HOVERING BITCHES');
	    $(document).bind('mousewheel DOMMouseScroll',function(){ 
	        stopWheel(); 
	    });
	}, function() {
	    $(document).unbind('mousewheel DOMMouseScroll');
	});


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