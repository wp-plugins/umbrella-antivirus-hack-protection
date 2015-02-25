<?php
namespace Umbrella;

	/*
	 * We don't want to hook this cause we want it to load as soon as posible 
	 * before all other plugins can make something bad.
	*/
	function is_forbidden( $value = '' ) {

		$forbidden_strings = array(
			'wp-config.php',
			'.htaccess',
			'.php',
			'../'
		);

		foreach($forbidden_strings as $forbidden)
		{
			// Return true if $value is found in $forbidden
			if ( strpos($value, $forbidden) !== false ) 
				return true;
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

		}
	}

	// Clean up used variables.
	unset($forbidden);
?>