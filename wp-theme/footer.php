</main>

<footer class="skyworks-footer" role="contentinfo">
    <div class="container">
        <div class="footer-content">
            <div class="footer-brand">
                <?php if (has_custom_logo()): ?>
                    <div class="footer-logo">
                        <?php the_custom_logo(); ?>
                    </div>
                <?php endif; ?>
                <p class="footer-tagline"><?php bloginfo('description'); ?></p>
            </div>
            
            <nav class="footer-navigation" role="navigation" aria-label="<?php esc_attr_e('Footer Menu', 'skyworks'); ?>">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer',
                    'menu_class' => 'footer-menu',
                    'container' => false,
                    'fallback_cb' => false
                ));
                ?>
            </nav>
        </div>
        
        <div class="footer-bottom">
            <p class="copyright">
                &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. 
                <?php esc_html_e('All rights reserved.', 'skyworks'); ?>
            </p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>