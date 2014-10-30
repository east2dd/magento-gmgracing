<?php
/*
Plugin Name: GMG Custom Functions
*/

add_filter('template', 'change_theme');
add_filter('option_template', 'change_theme');
add_filter('option_stylesheet', 'change_theme');

function change_theme() 
{
  $version = $_GET['v'];
  if($version == 1)
  {
    return 'gmg_new';
  }
}
