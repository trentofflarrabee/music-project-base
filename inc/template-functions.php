<?php
/**
 * Theme template helper functions.
 */

if (!defined('ABSPATH')) {
    exit;
}

function mpb_get_default_home_sections() {
    return [
        'hero',
        'featured-content',
        'services',
        'quotes',
        'shows',
        'blog',
        'newsletter',
    ];
}

function mpb_get_home_sections() {
    if (function_exists('mpc_get_homepage_section_order')) {
        return mpc_get_homepage_section_order();
    }

    return mpb_get_default_home_sections();
}

function mpb_is_home_section_enabled($section) {
    $section = sanitize_key($section);

    if (!in_array($section, mpb_get_default_home_sections(), true)) {
        return false;
    }

    if (function_exists('mpc_is_homepage_section_visible')) {
        return mpc_is_homepage_section_visible($section);
    }

    return true;
}

function mpb_render_home_section($section) {
    $section = sanitize_key($section);

    if (!mpb_is_home_section_enabled($section)) {
        return;
    }

    get_template_part('template-parts/home/' . $section);
}