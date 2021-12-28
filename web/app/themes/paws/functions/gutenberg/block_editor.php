<?php

/**
 * Remove all core block patterns
 */
remove_theme_support( 'core-block-patterns' );

/*
* Disable free font size input
*/
add_theme_support('disable-custom-font-sizes');

/*
* Declare font size presets
*/
add_theme_support( 'editor-font-sizes', array(

	array(
		'name' => __( 'Large', 'paws' ),
		'size' => 20,
		'slug' => 'large'
	),
	array(
		'name' => __( 'Small', 'paws' ),
		'size' => 16,
		'slug' => 'small'
	),

) );

/*
* Declare editor color palette
*/
add_action( 'after_setup_theme', function() {
	// Disable Custom Colors
	add_theme_support( 'disable-custom-colors' );

	// Editor Color Palette
	add_theme_support( 'editor-color-palette', array(
		array(
			'name'  => __( 'White'),
			'slug'  => 'lightest',
			'color'	=> '#FFFFFF',
		),
		array(
			'name'  => __( 'Blue'),
			'slug'  => 'blue',
			'color'	=> '#59BACC',
		),
		array(
			'name'  => __( 'Green'),
			'slug'  => 'green',
			'color' => '#58AD69',
		),
		array(
			'name'  => __( 'Orange'),
			'slug'  => 'orange',
			'color' => '#FFBC49',
		),
		array(
			'name'	=> __( 'Red'),
			'slug'	=> 'red',
			'color'	=> '#E2574C',
		),
		array(
			'name'	=> __( 'Black'),
			'slug'	=> 'darkest',
			'color'	=> '#000000',
		),
	) );
});