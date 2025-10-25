<?php
/**
 * Archive Template for Cannabis Products
 * 
 * Modern grid-based product archive with filtering and AJAX load more
 */

get_header(); ?>

<div class="skyworld-archive-products">
    <!-- Archive Hero -->
    <section class="archive-hero">
        <div class="container">
            <h1>Our Products</h1>
            <p>Premium indoor cultivation from New York. Explore our curated selection of high-quality cannabis strains.</p>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="filter-section">
        <div class="filter-container">
            <div class="filter-group">
                <span class="filter-label">Filter by:</span>
                <button class="filter-btn active" data-filter="all">All</button>
                <?php
                $strain_types = get_terms(array(
                    'taxonomy' => 'strain_type',
                    'hide_empty' => true,
                ));
                if ($strain_types && !is_wp_error($strain_types)):
                    foreach ($strain_types as $type):
                ?>
                    <button class="filter-btn" data-filter="<?php echo esc_attr($type->slug); ?>">
                        <?php echo esc_html($type->name); ?>
                    </button>
                <?php 
                    endforeach;
                endif;
                ?>
            </div>
            <div class="filter-group">
                <span class="filter-label">Effect:</span>
                <button class="filter-btn" data-effect="all">All</button>
                <?php
                $effects = get_terms(array(
                    'taxonomy' => 'effects',
                    'hide_empty' => true,
                ));
                if ($effects && !is_wp_error($effects)):
                    foreach ($effects as $effect):
                ?>
                    <button class="filter-btn" data-effect="<?php echo esc_attr($effect->slug); ?>">
                        <?php echo esc_html($effect->name); ?>
                    </button>
                <?php 
                    endforeach;
                endif;
                ?>
            </div>
            <span class="product-count">
                Showing <span id="product-count"><?php echo $wp_query->found_posts; ?></span> products
            </span>
        </div>
    </section>

    <!-- Products Archive -->
    <section class="products-archive">
        <div class="products-grid" id="products-grid">
            <?php if (have_posts()): ?>
                <?php while (have_posts()): the_post(); 
                    $strain = get_field('product_strain');
                    $product_effects = array();
                    if ($strain && get_field('strain_effects', $strain->ID)) {
                        $product_effects = get_field('strain_effects', $strain->ID);
                        $product_effects = array_slice($product_effects, 0, 3); // Limit to 3 effects
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
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-products">
                    <h3>No products found</h3>
                    <p>Try adjusting your filters or check back later for new products.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Load More -->
        <?php if ($wp_query->max_num_pages > 1): ?>
            <div class="load-more-section">
                <button class="load-more-btn" id="load-more-btn" data-page="1" data-max-pages="<?php echo $wp_query->max_num_pages; ?>">
                    Load More Products
                </button>
            </div>
        <?php endif; ?>
    </section>
</div>

<?php get_footer(); ?>