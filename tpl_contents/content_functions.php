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

/**
 * Include the attachment post content template
 * @param  ACFPost $post The WP_Post for the post wrapped in ACFPost object.
 */
function tpl_content_attachment( $page ) {

	$type = get_post_mime_type($page->ID);
	switch ($type) {
    case 'image/jpeg':
    case 'image/png':
    case 'image/gif':
			return tpl( 'content' , 'image' , array(
				'page' => $page,
			) ); break;
    case 'video/mpeg':
    case 'video/mp4':
    case 'video/quicktime':
			return tpl( 'content' , 'video' , array(
				'page' => $page,
			) ); break;
    case 'text/csv':
    case 'text/plain':
    case 'text/xml':
			return tpl( 'content' , 'text' , array(
				'page' => $page,
			) ); break;
  }

}
