<?php

/**
 * Include some stuff
 */
include( 'plugins/plugins.php' );
include( 'includes/helpers.php' );
include( 'includes/acf_index_post_type_fields.php' );
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