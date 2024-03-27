<?php
class PostCreationHandler {
    public function __construct() {
        add_action('admin_post_custom_create_post', array($this, 'handlePostCreation'));
        add_action('admin_post_nopriv_custom_create_post', array($this, 'handlePostCreation'));
    }

    public function handlePostCreation() {
        // Check if user is logged in
		if ( ! is_user_logged_in() ) {
			wp_redirect( home_url() ); // Redirect to homepage if user is not logged in
			exit;
		}

		// Check if required fields are set
		if ( isset( $_POST['post_title'], $_POST['post_content'] ) && ! empty( $_POST['post_title'] ) && ! empty( $_POST['post_content'] ) ) {
			$post_title = sanitize_text_field( $_POST['post_title'] );
			$post_content = wp_kses_post( $_POST['post_content'] );

			// Handle image upload using media_handle_upload
			$attachment_id = 0;
			if ( isset( $_FILES['post_image'] ) && ! empty( $_FILES['post_image']['tmp_name'] ) ) {
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
				require_once( ABSPATH . 'wp-admin/includes/media.php' );

				$attachment_id = media_handle_upload( 'post_image', 0 ); // 0 means no post ID, as we don't have it yet
			}

			// Handle categories
			$post_categories = array();
			if ( isset( $_POST['post_category'] ) && ! empty( $_POST['post_category'] ) ) {
				$category_names = explode( ',', $_POST['post_category'] );
				foreach ( $category_names as $category_name ) {
					$category_name = sanitize_text_field( trim( $category_name ) );
					$existing_category = get_term_by( 'name', $category_name, 'category' );
					if ( $existing_category ) {
						$post_categories[] = $existing_category->term_id;
					} else {
						// Create new category if not exists
						$new_category = wp_insert_term( $category_name, 'category' );
						if ( ! is_wp_error( $new_category ) ) {
							$post_categories[] = $new_category['term_id'];
						}
					}
				}
			}

			// Create post array
			$new_post = array(
				'post_title'   => $post_title,
				'post_content' => $post_content,
				'post_status'  => 'publish',
				'post_author'  => get_current_user_id(),
				'post_category' => $post_categories,
			);

			// Insert the post into the database
			$post_id = wp_insert_post( $new_post );

			if ( ! is_wp_error( $post_id ) ) {
				// Set post thumbnail if an image was uploaded
				if ( $attachment_id && ! is_wp_error( $attachment_id ) ) {
					set_post_thumbnail( $post_id, $attachment_id );
				}

				// Redirect to the created post
				wp_redirect( get_permalink( $post_id ) );
				exit;
			} else {
				// Error handling
				wp_redirect( home_url() . '?post_creation_error=true' );
				exit;
			}
		} else {
			// Error handling
			wp_redirect( home_url() . '?post_creation_error=true' );
			exit;
		}
    }
}
new PostCreationHandler();
