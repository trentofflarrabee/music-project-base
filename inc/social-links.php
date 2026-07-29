<?php
/**
 * Social-link rendering helpers.
 *
 * Core owns configured platform data. Base owns icon artwork and frontend
 * presentation.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get fallback platform definitions for older Core versions.
 *
 * These are used only when Core does not yet expose its normalized
 * mpc_get_social_links() helper.
 *
 * @return array
 */
function mpb_get_social_link_fallback_items() {
    return [
        'instagram' => [
            'label'    => __('Instagram', 'music-project-base'),
            'type'     => 'url',
            'external' => true,
        ],
        'spotify' => [
            'label'    => __('Spotify', 'music-project-base'),
            'type'     => 'url',
            'external' => true,
        ],
        'apple_music' => [
            'label'    => __('Apple Music', 'music-project-base'),
            'type'     => 'url',
            'external' => true,
        ],
        'bandcamp' => [
            'label'    => __('Bandcamp', 'music-project-base'),
            'type'     => 'url',
            'external' => true,
        ],
        'youtube' => [
            'label'    => __('YouTube', 'music-project-base'),
            'type'     => 'url',
            'external' => true,
        ],
        'tiktok' => [
            'label'    => __('TikTok', 'music-project-base'),
            'type'     => 'url',
            'external' => true,
        ],
        'soundcloud' => [
            'label'    => __('SoundCloud', 'music-project-base'),
            'type'     => 'url',
            'external' => true,
        ],
        'facebook' => [
            'label'    => __('Facebook', 'music-project-base'),
            'type'     => 'url',
            'external' => true,
        ],
        'website' => [
            'label'    => __('Website', 'music-project-base'),
            'type'     => 'url',
            'external' => true,
        ],
        'email' => [
            'label'    => __('Email', 'music-project-base'),
            'type'     => 'email',
            'external' => false,
        ],
    ];
}

/**
 * Get configured social links.
 *
 * Current Core versions supply the canonical normalized data. The fallback
 * preserves compatibility when Base is updated before Core.
 *
 * @param string $context Rendering context.
 * @return array
 */
function mpb_get_social_links($context = '') {
    $context = sanitize_key((string) $context);

    if (function_exists('mpc_get_social_links')) {
        $links = mpc_get_social_links($context);

        return is_array($links)
            ? $links
            : [];
    }

    if (!function_exists('mpc_get_social_links_setting')) {
        return [];
    }

    $links = [];

    foreach (
        mpb_get_social_link_fallback_items()
        as $key => $item
    ) {
        $value = trim(
            (string) mpc_get_social_links_setting(
                $key,
                ''
            )
        );

        if ($value === '') {
            continue;
        }

        if ($item['type'] === 'email') {
            $email = sanitize_email($value);

            if (
                $email === ''
                || !is_email($email)
            ) {
                continue;
            }

            $url = 'mailto:' . $email;
        } else {
            $url = esc_url_raw(
                $value,
                ['http', 'https']
            );

            if ($url === '') {
                continue;
            }
        }

        $links[] = [
            'key'      => $key,
            'label'    => $item['label'],
            'url'      => $url,
            'type'     => $item['type'],
            'external' => !empty($item['external']),
        ];
    }

    /**
     * Filter theme-ready social links.
     *
     * @param array  $links   Normalized social links.
     * @param string $context Rendering context.
     */
    return (array) apply_filters(
        'mpb_social_links',
        $links,
        $context
    );
}

/**
 * Get a validated social-link display mode.
 *
 * @param string $context   Rendering context.
 * @param string $requested Explicit requested mode.
 * @param string $default   Default mode.
 * @return string
 */
function mpb_get_social_display_mode(
    $context = '',
    $requested = '',
    $default = 'labels'
) {
    $allowed = [
        'labels',
        'icons',
        'icons_labels',
    ];

    $context = sanitize_key((string) $context);
    $requested = sanitize_key((string) $requested);
    $default = sanitize_key((string) $default);

    if (!in_array($default, $allowed, true)) {
        $default = 'labels';
    }

    if (in_array($requested, $allowed, true)) {
        return $requested;
    }

    if (function_exists('mpc_get_social_display_mode')) {
        $display = mpc_get_social_display_mode(
            $context,
            $default
        );
    } elseif (
        $context !== ''
        && function_exists('mpc_get_social_links_setting')
    ) {
        $display = sanitize_key(
            (string) mpc_get_social_links_setting(
                $context . '_display',
                $default
            )
        );
    } else {
        $display = $default;
    }

    return in_array($display, $allowed, true)
        ? $display
        : $default;
}

