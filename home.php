<?php
/**
 * Blog / Posts Archive
 */

get_header();

$posts_page_id = (int) get_option('page_for_posts');
$archive_title = $posts_page_id ? get_the_title($posts_page_id) : __('Blog', 'music-project-base');
$archive_intro = $posts_page_id ? trim((string) get_post_field('post_content', $posts_page_id)) : '';
?>

<main id="site-main" class="site-main blog-archive-main">
    <section class="blog-archive">
        <header class="blog-archive__header">
            <!-- <p class="blog-archive__eyebrow">
                <?php esc_html_e('Blog & News', 'music-project-base'); ?>
            </p> -->

            <h1><?php echo esc_html($archive_title); ?></h1>

            <?php if ($archive_intro) : ?>
                <div class="blog-archive__intro">
                    <?php echo wp_kses_post(wpautop($archive_intro)); ?>
                </div>
            <?php endif; ?>
        </header>

        <?php if (have_posts()) : ?>
            <div class="blog-archive__grid">
                <?php while (have_posts()) : ?>
                    <?php the_post(); ?>

                    <article <?php post_class('blog-archive-card'); ?>>
                        <?php if (has_post_thumbnail()) : ?>
                            <a class="blog-archive-card__image-link" href="<?php the_permalink(); ?>">
                                <?php
                                the_post_thumbnail('large', [
                                    'class' => 'blog-archive-card__image',
                                    'loading' => 'lazy',
                                ]);
                                ?>
                            </a>
                        <?php endif; ?>

                        <div class="blog-archive-card__body">
                            <time class="blog-archive-card__date" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
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

                            <a class="blog-archive-card__link" href="<?php the_permalink(); ?>">
                                <?php esc_html_e('Read More', 'music-project-base'); ?>
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <div class="blog-archive__pagination">
                <?php
                the_posts_pagination([
                    'mid_size' => 1,
                    'prev_text' => __('Previous', 'music-project-base'),
                    'next_text' => __('Next', 'music-project-base'),
                ]);
                ?>
            </div>
        <?php else : ?>
            <div class="blog-archive__empty">
                <p><?php esc_html_e('No posts have been published yet.', 'music-project-base'); ?></p>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php get_footer(); ?>