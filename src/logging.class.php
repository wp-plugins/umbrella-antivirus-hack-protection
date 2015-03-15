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

		$table_name = $wpdb->prefix . 'umbrella_log';

		$request = $_REQUEST;
		unset($request['pwd']);
		
		$wpdb->insert( 
			$table_name, 
			array( 
				'time' => current_time( 'mysql' ), 
				'module' => $module, 
				'message' => $message, 
				'visitor_ip' => $_SERVER['REMOTE_ADDR'], 
				'query_string' => $_SERVER['REQUEST_URI'],
				'request_data' => serialize($request)
			) 
		);
	}


	static public function read()
	{
		global $wpdb;
		$table_name = $wpdb->prefix . 'umbrella_log';

		$logs = $wpdb->get_results( "SELECT * FROM {$table_name} ORDER BY TIME DESC" );

		return $logs;
	}

	/**
	 * Check database table
	 * Check if table exists and install if not
	 * @return void
	*/
	static public function checkDatabaseTable()
	{
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();
		$table_name = $wpdb->prefix . 'umbrella_log'; 

		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		  module varchar(55) DEFAULT '' NOT NULL,
		  message text NOT NULL,
		  visitor_ip varchar(55) DEFAULT '' NOT NULL,
		  query_string varchar(255) DEFAULT '' NOT NULL,
		  request_data text DEFAULT '' NOT NULL,
		  UNIQUE KEY id (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}
}