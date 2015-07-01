<?php

/**
 * Hooks
 */
add_action('after_switch_theme', 'tpl_activated');
add_action('init', 'tpl_init');



/** 
 * We use a generalized init function to intercept any custom form submissions
 * For example, if a form is submitted where $_POST['form_action'] == 'submit_something',
 * this form can be handled by creating an action hook add_action('form_action_submit_something','my_form_submission_handler');
 */
if( isset( $_POST['form_action'] ) ) {
	$action = '_'.$_POST['form_action'];
	$params = !empty($_POST['params']) ? $_POST['params'] : array();
	do_action( 'form_action_' . $action, $params );
}



/**
 * Required files
 */

// Plugin activation class
require get_template_directory() . '/includes/plugins/plugins.php';

// Main theme class
require get_template_directory() . '/classes/theme.class.php';

// Nav Menu Walkers
require get_template_directory() . '/classes/walkers/topbar-walker.class.php';
require get_template_directory() . '/classes/walkers/offcanvas-walker.class.php';



/**
 * Theme has been activated
 */
function tpl_activated(){

	/**
	 *  Set the permalink structure to use postname
	 */
	global $wp_rewrite;
	$wp_rewrite->set_permalink_structure( '/%postname%/' );

}



/**
 * WP Initialized
 */
function tpl_init(){

	/**
	 * Require our custom ACF Fieldset for the post type index page template
	 */
	require get_template_directory() . '/includes/acf_fieldsets/index_post_type_fields.php';

}