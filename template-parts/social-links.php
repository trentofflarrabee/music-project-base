<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('mpc_get_social_links') || !function_exists('mpc_get_social_platforms')) {
    return;
}

$social_links = mpc_get_social_links();
$platforms = mpc_get_social_platforms();

$has_links = false;

foreach ($social_links as $url) {
    if (!empty($url)) {
        $has_links = true;
        break;
    }
}

if (!$has_links) {
    return;
}
?>

<ul class="social-links">
    <?php foreach ($platforms as $platform => $data) : ?>
        <?php
        $value = isset($social_links[$platform]) ? $social_links[$platform] : '';

        if (!$value) {
            continue;
        }

        $label = $data['label'];

        if ($data['type'] === 'email') {
            $href = 'mailto:' . antispambot($value);
        } else {
            $href = $value;
        }
        ?>

        <li class="social-links__item social-links__item--<?php echo esc_attr($platform); ?>">
            <a
                class="social-links__link"
                href="<?php echo esc_url($href); ?>"
                <?php echo $data['type'] === 'email' ? '' : 'target="_blank" rel="noopener noreferrer"'; ?>
            >
                <?php echo esc_html($label); ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>