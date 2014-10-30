<?php get_header(); ?>
<div class="container">
    <div id="featured">
        <?php
        $cur_cat_id = get_cat_id( single_cat_title("",false) );
        $args = array(
            'post_type' => 'post',
            'showposts' => 1,
            'cat'=>$cur_cat_id,
            'category_id' => $cur_cat_id,
            'meta_key'=>'is_featured',
            'meta_value'=>'1'
        );
        $featured = new WP_Query($args);
        ?>
        <?php while ( $featured->have_posts() ) : $featured->the_post(); ?>
            <div <?php post_class(); ?>>
                <div class="post-thumbnail">
                    <?php the_post_thumbnail('gmg2', array('class'=>'img-responsive')); ?>
                </div>
                <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <div class="post-meta">
                    Posted In: <?php the_category(', '); ?>&nbsp;&nbsp;&nbsp;
                    By: <?php the_author_link();?>&nbsp;&nbsp;&nbsp;  
                    When: <?php the_time('M j, Y') ?>
                </div>
                <div class="post-excerpt"><?php the_excerpt(r); ?></div>
                <div class="post-readmore text-right clearfix">
                    <a class="btn btn-readmore" href="<?php the_permalink(); ?>">READ MORE <span class="glyphicon glyphicon-play"></span></a>
                </div>
                
            </div>
        <?php endwhile; ?>
    </div>
    
    <div class="row">
        <div class="col-sm-8 col-md-8">
            <div id="content" class="news">
                <?php
                $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
                
                while ( have_posts() ) : the_post(); ?>
                
                    <div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
                        <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <div class="post-meta">
                            Posted In: <?php the_category(', '); ?>&nbsp;&nbsp;&nbsp;
                            By: <?php the_author_link();?>&nbsp;&nbsp;&nbsp;
                            When: <?php the_time('M j, Y') ?>
                        </div>
                        <div class="row">
                            <div class="col-sm-3 col-xs-4">
                              <div class="post-thumbnail"><?php the_post_thumbnail('gmg3', array('class'=>'img-responsive')); ?></div>
                            </div>
                            <div class="col-sm-9 col-xs-8">
                                <div class="post-excerpt"><?php the_excerpt(); ?></div>
                                <div class="readmore text-right clearfix">
                                    <a class="btn btn-readmore" href="<?php the_permalink(); ?>">READ MORE <span class="glyphicon glyphicon-play"></span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                <?php endwhile; ?>
                
                <div class="pagination">
                    <?php

                    global $wp_query;

                    $big = 999999999; // need an unlikely integer
                    echo paginate_links( array(
                        'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                        'format' => '?page=%#%',
                        'current' => max( 1, get_query_var('paged') ),
                        'total' => $wp_query->max_num_pages,
                        'prev_text' => '<span class="glyphicon glyphicon-chevron-left"></span> PREVIOUS', 'next_text'=>'NEXT <span class="glyphicon glyphicon-chevron-right"></span>'
                    ) );
                ?>
                </div>
            </div><!-- #content -->
        </div>
        <div class="col-sm-4 col-md-4">
            <?php get_sidebar('blog'); ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>
