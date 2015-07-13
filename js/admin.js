// Include scripts with Codekit <https://incident57.com/codekit/help.html#javascript>
//
// @codekit-prepend "../bower_components/jquery.repeater/jquery.repeater.js"
// @codekit-prepend "_display_toggle.js"


// Include scripts with gulp-inlcude <https://www.npmjs.com/package/gulp-include>
//
//= include ../bower_components/jquery.repeater/jquery.repeater.js
//= include _display_toggle.js


(function($){

	$(document).ready(function(){

		$('.repeater').repeater({
			isFirstItemUndeletable : false,
			defaultValues: {
				'crop' : 'soft',
                'crop_y' : 'center',
                'crop_x' : 'center'
            },
		});

		if( $('.display-toggle').length ){
			$.displayToggle.init();
		}

	});

})(jQuery);
