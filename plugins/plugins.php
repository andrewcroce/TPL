<?php
/**
*
* Prepackage Plugins
* This little nugget allows you to add plugins along with the theme. WP will give notice to install these plugins.
* Thanks to Thomas Griffin for this.
* https://github.com/thomasgriffin/TGM-Plugin-Activation
* 
**/

include( 'class-tgm-plugin-activation.php' );

add_action('tgmpa_register', '_tgmpa_register_plugins');

function _tgmpa_register_plugins(){

	$plugins = array(

		// Required

		array(
			'name' => 'Advanced Custom Fields',
			'slug' => 'advanced-custom-fields',
			'force_activation' => true,
			'required' => true
		),

		array(
			'name' => 'Advanced Custom Fields Objects',
			'slug' => 'advanced-custom-fields-objects',
			'source' => get_stylesheet_directory().'/plugins/packages/advanced-custom-fields-objects.zip',
			'force_activation' => true,
			'external_url' => 'https://bitbucket.org/bneff84/advanced-custom-fields-objects',
			'required' => true
		),

		array(
			'name' => 'Custom Post Type UI',
			'slug' => 'custom-post-type-ui',
			'required' => true
		),



		// Optional

		array(
			'name' => 'WP PHP Console',
			'slug' => 'wp-php-console',
			'required' => false,
		),
		array(
			'name' => 'Better WP Security',
			'slug' => 'better-wp-security',
			'required' => false
		),
		array(
			'name' => 'W3 Total Cache',
			'slug' => 'w3-total-cache',
			'required' => false
		),
		array(
			'name' => 'Manual Image Crop',
			'slug' => 'manual-image-crop',
			'required' => false
		),
		array(
			'name' => 'Force Regenerate Thumbnails',
			'slug' => 'force-regenerate-thumbnails',
			'required' => false
		),
		array(
			'name' => 'Advanced Custom Fields: Repeater Field',
			'slug' => 'acf-repeater',
			'external_url' => 'http://www.advancedcustomfields.com/add-ons/repeater-field/',
			'required' => false,
			'source' => get_stylesheet_directory().'/plugins/packages/acf-repeater.zip'
		),
		array(
			'name' => 'Advanced Custom Fields: Options Page',
			'slug' => 'acf-options-page',
			'external_url' => 'http://www.advancedcustomfields.com/add-ons/options-page/',
			'required' => false,
			'source' => get_stylesheet_directory().'/plugins/packages/acf-options-page.zip'
		),
		array(
			'name' => 'Advanced Custom Fields: Flexible Content',
			'slug' => 'acf-flexible-content',
			'external_url' => 'http://www.advancedcustomfields.com/resources/flexible-content/',
			'required' => false,
			'source' => get_stylesheet_directory().'/plugins/packages/acf-flexible-content.zip'
		),
		array(
			'name' => 'Admin Menu Editor Pro',
			'slug' => 'admin-menu-editor-pro',
			'external_url' => 'http://adminmenueditor.com/',
			'required' => false,
			'source' => get_stylesheet_directory().'/plugins/packages/admin-menu-editor-pro.zip'
		),
		array(
			'name' => 'Advanced Post Types Order',
			'slug' => 'advanced-post-types-order',
			'external_url' => 'http://www.nsp-code.com/premium-plugins/wordpress-plugins/advanced-post-types-order/',
			'required' => false,
			'source' => get_stylesheet_directory().'/plugins/packages/advanced-post-types-order.zip'
		),
		array(
			'name' => 'Gravity Forms',
			'slug' => 'gravity-forms',
			'required' => false,
			'external_url' => 'http://www.gravityforms.com/',
			'source' => get_stylesheet_directory().'/plugins/packages/gravityforms.zip'
		),
		array(
			'name' => 'Relevanssi',
			'slug' => 'relevanssi',
			'required' => false
		)
		
	);

	$config = array(
		'domain' => 'theme',
		'is_automatic' => true
	);

	tgmpa( $plugins, $config );
}
?>