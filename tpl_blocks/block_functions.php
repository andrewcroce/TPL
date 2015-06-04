<?php

/**
 * Include the template to display a query's currently applied taxonomy filters
 *
 * @param  WP_Query $query 			A WP Query object, possibly containing a tax_query with currently applied term filters
 */
function tpl_block_current_taxonomy_filters( $query ){
	tpl('block','current-taxonomy-filters',array(
		'query' => $query
	));
}


/**
 * Include the comments template for a post
 * @param  ACFPost $post WP Post object wrapped in ACF Post object
 */
function tpl_block_comments( $post, $status = 'approve' ) {

	global $user_identity;

	$required = get_option( 'require_name_email' );

	tpl('block','comments',array(
		'post' => $post,
		'comments' => get_comments(array(
			'post_id' => $post->ID,
			'status' => $status
		)),
		'user_identity' => $user_identity,
		'commenter' => wp_get_current_commenter(),
		'required' => $required,
		'aria_required' => $required ? ' aria-required="true"' : ''

	));
}