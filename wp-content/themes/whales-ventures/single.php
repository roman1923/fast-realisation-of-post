<?php get_header(); ?>

<?php 
global $current_user;
get_currentuserinfo();
$post_author_id = get_post_field( 'post_author', get_the_ID() );
?>

<?php
if (have_posts()):
    while (have_posts()):
        the_post();
        ?>
        <div>
            <h2>
                <?php the_title(); ?>
            </h2>
            <?php if (has_post_thumbnail()): ?>
                <?php the_post_thumbnail('large'); ?>
            <?php endif; ?>
            <p>
                <?php the_content(); ?>
            </p>
            <p>Categories:
                <?php the_category(', '); ?>
            </p>
            <p>Author:
                <?php the_author(); ?>
            </p>
            <date>Date:
                <?php the_date(); ?>
            </date>
            <?php
            // Check if the current user is the author of the post
            if (is_user_logged_in() && get_current_user_id() === get_the_author_meta('ID')):
                if ($current_user->ID == $post_author_id) {
                    echo '<a href="'.site_url('edit-post').'?post-id='.get_the_ID().'">Edit</a>';
                }
                ?>
                <button class="delete-post-btn" data-post-id="<?php the_ID(); ?>">Delete</button>
            <?php endif; ?>
        </div>
        <?php
    endwhile;
else:
    echo 'No post found';
endif;
?>

<?php 
    if (comments_open() || get_comments_number()) :
        comments_template();
     endif;
?>

<?php get_footer(); ?>
