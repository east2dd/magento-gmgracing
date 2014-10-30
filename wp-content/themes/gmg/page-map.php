<?php
/*
* Template Name: Location Map
*/

get_header(); 
the_post();

?>
        
<div id="content" role="main">
	<!-- Start Google Map -->
    <div id="map"></div>
    <!-- End -->
    <!-- Start Google Map JavaScript -->
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
    <script type="text/javascript" src="<?php bloginfo('template_directory') ?>/js/gmaps.js"></script>
    <script type="text/javascript">
        var map;
        jQuery(document).ready(function(){
            // main directions
            map = new GMaps({
                el: '#map', lat: 33.703788, lng: -117.923513, zoom: 17, zoomControl : true, 
                zoomControlOpt: { style : 'SMALL', position: 'TOP_LEFT' }, panControl : false, scrollwheel: false
            });
            // add address markers
            map.addMarker({
                lat: 33.703788, lng: -117.923513, title: 'Global Motorsports Group',
                infoWindow: { content: '<p>3210 South Shannon Street Santa Ana, California 92704</p>' } 
            });
        });
    </script>
    <!-- End -->
	<div class="container">
        <div class="row">
            <div class="col-sm-3">
                <h2 class="page-title"><span><?php the_title(); ?></span></h2>
            </div>
        </div>
		<div class="page-content"><?php the_content(); ?></div>
	</div>

</div><!-- #content -->

<?php get_footer(); ?>
