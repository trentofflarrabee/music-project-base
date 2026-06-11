<footer class="site-footer">
    <div class="site-footer__inner">
        <div class="site-footer__brand">
            <?php if (has_custom_logo()) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <a class="site-footer__name" href="<?php echo esc_url(home_url('/')); ?>">
                    <?php bloginfo('name'); ?>
                </a>
            <?php endif; ?>
        </div>

        <nav class="site-footer__nav" aria-label="<?php esc_attr_e('Footer Menu', 'music-project-base'); ?>">
            <?php
            wp_nav_menu([
                'theme_location' => 'footer',
                'container'      => false,
                'menu_class'     => 'site-footer__menu',
                'fallback_cb'    => false,
                'depth'          => 1,
            ]);
            ?>
        </nav>

        <?php
        get_template_part('template-parts/social-links', null, [
            'context' => 'footer',
        ]);
        ?>

        <p class="site-footer__copyright">
            &copy; <?php echo esc_html(date('Y')); ?> <?php bloginfo('name'); ?>.
        </p>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>