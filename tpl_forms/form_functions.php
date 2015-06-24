<?php

/**
 * Include the login form template
 */
function tpl_form_login( $form_id, $redirect = null ) {

	if( is_null( $redirect ) )
		$redirect = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

	tpl( 'form' , 'login', array(
		'form_id' => $form_id,
		'redirect' => $redirect
	));
}