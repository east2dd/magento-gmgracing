<?php
/**
 * Widget - Flickr Badge Widget
 * 
 * @package Flickr Badge Widget
 * @subpackage Classes
 * For another improvement, you can drop email to zourbuth@gmail.com or visit http://zourbuth.com
 *
**/
 
class Flickr_Badges_Widget extends WP_Widget {
	
	var $prefix; 
	var $textdomain;
	
	
	/**
	 * Set up the widget's unique name, ID, class, description, and other options
	 * @since 1.2.1
	**/			
	function __construct() {
	
		// Set default variable for the widget instances
		$this->prefix = 'zflickr';	
		$this->textdomain = 'flickr-badges-widget';	
		
		// Set up the widget control options
		$control_options = array(
			'width' => 444,
			'height' => 350,
			'id_base' => $this->prefix
		);
		
		// Add some informations to the widget
		$widget_options = array('classname' => 'widget_flickr', 'description' => __( '[+] Displays a Flickr photo stream from an ID', $this->textdomain ) );
		
		// Create the widget
		$this->WP_Widget($this->prefix, __('Flickr Badge', $this->textdomain), $widget_options, $control_options );
		
		// Load additional scripts and styles file to the widget admin area
		add_action( 'load-widgets.php', array(&$this, 'widget_admin') );
		add_action('wp_ajax_fes_load_utility', array(&$this, 'fes_load_utility') );
		
		// Load the widget stylesheet for the widgets screen.
		if ( is_active_widget(false, false, $this->id_base, true) && !is_admin() ) {			
			wp_enqueue_style( 'z-flickr', FLICKR_BADGES_WIDGET_URL . 'css/widget.css', false, 0.7, 'screen' );
			add_action( 'wp_head', array( &$this, 'print_script' ) );
		}
	}
	
	
	/**
	 * Push all script and style from the widget "Custom Style & Script" box.
	 * @since 1.2.1
	**/	
	function print_script() {
		$settings = $this->get_settings();
		foreach ( $settings as $key => $setting ){		
			if ( !empty( $setting['custom'] ) ) 
				echo $setting['custom'];
		}
	}	
	
	
	/**
	 * Push additional script and style files to the widget admin area
	 * @since 1.2.1
	**/		
	function widget_admin() {
		wp_enqueue_style( 'z-flickr-admin', FLICKR_BADGES_WIDGET_URL . 'css/dialog.css' );
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'z-flickr-admin', FLICKR_BADGES_WIDGET_URL . 'js/jquery.dialog.js' );
		wp_localize_script( 'z-flickr-admin', 'fes', array(
			'nonce'		=> wp_create_nonce( 'fes-nonce' ),  // generate a nonce for further checking below
			'action'	=> 'fes_load_utility'
		));		
	}
	
	
	
	
	/**
	 * Outputs another item
	 * @since 1.2.2
	 */
	function fes_load_utility() {
		// Check the nonce and if not isset the id, just die.
		$nonce = $_POST['nonce'];
		if ( ! wp_verify_nonce( $nonce, 'fes-nonce' ) )
			die();

		$ch = curl_init('http://marketplace.envato.com/api/edge/new-files-from-user:zourbuth,codecanyon.json');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($ch);
		curl_close($ch);
		$data = json_decode($data);
		
		$i = 0; $html = '';
		if( $data ) {
			$i = 0;
			foreach( $data->{'new-files-from-user'} as $key => $value ) {
				if( $i < 15 ) {
					$html .= '<a href="'.$value->url.'?ref=zourbuth"><img src="'.$value->thumbnail.'"></a>&nbsp;';
					$i++;
				}
			}
		}
		echo $html;
		exit;
	}

	
	/**
	 * Push the widget stylesheet widget.css into widget admin page
	 * @since 1.2.1
	**/		
	function widget( $args, $instance ) {
		extract( $args );

		// Set up the arguments for wp_list_categories().
		$cur_arg = array(
			'title'			=> $instance['title'],
			'type'			=> empty( $instance['type'] ) ? 'user' : $instance['type'],
			'flickr_id'		=> $instance['flickr_id'],
			'count'			=> (int) $instance['count'],
			'display'		=> empty( $instance['display'] ) ? 'latest' : $instance['display'],
			'size'			=> isset( $instance['size'] ) ? $instance['size'] : 's',
			'copyright'		=> ! empty( $instance['copyright'] ) ? true : false
		);
		
		extract( $cur_arg );
	
		// print the before widget
		echo $before_widget;
		
		if ( $title )
			echo $before_title . $title . $after_title;
	
		// Get the user direction, rtl or ltr
		if ( function_exists( 'is_rtl' ) )
			$dir = is_rtl() ? 'rtl' : 'ltr';

		// Wrap the widget
		if ( ! empty( $instance['intro_text'] ) )
			echo '<p>' . do_shortcode( $instance['intro_text'] ) . '</p>';

		echo "<div class='zframe-flickr-wrap-$dir'>";
	
		// If the widget have an ID, we can continue
		if ( ! empty( $instance['flickr_id'] ) )
			echo "<script type='text/javascript' src='http://www.flickr.com/badge_code_v2.gne?count=$count&amp;display=$display&amp;size=$size&amp;layout=x&amp;source=$type&amp;$type=$flickr_id'></script>";
		else
			echo '<p>' . __('Please provide an Flickr ID', $this->textdomain) . '</p>';
		
		echo '</div>';
		
		if ( ! empty( $instance['outro_text'] ) )
			echo '<p>' . do_shortcode( $instance['outro_text'] ) . '</p>';
		
		if ( $copyright )
			echo '<a href="http://zourbuth.com/archives/500/flickr-badges-widget-free-wordpress-plugin/">
				<span style="font-size: 11px;"><span style="color: #0063DC; font-weight: bold;">Flick</span><span style="color: #FF0084; font-weight: bold;">r</span> Badge Widget</span>
				</a>';
		
		// Print the after widget
		echo $after_widget;
	}

	

	/**
	 * Widget update functino
	 * @since 1.2.1
	**/		
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['type'] 			= strip_tags($new_instance['type']);
		$instance['flickr_id'] 		= strip_tags($new_instance['flickr_id']);
		$instance['count'] 			= (int) $new_instance['count'];
		$instance['display'] 		= strip_tags($new_instance['display']);
		$instance['size']			= strip_tags($new_instance['size']);
		$instance['title']			= strip_tags($new_instance['title']);
		$instance['copyright']		= ( isset( $new_instance['copyright'] ) ? 1 : 0 );
		$instance['tab']			= $new_instance['tab'];
		$instance['intro_text'] 	= $new_instance['intro_text'];
		$instance['outro_text']		= $new_instance['outro_text'];
		$instance['custom']			= $new_instance['custom'];
		
		return $instance;
	}

	

	/**
	 * Widget form function
	 * @since 1.2.1
	**/		
	function form( $instance ) {
		// Set up the default form values.
		$defaults = array(
			'title'			=> esc_attr__( 'Flickr Widget', $this->textdomain ),
			'type'			=> 'user',
			'flickr_id'		=> '', // 71865026@N00
			'count'			=> 9,
			'display'		=> 'display',
			'size'			=> 's',
			'copyright'		=> true,
			'tab'			=> array( 0 => true, 1 => false, 2 => false, 3 => false ),
			'intro_text'	=> '',
			'outro_text'	=> '',
			'custom'		=> ''
		);

		/* Merge the user-selected arguments with the defaults. */
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$types = array( 
			'user'  => esc_attr__( 'user', $this->textdomain ), 
			'group' => esc_attr__( 'group', $this->textdomain )
		);
		$sizes = array(
			's' => esc_attr__( 'Standard', $this->textdomain ), 
			't' => esc_attr__( 'Thumbnail', $this->textdomain ),
			'm' => esc_attr__( 'Medium', $this->textdomain )
		);
		$displays = array( 
			'latest' => esc_attr__( 'latest', $this->textdomain ),
			'random' => esc_attr__( 'random', $this->textdomain )
		);
		
		$tabs = array( 
			__( 'General', $this->textdomain ),  
			__( 'Customs', $this->textdomain ),
			__( 'Feeds', $this->textdomain ),
			__( 'Supports', $this->textdomain ) 
		);
				
		?>
		
		<div class="pluginName">Flickr Badges Widget<span class="pluginVersion"><?php echo FLICKR_BADGES_WIDGET_VERSION; ?></span></div>
		<script type="text/javascript">
			// Tabs function
			jQuery(document).ready(function($){
				// Tabs function
				$('ul.nav-tabs li').each(function(i) {
					$(this).bind("click", function(){
						var liIndex = $(this).index();
						var content = $(this).parent("ul").next().children("li").eq(liIndex);
						$(this).addClass('active').siblings("li").removeClass('active');
						$(content).show().addClass('active').siblings().hide().removeClass('active');
	
						$(this).parent("ul").find("input").val(0);
						$('input', this).val(1);
					});
				});
				
				// Widget background
				$("#fbw-<?php echo $this->id; ?>").closest(".widget-inside").addClass("ntotalWidgetBg");
			});
		</script>
		
		<div id="fbw-<?php echo $this->id ; ?>" class="totalControls tabbable tabs-left">
			<ul class="nav nav-tabs">
				<?php foreach ($tabs as $key => $tab ) : ?>
					<li class="fes-<?php echo $key; ?> <?php echo $instance['tab'][$key] ? 'active' : '' ; ?>"><?php echo $tab; ?><input type="hidden" name="<?php echo $this->get_field_name( 'tab' ); ?>[]" value="<?php echo $instance['tab'][$key]; ?>" /></li>
				<?php endforeach; ?>							
			</ul>
			
			<ul class="tab-content">
				<li class="tab-pane <?php if ( $instance['tab'][0] ) : ?>active<?php endif; ?>">
					<ul>
						<li>
							<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', $this->textdomain); ?></label>
							<span class="controlDesc"><?php _e( 'Give the widget title, or leave it empty for no title.', $this->textdomain ); ?></span>
							<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
						</li>
						<li>
							<label for="<?php echo $this->get_field_id('type'); ?>"><?php _e( 'Type', $this->textdomain ); ?></label>
							<span class="controlDesc"><?php _e( 'The type of images from user or group.', $this->textdomain ); ?></span>
							<select id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>">
								<?php foreach ( $types as $k => $v ) { ?>
									<option value="<?php echo esc_attr( $k ); ?>" <?php selected( $instance['type'], $k ); ?>><?php echo esc_html( $v ); ?></option>
								<?php } ?>
							</select>				
						</li>
						<li>
							<label for="<?php echo $this->get_field_id('flickr_id'); ?>"><?php _e('Flickr ID', $this->textdomain); ?></label>							
							<input id="<?php echo $this->get_field_id('flickr_id'); ?>" name="<?php echo $this->get_field_name('flickr_id'); ?>" type="text" value="<?php echo esc_attr( $instance['flickr_id'] ); ?>" />
							<span class="controlDesc"><?php _e( 'Put the flickr ID here, go to <a href="http://goo.gl/PM6rZ" target="_blank">Flickr NSID Lookup</a> if you don\'t know your ID. Example: 71865026@N00', $this->textdomain ); ?></span>
						</li>
						<li>
							<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Number', $this->textdomain); ?></label>
							<span class="controlDesc"><?php _e( 'Number of images shown from 1 to 10', $this->textdomain ); ?></span>
							<input class="column-last" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo esc_attr( $instance['count'] ); ?>" size="3" />
						</li>
						<li>
							<label for="<?php echo $this->get_field_id('display'); ?>"><?php _e('Display Method', $this->textdomain); ?></label>
							<span class="controlDesc"><?php _e( 'Get the image from recent or use random function.', $this->textdomain ); ?></span>
							<select id="<?php echo $this->get_field_id( 'display' ); ?>" name="<?php echo $this->get_field_name( 'display' ); ?>">
								<?php foreach ( $displays as $k => $v ) { ?>
									<option value="<?php echo esc_attr( $k ); ?>" <?php selected( $instance['display'], $k ); ?>><?php echo esc_html( $v ); ?></option>
								<?php } ?>
							</select>	
						</li>
						<li>
							<label for="<?php echo $this->get_field_id('sizes'); ?>"><?php _e( 'Sizes', $this->textdomain ); ?></label>
							<span class="controlDesc"><?php _e( 'Represents the size of the image', $this->textdomain ); ?></span>
							<select id="<?php echo $this->get_field_id( 'size' ); ?>" name="<?php echo $this->get_field_name( 'size' ); ?>">
								<?php foreach ( $sizes as $k => $v ) { ?>
									<option value="<?php echo $k; ?>" <?php selected( $instance['size'], $k ); ?>><?php echo $v; ?></option>
								<?php } ?>
							</select>				
						</li>
						<li>
							<label for="<?php echo $this->get_field_id( 'copyright' ); ?>">
							<input class="checkbox" type="checkbox" <?php checked( $instance['copyright'], true ); ?> id="<?php echo $this->get_field_id( 'copyright' ); ?>" name="<?php echo $this->get_field_name( 'copyright' ); ?>" /><?php _e( 'Show Copyright', $this->textdomain ); ?></label>
							<span class="controlDesc"><?php _e( 'Display the plugin name with link in the front end.', $this->textdomain ); ?></span>
						</li>							
					</ul>
				</li>

				<li class="tab-pane <?php if ( $instance['tab'][1] ) : ?>active<?php endif; ?>">
					<ul>
						<li>
							<label for="<?php echo $this->get_field_id('intro_text'); ?>"><?php _e( 'Intro Text', $this->textdomain ); ?></label>
							<span class="controlDesc"><?php _e( 'This option will display addtional text before the widget content and HTML supports.', $this->textdomain ); ?></span>
							<textarea name="<?php echo $this->get_field_name( 'intro_text' ); ?>" id="<?php echo $this->get_field_id( 'intro_text' ); ?>" rows="2" class="widefat"><?php echo esc_textarea($instance['intro_text']); ?></textarea>
						</li>
						<li>
							<label for="<?php echo $this->get_field_id('outro_text'); ?>"><?php _e( 'Outro Text', $this->textdomain ); ?></label>
							<span class="controlDesc"><?php _e( 'This option will display addtional text after widget and HTML supports.', $this->textdomain ); ?></span>
							<textarea name="<?php echo $this->get_field_name( 'outro_text' ); ?>" id="<?php echo $this->get_field_id( 'outro_text' ); ?>" rows="2" class="widefat"><?php echo esc_textarea($instance['outro_text']); ?></textarea>
							
						</li>				
						<li>
							<label for="<?php echo $this->get_field_id('custom'); ?>"><?php _e( 'Custom Script & Stylesheet', $this->textdomain ) ; ?></label>
							<span class="controlDesc"><?php _e( 'Use this box for additional widget CSS style of custom javascript. Current widget selector: ', $this->textdomain ); ?><?php echo '<tt>#' . $this->id . '</tt>'; ?></span>
							<textarea name="<?php echo $this->get_field_name( 'custom' ); ?>" id="<?php echo $this->get_field_id( 'custom' ); ?>" rows="5" class="widefat code"><?php echo htmlentities($instance['custom']); ?></textarea>
						</li>
					</ul>
				</li>
				<li class="tab-pane <?php if ( $instance['tab'][2] ) : ?>active<?php endif; ?>">
					<ul>
						<li>
							<h3><?php _e( 'Zourbuth Blog Feeds', $this->textdomain ) ; ?></h3>
							<?php wp_widget_rss_output( 'http://zourbuth.com/feed/', array( 'items' => 10 ) ); ?>
						</li>
					</ul>
				</li>
				<li class="tab-pane <?php if ( $instance['tab'][3] ) : ?>active<?php endif; ?>">
					<ul>
						<li>
							<p><strong>Our Premium Plugins</strong></p>
							<div class="fesem"></div>
						</li>
						<li>	
							<a href="http://feedburner.google.com/fb/a/mailverify?uri=zourbuth&amp;loc=en_US">Subscribe to zourbuth by Email</a><br />
							<?php _e( 'Like my work? Please consider to ', $this->textdomain ); ?><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=W6D3WAJTVKAFC" title="Donate"><?php _e( 'donate', $this->textdomain ); ?></a>.<br /><br />
							
							If you like this plugin, please <a href="http://wordpress.org/support/view/plugin-reviews/flickr-badges-widget">give a good rating</a>.<br /><br />
							
							<span style="font-size: 11px;"><a href="http://wordpress.org/extend/plugins/flickr-badges-widget/"><span style="color: #0063DC; font-weight: bold;">Flick</span><span style="color: #FF0084; font-weight: bold;">r</span> Badge Widget</a> &copy; Copyright <a href="http://zourbuth.com">Zourbuth</a> <?php echo date("Y"); ?></span>.
						</li>
					</ul>
				</li>

			</ul>
		</div>
		<script type='text/javascript'>
			jQuery(document).ready(function($){
				$(document).on("click", ".fes-3", function(){
					var c, t;
					t = $(this);
					c = t.parents(".totalControls").find(".fesem");
					
					if ( c.is(':empty')) {
						c.append("<span class='fes-loading total-loading'>Loading item...</span>");
						$.post( ajaxurl, { action: fes.action, nonce : fes.nonce }, function(data){
							$(".fes-loading").remove();
							c.append(data);			
						});
					}
				});	
			});
		</script>			
		<?php
	}
}