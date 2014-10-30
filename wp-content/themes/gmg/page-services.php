<?php
/*
* Template Name: Services
*/

function custom_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );


get_header(); 
the_post();

?>

<?php
	$args = array('post_type' => 'capability','post_count'=>0);
	$capabilities = new WP_Query($args);
?>
<div class="container">
    <div class="row">
        <div class="col-sm-3">
            <h2 class="page-title"><span>CAPABILITIES</span></h2>
        </div>
    </div>
    <div>
        <p>
            Global Motorsports Group (GMG) is North America’s premier service facility for Porsche, Audi, Ferrari, Mercedes and other European performance automobiles. Located in Southern California, GMG technicians are factory trained and utilize the most comprehensive, up-to-date service techniques and equipment available. 
        </p>
        <p>
            GMG technicians have years of specialized hands-on training and experience working with your specific automobile.  Our technicians are experts in the particular brand of vehicle they work on, assuring your car is being attended to by someone who knows the ins and outs of your make and model like no one else.   When it comes to rare models within a manufactures' range, GMG has the advantage over dealer garages as our techs have worked across the entire lifetime of these vehicles, not just until the warranties runs out.
        </p>
    </div>
</div>
        
<div id="content" role="main">
	<div id="capabilities">
    	<?php 
			$index = 0;
			while ( $capabilities->have_posts() ) : $capabilities->the_post(); 
				$image = get_field('index_image', $post->ID);
				$text = get_field('index_text', $post->ID);
			?>
			<?php if ($index % 2 ==0){  ?>
			<div class="stripe even">
				<div class="stripe-content">
					<div class="container">
						<div class="row">
							<div class="col-sm-6 text-center">
								<div class="image" style="display: table-cell; vertical-align: middle;">
									<img src="<?php echo $image['url'];?>">
								</div>
							</div>
							<div class="col-sm-6">
								<h3><?php the_title(); ?></h3>
								<div>
									<?php echo $text; ?>
									<div class="text-right readmore">
										<a href="<?php echo get_permalink($post->ID); ?>"><?php the_title();?> PROGRAM <span class="glyphicon glyphicon-play"></span></a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php 
				}
			?>

			<?php if ($index % 2 ==1){  ?>
			<div class="stripe odd">
				<div class="stripe-content">
					<div class="container">
						<div class="row">
							<div class="col-sm-6">
								<h3><?php the_title(); ?></h3>
								<div>
									<?php echo $text; ?>
									<div class="text-right readmore">
										<a href="<?php echo get_permalink($post->ID); ?>"><?php the_title();?> PROGRAM <span class="glyphicon glyphicon-play"></span></a>
									</div>
								</div>
							</div>
							<div class="col-sm-6 text-center">
								<div class="image" style="display: table-cell; vertical-align: middle;">
									<img src="<?php echo $image['url'];?>">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php 
				} 
			?>


			<?php 
				$index ++;
			endwhile; 
			?>
	</div>
	<div class="container">
		<div class="services-wrapper">
			<div class="row">
		        <div class="text-center">
		            <h2><span class="page-title">INDIVIDUAL SHOP SERVICES</span></h2>
		        </div>
		    </div>

			<div id="services">
				<?php
					$args = array('post_type' => 'service','post_count'=>0);
					$services = new WP_Query($args);
				?>
				

			    <div class="row">
					<?php 
					$index = 1;
					while ( $services->have_posts() ) : $services->the_post(); 
						$image = get_field('index_image', $post->ID);
						$text = get_field('index_text', $post->ID);
					?>
					<div class="col-sm-4">
						<div class="service">
							<div class="service-thumbnail">
								<?php the_post_thumbnail('gmg1', array('class'=>'img-responsive')); ?>
							</div>
							<h3 class="service-title">
								<?php the_title(); ?>
							</h3>
							<div class="service-excerpt"><?php the_excerpt(); ?></div>
							<div>
								<a href="<?php echo get_permalink($post->ID);?>">LEARN MORE &gt;</a>
							</div>
						</div>
					</div>
					<?php if($index%3==0): ?>
					<div class="clearfix visible-xs"></div>
					<?php endif;?>

					<?php 

					endwhile; 
					?>
			    </div>
			</div>
		</div>
	</div>




</div><!-- #content -->

<?php get_footer(); ?>
