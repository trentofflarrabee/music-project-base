<?php get_header(); ?>

<main id="site-main" class="site-main home-main">

<?php foreach (mpb_get_home_sections() as $section) : ?>
    <?php mpb_render_home_section($section); ?>
<?php endforeach; ?>

</main>

<?php get_footer(); ?>