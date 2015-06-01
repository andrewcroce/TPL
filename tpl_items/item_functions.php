<?php

/**
 * Include the default item template
 * @param  ACFPost $post         WP Post object wrapped in ACF Post object
 * @param  boolean $include_date Show the post date?
 */
function tpl_item( $post, $include_date = true ) {

	tpl('item', 'default', array(
		'post' => $post,
		'include_date' => $include_date
	) );

}


/**
 * Include the default page template
 * @param  ACFPost $page WP Post object wrapped in ACF Post object
 */
function tpl_item_page( $page ) {
	tpl('item', 'page', array(
		'page' => $page
	) );
}


/**
 * Include the default comment template
 * Note, this should probably only be used as a callback for wp_list_comments()
 * 
 * @param  object $comment 	WP Comment object
 * @param  array $args    	Arguments from wp_list_comments()
 * @param  int $depth   	Comment depth, i.e. is it a top-level comment, a reply, or a reply to a reply, etc.
 */
function tpl_comment( $comment, $args, $depth ) {

	tpl('item', 'comment', array(
		'comment' => $comment,
		'args' => $args,
		'depth' => $depth
	));

}

function tpl_comment_end( $comment, $args, $depth ){
	echo '</li>';
}