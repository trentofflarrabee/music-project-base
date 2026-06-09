<?php

if (!defined('ABSPATH')) {
    exit;
}

function mpb_setup_theme() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');

    register_nav_menus([
        'primary' => __('Primary Menu', 'music-project-base'),
        'footer'  => __('Footer Menu', 'music-project-base'),
    ]);
}
add_action('after_setup_theme', 'mpb_setup_theme');

function mpb_enqueue_assets() {
    wp_enqueue_style(
        'mpb-style',
        get_stylesheet_uri(),
        [],
        '0.1.0'
    );
}
add_action('wp_enqueue_scripts', 'mpb_enqueue_assets');

require_once get_template_directory() . '/inc/template-functions.php';