<?php
class EditPostHandler {
    public function __construct() {
        add_action('init', array($this, 'handleEditPost'));
    }

    public function handleEditPost() {
        if (isset($_POST['custom_update_post_nonce']) && wp_verify_nonce($_POST['custom_update_post_nonce'], 'custom_update_post_nonce')) {
            $post_id = intval($_POST['post_id']);
            $post = get_post($post_id);
            if ($post && $post->post_author == get_current_user_id()) {
                $post_title = sanitize_text_field($_POST['post_title']);
                $post_content = wp_kses_post($_POST['post_content']);
                $updated_post = array(
                    'ID' => $post_id,
                    'post_title' => $post_title,
                    'post_content' => $post_content
                );

                // Handle image upload
                if (!empty($_FILES['post_image']['name'])) {
                    $attachment_id = $this->handleImageUpload($_FILES['post_image'], $post_id);
                    if (!is_wp_error($attachment_id)) {
                        set_post_thumbnail($post_id, $attachment_id);
                    }
                }

                // Handle categories
                if (!empty($_POST['post_category'])) {
                    $category_names = explode(',', $_POST['post_category']);
                    $post_categories = array();
                    foreach ($category_names as $category_name) {
                        $category_name = sanitize_text_field(trim($category_name));
                        $existing_category = get_term_by('name', $category_name, 'category');
                        if ($existing_category) {
                            $post_categories[] = $existing_category->term_id;
                        } else {
                            // Create new category if not exists
                            $new_category = wp_insert_term($category_name, 'category');
                            if (!is_wp_error($new_category)) {
                                $post_categories[] = $new_category['term_id'];
                            }
                        }
                    }
                    wp_set_post_categories($post_id, $post_categories);
                }
                // Update the post
                wp_update_post($updated_post);
                echo 'Post updated successfully! <a href="'.home_url().'">Home</a>';
            } else {
                echo 'You are not authorized to edit this post.';
            }
            exit;
        }
    }

    public function handleImageUpload($file, $post_id) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $attachment_id = media_handle_upload('post_image', $post_id);
        return $attachment_id;
    }
}

new EditPostHandler();
