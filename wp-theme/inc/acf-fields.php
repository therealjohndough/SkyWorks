<?php
/**
 * Advanced Custom Fields Configuration
 */

// Check if ACF is active
function skyworks_acf_init() {
    if (function_exists('acf_add_local_field_group')) {
        
        // Brand Asset Fields
        acf_add_local_field_group(array(
            'key' => 'group_brand_assets',
            'title' => 'Brand Assets',
            'fields' => array(
                array(
                    'key' => 'field_brand_logo',
                    'label' => 'Brand Logo',
                    'name' => 'brand_logo',
                    'type' => 'image',
                    'instructions' => 'Upload the main brand logo (SVG preferred)',
                    'return_format' => 'array',
                    'preview_size' => 'medium',
                    'library' => 'all',
                    'mime_types' => 'svg,png,jpg,jpeg',
                ),
                array(
                    'key' => 'field_brand_icon',
                    'label' => 'Brand Icon',
                    'name' => 'brand_icon',
                    'type' => 'image',
                    'instructions' => 'Upload the brand icon/favicon',
                    'return_format' => 'array',
                    'preview_size' => 'thumbnail',
                    'library' => 'all',
                    'mime_types' => 'svg,png',
                ),
                array(
                    'key' => 'field_brand_colors',
                    'label' => 'Brand Color Palette',
                    'name' => 'brand_colors',
                    'type' => 'repeater',
                    'instructions' => 'Define your brand color palette',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_color_name',
                            'label' => 'Color Name',
                            'name' => 'color_name',
                            'type' => 'text',
                            'wrapper' => array(
                                'width' => '50',
                            ),
                        ),
                        array(
                            'key' => 'field_color_value',
                            'label' => 'Color Value',
                            'name' => 'color_value',
                            'type' => 'color_picker',
                            'wrapper' => array(
                                'width' => '50',
                            ),
                        ),
                    ),
                    'min' => 0,
                    'layout' => 'table',
                    'button_label' => 'Add Color',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'options_page',
                        'operator' => '==',
                        'value' => 'brand-assets',
                    ),
                ),
            ),
        ));
        
        // Hero Section Fields
        acf_add_local_field_group(array(
            'key' => 'group_hero_section',
            'title' => 'Hero Section',
            'fields' => array(
                array(
                    'key' => 'field_hero_title',
                    'label' => 'Hero Title',
                    'name' => 'hero_title',
                    'type' => 'text',
                    'instructions' => 'Main hero headline',
                ),
                array(
                    'key' => 'field_hero_subtitle',
                    'label' => 'Hero Subtitle',
                    'name' => 'hero_subtitle',
                    'type' => 'textarea',
                    'instructions' => 'Supporting text for hero section',
                    'rows' => 3,
                ),
                array(
                    'key' => 'field_hero_background',
                    'label' => 'Hero Background',
                    'name' => 'hero_background',
                    'type' => 'image',
                    'instructions' => 'Background image or video for hero section',
                    'return_format' => 'array',
                    'preview_size' => 'large',
                ),
                array(
                    'key' => 'field_hero_cta',
                    'label' => 'Call to Action',
                    'name' => 'hero_cta',
                    'type' => 'link',
                    'instructions' => 'Primary call to action button',
                ),
                array(
                    'key' => 'field_enable_hero_animation',
                    'label' => 'Enable Hero Animation',
                    'name' => 'enable_hero_animation',
                    'type' => 'true_false',
                    'instructions' => 'Enable GSAP animations for hero section',
                    'default_value' => 1,
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'page_template',
                        'operator' => '==',
                        'value' => 'page-home.php',
                    ),
                ),
            ),
        ));
        
        // Add options page for brand assets
        if (function_exists('acf_add_options_page')) {
            acf_add_options_page(array(
                'page_title' => 'Brand Assets',
                'menu_title' => 'Brand Assets',
                'menu_slug' => 'brand-assets',
                'capability' => 'edit_posts',
                'icon_url' => 'dashicons-admin-customizer',
            ));
        }
    }
}
add_action('acf/init', 'skyworks_acf_init');