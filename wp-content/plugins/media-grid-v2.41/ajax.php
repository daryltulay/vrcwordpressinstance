<?php

////////////////////////////////////////////////
////// ADD GRID TERM ///////////////////////////
////////////////////////////////////////////////

function mg_add_grid_term() {
	if(!isset($_POST['grid_name'])) {die('data is missing');}
	$name = $_POST['grid_name'];
	
	$resp = wp_insert_term( $name, 'mg_grids', array( 'slug'=>sanitize_title($name)) );
	
	if(is_array($resp)) {die('success');}
	else {
		$err_mes = $resp->errors['term_exists'][0];
		die($err_mes);
	}
}
add_action('wp_ajax_mg_add_grid', 'mg_add_grid_term');


////////////////////////////////////////////////
////// LOAD GRID LIST //////////////////////////
////////////////////////////////////////////////

function mg_grid_list() {
	if(!isset($_POST['grid_page']) || !filter_var($_POST['grid_page'], FILTER_VALIDATE_INT)) {$pag = 1;}
	
	$pag = (int)$_POST['grid_page'];
	$per_page = 10;
	
	// search
	$search = (isset($_POST['grid_src'])) ? $_POST['grid_src']: '';
	if($search && !empty($search)) {
		$src_string = '&search='.$search;
	} else {
		$src_string = '';	
	}
	
	// get all terms 
	$grids = get_terms( 'mg_grids', 'hide_empty=0'.$src_string );
	$total = count($grids);
	
	$tot_pag = ceil( $total / $per_page );
	
	
	if($pag > $tot_pag) {$pag = $tot_pag;}
	$offset = ($pag - 1) * $per_page;
	
	// get page terms
	$args =  array(
		'number' => $per_page,
		'offset' => $offset,
		'hide_empty' => 0
	);
	if($src_string != '') {
		$args['search'] = $search;	
	}
	$grids = get_terms( 'mg_grids', $args);

	// clean term array
	$clean_grids = array();
	
	foreach ( $grids as $grid ) {
		$clean_grids[] = array('id' => $grid->term_id, 'name' => $grid->name);
	}
	
	$to_return = array(
		'grids' => $clean_grids,
		'pag' => $pag, 
		'tot_pag' => $tot_pag
	);
    
	echo json_encode($to_return);
	die();
}
add_action('wp_ajax_mg_get_grids', 'mg_grid_list');


////////////////////////////////////////////////
////// DELETE GRID TERM ////////////////////////
////////////////////////////////////////////////

function mg_del_grid_term() {
	if(!isset($_POST['grid_id'])) {die('data is missing');}
	$id = addslashes($_POST['grid_id']);
	
	$resp = wp_delete_term( $id, 'mg_grids');

	if($resp == '1') {die('success');}
	else {die('error during the grid deletion');}
}
add_action('wp_ajax_mg_del_grid', 'mg_del_grid_term');


////////////////////////////////////////////////
////// DISPLAY GRID  BUILDER ///////////////////
////////////////////////////////////////////////

