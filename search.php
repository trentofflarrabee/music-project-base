<?php
/**
 * Search Results Template
 */

get_header();

$search_query = get_search_query();
?>

<main id="site-main" class="site-main search-main">
    <section class="theme-search">
        <header class="theme-search__header">
            <h1>
                <?php
                printf(
                    esc_html__('Search results for “%s”', 'music-project-base'),
                    esc_html($search_query)
                );
                ?>
            </h1>

            <div class="theme-search__form">
                <?php get_search_form(); ?>
            </div>
        </header>

        <?php if (have_posts()) : ?>
            <div class="theme-search__grid">
                <?php while (have_posts()) : ?>
                    <?php the_post(); ?>

                    <article <?php post_class('theme-archive-card theme-search-card'); ?>>
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
<p class="theme-search-card__type">
    <?php
    $post_type = get_post_type();

    $post_type_object = is_string($post_type)
        ? get_post_type_object($post_type)
        : null;

    $post_type_label = (
        is_object($post_type_object)
        && isset($post_type_object->labels)
        && is_object($post_type_object->labels)
        && isset(
            $post_type_object->labels->singular_name
        )
        && is_scalar(
            $post_type_object->labels->singular_name
        )
    )
        ? (string) $post_type_object
            ->labels
            ->singular_name
        : __(
            'Content',
            'music-project-base'
        );

    echo esc_html($post_type_label);
    ?>
</p>
                            <h2>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h2>

                            <div class="theme-archive-card__excerpt">
                                <?php the_excerpt(); ?>
                            </div>

                            <a class="theme-archive-card__link" href="<?php the_permalink(); ?>">
                                <?php esc_html_e('View Result', 'music-project-base'); ?>
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
            <div class="theme-search__empty">
                <h2><?php esc_html_e('No results found.', 'music-project-base'); ?></h2>
                <p><?php esc_html_e('Try searching with a different phrase.', 'music-project-base'); ?></p>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php get_footer(); ?>