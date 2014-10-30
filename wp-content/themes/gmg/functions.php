<?php
    require_once("inc/CPT.php");
    require_once("inc/services.php");
    require_once("inc/jobs.php"); 
    require_once("inc/capabilities.php");
    require_once("inc/dealers.php"); 
    
    add_theme_support('post-thumbnails');
    register_nav_menu( 'primary', 'Primary Menu' );
    register_nav_menu( 'about', 'Secondary About Menu' );
    
    if ( function_exists('register_sidebar') ){
        register_sidebar(array(
          'name' => __( 'Blog Sidebar' ),
          'id' => 'blog-sidebar',
          'description' => __( 'Widgets in this area will be shown on the right-hand side.' ),
          'before_title' => '<h2>',
          'after_title' => '</h2>'
        ));
    }
    
    $sliders = new CPT('slider',
        array('supports' => array('title', ''))
    );
    
    $projects = new CPT('project',
        array('supports' => array('title', 'editor'), 'has_archive' => true, 'hierarchy' => true )
    );
    
    $projects->register_taxonomy('project-model');

    $staffs = new CPT('staff',
        array('supports' => array('title', 'editor'), 'has_archive' => true, 'hierarchy' => true )
    );

    $jobs = new CPT('job',
        array('supports' => array('title', 'editor'), 'has_archive' => true, 'hierarchy' => true )
    );

    $services = new CPT('service',
        array('supports' => array('title', 'editor'), 'has_archive' => true, 'hierarchy' => true )
    );

    $capabilities = new CPT(
        array(
          'post_type_name' => 'capability',
          'singular' => 'Capability',
          'plural' => 'Capabilities',
          'slug' => 'capability'
        ),
        array('supports' => array('title', 'editor'), 'has_archive' => true, 'hierarchy' => true )
    );

    $dealers = new CPT('dealer',
        array('supports' => array('title', 'editor'), 'has_archive' => true, 'hierarchy' => true )
    );

    $dealers->register_taxonomy('area');

function my_post_queries( $query ) {
  // do not alter the query on wp-admin pages and only alter it if it's the main query
  if (!is_admin() && $query->is_main_query()){

    // alter the query for the home and category pages 

    if(is_home()){
      $query->set('posts_per_page', 5);
    }

    if(is_category()){
      $query->set('posts_per_page', 5);
    }

  }
}
add_action( 'pre_get_posts', 'my_post_queries' );
add_filter('show_admin_bar', '__return_false');
