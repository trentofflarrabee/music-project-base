<section class="home-section home-blog">
    <header class="section-header">
        <h2>Blog</h2>
    </header>

    <?php
    $home_blog_posts = new WP_Query([
        'post_type' => 'post',
        'posts_per_page' => 2,
        'ignore_sticky_posts' => true,
    ]);
    ?>

    <?php if ($home_blog_posts->have_posts()) : ?>
        <div class="home-blog__grid">
            <?php while ($home_blog_posts->have_posts()) : $home_blog_posts->the_post(); ?>
                <article <?php post_class('home-blog-card'); ?>>
                    <h3>
                        <a href="<?php the_permalink(); ?>">
                            <?php the_title(); ?>
                        </a>
                    </h3>

                    <?php the_excerpt(); ?>
                </article>
            <?php endwhile; ?>
        </div>

        <?php wp_reset_postdata(); ?>
    <?php else : ?>
        <p>No blog posts yet.</p>
    <?php endif; ?>
</section>