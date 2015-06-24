<?php

/**
 * Include some stuff
 */
include( 'plugins/plugins.php' );
include( 'includes/topbar-walker.class.php' );
include( 'includes/offcanvas-walker.class.php' );
include( 'includes/MemberTools.class.php' );
include( 'theme.class.php' );

/**
 * This little snippet automatically includes any function files in tpl_ folders according to the naming structure.
 * So, a file at path tpl_foos/foo_functions.php will be included here
 * 
 */

$folders = glob( dirname(__FILE__).'/tpl_*' ); // Get all our tpl_ folders

foreach( $folders as $folder ) {

	$foldername = pathinfo( $folder , PATHINFO_FILENAME ); // Get the folder name
	$prefix = substr( $foldername , 4 , strlen( $foldername ) - 5 ); // Infer the corresponding prefix name
	
	// If the matching functions file exists and is readable, include it.
	if( is_readable( $folder . '/' . $prefix . '_functions.php' ) ) require_once $folder . '/' . $prefix . '_functions.php';
}






/**
 * ================
 * The TPL Function
 * ================
 * */

/**
 * General purpose template include function.
 * This will look for a PHP file matching the naming stucture {$prefix}-{$name}.php
 * within a directory tpl_{$prefix}s (note the 's' at the end, the pluralization of the $prefix).
 * So calling tpl('foo','bar'); will look for tpl_foos/foo_bar.php
 * 
 * If a params array is passed in the third parameter, it will be available in the template
 * 
 * @param string $prefix The first segment of the filename
 * @param string $name The second segment of the filename
 * @param array $params An optional parameters array to pass to the template file
 */
function tpl( $prefix, $name = null, $params = null ) {

	// If no $name is provided, use 'default'
	if( is_null( $name ) )
		$name = 'default';

	$filepath = 'tpl_' . $prefix . 's/' . $prefix . '-' . $name . '.php';

	// If the matching file exists...
	if( is_readable( dirname(__FILE__) . '/' . $filepath ) ) {
		
		// Include the template
		include locate_template( $filepath ) ;

	// Otherwise trigger an error
	} else {
		trigger_error('No such template exists: ' . $filepath );
	}

}






/* ====================================== */
/* THEME HELPER FUNCTIONS */
/* ====================================== */



/**
 * Used on the index page template to get several variables related to the index
 * @param  ACF_Post  $page  The WP Post object wrapped in ACF Post object
 * @param  integer $paged 	The current page number
 * @return array         	Array of variables
 */
function get_index_vars( $page, $paged = 0 ) {

	global $wp_query;

	// Prepare our index query arguments
	$index_arguments = array(
		'post_type' => $page->index_post_type,
		'paged' => $paged
	);	

	// Prepare an empty taxonomy query
	$tax_query = array();

	// Loop through our URL parameters
	foreach ($_GET as $key => $value) {

		// Use the URL params to check if there are any taxonomies with matching query_vars
		$matching_taxonomies = get_taxonomies( array( 'query_var' => $key ), 'objects' );

		// If we have a match
		if( !empty( $matching_taxonomies ) ){

			// Set a taxonomy query with that taxonomy name and value(s)
			$tax_query[] = array(
				'taxonomy' => reset($matching_taxonomies)->name,
				'field' => 'slug',
				'terms' => explode( ',', $value )
			);
		}
		
	}
	// Apply the taxonomy query to the WP query arguments
	$index_arguments['tax_query'] = $tax_query;

	// Prepare an array of variables to return
	$vars = array(
		'index' => new WP_Query( $index_arguments ), // Run a WP_Query
		'post_type' => get_post_type_object( $page->index_post_type )
	);

	// If the page has enabled taxonomy filters...
	if( isset( $page->{ 'index_taxonomies_for_' . $page->index_post_type } ) ) {

		// Loop through each of them
		foreach( $page->{ 'index_taxonomies_for_' . $page->index_post_type } as $taxonomy ){

			// Get the full WP taxonomy object
			$taxonomy_object = get_taxonomy( $taxonomy );

			// Add a custom property "filter_style" to the object
			// Unless changed, this is either "single" or "multi", to allow admin to choose whether each taxonomy can use filter by multiple terms at once
			$taxonomy_object->filter_style = $page->{'filter_style_for_' . $page->index_post_type . '_' . $taxonomy};
			
			// Add them to the vars array
			$vars['taxonomies'][] = $taxonomy_object;
		}
	}
	return $vars;
}






/**
 * Get some useful variables related to the page number.
 * These are used, for instance, to label paginated list pages
 * @param  WP_Query $query A WP Query object from which to generate page variables
 * @return array {
 *         Array of pagination-related variables
 *         
 *         @var int $page_number	Current page number
 *         @var int $start_number	The number of the first post on this page
 *         @var int $end_number		The number of the last post on this page
 *         @var int $total_number	The total number of posts found in the query
 * }
 */
