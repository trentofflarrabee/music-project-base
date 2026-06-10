<?php

if (!defined('ABSPATH')) {
    exit;
}

$plugin_active = function_exists('mpc_get_homepage_setting');

$enabled = $plugin_active
    ? (bool) mpc_get_homepage_setting('blog_enabled', 1)
    : true;

if (!$enabled) {
    return;
}

$heading = $plugin_active
    ? mpc_get_homepage_setting('blog_heading', 'Blog')
    : 'Blog';

$posts_per_page = $plugin_active
    ? absint(mpc_get_homepage_setting('blog_posts_per_page', 2))
    : 2;

if ($posts_per_page < 1) {
    $posts_per_page = 1;
}

$read_more_text = $plugin_active
    ? mpc_get_homepage_setting('blog_read_more_text', 'Read More')
    : 'Read More';

$view_all_text = $plugin_active
    ? mpc_get_homepage_setting('blog_view_all_text', 'View All Posts')
    : 'View All Posts';

$view_all_url = $plugin_active
    ? mpc_get_homepage_setting('blog_view_all_url', '/blog')
    : '/blog';

if (!$view_all_url) {
    $posts_page_id = get_option('page_for_posts');
    $view_all_url = $posts_page_id ? get_permalink($posts_page_id) : get_post_type_archive_link('post');
}

$home_blog_posts = new WP_Query([
    'post_type' => 'post',
    'posts_per_page' => $posts_per_page,
    'ignore_sticky_posts' => true,
]);
?>

<section id="blog" class="home-section home-blog">
    <header class="section-header home-blog__header">
        <?php if ($heading) : ?>
            <h2><?php echo esc_html($heading); ?></h2>
        <?php endif; ?>

        <?php if ($view_all_text && $view_all_url) : ?>
            <a class="home-blog__view-all" href="<?php echo esc_url($view_all_url); ?>">
                <?php echo esc_html($view_all_text); ?>
            </a>
        <?php endif; ?>
    </header>

    <?php if ($home_blog_posts->have_posts()) : ?>
        <div class="home-blog__grid">
            <?php while ($home_blog_posts->have_posts()) : $home_blog_posts->the_post(); ?>
                <article <?php post_class('home-blog-card'); ?>>
                    <?php if (has_post_thumbnail()) : ?>
                        <a class="home-blog-card__image-link" href="<?php the_permalink(); ?>">
                            <?php
                            the_post_thumbnail(
                                'large',
                                [
                                    'class' => 'home-blog-card__image',
                                ]
                            );
                            ?>
                        </a>
                    <?php endif; ?>

                    <div class="home-blog-card__body">
                        <time class="home-blog-card__date" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                            <?php echo esc_html(get_the_date()); ?>
                        </time>

                        <h3>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h3>

                        <div class="home-blog-card__excerpt">
                            <?php the_excerpt(); ?>
                        </div>

                        <?php if ($read_more_text) : ?>
                            <a class="home-blog-card__link" href="<?php the_permalink(); ?>">
                                <?php echo esc_html($read_more_text); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>

        <?php wp_reset_postdata(); ?>
    <?php else : ?>
        <p>No blog posts yet.</p>
    <?php endif; ?>
</section>