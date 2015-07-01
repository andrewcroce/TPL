<?php

/**
 * Include the login form template
 * @param string 		$form_id 			Text to use for the HTML form ID
 * @param string/int 	$redirect 			Page ID or slug to redirect to on login
 */
function tpl_form_login( $form_id, $redirect = null ) {

	if( is_null( $redirect ) )
		$redirect = 'profile';

	if( is_int( $redirect ) ) {
		$redirect_url = get_permalink( $redirect );
	} else {
		$redirect_url = get_permalink( get_page_by_path( $redirect ) );
	}

	tpl( 'form' , 'login', array(
		'form_id' => $form_id,
		'redirect_url' => $redirect_url
	));
}



/**
 * Include the profile form template 
 * @param WP_User $user
 */
function tpl_form_profile( $user = null ) {

	if( is_null( $user ) )
		$user = get_userdata( get_current_user_id() );

	$user_meta = array_map( function( $a ){ return $a[0]; }, get_user_meta( $user->ID ) );

	if( $user ){

		wp_enqueue_script( 'password-strength-meter' );

		tpl( 'form' , 'profile' , array(
			'user' => $user,
			'user_meta' => (object) $user_meta
		));

	}
}

/**
 * Include the search form template
 */
function tpl_form_search() {
	tpl( 'form' , 'search' );
}