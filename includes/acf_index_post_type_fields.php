<?php
/*
 * Register the ACF Field group for assigning an index_post_type to pages using the index template.
 * See tpl_templates/tpl_index.php
 */

if( function_exists("register_field_group")) {
	register_field_group(array (
		'id' => 'acf_index-template-fields',
		'title' => 'Index Template Fields',
		'fields' => array (
			array (
				'key' => 'field_555a3932c85f3',
				'label' => 'Post Type',
				'name' => 'index_post_type',
				'type' => 'select',
				'instructions' => 'This page will be an index for a post type, please select the post type.',
				'choices' => array (
					'post' => 'Posts',
					'page' => 'Pages',
					'attachment' => 'Media',
				),
				'default_value' => '',
				'allow_null' => 0,
				'multiple' => 0,
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'page_template',
					'operator' => '==',
					'value' => 'tpl_templates/tpl_index.php',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'acf_after_title',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
}