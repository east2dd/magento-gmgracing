<?php 

$index = 0;
while ( have_posts() ) : the_post(); 
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