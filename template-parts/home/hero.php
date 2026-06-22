<?php
/**
 * Homepage Hero Section
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('mpc_get_homepage_setting')) {
    return;
}

$enabled = (bool) mpc_get_homepage_setting('hero_enabled', 1);

if (!$enabled) {
    return;
}

$layout = sanitize_key((string) mpc_get_homepage_setting('hero_layout', 'split'));

if (!in_array($layout, ['split', 'full_bleed'], true)) {
    $layout = 'split';
}

$heading = trim((string) mpc_get_homepage_setting('hero_heading'));
$text = trim((string) mpc_get_homepage_setting('hero_text'));
$mobile_image_id = absint(mpc_get_homepage_setting('hero_mobile_image_id'));
$desktop_video_id = absint(mpc_get_homepage_setting('hero_desktop_video_id'));
$cta_text = trim((string) mpc_get_homepage_setting('hero_cta_text'));
$cta_url = trim((string) mpc_get_homepage_setting('hero_cta_url'));
$overlay_opacity = absint(mpc_get_homepage_setting('hero_overlay_opacity', 45));
$overlay_opacity = min(100, max(0, $overlay_opacity));
$overlay_opacity_css = $overlay_opacity / 100;

$overlay_style = sanitize_key((string) mpc_get_homepage_setting('hero_overlay_style', 'side'));
$content_position = sanitize_key((string) mpc_get_homepage_setting('hero_content_position', 'bottom_left'));

if (!in_array($overlay_style, ['side', 'bottom', 'center', 'even'], true)) {
    $overlay_style = 'side';
}

if (!in_array($content_position, ['bottom_left', 'center_left', 'bottom_center', 'center_center'], true)) {
    $content_position = 'bottom_left';
}

$mobile_image_url = $mobile_image_id ? wp_get_attachment_image_url($mobile_image_id, 'large') : '';
$desktop_video_url = $desktop_video_id ? wp_get_attachment_url($desktop_video_id) : '';

$classes = [
    'home-section',
    'home-hero',
    'home-hero--' . str_replace('_', '-', $layout),
    'home-hero--overlay-' . str_replace('_', '-', $overlay_style),
    'home-hero--content-' . str_replace('_', '-', $content_position),
];

if ($desktop_video_url) {
    $classes[] = 'home-hero--has-desktop-video';
} else {
    $classes[] = 'home-hero--image-only';
}

if ($mobile_image_url) {
    $classes[] = 'home-hero--has-image';
}
?>

<?php
/**
 * Homepage Hero Section
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('mpc_get_homepage_setting')) {
    return;
}

$enabled = (bool) mpc_get_homepage_setting('hero_enabled', 1);

if (!$enabled) {
    return;
}

$layout = sanitize_key((string) mpc_get_homepage_setting('hero_layout', 'split'));

if (!in_array($layout, ['split', 'full_bleed'], true)) {
    $layout = 'split';
}

$heading = trim((string) mpc_get_homepage_setting('hero_heading'));
$text = trim((string) mpc_get_homepage_setting('hero_text'));

$mobile_image_id = absint(mpc_get_homepage_setting('hero_mobile_image_id'));
$desktop_video_id = absint(mpc_get_homepage_setting('hero_desktop_video_id'));

$cta_text = trim((string) mpc_get_homepage_setting('hero_cta_text'));
$cta_url = trim((string) mpc_get_homepage_setting('hero_cta_url'));

$overlay_opacity = absint(mpc_get_homepage_setting('hero_overlay_opacity', 45));
$overlay_opacity = min(100, max(0, $overlay_opacity));
$overlay_opacity_css = $overlay_opacity / 100;

$overlay_style = sanitize_key((string) mpc_get_homepage_setting('hero_overlay_style', 'side'));

if (!in_array($overlay_style, ['side', 'bottom', 'center', 'even'], true)) {
    $overlay_style = 'side';
}

/**
 * Hero V2 content placement.
 *
 * New settings:
 * - hero_content_horizontal: left|center|right
 * - hero_content_vertical: top|center|bottom
 * - hero_text_align: auto|left|center|right
 *
 * Old setting:
 * - hero_content_position: bottom_left|center_left|bottom_center|center_center
 */
$legacy_content_position = sanitize_key((string) mpc_get_homepage_setting('hero_content_position', 'bottom_left'));

