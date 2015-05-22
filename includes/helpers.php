<?php


/**
 * Get some useful variables related to the page numner
 * @param  int $paged The WP global $paged variable or query parameter
 * @return array      Array of variables
 */
function get_paged_vars( $paged ) {

    $vars = array();

    if( $paged == 0 ) {
        $vars['page_number'] = 1;
        $vars['start_number'] = 1;
    } else {
        $posts_per_page = get_option('posts_per_page');
        $vars['page_number'] = $paged;
        $vars['start_number'] =  (( $posts_per_page * $paged ) - $posts_per_page ) + 1;
    }

    return $vars;
}




/**
 * Get a truncated snippet/excerpt from any content
 * This is a much more robust replacement for WP's excerpt field. This will generate a snippet from any text string,
 * taking into account HTML tags that might be cut off in the middle.
 * @param string $html HTML string to truncate
 * @param array $params Parameters array
 *          int $max_length Maximum character length to truncate the string to
 *          string $end_string Text to follow the truncated text
 *          string $before Append content before the truncated string 
 *          string $after Append content after the truncated string
 *          boolean $is_utf8 Is the string UTF8 encoded?
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