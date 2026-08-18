<?php
/**
 * Homepage Services Section
 */

$settings = function_exists('mpc_get_homepage_settings')
    ? mpc_get_homepage_settings()
    : [];

$heading = $settings['services_heading'] ?? __('Services', 'music-project-base');
$heading_size = mpb_normalize_homepage_size(
    $settings['services_heading_size']
        ?? 'standard'
);
$heading_font_role =
    mpb_get_homepage_section_heading_font_role(
        'services'
    );

$card_heading_font_role =
    mpb_resolve_homepage_font_role(
        $settings[
            'services_card_heading_font_role'
        ] ?? 'default',
        $heading_font_role
    );
$intro = $settings['services_intro'] ?? '';
$layout = $settings['services_layout'] ?? 'grid';
$columns = $settings['services_columns'] ?? '3';
$cta_text = $settings['services_cta_text'] ?? '';
$cta_url = $settings['services_cta_url'] ?? '';
$items = $settings['services_items'] ?? [];

$allowed_layouts = ['grid', 'featured_first', 'compact'];
$layout = in_array($layout, $allowed_layouts, true) ? $layout : 'grid';

$allowed_columns = ['2', '3', '4'];
$columns = in_array((string) $columns, $allowed_columns, true) ? (string) $columns : '3';

$items = is_array($items) ? $items : [];

$items = array_filter($items, function ($item) {
    $title = $item['title'] ?? '';
    $description = $item['description'] ?? '';

    return trim($title) || trim($description);
});

if (!$items && !current_user_can('manage_options')) {
    return;
}
?>

<section
    id="services"
    class="
        home-section
        home-services
        home-section--heading-font-<?php
            echo esc_attr(
                $heading_font_role
            );
        ?>
        home-services--card-heading-font-<?php
            echo esc_attr(
                $card_heading_font_role
            );
        ?>
        home-services--layout-<?php
            echo esc_attr($layout);
        ?>
        home-services--columns-<?php
            echo esc_attr($columns);
        ?>
    "
    style="--mpb-services-columns: <?php echo esc_attr($columns); ?>;"
>
    <?php if ($heading || $intro) : ?>
<header
    class="section-header home-services__header section-header--size-<?php echo esc_attr(
        $heading_size
    ); ?>"
>            <?php if ($heading) : ?>
                <h2><?php echo esc_html($heading); ?></h2>
            <?php endif; ?>

            <?php if ($intro) : ?>
                <div class="home-services__intro section-header__content">
                    <?php echo wp_kses_post(wpautop($intro)); ?>
                </div>
            <?php endif; ?>
        </header>
    <?php endif; ?>

    <?php if ($items) : ?>
        <div class="home-services__grid">
            <?php foreach ($items as $item) : ?>
                <?php
                $title = $item['title'] ?? '';
                $description = $item['description'] ?? '';
                $link_text = $item['link_text'] ?? '';
                $link_url = $item['link_url'] ?? '';
                ?>

                <article class="home-service-card">
                    <?php if ($title) : ?>
                        <h3><?php echo esc_html($title); ?></h3>
                    <?php endif; ?>

                    <?php if ($description) : ?>
                        <div class="home-service-card__description">
                            <?php echo wp_kses_post(wpautop($description)); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($link_url) : ?>
                        <a class="home-service-card__link" href="<?php echo esc_url($link_url); ?>">
                            <?php echo esc_html($link_text ?: __('Learn More', 'music-project-base')); ?>
                        </a>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    <?php elseif (current_user_can('manage_options')) : ?>
        <div class="home-services__empty">
            <p><?php esc_html_e('Add service items in Music Project → Homepage.', 'music-project-base'); ?></p>
        </div>
    <?php endif; ?>

    <?php if ($cta_text && $cta_url) : ?>
        <div class="home-services__cta">
            <a class="button" href="<?php echo esc_url($cta_url); ?>">
                <?php echo esc_html($cta_text); ?>
            </a>
        </div>
    <?php endif; ?>
</section>