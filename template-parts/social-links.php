<?php
/**
 * Social Links Template Part
 *
 * Args:
 * - context: hero|footer
 * - display: labels|icons|icons_labels
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('mpb_get_social_icon_svg')) {
    function mpb_get_social_icon_svg($key) {
        $svg_start = '<svg class="social-links__svg" width="20" height="20" viewBox="0 0 24 24" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg">';
        $svg_end = '</svg>';

        switch ($key) {
            case 'instagram':
                return $svg_start .
                    '<rect x="4" y="4" width="16" height="16" rx="5" fill="none" stroke="currentColor" stroke-width="2"/>' .
                    '<circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="2"/>' .
                    '<circle cx="17" cy="7" r="1.2" fill="currentColor"/>' .
                $svg_end;

            case 'spotify':
                return $svg_start .
                    '<circle cx="12" cy="12" r="9" fill="none" stroke="currentColor" stroke-width="2"/>' .
                    '<path d="M7.5 10c3.4-1 6.6-.7 9.3.8" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>' .
                    '<path d="M8.2 13c2.6-.7 5-.5 7.2.6" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>' .
                    '<path d="M9 15.8c1.8-.4 3.6-.3 5.2.5" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>' .
                $svg_end;

            case 'apple_music':
                return $svg_start .
                    '<path d="M9 18V7l10-2v11" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>' .
                    '<circle cx="7" cy="18" r="2.6" fill="none" stroke="currentColor" stroke-width="2"/>' .
                    '<circle cx="17" cy="16" r="2.6" fill="none" stroke="currentColor" stroke-width="2"/>' .
                $svg_end;

            case 'bandcamp':
                return $svg_start .
                    '<path d="M4 16l5-8h11l-5 8H4z" fill="currentColor"/>' .
                $svg_end;

            case 'youtube':
                return $svg_start .
                    '<rect x="3" y="6.5" width="18" height="11" rx="3" fill="none" stroke="currentColor" stroke-width="2"/>' .
                    '<path d="M10.5 9.5v5l4.5-2.5-4.5-2.5z" fill="currentColor"/>' .
                $svg_end;

            case 'tiktok':
                return $svg_start .
                    '<path d="M14 4v10.2a4 4 0 1 1-3.4-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>' .
                    '<path d="M14 4c.8 2.6 2.5 4.2 5 4.7" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>' .
                $svg_end;

            case 'soundcloud':
                return $svg_start .
                    '<path d="M5 16h12.4a3.1 3.1 0 0 0 .2-6.2A5.1 5.1 0 0 0 8 8.3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>' .
                    '<path d="M4 14v2M7 11v5M10 9v7" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>' .
                $svg_end;

            case 'facebook':
                return $svg_start .
                    '<path d="M14 8h2.2V5H14c-2.7 0-4 1.7-4 4v2H8v3h2v6h3v-6h2.5l.5-3h-3V9.2c0-.8.3-1.2 1-1.2z" fill="currentColor"/>' .
                $svg_end;

            case 'website':
                return $svg_start .
                    '<circle cx="12" cy="12" r="9" fill="none" stroke="currentColor" stroke-width="2"/>' .
                    '<path d="M3 12h18M12 3c2.2 2.5 3.3 5.5 3.3 9S14.2 18.5 12 21M12 3C9.8 5.5 8.7 8.5 8.7 12S9.8 18.5 12 21" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>' .
                $svg_end;

            case 'email':
                return $svg_start .
                    '<rect x="3.5" y="6" width="17" height="12" rx="2" fill="none" stroke="currentColor" stroke-width="2"/>' .
                    '<path d="M5 8l7 5 7-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>' .
                $svg_end;

            default:
                return $svg_start .
                    '<circle cx="12" cy="12" r="9" fill="none" stroke="currentColor" stroke-width="2"/>' .
                    '<path d="M8 12h8" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>' .
                $svg_end;
        }
    }
}

$items = [
    'instagram' => 'Instagram',
    'spotify' => 'Spotify',
    'apple_music' => 'Apple Music',
    'bandcamp' => 'Bandcamp',
    'youtube' => 'YouTube',
    'tiktok' => 'TikTok',
    'soundcloud' => 'SoundCloud',
    'facebook' => 'Facebook',
    'website' => 'Website',
    'email' => 'Email',
];

$context = isset($args['context']) ? sanitize_key($args['context']) : '';
$display = isset($args['display']) ? sanitize_key($args['display']) : '';

if (!$display && $context && function_exists('mpc_get_social_links_setting')) {
    $display = mpc_get_social_links_setting($context . '_display', 'labels');
}

$allowed_displays = ['labels', 'icons', 'icons_labels'];

if (!in_array($display, $allowed_displays, true)) {
    $display = 'labels';
}

$links = [];

foreach ($items as $key => $label) {
    if (!function_exists('mpc_get_social_links_setting')) {
        continue;
    }

    $value = trim((string) mpc_get_social_links_setting($key));

    if (!$value) {
        continue;
    }

    if ($key === 'email') {
        $url = 'mailto:' . antispambot($value);
    } else {
        $url = $value;
    }

    $links[] = [
        'key' => $key,
        'label' => $label,
        'url' => $url,
    ];
}

if (!$links) {
    return;
}

$display_class = 'social-links--' . str_replace('_', '-', $display);
$show_icons = in_array($display, ['icons', 'icons_labels'], true);
$show_labels = in_array($display, ['labels', 'icons_labels'], true);
?>

<ul class="social-links <?php echo esc_attr($display_class); ?>">
    <?php foreach ($links as $link) : ?>
        <li class="social-links__item social-links__item--<?php echo esc_attr($link['key']); ?>">
            <a
                class="social-links__link"
                href="<?php echo esc_url($link['url']); ?>"
                <?php echo $link['key'] === 'email' ? '' : 'target="_blank" rel="noopener noreferrer"'; ?>
                aria-label="<?php echo esc_attr($link['label']); ?>"
            >
                <?php if ($show_icons) : ?>
                    <span class="social-links__icon" aria-hidden="true">
                        <?php echo mpb_get_social_icon_svg($link['key']); ?>
                    </span>
                <?php endif; ?>

                <span class="social-links__label <?php echo $show_labels ? '' : 'screen-reader-text'; ?>">
                    <?php echo esc_html($link['label']); ?>
                </span>
            </a>
        </li>
    <?php endforeach; ?>
</ul>