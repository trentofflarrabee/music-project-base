<?php

if (!defined('ABSPATH')) {
    exit;
}

$plugin_active = function_exists('mpc_get_integration_setting');

$enabled = $plugin_active
    ? (bool) mpc_get_integration_setting('newsletter_enabled', 1)
    : true;

if (!$enabled) {
    return;
}

$heading = $plugin_active
    ? mpc_get_integration_setting('newsletter_heading', 'Newsletter')
    : 'Newsletter';

$text = $plugin_active
    ? mpc_get_integration_setting('newsletter_text', 'Sign up for updates.')
    : 'Sign up for updates.';

$embed = $plugin_active
    ? mpc_get_integration_setting('newsletter_embed', '')
    : '';

?>

<section id="signup" class="home-section home-newsletter">
    <div class="home-newsletter__inner">
        <header class="section-header">
            <?php if ($heading) : ?>
                <h2><?php echo esc_html($heading); ?></h2>
            <?php endif; ?>

            <?php if ($text) : ?>
                <p><?php echo esc_html($text); ?></p>
            <?php endif; ?>
        </header>

        <div class="newsletter-form">
            <?php if ($embed) : ?>
                <?php echo do_shortcode($embed); ?>
            <?php else : ?>
                <p>Newsletter form/embed will render here.</p>
            <?php endif; ?>
        </div>
    </div>
</section>