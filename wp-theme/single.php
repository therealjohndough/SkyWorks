<?php get_header(); ?>

<div class="skyworks-content single-post">
    <div class="container">
        <?php while (have_posts()): the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('post-single'); ?>>
                <header class="post-header">
                    <h1 class="post-title"><?php the_title(); ?></h1>
                    <div class="post-meta">
                        <span class="post-date"><?php echo get_the_date(); ?></span>
                        <span class="post-author"><?php the_author(); ?></span>
                        <?php if (has_category()): ?>
                            <span class="post-categories"><?php the_category(', '); ?></span>
                        <?php endif; ?>
                    </div>
                </header>

                <?php if (has_post_thumbnail()): ?>
                    <div class="post-featured-image">
                        <?php the_post_thumbnail('large', array('class' => 'featured-image')); ?>
                    </div>
                <?php endif; ?>

                <div class="post-content-wrapper">
                    <div class="post-content">
                        <?php the_content(); ?>
                    </div>

                    <?php if (has_tag()): ?>
                        <div class="post-tags">
                            <h3><?php esc_html_e('Tags:', 'skyworks'); ?></h3>
                            <?php the_tags('<ul class="tag-list"><li>', '</li><li>', '</li></ul>'); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <nav class="post-navigation" role="navigation">
                    <?php
                    the_post_navigation(array(
                        'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', 'skyworks') . '</span> <span class="nav-title">%title</span>',
                        'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', 'skyworks') . '</span> <span class="nav-title">%title</span>',
                    ));
                    ?>
                </nav>
            </article>
        <?php endwhile; ?>
    </div>
</div>

<?php get_footer(); ?>