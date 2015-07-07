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
 * Include the registration form template 
 * @param WP_User $user
 */
function tpl_form_register(){

	wp_enqueue_script( 'password-strength-meter' );

	tpl( 'form' , 'register' );

}



/**
 * Include the reset password form template
 */
function tpl_form_reset_password(){

	$reset_error = get_query_var('reset_error', 0);
	$reset_pending = get_query_var('reset_pending', 0);
	$key = get_query_var('reset_key', 0);
	$username = get_query_var('reset_username', 0);
	$status_message = null;

	if( $key && $username ) {

		if( MemberTools::validate_reset_key( $key, $username ) ){
			
			wp_enqueue_script( 'password-strength-meter' );

			switch( (string)$reset_error ) {
				case 'password':
					$status_message = __('Please enter a new password and confirmation.','theme');
					break;

				case 'confirm':
					$status_message = __('Please confirm your new password.','theme');
					break;

				case 'match':
					$status_message = __('Your password and confirmation do not match. Please try entering them again.','theme');
					break;
			}

			tpl( 'form' , 'new-password', array(
				'user' => get_user_by( 'login', $username ),
				'status_message' => $status_message,
				'alert_level' => 'alert'
			));

		} else {

			tpl( 'form' , 'reset-password' , array(
				'status_message' => __('Your reset-password link has expired, or is invalid. Please enter your username or email address again to receive a new link.','theme'),
				'alert_level' => 'alert'
			));

		}

	} elseif( $reset_error ) {

		switch( $reset_error ){
			case 'submission':
				$status_message = __('Please enter either your username, or the email address you provided when you registered your account.','theme');
				break;

			case 'user':
				$status_message = __('There is no account associated with the email address or username you entered. Perhaps you used a different email address.','theme');
				break;

			case 'invalid':
				$status_message = __('Your reset-password link has expired, or is invalid. Please enter your username or email address again to receive a new link.','theme');
				break;

			default:
				$status_message = __('An unknown error has occurred, please try again.','theme');
		}

		tpl( 'form' , 'reset-password' , array(
			'status_message' => $status_message,
			'alert_level' => 'alert'
		));
		
	} elseif( $reset_pending ) {
		
		tpl( 'form' , 'reset-password' , array(
			'status_message' => __('Please check your email for a message containing further instructions on how to reset your password.','theme'),
			'alert_level' => 'success'
		));

	} else {

		tpl( 'form' , 'reset-password' , array(
			'status_message' => '',
			'alert_level' => ''
		));

	}

}



/**
 * Include the search form template
 */
function tpl_form_search() {
	tpl( 'form' , 'search' );
}