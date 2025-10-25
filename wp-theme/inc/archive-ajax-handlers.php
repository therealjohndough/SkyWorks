<?php
/**
 * Archive AJAX Handlers
 *
 * AJAX functionality for product archive filtering and load more
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AJAX handler for loading more products
 */
function skyworld_load_more_products() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'skyworld_nonce')) {
        wp_die('Security check failed');
    }
    
    $page = intval($_POST['page']);
    $strain_type = sanitize_text_field($_POST['strain_type']);
    $effect = sanitize_text_field($_POST['effect']);
    
    // Build query args
    $args = array(
        'post_type' => 'cannabis_product',
        'posts_per_page' => get_option('posts_per_page', 12),
        'paged' => $page,
        'post_status' => 'publish',
        'meta_query' => array(),
        'tax_query' => array()
    );
    
    // Add strain type filter
    if ($strain_type && $strain_type !== 'all') {
        $args['tax_query'][] = array(
            'taxonomy' => 'strain_type',
            'field' => 'slug',
            'terms' => $strain_type
        );
    }
    
    // Add effect filter
    if ($effect && $effect !== 'all') {
        $args['tax_query'][] = array(
            'taxonomy' => 'effects',
            'field' => 'slug',
            'terms' => $effect
        );
    }
    
    // Add relation for multiple tax queries
    if (count($args['tax_query']) > 1) {
        $args['tax_query']['relation'] = 'AND';
    }
    
    $query = new WP_Query($args);
    
    if ($query->have_posts()) {
        ob_start();
        
        while ($query->have_posts()) {
            $query->the_post();
            
            $strain = get_field('product_strain');
            $product_effects = array();
            if ($strain && get_field('strain_effects', $strain->ID)) {
                $product_effects = get_field('strain_effects', $strain->ID);
                $product_effects = array_slice($product_effects, 0, 3);
            }
            
            // Determine product badge
            $badge = '';
            $post_date = get_the_date('Y-m-d');
            $days_old = (time() - strtotime($post_date)) / (60 * 60 * 24);
            $inventory = get_field('product_inventory');
            
            if ($days_old < 30) {
                $badge = 'New';
            } elseif ($inventory === 'low_stock') {
                $badge = 'Limited';
            } elseif (get_comments_number() > 5) {
                $badge = 'Popular';
            }
            ?>
            <div class="product-card" 
                 data-strain-type="<?php echo $strain ? get_field('strain_type', $strain->ID) : ''; ?>"
                 data-effects="<?php echo $strain ? implode(',', get_field('strain_effects', $strain->ID) ?: array()) : ''; ?>">
                <div class="product-image">
                    <?php if (has_post_thumbnail()): ?>
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail('medium', array('alt' => get_the_title())); ?>
                        </a>
                    <?php else: ?>
                        <a href="<?php the_permalink(); ?>">
                            <div class="placeholder-image">[Product Image]</div>
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($badge): ?>
                        <span class="product-badge"><?php echo esc_html($badge); ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="product-info">
                    <h3 class="product-name">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h3>
                    
                    <?php if ($product_effects): ?>
                        <div class="product-effects">
                            <?php foreach ($product_effects as $effect): ?>
                                <span class="effect-tag"><?php echo ucfirst(str_replace('_', ' ', $effect)); ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="product-description">
                        <?php 
                        $aroma = get_field('aroma_profile');
                        $flavor = get_field('flavor_profile');
                        if ($aroma) {
                            echo esc_html(wp_trim_words($aroma, 15));
                        } elseif ($flavor) {
                            echo esc_html(wp_trim_words($flavor, 15));
                        } else {
                            echo esc_html(wp_trim_words(get_the_excerpt(), 15));
                        }
                        ?>
                    </div>
                    
                    <a href="<?php the_permalink(); ?>" class="view-product-btn">
                        View Details
                    </a>
                </div>
            </div>
            <?php
        }
        
        wp_reset_postdata();
        
        $html = ob_get_clean();
        
        wp_send_json_success(array(
            'products' => $html,
            'has_more' => $page < $query->max_num_pages
        ));
    } else {
        wp_send_json_error('No more products found');
    }
}
add_action('wp_ajax_skyworld_load_more_products', 'skyworld_load_more_products');
add_action('wp_ajax_nopriv_skyworld_load_more_products', 'skyworld_load_more_products');

