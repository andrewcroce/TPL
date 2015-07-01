<?php

class Topbar_Walker_Nav_Menu extends Walker_Nav_Menu {

	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);

		/**
		 * Change the Submenu class name to match Foundation's required class,
		 * Append the back link as the first item
		 */
	    $output .= "\n$indent<ul class=\"dropdown\">\n";
	}


	 function start_el(  &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

	    $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

	    $classes = empty( $item->classes ) ? array() : (array) $item->classes;

	    $classes[] = 'menu-item-' . $item->ID;
	    $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );

	    /**
	     * Change WP's default classes to match Foundation's required classes
	     */
	    $class_names = str_replace(
	    	array('menu-item-has-children','current-menu-item'),
	    	array('has-dropdown','active'),
	    	$class_names
	    );
	    // ==========================

	    $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

	    $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
	    $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

	    $output .= $indent . '<li' . $id . $class_names .'>';

	    $atts = array();
	    $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
	    $atts['target'] = ! empty( $item->target )     ? $item->target     : '';
	    $atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
	    $atts['href']   = ! empty( $item->url )        ? $item->url        : '';
	    $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

	    $attributes = '';
	    foreach ( $atts as $attr => $value ) {
	    	if ( ! empty( $value ) ) {
	    		$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
	    		$attributes .= ' ' . $attr . '="' . $value . '"';
	    	}
	    }

	    $item_output = $args->before;
	    $item_output .= '<a'. $attributes .'>';
	    $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
	    $item_output .= '</a>';
	    $item_output .= $args->after;

	    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}