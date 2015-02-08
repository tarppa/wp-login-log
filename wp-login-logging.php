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
		$attempt   = '';
		$password  = $_POST['pwd'];
		$timestamp = date("Y-m-d H:i:s");
		
		if(empty($username)){
			
			$username = 'EMPTY_USERNAME';
		}

		$user = wp_authenticate_username_password(null, $username, $password);
		
		if(is_wp_error($user)) {
		 
		 $attempt = 'FAILURE'; 
		
		}
		else {
			
		$attempt = 'SUCCESS';	
		}
		$content = array($timestamp, $username, $attempt,"\n"); 
		$content = implode(" ", $content);
		
		# write to file
		$file_handle = fopen('/home/tari/data/log/login.log', 'a+');
		fwrite($file_handle, $content);
		fclose($file_handle);
	}
	
	#wp_login is a deprecated function, but in this case it's to be used
	add_action('wp_authenticate', 'write_to_log');

?>
