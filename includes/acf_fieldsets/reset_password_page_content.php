<?php

$reset_password_page = get_page_by_path('reset-password');

if( function_exists('register_field_group') && !is_null( $reset_password_page ) ) {

	register_field_group(array (
		'id' => 'acf_reset-password-page-content',
		'title' => 'Reset Password Page Content',
		'fields' => array (
			array (
				'key' => 'field_REQUEST_RESET_CONTENT',
				'label' => 'Request Reset Content',
				'name' => 'request_reset_content',
				'type' => 'wysiwyg',
				'instructions' => 'This content will display on the Reset Password page during the initial request, when a user is prompted to enter their email or username.',
				'default_value' => 'Enter your username or email address below, and an email will be sent to you with instructions on how to reset your password.',
				'toolbar' => 'full',
				'media_upload' => 'yes',
			),
			array (
				'key' => 'field_NEW_PASSWORD_CONTENT',
				'label' => 'New Password Content',
				'name' => 'new_password_content',
				'type' => 'wysiwyg',
				'instructions' => 'This content will display on the Reset Password page when a user is has followed the reset password link from the email that was sent to them.',
				'default_value' => 'Enter a new password below, then enter it again to confirm it. Your password must have at least an "OK" strength to be acceptable. It is recommended you use a combination of upper and lowercase letters, numbers, and special characters.',
				'toolbar' => 'full',
				'media_upload' => 'yes',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'page',
					'operator' => '==',
					'value' => $reset_password_page->ID,
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
				0 => 'the_content',
			),
		),
		'menu_order' => 0,
	));
}
