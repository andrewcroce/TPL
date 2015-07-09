(function($){

	$.displayToggle = {

		init : function(){

			// For each toggle control
			$('.display-toggle').each(function(){
				
				// Run toggle immediately
				$.displayToggle.toggle( $(this) );

				// And bind toggle to the change event
				$(this).on('change', function(){
					$.displayToggle.toggle( $(this) );
				});

			});

		},


		// Toggle display on elements tied to a controle element
		toggle : function( control ){

			// Get elements with display toggling tied to this control
			var elements = $('.display-toggleable[data-toggle-control="'+control.attr('id')+'"]');

			// If the control is both checked AND visible, show the elements
			if( control.is(':checked') && control.is(':visible') ){
				elements.show();

			// Otherwise hide them
			} else {
				elements.hide();
			}

			// Find any child elements that might themselves be tied to the elements you just showed/hid
			var childToggles = elements.find('.display-toggle')

			// Toggle them too
			if( childToggles.length ){
				$.displayToggle.toggle( childToggles );
			}
		}

	}

})(jQuery);