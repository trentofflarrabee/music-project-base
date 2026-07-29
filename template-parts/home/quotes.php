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

$heading = trim(
    (string) ($settings['quotes_heading'] ?? __('Kind Words', 'music-project-base'))
);

$intro = trim((string) ($settings['quotes_intro'] ?? ''));
$layout = sanitize_key((string) ($settings['quotes_layout'] ?? 'grid'));
$count = absint($settings['quotes_count'] ?? 3);
$featured_only = !empty($settings['quotes_featured_only']);
$show_attribution = !empty($settings['quotes_show_attribution']);
$tone = sanitize_key(
    (string) ($settings['quotes_background_tone'] ?? 'surface')
);

if (!in_array($layout, ['single', 'grid', 'featured_first'], true)) {
    $layout = 'grid';
}

if (!in_array($tone, ['default', 'surface', 'contrast'], true)) {
    $tone = 'surface';
}

$count = min(12, max(1, $count));

$base_query_args = [
    'post_type'      => 'mpc_press_quote',
    'post_status'    => 'publish',
    'no_found_rows'  => true,
    'orderby'        => [
        'menu_order' => 'ASC',
        'date'       => 'DESC',
    ],
];

$featured_meta_query = [
    [
        'key'     => '_mpc_press_quote_featured',
        'value'   => ['1', 'yes', 'true', 'on'],
        'compare' => 'IN',
    ],
];

$quotes = [];
$featured_quote = null;
$secondary_quotes = [];

/*
 * Featured First is intentionally rendered as two distinct areas:
 *
 * 1. One featured quote in a full-width row.
 * 2. Remaining quotes in a standard grid below it.
 *
 * When no quote is explicitly featured, the first normally ordered quote
 * is promoted so the selected layout remains useful.
 */
if ($layout === 'featured_first') {
    $featured_query_args = $base_query_args;
    $featured_query_args['posts_per_page'] = 1;
    $featured_query_args['meta_query'] = $featured_meta_query;

    $featured_quotes = get_posts($featured_query_args);

    if (!empty($featured_quotes)) {
        $featured_quote = $featured_quotes[0];
    } elseif (!$featured_only) {
        $fallback_query_args = $base_query_args;
        $fallback_query_args['posts_per_page'] = 1;

        $fallback_quotes = get_posts($fallback_query_args);

        if (!empty($fallback_quotes)) {
            $featured_quote = $fallback_quotes[0];
        }
    }

    if ($featured_quote instanceof WP_Post && $count > 1) {
        $secondary_query_args = $base_query_args;
        $secondary_query_args['posts_per_page'] = $count - 1;
        $secondary_query_args['post__not_in'] = [$featured_quote->ID];

        if ($featured_only) {
            $secondary_query_args['meta_query'] = $featured_meta_query;
        }

        $secondary_quotes = get_posts($secondary_query_args);
    }
} else {
    $query_args = $base_query_args;
    $query_args['posts_per_page'] = $layout === 'single' ? 1 : $count;

    if ($featured_only) {
        $query_args['meta_query'] = $featured_meta_query;
    }

    $quotes = get_posts($query_args);
}

$has_quotes = $layout === 'featured_first'
    ? $featured_quote instanceof WP_Post
    : !empty($quotes);

if (!$has_quotes && !current_user_can('manage_options')) {
    return;
}

/**
 * Read the first non-empty legacy quote meta value.
 *
 * This remains as a fallback when Base is updated before Core.
 *
 * @param int      $post_id Quote post ID.
 * @param string[] $keys    Meta keys in priority order.
 * @return mixed
 */
$get_quote_meta = static function ($post_id, $keys) {
    foreach ($keys as $key) {
        $value = get_post_meta($post_id, $key, true);

        if ($value !== '' && $value !== null) {
            return $value;
        }
    }

    return '';
};

/**
 * Normalize a quote for frontend rendering.
 *
 * @param WP_Post $quote_post Quote post object.
 * @return array
 */
