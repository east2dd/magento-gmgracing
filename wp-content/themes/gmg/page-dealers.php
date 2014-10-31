<?php
/*
* Template Name: Dealers
*/
get_header(); 
the_post();

$areas = get_terms( 'area', array( 'parent' => 0 ) );

?>

<style>
#map-canvas {
  height: 650px;
}

.labels {
 color: #fff;
 font-family: "Lucida Grande", "Arial", sans-serif;
 font-size: 18px;
 line-height: 30px;
 font-weight: bold;
 text-align: center;
 width: 39px;
 height: 39px;     
 white-space: nowrap;
}

.labels.label-authorized{
  background: url(<?php bloginfo("template_directory")?>/images/icon-logo-red.png);
}
.labels.label-platinum{
  background: url(<?php bloginfo("template_directory")?>/images/icon-logo-yellow.png);
}
.labels.label-premier{
  background: url(<?php bloginfo("template_directory")?>/images/icon-logo-blue.png);
}
</style>

<script>
  var locations = [];
</script>

<div id="dealers">
  <div class="featured"><div id="map-canvas"></div></div>
  <div class="container">
    <div class="stockist-types">
      <span><img src="<?php bloginfo("template_directory")?>/images/icon-logo-red.png"> &dash; Authorized GMG Installation Center</span>
      <span><img src="<?php bloginfo("template_directory")?>/images/icon-logo-yellow.png"> &dash; Platinum GMG Stockist</span>
      <span><img src="<?php bloginfo("template_directory")?>/images/icon-logo-blue.png"> &dash; Premier GMG Stockist</span>
    </div>
    <div class="row">
      <div class="col-sm-3">
        <h1 class="page-title">STOCKISTS</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-9">
        <div class="page-content">
          GMG Racing product stockists have been picked as an inspiring meeting place for car enthusiasts around the world. Located in key cities, inside you’ll find dealers stoked with the latest GMG Racing product, unwavering customer service skills, and hand picked, curated service centers that boast the highest standards on installation competency. Some of these stockists also screen live racing, host exclusive exibitions and events.
          The perfect home for the automotive enthusiast, GMG Stockists offer the ultimate GMG Racing experience outside of our own shop.
        </div>
      </div>
    </div>
  </div>
  <div class="container dealers">
      <?php
          foreach($areas as $area)
          {
                  $args = array(
                  'post_type'=> 'dealer',
                  'tax_query' => array(
                      array(
                          'taxonomy' => 'area',
                          'terms' => $area->term_id,
                          'field' => 'id'
                      )
                  ),
                  'orderby' => 'title',
                  'order' => 'ASC'
                  );
                  
                  query_posts( $args );

                  ?>
                  <h2 class="section-title"><?php echo $area->name;?></h2>
                  <div class="row">
                  
<?php
    while ( have_posts() ): 
      the_post();
      $location = get_field('address', $post->ID);
      $hours = get_field('hours', $post->ID);
      $stockist = get_field('stockist', $post->ID);
?>
<?php
  if($location):
?>
      <script>
        var location1 = new google.maps.LatLng(<?php echo $location['lat'];?>, <?php echo $location['lng'];?>);
        locations.push({location: location1, stockist: '<?php echo $stockist;?>', post: '<?php echo $post->ID?>', shop: '<?php the_title();?>'});
      </script>
<?php 
  endif; 
?>

  <div class="row dealer" id="dealer-<?php echo $post->ID?>">
    <div class="col-sm-6">
        <div class="col-sm-6">
          <?php the_post_thumbnail('gmg1', array('class'=>'img-responsive')); ?>
        </div>
        <div class="col-sm-6">
          <h2 class="shop-title"><?php the_title();?>
            <img class="pull-right" style="width: 18px; height: 18px;" src="<?php bloginfo("template_directory")?>/images/icon-logo-<?php echo $stockist; ?>.png"/>
          </h2>
          <div class="shop-excerpt"><?php the_content();?></div>
        </div>
    </div>
  
    <div class="col-sm-6">
        <div class="col-sm-8">
          <h2>Contact</h2>
          <span>ADDRESS: <?php echo $location['address']; ?></span><br/>
          <span>TELE: <?php echo get_field('tele', $post->ID); ?></span><br/>
          <span>MAIL: <?php echo get_field('email', $post->ID); ?></span><br/>
          <span>WEB: <?php echo get_field('web', $post->ID); ?></span><br/>
        </div>
        <div class="col-sm-4">
          <h2>HOURS</h2>
          <div class="hours">
            <?php foreach($hours as $hour):?>
              <div><?php echo $hour['day']?>: <?php echo $hour['time']?></div>

            <?php endforeach;?>
          </div>
        </div>
    </div>
  </div>

<?php endwhile; ?>

                  </div>
                  <?php
                  rewind_posts();
          }
      ?>
      
  </div>
</div>

<script>
function initialize() {
  var mapOptions = {
    zoom: 6,
    center: locations[0]['location']
  }
  var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

  for(var i=0; i<locations.length; i++)
  {
    var marker = new MarkerWithLabel({
       position: locations[i]['location'],
       draggable: false,
       raiseOnDrag: true,
       title: locations[i]['shop'],
       map: map,
       icon: '/',
       labelContent: "",
       labelAnchor: new google.maps.Point(20, 20),
       labelClass: "labels label-" + locations[i]['stockist'] + " dealer-" + locations[i]['post'], // the CSS class for the label
       labelStyle: {},
       post: locations[i]['post']
     });

    google.maps.event.addListener(marker, "click", function (e) { 
      jQuery("html, body").animate({scrollTop: jQuery('#dealer-' + this.post).offset().top - 50 }, 1000);
    });

  }
}

google.maps.event.addDomListener(window, 'load', initialize);
</script>

<?php get_footer(); ?>
