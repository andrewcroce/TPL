<?php

/**
 * Include default nav menu template
 * @param  string $menu_location A registered WP menu location. Default: 'main_menu'
 * @param  string $id            ID to add to the <nav> element. Default: value of $menu_location
 * @param  string $class         Classes to add to the <ul> element. Default: ''
 */
function tpl_nav( $menu_location = 'main_menu', $id = '', $classes = '' ) {

	if( empty( $id ) ) $id = $menu_location;

	tpl('nav','default',array(
		'menu_location' => $menu_location,
		'id' => $id,
		'classes' => $classes
	));

}


/**
 * Include paginationt template
 * @param  WP_Query $query     A WP Query object from which to generate pagination
 * @param  string $prev_text   Text for the prev link. Default: "« Previous"
 * @param  string $next_text   Text for the next link. Default: "Next »"
 */
function tpl_pagination( $query, $prev_text = '', $next_text = '' ) {

	if( empty( $prev_text ) ) $prev_text = __('« Previous');
	if( empty( $next_text ) ) $next_text = __('Next »');

	tpl('nav','pagination',array(
		'query' => $query,
		'prev_text' => $prev_text,
		'next_text' => $next_text
	));
}