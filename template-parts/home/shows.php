<?php
/**
 * Homepage Shows / Events section.
 */

if (!defined('ABSPATH')) {
    exit;
}

$plugin_active = function_exists(
    'mpc_get_integration_setting'
);

$enabled = $plugin_active
    ? (bool) mpc_get_integration_setting(
        'shows_enabled',
        1
    )
    : true;

if (!$enabled) {
    return;
}

$heading = $plugin_active
    ? trim(
        (string) mpc_get_integration_setting(
            'shows_heading',
            __('Shows', 'music-project-base')
        )
    )
    : __('Shows', 'music-project-base');

    $heading_size = $plugin_active
    ? mpb_normalize_homepage_size(
        mpc_get_integration_setting(
            'shows_heading_size',
            'standard'
        )
    )
    : 'standard';

$embed = $plugin_active
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
    class="home-section home-shows"
>
    <?php if ($heading) : ?>
       <header
    class="section-header section-header--size-<?php echo esc_attr(
        $heading_size
    ); ?>"
>
    <?php endif; ?>

    <div class="shows-embed">
        <?php if ($rendered_embed !== '') : ?>
            <?php
            /*
             * Integration content was sanitized when saved and rendered
             * through Core's integration renderer.
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