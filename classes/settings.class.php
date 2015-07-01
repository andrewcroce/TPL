<?php

if( !class_exists( 'Settings' ) ) {

	class Settings {

		/**
		*
		* Load
		* Primarily used to set up action and filter hooks.
		**/
		public static function load() {

			add_action('admin_menu',	array(__CLASS__,'_admin_menu'));
			add_action('admin_init', 	array(__CLASS__,'_admin_init'));

		}

		/**
		 * Add a Functionality Settings admin submenu page
		 */
		static function _admin_menu() {
			add_submenu_page(
				'options-general.php',
				__('Functionality Settings','theme'),
				__('Functionality','theme'),
				'manage_options',
				'functionality-settings',
				array(__CLASS__,'_functionality_settings_page') );
		}

		/**
		 * Include the Functionality Settings admin submenu page
		 */
		static function _functionality_settings_page(){
			include get_template_directory() . '/includes/admin/settings-page.php';
		}

		/**
		 * Add setting sections and fields
		 */
		static function _admin_init(){


			/**
			 * =============
			 * Menu Settings
			 * =============
			 */
			
			// register setting
			register_setting('functionality-settings','menu_settings');

			// add setting section
			add_settings_section(
				'menu-settings',
				__('Menu Settings','theme'),
				array(__CLASS__,'_render_menu_settings_section'),
				'functionality-settings'
			);

			// add 'enable_index_template' field
			add_settings_field(
				'menu_locations',
				__('Menu locations','theme'),
				array(__CLASS__,'_render_menu_locations_field'),
				'functionality-settings',
				'menu-settings'
			);



			/**
			 * =================
			 * Template Settings
			 * =================
			 */
			
			// register setting
			register_setting('functionality-settings','tpl_settings');

			// add setting section
			add_settings_section(
				'tpl-settings',
				__('Template Settings','theme'),
				array(__CLASS__,'_render_template_settings_section'),
				'functionality-settings'
			);

			// add 'enable_index_template' field
			add_settings_field(
				'enable_index_template',
				__('Index template','theme'),
				array(__CLASS__,'_render_enable_index_template_field'),
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
		 * =======================
		 * Menu Settings Renderers
		 * =======================
		 */

		/**
		 * Render content at the top of the Menu Settings section
		 */
		static function _render_menu_settings_section(){
			// silence
		}


		static function _render_menu_locations_field(){

			$options = get_option('menu_settings');
			$value = isset( $options['menu_locations'] ) ? $options['menu_locations'] : 0;

			PC::debug($value);

			echo '<div data-repeater-list="menu_locations">';

			if( $value ){

				foreach( $value['location'] as $key => $location ) {
					echo '<div data-repeater-item>';
						echo '<label for="menu_location_slug_'.$key.'">'.__('Slug','theme').'</label> <input type="text" id="menu_location_slug_'.$key.'" name="menu_settings[menu_locations][location]['.$key.'][slug]" value="'.$location['slug'].'"> ';
						echo '<label for="menu_location_desc_'.$key.'">'.__('Description','theme').'</label> <input type="text" id="menu_location_desc_'.$key.'" name="menu_settings[menu_locations][location]['.$key.'][description]" value="'.$location['description'].'">';
						echo '<input data-repeater-delete type="button" value="Delete"/>';
					echo '</div>';
				}

			} else {
				echo '<div data-repeater-item>';
					echo '<label for="menu_location_slug_0">'.__('Slug','theme').'</label> <input type="text" id="menu_location_slug_0" name="menu_settings[menu_locations][location][0][slug]" value="" > ';
					echo '<label for="menu_location_desc_0">'.__('Description','theme').'</label> <input type="text" id="menu_location_desc_0" name="menu_settings[menu_locations][location][0][description]" value="">';
					echo '<input data-repeater-delete type="button" value="Delete"/>';
				echo '</div>';
			}
			echo '</div>';
			echo '<input data-repeater-create type="button" value="Add"/>';
		
		}



		/**
		 * ===========================
		 * Template Settings Renderers
		 * ===========================
		 */

		/**
		 * Render content at the top of the Template Settings section
		 */
		static function _render_template_settings_section(){
			// silence
		}

		/**
		 * Enable index template
		 * Checkbox field for 'enable_index_template' setting
		 */
		static function _render_enable_index_template_field(){

			$options = get_option('tpl_settings');
			$value = isset( $options['enable_index_template'] ) ? $options['enable_index_template'] : 0;
			$checked = checked( $value, 1, false );
			echo '<input type="checkbox" id="enable_index_template" name="tpl_settings[enable_index_template]" value="1" '.$checked.'>';
			echo '<label for="enable_index_template">'.__('Enable','theme').'</label>';
			echo '<p class="description">'.__('This creates an admin-selectable page template that allows you to use that page as a paginated, filterable post-type index.','theme').'</p>';
		}
		

	}

}

if( class_exists('Settings') ){
	Settings::load();
}