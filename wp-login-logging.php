<?php
/**
 *	Plugin Name: Login logging
 * 	Description: Write all login attempts to a logfile
 *	Author: https://github.com/tarppa/
 * 	Version: 0
 *  License: NO WARRANTY. DO WHATEVER YOU LIKE WITH THIS
 */
	function write_to_log() {
	
	#get login data    
	$username  = $_POST['log'];
	$timestamp = strftime();
	
	# check if login succeeded
		if(is_user_logged_in()) {
		 $attempt = 'SUCCESS';
		 
		}
		else {
			
		$attempt = 'FAILURE';	
			
		}
	$content = array($timestamp, $username, $attempt); 
	$content = implode(" ", $content);
	# write to file
	
	#$content = "testing";
	$file_handle = fopen('/home/tari/data/log/login.log', 'a+');
	fwrite($file_handle, $content);
	fclose($file_handle);
	}
	
	#wp_login is a deprecated function, but in this case it's to be used
	add_action('wp_login', 'write_to_log');

?>