function mg_grid_builder() {
	require_once(MG_DIR . '/functions.php');
	$tt_path = MG_TT_URL;
	
	if(!isset($_POST['grid_id'])) {die('data is missing');}
	$grid_id = addslashes($_POST['grid_id']);

	// get grid items
	$grid_items = get_option('mg_grid_'.$grid_id.'_items');
	if(!is_array($grid_items)) {$grid_items = array();}

	// item categories list
	$item_cats = get_terms( 'mg_item_categories', 'hide_empty=0' );
	
	// grid items sizes (from v1.1)
	$items_width = get_option('mg_grid_'.$grid_id.'_items_width');
	$items_height = get_option('mg_grid_'.$grid_id.'_items_height');
	
	// cat and page selector
	?>
    <h2></h2>
    
    <div id="mg_grid_builder_cat" class="postbox">
      <h3 class="hndle"><?php _e('Add Grid Items', 'mg_ml') ?></h3>
      <div class="inside">
    
        <div class="lcwp_mainbox_meta">
          <table class="widefat lcwp_table lcwp_metabox_table">
            <tr>
              <td class="lcwp_label_td"><?php _e("Item Categories", 'mg_ml'); ?></td>
              <td class="lcwp_field_td">
                  <select data-placeholder="><?php _e('Select item categories', 'mg_ml') ?> .." name="mh_grid_cats" id="mh_grid_cats" class="chzn-select" tabindex="2" style="width: 400px;">
                  <option value="all"><?php _e('All', 'mg_ml') ?></option>
                    <?php 
                    foreach($item_cats as $cat) {
                        echo '<option value="'.$cat->term_id.'">'.$cat->name.'</option>';
                    }
                    ?>
                  </select>
              </td>     
              <td><span class="info"></span></td>
            </tr>
            
            <tr>
              <td class="lcwp_label_td"><?php _e("Select an Item", 'mg_ml'); ?></td>
              <td class="lcwp_field_td" id="terms_posts_list">
              	  <?php 
				  $post_list = mg_item_cat_posts('all'); 
				  
				  if(!$post_list) {echo '<span>'. __('No items found', 'mg_ml') .' ..</span>';}
				  else {echo $post_list['dd'];}
				  ?>
              </td>     
              <td>
                <?php if($post_list) echo $post_list['img']; ?>
              
                <div id="add_item_btn" <?php if(!$post_list) echo 'style="display: none;"'; ?>>
                  <input type="button" name="add_item" value="<?php _e('Add', 'mg_ml') ?>" class="button-secondary" />
                  <div style="width: 30px; padding-left: 7px; float: right;"></div>
                </div>
              </td>
            </tr>
          </table>  
        <div>  
      </div>
	</div>
    </div>
    </div>
    
    <div class="postbox">
      <h3 class="hndle"><?php _e('Grid Preview', 'mg_ml') ?></h3>
      <div class="inside">
      
		<div id="visual_builder_wrap">
        
		<ul id="mg_sortable">
          <?php
          $items_data = mg_grid_builder_items_data($grid_items);
          
          if($items_data) {
			$a = 0;  
            foreach($items_data as $item) {
			  if( get_post_status($item['id']) == 'publish' ) {
			  		
				  if(!$items_width) {	
					  $item_w = $item['width'];
					  $item_h = $item['height'];
				  }
				  else {
					  $item_w = $items_width[$a];
					  $item_h = $items_height[$a]; 
				  }	  
				  
				  // featured image
				  $img_id = get_post_thumbnail_id($item['id']); 
				  $sizes = mg_sizes();
				  
				  
				  // item thumb
				  if($item['type'] == 'spacer') {
					  $item_thumb = '<img src="'.MG_URL. '/img/spacer_icon.png" height="19" width="19" class="thumb" alt="" />';	
				  }
				  else {
					 $item_thumb = '<img src="'.mg_thumb_src($img_id, 19, 19).'" class="thumb" alt="" />'; 	
				  }	
				  	
				  echo '
				  <li class="mg_box col'.$item_w.' row'.$item_h.'" id="box_'.mt_rand().$item['id'].'" mg-width="'.$item_w.'" mg-height="'.$item_h.'">
					<input type="hidden" name="grid_items[]" value="'.$item['id'].'" />
					<div class="handler" id="boxlabel" name="'.$item['id'].'">
						<div class="del_item" title="'. __('remove item', 'mg_ml') .'"></div>
						<a href="'.get_admin_url().'post.php?post='.$item['id'].'&action=edit" class="edit_item" target="_blank" title="'. __('edit item', 'mg_ml') .'"></a>
						<h3>
							'.$item_thumb.'
							'.$item['title'].'
						</h3>
						<p style="padding-top: 6px;">'.item_slug_to_name($item['type']).'</p>
						<p>';
						
						// choose the width
						echo __('Width', 'mg_ml').' <select name="items_w[]" value="" class="select_w mg_items_sizes_dd">'; 
							
							foreach($sizes as $size) {
								($size == $item_w) ? $sel = 'selected="selected"' : $sel = '';
								($size == $item['width']) ? $orig = ' ('. __('original', 'mg_ml') .')' : $orig = '';
								
								echo '<option value="'.$size.'" '.$sel.' autofocus>'.str_replace('_', '/', $size).$orig.'</option>';	
							}
						
						echo '</select> <br/>  '. __('Height', 'mg_ml').' <select class="select_h mg_items_sizes_dd">';
							
							// add height == auto
							if($item['type'] != 'spacer') {
								$sizes[] = 'auto'; 
							}
							foreach($sizes as $size) {
								($size == $item_h) ? $sel = 'selected="selected"' : $sel = '';
								($size == $item['height']) ? $orig = ' ('. __('original', 'mg_ml') .')' : $orig = '';
								
								echo '<option value="'.$size.'" '.$sel.'>'.str_replace('_', '/', $size).$orig.'</option>';	
							}

				  echo '</select></p>
					</div>
				  </li>';
			  }
			  $a++;
            }
          }
		  else {echo '<p>'. __('No items in the grid', 'mg_ml') .' ..</p>';}
          ?>

       </ul>
       </div> 
         
	</div>
    </div>
    </div>
    
	<?php
	die();
}
add_action('wp_ajax_mg_grid_builder', 'mg_grid_builder');


