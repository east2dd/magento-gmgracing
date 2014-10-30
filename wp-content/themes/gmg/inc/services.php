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
        'id' => '52d502056e407',
        'title' => 'GMG Service',
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
        ),
        'location' => 
        array (
            'rules' => 
            array (
                0 => 
                array (
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'service',
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
