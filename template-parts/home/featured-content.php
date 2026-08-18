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
$layout = sanitize_key((string) mpc_get_homepage_setting('featured_layout', 'split_card'));
$quote_position = sanitize_key((string) mpc_get_homepage_setting('featured_quote_position', 'beside'));
$heading_size = mpb_normalize_homepage_size(
    mpc_get_homepage_setting(
        'featured_heading_size',
        'standard'
    )
);

$heading_font_role =
    mpb_get_homepage_section_heading_font_role(
        'featured'
    );

$quote_size = mpb_normalize_homepage_size(
    mpc_get_homepage_setting(
        'featured_quote_size',
        'standard'
    )
);

$quote_color = sanitize_hex_color(
    (string) mpc_get_homepage_setting(
        'featured_quote_color',
        ''
    )
);

if (!$quote_color) {
    $quote_color = '';
}
$label = trim((string) mpc_get_homepage_setting('featured_label'));
$title = trim((string) mpc_get_homepage_setting('featured_title'));
$text = trim((string) mpc_get_homepage_setting('featured_text'));
$image_id = absint(mpc_get_homepage_setting('featured_image_id'));
$media_type = sanitize_key((string) mpc_get_homepage_setting('featured_media_type', 'image'));
$video_url = trim((string) mpc_get_homepage_setting('featured_video_url'));
$cta_text = trim((string) mpc_get_homepage_setting('featured_cta_text'));
$cta_url = trim((string) mpc_get_homepage_setting('featured_cta_url'));

$legacy_show_quote = (bool) mpc_get_homepage_setting('featured_show_quote', 1);

$allowed_layouts = ['split_card', 'media_left', 'media_right', 'stacked'];
$allowed_quote_positions = ['beside', 'below', 'hidden'];
$allowed_media_types = ['image', 'video'];

if (!in_array($layout, $allowed_layouts, true)) {
    $layout = 'split_card';
}

if (!in_array($quote_position, $allowed_quote_positions, true)) {
    $quote_position = 'beside';
}

if (!$legacy_show_quote) {
    $quote_position = 'hidden';
}

if (!in_array($media_type, $allowed_media_types, true)) {
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

$quote_text = '';
$quote_source_name = '';
$quote_source_url = '';

if ($quote_position !== 'hidden' && function_exists('mpc_get_featured_press_quote')) {
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

$section_classes = [
    'home-section',
    'home-featured-content',
    'home-section--heading-font-'
        . $heading_font_role,
    'home-featured-content--layout-'
        . str_replace('_', '-', $layout),
    'home-featured-content--quote-'
        . str_replace(
            '_',
            '-',
            $quote_position
        ),
];

$card_classes = [
    'featured-content-card',
    'featured-content-card--media-' . $media_type,
];

$render_quote_card = static function (
    $quote_text,
    $quote_source_name,
    $quote_source_url
) use (
    $quote_size,
    $quote_color
) {
        $quote_classes = [
        'press-quote',
        'press-quote--size-' . $quote_size,
    ];

    $quote_style = $quote_color
        ? '--mpb-home-quote-color:' . $quote_color . ';'
        : '';
    ?>
    <aside class="featured-quote-card">
<blockquote
    class="<?php echo esc_attr(
        implode(' ', $quote_classes)
    ); ?>"
    <?php if ($quote_style) : ?>
        style="<?php echo esc_attr($quote_style); ?>"
    <?php endif; ?>
>            <p>“<?php echo esc_html($quote_text); ?>”</p>

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
    <?php
};
?>

<section id="featured" class="<?php echo esc_attr(implode(' ', $section_classes)); ?>">
    <?php if ($heading) : ?>
<header
    class="section-header section-header--size-<?php echo esc_attr(
        $heading_size
    ); ?>"
>            <h2><?php echo esc_html($heading); ?></h2>
        </header>
    <?php endif; ?>

    <div class="featured-content-layout">
        <article class="<?php echo esc_attr(implode(' ', $card_classes)); ?>">
            <?php if ($has_media) : ?>
                <div class="featured-content-card__media featured-content-card__image">
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
                    <div class="featured-content-card__text">
                        <?php echo wp_kses_post(wpautop($text)); ?>
                    </div>
                <?php endif; ?>

                <?php if ($cta_text && $cta_url) : ?>
                    <a class="featured-content-card__button" href="<?php echo esc_url($cta_url); ?>">
                        <?php echo esc_html($cta_text); ?>
                    </a>
                <?php endif; ?>
            </div>
        </article>

        <?php if ($quote_text && $quote_position === 'beside') : ?>
            <?php $render_quote_card($quote_text, $quote_source_name, $quote_source_url); ?>
        <?php endif; ?>
    </div>

    <?php if ($quote_text && $quote_position === 'below') : ?>
        <div class="featured-quote-below">
            <?php $render_quote_card($quote_text, $quote_source_name, $quote_source_url); ?>
        </div>
    <?php endif; ?>
</section>