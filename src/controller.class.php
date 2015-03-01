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
			array('umbrella-vulnerabilities', 'Vulnerabilities'),
			// array('umbrella-permissions', 'File &amp; Directories permissions'),
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
	 * Vulnerabilities
	 * Controller for view Vulnerabilities
	 * @return void
	*/
	static public function vulnerabilities() {
		$all_plugins = get_plugins();
		$plugins = array();

		foreach ($all_plugins as $key => $plugin)
		{
			$slug = explode('/', $key);
			$slug = reset($slug);

			if ($slug == 'hello.php')
				continue;

			if ( false === ( $json = get_transient( "umbrella_vulndb_{$slug}" ) ) ) {
				$json = wp_remote_get( "https://wpvulndb.com/api/v1/plugins/{$slug}" );
				set_transient( "umbrella_vulndb_{$slug}", $json, 3600 );
			}

			$merge = array('vulndb' => $json);
		
			$plugins[] = array_merge($plugin,$merge);
		}

		$all_themes = wp_get_themes();
		$themes = array();

		foreach ($all_themes as $slug => $theme)
		{
			if ( false === ( $json = get_transient( "umbrella_vulndb_theme_{$slug}" ) ) ) {
				$json = wp_remote_get( "https://wpvulndb.com/api/v1/themes/{$slug}" );
				set_transient( "umbrella_vulndb_theme_{$slug}", $json, 3600 );
			}

			$merge = array(
				'vulndb' => $json,
				'Name' => $theme->get('Name'),
				'Version' => $theme->get('Version'),
				'Author' => $theme->get('Author'),
			);
			$themes[] = $merge;

		}

		$data['plugins'] = $plugins;
		$data['themes'] = $themes;

		self::make('vulnerabilities', $data);
	}	

	/**
	 * Permissions
	 * Controller for view Permissions
	 * @return void
	*/
	static public function permissions() {

		$filehandler = new FileHandler();
		$filelist = $filehandler->search(ABSPATH, 'is_writable', '1', false);
		
		$data['warning_list'] = $filelist;

		self::make('permissions', $data);
	}

	/**
	 * Make View
	 * This will help render the views.
	 * @return void
	*/
	static public function make( $view = '', $data = array() ) {

		$path_to_file = UMBRELLA__PLUGIN_DIR . 'views/' . $view . '.view.php';

		if (is_array($data)) 
			extract($data);

		if ( file_exists( $path_to_file ) )
			require_once($path_to_file);
		else
			echo "File " . $path_to_file . " does not exists.";

	}

	
}