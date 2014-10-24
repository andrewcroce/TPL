<?php

/**
 * Starter Theme
 *
 * @package WordPress
 * @subpackage WP_Starter_Theme
 */


if(!class_exists('Theme')){
	
	class Theme {
		
		function Theme() {
			$this->__construct();
		}
		
		
		/**
		*
		* Constructor
		* Primarily used to set up action and filter hooks.
		* 
		**/
		function __construct() {
			
			add_action('init', array(&$this, '_init'));
			add_action('wp_print_styles', array(&$this,'_add_styles'));
			add_action('wp_print_scripts', array(&$this,'_add_scripts'));
			add_action('after_setup_theme', array(&$this,'_setup_theme'));
			add_action('tgmpa_register', array(&$this,'_register_plugins'));
			add_action('template_redirect', array(&$this,'_template_redirect'));

			add_filter('posts_results', array(&$this,'_posts_results'));
			add_filter('query_vars', array(&$this,'_query_vars'));
			add_filter('rewrite_rules_array', array(&$this,'_rewrite_rules_array'));
			
			// Add any additional action or filter hooks here.
		}



		/* 
		* 
		* We use a generalized init function to intercept any custom form submissions
		* For example, if a form is submitted where $_POST['form_action'] == 'some_action',
		* the function $this->_some_action() will process it
		*
		*/
		function _init() {
			if( isset( $_POST['form_action'] ) ) {
				$action = '_'.$_POST['form_action'];
				$params = !empty($_POST['params']) ? $_POST['params'] : array();
				$this->$action($params);
			}
		}
		
		
		
		/**
		*
		* Add Styles
		* This adds CSS files to the theme. You shouldn't need to add files, since app.css is compiled from SASS.
		* 
		**/
		function _add_styles() {
			if (is_admin()) return;
			wp_enqueue_style('theme', get_stylesheet_directory_uri() .'/stylesheets/app.css', array(), '1.0.0', 'screen');
			wp_enqueue_style('override', get_stylesheet_directory_uri() .'/stylesheets/override.css', array('template'), '1.0.0', 'screen');
		}
		
		
		/**
		*
		* Add Scripts
		* This adds javascript files to the theme. Add any additional script files using the wp_enqueue_script() method.
		* Be mindful of the array of dependencies, the third parameter. Specifying a dependencies will ensure scripts are loaded in proper order.
		* 
		**/
		function _add_scripts() {
			if (is_admin()) return;
			wp_enqueue_script('modernizr', get_stylesheet_directory_uri() .'/bower_components/modernizr/modernizr.js', array(), '2.7.1');
			wp_enqueue_script('foundation', get_stylesheet_directory_uri() .'/bower_components/foundation/js/foundation.min.js', array(), '5.0', true);
			wp_enqueue_script('theme.app', get_stylesheet_directory_uri() .'/js/app.js', array('jquery','modernizr','foundation'), '0.1.0', true);
			wp_localize_script('theme.app', 'theme', $this->_add_js_vars()); // Call _add_js_vars() to add PHP variables to frontend 
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
		 * Modify queries post object(s) when a WP_Query is run.
		 * This applies to all query methods, including get_posts() function.
		 * We are adding all custom ACF field data directly to the object, if ACF function exists.
		 */
		function _posts_results( $posts ) {

			// Don't bother in the admin
			if( is_admin() ) return;

			// Check if ACF function is available
			if( function_exists('get_fields') ) {

				foreach ( $posts as $post ) {

					//Loop through all custom fields
					foreach ( get_fields( $post->ID ) as $key => $field ) {

						// If the key isn't blank, and it doesn't exist already
						if( !empty( $key ) && !property_exists( $post , $key ) ) {

							// Add it to the post object
							$post->{$key} = $field;
						}

					}

				}
			}
			return $posts;
		}
		
		
		/**
		*
		* General Theme Setup
		* Add various functionality, such as sidebars, menus, image sizes, and whatnot
		* 
		**/
		function _setup_theme() {
			
			load_theme_textdomain( 'theme', get_template_directory() . '/languages' );
			
			/**
			*
			* Widget Positions
			* You will be able to add widgets to these positions in the WordPress admin.
			*
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
			*
			* Menus
			* 
			**/
			
			register_nav_menus( array(
				'main_menu' => 'Main Menu',
				// Add additional menus, ie.
				// 'secondary_menu' => 'Secondary Menu',
			) );
			
			
			
			/**
			*
			* Images
			* refer to http://codex.wordpress.org/Function_Reference/add_image_size
			* 
			**/
			// add_image_size('my-image-size', 500,500,false);
			
			
		}
		
		
		
		
		/**
		*
		* Prepackage Plugins
		* This little nugget allows you to add plugins along with the theme. WP will give notice to install these plugins.
		* Thanks to Thomas Griffin for this. PHP source in inc/plugin-activation.class.php.
		* https://github.com/thomasgriffin/TGM-Plugin-Activation
		* 
		**/
		function _register_plugins() {
			
			// My top three, packaged here
			
			$plugins = array(
				array(
					'name' => 'Advanced Custom Fields',
					'slug' => 'advanced-custom-fields',
					'required' => true
				),
				array(
					'name' => 'Custom Post Type UI',
					'slug' => 'custom-post-type-ui',
					'required' => true
				),
				array(
					'name' => 'Better WP Security',
					'slug' => 'better-wp-security',
					'required' => true
				),
				
				
				
				/* Paid Plugins
				 * Get em yourself, and put the .zip files in /plugins/ folder within this theme
				 * /
				
				/*
				
				array(
					'name' => 'Advanced Custom Fields: Repeater Field',
					'slug' => 'acf-repeater',
					'required' => true,
					'source' => get_stylesheet_directory().'/plugins/acf-repeater.zip'
				),
				array(
					'name' => 'Advanced Custom Fields: Options Page',
					'slug' => 'acf-options-page',
					'required' => true,
					'source' => get_stylesheet_directory().'/plugins/acf-options-page.zip'
				),
				array(
					'name' => 'Gravity Forms',
					'slug' => 'gravity-forms',
					'required' => true,
					'source' => get_stylesheet_directory().'/plugins/gravityforms.zip'
				)
				*/
			);
			$config = array(
				'domain' => 'theme'
			);
			tgmpa( $plugins, $config );
		}
		
		
		
		
		/**
		*
		* Add Custom WP Query Variables
		* Allows you to add any new query variables you need to the WP query system.
		* This will allow you to do things like query/search/filter by your custom variable using WP's built-in functionality,
		* rather than using the $_REQUEST global and reinventing the wheel.
		* 
		**/
		function _query_vars( $query_vars ) {
			/*
			array_push( $query_vars,
				'some_query_var',
			);
			*/
			return $query_vars;
		}
		
		
		/**
		*
		* Setup Custom URLS
		* Allows you to create your own URL structures and replacement patterns.
		* Often you will need to use this in conjunction with a custom query variable, in order to create pretty URL's that use that variable.
		* 
		**/
		function _rewrite_rules_array( $rules ) {
			
			$new_rules = array(
				// 'some-slug/([^/]+)/?$' => 'index.php?pagename=some-slug&some_query_var=$matches[1]'
			);
			$rules = $new_rules + $rules;
			return $rules;
		}
		
		
		
		/**
		*
		* Template Redirection
		* Generic hook for handling various redirections. Do what you will.
		* 
		**/
		function _template_redirect() {
			
		}
		
		
	}
	
}

if(class_exists('Theme')){
	$theme = new Theme();
}

?>