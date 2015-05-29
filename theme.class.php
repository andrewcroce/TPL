<?php

/**
 * Starter Theme
 *
 * @package WordPress
 * @subpackage WP_Starter_Theme
 */


if(!class_exists('StarterTheme')){
	
	class StarterTheme {
		
		
		/**
		*
		* Constructor
		* Primarily used to set up action and filter hooks.
		**/
		function __construct() {
			
			add_action('init', array(&$this, '_init'));
			add_action('wp_print_styles', array(&$this,'_add_styles'));
			add_action('wp_print_scripts', array(&$this,'_add_scripts'));
			add_action('after_setup_theme', array(&$this,'_setup_theme'));
			add_action('template_redirect', array(&$this,'_template_redirect'));

			add_filter('wp_starter_skiplinks', array(&$this,'_add_skiplinks'));
			add_filter('template_include', array(&$this,'_template_include'));
			add_filter('body_class', array(&$this,'_body_class'));
			add_filter('query_vars', array(&$this,'_query_vars'));
			add_filter('rewrite_rules_array', array(&$this,'_rewrite_rules_array'));
			add_filter('acf/load_field/name=index_post_type', array(&$this,'_acf_load_index_post_type') );
			add_filter('the_content', array(&$this,'_the_content'));

			
			// Add any additional action or filter hooks here.
		}



		/* 
		* 
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



			/**
			* Generate Style Guide Page
			**/
			$style_guide = get_page_by_path('style-guide');
			if( is_null( $style_guide ) ){
				wp_insert_post(array(
					'post_content' => __('<p>This page is require to display the style guide, please do not delete it.</p>'),
					'post_name' => 'style-guide',
					'post_title' => 'Style Guide',
					'post_status' => 'publish',
					'post_type' => 'page'
				));
			}
			
			
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
				// Add vars
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
				// 'some-slug/([^/]+)/?$' => 'index.php?pagename=some-slug&some_query_var=$matches[1]'
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
		
	}
	
}

if(class_exists('StarterTheme')){
	$theme = new StarterTheme();
}

?>