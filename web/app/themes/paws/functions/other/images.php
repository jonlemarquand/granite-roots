<?php

/**
* Enable featured images in posts
*/
add_theme_support( 'post-thumbnails' );

/**
 * If resizing parameters are set, return resized image
 */
add_action( 'init', function() {

	// check if current url has an image path
	if (preg_match("~images\/~", $_SERVER['REQUEST_URI'])) {

		$image_url = $_SERVER['REQUEST_URI'];

		// remove "/images/"
		$image_url_minus_image_path = str_replace("/images/", "", $image_url);

		// save everything up to the next "/" to a var
		$options_string = substr($image_url_minus_image_path, 0, strpos($image_url_minus_image_path, "/"));

		// explode the ","-separated $options_string into array of strings
		$options_string_array = explode(",", $options_string);

		// explode each "="-separated option string into a key/value pair
		$options_key_value_array = array();
		foreach ($options_string_array as $pair) {
			list($key, $value) = explode("=", $pair);
			// create array of the key/value pairs
			$options_key_value_array[$key] = $value;
		}

		// re-add "/app/uploads/" to the image url (minus "/images/width=3000...etc/")
		$image_url_minus_options = str_replace("/images/".$options_string."/", "", $image_url);
		$image_directory_and_filename = "/app/uploads/" . $image_url_minus_options;
		
		// reconstruct image url
		$original_image_url = 'http://' . $_SERVER['HTTP_HOST'] . $image_directory_and_filename;
		
		// get image ID from its url
		$image_id = attachment_url_to_postid($original_image_url);
		
		// get width, height & crop values
		$width = $options_key_value_array['width'];
		$height = $options_key_value_array['height'];
		$crop = $options_key_value_array['crop'] == '1' ? true : false;
		
		// run function that uses Fly to get or generate resized image
		$fly_image = paws_get_image_src($image_id, array( $width, $height ), $crop);

		// get Fly image url
		$fly_image_url = $fly_image['src'];

		// turn Fly image url into associative array (scheme, host, path, query)
		$fly_image_url_parsed = parse_url($fly_image_url);
		
		// construct the path to the Fly image
		$trimmed_abspath = substr(ABSPATH, 0, -3); // trim 'wp/' subfolder from ABSPATH
		$fly_image_path = $trimmed_abspath . $fly_image_url_parsed['path'];

		$type = mime_content_type($fly_image_path);

		header('Content-Type:'.$type);
		//TODO add other headers such as ETag here

		readfile($fly_image_path);
		//output the file stream
		
		exit;

	} else {
		return;
	}
});

/*
 * Custom filter to remove default WP image sizes.
 * We just want the original and the Thumbnail (for display in media library)
 */
add_action('intermediate_image_sizes_advanced', function( $sizes ) {
	
	unset( $sizes['medium'] );
	unset( $sizes['large'] );
	unset( $sizes['medium_large'] );
	unset( $sizes['1536x1536'] ); // disable 2x medium-large size 
	unset( $sizes['2048x2048'] ); // disable 2x large size
	return $sizes;
});

/*
 * Completely disable image size threshold
 * (to avoid any extra scaled-down image size being generated)
 */
add_filter( 'big_image_size_threshold', '__return_false' );


/**
 * Custom functions for registering and getting cropped images using Fly
 */

// Register image size in Fly or default fallback
function paws_add_image_size( $size_name = '', $width = 0, $height = 0, $crop = false ) {
	if ( function_exists( 'fly_add_image_size' ) ) {
		return fly_add_image_size( $size_name, $width, $height, $crop );
	}
	else {
		return add_image_size( $size_name, $width, $height, $crop );
	}
}

// Get image tag with Fly or default fallback
function paws_get_image( $attachment_id, $size, $crop, $attr ) {
	if ( function_exists( 'fly_get_attachment_image' ) ) {
		return fly_get_attachment_image( $attachment_id, $size, $crop, $attr );
	} else {
		return wp_get_attachment_image( $attachment_id, $size, $crop, $attr );
	}
}

