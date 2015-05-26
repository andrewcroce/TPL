<?php

/**
 * @param ACFPost $acfPost The WP_Post for the page wrapped in ACFPost object.
 */
function tpl_content_page( $acfPost ) {

	tpl( 'content' , 'page' , array(
		'acfPost' => $acfPost,
	) );

}