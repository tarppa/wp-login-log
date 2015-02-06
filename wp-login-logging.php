<?php
	# Wordpress plugin for writing login attempts to a file

	function log_write() {
	    
	$content = "timestamp-username-failed/successful\n";

	$handle = fopen('login.log','a+');
	fwrite($handle,$content)
	fclose($handle);
	}
	
	add_action('wp_signon', 'log_write');

?>
