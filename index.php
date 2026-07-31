<?php get_header(); ?>

<main id="site-main" class="site-main archive-main">

    <?php if (have_posts()) : ?>

        <div class="archive-posts">
            <?php while (have_posts()) : the_post(); ?>

                <article <?php post_class('archive-post-card'); ?>>
                    <h2>
                        <a href="<?php the_permalink(); ?>">
                            <?php the_title(); ?>
                        </a>
                    </h2>

                    <?php the_excerpt(); ?>
                </article>

            <?php endwhile; ?>
        </div>

    <?php else : ?>

        <p>
            <?php
            esc_html_e(
                'No content found.',
                'music-project-base'
            );
            ?>
        </p>

    <?php endif; ?>

</main>

<?php get_footer(); ?>