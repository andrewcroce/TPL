<?php

/**
 * Include the comments template for a post
 * @param  ACFPost $post WP Post object wrapped in ACF Post object
 */
function tpl_comments( $post, $status = 'approve' ) {

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