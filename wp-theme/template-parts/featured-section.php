<?php
/**
 * Featured Section Template Part
 * 
 * "Rooted in the Empire State" section with three cards
 */
?>

<!-- Featured Section - Rooted in the Empire State -->
<section class="featured" id="featured">
    <div class="container">
        <h2 class="section-title">Rooted in the Empire State</h2>
        <div class="featured-grid">
            <div class="featured-card">
                <div class="featured-image">
                    <?php if (get_field('ny_cannabis_image', 'option')): ?>
                        <?php echo wp_get_attachment_image(get_field('ny_cannabis_image', 'option'), 'large'); ?>
                    <?php else: ?>
                        <span>[NY Cannabis Image]</span>
                    <?php endif; ?>
                </div>
                <h3>New York Proud</h3>
                <p>We are proud to be a New York cannabis brand, committed to serving our local communities with premium, locally-grown cannabis.</p>
                <a href="<?php echo home_url('/about/'); ?>" class="learn-more">Learn More</a>
            </div>
            
            <div class="featured-card">
                <div class="featured-image">
                    <?php if (get_field('indigenous_badge_image', 'option')): ?>
                        <?php echo wp_get_attachment_image(get_field('indigenous_badge_image', 'option'), 'large'); ?>
                    <?php else: ?>
                        <span>[Indigenous Badge]</span>
                    <?php endif; ?>
                </div>
                <h3>Indigenous-Owned</h3>
                <p>We believe New Yorkers deserve access to consistent, high-quality cannabis grown with expertise and transparency.</p>
                <a href="<?php echo home_url('/about/'); ?>" class="learn-more">Learn More</a>
            </div>
            
            <div class="featured-card">
                <div class="featured-image">
                    <?php if (get_field('license_badge_image', 'option')): ?>
                        <?php echo wp_get_attachment_image(get_field('license_badge_image', 'option'), 'large'); ?>
                    <?php else: ?>
                        <span>[License Badge]</span>
                    <?php endif; ?>
                </div>
                <h3>Licensed & Compliant</h3>
                <p>Fully licensed by New York State.<br><br>#OCM-PROC-24-000030<br>#OCM-CULT-2023-000179</p>
                <a href="<?php echo home_url('/labs/'); ?>" class="learn-more">Learn More</a>
            </div>
        </div>
    </div>
</section>
