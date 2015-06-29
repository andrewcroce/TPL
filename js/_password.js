(function($){

	$.password = {

		// Status texts
		statuses : [
			'Bad password',
			'Weak password',
			'OK password',
			'Good password',
			'Strong password',
			'Passwords don\'t match'
		],

		allowedStatusesRegex : function(){
			var string = '^(';
			for( var i = 2; i < 5; i++ ) {
				if( i !== 2 ) string += '|';
				string += $.password.statuses[i];
			}
			string += ')$';
			return new RegExp(string);
		},

		// Initialize
		init : function(){

			// Find each instance of a password field
			$('.check-pass-strength').each(function(){

				// Get all the elements
				var pass_id		= $(this).data('pass-id'),
					pass 		= $('.check-pass-strength[data-pass-id="'+pass_id+'"]'),
					confirm 	= $('.check-pass-strength-confirm[data-pass-id="'+pass_id+'"]'),
					username	= $('.check-pass-strength-username[data-pass-id="'+pass_id+'"]'),
					email		= $('.check-pass-strength-email[data-pass-id="'+pass_id+'"]'),
					meter 		= $('.check-pass-strength-meter[data-pass-id="'+pass_id+'"]'),
					allow		= $('.check-pass-strength-allow[data-pass-id="'+pass_id+'"]');

					// Package them up in an object
					set 		= {
						'pass' 		: pass,
						'confirm'	: confirm,
						'username'	: username,
						'email'		: email,
						'meter'		: meter,
						'allow'		: allow,
					};

				// Bind the keyup events for the password and confirmation fields
				// Debounce them, so they don't trigger more than once every 500ms
				// @see http://foundation.zurb.com/docs/javascript-utilities.html#delay
				$( pass ).on( 'keyup', Foundation.utils.debounce( function(){
					$.password.checkStrength( set ); 
				}, 500 ));

				$( confirm ).on( 'keyup', Foundation.utils.debounce( function(){
					$.password.checkStrength( set ); 
				}, 500 ));

			});

		},


		checkStrength : function( set ) {

			// Get all our values
			var pass 		= $(set.pass).val().trim(),
				confirm 	= $(set.confirm).val().trim(),
				username 	= $(set.username).val().trim(),
				email 		= $(set.email).val().trim(),
				meter 		= $(set.meter),
				allow 		= $(set.allow);

			// If the password and confirmation fields are empty, don't bother checking.
			// Clear the meter and allow normal form submission
			if( !pass && !confirm ) {
				meter.val('').attr('class','check-pass-strength-meter');
				allow.val(1).trigger('change');
				return;
			}

			// Check strength using the built-in WP password strength function
			var strength = wp.passwordStrength.meter(
				pass, [ username, email ], confirm
			);

			// Set the strength meter status
			$(set.meter)
				.val( $.password.statuses[strength] )
				.attr('class','check-pass-strength-meter strength-'+strength);

			// If the strength is bad or weak, or they don't match
			// don't allow submission
			if( strength === 0 || strength === 1 || strength === 5 ) {
				allow.val(0).trigger('change');
				return;
			}

			// I'll allow it
			allow.val(1).trigger('change');

		}

	};

})(jQuery);