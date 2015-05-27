<?php 
/**
 * Pagination Template - tpl_pagination()
 * 
 * @var array $params {
 *      
 *      Parameters passed into the template from tpl_pagination()
 *
 * 		@var  WP_Query $query     A WP Query object from which to generate pagination
 *      @var  string $prev_text   Text for the prev link. Default: "« Previous"
 *      @var  string $next_text   Text for the next link. Default: "Next »"
 * }	
 * 
 */ 

extract( $params ); ?>

<nav class="pagination">

<?php
// see https://codex.wordpress.org/Function_Reference/paginate_links

$giant_number = 999999999;

echo paginate_links( array(
	
	// (string) (optional) Used to reference the url, which will be used to create the paginated links. 
	'base' => str_replace( $giant_number, '%#%', esc_url( get_pagenum_link( $giant_number ) ) ),

	// (string) (optional) Used for Pagination structure. 
	'format' => '/page/%#%',

	// (integer) (optional) The total amount of pages.
	'total' => $query->max_num_pages,

	// (integer) (optional) The current page number.
	'current' => max( 1, $query->query_vars['paged'] ),
	
	// (boolean) (optional) If set to True, then it will show all of the pages instead of a short list of the pages near the current page.
	// By default, the 'show_all' is set to false and controlled by the 'end_size' and 'mid_size' arguments.
	'show_all' => false,
	
	// (integer) (optional) How many numbers on either the start and the end list edges.
	'end_size' => 1,

	// (integer) (optional) How many numbers to either side of current page, but not including current page.
	'mid_size' => 2,

	// (boolean) (optional) Whether to include the previous and next links in the list or not.
	'prev_next' => true,

	// (string) (optional) The previous page text. Works only if 'prev_next' argument is set to true.
	'prev_text' => $prev_text,

	// (string) (optional) The next page text. Works only if 'prev_next' argument is set to true.
	'next_text' => $next_text,

	// (string) (optional) Controls format of the returned value. Possible values are:
	// 'plain' - A string with the links separated by a newline character.
	// 'array' - An array of the paginated link list to offer full control of display.
	// 'list' - Unordered HTML list.
	'type' => 'list',

	// (array) (optional) An array of query args to add.
	'add_args' => false,

	// (string) (optional) A string to append to each link.
	'add_fragment' => '',

	// (string) (optional) A string to appear before the page number.
	'before_page_number' => '',

	// (string) (optional) A string to append after the page number.
	'after_page_number' => ''
) );
?>

</nav>