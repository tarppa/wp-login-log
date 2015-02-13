<?php
/**
* Plugin Name: WP Login Log
* Description: Write all login attempts to a logfile
* Author: https://github.com/tarppa/
* Version: 0.01
* License: 3-clause BSD
*/

/*
 *      Redistribution and use in source and binary forms, with or without
 *      modification, are permitted provided that the following conditions are
 *      met:
 *
 *      * Redistributions of source code must retain the above copyright
 *        notice, this list of conditions and the following disclaimer.
 *      * Redistributions in binary form must reproduce the above
 *        copyright notice, this list of conditions and the following disclaimer
 *        in the documentation and/or other materials provided with the
 *        distribution.
 *      * Neither the name of the Compdigitec nor the names of its
 *        contributors may be used to endorse or promote products derived from
 *        this software without specific prior written permission.
 *
 *      THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 *      "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 *      LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 *      A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 *      OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 *      SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 *      LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 *      DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 *      THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 *      (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 *      OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

/* signon -> wp_authenticate -> authenticate_filter -> wp_authenticate_username_password*/


//a modified wp_authenticate_username_password function with added logging functionality
function authenticate_and_log( $user, $username, $password ) {
  $attempt = '';
  
  // do the authentication here
  
  if ( is_a( $user, 'WP_User' ) ) {
    $attempt = 'SUCCESS';
    write_to_log($user->name, $attempt);
    return $user;
  }
  
  if ( empty($username) || empty($password) ) {
    if ( is_wp_error( $user ) ){
      return $user;
    }
  
    $error = new WP_Error();
    
    if ( empty($username) ){
      $error->add('empty_username', __('<strong>ERROR</strong>: The username field is empty.'));
    } if ( empty($password) ){
      $error->add('empty_password', __('<strong>ERROR</strong>: The password field is empty.'));
    }
    return $error;
  }
  
  $user = get_user_by('login', $username);

  if ( !$user ){
    $attempt = 'FAILURE';
    write_to_log( $username, $attempt );
    return new WP_Error( 'invalid_username', sprintf( __( '<strong>ERROR</strong>: Invalid username. <a href="%s">Lost your password</a>?' ), wp_lostpassword_url() ) );
  }
  
  $user = apply_filters( 'wp_authenticate_user', $user, $password );

  if ( is_wp_error($user) ){
    return $user;
  }
  
  if ( !wp_check_password($password, $user->user_pass, $user->ID) ){
    $attempt = 'FAILURE';
    write_to_log( $username, $attempt );
    return new WP_Error( 'incorrect_password', sprintf( __( '<strong>ERROR</strong>: The password you entered for the username <strong>%1$s</strong> is incorrect. <a href="%2$s">Lost your password</a>?' ),$username, wp_lostpassword_url() ) );
  }
  
  $attempt = 'SUCCESS';
  write_to_log( $username, $attempt );
  return $user;
}

// write data to log
function write_to_log($username,$attempt){
  
  $timestamp = date( "Y-m-d H:i:s" );  
  $log_location = dirname( ini_get( 'error_log' ) );
  $log_line = "{$timestamp} {$username} {$attempt}\n";
  // write to file
  // debug error_log("{$username} {$attempt}");
  $file_handle = fopen( "$log_location/login.log",'a' );
  fwrite( $file_handle, $log_line );
  fclose( $file_handle );
}
// replace the authentication function with a custom one
remove_filter( 'authenticate', 'wp_authenticate_username_password',  20, 3 );
add_filter( 'authenticate', 'authenticate_and_log',20,3 );

?>
