<?php
/*
* Template Name: Search Result
*/

echo get_header();
?>
<div id="content" role="main" class="search-result">
  <div class="container">
    <div class="products">
      <?php 
        // The Query
        $query = $_GET['q'];
        $query3 = $_GET['q'] . ', 3';

        global $Magento;
      ?>
      <h2>PRODUCTS</h2>
      <?php
        //the_widget('Magento_Products_Widget', array('name_like'=>$query), array('widget_id'=>'meta') );
      ?>
      <?php 
        echo do_shortcode("[magento name_like='".$query3."']");         
      ?>
      <div class="row"><div class="col-sm-4"><hr></div></div>
    </div>

    <div class="journals">
      <?php 
        // The Query
        $args = array(
          'post_type' => 'post',
          'category_name' => 'journal',
          's'=>$_GET['q'],
          'posts_per_page'=>3
        );
        $the_query = new WP_Query( $args );
      ?>
      <h2>JOURNALS</h2>
      <div class="search-meta">
        <?php echo $the_query->post_count; ?> of <?php echo $the_query->found_posts; ?> results &nbsp;&nbsp;&nbsp;
        <?php if ($the_query->max_num_pages > 1): ?>
          <a href="#">View All</a>
        <?php endif; ?>
      </div>
      <div class="row">
      <?php
        // The Loop
        if ( $the_query->have_posts() ) {
          while ( $the_query->have_posts() ) {
            $the_query->the_post();
            ?>
              <div class="col-sm-4">
                <div class="post">
                  <div class="post-thumbnail"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('full', array('class'=>'img-responsive'));?></a></div>
                  <div class="post-title"><a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a></div>
                  <div class="post-date"><?php the_time('M j, Y') ?></div>
                </div>
              </div>
            <?php
          }
        } else {
          // no posts found
        }
        /* Restore original Post Data */
        wp_reset_postdata();
      ?>
      </div>
      <div class="row"><div class="col-sm-4"><hr></div></div>
    </div>

    <div class="project-cars">
      <?php 
        // The Query
        $args = array(
          'post_type' => 'project',
          's'=>$_GET['q'],
          'posts_per_page'=>3
        );
        $the_query = new WP_Query( $args );
      ?>
      <h2>PROJECT CARS</h2>
      <div class="search-meta">
        <?php echo $the_query->post_count; ?> of <?php echo $the_query->found_posts; ?> results &nbsp;&nbsp;&nbsp;
        <?php if ($the_query->max_num_pages > 1): ?>
          <a href="#">View All</a>
        <?php endif; ?>
      </div>
      <div class="row">
      <?php
        // The Loop
        if ( $the_query->have_posts() ) {
          while ( $the_query->have_posts() ) {
            $the_query->the_post();
            ?>
              <div class="col-sm-4">
                <div class="post">
                  <div class="post-thumbnail"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('full', array('class'=>'img-responsive'));?></a></div>
                  <div class="post-title"><a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a></div>
                  <div class="post-date"><?php the_time('M j, Y') ?></div>
                </div>
              </div>
            <?php
          }
        } else {
          // no posts found
        }
        /* Restore original Post Data */
        wp_reset_postdata();
      ?>
      </div>
      <div class="row"><div class="col-sm-4"><hr></div></div>
    </div>



  </div>
</div>
<?php
echo get_footer();
?>