////////////////////////////////////////////////
////// GET ITEM CATEGORIES POSTS ///////////////
////////////////////////////////////////////////

function mg_item_cat_posts($fnc_cat = false) {	
	require_once(MG_DIR . '/functions.php');
	$tt_path = MG_TT_URL;

	$cat = $fnc_cat;
	// if is not called directly
	if(!$cat) {
		if(!isset($_POST['item_cats'])) {die('data is missing');}
		$cat = $_POST['item_cats'];
	}

	$post_list = mg_get_cat_items($cat);	
	if(!$post_list) {return false;}
	
    $select = '
	<select data-placeholder="'. __('Select an item', 'mg_ml') .' .." name="mh_grid_item" id="mh_grid_item" class="chzn-select" tabindex="2" style="width: 400px;">';
	 
	 $a = 0;
	 foreach($post_list as $post) {
		($a == 0) ? $sel = '' : $sel = 'style="display: none;"'; 
		
		// create thumbs array 
	   if($post['type'] == 'spacer') {
			$thumbs[] = '<img src="'.MG_URL. '/img/spacer_icon.png" height="23" width="23" alt="'.$post['id'].'" '.$sel.' />';	
		}
		else {
		   $thumbs[] = '<img src="'.mg_thumb_src($post['img'], 23, 23).'" alt="'.$post['id'].'" '.$sel.' />'; 	
		}	
		 
		$select .= '<option value="'.$post['id'].'">
			'.$post['title'].' - '.item_slug_to_name($post['type']).'
		</option>'; 
		$a++;
	 }
	 
    $select .= '</select>';
	
	
	// preview thumb images
	if(isset($thumbs)) { $thumbs_block = '<div class="mg_dd_items_preview">' . implode('', $thumbs) . '</div>'; }
	else {$thumbs_block = '';}
	
	// what to return 
	$to_return = array(
		'dd' => $select,
		'img' => $thumbs_block
	);
	
	if(!$fnc_cat) {echo json_encode($to_return);}
	else {return $to_return;}
	
	die();
}
add_action('wp_ajax_mg_item_cat_posts', 'mg_item_cat_posts');


////////////////////////////////////////////////
////// ADD AN ITEM TO THE VISUAL BUILDER ///////
////////////////////////////////////////////////

function mg_add_item_to_builder() {	
	require_once(MG_DIR . '/functions.php');
	$tt_path = MG_TT_URL;
	
	if(!isset($_POST['item_id'])) {die('data is missing');}
	$item_id = addslashes($_POST['item_id']);
	
	$sizes = mg_sizes();
	
	$items_data = mg_grid_builder_items_data( array($item_id) );         
	foreach($items_data as $item) {
		$item_w = $item['width'];
		$item_h = $item['height'];
		
		// featured image
		$img_id = get_post_thumbnail_id($item_id); 
		
		// item thumb
		if($item['type'] == 'spacer') {
		   $item_thumb = '<img src="'.MG_URL. '/img/spacer_icon.png" height="19" width="19" class="thumb" alt="" />';	
		}
		else {
		   $item_thumb = '<img src="'.mg_thumb_src($img_id, 19, 19).'" class="thumb" alt="" />';	
		}	
		
		
		echo '
		<li class="mg_box col'.$item['width'].' row'.$item['height'].'" id="box_'.mt_rand().$item['id'].'" mg-width="'.$item_w.'" mg-height="'.$item_h.'">
		  <input type="hidden" name="grid_items[]" value="'.$item['id'].'" />
		  <div class="handler" id="boxlabel" name="'.$item['id'].'">
		  	  <div class="del_item"></div>
			  <a href="'.get_admin_url().'post.php?post='.$item['id'].'&action=edit" class="edit_item" target="_blank" title="'.__('edit item', 'mg_ml').'"></a>
			  <h3>
			    '.$item_thumb.'
			  	'.$item['title'].'
			  </h3>
			  <p style="padding-top: 6px;">'.item_slug_to_name($item['type']).'</p>
			  <p>';
						
			// choose the width
			echo __('Width', 'mg_ml').' <select name="items_w[]" value="" class="select_w mg_items_sizes_dd">'; 
				
				foreach($sizes as $size) {
					($size == $item_w) ? $sel = 'selected="selected"' : $sel = '';
					($size == $item_w) ? $orig = ' ('. __('original', 'mg_ml') .')' : $orig = '';
					
					echo '<option value="'.$size.'" '.$sel.'>'.str_replace('_', '/', $size).$orig.'</option>';	
				}
			
			echo '</select> <br/>  '. __('Height', 'mg_ml').' <select class="select_h mg_items_sizes_dd">';
				
				// add height == auto
				if($item['type'] != 'spacer') {
					$sizes[] = 'auto'; 
				}
				
				foreach($sizes as $size) {
					($size == $item_h) ? $sel = 'selected="selected"' : $sel = '';
					($size == $item_h) ? $orig = ' ('. __('original', 'mg_ml') .')' : $orig = '';
					
					echo '<option value="'.$size.'" '.$sel.'>'.str_replace('_', '/', $size).$orig.'</option>';	
				}
	
	  echo '</select></p>
		  </div>
		</li>';	
			
	}
	
	die();	
}
add_action('wp_ajax_mg_add_item_to_builder', 'mg_add_item_to_builder');



