<?php

/**
 * Include the template to display a query's currently applied taxonomy filters
 *
 * @param  WP_Query $query 			A WP Query object, possibly containing a tax_query with currently applied term filters
 */
function tpl_block_current_taxonomy_filters( $query ){
	tpl('block','current-taxonomy-filters',array(
		'query' => $query
	));
}


/**
 * Include the comments template for a post
 * @param  ACFPost $post WP Post object wrapped in ACF Post object
 */
function tpl_block_comments( $post, $status = 'approve' ) {

	global $user_identity;

	$required = get_option( 'require_name_email' );

	tpl('block','comments',array(
		'post' => $post,
		'comments' => get_comments(array(
			'post_id' => $post->ID,
			'status' => $status
		)),
		'user_identity' => $user_identity,
		'commenter' => wp_get_current_commenter(),
		'required' => $required,
		'aria_required' => $required ? ' aria-required="true"' : ''

	));
}



/**
 * General purpose status message alert box, for use on home page, or any other page where
 * the query_var('status') might be set
 * @param  string $status Status name
 */
function tpl_block_status( $status ){

	switch( (string)$status ){

		// Various pending statuses are displayed on the home page after a redirect,
		// thats why we are using the general purpose status message, and not an action-specific message function
		case 'registration-pending':
			$message = __('Thank you for registering! Please check your email for a message containing further instructions on how to activate your profile. You will not be able to log in until your profile is activated.','theme');
			$class = '';
			break;

		case 'password-reset-pending':
			$message = __('Please check your email for a message containing further instructions on how to reset your password.','theme');
			$class = '';
			break;

		case 'activation-pending':
			$message = __('Please check your email for a message containing further instructions on how to activate your profile. You will not be able to log in until your profile is activated.','theme');
			$class = '';
			break;

		default:
			$message = '';
			$class = '';
	}

	tpl_block_alert( $message, $class );

}



/**
 * Display an alert box with a message corresponding to the provided login status name
 * @param  string $status Status string from query var
 */
function tpl_block_login_status( $status ){

	switch( (string)$status ) {

		case 'error':

			switch( get_query_var('login_error','') ){
				case 'email':
					$message = __('Please enter the email address you registered with.','theme');
					$class = 'alert';
					break;

				case 'password':
					$message = sprintf(__('Please enter your password. If you\'ve forgotten it, you can <a href="%s">reset your password here</a>.','theme'), home_url('reset-password'));
					$class = 'alert';
					break;

				case 'profile':
					$message = __('There is no user profile matching the email address you entered. Perhaps you registered with a different email address.','theme');
					$class = 'alert';
					break;

				case 'failed':
					$message =  sprintf(__('The email address and password combination you entered is incorrect. If you\'ve forgotten your password, you can <a href="%s">reset it here</a>.','theme'), home_url('reset-password'));
					$class = 'alert';
					break;

				case 'activate':
					$message =  sprintf(__('Your profile has not been activated yet. Please click the link in the email you received when you registered, or <a href="%s">resend the activation email</a>.','theme'), home_url('activate'));
					$class = 'alert';
				}
			break;

		case 'restricted':
			$redirect_page = get_page_by_path( get_query_var('redirect','profile') );
			$message = sprintf(__('You must be logged in to view the %s page','theme'), $redirect_page->post_title );
			$class = 'alert';
			break;

		case 'activate':
			$message = __('Please login to activate your profile and complete the registration process.','theme');
			$class = '';
			break;
	}

	tpl_block_alert( $message, $class );
}



/**
 * Display an alert box with a message corresponding to the provided profile status name
 * @param  string $status Status string from query var
 */
function tpl_block_profile_status( $status ){

	switch( (string)$status ) {

		case 'error':
			$message = __('There was a problem saving your profile, please make sure all required fields are filled.','theme');
			$class = 'alert';
			break;

		case 'update':
			$message = __('Your profile has been updated.','theme');
			$class = 'success';
			break;

		case 'new-password':
			$message = __('Your new password has been saved.','theme');
			$class = 'success';
			break;

		case 'created':
			$message = __('Welcome! Your profile has successfully been created.','theme');
			$class = 'success';
			break;

	}

	tpl_block_alert( $message, $class );
}



