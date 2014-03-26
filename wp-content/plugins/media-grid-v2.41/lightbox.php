<?php
// ajax lightbox trigger
function mg_ajax_lightbox() {
	if(isset($_POST['mg_type']) && $_POST['mg_type'] == 'mg_overlay_layout') {
		require_once(MG_DIR . '/functions.php');
	
		if(!isset($_POST['pid']) || !filter_var($_POST['pid'], FILTER_VALIDATE_INT)) {die('data is missing');}
		$pid = addslashes($_POST['pid']);
		
		mg_lightbox($pid);
		die();
	}
}
add_action('init', 'mg_ajax_lightbox');



// lightbox code
function mg_lightbox($post_id) {
	require_once(MG_DIR . '/functions.php');
	
	// post type and layout
	$type = get_post_meta($post_id, 'mg_main_type', true);
	$layout = get_post_meta($post_id, 'mg_layout', true);
	$fc_max_w = (int)get_post_meta($post_id, 'mg_lb_max_w', true);
	$lb_max_w = (int)get_option('mg_item_maxwidth');
	$res_method = get_post_meta($post_id, 'mg_img_res_method', true);
	$touchswipe = (get_option('mg_lb_touchswipe')) ? 'mg_touchswipe' : '';

	// canvas color for TT
	(get_option('mg_item_bg_color')) ? $tt_canvas = substr(get_option('mg_item_bg_color'), 1) : $tt_canvas = 'ffffff';
	
	// Thumb res method fix for old plugin versions
	if(!$res_method) {$res_method == 1;}
	
	// maxwidth control
	if($lb_max_w == 0) {$lb_max_w = 960;}
	
	// Thumb width
	($layout == 'full') ? $tt_w = $lb_max_w : $tt_w = ($lb_max_w * 0.675);
	
	// Thumb center
	(get_post_meta($post_id, 'mg_thumb_center', true)) ? $tt_center = get_post_meta($post_id, 'mg_thumb_center', true) : $tt_center = 'c'; 
	
	// featured item max width
	if(!$fc_max_w || $fc_max_w < 280) {$fc_max_w = false;} 
	
	// custom opt
	$cust_opt = mg_item_copts_array($type, $post_id); 
	
	// item featured image
	$fi_img_id = get_post_thumbnail_id($post_id);
	$fi_src = wp_get_attachment_image_src($fi_img_id, 'medium');
	
	///////////////////////////
	// types
	
	if($type == 'single_img') {
		$img_id = get_post_thumbnail_id($post_id);
		$max_h = (int)get_post_meta($post_id, 'mg_img_maxheight', true);
		$src = wp_get_attachment_image_src($img_id, 'full');
		
		if($max_h > 0 && $src[2] > $max_h) {
			$img_url = mg_thumb_src($img_id, $tt_w, $max_h, $quality = 95, $tt_center, $res_method, $tt_canvas);
		}
		else {$img_url = $src[0];}

		$featured = '<img src="'.$img_url.'" alt="" />';
	}
	
	
	//////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////
	elseif($type == 'img_gallery') {
		$slider_img = get_post_meta($post_id, 'mg_slider_img', true);
		$style = get_option('mg_slider_style', 'light');
		$unique_id = uniqid();
		$autoplay = (get_post_meta($post_id, 'mg_slider_autoplay', true)) ? 'true' : 'false';
		
		// slider height
		$def_h_val = get_option('mg_slider_main_w_val', 55);
		$def_h_type = get_option('mg_slider_main_w_type', '%');
		$h_val = get_post_meta($post_id, 'mg_slider_w_val', true);
		$h_type = get_post_meta($post_id, 'mg_slider_w_type', true);
		
		if(!$h_val) {$h_val =  $def_h_val;}
		if(!$h_type) {$h_type =  $def_h_type;}
		$height = $h_val.$h_type;
		
		// slider proportions parameter
		if(strpos($height, '%') !== false) {
			$val = (int)str_replace("%", "", $height) / 100;
			$proportions_param = 'asp-ratio="'.$val.'"';
			$proportions_class = "mg_galleria_responsive";
			$slider_h = '';
		} else {
			$proportions_param = '';	
			$proportions_class = "";
			$slider_h = 'height: '.$height.';';
		}
		
		// images management
		$crop = get_post_meta($post_id, 'mg_slider_crop', true);
		if(!$crop) {$crop = 'true';}
		
		// slider thumbs visibility
		$thumbs_visibility = get_post_meta($post_id, 'mg_slider_thumbs', true);
		$thumbs_class = ($thumbs_visibility == 'yes' || $thumbs_visibility == 'always') ? 'mg_galleria_slider_show_thumbs' : '';
		
		if($thumbs_visibility == 'always' || $thumbs_visibility == 'never') {
			$css_code = '.mg_galleria_slider_wrap .galleria-mg-toggle-thumb {display: none !important;}';	
		} else {
			$css_code = '';	
		}
		if(!$thumbs_visibility || $thumbs_visibility == 'no' || $thumbs_visibility == 'never') {
			$css_code .= '.mg_galleria_slider_wrap .galleria-thumbnails-container {opacity: 0; filter: alpha(opacity=0);}';	
		}
		
		
		$featured = '
		<style type="text/css">
		.mg_item_featured {background-image: none !important;}
		'.$css_code.'
		</style>
		
		<script type="text/javascript">
		mg_galleria_img_crop = "'.$crop.'";
		mg_galleria_autoplay = '.$autoplay.';
		</script>	
		
		<div id="'.$unique_id.'" 
			class="mg_galleria_slider_wrap mg_show_loader mg_galleria_slider_'.$style.' '.$thumbs_class.' '.$proportions_class.' mgs_'.$post_id.' noSwipe" 
			style="width: 100%; '.$slider_h.'" '.$proportions_param.'
		>
		';
		  
		  if(is_array($slider_img)) {
			  foreach($slider_img as $img_id) {
				  $src = wp_get_attachment_image_src($img_id, 'full');
				  $max_h = (int)get_post_meta($post_id, 'mg_img_maxheight', true);
				  $img_url = $src[0];
					
				  if(get_post_meta($post_id, 'mg_slider_captions', true) == 1) {
					 $img_data = get_post($img_id);
					 $caption = trim($img_data->post_content);
					 
					 ($caption == '') ? $caption_code = '' :  $caption_code = $caption;
				  }
				  else {$caption_code = '';}
					 
					 
				  $thumb = mg_thumb_src($img_id, 100, 69, $thumb_q = 90, 'c');			  
				  $featured .= '
				  <a href="'.$img_url.'"><img src="'.mg_sanitize_input($thumb).'" data-big="'.$img_url.'" data-title="" data-description="'.mg_sanitize_input($caption_code).'" /></a>';

			  }
		  }

		  $featured .= '<div style="clear: both;"></div>
		  </div>'; // slider wrap closing
		  
		  // slider init
		  $featured .= '<script type="text/javascript"> 
		  jQuery(document).ready(function($) { 
			  // Load the LCweb theme
			  Galleria.loadTheme("'.MG_URL.'/js/jquery.galleria/themes/lcweb/galleria.lcweb.min.js");
			  
			  if(eval("typeof mg_galleria_init == \'function\'")) { 
				  mg_galleria_show("#'.$unique_id.'");
				  mg_galleria_init("#'.$unique_id.'");
			  }
		  });
		  </script>';

		  //$featured .= '</div></div>'; 	
	}
		
		
	//////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////		
	elseif($type == 'video') {
		$src = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'full');
		$video_url = get_post_meta($post_id, 'mg_video_url', true);
		
		($layout == 'full') ? $w = 960 : $w = (960 * 0.675);
		$h = $w * 0.56;
		
		// featured image
		$img_id = get_post_thumbnail_id($post_id);
		
		if(lcwp_video_embed_url($video_url) == 'wrong_url') {
			if(get_post_meta($post_id, 'mg_video_use_poster', true) == 1) {
				$src = wp_get_attachment_image_src($img_id, 'full');
				$poster = $src[0];
			}
			else {
				$poster = false;
			}
			
			$autoplay = (get_option('mg_video_autoplay')) ? true : '';
			$featured = '<div id="mg_wp_video_wrap">' . 
				wp_video_shortcode(array(
					'src'      => $video_url,
					'poster'   => $poster,
					'loop'     => '',
					'autoplay' => $autoplay,
					'preload'  => 'metadata',
					'height'   => $h,
					'width'    => $w
				));
			
			$featured .= "
			</div>
			
			<link rel='stylesheet' id='mediaelement-css'  href='".includes_url()."js/mediaelement/mediaelementplayer.min.css?ver=2.13.0' type='text/css' media='all' />
			<link rel='stylesheet' id='wp-mediaelement-css'  href='".includes_url()."js/mediaelement/wp-mediaelement.css?ver=3.6' type='text/css' media='all' />
			
			<script type='text/javascript'>
			/* <![CDATA[ */
			var mejsL10n = {'language':'en-US','strings':{
				'Close':'Close',
				'Fullscreen': '". __('Fullscreen', 'mg_ml') ."',
				'Download File': '". __('Download File', 'mg_ml') ."',
				'Download Video': '". __('Download Video', 'mg_ml') ."',
				'Play\/Pause': '". __('Play\/Pause', 'mg_ml') ."',
				'Mute Toggle': '". __('Mute Toggle', 'mg_ml') ."',
				'None': '". __('None', 'mg_ml') ."',
				'Turn off Fullscreen': '". __('Turn off Fullscreen', 'mg_ml') ."',
				'Go Fullscreen': '". __('Go Fullscreen', 'mg_ml') ."',
				'Unmute': '". __('Unmute', 'mg_ml') ."',
				'Mute': '". __('Mute', 'mg_ml') ."',
				'Captions\/Subtitles': '". __('Captions\/Subtitles', 'mg_ml') ."'
			}};
			/* ]]> */
			</script>
			<script type='text/javascript' src='".includes_url()."js/mediaelement/mediaelement-and-player.min.js'></script>
			<script type='text/javascript'>
			/* <![CDATA[ */
			var _wpmejsSettings = {'pluginPath':'". str_replace('/', '\/', includes_url()) ."js\/mediaelement\/'};
			/* ]]> */
			</script>
			
			<script type='text/javascript' src='".includes_url()."js/mediaelement/wp-mediaelement.js'></script>
			";
		} else {
			$featured = '
			<iframe width="'.$w.'" height="'.$h.'" src="'.lcwp_video_embed_url($video_url).'" frameborder="0" allowfullscreen></iframe>
			';
		}
	}
	
	
	//////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////
	elseif($type == 'audio') {
		$img_id = get_post_thumbnail_id($post_id);
		$src = wp_get_attachment_image_src($img_id, 'full');
		$max_h = (int)get_post_meta($post_id, 'mg_img_maxheight', true);
		
		if($max_h > 0 && $src[2] > $max_h) {
			$img_url = mg_thumb_src($img_id, $tt_w, $max_h, $quality = 95, $tt_center, $res_method, $tt_canvas);
		}
		else {$img_url = $src[0];}
		
		$tracklist = get_post_meta($post_id, 'mg_audio_tracks', true);
		$tot = (is_array($tracklist)) ? count($tracklist) : 0;
		
		($tot == 1 || !get_option('mg_audio_tracklist')) ? $tl_class = 'jp_hide_tracklist' : $tl_class = 'jp_full_tracklist';
		
		$featured = '<img src="'.$img_url.'" alt="" />';
		
		$featured .= '
		<div id="mg_audio_player_'.$post_id.'" class="jp-jplayer"></div>
	
		<div id="mg_audio_wrap_'.$post_id.'" class="jp-audio noSwipe" style="display: none;">
			<div class="jp-type-playlist">
				<div class="jp-gui jp-interface">
					<div class="jp-cmd-wrap">';
					
						if($tot > 1) {$featured .= '<a href="javascript:;" class="jp-previous">previous</a>';}
					
						$featured .= '
						<a href="javascript:;" class="jp-play">play</a>
						<a href="javascript:;" class="jp-pause">pause</a>';
						
						if($tot > 1) {$featured .= '<a href="javascript:;" class="jp-next">next</a>';}

						$featured .= '
						<div class="jp-time-holder">
							<div class="jp-current-time"></div> 
							<span>/</span> 
							<div class="jp-duration"></div>
						</div>
						
						<div class="jp-progress">
							<div class="jp-seek-bar">
								<div class="jp-play-bar"></div>
							</div>
						</div>';	
						
						
						$featured .= '
						<div class="jp-volume-group">
							<a href="javascript:;" class="jp-mute" title="mute">mute</a>
							<a href="javascript:;" class="jp-unmute" title="unmute">unmute</a>
							
							<div class="jp-volume-bar">
								<div class="jp-volume-bar-value"></div>
							</div>
						</div>
					</div>';	

				$featured .= '
					<div class="jp-track-title">
						<div class="jp-playlist '.$tl_class.'">
							<ul>
								<li></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>';
		
		if(is_array($tracklist) && count($tracklist) > 0) {
			// js code
			$featured .= '
			<script type="text/javascript">
			jQuery(function(){
				mg_lb_jplayer = function() {
					new jPlayerPlaylist({
						jPlayer: "#mg_audio_player_'.$post_id.'",
						cssSelectorAncestor: "#mg_audio_wrap_'.$post_id.'",
						displayTime: 0,
					}, [';
					
					$a = 1;
					foreach($tracklist as $track) {
						$track_data = get_post($track);
						
						($tot > 1) ? $counter = '<em>'.$a.'/'.$tot.'</em>) ' : $counter = ''; 
						
						$track_json[] = '
						{
							title:"'.$counter.addslashes($track_data->post_title).'",
							mp3:"'.$track_data->guid.'"
						}
						';
						
						$a++;
					}
		
					$featured .= implode(',', $track_json) . '
					], {';
					
					// autoplay
					if(get_option('mg_audio_autoplay')) {	
						$featured .= '
						playlistOptions: {
							autoPlay: true
						},
						';
					}
					
					$featured .= '
						swfPath: "'.MG_URL.'/js/jPlayer/",
						supplied: "mp3"
					});
				}
			}); 
			</script>
			';
		}
	}
	
	else {
		$src = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'full');
		$featured = '';
	}
	
	
	// force the layout for the lightbox custom contents
	if($type == 'lb_text') {$layout = 'full';}
	
	
	///////////////////////////
	// builder	
	?>
    <div id="mg_close" class="noSwipe"></div>
    <div id="mg_nav" class="noSwipe"></div>
    
	<div class="mg_layout_<?php echo $layout; ?>">
      <div>
      	<?php if($type != 'lb_text') : ?>
		<div class="mg_item_featured" <?php if($fc_max_w) echo 'rel="'.$fc_max_w.'px"'; ?>>
			<?php echo $featured; ?>
		</div>
        <?php endif; ?>
		
		<div class="mg_item_content">
			<?php if($layout == 'full' && count($cust_opt) > 0) {echo '<div class="mg_content_left">';} ?>
		
				<h1 class="mg_item_title"><?php echo get_the_title($post_id); ?></h1>
				
				<?php 
				// custom options
				if(count($cust_opt) > 0) {
					echo '<ul class="mg_cust_options">';
					foreach($cust_opt as $copt => $val) {					
						echo '<li><span>'.mg_wpml_string($type, $copt).':</span> '.$val.'</li>';
					}
					echo '</ul>';
				} ?>

			<?php if($layout == 'full' && count($cust_opt) > 0) {echo '</div>';} ?>
			
			<div class="mg_item_text <?php if($layout == 'full' && count($cust_opt) == 0) {echo 'mg_widetext';} ?>">
				<?php echo do_shortcode( wpautop(get_post_field('post_content', $post_id)) ); ?>
            </div>
            
            <?php 
			// SOCIALS
			if(get_option('mg_facebook') || get_option('mg_twitter') || get_option('mg_pinterest')): 
			  $dl_part = (get_option('mg_disable_dl')) ? '' : '#mg_ld_'.$post_id; 
			?>
              <div id="mg_socials">
            	<ul>
                  <?php if(get_option('mg_facebook')): ?>
                  <li id="mg_fb_share">
					<a onClick="window.open('https://www.facebook.com/dialog/feed?app_id=425190344259188&display=popup&name=<?php echo urlencode(get_the_title($post_id)); ?>&description=<?php echo urlencode(strip_tags(strip_shortcodes(get_post_field('post_content', $post_id)))); ?>&nbsp;&picture=<?php echo urlencode($fi_src[0]); ?>&link=<?php echo urlencode(lcwp_curr_url().$dl_part); ?>&redirect_uri=http://www.lcweb.it/lcis_redirect.php','sharer','toolbar=0,status=0,width=548,height=325');"
                    
                    <?php if($touchswipe) : ?>
                    ontouchstart="window.open('https://www.facebook.com/dialog/feed?app_id=425190344259188&display=popup&name=<?php echo urlencode(get_the_title($post_id)); ?>&description=<?php echo urlencode(strip_tags(strip_shortcodes(get_post_field('post_content', $post_id)))); ?>&nbsp;&picture=<?php echo urlencode($fi_src[0]); ?>&link=<?php echo urlencode(lcwp_curr_url().$dl_part); ?>&redirect_uri=http://www.lcweb.it/lcis_redirect.php','sharer','toolbar=0,status=0,width=548,height=325');"
                    <?php endif; ?>
                    
                    href="javascript: void(0)"><span title="<?php _e('Share it!', 'mg_ml') ?>"></span></a>
                  </li>
                  <?php endif; ?>
                  
                  
                  <?php if(get_option('mg_twitter')): ?>
                  <li id="mg_tw_share">
					<a onClick="window.open('https://twitter.com/share?text=<?php echo urlencode('Check out "'.get_the_title($post_id).'" on '.get_bloginfo('name')); ?>&url=<?php echo urlencode(lcwp_curr_url().$dl_part); ?>','sharer','toolbar=0,status=0,width=548,height=325');" 
                    
                    <?php if($touchswipe) : ?>
                    ontouchstart="window.open('https://twitter.com/share?text=<?php echo urlencode('Check out "'.get_the_title($post_id).'" on '.get_bloginfo('name')); ?>&url=<?php echo urlencode(lcwp_curr_url().$dl_part); ?>','sharer','toolbar=0,status=0,width=548,height=325');"
                    <?php endif; ?>
					
                    href="javascript: void(0)"><span title="<?php _e('Tweet it!', 'mg_ml') ?>"></span></a>
                  </li>
                  <?php endif; ?>
                  
                  
                  <?php if(get_option('mg_pinterest')): ?>
                  <li id="mg_pn_share">
                  	<a onClick="window.open('http://pinterest.com/pin/create/button/?url=<?php echo urlencode(lcwp_curr_url().$dl_part); ?>&media=<?php echo urlencode($fi_src[0]); ?>&description=<?php echo urlencode(get_the_title($post_id)); ?>','sharer','toolbar=0,status=0,width=575,height=330');" 
                    
                    <?php if($touchswipe) : ?>
                    ontouchstart="window.open('http://pinterest.com/pin/create/button/?url=<?php echo urlencode(lcwp_curr_url().$dl_part); ?>&media=<?php echo urlencode($fi_src[0]); ?>&description=<?php echo urlencode(get_the_title($post_id)); ?>','sharer','toolbar=0,status=0,width=575,height=330');"
                    <?php endif; ?>
                    
                    href="javascript: void(0)"><span title="<?php _e('Pin it!', 'mg_ml') ?>"></span></a>
                  </li>
                  <?php endif; ?>
                  
                  
                  <?php if(get_option('mg_googleplus') && !get_option('mg_disable_dl')): ?>
                  <li id="mg_gp_share">
                  	<a onClick="window.open('https://plus.google.com/share?url=<?php echo urlencode(lcwp_curr_url().$dl_part); ?>','sharer','toolbar=0,status=0,width=490,height=360');" 
                    
                    <?php if($touchswipe) : ?>
                    ontouchstart="window.open('https://plus.google.com/share?url=<?php echo urlencode(lcwp_curr_url().$dl_part); ?>','sharer','toolbar=0,status=0,width=490,height=360');"
                    <?php endif; ?>
                    
                    href="javascript: void(0)"><span title="<?php _e('Share it!', 'mg_ml') ?>"></span></a>
                  </li>
                  <?php endif; ?>
                </ul>
                
              </div>
            <?php endif; ?>
            
			<br style="clear: both;" />
		</div>
      </div>
	</div> 
	<?php
}
?>