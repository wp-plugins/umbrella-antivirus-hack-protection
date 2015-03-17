=== Umbrella ===
Contributors: rkjellberg
Tags: antivirus, vulnerabilities, anti, hack, protection, firewall, trackbacks, pingbacks, version number, captcha, captcha login, secure login
Keywords: antivirus, vulnerabilities, anti, hack, protection, firewall, trackbacks, pingbacks, version number, captcha, captcha login, secure login
Requires at least: 3.0.1
Tested up to: 4.1.1
Stable tag: 1.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WordPress Antivirus and Hack protection.

== Description ==

Umbrella helps you protect your WordPress site and checks your plugin and themes for known vulnerabilities. 
More functions are planned and will be launched soon. Please look for updates :)

= Major features in Umbrella include =
* Vulnerabilities scanner for themes and plugins. 
* Add CAPTCHA to login screen for a more secure login. 
* Block all unauthorized and irrelevant requests through query strings.
* Hide version numbers in your front-end source code for WordPress-core and all of your plugins. 
* Completely turn off trackbacks & pingbacks to your site.
* Scan WordPress for unknown files and file modifications by comparing md5 strings.

= Planned features =
* "Hjälp till att förbättra kvaliten genom att skicka anonyma data och användningsstatistik"
* Scan WordPress folders and help user with permission issues.
* Get notified by email when a new vulnerability is found.

== Installation ==

1. Upload the entire `umbrella-antivirus-hack-protection` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.

You will find 'Umbrella' menu in your WordPress admin panel.

== Screenshots ==

1. dashboard.png
2. file-scanner.png
3. vulnerabilities.png 

== Changelog ==

= 1.3 =
*Release Date - 15 March, 2015*

* Bugfix: Filter Requests blocked some post updates
* Google Safe Browsing Checker
* Hosting Status in Dashboard
* Widgets in Dashboard
* Log all suspicious traffic for later analysation
* Updated language swedish (sv_SE)
* Cload Flare checker

= 1.2 =
*Release Date - 7 March, 2015*

* BETA version of File Scanner
* Bugfix: Undefined property: stdClass::$url when vulnerability has no external URLs.
* Translation to swedish (sv_SE)

= 1.1 =
*Release Date - 1 March, 2015*

* Vulnerabilities scanner for themes and plugins. 
* Add CAPTCHA to login screen for a more secure login. 