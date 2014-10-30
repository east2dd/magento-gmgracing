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
    $makes = get_terms( 'project-model', array( 'parent' => 0 ) );
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div id="content" class="projects">
                <div>
                    <?php get_header('project-model'); ?>
                </div>
                <div>
                <?php
                    foreach($makes as $make)
                    {
                        $args = array( 'child_of' => $make->term_id);
                        $models = get_terms( 'project-model', $args);

                        foreach($models as $model)
                        {
                            ?>
                            <h5 class="project-category"><span><?php echo $make->name; ?></span><span>&nbsp;&gt;&nbsp;</span><strong><?php echo $model->name; ?></strong></h5>
                            <?php
                            $args = array(
                            'post_type'=> 'project',
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'project-model',
                                    'terms' => $model->term_id,
                                    'field' => 'id'
                                )
                            ),
                            'orderby' => 'title',
                            'order' => 'ASC'
                            );
                            
                            query_posts( $args );
                            ?>
                            <div class="row">
                            <?php
                                get_template_part( 'loop', 'archive-project' );
                            ?>
                            </div>
                            <?php
                            rewind_posts();
                        }
                    }
                ?>
                </div>
    		</div><!-- #content -->
    	</div>
    </div>
</div>
<?php get_footer(); ?>