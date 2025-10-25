<?php
/**
 * Store Locator Page Template
 * 
 * Interactive store locator for finding Skyworld products
 */

get_header(); ?>

<div class="skyworld-store-locator-page">
    <!-- Store Locator Header -->
    <section class="locator-header">
        <div class="container">
            <h1 class="page-title">Find Skyworld Near You</h1>
            <p class="locator-description">Locate dispensaries and retailers carrying our premium indoor-grown cannabis products throughout New York State.</p>
        </div>
    </section>

    <!-- Store Locator Map -->
    <section class="store-locator-map">
        <div class="container">
            <!-- Agile Store Locator Plugin Integration -->
            <div class="locator-wrapper">
                <div class="locator-placeholder">
                    <h3>[AGILE STORE LOCATOR PLUGIN]</h3>
                    <p>Interactive store locator map will be integrated here</p>
                    <div class="locator-features">
                        <div class="feature-item">
                            <span class="feature-icon">📍</span>
                            <span class="feature-text">Find nearby dispensaries</span>
                        </div>
                        <div class="feature-item">
                            <span class="feature-icon">🛒</span>
                            <span class="feature-text">Check product availability</span>
                        </div>
                        <div class="feature-item">
                            <span class="feature-icon">📱</span>
                            <span class="feature-text">Get directions & hours</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Locations -->
    <section class="featured-locations">
        <div class="container">
            <h2 class="section-title">Featured Locations</h2>
            <div class="locations-grid">
                <?php
                // Get featured retailers (if we create a retailer CPT)
                $featured_retailers = get_posts(array(
                    'post_type' => 'retailer',
                    'meta_query' => array(
                        array(
                            'key' => 'featured_retailer',
                            'value' => '1',
                            'compare' => '='
                        )
                    ),
                    'posts_per_page' => 6,
                    'orderby' => 'menu_order',
                    'order' => 'ASC'
                ));
                
                if ($featured_retailers):
                    foreach ($featured_retailers as $retailer):
                        $address = get_field('retailer_address', $retailer->ID);
                        $phone = get_field('retailer_phone', $retailer->ID);
                        $website = get_field('retailer_website', $retailer->ID);
                        $hours = get_field('retailer_hours', $retailer->ID);
                ?>
                    <div class="retailer-card">
                        <h3 class="retailer-name">
                            <a href="<?php echo get_permalink($retailer->ID); ?>">
                                <?php echo get_the_title($retailer->ID); ?>
                            </a>
                        </h3>
                        
                        <?php if ($address): ?>
                            <div class="retailer-address">
                                <span class="address-icon">📍</span>
                                <span class="address-text"><?php echo $address; ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($phone): ?>
                            <div class="retailer-phone">
                                <span class="phone-icon">📞</span>
                                <a href="tel:<?php echo $phone; ?>" class="phone-link"><?php echo $phone; ?></a>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($website): ?>
                            <div class="retailer-website">
                                <a href="<?php echo esc_url($website); ?>" class="website-link" target="_blank">
                                    Visit Website
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($hours): ?>
                            <div class="retailer-hours">
                                <span class="hours-label">Hours:</span>
                                <span class="hours-text"><?php echo $hours; ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php 
                    endforeach;
                else:
                ?>
                    <div class="no-locations">
                        <p>Featured locations will be displayed here. Check back soon!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Wholesale Information -->
    <section class="wholesale-info">
        <div class="container">
            <div class="wholesale-content">
                <h2>Interested in Carrying Skyworld Products?</h2>
                <p>We're always looking to expand our retail network with quality dispensaries and retailers who share our commitment to premium cannabis products.</p>
                <div class="wholesale-ctas">
                    <a href="<?php echo home_url('/contact/'); ?>" class="cta-btn cta-primary">
                        Become a Retailer
                    </a>
                    <a href="<?php echo home_url('/about/'); ?>" class="cta-btn cta-secondary">
                        Learn About Skyworld
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Coverage Areas -->
    <section class="coverage-areas">
        <div class="container">
            <h2 class="section-title">Coverage Areas</h2>
            <div class="coverage-list">
                <div class="coverage-item">
                    <span class="coverage-icon">🏙️</span>
                    <span class="coverage-text">New York City</span>
                </div>
                <div class="coverage-item">
                    <span class="coverage-icon">🌆</span>
                    <span class="coverage-text">Albany</span>
                </div>
                <div class="coverage-item">
                    <span class="coverage-icon">🏔️</span>
                    <span class="coverage-text">Buffalo</span>
                </div>
                <div class="coverage-item">
                    <span class="coverage-icon">🌊</span>
                    <span class="coverage-text">Rochester</span>
                </div>
                <div class="coverage-item">
                    <span class="coverage-icon">🌲</span>
                    <span class="coverage-text">Syracuse</span>
                </div>
                <div class="coverage-item">
                    <span class="coverage-icon">🏛️</span>
                    <span class="coverage-text">And More</span>
                </div>
            </div>
        </div>
    </section>
</div>

<?php get_footer(); ?>
