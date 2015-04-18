<?php
namespace Umbrella;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Log
{
	/**
	 * Write
	 * Write to log
	 * @return void
	*/
	static public function write( $module, $message )
	{
		global $wpdb;

		self::checkDatabaseTable();

		$table_name = $wpdb->prefix . 'umbrella_sp_log';

		$request = $_REQUEST;
		unset($request['pwd']);
		
		$wpdb->insert( 
			$table_name, 
			array( 
				'time' => current_time( 'mysql' ), 
				'module' => $module, 
				'message' => $message, 
				'visitor_ip' => self::ipaddress(), 
				'query_string' => $_SERVER['REQUEST_URI'],
				'request_data' => serialize($request),
				'admin_notice' => 1
			) 
		);
	}

	/**
	 * IP Address
	 * Get visitor ipaddress.
	 * @return void
	*/
	static public function ipaddress()
	{
		// Replace IP if behind CloudeFlare proxy.
		if (isset($_SERVER["HTTP_CF_CONNECTING_IP"]))
  			$ipaddress = $_SERVER["HTTP_CF_CONNECTING_IP"];
  		else 
  			$ipaddress =$_SERVER['REMOTE_ADDR'];

  		return $ipaddress;
	}

	/**
	 * Reset Counter
	 * Update all admin_notice columns to 0.
	 * @return void
	*/
	static public function reset_counter()
	{
		global $wpdb;

		self::checkDatabaseTable();
		
		$table_name = $wpdb->prefix . 'umbrella_sp_log';

		$wpdb->query("UPDATE {$table_name} SET admin_notice = 0");
	}

	/**
	 * Counter
	 * Count unviewed log entries.
	 * @return void
	*/
	static public function counter()
	{
		global $wpdb;

		self::checkDatabaseTable();

		$table_name = $wpdb->prefix . 'umbrella_sp_log';

		if ($results = $wpdb->get_results("SELECT count(admin_notice) as count FROM {$table_name} WHERE admin_notice = 1"))
		{
			return $results[0]->count;
		}
		else 
			return 0;
	}


	/**
	 * Read
	 * Get log entries from database table
	 * @return array
	*/
	static public function read()
	{
		global $wpdb;

		self::checkDatabaseTable();

		$table_name = $wpdb->prefix . 'umbrella_sp_log';

		$logs = $wpdb->get_results( "SELECT * FROM {$table_name} ORDER BY TIME DESC" );

		return $logs;
	}	

	/**
	 * Empty Logs
	 * Erase all log entries from table.
	 * @return array
	*/
	static public function empty_logs()
	{
		global $wpdb;

		// Truncate log table
		$table_name = $wpdb->prefix . 'umbrella_sp_log';
		$delete = $wpdb->query("TRUNCATE TABLE {$table_name}");

		return true;
	}

	/**
	 * Check database table
	 * Check if table exists and install if not
	 * @return void
	*/
	static public function checkDatabaseTable()
	{
		global $wpdb;

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$charset_collate = $wpdb->get_charset_collate();
		$table_name = $wpdb->prefix . 'umbrella_sp_log'; 

		// Plugin version 1.0
		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		  module varchar(55) DEFAULT '' NOT NULL,
		  message text NOT NULL,
		  visitor_ip varchar(20) DEFAULT '' NOT NULL,
		  query_string varchar(255) DEFAULT '' NOT NULL,
		  request_data text DEFAULT '' NOT NULL,
		  UNIQUE KEY id (id)
		) $charset_collate;";
		
		$wpdb->query($sql);

		// Plugin version 1.4
		$col = $wpdb->query("SHOW COLUMNS FROM {$table_name} LIKE 'admin_notice'");
		if (!$col)
		{
			$sql = "ALTER TABLE {$table_name} ADD admin_notice int(1)";
			$wpdb->query($sql);
		}

	}
}