<?php
namespace Umbrella;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Backup
{

	public static function database() {

		global $wpdb;

		$tables = array();

		// Get all tables from database
		$tables = $wpdb->get_results( 'SHOW TABLES' );
		
		foreach($tables as $table) {
			$table = reset($table);

			echo "{$table} <br>";
		}

		die();

	}

}