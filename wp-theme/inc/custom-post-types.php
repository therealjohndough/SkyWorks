<?php
/**
 * Custom Post Types for Hub and Spoke Model
 * 
 * Strains (Hub) - Central library of strain information
 * Products (Spokes) - Inventory items linked to strains
 */

// Register Strain Custom Post Type (Hub)
function skyworks_register_strain_cpt() {
    $labels = array(
        'name' => 'Strains',
        'singular_name' => 'Strain',
        'menu_name' => 'Strains',
        'add_new' => 'Add New Strain',
        'add_new_item' => 'Add New Strain',
        'edit_item' => 'Edit Strain',
        'new_item' => 'New Strain',
        'view_item' => 'View Strain',
        'search_items' => 'Search Strains',
        'not_found' => 'No strains found',
        'not_found_in_trash' => 'No strains found in trash',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'strains'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-leaf',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'show_in_rest' => true,
    );

    register_post_type('strain', $args);
}
add_action('init', 'skyworks_register_strain_cpt');

// Register Cannabis Product Custom Post Type (Spokes)
function skyworld_register_cannabis_product_cpt() {
    $labels = array(
        'name' => 'Cannabis Products',
        'singular_name' => 'Cannabis Product',
        'menu_name' => 'Cannabis Products',
        'add_new' => 'Add New Product',
        'add_new_item' => 'Add New Cannabis Product',
        'edit_item' => 'Edit Cannabis Product',
        'new_item' => 'New Cannabis Product',
        'view_item' => 'View Cannabis Product',
        'search_items' => 'Search Cannabis Products',
        'not_found' => 'No cannabis products found',
        'not_found_in_trash' => 'No cannabis products found in trash',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'cannabis-products'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 6,
        'menu_icon' => 'dashicons-leaf',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'show_in_rest' => true,
    );

    register_post_type('cannabis_product', $args);
}
add_action('init', 'skyworld_register_cannabis_product_cpt');

// Add custom taxonomies for better organization
function skyworks_register_taxonomies() {
    // Strain Categories (Indica, Sativa, Hybrid)
    register_taxonomy('strain_type', 'strain', array(
        'labels' => array(
            'name' => 'Strain Types',
            'singular_name' => 'Strain Type',
            'menu_name' => 'Strain Types',
        ),
        'hierarchical' => true,
        'public' => true,
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'strain-type'),
    ));

    // Cannabis Product Categories (Flower, Pre-rolls, Concentrates, etc.)
    register_taxonomy('cannabis_product_category', 'cannabis_product', array(
        'labels' => array(
            'name' => 'Cannabis Product Categories',
            'singular_name' => 'Cannabis Product Category',
            'menu_name' => 'Cannabis Product Categories',
        ),
        'hierarchical' => true,
        'public' => true,
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'cannabis-product-category'),
    ));

    // Effects (Relaxing, Energizing, Creative, etc.)
    register_taxonomy('effects', array('strain', 'cannabis_product'), array(
        'labels' => array(
            'name' => 'Effects',
            'singular_name' => 'Effect',
            'menu_name' => 'Effects',
        ),
        'hierarchical' => false,
        'public' => true,
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'effects'),
    ));

    // Terpenes (Limonene, Myrcene, Pinene, etc.)
    register_taxonomy('terpenes', array('strain', 'cannabis_product'), array(
        'labels' => array(
            'name' => 'Terpenes',
            'singular_name' => 'Terpene',
            'menu_name' => 'Terpenes',
        ),
        'hierarchical' => false,
        'public' => true,
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'terpenes'),
    ));
}
add_action('init', 'skyworks_register_taxonomies');

// Flush rewrite rules on theme activation
function skyworld_flush_rewrite_rules() {
    skyworld_register_strain_cpt();
    skyworld_register_cannabis_product_cpt();
    skyworld_register_taxonomies();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'skyworld_flush_rewrite_rules');
