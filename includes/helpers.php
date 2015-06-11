<?php

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