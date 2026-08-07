<?php

/**
 * Theme template helper functions.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get the curated homepage sections owned by Music Project Base.
 *
 * These identifiers correspond to templates in template-parts/home/. The
 * list is intentionally explicit so external data cannot select arbitrary
 * template paths.
 *
 * @return string[]
 */
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

/**
 * Get a normalized homepage section order.
 *
 * Music Project Core is the canonical source when available. Base validates
 * that boundary so an older, newer, malformed, or customized Core response
 * cannot cause warnings or select arbitrary template paths.
 *
 * Missing built-in sections are appended in their default order so version
 * mismatches degrade predictably rather than producing an incomplete list.
 *
 * @return string[]
 */
function mpb_get_home_sections() {
    $default_sections =
        mpb_get_default_home_sections();

    if (
        !function_exists(
            'mpc_get_homepage_section_order'
        )
    ) {
        return $default_sections;
    }

    $sections =
        mpc_get_homepage_section_order();

    if (!is_array($sections)) {
        return $default_sections;
    }

    $normalized = [];

    foreach ($sections as $section) {
        if (!is_scalar($section)) {
            continue;
        }

        $section = sanitize_key(
            (string) $section
        );

        if (
            $section === ''
            || !in_array(
                $section,
                $default_sections,
                true
            )
            || in_array(
                $section,
                $normalized,
                true
            )
        ) {
            continue;
        }

        $normalized[] = $section;
    }

    /*
     * Retain every section Base knows how to render. Core remains responsible
     * for deciding whether an individual section is visible.
     */
    foreach (
        $default_sections
        as $section
    ) {
        if (
            !in_array(
                $section,
                $normalized,
                true
            )
        ) {
            $normalized[] = $section;
        }
    }

    return $normalized;
}

/**
 * Determine whether a Base homepage section should render.
 *
 * @param mixed $section Section identifier.
 * @return bool
 */
function mpb_is_home_section_enabled($section) {
    if (!is_scalar($section)) {
        return false;
    }

    $section = sanitize_key(
        (string) $section
    );

    if (
        $section === ''
        || !in_array(
            $section,
            mpb_get_default_home_sections(),
            true
        )
    ) {
        return false;
    }

    if (
        function_exists(
            'mpc_is_homepage_section_visible'
        )
    ) {
        return (bool)
            mpc_is_homepage_section_visible(
                $section
            );
    }

    return true;
}

/**
 * Render a curated Base homepage section.
 *
 * @param mixed $section Section identifier.
 * @return void
 */
function mpb_render_home_section($section) {
    if (!is_scalar($section)) {
        return;
    }

    $section = sanitize_key(
        (string) $section
    );

    if (
        $section === ''
        || !mpb_is_home_section_enabled(
            $section
        )
    ) {
        return;
    }

    get_template_part(
        'template-parts/home/' . $section
    );
}