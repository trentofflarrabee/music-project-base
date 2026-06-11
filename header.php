<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
    <div class="site-header__inner">
        <div class="site-branding">
            <?php if (has_custom_logo()) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <a class="site-branding__name" href="<?php echo esc_url(home_url('/')); ?>">
                    <?php bloginfo('name'); ?>
                </a>
            <?php endif; ?>
        </div>

        <button
            class="site-menu-toggle"
            type="button"
            aria-controls="primary-menu"
            aria-expanded="false"
        >
            <span class="screen-reader-text">
                <?php esc_html_e('Menu', 'music-project-base'); ?>
            </span>
            <span class="site-menu-toggle__bars" aria-hidden="true"></span>
        </button>

        <nav class="site-nav" aria-label="<?php esc_attr_e('Primary Menu', 'music-project-base'); ?>">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'site-nav__menu',
                'menu_id'        => 'primary-menu',
                'fallback_cb'    => false,
                'depth'          => 2,
            ]);
            ?>
        </nav>
    </div>
</header>