<?php
/**
 * Homepage Featured Content Section
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('mpc_get_homepage_setting')) {
    return;
}

$enabled = (bool) mpc_get_homepage_setting('featured_enabled', 1);

if (!$enabled) {
    return;
}

$heading = trim((string) mpc_get_homepage_setting('featured_heading'));
$label = trim((string) mpc_get_homepage_setting('featured_label'));
$title = trim((string) mpc_get_homepage_setting('featured_title'));
$text = trim((string) mpc_get_homepage_setting('featured_text'));
$image_id = absint(mpc_get_homepage_setting('featured_image_id'));
$media_type = sanitize_key((string) mpc_get_homepage_setting('featured_media_type', 'image'));
$video_url = trim((string) mpc_get_homepage_setting('featured_video_url'));
$cta_text = trim((string) mpc_get_homepage_setting('featured_cta_text'));
$cta_url = trim((string) mpc_get_homepage_setting('featured_cta_url'));
$show_quote = (bool) mpc_get_homepage_setting('featured_show_quote', 1);

if (!in_array($media_type, ['image', 'video'], true)) {
    $media_type = 'image';
}

$image_html = $image_id
    ? wp_get_attachment_image($image_id, 'large', false, [
        'class' => 'featured-content-card__img',
        'loading' => 'lazy',
    ])
    : '';

$embed_html = '';

if ($media_type === 'video' && $video_url) {
    $embed_html = wp_oembed_get($video_url);

    if (!$embed_html) {
        $embed_html = sprintf(
            '<p><a href="%s" target="_blank" rel="noopener noreferrer">%s</a></p>',
            esc_url($video_url),
            esc_html__('Watch video', 'music-project-base')
        );
    }
}

$quote = null;
$quote_text = '';
$quote_source_name = '';
$quote_source_url = '';

if ($show_quote && function_exists('mpc_get_featured_press_quote')) {
    $quote = mpc_get_featured_press_quote();

    if (is_array($quote)) {
        $quote_text = $quote['quote']
            ?? $quote['text']
            ?? $quote['quote_text']
            ?? '';

        $quote_source_name = $quote['source_name']
            ?? $quote['source']
            ?? '';

        $quote_source_url = $quote['source_url']
            ?? '';
    }
}

$has_media = ($media_type === 'video' && $embed_html) || $image_html;
?>

<section id="featured" class="home-section home-featured-content">
    <?php if ($heading) : ?>
        <header class="section-header">
            <h2><?php echo esc_html($heading); ?></h2>
        </header>
    <?php endif; ?>

    <div class="featured-content-layout">
        <article class="featured-content-card featured-content-card--media-<?php echo esc_attr($media_type); ?>">
            <?php if ($has_media) : ?>
                <div class="featured-content-card__image">
                    <?php if ($media_type === 'video' && $embed_html) : ?>
                        <div class="featured-content-card__embed">
                            <?php echo $embed_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </div>
                    <?php else : ?>
                        <?php echo $image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    <?php endif; ?>
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
                <?php endif; ?>

                <?php if ($text) : ?>
                    <p><?php echo esc_html($text); ?></p>
                <?php endif; ?>

                <?php if ($cta_text && $cta_url) : ?>
                    <a class="featured-content-card__button" href="<?php echo esc_url($cta_url); ?>">
                        <?php echo esc_html($cta_text); ?>
                    </a>
                <?php endif; ?>
            </div>
        </article>

        <?php if ($quote_text) : ?>
            <aside class="featured-quote-card">
                <blockquote class="press-quote">
                    <p>“<?php echo esc_html($quote_text); ?>”</p>

                    <?php if ($quote_source_url && $quote_source_name) : ?>
                        <cite>
                            <a href="<?php echo esc_url($quote_source_url); ?>" target="_blank" rel="noopener noreferrer">
                                <?php echo esc_html($quote_source_name); ?>
                            </a>
                        </cite>
                    <?php elseif ($quote_source_name) : ?>
                        <cite><?php echo esc_html($quote_source_name); ?></cite>
                    <?php endif; ?>
                </blockquote>
            </aside>
        <?php endif; ?>
    </div>
</section>