/**
 * AJAX handler for filtering products
 */
function skyworld_filter_products() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'skyworld_nonce')) {
        wp_die('Security check failed');
    }
    
    $strain_type = sanitize_text_field($_POST['strain_type']);
    $effect = sanitize_text_field($_POST['effect']);
    $page = intval($_POST['page']) ?: 1;
    
    // Build query args
    $args = array(
        'post_type' => 'cannabis_product',
        'posts_per_page' => get_option('posts_per_page', 12),
        'paged' => $page,
        'post_status' => 'publish',
        'meta_query' => array(),
        'tax_query' => array()
    );
    
    // Add strain type filter
    if ($strain_type && $strain_type !== 'all') {
        $args['tax_query'][] = array(
            'taxonomy' => 'strain_type',
            'field' => 'slug',
            'terms' => $strain_type
        );
    }
    
    // Add effect filter
    if ($effect && $effect !== 'all') {
        $args['tax_query'][] = array(
            'taxonomy' => 'effects',
            'field' => 'slug',
            'terms' => $effect
        );
    }
    
    // Add relation for multiple tax queries
    if (count($args['tax_query']) > 1) {
        $args['tax_query']['relation'] = 'AND';
    }
    
    $query = new WP_Query($args);
    
    if ($query->have_posts()) {
        ob_start();
        
        while ($query->have_posts()) {
            $query->the_post();
            
            $strain = get_field('product_strain');
            $product_effects = array();
            if ($strain && get_field('strain_effects', $strain->ID)) {
                $product_effects = get_field('strain_effects', $strain->ID);
                $product_effects = array_slice($product_effects, 0, 3);
            }
            
            // Determine product badge
            $badge = '';
            $post_date = get_the_date('Y-m-d');
            $days_old = (time() - strtotime($post_date)) / (60 * 60 * 24);
            $inventory = get_field('product_inventory');
            
            if ($days_old < 30) {
                $badge = 'New';
            } elseif ($inventory === 'low_stock') {
                $badge = 'Limited';
            } elseif (get_comments_number() > 5) {
                $badge = 'Popular';
            }
            ?>
            <div class="product-card" 
                 data-strain-type="<?php echo $strain ? get_field('strain_type', $strain->ID) : ''; ?>"
                 data-effects="<?php echo $strain ? implode(',', get_field('strain_effects', $strain->ID) ?: array()) : ''; ?>">
                <div class="product-image">
                    <?php if (has_post_thumbnail()): ?>
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail('medium', array('alt' => get_the_title())); ?>
                        </a>
                    <?php else: ?>
                        <a href="<?php the_permalink(); ?>">
                            <div class="placeholder-image">[Product Image]</div>
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($badge): ?>
                        <span class="product-badge"><?php echo esc_html($badge); ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="product-info">
                    <h3 class="product-name">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h3>
                    
                    <?php if ($product_effects): ?>
                        <div class="product-effects">
                            <?php foreach ($product_effects as $effect): ?>
                                <span class="effect-tag"><?php echo ucfirst(str_replace('_', ' ', $effect)); ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="product-description">
                        <?php 
                        $aroma = get_field('aroma_profile');
                        $flavor = get_field('flavor_profile');
                        if ($aroma) {
                            echo esc_html(wp_trim_words($aroma, 15));
                        } elseif ($flavor) {
                            echo esc_html(wp_trim_words($flavor, 15));
                        } else {
                            echo esc_html(wp_trim_words(get_the_excerpt(), 15));
                        }
                        ?>
                    </div>
                    
                    <a href="<?php the_permalink(); ?>" class="view-product-btn">
                        View Details
                    </a>
                </div>
            </div>
            <?php
        }
        
        wp_reset_postdata();
        
        $html = ob_get_clean();
        
        wp_send_json_success(array(
            'products' => $html,
            'found_posts' => $query->found_posts,
            'max_pages' => $query->max_num_pages
        ));
    } else {
        wp_send_json_error('No products found');
    }
}
add_action('wp_ajax_skyworld_filter_products', 'skyworld_filter_products');
add_action('wp_ajax_nopriv_skyworld_filter_products', 'skyworld_filter_products');
