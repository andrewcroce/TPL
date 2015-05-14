<?php

/**
*
* Get a truncated snippet/excerpt from any content
* This is a much more robust replacement for WP's excerpt field. This will generate a snippet from any text string,
* taking into account HTML tags that might be cut off in the middle.
* 
**/
function truncate( $html, $maxLength, $pad = '…', $before = '', $after = '', $isUtf8 = true ) {

    if( strlen( $html ) <= $maxLength ) {
        return wpautop( $before . $html . $after );
    }
    
    $output = "";
    $printedLength = 0;
    $position = 0;
    $tags = array();

    // For UTF-8, we need to count multibyte sequences as one character.
    $re = $isUtf8
        ? '{</?([a-z]+)[^>]*>|&#?[a-zA-Z0-9]+;|[\x80-\xFF][\x80-\xBF]*}'
        : '{</?([a-z]+)[^>]*>|&#?[a-zA-Z0-9]+;}';

    while( $printedLength < $maxLength && preg_match( $re, $html, $match, PREG_OFFSET_CAPTURE, $position ) ) {
        
        list( $tag, $tagPosition ) = $match[0];

        // Print text leading up to the tag.
        $str = substr( $html, $position, $tagPosition - $position );
        
        //check to see if adding this text to the output would put us over the max length
        if( $printedLength + strlen($str) > $maxLength){
            
            if( preg_match( '{\b}', $str, $wordBoundary, PREG_OFFSET_CAPTURE, $maxLength - $printedLength ) ) {
                
                //we found a word boundary after the truncation point
                $wordBoundary = $wordBoundary[0][1]; //linearize to the position of the boundary
                $output .= substr( $str, 0, $wordBoundary );
                $printedLength += $wordBoundary;
            
            } else {
                //there's no word boundary after the truncation point
                $output .= substr($str, 0, $maxLength - $printedLength);
                $printedLength = $maxLength;
            }

            break;
        }

        $output .= $str;
        $printedLength += strlen($str);
        
        if( $printedLength >= $maxLength ) break;

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
    if( $printedLength < $maxLength && $position < strlen( $html ) )
        $output .= substr($html, $position, $maxLength - $printedLength);
    
    //add the pad characters
    $output .= $pad;
    
    // Close any open tags.
    while (!empty($tags))
        $output .= '</'.array_pop($tags).'>';
    
    return wpautop( $before . $output . $after );

}






/**
 * Dump preformatted data to the page
 */
function dump( $data ){

    echo '<pre>';
    print_r( $data );
    echo '</pre>';

}