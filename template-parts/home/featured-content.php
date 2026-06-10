<?php

if (!defined('ABSPATH')) {
    exit;
}

$plugin_active = function_exists('mpc_get_homepage_setting');

$enabled = $plugin_active
    ? (bool) mpc_get_homepage_setting('featured_enabled', 1)
    : true;

if (!$enabled) {
    return;
}

$heading = $plugin_active
    ? mpc_get_homepage_setting('featured_heading', 'Featured Content')
    : 'Featured Content';

$label = $plugin_active
    ? mpc_get_homepage_setting('featured_label', 'Latest Release')
    : 'Latest Release';

$title = $plugin_active
    ? mpc_get_homepage_setting('featured_title', '')
    : '';

$text = $plugin_active
    ? mpc_get_homepage_setting('featured_text', '')
    : '';

$image_id = $plugin_active
    ? absint(mpc_get_homepage_setting('featured_image_id', 0))
    : 0;

$cta_text = $plugin_active
    ? mpc_get_homepage_setting('featured_cta_text', '')
    : '';

$cta_url = $plugin_active
    ? mpc_get_homepage_setting('featured_cta_url', '')
    : '';

$show_quote = $plugin_active
    ? (bool) mpc_get_homepage_setting('featured_show_quote', 1)
    : true;

$featured_quote = ($show_quote && function_exists('mpc_get_featured_press_quote'))
    ? mpc_get_featured_press_quote()
    : null;

?>

<section class="home-section home-featured-content">
    <header class="section-header">
        <?php if ($heading) : ?>
            <h2><?php echo esc_html($heading); ?></h2>
        <?php endif; ?>
    </header>

    <div class="featured-content-layout">
        <article class="featured-content-card">
            <?php if ($image_id) : ?>
                <div class="featured-content-card__image">
                    <?php
                    echo wp_get_attachment_image(
                        $image_id,
                        'large',
                        false,
                        [
                            'class' => 'featured-content-card__img',
                        ]
                    );
                    ?>
                </div>
            <?php endif; ?>

            <div class="featured-content-card__body">
                <?php if ($label) : ?>
                    <p class="featured-content-card__label">
                        <?php echo esc_html($label); ?>
                    </p>
                <?php endif; ?>

                <?php if ($title) : ?>
                    <h3><?php echo esc_html($title); ?></h3>
                <?php else : ?>
                    <h3>Featured title goes here</h3>
                <?php endif; ?>

                <?php if ($text) : ?>
                    <p><?php echo nl2br(esc_html($text)); ?></p>
                <?php else : ?>
                    <p>This area can feature a release, video, announcement, press link, or custom promo.</p>
                <?php endif; ?>

                <?php if ($cta_text && $cta_url) : ?>
                    <p>
                        <a class="button featured-content-card__button" href="<?php echo esc_url($cta_url); ?>">
                            <?php echo esc_html($cta_text); ?>
                        </a>
                    </p>
                <?php endif; ?>
            </div>
        </article>

        <?php if ($show_quote) : ?>
            <div class="featured-quote-card">
                <?php if ($featured_quote && !empty($featured_quote['text'])) : ?>
                    <blockquote class="press-quote">
                        <p>
                            “<?php echo esc_html($featured_quote['text']); ?>”
                        </p>

                        <?php if (!empty($featured_quote['source_name'])) : ?>
                            <cite>
                                <?php if (!empty($featured_quote['source_url'])) : ?>
                                    <a href="<?php echo esc_url($featured_quote['source_url']); ?>" target="_blank" rel="noopener noreferrer">
                                        <?php echo esc_html($featured_quote['source_name']); ?>
                                    </a>
                                <?php else : ?>
                                    <?php echo esc_html($featured_quote['source_name']); ?>
                                <?php endif; ?>
                            </cite>
                        <?php endif; ?>
                    </blockquote>
                <?php else : ?>
                    <blockquote class="press-quote press-quote--empty">
                        <p>No featured press quote yet.</p>
                    </blockquote>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>