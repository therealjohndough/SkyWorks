<?php
/**
 * Skyworld Cannabis Homepage
 * 
 * Premium Indoor Cultivation - born from a passion for the plant
 */

get_header(); ?>

<div class="skyworld-homepage">
    <?php get_template_part('template-parts/hero-section'); ?>
    <?php get_template_part('template-parts/featured-section'); ?>

    <!-- Products Section - Explore SkyWorld -->
    <section class="products" id="products">
        <div class="container">
            <h2 class="section-title">Explore SkyWorld</h2>
            <div class="products-grid">
                <div class="product-card">
                    <div class="product-icon"></div>
                    <h4>Genetics</h4>
                    <p>Premium cannabis genetics</p>
                </div>
                <div class="product-card">
                    <div class="product-icon"></div>
                    <h4>Apparel</h4>
                    <p>Official SkyWorld merch</p>
                </div>
                <div class="product-card">
                    <div class="product-icon"></div>
                    <h4>Products</h4>
                    <p>Our full product line</p>
                </div>
                <div class="product-card">
                    <div class="product-icon"></div>
                    <h4>SkyNews</h4>
                    <p>Latest updates & news</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Store Locator Section -->
    <section class="tour" id="locations">
        <div class="container">
            <h2 class="section-title">Find SkyWorld Near You</h2>
            <p class="tour-description">We believe New Yorkers deserve access to consistent, high-quality cannabis grown with expertise and transparency. Our mission is simple: to elevate your cannabis experience through uncompromising quality, rooted right here in NY.</p>
            
            <!-- Store Locator Plugin Placeholder -->
            <div class="store-locator-placeholder">
                <p class="locator-title">[AGILE STORE LOCATOR PLUGIN]</p>
                <p class="locator-subtitle">Interactive store locator map will be integrated here</p>
            </div>
            
            <div class="tour-locations">
                <div class="location-tag">New York</div>
            </div>
        </div>
    </section>

    <!-- Hub Navigation -->
    <?php get_template_part('template-parts/hub-navigation'); ?>

</div>

<?php get_template_part('template-parts/age-gate'); ?>

<?php get_footer(); ?>
