<?php

if( function_exists('acf_add_options_page') ) {

	acf_add_options_page(array(
		'page_title' 	=> 'Preferences',
		'menu_title'	=> 'Preferences',
		'menu_slug' 	=> 'theme-general-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> true,
		'position'      => '20.8',
	));
}

if( function_exists('acf_add_options_sub_page') ) {

    $optionsFooter = acf_add_options_sub_page(array(
		'page_title'  => __('404'),
		'menu_title'  => __('404'),
		'parent_slug' => 'theme-general-settings',
		'capability'  => 'edit_posts',
		'redirect'	  => false
	));
}