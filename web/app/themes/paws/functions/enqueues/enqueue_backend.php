<?php

/**
 * Enqueue block editor only JavaScript and CSS.
 */
function enqueue_block_editor_assets() {

	$block_path 			= '/build/webpack-output/gutenberg-editor.build.js';
	$editor_style_path 		= '/build/gulp-output/editor.build.css';

	// Enqueue the bundled block JS file
	wp_enqueue_script(
		'paws-blocks-js',
		get_template_directory_uri() . $block_path,
		[ 'wp-i18n', 'wp-element', 'wp-blocks', 'wp-components', 'wp-editor' ],
		filemtime( get_template_directory() . $block_path )
	);

    wp_localize_script(
		'paws-blocks-js',
		'pawsVars',
		[
			'templateDirectoryUri' => get_template_directory_uri(),
			'siteDomain' => get_home_url(),
		]
	);

	wp_enqueue_style(
		'paws-editor-styles',
        get_template_directory_uri() . $editor_style_path,
        array(),
        filemtime( get_template_directory() . $editor_style_path )
	);
}
add_action( 'enqueue_block_editor_assets', 'enqueue_block_editor_assets' );
