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
			array('umbrella', __('Dashboard')),
			array('umbrella-vulnerabilities', __('Vulnerabilities', UMBRELLA__TEXTDOMAIN)),
			array('umbrella-scanner', __('File Scanner', UMBRELLA__TEXTDOMAIN) . ' (BETA)'),
			array('umbrella-logging', __('Logs', UMBRELLA__TEXTDOMAIN)),
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
	 * Logging
	 * Controller for Logging
	 * @return void
	*/
	static public function logging() {

		$data = array();

		$data['logs'] = Log::read();
		self::make('logging', $data);
	}	

	/**
	 * Dashboard
	 * Controller for view Dashboard
	 * @return void
	*/
	static public function dashboard() {

		// Get web server
		$server_software = $_SERVER['SERVER_SOFTWARE'];
		$server_software = explode(' ', $server_software);
		$server_software = $server_software[0];

		// Get charset
		$server_protocol = $_SERVER['SERVER_PROTOCOL'];

		// Domains on shared ip
		$shared_domains = Scanner::reverse_ip();

		$data['safebrowsing_status_code'] = Scanner::google_safe_browsing_code();
		$data['available_options'] = Modules::valid_modules();
		$data['ip'] = gethostbyname($_SERVER['SERVER_NAME']);
		$data['software'] = $server_software;
		$data['protocol'] = $server_protocol;
		$data['shared_domains'] = $shared_domains;
		
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
	
		$plugins = Scanner::vulndb_plugins();
		$themes = Scanner::vulndb_themes();

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