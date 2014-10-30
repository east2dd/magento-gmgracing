<?php
/*
Template Name:Media Blog
*/
?>
<?php
get_header(); ?>
<script type="text/javascript">
				function callBackjs(){
						var html = "";
						$('.ngg-slideshow img').each(function(index) {
							$(this).attr('id', 'image_'+index);
							html += "<span id='box_"+index+"'></span>";
						});
						$('.ngg-slideshow').after('<div id="nav_box">'+html+'</div>');
				}
			 </script>
<div id="main-bod2">
<div id="blog-wrap">
		<?php
        	$u_agent = $_SERVER['HTTP_USER_AGENT'];
			$ub = '';
			if(preg_match('/MSIE/i',$u_agent))
			{
				echo '<div id="main-blog">';
			}else{
				echo '<div id="main-blog">';	
			}
		?>
        <div id="blog-slider">
        	<?php echo do_shortcode('[nggallery id=11 w=988 h=412 template=racing]'); ?>
        </div>
        
        <div id="page-title">Media</div>
		<?php get_sidebar('mediablog'); ?>
       
			<div id="content" role="main" style="color:#000;">
			<?php $loop = new WP_Query(array( 'post_type' => 'post', 'category_name' =>  'media', 'posts_per_page'=> '4', 'paged'=> get_query_var( 'page' ))); // exclude category 9
			while ( $loop->have_posts() ) : $loop->the_post(); ?>
            
				<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
					<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
                    <span class="post-detials">Posted In: <?php the_category(', '); ?>  By: <?php the_author_link();?>  When: <?php the_time('M j, Y') ?></span>
                    <div style="float:left"><?php the_post_thumbnail(array(210,210)); ?></div>
					 <?php the_excerpt(r); ?><span class="read_more"><a class="more" href="<?php the_permalink(); ?>">More</a></span>
                     <div class="clear"></div>
                    
				</div>
				
			<?php endwhile; ?>
            <center>
            <div id="pagin">
           <?php
		   $pageURL = 'http';
			if( isset($_SERVER["HTTPS"]) ) {
				if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
			}
			$pageURL .= "://";
			if ($_SERVER["SERVER_PORT"] != "80") {
				$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
			} else {
				$base_url = $pageURL;
				$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
				$base_url .= $_SERVER["SERVER_NAME"];
			}
			$found = strpos($pageURL, '?'); 
			if($found !== false) $pageURL = substr($pageURL, 0, $found); 
		   if(get_query_var( 'page' ) > 1){
				//echo 'Test'.$loop->found_posts;
				//echo $pageURL;
				echo '<a href="'.$pageURL.'">1</a> ';
				$num = 1;
				while(($num*4) < $loop->found_posts){
					$num++;
					if(get_query_var( 'page' ) != $num){
						echo '<a href="'.$pageURL.'?page='.$num.'" >'.$num.'</a> '; 
					}else{
						echo '<a href="'.$pageURL.'?page='.$num.'" class="current">'.$num.'</a> '; 
						$current = $num;
					}
					$last = $num;
				}
		   }else{
				echo '<a href="'.$pageURL.'" class="current">1</a> '; 
				$current = 1;
				$num = 1;
				while(($num*4) < $loop->found_posts){
					$num++;
					echo '<a href="'.$pageURL.'?page='.$num.'" >'.$num.'</a> '; 
				}
				$last  = $num;
		   }
		   if($current != $last){
		  	 echo '<a href="'.$pageURL.'?page='.($current+1).'">NEXT <img src="'.$base_url.'/wp-content/themes/gmg/img/next.png" border="0" height="14" style="margin-bottom:-3px;" /></a> ';
		   }
		   ?>
           </div>
           </center>
			<?php wp_reset_postdata(); // reset the query ?>
			 <?php if(function_exists('wp_paginate')) {
					wp_paginate();
				} 
				?>
			</div><!-- #content -->
		</div><!-- #container -->
        </div>
        </div>
<?php get_footer(); ?>
