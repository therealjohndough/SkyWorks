<?php get_header(); ?>

<div class="skyworks-content">
    <div class="container">
        <?php if (have_posts()): ?>
            <div class="posts-grid">
                <?php while (have_posts()): the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?>>
                        <?php if (has_post_thumbnail()): ?>
                            <div class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium', array('class' => 'featured-image')); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="post-content">
                            <header class="post-header">
                                <h2 class="post-title">
                                    <a href="<?php the_permalink(); ?>" rel="bookmark">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>
                                <div class="post-meta">
                                    <span class="post-date"><?php echo get_the_date(); ?></span>
                                    <span class="post-author"><?php the_author(); ?></span>
                                </div>
                            </header>
                            
                            <div class="post-excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                            
                            <a href="<?php the_permalink(); ?>" class="read-more-btn">
                                <?php esc_html_e('Read More', 'skyworks'); ?>
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
            
            <div class="pagination">
                <?php
                the_posts_pagination(array(
                    'mid_size' => 2,
                    'prev_text' => __('Previous', 'skyworks'),
                    'next_text' => __('Next', 'skyworks'),
                ));
                ?>
            </div>
        <?php else: ?>
            <div class="no-posts">
                <h2><?php esc_html_e('No posts found', 'skyworks'); ?></h2>
                <p><?php esc_html_e('It looks like nothing was found at this location.', 'skyworks'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>