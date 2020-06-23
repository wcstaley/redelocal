<?php

// add the publix settings item to the Settings menu
if( function_exists('acf_add_options_page') ) {
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Publix Form Settings',
		'menu_title'	=> 'Publix Form Settings',
		'parent_slug'	=> 'options-general.php',
	));
}

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_5eb197bc77d30',
	'title' => 'Publix Co-op Calendar',
	'fields' => array(
		array(
			'key' => 'field_5eb1982bdd22d',
			'label' => 'Entry',
			'name' => 'coop_cal_entry',
			'type' => 'repeater',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'collapsed' => '',
			'min' => 0,
			'max' => 0,
			'layout' => 'block',
			'button_label' => 'Add Entry',
			'sub_fields' => array(
				array(
					'key' => 'field_coopcaluniqid',
					'label' => 'Title (must be unique)',
					'name' => 'unique_id',
					'type' => 'text',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '25',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_5eb19849dd22e',
					'label' => 'Start Date',
					'name' => 'start_date',
					'type' => 'date_picker',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '25',
						'class' => '',
						'id' => '',
					),
					'display_format' => 'm/d/Y',
					'return_format' => 'm/d/Y',
					'first_day' => 1,
				),
				array(
					'key' => 'field_5eb19877dd22f',
					'label' => 'End Date',
					'name' => 'end_date',
					'type' => 'date_picker',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '25',
						'class' => '',
						'id' => '',
					),
					'display_format' => 'm/d/Y',
					'return_format' => 'm/d/Y',
					'first_day' => 1,
				),
				array(
					'key' => 'field_5eb19888dd230',
					'label' => 'Popular Holidays',
					'name' => 'popular_holidays',
					'type' => 'text',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '25',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_5eb19926dd232',
					'label' => 'Submission Start',
					'name' => 'submission_start',
					'type' => 'date_picker',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '20',
						'class' => '',
						'id' => '',
					),
					'display_format' => 'm/d/Y',
					'return_format' => 'm/d/Y',
					'first_day' => 1,
				),
				array(
					'key' => 'field_5eb19938dd233',
					'label' => 'Submission End',
					'name' => 'submission_end',
					'type' => 'date_picker',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '20',
						'class' => '',
						'id' => '',
					),
					'display_format' => 'm/d/Y',
					'return_format' => 'm/d/Y',
					'first_day' => 1,
				),
				array(
					'key' => 'field_5eb19944dd234',
					'label' => 'Approval Notification Date',
					'name' => 'approval_notification_date',
					'type' => 'date_picker',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '20',
						'class' => '',
						'id' => '',
					),
					'display_format' => 'm/d/Y',
					'return_format' => 'm/d/Y',
					'first_day' => 1,
				),
				array(
					'key' => 'field_5eb198a6dd231',
					'label' => 'Theme Pages',
					'name' => 'theme_pages',
					'type' => 'checkbox',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '40',
						'class' => '',
						'id' => '',
					),
					'choices' => array(
						'stockup' => 'Stock Up',
						'newitem' => 'New Item',
						'gameday' => 'Game Day',
						'plantbased' => 'Plant Based',
						'organic' => 'Organic',
						'coolfoods' => 'Cool Foods',
						'breakfast' => 'Breakfast',
						'icecream' => 'Ice Cream',
						'hydration' => 'Hydration',
						'backtoschool' => 'Back to School',
						'storm' => 'Hurricane/Storm Basics',
						'everyday' => 'Everyday Delicious',
					),
					'allow_custom' => 0,
					'default_value' => array(
					),
					'layout' => 'horizontal',
					'toggle' => 0,
					'return_format' => 'value',
					'save_custom' => 0,
				),
			),
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'acf-options-publix-form-settings',
			),
		),
	),
	'menu_order' => 10,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
));

endif;


if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_5eea40e856c3d',
	'title' => 'Theme Page Options',
	'fields' => array(
		array(
			'key' => 'field_5eea40fe37a07',
			'label' => 'Theme Pages',
			'name' => 'theme_pages',
			'type' => 'repeater',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'collapsed' => '',
			'min' => 0,
			'max' => 0,
			'layout' => 'table',
			'button_label' => '',
			'sub_fields' => array(
				array(
					'key' => 'field_5eea410e37a08',
					'label' => 'Key',
					'name' => 'key',
					'type' => 'text',
					'instructions' => 'all lower case, no spaces',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_5eea412937a09',
					'label' => 'Value',
					'name' => 'value',
					'type' => 'text',
					'instructions' => 'Readable Name of theme',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_5eea414137a0a',
					'label' => 'Image',
					'name' => 'image',
					'type' => 'image',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'return_format' => 'id',
					'preview_size' => 'medium',
					'library' => 'all',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => '',
				),
				array(
					'key' => 'field_5eea415c37a0b',
					'label' => 'Description',
					'name' => 'description',
					'type' => 'textarea',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'maxlength' => '',
					'rows' => 3,
					'new_lines' => '',
				),
			),
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'acf-options-publix-form-settings',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
));

endif;

function update_theme_options_field( $field ) {
	$themeOptions = get_field("theme_pages", 'options');
	$choices = array();
	foreach($themeOptions as $themeOption){
		$choices[$themeOption['key']] = $themeOption['value'];
	}
    $field['choices'] = $choices;
    return $field;
}


// Apply to field with key "field_123abcf".
add_filter('acf/load_field/key=field_5eb198a6dd231', 'update_theme_options_field');
// 
// 
// 