/**
 * Get one theme-owned social icon.
 *
 * Unknown extension platforms receive a generic link icon.
 *
 * @param string $key Platform key.
 * @return string
 */
function mpb_get_social_icon_svg($key) {
    $key = sanitize_key((string) $key);

    $svg_start = '<svg class="social-links__svg" width="20" height="20" viewBox="0 0 24 24" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg">';
    $svg_end = '</svg>';

    switch ($key) {
        case 'instagram':
            return $svg_start
                . '<rect x="4" y="4" width="16" height="16" rx="5" fill="none" stroke="currentColor" stroke-width="2"/>'
                . '<circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="2"/>'
                . '<circle cx="17" cy="7" r="1.2" fill="currentColor"/>'
                . $svg_end;

        case 'spotify':
            return $svg_start
                . '<circle cx="12" cy="12" r="9" fill="none" stroke="currentColor" stroke-width="2"/>'
                . '<path d="M7.5 10c3.4-1 6.6-.7 9.3.8" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>'
                . '<path d="M8.2 13c2.6-.7 5-.5 7.2.6" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>'
                . '<path d="M9 15.8c1.8-.4 3.6-.3 5.2.5" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>'
                . $svg_end;

        case 'apple_music':
            return $svg_start
                . '<path d="M9 18V7l10-2v11" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>'
                . '<circle cx="7" cy="18" r="2.6" fill="none" stroke="currentColor" stroke-width="2"/>'
                . '<circle cx="17" cy="16" r="2.6" fill="none" stroke="currentColor" stroke-width="2"/>'
                . $svg_end;

        case 'bandcamp':
            return $svg_start
                . '<path d="M4 16l5-8h11l-5 8H4z" fill="currentColor"/>'
                . $svg_end;

        case 'youtube':
            return $svg_start
                . '<rect x="3" y="6.5" width="18" height="11" rx="3" fill="none" stroke="currentColor" stroke-width="2"/>'
                . '<path d="M10.5 9.5v5l4.5-2.5-4.5-2.5z" fill="currentColor"/>'
                . $svg_end;

        case 'tiktok':
            return $svg_start
                . '<path d="M14 4v10.2a4 4 0 1 1-3.4-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>'
                . '<path d="M14 4c.8 2.6 2.5 4.2 5 4.7" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>'
                . $svg_end;

        case 'soundcloud':
            return $svg_start
                . '<path d="M5 16h12.4a3.1 3.1 0 0 0 .2-6.2A5.1 5.1 0 0 0 8 8.3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>'
                . '<path d="M4 14v2M7 11v5M10 9v7" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>'
                . $svg_end;

        case 'facebook':
            return $svg_start
                . '<path d="M14 8h2.2V5H14c-2.7 0-4 1.7-4 4v2H8v3h2v6h3v-6h2.5l.5-3h-3V9.2c0-.8.3-1.2 1-1.2z" fill="currentColor"/>'
                . $svg_end;

        case 'website':
            return $svg_start
                . '<circle cx="12" cy="12" r="9" fill="none" stroke="currentColor" stroke-width="2"/>'
                . '<path d="M3 12h18M12 3c2.2 2.5 3.3 5.5 3.3 9S14.2 18.5 12 21M12 3C9.8 5.5 8.7 8.5 8.7 12S9.8 18.5 12 21" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>'
                . $svg_end;

        case 'email':
            return $svg_start
                . '<rect x="3.5" y="6" width="17" height="12" rx="2" fill="none" stroke="currentColor" stroke-width="2"/>'
                . '<path d="M5 8l7 5 7-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>'
                . $svg_end;

        default:
            return $svg_start
                . '<circle cx="12" cy="12" r="9" fill="none" stroke="currentColor" stroke-width="2"/>'
                . '<path d="M8 12h8M12 8v8" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>'
                . $svg_end;
    }
}

/**
 * Get the limited HTML allowed in theme-owned SVG icons.
 *
 * @return array
 */
function mpb_get_social_icon_allowed_html() {
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
            'x'            => true,
            'y'            => true,
            'width'        => true,
            'height'       => true,
            'rx'           => true,
            'fill'         => true,
            'stroke'       => true,
            'stroke-width' => true,
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
        ],
    ];
}