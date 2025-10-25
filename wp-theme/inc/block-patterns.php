<?php
/**
 * Block Patterns for Skyworld Theme
 * 
 * Reusable block patterns for common layouts
 */

// Register block patterns
function skyworld_register_block_patterns() {
    // Badge Strip Pattern
    register_block_pattern(
        'skyworld/badge-strip',
        array(
            'title' => 'Badge Strip',
            'description' => 'Indigenous-owned and license badges',
            'content' => '<!-- wp:group {"className":"badge-strip","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"center"}} -->
<div class="wp-block-group badge-strip"><!-- wp:group {"className":"badge indigenous-owned","layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group badge indigenous-owned"><!-- wp:paragraph {"className":"badge-icon"} -->
<p class="badge-icon">🌱</p>
<!-- /wp:paragraph --><!-- wp:paragraph {"className":"badge-text"} -->
<p class="badge-text">Indigenous-Owned</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --><!-- wp:group {"className":"badge licensed","layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group badge licensed"><!-- wp:paragraph {"className":"badge-icon"} -->
<p class="badge-icon">📜</p>
<!-- /wp:paragraph --><!-- wp:paragraph {"className":"badge-text"} -->
<p class="badge-text">Licensed</p>
<!-- /wp:paragraph --><!-- wp:paragraph {"className":"license-ids"} -->
<p class="license-ids">#OCM-PROC-24-000030<br>#OCM-CULT-2023-000179</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->',
            'categories' => array('skyworld'),
        )
    );

    // Stats Grid Pattern
    register_block_pattern(
        'skyworld/stats-grid',
        array(
            'title' => 'Stats Grid',
            'description' => 'THC, CBD, and terpene statistics grid',
            'content' => '<!-- wp:group {"className":"stats-grid","layout":{"type":"grid","columnCount":3}} -->
<div class="wp-block-group stats-grid"><!-- wp:group {"className":"stat-item","layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group stat-item"><!-- wp:paragraph {"className":"stat-label"} -->
<p class="stat-label">THC</p>
<!-- /wp:paragraph --><!-- wp:paragraph {"className":"stat-value"} -->
<p class="stat-value">22.5%</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --><!-- wp:group {"className":"stat-item","layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group stat-item"><!-- wp:paragraph {"className":"stat-label"} -->
<p class="stat-label">CBD</p>
<!-- /wp:paragraph --><!-- wp:paragraph {"className":"stat-value"} -->
<p class="stat-value">1.2%</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --><!-- wp:group {"className":"stat-item","layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group stat-item"><!-- wp:paragraph {"className":"stat-label"} -->
<p class="stat-label">Terpenes</p>
<!-- /wp:paragraph --><!-- wp:paragraph {"className":"stat-value"} -->
<p class="stat-value">2.8%</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->',
            'categories' => array('skyworld'),
        )
    );

    // COA List Pattern
    register_block_pattern(
        'skyworld/coa-list',
        array(
            'title' => 'COA List',
            'description' => 'Certificate of Analysis results list',
            'content' => '<!-- wp:group {"className":"coa-results-grid","layout":{"type":"grid","columnCount":2}} -->
<div class="wp-block-group coa-results-grid"><!-- wp:group {"className":"coa-card","layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group coa-card"><!-- wp:heading {"level":3,"className":"product-name"} -->
<h3 class="product-name">Product Name</h3>
<!-- /wp:heading --><!-- wp:group {"className":"coa-stats","layout":{"type":"flex","flexWrap":"wrap"}} -->
<div class="wp-block-group coa-stats"><!-- wp:paragraph {"className":"stat-item"} -->
<p class="stat-item"><strong>THC:</strong> 22.5%</p>
<!-- /wp:paragraph --><!-- wp:paragraph {"className":"stat-item"} -->
<p class="stat-item"><strong>CBD:</strong> 1.2%</p>
<!-- /wp:paragraph --><!-- wp:paragraph {"className":"stat-item"} -->
<p class="stat-item"><strong>Tested:</strong> Jan 15, 2025</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --><!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"coa-download"} -->
<div class="wp-block-button coa-download"><a class="wp-block-button__link wp-element-button">📄 Download COA</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->',
            'categories' => array('skyworld'),
        )
    );

    // Post Cards Pattern
    register_block_pattern(
        'skyworld/post-cards',
        array(
            'title' => 'Post Cards',
            'description' => '3-column news/blog post cards',
            'content' => '<!-- wp:group {"className":"news-grid","layout":{"type":"grid","columnCount":3}} -->
<div class="wp-block-group news-grid"><!-- wp:group {"className":"news-card","layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group news-card"><!-- wp:image {"className":"news-image"} -->
<figure class="wp-block-image news-image"><img src=""/></figure>
<!-- /wp:image --><!-- wp:group {"className":"news-content","layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group news-content"><!-- wp:paragraph {"className":"news-meta"} -->
<p class="news-meta">Jan 15, 2025</p>
<!-- /wp:paragraph --><!-- wp:heading {"level":3,"className":"news-title"} -->
<h3 class="news-title">News Title</h3>
<!-- /wp:heading --><!-- wp:paragraph {"className":"news-excerpt"} -->
<p class="news-excerpt">News excerpt content goes here...</p>
<!-- /wp:paragraph --><!-- wp:paragraph {"className":"read-more"} -->
<p class="read-more"><a href="#">Read More</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->',
            'categories' => array('skyworld'),
        )
    );

    // CTA Bar Pattern
    register_block_pattern(
        'skyworld/cta-bar',
        array(
            'title' => 'CTA Bar',
            'description' => 'Call-to-action button bar',
            'content' => '<!-- wp:group {"className":"cta-bar","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"center"}} -->
<div class="wp-block-group cta-bar"><!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"cta-btn cta-primary"} -->
<div class="wp-block-button cta-btn cta-primary"><a class="wp-block-button__link wp-element-button">Connect (Wholesale)</a></div>
<!-- /wp:button --><!-- wp:button {"className":"cta-btn cta-secondary"} -->
<div class="wp-block-button cta-btn cta-secondary"><a class="wp-block-button__link wp-element-button">Store Locator</a></div>
<!-- /wp:button --><!-- wp:button {"className":"cta-btn cta-secondary"} -->
<div class="wp-block-button cta-btn cta-secondary"><a class="wp-block-button__link wp-element-button">View COAs</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->',
            'categories' => array('skyworld'),
        )
    );
}
add_action('init', 'skyworld_register_block_patterns');

// Register pattern category
function skyworld_register_pattern_category() {
    register_block_pattern_category(
        'skyworld',
        array('label' => __('Skyworld Cannabis', 'skyworld'))
    );
}
add_action('init', 'skyworld_register_pattern_category');
