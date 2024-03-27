<?php
get_header();
?>

<?php
// Check if the user is logged in
if (is_user_logged_in()) {
    // If logged in, display welcome message and logout link
    $current_user = wp_get_current_user();
    echo 'Welcome, ' . $current_user->user_login . '! ';
    echo '<a href="' . wp_logout_url(home_url()) . '">Log out</a>';
} else {
    // If not logged in, display registration and login form
    ?>
    <h2>Login</h2>
    <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
        <input type="hidden" name="action" value="custom_login">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>
    <p>Don't have an account? <a class="reg-btn" href="#">Register here</a></p>
    <form id="registration-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
        <input type="hidden" name="action" value="custom_register_user">
        <label for="reg-username">Username:</label><br>
        <input type="text" id="reg-username" name="reg-username" required><br>
        <label for="reg-email">Email:</label><br>
        <input type="email" id="reg-email" name="reg-email" required><br>
        <label for="reg-password">Password:</label><br>
        <input type="password" id="reg-password" name="reg-password" required><br><br>
        <input type="submit" value="Register">
    </form>
    <?php
}
?>

<?php
if (is_user_logged_in()) { ?>
    <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="custom_create_post">
        <label for="post_title">Title:</label><br>
        <input type="text" id="post_title" name="post_title" required><br>
        <label for="post_content">Content:</label><br>
        <textarea id="post_content" name="post_content" required></textarea><br>
        <label for="post_image">Image:</label><br>
        <input type="file" id="post_image" name="post_image"><br>
        <label for="post_category">Category:</label><br>
        <input type="text" id="post_category" name="post_category" placeholder="Enter categories, separated by commas"><br>
        <input type="submit" value="Create Post">
    </form>
<?php } ?>

<form action="<?php echo esc_url(home_url()); ?>" method="get">
    <label for="category_filter">Filter by Category:</label>
    <select name="category_filter" id="category_filter">
        <option value="">All Categories</option>
        <?php
        $selected_category = isset($_GET['category_filter']) ? $_GET['category_filter'] : ''; // Get the selected category from the URL
        $categories = get_categories();
        foreach ($categories as $category) {
            $selected = ($selected_category == $category->slug) ? 'selected' : ''; // Check if this option is selected
            printf('<option value="%s" %s>%s</option>', esc_attr($category->slug), $selected, esc_html($category->name));
        }
        ?>
    </select>
    <input type="submit" value="Filter">
</form>


<?php
$args = array(
    'posts_per_page' => -1,
    'order' => 'DESC',
    'orderby' => 'date',
);

if (isset ($_GET['category_filter']) && $_GET['category_filter'] !== '') {
    $args['category_name'] = sanitize_text_field($_GET['category_filter']);
}

$recent_posts = new WP_Query($args);

if ($recent_posts->have_posts()):
    while ($recent_posts->have_posts()):
        $recent_posts->the_post();
        ?>
        <a href="<?php the_permalink(); ?>">
            <h2>
                <?php the_title(); ?>
            </h2>
            <?php if (has_post_thumbnail()): ?>
                <?php the_post_thumbnail('thumbnail'); ?>
            <?php endif; ?>
            <div>
                <?php the_content(); ?>
            </div>
            <p>Categories:
                <?php the_category(', '); ?>
            </p>
        </a>
        <?php
    endwhile;
    wp_reset_postdata();
else:
    echo 'No posts found';
endif;
?>

<?php
get_footer();
?>
