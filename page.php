<?php
/**
 * Default Page Template
 */

get_header();
?>

<main id="site-main" class="site-main page-main">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : ?>
            <?php the_post(); ?>

            <article <?php post_class('theme-page'); ?>>
                <header class="theme-page__header">
                    <h1><?php the_title(); ?></h1>
                </header>

                <?php if (has_post_thumbnail()) : ?>
                    <figure class="theme-page__featured-image">
                        <?php
                        the_post_thumbnail('large', [
                            'class' => 'theme-page__image',
                            'loading' => 'eager',
                        ]);
                        ?>
                    </figure>
                <?php endif; ?>

                <div class="theme-page__content">
                    <?php the_content(); ?>

                    <?php
                    wp_link_pages([
                        'before' => '<div class="theme-page__page-links">' . esc_html__('Pages:', 'music-project-base'),
                        'after' => '</div>',
                    ]);
                    ?>
                </div>
            </article>
        <?php endwhile; ?>
    <?php endif; ?>
</main>

<?php get_footer(); ?>