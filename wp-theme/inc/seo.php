<?php
/**
 * SEO Optimization
 */

// Add structured data
function skyworks_structured_data() {
    if (is_single()) {
        global $post;
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => get_the_title(),
            'author' => array(
                '@type' => 'Person',
                'name' => get_the_author()
            ),
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c'),
            'description' => get_the_excerpt(),
            'url' => get_permalink(),
        );
        
        if (has_post_thumbnail()) {
            $schema['image'] = wp_get_attachment_image_url(get_post_thumbnail_id(), 'large');
        }
        
        echo '<script type="application/ld+json">' . json_encode($schema) . '</script>';
    }
    
    if (is_home() || is_front_page()) {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => get_bloginfo('name'),
            'description' => get_bloginfo('description'),
            'url' => home_url(),
            'logo' => array(
                '@type' => 'ImageObject',
                'url' => get_theme_mod('custom_logo') ? wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full') : ''
            )
        );
        
        echo '<script type="application/ld+json">' . json_encode($schema) . '</script>';
    }
}
add_action('wp_head', 'skyworks_structured_data');

// Optimize meta tags
function skyworks_meta_tags() {
    if (is_single()) {
        global $post;
        echo '<meta name="description" content="' . esc_attr(wp_trim_words(strip_tags($post->post_content), 20)) . '">';
        echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '">';
        echo '<meta property="og:description" content="' . esc_attr(wp_trim_words(strip_tags($post->post_content), 20)) . '">';
        echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '">';
        echo '<meta property="og:type" content="article">';
        
        if (has_post_thumbnail()) {
            $image_url = wp_get_attachment_image_url(get_post_thumbnail_id(), 'large');
            echo '<meta property="og:image" content="' . esc_url($image_url) . '">';
            echo '<meta name="twitter:image" content="' . esc_url($image_url) . '">';
        }
        
        echo '<meta name="twitter:card" content="summary_large_image">';
        echo '<meta name="twitter:title" content="' . esc_attr(get_the_title()) . '">';
        echo '<meta name="twitter:description" content="' . esc_attr(wp_trim_words(strip_tags($post->post_content), 20)) . '">';
    }
    
    if (is_home() || is_front_page()) {
        echo '<meta name="description" content="' . esc_attr(get_bloginfo('description')) . '">';
        echo '<meta property="og:title" content="' . esc_attr(get_bloginfo('name')) . '">';
        echo '<meta property="og:description" content="' . esc_attr(get_bloginfo('description')) . '">';
        echo '<meta property="og:url" content="' . esc_url(home_url()) . '">';
        echo '<meta property="og:type" content="website">';
    }
}
add_action('wp_head', 'skyworks_meta_tags');

// XML Sitemap support
function skyworks_sitemap_support() {
    add_theme_support('wp-block-styles');
    add_theme_support('responsive-embeds');
    add_theme_support('editor-styles');
}
add_action('after_setup_theme', 'skyworks_sitemap_support');

// Breadcrumbs
function skyworks_breadcrumbs() {
    if (!is_home() && !is_front_page()) {
        echo '<nav class="breadcrumbs" aria-label="Breadcrumb">';
        echo '<ol>';
        echo '<li><a href="' . home_url() . '">Home</a></li>';
        
        if (is_category()) {
            echo '<li>' . single_cat_title('', false) . '</li>';
        } elseif (is_single()) {
            $category = get_the_category();
            if ($category) {
                echo '<li><a href="' . get_category_link($category[0]->term_id) . '">' . $category[0]->name . '</a></li>';
            }
            echo '<li>' . get_the_title() . '</li>';
        } elseif (is_page()) {
            echo '<li>' . get_the_title() . '</li>';
        } elseif (is_search()) {
            echo '<li>Search Results for "' . get_search_query() . '"</li>';
        }
        
        echo '</ol>';
        echo '</nav>';
    }
}

// Canonical URLs
function skyworks_canonical_url() {
    if (is_single() || is_page()) {
        echo '<link rel="canonical" href="' . esc_url(get_permalink()) . '">';
    }
}
add_action('wp_head', 'skyworks_canonical_url');