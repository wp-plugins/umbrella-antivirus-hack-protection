<?php
namespace Umbrella;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
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
			array('umbrella', __('Modules', UMBRELLA__TEXTDOMAIN)),
			array('umbrella-vulnerabilities', __('Vulnerabilities', UMBRELLA__TEXTDOMAIN)),
			array('umbrella-scanner', __('File Scanner', UMBRELLA__TEXTDOMAIN) . ' (BETA)'),
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

		$data['available_options'] = Modules::valid_modules();
		self::make('dashboard', $data);
	}		

	/**
	 * Scanner
	 * Controller for view Scanner
	 * @return void
	*/
	static public function scanner() {

		wp_enqueue_script('umbrella-filescanner', UMBRELLA__PLUGIN_URL . 'js/filescanner.js');

		$scanner = new Scanner;
		$locale = get_locale();
		$data_file = UMBRELLA__PLUGIN_DIR . "data/wordpress-{$scanner->wp_version()}.db";

		// Show error page if no data file found.
		if (!file_exists($data_file))
			return self::make('scanner_no_md5_list');
		
		$data = array();

		self::make('scanner', $data);

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
			unset($merge);
			unset($json);

			$slug = explode('/', $key);
			$slug = reset($slug);

			if ($slug == 'hello.php')
				continue;

			if ( false === ( $json = get_transient( "umbrella_vulndb_{$slug}" ) ) ) {
				$json = wp_remote_get( "https://wpvulndb.com/api/v1/plugins/{$slug}" );
				
				if (!\is_wp_error($json))
					set_transient( "umbrella_vulndb_{$slug}", $json, 300 );
			}

			if (!\is_wp_error($json))
				$merge = array('vulndb' => $json);
			else 
				$merge['vulndb']['error']['code'] = '501';
			
		
			$plugins[] = array_merge($plugin,$merge);
		}

		$all_themes = wp_get_themes();
		$themes = array();

		foreach ($all_themes as $slug => $theme)
		{
			unset($merge);
			unset($json);
			
			if ( false === ( $json = get_transient( "umbrella_vulndb_theme_{$slug}" ) ) ) {
				$json = wp_remote_get( "https://wpvulndb.com/api/v1/themes/{$slug}" );
				
				if (!\is_wp_error($json))
					set_transient( "umbrella_vulndb_theme_{$slug}", $json, 300 );
			}

			$merge = array(
				'Name' => $theme->get('Name'),
				'Version' => $theme->get('Version'),
				'Author' => $theme->get('Author'),
			);

			if (!\is_wp_error($json))
				$merge['vulndb'] = $json;
			else {
				$merge['vulndb']['error']['code'] = '0';
			}

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