<?php
namespace Umbrella;
class Controller
{
	/**
	 * Header
	 * Controller for header
	 * @return void
	*/
	static public function header() {

		$data = array(
		'navbars' => array(
			array('umbrella', 'Modules'),
			array('umbrella-permissions', 'File &amp; Directories permissions'),
		));

		self::make('header', $data);
	}		

	/**
	 * Footer
	 * Controller for footer
	 * @return void
	*/
	static public function footer() {

		$data = array();
		self::make('footer', $data);
	}	

	/**
	 * Modules
	 * Controller for view Modules
	 * @return void
	*/
	static public function modules() {

		$data['available_options'] = Modules::$valid_modules;
		self::make('dashboard', $data);
	}	

	/**
	 * Permissions
	 * Controller for view Permissions
	 * @return void
	*/
	static public function permissions() {

		$data = array();
		self::make('permissions', $data);
	}

	/**
	 * Make View
	 * This will help render the views.
	 * @return void
	*/
	static public function make( $view = '', $data = array() ) {

		$path_to_file = UMBRELLA__PLUGIN_DIR . 'views/' . $view . '.view.php';

		extract($data);

		if ( file_exists( $path_to_file ) )
			require_once($path_to_file);
		else
			echo "File " . $path_to_file . " does not exists.";

	}

	
}