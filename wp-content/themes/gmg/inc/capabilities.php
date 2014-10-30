<?php
/**
 * Activate Add-ons
 * Here you can enter your activation codes to unlock Add-ons to use in your theme. 
 * Since all activation codes are multi-site licenses, you are allowed to include your key in premium themes. 
 * Use the commented out code to update the database with your activation code. 
 * You may place this code inside an IF statement that only runs on theme activation.
 */ 
 
// if(!get_option('acf_repeater_ac')) update_option('acf_repeater_ac', "xxxx-xxxx-xxxx-xxxx");
// if(!get_option('acf_options_page_ac')) update_option('acf_options_page_ac', "xxxx-xxxx-xxxx-xxxx");
// if(!get_option('acf_flexible_content_ac')) update_option('acf_flexible_content_ac', "xxxx-xxxx-xxxx-xxxx");
// if(!get_option('acf_gallery_ac')) update_option('acf_gallery_ac', "xxxx-xxxx-xxxx-xxxx");


/**
 * Register field groups
 * The register_field_group function accepts 1 array which holds the relevant data to register a field group
 * You may edit the array as you see fit. However, this may result in errors if the array is not compatible with ACF
 * This code must run every time the functions.php file is read
 */

if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => '52cedb9436035',
		'title' => 'GMG Capability',
		'fields' => 
		array (
			0 => 
			array (
				'key' => 'field_52c2221607358',
				'label' => 'Slider',
				'name' => 'slider',
				'type' => 'post_object',
				'instructions' => '',
				'required' => '0',
				'post_type' => 
				array (
					0 => 'slider',
				),
				'taxonomy' => 
				array (
					0 => 'all',
				),
				'allow_null' => '1',
				'multiple' => '0',
				'order_no' => 0,
			),
			1 => 
			array (
				'key' => 'field_528b90a05d6ee',
				'label' => 'Index Image',
				'name' => 'index_image',
				'type' => 'image',
				'instructions' => '',
				'required' => '0',
				'save_format' => 'object',
				'preview_size' => 'medium',
				'order_no' => 1,
			),
			2 => 
			array (
				'key' => 'field_528b90a05e7f9',
				'label' => 'Index Text',
				'name' => 'index_text',
				'type' => 'textarea',
				'instructions' => '',
				'required' => '0',
				'default_value' => '',
				'formatting' => 'br',
				'order_no' => 2,
			),
			3 => 
			array (
				'key' => 'field_528b61fb04a3a',
				'label' => 'Professional Services',
				'name' => 'professional_services',
				'type' => 'repeater',
				'instructions' => '',
				'required' => '0',
				'sub_fields' => 
				array (
					0 => 
					array (
						'key' => 'field_528b61fb04a57',
						'label' => 'Service Name',
						'name' => 'service_name',
						'type' => 'text',
						'instructions' => '',
						'column_width' => '',
						'default_value' => '',
						'formatting' => 'none',
						'order_no' => 0,
					),
					1 => 
					array (
						'key' => 'field_528b61fb04a69',
						'label' => 'Service Description',
						'name' => 'service_description',
						'type' => 'wysiwyg',
						'instructions' => '',
						'column_width' => '',
						'default_value' => '',
						'toolbar' => 'full',
						'media_upload' => 'yes',
						'the_content' => 'yes',
						'order_no' => 1,
					),
				),
				'row_min' => '0',
				'row_limit' => '',
				'layout' => 'table',
				'button_label' => 'Add Row',
				'order_no' => 3,
			),
			4 => 
			array (
				'key' => 'field_528b61fb0507c',
				'label' => 'Equipment',
				'name' => 'equipment',
				'type' => 'wysiwyg',
				'instructions' => '',
				'required' => '0',
				'default_value' => '',
				'toolbar' => 'full',
				'media_upload' => 'yes',
				'the_content' => 'yes',
				'order_no' => 4,
			),
		),
		'location' => 
		array (
			'rules' => 
			array (
				0 => 
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'capability',
					'order_no' => 0,
				),
			),
			'allorany' => 'all',
		),
		'options' => 
		array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => 
			array (
			),
		),
		'menu_order' => 0,
	));
}
