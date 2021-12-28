<?php

/**
* Function that include the file of the current block
*/
function block_gutenberg_render( $block ) {

    $slug = str_replace('acf/', '', $block['name']);

    if( file_exists( get_theme_file_path('blocks-acf/'.$slug.'/'.$slug.'.php') ) ) {
		include( get_theme_file_path('blocks-acf/'.$slug.'/'.$slug.'.php') );
	}
}

/**
* Register the blocks
*/
function paws_acf_init() {

	if( function_exists('acf_register_block') ) {

		acf_register_block(array(
	    'name'				=> 'example',
	    'title'				=> __('Example'),
	    'description'		=> __('Example'),
	    'render_callback'	=> 'block_gutenberg_render',
	    'icon'				=> 'format-aside',
		'supports'			=> array(
			'mode' => false,
			'align' => false,
		)
    ));

	}
}
add_action('acf/init', 'paws_acf_init');
