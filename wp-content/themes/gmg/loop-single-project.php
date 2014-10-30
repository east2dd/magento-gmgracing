<?php
    $slider = get_field('slider', $post->ID);
    $slides = get_field('slides', $slider->ID);
?>

<div id="slideshow-1" class="cycle-slideshow" data-cycle-fx=scrollHorz
    data-cycle-timeout='0'
    data-cycle-pager="#adv-custom-pager"
    data-cycle-pager-template="<a href='#' class='col-xs-4 col-sm-2'><img class='img-responsive' src='{{src}}'></a>"
    >
    <?php
        foreach($slides as $slide): 
    ?>
        <img class="img-responsive" src="<?php echo $slide['image']['url'];?>">
    <?php
        endforeach;
    ?>
</div>
<div id="adv-custom-pager" class="row"></div>

<?php while ( have_posts() ) : the_post(); ?>
    <div <?php post_class(); ?>>
        <h2 class="post-title"><?php the_title(); ?></h2>
        <div class="post-meta">
            <?php the_terms( $post->ID, 'project-model', 'Posted In: ', ', ' ); ?>
        </div>
        <div class="post-content"><?php the_content(); ?></div>
        <div class="infos">
            <table class="table table-striped">
                <tr>
                    <td class="col-sm-2"><label>Car:</label></td>
                    <td><?php the_title(); ?></td>
                </tr>
                <tr>
                    <td><label>Performance:</label></td>
                    <td><?php echo get_field('performance'); ?></td>
                </tr>
                <tr>
                    <td><label>Suspension:</label></td>
                    <td><?php echo get_field('suspension'); ?></td>
                </tr>
                <tr>
                    <td><label>Wheels &amp; Tires:</label></td>
                    <td><?php echo get_field('wheels_tires'); ?></td>
                </tr>
                <tr>
                    <td><label>Brakes:</label></td>
                    <td><?php echo get_field('brakes'); ?></td>
                </tr>
                <tr>
                    <td><label>Exterior:</label></td>
                    <td><?php echo get_field('exterior'); ?></td>
                </tr>
                <tr>
                    <td><label>Interior:</label></td>
                    <td><?php echo get_field('interior'); ?></td>
                </tr>
            </table>
        </div>
    </div>
<?php endwhile; ?>

<div class="dottedline"></div>

<div class="comments">
</div>