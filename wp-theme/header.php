<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class('skyworks-body'); ?>>
<?php wp_body_open(); ?>

<header class="skyworks-header" role="banner">
    <div class="container">
        <div class="header-brand">
            <?php if (has_custom_logo()): ?>
                <div class="custom-logo-wrapper">
                    <?php the_custom_logo(); ?>
                </div>
            <?php else: ?>
                <h1 class="site-title">
                    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                        <?php bloginfo('name'); ?>
                    </a>
                </h1>
            <?php endif; ?>
        </div>
        
        <nav class="main-navigation" role="navigation" aria-label="<?php esc_attr_e('Primary Menu', 'skyworks'); ?>">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'menu_class' => 'primary-menu',
                'container' => false,
                'fallback_cb' => false
            ));
            ?>
            <button class="mobile-menu-toggle" aria-expanded="false">
                <span class="sr-only"><?php esc_html_e('Menu', 'skyworks'); ?></span>
                <span class="hamburger"></span>
            </button>
        </nav>
    </div>
</header>

<main class="skyworks-main" role="main">