// Get image source with Fly or default fallback
function paws_get_image_src( $attachment_id, $size, $crop, $attr='' ) {
	if ( function_exists( 'fly_get_attachment_image_src' ) ) {
		return fly_get_attachment_image_src( $attachment_id, $size, $crop, $attr );
	} else {
		return wp_get_attachment_image_src( $attachment_id, $size, $crop, $attr );
	}
}

/**
 * REST endpoint for getting cropped images in the block editor
 */
add_action( 'rest_api_init', function () {
	register_rest_route( 'paws/v1', '/get-resized-image-by-id/(?P<id>\d+)', array(
		'methods' => 'GET',
		'callback' => 'paws_get_cropped_image_by_id',
		'permission_callback' => function () {
			return current_user_can( 'edit_posts' );
		}
	) );
} );

function paws_get_cropped_image_by_id( $data ) {
	$posts = get_posts( array(
		'author' => $data['id'],
	) );

	if ( empty( $posts ) ) {
		return null;
	}

	return $posts[0]->post_title;
}


/** 
 * Add path + options to image url 
 */
function add_resizing_settings_to_image_path($url, $width, $height, $crop) {

	$ext = pathinfo($url, PATHINFO_EXTENSION);// get file extension

	if($url == '') {
		// return if there was no image passed
		return;
	}

	if($ext == "svg"){
		// leave url unchanged if it's an svg
		return $url;
	} else {
		$site_domain = get_home_url();
		$url = str_replace('app/uploads/', '', parse_url($url, PHP_URL_PATH));
		// add query string for image resizing to the url
		return $site_domain . '/images/width=' . $width . ',height=' . $height . ',crop=' . $crop . $url;
	}
}

/** 
 * 1. Replace imageUrl attribute with resized image url 
 * 2. Add srcset attribute to img tag for 2x pixel density
 */
function replace_image_url_with_resized_url_and_add_srcset( $content, $attributes, $width, $height, $crop ) {
	
	$image_url = isset($attributes["imageUrl"]) ? $attributes["imageUrl"] : false;
	
	if($image_url){
		// add path + options to image url
		$resized_image_url = add_resizing_settings_to_image_path($image_url, $width, $height, $crop);
		
		// find the src url and replace it with resized url
		$pattern = '~' . $image_url . '~';
		$content = preg_replace($pattern, $resized_image_url, $content);
		
		// add path + options for a 2x scale image url
		$resized_image_url_2x = add_resizing_settings_to_image_path($image_url, $width*2, $height*2, $crop);
		// compose opening img tag with srcset markup
		$img_with_srcset_attribute = '<img srcset="'.$resized_image_url.' 1x, '.$resized_image_url_2x.' 2x"';
		// replace opening img tag with srcset affixed version
		$pattern = '~<img~';
		$content = preg_replace($pattern, $img_with_srcset_attribute, $content);

		return $content;
	} else { // no image used
		return $content;
	}
}

/** 
 * Modify images for blocks
 */
add_action( 'init', function() {

	// Check if we're not in admin or generating a REST response for Gutenberg.
	if( !is_admin() && !( defined( 'REST_REQUEST' ) && REST_REQUEST ) ){

		// Example block
		register_block_type( 'paws/example', array(
			'render_callback' => function( $attributes, $content ) {
				return replace_image_url_with_resized_url_and_add_srcset($content, $attributes, 600, 400, true);
			},
		));
		
		// custom Image block
		register_block_type( 'paws/image', array(
			'render_callback' => function( $attributes, $content ) {
				
				$crop = true;
				
				if($attributes["parentBlockName"] == "paws/accordion-item"){
					$container_width = 1330; // parent block max width
				} else {
					$container_width = 1600; // default content max width
				}

				return replace_image_url_with_resized_url_and_add_srcset($content, $attributes, $container_width, $container_width, $crop);
			},
		));

	}
});