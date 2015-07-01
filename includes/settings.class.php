<?php

if( !class_exists( 'TPL_Settings' ) ) {

	class TPL_Settings {

		/**
		*
		* Constructor
		* Primarily used to set up action and filter hooks.
		**/
		function __construct() {

			add_action('admin_menu',array(&$this,'_admin_menu'));
			add_action('admin_init', array(&$this,'_admin_init'));

		}

		/**
		 * Add a Functionality Settings admin submenu page
		 */
		function _admin_menu() {
			add_submenu_page('options-general.php',__('Functionality Settings','theme'), __('Functionality','theme'), 'manage_options', 'functionality-settings', array(&$this,'_functionality_settings_page') );
		}

		/**
		 * Include the Functionality Settings admin submenu page
		 */
		function _functionality_settings_page(){
			include get_template_directory() . '/includes/admin/settings-page.php';
		}

		/**
		 * Add setting sections and fields
		 */
		function _admin_init(){


			/**
			 * =================
			 * Template Settings
			 * =================
			 */
			
			// register setting
			register_setting('functionality-settings','tpl_settings');

			// add setting sections
			add_settings_section(
				'tpl-settings',
				__('Template Settings','theme'),
				array(&$this,'_render_template_settings_section'),
				'functionality-settings'
			);

			// add 'enable_index_template' field
			add_settings_field(
				'enable_index_template',
				__('Enable the index template','theme'),
				array(&$this,'_render_enable_index_template_field'),
				'functionality-settings',
				'tpl-settings'
			);
			


		}







		/**
		 * ========================
		 * ========================
		 * SETTINGS FIELD RENDERERS
		 * ========================
		 * ========================
		 */
		

		/**
		 * ===========================
		 * Template Settings Renderers
		 * ===========================
		 */

		/**
		 * Render content at the top of the Template Settings section
		 */
		function _render_template_settings_section(){
			// silence
		}

		/**
		 * Enable index template
		 * Checkbox field for 'enable_index_template' setting
		 */
		function _render_enable_index_template_field(){

			$options = get_option('tpl_settings');
			$value = isset( $options['enable_index_template'] ) ? $options['enable_index_template'] : 0;
			$checked = checked( $value, 1, false );
			echo '<input type="checkbox" id="enable_index_template" name="tpl_settings[enable_index_template]" value="1" '.$checked.'>';
			echo '<label for="enable_index_template">'.__('This creates an admin-selectable page template that allows you to use that page as a paginated, filterable post-type index.','theme').'</label>';
		}
		

	}

}

if(class_exists('TPL_Settings')){
	$settings = new TPL_Settings();
}