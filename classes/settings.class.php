<?php

if( !class_exists( 'Settings' ) ) {

	class Settings {



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

		public static function frontend_login_enabled() {
			$member_tools_settings = get_option('member_tools_settings');
			if( isset( $member_tools_settings ) && isset( $member_tools_settings['enable_frontend_login'] ) && $member_tools_settings['enable_frontend_login'] == 1 )
				return true;
			return false;
		}

		public static function frontend_profile_enabled() {
			$member_tools_settings = get_option('member_tools_settings');
			if( isset( $member_tools_settings ) && isset( $member_tools_settings['enable_frontend_profile'] ) && $member_tools_settings['enable_frontend_profile'] == 1 )
				return true;
			return false;
		}

		public static function frontend_registration_enabled() {
			$member_tools_settings = get_option('member_tools_settings');
			if( isset( $member_tools_settings ) && isset( $member_tools_settings['enable_frontend_registration'] ) && $member_tools_settings['enable_frontend_registration'] == 1 )
				return true;
			return false;
		}

		public static function registration_activation_required() {
			$member_tools_settings = get_option('member_tools_settings');
			if( isset( $member_tools_settings ) && isset( $member_tools_settings['enable_frontend_registration'] ) && $member_tools_settings['enable_frontend_registration'] == 1 ){
				if( isset( $member_tools_settings['registration_activation_required'] ) && $member_tools_settings['registration_activation_required'] == 1 )
					return true;
				return false;
			}
			return false;
		}





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
			register_setting('tpl-config-template','template_settings');

			// add setting section
			add_settings_section(
				'template-settings',
				__('Page & Template Settings','theme'),
				array(__CLASS__,'_render_template_settings_section'),
				'tpl-config-template'
			);

			// add 'generate_home_page' field
			add_settings_field(
				'generate_home_page',
				__('Home Page','theme'),
				array(__CLASS__,'_render_generate_home_page_field'),
				'tpl-config-template',
				'template-settings'
			);

			// add 'generate_style_guide' field
			add_settings_field(
				'generate_style_guide',
				__('Style Guide','theme'),
				array(__CLASS__,'_render_generate_style_guide_field'),
				'tpl-config-template',
				'template-settings'
			);

			// add 'enable_index_template' field
			add_settings_field(
				'enable_index_template',
				__('Index template','theme'),
				array(__CLASS__,'_render_enable_index_template_field'),
				'tpl-config-template',
				'template-settings'
			);



			/**
			 * =====================
			 * Member Tools Settings
			 * =====================
			 */
			
			// register setting
			register_setting('tpl-config-member-tools','member_tools_settings');

			// add setting section
			add_settings_section(
				'member-tools-settings',
				__('Member Tools Settings','theme'),
				array(__CLASS__,'_render_member_tools_settings_section'),
				'tpl-config-member-tools'
			);

			// add 'enable_frontend_login' field
			add_settings_field(
				'enable_frontend_login',
				__('Front End Login','theme'),
				array(__CLASS__,'_render_enable_frontend_login_field'),
				'tpl-config-member-tools',
				'member-tools-settings'
			);

			add_settings_field(
				'password_reset_email_content',
				__('<span class="display-toggleable" data-toggle-control="enable_frontend_login">Password Reset Email Content</span>','theme'),
				array(__CLASS__,'_render_password_reset_email_content_field'),
				'tpl-config-member-tools',
				'member-tools-settings'
			);

			// add 'enable_frontend_profile' field
			add_settings_field(
				'enable_frontend_profile',
				__('Front End Profile','theme'),
				array(__CLASS__,'_render_enable_frontend_profile_field'),
				'tpl-config-member-tools',
				'member-tools-settings'
			);

			// add 'enable_frontend_registration' field
			add_settings_field(
				'enable_frontend_registration',
				__('Front End Registration','theme'),
				array(__CLASS__,'_render_enable_frontend_registration_field'),
				'tpl-config-member-tools',
				'member-tools-settings'
			);

			// add 'registration_activation_required' field
			add_settings_field(
				'registration_activation_required',
				__('<span class="display-toggleable" data-toggle-control="enable_frontend_registration">Require Account Activation</span>','theme'),
				array(__CLASS__,'_render_registration_activation_required_field'),
				'tpl-config-member-tools',
				'member-tools-settings'
			);

			// add 'registration_email_content' field
			add_settings_field(
				'registration_email_content',
				__('<span class="display-toggleable" data-toggle-control="registration_activation_required">Registration Activation Email Content</span>','theme'),
				array(__CLASS__,'_render_registration_email_content_field'),
				'tpl-config-member-tools',
				'member-tools-settings'
			);

			// add 'activation_email_content' field
			add_settings_field(
				'activation_email_content',
				__('<span class="display-toggleable" data-toggle-control="registration_activation_required">Resend Activation Email Content</span>','theme'),
				array(__CLASS__,'_render_activation_email_content_field'),
				'tpl-config-member-tools',
				'member-tools-settings'
			);



			/**
			 * =============
			 * Menu Settings
			 * =============
			 */
			
			// register setting
			register_setting('tpl-config-menus','menu_settings');

			// add setting section
			add_settings_section(
				'menu-settings',
				__('Menu Settings','theme'),
				array(__CLASS__,'_render_menu_settings_section'),
				'tpl-config-menus'
			);

			// add 'enable_index_template' field
			add_settings_field(
				'menu_locations',
				__('Menu locations','theme'),
				array(__CLASS__,'_render_menu_locations_field'),
				'tpl-config-menus',
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
		 * ===============================
		 * Member Tools Settings Renderers
		 * ===============================
		 */

		/**
		 * Render content at the top of the Member Tools Settings section
		 */
		static function _render_member_tools_settings_section(){
			// Silence
		}


		/**
		 * Enable front end login tools
		 */
		static function _render_enable_frontend_login_field(){
			$options = get_option('member_tools_settings');
			$value = isset( $options['enable_frontend_login'] ) ? $options['enable_frontend_login'] : 0;
			$checked = checked( $value, 1, false );
			echo '<input class="display-toggle" type="checkbox" id="enable_frontend_login" name="member_tools_settings[enable_frontend_login]" value="1" '.$checked.'>';
			echo '<label for="enable_frontend_login">'.__('Enable','theme').'</label>';
			echo '<p class="description">'.__('This will generate front end login and password reset pages. Note: disabling this setting <strong>will not delete</strong> the pages.','theme').'</p>';
		}

		static function _render_password_reset_email_content_field(){
			$options = get_option('member_tools_settings');
			echo '<div class="display-toggleable" data-toggle-control="enable_frontend_login">';
			$default = '<p>A password reset request was submitted for {user_display_name} <{user_email}>. If this was a mistake, you may safely ignore this email.</p><p>To reset your password, please click the following link:<br><strong>{reset_key_link}</strong></p>';
			$value = isset( $options['password_reset_email_content'] ) ? $options['password_reset_email_content'] : $default;
			wp_editor( $value, 'password_reset_email_content',
				array(
					'wpautop' => true,
					'media_buttons' => true,
					'textarea_name' => 'member_tools_settings[password_reset_email_content]',
					'textarea_rows' => 6
				)
			);
			echo '<p class="description">'.__('The content of the email sent for password reset requests. The following placeholder tags can be added: {reset_key_link}, {user_display_name}, {user_email}, {site_title}.','theme').'</p>';
			echo '</div>';
		}

		static function _render_enable_frontend_profile_field(){
			$options = get_option('member_tools_settings');
			$value = isset( $options['enable_frontend_profile'] ) ? $options['enable_frontend_profile'] : 0;
			$checked = checked( $value, 1, false );
			echo '<input type="checkbox" id="enable_frontend_profile" name="member_tools_settings[enable_frontend_profile]" value="1" '.$checked.'>';
			echo '<label for="enable_frontend_profile">'.__('Enable','theme').'</label>';
			echo '<p class="description">'.__('This will generate front end profile form page. Note: disabling this setting <strong>will not delete</strong> the page.','theme').'</p>';
		}


		static function _render_enable_frontend_registration_field(){
			$options = get_option('member_tools_settings');
			$value = isset( $options['enable_frontend_registration'] ) ? $options['enable_frontend_registration'] : 0;
			$checked = checked( $value, 1, false );
			echo '<input class="display-toggle" type="checkbox" id="enable_frontend_registration" name="member_tools_settings[enable_frontend_registration]" value="1" '.$checked.'>';
			echo '<label for="enable_frontend_registration">'.__('Enable','theme').'</label>';
			echo '<p class="description">'.__('This will generate front end registration form page. Note: disabling this setting <strong>will not delete</strong> the page.','theme').'</p>';
		}

		static function _render_registration_activation_required_field(){
			$options = get_option('member_tools_settings');
			$value = isset( $options['registration_activation_required'] ) ? $options['registration_activation_required'] : 0;
			$checked = checked( $value, 1, false );
			echo '<div class="display-toggleable" data-toggle-control="enable_frontend_registration">';
			echo '<input type="checkbox" class="display-toggle" id="registration_activation_required" name="member_tools_settings[registration_activation_required]" value="1" '.$checked.'>';
			echo '<label for="registration_activation_required">'.__('Enable','theme').'</label>';
			echo '<p class="description">'.__('If front end registration is enabled, this will send an email to new users, and required them to click a link and login to activate their account. Private account pages will be restricted until a user\'s account is activated.','theme').'</p>';
			echo '</div>';
		}
		
		static function _render_registration_email_content_field(){
			$options = get_option('member_tools_settings');
			echo '<div class="display-toggleable" data-toggle-control="registration_activation_required">';
			$default = '<p>Thank you for registering on {site_title}. To complete the process, please click the link below and login.</p><p>Activate your registration here:<br><strong>{activation_link}</strong></p>';
			$value = isset( $options['registration_email_content'] ) ? $options['registration_email_content'] : $default;
			wp_editor( $value, 'registration_email_content',
				array(
					'wpautop' => true,
					'media_buttons' => true,
					'textarea_name' => 'member_tools_settings[registration_email_content]',
					'textarea_rows' => 6
				)
			);
			echo '<p class="description">'.__('The content of the email sent after registration with activation required. The following placeholder tags can be added: {activation_link}, {user_display_name}, {user_email}, {site_title}.','theme').'</p>';
			echo '</div>';
		}

		static function _render_activation_email_content_field(){
			$options = get_option('member_tools_settings');
			echo '<div class="display-toggleable" data-toggle-control="registration_activation_required">';
			$default = '<p>A profile activation request was submitted for {user_display_name} <{user_email}>.</p><p>To activate your profile, please click the following link:<br><strong>{activation_link}</strong></p>';
			$value = isset( $options['activation_email_content'] ) ? $options['activation_email_content'] : $default;
			wp_editor( $value, 'activation_email_content',
				array(
					'wpautop' => true,
					'media_buttons' => true,
					'textarea_name' => 'member_tools_settings[activation_email_content]',
					'textarea_rows' => 6
				)
			);
			echo '<p class="description">'.__('The content of the email sent when an account re-activation is requested. The following placeholder tags can be added: {activation_link}, {user_display_name}, {user_email}, {site_title}.','theme').'</p>';
			echo '</div>';
		}

	}

}

if( class_exists('Settings') ){
	Settings::load();
}