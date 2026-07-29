<!doctype html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script>
        document.documentElement.classList.remove('no-js');
        document.documentElement.classList.add('js');
    </script>

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php
$theme_style = function_exists('mpb_get_theme_style_settings')
    ? mpb_get_theme_style_settings()
    : [];

$brand_display = sanitize_key($theme_style['brand_display'] ?? 'logo_name');

if (!in_array($brand_display, ['logo_name', 'logo_only', 'name_only', 'hidden'], true)) {
    $brand_display = 'logo_name';
}

$show_logo = has_custom_logo() && in_array($brand_display, ['logo_name', 'logo_only'], true);
$show_name = in_array($brand_display, ['logo_name', 'name_only'], true);
$show_branding = $brand_display !== 'hidden' && ($show_logo || $show_name);
?>

<header class="site-header">
    <div class="site-header__inner">
        <?php if ($show_branding) : ?>
            <div class="site-branding">
                <?php if ($show_logo) : ?>
                    <?php the_custom_logo(); ?>
                <?php endif; ?>

                <?php if ($show_name) : ?>
                    <a class="site-branding__name" href="<?php echo esc_url(home_url('/')); ?>">
                        <?php bloginfo('name'); ?>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

<button
    class="site-menu-toggle"
    type="button"
    aria-controls="site-navigation"
    aria-expanded="false"
    data-open-label="<?php esc_attr_e('Open menu', 'music-project-base'); ?>"
    data-close-label="<?php esc_attr_e('Close menu', 'music-project-base'); ?>"
>
    <span class="screen-reader-text">
        <?php esc_html_e('Open menu', 'music-project-base'); ?>
    </span>

    <span
        class="site-menu-toggle__bars"
        aria-hidden="true"
    ></span>
</button>

        <nav id="site-navigation" class="site-nav" aria-label="<?php esc_attr_e('Primary menu', 'music-project-base'); ?>">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'menu_id' => 'primary-menu',
                'menu_class' => 'site-nav__menu',
                'container' => false,
                'depth' => 1,
            ]);
            ?>

            <div class="site-nav__footer">
                <?php
                    get_template_part(
                        'template-parts/social-links',
                        null,
                        [
                            'context' => 'navigation',
                            'display' => 'icons',
                        ]
                    );
                ?>
            </div>
        </nav>
    </div>
</header>