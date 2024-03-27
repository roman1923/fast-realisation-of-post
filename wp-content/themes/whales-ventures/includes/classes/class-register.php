<?php
class RegistrationFormHandler {
    public function __construct() {
        add_action('admin_post_custom_register_user', array($this, 'handleRegistrationForm'));
        add_action('admin_post_nopriv_custom_register_user', array($this, 'handleRegistrationForm'));
    }

    public function handleRegistrationForm() {
        // Check for empty fields
		if (isset($_POST['reg-username']) && isset($_POST['reg-email']) && isset($_POST['reg-password']) && !empty($_POST['reg-username']) && !empty($_POST['reg-email']) && !empty($_POST['reg-password'])) {
			$username = sanitize_user($_POST['reg-username']);
			$email = sanitize_email($_POST['reg-email']);
			$password = $_POST['reg-password'];

			$userdata = array(
				'user_login' => $username,
				'user_email' => $email,
				'user_pass'  => $password,
			);

			$user_id = wp_insert_user($userdata);

			if (!is_wp_error($user_id)) {
				wp_redirect( home_url() . '?registration_success=true' );
				exit;
			} else {
				wp_redirect( home_url() . '?registration_error=true' ); // Redirect with error parameter
				exit;
			}
		} else {
			// Display error message if fields are empty
			wp_redirect( home_url() . '?registration_error=true' ); // Redirect with error parameter
			exit;
		}
    }
}
new RegistrationFormHandler();
