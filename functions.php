<?php

include( 'plugins/plugins.php' );
include( 'theme.class.php' );



/* ====================================== */
/* THEME FUNCTIONS */
/* ====================================== */




function tpl_wrapper( $name, $position ){
	if( file_exists( dirname(__FILE__) . '/tpl_wrappers/' . $name . '-wrapper-' . $position . '.php' )  ) {
		include locate_template( 'tpl_wrappers/' . $name . '-wrapper-' . $position . '.php');
	} else {
		throw new Exception('No such template exists: "tpl_wrappers/' . $name . '-wrapper-' . $position . '.php"', 1);
	}
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



/**
 * Dump preformatted data to the page
 */
function dump( $data ){
	echo '<pre>';
	print_r( $data );
	echo '</pre>';
}





?>