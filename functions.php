<?php

include( 'plugins/plugins.php' );
include( 'includes/helpers.php' );
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
 * If an object is passed in the third parameter, it will be available in the template
 * as a variable matching the passed $prefix string.
 * So calling tpl('foo','bar',$post); will make a variable called $foo available in the template.
 * 
 * @param string $prefix The first segment of the filename
 * @param string $name The second segment of the filename
 * @param mixed $object An optional object/array (i.e. a WP Post or ACF Post object) to pass to the template file
 */
function tpl( $prefix, $name, $object = null ) {

	// If no $name is provided, use 'default'
	if( is_null( $name ) )
		$name = 'default';

	// If the matching file exists...
	if( file_exists( dirname(__FILE__) . '/tpl_' . $prefix . 's/' . $prefix . '-' . $name . '.php' ) ) {
		
		// If an object was passed, assign it to a variable for the template
		if( !is_null( $object ) )
			${$name} = $object;
		
		// Include the template
		include locate_template( 'tpl_' . $prefix . 's/' . $prefix . '-' . $name . '.php');

	// Otherwise trigger an error
	} else {
		trigger_error('No such template exists: "tpl_' . $prefix . 's/' . $prefix . '-' . $name . '.php"')
	}

}