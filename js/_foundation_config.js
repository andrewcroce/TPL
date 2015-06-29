/* global jQuery.password */

(function($){

	// Custom Foundation configuration option,
	// passed into $(document).foundation(); call in app.js
	
	$.foundationConfig = {

		// Abide form validation
		abide : {
			
			patterns : {
				'allowed_statuses' : $.password.allowedStatusesRegex()
			},

			validators : {
				strongPassword : function( el , required, parent ){
					
					if( el.value.trim().length === 0 ) return true;
					
					if( typeof zxcvbn === 'function' ) {
						var result = zxcvbn( el.value );
						if( result.score === 2 || result.score === 3 || result.score === 4 ) return true;
						return false;
					} else {
						throw new Error( 'Error on field #'+$(el).attr('id')+': "strongPassword" validation requires the zxcvbn function, which is currently undefined. Include the library on this page, or else this field will always validate.' );
					}
				}
			}
		}

	};

})(jQuery);