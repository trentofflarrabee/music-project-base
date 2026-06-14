<?php
/**
 * Homepage Blog / News Section
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('mpc_get_homepage_setting')) {
    return;
}

$enabled = (bool) mpc_get_homepage_setting('blog_enabled', 1);

if (!$enabled) {
    return;
}

$heading = trim((string) mpc_get_homepage_setting('blog_heading', 'Blog'));
$layout = sanitize_key((string) mpc_get_homepage_setting('blog_layout', 'grid'));
$featured_source = sanitize_key((string) mpc_get_homepage_setting('blog_featured_source', 'latest'));
$featured_post_id = absint(mpc_get_homepage_setting('blog_featured_post_id', 0));
$posts_per_page = absint(mpc_get_homepage_setting('blog_posts_per_page', 3));
$additional_posts_count = absint(mpc_get_homepage_setting('blog_additional_posts', 2));
$show_images = (bool) mpc_get_homepage_setting('blog_show_images', 1);
$show_dates = (bool) mpc_get_homepage_setting('blog_show_dates', 1);
$show_excerpts = (bool) mpc_get_homepage_setting('blog_show_excerpts', 1);
$read_more_text = trim((string) mpc_get_homepage_setting('blog_read_more_text', 'Read More'));
$view_all_text = trim((string) mpc_get_homepage_setting('blog_view_all_text', 'View All Posts'));
$view_all_url = trim((string) mpc_get_homepage_setting('blog_view_all_url', '/blog'));

if (!in_array($layout, ['grid', 'featured_first', 'compact'], true)) {
    $layout = 'grid';
}

if (!in_array($featured_source, ['latest', 'manual'], true)) {
    $featured_source = 'latest';
}

if ($posts_per_page < 1) {
    $posts_per_page = 1;
}

if ($posts_per_page > 12) {
    $posts_per_page = 12;
}

if ($additional_posts_count > 6) {
    $additional_posts_count = 6;
}

$render_post_card = static function ($post_id, $args = []) use ($show_images, $show_dates, $show_excerpts, $read_more_text) {
    $args = wp_parse_args($args, [
        'variant' => 'default',
        'excerpt_words' => 24,
    ]);

    $variant = sanitize_key($args['variant']);
    $excerpt_words = absint($args['excerpt_words']);
    $has_image = $show_images && has_post_thumbnail($post_id);

    $classes = [
        'home-blog-card',
        'home-blog-card--' . $variant,
    ];

    if (!$has_image) {
        $classes[] = 'home-blog-card--no-image';
    }
    ?>
    <article class="<?php echo esc_attr(implode(' ', $classes)); ?>">
        <?php if ($has_image) : ?>
            <a class="home-blog-card__image-link" href="<?php echo esc_url(get_permalink($post_id)); ?>">
                <?php
                echo get_the_post_thumbnail($post_id, 'large', [
                    'class' => 'home-blog-card__image',
                    'loading' => 'lazy',
                ]);
                ?>
            </a>
        <?php endif; ?>

        <div class="home-blog-card__body">
            <?php if ($show_dates) : ?>
                <time class="home-blog-card__date" datetime="<?php echo esc_attr(get_the_date('c', $post_id)); ?>">
                    <?php echo esc_html(get_the_date('', $post_id)); ?>
                </time>
            <?php endif; ?>

            <h3>
                <a href="<?php echo esc_url(get_permalink($post_id)); ?>">
                    <?php echo esc_html(get_the_title($post_id)); ?>
                </a>
            </h3>

            <?php if ($show_excerpts) : ?>
                <div class="home-blog-card__excerpt">
                    <p>
                        <?php
                        echo esc_html(
                            wp_trim_words(
                                get_the_excerpt($post_id),
                                $excerpt_words
                            )
                        );
                        ?>
                    </p>
                </div>
            <?php endif; ?>

            <?php if ($read_more_text) : ?>
                <a class="home-blog-card__link" href="<?php echo esc_url(get_permalink($post_id)); ?>">
                    <?php echo esc_html($read_more_text); ?>
                </a>
            <?php endif; ?>
        </div>
    </article>
    <?php
};

$section_classes = [
    'home-section',
    'home-blog',
    'home-blog--layout-' . str_replace('_', '-', $layout),
];

$featured_post = null;
$secondary_posts = [];

if ($layout === 'featured_first') {
    if ($featured_source === 'manual' && $featured_post_id) {
        $manual_post = get_post($featured_post_id);

        if ($manual_post && $manual_post->post_type === 'post' && $manual_post->post_status === 'publish') {
            $featured_post = $manual_post;
        }
    }

    if (!$featured_post) {
        $latest_query = new WP_Query([
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 1,
            'ignore_sticky_posts' => true,
        ]);

        if ($latest_query->have_posts()) {
            $featured_post = $latest_query->posts[0];
        }

        wp_reset_postdata();
    }

    if ($featured_post && $additional_posts_count > 0) {
        $secondary_query = new WP_Query([
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => $additional_posts_count,
            'post__not_in' => [$featured_post->ID],
            'ignore_sticky_posts' => true,
        ]);

        $secondary_posts = $secondary_query->posts;

        wp_reset_postdata();
    }
} else {
    $posts_query = new WP_Query([
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => $posts_per_page,
        'ignore_sticky_posts' => true,
    ]);

    $secondary_posts = $posts_query->posts;

    wp_reset_postdata();
}

if ($layout === 'featured_first' && !$featured_post) {
    return;
}

if ($layout !== 'featured_first' && empty($secondary_posts)) {
    return;
}
?>

<section id="blog" class="<?php echo esc_attr(implode(' ', $section_classes)); ?>">
    <div class="home-blog__header">
        <?php if ($heading) : ?>
            <header class="section-header">
                <h2><?php echo esc_html($heading); ?></h2>
            </header>
        <?php endif; ?>

        <?php if ($view_all_text && $view_all_url) : ?>
            <a class="home-blog__view-all" href="<?php echo esc_url($view_all_url); ?>">
                <?php echo esc_html($view_all_text); ?>
            </a>
        <?php endif; ?>
    </div>

    <?php if ($layout === 'featured_first') : ?>
        <div class="home-blog__featured-layout">
            <?php $render_post_card($featured_post->ID, [
                'variant' => 'featured',
                'excerpt_words' => 42,
            ]); ?>

            <?php if ($secondary_posts) : ?>
                <div class="home-blog__secondary-grid">
                    <?php foreach ($secondary_posts as $post_item) : ?>
                        <?php $render_post_card($post_item->ID, [
                            'variant' => 'secondary',
                            'excerpt_words' => 20,
                        ]); ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php elseif ($layout === 'compact') : ?>
        <div class="home-blog__compact-list">
            <?php foreach ($secondary_posts as $post_item) : ?>
                <?php $render_post_card($post_item->ID, [
                    'variant' => 'compact',
                    'excerpt_words' => 22,
                ]); ?>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <div class="home-blog__grid">
            <?php foreach ($secondary_posts as $post_item) : ?>
                <?php $render_post_card($post_item->ID, [
                    'variant' => 'grid',
                    'excerpt_words' => 24,
                ]); ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>