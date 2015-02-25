<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Remove WP Generator from head.
function rkwpa_hide_version() {
	remove_action('wp_head', 'wp_generator');
}

// Remove WordPress version number from both your head file and RSS feeds.
add_filter('the_generator', 'wpbeginner_remove_version');
function wpbeginner_remove_version() {
	return '';
}

// Hide versions from plugin stylesheets & javascripts
function remove_wp_ver_par( $src ) {
    if ( strpos( $src, 'ver=' . get_bloginfo( 'version' ) ) )
        $src = remove_query_arg( 'ver', $src );
    return $src;
}

add_action( 'init', 'rkwpa_hide_version' );
add_filter( 'style_loader_src', 'remove_wp_ver_par', 9999 );
add_filter( 'script_loader_src', 'remove_wp_ver_par', 9999 );