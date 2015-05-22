<?php

include( 'plugins/plugins.php' );
include( 'includes/helpers.php' );
include( 'includes/acf_index_post_type_fields.php' );
include( 'theme.class.php' );



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