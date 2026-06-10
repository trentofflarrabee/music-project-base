<?php get_header(); ?>

<main id="site-main" class="site-main single-main">

    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>

            <article <?php post_class('single-post'); ?>>

                <header class="single-post__header">
                    <p class="single-post__meta">
                        <?php echo esc_html(get_the_date()); ?>
                    </p>

                    <h1><?php the_title(); ?></h1>
                </header>

                <?php if (has_post_thumbnail()) : ?>
                    <div class="single-post__featured-image">
                        <?php
                        the_post_thumbnail(
                            'large',
                            [
                                'class' => 'single-post__image',
                            ]
                        );
                        ?>
                    </div>
                <?php endif; ?>

                <div class="single-post__content">
                    <?php the_content(); ?>
                </div>

            </article>

        <?php endwhile; ?>
    <?php endif; ?>

</main>

<?php get_footer(); ?>