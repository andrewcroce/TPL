<?php
/**
 * Register the ACF Field group for assigning an index_post_type to pages using the index template.
 * This template applies to all pages using the "Index" template
 * @see tpl_templates/tpl_index.php
 */

if( function_exists("register_field_group")) {

	// Empty Post Types array we will give to ACF register_field_group()
	$post_type_choices = array();

	// Empty array of taxonomy sets per post type, we will give to ACF register_field_group() for conditional fields
	$taxonomy_sets = array();

	// Get all public post types
	$post_types = get_post_types(array(
		'public' => 1
	), 'objects');

	// Post types include media attachments, we probably don't want those... or maybe you do
	unset( $post_types['attachment'] );
			
	// Loop through the post types...
	foreach( $post_types as $key => $post_type ){

		// Set the key->value pairs for post_type_choices array
		$post_type_choices[$key] = $post_type->label;

		// Get all the taxonomies registered to this post type
		$taxonomies = get_taxonomies(array(
			'public'   => true,
			'object_type' => array( $key )
		),'objects');

		// If we have taxonomies on this post type...
		if( !empty( $taxonomies ) ) {

			// By default, WP includes 'post_format' as a taxonomy, even if its not enabled in the theme
			// We'll just unset it here since we probably don't want to sort by it... or maybe you do
			unset( $taxonomies['post_format'] );
			
			// Add this post type's set of taxonomies to the list
			$taxonomy_sets[$key] = $taxonomies;
		}
	}


	// Build our fields array
	$fields = array (

		// Our primary choice, select post type?
		array (
			'key' => 'field_INDEX_POST_TYPE',
			'label' => 'Post Type',
			'name' => 'index_post_type',
			'type' => 'select',
			'required' => 1,
			'instructions' => 'This page will be an index for a post type, please select the post type.',
			'choices' => $post_type_choices,
			'default_value' => '',
			'allow_null' => 1,
			'multiple' => 0,
		)
	);

	// Loop through our taxonomy sets to create the corresponding
	// checkboxes for taxonomies attached to each post type.
	// This will enable filtering by those taxonomies on the front end.
	foreach( $taxonomy_sets as $post_type => $taxonomies ) {
		
		// Empty array for our taxonomy choices
		$choices = array();

		// Loop through each taxonomy and add it to our choices
		foreach( $taxonomies as $taxonomy ) {					
			$choices[$taxonomy->name] = $taxonomy->label;
		}

		// Build our conditional select field
		$fields[] = array(
			'key' => 'field_INDEX_TAXONOMY_FOR_' . $post_type,
			'label' => 'Allow filtering by:',
			'name' => 'index_taxonomies_for_' . $post_type,
			'type' => 'checkbox',
			'conditional_logic' => array(
				'status' => 1,
				'rules' => array(
					array(
						'field' => 'field_INDEX_POST_TYPE',
						'operator' => '==',
						'value' => $post_type,
					)
				),
				'allorany' => 'all'
			),
			'choices' => $choices,
			'default_value' => '',
			'allow_null' => 1,
			'layout' => 'vertical',
		);


		// Each taxonomy get a 'filter style' choice
		// Single-select, or multi-select
		foreach( $choices as $taxonomy_name => $taxonomy_label ){
			
			$fields[] = array(
				'key' => 'field_FILTER_STYLE_FOR_' . $post_type . '_' . $taxonomy_name,
				'label' => 'Filter style for ' . $taxonomy_label,
				'name' => 'filter_style_for_' . $post_type . '_' . $taxonomy_name,
				'type' => 'radio',
				'conditional_logic' => array(
					'status' => 1,
					'rules' => array(
						array(
							'field' => 'field_INDEX_TAXONOMY_FOR_' . $post_type,
							'operator' => '==',
							'value' => $taxonomy_name
						)
					),
					'allorany' => 'all'
				),
				'choices' => array(
					'single' => 'Single Select',
					'multi' => 'Multi Select'
				),
				'other_choice' => 0,
				'save_other_choice' => 0,
				'default_value' => 'single',
				'layout' => 'horizontal'
			);
		}
	}


	// Finally, we register the field group with ACF
	register_field_group(array (
		'id' => 'acf_index-template-fields',
		'title' => 'Index Template Fields',
		'fields' => $fields,
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