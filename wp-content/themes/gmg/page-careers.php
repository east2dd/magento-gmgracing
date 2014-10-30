<?php
/*
* Template Name: About - Careers
*/
get_header(); 
the_post();
?>

<div id="container" class="container">
    <div id="content" role="main">
    	<div id="scrolling-stop-1"></div>
    	<div class="page-head">
	        <div class="row">
	            <div class="col-sm-3">
	                <h2 class="page-title"><span><?php the_title(); ?></span></h2>
	            </div>
	        </div>
    	</div>
        <div class="page-featured-image"><?php the_post_thumbnail('full', array('class'=>'img-responsive'));?></div>
        <div class="page-content">
        	<?php the_content(); ?>
        	<div id="scrolling-stop-2"></div>
        	<div class="row">
        		<div class="col-sm-8">
        			<div id="jobs">
	        			<?php
	        				$args = array('post_type' => 'job','post_count'=>0);
							$jobs = new WP_Query($args);
						?>
						<?php if ( $jobs->have_posts() ): 
								while ( $jobs->have_posts() ): 
									$jobs->the_post(); 

									$functions_responsiblities = get_field('essential_functions_responsiblities', $post->ID);
									$contact = get_field('contact', $post->ID);
						?>
							<div class="job" id="job<?php echo $post->ID?>">
								<h3><?php the_title(); ?></h3>
								<div><?php the_content(); ?></div>

								<p><strong>ESSENTIAL FUNCTIONS &amp; RESPONSIBLITIES</strong></p>
								<p>
									<?php foreach($functions_responsiblities as $item):?>
										+ <?php echo $item['function_responsibility']; ?><br/>
									<?php endforeach;?>
								</p>

								<p>
									<strong>CONTACT:</strong> <a href="mailto:<?php echo $contact;?>"><span><?php echo $contact;?></span></a>
								</p>
							</div>

						<?php endwhile; endif; ?>
					</div>
        		</div>
        		<div class="col-sm-4">
        			
        			<div id="openings">
        				<h3>Openings</h3>
						<?php if ( $jobs->have_posts() ): 
								while ( $jobs->have_posts() ): 
									$jobs->the_post();
						?>
								<p><a href="#job<?php echo $post->ID;?>"><strong><?php the_title(); ?></strong></a></p>
						<?php endwhile; endif; ?>
					</div>
					
					<script src="<?php bloginfo('template_directory') ?>/js/jquery.floatScroll.js"></script>
					<script type="text/javascript">
						jQuery(".page-head").floatScroll({
							positionTop: 0,
							zIndex: 9
						});
					</script>
					<script type="text/javascript">
						
						jQuery(function($){
							$('#openings a').on('click', function(e){
								e.preventDefault();
								var target = $($(this).attr("href"));
								$('body').animate({ scrollTop: target.offset().top - 80 });

							});

							$(window).scroll(function(){
								if($(window).scrollTop() > $('#scrolling-stop-1').offset().top)
								{
									$('.page-head').addClass("fixed-position");
								}else{
									$('.page-head').removeClass("fixed-position");
								}

								if($(window).scrollTop() > $('#scrolling-stop-2').offset().top - 50)
								{
									$('#openings').addClass("fixed-position");
								}else{
									$('#openings').removeClass("fixed-position");
								}

							});
						});

					</script>
        		</div>
        	</div>
        </div>
    </div><!-- #content -->
</div><!-- #container -->
<?php get_footer(); ?>