$legacy_position_map = [
    'bottom_left' => [
        'horizontal' => 'left',
        'vertical' => 'bottom',
    ],
    'center_left' => [
        'horizontal' => 'left',
        'vertical' => 'center',
    ],
    'bottom_center' => [
        'horizontal' => 'center',
        'vertical' => 'bottom',
    ],
    'center_center' => [
        'horizontal' => 'center',
        'vertical' => 'center',
    ],
];

$legacy_position = $legacy_position_map[$legacy_content_position] ?? $legacy_position_map['bottom_left'];

$content_horizontal = sanitize_key((string) mpc_get_homepage_setting('hero_content_horizontal', $legacy_position['horizontal']));
$content_vertical = sanitize_key((string) mpc_get_homepage_setting('hero_content_vertical', $legacy_position['vertical']));
$text_align = sanitize_key((string) mpc_get_homepage_setting('hero_text_align', 'auto'));

if (!in_array($content_horizontal, ['left', 'center', 'right'], true)) {
    $content_horizontal = $legacy_position['horizontal'];
}

if (!in_array($content_vertical, ['top', 'center', 'bottom'], true)) {
    $content_vertical = $legacy_position['vertical'];
}

if (!in_array($text_align, ['auto', 'left', 'center', 'right'], true)) {
    $text_align = 'auto';
}

$mobile_image_url = $mobile_image_id ? wp_get_attachment_image_url($mobile_image_id, 'large') : '';
$desktop_video_url = $desktop_video_id ? wp_get_attachment_url($desktop_video_id) : '';

$classes = [
    'home-section',
    'home-hero',
    'home-hero--' . str_replace('_', '-', $layout),
    'home-hero--overlay-' . str_replace('_', '-', $overlay_style),

    // Legacy class kept for backward compatibility during transition.
    'home-hero--content-' . str_replace('_', '-', $legacy_content_position),

    // Hero V2 placement classes.
    'home-hero--x-' . $content_horizontal,
    'home-hero--y-' . $content_vertical,
    'home-hero--text-' . $text_align,
];

if ($desktop_video_url) {
    $classes[] = 'home-hero--has-desktop-video';
} else {
    $classes[] = 'home-hero--image-only';
}

if ($mobile_image_url) {
    $classes[] = 'home-hero--has-image';
}
?>

<section
    class="<?php echo esc_attr(implode(' ', $classes)); ?>"
    style="--mpb-hero-overlay-opacity: <?php echo esc_attr($overlay_opacity_css); ?>;"
>
    <div class="home-hero__media <?php echo $desktop_video_url ? 'has-desktop-video' : ''; ?>" aria-hidden="true">
        <?php if ($mobile_image_url) : ?>
            <div class="home-hero__image-wrap">
                <img
                    class="home-hero__image"
                    src="<?php echo esc_url($mobile_image_url); ?>"
                    alt=""
                    loading="eager"
                    decoding="async"
                >
            </div>
        <?php endif; ?>

        <?php if ($desktop_video_url) : ?>
            <video
                class="home-hero__video"
                src="<?php echo esc_url($desktop_video_url); ?>"
                autoplay
                muted
                loop
                playsinline
            ></video>
        <?php endif; ?>

        <?php if (!$mobile_image_url && !$desktop_video_url) : ?>
            <div class="home-hero__placeholder">
                <span><?php esc_html_e('Add a hero image or video', 'music-project-base'); ?></span>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($layout === 'full_bleed') : ?>
        <div class="home-hero__overlay" aria-hidden="true"></div>
    <?php endif; ?>

    <div class="home-hero__content-shell">
        <div class="home-hero__content">
            <?php if ($heading) : ?>
                <h1><?php echo esc_html($heading); ?></h1>
            <?php endif; ?>

            <?php if ($text) : ?>
                <p><?php echo esc_html($text); ?></p>
            <?php endif; ?>

            <?php if ($cta_text && $cta_url) : ?>
                <a class="home-hero__button" href="<?php echo esc_url($cta_url); ?>">
                    <?php echo esc_html($cta_text); ?>
                </a>
            <?php endif; ?>

            <?php
            get_template_part('template-parts/social-links', null, [
                'context' => 'hero',
            ]);
            ?>
        </div>
    </div>
</section>