function get_paged_vars( $query ) {

    $page_number = empty( $query->query['paged'] ) ? 1 : $query->query['paged'] ;
    $start_number = (( $query->query_vars['posts_per_page'] * $page_number ) - $query->query_vars['posts_per_page'] ) + 1;

    return array(
        'page_number' => $page_number,
        'start_number' => $start_number,
        'end_number' => $start_number + ( $query->post_count - 1 ),
        'total_number' => $query->found_posts
    );
}






/**
 * Check if a page has a family tree, i.e. it has children or ancestors
 * @param  int/WP_Post $post A page ID or WP Post object
 * @return boolean       
 */
function page_has_family_tree( $post = null ){
	
	if( is_null( $post ) ) {
		global $post;
	} else {
		if( is_int( $post ) )
			$post = get_post( $post );
	}

	$cached = wp_cache_get( 'page_has_family_tree_' . $post->ID );

	if( $cached ) return $cached;

	$ancestors = get_ancestors( $post->ID, 'page' );

	if( !empty( $ancestors ) ) return true;

	$children = get_posts(array(
		'post_type' => 'page',
		'post_parent' => $post->ID,
		'child_of' => $post->ID
	));

	if( !empty( $children ) ) return true;

	return false;
}






/**
 * Get the page hierarchy associated with a page
 * @param  int/WP_Post/ACF_Post $page 	Page ID or post object
 * @return array       					Hierarchical array of pages and their descendants
 *
 * @see includes/helpers.php for the recursive add_descendants() function
 */
function get_page_tree( $page ) {

	// If its an ID, get the post object
	if( is_int( $page ) )
		$page = get_post( $page );

	// Get the linear array of the page's ancestors.
	// The last item in the array will always be a top level page
	$ancestors = get_ancestors( $page->ID, 'page' );

	$tree = array();

	// If there are no ancestors, this is a top level page
	if( empty( $ancestors ) ){
		$root_ancestor = $page->ID;

	// The ancestral post is the last one
	} else {
		$root_ancestor = $ancestors[ count( $ancestors ) - 1 ];
	}

	// See if theres a cached copy
	$cached_tree = wp_cache_get( 'page_tree_' . $root_ancestor );

	// If so, return it
	if( $cached_tree ) {
		return $cached_tree;
	}

	// Add the root ancestor as the root item in our tree
	$tree[0] = get_post( $root_ancestor );

	// Add any descendants recursively
	add_descendents( $tree[0] );

	// Cache the results
	wp_cache_set( 'page_tree_' . $root_ancestor, $tree );

	return $tree;

}






/**
 * Recursive function to add an array of child posts to any post object
 * as a property called "children".
 * @param WP_Post &$post A post object
 */
function add_descendents( &$post ) {

    // Get any immediate children of this post
    $children = get_posts(array(
        'post_type' => $post->post_type,
        'post_parent' => $post->ID,
        'child_of' => $post->ID
    ));

    // If there are children, add them to the object
    if( !empty( $children ) ){
        
        $post->children = $children;

        // Loop through each child and recursively call this function again to add their children
        foreach( $children as $child ){
            add_descendents( $child );
        }
    }

    return;
}






/**
 * Generic recursive function to generate a nested list of posts or taxonomy terms
 * @param  array &$items Array of posts or terms
 * @param  array  $params Parameters array
 * @return string         HTML
 */
function nested_list( &$items, $params = array() ) {

    // We need the global post object, so we can identify the active item
    global $post;

    // Default parameters
    $defaults = array(
        'items_wrap' => '<ul class="side-nav">%s</ul>',
        'item_wrap' => '<li class="%3$s">%1$s%2$s</li>',
        'item_class' => '',
        'link_items' => 1,
        'object_type' => 'post', // or 'term'
        'child_key' => 'children'
    );
    $params = array_merge( $defaults, $params );
    extract( $params );

    // Prepare empty HTML string
    $html = '';

    // Loop through each item
    foreach( $items as $item ){

        // Empty string for child elements
        $children = '';

        // Empty string for item's HTML
        $item_html = '';

        $this_item_class = $item_class;

        if( $post->ID == $item->ID )
            $this_item_class .= ' active';
        
        // If its a post object (any post type)
        if( $object_type == 'post' ) {

            // If its an ID, get the post object
            if( is_int( $item ) )
                $item = get_post( $item );

            // Wrap title in permalink by default
            if( $link_items ) {
                $item_html .= '<a href="' . get_permalink( $item->ID ) . '">' . $item->post_title . '</a>';
            
            // Otherwise just output the title
            } else {
                $item_html .= $item->post_title;
            }

            // If there are child items, recursively run this function again to generate a nested list
            if( isset( $item->{$child_key} ) ){
                $children = nested_list( $item->{$child_key}, $params );
            }


        // If its a taxonomy term
        } elseif( $object_type == 'term' ) {
            // TO DO: make this work for taxonomy terms too
        }

        // Wrap item HTML in our item wrap
        $html .= sprintf( $item_wrap, $item_html, $children, $this_item_class );
    }

    // Wrap HTML in our items wrap
    $html = sprintf( $items_wrap, $html );

    return $html;
}






