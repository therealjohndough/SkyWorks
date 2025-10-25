<?php
/**
 * Enhanced Hub Navigation Template Part
 * 
 * Comprehensive navigation system connecting strains (hubs) with products (spokes)
 */
?>

<!-- Enhanced Hub Navigation -->
<section class="hub-navigation" role="navigation" aria-label="Hub and Spoke Navigation">
    <div class="container">
        <div class="hub-nav-wrapper">
            
            <!-- Hub Breadcrumb -->
            <div class="hub-breadcrumb">
                <nav class="breadcrumb-nav" aria-label="Breadcrumb">
                    <ol class="breadcrumb-list">
                        <li class="breadcrumb-item">
                            <a href="<?php echo home_url('/'); ?>" class="hub-home-link">
                                <span class="hub-icon" aria-hidden="true">🏠</span>
                                <span class="hub-text">Skyworld Hub</span>
                            </a>
                        </li>
                        <?php if (is_single()): ?>
                            <li class="breadcrumb-separator" aria-hidden="true">›</li>
                            <li class="breadcrumb-item">
                                <a href="<?php echo get_post_type_archive_link(get_post_type()); ?>" class="archive-link">
                                    <?php echo ucfirst(str_replace('_', ' ', get_post_type())); ?>s
                                </a>
                            </li>
                            <li class="breadcrumb-separator" aria-hidden="true">›</li>
                            <li class="breadcrumb-item current" aria-current="page">
                                <?php the_title(); ?>
                            </li>
                        <?php endif; ?>
                    </ol>
                </nav>
            </div>

            <!-- Strain Hub Navigation -->
            <?php if (is_single() && get_post_type() === 'strain'): ?>
                <div class="strain-hub-section">
                    <div class="hub-header">
                        <h2 class="hub-title">Strain Hub: <?php the_title(); ?></h2>
                        <p class="hub-description">Explore all products derived from this strain</p>
                    </div>
                    
                    <?php
                    $related_products = get_posts(array(
                        'post_type' => 'cannabis_product',
                        'meta_query' => array(
                            array(
                                'key' => 'product_strain',
                                'value' => get_the_ID(),
                                'compare' => '='
                            )
                        ),
                        'posts_per_page' => -1,
                        'orderby' => 'menu_order',
                        'order' => 'ASC'
                    ));
                    
                    if ($related_products):
                    ?>
                        <div class="spoke-products-grid">
                            <h3 class="spoke-title">Available Products (Spokes)</h3>
                            <div class="products-grid">
                                <?php foreach ($related_products as $product): 
                                    $product_type = get_field('product_type', $product->ID);
                                    $product_price = get_field('product_price', $product->ID);
                                    $product_weight = get_field('product_weight', $product->ID);
                                    $product_inventory = get_field('product_inventory', $product->ID);
                                ?>
                                    <div class="spoke-product-card" data-inventory="<?php echo esc_attr($product_inventory); ?>">
                                        <div class="product-header">
                                            <h4 class="product-title">
                                                <a href="<?php echo get_permalink($product->ID); ?>">
                                                    <?php echo get_the_title($product->ID); ?>
                                                </a>
                                            </h4>
                                            <span class="product-type-badge"><?php echo ucfirst($product_type); ?></span>
                                        </div>
                                        
                                        <div class="product-details">
                                            <?php if ($product_weight): ?>
                                                <div class="detail-item">
                                                    <span class="detail-label">Size:</span>
                                                    <span class="detail-value"><?php echo $product_weight; ?></span>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if ($product_price): ?>
                                                <div class="detail-item">
                                                    <span class="detail-label">Price:</span>
                                                    <span class="detail-value">$<?php echo number_format($product_price, 2); ?></span>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div class="detail-item">
                                                <span class="detail-label">Status:</span>
                                                <span class="inventory-status inventory-<?php echo esc_attr($product_inventory); ?>">
                                                    <?php echo ucfirst(str_replace('_', ' ', $product_inventory)); ?>
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="product-actions">
                                            <a href="<?php echo get_permalink($product->ID); ?>" class="view-product-btn">
                                                View Details
                                            </a>
                                            <a href="<?php echo home_url('/store-locator/'); ?>" class="find-near-btn">
                                                Find Near You
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="no-products-message">
                            <p>No products available for this strain yet.</p>
                            <a href="<?php echo get_post_type_archive_link('cannabis_product'); ?>" class="browse-products-link">
                                Browse All Products
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Product Spoke Navigation -->
            <?php if (is_single() && get_post_type() === 'cannabis_product'): ?>
                <div class="product-spoke-section">
                    <?php 
                    $strain = get_field('product_strain');
                    if ($strain): ?>
                        <div class="spoke-to-hub-connection">
                            <div class="connection-header">
                                <h2 class="connection-title">Connected to Strain Hub</h2>
                                <div class="strain-info">
                                    <h3 class="strain-name">
                                        <a href="<?php echo get_permalink($strain->ID); ?>">
                                            <?php echo get_the_title($strain->ID); ?>
                                        </a>
                                    </h3>
                                    <?php if (get_field('strain_lineage', $strain->ID)): ?>
                                        <p class="strain-lineage">
                                            <strong>Lineage:</strong> <?php echo get_field('strain_lineage', $strain->ID); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="hub-actions">
                                <a href="<?php echo get_permalink($strain->ID); ?>" class="view-strain-btn">
                                    <span class="btn-icon" aria-hidden="true">🧬</span>
                                    View Full Strain Information
                                </a>
                            </div>
                            
                            <?php
                            // Get other products from same strain
                            $same_strain_products = get_posts(array(
                                'post_type' => 'cannabis_product',
                                'meta_query' => array(
                                    array(
                                        'key' => 'product_strain',
                                        'value' => $strain->ID,
                                        'compare' => '='
                                    )
                                ),
                                'posts_per_page' => 4,
                                'exclude' => array(get_the_ID()),
                                'orderby' => 'menu_order',
                                'order' => 'ASC'
                            ));
                            
                            if ($same_strain_products):
                            ?>
                                <div class="same-strain-products">
                                    <h3 class="related-products-title">Other <?php echo get_the_title($strain->ID); ?> Products</h3>
                                    <div class="related-products-grid">
                                        <?php foreach ($same_strain_products as $product): 
                                            $product_type = get_field('product_type', $product->ID);
                                            $product_price = get_field('product_price', $product->ID);
                                        ?>
                                            <div class="related-product-card">
                                                <h4 class="related-product-title">
                                                    <a href="<?php echo get_permalink($product->ID); ?>">
                                                        <?php echo get_the_title($product->ID); ?>
                                                    </a>
                                                </h4>
                                                <div class="related-product-meta">
                                                    <span class="product-type"><?php echo ucfirst($product_type); ?></span>
                                                    <?php if ($product_price): ?>
                                                        <span class="product-price">$<?php echo number_format($product_price, 2); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="no-strain-connection">
                            <p>This product is not connected to a strain hub.</p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Global Navigation -->
            <div class="global-nav-section">
                <div class="nav-categories">
                    <h3 class="nav-title">Explore Skyworld</h3>
                    <div class="nav-links-grid">
                        <a href="<?php echo get_post_type_archive_link('strain'); ?>" class="nav-category-link strain-link">
                            <span class="nav-icon" aria-hidden="true">🧬</span>
                            <div class="nav-content">
                                <span class="nav-label">Strain Library</span>
                                <span class="nav-description">Browse our genetic library</span>
                            </div>
                        </a>
                        
                        <a href="<?php echo get_post_type_archive_link('cannabis_product'); ?>" class="nav-category-link product-link">
                            <span class="nav-icon" aria-hidden="true">🛍️</span>
                            <div class="nav-content">
                                <span class="nav-label">Product Catalog</span>
                                <span class="nav-description">Shop available products</span>
                            </div>
                        </a>
                        
                        <a href="<?php echo home_url('/labs/'); ?>" class="nav-category-link labs-link">
                            <span class="nav-icon" aria-hidden="true">🔬</span>
                            <div class="nav-content">
                                <span class="nav-label">Lab Results</span>
                                <span class="nav-description">View COAs & testing</span>
                            </div>
                        </a>
                        
                        <a href="<?php echo home_url('/store-locator/'); ?>" class="nav-category-link locator-link">
                            <span class="nav-icon" aria-hidden="true">📍</span>
                            <div class="nav-content">
                                <span class="nav-label">Store Locator</span>
                                <span class="nav-description">Find near you</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
