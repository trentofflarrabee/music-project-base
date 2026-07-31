<?php
/**
 * Homepage Newsletter / Mailing List section.
 */

if (!defined('ABSPATH')) {
    exit;
}

$plugin_active = function_exists(
    'mpc_get_integration_setting'
);

$enabled = $plugin_active
    ? (bool) mpc_get_integration_setting(
        'newsletter_enabled',
        1
    )
    : true;

if (!$enabled) {
    return;
}

$heading = $plugin_active
    ? trim(
(string) mpc_get_integration_setting(
    'newsletter_heading',
    __('Newsletter', 'music-project-base')
)
    )
    : __('Newsletter', 'music-project-base');

$text = $plugin_active
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

$embed = $plugin_active
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
    class="home-section home-newsletter"
>
    <div class="home-newsletter__inner">
        <?php if ($heading || $text) : ?>
            <header class="section-header">
                <?php if ($heading) : ?>
                    <h2>
                        <?php echo esc_html($heading); ?>
                    </h2>
                <?php endif; ?>

                <?php if ($text) : ?>
                    <p>
                        <?php echo esc_html($text); ?>
                    </p>
                <?php endif; ?>
            </header>
        <?php endif; ?>

        <div class="newsletter-form">
            <?php if ($rendered_embed !== '') : ?>
                <?php
                /*
                 * Integration content was sanitized when saved and rendered
                 * through Core's integration renderer.
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