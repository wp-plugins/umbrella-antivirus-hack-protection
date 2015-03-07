<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


	/*
	 * We don't want to hook this cause we want it to load as soon as posible 
	 * before all other plugins can make something bad.
	*/
	function is_forbidden( $value = '' ) {

		$forbidden_strings = array(
			'wp-config.php',
			'.htaccess',
			'../'
		);

		foreach($forbidden_strings as $forbidden) {
			
			// Return true if $value is found in $forbidden
			if (is_array($value)) {
				foreach($value as $val) {
					if ( strpos($val, $forbidden) !== false ) 
						return true;
				}
			} else {
				if ( strpos($value, $forbidden) !== false ) 
					return true;
			}
		}

		// Return false if no forbidden strings is found.
		return false;

	}

	/*
	* Get trough all GET and POST requests to see if we find something funny.
	* If something is found the $key will be unset and logged.
	*/
	foreach ($_REQUEST as $key => $value)
	{
		if (is_forbidden($value))
		{
			http_response_code(401);
			die("Umbrella: 401 Forbidden Request.");
		}
	}

	// Clean up used variables.
	unset($forbidden);
?>