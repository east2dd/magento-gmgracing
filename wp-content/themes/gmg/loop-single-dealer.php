<?php while ( have_posts() ) : the_post(); 
        $distance = get_field('distance', $post->ID);
        $elevation_gain = get_field('elevation_gain', $post->ID);
        $estimated_ride_time = get_field('estimated_ride_time', $post->ID);
?>
    <div class="featured text-center">
        <?php the_post_thumbnail(null, array('class'=>'img-responsive')); ?>
        <div class="graph">
            <?php $graph = get_field('graph'); ?>
            <img src="<?php echo $graph['url'];?>">
        </div>
        <div class="meta">
            <div class="row">
                <div class="col-sm-4"><?php echo $distance; ?> miles</div>
                <div class="col-sm-4"><?php echo $elevation_gain; ?> of elevation gain</div>
                <div class="col-sm-4"><?php echo $estimated_ride_time; ?> estimated ride time</div>
            </div>
        </div>
    </div>
    <div class="container">
        <?php the_content(); ?>
    </div>
<?php endwhile; ?>