<?php

if (!defined('ABSPATH')) {
    exit;
}

$plugin_active = function_exists('mpc_get_homepage_setting');

$hero_enabled = $plugin_active
    ? (bool) mpc_get_homepage_setting('hero_enabled', 1)
    : true;

if (!$hero_enabled) {
    return;
}

$heading = $plugin_active
    ? mpc_get_homepage_setting('hero_heading', get_bloginfo('name'))
    : get_bloginfo('name');

$text = $plugin_active
    ? mpc_get_homepage_setting('hero_text', '')
    : 'A reusable WordPress theme for bands, artists, and music projects.';

$mobile_image_id = $plugin_active
    ? absint(mpc_get_homepage_setting('hero_mobile_image_id', 0))
    : 0;

$desktop_video_id = $plugin_active
    ? absint(mpc_get_homepage_setting('hero_desktop_video_id', 0))
    : 0;

$cta_text = $plugin_active
    ? mpc_get_homepage_setting('hero_cta_text', '')
    : '';

$cta_url = $plugin_active
    ? mpc_get_homepage_setting('hero_cta_url', '')
    : '';

$desktop_video_url = $desktop_video_id ? wp_get_attachment_url($desktop_video_id) : '';
?>

<section class="home-section home-hero">
    <div class="home-hero__media <?php echo $desktop_video_url ? 'has-desktop-video' : ''; ?>">

        <?php if ($mobile_image_id) : ?>
            <div class="home-hero__image-wrap">
                <?php
                echo wp_get_attachment_image(
                    $mobile_image_id,
                    'large',
                    false,
                    [
                        'class' => 'home-hero__image',
                    ]
                );
                ?>
            </div>
        <?php endif; ?>

        <?php if ($desktop_video_url) : ?>
            <video
                class="home-hero__video"
                autoplay
                muted
                loop
                playsinline
            >
                <source src="<?php echo esc_url($desktop_video_url); ?>" type="video/mp4">
            </video>
        <?php endif; ?>

        <?php if (!$mobile_image_id && !$desktop_video_url) : ?>
            <div class="home-hero__placeholder">
                Hero image/video area
            </div>
        <?php endif; ?>

    </div>

    <div class="home-hero__content">
        <?php if ($heading) : ?>
            <h1><?php echo esc_html($heading); ?></h1>
        <?php endif; ?>

        <?php if ($text) : ?>
            <p><?php echo esc_html($text); ?></p>
        <?php endif; ?>

        <?php if ($cta_text && $cta_url) : ?>
            <p>
                <a class="button home-hero__button" href="<?php echo esc_url($cta_url); ?>">
                    <?php echo esc_html($cta_text); ?>
                </a>
            </p>
        <?php endif; ?>

        <?php get_template_part('template-parts/social-links'); ?>
    </div>
</section>