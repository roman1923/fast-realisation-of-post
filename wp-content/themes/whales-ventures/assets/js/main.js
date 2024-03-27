document.addEventListener("DOMContentLoaded", function() {
	var regBtn = document.querySelector(".reg-btn");

	if(regBtn) {
		regBtn.addEventListener("click", function() {
				document.querySelector('#registration-form').classList.toggle("active");
		});
	}
});


jQuery(document).ready(function($) {
	// Delete post
	$('.delete-post-btn').on('click', function() {
			var postId = $(this).data('post-id');

			if (confirm('Are you sure you want to delete this post?')) {
					$.ajax({
							url: ajax.url,
							type: 'POST',
							data: {
									action: 'custom_delete_post',
									post_id: postId,
									security: ajax.nonce
							},
							success: function(response) {
									alert(response.data);
									window.location.href = ajax.home;
							},
							error: function(xhr, status, error) {
									alert('Error: ' + error);
							}
					});
			}
	});
});
