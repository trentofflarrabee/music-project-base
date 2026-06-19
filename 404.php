<?php
/**
 * 404 Template
 */

get_header();
?>

<main id="site-main" class="site-main error-main">
    <section class="theme-error">
        <p class="theme-error__eyebrow">
            <?php esc_html_e('404', 'music-project-base'); ?>
        </p>

        <h1><?php esc_html_e('Page not found.', 'music-project-base'); ?></h1>

        <p>
            <?php esc_html_e('The page you’re looking for may have moved, been deleted, or never existed.', 'music-project-base'); ?>
        </p>

        <div class="theme-error__actions">
            <a class="button" href="<?php echo esc_url(home_url('/')); ?>">
                <?php esc_html_e('Back Home', 'music-project-base'); ?>
            </a>
        </div>

        <div class="theme-error__search">
            <?php get_search_form(); ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>