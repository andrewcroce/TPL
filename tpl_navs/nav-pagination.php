<?php

$giant_number = 999999999;

echo paginate_links( array(
	'base' => str_replace( $giant_number, '%#%', esc_url( get_pagenum_link( $giant_number ) ) ),
	'format' => '?paged=%#%',
	'current' => max( 1, $nav->query_vars['paged'] ),
	'total' => $nav->max_num_pages
) );
