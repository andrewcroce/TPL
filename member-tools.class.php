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
			// add reset info here...
			// !!!
			echo "XXXX";
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
		function _query_vars( $query_vars ) {
			echo "Q var"; // !!!
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
			echo "new rules"; // !!!
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
			echo "redir"; // !!!
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
		
		
	}
	
}

if(class_exists('StarterMemberTools')){
	$theme = new StarterMemberTools();
}

?>