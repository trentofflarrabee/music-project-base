<?php

if (!defined('ABSPATH')) {
    exit;
}

function mpb_setup_theme() {
    load_theme_textdomain(
        'music-project-base',
        get_template_directory() . '/languages'
    );

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');

    add_theme_support('custom-logo', [
        'height'      => 120,
        'width'       => 420,
        'flex-height' => true,
        'flex-width'  => true,
    ]);

    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ]);

    register_nav_menus([
        'primary' => __('Primary Menu', 'music-project-base'),
        'footer'  => __('Footer Menu', 'music-project-base'),
    ]);
}
add_action('after_setup_theme', 'mpb_setup_theme');

/**
 * Get a cache-busting version for a theme asset.
 *
 * The file modification time updates automatically whenever the asset is
 * overwritten. The theme version remains as a safe fallback.
 *
 * @param string $absolute_path Absolute filesystem path to the asset.
 * @return string
 */
function mpb_get_asset_version($absolute_path) {
    if (is_string($absolute_path) && file_exists($absolute_path)) {
        $modified_time = filemtime($absolute_path);

        if ($modified_time) {
            return (string) $modified_time;
        }
    }

    $theme = wp_get_theme(get_template());
    $version = $theme->get('Version');

    return $version ?: '1.2.0';
}

/**
 * Enqueue frontend styles and scripts.
 *
 * The Base stylesheet always loads from the parent theme. When a child theme
 * is active, its stylesheet loads afterward so project-specific overrides do
 * not replace the Base presentation layer.
 *
 * @return void
 */