////////////////////////////////////////////////
////// SAVE THE GRID ITEMS /////////////////////
////////////////////////////////////////////////

function mg_save_grid() {	
	require_once(MG_DIR . '/functions.php');
	
	if(!isset($_POST['grid_id'])) {die('data is missing');}
	$grid_id = addslashes($_POST['grid_id']);
	
	if(!isset($_POST['items_list'])) {die('data is missing');}
	$items_list = $_POST['items_list'];
	
	if(!isset($_POST['items_width'])) {die('data is missing');}
	$items_width = $_POST['items_width'];
	
	if(!isset($_POST['items_height'])) {die('data is missing');}
	$items_height = $_POST['items_height'];
	
	
	// save the items
	$key = 'mg_grid_'.$grid_id.'_items';
	if(!get_option($key)) { add_option($key, '255', '', 'yes'); }
	update_option($key, $items_list);	
	
	
	// save the sizes
	$key = 'mg_grid_'.$grid_id.'_items_width';
	if(!get_option($key)) { add_option($key, '255', '', 'yes'); }
	update_option($key, $items_width);	
	
	$key = 'mg_grid_'.$grid_id.'_items_height';
	if(!get_option($key)) { add_option($key, '255', '', 'yes'); }
	update_option($key, $items_height);	
	
	
	// save the terms list for the posts
	$terms_array = array();
	foreach($items_list as $post_id) {
		$pid_terms = wp_get_post_terms($post_id, 'mg_item_categories', array("fields" => "ids"));
		foreach($pid_terms as $pid_term) { $terms_array[] = $pid_term; }	
	}
	$terms_array = array_unique($terms_array);
	
	if(!get_option('mg_grid_'.$grid_id.'_cats')) { add_option('mg_grid_'.$grid_id.'_cats', '255', '', 'yes'); }
	update_option('mg_grid_'.$grid_id.'_cats', $terms_array);	
							
	echo 'success';
	die();				
}
add_action('wp_ajax_mg_save_grid', 'mg_save_grid');


//////////////////////////////

////////////////////////////////////////////////
////// MEDIA IMAGE PICKER FOR SLIDERS //////////
////////////////////////////////////////////////

