<?php

/**
 * Include some stuff
 */
include( 'plugins/plugins.php' );
include( 'includes/helpers.php' );
include( 'theme.class.php' );

/**
 * This little snippet automatically includes any function files in tpl_ folders according to the naming structure.
 * So, a file at path tpl_foos/foo_functions.php will be included here
 * 
 */

$folders = glob( dirname(__FILE__).'/tpl_*' ); // Get all our tpl_ folders

foreach( $folders as $folder ) {

	$foldername = pathinfo( $folder , PATHINFO_FILENAME ); // Get the folder name
	$prefix = substr( $foldername , 4 , strlen( $foldername ) - 5 ); // Infer the corresponding prefix name
	
	// If the matching functions file exists and is readable, include it.
	if( is_readable( $folder . '/' . $prefix . '_functions.php' ) ) require_once $folder . '/' . $prefix . '_functions.php';
}





/* ====================================== */
/* THEME FUNCTIONS */
/* ====================================== */

/**
 * General purpose template include function.
 * This will look for a PHP file matching the naming stucture {$prefix}-{$name}.php
 * within a directory tpl_{$prefix}s (note the 's' at the end, the pluralization of the $prefix).
 * So calling tpl('foo','bar'); will look for tpl_foos/foo_bar.php
 * 
 * If a params array is passed in the third parameter, it will be available in the template
 * 
 * @param string $prefix The first segment of the filename
 * @param string $name The second segment of the filename
 * @param array $params An optional parameters array to pass to the template file
 */
function tpl( $prefix, $name = null, $params = null ) {

	// If no $name is provided, use 'default'
	if( is_null( $name ) )
		$name = 'default';

	$filepath = 'tpl_' . $prefix . 's/' . $prefix . '-' . $name . '.php';

	// If the matching file exists...
	if( is_readable( dirname(__FILE__) . '/' . $filepath ) ) {
		
		// Include the template
		include locate_template( $filepath ) ;

	// Otherwise trigger an error
	} else {
		trigger_error('No such template exists: ' . $filepath );
	}

}



/**
 * Used on the index page template to get several variables related to the index
 * @param  ACF_Post  $page  The WP Post object wrapped in ACF Post object
 * @param  integer $paged 	The current page number
 * @return array         	Array of variables
 */
function get_index_vars( $page, $paged = 0 ) {

	global $wp_query;

	// Prepare our index query arguments
	$index = array(
		'post_type' => $page->index_post_type,
		'paged' => $paged
	);

	// Put any URL GET variables into the query args.
	// This may not be the best way to do this, but it allows us to only get the variables that appear in the URL.
	// If we use the $wp_query global for this, it comes with other vars we don't want here, like 'pagename'
	

	/*=================================*/
	//THIS BE BROKEN
	$tax_query = array();
	foreach ($_GET as $key => $value) {

		if( term_exists( $value, $key ) ){
			$tax_query[] = array(
				'taxonomy' => $key,
				'field' => 'slug',
				'terms' => $value
			);
		}
		
	}
	$index['tax_query'] = $tax_query;
	PC::debug($index);
	/*=================================*/

	// Prepare our vars array
	$vars = array(
		'index' => new WP_Query( $index ),
		'post_type' => get_post_type_object( $page->index_post_type )
	);

	// If the page has enabled taxonomy filters...
	if( isset( $page->{ 'index_taxonomies_for_' . $page->index_post_type } ) ) {

		// Loop through each of them
		foreach( $page->{ 'index_taxonomies_for_' . $page->index_post_type } as $taxonomy ){

			// Get the full WP taxonomy object
			$taxonomy_object = get_taxonomy( $taxonomy );

			// Add a custom property "filter_style" to the object
			// Unless changed, this is either "single" or "multi", to allow admin to choose whether each taxonomy can use filter by multiple terms at once
			$taxonomy_object->filter_style = $page->{'filter_style_for_' . $page->index_post_type . '_' . $taxonomy};
			
			// Add them to the vars array
			$vars['taxonomies'][] = $taxonomy_object;
		}
	}
	return $vars;
}




/**
 * Get some useful variables related to the page number.
 * These are used, for instance, to label paginated list pages
 * @param  WP_Query $query A WP Query object from which to generate page variables
 * @return array {
 *         Array of pagination-related variables
 *         
 *         @var int $page_number	Current page number
 *         @var int $start_number	The number of the first post on this page
 *         @var int $end_number		The number of the last post on this page
 *         @var int $total_number	The total number of posts found in the query
 * }
 */
function get_paged_vars( $query ) {

    $page_number = empty( $query->query['paged'] ) ? 1 : $query->query['paged'] ;
    $start_number = (( $query->query_vars['posts_per_page'] * $page_number ) - $query->query_vars['posts_per_page'] ) + 1;

    return array(
        'page_number' => $page_number,
        'start_number' => $start_number,
        'end_number' => $start_number + ( $query->post_count - 1 ),
        'total_number' => $query->found_posts
    );
}