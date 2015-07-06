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
			
			add_action('template_redirect', 					array(__CLASS__,'_template_redirect'));
			add_action('after_switch_theme', 					array(__CLASS__,'_theme_activated'));
			add_action('update_option_member_tools_settings',	array(__CLASS__,'_updated_member_tools_settings'), 10, 3);
			add_action('wp_logout', 							array(__CLASS__,'_wp_logout'));
			add_action('form_action_user_reset_password', 		array(__CLASS__,'_user_reset_password'));
			add_action('form_action_user_new_password',  		array(__CLASS__,'_user_new_password'));
			
			add_filter('query_vars', 			array(__CLASS__,'_query_vars'));
			add_filter('rewrite_rules_array', 	array(__CLASS__,'_rewrite_rules_array'));
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
			

		}



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
			}

		}



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
				'login_error',
				'profile_error',
				'reset_error',
				'reset_pending',
				'reset_key',
				'reset_username',
				'update'
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

				// Login error page
				'login/error/([^/]+)/?$' => 'index.php?pagename=login&login_error=$matches[1]',

				// Login page from restricted page, with redirect slug/ID
				'login/restricted/([^/]+)/?$' => 'index.php?pagename=login&restricted=1&redirect=$matches[1]',

				// Profile update page
				'profile/update/?$' => 'index.php?pagename=profile&update=1',
	
				// Profile error page
				'profile/error/?$' => 'index.php?pagename=profile&profile_error=1',

				// Reset password page with key and username, for new-password form
				'reset-password/([^/]+)/([^/]+)/?$' => 'index.php?pagename=reset-password&reset_username=$matches[1]&reset_key=$matches[2]',

				// Reset password page with key and username, for new-password form
				'reset-password/([^/]+)/([^/]+)/error/([^/]+)/?$' => 'index.php?pagename=reset-password&reset_username=$matches[1]&reset_key=$matches[2]&reset_error=$matches[3]',

				// Reset password errors
				'reset-password/error/([^/]+)/?$' => 'index.php?pagename=reset-password&reset_error=$matches[1]',

				// Reset password pending 
				'reset-password/pending/?$' => 'index.php?pagename=reset-password&reset_pending=1'

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

				/**
				 * If a non logged-in user is trying to access the profile page
				 * redirect them to login
				 */
				if( is_page('profile') ){
					wp_redirect( home_url('login/restricted/profile') );
					exit();
				}

			}

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
					wp_redirect( home_url('login/error/username_email') );
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

						wp_redirect( home_url('login/error/failed') );
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
			
			// Get the WP Database global
			global $wpdb;

			// Check if theres a user activation key in the database
			$key = $wpdb->get_var( $wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user->user_login) );
			
			// If not, generate one
			if( empty( $key ) ) {
				$key = wp_generate_password(20, false);
				$wpdb->update( $wpdb->users, array('user_activation_key' => $key), array('user_login' => $user->user_login) );
			}

			// Build an email message
			$message = '<p>';
			
			$message .= sprintf(__('A password reset request was submitted from %s. ','theme'), home_url('password-reset'));
			$message .= __('If this was a mistake, you may safely ignore this email. ','theme');
			
			$message .= '</p><p>';

			$message .= __('To reset your password, visit the following link:') . "\r\n\r\n" . '<br>';
			
			$message .= home_url('reset-password/' . rawurlencode( $user->user_login ) . '/' . $key );
			$message .= '</p>';

			// Set the email headers
			$headers[] = 'From: '. get_bloginfo('name') . ' <' . get_option('admin_email') . '>';
			$headers[] = 'Content-Type: text/html; charset=UTF-8';

			// Attempt to send the email
			if ( $message && !wp_mail($user->user_email, __('Password Reset Request','theme'), $message, $headers) ) {
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
				print('Invalid form submission');
				exit;
			}

			// Make sure the necessary security params are there before we go any further
			if( empty( $params ) || empty( $params['username'] ) || empty( $params['key'] ) || empty( $params['user_id'] ) ){
				wp_redirect( home_url('reset-password/error/invalid') );
				exit;
			}

			// If no password
			if( empty( $params['password'] ) ) {
				wp_redirect( home_url('reset-password/'.$params['username'].'/'.$params['key'].'/error/password') );
				exit;
			}

			// If no confirmation
			if( empty( $params['confirm_password'] ) ) {
				wp_redirect( home_url('reset-password/'.$params['username'].'/'.$params['key'].'/error/confirm') );
				exit;
			}

			// If they don't match
			if( trim( $params['password'] ) !== trim( $params['confirm_password'] ) ) {
				wp_redirect( home_url('reset-password/'.$params['username'].'/'.$params['key'].'/error/match') );
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
				wp_redirect( home_url('profile') );
				exit;
			} else {
				wp_redirect( admin_url() );
				exit;
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





		/**
		 * ================
		 * Helper functions
		 * ================
		 */


		/**
		 * Validate a reset-password key against a username
		 * @param  string $key      	Unique key from a reset-password link
		 * @param  string $username 	The username from a reset-password link
		 * @return boolean           	Did it validate?
		 */
		public static function validate_reset_key( $key, $username ){

			global $wpdb;

			$user = $wpdb->get_row($wpdb->prepare("SELECT ID, user_login, user_email FROM $wpdb->users WHERE user_activation_key = %s AND user_login = %s", $key, $username ));

			if( ! is_null( $user ) )
				return true;

			return false;
		}
		
	}
	
}

if(class_exists('MemberTools')){
	MemberTools::load();
}

?>