$get_quote_data = static function ($quote_post) use ($get_quote_meta) {
    $quote_id = $quote_post->ID;

    $quote_data = function_exists('mpc_get_press_quote_data')
        ? mpc_get_press_quote_data($quote_id)
        : null;

    if (is_array($quote_data)) {
        $quote_text = trim((string) ($quote_data['text'] ?? ''));
        $quote_source = trim((string) ($quote_data['source_name'] ?? ''));
        $quote_context = trim((string) ($quote_data['context'] ?? ''));
    } else {
        $quote_text = $get_quote_meta(
            $quote_id,
            [
                '_mpc_press_quote_text',
                '_mpc_quote_text',
            ]
        );

        $quote_source = $get_quote_meta(
            $quote_id,
            [
                '_mpc_press_quote_source_name',
                '_mpc_press_quote_source',
                '_mpc_press_quote_client',
                '_mpc_quote_source',
            ]
        );

        $quote_context = $get_quote_meta(
            $quote_id,
            [
                '_mpc_press_quote_context',
                '_mpc_press_quote_publication',
                '_mpc_press_quote_role',
                '_mpc_quote_context',
            ]
        );
    }

    if (!$quote_text) {
        $quote_text = get_the_excerpt($quote_post);

        if (!$quote_text) {
            $quote_text = wp_strip_all_tags($quote_post->post_content);
        }
    }

    return [
        'text'    => trim((string) $quote_text),
        'source'  => trim((string) $quote_source),
        'context' => trim((string) $quote_context),
    ];
};

/**
 * Render one quote card.
 *
 * The featured modifier is applied only by the Featured First layout.
 * A quote's stored Featured flag never changes its appearance in Grid.
 *
 * @param WP_Post $quote_post Quote post object.
 * @param bool    $is_featured Whether to use featured presentation.
 * @return void
 */
$render_quote_card = static function (
    $quote_post,
    $is_featured = false
) use (
    $get_quote_data,
    $show_attribution
) {
    $quote_data = $get_quote_data($quote_post);

    $card_classes = ['home-quote-card'];

    if ($is_featured) {
        $card_classes[] = 'home-quote-card--featured';
    }
    ?>
    <article class="<?php echo esc_attr(implode(' ', $card_classes)); ?>">
        <?php if ($quote_data['text']) : ?>
            <blockquote class="home-quote-card__quote">
                <?php echo esc_html($quote_data['text']); ?>
            </blockquote>
        <?php endif; ?>

        <?php
        if (
            $show_attribution
            && ($quote_data['source'] || $quote_data['context'])
        ) :
            ?>
            <footer class="home-quote-card__footer">
                <?php if ($quote_data['source']) : ?>
                    <cite>
                        <?php echo esc_html($quote_data['source']); ?>
                    </cite>
                <?php endif; ?>

                <?php if ($quote_data['context']) : ?>
                    <span>
                        <?php echo esc_html($quote_data['context']); ?>
                    </span>
                <?php endif; ?>
            </footer>
        <?php endif; ?>
    </article>
    <?php
};

$layout_class = str_replace('_', '-', $layout);

$classes = [
    'home-section',
    'home-quotes',
    'home-quotes--layout-' . $layout_class,
    'home-quotes--tone-' . $tone,
];

if ($featured_only) {
    $classes[] = 'home-quotes--featured-only';
}
?>

<section
    id="quotes"
    class="<?php echo esc_attr(implode(' ', $classes)); ?>"
>
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

        <?php if ($has_quotes) : ?>
            <?php if ($layout === 'featured_first') : ?>
                <div class="home-quotes__featured">
                    <?php $render_quote_card($featured_quote, true); ?>
                </div>

                <?php if ($secondary_quotes) : ?>
                    <div class="home-quotes__secondary-grid">
                        <?php foreach ($secondary_quotes as $quote_post) : ?>
                            <?php $render_quote_card($quote_post); ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php else : ?>
                <div class="home-quotes__grid">
                    <?php foreach ($quotes as $quote_post) : ?>
                        <?php $render_quote_card($quote_post); ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php elseif (current_user_can('manage_options')) : ?>
            <div class="home-quotes__empty">
                <p>
                    <?php
                    esc_html_e(
                        'Add quotes/testimonials in Music Project → Quotes / Testimonials.',
                        'music-project-base'
                    );
                    ?>
                </p>
            </div>
        <?php endif; ?>
    </div>
</section>