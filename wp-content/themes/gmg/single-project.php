<?php
    get_header(); 
?>
<?php 
    $makes = get_terms( 'project-model', array( 'parent' => 0 ) );
?>
<div id="container" class="container">
	<div id="content" role="main" class="projects">
	    <div>
	       <?php get_header('project-model'); ?>
	    </div>
	    <div>
        	<?php
        	/* Run the loop to output the post.
        	 * If you want to overload this in a child theme then include a file
        	 * called loop-single.php and that will be used instead.
        	 */
        	get_template_part( 'loop', 'single-project' );
        	?>
    	</div>

	</div><!-- #content -->
</div><!-- #container -->
<?php get_footer(); ?>
