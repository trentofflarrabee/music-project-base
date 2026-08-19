<?php
/**
 * Homepage Shows / Events section.
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
            'shows'
        );
} elseif ($integration_active) {
    $enabled = (bool) mpc_get_integration_setting(
        'shows_enabled',
        1
    );
} else {
    $enabled = true;
}

if (!$enabled) {
    return;
}

/*
 * Presentation is Homepage-owned.
 *
 * Integration fallbacks are retained so Base remains usable
 * with an older Core during an upgrade.
 */
if ($homepage_active) {
    $heading = trim(
        (string) mpc_get_homepage_setting(
            'shows_heading',
            __('Shows', 'music-project-base')
        )
    );

    $heading_size =
        mpb_normalize_homepage_size(
            mpc_get_homepage_setting(
                'shows_heading_size',
                'standard'
            )
        );

    $background =
        mpb_normalize_homepage_background(
            mpc_get_homepage_setting(
                'shows_background',
                'default'
            )
        );
} else {
    $heading = $integration_active
        ? trim(
            (string) mpc_get_integration_setting(
                'shows_heading',
                __('Shows', 'music-project-base')
            )
        )
        : __('Shows', 'music-project-base');

    $heading_size = $integration_active
        ? mpb_normalize_homepage_size(
            mpc_get_integration_setting(
                'shows_heading_size',
                'standard'
            )
        )
        : 'standard';

    $background = 'default';
}

$heading_font_role =
    mpb_get_homepage_section_heading_font_role(
        'shows'
    );

/*
 * The actual Shows source remains Integration-owned.
 */
$embed = $integration_active
    ? trim(
        (string) mpc_get_integration_setting(
            'shows_embed',
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
            'shows'
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
    id="shows"
    class="
        home-section
        home-shows
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
    <?php if ($heading) : ?>
        <header
            class="section-header section-header--size-<?php
                echo esc_attr(
                    $heading_size
                );
            ?>"
        >
            <h2>
                <?php
                echo esc_html($heading);
                ?>
            </h2>
        </header>
    <?php endif; ?>

    <div class="shows-embed">
        <?php if ($rendered_embed !== '') : ?>
            <?php
            /*
             * Integration content was sanitized when saved
             * and rendered through Core's integration renderer.
             */
            echo $rendered_embed; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            ?>
        <?php else : ?>
            <div class="shows-empty">
                <p>
                    <?php
                    esc_html_e(
                        'Add a shows shortcode, trusted provider embed, or supported URL under Music Project → Integrations.',
                        'music-project-base'
                    );
                    ?>
                </p>
            </div>
        <?php endif; ?>
    </div>
</section>