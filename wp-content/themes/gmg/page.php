<?php
get_header(); 
the_post();
?>

<div id="container" class="container">
    <div id="content" role="main">
        <div class="row">
            <div class="col-sm-3">
                <h2 class="page-title"><span><?php the_title(); ?></span></h2>
            </div>
        </div>
        <div class="page-featured-image"><?php the_post_thumbnail('full', array('class'=>'img-responsive'));?></div>
        <div class="page-content"><?php the_content(); ?></div>
    </div><!-- #content -->
</div><!-- #container -->
<?php get_footer(); ?>
