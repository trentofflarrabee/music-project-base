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

/**
 * Get the Link Hub display name.
 *
 * Core owns the optional override. Base falls back to the WordPress site
 * title for presentation.
 *
 * @return string
 */
function mpb_get_link_hub_display_name() {
    $display_name = '';

    if (function_exists('mpc_get_link_hub_setting')) {
        $display_name = trim(
            (string) mpc_get_link_hub_setting(
                'display_name',
                ''
            )
        );
    }

    if ($display_name === '') {
        $display_name = trim(
            (string) get_bloginfo('name')
        );
    }

    /*
     * Maintain a usable primary heading even on a site with no configured
     * Site Title.
     */
    if (
        $display_name === ''
        && function_exists('mpc_get_link_hub_page_id')
    ) {
        $page_id = absint(
            mpc_get_link_hub_page_id()
        );

        if ($page_id) {
            $display_name = trim(
                (string) get_the_title($page_id)
            );
        }
    }

    return sanitize_text_field(
        $display_name
    );
}

/**
 * Get the Link Hub tagline.
 *
 * @return string
 */
function mpb_get_link_hub_tagline() {
    if (!function_exists('mpc_get_link_hub_setting')) {
        return '';
    }

    return sanitize_text_field(
        trim(
            (string) mpc_get_link_hub_setting(
                'tagline',
                ''
            )
        )
    );
}

/**
 * Resolve the Link Hub profile image attachment ID.
 *
 * Auto uses the site's Custom Logo.
 * Custom uses Core's selected attachment.
 * None intentionally returns no image.
 *
 * @return int
 */
function mpb_get_link_hub_profile_image_id() {
    if (!function_exists('mpc_get_link_hub_setting')) {
        return 0;
    }

    $mode = sanitize_key(
        (string) mpc_get_link_hub_setting(
            'profile_image_mode',
            'auto'
        )
    );

    if ($mode === 'none') {
        return 0;
    }

    if ($mode === 'custom') {
        $attachment_id = absint(
            mpc_get_link_hub_setting(
                'profile_image_id',
                0
            )
        );

        return (
            $attachment_id
            && wp_attachment_is_image($attachment_id)
        )
            ? $attachment_id
            : 0;
    }

    /*
     * Unknown values fall back to Auto.
     */
    $attachment_id = absint(
        get_theme_mod(
            'custom_logo',
            0
        )
    );

    return (
        $attachment_id
        && wp_attachment_is_image($attachment_id)
    )
        ? $attachment_id
        : 0;
}

/**
 * Get the rendered Link Hub profile image.
 *
 * WordPress handles responsive srcset/sizes and the attachment's configured
 * alt text.
 *
 * @return string
 */
function mpb_get_link_hub_profile_image_html() {
    $attachment_id =
        mpb_get_link_hub_profile_image_id();

    if (!$attachment_id) {
        return '';
    }

    $image = wp_get_attachment_image(
        $attachment_id,
        'medium_large',
        false,
        [
            'class' =>
                'mpb-link-hub__profile-image',
            'loading' =>
                'eager',
            'decoding' =>
                'async',
        ]
    );

    return is_string($image)
        ? $image
        : '';
}

/**
 * Determine whether a Link Hub URL points away from this WordPress site.
 *
 * mailto: and tel: destinations are not treated as external web URLs.
 *
 * @param mixed $url Destination URL.
 * @return bool
 */
function mpb_is_link_hub_external_url($url) {
    if (!is_scalar($url)) {
        return false;
    }

    $url = (string) $url;

    $scheme = strtolower(
        (string) wp_parse_url(
            $url,
            PHP_URL_SCHEME
        )
    );

    if (
        !in_array(
            $scheme,
            [
                'http',
                'https',
            ],
            true
        )
    ) {
        return false;
    }

    $target_host = strtolower(
        (string) wp_parse_url(
            $url,
            PHP_URL_HOST
        )
    );

    $site_host = strtolower(
        (string) wp_parse_url(
            home_url('/'),
            PHP_URL_HOST
        )
    );

    if (
        $target_host === ''
        || $site_host === ''
    ) {
        return false;
    }

    return $target_host !== $site_host;
}

/**
 * Get one curated Link Hub icon.
 *
 * Icon keys come from Core's allowlist. Base owns all actual SVG markup.
 *
 * @param mixed $key Icon key.
 * @return string
 */
