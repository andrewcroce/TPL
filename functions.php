<?php

include( 'inc/plugin-activation.class.php' );
include( 'theme.class.php' );



/* ====================================== */
/* THEME FUNCTIONS */
/* ====================================== */




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