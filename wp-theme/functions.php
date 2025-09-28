<?php
/**
 * Theme Name: SkyWorks Cannabis
 * Description: Premium cannabis retro-futurism aesthetic theme for Skyworld Cannabis brand assets and web UI components
 * Version: 1.0.0
 * Author: John Dough
 * Text Domain: skyworks
 * Domain Path: /languages
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define theme constants
define('SKYWORKS_VERSION', '1.0.0');
define('SKYWORKS_THEME_DIR', get_template_directory());
define('SKYWORKS_THEME_URL', get_template_directory_uri());

// Theme setup
function skyworks_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption'
    ));
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'skyworks'),
        'footer' => __('Footer Menu', 'skyworks')
    ));
}
add_action('after_setup_theme', 'skyworks_setup');

// Enqueue scripts and styles
function skyworks_scripts() {
    // Enqueue main stylesheet
    wp_enqueue_style('skyworks-style', SKYWORKS_THEME_URL . '/dist/main.css', array(), SKYWORKS_VERSION);
    
    // Enqueue main JavaScript
    wp_enqueue_script('skyworks-main', SKYWORKS_THEME_URL . '/dist/main.bundle.js', array(), SKYWORKS_VERSION, true);
    
    // Enqueue GSAP animations
    wp_enqueue_script('skyworks-animations', SKYWORKS_THEME_URL . '/dist/animations.bundle.js', array('skyworks-main'), SKYWORKS_VERSION, true);
    
    // Localize script for AJAX
    wp_localize_script('skyworks-main', 'skyworks_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('skyworks_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'skyworks_scripts');

// Include theme functions
require_once SKYWORKS_THEME_DIR . '/inc/customizer.php';
require_once SKYWORKS_THEME_DIR . '/inc/acf-fields.php';
require_once SKYWORKS_THEME_DIR . '/inc/performance.php';
require_once SKYWORKS_THEME_DIR . '/inc/seo.php';