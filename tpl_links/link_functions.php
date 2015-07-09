<?php

/**
 * Include the login link template
 * @param  string $label   	Link text. Default: 'Login'
 * @param  string $classes 	Classes to add to the <a> tag. Default: 'login'
 */
function tpl_link_login( $label = null, $classes = 'login' ){

	if( is_null( $label ) )
		$label = __('Login','theme');

	if( Settings::frontend_login_enabled() ) {
		$url = home_url('login');
	} else {
		$url = wp_login_url(); 
	}

	tpl('link','login', array(
		'url' => $url,
		'label' => $label,
		'classes' => $classes
	));
}



/**
 * Include the logout link template
 * @param  string $label   	Link text. Default: 'Logout'
 * @param  string $classes 	Classes to add to the <a> tag. Default: 'logout'
 */
function tpl_link_logout( $label = null, $classes = 'logout' ){

	if( is_null( $label ) )
		$label = __('Logout','theme');

	tpl('link','login', array(
		'url' => wp_logout_url(),
		'label' => $label,
		'classes' => $classes
	));
}



/**
 * Include the logout link template
 * @param  string $label   	Link text. Default: 'Logout'
 * @param  string $classes 	Classes to add to the <a> tag. Default: 'logout'
 */
function tpl_link_register( $label = null, $classes = 'register' ){

	if( is_null( $label ) )
		$label = __('Register','theme');

	tpl('link','register', array(
		'url' => home_url('register'),
		'label' => $label,
		'classes' => $classes
	));
}


/**
 * Include the profile link template
 * @param  string $label   	Link text. Default: 'Profile'
 * @param  string $classes 	Classes to add to the <a> tag. Default: 'profile'
 */
function tpl_link_profile( $label = null, $classes = 'profile' ){

	if( is_null( $label ) )
		$label = __('Profile','theme');

	if( Settings::frontend_profile_enabled() ) {
		$url = home_url('profile');
	} else {
		$url = admin_url('profile.php');
	}

	tpl('link','profile', array(
		'url' => $url,
		'label' => $label,
		'classes' => $classes
	));
}



/**
 * Include the reset password link template
 * @param  string $label   	Link text. Default: 'Reset Password'
 * @param  string $classes 	Classes to add to the <a> tag. Default: 'reset-password'
 */
function tpl_link_reset_password( $label = null, $classes = 'reset-password' ){

	if( is_null( $label ) )
		$label = __('Reset Password','theme');

	if( Settings::frontend_login_enabled() ) {
		$url = home_url('reset-password');
	} else {
		$url = wp_lostpassword_url(); 
	}

	tpl('link','reset-password', array(
		'url' => $url,
		'label' => $label,
		'classes' => $classes
	));
}



/**
 * Include the Read More link template
 * @param  string $url     URL to link to
 * @param  string $label   Link text. Default: 'Read More'
 * @param  string $classes Classes to add to the <a> tag. Default: 'read-more'
 */
function tpl_link_read_more( $url, $label = null, $classes = 'read-more' ) {

	if( is_null( $label ) ) 
		$label = __('Read More','theme');

	tpl('link','read-more', array(
		'url' => $url,
		'label' => $label,
		'classes' => $classes
	));
}