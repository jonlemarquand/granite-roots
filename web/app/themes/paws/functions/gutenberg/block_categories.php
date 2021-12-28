<?php
/**
 * Add custom block categories
 */
add_filter( 'block_categories_all', function( $categories, $editor_context ) {

	// If non-post (e.g. widget) Gutenberg instance, return
	if ( empty( $editor_context->post ) ) {
		return $categories;
	}

	return array_merge(
		$categories,
		array(
			array(
				'slug' => 'example',
				'title' => 'Example',
			),
		)
	);
}, 10, 2);