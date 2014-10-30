<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
    get_header();
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div id="content" class="staffs">
                <div class="row">
                    <div class="col-sm-3">
                        <h2 class="page-title"><span>Staff</span></h2>
                    </div>
                </div>
                <div class="row" id="featured">
                    <?php
                    
                    $args = array(
                        'post_type' => 'staff',
                        'showposts' => 2,
                        'meta_key'=>'is_featured',
                        'meta_value'=>'1'
                    );
                    $featured = new WP_Query($args);
                    ?>
                    <?php while ( $featured->have_posts() ) : $featured->the_post(); ?>
                        <div class="col-sm-5">
                            <div class="featured-thumbnail">
                                <?php the_post_thumbnail('gmg1', array('class'=>'img-responsive')); ?>
                            </div>
                            <div class="featured-content">
                                <h4 class="staff-name"><?php echo get_field('first_name'); ?> <?php echo get_field('last_name'); ?></h4>
                                <p class="staff-position"><?php echo get_field('position'); ?></p>
                                <div class="staff-description"><?php the_content(); ?></div>
                                <p class="clearfix">
                                    <a href="mailto:<?php echo get_field('email'); ?>" class="staff-contact"><span class="glyphicon"></span> CONTACT <?php echo get_field('first_name'); ?></a>
                                </p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                <div class="row" id="staffs">
                <?php
                    get_template_part( 'loop', 'archive-staff' );
                ?>
                </div>
    		</div><!-- #content -->
    	</div>
    </div>
</div>
<?php get_footer(); ?>