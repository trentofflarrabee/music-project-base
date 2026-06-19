<?php
/**
 * Generic Archive Template
 */

get_header();

$archive_title = get_the_archive_title();
$archive_description = get_the_archive_description();
?>

<main id="site-main" class="site-main archive-main">
    <section class="theme-archive">
        <header class="theme-archive__header">
            <h1><?php echo wp_kses_post($archive_title); ?></h1>

            <?php if ($archive_description) : ?>
                <div class="theme-archive__description">
                    <?php echo wp_kses_post(wpautop($archive_description)); ?>
                </div>
            <?php endif; ?>
        </header>

        <?php if (have_posts()) : ?>
            <div class="theme-archive__grid">
                <?php while (have_posts()) : ?>
                    <?php the_post(); ?>

                    <article <?php post_class('theme-archive-card'); ?>>
                        <?php if (has_post_thumbnail()) : ?>
                            <a class="theme-archive-card__image-link" href="<?php the_permalink(); ?>">
                                <?php
                                the_post_thumbnail('large', [
                                    'class' => 'theme-archive-card__image',
                                    'loading' => 'lazy',
                                ]);
                                ?>
                            </a>
                        <?php endif; ?>

                        <div class="theme-archive-card__body">
                            <time class="theme-archive-card__date" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                <?php echo esc_html(get_the_date()); ?>
                            </time>

                            <h2>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h2>

                            <div class="theme-archive-card__excerpt">
                                <?php the_excerpt(); ?>
                            </div>

                            <a class="theme-archive-card__link" href="<?php the_permalink(); ?>">
                                <?php esc_html_e('Read More', 'music-project-base'); ?>
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <div class="theme-archive__pagination">
                <?php
                the_posts_pagination([
                    'mid_size' => 1,
                    'prev_text' => __('Previous', 'music-project-base'),
                    'next_text' => __('Next', 'music-project-base'),
                ]);
                ?>
            </div>
        <?php else : ?>
            <div class="theme-archive__empty">
                <p><?php esc_html_e('Nothing has been published here yet.', 'music-project-base'); ?></p>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php get_footer(); ?>