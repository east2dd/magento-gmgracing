<div class="infos">
    <p>
        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('gmg1', array('class'=>'img-responsive')); ?></a>
    </p>
    <p>
        <strong>Name:</strong><?php echo get_field('first_name'); ?> <?php echo get_field('last_name'); ?>
    </p>
    <p>
        <strong>Position:</strong><?php echo get_field('position'); ?>
    </p>
    <p>
        <strong>Email:</strong><?php echo get_field('email'); ?>
    </p>
    <p>
        <?php the_content(); ?>
    </p>
</div>