function mg_img_picker() {	
	require_once(MG_DIR . '/functions.php');
	$tt_path = MG_TT_URL; 
	
	if(!isset($_POST['page'])) {$page = 1;}
	else {$page = (int)addslashes($_POST['page']);}
	
	if(!isset($_POST['per_page'])) {$per_page = 15;}
	else {$per_page = (int)addslashes($_POST['per_page']);}
	
	$img_data = mg_library_images($page, $per_page);
	
	echo '<ul>';
	
	if($img_data['tot'] == 0) {echo '<p>No images found .. </p>';}
	else {
		foreach($img_data['img'] as $img) {
			echo '<li><img src="'.mg_thumb_src($img, 90, 90).'" id="'.$img.'" height="90" width="90" /></li>';
		}
	}
	
	echo '
	</ul>
	<br class="lcwp_clear" />
	<table cellspacing="0" cellpadding="5" border="0" width="100%">
		<tr>
			<td style="width: 35%;">';			
			if($page > 1)  {
				echo '<input type="button" class="mg_img_pick_back button-secondary" id="slp_'. ($page - 1) .'" name="mgslp_p" value="&laquo; '. __('Previous images', 'mg_ml') .'" />';
			}
			
		echo '</td><td style="width: 30%; text-align: center;">';
		
			if($img_data['tot'] > 0 && $img_data['tot_pag'] > 1) {
				echo '<em>'. __('page', 'mg_ml').' '.$img_data['pag'].' '. __('of', 'mg_ml') .' '.$img_data['tot_pag'].'</em> - <input type="text" size="2" name="mgslp_num" id="mg_img_pick_pp" value="'.$per_page.'" /> <em>'. __('images per page', 'mg_ml') .'</em>';	
			}
			else { echo '<input type="text" size="2" name="mgslp_num" id="mg_img_pick_pp" value="'.$per_page.'" /> <em>'. __('images per page', 'mg_ml') .'</em>';	}
			
		echo '</td><td style="width: 35%; text-align: right;">';
			if($img_data['more'] != false)  {
				echo '<input type="button" class="mg_img_pick_next button-secondary" id="slp_'. ($page + 1) .'" name="mgslp_n" value="'.__('Next images', 'mg_ml') .' &raquo;" />';
			}
		echo '</td>
		</tr>
	</table>
	';

	die();
}
add_action('wp_ajax_mg_img_picker', 'mg_img_picker');


///////////////////////////////////////////////////
////// MEDIA IMAGE PICKER - SELECTED RELOAD ///////
///////////////////////////////////////////////////
function mg_sel_img_reload() {	
	require_once(MG_DIR . '/functions.php');

	if(!isset($_POST['images'])) { $images = array();}
	else { $images = $_POST['images'];}
	
	// get the titles and recreate tracks
	$images = mg_existing_sel($images);
	$new_img = '';
	
	if(!$images) {$new_img = '<p>'. __('No images selected', 'mg_ml') .' .. </p>';}
	else {
		foreach($images as $img_id) {

			$new_img .= '
			<li>
				<input type="hidden" name="mg_slider_img[]" value="'.$img_id.'" />
				<img src="'.mg_thumb_src($img_id, 90, 90).'" />
				<span title="remove image"></span>
			</li>
			';	
		}
	}
	
	echo $new_img;
	die();
}
add_action('wp_ajax_mg_sel_img_reload', 'mg_sel_img_reload');


////////////////////////////////////////////////
////// MEDIA AUDIO PICKER  /////////////////////
////////////////////////////////////////////////

function mg_audio_picker() {	
	require_once(MG_DIR . '/functions.php');

	if(!isset($_POST['page'])) {$page = 1;}
	else {$page = (int)addslashes($_POST['page']);}
	
	if(!isset($_POST['per_page'])) {$per_page = 15;}
	else {$per_page = (int)addslashes($_POST['per_page']);}
	
	$audio_data = mg_library_audio($page, $per_page);
	
	echo '<ul>';
	
	if($audio_data['tot'] == 0) {echo '<p>'. __('No audio files found', 'mg_ml') .' .. </p>';}
	else {
		foreach($audio_data['tracks'] as $track) {
			echo '
			<li>
				<img src="'. MG_URL .'/img/audio_icon.png" id="'.$track['id'].'" />
				<p title="'.$track['title'].'">'.mg_excerpt($track['title'], 25).'</p>
			</li>';
		}
	}
	
	echo '
	</ul>
	<br class="lcwp_clear" />
	<table cellspacing="0" cellpadding="5" border="0" width="100%">
		<tr>
			<td style="width: 40%;">';			
			if($page > 1)  {
				echo '<input type="button" class="mg_audio_pick_back button-secondary" id="slp_'. ($page - 1) .'" name="mgslp_p" value="&laquo; '. __('Previous tracks', 'mg_ml') .'" />';
			}
			
		echo '</td><td style="width: 20%; text-align: center;">';
		
			if($audio_data['tot'] > 0 && $audio_data['tot_pag'] > 1) {
				echo '<em>page '.$audio_data['pag'].' '. __('of', 'mg_ml') .' '.$audio_data['tot_pag'].'</em> - <input type="text" size="2" name="mgslp_num" id="mg_audio_pick_pp" value="'.$per_page.'" /> <em>'. __('tracks per page', 'mg_ml') .'</em>';		
			}
			else { echo '<input type="text" size="2" name="mgslp_num" id="mg_audio_pick_pp" value="'.$per_page.'" /> <em>'. __('tracks per page', 'mg_ml') .'</em>'; }
			
		echo '</td><td style="width: 40%; text-align: right;">';
			if($audio_data['more'] != false)  {
				echo '<input type="button" class="mg_audio_pick_next button-secondary" id="slp_'. ($page + 1) .'" name="mgslp_n" value="'. __('Next tracks', 'mg_ml') .' &raquo;" />';
			}
		echo '</td>
		</tr>
	</table>
	';

	die();
}
add_action('wp_ajax_mg_audio_picker', 'mg_audio_picker');


