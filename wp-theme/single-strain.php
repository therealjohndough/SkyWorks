<?php
/**
 * Single Strain Template (Hub)
 * 
 * Displays strain information and all related products
 */

get_header(); ?>

<div class="skyworld-strain-page">
    <!-- Strain Header -->
    <section class="strain-header">
        <div class="container">
            <div class="strain-breadcrumb">
                <a href="<?php echo home_url('/'); ?>" class="hub-link">
                    <span class="hub-icon">🏠</span>
                    Back to Hub
                </a>
                <span class="breadcrumb-separator">›</span>
                <a href="<?php echo get_post_type_archive_link('strain'); ?>">All Strains</a>
            </div>
            
            <div class="strain-title-section">
                <?php if (has_post_thumbnail()): ?>
                    <div class="strain-image">
                        <?php the_post_thumbnail('large'); ?>
                    </div>
                <?php endif; ?>
                
                <div class="strain-info">
                    <h1 class="strain-title"><?php the_title(); ?></h1>
                    
                    <?php if (get_field('strain_lineage')): ?>
                        <p class="strain-lineage">
                            <strong>Lineage:</strong> <?php echo get_field('strain_lineage'); ?>
                        </p>
                    <?php endif; ?>
                    
                    <div class="strain-meta">
                        <?php if (get_field('strain_thc_range')): ?>
                            <div class="meta-item">
                                <span class="meta-label">THC:</span>
                                <span class="meta-value"><?php echo get_field('strain_thc_range'); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (get_field('strain_cbd_range')): ?>
                            <div class="meta-item">
                                <span class="meta-label">CBD:</span>
                                <span class="meta-value"><?php echo get_field('strain_cbd_range'); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Strain Details -->
    <section class="strain-details">
        <div class="container">
            <div class="details-grid">
                <!-- Effects -->
                <?php if (get_field('strain_effects')): ?>
                    <div class="detail-section">
                        <h3>Effects</h3>
                        <div class="effects-list">
                            <?php 
                            $effects = get_field('strain_effects');
                            foreach ($effects as $effect): ?>
                                <span class="effect-tag"><?php echo ucfirst(str_replace('_', ' ', $effect)); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Terpenes -->
                <?php if (have_rows('strain_terpenes')): ?>
                    <div class="detail-section">
                        <h3>Dominant Terpenes</h3>
                        <div class="terpenes-list">
                            <?php while (have_rows('strain_terpenes')): the_row(); ?>
                                <div class="terpene-item">
                                    <span class="terpene-name"><?php echo get_sub_field('terpene_name'); ?></span>
                                    <span class="terpene-percentage"><?php echo get_sub_field('terpene_percentage'); ?>%</span>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Growing Info -->
                <div class="detail-section">
                    <h3>Growing Information</h3>
                    <div class="growing-info">
                        <?php if (get_field('strain_flowering_time')): ?>
                            <div class="info-item">
                                <span class="info-label">Flowering Time:</span>
                                <span class="info-value"><?php echo get_field('strain_flowering_time'); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Strain Description -->
    <?php if (get_the_content()): ?>
        <section class="strain-description">
            <div class="container">
                <div class="description-content">
                    <?php the_content(); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Related Products (Spokes) -->
    <section class="related-products">
        <div class="container">
            <h2 class="section-title">Available Products</h2>
            
            <?php
            // Get all products related to this strain
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
                <div class="products-grid">
                    <?php foreach ($related_products as $product): ?>
                        <?php 
                        $product_type = get_field('product_type', $product->ID);
                        $product_price = get_field('product_price', $product->ID);
                        $product_weight = get_field('product_weight', $product->ID);
                        $product_inventory = get_field('product_inventory', $product->ID);
                        $product_thc = get_field('product_thc_content', $product->ID);
                        ?>
                        <div class="product-card" data-inventory="<?php echo esc_attr($product_inventory); ?>">
                            <?php if (has_post_thumbnail($product->ID)): ?>
                                <div class="product-image">
                                    <a href="<?php echo get_permalink($product->ID); ?>">
                                        <?php echo get_the_post_thumbnail($product->ID, 'medium'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="product-info">
                                <h3 class="product-title">
                                    <a href="<?php echo get_permalink($product->ID); ?>">
                                        <?php echo get_the_title($product->ID); ?>
                                    </a>
                                </h3>
                                
                                <div class="product-meta">
                                    <span class="product-type"><?php echo ucfirst($product_type); ?></span>
                                    <?php if ($product_weight): ?>
                                        <span class="product-weight"><?php echo $product_weight; ?></span>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if ($product_thc): ?>
                                    <div class="product-thc">
                                        <span class="thc-label">THC:</span>
                                        <span class="thc-value"><?php echo $product_thc; ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="product-price">
                                    <?php if ($product_price): ?>
                                        <span class="price">$<?php echo number_format($product_price, 2); ?></span>
                                    <?php endif; ?>
                                    
                                    <span class="inventory-status inventory-<?php echo esc_attr($product_inventory); ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $product_inventory)); ?>
                                    </span>
                                </div>
                                
                                <a href="<?php echo get_permalink($product->ID); ?>" class="product-link">
                                    View Details
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-products">
                    <p>No products available for this strain yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Navigation to Other Strains -->
    <section class="strain-navigation">
        <div class="container">
            <div class="nav-wrapper">
                <a href="<?php echo get_post_type_archive_link('strain'); ?>" class="all-strains-link">
                    View All Strains
                </a>
                
                <?php
                // Get related strains (same strain type)
                $current_strain_types = wp_get_post_terms(get_the_ID(), 'strain_type', array('fields' => 'ids'));
                if ($current_strain_types):
                    $related_strains = get_posts(array(
                        'post_type' => 'strain',
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'strain_type',
                                'field' => 'term_id',
                                'terms' => $current_strain_types,
                            )
                        ),
                        'posts_per_page' => 3,
                        'exclude' => array(get_the_ID()),
                        'orderby' => 'rand'
                    ));
                    
                    if ($related_strains):
                ?>
                        <div class="related-strains">
                            <h3>Similar Strains</h3>
                            <div class="strains-list">
                                <?php foreach ($related_strains as $strain): ?>
                                    <a href="<?php echo get_permalink($strain->ID); ?>" class="strain-link">
                                        <?php echo get_the_title($strain->ID); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Hub Navigation -->
    <?php get_template_part('template-parts/hub-navigation'); ?>
</div>

<?php get_footer(); ?>