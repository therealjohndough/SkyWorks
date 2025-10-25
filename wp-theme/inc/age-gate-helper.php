<?php
/**
 * Age Gate Helper Functions
 * 
 * Helper functions for age gate functionality
 */

/**
 * Get age gate setting with fallback
 */
function skyworld_get_age_gate_setting($field_name, $fallback = '') {
    $value = get_field($field_name, 'option');
    return $value ?: $fallback;
}

/**
 * Get all age gate settings
 */
function skyworld_get_age_gate_settings() {
    return array(
        'enabled' => skyworld_get_age_gate_setting('age_gate_enabled', true),
        'logo' => get_field('age_gate_logo', 'option'),
        'title' => skyworld_get_age_gate_setting('age_gate_title', 'Check Point'),
        'message' => skyworld_get_age_gate_setting('age_gate_message', 'You must be "21" or older to access the website'),
        'disclaimer' => skyworld_get_age_gate_setting('age_gate_disclaimer', 'This site uses cookies and by entering you acknowledge that you have read our Privacy and Cookie Notice.'),
        'yes_button' => skyworld_get_age_gate_setting('age_gate_yes_button', 'I\'m 21+'),
        'no_button' => skyworld_get_age_gate_setting('age_gate_no_button', 'Under 21'),
        'denied_message' => skyworld_get_age_gate_setting('age_gate_denied_message', 'You must be 21 or older to enter.'),
        'background' => get_field('age_gate_background', 'option'),
        'background_color' => skyworld_get_age_gate_setting('age_gate_background_color', 'rgba(0, 0, 0, 0.95)'),
        'primary_color' => skyworld_get_age_gate_setting('age_gate_primary_color', '#FF8C00'),
        'text_color' => skyworld_get_age_gate_setting('age_gate_text_color', '#FFFFFF'),
        'cookie_duration' => skyworld_get_age_gate_setting('age_gate_cookie_duration', 24),
        'redirect_url' => get_field('age_gate_redirect_url', 'option'),
    );
}

/**
 * Check if age gate is enabled
 */
function skyworld_is_age_gate_enabled() {
    return skyworld_get_age_gate_setting('age_gate_enabled', true);
}

/**
 * Get age gate cookie duration in seconds
 */
function skyworld_get_age_gate_cookie_duration() {
    $hours = skyworld_get_age_gate_setting('age_gate_cookie_duration', 24);
    return intval($hours) * 60 * 60; // Convert hours to seconds
}

/**
 * Check if user has verified age
 */
function skyworld_has_verified_age() {
    return isset($_COOKIE['age_verified']) && $_COOKIE['age_verified'] === 'true';
}

/**
 * Get age gate inline styles
 */
function skyworld_get_age_gate_styles() {
    $settings = skyworld_get_age_gate_settings();
    
    $styles = array();
    
    if ($settings['background_color']) {
        $styles[] = 'background: ' . esc_attr($settings['background_color']);
    }
    
    if ($settings['background'] && isset($settings['background']['url'])) {
        $styles[] = 'background-image: url(' . esc_url($settings['background']['url']) . ')';
        $styles[] = 'background-size: cover';
        $styles[] = 'background-position: center';
    }
    
    return implode('; ', $styles);
}

/**
 * Get age gate button styles
 */
function skyworld_get_age_gate_button_styles($button_type = 'yes') {
    $settings = skyworld_get_age_gate_settings();
    
    if ($button_type === 'yes') {
        return 'background: ' . esc_attr($settings['primary_color']) . '; color: #000;';
    } else {
        return 'color: ' . esc_attr($settings['text_color']) . '; border-color: ' . esc_attr($settings['text_color']) . ';';
    }
}

/**
 * Get age gate text styles
 */
function skyworld_get_age_gate_text_styles() {
    $settings = skyworld_get_age_gate_settings();
    return 'color: ' . esc_attr($settings['text_color']);
}

/**
 * Add age gate admin notice
 */
function skyworld_age_gate_admin_notice() {
    if (!skyworld_is_age_gate_enabled()) {
        $screen = get_current_screen();
        if ($screen && $screen->id === 'toplevel_page_age-gate-settings') {
            echo '<div class="notice notice-warning"><p><strong>Age Gate is currently disabled.</strong> Enable it in the settings below to show the age verification overlay.</p></div>';
        }
    }
}
add_action('admin_notices', 'skyworld_age_gate_admin_notice');

/**
 * Add age gate preview functionality
 */
function skyworld_age_gate_preview() {
    if (isset($_GET['age_gate_preview']) && current_user_can('edit_posts')) {
        // Force show age gate for preview
        add_action('wp_footer', function() {
            echo '<script>document.getElementById("ageGate").style.display = "flex";</script>';
        });
    }
}
add_action('init', 'skyworld_age_gate_preview');
