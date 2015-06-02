(function($){
	
	// Empty URL object
	var urlObject = null;

	// Initialize function, called in app.js
	$.initTaxonomyFilters = function(){

		// Create a URI.js URL object
		// see http://medialize.github.io/URI.js/
		urlObject = new URI( window.location );

		// When the "Apply Filters" button is clicked
		$('#apply-taxonomy-filters-button').on('click', function(){

			// Loop through each filter set
			$('.taxonomy-filter-list').each(function(){

				// Grab the corresponding taxonomy from the element's data
				var taxonomy = $(this).data('taxonomy');

				// Prepare an empty terms array
				var terms = [];

				// Push each checked term into the terms array 
				$(this).find('input:checked').each(function(){
					terms.push( $(this).data('term') );
				});
				
				// If we added terms, set the query on the URL object
				if( terms.length > 0 ) {
					urlObject.setQuery( taxonomy, terms.join() );
				}

			});

			// When we're done looping through each filter set,
			// Reload the page with the new query string
			window.location.href = urlObject.normalizeQuery();
		});


		// When the "Clear Filters" button is clicked
		$('#clear-taxonomy-filters-button').on('click', function(){

			// Simply clear out the URL query string and reload the page
			urlObject.query('');
			window.location.href = urlObject.normalizeQuery();
		});

		// When any filter term is checked, show the "Apply Filters" button
		$('.taxonomy-filter-list input').on('change',function(){
			$('#apply-taxonomy-filters-button').show();
		});
	}
	
})(jQuery);