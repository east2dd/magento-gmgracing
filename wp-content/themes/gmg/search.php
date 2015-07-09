<?php
/**
 * The template for displaying Search Results pages
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>
<div class="search-result" id="search-results">
  <div id="search-form-container">
      <form id="search-form" class="navbar-left" role="search" action="" method="get">
        <div class="container">
            <a class="clear" href="/"></a>
            <p>
              <label>SEARCH RESULTS FOR:</label>
            </p>
            <p>
              <input type="text" name="s" placeholder=" TYPE TO SEARCH" id="input-search" autofocus="autofocus" value="<?php echo $_GET['s'];?>">
              <input type="hidden" name="post_type" value="<?php echo $_GET['post_type']; ?>">
              <input type="hidden" name="category_name" value="<?php echo $_GET['category_name']; ?>">
            </p>
        </div>
      </form>
  </div>

  <div id="search-tabs">
    <div class="container">
      <div class="row">
        <div class="col-sm-4"><a href="/store/catalogsearch/result?q=<?php echo $_GET['s']; ?>">PRODUCTS</a></div>
        <div class="col-sm-4"><a href="/?s=<?php echo $_GET['s']; ?>&post_type=post&category_name=<?php echo $_GET['category_name']; ?>" class="<?php if($_GET['post_type']!='project'){ echo 'active'; } ?>">JOURNALS</a></div>
        <div class="col-sm-4"><a href="/?s=<?php echo $_GET['s']; ?>&post_type=project" class="<?php if($_GET['post_type']=='project'){ echo 'active'; } ?>">PROJECT CARS</a></div>
      </div>
    </div>
  </div>
  <div id="content" class="container" role="main">
	<?php if ( have_posts() ) : ?>
		<?php
			// Start the Loop.
			while ( have_posts() ) : the_post();
      ?>
        <div class="post">
          <div class="row">
            <div class="col-sm-4">
                <div class="post-thumbnail"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('full', array('class'=>'img-responsive'));?></a></div>
            </div>
            <div class="col-sm-8">
              <div class="post-brand">GMG RACING</div>
              <div class="post-title"><a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a></div>
              <div class="post-excerpt"><?php the_excerpt(); ?></div>
              <div class="post-date"><?php the_time('M j, Y'); ?><br/><br/></div>
              <div class="post-link"><a href="<?php the_permalink()?>"><?php the_permalink()?></a></div>
            </div>
          </div>
        </div>
      <?php

			endwhile;

		else :

		endif;
	?>

  <div id="pagination">
    <?php
    global $wp_query;

    $big = 999999999; // need an unlikely integer

    echo paginate_links( array(
      'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
      'format' => '?paged=%#%',
      'current' => max( 1, get_query_var('paged') ),
      'total' => $wp_query->max_num_pages,
      'prev_text'          => __('Previous'),
      'next_text'          => __('Next'),
    ) );
    ?>
  </div>
  </div><!-- #content -->
</div>

<?php
get_footer();
