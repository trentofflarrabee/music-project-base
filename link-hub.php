<?php
/**
 * Link Hub Template
 *
 * Music Project Core owns Link Hub data.
 * Music Project Base owns frontend presentation.
 */

if (!defined('ABSPATH')) {
    exit;
}

/*
 * Defensive fallback. If Core disappears or this template is reached outside
 * the assigned Link Hub request, use ordinary Page presentation instead.
 */
if (
    !function_exists('mpb_is_link_hub_request')
    || !mpb_is_link_hub_request()
) {
    include get_template_directory() . '/page.php';

    return;
}

$settings = function_exists(
    'mpc_get_link_hub_settings'
)
    ? mpc_get_link_hub_settings()
    : [];

$items = function_exists(
    'mpc_get_link_hub_items'
)
    ? mpc_get_link_hub_items()
    : [];

$display_name =
    mpb_get_link_hub_display_name();

$tagline =
    mpb_get_link_hub_tagline();

$profile_image =
    mpb_get_link_hub_profile_image_html();

$social_links =
    mpb_get_link_hub_social_links();

$show_footer_brand = (
    isset($settings['show_footer_brand'])
    && !empty($settings['show_footer_brand'])
);

$layout = isset($settings['layout'])
    ? sanitize_key(
        (string) $settings['layout']
    )
    : 'spotlight';

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

$site_host = wp_parse_url(
    home_url('/'),
    PHP_URL_HOST
);

