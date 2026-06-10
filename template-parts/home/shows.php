<?php

if (!defined('ABSPATH')) {
    exit;
}

$plugin_active = function_exists('mpc_get_integration_setting');

$enabled = $plugin_active
    ? (bool) mpc_get_integration_setting('shows_enabled', 1)
    : true;

if (!$enabled) {
    return;
}

$heading = $plugin_active
    ? mpc_get_integration_setting('shows_heading', 'Shows')
    : 'Shows';

$embed = $plugin_active
    ? mpc_get_integration_setting('shows_embed', '')
    : '';

?>

<section id="shows" class="home-section home-shows">
    <header class="section-header">
        <h2><?php echo esc_html($heading); ?></h2>
    </header>

    <div class="shows-embed">
        <?php if ($embed) : ?>
            <?php echo do_shortcode($embed); ?>
        <?php else : ?>
            <p>Shows/events embed will render here.</p>
        <?php endif; ?>
    </div>
</section>