<?php
namespace Umbrella;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Modules
{

	static public function valid_modules() { 

		return array(
			array('hide_version', 'Hide Versions', __('Hide version numbers in your front-end source code for WordPress-core and all of your plugins. This will affect meta-tags, stylesheet and javascripts urls.', UMBRELLA__TEXTDOMAIN ) ),
			array('disable_ping', 'Disable Pings', __('Completely turn off trackbacks &amp; pingbacks to your site.', UMBRELLA__TEXTDOMAIN ) ),
			array('filter_requests', 'Filter Requests', __('Block all unauthorized and irrelevant requests through query strings.', UMBRELLA__TEXTDOMAIN ) ),
			array('captcha_login', 'Captcha Login', __('Add CAPTCHA to login screen for a more secure login. <span style="color:red">This module requires the <a target="_blank" href="plugin-install.php?tab=search&s=Really%20Simple%20CAPTCHA">Really Simple CAPTCHA</a> plugin.</span>', UMBRELLA__TEXTDOMAIN ) ),
		);
	}

	static public function validate_modules( $options ) {

		$valid_modules = Modules::valid_modules();
	    
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