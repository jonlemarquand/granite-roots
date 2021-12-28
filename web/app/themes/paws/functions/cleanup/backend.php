<?php

/**
 * Show Posts instead of Dashboard after login
 */
add_filter('login_redirect', function($url) {

	return admin_url( 'edit.php' );
});

/**
* Remove all social profile fields that Yoast adds to user profile Contact Info
*/
add_filter( 'user_contactmethods', function ( $contact_methods ) {

    $contact_methods = array();
    return $contact_methods;
});

/**
* Hide 'biographical info' field in user profile About Yourself
*/
add_action('admin_head', function() {
    echo '<style>
    
    .user-description-wrap {
        display: none;
    }
    
    </style>';
});

/**
 * Remove Drop Cap option from Paragraph block
 */
add_filter( 'block_editor_settings_all', function( $editor_settings, $editor_context ) {

	if ( ! empty( $editor_context->post ) ) {
		$editor_settings['__experimentalFeatures']['typography']['dropCap'] = false;
	}

    return $editor_settings;
}, 10, 2 );

/**
 * Remove WP logo, search and "Howdy" from admin bar
 */
add_filter( 'admin_bar_menu', function( $wp_admin_bar ) {

	$my_account = $wp_admin_bar->get_node('my-account');
	$newtext = str_replace( 'Howdy,', '', $my_account->title );
	$wp_admin_bar->add_node( array(
		'id' => 'my-account',
		'title' => $newtext,
	));

	$wp_admin_bar->remove_node( 'wp-logo' );
	$wp_admin_bar->remove_node( 'search' );
}, 25 );

/**
 * Remove redundant 'Visit site' link under site name in admin bar
 */
add_action( 'wp_before_admin_bar_render', function() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('view-site');
});

/**
 * Remove 'Customize' button in admin top bar menu
 */
add_action( 'admin_bar_menu', function( $wp_admin_bar ) {
	
    $wp_admin_bar->remove_menu( 'customize' );
    $wp_admin_bar->remove_menu( 'themes' );
    $wp_admin_bar->remove_menu( 'dashboard' );
}, 999 );

/**
 * Remove Admin Menu Link to Theme Customizer
 */
add_action( 'admin_menu', function () {

	remove_menu_page('themes.php');

	if( !in_array( 'administrator', wp_get_current_user()->roles ) ) {
		remove_menu_page('tools.php');
	}
});	

/**
 * ???
 */
add_action( 'rest_api_init', function() {

	remove_filter( 'rest_pre_serve_request', 'rest_send_cors_headers' );

	add_filter( 'rest_pre_serve_request', function( $value ) {
		header( 'Access-Control-Allow-Origin: *' );
		header( 'Access-Control-Allow-Methods: GET' );
		header( 'Access-Control-Allow-Credentials: true' );
		header( 'Access-Control-Expose-Headers: Link', false );

		return $value;
	});

}, 15 );

/**
 * This code helps with ACF fields returning null in the post preview
 * https://gist.github.com/ChrisLTD/892eccf385752dadaf5f
 * https://support.advancedcustomfields.com/forums/topic/preview-solution/page/3/
 */
add_filter( 'wp_insert_post_data', function ( $data ) {
	if ( isset( $_GET['meta-box-loader'] ) ) {
		unset( $data["post_modified"] );
		unset( $data["post_modified_gmt"] );
	}

	return $data;
} );
