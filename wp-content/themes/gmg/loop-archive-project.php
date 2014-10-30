<?php while ( have_posts() ) : the_post(); ?>
    <div class="col-sm-4">
        <div class="project">
            <div class="post-thumbnail">
                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('gmg1', array('class'=>'img-responsive')); ?></a>
            </div>
            <h5 class="project-title clearfix">
                <a href="<?php the_permalink(); ?>" class="pull-left"><?php the_title(); ?></a>
                <span class="model pull-right"><?php echo get_field("model") ?></span>
            </h5>
        </div>
    </div>
<?php endwhile; ?>