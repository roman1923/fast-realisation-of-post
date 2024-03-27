<?php
class PostDeletionHandler {
    public function __construct() {
        add_action('wp_ajax_custom_delete_post', array($this, 'handlePostDeletion'));
        add_action('wp_ajax_nopriv_custom_delete_post', array($this, 'handlePostDeletion'));
    }

    public function handlePostDeletion() {
        if (!isset($_POST['post_id'])) {
			wp_send_json_error('Unauthorized or missing post ID');
		}
	
		$post_id = $_POST['post_id'];
	
		if (wp_delete_post($post_id)) {
			wp_send_json_success('Post deleted successfully');
		} else {
			wp_send_json_error('Failed to delete post');
		}
    }
}
new PostDeletionHandler();
