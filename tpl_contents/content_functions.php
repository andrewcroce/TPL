<?php

/**
 * Include the default post content template
 * @param  ACFPost $post The WP_Post for the post wrapped in ACFPost object.
 */
function tpl_content( $post ) {
	tpl( 'content' , 'default' , array(
		'post' => $post,
	) );
}


/**
 * Include the page content template
 * @param ACFPost $page The WP_Post for the page wrapped in ACFPost object.
 */
function tpl_content_page( $page ) {

	$has_sidebar_content = page_has_family_tree( $page );

	tpl( 'content' , 'page' , array(
		'page' => $page,
		'has_sidebar_content' => $has_sidebar_content
	) );

}