<?php

get_header(); 

$slider = get_field('home_slider', 'option');
    
$slides = get_field('slides', $slider->ID);

?>

<div id="content" role="main" class="home">
<div class="cycle-slideshow" data-cycle-speed="1000" data-cycle-timeout="5000" data-slides=".slide" data-pager-template="<span></span>">
    <?php
        foreach($slides as $slide): 
    ?>
        <div class="slide">
            <a href="<?php echo $slide['link'];?>" target="<?php echo $slide['target'];?>">
                <img style="width:100%;" src="<?php echo $slide['image']['url'];?>">
            </a>
        </div>
    <?php
        endforeach;
    ?>
    <div id="progress"></div>
    <div class="cycle-pager"></div>
</div>
<div class="container">
    <div class="row">
        <div class="col-sm-6 col-md-3">
            <div class="featured">
                <h3 class="featured-title">RACING NEWS</h3>
                <?php
                $category_id = get_cat_ID( 'racing' );
                $category_link = get_category_link( $category_id );
                
                $args = array(
                    'post_type' => 'post',
                    'showposts' => 1,
                    'category_name' => 'racing',
                    'meta_key'=>'is_featured',
                    'meta_value'=>'1'
                );
                $featured = new WP_Query($args);
                ?>
                <?php while ( $featured->have_posts() ) : $featured->the_post(); ?>
                        <div class="featured-thumbnail">
                            <a href="<?php echo get_permalink($featured->ID);?>"><?php the_post_thumbnail('gmg1', array('class'=>'img-responsive')); ?></a>
                        </div>
                        <div class="featured-content">
                            <h4><a href="<?php echo get_permalink($featured->ID);?>"><?php the_title(); ?></a></h4>
                            <div class="post-excerpt"><?php the_excerpt(r); ?></div>
                        </div>
                <?php endwhile; ?>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="featured">
                <h3 class="featured-title">PROJECTS</h3>
                <?php
                $args = array(
                    'post_type' => 'project',
                    'showposts' => 1,
                    'meta_key'=>'is_featured',
                    'meta_value'=>'1'
                );
                $featured = new WP_Query($args);
    
                ?>
                <?php while ( $featured->have_posts() ) : $featured->the_post(); ?>
                        <div class="featured-thumbnail">
                            <a href="<?php echo get_permalink($featured->ID);?>"><?php the_post_thumbnail('gmg1', array('class'=>'img-responsive')); ?></a>
                        </div>
                        <div class="featured-content">
                            <h4><a href="<?php echo get_permalink($featured->ID);?>"><?php the_title(); ?></a></h4>
                            <div class="post-excerpt"><?php the_excerpt(r); ?></div>
                        </div>
                <?php endwhile; ?>
            </div>
        </div>
        
        <!-- Add the extra clearfix for only the required viewport -->
        <div class="clearfix visible-xs visible-sm"></div>

        <div class="col-sm-6 col-md-3">
            <div class="featured">
                <h3 class="featured-title">JOURNAL NEWS</h3>
                <?php
                $category_id = get_cat_ID( 'journal' );
                $category_link = get_category_link( $category_id );
                $args = array(
                    'post_type' => 'post',
                    'showposts' => 1,
                    'category_name' => 'journal',
                    'meta_key'=>'is_featured',
                    'meta_value'=>'1'
                );
                $featured = new WP_Query($args);
                ?>
                <?php while ( $featured->have_posts() ) : $featured->the_post(); ?>
                        <div class="featured-thumbnail">
                            <a href="<?php echo get_permalink($featured->ID);?>"><?php the_post_thumbnail('gmg1', array('class'=>'img-responsive')); ?></a>
                        </div>
                        <div class="featured-content">
                            <h4><a href="<?php echo get_permalink($featured->ID);?>"><?php the_title(); ?></a></h4>
                            <div class="post-excerpt"><?php the_excerpt(); ?></div>
                        </div>
                <?php endwhile; ?>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="featured">
                <h3 class="featured-title">INSTAGRAM</h3>
                <div class="instagram">
                    <?php echo do_shortcode('[simply_instagram endpoints="users" type="recent-media" size="standard_resolution" display="1"]'); ?>
                </div>
            </div>
        </div>
    </div>
