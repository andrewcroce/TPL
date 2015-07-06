<?php

/**
 * Theme
 *
 * @package WordPress
 * @subpackage WP_Starter_Theme
 */


if(!class_exists('Theme')){
	
	class Theme {
		
		
		/**
		 * Load
		 * Primarily used to set up action and filter hooks.
		 */
		public static function load() {


			add_action('after_switch_theme', 					array(__CLASS__,'_theme_activated'));
			add_action('wp_print_styles', 						array(__CLASS__,'_add_styles'));
			add_action('wp_print_scripts', 						array(__CLASS__,'_add_scripts'));
			add_action('after_setup_theme', 					array(__CLASS__,'_setup_theme'));
			add_action('update_option_template_settings', 		array(__CLASS__,'_updated_template_settings'), 10, 3);

			add_filter('skiplinks', 			array(__CLASS__,'_add_skiplinks'));
			add_filter('template_include', 		array(__CLASS__,'_template_include'));
			add_filter('theme_page_templates',	array(__CLASS__,'_theme_page_templates'));
			add_filter('body_class', 			array(__CLASS__,'_body_class'));
			add_filter('query_vars', 			array(__CLASS__,'_query_vars'));
			add_filter('rewrite_rules_array', 	array(__CLASS__,'_rewrite_rules_array'));
			add_filter('get_search_form', 		array(__CLASS__,'_get_search_form'));
			add_filter('the_content', 			array(__CLASS__,'_the_content'));
		}


		/**
		 * Theme activation
		 * This runs when the theme is activated
		 */
		static function _theme_activated() {

			// Generate & activate the home page if the setting is enabled
			if( Settings::generate_home_page_enabled() ){
				self::_generate_home_page();
				self::_activate_home_page();
			}
			
			// Generate a style guide if the setting is enabled
			if( Settings::generate_style_guide_enabled() ){
				self::_generate_style_guide();
			}

			// Generate login and reset-password pages if the setting is enabled
			if( Settings::frontend_login_enabled() ){
				self::_generate_login_pages();
			}
			

		}
		


		/**
		 * Template Settings have been updated.
		 * Generate any necessary pages if their settings are enabled.
		 * @param  array/null $option    Array of template settings, or nothing if they are all disabled
		 */
		static function _updated_template_settings( $old_settings, $settings ){

			
			// Settings are enabled
			if( !empty( $settings ) ) {

				// Generate home page if its enabled
				if( isset( $settings['generate_home_page'] ) && $settings['generate_home_page'] == 1 ) {

					self::_generate_home_page();
					self::_activate_home_page();

				} else {

					self::_activate_home_page( false );

				}

				// Generate style guide if its enabled
				if( isset( $settings['generate_style_guide'] ) && $settings['generate_style_guide'] == 1 ) {
					self::_generate_style_guide();
				}

			// No Template Settings are enabled
			} else {

				// Deactivate the home page
				self::_activate_home_page( false );

			}
		}


		

		
		
		/**
		*
		* Add Styles
		* This adds CSS files to the theme. You shouldn't need to add files, since app.css is compiled from SASS.
		**/
		static function _add_styles() {
			if (is_admin()) return;
			wp_enqueue_style('theme', get_stylesheet_directory_uri() .'/css/app.css', array(), '1.0.0', 'screen');
		}
		

		function _admin_menu() {
			add_options_page( __('Functionality Settings','theme'), __('Functionality','theme'), 'manage_options', 'theme-functionality.php', array(&$this,'_functionality_settings_page') );
		}
		function _functionality_settings_page(){ 
			include 'includes/admin/theme-functionality-settings.php';
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
		static function _add_scripts() {
			
			// Don't mess with admin scripts
			if (is_admin()) return;

			// Find the current required jquery version
			$wp_jquery_ver = $GLOBALS['wp_scripts']->registered['jquery']->ver;

			// Deregister jquery, so we can re-register it in the footer
			wp_deregister_script('jquery');

			wp_enqueue_script('modernizr', get_stylesheet_directory_uri() .'/bower_components/modernizr/modernizr.js', array(), '2.8.3');
			wp_enqueue_script('jquery', site_url('/wp-includes/js/jquery/jquery.js?ver='.$wp_jquery_ver), array(), $wp_jquery_ver, true );
			wp_enqueue_script('theme.app', get_stylesheet_directory_uri() .'/js/min/app-min.js', array('modernizr','jquery'), '0.1.0', true);
			wp_localize_script('theme.app', 'theme', self::_add_js_vars()); // Call _add_js_vars() to add PHP variables to frontend 

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
		static function _add_js_vars() {
			return array(
				'ajax_url' => admin_url('admin-ajax.php'),
				'site_url' => get_site_url(),
				'doc_title' => get_bloginfo('title'),
				// Add any additional variables you want accessible in Javascript
			);
		}



		/**
		 * Generate home page
		 */
		static function _generate_home_page(){

			$home_page = get_page_by_path('home-page');

			if( is_null( $home_page ) ){
				$home_page = wp_insert_post(array(
					'post_content' => __('<p>This is the home page. Add ACF fields and customize as needed.</p>','theme'),
					'post_name' => 'home-page',
					'post_title' => 'Home Page',
					'post_status' => 'publish',
					'post_type' => 'page'
				));
			}

		}



		/**
		 * Activate or deactivate home page as the front page
		 * @param  boolean $activate Should we activate or deactivate?
		 */
		static function _activate_home_page( $activate = true ){


			if( $activate == true ){

				$home_page = get_page_by_path('home-page');

				if( ! $home_page ){
					self::_generate_home_page();
				}

				if( $home_page instanceof WP_Error ){
					trigger_error('Error generating home page');
				} else {
					update_option( 'page_on_front', $home_page->ID );
					update_option( 'show_on_front', 'page' );
				}

			} else {

				update_option( 'show_on_front', 'posts' );

			}

		}



		/**
		 * Generate a style-guide page
		 * @see tpl_pages/page-style-guide.php
		 */
		static function _generate_style_guide(){

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
		}








		/**
		 * Custom filter function that adds skiplinks to tpl_navs/nav-skiplinks.php
		 * @return array List of anchor links to add
		 *    {anchor} => {label} 
		 */
		static function _add_skiplinks() {

			$links =  array(
				'#main-navigation' => __('Skip to main navigation','theme'),
				'#main-content' => __('Skip to main content','theme'),
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
		static function _setup_theme() {

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
			* Menu locations can be added in the WP Admin -> Settings -> TPL Config
			**/
			$menu_options = get_option('menu_settings');

			if( isset( $menu_options['menu_locations'] ) && count( $menu_options['menu_locations']['location'] ) ){

				$menu_locations = array();

				foreach( $menu_options['menu_locations']['location'] as $location ) {
					$menu_locations[$location['slug']] = __( $location['description'], 'theme' );
				}

				// Add them manually if you must
				// $menu_locations['some_menu'] = __( 'Some Menu', 'theme' );

				register_nav_menus( $menu_locations );
			}

			



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
		static function _body_class( $classes ){

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
		static function _query_vars( $query_vars ) {

			$new_vars = array();
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
			
			$new_rules = array();
			$rules = $new_rules + $rules;
			return $rules;
		}
		


		/**
		 * Load templates in subdirectories
		 *
		 * @param string $template 	Path to template file
		 * @return string 			Path to (different) template file
		 **/
		static function _template_include( $template ){

			global $post;

			// If this is a page, try to locate a template file with a matching name
			if( is_page() && !is_page_template() ) {

				// If this is the front page, try to load the home page template
				if( is_front_page() ){
					if( is_readable( get_template_directory() . '/tpl_pages/page-home.php' ) ){
						return locate_template( 'tpl_pages/page-home.php' );
					} else {
						return locate_template( 'tpl_pages/page-default.php' );
					}
				}

				if( is_readable( get_template_directory() . '/tpl_pages/page-' . $post->post_name . '.php' ) ){
					return locate_template( 'tpl_pages/page-' . $post->post_name . '.php' );
				} else {
					return locate_template( 'tpl_pages/page-default.php' );
				}
			}

			// If this is a single post type (other than a page), try to locate a template file with a matching name
			if( is_single() ) {
				if( is_readable( get_template_directory() . '/tpl_singles/single-' . $post->post_type . '.php' ) ){
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

				if( is_readable( $specific_template_path ) )
					return $specific_template_path;
			}

			return $template;
		}


		/**
		 * Intercept our search form output so we can increment an ID counter.
		 * Since the searchform can be output multiple times on a given page, this could lead to invalid duplicate HTML IDs.
		 * We increment this counter, and append it to the field IDs, so each one is unique.
		 */
		static function _get_search_form(){
			/**
			 * Since we might have more than one search form on a page, incrememnt a global counter
			 * which will be used to create unique HTML ids for each form's fields
			 */
			global $search_form_counter;
			$search_form_counter += 1;


			if( is_readable( get_template_directory() . '/tpl_forms/form-search.php' ) ) {

				include get_template_directory() . '/tpl_forms/form-search.php';
				return false;

			}
		}



		/**
		 * Disable the Index template if the setting isn't enabled
		 * @param  array $templates List of page templates available for the theme
		 * @return array            Return the (modified) list of available templates
		 */
		static function _theme_page_templates( $templates ){
			if( isset( $templates['tpl_templates/template-index.php'] ) && ! Settings::index_template_enabled() )
				unset( $templates['tpl_templates/template-index.php'] );
			return $templates;
		}



		/**
		 * Hook The Content Filter
		 * @param  string $content HTML content
		 * @return string          Modified HTML
		 */
		static function _the_content( $content ){

			// Strip <p> tags around images, because images are not paragraphs
			$content = preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);

			// Wrap it in a wysiwyg class
			$content = '<div class="wysiwyg">' . $content . '</div>';

			return $content;
		}
		
	}
	
}


if( class_exists('Theme') ){
	Theme::load();
}