///////////////////////////////////////////////////
////// MEDIA AUDIO PICKER - SELECTED RELOAD ///////
///////////////////////////////////////////////////
function mg_sel_audio_reload() {	
	require_once(MG_DIR . '/functions.php');
	
	if(!isset($_POST['tracks'])) { $tracks = array();}
	else { $tracks = $_POST['tracks'];}
	
	$tracks = mg_existing_sel($tracks);
	
	// get the titles and recreate tracks
	$new_tracks = '';
	if(!$tracks) {$new_tracks = '<p>'. __('No tracks selected', 'mg_ml') .' .. </p>';}
	else {
		foreach($tracks as $track_id) {
			$title = html_entity_decode(get_the_title($track_id), ENT_NOQUOTES, 'UTF-8');
			
			if($title) {
				$new_tracks .= '
				<li>
					<input type="hidden" name="mg_audio_tracks[]" value="'.$track_id.'" />
					<img src="'.MG_URL.'/img/audio_icon.png" />
					<span title="remove track"></span>
					<p>'.$title.'</p>
				</li>
				';
			}
		}
	}
	
	echo $new_tracks;
	die();
}
add_action('wp_ajax_mg_sel_audio_reload', 'mg_sel_audio_reload');


////////////////////////////////////////////////////////////

////////////////////////////////////////////////
////// SET PREDEFINED GRID STYLES //////////////
////////////////////////////////////////////////

function mg_set_predefined_style() {
	if(!isset($_POST['style'])) {die('data is missing');}
	$style = $_POST['style'];
	
	require_once(MG_DIR . '/functions.php');
	
	$style_data = mg_predefined_styles($style);
	
	// additive settings if is a fresh installation
	if(!get_option('mg_item_width')) {
		$style_data['mg_item_width'] = 70;
		$style_data['mg_item_maxwidth'] = 960;	
	}
	
	// set option values
	foreach($style_data as $opt => $val) {
		if($opt != 'preview') {
			if(!get_option($opt)) { add_option($opt, '255', '', 'yes'); }
			update_option($opt, $val);				
		}
	}
	
	if(!get_option('mg_inline_css')) {
		mg_create_frontend_css();
	}

	die();
}
add_action('wp_ajax_mg_set_predefined_style', 'mg_set_predefined_style');


////////////////////////////////////////////////
////// SYNC OPTIONS WITH WPML //////////////////
////////////////////////////////////////////////

function mg_options_wpml_sync() {
	if(!function_exists('icl_register_string')) {die('error');}
	
	require_once(MG_DIR . '/functions.php');
	$already_saved = get_option('mg_wpml_synced_opts');
	$to_save = array();
	
	foreach(mg_main_types() as $type => $name) {
		$type_opts = get_option('mg_'.$type.'_opt');
		$typename = ($type == 'img_gallery') ? 'Image Gallery' : ucfirst($type);
		
		if(is_array($type_opts) && count($type_opts) > 0) {
			foreach($type_opts as $opt) {
				$index = $typename.' Options - '.$opt;
				$to_save[$index] = $index;
				
				icl_register_string('Media Grid - Item Options', $index, $opt);	
				
				if(isset($already_saved[$index])) {unset($already_saved[$index]);}
			}
		}
	}
	
	if(is_array($already_saved) && count($already_saved) > 0) {
		foreach($already_saved as $opt) {
			icl_unregister_string('Media Grid - Item Options', $opt);	
		}
	}
	
	if(!get_option('mg_wpml_synced_opts')) { add_option('mg_wpml_synced_opts', '255', '', 'yes'); }
	update_option('mg_wpml_synced_opts', $to_save);	
	
	die('success');
}
add_action('wp_ajax_mg_options_wpml_sync', 'mg_options_wpml_sync');

?>