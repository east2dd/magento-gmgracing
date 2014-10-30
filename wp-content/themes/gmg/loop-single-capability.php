<?php 
    the_post();
?>
<?php
    $slider = get_field('slider', $post->ID);
    $slides = get_field('slides', $slider->ID);
?>
<div class="row">
    <div class="col-sm-4">
        <h2 class="page-title"><?php the_title(); ?> Services</h2>
    </div>
</div>
<div class="featured-image">
    <?php //the_post_thumbnail('full', array('class'=>'img-responsive')); ?>
    <div id="slideshow-1" class="cycle-slideshow"
        data-cycle-timeout='0'
        >
        <div class="cycle-prev cycle-arrow"></div>
        <div class="cycle-next cycle-arrow"></div>
        <?php
            foreach($slides as $slide): 
        ?>
            <img class="img-responsive" src="<?php echo $slide['image']['url'];?>">
        <?php
            endforeach;
        ?>
    </div>
</div>
<div class="service-content">
    <?php the_content(); ?>
</div>

<h3>PROFESSIONAL SERVICE</h3>
<div class="text-center"><img src="<?php bloginfo('template_directory') ?>/images/icon-wrench-pro.png"/></div>

<?php
    $professional_services = get_field('professional_services', $post->ID);
?>
<div id="professional-services">
<?php foreach($professional_services as $service):?>
    <div class="row">
        <div class="col-sm-4">
            <div class="service-name"><?php echo $service['service_name'];?></div>
        </div>
        <div class="col-sm-8">
            <div class="service-description"><?php echo $service['service_description'];?></div>
        </div>
    </div>
<?php endforeach; ?>
</div>

<h3>EQUIPMENT</h3>
<div class="text-center"><img src="<?php bloginfo('template_directory') ?>/images/icon-wrench-eq.png"/></div>

<?php
    $equipment = get_field('equipment', $post->ID);
?>
<div id="equipment">
    <?php echo $equipment; ?>
</div>