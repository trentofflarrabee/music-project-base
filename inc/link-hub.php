<?php
/**
 * Link Hub frontend integration.
 *
 * Music Project Core owns Link Hub configuration and normalized data.
 * Music Project Base owns presentation and template routing.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Determine whether the current request should use the Link Hub presentation.
 *
 * Base never reads the raw Core option directly. If Core is inactive or its
 * public API is unavailable, this returns false and WordPress continues with
 * ordinary Page rendering.
 *
 * @return bool
 */
function mpb_is_link_hub_request() {
    if (
        !function_exists('mpc_is_link_hub_enabled')
        || !function_exists('mpc_get_link_hub_page_id')
    ) {
        return false;
    }

    if (!mpc_is_link_hub_enabled()) {
        return false;
    }

    $page_id = absint(
        mpc_get_link_hub_page_id()
    );

    if (!$page_id) {
        return false;
    }

    if (
        !is_singular('page')
        || !is_page($page_id)
    ) {
        return false;
    }

    return true;
}

/**
 * Use Base's dedicated Link Hub template for the assigned Page.
 *
 * A child theme may provide its own link-hub.php. Otherwise Base's parent
 * template is used.
 *
 * @param string $template Resolved WordPress template path.
 * @return string
 */
function mpb_link_hub_template_include($template) {
    if (!mpb_is_link_hub_request()) {
        return $template;
    }

    $link_hub_template = locate_template(
        [
            'link-hub.php',
        ],
        false,
        false
    );

    if (
        !is_string($link_hub_template)
        || $link_hub_template === ''
    ) {
        return $template;
    }

    return $link_hub_template;
}

add_filter(
    'template_include',
    'mpb_link_hub_template_include',
    50
);

/**
 * Add Link Hub-specific body classes.
 *
 * @param array $classes Existing body classes.
 * @return array
 */
function mpb_link_hub_body_classes($classes) {
    if (!mpb_is_link_hub_request()) {
        return $classes;
    }

    $classes[] = 'mpb-link-hub';

    if (function_exists('mpc_get_link_hub_setting')) {
        $layout = sanitize_key(
            (string) mpc_get_link_hub_setting(
                'layout',
                'spotlight'
            )
        );

        if (
            !in_array(
                $layout,
                [
                    'spotlight',
                    'stack',
                    'poster',
                ],
                true
            )
        ) {
            $layout = 'spotlight';
        }

        $classes[] =
            'mpb-link-hub-layout-' . $layout;
    }

    return array_values(
        array_unique($classes)
    );
}

add_filter(
    'body_class',
    'mpb_link_hub_body_classes'
);