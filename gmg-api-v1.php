<?php
	require('./wp-blog-header.php');

  $args = array(
    'post_type' => $_GET['post_type'],
    's'=>$_GET['q'],
    'posts_per_page'=>3
  );

  if (isset($_GET['category_name']))
  {
    $args = array_merge($args, array('category_name'=>$_GET['category_name']));
  }

  $the_query = new WP_Query( $args );
  $results = array();

  $results['query'] = $_GET['q'];
  $results['found'] = $the_query->found_posts;
  $results['count'] = $the_query->post_count;

  $posts = [];
  if ( $the_query->have_posts() ) {
    while ( $the_query->have_posts() ) {
      $the_query->the_post();

      $url = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );

      $posts[] = array(
          'name'=>get_the_title(),
          'description'=>get_the_excerpt(),
          'date'=>get_the_date(),
          'image'=>$url
      );
    }
  }

  $results['items'] = $posts;
  header('Content-Type: application/json');
  http_response_code(200);
  echo json_encode($results);
  exit;