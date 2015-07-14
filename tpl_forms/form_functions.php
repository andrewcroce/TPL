<?php
/**
 * This file contains form TEMPLATE include functions.
 * Form processing (for custom forms) occurs in on of the class files,
 * in a function hooked to add_action('form_action_{do_something}',Class::_do_something);
 */



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

	if( get_query_var('login_status') == 'activate' )
		$redirect_url .= 'created';

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
 * Include the registration form template 
 * @param WP_User $user
 */
function tpl_form_register(){

	wp_enqueue_script( 'password-strength-meter' );

	tpl( 'form' , 'register' );

}



/**
 * Include the reset password form template
 * Depending on the stage, either the request form, or the new password form
 */
function tpl_form_reset_password(){

	if( get_query_var('reset_stage') == 'new' ) {

		$user = get_user_by( 'email', urldecode( get_query_var('reset_email') ) );

		if( !$user ){
			wp_redirect( home_url('reset-password/request/error/invalid') );
			exit;
		}

		wp_enqueue_script( 'password-strength-meter' );
		tpl( 'form' , 'new-password', array(
			'user' => $user
		));

	} else {

		tpl( 'form' , 'request-reset-password' );
	
	}

}



/**
 * Include the profile activation form
 */
function tpl_form_activation(){
	tpl( 'form' , 'activation' );
}



/**
 * Include the search form template
 */
function tpl_form_search() {
	tpl( 'form' , 'search' );
}