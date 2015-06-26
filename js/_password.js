(function($){

	$.password = {

		// Empty object to store our password sets
		sets : {},

		// Status texts
		statuses : {
			'mismatch' 	: 'Passwords don\'t match',
			'short'		: 'Password too short',
			'bad'		: 'Weak password',
			'good'		: 'Good password',
			'strong'	: 'Strong password'
		},

		// Initialize
		init : function(){

			// Find each instance of a password field
			$('.check-pass-strength').each(function(){

				// Get all the elements
				var pass 		= $(this),
					pass_id 	= pass.data('pass-id'),
					confirm 	= $('.check-pass-strength-confirm[data-pass-id="'+pass_id+'"]'),
					username	= $('.check-pass-strength-username[data-pass-id="'+pass_id+'"]'),
					email		= $('.check-pass-strength-email[data-pass-id="'+pass_id+'"]'),
					meter 		= $('.check-pass-strength-meter[data-pass-id="'+pass_id+'"]'),

					// Package them up in an object
					set 		= {
						'pass' 		: pass,
						'confirm'	: confirm,
						'username'	: username,
						'email'		: email,
						'meter'		: meter
					};

				// Add the set to our collection of sets
				$.password.sets[pass_id] = set;

				// Bind the keyup events for the password and confirmation fields
				// Debounce them, so they don't trigger more than once every 500ms
				// @see http://foundation.zurb.com/docs/javascript-utilities.html#delay
				$( pass ).bind('keyup', Foundation.utils.debounce(function(){
					$.password.checkStrength( set );
				},500));

				$( confirm ).bind('keyup',Foundation.utils.debounce(function(){
					$.password.checkStrength( set );
				},500));

			});

		},


		checkStrength : function( set ) {

			if( $(set.email).val() == $(set.pass).val() )
				return 0;

			// Built in WP Function
			// @see https://core.trac.wordpress.org/browser/trunk/wp-admin/js/password-strength-meter.dev.js?rev=15998
			return passwordStrength(
				$(set.pass).val(),
				$(set.username).val(),
				$(set.confirm).val()
			);

		}

	};

})(jQuery);