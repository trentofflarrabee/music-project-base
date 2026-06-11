<?php

if (!defined('ABSPATH')) {
    exit;
}

function mpb_setup_theme() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');

    add_theme_support('custom-logo', [
        'height'      => 120,
        'width'       => 420,
        'flex-height' => true,
        'flex-width'  => true,
    ]);

    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ]);

    register_nav_menus([
        'primary' => __('Primary Menu', 'music-project-base'),
        'footer'  => __('Footer Menu', 'music-project-base'),
    ]);
}
add_action('after_setup_theme', 'mpb_setup_theme');

function mpb_enqueue_assets() {
    $theme_style = function_exists('mpc_get_theme_style_settings')
        ? mpc_get_theme_style_settings()
        : [];

    $google_fonts_url = isset($theme_style['google_fonts_url'])
        ? $theme_style['google_fonts_url']
        : '';

    if ($google_fonts_url) {
        wp_enqueue_style(
            'mpb-google-fonts',
            esc_url($google_fonts_url),
            [],
            null
        );
    }

    wp_enqueue_style(
        'mpb-style',
        get_stylesheet_uri(),
        [],
        '0.1.0'
    );

    wp_add_inline_style(
        'mpb-style',
        mpb_get_theme_style_inline_css()
    );
}
add_action('wp_enqueue_scripts', 'mpb_enqueue_assets');

function mpb_get_theme_style_defaults() {
    return [
        'color_background' => '#111111',
        'color_surface' => '#101010',
        'color_text' => '#f5f5f5',
        'color_muted' => '#b8b8b8',
        'color_accent' => '#ffffff',
        'color_button_background' => '#f5f5f5',
        'color_button_text' => '#111111',

        'font_heading' => 'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif',
        'font_body' => 'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif',
        'font_accent' => 'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif',
        'heading_text_transform' => 'none',
        'heading_letter_spacing' => '-0.04em',

        'texture_enabled' => 0,
        'texture_image_id' => 0,
        'texture_opacity' => '0.08',
        'texture_size' => '420px',
        'texture_repeat' => 'repeat',
        'texture_apply_body' => 1,
        'texture_apply_footer' => 1,
        'texture_apply_buttons' => 0,
    ];
}

function mpb_get_theme_style_settings() {
    $defaults = mpb_get_theme_style_defaults();

    if (!function_exists('mpc_get_theme_style_settings')) {
        return $defaults;
    }

    $settings = mpc_get_theme_style_settings();

    if (!is_array($settings)) {
        $settings = [];
    }

    return wp_parse_args($settings, $defaults);
}

function mpb_clean_font_family($value) {
    $value = is_string($value) ? wp_strip_all_tags($value) : '';
    $value = preg_replace('/[^a-zA-Z0-9\s,\-_"\'().]/', '', $value);

    return trim($value);
}

function mpb_get_theme_style_inline_css() {
    $settings = mpb_get_theme_style_settings();

    $texture_url = 'none';

    if (!empty($settings['texture_enabled']) && !empty($settings['texture_image_id'])) {
        $image_url = wp_get_attachment_image_url(absint($settings['texture_image_id']), 'full');

        if ($image_url) {
            $texture_url = 'url("' . esc_url($image_url) . '")';
        }
    }

    $color_background = sanitize_hex_color($settings['color_background']) ?: '#111111';
    $color_surface = sanitize_hex_color($settings['color_surface']) ?: '#101010';
    $color_text = sanitize_hex_color($settings['color_text']) ?: '#f5f5f5';
    $color_muted = sanitize_hex_color($settings['color_muted']) ?: '#b8b8b8';
    $color_accent = sanitize_hex_color($settings['color_accent']) ?: '#ffffff';
    $color_button_background = sanitize_hex_color($settings['color_button_background']) ?: '#f5f5f5';
    $color_button_text = sanitize_hex_color($settings['color_button_text']) ?: '#111111';

    $font_heading = mpb_clean_font_family($settings['font_heading']);
    $font_body = mpb_clean_font_family($settings['font_body']);
    $font_accent = mpb_clean_font_family($settings['font_accent']);

    $heading_text_transform = sanitize_key($settings['heading_text_transform']);
    $heading_letter_spacing = esc_html($settings['heading_letter_spacing']);

    $texture_opacity = is_numeric($settings['texture_opacity'])
        ? max(0, min(1, (float) $settings['texture_opacity']))
        : 0.08;

    $texture_size = esc_html($settings['texture_size']);
    $texture_repeat = esc_html($settings['texture_repeat']);

    return "
        :root {
            --mpb-color-bg: {$color_background};
            --mpb-color-surface: {$color_surface};
            --mpb-color-text: {$color_text};
            --mpb-color-muted: {$color_muted};
            --mpb-color-accent: {$color_accent};
            --mpb-color-button-bg: {$color_button_background};
            --mpb-color-button-text: {$color_button_text};

            --mpb-font-heading: {$font_heading};
            --mpb-font-body: {$font_body};
            --mpb-font-accent: {$font_accent};

            --mpb-heading-text-transform: {$heading_text_transform};
            --mpb-heading-letter-spacing: {$heading_letter_spacing};

            --mpb-texture-image: {$texture_url};
            --mpb-texture-opacity: {$texture_opacity};
            --mpb-texture-size: {$texture_size};
            --mpb-texture-repeat: {$texture_repeat};
        }
    ";
}

function mpb_theme_style_body_classes($classes) {
    $settings = mpb_get_theme_style_settings();

    if (!empty($settings['texture_enabled']) && !empty($settings['texture_image_id'])) {
        $classes[] = 'mpb-texture-enabled';

        if (!empty($settings['texture_apply_body'])) {
            $classes[] = 'mpb-texture-body';
        }

        if (!empty($settings['texture_apply_footer'])) {
            $classes[] = 'mpb-texture-footer';
        }

        if (!empty($settings['texture_apply_buttons'])) {
            $classes[] = 'mpb-texture-buttons';
        }
    }

    return $classes;
}
add_filter('body_class', 'mpb_theme_style_body_classes');

require_once get_template_directory() . '/inc/template-functions.php';