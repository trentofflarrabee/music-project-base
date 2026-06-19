<?php
/**
 * Site Footer
 */

$footer_layout = function_exists('mpc_get_footer_setting')
    ? sanitize_key((string) mpc_get_footer_setting('footer_layout', 'simple'))
    : 'simple';

if (!in_array($footer_layout, ['simple', 'stacked', 'split'], true)) {
    $footer_layout = 'simple';
}

$footer_tagline = function_exists('mpc_get_footer_setting')
    ? trim((string) mpc_get_footer_setting('footer_tagline', ''))
    : '';

$footer_copyright = function_exists('mpc_get_footer_setting')
    ? trim((string) mpc_get_footer_setting('footer_copyright', '© {year} {site_name}. All rights reserved.'))
    : '© ' . date_i18n('Y') . ' ' . get_bloginfo('name') . '. All rights reserved.';

if (function_exists('mpc_parse_footer_tokens')) {
    $footer_copyright = mpc_parse_footer_tokens($footer_copyright);
}

$show_brand = function_exists('mpc_get_footer_setting')
    ? (bool) mpc_get_footer_setting('footer_show_brand', 1)
    : true;

$show_menu = function_exists('mpc_get_footer_setting')
    ? (bool) mpc_get_footer_setting('footer_show_menu', 1)
    : true;

$show_socials = function_exists('mpc_get_footer_setting')
    ? (bool) mpc_get_footer_setting('footer_show_socials', 1)
    : true;
?>

<footer id="colophon" class="site-footer site-footer--layout-<?php echo esc_attr($footer_layout); ?>">
    <div class="site-footer__inner">
        <?php if ($show_brand) : ?>
            <div class="site-footer__brand-wrap">
                <div class="site-footer__brand">
                    <?php if (has_custom_logo()) : ?>
                        <?php the_custom_logo(); ?>
                    <?php else : ?>
                        <a class="site-footer__name" href="<?php echo esc_url(home_url('/')); ?>">
                            <?php bloginfo('name'); ?>
                        </a>
                    <?php endif; ?>
                </div>

                <?php if ($footer_tagline) : ?>
                    <p class="site-footer__tagline">
                        <?php echo esc_html($footer_tagline); ?>
                    </p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($show_menu && has_nav_menu('footer')) : ?>
            <nav class="site-footer__nav" aria-label="<?php esc_attr_e('Footer menu', 'music-project-base'); ?>">
                <?php
                wp_nav_menu([
                    'theme_location' => 'footer',
                    'menu_class' => 'site-footer__menu',
                    'container' => false,
                    'depth' => 1,
                ]);
                ?>
            </nav>
        <?php endif; ?>

        <?php if ($show_socials) : ?>
            <?php
            get_template_part('template-parts/social-links', null, [
                'context' => 'footer',
            ]);
            ?>
        <?php endif; ?>

        <?php if ($footer_copyright) : ?>
            <p class="site-footer__copyright">
                <?php echo esc_html($footer_copyright); ?>
            </p>
        <?php endif; ?>
    </div>
</footer>

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