<?php

/**
 * The Header for our theme.
**/

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html <?php language_attributes(); ?>>

<head>

<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>

<?php

	global $page, $paged;

	wp_title( '|', true, 'right' );

	bloginfo( 'name' );

	// Add the blog description for the home/front page.

	$site_description = get_bloginfo( 'description', 'display' );

	if ( $site_description && ( is_home() || is_front_page() ) )

		echo " | $site_description";

	// Add a page number if necessary:

	if ( $paged >= 2 || $page >= 2 )

		echo ' | ' . sprintf( __( 'Page %s', 'twentyten' ), max( $paged, $page ) );

	?>

</title>

<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('template_directory') ?>/style.css" />

<?php

	/* We add some JavaScript to pages with the comment form

	 * to support sites with threaded comments (when in use).

	 */

	if ( is_singular() && get_option( 'thread_comments' ) )

		wp_enqueue_script( 'comment-reply' );

	wp_head();

?>

<?php
  echo get_header('include');
?>

</head>

<body>
  
  <?php
    echo get_header('main');
  ?>
    <div role="main" id="main">