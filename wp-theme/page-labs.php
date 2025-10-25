<?php
/**
 * Labs/COAs Page Template
 * 
 * Quality Check - COAs and Lab Results
 */

get_header(); ?>

<div class="skyworld-labs-page">
    <!-- Labs Header -->
    <section class="labs-header">
        <div class="container">
            <h1 class="page-title">QUALITY CHECK</h1>
            
            <div class="labs-intro">
                <p>At Skyworld, we believe in complete transparency. Every batch of our premium indoor-grown cannabis undergoes comprehensive third-party laboratory testing to ensure quality, potency, and safety.</p>
            </div>
        </div>
    </section>

    <!-- COA Results -->
    <section class="coa-results">
        <div class="container">
            <h2 class="section-title">Certificate of Analysis (COA) Results</h2>
            
            <div class="coa-filters">
                <div class="filter-group">
                    <label for="product_filter">Filter by Product:</label>
                    <select id="product_filter" class="coa-filter">
                        <option value="">All Products</option>
                        <?php
                        $products = get_posts(array(
                            'post_type' => 'cannabis_product',
                            'posts_per_page' => -1,
                            'orderby' => 'title',
                            'order' => 'ASC'
                        ));
                        
                        if ($products):
                            foreach ($products as $product):
                        ?>
                            <option value="<?php echo $product->ID; ?>">
                                <?php echo get_the_title($product->ID); ?>
                            </option>
                        <?php 
                            endforeach;
                        endif;
                        ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="date_filter">Filter by Date:</label>
                    <select id="date_filter" class="coa-filter">
                        <option value="">All Dates</option>
                        <option value="last_month">Last Month</option>
                        <option value="last_3_months">Last 3 Months</option>
                        <option value="last_6_months">Last 6 Months</option>
                    </select>
                </div>
            </div>
            
            <div class="coa-results-grid">
                <?php
                // Get all cannabis products with COA data
                $products_with_coas = get_posts(array(
                    'post_type' => 'cannabis_product',
                    'posts_per_page' => -1,
                    'meta_query' => array(
                        array(
                            'key' => 'cannabis_product_lab_results',
                            'compare' => 'EXISTS'
                        )
                    ),
                    'orderby' => 'date',
                    'order' => 'DESC'
                ));
                
                if ($products_with_coas):
                    foreach ($products_with_coas as $product):
                        $strain = get_field('cannabis_product_strain', $product->ID);
                        $thc_content = get_field('cannabis_product_thc_content', $product->ID);
                        $cbd_content = get_field('cannabis_product_cbd_content', $product->ID);
                        $lab_results = get_field('cannabis_product_lab_results', $product->ID);
                        $batch_id = get_field('cannabis_product_batch_id', $product->ID);
                ?>
                    <div class="coa-card" data-product="<?php echo $product->ID; ?>" data-date="<?php echo get_the_date('Y-m-d', $product->ID); ?>">
                        <div class="coa-header">
                            <h3 class="product-name">
                                <a href="<?php echo get_permalink($product->ID); ?>">
                                    <?php echo get_the_title($product->ID); ?>
                                </a>
                            </h3>
                            
                            <?php if ($strain): ?>
                                <div class="strain-ref">
                                    <span class="strain-label">Strain:</span>
                                    <a href="<?php echo get_permalink($strain->ID); ?>" class="strain-name">
                                        <?php echo get_the_title($strain->ID); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($batch_id): ?>
                                <div class="batch-id">
                                    <span class="batch-label">Batch ID:</span>
                                    <span class="batch-value"><?php echo $batch_id; ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="coa-stats">
                            <div class="stat-grid">
                                <?php if ($thc_content): ?>
                                    <div class="stat-item">
                                        <span class="stat-label">THC</span>
                                        <span class="stat-value"><?php echo $thc_content; ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($cbd_content): ?>
                                    <div class="stat-item">
                                        <span class="stat-label">CBD</span>
                                        <span class="stat-value"><?php echo $cbd_content; ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="stat-item">
                                    <span class="stat-label">Tested</span>
                                    <span class="stat-value"><?php echo get_the_date('M j, Y', $product->ID); ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="coa-actions">
                            <?php if ($lab_results): ?>
                                <a href="<?php echo $lab_results['url']; ?>" 
                                   class="coa-download" 
                                   target="_blank">
                                    <span class="download-icon">📄</span>
                                    Download COA
                                </a>
                            <?php endif; ?>
                            
                            <a href="<?php echo get_permalink($product->ID); ?>" class="view-product">
                                View Product
                            </a>
                        </div>
                    </div>
                <?php 
                    endforeach;
                else:
                ?>
                    <div class="no-coas">
                        <p>No COA results available at this time. Check back soon for updated lab results.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Quality Standards -->
    <section class="quality-standards">
        <div class="container">
            <h2 class="section-title">Our Quality Standards</h2>
            
            <div class="standards-grid">
                <div class="standard-item">
                    <div class="standard-icon">🧪</div>
                    <h3>Third-Party Testing</h3>
                    <p>Every batch is tested by independent, certified laboratories to ensure accuracy and compliance.</p>
                </div>
                
                <div class="standard-item">
                    <div class="standard-icon">📊</div>
                    <h3>Comprehensive Analysis</h3>
                    <p>Full cannabinoid and terpene profiles, plus testing for pesticides, heavy metals, and microbial contaminants.</p>
                </div>
                
                <div class="standard-item">
                    <div class="standard-icon">🔒</div>
                    <h3>Transparency</h3>
                    <p>All lab results are publicly available and linked to specific product batches for complete traceability.</p>
                </div>
                
                <div class="standard-item">
                    <div class="standard-icon">✅</div>
                    <h3>NY Compliance</h3>
                    <p>All products meet or exceed New York State cannabis regulations and testing requirements.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Lab Partners -->
    <section class="lab-partners">
        <div class="container">
            <h2 class="section-title">Testing Partners</h2>
            <p>We work with trusted, certified laboratories to ensure the highest standards of testing and compliance.</p>
            
            <div class="partners-grid">
                <!-- This would be populated with actual lab partner logos/info -->
                <div class="partner-item">
                    <div class="partner-logo">Lab Logo</div>
                    <div class="partner-info">
                        <h4>Certified Testing Lab</h4>
                        <p>NY State Licensed</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
// Simple filtering for COA results
document.addEventListener('DOMContentLoaded', function() {
    const productFilter = document.getElementById('product_filter');
    const dateFilter = document.getElementById('date_filter');
    const coaCards = document.querySelectorAll('.coa-card');
    
    function filterCOAs() {
        const selectedProduct = productFilter.value;
        const selectedDate = dateFilter.value;
        
        coaCards.forEach(card => {
            let show = true;
            
            if (selectedProduct && card.dataset.product !== selectedProduct) {
                show = false;
            }
            
            if (selectedDate) {
                const cardDate = new Date(card.dataset.date);
                const now = new Date();
                const monthsAgo = selectedDate === 'last_month' ? 1 : 
                                selectedDate === 'last_3_months' ? 3 : 6;
                const cutoffDate = new Date(now.getFullYear(), now.getMonth() - monthsAgo, now.getDate());
                
                if (cardDate < cutoffDate) {
                    show = false;
                }
            }
            
            card.style.display = show ? 'block' : 'none';
        });
    }
    
    if (productFilter) productFilter.addEventListener('change', filterCOAs);
    if (dateFilter) dateFilter.addEventListener('change', filterCOAs);
});
</script>

<?php get_footer(); ?>
