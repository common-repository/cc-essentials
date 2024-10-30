(function($){

	"use strict";

	$(document).ready(function(){
		$(".cce-tabs").tabs({
			hide: { effect: "fadeOut", duration: 100 },
			show: { effect: "fadeIn", duration: 100 }
		});

		$(".cce-toggle").each( function () {
			if($(this).attr('data-id') == 'closed') {
				$(this).accordion({ header: '.cce-toggle-title', collapsible: true, heightStyle: "content", active: false });
			} else {
				$(this).accordion({ header: '.cce-toggle-title', collapsible: true, heightStyle: "content" });
			}
		});
		
		$(".cce-notification .dismiss-notification").on('click', function() {
			$(this).parent().fadeOut('normal', function(){
				$(this).remove();
			});
		});
	});

})(jQuery);
