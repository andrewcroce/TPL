<?php

/**
 * Include some stuff
 */
include( 'plugins/plugins.php' );
include( 'includes/helpers.php' );
include( 'includes/settings.php' );
include( 'includes/topbar-walker.class.php' );
include( 'includes/offcanvas-walker.class.php' );
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
	$index_arguments = array(
		'post_type' => $page->index_post_type,
		'paged' => $paged
	);	

	// Prepare an empty taxonomy query
	$tax_query = array();

	// Loop through our URL parameters
	foreach ($_GET as $key => $value) {

		// Use the URL params to check if there are any taxonomies with matching query_vars
		$matching_taxonomies = get_taxonomies( array( 'query_var' => $key ), 'objects' );

		// If we have a match
		if( !empty( $matching_taxonomies ) ){

			// Set a taxonomy query with that taxonomy name and value(s)
			$tax_query[] = array(
				'taxonomy' => reset($matching_taxonomies)->name,
				'field' => 'slug',
				'terms' => explode( ',', $value )
			);
		}
		
	}
	// Apply the taxonomy query to the WP query arguments
	$index_arguments['tax_query'] = $tax_query;

	// Prepare an array of variables to return
	$vars = array(
		'index' => new WP_Query( $index_arguments ), // Run a WP_Query
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



/**
 * Check if a page has a family tree, i.e. it has children or ancestors
 * @param  int/WP_Post $post A page ID or WP Post object
 * @return boolean       
 */
function page_has_family_tree( $post = null ){
	
	if( is_null( $post ) ) {
		global $post;
	} else {
		if( is_int( $post ) )
			$post = get_post( $post );
	}

	$cached = wp_cache_get( 'page_has_family_tree_' . $post->ID );

	if( $cached ) return $cached;

	$ancestors = get_ancestors( $post->ID, 'page' );

	if( !empty( $ancestors ) ) return true;

	$children = get_posts(array(
		'post_type' => 'page',
		'post_parent' => $post->ID,
		'child_of' => $post->ID
	));

	if( !empty( $children ) ) return true;

	return false;
}




/**
 * Get the page hierarchy associated with a page
 * @param  int/WP_Post/ACF_Post $page 	Page ID or post object
 * @return array       					Hierarchical array of pages and their descendants
 *
 * @see includes/helpers.php for the recursive add_descendants() function
 */
function get_page_tree( $page ) {

	// If its an ID, get the post object
	if( is_int( $page ) )
		$page = get_post( $page );

	// Get the linear array of the page's ancestors.
	// The last item in the array will always be a top level page
	$ancestors = get_ancestors( $page->ID, 'page' );

	$tree = array();

	// If there are no ancestors, this is a top level page
	if( empty( $ancestors ) ){
		$root_ancestor = $page->ID;

	// The ancestral post is the last one
	} else {
		$root_ancestor = $ancestors[ count( $ancestors ) - 1 ];
	}

	// See if theres a cached copy
	$cached_tree = wp_cache_get( 'page_tree_' . $root_ancestor );

	// If so, return it
	if( $cached_tree ) {
		return $cached_tree;
	}

	// Add the root ancestor as the root item in our tree
	$tree[0] = get_post( $root_ancestor );

	// Add any descendants recursively
	add_descendents( $tree[0] );

	// Cache the results
	wp_cache_set( 'page_tree_' . $root_ancestor, $tree );

	return $tree;

}