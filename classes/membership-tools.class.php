<?php

/**
 * @package StarterThemeMemberToolsModule
 */



// includes were here, they have since been removed....


if(!class_exists('StarterMemberTools')){
	
	class StarterMemberTools {
		
		
		/**
		*
		* Constructor
		* Primarily used to set up action and filter hooks.
		**/
		function __construct() {
			
			add_action('init', array(&$this, '_init'));
			add_action('template_redirect', array(&$this,'_template_redirect'));
			add_filter('query_vars', array(&$this,'_query_vars'));
			add_filter('rewrite_rules_array', array(&$this,'_rewrite_rules_array'));

			// User/account related hooks
			
			add_action('wp_logout', array(&$this,'_wp_logout'));
			add_filter('authenticate', array(&$this, '_authenticate'), 100, 3);
			add_filter('lostpassword_url', array(&$this,'_lostpassword_url'), 10, 2);
			add_filter('register', array(&$this,'_register'));			
			// Add any additional action or filter hooks here.
		}



		/**
		 * Init
		 * Hook into Wordpress initialization
		 */
		function _init() {

			// handle all of the password reset requests.
			self::pw_reset_router();			
			//
		}
		// end init
		



		/**
		 * route the pw reset behavior to the correct method
		 */
		public static function pw_reset_router(){
			
			// this is from the regular reset password form
			// load in the first state
			if($_POST['action'] == "tg_pwd_reset"){
				self::pw_reset_load_state_one();
			}

			// this is from the link in the first email
			// load in the second state
			if(isset($_GET['key']) && $_GET['action'] == "reset_pwd_state_two"){
				self::pw_reset_load_state_two();
			}

			// reset success yay
			if ($_GET['action'] == 'reset_success'){
				self::$status_message = "You will receive an email with your new password shortly.";
			}
		}
		//

		/**
		 * this is the initial response from a reset password request.
		 * it creates a key and emails the user with that key which will allow them
		 * to generate a new password.
		 */
		public static function pw_reset_load_state_one(){
			global $wpdb;
			if ( !wp_verify_nonce( $_POST['tg_pwd_nonce'], "tg_pwd_nonce")) {
				self::$status_message = 'No Tricks Please';
			}
			if(empty($_POST['user_input'])) {
				self::$status_message = 'Please Enter Your Email Address';
			}
			$user_input = trim($_POST['user_input']);

			if ( strpos($user_input, '@') ) {
				$user_data = get_user_by( 'email', $user_input );
				if( empty($user_data) ) {
					self::$status_message = 'Invalid Email Address';
				}
			} else {
				$user_data = get_userdatabylogin($user_input);
				if( empty($user_data) ) {
					self::$status_message = 'Invalid Username';
				}
			}

			$user_login = $user_data->user_login;
			$user_email = $user_data->user_email;

			$key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));
			if(empty($key)) {
				//generate reset key
				$key = wp_generate_password(20, false);
				$wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $user_login));
			}

			//mailing reset details to the user
			$message = __('Someone requested that the password be reset for the following account:') . "\r\n\r\n" . '<br>';
			$message .= get_home_url() . "\r\n\r\n" . '<br>';
			$message .= sprintf(__('Username: %s'), $user_email) . "\r\n\r\n" . '<br>';
			$message .= __('If this was a mistake, just ignore this email and nothing will happen.') . "\r\n\r\n" . '<br>';
			$message .= __('To reset your password, visit the following address:') . "\r\n\r\n" . '<br>';
			$message .= home_url("/reset-password/?action=reset_pwd_state_two&key=$key&login=" . rawurlencode($user_login)) . "\r\n" . '<br>';
			$message .= '<br><hr /><br>';

			$headers[] = 'From: Admin';
			$headers[] = 'Content-Type: text/html; charset=UTF-8';

			if ( $message && !wp_mail($user_email, 'Password Reset Request', $message, $headers) ) {
				self::$status_message = "Email failed to send, please contact the site Admin";
			} else {
				self::$status_message = "You will receive and email with password reset instructions within the next few minutes.";
			}
		}
		// end load state 1



		/**
		 * load the second state of the password reset
		 * the user arrives here by clicking the link in the first email they were sent
		 * this checks if the key is correct, then generates a new password
		 * and emails it to the user with a link to the login page.
		 */
		public static function pw_reset_load_state_two(){
			global $wpdb;
			$reset_key = $_GET['key'];
			$user_login = $_GET['login'];
			$user_data = $wpdb->get_row($wpdb->prepare("SELECT ID, user_login, user_email FROM $wpdb->users WHERE user_activation_key = %s AND user_login = %s", $reset_key, $user_login));

			$user_login = $user_data->user_login;
			$user_email = $user_data->user_email;
		
			if(!empty($reset_key) && !empty($user_data)) {
				$new_password = wp_generate_password(7, false);
				//echo $new_password; exit();
				wp_set_password( $new_password, $user_data->ID );
				//mailing reset details to the user
				$message = __('Your new password for the account at:') . "\r\n\r\n" . '<br>';
				$message .= get_home_url() . "\r\n\r\n" . '<br>';
				$message .= sprintf(__('Username: %s'), $user_email) . "\r\n\r\n" . '<br>';
				$message .= sprintf(__('Password: %s'), $new_password) . "\r\n\r\n" . '<br>';
				$message .= __('You can now login with your new password at: ') . get_option('siteurl')."/login" . "\r\n\r\n" . '<br>';
				$message .= '<br><hr /><br>';

				$headers[] = 'From: Admin';
				$headers[] = 'Content-Type: text/html; charset=UTF-8';
				if ( $message && !wp_mail($user_email, 'Password Reset Request', $message, $headers) ) {
					self::$status_message = 'Email failed to send for some unknown reason';
				} else {
					self::$status_message = 'Another email containing a temporary password has been sent!';
				}
			}else{
				exit('No Not a Valid Key.');
			}
		}
		// end state two
				


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
		function _query_vars( $query_vars ) {
			$new_vars = array(
				'restricted',
				'redirect',
				'login_error',
				'profile_error',
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
		function _rewrite_rules_array( $rules ) {
			$new_rules = array(

				// Login error page
				'login/error/([^/]+)/?$' => 'index.php?pagename=login&login_error=$matches[1]',

				// Login page from restricted page, with redirect slug/ID
				'login/restricted/([^/]+)/?$' => 'index.php?pagename=login&restricted=1&redirect=$matches[1]',

				// Profile update page
				'profile/update/?$' => 'index.php?pagename=profile&update=1',
	
				// Profile error page
				'profile/error/?$' => 'index.php?pagename=profile&profile_error=1'

			);
			$rules = $new_rules + $rules;
			return $rules;
		}
		
		
		
		/**
		*
		* Template Redirection
		* Generic hook for handling various redirections. Do what you will... carefully
		**/
		function _template_redirect() {
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
		function _wp_logout(){
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
		function _authenticate( $user, $username_email, $password ){

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

					PC::debug('not an email');

					// Maybe its a username, try that
					$user = get_user_by( 'login', $username_email );

					// If that didn't work then its a failure
					if( ! $user ) {

						PC::debug('not a username');

						wp_redirect( home_url('login/error/failed') );
						exit();

					}
				}

				// Now check their password
				if( ! wp_check_password( $password, $user->user_pass, $user->ID ) ) {

					PC::debug('bad password');
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
		

		function _user_save_profile( $params ) {

			if( ! isset( $_POST['user_save_profile_nonce'] ) || ! wp_verify_nonce( $_POST['user_save_profile_nonce'], 'user_save_profile' ) ) {
				print('Invalid form submission');
				exit;
			}

			$error = false;


			if( isset( $params['user_id'] ) ) {

				$user_data = array( 'ID' => $params['user_id'] );

				foreach( $params['data'] as $key => $data ){
					$user_data[$key] = esc_attr( $data );
				}

				foreach( $params['meta'] as $key => $meta ){
					$user_data[$key] = esc_attr( $meta );
				}

			} else {

				$error = true;
			}


			if( !empty( $params['password'] ) && !empty( $params['confirm_password'] ) ) {

				if( $params['password'] == $params['confirm_password'] ){

					$user_data['user_pass'] = esc_attr( $params['password'] );

				} else {
					
					PC::debug('pass error');
					$error = true;
				}

			}

			if( $error ){

				wp_redirect( home_url('profile/error') );
				exit;
			}

			if( count( $user_data ) > 1 ){
				wp_update_user( $user_data );
			}

			wp_redirect( home_url('profile/update') );
			exit;
		

		}

		/**
		 *	Helper functions
		 */
		
		public static $status_message = '';

		/**
		 * Get the status message
		 */
		public static function get_status(){
			return self::$status_message;
		}
		
	}
	
}

if(class_exists('StarterMemberTools')){
	$theme = new StarterMemberTools();
}

?>