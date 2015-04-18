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
			array('umbrella-site-protection', __('Dashboard')),
			array('umbrella-vulnerabilities', __('Vulnerabilities', UMBRELLA__TEXTDOMAIN)),
			array('umbrella-scanner', __('Core Scanner', UMBRELLA__TEXTDOMAIN)),
			array('umbrella-logging', __('Logs', UMBRELLA__TEXTDOMAIN)),
			array('umbrella-sp-network', __('Umbrella Network*', UMBRELLA__TEXTDOMAIN)),
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

		// Empty logs function
		if (isset($_GET['do'])) {

			switch ($_GET['do']) {

				case 'empty-logs':
					Log::empty_logs();
					$data['refresh_page'] = 1;
				break;

				case 'disable-admin-notices':
					add_option('umbrella_sp_disable_notices', 1);
					$data['refresh_page'] = 1;
				break;

				case 'enable-admin-notices':
					delete_option('umbrella_sp_disable_notices');
					$data['refresh_page'] = 1;
				break;
			}
		}

		$data['logs'] = Log::read();

		Log::reset_counter();

		self::make('logging', $data);
	}		

	/**
	 * Umbrella SP Network
	 * Controller for Logging
	 * @return void
	*/
	static public function sp_network() {
		$data = array();
		self::make('network', $data);
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
		$data['ip'] = $_SERVER['HTTP_HOST'];
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
	
		// Get data if cached.
		$fileslist = array();
		if (null !== get_transient('umbrella-file-scan'))
			$fileslist = get_transient('umbrella-file-scan');

		// Remove temporary ignored files from files list.
     	$ignored_files = array();
        $ignored_files = get_option('umbrella-sp-ignored-files');

        // If option is set, unserialize it into an array.
        if (!empty($ignored_files)) 
            $ignored_files = unserialize($ignored_files);

        if (is_array($fileslist)) {

	    	foreach ($fileslist as $index => $file)
	        {
	        	if (isset($ignored_files[$file['file']]) 
	        		AND isset($file['response']['md5']) 
	        		AND $ignored_files[$file['file']] == $file['response']['md5'])
	        			unset($fileslist[$index]);
	        } 
		}

        $data['fileslist'] = $fileslist;
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

		wp_enqueue_style('umbrella-filescanner-css', UMBRELLA__PLUGIN_URL . 'css/layout.css');
		wp_enqueue_script('umbrella-js', UMBRELLA__PLUGIN_URL . 'js/siteprotection.js');


		$path_to_file = UMBRELLA__PLUGIN_DIR . 'views/' . $view . '.view.php';

		if (is_array($data)) 
			extract($data);

		if ( file_exists( $path_to_file ) )
			require_once($path_to_file);
		else
			echo "File " . $path_to_file . " does not exists.";

	}

	
}