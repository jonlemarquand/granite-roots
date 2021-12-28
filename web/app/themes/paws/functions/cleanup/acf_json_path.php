<?php

/**
 * set save point
 */
add_filter('acf/settings/save_json', function($path) {

	// Update path
	$path = get_theme_file_path('/functions/acf-json');

	return $path;

});

/**
 * set load point
 */
add_filter('acf/settings/load_json', function($paths) {
   
	// Remove original path
	unset( $paths[0] );

	// Append our new path
	$paths[] = get_theme_file_path('/functions/acf-json');
   
	return $paths;
});