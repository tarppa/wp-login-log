<?php
/**
 *	Plugin Name: Login logging
 * 	Description: Write all login attempts to a logfile
 *	Author: https://github.com/tarppa/
 * 	Version: 0
 *  License: NO WARRANTY. DO WHATEVER YOU LIKE WITH THIS
 */
	function write_to_log() {
	
	# get login data    
	$username  = $_POST['log'];
	$timestamp = current_time('timestamp');
	
	# check if login succeeded
		if(is_user_logged_in()) {
		 $attempt = 'SUCCESS';
		 
		}
		else {
			
		$attempt = 'FAILURE';	
			
		}
	$content = array($timestamp, $username, $attempt); 
	# write to file
	$file_handle = fopen('/home/tari/data/log/login.log', 'a+');
	fwrite($file_handle, implode(" ", $content));
	fclose($file_handle);
	}
	
	add_action('wp_signon', 'write_to_log');

?>
