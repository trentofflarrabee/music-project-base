<?php
/**
 * Social Links Template Part
 *
 * Args:
 *
 * - context: hero|footer|navigation|status
 * - display: labels|icons|icons_labels
 */

if (!defined('ABSPATH')) {
    exit;
}

$context = isset($args['context'])
    ? sanitize_key((string) $args['context'])
    : 'general';

$requested_display = isset($args['display'])
    ? sanitize_key((string) $args['display'])
    : '';

$default_display = in_array(
    $context,
    ['navigation', 'status'],
    true
)
    ? 'icons'
    : 'labels';

$display = function_exists('mpb_get_social_display_mode')
    ? mpb_get_social_display_mode(
        $context,
        $requested_display,
        $default_display
    )
    : $default_display;

$links = function_exists('mpb_get_social_links')
    ? mpb_get_social_links($context)
    : [];

if (!$links) {
    return;
}

$display_class = 'social-links--'
    . str_replace('_', '-', $display);

$context_class = 'social-links--context-'
    . sanitize_html_class($context);

$show_icons = in_array(
    $display,
    ['icons', 'icons_labels'],
    true
);

$show_labels = in_array(
    $display,
    ['labels', 'icons_labels'],
    true
);

$classes = [
    'social-links',
    $display_class,
    $context_class,
];
?>

<ul
    class="<?php echo esc_attr(implode(' ', $classes)); ?>"
    aria-label="<?php esc_attr_e('Social links', 'music-project-base'); ?>"
>
    <?php foreach ($links as $link) : ?>
        <?php
        $key = sanitize_key(
            (string) ($link['key'] ?? '')
        );

        $label = sanitize_text_field(
            (string) ($link['label'] ?? '')
        );

        $url = esc_url(
            (string) ($link['url'] ?? '')
        );

        $external = !empty($link['external']);

        if (
            $key === ''
            || $label === ''
            || $url === ''
        ) {
            continue;
        }
        ?>

        <li
            class="social-links__item social-links__item--<?php echo esc_attr($key); ?>"
        >
            <a
                class="social-links__link"
                href="<?php echo $url; ?>"
                <?php if ($external) : ?>
                    target="_blank"
                    rel="external noopener noreferrer"
                <?php endif; ?>
            >
                <?php if ($show_icons) : ?>
                    <span
                        class="social-links__icon"
                        aria-hidden="true"
                    >
                        <?php
                        echo wp_kses(
                            mpb_get_social_icon_svg($key),
                            mpb_get_social_icon_allowed_html()
                        );
                        ?>
                    </span>
                <?php endif; ?>

                <span
                    class="social-links__label<?php echo $show_labels ? '' : ' screen-reader-text'; ?>"
                >
                    <?php echo esc_html($label); ?>
                </span>

                <?php if ($external) : ?>
                    <span class="screen-reader-text">
                        <?php
                        esc_html_e(
                            ' (opens in a new tab)',
                            'music-project-base'
                        );
                        ?>
                    </span>
                <?php endif; ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>