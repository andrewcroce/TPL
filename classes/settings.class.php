<?php

if( !class_exists( 'Settings' ) ) {

	class Settings {


		/**
		*
		* Load
		* Primarily used to set up action and filter hooks.
		**/
		public static function load() {

			add_action('admin_menu',		array(__CLASS__,'_admin_menu'));
			add_action('admin_init', 		array(__CLASS__,'_admin_init'));
		}



		/**
		 * Add a Functionality Settings admin submenu page
		 */
		static function _admin_menu() {
			add_submenu_page(
				'options-general.php',
				__('TPL Configuration Settings','theme'),
				__('TPL Config','theme'),
				'manage_options',
				'tpl-config',
				array(__CLASS__,'_tpl_config_page') );
		}



		/**
		 * Include the Functionality Settings admin submenu page
		 */
		static function _tpl_config_page(){
			include get_template_directory() . '/includes/admin/tpl-config-page.php';
		}



		/**
		 * Add setting sections and fields
		 */
		static function _admin_init(){


			/**
			 * ========================
			 * Page & Template Settings
			 * ========================
			 */
			
			// register setting
			register_setting('tpl-config','template_settings');

			// add setting section
			add_settings_section(
				'template-settings',
				__('Page & Template Settings','theme'),
				array(__CLASS__,'_render_template_settings_section'),
				'tpl-config'
			);

			// add 'generate_home_page' field
			add_settings_field(
				'generate_home_page',
				__('Home Page','theme'),
				array(__CLASS__,'_render_generate_home_page_field'),
				'tpl-config',
				'template-settings'
			);

			// add 'generate_style_guide' field
			add_settings_field(
				'generate_style_guide',
				__('Style Guide','theme'),
				array(__CLASS__,'_render_generate_style_guide_field'),
				'tpl-config',
				'template-settings'
			);

			// add 'enable_index_template' field
			add_settings_field(
				'enable_index_template',
				__('Index template','theme'),
				array(__CLASS__,'_render_enable_index_template_field'),
				'tpl-config',
				'template-settings'
			);
			


			/**
			 * =============
			 * Menu Settings
			 * =============
			 */
			
			// register setting
			register_setting('tpl-config','menu_settings');

			// add setting section
			add_settings_section(
				'menu-settings',
				__('Menu Settings','theme'),
				array(__CLASS__,'_render_menu_settings_section'),
				'tpl-config'
			);

			// add 'enable_index_template' field
			add_settings_field(
				'menu_locations',
				__('Menu locations','theme'),
				array(__CLASS__,'_render_menu_locations_field'),
				'tpl-config',
				'menu-settings'
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

			echo '<div data-repeater-list="menu_settings[menu_locations][location]">';

			if( $value ){

				foreach( $value['location'] as $key => $location ) {
					echo '<div data-repeater-item>';
						echo '<label for="menu_location_slug_'.$key.'">'.__('Slug','theme').'</label> <input type="text" id="menu_location_slug_'.$key.'" name="menu_settings[menu_locations][location]['.$key.'][slug]" value="'.$location['slug'].'"> &nbsp;&nbsp;&nbsp;';
						echo '<label for="menu_location_desc_'.$key.'">'.__('Description','theme').'</label> <input type="text" id="menu_location_desc_'.$key.'" name="menu_settings[menu_locations][location]['.$key.'][description]" value="'.$location['description'].'">';
						echo '<input data-repeater-delete type="button" class="button-secondary" value="Delete"/>';
					echo '</div>';
				}

			} else {
				echo '<div data-repeater-item>';
					echo '<label for="menu_location_slug">'.__('Slug','theme').'</label> <input type="text" id="menu_location_slug" name="menu_settings[menu_locations][location][][slug]" value="" > &nbsp;&nbsp;&nbsp;';
					echo '<label for="menu_location_desc">'.__('Description','theme').'</label> <input type="text" id="menu_location_desc" name="menu_settings[menu_locations][location][][description]" value="">';
					echo '<input data-repeater-delete type="button" class="button-secondary" value="Delete"/>';
				echo '</div>';
			}
			echo '</div>';
			echo '<p><input data-repeater-create class="button-secondary" type="button" value="Add Menu Location"/></p>';
		
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
		 * Generate home page
		 */
		static function _render_generate_home_page_field(){
			$options = get_option('template_settings');
			$value = isset( $options['generate_home_page'] ) ? $options['generate_home_page'] : 0;
			$checked = checked( $value, 1, false );
			echo '<input type="checkbox" id="generate_home_page" name="template_settings[generate_home_page]" value="1" '.$checked.'>';
			echo '<label for="generate_home_page">'.__('Enable','theme').'</label>';
			echo '<p class="description">'.__('This will generate a page called Home Page and set it as the front page. Note: disabling this setting <strong>will not delete</strong> the page, but it will restore the default post index front page.','theme').'</p>';
		}


		static function _render_generate_style_guide_field(){
			$options = get_option('template_settings');
			$value = isset( $options['generate_style_guide'] ) ? $options['generate_style_guide'] : 0;
			$checked = checked( $value, 1, false );
			echo '<input type="checkbox" id="generate_style_guide" name="template_settings[generate_style_guide]" value="1" '.$checked.'>';
			echo '<label for="generate_style_guide">'.__('Enable','theme').'</label>';
			echo '<p class="description">'.__('This will generate a Style Guide page with various HTML elements. To edit the page, see tpl_pages/page-style-guide.php. Note: disabling this setting <strong>will not delete</strong> the page.','theme').'</p>';
		}


		/**
		 * Enable index template
		 * Checkbox field for 'enable_index_template' setting
		 */
		static function _render_enable_index_template_field(){

			$options = get_option('template_settings');
			$value = isset( $options['enable_index_template'] ) ? $options['enable_index_template'] : 0;
			$checked = checked( $value, 1, false );
			echo '<input type="checkbox" id="enable_index_template" name="template_settings[enable_index_template]" value="1" '.$checked.'>';
			echo '<label for="enable_index_template">'.__('Enable','theme').'</label>';
			echo '<p class="description">'.__('This creates an admin-selectable page template that allows you to use that page as a paginated, filterable post-type index.','theme').'</p>';
		}
		


		/**
		 * ========================
		 * Settings Check functions
		 * ========================
		 */

		public static function index_template_enabled() {

			$template_settings = get_option('template_settings');
			if( isset( $template_settings ) && isset( $template_settings['enable_index_template'] ) && $template_settings['enable_index_template'] == 1 )
				return true;
			return false;
		}


		public static function generate_home_page_enabled() {

			$template_settings = get_option('template_settings');
			if( isset( $template_settings ) && isset( $template_settings['generate_home_page'] ) && $template_settings['generate_home_page'] == 1 )
				return true;
			return false;

		}


		public static function generate_style_guide_enabled() {

			$template_settings = get_option('template_settings');
			if( isset( $template_settings ) && isset( $template_settings['generate_style_guide'] ) && $template_settings['generate_style_guide'] == 1 )
				return true;
			return false;

		}
		

	}

}

if( class_exists('Settings') ){
	Settings::load();
}