function mpb_get_link_hub_icon_svg($key) {
    $key = sanitize_key(
        (string) $key
    );

    $start =
        '<svg class="mpb-link-hub__icon-svg" width="24" height="24" viewBox="0 0 24 24" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg">';

    $end = '</svg>';

    switch ($key) {
        case 'music':
            return $start
                . '<path d="M9 18V6l10-2v12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>'
                . '<circle cx="6.5" cy="18" r="2.5" fill="none" stroke="currentColor" stroke-width="2"/>'
                . '<circle cx="16.5" cy="16" r="2.5" fill="none" stroke="currentColor" stroke-width="2"/>'
                . $end;

        case 'play':
            return $start
                . '<circle cx="12" cy="12" r="9" fill="none" stroke="currentColor" stroke-width="2"/>'
                . '<path d="M10 8.5v7l6-3.5-6-3.5z" fill="currentColor"/>'
                . $end;

        case 'video':
            return $start
                . '<rect x="3" y="5" width="14" height="14" rx="2" fill="none" stroke="currentColor" stroke-width="2"/>'
                . '<path d="M17 9l4-2v10l-4-2V9z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>'
                . $end;

        case 'ticket':
            return $start
                . '<path d="M4 7h16v3a2 2 0 0 0 0 4v3H4v-3a2 2 0 0 0 0-4V7z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>'
                . '<path d="M12 8.5v7" fill="none" stroke="currentColor" stroke-width="2" stroke-dasharray="2 2"/>'
                . $end;

        case 'shop':
            return $start
                . '<path d="M5 9h14l-1 11H6L5 9z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>'
                . '<path d="M9 10V7a3 3 0 0 1 6 0v3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>'
                . $end;

        case 'merch':
            return $start
                . '<path d="M8 5l4 2 4-2 4 3-2.5 4-2-1v9h-7v-9l-2 1L4 8l4-3z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>'
                . $end;

        case 'newsletter':
            return $start
                . '<rect x="3" y="6" width="18" height="12" rx="2" fill="none" stroke="currentColor" stroke-width="2"/>'
                . '<path d="M5 8l7 5 7-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>'
                . $end;

        case 'download':
            return $start
                . '<path d="M12 4v11M8 11l4 4 4-4M5 20h14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>'
                . $end;

        case 'calendar':
            return $start
                . '<rect x="3.5" y="5.5" width="17" height="15" rx="2" fill="none" stroke="currentColor" stroke-width="2"/>'
                . '<path d="M7 3.5v4M17 3.5v4M4 10h16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>'
                . $end;

        case 'heart':
            return $start
                . '<path d="M12 20S4 15.5 4 9.5A4.5 4.5 0 0 1 12 7a4.5 4.5 0 0 1 8 2.5C20 15.5 12 20 12 20z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>'
                . $end;

        case 'star':
            return $start
                . '<path d="M12 3l2.7 5.5 6.1.9-4.4 4.3 1 6-5.4-2.9-5.4 2.9 1-6-4.4-4.3 6.1-.9L12 3z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>'
                . $end;

        case 'external':
            return $start
                . '<path d="M14 5h5v5M19 5l-8 8M17 13v6H5V7h6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>'
                . $end;

        case 'link':
        default:
            return $start
                . '<path d="M10 13a4 4 0 0 0 5.7 0l2.3-2.3a4 4 0 0 0-5.7-5.7L11 6.3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>'
                . '<path d="M14 11a4 4 0 0 0-5.7 0L6 13.3A4 4 0 0 0 11.7 19l1.3-1.3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>'
                . $end;
    }
}

/**
 * Get the restricted HTML allowlist used for Link Hub icons.
 *
 * @return array
 */
function mpb_get_link_hub_icon_allowed_html() {
    if (function_exists('mpb_get_social_icon_allowed_html')) {
        return mpb_get_social_icon_allowed_html();
    }

    return [
        'svg' => [
            'class'       => true,
            'width'       => true,
            'height'      => true,
            'viewbox'     => true,
            'aria-hidden' => true,
            'focusable'   => true,
            'xmlns'       => true,
        ],
        'rect' => [
            'x'             => true,
            'y'             => true,
            'width'         => true,
            'height'        => true,
            'rx'            => true,
            'fill'          => true,
            'stroke'        => true,
            'stroke-width'  => true,
        ],
        'circle' => [
            'cx'           => true,
            'cy'           => true,
            'r'            => true,
            'fill'         => true,
            'stroke'       => true,
            'stroke-width' => true,
        ],
        'path' => [
            'd'               => true,
            'fill'            => true,
            'stroke'          => true,
            'stroke-width'    => true,
            'stroke-linecap'  => true,
            'stroke-linejoin' => true,
            'stroke-dasharray'=> true,
        ],
    ];
}

/**
 * Get configured Social Links for the Link Hub.
 *
 * @return array
 */
function mpb_get_link_hub_social_links() {
    if (
        !function_exists('mpc_get_link_hub_setting')
        || !mpc_get_link_hub_setting(
            'show_social_links',
            true
        )
    ) {
        return [];
    }

    if (!function_exists('mpb_get_social_links')) {
        return [];
    }

    return mpb_get_social_links(
        'link_hub'
    );
}