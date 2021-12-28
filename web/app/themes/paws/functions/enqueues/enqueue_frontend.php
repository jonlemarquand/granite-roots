<?php

/**
 * Styles (frontend)
 */
add_action('wp_enqueue_scripts', function() {

    if( file_exists(get_theme_file_path('/build/gulp-output/frontend.build.css')) ) {
	    wp_enqueue_style('paws-frontend-styles', get_theme_file_uri('/build/gulp-output/frontend.build.css'), array(), filemtime(get_theme_file_path('/build/gulp-output/frontend.build.css')));
    }

});

/**
 * Scripts (frontend)
 */
add_action( 'wp_enqueue_scripts', function() {

    if ( !is_admin() ) {
		wp_deregister_script('jquery');
		wp_register_script('jquery', get_template_directory_uri().'/build/jquery.min.js', [], '3.5.1', false);
		wp_enqueue_script('jquery');
    }

    if( file_exists ( get_theme_file_path('/build/gulp-output/frontend.build.js') ) ) {
        wp_enqueue_script('paws-script', get_theme_file_uri('/build/gulp-output/frontend.build.js'), array('jquery'), filemtime(get_theme_file_path('/build/gulp-output/frontend.build.js')));
        wp_localize_script( 'paws-script', 'example_call', array(
          'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php' // WordPress AJAX
        ) );
    }

});