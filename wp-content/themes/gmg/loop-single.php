<?php while ( have_posts() ) : the_post(); ?>
    <div <?php post_class(); ?>>
        <div class="post-thumbnail">
            <?php the_post_thumbnail(null, array('class'=>'img-responsive')); ?>
        </div>
        <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <div class="post-meta">
            Posted In: <?php the_category(', '); ?>&nbsp;&nbsp;&nbsp;
            By: <?php the_author_link();?>&nbsp;&nbsp;&nbsp;  
            When: <?php the_time('M j, Y') ?>
        </div>
        <div class="post-content"><?php the_content(); ?></div>
        
    </div>
<?php endwhile; ?>