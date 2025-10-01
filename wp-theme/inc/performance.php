<?php
/**
 * Performance Optimization
 */

// Remove unnecessary WordPress features for performance
function skyworks_performance_optimizations() {
    // Remove emoji scripts and styles
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    
    // Remove WordPress generator meta tag
    remove_action('wp_head', 'wp_generator');
    
    // Remove RSD link
    remove_action('wp_head', 'rsd_link');
    
    // Remove wlwmanifest.xml
    remove_action('wp_head', 'wlwmanifest_link');
    
    // Remove shortlink
    remove_action('wp_head', 'wp_shortlink_wp_head');
    
    // Remove adjacent posts rel links
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
    
    // Remove feed links (unless needed)
    remove_action('wp_head', 'feed_links_extra', 3);
    remove_action('wp_head', 'feed_links', 2);
}
add_action('init', 'skyworks_performance_optimizations');

// Optimize jQuery loading
function skyworks_optimize_jquery() {
    if (!is_admin() && !is_customize_preview()) {
        wp_deregister_script('jquery');
        wp_register_script('jquery', 'https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js', false, '3.6.0', true);
        wp_enqueue_script('jquery');
    }
}
add_action('wp_enqueue_scripts', 'skyworks_optimize_jquery');

// Preload critical resources
function skyworks_preload_resources() {
    ?>
    <link rel="preload" href="<?php echo SKYWORKS_THEME_URL; ?>/dist/main.css" as="style">
    <link rel="preload" href="<?php echo SKYWORKS_THEME_URL; ?>/dist/main.bundle.js" as="script">
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <?php
}
add_action('wp_head', 'skyworks_preload_resources', 1);

// Enable Gzip compression
function skyworks_enable_gzip() {
    if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
        ob_start('ob_gzhandler');
    } else {
        ob_start();
    }
}
add_action('init', 'skyworks_enable_gzip');

// Defer non-critical JavaScript
function skyworks_defer_scripts($tag, $handle, $src) {
    $defer_scripts = array(
        'skyworks-animations',
        'contact-form-7'
    );
    
    if (in_array($handle, $defer_scripts)) {
        return str_replace('<script ', '<script defer ', $tag);
    }
    
    return $tag;
}
add_filter('script_loader_tag', 'skyworks_defer_scripts', 10, 3);

// Optimize images
function skyworks_image_optimization() {
    // Add WebP support
    add_filter('wp_check_filetype_and_ext', function($data, $file, $filename, $mimes) {
        $filetype = wp_check_filetype($filename, $mimes);
        return [
            'ext'             => $filetype['ext'],
            'type'            => $filetype['type'],
            'proper_filename' => $data['proper_filename']
        ];
    }, 10, 4);
    
    add_filter('mime_types', function($mimes) {
        $mimes['svg'] = 'image/svg+xml';
        $mimes['webp'] = 'image/webp';
        return $mimes;
    });
    
    // Lazy load images
    add_filter('wp_get_attachment_image_attributes', function($attr) {
        if (!is_admin()) {
            $attr['loading'] = 'lazy';
        }
        return $attr;
    });
}
add_action('init', 'skyworks_image_optimization');

// Critical CSS inlining
function skyworks_critical_css() {
    $critical_css = SKYWORKS_THEME_DIR . '/assets/css/critical.css';
    if (file_exists($critical_css)) {
        echo '<style>' . file_get_contents($critical_css) . '</style>';
    }
}
add_action('wp_head', 'skyworks_critical_css', 2);