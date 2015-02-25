<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Disable X-Pingback HTTP Header.
add_filter('wp_headers', function($headers, $wp_query){
    if(isset($headers['X-Pingback'])){
        // Drop X-Pingback
        unset($headers['X-Pingback']);
    }
    return $headers;
}, 11, 2);

// Disable XMLRPC by hijacking and blocking the option.
add_filter('pre_option_enable_xmlrpc', function($state){
    return '0'; // return $state; // To leave XMLRPC intact and drop just Pingback
});

// Remove rsd_link from filters (<link rel="EditURI" />).
add_action('wp', function(){
    remove_action('wp_head', 'rsd_link');
}, 9);

// Hijack pingback_url for get_bloginfo (<link rel="pingback" />).
add_filter('bloginfo_url', function($output, $property){
    return ($property == 'pingback_url') ? null : $output;
}, 11, 2);

// Just disable pingback.ping functionality while leaving XMLRPC intact?
add_action('xmlrpc_call', function($method){
    if($method != 'pingback.ping') return;
    wp_die(
        'Pingback functionality is disabled on this Blog.',
        'Pingback Disabled!',
        array('response' => 403)
    );
});