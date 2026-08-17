<?php
/**
 * Link Hub Template
 *
 * Specialized minimal shell for the WordPress Page assigned as the Link Hub.
 *
 * Music Project Core owns all reusable Link Hub configuration and data.
 * Music Project Base owns this frontend presentation.
 */

if (!defined('ABSPATH')) {
    exit;
}

/*
 * This template should only be reached through Base's guarded Link Hub
 * routing. Keep an additional defensive check here so direct or unexpected
 * template use cannot produce a broken page.
 */
if (
    !function_exists('mpb_is_link_hub_request')
    || !mpb_is_link_hub_request()
) {
    include get_template_directory() . '/page.php';

    return;
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
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : ?>
            <?php the_post(); ?>

            <div class="mpb-link-hub__development-shell">
                <h1>
                    <?php
                    echo esc_html(
                        get_bloginfo('name')
                    );
                    ?>
                </h1>

                <p>
                    <?php
                    esc_html_e(
                        'Link Hub',
                        'music-project-base'
                    );
                    ?>
                </p>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</main>

<?php wp_footer(); ?>
</body>
</html>