<?php
/**
 *	Plugin Name: Login logging
 * 	Description: Write all login attempts to a logfile
 *	Author: https://github.com/tarppa/
 * 	Version: 0
 */
	function log_write() {
	
	# get login data    
	$content = "timestamp-username-failed/successful\n";
	
	
	# write to file
	$handle = fopen('/home/tari/data/log/login.log','a+');
	fwrite($handle,$content)
	fclose($handle);
	}
	
	add_action('wp_signon', 'log_write');

?>
