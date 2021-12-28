<?php
/*
* Conditionally allow specific Gutenberg blocks by post type
*/
add_filter( 'allowed_block_types_all', function( $allowed_blocks, $editor_context ) {

	if ( empty( $editor_context->post ) ) {
		return $allowed_blocks;
	}

	if(
		$editor_context->post->post_type == 'post'
		||
		$editor_context->post->post_type == 'page'
	) {
		return array(

			// Core
			'core/paragraph',
			'core/heading',
			'core/list',
			'core/block',
			'core/quote',

			// Custom
			"paws/image",
			"paws/buttons",
			"paws/button",
			"paws/tabs",
			"paws/tab",

			// ACF
			// "acf/example",
		);
	}
	else {
		return $allowed_blocks;
		// Allow defaults in all other post types
	}
}, 10, 2 );