if (
    !is_string($site_host)
    || $site_host === ''
) {
    $site_host = get_bloginfo('name');
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

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<main
    id="site-main"
    class="mpb-link-hub__main"
>
    <div
        class="mpb-link-hub__shell mpb-link-hub__shell--<?php echo esc_attr($layout); ?>"
    >
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : ?>
                <?php the_post(); ?>

                <header class="mpb-link-hub__identity">
                    <?php if ($profile_image) : ?>
                        <div class="mpb-link-hub__profile">
                            <?php
                            echo wp_kses_post(
                                $profile_image
                            );
                            ?>
                        </div>
                    <?php endif; ?>

                    <h1 class="mpb-link-hub__title">
                        <?php
                        echo esc_html(
                            $display_name
                        );
                        ?>
                    </h1>

                    <?php if ($tagline) : ?>
                        <p class="mpb-link-hub__tagline">
                            <?php
                            echo esc_html(
                                $tagline
                            );
                            ?>
                        </p>
                    <?php endif; ?>
                </header>

                <div class="mpb-link-hub__content">
                    <?php if ($items) : ?>
                        <?php foreach ($items as $item) : ?>

                            <?php
                            if (
                                !is_array($item)
                                || empty($item['type'])
                            ) {
                                continue;
                            }
                            ?>

                            <?php if ($item['type'] === 'section') : ?>
                                <?php
                                $section_label =
                                    isset($item['label'])
                                        ? trim(
                                            (string) $item['label']
                                        )
                                        : '';

                                if ($section_label === '') {
                                    continue;
                                }
                                ?>

                                <h2 class="mpb-link-hub__section-heading">
                                    <?php
                                    echo esc_html(
                                        $section_label
                                    );
                                    ?>
                                </h2>

                                <?php continue; ?>
                            <?php endif; ?>

                            <?php
                            if ($item['type'] !== 'link') {
                                continue;
                            }

                            $label = isset($item['label'])
                                ? trim(
                                    (string) $item['label']
                                )
                                : '';

                            $subtitle = isset($item['subtitle'])
                                ? trim(
                                    (string) $item['subtitle']
                                )
                                : '';

                            $url = isset($item['url'])
                                ? (string) $item['url']
                                : '';

                            if (
                                $label === ''
                                || $url === ''
                            ) {
                                continue;
                            }

                            $icon = isset($item['icon'])
                                ? sanitize_key(
                                    (string) $item['icon']
                                )
                                : 'link';

                            $variant = isset($item['variant'])
                                ? sanitize_key(
                                    (string) $item['variant']
                                )
                                : 'standard';

                            if (
                                !in_array(
                                    $variant,
                                    [
                                        'standard',
                                        'featured',
                                    ],
                                    true
                                )
                            ) {
                                $variant = 'standard';
                            }

                            $scheme = strtolower(
                                (string) wp_parse_url(
                                    $url,
                                    PHP_URL_SCHEME
                                )
                            );

                            $open_new_window = (
                                !empty($item['new_window'])
                                && in_array(
                                    $scheme,
                                    [
                                        'http',
                                        'https',
                                    ],
                                    true
                                )
                            );

                            $is_external =
                                mpb_is_link_hub_external_url(
                                    $url
                                );
                            ?>

                            <a
                                class="mpb-link-hub__link mpb-link-hub__link--<?php echo esc_attr($variant); ?>"
                                href="<?php echo esc_url($url); ?>"
                                <?php if ($open_new_window) : ?>
                                    target="_blank"
                                    rel="noopener noreferrer"
                                <?php endif; ?>
                            >
                                <span
                                    class="mpb-link-hub__link-icon"
                                    aria-hidden="true"
                                >
                                    <?php
                                    echo wp_kses(
                                        mpb_get_link_hub_icon_svg(
                                            $icon
                                        ),
                                        mpb_get_link_hub_icon_allowed_html()
                                    );
                                    ?>
                                </span>

                                <span class="mpb-link-hub__link-copy">
                                    <span class="mpb-link-hub__link-label">
                                        <?php
                                        echo esc_html(
                                            $label
                                        );
                                        ?>
                                    </span>

                                    <?php if ($subtitle) : ?>
                                        <span class="mpb-link-hub__link-subtitle">
                                            <?php
                                            echo esc_html(
                                                $subtitle
                                            );
                                            ?>
                                        </span>
                                    <?php endif; ?>
                                </span>

                                <?php if ($is_external) : ?>
                                    <span
                                        class="mpb-link-hub__external"
                                        aria-hidden="true"
                                    >
                                        <?php
                                        echo wp_kses(
                                            mpb_get_link_hub_icon_svg(
                                                'external'
                                            ),
                                            mpb_get_link_hub_icon_allowed_html()
                                        );
                                        ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        <?php endforeach; ?>

                    <?php else : ?>

                        <p class="mpb-link-hub__empty">
                            <?php
                            esc_html_e(
                                'Links coming soon.',
                                'music-project-base'
                            );
                            ?>
                        </p>

                    <?php endif; ?>
                </div>

                <?php if ($social_links) : ?>
                    <nav
                        class="mpb-link-hub__social"
                        aria-label="<?php esc_attr_e('Social links', 'music-project-base'); ?>"
                    >
                        <ul class="mpb-link-hub__social-list">
                            <?php foreach ($social_links as $social_link) : ?>
                                <?php
                                if (
                                    !is_array($social_link)
                                    || empty($social_link['key'])
                                    || empty($social_link['label'])
                                    || empty($social_link['url'])
                                ) {
                                    continue;
                                }

                                $social_key =
                                    sanitize_key(
                                        (string)
                                            $social_link['key']
                                    );

                                $social_label =
                                    sanitize_text_field(
                                        (string)
                                            $social_link['label']
                                    );

                                $social_url =
                                    (string)
                                        $social_link['url'];

                                $social_external =
                                    !empty(
                                        $social_link['external']
                                    );
                                ?>

                                <li class="mpb-link-hub__social-item">
                                    <a
                                        class="mpb-link-hub__social-link"
                                        href="<?php echo esc_url($social_url); ?>"
                                        aria-label="<?php echo esc_attr($social_label); ?>"
                                        <?php if ($social_external) : ?>
                                            target="_blank"
                                            rel="noopener noreferrer"
                                        <?php endif; ?>
                                    >
                                        <span aria-hidden="true">
                                            <?php
                                            if (
                                                function_exists(
                                                    'mpb_get_social_icon_svg'
                                                )
                                                && function_exists(
                                                    'mpb_get_social_icon_allowed_html'
                                                )
                                            ) {
                                                echo wp_kses(
                                                    mpb_get_social_icon_svg(
                                                        $social_key
                                                    ),
                                                    mpb_get_social_icon_allowed_html()
                                                );
                                            }
                                            ?>
                                        </span>

                                        <span class="screen-reader-text">
                                            <?php
                                            echo esc_html(
                                                $social_label
                                            );
                                            ?>
                                        </span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </nav>
                <?php endif; ?>

                <?php if ($show_footer_brand) : ?>
                    <footer class="mpb-link-hub__footer">
                        <a
                            class="mpb-link-hub__brand-link"
                            href="<?php echo esc_url(home_url('/')); ?>"
                        >
                            <?php
                            echo esc_html(
                                $site_host
                            );
                            ?>
                        </a>
                    </footer>
                <?php endif; ?>

            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</main>

<?php wp_footer(); ?>
</body>
</html>