/**
 * Display an alert box with a message corresponding to the provided registration error name
 * @param  string $error Error string from query var
 */
function tpl_block_register_error( $error ){

	switch( (string)$error ) {

		case 'incomplete':
			$message = __('Please enter all required fields below.','theme');
			$class = 'alert';
			break;

		case 'match':
			$message = __('Your password and confirmation do not match. Please try entering them again.','theme');
			$class = 'alert';
			break;

		case 'email':
			$message = sprintf( __('There was a problem sending your confirmation email, please <a href="mailto:%s">contact the website\'s administrator</a>.','theme'), get_option('admin_email'));
			$class = 'alert';
			break;

	}

	tpl_block_alert( $message, $class );

}



function tpl_block_password_reset_status( $stage, $status ){

	// Either requesting a password reset,
	// or entering a new password
	switch( (string)$stage ){

		// The initial password-reset request stage
		case 'request':

			switch( (string)$status ){

				// Error status.
				// No other statuses exist at the request stage, but we'll leave it as a switch() incase a new status is needed
				case 'error':
					$error = get_query_var('reset_error','');
					$class = 'alert';

					switch( $error ){

						case 'invalid':
							$message = __('Your reset-password link has expired, or is invalid. Please enter your email address again to receive a new link.','theme');
							break;

						case 'submission':
							$message = __('Please enter the email address you provided when you registered your account.','theme');
							break;

						case 'user':
							$message = __('There is no account associated with the email address or username you entered. Perhaps you used a different email address.','theme');
							break;

						case 'email':
							$message = sprintf( __('There was a problem sending your reset-password email, please <a href="mailto:%s">contact the website\'s administrator</a>.','theme'), get_option('admin_email'));
							break;

						default:
							$message = sprintf( __('An unknown error has occured in trying to request a new password, please <a href="mailto:%s">contact the website\'s administrator</a>.','theme'), get_option('admin_email'));
					}
					break;

			}
			break;

		// The second stage, when user is entering a new password
		case 'new':

			switch( (string)$status ){

				// Error status.
				// No other statuses exist at the request stage, but we'll leave it as a switch() incase a new status is needed
				case 'error':
					$error = get_query_var('reset_error','');
					$class = 'alert';

					switch( $error ){

						case 'password':
							$message = __('Please enter a new password and confirmation.','theme');
							break;

						case 'confirm':
							$message = __('Please confirm your new password by entering it again.','theme');
							break;

						case 'match':
							$message = __('Your password and confirmation do not match. Please try entering them again.','theme');
							break;

						default:
							$message = sprintf( __('An unknown error has occured in trying to save your new password, please <a href="mailto:%s">contact the website\'s administrator</a>.','theme'), get_option('admin_email'));
					}
					break;
			}
			break;

	}

	tpl_block_alert( $message, $class );

}



/**
 * Display an alert box with a message corresponding to the provided activation status name
 * @param  string $status Status string from query var
 */
function tpl_block_activation_status( $status ){

	switch( (string)$status ){

		// Error status.
		// No other activation statuses exist, but we'll leave it as a switch() incase a new status is needed		
		case 'error':
			$error = get_query_var('activation_error');
			$class = 'alert';

			switch( $error ){

				case 'submission':
					$message = __('Please enter the email address you provided when you registered your account.','theme');
					break;

				case 'user':
					$message = __('There is no account associated with the email address you entered. Perhaps you used a different email address.','theme');
					break;

				case 'inactive':
					$message = __('Your profile has not been activated yet. Please click the link in the email you received when you registered, or enter your email address below to receive new instructions on how to activate your profile.','theme');
					break;

				default:
					$message = sprintf( __('An unknown error has occured in trying to activate your profile, please <a href="mailto:%s">contact the website\'s administrator</a>.','theme'), get_option('admin_email'));
			}
			break;
	}

	tpl_block_alert( $message, $class );

}




/**
 * Include the alert block template 
 * @param string $message Alert message to display
 * @param string $class alert-box class name.
 * @see http://foundation.zurb.com/docs/components/alert_boxes.html
 */
function tpl_block_alert( $message, $class = '' ) {
	tpl( 'block' , 'alert' , array(
		'message' => $message,
		'class' => $class,
	) );
}