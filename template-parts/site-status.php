<?php
/**
 * Site Status template.
 *
 * Used for Coming Soon / Parking / Maintenance Mode.
 */

if (!defined('ABSPATH')) {
    exit;
}

$template_args = isset($args) && is_array($args)
    ? $args
    : [];

$settings = isset($template_args['settings'])
    && is_array($template_args['settings'])
        ? $template_args['settings']
        : [];

if (
    !$settings
    && function_exists('mpc_get_site_status_settings')
) {
    $settings = mpc_get_site_status_settings();
}

$mode = sanitize_key(
    $template_args['mode']
        ?? ($settings['mode'] ?? 'coming_soon')
);

if (
    !in_array(
        $mode,
        ['coming_soon', 'maintenance'],
        true
    )
) {
    $mode = 'coming_soon';
}

$previewing = array_key_exists(
    'previewing',
    $template_args
)
    ? !empty($template_args['previewing'])
    : (
        function_exists('mpc_is_site_status_preview')
        && mpc_is_site_status_preview()
    );

$heading = trim(
    (string) ($settings['heading'] ?? '')
);

if ($heading === '') {
    $heading = $mode === 'maintenance'
        ? __(
            'Maintenance Mode',
            'music-project-base'
        )
        : __(
            'Coming Soon',
            'music-project-base'
        );
}

$message = trim(
    (string) ($settings['message'] ?? '')
);

if ($message === '') {
    $message = __(
        'We’re getting things ready. Check back soon.',
        'music-project-base'
    );
}

$button_text = trim(
    (string) ($settings['button_text'] ?? '')
);

$button_url = trim(
    (string) ($settings['button_url'] ?? '')
);

$show_social_links = !empty(
    $settings['show_social_links']
);

$body_classes = [
    'site-status-page',
    'site-status-page--'
        . sanitize_html_class(
            str_replace('_', '-', $mode)
        ),
];

if ($previewing) {
    $body_classes[] = 'site-status-page--previewing';
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >
    <meta
        name="robots"
        content="noindex,nofollow,noarchive"
    >
    <?php wp_head(); ?>
</head>

<body <?php body_class($body_classes); ?>>
    <?php wp_body_open(); ?>

    <?php if ($previewing) : ?>
        <div
            class="site-status-preview-bar"
            role="status"
        >
            <span>
                <?php
                esc_html_e(
                    'Previewing Site Status page',
                    'music-project-base'
                );
                ?>
            </span>

            <a href="<?php echo esc_url(home_url('/')); ?>">
                <?php
                esc_html_e(
                    'Exit Preview',
                    'music-project-base'
                );
                ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="site-status-screen">
        <main
            class="site-status-card"
            aria-labelledby="site-status-heading"
        >
            <div class="site-status-brand">
                <?php if (has_custom_logo()) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <a
                        class="site-status-brand__name"
                        href="<?php echo esc_url(home_url('/')); ?>"
                    >
                        <?php
                        echo esc_html(
                            get_bloginfo('name')
                        );
                        ?>
                    </a>
                <?php endif; ?>
            </div>

            <div class="site-status-content">
                <p class="site-status-eyebrow">
                    <?php
                    echo esc_html(
                        $mode === 'maintenance'
                            ? __(
                                'Temporarily Unavailable',
                                'music-project-base'
                            )
                            : __(
                                'Launching Soon',
                                'music-project-base'
                            )
                    );
                    ?>
                </p>

                <h1 id="site-status-heading">
                    <?php echo esc_html($heading); ?>
                </h1>

                <p class="site-status-message">
                    <?php
                    echo nl2br(
                        esc_html($message)
                    );
                    ?>
                </p>

                <?php if ($button_text && $button_url) : ?>
                    <a
                        class="button site-status-button"
                        href="<?php echo esc_url($button_url); ?>"
                    >
                        <?php echo esc_html($button_text); ?>
                    </a>
                <?php endif; ?>

                <?php if ($show_social_links) : ?>
                    <div class="site-status-socials">
                        <?php
                            get_template_part(
                                'template-parts/social-links',
                                null,
                                [
                                    'context' => 'status',
                                    'display' => 'icons',
                                ]
                            );
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <?php wp_footer(); ?>
</body>
</html>