function mpb_enqueue_assets() {
    $theme_style = function_exists('mpc_get_theme_style_settings')
        ? mpc_get_theme_style_settings()
        : [];

    $google_fonts_url = isset($theme_style['google_fonts_url'])
        ? $theme_style['google_fonts_url']
        : '';

    if ($google_fonts_url) {
        wp_enqueue_style(
            'mpb-google-fonts',
            esc_url($google_fonts_url),
            [],
            null
        );
    }

    $parent_style_path =
        get_template_directory() . '/style.css';

    wp_enqueue_style(
        'mpb-style',
        get_template_directory_uri() . '/style.css',
        [],
        mpb_get_asset_version(
            $parent_style_path
        )
    );

    wp_add_inline_style(
        'mpb-style',
        mpb_get_theme_style_inline_css()
    );

    if (
        get_stylesheet_directory()
        !== get_template_directory()
    ) {
        $child_style_path =
            get_stylesheet_directory() . '/style.css';

        if (file_exists($child_style_path)) {
            wp_enqueue_style(
                'mpb-child-style',
                get_stylesheet_uri(),
                [
                    'mpb-style',
                ],
                mpb_get_asset_version(
                    $child_style_path
                )
            );
        }
    }

    /*
     * Link Hub intentionally has no primary site navigation, so do not load
     * navigation behavior on that lightweight microsite request.
     */
    if (
        !function_exists('mpb_is_link_hub_request')
        || !mpb_is_link_hub_request()
    ) {
        wp_enqueue_script(
            'mpb-navigation',
            get_template_directory_uri()
                . '/assets/js/navigation.js',
            [],
            mpb_get_asset_version(
                get_template_directory()
                    . '/assets/js/navigation.js'
            ),
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'mpb_enqueue_assets');

function mpb_get_theme_style_defaults() {
    return [
        'color_background' => '#111111',
        'color_surface' => '#101010',
        'color_text' => '#f5f5f5',
        'color_muted' => '#b8b8b8',
        'color_accent' => '#ffffff',
        'color_button_background' => '#f5f5f5',
        'color_button_text' => '#111111',

        // Site chrome.
        'header_background_color' => '#000000',
        'header_text_color' => '#f5f5f5',
        'header_border_color' => '#1f1f1f',

        'header_behavior' => 'standard',
        'brand_display' => 'logo_name',

        'mobile_nav_background_color' => '#000000',
        'mobile_nav_text_color' => '#f5f5f5',
        'mobile_nav_border_color' => '#242424',

        'footer_background_color' => '#000000',
        'footer_text_color' => '#f5f5f5',
        'footer_muted_color' => '#b8b8b8',
        'footer_border_color' => '#1f1f1f',

'font_body' => 'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif',
'font_heading' => 'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif',
'font_accent' => 'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif',
'font_quote' => '',

'font_role_body' => 'body',
'font_role_heading' => 'heading',
'font_role_blog_heading' => 'heading',
'font_role_blog_body' => 'body',
'font_role_hero_heading' => 'heading',
'font_role_nav' => 'accent',
'font_role_brand' => 'accent',
'font_role_button' => 'accent',
'font_role_accent' => 'accent',
'font_role_quote' => 'quote',

// Editorial presentation.
'blog_body_size' => 'standard',

'heading_text_transform' => 'none',        
        'heading_letter_spacing' => '-0.04em',

        'heading_alignment_scope' => 'none',

        'hero_heading_color' => '#ffffff',
        'hero_lead_color' => '#f5f5f5',
        'hero_text_shadow' => 'subtle',
        'hero_text_shadow_color' => '#000000',

        'corner_style' => 'rounded',
'card_shadow_style' => 'standard',
'border_strength' => 'subtle',


    'texture_enabled'            => 0,
    'texture_image_id'           => 0,
'texture_opacity'            => '0.72',
    'texture_size'               => '420px',
    'texture_repeat'             => 'repeat',

    // Texture V2 environmental zones.
    'texture_apply_header'       => 0,
    'texture_apply_mobile_nav'   => 1,
    'texture_apply_footer'       => 1,
    'texture_apply_sections'     => 0,
    'texture_apply_editorial'    => 0,

    // Legacy keys retained for compatibility with older saved data.
    'texture_apply_body'         => 1,
    'texture_apply_buttons'      => 0,
    'texture_apply_cards'        => 0,
    'texture_apply_media_frames' => 0,

];
}

function mpb_get_theme_style_settings() {
    $defaults = mpb_get_theme_style_defaults();

    if (!function_exists('mpc_get_theme_style_settings')) {
        return $defaults;
    }

    $settings = mpc_get_theme_style_settings();

    if (!is_array($settings)) {
        $settings = [];
    }

    return wp_parse_args($settings, $defaults);
}

function mpb_clean_font_family($value) {
    $value = is_string($value) ? wp_strip_all_tags($value) : '';
    $value = preg_replace('/[^a-zA-Z0-9\s,\-_"\'().]/', '', $value);

    return trim($value);
}

/**
 * Resolve a typography role to one of the configured font slots.
 */
function mpb_resolve_font_role($role, array $font_slots, $fallback_role = 'body') {
    $role = sanitize_key((string) $role);
    $fallback_role = sanitize_key((string) $fallback_role);

    if (isset($font_slots[$role]) && $font_slots[$role]) {
        return $font_slots[$role];
    }

    if (isset($font_slots[$fallback_role]) && $font_slots[$fallback_role]) {
        return $font_slots[$fallback_role];
    }

    return $font_slots['body'] ?? 'system-ui, sans-serif';
}

function mpb_hex_to_rgb_channels($hex) {
    $hex = sanitize_hex_color($hex);

    if (!$hex) {
        return '0, 0, 0';
    }

    $hex = ltrim($hex, '#');

    if (strlen($hex) === 3) {
        $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
    }

    if (strlen($hex) !== 6) {
        return '0, 0, 0';
    }

    return hexdec(substr($hex, 0, 2)) . ', ' .
        hexdec(substr($hex, 2, 2)) . ', ' .
        hexdec(substr($hex, 4, 2));
}

function mpb_get_theme_style_inline_css() {
    $settings = mpb_get_theme_style_settings();

    $texture_url = 'none';

    if (!empty($settings['texture_enabled']) && !empty($settings['texture_image_id'])) {
        $image_url = wp_get_attachment_image_url(absint($settings['texture_image_id']), 'full');

        if ($image_url) {
            $texture_url = 'url("' . esc_url($image_url) . '")';
        }
    }

$color_background = sanitize_hex_color(
    $settings['color_background']
) ?: '#111111';

$color_surface = sanitize_hex_color(
    $settings['color_surface']
) ?: '#101010';

$color_text = sanitize_hex_color(
    $settings['color_text']
) ?: '#f5f5f5';

$color_muted = sanitize_hex_color(
    $settings['color_muted']
) ?: '#b8b8b8';

$color_accent = sanitize_hex_color(
    $settings['color_accent']
) ?: '#ffffff';

$color_button_background = sanitize_hex_color(
    $settings['color_button_background']
) ?: '#f5f5f5';

$color_button_text = sanitize_hex_color(
    $settings['color_button_text']
) ?: '#111111';

$color_text_rgb = mpb_hex_to_rgb_channels(
    $color_text
);

$color_button_text_rgb = mpb_hex_to_rgb_channels(
    $color_button_text
);


$header_background_color = sanitize_hex_color(
    $settings['header_background_color'] ?? ''
) ?: '#000000';

$header_text_color = sanitize_hex_color(
    $settings['header_text_color'] ?? ''
) ?: '#f5f5f5';

$header_border_color = sanitize_hex_color(
    $settings['header_border_color'] ?? ''
) ?: '#1f1f1f';

$mobile_nav_background_color = sanitize_hex_color(
    $settings['mobile_nav_background_color'] ?? ''
) ?: '#000000';

$mobile_nav_text_color = sanitize_hex_color(
    $settings['mobile_nav_text_color'] ?? ''
) ?: '#f5f5f5';

$mobile_nav_border_color = sanitize_hex_color(
    $settings['mobile_nav_border_color'] ?? ''
) ?: '#242424';

$footer_background_color = sanitize_hex_color(
    $settings['footer_background_color'] ?? ''
) ?: '#000000';

$footer_text_color = sanitize_hex_color(
    $settings['footer_text_color'] ?? ''
) ?: '#f5f5f5';

$footer_muted_color = sanitize_hex_color(
    $settings['footer_muted_color'] ?? ''
) ?: '#b8b8b8';

$footer_border_color = sanitize_hex_color(
    $settings['footer_border_color'] ?? ''
) ?: '#1f1f1f';

$header_text_rgb = mpb_hex_to_rgb_channels(
    $header_text_color
);

$mobile_nav_text_rgb = mpb_hex_to_rgb_channels(
    $mobile_nav_text_color
);

$footer_text_rgb = mpb_hex_to_rgb_channels(
    $footer_text_color
);

// Font library slots.
$font_slot_body = mpb_clean_font_family($settings['font_body'] ?? '');
$font_slot_display = mpb_clean_font_family($settings['font_heading'] ?? '');
$font_slot_accent = mpb_clean_font_family($settings['font_accent'] ?? '');
$font_slot_quote = mpb_clean_font_family($settings['font_quote'] ?? '');

if (!$font_slot_body) {
    $font_slot_body = 'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif';
}

if (!$font_slot_display) {
    $font_slot_display = $font_slot_body;
}

if (!$font_slot_accent) {
    $font_slot_accent = $font_slot_body;
}

if (!$font_slot_quote) {
    $font_slot_quote = $font_slot_display;
}

/*
 * Semantic roles reference the font-slot variables rather than copying
 * their current font-family values. This preserves the distinction between
 * the configured font library and the roles assigned to that library.
 */
$font_slot_references = [
    'body'    => 'var(--mpb-font-slot-body)',
    'heading' => 'var(--mpb-font-slot-display)',
    'accent'  => 'var(--mpb-font-slot-accent)',
    'quote'   => 'var(--mpb-font-slot-quote)',
];

// Assigned typography roles.
$font_body = mpb_resolve_font_role(
    $settings['font_role_body'] ?? 'body',
    $font_slot_references,
    'body'
);

$font_heading = mpb_resolve_font_role(
    $settings['font_role_heading'] ?? 'heading',
    $font_slot_references,
    'heading'
);

$font_blog_heading = mpb_resolve_font_role(
    $settings['font_role_blog_heading'] ?? 'heading',
    $font_slot_references,
    'heading'
);

$font_blog_body = mpb_resolve_font_role(
    $settings['font_role_blog_body'] ?? 'body',
    $font_slot_references,
    'body'
);

$font_hero_heading = mpb_resolve_font_role(
    $settings['font_role_hero_heading'] ?? 'heading',
    $font_slot_references,
    'heading'
);

$font_nav = mpb_resolve_font_role(
    $settings['font_role_nav'] ?? 'accent',
    $font_slot_references,
    'accent'
);

$font_brand = mpb_resolve_font_role(
    $settings['font_role_brand'] ?? 'accent',
    $font_slot_references,
    'accent'
);

$font_button = mpb_resolve_font_role(
    $settings['font_role_button'] ?? 'accent',
    $font_slot_references,
    'accent'
);

$font_accent = mpb_resolve_font_role(
    $settings['font_role_accent'] ?? 'accent',
    $font_slot_references,
    'accent'
);

$font_quote = mpb_resolve_font_role(
    $settings['font_role_quote'] ?? 'quote',
    $font_slot_references,
    'quote'
);

$blog_body_size = sanitize_key(
    $settings['blog_body_size'] ?? 'standard'
);

$blog_body_size_map = [
    'compact' => 'clamp(1.0625rem, 1.03125rem + 0.2vw, 1.125rem)',
    'standard' => 'clamp(1.125rem, 1.0625rem + 0.4vw, 1.25rem)',
    'large' => 'clamp(1.125rem, 1rem + 0.8vw, 1.375rem)',
];

$blog_body_size_css = $blog_body_size_map[$blog_body_size]
    ?? $blog_body_size_map['standard'];

    $heading_text_transform = sanitize_key($settings['heading_text_transform']);
    $heading_letter_spacing = esc_html($settings['heading_letter_spacing']);

$hero_heading_color = sanitize_hex_color(
    $settings['hero_heading_color'] ?? ''
) ?: '#ffffff';

$hero_lead_color = sanitize_hex_color(
    $settings['hero_lead_color'] ?? ''
) ?: '#f5f5f5';

$hero_heading_color_rgb = mpb_hex_to_rgb_channels(
    $hero_heading_color
);

$hero_text_shadow = sanitize_key($settings['hero_text_shadow'] ?? 'subtle');

$hero_text_shadow_color = sanitize_hex_color($settings['hero_text_shadow_color'] ?? '') ?: '#000000';
$hero_text_shadow_rgb = function_exists('mpb_hex_to_rgb_channels')
    ? mpb_hex_to_rgb_channels($hero_text_shadow_color)
    : '0, 0, 0';

$hero_text_shadow_map = [
    'none' => 'none',

    'subtle' => "0 1px 2px rgba({$hero_text_shadow_rgb}, 0.65), 0 8px 24px rgba({$hero_text_shadow_rgb}, 0.35)",

    'strong' => "0 2px 4px rgba({$hero_text_shadow_rgb}, 0.85), 0 12px 36px rgba({$hero_text_shadow_rgb}, 0.75), 0 0 1px rgba({$hero_text_shadow_rgb}, 0.95)",
];

$hero_text_shadow_css = $hero_text_shadow_map[$hero_text_shadow] ?? $hero_text_shadow_map['subtle'];


$corner_style = sanitize_key($settings['corner_style'] ?? 'rounded');

$corner_style_map = [
    'sharp' => [
        'card' => '0',
        'media' => '0',
        'control' => '0',
    ],
    'subtle' => [
        'card' => '6px',
        'media' => '4px',
        'control' => '4px',
    ],
    'rounded' => [
        'card' => '16px',
        'media' => '12px',
        'control' => '999px',
    ],
    'soft' => [
        'card' => '28px',
        'media' => '22px',
        'control' => '999px',
    ],
];

$corner_values = $corner_style_map[$corner_style] ?? $corner_style_map['rounded'];

$card_shadow_style = sanitize_key($settings['card_shadow_style'] ?? 'standard');

$card_shadow_style_map = [
    'none' => [
        'card' => 'none',
        'hover' => 'none',
        'transform' => 'none',
    ],
    'subtle' => [
        'card' => '0 8px 24px rgba(0, 0, 0, 0.14)',
        'hover' => '0 12px 32px rgba(0, 0, 0, 0.18)',
        'transform' => 'translateY(-1px)',
    ],
    'standard' => [
        'card' => '0 20px 70px rgba(0, 0, 0, 0.22)',
        'hover' => '0 24px 80px rgba(0, 0, 0, 0.32)',
        'transform' => 'translateY(-2px)',
    ],
    'dramatic' => [
        'card' => '0 30px 90px rgba(0, 0, 0, 0.34)',
        'hover' => '0 38px 120px rgba(0, 0, 0, 0.46)',
        'transform' => 'translateY(-3px)',
    ],
];

$shadow_values = $card_shadow_style_map[$card_shadow_style] ?? $card_shadow_style_map['standard'];

$border_strength = sanitize_key(
    $settings['border_strength'] ?? 'subtle'
);

$border_strength_map = [
    'minimal' => [
        'subtle' => '0.03',
        'medium' => '0.08',
        'strong' => '0.14',
    ],
    'subtle' => [
        'subtle' => '0.06',
        'medium' => '0.14',
        'strong' => '0.22',
    ],
    'defined' => [
        'subtle' => '0.12',
        'medium' => '0.22',
        'strong' => '0.34',
    ],
];

$border_values = $border_strength_map[$border_strength]
    ?? $border_strength_map['subtle'];



$texture_opacity = is_numeric($settings['texture_opacity'])
    ? max(0, min(1, (float) $settings['texture_opacity']))
    : 0.72;

    $texture_size = esc_html($settings['texture_size']);
    $texture_repeat = esc_html($settings['texture_repeat']);

    return "
        :root {
            --mpb-color-bg: {$color_background};
            --mpb-color-surface: {$color_surface};
            --mpb-color-text: {$color_text};
            --mpb-color-text-rgb: {$color_text_rgb};
            --mpb-color-muted: {$color_muted};
            --mpb-color-accent: {$color_accent};

            --mpb-color-button-bg: {$color_button_background};
            --mpb-color-button-text: {$color_button_text};
            --mpb-color-button-text-rgb: {$color_button_text_rgb};

            --mpb-color-header-bg: {$header_background_color};
            --mpb-color-header-text: {$header_text_color};
            --mpb-color-header-text-rgb: {$header_text_rgb};
            --mpb-color-header-border: {$header_border_color};

            --mpb-color-mobile-nav-bg: {$mobile_nav_background_color};
            --mpb-color-mobile-nav-text: {$mobile_nav_text_color};
            --mpb-color-mobile-nav-text-rgb: {$mobile_nav_text_rgb};
            --mpb-color-mobile-nav-border: {$mobile_nav_border_color};

            --mpb-color-footer-bg: {$footer_background_color};
            --mpb-color-footer-text: {$footer_text_color};
            --mpb-color-footer-text-rgb: {$footer_text_rgb};
            --mpb-color-footer-muted: {$footer_muted_color};
            --mpb-color-footer-border: {$footer_border_color};

            /* Font library slots. */
            --mpb-font-slot-body: {$font_slot_body};
            --mpb-font-slot-display: {$font_slot_display};
            --mpb-font-slot-accent: {$font_slot_accent};
            --mpb-font-slot-quote: {$font_slot_quote};

            /* Assigned typography roles. */
            --mpb-font-body: {$font_body};
            --mpb-font-heading: {$font_heading};
            --mpb-font-blog-heading: {$font_blog_heading};
            --mpb-font-blog-body: {$font_blog_body};
            --mpb-font-hero-heading: {$font_hero_heading};
            --mpb-font-nav: {$font_nav};
            --mpb-font-brand: {$font_brand};
            --mpb-font-button: {$font_button};
            --mpb-font-accent: {$font_accent};
            --mpb-font-quote: {$font_quote};
            --mpb-font-blog-body-size: {$blog_body_size_css};
            --mpb-heading-text-transform: {$heading_text_transform};
            --mpb-heading-letter-spacing: {$heading_letter_spacing};

            --mpb-hero-heading-color: {$hero_heading_color};
            --mpb-hero-heading-color-rgb: {$hero_heading_color_rgb};
            --mpb-hero-lead-color: {$hero_lead_color};

            --mpb-hero-text-shadow-color: {$hero_text_shadow_color};
            --mpb-hero-text-shadow-color-rgb: {$hero_text_shadow_rgb};
            --mpb-hero-text-shadow: {$hero_text_shadow_css};

            --mpb-radius-card: {$corner_values['card']};
            --mpb-radius-media: {$corner_values['media']};
            --mpb-radius-control: {$corner_values['control']};

            --mpb-shadow-card: {$shadow_values['card']};
            --mpb-shadow-card-hover: {$shadow_values['hover']};
            --mpb-card-hover-transform: {$shadow_values['transform']};

            --mpb-border-alpha-subtle: {$border_values['subtle']};
            --mpb-border-alpha-medium: {$border_values['medium']};
            --mpb-border-alpha-strong: {$border_values['strong']};

            --mpb-border-subtle:
                rgba(
                    var(--mpb-color-text-rgb),
                    var(--mpb-border-alpha-subtle)
                );

            --mpb-border-medium:
                rgba(
                    var(--mpb-color-text-rgb),
                    var(--mpb-border-alpha-medium)
                );

            --mpb-border-strong:
                rgba(
                    var(--mpb-color-text-rgb),
                    var(--mpb-border-alpha-strong)
                );


            --mpb-texture-image: {$texture_url};
            --mpb-texture-opacity: {$texture_opacity};
            --mpb-texture-size: {$texture_size};
            --mpb-texture-repeat: {$texture_repeat};
        }
    ";
}

function mpb_theme_style_body_classes($classes) {
    $settings = mpb_get_theme_style_settings();

    $heading_alignment_scope = $settings['heading_alignment_scope'] ?? 'none';

    if ($heading_alignment_scope === 'home') {
        $classes[] = 'mpb-heading-align-home';
    }

    if ($heading_alignment_scope === 'all') {
        $classes[] = 'mpb-heading-align-all';
    }

    /**
     * Header behavior.
     *
     * Transparent modes only apply visually on the front page via CSS.
     */
    $allowed_header_behaviors = [
        'standard',
        'sticky',
        'transparent',
        'transparent_scroll',
    ];

    $header_behavior = sanitize_key($settings['header_behavior'] ?? 'standard');

    if (!in_array($header_behavior, $allowed_header_behaviors, true)) {
        $header_behavior = 'standard';
    }

    $classes[] = 'mpb-header-' . str_replace('_', '-', $header_behavior);

    if (is_front_page()) {
        $classes[] = 'mpb-is-front-page';
    }

    /**
     * Brand display.
     */
    $allowed_brand_displays = [
        'logo_name',
        'logo_only',
        'name_only',
        'hidden',
    ];

    $brand_display = sanitize_key($settings['brand_display'] ?? 'logo_name');

    if (!in_array($brand_display, $allowed_brand_displays, true)) {
        $brand_display = 'logo_name';
    }

    $classes[] = 'mpb-brand-' . str_replace('_', '-', $brand_display);

    if ( ! empty( $settings['texture_enabled'] ) && ! empty( $settings['texture_image_id'] ) ) {
    $classes[] = 'mpb-texture-enabled';

    if ( ! empty( $settings['texture_apply_header'] ) ) {
        $classes[] = 'mpb-texture-header';
    }
    if ( ! empty( $settings['texture_apply_mobile_nav'] ) ) {
        $classes[] = 'mpb-texture-mobile-nav';
    }
    if ( ! empty( $settings['texture_apply_footer'] ) ) {
        $classes[] = 'mpb-texture-footer';
    }
    if ( ! empty( $settings['texture_apply_sections'] ) ) {
        $classes[] = 'mpb-texture-sections';
    }
    if ( ! empty( $settings['texture_apply_editorial'] ) ) {
        $classes[] = 'mpb-texture-editorial';
    }
}

    return $classes;
}
add_filter('body_class', 'mpb_theme_style_body_classes');
require_once get_template_directory() . '/inc/template-functions.php';
require_once get_template_directory() . '/inc/social-links.php';
require_once get_template_directory() . '/inc/link-hub.php';