<?php

/**
 * The template for displaying the footer.
 **/
?>
<footer id="footer">
	<div id="bottom-foot">
		<div class="container">
			<div class="row">
				<div class="col-sm-12 col-md-4 col-lg-4">
					<div id="links" class="text-center">
						<p>
						  <a href="<?php echo get_option('home'); ?>">
								<h2><img class="img-responsive" src="<?php bloginfo('template_directory') ?>/images/logo-dark.png" name="logo" id="logo" title="Logo" alt="Logo" border="0" /></h2>
							</a>
							
						</p>
						
						<p class="blackline"></p>
						
						<p>
							<strong>
								<a href="/about/about-us/">about</a>  +  
								<a href="/staff/">staff</a>  +  
								<a href="/about/careers/">careers</a>  +  
								<a href="/category/journal/">journal</a>
							</strong>
						</p>
						
						<p class="blackline"></p>
						
						<p class="social">
							<a href="http://www.instagram.com/TeamGMG"><img src="<?php bloginfo('template_directory') ?>/images/link-instagram.png" /></a>&nbsp;
							<a href="http://www.facebook.com/TeamGMG"><img src="<?php bloginfo('template_directory') ?>/images/link-facebook.png" /></a>&nbsp;
							<a href="https://twitter.com/#!/gmgracing"><img src="<?php bloginfo('template_directory') ?>/images/link-twitter.png" /></a> &nbsp;
							<a href="http://www.flickr.com/photos/61989451@N08/"><img src="<?php bloginfo('template_directory') ?>/images/link-flickr.png" /></a>
						</p>
					</div>
				</div>
				
				<!-- Add the extra clearfix for only the required viewport -->
      			<div class="clearfix visible-xs visible-sm"></div>

				<div class="col-sm-6 col-md-4 col-lg-4">
					<div id="contact-us">
						<h2>Contact us</h2>
						<p class="address">
							<strong>Address:</strong>
							<span>3210 South Shannon Street<br />Santa Ana, California 92704</span>
						</p>
						<p>
							<strong>Phone:</strong>
							<span> +1 (714) 432 - 1582</span>
						</p>
						<p>
							<strong>Fax:</strong>
							<span> +1 (714) 432 - 1590</span>
						</p>
						<p>
							<strong>General inquires:</strong> <span><a href="mailto:info@gmgracing.com">info@gmgracing.com</a></span>
						</p>
						<p>
							<strong>Sales inquires:</strong> <span><a href="mailto:info@gmgracing.com">sales@gmgracing.com</a></span>
						</p>
						
						<p class="blackline"></p>
						
						<p><strong><a href="javascript:void(0)" id="link-map">location map</a>  +  terms &amp; conditions<br />privacy policy  +  order help</strong></p>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-4">
					<div id="newsletter">
						<h2>Newsletter</h2>
						<p><span>Be the first to receive updates about our<br />products, racing team, sales, and events.</span></p>
                          <!-- Begin MailChimp Signup Form -->
                          <div id="mc_embed_signup">
                              <form action="http://gmgracing.us3.list-manage1.com/subscribe/post?u=4ca73b52af44d89e425ecc62c&amp;id=a717203810" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate form-inline" target="_blank" novalidate>
                                  <div class="form-group">
                                      <input type="email" value="" name="EMAIL" class="newsletter-box" id="mce-EMAIL" placeholder="Enter your email here" required>
                                      <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                                      <div style="position: absolute; left: -5000px;"><input type="text" name="b_4ca73b52af44d89e425ecc62c_a717203810" value=""></div>
                                  </div>
                                  <div class="form-group">
                                      <input type="image" src="<?php bloginfo('template_directory') ?>/images/btn-submit.png" name="subscribe" id="mc-embedded-subscribe">
                                  </div>
                              </form>
                          </div>
                          <!--End mc_embed_signup-->
					</div>
				</div>
			</div>
		</div>
		
	</div>
</footer>

<!-- Start Google Map -->
<div id="map"></div>
<!-- End -->

<!-- Start Google Map JavaScript -->
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
<?php //wp_footer(); ?>