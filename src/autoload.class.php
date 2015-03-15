<?php
namespace Umbrella;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Autoload
{
	// Hooks to autoload.
	protected $autoload = array( 'plugins_loaded', 'init', 'admin_init', 'admin_menu');

	/**
	 * Autoload Helper
	 * This will init the plugin and autoload files.
	 * @return void
	*/
	function __construct() {
		$this->autoload();
	}

	/**
	 * Load hooks
	 * This will load everything :) 
	 * @return void
	*/
	public function autoload() {
		// Get all hooks from protected var $autoload;
		$hooks = $this->autoload;

		// Loop trough hooks and add actions for those who have declared methods.
		foreach ($hooks as $hook) {
			if (method_exists($this, $hook)) 
				add_action($hook, array($this, $hook));
		}
	}

	/**
	 * Init
	 * This function will run when WordPress calls the hook "init".
	 * @return void
	*/
	public function init() {

		// Load all activated modules.
		$this->load_modules();
		
	}		

	/**
	 * Plugins Loaded
	 * This function will run when WordPress calls the hook "plugins_loaded".
	 * @return void
	*/
	public function plugins_loaded() {

	}

	/**
	 * Admin Init
	 * This function will run when WordPress calls the hook "admin_init".
	 * @return void
	*/
	public function admin_init() {

		//register our settings
		register_setting( 'umbrella-settings', 'umbrella_load_modules', array('Umbrella\Modules', 'validate_modules') );
	}

	/**
	 * Admin Menu
	 * This function will run when WordPress calls the hook "admin_menu".
	 * @return void
	*/
	public function admin_menu() {
		add_menu_page( 'DASHBOARD | WordPress Antivirus and Hack Protection', 'Umbrella', 'administrator', 'umbrella', array('Umbrella\controller', 'dashboard') , 'dashicons-shield', 3 ); 
		add_submenu_page( 'umbrella', 'Umbrella', __('Dashboard'), 'administrator', 'umbrella', array('Umbrella\controller', 'dashboard') ); 
		//add_submenu_page( 'umbrella', 'PERMISSIONS | WordPress Antivirus and Hack Protection', 'Permissions', 'administrator', 'umbrella-permissions', array('Umbrella\controller', 'permissions') ); 
		add_submenu_page( 'umbrella', __('Vulnerabilities', UMBRELLA__TEXTDOMAIN), __('Vulnerabilities', UMBRELLA__TEXTDOMAIN), 'administrator', 'umbrella-vulnerabilities', array('Umbrella\controller', 'vulnerabilities') ); 
		add_submenu_page( 'umbrella', __('File Scanner', UMBRELLA__TEXTDOMAIN), __('File Scanner', UMBRELLA__TEXTDOMAIN), 'administrator', 'umbrella-scanner', array('Umbrella\controller', 'scanner') ); 
		add_submenu_page( 'umbrella', __('Logs', UMBRELLA__TEXTDOMAIN), __('Logs', UMBRELLA__TEXTDOMAIN), 'administrator', 'umbrella-logging', array('Umbrella\controller', 'logging') ); 
	}

	/**
	 * Load Modules
	 * This will load all activated modules
	 * @return void
	*/
	public function load_modules() {
		
		// Get all available modules from module class.
		$modules = Modules::valid_modules();
		$options = get_option('umbrella_load_modules');

		// Loop trough all available modules and include them if they are activated.
		foreach ($modules as $mod)
		{
			// Check if modules is activated.
			if (isset($options[$mod[0]]))
			{
				// Include module if it exists.
				$path_to_file = UMBRELLA__PLUGIN_DIR . 'src/modules/' . $mod[0] . '.php';
				if (file_exists($path_to_file))
					require_once($path_to_file);
			}
		}

	}
}


