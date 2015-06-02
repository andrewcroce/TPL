// Import the foundation base script, add additional libraries/modules as needed
// @codekit-prepend "../bower_components/foundation/js/foundation.js"
// @codekit-prepend "../bower_components/uri.js/src/URI.js"
// @codekit-prepend "_taxonomy_filters.js"


jQuery(document).foundation();

(function($){
	
	$(document).ready(function(){
		
		$.initTaxonomyFilters();

	});
	
})(jQuery);

