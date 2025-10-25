<?php
/**
 * Hero Section Template Part
 * 
 * Main hero section with title and CTA
 */
?>

<!-- Hero Section -->
<section class="hero-section" id="home">
    <div class="hero-background">
        <?php if (get_field('hero_background')): ?>
            <?php 
            $hero_bg = get_field('hero_background');
            $hero_bg_url = $hero_bg['sizes']['large'] ?? $hero_bg['url'];
            ?>
            <div class="hero-bg-image" style="background-image: url('<?php echo esc_url($hero_bg_url); ?>');"></div>
        <?php endif; ?>
        <div class="hero-bg"></div>
    </div>
    
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">Premium Indoor Cultivation</h1>
            <p class="hero-subtitle">Born from a passion for the plant</p>
            <a href="#featured" class="cta-btn">Learn More</a>
        </div>
    </div>
</section>
