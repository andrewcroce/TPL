<?php

/**
 * Include the default item template
 * @param  ACFPost $post         WP Post object wrapped in ACF Post object
 * @param  boolean $include_date Show the post date?
 */
function tpl_item( $post, $include_date = true ) {

	tpl( 'item' , 'default' , array(
		'post' => $post,
		'include_date' => $include_date
	) );

}