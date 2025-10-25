<?php
/**
 * Spoke Page Template
 * 
 * Template for spoke pages in the hub and spoke model.
 * These pages branch out from the main hub (homepage).
 */

get_header(); ?>

<div class="skyworks-spoke-page">
    <!-- Spoke Header -->
    <section class="spoke-header">
        <div class="container">
            <div class="spoke-breadcrumb">
                <a href="<?php echo home_url('/'); ?>" class="hub-link">
                    <span class="hub-icon">🏠</span>
                    Back to Hub
                </a>
            </div>
            
            <div class="spoke-title-section">
                <?php if (get_field('spoke_icon')): ?>
                    <div class="spoke-icon-large">
                        <?php 
                        $spoke_icon = get_field('spoke_icon');
                        echo wp_get_attachment_image($spoke_icon['ID'], 'medium');
                        ?>
                    </div>
                <?php endif; ?>
                
                <h1 class="spoke-title"><?php the_title(); ?></h1>
                <?php if (get_field('spoke_description')): ?>
                    <p class="spoke-description"><?php echo get_field('spoke_description'); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Spoke Content -->
    <section class="spoke-content">
        <div class="container">
            <div class="content-wrapper">
                <?php while (have_posts()): the_post(); ?>
                    <div class="spoke-main-content">
                        <?php the_content(); ?>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <!-- Related Spokes -->
    <?php
    $current_spokes = get_posts(array(
        'post_type' => 'page',
        'meta_query' => array(
            array(
                'key' => '_wp_page_template',
                'value' => 'page-spoke.php',
                'compare' => '='
            )
        ),
        'posts_per_page' => 3,
        'exclude' => array(get_the_ID()),
        'orderby' => 'rand'
    ));
    
    if ($current_spokes):
    ?>
    <section class="related-spokes">
        <div class="container">
            <h2 class="section-title">Explore More</h2>
            <div class="spokes-grid">
                <?php foreach ($current_spokes as $spoke): ?>
                    <?php 
                    $spoke_icon = get_field('spoke_icon', $spoke->ID);
                    $spoke_color = get_field('spoke_color', $spoke->ID) ?: '#00ff88';
                    ?>
                    <a href="<?php echo get_permalink($spoke->ID); ?>" 
                       class="spoke-card" 
                       style="--spoke-color: <?php echo esc_attr($spoke_color); ?>">
                        <?php if ($spoke_icon): ?>
                            <div class="spoke-icon">
                                <?php echo wp_get_attachment_image($spoke_icon['ID'], 'thumbnail'); ?>
                            </div>
                        <?php endif; ?>
                        <h3 class="spoke-title"><?php echo get_the_title($spoke->ID); ?></h3>
                        <p class="spoke-description"><?php echo get_field('spoke_description', $spoke->ID); ?></p>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Hub Navigation -->
    <section class="hub-navigation">
        <div class="container">
            <div class="hub-nav-wrapper">
                <a href="<?php echo home_url('/'); ?>" class="return-to-hub">
                    <span class="hub-icon">🏠</span>
                    Return to Hub
                </a>
                
                <div class="spoke-navigation">
                    <?php
                    // Get all spoke pages for navigation
                    $all_spokes = get_posts(array(
                        'post_type' => 'page',
                        'meta_query' => array(
                            array(
                                'key' => '_wp_page_template',
                                'value' => 'page-spoke.php',
                                'compare' => '='
                            )
                        ),
                        'posts_per_page' => -1,
                        'orderby' => 'menu_order',
                        'order' => 'ASC'
                    ));
                    
                    if ($all_spokes):
                        $current_id = get_the_ID();
                        $current_index = array_search($current_id, wp_list_pluck($all_spokes, 'ID'));
                        $prev_spoke = isset($all_spokes[$current_index - 1]) ? $all_spokes[$current_index - 1] : null;
                        $next_spoke = isset($all_spokes[$current_index + 1]) ? $all_spokes[$current_index + 1] : null;
                    ?>
                        <div class="spoke-nav-controls">
                            <?php if ($prev_spoke): ?>
                                <a href="<?php echo get_permalink($prev_spoke->ID); ?>" class="spoke-nav-prev">
                                    ← <?php echo get_the_title($prev_spoke->ID); ?>
                                </a>
                            <?php endif; ?>
                            
                            <?php if ($next_spoke): ?>
                                <a href="<?php echo get_permalink($next_spoke->ID); ?>" class="spoke-nav-next">
                                    <?php echo get_the_title($next_spoke->ID); ?> →
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>

<?php get_footer(); ?>
