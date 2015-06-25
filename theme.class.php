<?php

/**
 * Starter Theme
 *
 * @package WordPress
 * @subpackage WP_Starter_Theme
 */



/**
 * Include some stuff
 */
include( 'plugins/plugins.php' );
include( 'includes/topbar-walker.class.php' );
include( 'includes/offcanvas-walker.class.php' );


if(!class_exists('StarterTheme')){
	
	class StarterTheme {
		
		
		/**
		*
		* Constructor
		* Primarily used to set up action and filter hooks.
		**/
		function __construct() {
			
			add_action('init', array(&$this, '_init'));
			add_action('after_switch_theme', array(&$this, '_theme_activated'));
			add_action('wp_print_styles', array(&$this,'_add_styles'));
			add_action('wp_print_scripts', array(&$this,'_add_scripts'));
			add_action('after_setup_theme', array(&$this,'_setup_theme'));
			add_action('template_redirect', array(&$this,'_template_redirect'));
			add_action('wp_logout', array(&$this,'_wp_logout'));

			add_filter('wp_starter_skiplinks', array(&$this,'_add_skiplinks'));
			add_filter('template_include', array(&$this,'_template_include'));
			add_filter('body_class', array(&$this,'_body_class'));
			add_filter('query_vars', array(&$this,'_query_vars'));
			add_filter('rewrite_rules_array', array(&$this,'_rewrite_rules_array'));
			add_filter('get_search_form', array(&$this,'_get_search_form'));
			add_filter('the_content', array(&$this,'_the_content'));

			
			// Add any additional action or filter hooks here.
		}


		/**
		 * Theme activation
		 * This runs when the theme is activated
		 */
		function _theme_activated() {

			/**
			* Generate Home Page
			**/
			$home_page = get_page_by_path('home-page');
			if( is_null( $home_page ) ){
				$home_page = wp_insert_post(array(
					'post_content' => __('<p>This is the home page. Add ACF fields and customize as needed.</p>'),
					'post_name' => 'home-page',
					'post_title' => 'Home Page',
					'post_status' => 'publish',
					'post_type' => 'page'
				));

				if( $home_page ){
					if( $home_page instanceof WP_Error ){
						trigger_error('Error generating home page');
					} else {
						update_option( 'page_on_front', $home_page );
    					update_option( 'show_on_front', 'page' );
					}
				}
			}



			/**
			* Generate Style Guide Page
			**/
			$style_guide = get_page_by_path('style-guide');
			if( is_null( $style_guide ) ){
				$style_guide = wp_insert_post(array(
					'post_content' => __('<p>This page is require to display the style guide, please do not delete it.</p>'),
					'post_name' => 'style-guide',
					'post_title' => 'Style Guide',
					'post_status' => 'publish',
					'post_type' => 'page'
				));
				if( $style_guide instanceof WP_Error )
					trigger_error('Error generating style guide page');
			}


			/**
			* Generate Login Page
			**/
			$login_page = get_page_by_path('login');
			if( is_null( $login_page ) ){
				$login_page = wp_insert_post(array(
					'post_content' => __('<p>This page is require to display the front end login form, please do not delete it.</p>'),
					'post_name' => 'login',
					'post_title' => 'Login',
					'post_status' => 'publish',
					'post_type' => 'page'
				));
				if( $login_page instanceof WP_Error )
					trigger_error('Error generating login page');
			}


			/**
			* Generate Forgot Password Page
			**/
			$forgot_password_page = get_page_by_path('forgot-password');
			if( is_null( $forgot_password_page ) ){
				$forgot_password_page = wp_insert_post(array(
					'post_content' => __('<p>This page is require to display the forgot-password form, please do not delete it.</p>'),
					'post_name' => 'forgot-password',
					'post_title' => 'Forgot Password',
					'post_status' => 'publish',
					'post_type' => 'page'
				));
				if( $forgot_password_page instanceof WP_Error )
					trigger_error('Error generating forgot password page');
			}


			/**
			* Generate Profile Page
			**/
			$profile_page = get_page_by_path('profile');
			if( is_null( $profile_page ) ){
				$profile_page = wp_insert_post(array(
					'post_content' => __('<p>This page is require to display the profile page, please do not delete it.</p>'),
					'post_name' => 'profile',
					'post_title' => 'Member Profile',
					'post_status' => 'publish',
					'post_type' => 'page'
				));
				if( $profile_page instanceof WP_Error )
					trigger_error('Error generating profile page');
			}

		}




		/**
		 * Init
		 * Hook into Wordpress initialization
		 */
		function _init() {

			/* Set the permalink structure to use postname
			*/
			global $wp_rewrite;
			$wp_rewrite->set_permalink_structure( '/%postname%/' );


			/* We use a generalized init function to intercept any custom form submissions
			* For example, if a form is submitted where $_POST['form_action'] == 'some_action',
			* the function $this->_some_action() will process it
			*/
			if( isset( $_POST['form_action'] ) ) {
				$action = '_'.$_POST['form_action'];
				$params = !empty($_POST['params']) ? $_POST['params'] : array();
				$this->$action($params);
			}

			/**
			 * Include our custom ACF Fieldset for the post type index page template
			 */
			include( 'includes/acf_index_post_type_fields.php' );
		}
		
		
		
		/**
		*
		* Add Styles
		* This adds CSS files to the theme. You shouldn't need to add files, since app.css is compiled from SASS.
		**/
		function _add_styles() {
			if (is_admin()) return;
			wp_enqueue_style('theme', get_stylesheet_directory_uri() .'/css/app.css', array(), '1.0.0', 'screen');
		}
		


		/**
		*
		* Add Scripts
		* This adds javascript files to the theme. Add any additional script files using the wp_enqueue_script() method.
		* Be mindful of the array of dependencies, the third parameter. Specifying a dependencies will ensure scripts are loaded in proper order.
		* 
		* Only modernizr is loaded in the header, otherwise we could see a noticable delay in applied CSS that depends on modernizr classes (FOUC <http://en.wikipedia.org/wiki/Flash_of_unstyled_content>).
		* All other scripts are loaded in non render-blocking fashion in the footer
		*
		* It is recommended that all JS libraries you use are prepended to app-min.js using a JS compiler, such as Codekit.
		* This will keep the number of individual HTTP requests to a minimum.
		**/
		function _add_scripts() {
			
			// Don't mess with admin scripts
			if (is_admin()) return;

			// Find the current required jquery version
			$wp_jquery_ver = $GLOBALS['wp_scripts']->registered['jquery']->ver;

			// Deregister jquery, so we can re-register it in the footer
			wp_deregister_script('jquery');

			wp_enqueue_script('modernizr', get_stylesheet_directory_uri() .'/bower_components/modernizr/modernizr.js', array(), '2.8.3');
			wp_enqueue_script('jquery', site_url('/wp-includes/js/jquery/jquery.js?ver='.$wp_jquery_ver), array(), $wp_jquery_ver, true );
			wp_enqueue_script('theme.app', get_stylesheet_directory_uri() .'/js/min/app-min.js', array('modernizr','jquery'), '0.1.0', true);
			wp_localize_script('theme.app', 'theme', $this->_add_js_vars()); // Call _add_js_vars() to add PHP variables to frontend 

			if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
				wp_enqueue_script( 'comment-reply' );
			}

		}
		
		

		/**
		*
		* Add JS Vars
		* This adds PHP side variables to a front end Javascript object 'theme'.
		* This is useful for doing AJAX, for example. In JS, theme.ajax_url will return the URL where all AJAX requests should be posted.
		* @return  array Array of variables to be added to the front end window.theme
		* 
		**/
		function _add_js_vars() {
			return array(
				'ajax_url' => admin_url('admin-ajax.php'),
				'site_url' => get_site_url(),
				'doc_title' => get_bloginfo('title'),
				// Add any additional variables you want accessible in Javascript
			);
		}



		/**
		 * Custom filter function that adds skiplinks to tpl_navs/nav-skiplinks.php
		 * @return array List of anchor links to add
		 *    {anchor} => {label} 
		 */
		function _add_skiplinks() {

			$links =  array(
				'#main-navigation' => __('Skip to main navigation'),
				'#main-content' => __('Skip to main content'),
			);

			// Add any context-specific skiplinks, i.e.
			// if( is_page('foo') ){
			//    $links['#foo'] = __('Skip to foo');
			// }
		
			return $links;
		}
		
		
		
		/**
		*
		* General Theme Setup
		* Add various functionality, such as sidebars, menus, image sizes, and whatnot
		* 
		**/
		function _setup_theme() {

			/**
			 * Create a global counter variable to use in multiple search forms,
			 * which prevents duplicate IDs
			 */
			global $search_form_counter;
			$search_form_counter = 0;


			/**
			 * Add support for automatic title tag output by wp_head();
			 * Requires WP 4.1 or higher
			 */
			add_theme_support( 'title-tag' );

			
			/**
			 * Theme text domain, by default just called "theme"
			 * So you can use i10n/i18n string functions __( $string, 'theme' ); and _e( $string, 'theme' );
			 * For language translations, create a '/languages' directory, and add your .mo files there
			 */
			load_theme_textdomain( 'theme', get_template_directory() . '/languages' );
			
			/**
			* Widget Positions
			* You will be able to add widgets to these positions in the WordPress admin.
			**/
			
			/*
			register_sidebar( array(
				'name' => 'Column One',
				'id' => 'column-one',
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => "</div>",
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>',
			) );
			*/
			
			
			/**
			* Register Menus
			**/
			
			register_nav_menus( array(
				'main_menu' => 'Main Menu',
				// Add additional menus, ie.
				// 'secondary_menu' => 'Secondary Menu',
			) );
			



			/**
			 * Switch default core markup to output valid HTML5
			 */
			
			add_theme_support( 'html5', array(
				'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
			) );
			


			/**
			* Image Sizes
			* refer to http://codex.wordpress.org/Function_Reference/add_image_size
			**/
			// add_image_size('my-image-size', 500,500,false);
			
			
		}
		


		/**
		 * Modify body classes
		 * @param  array $classes Array of default body classes
		 * @return array          Modified array of body classes
		 */
		function _body_class( $classes ){

			global $post;
			
			// Add the page slug class "page-{post_name}"
			if( is_page() ){
				$classes[] = 'page-' . $post->post_name;
			}

			// Add a class for the parent page name
			if( is_page() && $post->post_parent ){
				$post_parent = get_post($post->post_parent);
        		$classes[] = "parent-" . $post_parent->post_name;
			}

			// Add a class for the template name, if one is being used
			$template = get_page_template();
			if( $template != null ) {
			    $path = pathinfo( $template );
			    $classes[] = str_replace( 'tpl_', 'template-', $path['filename'] );
			}
			
			return $classes;
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

			$new_vars = array(
				'restricted',
				'redirect',
				'login_error'
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
				'login/restricted/([^/]+)/?$' => 'index.php?pagename=login&restricted=1&redirect=$matches[1]'

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

			// If $user isn't null, some other process has either failed or succeeded at authenticating the user.
			// Not sure how this would ever happen, but who know.
			if( ! is_null( $user ) ) {

				// An authentication error came from some other process
				if( is_wp_error( $user ) ) {
					wp_redirect( home_url('login/error/failed') );
					exit();
				}

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
		



		/**
		 * Load templates in subdirectories
		 *
		 * @param string $template 	Path to template file
		 * @return string 			Path to (different) template file
		 **/
		function _template_include( $template ){

			global $post;

			// If this is a page, try to locate a template file with a matching name
			if( is_page() && !is_page_template() ) {

				// If this is the front page, try to load the home page template
				if( is_front_page() ){
					if( file_exists( dirname(__FILE__) . '/tpl_pages/page-home.php' ) ){
						return locate_template( 'tpl_pages/page-home.php' );
					} else {
						return locate_template( 'tpl_pages/page-default.php' );
					}
				}

				if( file_exists( dirname(__FILE__) . '/tpl_pages/page-' . $post->post_name . '.php' ) ){
					return locate_template( 'tpl_pages/page-' . $post->post_name . '.php' );
				} else {
					return locate_template( 'tpl_pages/page-default.php' );
				}
			}

			// If this is a single post type (other than a page), try to locate a template file with a matching name
			if( is_single() ) {
				if( file_exists( dirname(__FILE__) . '/tpl_singles/single-' . $post->post_type . '.php' ) ){
					return locate_template( 'tpl_singles/single-' . $post->post_type . '.php' );
				} else {
					return locate_template( 'tpl_singles/single-default.php' );
				}
			}

			// If this page is using an admin-selectable page template
			// check if a page-specific version exists, and return that instead.
			// This is useful especially for the index template, but may be used for any admin-selectable temple,
			// where the admin-template pages need to share options, but may have other unique fields or layout.
			if( is_page_template() ) {

				$specific_template_path = str_replace( '.php', '-' . $post->post_name . '.php', get_page_template() );

				if( file_exists( $specific_template_path ) )
					return $specific_template_path;
			}

			return $template;
		}



		/**
		 * Hook ACF field for "index_post_type".
		 * This field is attached to pages with the "index" template, used for custom post type indexes/archives.
		 * The field group is automatically registered in includes/acf_index_post_type_fields.php
		 * @param  array $field The field properties
		 * @return array       Modified field properties
		 */
		function _acf_load_index_post_type( $field ){

			// Get all public post types
			$post_types = get_post_types(array(
				'public' => true
			), 'objects');
			
			// Add them to the field's choices
			foreach( $post_types as $key => $post_type ){
				$field['choices'][$key] = $post_type->label;
			}

			return $field;
		}



		/**
		 * Intercept our search form output so we can increment an ID counter.
		 * Since the searchform can be output multiple times on a given page, this could lead to invalid duplicate HTML IDs.
		 * We increment this counter, and append it to the field IDs, so each one is unique.
		 */
		function _get_search_form( $form ){

			global $search_form_counter;
			$search_form_counter += 1;

		}



		/**
		 * Hook The Content Filter
		 * @param  string $content HTML content
		 * @return string          Modified HTML
		 */
		function _the_content( $content ){

			// Strip <p> tags around images, because images are not paragraphs
			$content = preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);

			// Wrap it in a wysiwyg class
			$content = '<div class="wysiwyg">' . $content . '</div>';

			return $content;
		}






		/**
		 * =============
		 * FORM HANDLERS
		 * =============
		 */
		
		function _handle_member_login( $params ){

			if( ! isset( $_POST['member_login_nonce'] ) || ! wp_verify_nonce( $_POST['member_login_nonce'], 'handle_member_login' ) ) {
				print 'Begone, fiend!';
   				exit;
			}

			MemberTools::try_login( $params );

		}
		
	}
	
}

if(class_exists('StarterTheme')){
	$theme = new StarterTheme();
}

?>