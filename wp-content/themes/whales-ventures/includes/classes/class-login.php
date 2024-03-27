<?php
class LoginFormHandler {
    public function __construct() {
        add_action('admin_post_custom_login', array($this, 'handleLoginForm'));
        add_action('admin_post_nopriv_custom_login', array($this, 'handleLoginForm'));
    }

    public function handleLoginForm() {
        // Check for empty fields
		if ( isset( $_POST['username'] ) && isset( $_POST['password'] ) && ! empty( $_POST['username'] ) && ! empty( $_POST['password'] ) ) {
			$username = sanitize_user( $_POST['username'] );
			$password = $_POST['password'];

			// Try to log in the user
			$user = wp_authenticate( $username, $password );

			if ( ! is_wp_error( $user ) ) {
				wp_set_auth_cookie( $user->ID, true );
				wp_redirect( home_url() ); // Redirect to home page after successful login
				exit;
			} else {
				// Display error message if login fails
				wp_redirect( home_url() . '?login_error=true' ); // Redirect with error parameter
				exit;
			}
		} else {
			// Display error message if fields are empty
			wp_redirect( home_url() . '?login_error=true' ); // Redirect with error parameter
			exit;
		}
    }
}
new LoginFormHandler();
