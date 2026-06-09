<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * MVP list of homepage sections.
 * Later, this will be controlled from the WordPress admin.
 */
function mpb_get_home_sections() {
    return [
        'hero',
        'featured-content',
        'shows',
        'blog',
        'newsletter',
    ];
}

/**
 * Check whether a homepage section is enabled.
 * For now, all MVP sections are enabled.
 * Later, this will check saved admin settings.
 */
function mpb_is_home_section_enabled($section) {
    return in_array($section, mpb_get_home_sections(), true);
}

/**
 * Render a homepage section if enabled.
 */
function mpb_render_home_section($section) {
    if (!mpb_is_home_section_enabled($section)) {
        return;
    }

    get_template_part('template-parts/home/' . $section);
}