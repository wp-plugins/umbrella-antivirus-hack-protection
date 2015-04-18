<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Remove WP Generator from head.
add_action( 'init', function() {
	remove_action('wp_head', 'wp_generator');
});

// Remove WordPress version number from both your head file and RSS feeds.
add_filter('the_generator', function() {
	return '';
});

// Hide versions from plugin stylesheets & javascripts
add_filter( 'style_loader_src', 'remove_wp_ver_par', 9999 );
add_filter( 'script_loader_src', 'remove_wp_ver_par', 9999 );

function remove_wp_ver_par( $src ) {
    if ( strpos( $src, 'ver=' . get_bloginfo( 'version' ) ) )
        $src = remove_query_arg( 'ver', $src );
    return $src;
}