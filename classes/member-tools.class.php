<?php

/**
 * @package StarterThemeMemberToolsModule
 */



// includes were here, they have since been removed....


if(!class_exists('MemberTools')){
	
	class MemberTools {
		
		
		/**
		*
		* Load
		* Primarily used to set up action and filter hooks.
		**/
		public static function load() {
			
			add_action('after_switch_theme', 					array(__CLASS__,'_theme_activated'));
			add_action('init', 									array(__CLASS__,'_init'));
			add_action('admin_menu',							array(__CLASS__,'_admin_menu'));
			add_action('template_redirect', 					array(__CLASS__,'_template_redirect'));
			add_action('update_option_member_tools_settings',	array(__CLASS__,'_updated_member_tools_settings'), 10, 3);
			
			add_action('wp_logout', 							array(__CLASS__,'_wp_logout'));
			add_action('form_action_user_reset_password', 		array(__CLASS__,'_user_reset_password'));
			add_action('form_action_user_new_password',  		array(__CLASS__,'_user_new_password'));
			add_action('form_action_user_register',				array(__CLASS__,'_user_register'));
			add_action('form_action_user_save_profile',			array(__CLASS__,'_user_save_profile'));
			add_action('form_action_user_send_activation',		array(__CLASS__,'_user_send_activation'));
			
			add_filter('query_vars', 			array(__CLASS__,'_query_vars'));
			add_filter('rewrite_rules_array', 	array(__CLASS__,'_rewrite_rules_array'));
			add_filter('login_form_top', 		array(__CLASS__,'_login_form_top'));
			add_filter('authenticate', 			array(__CLASS__, '_authenticate'), 100, 3);
			add_filter('lostpassword_url', 		array(__CLASS__,'_lostpassword_url'), 10, 2);
			add_filter('register', 				array(__CLASS__,'_register'));			

		}

		


		/**
		 * Theme activation
		 * This runs when the theme is activated
		 */
		static function _theme_activated() {

			// Generate login and reset-password pages if the setting is enabled
			if( Settings::frontend_login_enabled() ){
				self::_generate_login_pages();
			}

			// Generate profile page if the setting is enabled
			if( Settings::frontend_profile_enabled() ){
				self::_generate_profile_page();
			}

			// Generate profile page if the setting is enabled
			if( Settings::frontend_registration_enabled() ){
				self::_generate_registration_page();

				if( Settings::registration_activation_required() ){
					self::_generate_activation_page();
				}
			}
			

		}



		/**
		 * WP Initialized
		 */
		static function _init(){

			/**
			 * Require our custom ACF Fieldset for the reset-password page content if front end login is enabled
			 */
			if( Settings::frontend_login_enabled() )
				require get_template_directory() . '/includes/acf_fieldsets/reset_password_page_content.php';

		}



		/**
		 * Admin Menu hook
		 */
		static function _admin_menu(){

			// If this is a post edit page
			if( isset( $_GET['post'] ) ) {

				// Get our auto-generated pages
				$login_page = get_page_by_path('login');
				$reset_password_page = get_page_by_path('reset-password');
				$profile_page = get_page_by_path('profile');
				$registration_page = get_page_by_path('register');

				// Empty array for IDs
				$ids = array();

				// Add each page ID to the array, if it exists and the setting is enabled
				if( !is_null( $login_page ) && Settings::frontend_login_enabled() )
					$ids[] = $login_page->ID;

				if( !is_null( $reset_password_page ) && Settings::frontend_login_enabled() )
					$ids[] = $reset_password_page->ID;

				if( !is_null( $profile_page ) && Settings::frontend_profile_enabled() )
					$ids[] = $profile_page->ID;

				if( !is_null( $registration_page ) && Settings::frontend_registration_enabled() )
					$ids[] = $registration_page->ID;

				// If we're looking at one of these pages
				// hide the "Page Attributes" metabox
				if( in_array( $_GET['post'], $ids ) )
					remove_meta_box( 'pageparentdiv', 'page', 'normal' );

			}
		}



		/**
		 * The Member Tools settings have been updated
		 * @param  mixed $old_settings Array of previous settings, or nothing if they were previously disabled
		 * @param  mixed $settings     Array of new settings, or nothing if they have been disabled 
		 */
		static function _updated_member_tools_settings( $old_settings, $settings ){

			// Settings are enabled
			if( !empty( $settings ) ) {

				// Generate login and password reset pages if setting is enabled
				if( isset( $settings['enable_frontend_login'] ) && $settings['enable_frontend_login'] == 1 ) {
					self::_generate_login_pages();
				}

				// Generate login and password reset pages if setting is enabled
				if( isset( $settings['enable_frontend_profile'] ) && $settings['enable_frontend_profile'] == 1 ) {
					self::_generate_profile_page();
				}

				// Generate login and password reset pages if setting is enabled
				if( isset( $settings['enable_frontend_registration'] ) && $settings['enable_frontend_registration'] == 1 ) {
					self::_generate_registration_page();

					if( isset( $settings['registration_activation_required'] ) && $settings['registration_activation_required'] == 1 ) {
						self::_generate_activation_page();
					}

				}
			}

		}



		/**
		 * Generate a login and reset password page, if they don't already exist
		 */
		static function _generate_login_pages(){

			$login_page = get_page_by_path('login');

			if( is_null( $login_page ) ){
				$login_page = wp_insert_post(array(
					'post_content' => __('<p>This is the login page. It is required if front-end login is enabled.</p>','theme'),
					'post_name' => 'login',
					'post_title' => 'Login',
					'post_status' => 'publish',
					'post_type' => 'page'
				));
			}

			$reset_password_page = get_page_by_path('reset-password');

			if( is_null( $reset_password_page ) ){
				$reset_password_page = wp_insert_post(array(
					'post_content' => __('<p>This is the reset password page. It is required if front-end login is enabled.</p>','theme'),
					'post_name' => 'reset-password',
					'post_title' => 'Reset Password',
					'post_status' => 'publish',
					'post_type' => 'page'
				));
			}

		}



		/**
		 * Generate a user profile page
		 */
		static function _generate_profile_page(){

			$profile_page = get_page_by_path('profile');

			if( is_null( $profile_page ) ){
				$profile_page = wp_insert_post(array(
					'post_content' => __('<p>This is the user profile form page. It is required if front-end profile is enabled.</p>','theme'),
					'post_name' => 'profile',
					'post_title' => 'Profile',
					'post_status' => 'publish',
					'post_type' => 'page'
				));
			}

		}



		/**
		 * Generate a registration page
		 */
		static function _generate_registration_page(){

			$registration_page = get_page_by_path('register');

			if( is_null( $registration_page ) ){
				$registration_page = wp_insert_post(array(
					'post_content' => __('<p>This is the user registration form page. It is required if front-end registration is enabled.</p>','theme'),
					'post_name' => 'register',
					'post_title' => 'Register',
					'post_status' => 'publish',
					'post_type' => 'page'
				));
			}

		}


		
		static function _generate_activation_page(){

			$registration_page = get_page_by_path('activate');

			if( is_null( $registration_page ) ){
				$registration_page = wp_insert_post(array(
					'post_content' => __('<p>This is the user activation form page. It is required if front-end registration and email activation is enabled.</p>','theme'),
					'post_name' => 'activate',
					'post_title' => 'Send Activation Email',
					'post_status' => 'publish',
					'post_type' => 'page'
				));
			}


		}


		/**
		 * Add Custom WP Query Variables
		 *
		 * Allows you to add any new query variables you need to the WP query system.
		 * This will allow you to do things like query/search/filter by your custom variable using WP's built-in functionality,
		 * rather than using the $_REQUEST global and reinventing the wheel.
		 *  
		 * @param  array $query_vars  	Query vars array to be modified
		 * @return array 				Modified query vars array
		 **/
		static function _query_vars( $query_vars ) {
			$new_vars = array(
				'restricted',
				'redirect',
				'login_status',
				'login_email',
				'login_error',
				'profile_status',
				'register_error',
				'register_status',
				'reset_error',
				'reset_pending',
				'reset_username',
				'activation_key',
				'activation_status'
			);
			return array_merge( $new_vars, $query_vars );
		}
		
		
		
		/**
		*
		* Setup Custom URLS
		* Allows you to create your own URL structures and replacement patterns.
		* Often you will need to use this in conjunction with a custom query variable, in order to create pretty URL's that use that variable.
		*
		* @param  array $rules  	Rewrite rules array to be modified
		* @return array 			Modified rewrite rules array
		**/
		static function _rewrite_rules_array( $rules ) {
			$new_rules = array(

				// Login link with email provided
				'login/activate/([^/]+)/([^/]+)/?$' => 'index.php?pagename=login&login_status=activate&login_email=$matches[1]&activation_key=$matches[2]',

				// Login error page
				'login/error/([^/]+)/?$' => 'index.php?pagename=login&login_status=error&login_error=$matches[1]',

				// Login page from restricted page, with redirect slug/ID
				'login/restricted/([^/]+)/?$' => 'index.php?pagename=login&login_status=restricted&redirect=$matches[1]',

				// Profile page
				'profile/([^/]+)/?$' => 'index.php?pagename=profile&profile_status=$matches[1]',

				// Reset password page with key and username, for new-password form
				'reset-password/([^/]+)/([^/]+)/?$' => 'index.php?pagename=reset-password&reset_username=$matches[1]&activation_key=$matches[2]',

				// Reset password page with key and username, for new-password form
				'reset-password/([^/]+)/([^/]+)/error/([^/]+)/?$' => 'index.php?pagename=reset-password&reset_username=$matches[1]&activation_key=$matches[2]&reset_error=$matches[3]',

				// Reset password errors
				'reset-password/error/([^/]+)/?$' => 'index.php?pagename=reset-password&reset_error=$matches[1]',

				// Reset password pending 
				'reset-password/pending/?$' => 'index.php?pagename=reset-password&reset_pending=1',

				// Registration error page
				'register/error/([^/]+)/?$' => 'index.php?pagename=register&register_error=$matches[1]',

				// Send activation email page
				'activate/([^/]+)/?' => 'index.php?pagename=activate&activation_status=$matches[1]',

			);
			$rules = $new_rules + $rules;
			return $rules;
		}
		
		
		
		/**
		*
		* Template Redirection
		* Generic hook for handling various redirections. Do what you will... carefully
		**/
		static function _template_redirect() {

			/**
			 * If the user is not logged into WP
			 */
			if( ! is_user_logged_in() ) {

				// If a non logged-in user is trying to access the profile page
				// redirect them to login
				if( is_page('profile') ){
					wp_redirect( home_url('login/restricted/profile') );
					exit();
				}


			/**
			 * If the user is logged in
			 */
			} else {

				// If someone tries to access the login page, when they are already logged in 
				if( is_page('login') ) {
					
					// Redirect to their profile, if the front end profile setting is enabled
					if( Settings::frontend_login_enabled() ){
						wp_redirect( home_url('profile/loggedin') );
						exit;

					// Otherwise redirect to the admin
					} else {
						wp_redirect( admin_url() );
						exit;
					}
				}

			}

		}



		/**
		 * Hook the WP login form to add fields at the end
		 */
		static function _login_form_top(){

			$activation_key = get_query_var('activation_key',0);
			return '<input type="hidden" name="user_activation_key" value="'. $activation_key .'">';

		}



		/**
		 * If logging out from the front end, redirect to the home page
		 */
		static function _wp_logout(){
			if( ! is_admin() ){
				wp_redirect( home_url() );
				exit();
			}
		}



		/**
		 * Custom user authentication by email or username
		 * @param  mixed 	$user				A WP_User, WP_Error, or null
		 * @param  string 	$username_email		The supplied username or email address
		 * @param  string 	$password       	The supplied password
		 * @return WP_User                 		A WP_User object, if it all goes well
		 */
		static function _authenticate( $user, $username_email, $password ){

			// Only do our custom authentication if 'login' is the referring page
			if( isset( $_SERVER['HTTP_REFERER'] ) && strstr( $_SERVER['HTTP_REFERER'] ,'login' ) ) {

				// If $user isn't null, some other process has either failed or succeeded at authenticating the user.
				// Not sure how this would ever happen, but who knows.
				if( ! is_null( $user ) && ! is_wp_error( $user ) ) {

					// This means $user is an authenticated WP_User, so return it
					return $user;
				}


				// If username/email is blank
				if( empty( $username_email ) ){
					wp_redirect( home_url('login/error/email') );
					exit();
				}

				// If password is blank
				if( empty( $password ) ){
					wp_redirect( home_url('login/error/password') );
					exit();
				}

				// First attempt to get the user data by email
				// It might not be an email address, but we'll start with that since its more likely/user-friendly
				$user = get_user_by( 'email', $username_email );

				// If that didn't work...
				if( ! $user ){

					// Maybe its a username, try that
					$user = get_user_by( 'login', $username_email );

					// If that didn't work then its a failure
					if( ! $user ) {

						wp_redirect( home_url('login/error/profile') );
						exit();

					}
				}

				// We've verified the user exists 
				
				// If an activation key was sent along with the form,
				// i.e. they followed a login link from a profile activation email
				if( isset($_POST['user_activation_key']) ){

					// Make sure the key checks out
					if( self::validate_activation_key( $_POST['user_activation_key'], $user->user_login ) ){

						// If they currenly don't have an assigned role (i.e. they are inactive)
						// set them as a subscriber.
						if( empty( $user->roles ) )
							$user->set_role('subscriber');

					}

				}

				if( Settings::registration_activation_required() ){

					if( !self::user_activated( $user ) ){
						wp_redirect( home_url('login/error/activate') );
						exit();

					}
				}


				// Now check their password
				if( ! wp_check_password( $password, $user->user_pass, $user->ID ) ) {

					wp_redirect( home_url('login/error/failed') );
					exit();
				}

				// Success
				return $user;

			}
			
		}
		


		/**
		 * =============
		 * FORM HANDLERS
		 * =============
		 */
		
		/**
		 * Handler for reset-password form submission
		 * @param  array $params {
		 *      
		 *      Parameters submitted from form
		 *
		 * 		@var string $username_email The username or email address entered by user
		 * 
		 * }
		 */
		static function _user_reset_password( $params ){

			// Verify the nonce 
			if( ! isset( $_POST['user_reset_password_nonce'] ) || ! wp_verify_nonce( $_POST['user_reset_password_nonce'], 'user_reset_password' ) ) {
				print('Invalid form submission');
				exit;
			}

			if( empty( $params ) || empty( $params['username_email'] ) ) {
				wp_redirect( home_url('reset-password/error/submission') );
				exit;
			}

			// First attempt to get the user data by email
			// It might not be an email address, but we'll start with that since its more likely/user-friendly
			$user = get_user_by( 'email', trim( $params['username_email'] ) );

			// If that didn't work...
			if( ! $user ){

				// Maybe its a username, try that
				$user = get_user_by( 'login', $params['username_email'] );

				// If that didn't work then its a failure
				if( ! $user ) {

					wp_redirect( home_url('reset-password/error/user') );
					exit();

				}
			}

			// If no error by now, we successfully found the user
			// Get/create a user activation key
			$activation_key = self::get_user_activation_key( $user->user_login );

			// Build an email message
			$message = '<p>';
			$message .= sprintf(__('A password reset request was submitted from %s. ','theme'), home_url('password-reset'));
			$message .= __('If this was a mistake, you may safely ignore this email. ','theme');
			$message .= '</p><p>';
			$message .= __('To reset your password, visit the following link:') . "\r\n\r\n" . '<br>';
			$message .= home_url('reset-password/' . rawurlencode( $user->user_login ) . '/' . $activation_key );
			$message .= '</p>';

			// Set the email headers
			$headers[] = 'From: '. get_bloginfo('name') . ' <' . get_option('admin_email') . '>';
			$headers[] = 'Content-Type: text/html; charset=UTF-8';

			// Attempt to send the email
			if ( ! wp_mail($user->user_email, __('Password Reset Request','theme'), $message, $headers) ) {
				// Didn't work
				wp_redirect( home_url('reset-password/error/email') );
				exit();
			} else {
				// Worked
				wp_redirect( home_url('reset-password/pending') );
				exit();
			}

		}


		/**
		 * Handler for new-password form submission
		 * @param  array $params {
		 *
		 * 		Parameters submitted from form
		 * 		
		 * 		@var string $user_email 		Hidden field
		 * 		@var int 	$user_ID 			Hidden field
		 * 		@var string $key 				Hidden field
		 * 		@var string $password 			The new password entered by the user
		 * 		@var string $confirm_password 	The new password confirmation entered by the user
		 * }
		 */
		static function _user_new_password( $params ){
			
			// Verify the nonce 
			if( ! isset( $_POST['user_new_password_nonce'] ) || ! wp_verify_nonce( $_POST['user_new_password_nonce'], 'user_new_password' ) ) {
				// Begone, fiend!
				print('Invalid form submission');
				exit;
			}

			// Make sure the necessary security params are there before we go any further
			if( empty( $params ) || empty( $params['username'] ) || empty( $params['activation_key'] ) || empty( $params['user_id'] ) ){
				wp_redirect( home_url('reset-password/error/invalid') );
				exit;
			}

			// If no password was entered
			if( empty( $params['password'] ) ) {
				wp_redirect( home_url('reset-password/'.$params['username'].'/'.$params['activation_key'].'/error/password') );
				exit;
			}

			// If no confirmation was entered
			if( empty( $params['confirm_password'] ) ) {
				wp_redirect( home_url('reset-password/'.$params['username'].'/'.$params['activation_key'].'/error/confirm') );
				exit;
			}

			// If the passwords don't match
			if( trim( $params['password'] ) !== trim( $params['confirm_password'] ) ) {
				wp_redirect( home_url('reset-password/'.$params['username'].'/'.$params['activation_key'].'/error/match') );
				exit;
			}

			// Good enough, do it
			$user_id = wp_update_user(array(
				'ID' => $params['user_id'],
				'user_pass' => esc_attr( $params['password'] )
			));

			// Log the user in by setting their auth cookie
			wp_set_auth_cookie( $user_id );

			// Redirect to either the profile page, or the admin
			if( Settings::frontend_profile_enabled() ) {
				wp_redirect( home_url('profile/loggedin') );
				exit;
			} else {
				wp_redirect( admin_url() );
				exit;
			}

		}



		static function _user_register( $params ){

			// Verify the nonce
			if( ! isset( $_POST['user_register_nonce'] ) || ! wp_verify_nonce( $_POST['user_register_nonce'], 'user_register' ) ) {
				print('Invalid form submission');
				exit;
			}

			// If required fields are missing
			if( empty( $params['password'] ) || empty( $params['confirm_password'] ) || empty( $params['data']['display_name'] ) || empty( $params['data']['user_email'] ) ) {
				wp_redirect( home_url('register/error/incomplete') );
				exit;
			}

			// If the passwords don't match
			if( trim( $params['password'] ) !== trim( $params['confirm_password'] ) ) {
				wp_redirect( home_url('register/error/match') );
				exit;
			}

			// Build a username
			$username = '';

			// If a first-name was supplied, use that
			if( !empty( $params['meta']['first_name'] ) ){
				$username = esc_attr( strtolower( trim( $params['meta']['first_name'] ) ) );

				// If a last name was also supplied, append that with a '.'
				if( !empty( $params['meta']['last_name'] ) )
					$username .= '.' . esc_attr( strtolower( trim( $params['meta']['last_name'] ) ) );

			// If not a first name, maybe a last name was supplied, try that
			} elseif( !empty( $params['meta']['last_name'] ) ) {
				$username = esc_attr( strtolower( trim( $params['meta']['last_name'] ) ) );

			// Otherwise, use the display name, since thats required anyway
			} else {

				$username = esc_attr( strtolower( trim( $params['data']['display_name'] ) ) );
			}

			// Make sure the username is unique
			$username = self::uniquify_username( $username );

			// Create the user
			$user_id = wp_create_user(
				$username,
				esc_attr( $params['password'] ),
				esc_attr( $params['data']['user_email'] ) 
			);

			$user_data = array();

			// Build a user data array for saving additional metadata and user data.
			// This might be repetative, but it allows for adding additional fields to the form
			// without needing to modify this function
			$user_data = array( 'ID' => $user_id );

			// Add each data field in the form
			foreach( $params['data'] as $key => $data ){
				$user_data[$key] = esc_attr( $data );
			}

			// Add each meta field in the form
			foreach( $params['meta'] as $key => $meta ){
				$user_data[$key] = esc_attr( $meta );
			}

			
			// If registration email activation is required
			if( Settings::registration_activation_required() ){
				
				// Set their role to empty, to make their account inactive
				$user_data['role'] = '';

				// Save the additional data
				wp_update_user( $user_data );

				$activation_key = self::get_user_activation_key( $username );

				$message = '<p>';
				$message .= sprintf(__('Thank you for registering on %s. To complete the process, please click the link below and login.','theme'), get_bloginfo('name'));
				$message .= '</p><p>';
				$message .= __('Activaye your registration here:') . "\r\n\r\n" . '<br>';
				$message .= home_url('login/activate/' . rawurlencode( $params['data']['user_email'] ) . '/' . $activation_key );
				$message .= '</p>';

				// Set the email headers
				$headers[] = 'From: '. get_bloginfo('name') . ' <' . get_option('admin_email') . '>';
				$headers[] = 'Content-Type: text/html; charset=UTF-8';

				// Attempt to send the email
				if ( ! wp_mail( $params['data']['user_email'], __('Complete Your Registration','theme'), $message, $headers ) ) {
					// Didn't work
					wp_redirect( home_url('register/error/email') );
					exit();
				} else {
					// Worked
					if( Settings::frontend_profile_enabled() ) {
						wp_redirect( home_url('status/registration-pending') );
						exit;
					} else {
						wp_redirect( admin_url() );
						exit;
					}
				}

			// Otherwise, just log the user in right away
			} else {

				// Log the user in by setting their auth cookie
				wp_set_auth_cookie( $user_id );

				// Redirect to either the profile page, or the admin
				if( Settings::frontend_profile_enabled() ) {
					wp_redirect( home_url('profile/created') );
					exit;
				} else {
					wp_redirect( admin_url() );
					exit;
				}
			}

			

		}



		/**
		 * Handle save profile form
		 * @param  array $params {
		 *
		 *     Parameters submitted from form
		 *     
		 *     @var string 	$username 			Hidden field
		 *     @var int 	$user_id 			Hidden Field
		 *     @var string 	$password 			New password entered by user
		 *     @var string 	$confirm_password 	New password confirmed by user
		 *     @var array 	$data {
		 *
		 * 			Array of user data. By default, just display name and email,
		 * 			but other user data fields added to the form will be saved too.
		 * 			
		 * 			@var string $display_name The user's public display name
		 *          @var email 	$user_email The user's email address
		 *     }
		 *     @var array $meta {
		 *
		 * 			Array of user meta fields. By default, just first and last name, 
		 * 			but other user meta fields added to the form will be saved too.
		 *
		 * 			@var string $first_name User's first name
		 * 			@var string $last_Name 	User's last name
		 *     }
		 * }
		 */
		static function _user_save_profile( $params ) {

			// Verify the nonce
			if( ! isset( $_POST['user_save_profile_nonce'] ) || ! wp_verify_nonce( $_POST['user_save_profile_nonce'], 'user_save_profile' ) ) {
				print('Invalid form submission');
				exit;
			}

			// Default no error
			$error = false;

			// Check if a user ID was supplied in the form
			if( isset( $params['user_id'] ) ) {

				// Build a user data array for saving
				$user_data = array( 'ID' => $params['user_id'] );

				// Add each data field in the form
				foreach( $params['data'] as $key => $data ){
					$user_data[$key] = esc_attr( $data );
				}

				// Add each meta field in the form
				foreach( $params['meta'] as $key => $meta ){
					$user_data[$key] = esc_attr( $meta );
				}
				// * Note, even though user data and user meta are stored separately in the database,
				// They can be mixed together in the wp_update_user() function

			// If not, theres something wrong
			} else {

				$error = true;
			}

			// If the password fields were entered, update the password too
			if( !empty( $params['password'] ) && !empty( $params['confirm_password'] ) ) {

				// Make sure they match
				if( $params['password'] == $params['confirm_password'] ){

					$user_data['user_pass'] = esc_attr( $params['password'] );

				// Otherwise call it an error
				} else {
					
					$error = true;
				}

			}

			// If there are errors, redirect to the profile error page
			if( $error ){

				wp_redirect( home_url('profile/error') );
				exit;
			}

			// If there are updates to make
			if( count( $user_data ) > 1 ){

				// Do it
				wp_update_user( $user_data );
			}

			// Redirect back to the updated profile
			wp_redirect( home_url('profile/update') );
			exit;
		

		}



		static function _user_send_activation( $params ){

			// Verify the nonce
			if( ! isset( $_POST['user_send_activation_nonce'] ) || ! wp_verify_nonce( $_POST['user_send_activation_nonce'], 'user_send_activation' ) ) {
				print('Invalid form submission');
				exit;
			}

			if( empty( $params ) || empty( $params['username_email'] ) ) {
				wp_redirect( home_url('reset-password/error/submission') );
				exit;
			}

			// First attempt to get the user data by email
			// It might not be an email address, but we'll start with that since its more likely/user-friendly
			$user = get_user_by( 'email', trim( $params['username_email'] ) );

			// If that didn't work...
			if( ! $user ){

				// Maybe its a username, try that
				$user = get_user_by( 'login', $params['username_email'] );

				// If that didn't work then its a failure
				if( ! $user ) {

					wp_redirect( home_url('activate/error/user') );
					exit();

				}
			}


			// If no error by now, we successfully found the user
			// Get/create a user activation key
			$activation_key = self::get_user_activation_key( $user->user_login );

			// Build an email message
			$message = '<p>';
			$message .= sprintf(__('A profile activation request was submitted from %s. ','theme'), home_url('password-reset'));
			$message .= '</p><p>';
			$message .= __('To activate your profile, visit the following link:') . "\r\n\r\n" . '<br>';
			$message .= home_url('login/activate/' . rawurlencode( $user->user_email ) . '/' . $activation_key );
			$message .= '</p>';

			// Set the email headers
			$headers[] = 'From: '. get_bloginfo('name') . ' <' . get_option('admin_email') . '>';
			$headers[] = 'Content-Type: text/html; charset=UTF-8';

			// Attempt to send the email
			if ( ! wp_mail($user->user_email, __('Profile Activation Request','theme'), $message, $headers) ) {
				// Didn't work
				wp_redirect( home_url('activate/error/email') );
				exit();
			} else {
				// Worked
				wp_redirect( home_url('activate/pending') );
				exit();
			}
		}




		/**
		 * ================
		 * Helper functions
		 * ================
		 */



		protected static function get_user_activation_key( $username ){

			// Get the WP Database global
			global $wpdb;

			// Check if theres a user activation key in the database
			$activation_key = $wpdb->get_var( $wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $username ) );
			
			// If not, generate one
			if( empty( $activation_key ) ) {
				$activation_key = wp_generate_password(20, false);
				$wpdb->update( $wpdb->users, array('user_activation_key' => $activation_key), array('user_login' => $username ) );
			}

			return $activation_key;
		}



		/**
		 * Validate an activation key against a username
		 * @param  string $key      	Unique key from a reset-password link
		 * @param  string $username 	The username from a reset-password link
		 * @return boolean           	Did it validate?
		 */
		public static function validate_activation_key( $key, $username ){

			global $wpdb;

			$user = $wpdb->get_row($wpdb->prepare("SELECT ID, user_login, user_email FROM $wpdb->users WHERE user_activation_key = %s AND user_login = %s", $key, $username ));

			if( ! is_null( $user ) )
				return true;

			return false;
		}
		

		public static function user_activated( $user ){

			if( is_int( $user ) )
				$user = get_userdata( $user );

			if( is_wp_error( $user ) )
				return false;

			if( empty( $user->roles ) )
				return false;

			return true;

		}

		/**
		 * Check if a username is unique, and if not append an incrementing number until it is
		 * @param 	string 	$username 	The username to test
		 * @param 	int 	$iteration 	Iteration number, for recursive calls when a supplied username isn't unique	
		 * @return 	string           	A unique username
		 */
		protected static function uniquify_username( $username, $iteration = 1 ){

			if( username_exists( $username ) ) {

				$iteration ++;
				$username .= (string)$iteration;
				$username = self::uniquify_username( $username, $iteration );
			}

			return $username;

		}
	}
	
}

if(class_exists('MemberTools')){
	MemberTools::load();
}

?>