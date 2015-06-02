<?php
if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_test-fields',
		'title' => 'Test Fields',
		'fields' => array (
			array (
				'key' => 'field_556cfa7e2aecc',
				'label' => 'Choice',
				'name' => 'choice',
				'type' => 'select',
				'required' => 1,
				'choices' => array (
					'one' => 'One',
					'two' => 'Two',
					'three' => 'Three',
				),
				'default_value' => '',
				'allow_null' => 1,
				'multiple' => 0,
			),
			array (
				'key' => 'field_556cfaae2aecd',
				'label' => 'Sub Choice One',
				'name' => 'sub_choice_one',
				'type' => 'checkbox',
				'required' => 1,
				'conditional_logic' => array (
					'status' => 1,
					'rules' => array (
						array (
							'field' => 'field_556cfa7e2aecc',
							'operator' => '==',
							'value' => 'one',
						),
					),
					'allorany' => 'all',
				),
				'choices' => array (
					'a' => 'A',
					'b' => 'B',
					'c' => 'C',
				),
				'default_value' => '',
				'layout' => 'vertical',
			),
			array (
				'key' => 'field_556cfaf72aece',
				'label' => 'Sub Choice Two',
				'name' => 'sub_choice_two',
				'type' => 'checkbox',
				'required' => 1,
				'conditional_logic' => array (
					'status' => 1,
					'rules' => array (
						array (
							'field' => 'field_556cfa7e2aecc',
							'operator' => '==',
							'value' => 'two',
						),
					),
					'allorany' => 'all',
				),
				'choices' => array (
					'd' => 'D',
					'e' => 'E',
					'f' => 'F',
				),
				'default_value' => '',
				'layout' => 'vertical',
			),
			array (
				'key' => 'field_556cfb292aecf',
				'label' => 'Sub Choice Three',
				'name' => 'sub_choice_three',
				'type' => 'checkbox',
				'required' => 1,
				'conditional_logic' => array (
					'status' => 1,
					'rules' => array (
						array (
							'field' => 'field_556cfa7e2aecc',
							'operator' => '==',
							'value' => 'three',
						),
					),
					'allorany' => 'all',
				),
				'choices' => array (
					'g' => 'G',
					'h' => 'H',
					'i' => 'I',
				),
				'default_value' => '',
				'layout' => 'vertical',
			),
			array (
				'key' => 'field_556db6efba992',
				'label' => 'Sub Sub Choice',
				'name' => 'sub_sub_choice',
				'type' => 'radio',
				'conditional_logic' => array (
					'status' => 1,
					'rules' => array (
						array (
							'field' => 'field_556cfaae2aecd',
							'operator' => '==',
							'value' => 'a',
						),
					),
					'allorany' => 'all',
				),
				'choices' => array (
					'x' => 'X',
					'y' => 'Y',
				),
				'other_choice' => 0,
				'save_other_choice' => 0,
				'default_value' => 'x',
				'layout' => 'horizontal',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'post',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
}
