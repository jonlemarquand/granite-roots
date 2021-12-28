<?php

/**
 * Remove unnecessary tags from head
 */
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'index_rel_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'feed_links_extra', 3 );
remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 );
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );
remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

/**
 * Remove emoji CDN hostname from DNS prefetching hints
 */
add_filter( 'wp_resource_hints', function ( $urls, $relation_type ) {

	if ( 'dns-prefetch' == $relation_type ) {
		$emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );
		$urls = array_diff( $urls, array( $emoji_svg_url ) );
	}

	return $urls;
}, 10, 2 );

/**
* Disable RSS feeds
*/
function fm_disable_feeds() {
	wp_redirect( home_url(), 302 );
	exit();
}
add_action('do_feed', 'fm_disable_feeds', 1);
add_action('do_feed_rdf', 'fm_disable_feeds', 1);
add_action('do_feed_rss', 'fm_disable_feeds', 1);
add_action('do_feed_rss2', 'fm_disable_feeds', 1);
add_action('do_feed_atom', 'fm_disable_feeds', 1);
add_action('do_feed_rss2_comments', 'fm_disable_feeds', 1);
add_action('do_feed_atom_comments', 'fm_disable_feeds', 1);
remove_action( 'wp_head', 'feed_links_extra', 3 );
remove_action( 'wp_head', 'feed_links', 2 );

/**
 * Remove default favicon
 * https://gist.github.com/webdados/a7702e588070f9a1cfa12dff89b3573c
 */
add_action( 'do_faviconico', function() {

	header( 'Content-Type: image/vnd.microsoft.icon' );
	exit;
} );

/**
 * Make the title tag populate automatically from Yoast
 */
add_theme_support( 'title-tag' );

/**
 * Add logged in body class
 */
add_filter( 'body_class', 'add_logged_wp_logged_in_class' );
function add_logged_wp_logged_in_class( $classes ) {
    if ( is_user_logged_in() ) {
        $classes[] = 'logged-in';
    }
    return $classes;
}

/**
 * Remove admin bar positioning CSS
 */
add_action('admin_bar_init', function() {
	remove_action('wp_head', '_admin_bar_bump_cb');
});

/**
 * Disable attachment pages for media by redirecting to the post associated with that image
 */
add_action( 'template_redirect', function() {

	if ( is_attachment() ) {
		global $post;
		if ( $post && $post->post_parent ) {
			wp_redirect( esc_url( get_permalink( $post->post_parent ) ), 301 );
			exit;
		} else {
			wp_redirect( esc_url( home_url( '/' ) ), 301 );
			exit;
		}
	}
});