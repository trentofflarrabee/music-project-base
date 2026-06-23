<?php
/**
 * Homepage Quotes / Testimonials Section
 */

if (!defined('ABSPATH')) {
    exit;
}

$settings = function_exists('mpc_get_homepage_settings')
    ? mpc_get_homepage_settings()
    : [];

$heading = trim((string) ($settings['quotes_heading'] ?? __('Kind Words', 'music-project-base')));
$intro = trim((string) ($settings['quotes_intro'] ?? ''));
$layout = sanitize_key((string) ($settings['quotes_layout'] ?? 'grid'));
$count = absint($settings['quotes_count'] ?? 3);
$featured_only = !empty($settings['quotes_featured_only']);
$tone = sanitize_key((string) ($settings['quotes_background_tone'] ?? 'surface'));

if (!in_array($layout, ['single', 'grid', 'featured_first'], true)) {
    $layout = 'grid';
}

if (!in_array($tone, ['default', 'surface', 'contrast'], true)) {
    $tone = 'surface';
}

$count = min(12, max(1, $count));

$query_args = [
    'post_type' => 'mpc_press_quote',
    'post_status' => 'publish',
    'posts_per_page' => $layout === 'single' ? 1 : $count,
    'no_found_rows' => true,
    'orderby' => [
        'menu_order' => 'ASC',
        'date' => 'DESC',
    ],
];

if ($featured_only) {
    $query_args['meta_query'] = [
        [
            'key' => '_mpc_press_quote_featured',
            'value' => ['1', 'yes', 'true', 'on'],
            'compare' => 'IN',
        ],
    ];
}

$quotes_query = new WP_Query($query_args);

if (!$quotes_query->have_posts() && !current_user_can('manage_options')) {
    return;
}

$get_quote_meta = static function ($post_id, $keys) {
    foreach ($keys as $key) {
        $value = get_post_meta($post_id, $key, true);

        if ($value !== '') {
            return $value;
        }
    }

    return '';
};

$classes = [
    'home-section',
    'home-quotes',
    'home-quotes--layout-' . $layout,
    'home-quotes--tone-' . $tone,
];

if ($featured_only) {
    $classes[] = 'home-quotes--featured-only';
}
?>

<section id="quotes" class="<?php echo esc_attr(implode(' ', $classes)); ?>">
    <div class="home-quotes__inner">
        <?php if ($heading || $intro) : ?>
            <header class="section-header home-quotes__header">
                <?php if ($heading) : ?>
                    <h2><?php echo esc_html($heading); ?></h2>
                <?php endif; ?>

                <?php if ($intro) : ?>
                    <p><?php echo esc_html($intro); ?></p>
                <?php endif; ?>
            </header>
        <?php endif; ?>

        <?php if ($quotes_query->have_posts()) : ?>
            <div class="home-quotes__grid">
                <?php while ($quotes_query->have_posts()) : ?>
                    <?php
                    $quotes_query->the_post();

                    $quote_id = get_the_ID();

                    $quote_text = $get_quote_meta($quote_id, [
                        '_mpc_press_quote_text',
                        '_mpc_quote_text',
                    ]);

                    if (!$quote_text) {
                        $quote_text = get_the_excerpt();

                        if (!$quote_text) {
                            $quote_text = wp_strip_all_tags(get_the_content());
                        }
                    }

                    $quote_source = $get_quote_meta($quote_id, [
                        '_mpc_press_quote_source',
                        '_mpc_press_quote_client',
                        '_mpc_quote_source',
                    ]);

                    $quote_context = $get_quote_meta($quote_id, [
                        '_mpc_press_quote_context',
                        '_mpc_press_quote_publication',
                        '_mpc_press_quote_role',
                        '_mpc_quote_context',
                    ]);
                    ?>

                    <article class="home-quote-card">
                        <?php if ($quote_text) : ?>
                            <blockquote class="home-quote-card__quote">
                                <?php echo esc_html($quote_text); ?>
                            </blockquote>
                        <?php endif; ?>

                        <?php if ($quote_source || $quote_context) : ?>
                            <footer class="home-quote-card__footer">
                                <?php if ($quote_source) : ?>
                                    <cite><?php echo esc_html($quote_source); ?></cite>
                                <?php endif; ?>

                                <?php if ($quote_context) : ?>
                                    <span><?php echo esc_html($quote_context); ?></span>
                                <?php endif; ?>
                            </footer>
                        <?php endif; ?>
                    </article>
                <?php endwhile; ?>
            </div>

            <?php wp_reset_postdata(); ?>
        <?php elseif (current_user_can('manage_options')) : ?>
            <div class="home-quotes__empty">
                <p>
                    <?php esc_html_e('Add quotes/testimonials in Music Project → Quotes / Testimonials.', 'music-project-base'); ?>
                </p>
            </div>
        <?php endif; ?>
    </div>
</section>