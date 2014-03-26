<?php
// SHORCODE TO DISPLAY THE GRID

// [mediagrid] 
function mg_shortcode( $atts, $content = null ) {
	include_once(MG_DIR . '/functions.php');
	
	extract( shortcode_atts( array(
		'cat' => '',
		'filter' => 1,
		'r_width' => 'auto',
		'title_under' => 0,
		'overlay'	=> 'default'
	), $atts ) );

	if($cat == '') {return '';}
	
	// deeplinking class
	(get_option('mg_disable_dl')) ? $dl_class = '' : $dl_class = 'mg_deeplink'; 
	
	// init
	$grid = '';
	
	// filter
	if($filter == '1') {
		$filter_type = (get_option('mg_use_old_filters')) ? 'mg_old_filters' : 'mg_new_filters';
		$filter_code = mg_grid_terms_data($cat);
		
		$grid .= '<div id="mgf_'.$cat.'" class="mg_filter '.$filter_type.'">';
			if($filter_code) { $grid .= $filter_code; }
		$grid .= '</div>';
	}
	
	// title under - wrap class
	$tit_under_class = ($title_under == 1) ? 'mg_grid_title_under' : '';
	
	
	// image overlay code 
	$ol_man = new mg_overlay_manager($overlay, $title_under);
	
	
	$grid .= '
	<div class="mg_grid_wrap '.$dl_class.'">
      <div id="mg_grid_'.$cat.'" class="mg_container lcwp_loading '.$tit_under_class.' '.$ol_man->txt_vis_class.'" rel="'.$r_width.'" '.$ol_man->img_fx_attr.'>';
	
	/////////////////////////
	// grid contents
		
	$items_list = get_option('mg_grid_'.$cat.'_items');
	$items_w = get_option('mg_grid_'.$cat.'_items_width');
	$items_h = get_option('mg_grid_'.$cat.'_items_height');
	
	$a = 0;
	if(!is_array($items_list)) {return '';}
	foreach($items_list as $post_id) {
      	if(!$items_w) {
			$cell_width = get_post_meta($post_id, 'mg_width', true);
			$cell_height = get_post_meta($post_id, 'mg_height', true);
		}
		else {
			$cell_width = $items_w[$a];
			$cell_height = $items_h[$a];	
		}

		$main_type = get_post_meta($post_id, 'mg_main_type', true);
		$item_layout = get_post_meta($post_id, 'mg_layout', true);
		
		if($main_type != 'spacer') {
			// calculate the thumb img size
			(!get_option('mg_maxwidth')) ? $grid_max_w = 960 : $grid_max_w = (int)get_option('mg_maxwidth');
			$thb_w = ceil($grid_max_w * mg_size_to_perc($cell_width));
			$thb_h = ceil($grid_max_w * mg_size_to_perc($cell_height));
			
			// thumb url
			$img_id = get_post_thumbnail_id($post_id);
			(get_post_meta($post_id, 'mg_thumb_center', true)) ? $thumb_center = get_post_meta($post_id, 'mg_thumb_center', true) : $thumb_center = 'c'; 
			
			// create thumb
			if($cell_height != 'auto') {
				$thumb_url = mg_thumb_src($img_id, $thb_w, $thb_h, get_option('mg_thumb_q'), $thumb_center);
			} else {
				$thumb_url = mg_thumb_src($img_id, $thb_w, false, get_option('mg_thumb_q'), $thumb_center);
			}
			
			// item title
			$item_title = get_the_title($post_id);
			
			// image ALT attribute
			$img_alt = strip_tags( mg_sanitize_input($item_title) );
			
			// title under switch
			if($title_under == 1) {
				$img_ol = '<div class="overlays">' . $ol_man->get_img_ol($post_id) . '</div>';
				$txt_under = $ol_man->get_txt_under($post_id);
			} 
			else {
				$img_ol = '<div class="overlays">' . $ol_man->get_img_ol($post_id) . '</div>';
				$txt_under = '';
			}
			
			// image proportions for the "auto" height
			if($cell_height == 'auto') {
				$img_info = wp_get_attachment_image_src($img_id, 'full');
				$ratio_val = (float)$img_info[2] / (float)$img_info[1];
				$ratio = 'ratio="'.$ratio_val.'"';
			} else {
				$ratio = '';	
			}
		}

		
		////////////////////////////
		// simple image
		if($main_type == 'simple_img') {

			$grid .= '
			<div id="'.uniqid().'" class="mg_box col'.$cell_width.' row'.$cell_height.' mgi_'.$post_id.' '.mg_item_terms_classes($post_id).'" '.$ratio.'>	
				<div class="mg_shadow_div">
					<div class="img_wrap">';
						$grid .= '<div><img src="'.$thumb_url.'" class="thumb" alt="'.$img_alt.'" /></div>';
						
				$grid .= '		
					</div>
					'.$txt_under.'
				</div>	
			</div>';	
		}
		
		
		////////////////////////////
		// single image
		else if($main_type == 'single_img') {
			
			$grid .= '
			<div id="'.uniqid().'" class="mg_box mg_transitions col'.$cell_width.' row'.$cell_height.' mgi_'.$post_id.' mg_image mg_closed '.mg_item_terms_classes($post_id).'" rel="pid_'.$post_id.'" '.$ratio.'>	
				<div class="mg_shadow_div">
					<div class="img_wrap">
						<div>';
					
							$grid .= '<img src="'.$thumb_url.'" class="thumb" alt="'.$img_alt.'" />';
							$grid .= $img_ol;
						
				$grid .= '</div>	
					</div>
					'.$txt_under.'
				</div>	
			</div>';
		}
		
		
		////////////////////////////
		// image slider
		else if($main_type == 'img_gallery') {
			$slider_img = get_post_meta($post_id, 'mg_slider_img', true);
			
			$grid .= '
			<div id="'.uniqid().'" class="mg_box mg_transitions col'.$cell_width.' row'.$cell_height.' mgi_'.$post_id.' mg_gallery mg_closed '.mg_item_terms_classes($post_id).'" rel="pid_'.$post_id.'" '.$ratio.'>					
				<div class="mg_shadow_div">
					<div class="img_wrap">
						 <div>';
						 
							$grid .= '<img src="'.$thumb_url.'" class="thumb" alt="'.$img_alt.'" />';
							$grid .= $img_ol;
						
				$grid .= '</div>	
					</div>
					'.$txt_under.'
				</div>	
			</div>';
		}
		
		
		////////////////////////////
		// video
		else if($main_type == 'video') {
			$video_url = get_post_meta($post_id, 'mg_video_url', true);
			
			$grid .= '
			<div id="'.uniqid().'" class="mg_box mg_transitions col'.$cell_width.' row'.$cell_height.' mgi_'.$post_id.' mg_video mg_closed '.mg_item_terms_classes($post_id).'" rel="pid_'.$post_id.'" '.$ratio.'>				
				<div class="mg_shadow_div">
					<div class="img_wrap">
						<div>';
						
						$grid .= '<img src="'.$thumb_url.'" class="thumb" alt="'.$img_alt.'" />';
						$grid .= $img_ol;
	
				$grid .= '</div>	
					</div>
					'.$txt_under.'
				</div>	
			</div>';
		}
		
		
		////////////////////////////
		// audio
		else if($main_type == 'audio') {
			$tracklist = get_post_meta($post_id, 'mg_audio_tracks', true);
			
			$grid .= '
			<div id="'.uniqid().'" class="mg_box mg_transitions col'.$cell_width.' row'.$cell_height.' mgi_'.$post_id.' mg_audio mg_closed '.mg_item_terms_classes($post_id).'" rel="pid_'.$post_id.'" '.$ratio.'>	
				<div class="mg_shadow_div">
					<div class="img_wrap">
						<div>';
						
							$grid .= '<img src="'.$thumb_url.'" class="thumb" alt="'.$img_alt.'" />';
							$grid .= $img_ol;
						
				$grid .= '</div>	
					</div>
					'.$txt_under.'
				</div>	
			</div>';
		}
		
		
		////////////////////////////
		// link 
		else if($main_type == 'link') {
			$link_url = get_post_meta($post_id, 'mg_link_url', true);
			$link_target = get_post_meta($post_id, 'mg_link_target', true);
			$nofollow = (get_post_meta($post_id, 'mg_link_nofollow', true) == '1') ? 'rel="nofollow"' : '';
			
			$grid .= '
			<div id="'.uniqid().'" class="mg_box col'.$cell_width.' row'.$cell_height.' mgi_'.$post_id.' mg_link '.mg_item_terms_classes($post_id).'" '.$ratio.'>	
				<div class="mg_shadow_div">
					<div class="img_wrap">
						<div>';
		
							$grid .= '
							<a href="'.$link_url.'" target="_'.$link_target.'" '.$nofollow.' class="mg_link_elem">
								<img src="'.$thumb_url.'" class="thumb" alt="'.$img_alt.'" />
							  
							'. $img_ol;
						
					$grid .= '</a>
						</div>';
						
				$grid .= '	
					</div>
					'.$txt_under.'
				</div>	
			</div>';		
		}
		
		
		////////////////////////////
		// lightbox custom content
		else if($main_type == 'lb_text') {
			
			$grid .= '
			<div id="'.uniqid().'" class="mg_box mg_transitions col'.$cell_width.' row'.$cell_height.' mgi_'.$post_id.' mg_lb_text mg_closed '.mg_item_terms_classes($post_id).'" rel="pid_'.$post_id.'" '.$ratio.'>	
				<div class="mg_shadow_div">
					<div class="img_wrap">
						<div>';
					
							$grid .= '<img src="'.$thumb_url.'" class="thumb" alt="'.$img_alt.'" />';
							$grid .= $img_ol;
						
				$grid .= '</div>	
					</div>
					'.$txt_under.'
				</div>	
			</div>';	
		}
		
		
		
		////////////////////////////
		// spacer 
		else if($main_type == 'spacer') {
			$grid .= '
			<div id="'.uniqid().'" class="mg_box col'.$cell_width.' row'.$cell_height.' mg_spacer"></div>';		
		}
	
		$a++; // counter for the sizes
	}

	////////////////////////////////

	$grid .= '</div></div>';
	
	
	//////////////////////////////////////////////////
	// OVERLAY MANAGER ADD-ON
	if(defined('MGOM_URL')) {
		$grid .= '
		<script type="text/javascript">
		jQuery(document).ready(function($) { 
			if( eval("typeof mgom_hub == \'function\'") ) {
				mgom_hub('.$cat.');
			}
		});
		</script>
		';	
	}
	//////////////////////////////////////////////////
	
	
	// Ajax init
	if(get_option('mg_enable_ajax')) {
		$grid .= '
		<script type="text/javascript">
		jQuery(document).ready(function($) { 
			if( eval("typeof mg_ajax_init == \'function\'") ) {
				mg_ajax_init('.$cat.');
			}
		});
		</script>
		';
	}

	return $grid;
}
add_shortcode('mediagrid', 'mg_shortcode');

?>