<?php
/**
 * Single Post Template
 */

get_header();

$posts_page_id = (int) get_option('page_for_posts');
$blog_url = $posts_page_id ? get_permalink($posts_page_id) : home_url('/blog/');
$blog_label = $posts_page_id ? get_the_title($posts_page_id) : __('Blog', 'music-project-base');
?>

<main id="site-main" class="site-main single-main">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : ?>
            <?php the_post(); ?>

            <article <?php post_class('single-entry'); ?>>
                <a class="single-post__back-link" href="<?php echo esc_url($blog_url); ?>">
                    <?php
                    printf(
                        esc_html__('← Back to %s', 'music-project-base'),
                        esc_html($blog_label)
                    );
                    ?>
                </a>

                <header class="single-post__header">
                    <p class="single-post__meta">
                        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                            <?php echo esc_html(get_the_date()); ?>
                        </time>
                    </p>

                    <h1><?php the_title(); ?></h1>

                    <?php if (has_excerpt()) : ?>
                        <div class="single-post__excerpt">
                            <?php the_excerpt(); ?>
                        </div>
                    <?php endif; ?>
                </header>

                <?php if (has_post_thumbnail()) : ?>
                    <figure class="single-post__featured-image">
                        <?php
                        the_post_thumbnail('large', [
                            'class' => 'single-post__image',
                            'loading' => 'eager',
                        ]);
                        ?>
                    </figure>
                <?php endif; ?>

                <div class="single-post__content">
                    <?php the_content(); ?>
                </div>

                <footer class="single-post__footer">
                    <?php
                    wp_link_pages([
                        'before' => '<div class="single-post__page-links">' . esc_html__('Pages:', 'music-project-base'),
                        'after' => '</div>',
                    ]);
                    ?>

<nav
    class="single-post__nav"
    aria-label="<?php esc_attr_e('Post navigation', 'music-project-base'); ?>"
>
    <div class="single-post__nav-item single-post__nav-item--prev">
        <?php
        previous_post_link(
            '%link',
            '<span>'
                . esc_html__(
                    'Previous',
                    'music-project-base'
                )
                . '</span>%title'
        );
        ?>
    </div>

    <div class="single-post__nav-item single-post__nav-item--next">
        <?php
        next_post_link(
            '%link',
            '<span>'
                . esc_html__(
                    'Next',
                    'music-project-base'
                )
                . '</span>%title'
        );
        ?>
    </div>
</nav>
                </footer>
            </article>
        <?php endwhile; ?>
    <?php endif; ?>
</main>

<?php get_footer(); ?>