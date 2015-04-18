<?php
namespace Umbrella;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Autoload
{
	// Hooks to autoload.
	protected $autoload = array( 'admin_notices', 'plugins_loaded', 'init', 'admin_init', 'admin_menu' );

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

		add_filter('plugin_row_meta', array( &$this, 'plugin_row_meta' ), 10, 2);
		add_filter('plugin_action_links_' . UMBRELLA__TEXTDOMAIN . '/init.php', array( &$this, 'action_links' ) );

		// Load all activated modules.
		$this->load_modules();
	}

	/**
	 * Init
	 * This function will run when WordPress calls the hook "init".
	 * @return void
	*/
	public function init() {
		
	}		

	/**
	 * Plugin Row Meta
	 * This function will run when WordPress calls the filter "plugin_row_meta".
	 * @return html
	*/
	public function plugin_row_meta($links, $file) {
		
		$file = plugin_dir_path( UMBRELLA__PLUGIN_DIR ) . $file;

		if ($file == UMBRELLA__PLUGIN_DIR . 'init.php') {
			// Add links under the description field
		}
		return $links;	
	}		

	/**
	 * Action links
	 * This function will run when WordPress calls the filter "action_links".
	 * @return html
	*/
	public function action_links($links) {
		
		$links['settings'] = '<a href="admin.php?page=umbrella-site-protection">' . __('Settings', UMBRELLA__TEXTDOMAIN) . '</a>';
		$links['logs'] = '<a href="admin.php?page=umbrella-logging">' . __('Logs', UMBRELLA__TEXTDOMAIN) . '</a>';
		$links['review'] = '<a target="_blank" href="https://wordpress.org/support/plugin/umbrella-antivirus-hack-protection">' . __('Support', UMBRELLA__TEXTDOMAIN) . '</a>';

		return $links;
	}	

	/**
	 * Plugins Loaded
	 * This function will run when WordPress calls the hook "plugins_loaded".
	 * @return void
	*/
	public function plugins_loaded() {
	
	}	

	/**
	 * Admin Notices
	 * This function will run when WordPress calls the hook "admin_notices".
	 * @return void
	*/
	public function admin_notices() {


		$logs = Log::counter();

		if ($logs > 0 AND get_option('umbrella_sp_disable_notices') != 1):
	?>
    <div class="error umbrella">
    	<a href="?page=umbrella-logging&amp;do=disable-admin-notices" class="button" style="float:right;margin-top: 3px;margin-left: 5px;"><?php _e( 'Disable log notices', UMBRELLA__TEXTDOMAIN ); ?></a>
     	<a href="admin.php?page=umbrella-logging" class="button button-primary" style="float:right;margin-top: 3px;"><?php _e( 'View log', UMBRELLA__TEXTDOMAIN ); ?></a>
        <p>
        	<a href="admin.php?page=umbrella-site-protection"><strong><?php _e( 'Site Protection', UMBRELLA__TEXTDOMAIN ); ?></strong></a>: 
        	<?php printf( __( 'You have <strong>%d</strong> new log message(s).', UMBRELLA__TEXTDOMAIN ), $logs); ?>
    	</p>
    </div>
    <?php
    	endif;
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
		add_menu_page( 'Site Protection | Umbrella', __('Site Protection', UMBRELLA__TEXTDOMAIN), 'administrator', 'umbrella-site-protection', array('Umbrella\controller', 'dashboard') , 'dashicons-shield', 3 ); 
		add_submenu_page( 'umbrella-site-protection', __('Site Protection by Umbrella Plugins', UMBRELLA__TEXTDOMAIN), __('Site Protection by Umbrella Plugins', UMBRELLA__TEXTDOMAIN), 'administrator', 'umbrella-site-protection', array('Umbrella\controller', 'dashboard') ); 
		//add_submenu_page( 'umbrella', 'PERMISSIONS | WordPress Antivirus and Hack Protection', 'Permissions', 'administrator', 'umbrella-permissions', array('Umbrella\controller', 'permissions') ); 
		add_submenu_page( 'umbrella-site-protection', __('Vulnerabilities', UMBRELLA__TEXTDOMAIN), __('Vulnerabilities', UMBRELLA__TEXTDOMAIN), 'administrator', 'umbrella-vulnerabilities', array('Umbrella\controller', 'vulnerabilities') ); 
		add_submenu_page( 'umbrella-site-protection', __('Core Scanner', UMBRELLA__TEXTDOMAIN), __('Core Scanner', UMBRELLA__TEXTDOMAIN), 'administrator', 'umbrella-scanner', array('Umbrella\controller', 'scanner') ); 
		add_submenu_page( 'umbrella-site-protection', __('Logs', UMBRELLA__TEXTDOMAIN), __('Logs', UMBRELLA__TEXTDOMAIN), 'administrator', 'umbrella-logging', array('Umbrella\controller', 'logging') ); 
		add_submenu_page( 'umbrella-site-protection', __('Umbrella Network', UMBRELLA__TEXTDOMAIN), __('Umbrella Network', UMBRELLA__TEXTDOMAIN), 'administrator', 'umbrella-sp-network', array('Umbrella\controller', 'sp_network') ); 
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


