<?php
/**
 * Theme Customizer
 */

function skyworks_customize_register($wp_customize) {
    // Brand Colors Section
    $wp_customize->add_section('skyworks_colors', array(
        'title' => __('Brand Colors', 'skyworks'),
        'priority' => 30,
    ));
    
    // Primary Brand Color
    $wp_customize->add_setting('skyworks_primary_color', array(
        'default' => '#00ff88',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'skyworks_primary_color', array(
        'label' => __('Primary Brand Color', 'skyworks'),
        'section' => 'skyworks_colors',
        'settings' => 'skyworks_primary_color',
    )));
    
    // Secondary Brand Color
    $wp_customize->add_setting('skyworks_secondary_color', array(
        'default' => '#ff0080',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'skyworks_secondary_color', array(
        'label' => __('Secondary Brand Color', 'skyworks'),
        'section' => 'skyworks_colors',
        'settings' => 'skyworks_secondary_color',
    )));
    
    // Accent Color
    $wp_customize->add_setting('skyworks_accent_color', array(
        'default' => '#ffff00',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'skyworks_accent_color', array(
        'label' => __('Accent Color', 'skyworks'),
        'section' => 'skyworks_colors',
        'settings' => 'skyworks_accent_color',
    )));
    
    // Typography Section
    $wp_customize->add_section('skyworks_typography', array(
        'title' => __('Typography', 'skyworks'),
        'priority' => 40,
    ));
    
    // Header Font
    $wp_customize->add_setting('skyworks_header_font', array(
        'default' => 'Orbitron',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('skyworks_header_font', array(
        'label' => __('Header Font', 'skyworks'),
        'section' => 'skyworks_typography',
        'type' => 'select',
        'choices' => array(
            'Orbitron' => 'Orbitron',
            'Exo' => 'Exo',
            'Rajdhani' => 'Rajdhani',
            'Space Mono' => 'Space Mono'
        ),
    ));
    
    // Body Font
    $wp_customize->add_setting('skyworks_body_font', array(
        'default' => 'Inter',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('skyworks_body_font', array(
        'label' => __('Body Font', 'skyworks'),
        'section' => 'skyworks_typography',
        'type' => 'select',
        'choices' => array(
            'Inter' => 'Inter',
            'Work Sans' => 'Work Sans',
            'Source Sans Pro' => 'Source Sans Pro',
            'Nunito Sans' => 'Nunito Sans'
        ),
    ));
    
    // Animation Settings
    $wp_customize->add_section('skyworks_animations', array(
        'title' => __('Animations', 'skyworks'),
        'priority' => 50,
    ));
    
    $wp_customize->add_setting('skyworks_enable_animations', array(
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('skyworks_enable_animations', array(
        'label' => __('Enable GSAP Animations', 'skyworks'),
        'section' => 'skyworks_animations',
        'type' => 'checkbox',
    ));
}
add_action('customize_register', 'skyworks_customize_register');

// Output custom CSS
function skyworks_customizer_css() {
    $primary_color = get_theme_mod('skyworks_primary_color', '#00ff88');
    $secondary_color = get_theme_mod('skyworks_secondary_color', '#ff0080');
    $accent_color = get_theme_mod('skyworks_accent_color', '#ffff00');
    $header_font = get_theme_mod('skyworks_header_font', 'Orbitron');
    $body_font = get_theme_mod('skyworks_body_font', 'Inter');
    
    ?>
    <style type="text/css">
        :root {
            --skyworks-primary: <?php echo esc_attr($primary_color); ?>;
            --skyworks-secondary: <?php echo esc_attr($secondary_color); ?>;
            --skyworks-accent: <?php echo esc_attr($accent_color); ?>;
            --skyworks-header-font: '<?php echo esc_attr($header_font); ?>', sans-serif;
            --skyworks-body-font: '<?php echo esc_attr($body_font); ?>', sans-serif;
        }
    </style>
    <?php
}
add_action('wp_head', 'skyworks_customizer_css');