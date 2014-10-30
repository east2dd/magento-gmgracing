<?php while ( have_posts() ) : the_post(); 

$is_featured = get_field('is_featured');
if($is_featured)
{
    continue;
}

?>
    <div class="col-sm-3">
        <div class="staff">
            <div class="staff-thumbnail">
                <?php the_post_thumbnail('gmg1', array('class'=>'img-responsive')); ?>
            </div>
            <h5 class="staff-name clearfix">
                <?php echo get_field('first_name'); ?> <?php echo get_field('last_name'); ?>
                <a href="mailto:<?php echo get_field('email'); ?>" class="pull-right"><span class="glyphicon"></span></a>
            </h5>
            <p class="staff-position"><?php echo get_field('position'); ?></p>
        </div>
    </div>
<?php endwhile; ?>