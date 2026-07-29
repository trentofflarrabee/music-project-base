<?php
/**
 * Site Footer
 */

if (!defined('ABSPATH')) {
    exit;
}

$footer_layout = function_exists('mpc_get_footer_setting')
    ? sanitize_key(
        (string) mpc_get_footer_setting(
            'footer_layout',
            'simple'
        )
    )
    : 'simple';

if (
    !in_array(
        $footer_layout,
        ['simple', 'stacked', 'split'],
        true
    )
) {
    $footer_layout = 'simple';
}

$site_name = trim(
    (string) get_bloginfo('name')
);

$footer_tagline = function_exists('mpc_get_footer_setting')
    ? trim(
        (string) mpc_get_footer_setting(
            'footer_tagline',
            ''
        )
    )
    : '';

$default_copyright = sprintf(
    /* translators: 1: current year, 2: site name. */
    __(
        '© %1$s %2$s. All rights reserved.',
        'music-project-base'
    ),
    date_i18n('Y'),
    $site_name
);

$footer_copyright = function_exists('mpc_get_footer_setting')
    ? trim(
        (string) mpc_get_footer_setting(
            'footer_copyright',
            '© {year} {site_name}. All rights reserved.'
        )
    )
    : $default_copyright;

if (
    $footer_copyright !== ''
    && function_exists('mpc_parse_footer_tokens')
) {
    $footer_copyright = mpc_parse_footer_tokens(
        $footer_copyright
    );
}

$show_brand = function_exists('mpc_get_footer_setting')
    ? (bool) mpc_get_footer_setting(
        'footer_show_brand',
        1
    )
    : true;

$show_menu = function_exists('mpc_get_footer_setting')
    ? (bool) mpc_get_footer_setting(
        'footer_show_menu',
        1
    )
    : true;

$show_socials = function_exists('mpc_get_footer_setting')
    ? (bool) mpc_get_footer_setting(
        'footer_show_socials',
        1
    )
    : true;

$has_brand_identity = (
    has_custom_logo()
    || $site_name !== ''
);

$has_footer_brand = (
    $show_brand
    && (
        $has_brand_identity
        || $footer_tagline !== ''
    )
);

$has_footer_menu = (
    $show_menu
    && has_nav_menu('footer')
);

/*
 * Capture the shared social-links template so an empty social configuration
 * does not leave an empty layout column in the footer.
 */
$footer_socials = '';

if ($show_socials) {
    ob_start();

    get_template_part(
        'template-parts/social-links',
        null,
        [
            'context' => 'footer',
        ]
    );

    $footer_socials = trim(
        (string) ob_get_clean()
    );
}

$has_footer_socials = $footer_socials !== '';

$has_footer_connections = (
    $has_footer_menu
    || $has_footer_socials
);

$has_footer_content = (
    $has_footer_brand
    || $has_footer_connections
    || $footer_copyright !== ''
);

$footer_classes = [
    'site-footer',
    'site-footer--layout-' . $footer_layout,
];

if ($has_footer_brand) {
    $footer_classes[] = 'site-footer--has-brand';
}

if ($has_footer_connections) {
    $footer_classes[] = 'site-footer--has-connections';
}
?>

<?php if ($has_footer_content) : ?>
    <footer
        id="colophon"
        class="<?php echo esc_attr(implode(' ', $footer_classes)); ?>"
    >
        <div class="site-footer__inner">
            <?php if ($has_footer_brand || $has_footer_connections) : ?>
                <div class="site-footer__main">
                    <?php if ($has_footer_brand) : ?>
                        <div class="site-footer__brand-wrap">
                            <?php if ($has_brand_identity) : ?>
                                <div class="site-footer__brand">
                                    <?php if (has_custom_logo()) : ?>
                                        <?php the_custom_logo(); ?>
                                    <?php elseif ($site_name !== '') : ?>
                                        <a
                                            class="site-footer__name"
                                            href="<?php echo esc_url(home_url('/')); ?>"
                                        ><?php echo esc_html($site_name); ?></a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($footer_tagline !== '') : ?>
                                <p class="site-footer__tagline">
                                    <?php echo esc_html($footer_tagline); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($has_footer_connections) : ?>
                        <div class="site-footer__connections">
                            <?php if ($has_footer_menu) : ?>
                                <nav
                                    class="site-footer__nav"
                                    aria-label="<?php esc_attr_e('Footer menu', 'music-project-base'); ?>"
                                >
                                    <?php
                                    wp_nav_menu(
                                        [
                                            'theme_location' => 'footer',
                                            'menu_id'        => 'footer-menu',
                                            'menu_class'     => 'site-footer__menu',
                                            'container'      => false,
                                            'depth'          => 1,
                                            'fallback_cb'    => false,
                                            'item_spacing'   => 'discard',
                                        ]
                                    );
                                    ?>
                                </nav>
                            <?php endif; ?>

                            <?php if ($has_footer_socials) : ?>
                                <div class="site-footer__socials">
                                    <?php
                                    echo $footer_socials; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                    ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if ($footer_copyright !== '') : ?>
                <p class="site-footer__copyright">
                    <?php echo esc_html($footer_copyright); ?>
                </p>
            <?php endif; ?>
        </div>
    </footer>
<?php endif; ?>

<?php if (!is_front_page()) : ?>
    <button
        class="scroll-top"
        type="button"
        aria-label="<?php esc_attr_e('Scroll to top', 'music-project-base'); ?>"
        hidden
    >
        <span aria-hidden="true">↑</span>
    </button>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>