/**
 * Get a truncated snippet/excerpt from any content
 * This is a much more robust replacement for WP's excerpt field. This will generate a snippet from any text string,
 * taking into account HTML tags that might be cut off in the middle.
 * @param string $html HTML string to truncate
 * @param array $params {
 *          Parameters array
 *          
 *          @var int $max_length Maximum character length to truncate the string to
 *          @var string $end_string Text to follow the truncated text
 *          @var string $before Append content before the truncated string 
 *          @var string $after Append content after the truncated string
 *          @var boolean $is_utf8 Is the string UTF8 encoded?
 * }
 * @return string
 */
function truncate( $html, $params ) {

    $defaults = array(
        'max_length' => 200,
        'end_string' => '…',
        'before' => '',
        'after' => '',
        'is_utf8' => true,
        'allowed_tags' => '<p>'
    );

    extract( array_merge( $defaults, $params ) );

    $html = strip_tags( $html, $allowed_tags );

    if( strlen( $html ) <= $max_length ) {
        return wpautop( $before . $html . $after );
    }
    
    $output = "";
    $printedLength = 0;
    $position = 0;
    $tags = array();

    // For UTF-8, we need to count multibyte sequences as one character.
    $re = $is_utf8
        ? '{</?([a-z]+)[^>]*>|&#?[a-zA-Z0-9]+;|[\x80-\xFF][\x80-\xBF]*}'
        : '{</?([a-z]+)[^>]*>|&#?[a-zA-Z0-9]+;}';

    while( $printedLength < $max_length && preg_match( $re, $html, $match, PREG_OFFSET_CAPTURE, $position ) ) {
        
        list( $tag, $tagPosition ) = $match[0];

        // Print text leading up to the tag.
        $str = substr( $html, $position, $tagPosition - $position );
        
        //check to see if adding this text to the output would put us over the max length
        if( $printedLength + strlen($str) > $max_length){
            
            if( preg_match( '{\b}', $str, $wordBoundary, PREG_OFFSET_CAPTURE, $max_length - $printedLength ) ) {
                
                //we found a word boundary after the truncation point
                $wordBoundary = $wordBoundary[0][1]; //linearize to the position of the boundary
                $output .= substr( $str, 0, $wordBoundary );
                $printedLength += $wordBoundary;
            
            } else {
                //there's no word boundary after the truncation point
                $output .= substr($str, 0, $max_length - $printedLength);
                $printedLength = $max_length;
            }

            break;
        }

        $output .= $str;
        $printedLength += strlen($str);
        
        if( $printedLength >= $max_length ) break;

        if( $tag[0] == '&' || ord( $tag ) >= 0x80 ){

            // Pass the entity or UTF-8 multibyte sequence through unchanged.
            $output .= $tag;
            $printedLength++;

        } else {

            // Handle the tag.
            $tagName = $match[1][0];

            if( $tag[1] == '/' ){

                // This is a closing tag.
                $openingTag = array_pop($tags);
                assert( $openingTag == $tagName ); // check that tags are properly nested.
                $output .= $tag;

            } else if( $tag[strlen($tag) - 2] == '/' ){

                // Self-closing tag.
                $output .= $tag;
            
            } else {
                
                // Opening tag.
                $output .= $tag;
                $tags[] = $tagName;
            }
        }

        // Continue after the tag.
        $position = $tagPosition + strlen($tag);
    }

    // Print any remaining text.
    if( $printedLength < $max_length && $position < strlen( $html ) )
        $output .= substr($html, $position, $max_length - $printedLength);
    
    // Concatenate all the parts together
    $output = $before . $output . $end_string . $after;
    
    // Close any open tags.
    while (!empty($tags))
        $output .= '</'.array_pop($tags).'>';
    
    return wpautop( $output, false );

}






/**
 * Dump preformatted data to the page
 * @param mixed $data object/array/string/whatever to dump on the page
 */
function dump( $data ){

    echo '<pre>';
    print_r( $data );
    echo '</pre>';

}