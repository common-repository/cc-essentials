jQuery(document).ready(function($){

	$('.cce-loveit').live('click', function() {
    		
    		var link = $(this);
    		
    		if(link.hasClass('loved')) {
	    		
	    		return false;
	    	
	    	} else {
			
				link.addClass('loved');
				
	    		var id = $(this).attr('id'),
	    			suffix = link.find('.cce-loveit-suffix').text(),
					count = link.find('.cce-loveit-count'),
	    			countInt = parseInt(count.text()) + 1;
	    		
	    		count.text(countInt.toString());	// User Experience: Immediately acknowledging the user that your ‘love’ has been 
	    											// received while we send the request to the server and wait for the response.
				
	    		$.post(cce_loveit.ajaxurl, { action:'cce_loveit', loves_id:id, suffix:suffix }, function(data){
	    			link.html(data).addClass('loved').attr('title',cce_loveit.loved_text);
	    		});
			
	    		return false;
    		
    		}
	});
	
	
    $('.cce-loveit').each(function(){
		var id = $(this).attr('id');
		$(this).load(cce_loveit.ajaxurl, { action:'cce_loveit', post_id:id });
	});

});