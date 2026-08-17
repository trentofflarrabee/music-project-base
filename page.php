<?php
/**
 * Default Page Template.
 */

get_header();
?>

<main id="site-main" class="site-main page-main">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : ?>
            <?php
            the_post();

            $page_title_style = 'standard';

            if (function_exists('mpc_get_page_title_style')) {
                $page_title_style = sanitize_key(
                    (string) mpc_get_page_title_style(
                        get_the_ID()
                    )
                );
            }

            $allowed_page_title_styles = [
                'standard',
                'editorial-panel',
                'minimal-overlay',
            ];

            if (
                !in_array(
                    $page_title_style,
                    $allowed_page_title_styles,
                    true
                )
            ) {
                $page_title_style = 'standard';
            }

            $has_featured_image = has_post_thumbnail();

            /*
             * Minimal Overlay requires an image surface.
             * Without one, use the normal Page presentation.
             */
            if (
                'minimal-overlay' === $page_title_style
                && !$has_featured_image
            ) {
                $page_title_style = 'standard';
            }

            $theme_style = function_exists(
                'mpb_get_theme_style_settings'
            )
                ? mpb_get_theme_style_settings()
                : [];

            $panel_tone = sanitize_key(
                (string) (
                    $theme_style['page_title_panel_tone']
                    ?? 'surface'
                )
            );

            $allowed_panel_tones = [
                'background',
                'surface',
                'accent',
                'button',
            ];

            if (
                !in_array(
                    $panel_tone,
                    $allowed_panel_tones,
                    true
                )
            ) {
                $panel_tone = 'surface';
            }

            $panel_strength = sanitize_key(
                (string) (
                    $theme_style['page_title_panel_strength']
                    ?? 'strong'
                )
            );

            $allowed_panel_strengths = [
                'soft',
                'strong',
                'solid',
            ];

            if (
                !in_array(
                    $panel_strength,
                    $allowed_panel_strengths,
                    true
                )
            ) {
                $panel_strength = 'strong';
            }

            $title_size = sanitize_key(
                (string) (
                    $theme_style['page_title_size']
                    ?? 'standard'
                )
            );

            $allowed_title_sizes = [
                'compact',
                'standard',
                'large',
            ];

            if (
                !in_array(
                    $title_size,
                    $allowed_title_sizes,
                    true
                )
            ) {
                $title_size = 'standard';
            }

            $article_classes = [
                'theme-page',
                'theme-page--title-' . $page_title_style,
                'theme-page--panel-tone-' . $panel_tone,
                'theme-page--panel-strength-' . $panel_strength,
                'theme-page--title-size-' . $title_size,
            ];
            ?>

            <article <?php post_class($article_classes); ?>>

                <?php if ('standard' === $page_title_style) : ?>

                    <header class="theme-page__header">
                        <h1><?php the_title(); ?></h1>
                    </header>

                    <?php if ($has_featured_image) : ?>
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

                <?php elseif ('editorial-panel' === $page_title_style) : ?>

                    <?php if ($has_featured_image) : ?>

                        <header
                            class="
                                theme-page__hero
                                theme-page__hero--editorial
                            "
                        >
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

                            <div class="theme-page__title-panel">
                                <h1><?php the_title(); ?></h1>
                            </div>
                        </header>

                    <?php else : ?>

                        <header
                            class="
                                theme-page__header
                                theme-page__title-panel
                                theme-page__title-panel--standalone
                            "
                        >
                            <h1><?php the_title(); ?></h1>
                        </header>

                    <?php endif; ?>

                <?php elseif ('minimal-overlay' === $page_title_style) : ?>

                    <header
                        class="
                            theme-page__hero
                            theme-page__hero--minimal-overlay
                        "
                    >
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

                        <div class="theme-page__title-overlay">
                            <h1><?php the_title(); ?></h1>
                        </div>
                    </header>

                <?php endif; ?>

                <div class="theme-page__content">
                    <?php the_content(); ?>

                    <?php
                    wp_link_pages(
                        [
                            'before' =>
                                '<div class="theme-page__page-links">'
                                . esc_html__(
                                    'Pages:',
                                    'music-project-base'
                                ),
                            'after' => '</div>',
                        ]
                    );
                    ?>
                </div>
            </article>

        <?php endwhile; ?>
    <?php endif; ?>
</main>

<?php get_footer(); ?>