</div>


    
<div class="container">
    <div id="our-partners" class="text-center">
        <p class="grayline"></p>
        <h2>OUR PARTNERS</h2>
        <p class="grayline"></p>
        <p class="logos">
            <a href=""><img src="<?php bloginfo('template_directory') ?>/images/partner-mobile1.png" /></a>
            <a href=""><img src="<?php bloginfo('template_directory') ?>/images/partner-eibach.png" /></a>
            <a href=""><img src="<?php bloginfo('template_directory') ?>/images/partner-stoptech.png" /></a>
            <a href=""><img src="<?php bloginfo('template_directory') ?>/images/partner-pirelli.png" /></a>
            <a href=""><img src="<?php bloginfo('template_directory') ?>/images/partner-brembo.png" /></a>
            <a href=""><img src="<?php bloginfo('template_directory') ?>/images/partner-giac.png" /></a>
            <a href=""><img src="<?php bloginfo('template_directory') ?>/images/partner-jrz.png" /></a>
        </p>
        <p class="logos">
            <a href=""><img src="<?php bloginfo('template_directory') ?>/images/partner-posche.png" /></a>
            <a href=""><img src="<?php bloginfo('template_directory') ?>/images/partner-audisport.png" /></a>
            <a href=""><img src="<?php bloginfo('template_directory') ?>/images/partner-worldc.png" /></a>
            <a href=""><img src="<?php bloginfo('template_directory') ?>/images/partner-alms.png" /></a>
            <a href=""><img src="<?php bloginfo('template_directory') ?>/images/partner-usc.png" /></a>
            <a href=""><img src="<?php bloginfo('template_directory') ?>/images/partner-super.png" /></a>
        </p>
        <div style="display:none;">
           
            <img src="<?php bloginfo('template_directory') ?>/images/partner-mobile1-on.png" />
            <img src="<?php bloginfo('template_directory') ?>/images/partner-eibach-on.png" />
            <img src="<?php bloginfo('template_directory') ?>/images/partner-stoptech-on.png" />
            <img src="<?php bloginfo('template_directory') ?>/images/partner-pirelli-on.png" />
            <img src="<?php bloginfo('template_directory') ?>/images/partner-brembo-on.png" />
            <img src="<?php bloginfo('template_directory') ?>/images/partner-giac-on.png" />
            <img src="<?php bloginfo('template_directory') ?>/images/partner-jrz-on.png" />
       
            <img src="<?php bloginfo('template_directory') ?>/images/partner-posche-on.png" />
            <img src="<?php bloginfo('template_directory') ?>/images/partner-audisport-on.png" />
            <img src="<?php bloginfo('template_directory') ?>/images/partner-worldc-on.png" />
            <img src="<?php bloginfo('template_directory') ?>/images/partner-alms-on.png" />
            <img src="<?php bloginfo('template_directory') ?>/images/partner-usc-on.png" />
            <img src="<?php bloginfo('template_directory') ?>/images/partner-super-on.png" />
          
        </div>
    </div>

</div>

<script>
jQuery(function($){
var progress = $('#progress'),
    slideshow = $( '.cycle-slideshow' );

slideshow.on( 'cycle-initialized cycle-before', function( e, opts ) {
    progress.stop(true).css( 'width', 0 );
});

slideshow.on( 'cycle-initialized cycle-after', function( e, opts ) {
    if ( ! slideshow.is('.cycle-paused') )
        progress.animate({ width: '100%' }, opts.timeout, 'linear' );
});

slideshow.on( 'cycle-paused', function( e, opts ) {
   progress.stop(); 
});

slideshow.on( 'cycle-resumed', function( e, opts, timeoutRemaining ) {
    progress.animate({ width: '100%' }, timeoutRemaining, 'linear' );
});
    
});

</script>
</div><!-- #content -->
<?php get_footer(); ?>
