<?php
/**
 * Homepage Newsletter / Mailing List section.
 */

if (!defined('ABSPATH')) {
    exit;
}

$homepage_active = function_exists(
    'mpc_get_homepage_setting'
);

$integration_active = function_exists(
    'mpc_get_integration_setting'
);

/*
 * Homepage Section Manager is canonical when available.
 * The integration enable key remains a fallback for older
 * Core versions.
 */
if (
    function_exists(
        'mpc_is_homepage_section_visible'
    )
) {
    $enabled =
        mpc_is_homepage_section_visible(
            'newsletter'
        );
} elseif ($integration_active) {
    $enabled = (bool) mpc_get_integration_setting(
        'newsletter_enabled',
        1
    );
} else {
    $enabled = true;
}

if (!$enabled) {
    return;
}

/*
 * Presentation and supporting Homepage copy are
 * Homepage-owned.
 */
if ($homepage_active) {
    $heading = trim(
        (string) mpc_get_homepage_setting(
            'newsletter_heading',
            __(
                'Newsletter',
                'music-project-base'
            )
        )
    );

    $heading_size =
        mpb_normalize_homepage_size(
            mpc_get_homepage_setting(
                'newsletter_heading_size',
                'standard'
            )
        );

    $text = trim(
        (string) mpc_get_homepage_setting(
            'newsletter_text',
            __(
                'Sign up for updates.',
                'music-project-base'
            )
        )
    );

    $background =
        mpb_normalize_homepage_background(
            mpc_get_homepage_setting(
                'newsletter_background',
                'default'
            )
        );
} else {
    $heading = $integration_active
        ? trim(
            (string) mpc_get_integration_setting(
                'newsletter_heading',
                __(
                    'Newsletter',
                    'music-project-base'
                )
            )
        )
        : __(
            'Newsletter',
            'music-project-base'
        );

    $heading_size = $integration_active
        ? mpb_normalize_homepage_size(
            mpc_get_integration_setting(
                'newsletter_heading_size',
                'standard'
            )
        )
        : 'standard';

    $text = $integration_active
        ? trim(
            (string) mpc_get_integration_setting(
                'newsletter_text',
                __(
                    'Sign up for updates.',
                    'music-project-base'
                )
            )
        )
        : __(
            'Sign up for updates.',
            'music-project-base'
        );

    $background = 'default';
}

$heading_font_role =
    mpb_get_homepage_section_heading_font_role(
        'newsletter'
    );

/*
 * The actual signup source remains Integration-owned.
 */
$embed = $integration_active
    ? trim(
        (string) mpc_get_integration_setting(
            'newsletter_embed',
            ''
        )
    )
    : '';

$rendered_embed = '';

if ($embed !== '') {
    $rendered_embed = function_exists(
        'mpc_render_integration_content'
    )
        ? mpc_render_integration_content(
            $embed,
            'newsletter'
        )
        : do_shortcode($embed);
}

$rendered_embed = trim(
    (string) $rendered_embed
);

if (
    $rendered_embed === ''
    && !current_user_can('manage_options')
) {
    return;
}
?>

<section
    id="signup"
    class="
        home-section
        home-newsletter
        home-section--heading-font-<?php
            echo esc_attr(
                $heading_font_role
            );
        ?>
        home-section--background-<?php
            echo esc_attr(
                $background
            );
        ?>
    "
>
    <div class="home-newsletter__inner">
        <?php if ($heading || $text) : ?>
            <header
                class="section-header section-header--size-<?php
                    echo esc_attr(
                        $heading_size
                    );
                ?>"
            >
                <?php if ($heading) : ?>
                    <h2>
                        <?php
                        echo esc_html($heading);
                        ?>
                    </h2>
                <?php endif; ?>

                <?php if ($text) : ?>
                    <p>
                        <?php
                        echo esc_html($text);
                        ?>
                    </p>
                <?php endif; ?>
            </header>
        <?php endif; ?>

        <div class="newsletter-form">
            <?php if ($rendered_embed !== '') : ?>
                <?php
                /*
                 * Integration content was sanitized when saved
                 * and rendered through Core's integration renderer.
                 */
                echo $rendered_embed; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                ?>
            <?php else : ?>
                <div class="home-newsletter__empty">
                    <p>
                        <?php
                        esc_html_e(
                            'Add a newsletter shortcode, trusted provider embed, or supported URL under Music Project → Integrations.',
                            'music-project-base'
                        );
                        ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>