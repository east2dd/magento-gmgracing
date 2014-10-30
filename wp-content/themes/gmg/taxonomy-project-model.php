<?php
get_header(); ?>
<div class="container">
<div class="row">
    <div class="col-md-12">
        <div id="content" class="projects">
           <div>
                <?php get_header('project-model'); ?>
           </div>
           <div>
                <?php
                    $current_model = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
                    $current_make = get_term_by('id', $current_model->parent, 'project-model');
                    
                    if ($current_make == false)
                        $current_make = $current_model;
                    
                    $model_ids = get_term_children( $current_make->term_id, 'project-model' );
                    
                    foreach($model_ids as $model_id)
                    {
                        if($current_make!=$current_model && $model_id != $current_model->term_id)
                           continue;
                        $model = get_term_by('id', $model_id, 'project-model');
                        ?>
                        <h5 class="project-category"><?php echo $current_make->name; ?> &gt; <?php echo $model->name; ?></h5>
                        <?php
                        $args = array(
                        'post_type'=> 'project',
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'project-model',
                                'terms' => $model_id,
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
                    }?>
                </div>
        </div><!-- #content -->
    </div>
</div>
</div>

<?php get_footer(); ?>
