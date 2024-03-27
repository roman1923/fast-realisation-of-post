<?php
class EditPostPageCreator {
    public function __construct() {
        add_action('admin_init', array($this, 'createEditPostPage'));
    }

    public function createEditPostPage() {
        $edit_page_title = 'Edit Post';
		$edit_page_slug = 'edit-post';
		$edit_page = get_page_by_path($edit_page_slug);
		if (!$edit_page) {
			$edit_page_id = wp_insert_post(array(
				'post_title' => $edit_page_title,
				'post_name' => $edit_page_slug,
				'post_type' => 'page',
				'post_status' => 'publish'
			));
			update_post_meta($edit_page_id, '_wp_page_template', 'edit-post.php');
		}
    }
}
new EditPostPageCreator();
