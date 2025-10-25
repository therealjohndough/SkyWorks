<?php
/**
 * Single Product Template (Spoke)
 * 
 * Modern two-column product page with comprehensive data integration
 */

get_header(); ?>

<div class="skyworld-product-page">
    <!-- Product Container -->
    <div class="product-container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="<?php echo home_url('/'); ?>">Home</a>
            <span>/</span>
            <a href="<?php echo get_post_type_archive_link('cannabis_product'); ?>">Products</a>
            <span>/</span>
            <span class="current"><?php the_title(); ?></span>
        </div>

        <!-- Product Main Section -->
        <div class="product-main">
            <!-- Product Image Section -->
            <div class="product-image-section">
                <?php 
                $product_images = get_field('product_images');
                if ($product_images && count($product_images) > 0): 
                    $main_image = $product_images[0];
                ?>
                    <div class="product-image">
                        <img src="<?php echo esc_url($main_image['sizes']['large']); ?>" 
                             alt="<?php echo esc_attr($main_image['alt']); ?>" 
                             id="main-product-image">
                    </div>
                    <?php if (count($product_images) > 1): ?>
                        <div class="product-thumbnails">
                            <?php foreach ($product_images as $index => $image): ?>
                                <div class="thumbnail <?php echo $index === 0 ? 'active' : ''; ?>" 
                                     data-image="<?php echo esc_url($image['sizes']['large']); ?>"
                                     data-alt="<?php echo esc_attr($image['alt']); ?>">
                                    <img src="<?php echo esc_url($image['sizes']['thumbnail']); ?>" 
                                         alt="<?php echo esc_attr($image['alt']); ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php elseif (has_post_thumbnail()): ?>
                    <div class="product-image">
                        <?php the_post_thumbnail('large', array('id' => 'main-product-image')); ?>
                    </div>
                <?php else: ?>
                    <div class="product-image">
                        <div class="placeholder-image">[Product Image - <?php the_title(); ?>]</div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Product Info -->
            <div class="product-info">
                <h1 class="product-title"><?php the_title(); ?></h1>
                <p class="product-subtitle">by SkyWorld</p>

                <!-- Effects from Parent Strain -->
                <?php 
                $strain = get_field('product_strain');
                if ($strain && get_field('strain_effects', $strain->ID)): 
                    $effects = get_field('strain_effects', $strain->ID);
                ?>
                    <div class="product-effects">
                        <?php foreach ($effects as $effect): ?>
                            <div class="effect-badge"><?php echo ucfirst(str_replace('_', ' ', $effect)); ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Aroma Profile -->
                <?php if (get_field('aroma_profile')): ?>
                    <div class="detail-section">
                        <h3>Aroma Profile</h3>
                        <p><?php echo get_field('aroma_profile'); ?></p>
                    </div>
                <?php endif; ?>

                <!-- Flavor Profile -->
                <?php if (get_field('flavor_profile')): ?>
                    <div class="detail-section">
                        <h3>Flavor Profile</h3>
                        <p><?php echo get_field('flavor_profile'); ?></p>
                    </div>
                <?php endif; ?>

                <!-- Dominant Terpenes from Parent Strain -->
                <?php 
                if ($strain && have_rows('strain_terpenes', $strain->ID)): 
                ?>
                    <div class="detail-section">
                        <h3>Dominant Terpenes</h3>
                        <ul class="terpenes-list">
                            <?php while (have_rows('strain_terpenes', $strain->ID)): the_row(); ?>
                                <li class="terpene-item">
                                    <span class="terpene-name"><?php echo get_sub_field('terpene_name'); ?></span>
                                    <span class="terpene-percentage"><?php echo get_sub_field('terpene_percentage'); ?>%</span>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- CTA Section -->
                <div class="cta-section">
                    <h3>Find <?php the_title(); ?> at a Store Near You</h3>
                    <?php 
                    $store_locator = get_field('product_store_locator');
                    if ($store_locator): 
                    ?>
                        <a href="<?php echo esc_url($store_locator['url']); ?>" 
                           class="find-stores-btn"
                           <?php if ($store_locator['target']): ?>target="_blank"<?php endif; ?>>
                            Find Stores
                        </a>
                    <?php else: ?>
                        <a href="<?php echo home_url('/store-locator/'); ?>" class="find-stores-btn">
                            Find Stores
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Additional Product Details (Collapsible) -->
                <div class="product-details-toggle">
                    <button class="toggle-details-btn">View Product Details</button>
                    <div class="product-details-content" style="display: none;">
                        <!-- Pricing & Inventory -->
                        <div class="detail-section">
                            <h3>Pricing & Availability</h3>
                            <div class="pricing-info">
                                <?php if (get_field('product_price')): ?>
                                    <div class="price-display">
                                        <span class="price">$<?php echo number_format(get_field('product_price'), 2); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php 
                                $inventory = get_field('product_inventory');
                                $inventory_class = str_replace('_', '-', $inventory);
                                ?>
                                <div class="inventory-status status-<?php echo esc_attr($inventory_class); ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $inventory)); ?>
                                </div>
                            </div>
                        </div>

                        <!-- Cannabinoid Content -->
                        <div class="detail-section">
                            <h3>Cannabinoid Content</h3>
                            <div class="cannabinoid-info">
                                <?php if (get_field('product_thc_content')): ?>
                                    <div class="cannabinoid-item">
                                        <span class="cannabinoid-label">THC:</span>
                                        <span class="cannabinoid-value"><?php echo get_field('product_thc_content'); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (get_field('product_cbd_content')): ?>
                                    <div class="cannabinoid-item">
                                        <span class="cannabinoid-label">CBD:</span>
                                        <span class="cannabinoid-value"><?php echo get_field('product_cbd_content'); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Lab Results -->
                        <?php if (get_field('product_lab_results')): ?>
                            <div class="detail-section">
                                <h3>Lab Results</h3>
                                <div class="lab-results">
                                    <a href="<?php echo get_field('product_lab_results')['url']; ?>" 
                                       class="lab-results-link" 
                                       target="_blank">
                                        View Lab Test Results
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Product Meta -->
                        <div class="detail-section">
                            <h3>Product Information</h3>
                            <div class="product-meta">
                                <?php if (get_field('product_type')): ?>
                                    <div class="meta-item">
                                        <span class="meta-label">Type:</span>
                                        <span class="meta-value"><?php echo ucfirst(get_field('product_type')); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (get_field('product_weight')): ?>
                                    <div class="meta-item">
                                        <span class="meta-label">Size:</span>
                                        <span class="meta-value"><?php echo get_field('product_weight'); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (get_field('product_sku')): ?>
                                    <div class="meta-item">
                                        <span class="meta-label">SKU:</span>
                                        <span class="meta-value"><?php echo get_field('product_sku'); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <section class="related-products">
        <h2 class="section-title">You May Also Like</h2>
        <div class="related-grid">
            <?php
            // Get related products from same strain
            $same_strain_products = array();
            if ($strain) {
                $same_strain_products = get_posts(array(
                    'post_type' => 'cannabis_product',
                    'meta_query' => array(
                        array(
                            'key' => 'product_strain',
                            'value' => $strain->ID,
                            'compare' => '='
                        )
                    ),
                    'posts_per_page' => 2,
                    'exclude' => array(get_the_ID()),
                    'orderby' => 'menu_order',
                    'order' => 'ASC'
                ));
            }
            
            // Get products from different strains with similar effects
            $cross_strain_products = array();
            if ($strain && get_field('strain_effects', $strain->ID)) {
                $current_effects = get_field('strain_effects', $strain->ID);
                $cross_strain_products = get_posts(array(
                    'post_type' => 'cannabis_product',
                    'meta_query' => array(
                        array(
                            'key' => 'product_strain',
                            'value' => $strain->ID,
                            'compare' => '!='
                        )
                    ),
                    'posts_per_page' => 2,
                    'exclude' => array(get_the_ID()),
                    'orderby' => 'rand'
                ));
            }
            
            // Combine and display products
            $related_products = array_merge($same_strain_products, $cross_strain_products);
            $related_products = array_slice($related_products, 0, 4); // Limit to 4 products
            
            if ($related_products):
                foreach ($related_products as $product): 
                    $product_strain = get_field('product_strain', $product->ID);
                    $product_effects = array();
                    if ($product_strain && get_field('strain_effects', $product_strain->ID)) {
                        $product_effects = get_field('strain_effects', $product_strain->ID);
                        $product_effects = array_slice($product_effects, 0, 3); // Limit to 3 effects
                    }
            ?>
                <div class="related-card">
                    <div class="related-image">
                        <?php if (has_post_thumbnail($product->ID)): ?>
                            <a href="<?php echo get_permalink($product->ID); ?>">
                                <?php echo get_the_post_thumbnail($product->ID, 'medium'); ?>
                            </a>
                        <?php else: ?>
                            <span>[Product Image]</span>
                        <?php endif; ?>
                    </div>
                    <div class="related-info">
                        <h4>
                            <a href="<?php echo get_permalink($product->ID); ?>">
                                <?php echo get_the_title($product->ID); ?>
                            </a>
                        </h4>
                        <?php if ($product_effects): ?>
                            <p>
                                <?php 
                                $effect_names = array_map(function($effect) {
                                    return ucfirst(str_replace('_', ' ', $effect));
                                }, $product_effects);
                                echo implode(' • ', $effect_names);
                                ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php 
                endforeach;
            else:
            ?>
                <div class="no-products">
                    <p>No related products found.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Hub Navigation -->
    <?php get_template_part('template-parts/hub-navigation'); ?>
</div>

<?php get_footer(); ?>