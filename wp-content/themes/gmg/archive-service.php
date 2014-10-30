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


        <div id="content" class="services">
            <div class="container">
                <div class="row">
                    <div class="col-sm-3">
                        <h2 class="page-title"><span>CAPABILITIES</span></h2>
                    </div>
                </div>
                <div>
                    <p>
                        Global Motorsports Group (GMG) is North America’s premier service facility for Porsche, Audi, Ferrari, Mercedes and other European performance automobiles. Located in Southern California, GMG technicians are factory trained and utilize the most comprehensive, up-to-date service techniques and equipment available. 
                    </p>
                    <p>
                        GMG technicians have years of specialized hands-on training and experience working with your specific automobile.  Our technicians are experts in the particular brand of vehicle they work on, assuring your car is being attended to by someone who knows the ins and outs of your make and model like no one else.   When it comes to rare models within a manufactures' range, GMG has the advantage over dealer garages as our techs have worked across the entire lifetime of these vehicles, not just until the warranties runs out.
                    </p>
                </div>
            </div>
            <div id="services">
                <?php
                    get_template_part( 'loop', 'archive-service' );
                ?>
            </div>
        </div><!-- #content -->



<?php get_footer(); ?>