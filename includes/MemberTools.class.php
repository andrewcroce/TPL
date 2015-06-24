<?php

class MemberTools {

	public static function try_login( $params ){

		extract( $params );

		$credentials = array(
			'user_password' => $login_password
		);

		if( filter_var( $login_username_email, FILTER_VALIDATE_EMAIL ) ) {
			$credentials['user_email'] = $login_username_email;
		} else {
			$credentials['user_login'] = $login_username_email;
		}

		$attempt = wp_signon( $credentials, false );

		if ( is_wp_error( $attempt ) ) {
			echo $attempt->get_error_message();
		} else {
			wp_set_current_user( $attempt->ID );
			wp_set_auth_cookie( $attempt->ID );
			wp_safe_redirect( $redirect );
		}

	}

}