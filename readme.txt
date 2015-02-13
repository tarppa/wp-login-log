=== wp-login-logging ===
Contributors: (this should be a list of wordpress.org userid's)
Donate link: http://example.com/
Tags: login, authentication
Requires at least: 4.1
Tested up to: 4.1
Stable tag: [ REMEMBER TO MODIFY ]
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Write all login attempts to a logfile by replacing default wp_authenticate_username_password functionality

== Description ==

Write all login attempts where both username and password are supplied to a log file. The logfile is relative
to error.log and is called 'login.log', the format : "TIMESTAMP USERNAME SUCCESS/FAILURE".

In practice this plugin contains a rewritten version of the wp_authenticate_username_password function and replaces
it when the plugin is enabled.

== Installation ==

1. Upload `wp-login-logging.php` to the `/wp-content/plugins/wp-login-logging` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Enjoy!

== Changelog ==

= 0.5=
* First version`
