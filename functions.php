<?php

include( 'inc/plugin-activation.class.php' );
include( 'theme.class.php' );



/* ====================================== */
/* THEME FUNCTIONS */
/* ====================================== */



/**
 * Include the starting tags for the main content wrapper
 */
function content_wrapper_start(){
	include locate_template('wrappers/content_wrapper_start.php');
}

/**
 * Include the ending tags for the main content wrapper
 */
function content_wrapper_end(){
	include locate_template('wrappers/content_wrapper_end.php');
}



/**
*
* Get a snippet/excerpt from any content
* This is a much more robust replacement for WP's excerpt field. This will generate a snippet from any text string.
* 
**/
function get_snippet( $content, $limit, $break=" ", $pad="..." ) {
	if(strlen($content) <= $limit) {
		return wpautop($content);
	}
	if(false !== ($breakpoint = strpos($content, $break, $limit))) {
		if($breakpoint < strlen($content) - 1) {
			$content = substr($content, 0, $breakpoint) . $pad;
		}
	}
 	return wpautop($content);
}





?>