<?php
/**
 * Enqueues scripts and styles.
 */
add_theme_support( 'post-thumbnails' );
function whales_ventures_scripts()
{
    // Enqueue styles
    wp_enqueue_style( 'whales-ventures-main', get_template_directory_uri() . '/assets/styles/main.min.css', array());

    // Enqueue scripts
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'whales-ventures-script', get_template_directory_uri() . '/assets/js/main.js', array( 'jquery' ), true );

    // Localize script
    wp_localize_script('whales-ventures-script', 'ajax', array(
        'url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('ajax'),
        'home' => home_url()
    ));
}
add_action( 'wp_enqueue_scripts', 'whales_ventures_scripts' );
