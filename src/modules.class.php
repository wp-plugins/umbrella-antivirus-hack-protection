<?php
namespace Umbrella;
class Modules
{

	static $valid_modules = array(
		array('hide_version', 'Hide Versions', 'Hide version numbers in your front-end source code for WordPress-core and all of your plugins. This will affect meta-tags, stylesheet and javascripts urls.'),
		array('disable_ping', 'Disable Pings', 'Completely turn off trackbacks &amp; pingbacks to your site.'),
		array('filter_requests', 'Filter Requests', 'block all unauthorized and irrelevant requests through query strings.'),
	);

	static public function validate_modules( $options ) {

		$valid_modules = Modules::$valid_modules;
	    
	    if( !is_array( $options ) || empty( $options ) || ( false === $options ) )
	        return array();

	    $valid_names = $valid_modules;
	    $clean_options = array();

	    foreach( $valid_names as $option_name ) {
	        if( isset( $options[$option_name[0]] ) && ( 1 == $options[$option_name[0]] ) )
	            $clean_options[$option_name[0]] = 1;
	        continue;
	    }
	    unset( $options );
	    return $clean_options;
	}
}