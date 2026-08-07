<?php
/**
 * Front Page Template
 *
 * Music Project Core supplies the configurable homepage section registry.
 * When the required Core homepage APIs are unavailable, Base falls back to
 * normal WordPress page or posts content instead of producing an empty page.
 */

get_header();

$core_homepage_available = (
    function_exists(
        'mpc_get_homepage_settings'
    )
    && function_exists(
        'mpc_get_homepage_section_order'
    )
    && function_exists(
        'mpc_is_homepage_section_visible'
    )
);
?>

<main id="site-main" class="site-main home-main">
    <?php if ($core_homepage_available) : ?>
        <?php foreach (mpb_get_home_sections() as $section) : ?>
            <?php mpb_render_home_section($section); ?>
        <?php endforeach; ?>
    <?php elseif (is_home()) : ?>
        <section class="blog-archive blog-archive--front-fallback">
            <header class="blog-archive__header">
                <h1>
                    <?php
                    esc_html_e(
                        'Latest Posts',
                        'music-project-base'
                    );
                    ?>
                </h1>
            </header>

            <?php if (have_posts()) : ?>
                <div class="blog-archive__grid">
                    <?php while (have_posts()) : ?>
                        <?php the_post(); ?>

                        <article <?php post_class('blog-archive-card'); ?>>
                            <?php if (has_post_thumbnail()) : ?>
                                <a
                                    class="blog-archive-card__image-link"
                                    href="<?php the_permalink(); ?>"
                                >
                                    <?php
                                    the_post_thumbnail(
                                        'large',
                                        [
                                            'class'   => 'blog-archive-card__image',
                                            'loading' => 'lazy',
                                        ]
                                    );
                                    ?>
                                </a>
                            <?php endif; ?>

                            <div class="blog-archive-card__body">
                                <time
                                    class="blog-archive-card__date"
                                    datetime="<?php echo esc_attr(get_the_date('c')); ?>"
                                >
                                    <?php echo esc_html(get_the_date()); ?>
                                </time>

                                <h2>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>

                                <div class="blog-archive-card__excerpt">
                                    <?php the_excerpt(); ?>
                                </div>

                                <a
                                    class="blog-archive-card__link"
                                    href="<?php the_permalink(); ?>"
                                >
                                    <?php
                                    esc_html_e(
                                        'Read More',
                                        'music-project-base'
                                    );
                                    ?>
                                </a>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>

                <div class="blog-archive__pagination">
                    <?php
                    the_posts_pagination(
                        [
                            'mid_size'  => 1,
                            'prev_text' => __(
                                'Previous',
                                'music-project-base'
                            ),
                            'next_text' => __(
                                'Next',
                                'music-project-base'
                            ),
                        ]
                    );
                    ?>
                </div>
            <?php else : ?>
                <div class="blog-archive__empty">
                    <p>
                        <?php
                        esc_html_e(
                            'No posts have been published yet.',
                            'music-project-base'
                        );
                        ?>
                    </p>
                </div>
            <?php endif; ?>
        </section>
    <?php elseif (have_posts()) : ?>
        <?php while (have_posts()) : ?>
            <?php the_post(); ?>

            <article <?php post_class('theme-page theme-page--front-fallback'); ?>>
                <header class="theme-page__header">
                    <h1><?php the_title(); ?></h1>
                </header>

                <?php if (has_post_thumbnail()) : ?>
                    <figure class="theme-page__featured-image">
                        <?php
                        the_post_thumbnail(
                            'large',
                            [
                                'class'   => 'theme-page__image',
                                'loading' => 'eager',
                            ]
                        );
                        ?>
                    </figure>
                <?php endif; ?>

                <div class="theme-page__content">
                    <?php the_content(); ?>

                    <?php
                    wp_link_pages(
                        [
                            'before' => '<div class="theme-page__page-links">'
                                . esc_html__(
                                    'Pages:',
                                    'music-project-base'
                                ),
                            'after'  => '</div>',
                        ]
                    );
                    ?>
                </div>
            </article>
        <?php endwhile; ?>
    <?php else : ?>
        <div class="theme-page">
            <div class="theme-page__content">
                <p>
                    <?php
                    esc_html_e(
                        'No content found.',
                        'music-project-base'
                    );
                    ?>
                </p>
            </div>
        </div>
    <?php endif; ?>
</main>

<?php get_footer(); ?>