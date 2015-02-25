<?php
/*
	Plugin Name: Umbrella (Antivirus & Hack Protection)
	Plugin URI: http://www.rasmuskjellberg.se/wordpress-plugins/umbrella/
	Description: WordPress Antivirus and Hack protection. More functions are planned and will be launched soon. Look for an update :) 
	Author: Rasmus Kjellberg
	Version: 1.0
	Author URI: http://www.rasmuskjellberg.se/
*/
/*  
	Copyright 2015 Rasmus Kjellberg (rk@youngmedia.se)
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/ 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Define some good constants.
define( 'UMBRELLA__VERSION', '1.0' );
define( 'UMBRELLA__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'UMBRELLA__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

// Include all libraries.
// There is no libraries yet..

// Include all source files
require_once( UMBRELLA__PLUGIN_DIR . 'src/controller.class.php' );
require_once( UMBRELLA__PLUGIN_DIR . 'src/modules.class.php' );
require_once( UMBRELLA__PLUGIN_DIR . 'src/autoload.class.php' );

// Run the autoloader
new Umbrella\Autoload;