// Include scripts with Codekit <https://incident57.com/codekit/help.html#javascript>
// 
// @codekit-prepend "../bower_components/foundation/js/foundation.js"
// @codekit-prepend "../bower_components/uri.js/src/URI.js"
// @codekit-prepend "../bower_components/jquery-throttle-debounce/jquery.ba-throttle-debounce.js"
// @codekit-prepend "../bower_components/jquery-transporter/src/transporter.js"
// @codekit-prepend "_taxonomy_filters.js"
// @codekit-prepend "_password.js"



// Include scripts with gulp-inlcude <https://www.npmjs.com/package/gulp-include>
// 
//= include ../bower_components/foundation/js/foundation.js
//= include ../bower_components/uri.js/src/URI.js
//= include ../bower_components/jquery-throttle-debounce/jquery.ba-throttle-debounce.js
//= include ../bower_components/jquery-transporter/src/transporter.js
//= include _taxonomy_filters.js
//= include _password.js



jQuery(document).foundation();

(function($){
	
	$(document).ready(function(){
		
		$.initTaxonomyFilters();

		if( $('.check-pass-strength').length ) {
			$.password.init();
		}

	});
	
})(jQuery);

