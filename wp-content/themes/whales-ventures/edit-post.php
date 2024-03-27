<?php
/*
Template Name: Edit Post
*/
get_header();

if (isset ($_GET['post-id'])) {
	$post_id = intval($_GET['post-id']);
	$post = get_post($post_id);
	if ($post && $post->post_author == get_current_user_id()) { ?>
		<form id="edit_post_form" method="post" enctype="multipart/form-data">
			<label for="post_title">Title:</label><br />
			<input type="text" id="post_title" name="post_title" value="<?php echo esc_attr($post->post_title); ?>" /><br />
			<label for="post_content">Content:</label><br />
			<textarea id="post_content" name="post_content"><?php echo esc_textarea($post->post_content); ?></textarea><br />
			<label for="post_image">Image:</label><br />
			<input type="file" id="post_image" name="post_image" accept="image/*" /><br />
			<label for="post_category">Categories:</label><br />
			<input type="text" id="post_category" name="post_category" /><br />
			<input type="submit" value="Update Post" />
			<input type="hidden" name="post_id" value="<?php echo $post->ID; ?>" />
			<?php wp_nonce_field('custom_update_post_nonce', 'custom_update_post_nonce'); ?>
		</form>
	<?php } else {
		echo '<p>You are not authorized to edit this post.</p>';
	}
} else {
	echo '<p>Post ID not provided.</p>';
}
get_footer();
?>